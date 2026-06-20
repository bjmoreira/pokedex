<div align="center">

# 🔴 Pokédex

**Laravel 12 · PHP 8.3 · Vue 3 · Vite · MySQL 8 · Docker**

A full-stack Pokédex: browse Pokémon, capture them and manage your personal collection.

[🇧🇷 Português](#-português) · [🇺🇸 English](#-english) · [🇪🇸 Español](#-español)

</div>

---

## 🚀 Quick start

```bash
git clone https://github.com/bjmoreira/pokedex.git
cd pokedex
docker compose up -d --build
```

| Service | URL | Notes |
| --- | --- | --- |
| 🖥️ Frontend (SPA) | http://localhost:8080 | Vue 3 + Vite served by nginx |
| ⚙️ Backend (API) | http://localhost:8000 | Laravel 12 REST API |
| 🗄️ Database | localhost:3306 | MySQL 8 |

**Default test account / Conta de teste / Cuenta de prueba**

```
email:    admin@admin.com
password: 123456
```

> Migrations and seeding run automatically on the first `docker compose up`.
> Migrations e seed rodam automaticamente no primeiro `docker compose up`.
> Las migraciones y el seed se ejecutan automáticamente en el primer `docker compose up`.

---

<a name="-português"></a>
## 🇧🇷 Português

### Sobre

Pokédex full-stack reescrita do zero para **Laravel 12 / PHP 8.3** no backend (API REST) e
**Vue 3 + Vite** no frontend (SPA), com banco **MySQL 8** — tudo orquestrado por Docker Compose.

### Funcionalidades

- 🔐 **Autenticação** por token (Laravel Sanctum) com tela de login.
- 📃 **Listagem de Pokémon** consumindo a [PokeAPI](https://pokeapi.co), exibidos em cards com imagem, número, nome e tipos.
- 🔎 **Busca** por nome em tempo real.
- 🔍 **Detalhes** de cada Pokémon (altura, peso, experiência base e tipos) em modal.
- 🧬 **Cadeia de evolução** completa exibida no modal de detalhes.
- 🎯 **Capturar** Pokémon para a coleção do usuário autenticado.
- 📦 **Coleção pessoal** ("Capturados") isolada por usuário.
- ✏️ **Editar** apelido, nível e anotações de cada Pokémon capturado.
- 🗑️ **Soltar** (excluir) um Pokémon da coleção.
- 🌐 **Multi-idioma** na interface (🇧🇷 Português, 🇺🇸 Inglês, 🇪🇸 Espanhol) com troca por bandeiras.
- ⚡ **Cache** das respostas da PokeAPI no backend para respostas rápidas.

### Stack

| Camada | Tecnologia |
| --- | --- |
| Backend | Laravel 12, PHP 8.3, Sanctum, MySQL |
| Frontend | Vue 3, Vite, Pinia, Vue Router, vue-i18n, Bootstrap 5 |
| Infra | Docker, Docker Compose, nginx |

### Como rodar

1. Tenha **Docker** e **Docker Compose** instalados.
2. `docker compose up -d --build`
3. Acesse **http://localhost:8080** e entre com `admin@admin.com` / `123456`.

Para acompanhar os logs: `docker compose logs -f`.
Para parar: `docker compose down` (use `-v` para apagar também o banco).

### Estrutura

```
pokedex/
├── docker-compose.yml      # orquestra os 3 serviços
├── backend/                # API Laravel 12 (PHP 8.3)
│   ├── app/                # Models, Controllers, Services, Requests
│   ├── routes/api.php      # rotas da API
│   ├── database/           # migrations, seeders, factories
│   └── Dockerfile
└── frontend/               # SPA Vue 3 + Vite
    ├── src/                # views, components, stores, i18n
    ├── nginx.conf          # serve o SPA e faz proxy de /api
    └── Dockerfile
```

### Endpoints da API

| Método | Rota | Descrição |
| --- | --- | --- |
| `POST` | `/api/login` | Autentica e retorna um token |
| `POST` | `/api/logout` | Revoga o token atual |
| `GET` | `/api/user` | Usuário autenticado |
| `GET` | `/api/pokemon` | Lista Pokémon (PokeAPI, com cache) |
| `GET` | `/api/pokemon/{idOrName}` | Detalhes de um Pokémon |
| `GET` | `/api/pokemon/{idOrName}/evolution` | Cadeia de evolução |
| `GET` | `/api/captured` | Lista os capturados do usuário |
| `POST` | `/api/captured` | Captura um Pokémon |
| `PUT` | `/api/captured/{id}` | Atualiza apelido/nível/nota |
| `DELETE` | `/api/captured/{id}` | Solta um Pokémon |

---

<a name="-english"></a>
## 🇺🇸 English

### About

A full-stack Pokédex rewritten from scratch using **Laravel 12 / PHP 8.3** on the backend (REST API)
and **Vue 3 + Vite** on the frontend (SPA), backed by **MySQL 8** — all orchestrated with Docker Compose.

### Features

- 🔐 **Token authentication** (Laravel Sanctum) with a login screen.
- 📃 **Pokémon listing** consuming the [PokeAPI](https://pokeapi.co), shown as cards with image, number, name and types.
- 🔎 **Real-time search** by name.
- 🔍 **Details** for each Pokémon (height, weight, base experience and types) in a modal.
- 🧬 **Full evolution chain** displayed in the details modal.
- 🎯 **Capture** Pokémon into the authenticated user's collection.
- 📦 **Personal collection** ("Captured") scoped per user.
- ✏️ **Edit** nickname, level and notes for each captured Pokémon.
- 🗑️ **Release** (delete) a Pokémon from the collection.
- 🌐 **Multi-language** UI (🇧🇷 Portuguese, 🇺🇸 English, 🇪🇸 Spanish) with a flag switcher.
- ⚡ **Caching** of PokeAPI responses on the backend for fast responses.

### Stack

| Layer | Technology |
| --- | --- |
| Backend | Laravel 12, PHP 8.3, Sanctum, MySQL |
| Frontend | Vue 3, Vite, Pinia, Vue Router, vue-i18n, Bootstrap 5 |
| Infra | Docker, Docker Compose, nginx |

### Running

1. Install **Docker** and **Docker Compose**.
2. `docker compose up -d --build`
3. Open **http://localhost:8080** and sign in with `admin@admin.com` / `123456`.

Follow logs: `docker compose logs -f`.
Stop: `docker compose down` (add `-v` to also drop the database).

### API endpoints

| Method | Route | Description |
| --- | --- | --- |
| `POST` | `/api/login` | Authenticate and return a token |
| `POST` | `/api/logout` | Revoke the current token |
| `GET` | `/api/user` | Authenticated user |
| `GET` | `/api/pokemon` | List Pokémon (PokeAPI, cached) |
| `GET` | `/api/pokemon/{idOrName}` | Pokémon details |
| `GET` | `/api/pokemon/{idOrName}/evolution` | Evolution chain |
| `GET` | `/api/captured` | List the user's captured Pokémon |
| `POST` | `/api/captured` | Capture a Pokémon |
| `PUT` | `/api/captured/{id}` | Update nickname/level/note |
| `DELETE` | `/api/captured/{id}` | Release a Pokémon |

---

<a name="-español"></a>
## 🇪🇸 Español

### Acerca de

Una Pokédex full-stack reescrita desde cero con **Laravel 12 / PHP 8.3** en el backend (API REST)
y **Vue 3 + Vite** en el frontend (SPA), con base de datos **MySQL 8** — todo orquestado con Docker Compose.

### Funcionalidades

- 🔐 **Autenticación** por token (Laravel Sanctum) con pantalla de inicio de sesión.
- 📃 **Listado de Pokémon** consumiendo la [PokeAPI](https://pokeapi.co), mostrados en tarjetas con imagen, número, nombre y tipos.
- 🔎 **Búsqueda** por nombre en tiempo real.
- 🔍 **Detalles** de cada Pokémon (altura, peso, experiencia base y tipos) en un modal.
- 🧬 **Cadena evolutiva** completa mostrada en el modal de detalles.
- 🎯 **Capturar** Pokémon para la colección del usuario autenticado.
- 📦 **Colección personal** ("Capturados") aislada por usuario.
- ✏️ **Editar** apodo, nivel y notas de cada Pokémon capturado.
- 🗑️ **Liberar** (eliminar) un Pokémon de la colección.
- 🌐 **Interfaz multilenguaje** (🇧🇷 Portugués, 🇺🇸 Inglés, 🇪🇸 Español) con selector de banderas.
- ⚡ **Caché** de las respuestas de la PokeAPI en el backend para respuestas rápidas.

### Stack

| Capa | Tecnología |
| --- | --- |
| Backend | Laravel 12, PHP 8.3, Sanctum, MySQL |
| Frontend | Vue 3, Vite, Pinia, Vue Router, vue-i18n, Bootstrap 5 |
| Infra | Docker, Docker Compose, nginx |

### Ejecución

1. Instala **Docker** y **Docker Compose**.
2. `docker compose up -d --build`
3. Abre **http://localhost:8080** e inicia sesión con `admin@admin.com` / `123456`.

Ver logs: `docker compose logs -f`.
Detener: `docker compose down` (añade `-v` para borrar también la base de datos).

### Endpoints de la API

| Método | Ruta | Descripción |
| --- | --- | --- |
| `POST` | `/api/login` | Autentica y devuelve un token |
| `POST` | `/api/logout` | Revoca el token actual |
| `GET` | `/api/user` | Usuario autenticado |
| `GET` | `/api/pokemon` | Lista Pokémon (PokeAPI, con caché) |
| `GET` | `/api/pokemon/{idOrName}` | Detalles de un Pokémon |
| `GET` | `/api/pokemon/{idOrName}/evolution` | Cadena evolutiva |
| `GET` | `/api/captured` | Lista los capturados del usuario |
| `POST` | `/api/captured` | Captura un Pokémon |
| `PUT` | `/api/captured/{id}` | Actualiza apodo/nivel/nota |
| `DELETE` | `/api/captured/{id}` | Libera un Pokémon |

---

<div align="center">

Made with ❤️ · Powered by the [PokeAPI](https://pokeapi.co)

</div>
