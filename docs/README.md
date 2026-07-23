# XAWAD: Simple XAMPP alternative Web Apps Development Environment Using PHP and MariaDB

Welcome to the **XAWAD** (*Web Apps Development Environment Using PHP and MariaDB*) project! This project is designed to help you learn the fundamentals of web programming (PHP), HTML forms, and database management (MariaDB / SQL).

## Why This Project Setup?

In standard Computer Science curricula, students are typically introduced to web and database development using **XAMPP on Windows**.

While XAMPP is commonly used on Windows in schools, this project uses **NixOS / Linux** with **`nix` + `devenv`** to deliver a more modern, flexible setup. This provides several key advantages:

* **Zero Manual Downloads:** phpMyAdmin, PHP, and MariaDB are automatically fetched and configured via the Nix Store.
* **Automated Database Seeding:** Databases, schemas, sample tables, and users are auto-created on initial launch.
* **No Hidden System Services:** Stopping `devenv` cleanly shuts down MariaDB without leaving background daemons running on your host system.
* **Selective Process Control:** Run only the specific web applications you are currently testing.
* **Reproducible Setup:** Anyone cloning this repository gets the exact same versions of PHP and MariaDB, avoiding any *"it works on my machine"* troubleshooting.

## Key Definitions

Before you begin, here are definitions for terms you will see throughout this guide:

- **Repository (Repo)**: A folder where all source code and files for a project are stored and tracked using Git.

- **Clone**: Creating a local copy of a remote Git repository on your own computer.

- **Localhost**: A standard hostname that means "this computer." It allows your computer to run web servers locally without sending requests over the Internet.

- **Port**: A software endpoint directing network traffic to specific local services (e.g., Port `8000` for phpMyAdmin, Port `8001` for App 1).

- **Database (DB)**: An organized collection of structured data (like tables with rows and columns) stored electronically.

- **Prepared Statements**: A secure method of executing SQL queries that prevents malicious users from manipulating database commands (preventing SQL Injection).

- **`devenv` Process Manager**: The CLI tool used to start, stop, and manage local environment services.

## Project Directory Overview

```
xawad/
├── apps/
│   │
│   ├── app1/
│   │   ├── db/
│   │   │   ├── db/schema.sql       # Initial database table structure & sample data
│   │   │   └── db/db.php           # Database connection helper script
│   │   ├── public/                 # Files accessible to web browsers
│   │   │   ├── index.php           # App dashboard (lists appointments)
│   │   │   ├── book.php            # HTML booking form
│   │   │   └── process-book.php    # Form handling and SQL insertion logic
│   │   └── docs/
│   │       └── README.md
│   │
│   ├── app2/
│   │   ├── db/
│   │   │   ├── db/schema.sql
│   │   │   └── db/db.php
│   │   ├── public/
│   │   │   └── index.php
│   │   └── docs/
│   │       └── README.md
│   │
│   └── app3/
│       ├── db/
│       │   ├── db/schema.sql
│       │   └── db/db.php
│       ├── public/
│       │   └── index.php
│       └── docs/
│           └── README.md
│
├── docs/
│   └── README.md
├── devenv.nix                      # Environment definition (Nix configuration)
└── .devenv/                        # Local runtime data & database state (Git ignored)
```

## Prerequisites

To run this project, your system only needs:

- *Nix Package Manager* (or *NixOS* operating system).
- `direnv` shell extension (with `nix-direnv` integration enabled).
- `git` version control tool.

> **Note:** No global installations of PHP, MariaDB, or phpMyAdmin are required.

## Getting Started

Follow these steps in order to set up your environment for the first time.

### Step 1: Clone the Repository

Open your terminal and clone the project into your local source directory:

```Bash
mkdir -p ~/src
cd ~/src
git clone https://github.com/mnajib/xawad.git
cd xawad
```

### Step 2: Enable the Environment

When you enter the project directory for the first time, `direnv` will ask for permission to load the Nix development environment.

Run:
```Bash
direnv allow
```

> **Note**: The first time you run this, Nix will download PHP, MariaDB, and other required packages. This may take a few minutes.

### Step 3: Launch Services & Initialize Database

Start the environment with all automated provisioning tasks enabled:

```Bash
devenv up --mode all
```

Upon launching:

1. MariaDB starts on port 3306.

2. car_service_db is created and seeded with ./apps/app1/db/scheme.sql.

3. app_user is provisioned with database permissions.

4. phpMyAdmin is served directly from the Nix store at http://127.0.0.1:8000.

5. App 1 starts automatically at http://127.0.0.1:8001.

## Accessing Services

| Service | Local URL | Port |Auto-Start |
|---------|-----------|------|-----------|
|phpMyAdmin | http://127.0.0.1:8000 | 8000 | Yes |
|App 1 | http://127.0.0.1:8001 | 8001 | Yes |
|App 2 | http://127.0.0.1:8002 | 8002 | Manual |
|App 3 | http://127.0.0.1:8003 | 8003 |Manual |

## Controlling Additional Applications

To start secondary applications without running everything simultaneously:

- To start App 2

    ```Bash
    devenv processes start app2
    ```

- To stop App 2

    ```Bash
    devenv processes stop app2
    ```

## Database Authentication

    - phpMyAdmin Login:
      - Host: 127.0.0.1
      - Username: root
      - Password: (Leave blank)

    - Application Connection Settings (app_user):
      - Host: 127.0.0.1:3306
      - Database Name: car_service_db
      - Username: app_user
      - Password: app_password

## Troubleshooting & State Reset

If MariaDB experiences port conflicts or schema initialization failures:

1. Kill any orphaned MariaDB daemons

    ```Bash
    pkill -9 -f mariadbd
    ```

2. Reset local state directory

    ```Bash
    rm -rf .devenv
    ```

3. Relaunch environment and tasks

    ```Bash
    devenv up --mode all
    ```

## Stopping the Servers

When you finish working, stop each service by pressing `Ctrl + C` in its respective terminal window.
