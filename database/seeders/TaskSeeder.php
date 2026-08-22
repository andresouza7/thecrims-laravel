<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\TaskParam;
use App\Models\GameParam;
use App\Models\Drug;
use App\Models\Component;
use App\Models\Equipment;
use App\Models\Hooker;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {


        // 2. Register task-related GameParams
        $gps = [
            ['name' => 'single_robbery_count', 'type' => 'requirement'],
            ['name' => 'kills_count', 'type' => 'requirement'],
            ['name' => 'available_stats', 'type' => 'reward'],
        ];

        foreach ($gps as $gp) {
            GameParam::firstOrCreate([
                'name' => $gp['name'],
                'type' => $gp['type'],
            ]);
        }

        // Register equipment_active requirement for all equipment
        Equipment::all()->each(function (Equipment $equipment) {
            GameParam::firstOrCreate([
                'name' => 'equipment_active',
                'type' => 'requirement',
                'target_type' => Equipment::class,
                'target_id' => $equipment->id,
            ]);
        });

        // Register component_received reward for all components
        Component::all()->each(function (Component $component) {
            GameParam::firstOrCreate([
                'name' => 'component_received',
                'type' => 'reward',
                'target_type' => Component::class,
                'target_id' => $component->id,
            ]);
        });

        // Register hooker_received reward for all hookers
        Hooker::all()->each(function (Hooker $hooker) {
            GameParam::firstOrCreate([
                'name' => 'hooker_received',
                'type' => 'reward',
                'target_type' => Hooker::class,
                'target_id' => $hooker->id,
            ]);
        });

        // Fetch entities for dynamic targets
        $maconha = Drug::where('name', 'Maconha')->first();
        $cerveja = Drug::where('name', 'Cerveja')->first();
        $anfetamina = Drug::where('name', 'Anfetamina')->first();
        $ecstasy = Drug::where('name', 'Ecstasy')->first();
        $cocaina = Drug::where('name', 'Cocaína')->first();
        $heroina = Drug::where('name', 'Heroína')->first();

        $compMaconha = Component::where('drug_id', $maconha?->id)->first();
        $compCerveja = Component::where('drug_id', $cerveja?->id)->first();
        $compAnfetamina = Component::where('drug_id', $anfetamina?->id)->first();
        $compEcstasy = Component::where('drug_id', $ecstasy?->id)->first();
        $compCocaina = Component::where('drug_id', $cocaina?->id)->first();
        $compHeroina = Component::where('drug_id', $heroina?->id)->first();

        $taco = Equipment::where('name', 'Taco de Beisebol')->first();
        $glock = Equipment::where('name', 'Pistola 9mm Glock')->first();

        // 3. Define the 5 task categories with linear progression
        $categories = [
            [
                'name' => 'Primeiros Passos nas Ruas',
                'description' => 'Aprenda o básico sobre cometer pequenos roubos, acumular respeito e negociar sua primeira droga.',
                'tasks' => [
                    [
                        'name' => 'Pedinte das Sombras',
                        'description' => 'Realize seus primeiros assaltos de rua para chamar atenção básica.',
                        'requirements' => [
                            ['name' => 'single_robbery_count', 'value' => 5],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 500],
                            ['name' => 'available_stats', 'value' => 5],
                        ],
                    ],
                    [
                        'name' => 'Dinheiro Fácil',
                        'description' => 'Acumule uma carteira inicial de $2.000 em dinheiro vivo para investimentos.',
                        'requirements' => [
                            ['name' => 'cash', 'value' => 2000],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 1000],
                            ['name' => 'available_stats', 'value' => 10],
                            ['name' => 'component_received', 'target_type' => Component::class, 'target_id' => $compMaconha?->id, 'value' => 5],
                        ],
                    ],
                    [
                        'name' => 'Respeitado do Bairro',
                        'description' => 'Alcance 50 de respeito para que os menores comecem a temer você.',
                        'requirements' => [
                            ['name' => 'respect', 'value' => 50],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 2000],
                            ['name' => 'available_stats', 'value' => 15],
                        ],
                    ],
                    [
                        'name' => 'Pequeno Distribuidor',
                        'description' => 'Venda pelo menos 15 unidades de maconha nas boates locais.',
                        'requirements' => [
                            ['name' => 'drug_sold', 'target_type' => Drug::class, 'target_id' => $maconha?->id, 'value' => 15],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 3000],
                            ['name' => 'available_stats', 'value' => 20],
                            ['name' => 'drug_received', 'target_type' => Drug::class, 'target_id' => $maconha?->id, 'value' => 10],
                        ],
                    ],
                ]
            ],
            [
                'name' => 'Entrando no Negócio',
                'description' => 'Comece a expandir seus horizontes contratando prostitutas e obtendo equipamentos mais confiáveis.',
                'tasks' => [
                    [
                        'name' => 'Agenciador Novato',
                        'description' => 'Hateie e contrate pelo menos 2 prostitutas para acumular renda passiva.',
                        'requirements' => [
                            ['name' => 'hookers_count', 'value' => 2],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 5000],
                            ['name' => 'available_stats', 'value' => 25],
                        ],
                    ],
                    [
                        'name' => 'Cerveja Gelada',
                        'description' => 'Distribua 30 garrafas de Cerveja na boate e comece a obter componentes de malte.',
                        'requirements' => [
                            ['name' => 'drug_sold', 'target_type' => Drug::class, 'target_id' => $cerveja?->id, 'value' => 30],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 8000],
                            ['name' => 'available_stats', 'value' => 30],
                            ['name' => 'component_received', 'target_type' => Component::class, 'target_id' => $compCerveja?->id, 'value' => 10],
                        ],
                    ],
                    [
                        'name' => 'Equipamento Próprio',
                        'description' => 'Compre e possua um Taco de Beisebol no seu inventário para se proteger.',
                        'requirements' => [
                            ['name' => 'equipment_owned', 'target_type' => Equipment::class, 'target_id' => $taco?->id, 'value' => 1],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 10000],
                            ['name' => 'available_stats', 'value' => 40],
                        ],
                    ],
                    [
                        'name' => 'Condicionamento Físico',
                        'description' => 'Melhore sua musculatura e mente acumulando um total de 150 pontos em atributos totais.',
                        'requirements' => [
                            ['name' => 'stats_total', 'value' => 150],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 15000],
                            ['name' => 'available_stats', 'value' => 50],
                        ],
                    ],
                ]
            ],
            [
                'name' => 'Expandindo os Contatos',
                'description' => 'Comércio de nível intermediário de drogas e estabelecimento de bordéis organizados nas ruas centrais.',
                'tasks' => [
                    [
                        'name' => 'Energético Químico',
                        'description' => 'Comercialize 50 unidades de Anfetamina para atrair novos viciados.',
                        'requirements' => [
                            ['name' => 'drug_sold', 'target_type' => Drug::class, 'target_id' => $anfetamina?->id, 'value' => 50],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 20000],
                            ['name' => 'available_stats', 'value' => 60],
                            ['name' => 'component_received', 'target_type' => Component::class, 'target_id' => $compAnfetamina?->id, 'value' => 15],
                        ],
                    ],
                    [
                        'name' => 'Bordel do Centro',
                        'description' => 'Tenha pelo menos 6 prostitutas contratadas em sua folha de pagamento.',
                        'requirements' => [
                            ['name' => 'hookers_count', 'value' => 6],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 30000],
                            ['name' => 'available_stats', 'value' => 70],
                        ],
                    ],
                    [
                        'name' => 'Reserva Segura',
                        'description' => 'Acumule $100.000 em mãos para demonstrar sua liquidez comercial.',
                        'requirements' => [
                            ['name' => 'cash', 'value' => 100000],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 50000],
                            ['name' => 'available_stats', 'value' => 80],
                        ],
                    ],
                    [
                        'name' => 'Veterano de Roubos',
                        'description' => 'Complete um total de 40 assaltos com sucesso nas ruas da cidade.',
                        'requirements' => [
                            ['name' => 'single_robbery_count', 'value' => 40],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 60000],
                            ['name' => 'available_stats', 'value' => 100],
                            ['name' => 'component_received', 'target_type' => Component::class, 'target_id' => $compEcstasy?->id, 'value' => 20],
                        ],
                    ],
                ]
            ],
            [
                'name' => 'Mão de Ferro',
                'description' => 'Combate ativo com gangues rivais e estabelecimento de armas de fogo de alto calibre.',
                'tasks' => [
                    [
                        'name' => 'Aviso de Sangue',
                        'description' => 'Mate 2 oponentes na arena para demonstrar que você não aceita desaforo.',
                        'requirements' => [
                            ['name' => 'kills_count', 'value' => 2],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 80000],
                            ['name' => 'available_stats', 'value' => 120],
                        ],
                    ],
                    [
                        'name' => 'Glock Equipada',
                        'description' => 'Mantenha a Pistola 9mm Glock ativa em seu inventário como sua arma principal.',
                        'requirements' => [
                            ['name' => 'equipment_active', 'target_type' => Equipment::class, 'target_id' => $glock?->id, 'value' => 1],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 120000],
                            ['name' => 'available_stats', 'value' => 150],
                        ],
                    ],
                    [
                        'name' => 'Influência Pesada',
                        'description' => 'Consolide sua influência alcançando 2.000 pontos de respeito total.',
                        'requirements' => [
                            ['name' => 'respect', 'value' => 2000],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 200000],
                            ['name' => 'available_stats', 'value' => 200],
                        ],
                    ],
                    [
                        'name' => 'Distribuição Fina',
                        'description' => 'Comercialize 100 gramas de cocaína pura nas boates para obter componentes químicos.',
                        'requirements' => [
                            ['name' => 'drug_sold', 'target_type' => Drug::class, 'target_id' => $cocaina?->id, 'value' => 100],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 300000],
                            ['name' => 'available_stats', 'value' => 250],
                            ['name' => 'component_received', 'target_type' => Component::class, 'target_id' => $compCocaina?->id, 'value' => 50],
                        ],
                    ],
                ]
            ],
            [
                'name' => 'Império Consolidado',
                'description' => 'O topo absoluto do submundo. Exige a conclusão de grandes golpes e impérios de fábricas.',
                'tasks' => [
                    [
                        'name' => 'Assaltante Profissional',
                        'description' => 'Conclua 150 assaltos solo com maestria absoluta.',
                        'requirements' => [
                            ['name' => 'single_robbery_count', 'value' => 150],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 500000],
                            ['name' => 'available_stats', 'value' => 300],
                        ],
                    ],
                    [
                        'name' => 'Tráfico de Elite',
                        'description' => 'Venda 150 unidades de Heroína para os viciados mais ricos da cidade.',
                        'requirements' => [
                            ['name' => 'drug_sold', 'target_type' => Drug::class, 'target_id' => $heroina?->id, 'value' => 150],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 1000000],
                            ['name' => 'available_stats', 'value' => 400],
                            ['name' => 'component_received', 'target_type' => Component::class, 'target_id' => $compHeroina?->id, 'value' => 100],
                        ],
                    ],
                    [
                        'name' => 'Matador de Aluguel',
                        'description' => 'Colete a recompensa por matar 10 oponentes em lutas mortais.',
                        'requirements' => [
                            ['name' => 'kills_count', 'value' => 10],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 2000000],
                            ['name' => 'available_stats', 'value' => 500],
                        ],
                    ],
                    [
                        'name' => 'Império Lendário',
                        'description' => 'Acumule $10.000.000 em dinheiro vivo para coroar sua lenda.',
                        'requirements' => [
                            ['name' => 'cash', 'value' => 10000000],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 5000000],
                            ['name' => 'available_stats', 'value' => 1000],
                        ],
                    ],
                ]
            ],
        ];

        // 4. Insert categories, tasks, and task parameters
        foreach ($categories as $catIndex => $catData) {
            $category = TaskCategory::create([
                'name' => $catData['name'],
                'description' => $catData['description'],
            ]);

            foreach ($catData['tasks'] as $taskIndex => $taskData) {
                $task = Task::create([
                    'task_category_id' => $category->id,
                    'name' => $taskData['name'],
                    'description' => $taskData['description'],
                    'order' => $taskIndex + 1,
                ]);

                // Insert requirements
                foreach ($taskData['requirements'] as $req) {
                    $targetType = $req['target_type'] ?? null;
                    $targetId = $req['target_id'] ?? null;

                    $gp = GameParam::where('name', $req['name'])
                        ->where('type', 'requirement')
                        ->where('target_type', $targetType)
                        ->where('target_id', $targetId)
                        ->first();

                    if ($gp) {
                        TaskParam::create([
                            'task_id' => $task->id,
                            'game_param_id' => $gp->id,
                            'value' => $req['value'],
                        ]);
                    }
                }

                // Insert rewards
                foreach ($taskData['rewards'] as $rew) {
                    $targetType = $rew['target_type'] ?? null;
                    $targetId = $rew['target_id'] ?? null;

                    $gp = GameParam::where('name', $rew['name'])
                        ->where('type', 'reward')
                        ->where('target_type', $targetType)
                        ->where('target_id', $targetId)
                        ->first();

                    if ($gp) {
                        TaskParam::create([
                            'task_id' => $task->id,
                            'game_param_id' => $gp->id,
                            'value' => $rew['value'],
                        ]);
                    }
                }
            }
        }
    }
}
