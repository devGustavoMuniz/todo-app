# 📋 Sistema de Gestão de Tarefas Colaborativa

Sistema moderno de gestão de tarefas construído com Laravel 12 + Vue 3 + Inertia.js, utilizando Laravel Sail para desenvolvimento containerizado.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Vue.js](https://img.shields.io/badge/Vue.js-3-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white)
![TypeScript](https://img.shields.io/badge/TypeScript-5-3178C6?style=for-the-badge&logo=typescript&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Sail-2496ED?style=for-the-badge&logo=docker&logoColor=white)

## 🚀 **Pré-requisito: Docker Apenas!**

**Você só precisa ter o Docker instalado.** Nada mais - nem PHP, nem Node.js, nem MySQL. Tudo roda em containers.

**Verificação rápida:**
```bash
docker --version
docker compose version
```
---

## ⚡ **Instalação Rápida - Do Zero**

```bash
# 1. Clone o projeto
git clone <url-do-seu-repositorio>
cd todo-app

# 2. Configure ambiente
cp .env.example .env

# 3. Bootstrap inicial (primeira vez apenas)
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs

# 4. Agora use Sail normalmente
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail npm install

# 5. Rode frontend (nova aba do terminal)
./vendor/bin/sail npm run dev

# ✅ Pronto! Acesse http://localhost
# Login: test@example.com / password
```

---

## ✨ Funcionalidades

- 🔐 **Autenticação Completa** - Sistema de login/registro com Laravel Breeze
- 📊 **Dashboard Interativo** - Estatísticas em tempo real e progresso visual
- ✅ **Gestão de Tarefas** - CRUD completo com filtros e busca avançada
- 🔍 **Busca e Filtros** - Pesquisa por título/descrição e filtro por status
- 📱 **Design Responsivo** - Interface mobile-first com shadcn/ui e Tailwind CSS
- 🌙 **Dark Mode** - Tema escuro com detecção automática de preferência do sistema
- 🚀 **SPA Experience** - Navegação fluida com Inertia.js e transições suaves
- 🔒 **Segurança** - Middleware de autorização e validação robusta
- 📈 **Estatísticas** - Métricas de produtividade e taxa de conclusão
- 🎨 **UX Moderna** - Componentes reutilizáveis, microinterações e animações

## 🛠️ Stack Tecnológica

### Backend
- **Laravel 12** - Framework PHP moderno
- **PHP 8.3** - Linguagem de programação
- **MySQL** - Banco de dados relacional
- **Redis** - Cache e sessões
- **Laravel Breeze** - Autenticação web (session-based)

### Frontend
- **Vue 3** - Framework JavaScript reativo
- **TypeScript** - Tipagem estática
- **Inertia.js** - Ponte Laravel + Vue SPA
- **Tailwind CSS** - Framework CSS utility-first
- **shadcn/ui** - Biblioteca de componentes
- **Zod** - Validação de esquemas

### Desenvolvimento
- **Laravel Sail** - Ambiente Docker simplificado
- **Vite** - Build tool e HMR
- **PHPUnit** - Testes automatizados
- **Pinia** - Gerenciamento de estado


### 🎯 **Acessar a Aplicação**

Uma vez que todos os passos foram concluídos:

- **🌐 Aplicação Web**: http://localhost
- **🗄️ Banco MySQL**: 
  - Host: localhost:3306
  - Usuário: sail
  - Senha: password
  - Database: todo_app

---

### 🔐 **Credenciais de Teste**

Se você executou o seeder (`sail artisan db:seed`), pode fazer login com:

- **📧 Email**: test@example.com  
- **🔒 Senha**: password

**Usuário já tem tarefas de exemplo criadas para testar todas as funcionalidades!**

---

## 📁 Estrutura do Projeto

```
todo-app/
├── app/
│   ├── Http/Controllers/          # Controllers da aplicação
│   │   ├── TaskController.php     # CRUD de tarefas
│   │   └── DashboardController.php # Dashboard com estatísticas
│   ├── Models/                    # Models Eloquent
│   │   ├── User.php              # Modelo de usuário
│   │   └── Task.php              # Modelo de tarefa
│   ├── Repositories/             # Repository Pattern
│   │   └── TaskRepository.php    # Repositório de tarefas
│   ├── Services/                 # Camada de serviços
│   │   └── TaskService.php       # Lógica de negócio
│   ├── Policies/                 # Políticas de autorização
│   │   └── TaskPolicy.php        # Autorização de tarefas
│   └── Http/Middleware/          # Middleware customizado
│       └── EnsureTaskOwnership.php # Validação de proprietário
├── resources/
│   ├── js/
│   │   ├── Components/           # Componentes Vue
│   │   │   └── ui/              # Componentes shadcn/ui
│   │   ├── Pages/               # Páginas Inertia.js
│   │   │   ├── Auth/            # Páginas de autenticação
│   │   │   ├── Tasks/           # Páginas de tarefas
│   │   │   └── Dashboard.vue    # Página principal
│   │   ├── types/               # Tipos TypeScript
│   │   └── schemas/             # Esquemas Zod
│   └── css/                     # Estilos globais
├── tests/                       # Testes automatizados
│   ├── Feature/                 # Testes de funcionalidade
│   └── Unit/                    # Testes unitários
├── database/                    # Migrations e seeds
└── docker-compose.yml           # Configuração Docker
```

## 🏗️ Arquitetura

### Repository Pattern
O projeto implementa o Repository Pattern para abstrair a camada de dados:

```php
// Interface
TaskRepositoryInterface -> TaskRepository -> TaskService -> Controller
```

### Autorização em Camadas
- **Middleware**: `EnsureTaskOwnership` valida proprietário
- **Policies**: `TaskPolicy` define permissões
- **Form Requests**: Validação de entrada

### Frontend Moderno
- **Componentes**: Arquitetura baseada em componentes reutilizáveis
- **TypeScript**: Tipagem forte em todo frontend
- **Inertia.js**: SPA sem API, dados passados via props

## 🧪 Testes

Execute a suíte de testes completa:

```bash
# Todos os testes
./vendor/bin/sail test
```

## 📊 Funcionalidades Detalhadas

### 🏠 Dashboard
- **📈 Estatísticas em Tempo Real**: Contadores visuais de tarefas (total, pendentes, em progresso, concluídas)
- **📊 Taxa de Conclusão**: Cálculo automático de produtividade do usuário
- **⚡ Navegação Rápida**: Links diretos para criar nova tarefa e filtrar por status
- **🎨 Design Responsivo**: Interface adaptável para desktop, tablet e mobile
- **🌙 Dark Mode**: Alternância automática/manual entre tema claro e escuro

### ✅ Gestão de Tarefas
- **📝 CRUD Completo**: 
  - ✨ Criar: Formulário com validação em tempo real
  - 👀 Visualizar: Página detalhada de cada tarefa
  - ✏️ Editar: Atualização inline ou página dedicada
  - 🗑️ Excluir: Confirmação com modal para segurança

- **🔄 Estados de Tarefa**:
  - 🟡 **Pendente**: Nova tarefa criada
  - 🔵 **Em Progresso**: Tarefa sendo trabalhada
  - 🟢 **Concluída**: Tarefa finalizada

- **🔍 Filtros e Busca Avançada**:
  - Busca por título e descrição em tempo real
  - Filtro por status com contador de resultados
  - URL persistente para compartilhar filtros

- **📄 Paginação Inteligente**: Navegação eficiente com lazy loading

### 🔐 Segurança e Autorização
- **🔑 Autenticação Completa**: Laravel Breeze com registro, login, logout
- **🛡️ Autorização Multinível**:
  - Laravel Policies para controle granular
  - Middleware custom para validação de proprietário
  - Isolamento total entre usuários

- **✅ Validação Dupla**:
  - Frontend: Zod schemas com feedback visual
  - Backend: Form Requests com mensagens em português

- **🔒 Proteções de Segurança**:
  - CSRF Protection automática
  - SQL Injection prevention via Eloquent
  - XSS Protection via sanitização

### 🎨 UX/UI Moderna
- **🧩 Componentes Reutilizáveis**: Biblioteca shadcn/ui com componentes customizados
- **🎭 Micro-interações**: Animações suaves em botões, cards e transições
- **📱 Mobile-First**: Interface otimizada para touch e gestos mobile
- **⚡ Performance**: Lazy loading, code splitting, otimização de assets
- **🎯 Acessibilidade**: Navegação por teclado, ARIA labels, contraste otimizado

### 🔧 Arquitetura Técnica
- **🏗️ Repository Pattern**: Abstração da camada de dados para testabilidade
- **⚙️ Service Layer**: Lógica de negócio centralizada e reutilizável
- **🧪 Testing Coverage**: Testes automatizados para funcionalidades críticas
- **📦 Docker Ready**: Ambiente de desenvolvimento e produção containerizado
- **🚀 CI/CD Ready**: Preparado para pipelines de integração contínua

## 🚀 Deploy

### Produção com Sail
```bash
# Build para produção
./vendor/bin/sail npm run build

# Otimizações Laravel
./vendor/bin/sail artisan config:cache
./vendor/bin/sail artisan route:cache
./vendor/bin/sail artisan view:cache

# Migre a produção
./vendor/bin/sail artisan migrate --force
```

### Variáveis de Ambiente
Configure no `.env` de produção:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seu-dominio.com

DB_CONNECTION=mysql
DB_HOST=seu-host
DB_DATABASE=sua_database
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

REDIS_HOST=seu-redis-host
```

### Padrões de Código
- **PSR-12** para PHP
- **ESLint** para TypeScript/Vue
- **Prettier** para formatação
- **Conventional Commits** para mensagens
