# PHP Project Base

This project provides a clean and organized starting point for building modern PHP applications.

It follows best development practices and uses an **MVC (Model-View-Controller)** architecture with clear separation of concerns, making it easier to maintain, reuse, and scale.

---

## ✨ What's included

* Modern, organized **MVC** architecture  
* Separate layers: **Entities**, **Repositories (Interfaces and Implementations)**, **Services**, **Controllers**  
* **PSR-4** autoloading configured via Composer  
* Simple and efficient routing with FastRoute  
* Single entry point (`public/index.php`)  
* Ideal for building **APIs**, **admin panels**, or **SaaS platforms**  
* **Doctrine DBAL** for database communication  
* **Doctrine Migrations** for robust schema management  
* **Dependency Injection** using PHP-DI for flexible and decoupled code  
* Global exception handling for consistent error management  

> The architecture models the data layer with Entities, database access with Repositories, and business logic with Services, ensuring low coupling and high cohesion.

---

## 📝 Note from the Author

This README was not written 100% in English by me, as I am still developing my language skills and haven’t yet reached the level to write complete texts fluently.

However, I translated it to make this repository accessible to people from all over the world.

I do not guarantee this is the best way to start your projects — you may find situations that are not best practices.

What I can say for sure is that I put together everything I know combined with the application of studies I have done.

I believe the best way to learn is by applying what you study in practice.


---

## 🛠️ Technologies and libraries used

* **PHP 8.3.13**  
* **Composer** – Dependency manager  
* [**Doctrine DBAL**](https://www.doctrine-project.org/projects/dbal.html) – Database abstraction layer  
* [**Doctrine Migrations**](https://www.doctrine-project.org/projects/migrations.html) – Schema versioning and migration  
* [**PHP-DI**](https://php-di.org/) – Dependency injection container with autowiring  
* [**FastRoute**](https://github.com/nikic/FastRoute) – Fast and lightweight router  
* [**Twig**](https://twig.symfony.com/) – Templating engine  
* [**Respect/Validation**](https://respect-validation.readthedocs.io/) – Data validation library  
* [**vlucas/phpdotenv**](https://github.com/vlucas/phpdotenv) – Loads environment variables from `.env`

---

## 📁 Folder Structure

```
├── App/
│   ├── Config/                       # Application configuration and bootstrap files
│   ├── Controllers/                  # Controllers (application entry point logic)
│   ├── Database/
│   │   ├── Entities/                 # Entities representing database tables
│   │   ├── Migrations/               # Doctrine migration files
│   │   └── Repositories/
│   │       ├── Interfaces/           # Repository contracts (interfaces)
│   │       └── Implementations/
│   │           └── Doctrine/         # Doctrine repository implementations
│   ├── Exceptions/                   # Custom exceptions and global handler
│   ├── Functions/                    # Helper functions (e.g. Twig integration)
│   ├── Request/                      # Input validation and request rules
│   ├── Routes/                       # Route definitions
│   ├── Services/                     # Business logic layer (service classes)
│   └── Views/                        # HTML templates organized by domain
│       ├── Company/
│       ├── Error/
│       ├── Home/
│       ├── Partials/
│       └── User/
│
├── Core/
│   ├── Database/                     # Database infrastructure
│   │   ├── Exceptions/               # Database-specific exceptions
│   │   └── Implementations/Doctrine/ # Generic Doctrine implementations
│   ├── Functions/                    # Generic helpers and functions
│   ├── Library/                      # Reusable classes (Auth, Router, etc.)
│   ├── Request/                      # Base validation classes
│   ├── Services/                     # Service configuration and DI bindings
│   └── Utils/                        # Miscellaneous utilities
│
├── Public/                           # Public assets (CSS, JS, images)
│   ├── Assets/
│   └── index.php                     # Single entry point (front controller)
│
├── Temp/
│   └── Logs/                         # Application log files
│
├── .env.example                      # Environment variables example
├── cli-config.php                    # Doctrine CLI configuration
├── composer.json                     # Composer dependencies and autoload config
├── migrations.php                    # Doctrine Migrations configuration
└── README.md                         # This file
```

---

## 🚀 How to use

1. Clone the repository:  
    ```bash
    git clone https://github.com/rauldiamantino/project-starter-php.git
    ```

2. Navigate to the project folder and install dependencies:  
    ```bash
    cd project-starter-php
    composer install
    ```

3. Copy the environment file:  
    ```bash
    cp .env.example .env
    ```

4. Edit `.env` with your database credentials and environment settings.

5. Run Doctrine Migrations to create your database schema:  
    ```bash
    php vendor/bin/doctrine-migrations migrate
    ```

6. Point your local web server (Apache, Nginx, PHP Built-in) to the `public/` folder.

7. You're ready! Start developing 🚀

---

## 🙌 Why I created this project

I created this project to simplify starting new projects, whether for learning or micro SaaS applications.

The structure is simple, organized, and reusable, making it useful for the community to speed up development of robust PHP apps.

Feel free to use and adapt it to your needs!

---

## 👨‍💻 About

Created by **Raul Diamantino**  
rauldiamantino25@gmail.com

---
    
