<?php

use App\Models\User;
use App\Models\Factory;
use App\Models\UserFactory;
use App\Models\Component;
use App\Models\Drug;
use App\Models\LabProduction;
use App\Services\ActionService;

beforeEach(function () {
    // Set up a user, drug, component, and factory
    $this->user = User::factory()->create([
        'cash' => 200000,
    ]);

    $this->drug = Drug::factory()->create([
        'name' => 'Teste Drug',
        'price' => 75, // should require 2 * (75 * 0.12) = 18 components per unit of drug
    ]);

    $this->component = Component::factory()->create([
        'name' => 'Teste Component',
        'drug_id' => $this->drug->id,
    ]);

    $this->factory = Factory::factory()->create([
        'name' => 'Laboratório de Teste',
        'price' => 50000,
        'production' => 50, // Base capacity = 50 * 1000 = 50000 components
        'maintenance' => 1000,
        'is_lab' => true,
    ]);

    $this->user->factories()->attach($this->factory->id, [
        'level' => 1,
        'investment' => 50000,
    ]);

    $this->userFactory = UserFactory::where('user_id', $this->user->id)->first();
    $this->user->components()->syncWithoutDetaching([$this->component->id => ['amount' => 1000000]]);
    $this->actionService = new ActionService($this->user);
});

test('drug getComponentsPerUnit returns correct ratio based on price', function () {
    expect($this->drug->getComponentsPerUnit())->toBe(18);

    $cheapDrug = Drug::factory()->create(['price' => 12]);
    expect($cheapDrug->getComponentsPerUnit())->toBe(2);

    $expensiveDrug = Drug::factory()->create(['price' => 124]);
    expect($expensiveDrug->getComponentsPerUnit())->toBe(29);
});

test('cannot upgrade factory or laboratory past level 3', function () {
    $this->userFactory->level = 3;
    $this->userFactory->save();

    $this->expectException(\RuntimeException::class);
    $this->expectExceptionMessage("O nível máximo permitido para upgrade é 3.");

    $this->actionService->upgradeFactory($this->userFactory);
});

test('laboratory production calculates drug yield and removes correct components amount', function () {
    // Request to produce 100 units of drug.
    // 100 * 18 = 1,800 components required.
    $initialAmount = $this->component->getAmountForUser($this->user);

    $this->actionService->createLabProduction($this->userFactory, $this->component->id, 100);

    // Verify component inventory reduction
    expect($this->component->refresh()->getAmountForUser($this->user))->toBe($initialAmount - 1800);

    // Verify production record has drug yield of 100
    $production = LabProduction::where('user_factory_id', $this->userFactory->id)->first();
    expect($production)->not->toBeNull();
    expect($production->amount)->toBe(100);
});

test('laboratory capacity scale limits component processing quantity', function () {
    // Level 1 max capacity = 50,000. Try to produce 3,000 units of drug which requires 54,000 components.
    $this->expectException(\RuntimeException::class);
    $this->expectExceptionMessage("O laboratório nível 1 pode processar no máximo 50,000 componentes por fila. A produção de 3,000 unidades exige 54,000 componentes.");

    $this->actionService->createLabProduction($this->userFactory, $this->component->id, 3000);
});

test('simultaneous production queue count limit scales with level', function () {
    // Level 1: max 1 queue.
    $this->actionService->createLabProduction($this->userFactory, $this->component->id, 10);

    // Try starting 2nd production at Level 1
    try {
        $this->actionService->createLabProduction($this->userFactory, $this->component->id, 10);
        $this->fail("Should have failed to start second production queue at Level 1.");
    } catch (\RuntimeException $e) {
        expect($e->getMessage())->toContain("O laboratório nível 1 suporta no máximo 1 produção(ões) simultânea(s).");
    }

    // Upgrade level to 2: should allow 2nd queue but block 3rd
    $this->userFactory->level = 2;
    $this->userFactory->save();

    $this->actionService->createLabProduction($this->userFactory, $this->component->id, 10); // 2nd queue

    try {
        $this->actionService->createLabProduction($this->userFactory, $this->component->id, 10); // 3rd queue
        $this->fail("Should have failed to start third production queue at Level 2.");
    } catch (\RuntimeException $e) {
        expect($e->getMessage())->toContain("O laboratório nível 2 suporta no máximo 2 produção(ões) simultânea(s).");
    }
});

test('laboratory production calculates duration with a smoothed square-root formula', function () {
    // 100 units of drug -> requires 1,800 components.
    // scaledAmount = 1,800 / 1,000 = 1
    // total = 2 + floor(sqrt(1)) = 3 minutes
    $this->actionService->createLabProduction($this->userFactory, $this->component->id, 100);
    $production = LabProduction::where('user_factory_id', $this->userFactory->id)->first();

    $expectedDuration = 3;
    $diffInMinutes = (int) round(now()->diffInSeconds($production->ends_at) / 60);
    expect($diffInMinutes)->toBe($expectedDuration);
});
