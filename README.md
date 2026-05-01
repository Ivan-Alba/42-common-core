This project has been created as part of the 42 curriculum bymirifern, daortega, igarcia2 and kseligma

# NexusNine: ft_transcendence

## Description

### Goal:

The goal of this project is to develop a secure and performant web platform for real-time multiplayer gaming, focusing on seamless competition, player connectivity, and individual progression tracking.

### Overview:

NexusNine is a centralized gaming hub where users can engage in competitive matches and monitor their development through a dedicated statistics system. The platform facilitates social interaction by allowing users to manage a friend list and track the status of their peers, focusing on the competitive bond between players.

Progression is a core element of the experience, driven by an achievement system and a global ranking ladder that rewards skill and consistency. The application is designed to provide a fluid transition from user authentication to live gameplay, ensuring that matchmaking and performance metrics are always at the forefront of the user experience.

### Key Features:

- Competitive Multiplayer: Core interactive gameplay optimized for real-time competition between users.
- Automated Matchmaking: An efficient system that pairs available players for immediate game sessions.
- Achievement System: A set of unlockable milestones that recognize and reward specific player accomplishments and milestones.
- Social Connectivity: A streamlined friend management system to build a network of competitive peers and track their activity.
- Global Ranking: A dynamic leaderboard that displays player standings and performance metrics across the platform.
- Profile & Progress Tracking: Secure user accounts that store detailed match history, statistics, and earned rewards.

## Instructions

### Prerequisites:
- Docker: Version 29.1.4 (Minimum recommended: 24.x.x).
- Docker Compose: Version v5.0.1 (Minimum recommended: v2.20.x).
- Modern Web Browser: (Chrome) with WebGL support to run the Unity game client.
- Internet Connection: Required for the initial build to download base images and for the email password recovery system.

### Setup: 

[Instrucciones para .env y configuración inicial]

### Step-by-Step Execution:
[Paso 1]
[Paso 2]

## Resources

### References: 

[Documentación, artículos, tutoriales, etc.]

#### Unity

#### Frontend

- [React](https://react.dev/learn)  
- [Node.js](https://nodejs.org/en/docs)
- [npm](https://docs.npmjs.com/)  
- [TypeScript](https://www.typescriptlang.org/docs/)  
- [Vite (frontend usage)](https://vitejs.dev/guide/)  
- [React Router](https://reactrouter.com/en/main)  
- [Axios (HTTP client)](https://axios-http.com/docs/intro)  
- [Tailwind CSS](https://tailwindcss.com/docs)  

---

#### Backend

- [Laravel](https://laravel.com/docs)  
- [PHP](https://www.php.net/docs.php)  
- [Brevo (formerly Sendinblue)](https://developers.brevo.com/docs)  
- [Redis](https://redis.io/docs/)  
- [Vite (Laravel integration)](https://laravel.com/docs/vite)  
- [MariaDB](https://mariadb.com/docs/)  
- [Reverb (Laravel WebSockets)](https://laravel.com/docs/reverb)  
- [Adminer](https://www.adminer.org/en/)  
- [Composer (PHP package manager)](https://getcomposer.org/doc/)  
- [Laravel Eloquent ORM](https://laravel.com/docs/eloquent)  
- [Laravel Queues](https://laravel.com/docs/queues)  
- [Laravel Events & Broadcasting](https://laravel.com/docs/broadcasting)  
- [Laravel Validation](https://laravel.com/docs/validation)  
- [Laravel Authentication (Sanctum)](https://laravel.com/docs/sanctum)  
- [REST API design](https://restfulapi.net/)  
- [PHPUnit (testing)](https://phpunit.de/documentation.html)  

#### DevOps

- https://docs.docker.com/get-started/docker-overview/
- https://docs.docker.com/compose/gettingstarted/
- https://docs.docker.com/engine/storage/volumes/
- https://nginx.org/en/docs/http/ngx_http_proxy_module.h
- https://docs.unity3d.com/2022.3/Documentation/Manual/web-server-config-nginx.html

### AI Usage: 

Artificial intelligence has been used through the whole project in different manners. 

- Code generation assistance through autocomplete and code snippet generation.
- Assistance on the architectural design of the backend.
- Search engine to find resources on internet.
- Assistance by answering questions, helping the developers understand the tools integrated into the project.
- Quality assurance. (QA)
- Debugging assistance. 
  
#### DevOps:
Research and Understand: Explore various containerization patterns and networking configurations within Docker.
Comparative Analysis: Evaluate the advantages and disadvantages of different deployment strategies to determine the most stable and scalable approach for our microservices.
Configuration Validation: Verify syntax and best practices for docker-compose files and environment variable management, ensuring security and efficiency.
Troubleshooting: Assist in diagnosing complex networking issues between the Unity WebGL client and the backend services.

## Team Information

**Importante**, poner los roles del subject (Product Owner (PO), Project Manager (PM) / Scrum Master, Technical Lead / Architect)
| Member   | Assigned Role(s)                         | Responsibilities                                                                 |
|----------|------------------------------------------|----------------------------------------------------------------------------------|
| mirifern | Frontend Developer / Graphic Designer    | ___                                                                              |
| daortega | DevOps Engineer / System Architect       | Designed and implemented the project’s infrastructure using Docker and Docker Compose, ensuring a containerized and reproducible environment for all services. |
| igarcia2 | ___                                      | ____                                                                             |
| kseligma | Backend Developer                        | Designed and implemented the project's backend; Laravel, API, database, Redis, Mailing, authentication, etc. |

## Project Management

### Organization: 
[Cómo se organizó el trabajo, distribución de tareas y reuniones]

### Tools: 
Github, Visual Studio Code

### Communication: 
Discord, Slack, Whatsapp

## Technical Stack
- **Frontend**: [Tecnologías y frameworks]
- **Backend**: Laravel, Reverb, Redis, Blade, Brevo, Laravel ecosystem dependences (Sanctum, Fortify, Phpunit) 
- **Database**: We chose MariaDB as our primary database system due to the team's high level of familiarity and prior experience with its ecosystem.
- **Other**: [Otras librerías o tecnologías significativas]

### Major Technical Choices Justification: 
[Justificación de las decisiones técnicas principales]

#### Frontend

#### Backend

- Laravel is a PHP framework with a big community and a rich ecosystem that offers solutions to many of the problems an application like ours have.
- Reverb is a Laravel-adjacent websocket server, which is fundamental for multiplayer features facilitating live interaction between browser users.

#### Database

#### Other

## Database Schema

This database supports a multiplayer card game with users, matches, achievements, and chat.

### `users`: Stores player accounts.

- `id` (PK)
- `name`, `email`
- `password`
- `status` (online, offline, etc.)
- `language`

### `cards`: Game cards with stats.

- `id` (PK)
- `name`, `description`
- `category` (human, animal, beast, artifact)
- `top`, `bottom`, `left`, `right`
- `rarity`

### `card_translations`: Card localization.

- `card_id` -> cards.id
- `language`
- `name`, `description`

### `card_user`: User card pivot table.

- `user_id` → users.id
- `card_id` → cards.id

### `matches`: Finished matches.

- `player_1_id` → users.id
- `player_2_id` → users.id
- `winner_id` → users.id
- `p1_score`, `p2_score`

### `active_matches`

- `match_uuid`
- `player_1_id`, `player_2_id`
- `current_turn_player_id`
- `board_state` (JSON)
- `hands_state` (JSON)

#### `matchmaking_queue`: Players waiting for match.

- `user_id` → users.id
- `game_mode`

#### `achievements`

- `code` (unique)
- `category`
- `goal`
- `points`

#### `player_stats`

- `user_id` → users.id
- `level`, `experience`
- `wins`, `losses`, `draws`
- `ranked_points`

#### `friendships`

- `user_id` → users.id
- `friend_id` → users.id
- `status` (pending, accepted, rejected)

#### `chats`

- `id` (PK)
- `visibility`

#### `messages`: Chat messages.

- `chat_id` → chats.id
- `user_id` → users.id
- `text`

#### `user_chat`: Users in chats

- `user_id` → users.id
- `chat_id` → chats.id

#### Other

- `games`, `teams`, `team_user` → game structure
- `jobs`, `failed_jobs`, `job_batches` → queues
- `cache`, `sessions` → system

#### Relationships Summary

- Users - Cards -> `card_user`
- Users - Achievements -> `achievement_user`
- Users - Matches -> `matches`, `active_matches`
- Users - Users -> `friendships`
- Users - Chats -> `user_chat`

## Features List
| Feature                    | Member(s) | Description                                                                 |
|---------------------------|-----------|-----------------------------------------------------------------------------|
| Unified Authentication    |           | Secure login system including password recovery via email and profile-based password updates. |
| Real-time Matchmaking     |           | Automated system that connects two online players for a live competitive session. |
| Versus AI Mode            |           | A single-player mode where users can practice against an artificial intelligence with adjustable difficulty. |
| Multiple Game Modes       |           | Different gameplay variations (e.g., ranked, no card point limit for maximum flexibility) to enhance the core experience. |
| Global Ranking System     |           | A dynamic leaderboard that calculates and displays player standings based on match results. |
| Achievement System        |           | A collection of unlockable rewards and milestones based on specific gameplay performance. |
| Friend Management         |           | A social interface to find, add, and track the online status of other players. |
| Personal Match History    |           | A dedicated section in the user profile to review past performance and scores. |
| Containerized Deployment  |           | Full orchestration of all the features above using Docker to ensure system stability. |


## Modules

IMPORTANTE: PONER JUSTIFICACIÓN, COMO SE IMPLEMENTO Y QUIEN TRABAJÓ EN EL MODULO
  
Web
- **Major**: Use a framework for both the frontend and backend.
- **Minor**: Use a frontend framework (React, Vue, Angular, Svelte, etc.).
- **Minor**: Use a backend framework (Express, Fastify, NestJS, Django, etc.).
- **Major**: Implement real-time features using WebSockets or similar technology
- **Major**: Allow users to interact with other users
- **Minor**: Use an ORM for the database.
- **Minor**: Custom-made design system with reusable components, including a proper color palette, typography, and icons (minimum: 10 reusable components).
- **Minor**: Implement advanced search functionality with filters, sorting, and pagination.

Accessibility and Internationalization
- **Minor**: Support for multiple languages (at least 3 languages).
- **Minor**: Support for additional browsers.

User Management
- **Major**: Standard user management and authentication.
- **Minor**: Game statistics and match history (requires a game module).
- **Minor**: Implement remote authentication with OAuth 2.0 (Google, GitHub, 42, etc.).

Artificial Intelligence
- **Major**: Introduce an AI Opponent for games

Gaming and user experience
- **Major**: Implement a complete web-based game where users can play against each other.
- **Major**: Remote players — Enable two players on separate computers to play the same game in real-time.
- **Minor**: Game customization options.
- **Minor**: A gamification system to reward users for their actions
- **Minor**: Implement spectator mode for games.

Total sin dudas: 19
Total con dudas: 24

## Individual Contributions

### [login1]

### Contributions: 
[Desglose detallado]

### Challenges: 
[Retos enfrentados y cómo se superaron]

