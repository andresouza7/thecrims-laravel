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
        // 1. Register new task-related GameParams
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

        // Fetch entities for dynamic parameter targets
        $maconha = Drug::where('name', 'Maconha')->first();
        $meta = Drug::where('name', 'Metanfetamina')->first();
        $cocaina = Drug::where('name', 'Cocaína')->first();
        $heroina = Drug::where('name', 'Heroína')->first();

        $compMaconha = Component::where('drug_id', $maconha?->id)->first();
        $compMeta = Component::where('drug_id', $meta?->id)->first();
        $compCocaina = Component::where('drug_id', $cocaina?->id)->first();
        $compHeroina = Component::where('drug_id', $heroina?->id)->first();

        $equipments = Equipment::all();
        $hookers = Hooker::all();

        // 2. Define task categories
        $categories = [
            [
                'name' => 'Iniciante no Crime',
                'description' => 'Ideal para quem está começando a sujar as mãos nas ruas. Estas tarefas básicas ajudarão você a se orientar no submundo e adquirir o respeito inicial necessário para progredir.',
                'tasks' => [
                    [
                        'name' => 'Pedinte das Sombras',
                        'description' => 'Cometa roubos simples e de baixo risco para iniciar sua jornada criminosa nas ruas.',
                        'requirements' => [
                            ['name' => 'single_robbery_count', 'value' => 5],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 500],
                            ['name' => 'available_stats', 'value' => 5],
                        ],
                    ],
                    [
                        'name' => 'Primeiro Troco',
                        'description' => 'Acumule uma pequena quantidade de dinheiro vivo e continue realizando pequenos assaltos solo.',
                        'requirements' => [
                            ['name' => 'cash', 'value' => 2000],
                            ['name' => 'single_robbery_count', 'value' => 10],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 1000],
                            ['name' => 'available_stats', 'value' => 10],
                            ['name' => 'component_received', 'target_type' => Component::class, 'target_id' => $compMaconha?->id, 'value' => 5],
                        ],
                    ],
                    [
                        'name' => 'Olhos Abertos',
                        'description' => 'Mostre que você está crescendo no submundo acumulando prestígio e respeito nas ruas.',
                        'requirements' => [
                            ['name' => 'respect', 'value' => 50],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 2000],
                            ['name' => 'available_stats', 'value' => 15],
                        ],
                    ],
                    [
                        'name' => 'Pequeno Vendedor',
                        'description' => 'Negocie e venda maconha em pequena escala para os viciados do quarteirão.',
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
                'name' => 'Comerciante das Sombras',
                'description' => 'O verdadeiro crime é financiado através do comércio de substâncias ilícitas. Gerencie suas vendas de drogas nas boates para enriquecer.',
                'tasks' => [
                    [
                        'name' => 'Química Básica',
                        'description' => 'Venda metanfetamina para atrair clientes de classe média e elevar seus lucros de comércio.',
                        'requirements' => [
                            ['name' => 'drug_sold', 'target_type' => Drug::class, 'target_id' => $meta?->id, 'value' => 30],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 8000],
                            ['name' => 'available_stats', 'value' => 30],
                            ['name' => 'component_received', 'target_type' => Component::class, 'target_id' => $compMeta?->id, 'value' => 10],
                        ],
                    ],
                    [
                        'name' => 'Capitalista do Tráfico',
                        'description' => 'Acumule uma reserva financeira significativa e garanta o abastecimento contínuo de maconha.',
                        'requirements' => [
                            ['name' => 'cash', 'value' => 20000],
                            ['name' => 'drug_sold', 'target_type' => Drug::class, 'target_id' => $maconha?->id, 'value' => 50],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 12000],
                            ['name' => 'available_stats', 'value' => 40],
                        ],
                    ],
                    [
                        'name' => 'Preparo de Qualidade',
                        'description' => 'Treine e aumente seu total acumulado de atributos para aguentar o ritmo frenético e as ameaças das ruas.',
                        'requirements' => [
                            ['name' => 'stats_total', 'value' => 100],
                        ],
                        'rewards' => [
                            ['name' => 'available_stats', 'value' => 50],
                            ['name' => 'component_received', 'target_type' => Component::class, 'target_id' => $compMeta?->id, 'value' => 15],
                        ],
                    ],
                    [
                        'name' => 'Grande Negócio',
                        'description' => 'Negocie e venda cocaína pura de alta qualidade para obter margens de lucro impressionantes.',
                        'requirements' => [
                            ['name' => 'drug_sold', 'target_type' => Drug::class, 'target_id' => $cocaina?->id, 'value' => 50],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 25000],
                            ['name' => 'available_stats', 'value' => 60],
                            ['name' => 'drug_received', 'target_type' => Drug::class, 'target_id' => $cocaina?->id, 'value' => 15],
                        ],
                    ],
                ]
            ],
            [
                'name' => 'Dono das Ruas',
                'description' => 'A noite pertence àqueles que controlam o prazer e cobram taxas pelo respeito. Expanda seu bordel e mostre quem gerencia os negócios de prostituição.',
                'tasks' => [
                    [
                        'name' => 'Agenciador Iniciante',
                        'description' => 'Contrate suas primeiras garotas de programa para trabalhar em seu território.',
                        'requirements' => [
                            ['name' => 'hookers_count', 'value' => 3],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 15000],
                            ['name' => 'available_stats', 'value' => 50],
                        ],
                    ],
                    [
                        'name' => 'Império do Prazer',
                        'description' => 'Recrute garotas específicas e receba garotas de nível superior para o seu time.',
                        'requirements' => [
                            ['name' => 'hooker_type_owned', 'target_type' => Hooker::class, 'target_id' => $hookers->first()?->id ?? 1, 'value' => 2],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 25000],
                            ['name' => 'available_stats', 'value' => 60],
                            ['name' => 'hooker_received', 'target_type' => Hooker::class, 'target_id' => $hookers->skip(1)->first()?->id ?? ($hookers->first()?->id ?? 1), 'value' => 1],
                        ],
                    ],
                    [
                        'name' => 'Respeito das Ruas',
                        'description' => 'Eleve substancialmente sua moral e prestígio no crime por meio do controle noturno.',
                        'requirements' => [
                            ['name' => 'respect', 'value' => 500],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 40000],
                            ['name' => 'available_stats', 'value' => 80],
                        ],
                    ],
                    [
                        'name' => 'Bordel Central',
                        'description' => 'Construa uma rede imensa de prostituição com várias garotas sob a sua asa.',
                        'requirements' => [
                            ['name' => 'hookers_count', 'value' => 10],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 60000],
                            ['name' => 'available_stats', 'value' => 100],
                            ['name' => 'component_received', 'target_type' => Component::class, 'target_id' => $compCocaina?->id, 'value' => 20],
                        ],
                    ],
                ]
            ],
            [
                'name' => 'Assassino de Aluguel',
                'description' => 'Contratos de sangue exigem precisão. Elimine seus rivais em combates armados e garanta que você está usando os melhores equipamentos ativos do seu inventário.',
                'tasks' => [
                    [
                        'name' => 'Primeiro Sangue',
                        'description' => 'Elimine seu primeiro rival na arena de batalha.',
                        'requirements' => [
                            ['name' => 'kills_count', 'value' => 1],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 30000],
                            ['name' => 'available_stats', 'value' => 80],
                        ],
                    ],
                    [
                        'name' => 'Preparado para Matar',
                        'description' => 'Tenha um equipamento ativo específico (como arma ou armadura) equipado para aumentar suas chances de sucesso.',
                        'requirements' => [
                            ['name' => 'equipment_active', 'target_type' => Equipment::class, 'target_id' => $equipments->first()?->id ?? 1, 'value' => 1],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 50000],
                            ['name' => 'available_stats', 'value' => 100],
                        ],
                    ],
                    [
                        'name' => 'Força Bruta',
                        'description' => 'Alcance um total considerável de força e inteligência para garantir a precisão de seus golpes.',
                        'requirements' => [
                            ['name' => 'stats_total', 'value' => 500],
                        ],
                        'rewards' => [
                            ['name' => 'available_stats', 'value' => 120],
                            ['name' => 'equipment_received', 'target_type' => Equipment::class, 'target_id' => $equipments->skip(1)->first()?->id ?? ($equipments->first()?->id ?? 1), 'value' => 1],
                        ],
                    ],
                    [
                        'name' => 'Matador Profissional',
                        'description' => 'Elimine múltiplos inimigos e torne-se temido nas ruas.',
                        'requirements' => [
                            ['name' => 'kills_count', 'value' => 4],
                            ['name' => 'respect', 'value' => 1500],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 100000],
                            ['name' => 'available_stats', 'value' => 150],
                        ],
                    ],
                ]
            ],
            [
                'name' => 'Lenda do Crime',
                'description' => 'O ápice da pirâmide criminosa. Apenas os maiores assaltantes e traficantes com respeito absoluto alcançarão estas tarefas finais.',
                'tasks' => [
                    [
                        'name' => 'Mestre dos Assaltos',
                        'description' => 'Conclua dezenas de assaltos solo com êxito.',
                        'requirements' => [
                            ['name' => 'single_robbery_count', 'value' => 50],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 100000],
                            ['name' => 'available_stats', 'value' => 150],
                        ],
                    ],
                    [
                        'name' => 'Distribuidor Lendário',
                        'description' => 'Venda e distribua heroína de altíssima qualidade nas ruas da cidade.',
                        'requirements' => [
                            ['name' => 'drug_sold', 'target_type' => Drug::class, 'target_id' => $heroina?->id, 'value' => 50],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 200000],
                            ['name' => 'available_stats', 'value' => 200],
                            ['name' => 'component_received', 'target_type' => Component::class, 'target_id' => $compHeroina?->id, 'value' => 30],
                        ],
                    ],
                    [
                        'name' => 'Poder Supremo',
                        'description' => 'Treine até que seu corpo e mente atinjam níveis extraordinários.',
                        'requirements' => [
                            ['name' => 'stats_total', 'value' => 2000],
                        ],
                        'rewards' => [
                            ['name' => 'available_stats', 'value' => 250],
                        ],
                    ],
                    [
                        'name' => 'Chefão Inquestionável',
                        'description' => 'Acumule uma fortuna em dinheiro e respeito máximo para consolidar seu reinado absoluto.',
                        'requirements' => [
                            ['name' => 'cash', 'value' => 1000000],
                            ['name' => 'respect', 'value' => 5000],
                        ],
                        'rewards' => [
                            ['name' => 'cash', 'value' => 500000],
                            ['name' => 'available_stats', 'value' => 300],
                        ],
                    ],
                ]
            ],
        ];

        // 3. Insert categories, tasks, and task parameters
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
