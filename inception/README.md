# Inception — System Administration & DevOps Infrastructure (Docker)

<p align="center">
  <img src="https://img.shields.io/badge/Project-Inception-000000?style=for-the-badge&logo=42&logoColor=white" alt="Inception" />
  <img src="https://img.shields.io/badge/Author-igarcia2-00599C?style=for-the-badge&logo=github&logoColor=white" alt="igarcia2" />
  <img src="https://img.shields.io/badge/Base_OS-Debian_Bullseye-A81D33?style=for-the-badge&logo=debian&logoColor=white" alt="Debian Bullseye" />
  <img src="https://img.shields.io/badge/Grade-125%20%2F%20125-success?style=for-the-badge" alt="125/125" />
</p>

---

## 📖 Overview

**Inception** is a system administration and DevOps infrastructure project developed by **igarcia2** as part of the 42 curriculum. The project focuses on building and orchestrating a secure, production-ready WordPress web infrastructure from scratch using **Docker** and **Docker Compose**.

Instead of using pre-built images from Docker Hub, every container service (NGINX, WordPress, MariaDB) is built using custom `Dockerfiles` based on **Debian Bullseye**. The infrastructure is completely isolated within a dedicated virtual network (`wp_network`), featuring NGINX as the sole TLS entry point on **Port 443**.

---

## 📋 Key Design Choices & Security Specifications

Adhering strictly to the 42 School constraints and best security practices, the infrastructure enforces the following operational standards:

*   **Docker Secrets vs. Environment Variables**: Passwords and sensitive data are strictly managed via **Docker Secrets**. Credentials are saved in isolated `.txt` files within a non-indexed `secrets/` directory and mounted at runtime to `/run/secrets/` inside containers. This prevents sensitive data exposure through environment variable dumps (e.g., the `env` command).
*   **Custom Network Isolation (`wp_network`)**: The stack runs on a custom bridge network (`wp_network`). Only the NGINX container exposes port 443 to the host. All inter-service communication (such as WordPress querying MariaDB at `mariadb:3306`) occurs internally via Docker's embedded DNS.
*   **No Pre-built Service Images**: Usage of official pre-configured images (e.g., `nginx:latest`, `wordpress:latest`) is strictly forbidden. All images are built manually using **Debian Bullseye** as the common base OS.
*   **TLS v1.2 / TLS v1.3 Encryption**: Port 80 (HTTP) is completely disabled. Traffic is strictly encrypted using self-signed SSL certificates bound to `igarcia2.42.fr`.
*   **Volume Persistence**: Persistent site assets and MariaDB database records are preserved outside the containers in designated host volume directories, ensuring zero data loss upon container deletion.

---

## 🧠 Infrastructure & Container Architecture

<div align="center">
<pre>
                                [ HOST MACHINE ]
                                       |
                         HTTPS Request (Port 443)
                                       |
+--------------------------------------v--------------------------------------+
|  CUSTOM DOCKER BRIDGE NETWORK (wp_network)                                  |
|                                                                             |
|   +---------------------------------------------------------------------+   |
|   | NGINX Container (TLS v1.2 / TLS v1.3)                               |   |
|   | - Sole external entry point (Port 443)                              |   |
|   | - Reverse proxies PHP requests to WordPress via FastCGI (Port 9000) |   |
|   +----------------------------------+----------------------------------+   |
|                                      |                                      |
|                                FastCGI / PHP                                |
|                                      |                                      |
|   +----------------------------------v----------------------------------+   |
|   | WordPress + php-fpm Container                                       |   |
|   | - Executes application logic                                        |   |
|   | - Connects internally to mariadb:3306 via Docker DNS                |   |
|   | - Reads database credentials from /run/secrets/                     |   |
|   +----------------------------------+----------------------------------+   |
|                                      |                                      |
|                              Database Queries                               |
|                                      |                                      |
|   +----------------------------------v----------------------------------+   |
|   | MariaDB Container                                                   |   |
|   | - SQL Database storage engine                                       |   |
|   | - Reads root/user credentials from /run/secrets/                    |   |
|   +---------------------------------------------------------------------+   |
|                                                                             |
+--------------------------------------+--------------------------------------+
                                       |
                        Persistent Storage / Volumes
                                       |
          +----------------------------+----------------------------+
          |                                                         |
          v                                                         v
  /home/igarcia2/data/wordpress             /home/igarcia2/data/mariadb
  (WordPress Filesystem)                    (MariaDB Database Engine)
</pre>
</div>

---

## 🛠️ Repository Directory Structure

```text
inception/
├── DEV_DOC.md                      # Developer documentation & infrastructure guidelines
├── USER_DOC.md                     # End-user operations & deployment guide
├── Makefile                        # Master orchestration (directory setup, SSL, secrets, build)
└── srcs/
    ├── .env                        # Environment configuration (DOMAIN_NAME, MYSQL_USER, etc.)
    ├── docker-compose.yml          # Master service orchestration manifest & secrets mounting
    ├── secrets/                    # Non-indexed directory storing Docker Secrets (.txt files)
    └── requirements/
        ├── mariadb/
        │   ├── Dockerfile          # Custom MariaDB image based on Debian Bullseye
        │   └── tools/              # Database initialization scripts reading from /run/secrets/
        │
        ├── nginx/
        │   ├── Dockerfile          # Custom NGINX image based on Debian Bullseye
        │   └── conf/               # TLS 1.2/1.3 reverse proxy configuration
        │
        ├── wordpress/
        │   ├── Dockerfile          # Custom PHP-FPM image based on Debian Bullseye
        │   ├── conf/               # FastCGI pool configuration (listens on 0.0.0.0:9000)
        │   └── tools/              # wp-cli auto-installation & configuration script
        │
        └── bonus/                  # Extended Infrastructure Services
            ├── adminer/
            │   └── Dockerfile      # Lightweight database management interface
            ├── ftp/
            │   ├── Dockerfile      # vsftpd server accessing WordPress volume
            │   └── conf/           # FTP server access and network configuration
            ├── portainer/
            │   └── Dockerfile      # Container management UI dashboard
            ├── redis/
            │   └── Dockerfile      # In-memory database cache for WordPress performance
            └── static/
                ├── Dockerfile      # Isolated lightweight static Web server
                ├── conf/           # Web server configuration
                └── site/           # Static website source assets
```

## 🚀 Setup & Execution

### 1. Host Network Setup
Map your local IP address to the project's target domain in your `/etc/hosts` file:

```bash
echo "127.0.0.1 igarcia2.42.fr" | sudo tee -a /etc/hosts
```

### 2. Environment Configuration
Create the `srcs/.env` file containing the core infrastructure parameters:

```env
DOMAIN_NAME=igarcia2.42.fr
MYSQL_DATABASE=wordpress
MYSQL_USER=wp_user
FTP_USER=ftp_user
```

### 3. Operational Commands

All stack actions are managed via the root `Makefile`:

*   **`make`**: Fully automates host directory creation, checks Docker installation, generates SSL certificates, generates random secret files in `srcs/secrets/`, and starts the container stack in detached mode (`docker-compose up -d --build`).
*   **`make down`**: Stops and removes running containers and networks while preserving volume data.
*   **`make clean`**: Stops containers and removes temporary build artifacts and custom images.
*   **`make fclean`**: Performs a deep clean, purging all containers, custom networks, built images, persistent volume directories, secret files, and SSL certificates.

---

## 🔍 Verification

Once the stack is deployed, verify the installation by testing access and volume state:

1.  **Check Running Containers**:
    ```bash
    docker ps
    ```
2.  **Access Web Application**:
    Open a web browser and navigate to `https://igarcia2.42.fr` (accept the self-signed SSL certificate warning).
3.  **Inspect Active Secrets inside Containers**:
    ```bash
    docker exec -it wordpress ls -la /run/secrets/
    ```

---

<div align="center">
  <p>Developed as part of the 42 School Curriculum by <b>igarcia2</b>.</p>
</div>
