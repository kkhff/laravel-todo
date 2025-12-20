# Laravel Todo App - Monolith Edition
Project ini adalah aplikasi Todo List sederhana yang dibangun menggunakan Laravel dan PostgreSQL. Project ini merupakan tahap awal (Milestone 1) dari pembelajaran arsitektur Backend, yang nantinya akan dievolusi menjadi RESTful API dengan dokumentasi Swagger.

## Tech Stack
- **Framework:** Laravel 11
- **Database:** PostgreSQL
- **Server:** Docker (Laravel Sail)
- **Frontend:** Blade Templating + Tailwind CSS

## Cara Menjalankan Project (Lokal)
1. **Clone Repository:**
    ``` Bash
    git clone https://github.com/kkhff/laravel-todo.git
    cd laravel-todo
    ```
2. **Setup Environment:**
    ``` Bash
    cp .env.example .env
    ```
3. **Install Dependencies:**
    ``` Bash
    composer install
    ```
4. **Jalankan Docker Sail:**
    ``` Bash
    ./vendor/bin/sail up -d
    ```
5. **Generate Key & Migrate:**
    ``` Bash 
    ./vendor/bin/sail artisan key:generate
    ```
6. **Migrasi Database:**
    ``` Bash
    ./vendor/bin/sail artisan migrate
    ```