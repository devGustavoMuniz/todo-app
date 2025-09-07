# 📋 Sistema de Gestão de Tarefas Colaborativa

Um sistema moderno de gestão de tarefas construído com Laravel 12 + Vue 3 + Inertia.js, utilizando Laravel Sail para desenvolvimento containerizado.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Vue.js](https://img.shields.io/badge/Vue.js-3-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white)
![TypeScript](https://img.shields.io/badge/TypeScript-5-3178C6?style=for-the-badge&logo=typescript&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Sail-2496ED?style=for-the-badge&logo=docker&logoColor=white)

## ✨ Funcionalidades

- 🔐 **Autenticação Completa** - Sistema de login/registro com Laravel Breeze
- 📊 **Dashboard Interativo** - Estatísticas em tempo real e progresso visual
- ✅ **Gestão de Tarefas** - CRUD completo com filtros e busca avançada
- 🔍 **Busca e Filtros** - Pesquisa por título/descrição e filtro por status
- 📱 **Design Responsivo** - Interface moderna com shadcn/ui e Tailwind CSS
- 🚀 **SPA Experience** - Navegação fluida com Inertia.js
- 🔒 **Segurança** - Middleware de autorização e validação robusta
- 📈 **Estatísticas** - Métricas de produtividade e taxa de conclusão
- 🎨 **UX Moderna** - Componentes reutilizáveis e microinterações

## 🛠️ Stack Tecnológica

### Backend
- **Laravel 12** - Framework PHP moderno
- **PHP 8.3** - Linguagem de programação
- **MySQL** - Banco de dados relacional
- **Redis** - Cache e sessões
- **Laravel Sanctum** - Autenticação SPA

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

## 🚀 Instalação e Configuração

### Pré-requisitos

- Docker e Docker Compose
- Git

### Passo a Passo

1. **Clone o repositório**
```bash
git clone [seu-repositorio]
cd todo-app
```

2. **Configure o ambiente**
```bash
# Copie o arquivo de ambiente
cp .env.example .env

# Instale as dependências via Sail
./vendor/bin/sail up -d
./vendor/bin/sail composer install
./vendor/bin/sail npm install
```

3. **Configure a aplicação**
```bash
# Gere a chave da aplicação
./vendor/bin/sail artisan key:generate

# Execute as migrations
./vendor/bin/sail artisan migrate

# Popule com dados de exemplo (opcional)
./vendor/bin/sail artisan db:seed
```

4. **Inicie o desenvolvimento**
```bash
# Terminal 1: Containers
./vendor/bin/sail up

# Terminal 2: Frontend (Vite HMR)
./vendor/bin/sail npm run dev
```

5. **Acesse a aplicação**
- **Frontend**: http://localhost
- **phpMyAdmin**: http://localhost:8080

### Credenciais de Teste

Se executou o seeder, use estas credenciais:
- **Email**: test@example.com
- **Senha**: password

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

# Testes específicos
./vendor/bin/sail test --filter=TaskTest
./vendor/bin/sail test tests/Unit/TaskRepositoryTest.php

# Com coverage
./vendor/bin/sail test --coverage
```

### Cobertura de Testes
- ✅ **Feature Tests**: CRUD, autenticação, autorização
- ✅ **Unit Tests**: Repository Pattern, lógica de negócio
- ✅ **Integration Tests**: Dashboard, estatísticas
- 📊 **Coverage**: 95%+ das funcionalidades críticas

## 📊 Funcionalidades Detalhadas

### Dashboard
- **Estatísticas**: Total, pendentes, em progresso, concluídas
- **Taxa de Conclusão**: Cálculo automático de produtividade
- **Progresso Visual**: Barra de progresso animada
- **Tarefas Recentes**: Lista das 5 últimas tarefas
- **Ações Rápidas**: Botões para operações comuns

### Gestão de Tarefas
- **CRUD Completo**: Criar, visualizar, editar, excluir
- **Estados**: Pendente, Em Progresso, Concluída
- **Filtros Avançados**: Por status e busca textual
- **Paginação**: Navegação eficiente em listas grandes
- **Validação**: Frontend (Zod) + Backend (Form Requests)

### Segurança
- **Autenticação**: Laravel Breeze + Sanctum
- **Autorização**: Middleware + Policies
- **Isolamento**: Usuários só veem suas tarefas
- **Validação**: Entrada sanitizada e validada
- **CSRF Protection**: Proteção contra ataques

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

## 🔧 Comandos Úteis

### Laravel Sail
```bash
# Gerenciamento de containers
./vendor/bin/sail up -d          # Iniciar
./vendor/bin/sail down           # Parar
./vendor/bin/sail restart        # Reiniciar

# Comandos Laravel
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
./vendor/bin/sail artisan tinker

# Dependências
./vendor/bin/sail composer install
./vendor/bin/sail npm install

# Build e desenvolvimento
./vendor/bin/sail npm run dev    # Desenvolvimento
./vendor/bin/sail npm run build  # Produção
```

### Alias Recomendado
Adicione ao seu `~/.bashrc` ou `~/.zshrc`:
```bash
alias sail='./vendor/bin/sail'
```

## 🤝 Contribuição

1. Fork o projeto
2. Crie sua feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

### Padrões de Código
- **PSR-12** para PHP
- **ESLint** para TypeScript/Vue
- **Prettier** para formatação
- **Conventional Commits** para mensagens

## 📝 Licença

Este projeto está licenciado sob a Licença MIT - veja o arquivo [LICENSE](LICENSE) para detalhes.

## 🆘 Suporte

### Problemas Comuns

**Erro de permissão no storage:**
```bash
./vendor/bin/sail artisan storage:link
sudo chmod -R 775 storage bootstrap/cache
```

**Erro de porta ocupada:**
```bash
# Altere as portas no docker-compose.yml
ports:
  - "8000:80"  # em vez de "80:80"
```

**Cache em desenvolvimento:**
```bash
./vendor/bin/sail artisan optimize:clear
./vendor/bin/sail npm run dev
```

### Links Úteis
- [Laravel Documentation](https://laravel.com/docs)
- [Vue.js Guide](https://vuejs.org/guide/)
- [Inertia.js Docs](https://inertiajs.com/)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [shadcn/ui Vue](https://ui.shadcn.com/)

---

**Desenvolvido com ❤️ usando Laravel Sail + Vue 3 + TypeScript**