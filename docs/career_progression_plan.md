# 🚀 Plano de Refatoração: Progressão de Carreira e GameParams Mórficos (`target`)

## 📌 Visão Geral
O objetivo deste plano é refatorar o mecanismo de progressão de carreira do jogo **The Crims**. A arquitetura atual possui `game_params` genéricos e não processa a verificação de requisitos nem a concessão de recompensas de nível.

Nesta refatoração:
1. Ajustaremos `game_params` para restringir o campo `type` estritamente a `requirement` ou `reward`, implementando o relacionamento polimórfico com o prefixo **`target`** (`target_type` e `target_id`).
2. Parametrizaremos requisitos e recompensas **genéricos** (ex: Dinheiro, Respeito, Total de Prostitutas) e **específicos via Target Morph** (ex: Drogas específicas vendidas/recebidas, Equipamentos recebidos, Tipos de Prostituta).
3. O **Nível 1** de qualquer carreira não terá requisitos nem recompensas (é o nível inicial).
4. Para avançar do **Nível N-1 para o Nível N**, o jogador precisará cumprir todos os requisitos do Nível N. Ao atingir o Nível N, todas as recompensas configuradas para esse nível serão concedidas automaticamente.
5. Os requisitos e recompensas serão **variados a cada nível**, e o campo `value` em `career_level_params` escalará **progressivamente** à medida que o jogador avança de nível (valores maiores tanto para requisitos quanto para recompensas).
6. Atualizaremos os **Seeders** para popular automaticamente esses parâmetros fictícios com progressão exponencial/logarítmica.

---

## 🛠️ Detalhamento das Alterações

### 1. Migrações e Banco de Dados (`game_params`)
- **Tabela `game_params`**:
  - Restringir `type` a `ENUM('requirement', 'reward')` ou validação estrita.
  - Adicionar colunas polimórficas `$table->nullableMorphs('target')` (`target_type` e `target_id`).
  - Quando `target_type` e `target_id` forem `NULL`, o parâmetro é **genérico** (ex: dinheiro, estatísticas gerais, contagem total de prostitutas).
  - Quando forem definidos, o parâmetro faz Morph para models como `App\Models\Drug`, `App\Models\Equipment`, `App\Models\Hooker`, `App\Models\Factory`, etc.

### 2. Refatoração dos Models Eloquent
- **`GameParam`**:
  - Definir `$fillable = ['name', 'type', 'target_type', 'target_id']`.
  - Criar o método polimórfico `public function target() { return $this->morphTo(); }`.
  - Criar scopes `scopeRequirements()` e `scopeRewards()`.
- **`CareerLevelParam`**:
  - Garantir o relacionamento com `GameParam` e helper para acessar o `target` via `$this->game_param->target`.
- **`CareerLevel`**:
  - Métodos `requirements()` e `rewards()` para recuperar facilmente os parâmetros associados filtrados por `type`.

### 3. Motor de Avaliação e Recompensas (`CareerService`)
Reformularemos a classe `App\Services\CareerService` para lidar com:

1. **Avaliação de Requisitos (`evaluateRequirement`)**:
   - **Genéricos**:
     - `cash`: verifica `$user->cash`.
     - `respect`: verifica `$user->respect`.
     - `hookers_count`: verifica a soma total de prostitutas do usuário.
     - `stats_total`: soma de força, tolerância, carisma e inteligência.
   - **Mórficos via `target` (Específicos)**:
     - `drug_sold` + `target (Drug)`: verifica a quantidade vendida daquela droga específica.
     - `drug_owned` + `target (Drug)`: verifica se o usuário possui a droga no inventário.
     - `hooker_type_owned` + `target (Hooker)`: quantidade de prostitutas daquele tipo específico.
     - `equipment_owned` + `target (Equipment)`: verifica se possui a arma/armadura.

2. **Concessão de Recompensas (`grantRewards`)**:
   - Quando o usuário sobe para o Nível N, a classe `CareerService` executa as recompensas:
     - **Genéricas**: incrementa `$user->cash`, `$user->respect`, ajusta atributos de vida ou pontos.
     - **Mórficas via `target`**:
       - `drug_received` + `target (Drug)`: adiciona a quantidade da droga na tabela `user_drugs`.
       - `equipment_received` + `target (Equipment)`: adiciona o equipamento em `user_equipments`.
       - `hooker_received` + `target (Hooker)`: adiciona prostitutas em `user_hookers`.

3. **Subida de Nível (`levelUp`)**:
   - Verifica se o jogador cumpriu 100% dos requisitos do próximo nível.
   - Caso positivo, incrementa o nível do usuário (`$user->career_level`), concede as recompensas do novo nível e dispara o evento `user-stats-updated`.

### 4. Reestruturação dos Seeders com Escalonamento Progressivo
Reconfiguraremos os seeders para popular os parâmetros variando a cada nível e aumentando progressivamente seus valores:

1. **`GameParamSeeder`**:
   - **Requisitos e Recompensas Genéricos**:
     - `cash` (Requisito / Recompensa)
     - `respect` (Requisito / Recompensa)
     - `hookers_count` (Requisito)
     - `stats_total` (Requisito)
   - **Requisitos e Recompensas Mórficos (`target`)**:
     - Para cada `Drug` cadastrado: criar `drug_sold` (Requirement) e `drug_received` (Reward).
     - Para cada `Equipment` cadastrado: criar `equipment_owned` (Requirement) e `equipment_received` (Reward).
     - Para cada `Hooker` cadastrada: criar `hooker_type_owned` (Requirement).

2. **`CareerLevelParamSeeder`**:
   - **Nível 1**: NENHUM parâmetro (0 requisitos, 0 recompensas).
   - **Níveis 2 a 5**:
     - Seleciona uma combinação **variada** de requisitos e recompensas para cada nível.
     - O valor (`value`) é calculado com um fator multiplicador progressivo baseado no número do nível:
       - **Nível 2**: Valores de entrada (ex: Requisito Dinheiro: $1.000 | Recompensa: $500).
       - **Nível 3**: Fator ~5x a 10x maior (ex: Requisito Dinheiro: $10.000 | Recompensa: $5.000).
       - **Nível 4**: Fator ~5x a 10x maior (ex: Requisito Dinheiro: $100.000 | Recompensa: $50.000).
       - **Nível 5**: Nível máximo / elite (ex: Requisito Dinheiro: $1.000.000 | Recompensa: $500.000).

### 5. Interface do Usuário (Livewire Component & Blade)
Atualizaremos a tela de Carreira (`App\Livewire\Game\Career\About` / `resources/views/livewire/game/career/about.blade.php`):
- Exibir a lista de **Requisitos** com barras de progresso percentuais (`[====  ] 50% - Ter $20.000 (Atual: $10.000)`).
- Exibir a lista de **Recompensas** que o jogador receberá ao alcançar o nível.
- Exibir o botão **"Promover de Nível"** habilitado apenas se todos os requisitos do próximo nível estiverem 100% concluídos.
