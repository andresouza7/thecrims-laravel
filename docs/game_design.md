# Documentação de Design do Jogo: The Crims

Esta documentação lista todas as mecânicas, itens, regras de negócio e sistemas de progressão do jogo **The Crims Clone**, servindo como fonte única de verdade para balanceamento e desenvolvimento.

---

## 1. Mecânicas de Interação (Features)

O jogo possui as seguintes interfaces e interações disponíveis para o jogador:

1. **Dashboard (Home):**
   - Exibe o status geral do jogador (Energia, Vício, Respeito, Atributos de Força, Inteligência, Carisma, Tolerância).
   - Mostra o dia e a hora do jogo.
2. **Banco (Bank):**
   - Permite depositar e retirar dinheiro. Dinheiro no banco rende juros a cada dia do jogo e fica protegido de roubos/ataques.
3. **Vida Noturna (Nightlife):**
   - Dividida em dois estabelecimentos acessíveis: a **Boate** e a **Mansão das Putas**.
   - **A Boate:** Permite consumir estimulantes para recuperar 100% de Stamina. O custo é proporcional ao respeito e à stamina faltante. Aumenta o vício em +15% e consome 1 ticket. Permite desafiar outros jogadores para lutas de rua.
   - **Mansão das Putas:** Permite contratar acompanhante para recuperar 100% de Stamina. O custo é proporcional ao respeito do jogador. Consome 1 ticket. Há uma chance de 10% de contrair doenças e ser enviado para internação no Hospital por 2 minutos. Permite desafiar outros jogadores para lutas na área VIP.
4. **Assaltos (Robbery):**
   - O jogador gasta energia para cometer assaltos.
   - Cada assalto exige um certo nível de Poder de Assalto Solo (`single_robbery_power`).
   - Assaltos bem-sucedidos dão dinheiro, drogas e componentes. Assaltos mal-sucedidos enviam o jogador para a prisão.
5. **Prostitutas (Hooker Market):**
   - Compra de prostitutas que geram renda passiva recorrente.
   - O jogador pode coletar os lucros acumulados das prostitutas contratadas.
6. **Boca de Fumo (Drug Dealer):**
   - Compra e venda de drogas de forma dinâmica. Os preços variam de acordo com as mecânicas de mercado.
7. **Fábricas & Laboratório (Factories):**
   - Compra de estufas/refinarias para produção passiva de drogas e seus componentes específicos.
   - Gerenciamento de produções no Laboratório (`Lab`), utilizando componentes para refinar drogas complexas.
8. **Docas (Boat):**
   - Barcos chegam ao porto demandando uma droga específica por um preço muito superior ao de mercado.
   - Oportunidade perfeita para vender grandes stashes de drogas.
9. **Prisão (Jail):**
   - Quando o jogador falha em um assalto, fica preso por um tempo determinado.
   - É possível subornar os guardas (gastando dinheiro) ou aguardar o tempo expirar para ser solto.
10. **Hospital:**
    - Permite comprar energia (Stamina) instantaneamente ou fazer desintoxicação (detox) para zerar o vício de drogas.
11. **Mercado de Equipamentos (Market):**
    - Compra de armas (que aumentam o dano básico e poder de assalto/ataque) e armaduras (que aumentam a defesa básica).
12. **Inventário (Inventory):**
    - Visualização dos itens comprados, ativação/desativação de equipamentos e venda de armas/armaduras obsoletas pela metade do preço de compra.
13. **Especialização & Carreira (Career):**
    - Escolha de uma entre 6 carreiras no crime organizado.
    - Promoção de nível ao cumprir requisitos progressivos.
    - Distribuição de pontos de atributos livres (`available_stats`) recebidos ao completar níveis de carreira ou tarefas.
14. **O Beco (Street):**
    - Painel de tarefas divididas em categorias que guiam o jogador de forma linear ao longo do jogo.

---

## 2. Itens do Jogo (Catálogos Estáticos)

O jogador inicia sua jornada com **$1.000** em dinheiro vivo, permitindo que adquira itens mais básicos (como o soco inglês, jaqueta de couro e sua primeira prostituta) para começar. Os preços e a economia foram totalmente reajustados para serem proporcionais e realistas.

### A. Drogas (10 tipos)
Drogas variam de valor de forma perfeitamente linear, de $10 até $100 por unidade.
1. **Maconha** (Preço base: $10)
2. **Cerveja** (Preço base: $20)
3. **Anfetamina** (Preço base: $30)
4. **Ecstasy** (Preço base: $40)
5. **Metanfetamina** (Preço base: $50)
6. **LSD** (Preço base: $60)
7. **Cocaína** (Preço base: $70)
8. **Heroína** (Preço base: $80)
9. **Ópio** (Preço base: $90)
10. **Special K** (Preço base: $100)

### B. Fábricas & Componentes (10 tipos)
Cada droga tem sua respectiva fábrica produtora (com preço e custos proporcionais) e o componente de refino.
As fábricas iniciam em **$50.000** (para incentivar roubos e prostituição no começo da jornada) e escalam até **$1.000.000**.
1. **Estufa de Maconha** (Preço: $50.000 | Manutenção: $1.000/dia | Prod: 50 u/dia | Componente de Maconha)
2. **Cervejaria** (Preço: $100.000 | Manutenção: $2.005/dia | Prod: 45 u/dia | Malte e Lúpulo)
3. **Laboratório de Anfetamina** (Preço: $150.000 | Manutenção: $3.000/dia | Prod: 40 u/dia | Precursores de Anfetamina)
4. **Laboratório de Ecstasy** (Preço: $200.000 | Manutenção: $4.000/dia | Prod: 35 u/dia | Componente MDMA)
5. **Laboratório de Metanfetamina** (Preço: $300.000 | Manutenção: $6.000/dia | Prod: 30 u/dia | Efedrina Pura)
6. **Laboratório de LSD** (Preço: $400.000 | Manutenção: $8.000/dia | Prod: 25 u/dia | Componente de Ácido)
7. **Refinaria de Cocaína** (Preço: $500.000 | Manutenção: $10.000/dia | Prod: 20 u/dia | Pasta Base de Coca)
8. **Fábrica de Heroína** (Preço: $600.000 | Manutenção: $12.000/dia | Prod: 15 u/dia | Flor de Papoula)
9. **Fábrica de Ópio** (Preço: $800.000 | Manutenção: $16.000/dia | Prod: 10 u/dia | Extrato de Ópio)
10. **Laboratório de Special K** (Preço: $1.000.000 | Manutenção: $20.000/dia | Prod: 5 u/dia | Cetamina Concentrada)
- **Laboratório de Drogas (Lab, inicial):** Preço: $150.000 | Manutenção: $3.000/dia

### C. Prostitutas (10 tipos)
Prostitutas geram renda passiva proporcional com base no preço de compra (ROI de ~10 dias de jogo).
1. **Bete do Calçadão** (Preço: $50 | Renda: $5/dia)
2. **Samanta da Esquina** (Preço: $150 | Renda: $15/dia)
3. **Carol da Boate** (Preço: $500 | Renda: $50/dia)
4. **Jéssica do Motel** (Preço: $1.500 | Renda: $150/dia)
5. **Aline de Luxo** (Preço: $4.000 | Renda: $400/dia)
6. **Mônica do Privê** (Preço: $10.000 | Renda: $1.000/dia)
7. **Patrícia VIP** (Preço: $20.000 | Renda: $2.000/dia)
8. **Valéria Internacional** (Preço: $30.000 | Renda: $3.000/dia)
9. **Catarina de Elite** (Preço: $40.000 | Renda: $4.000/dia)
10. **Imperatriz da Noite** (Preço: $50.000 | Renda: $5.500/dia)

### D. Armas (10 tipos)
Aumentam o poder de assalto e ataque.
1. **Soco Inglês** (Preço: $100 | Dano: +5 | Multiplicador: 1.05)
2. **Taco de Beisebol** (Preço: $400 | Dano: +15 | Multiplicador: 1.10)
3. **Faca de Combate** (Preço: $1.500 | Dano: +35 | Multiplicador: 1.15)
4. **Pistola .38** (Preço: $5.000 | Dano: +80 | Multiplicador: 1.20)
5. **Pistola 9mm Glock** (Preço: $15.000 | Dano: +180 | Multiplicador: 1.25)
6. **Escopeta Calibre 12** (Preço: $40.000 | Dano: +400 | Multiplicador: 1.30)
7. **Submetralhadora Uzi** (Preço: $100.000 | Dano: +900 | Multiplicador: 1.40)
8. **Fuzil de Assalto M4A1** (Preço: $200.000 | Dano: +2.000 | Multiplicador: 1.50)
9. **Fuzil Sniper .50** (Preço: $350.000 | Dano: +5.000 | Multiplicador: 1.70)
10. **Lança-Mísseis RPG** (Preço: $500.000 | Dano: +12.000 | Multiplicador: 2.00)

### E. Armaduras (10 tipos)
Aumentam a tolerância e defesa contra prisões e ataques de outros jogadores.
1. **Jaqueta de Couro** (Preço: $50 | Defesa: +3 | Multiplicador: 1.03)
2. **Jaqueta de Couro Reforçada** (Preço: $200 | Defesa: +10 | Multiplicador: 1.06)
3. **Colete Tático Simples** (Preço: $1.000 | Defesa: +25 | Multiplicador: 1.10)
4. **Colete Kevlar Leve** (Preço: $4.000 | Defesa: +60 | Multiplicador: 1.15)
5. **Colete Kevlar Reforçado** (Preço: $12.000 | Defesa: +130 | Multiplicador: 1.20)
6. **Traje Tático SWAT** (Preço: $30.000 | Defesa: +300 | Multiplicador: 1.25)
7. **Colete de Placas de Cerâmica** (Preço: $80.000 | Defesa: +700 | Multiplicador: 1.35)
8. **Armadura Corporal Militar** (Preço: $150.000 | Defesa: +1.600 | Multiplicador: 1.45)
9. **Armadura Exoesquelética** (Preço: $250.000 | Defesa: +4.000 | Multiplicador: 1.60)
10. **Traje Nano-Protetor** (Preço: $400.000 | Defesa: +10.000 | Multiplicador: 1.90)

### F. Assaltos (12 tipos)
Lista de assaltos solo com nomes simplificados e escala direta de dificuldade.
1. **Mendigar** (Poder: 3 | Stamina: 10 | Grana: $50)
2. **Bater Carteira** (Poder: 10 | Stamina: 10 | Grana: $200)
3. **Roubar Padaria** (Poder: 40 | Stamina: 15 | Grana: $600)
4. **Assaltar Posto** (Poder: 150 | Stamina: 20 | Grana: $1.500)
5. **Roubar Carro** (Poder: 500 | Stamina: 25 | Grana: $4.500)
6. **Invadir Mansão** (Poder: 1.500 | Stamina: 30 | Grana: $12.000)
7. **Assaltar Joalheria** (Poder: 4.500 | Stamina: 35 | Grana: $35.000)
8. **Seqüestrar Político** (Poder: 12.000 | Stamina: 40 | Grana: $100.000)
9. **Assaltar Banco** (Poder: 30.000 | Stamina: 45 | Grana: $300.000)
10. **Roubar Cassino** (Poder: 80.000 | Stamina: 50 | Grana: $800.000)
11. **Invadir Base Militar** (Poder: 200.000 | Stamina: 60 | Grana: $2.500.000)
12. **Assaltar Reserva Federal** (Poder: 500.000 | Stamina: 70 | Grana: $8.000.000)

---

## 3. Regras de Negócio e Fórmulas

- **Poder de Assalto Solo (`single_robbery_power`):**
  $$Poder = (Inteligência \times 0.5 + Tolerância \times 0.25 + Carisma \times 0.1 + Força \times 0.15) \times 0.6 \times RobberySkill + DanoArma + DefesaArmadura$$
- **Respeito (`respect`):**
  $$Respeito = \frac{Inteligência + Força + Carisma + Tolerância}{8} + \frac{DinheiroTotal}{30.000}$$
- **Prisão (Jail):**
  - Jogador é enviado para a prisão ao falhar em assaltos (chances calculadas dinamicamente com base no poder exigido vs poder real).
  - Tempo de prisão: 2 minutos por padrão.
  - Suborno de fiança: R$ baseado em poder de assalto.
- **Hospital:**
  - Jogador vai para o hospital se perder toda a vida (vida < 0) em assaltos ou lutas.
  - Vício acumulado reduz a eficiência das drogas de boate. Apenas a desintoxicação (detox) no hospital limpa o vício.

---

## 4. Estrutura de Carreiras (6 Carreiras x 10 Níveis)

Cada carreira tem uma vantagem mecânica distinta (atributos focados e focos de requisitos) e desvantagens.

### As Carreiras:
1. **Empresário (Entrepreneur):**
   - **Vantagem:** Maior geração de lucro em fábricas. Recompensas de dinheiro e fábricas maiores.
   - **Foco de Requisitos:** Dinheiro, fábricas compradas, inteligência.
2. **Traficante (Dealer):**
   - **Vantagem:** Maior lucro na venda de drogas para o traficante ou boates.
   - **Foco de Requisitos:** Drogas vendidas (`drug_sold`), dinheiro, tolerância.
3. **Cafetão (Pimp):**
   - **Vantagem:** Maior lucro passivo de garotas.
   - **Foco de Requisitos:** Prostitutas totais (`hookers_count`), tipos específicos de garotas, carisma.
4. **Assassino (Hitman):**
   - **Vantagem:** Aumento expressivo em poder de combate básico.
   - **Foco de Requisitos:** Assassinatos de rivais (`kills_count`), armas ativas específicas, força.
5. **Ladrão (Thief):**
   - **Vantagem:** Menor custo de energia em assaltos e maior chance de sucesso.
   - **Foco de Requisitos:** Quantidade de roubos solo (`single_robbery_count`), respeito.
6. **Vigarista (Swindler):**
   - **Vantagem:** Ganho extra de respeito por dinheiro acumulado e atributos mistos equilibrados.
   - **Foco de Requisitos:** Atributos gerais equilibrados (`stats_total`), respeito total, dinheiro.

### Progressão dos 10 Níveis:
A progressão escala de forma exponencial a cada nível para dar o tom de "endgame" nos níveis superiores:
- **Nível 1 (Recruta):** Entrada livre. Sem requisitos ou recompensas.
- **Nível 2 (Iniciado):** Requisitos fáceis. Recompensa: Atributos básicos (+10) e dinheiro inicial ($1.000).
- **Nível 3 (Capanga):** Requisitos moderados. Recompensa: Atributos (+30), dinheiro ($3.000) e primeira droga/arma leve.
- **Nível 4 (Soldado):** Requisitos médios. Recompensa: Atributos (+100), dinheiro ($10.000) e componentes de refino.
- **Nível 5 (Tenente):** Requisitos exigentes. Recompensa: Atributos (+300), dinheiro ($35.000) e arma intermediária.
- **Nível 6 (Capitão):** Requisitos avançados. Recompensa: Atributos (+1.000), dinheiro ($100.000) e primeira fábrica leve.
- **Nível 7 (Subchefe):** Requisitos pesados. Recompensa: Atributos (+3.500), dinheiro ($350.000) e prostitutas VIP.
- **Nível 8 (Conselheiro):** Requisitos de elite. Recompensa: Atributos (+10.000), dinheiro ($1.200.000) e armas de ponta.
- **Nível 9 (Chefão):** Requisitos massivos. Recompensa: Atributos (+30.000), dinheiro ($4.500.000) e fábricas complexas.
- **Nível 10 (Padrinho/Lenda):** Requisitos lendários. Recompensa: Atributos (+100.000) e prestígio máximo ($15.000.000).

---

## 5. Sistema de Tarefas do Beco (O Beco)

As tarefas do beco servem como um tutorial e progressão linear de suporte para o jogador subir de carreira, fornecendo recursos fundamentais (atributos livres e componentes) de forma ordenada.

### Categorias de Tarefas (Progressão Linear):
1. **Primeiros Passos nas Ruas (Categoria 1):**
   - Ensina o jogador a cometer pequenos roubos, comprar a primeira arma simples e acumular um caixa inicial.
2. **Entrando no Negócio (Categoria 2):**
   - Foca em comprar as primeiras prostitutas e estufas de maconha para estabelecer renda e produção básicas.
3. **Expandindo os Contatos (Categoria 3):**
   - Exige venda de drogas básicas (Maconha/Cerveja/Anfetamina) e refino de componentes.
4. **Mão de Ferro (Categoria 4):**
   - Exige combate com outros jogadores, ativação de armas médias e conquista de respeito elevado.
5. **Império Consolidado (Categoria 5):**
   - Requisitos finais de altíssimo nível (assaltos lendários, milhões em banco, e produção de drogas complexas).
