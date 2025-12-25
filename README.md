# Laravel Todo App - API & Interactive Edition
Project ini adalah aplikasi Todo List yang dikembangkan dari arsitektur Monolith menjadi **Hybird Web-API** Project ini adalah **(Milestone 2)** yang menonjolkan integrasi laravel sebagai Backend API, Alpine.js di Frontend, dan Dokumentasi API menggunakan Swagger.

## Tech Stack
- **Framework:** Laravel 11 (RESTful API)
- **Database:** PostgreSQL
- **Frontend Interactivity:** Alpine.js (Singgle Page Experience)
- **Documentation:** Swagger UI (OpenAPI 3.0)
- **Styling:** Tailwind CSS
- **Server:** Docker (Laravel Sail)

## API Documentation
Project ini sudah dilengkapi dengan **Swagger UI** untuk memudahkan pengujian API.
Setelah server berjalan, kamu dapat mengakses dokumentasi di:
`http://localhost/api/documentation`

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
3. **Install Dependencies:** Jika kamu memiliki PHP dan Composer di lokal:
    ``` Bash
    composer install
    ```
    Jika kamu **hanya ingin menggunakan** Docker (Tanpa install PHP di lokal):
    ``` Bash
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php83-composer:latest \
        composer install --ignore-platform-reqs
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