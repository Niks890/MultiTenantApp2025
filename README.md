# Overview

This is a multi-tenant system built using Laravel 12, designed to support multiple tenants with isolated data. Each tenant has its own database or schema, ensuring data separation while sharing the same codebase. The system is ideal for SaaS applications requiring scalability and customization.

# CDM
![Image Alt](https://github.com/Niks890/MultiTenantApp2025/blob/94e983ccc6a8bcd76040525821cec4b059c7cc27/CDM1.png)

## Features

-   Multi-tenancy with database-per-tenant or schema-per-tenant approach.
-   Tenant identification via subdomain, domain, or custom logic.
-   Centralized management for tenant creation and configuration.
-   Secure authentication and authorization for tenant users.
-   Scalable architecture for handling multiple tenants efficiently.
-   Laravel provides a robust, maintainable, and scalable foundation for large applications.

### Installation

Follow these steps to set up the project locally:

1. Clone the repository

-   This project is private. Team members with access can clone the repository using:

```bash
git clone <repository-url>
cd <project-folder>
```

2. Install Dependencies

```bash
composer install
npm install
```

3. Environment Setup

-   Copy the example environment file:

```bash
cp .env.example .env
```

-   Configure .env with your database credentials and application settings:

```bash
APP_NAME=your_app_name
APP_URL=http://your-app-url
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_name_database
DB_USERNAME=root
DB_PASSWORD=
```

4. Generate Application Key

```bash
php artisan key:generate
```

5. Run Migrations

-   Run migrations for the central database:

```bash
php artisan migrate
```

6. Add vietnam_units.json file

-   Create the folder in the project root if it doesn't already exist:

```bash
mkdir -p storage/app/private/data
```

-   Copy the vietnam_units.json file into that folder:

```bash
storage/app/private/data/vietnam_units.json
```

-   Example folder structure:

```bash
/your-project-root/
 └── storage/
     └── app/
         └── private/
             └── data/
                 └── vietnam_units.json
```

-   vietnam_units.json must exist before running the seeder VietnamUnitsSeeder.

7. Run Seeders

-   Run all seeders:

```bash
php artisan db:seed
```

-   Or run specific seeders as needed:

```bash
# Create system superadmin
php artisan db:seed --class=SystemUserSeeder

# Create default plans
php artisan db:seed --class=DefaultPlansSeeder

# Seed provinces and wards
php artisan db:seed --class=VietnamUnitsSeeder
```

8. Start the Application

```bash
php artisan serve
```
