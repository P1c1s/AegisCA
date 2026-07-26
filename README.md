<p align="center">
  <img src="resources/aegis-ca.svg" alt="Aegis CA Logo" width="168" height="270">
  <br>
  <em>"Aegis CA, your local SSL certificate manager."</em>
</p>

# Aegis CA 


**Aegis CA** is a lightweight, secure, and completely self-contained web application for creating, signing, and managing a **Local Certificate Authority (CA)** and its corresponding **self-signed SSL/TLS certificates**.

Designed for **developers**, **system administrators**, and **HomeLab** enthusiasts, Aegis CA allows you to independently manage your own **PKI (Public Key Infrastructure)**. A PKI is a system of policies, processes, and technologies used to create, manage, distribute, store, and revoke digital certificates and public-key encryption. By hosting your own local PKI, you can eliminate browser security warnings for internal services (e.g., FreshRSS, Pi-hole, Proxmox, Home Assistant, and many others) without relying on external public certificate authorities.

##### Recommended Setup: Nginx Proxy Manager (NPM)

For seamless local traffic routing, we highly recommend pairing Aegis CA with [Nginx Proxy Manager (NPM)](https://github.com/NginxProxyManager/nginx-proxy-manager). NPM makes it incredibly easy to secure your reverse proxies because it **supports custom SSL certificate uploads**. Simply generate your private key (`.key`) and certificate (`.crt`/`.pem`) in Aegis CA, upload them directly to NPM as a "Custom Certificate", and assign them to your local domains in seconds.

---

# 📚 Table of Contents

* [✨ Features](#-features)
* [🛠️ Tech Stack](#-tech-stack)
* [📦 Installation](#-installation)
* [💻 Aegis CA CLI](#-aegis-ca-cli)

---

# ✨ Features

## 🛡️ Security and Authentication

* Secure login with password hashing via PHP's `password_hash()`.
* Password changes directly from the admin area.
* Secure session management with access control on all protected pages.
* Multi-admin support.

<!-- PLACEHOLDER -->

<!-- ![Login](web-ui/public/assets/img/login.png) -->



## 🏛️ Certificate Authority (Root CA) Management

* Guided Root CA creation.
* Separate input fields for Subject (`C`, `ST`, `L`, `O`, `OU`, `CN`) with automatic X.509 standard composition.
* Validity monitoring with creation date, expiration, and status (Active/Expired).
* Import and export of certificates (`.crt`) and private keys (`.key`).

![CA Management](resources/ca.png)


## 🚀 SSL Certificate Issuance

* Certificate signing using any CA present in the database.
* Full **Subject Alternative Names (SAN)** support.
* Compatibility with:

  * Domains
  * Wildcards (`*.example.local`)
  * Subdomains
  * IPv4 and IPv6 addresses
* Default validity of **825 days**, in line with modern browser recommendations.

![Dashboard](resources//dashboard.png)

---

# 🛠️ Tech Stack

Aegis CA follows a **No Framework** philosophy, prioritizing simplicity, code readability, and performance.

## Backend

* PHP 8 (OOP)
* Native OpenSSL extension (`php_openssl`)
* No use of `exec()` or external OpenSSL calls

## Database

* MySQL
* MariaDB
* PDO with Prepared Statements

## Frontend

* HTML5
* CSS3
* Vanilla JavaScript
* Responsive interface
* Native Dark Mode

---

# 📦 Installation

## Docker
Using **Docker** is the recommended method to install and run Aegis CA.

The containerized approach allows you to:

* avoid manual configuration of PHP and its extensions;
* completely isolate the application;
* simplify updates;
* have a reproducible environment in seconds.

The official image is available on the GitHub Container Registry:

https://github.com/users/P1c1s/packages/container/package/aegis-ca

#### Download the image

```bash
docker pull ghcr.io/p1c1s/aegis-ca:latest
```

#### Running with Docker

You can pull and run the container directly using the Docker CLI:

```bash 
docker run -d \
  --name aegis-ca \
  --hostname aegis-ca \
  --restart always \
  -e TZ=Europe/Rome \
  -p 8080:80 \
  -v aegis-ca_data:/data \
  ghcr.io/p1c1s/aegis-ca:latest
```
#### Docker Compose

Download the [docker-compose.yml](https://github.com/P1c1s/AegisCA/blob/main/docker-compose.yml
) file from the repository, or create one locally. Then start the stack using Docker.

```bash
docker compose up -d
```
Ecco il testo pronto in inglese (perfetto da aggiungere al file `README.md`):


## First Configuration

Once the container is up and running:

1. Open your web browser and navigate to `http://<your-ip>:8080` (replace `<your-ip>` with your server's IP address).
2. Log in using the default credentials:
* **Username:** `admin`
* **Password:** `admin`


3. **Important:** Make sure to change the default admin password immediately after your first login for security reasons.

---

# Aegis CA CLI

Aegis CA also includes a **Command Line Interface (CLI)** designed primarily as a **maintenance and recovery tool**. While it allows administrators to manage users, Certificate Authorities, and certificates directly from the terminal, its main purpose is to provide a reliable way to perform administrative and recovery tasks when the web interface is unavailable or insufficient.

The CLI is especially useful for **certificate recovery**, data import and export, application migrations, and resolving issues caused by database corruption or version incompatibilities during upgrades. This makes it possible to quickly restore the PKI infrastructure and recover certificates and private keys without relying solely on the web interface.

The CLI is implemented as a **Python script** and is automatically installed at `/usr/local/bin/aegis-ca`, making it directly executable with:

```bash
aegis-ca
```

To display all available commands:

```bash
aegis-ca --help
```

---

## 👤 User Management (`user`)

Provides commands for creating and managing application users.

### Available Commands

- `list` → List all registered users.
- `create` → Create a new administrator.
- `update` → Update a username and/or password.

#### List Users

```bash
aegis-ca user list
```

#### Create a User

```bash
aegis-ca user create \
    -u admin \
    -p secure_password
```

#### Update Username and Password

```bash
aegis-ca user update \
    -u admin \
    --new-username administrator \
    --new-password new_secure_password
```

---

## 🔐 Certificate Management (`cert`)

Provides commands for managing Certificate Authorities and certificates stored in the database.

### Available Commands

- `list`
- `import`
- `export`

### Options

| Option | Description |
|--------|-------------|
| `-t`, `--type` | Certificate type (`ca` or `cert`). **Required**. |
| `-f`, `--file` | File path or wildcard pattern used for importing certificates. |
| `--cn` | Common Name (CN) to export. Use `all` (or omit the option) to export every matching item. |

#### List All Certificate Authorities

```bash
aegis-ca cert list -t ca
```

#### List All Certificates

```bash
aegis-ca cert list -t cert
```

#### Import a Certificate

If the current directory contains:

- `my-server.crt`
- `my-server.key`

simply specify the base filename:

```bash
aegis-ca cert import \
    -t cert \
    -f my-server
```

#### Bulk Import Using Wildcards

```bash
aegis-ca cert import \
    -t ca \
    -f exported_cas/*
```

The CLI automatically detects matching `.crt` and `.key` pairs, ignoring duplicate or incomplete files.

#### Export All Certificates

```bash
aegis-ca cert export -t cert
```

A directory containing all certificates and their corresponding private keys will be created automatically.

#### Export a Single Certificate Authority

```bash
aegis-ca cert export \
    -t ca \
    --cn "HomeLab-Root-CA"
```