# PHP Project Base

This project provides a clean and organized starting point for building modern PHP applications.

It follows good development practices and uses an **MVC (Model-View-Controller)** architecture.

This means the code is split into clear layers, making it easier to maintain, reuse, and scale.

---

## ✨ What's included

* Organized, modern **MVC** architecture
* Separate layers: **Entity**, **Repository**, **Service**, **Controller**
* **PSR-4** autoloading configured (via Composer)
* Simple and efficient routing with FastRoute
* Single entry point (`public/index.php`)
* Perfect for creating **APIs**, **admin panels**, or **SaaS systems**
* **Doctrine Migrations** for robust database schema management
* A **global exception handler** to catch and manage unmapped exceptions

> The Model is structured in a modern way, with clear separation of responsibilities among Entities (data), Repositories (database access), and Services (business logic).

---

## 🛠️ Technologies and libraries used

* **PHP 8.3.13**
* **Composer** – Dependency Manager
* [**Doctrine DBAL**](https://www.doctrine-project.org/projects/dbal.html) – Database communication
* [**Doctrine Migrations**](https://www.doctrine-project.org/projects/migrations.html) – Database schema management
* [**PHP-DI**](https://php-di.org/) – Dependency Injection
* [**FastRoute**](https://github.com/nikic/FastRoute) – Lightweight and fast router
* [**Twig**](https://twig.symfony.com/) – Template Engine (HTML)
* [**Respect/Validation**](https://respect-validation.readthedocs.io/) – Data validation
* [**vlucas/phpdotenv**](https://github.com/vlucas/phpdotenv) – Loads `.env` variables into the local environment

---

## 📁 Folder Structure

```
├── App/
│   ├── Config/                 # Configuration and bootstrap files
│   ├── Controllers/            # Controllers (application entry logic)
│   ├── Database/
│   │   ├── Entities/           # Table representations (data model)
│   │   ├── Migrations/         # Doctrine Migration files
│   │   └── Repositories/       # Database queries and access
│   ├── Exceptions/             # Custom application exceptions and global handler
│   ├── Functions/              # Helper functions (e.g., Twig integration)
│   ├── Request/                # Form validations and input rules
│   ├── Routes/                 # Route definition files
│   ├── Services/               # Business logic (middleware between Controller and Repository)
│   └── Views/                  # HTML templates organized by domain
│
├── Core/
│   ├── Dbal/                   # Doctrine DBAL integration
│   ├── Functions/              # Generic functions and helpers
│   ├── Library/                # Reusable classes (Auth, Router, etc.)
│   ├── Request/                # Base class for validations
│   ├── Services/               # Service configuration (dependency injection)
│   └── Utils/                  # Various utilities
│
├── Public/
│   ├── Assets/                 # Public files (CSS, JS, images)
│   └── index.php               # Application entry point (front controller)
│
├── Temp/
│   └── Logs/                   # Application logs
│
├── .env.example                # Environment configuration example
├── cli-config.php              # Doctrine CLI configuration
├── composer.json               # Autoload and dependencies
├── migrations.php              # Doctrine Migrations configuration
```

---

## 🚀 How to use

1.  Clone this repository:
    ```bash
    git clone https://github.com/rauldiamantino/project-starter-php.git
    ```

2.  Go into the project folder and install dependencies:
    ```bash
    cd project-starter-php
    composer install
    ```

3.  Copy the environment file:
    ```bash
    cp .env.example .env
    ```

4.  Edit `.env` with your database and environment settings.

5.  Run Doctrine Migrations to set up your database schema:
    ```bash
    php vendor/bin/doctrine-migrations migrate
    ```

6.  Point your local server (Apache or Nginx) to the `public/` folder.

7.  You're all set! Time to start developing 🚀

---

## 🙌 Why I created this project

I created this project to make my life easier when starting new projects, whether for learning or for future micro SaaS applications.

Since the structure is simple, organized, and reusable, I believe it can also be useful for the community.

If you're just starting out or want a reliable foundation, feel free to use and adapt it!

---

## 👨‍💻 About

Created by **Raul Diamantino**

rauldiamantino25@gmail.com
