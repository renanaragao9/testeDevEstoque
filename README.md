# Sistema de Gestão de Estoque

Sistema moderno para controle de produtos, vendas, compras e movimentações de estoque.

## Tecnologias

**Backend:**

- Laravel 12 + PHP 8.3
- MySQL 8.0
- Laravel Sanctum (autenticação)

**Frontend:**

- Vue 3 + TypeScript
- PrimeVue 4.3 (componentes)
- Tailwind CSS (estilização)
- Pinia (gerenciamento de estado)

**Infraestrutura:**

- Docker + Docker Compose

## Arquitetura Laravel (Service Partner)

O backend usa o padrão **Service Partner** para separar a lógica de negócio em camadas:

- **Form Requests** (`app/Http/Requests/`): Validação e autorização de entrada.
- **Controllers** (`app/Http/Controllers/`): Orquestração, chamam Services e retornam Resources.
- **Services** (`app/Services/`): Lógica de negócio, interações com Models e transações.
- **API Resources** (`app/Http/Resources/`): Formatação de saída JSON.
- **Models** (`app/Models/`): Relacionamentos Eloquent e métodos simples.

Fluxo: `Request → Controller → Service → Model → Service → Resource → Response`

## Arquitetura Frontend

O frontend usa Vue 3 com Pinia para gerenciamento de estado e separação de responsabilidades:

- **Views** (`src/views/`): Páginas e layouts principais.
- **Stores** (`src/stores/`): Estado global com Pinia.
- **Services** (`src/services/`): Chamadas de API e lógica de negócio.
- **Types** (`src/types/`): Definições de tipos TypeScript.

Fluxo: `View → Service → Store → View`

## Instalação

1. Clone o repositório.
2. Execute `docker-compose up -d` para subir os containers.
3. Execute `docker-compose exec backend php artisan migrate --seed` para migrar e testar o banco
4. Acesse o frontend em http://localhost:5173 e o backend em http://localhost:8000.
