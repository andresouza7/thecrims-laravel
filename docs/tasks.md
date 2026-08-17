# 📋 Tarefas Pendentes (Backlog)

Lista de tarefas, melhorias e futuras implementações para o **The Crims**.

---

## 🔒 Autenticação & Segurança

- [ ] **Proteger rotas do jogo com Middleware `auth`**
  - **Descrição**: Atualmente, para fins de desenvolvimento, o sistema utiliza um fallback para `User::first()` em `GameFacade.php`, `CheckUserStatus.php` e `CheckUserCareer.php`.
  - **Ação necessária**:
    1. Remover o fallback `?? User::first()` dos serviços e middlewares.
    2. Adicionar o middleware `->middleware('auth')` no grupo de rotas do jogo em `routes/web.php`.
    3. Garantir que usuários não autenticados sejam redirecionados para a tela de login (`/login`).

---

## 🎮 Funcionalidades do Jogo (Gameplay)

- [ ] **Refinar Sistema de Assaltos (Solo & Gangue)**
  - Implementar telas/componentes Livewire dedicados para assaltos individuais e em grupo com cálculo de poder.

- [ ] **Notificações em Tempo Real (Toasts)**
  - Adicionar suporte a notificações dinâmicas (ex: Alpine.js / Livewire Dispatch) para eventos do jogo e alertas de ataque/prisão.

- [ ] **Polimento de UI/UX**
  - Adicionar animações de feedback em botões de ação e barras de progresso ativas.
