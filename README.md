# Garud - Metal Management System

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

**Garud** is a powerful, modern web-based **Metal Management System** built with Laravel. It is designed specifically for businesses in the metal industry — such as jewelry manufacturing, bullion trading, gold/silver trading, and scrap metal management.

The system provides a clean admin dashboard, real-time metal rate tracking, product management, and detailed **Profit & Loss (P&L)** reporting to help businesses make data-driven decisions.

## ✨ Key Features

- **Modern Responsive Admin Dashboard**  
  Comprehensive overview with key business statistics, total weight, value, and performance metrics.

- **Product Management**  
  Full CRUD functionality for managing products with details like weight, purity, and pricing.

- **Live Metal Rate Management**  
  Dynamic tracking and updating of metal rates (Gold, Silver, etc.) with historical records.

- **Profit & Loss Reporting**  
  Advanced analytics to calculate profitability based on metal rate fluctuations and product data.

- **Professional Admin Interface**  
  Responsive sidebar navigation, modern navbar, dark/light theme toggle, and mobile-friendly design.

- **Secure Authentication System**  
  Built-in Laravel Auth with protected admin routes.

- **Clean & Scalable Codebase**  
  Follows Laravel best practices, Eloquent ORM, Blade templating, and Vite asset bundling.

## 🛠️ Tech Stack

- **Backend**: PHP 8.1+ | Laravel 10/11
- **Frontend**: Blade Templates | JavaScript | SCSS
- **Database**: MySQL / MariaDB
- **Build Tool**: Vite
- **Testing**: PHPUnit

## 🚀 Quick Installation

### Prerequisites
- PHP ≥ 8.1
- Composer
- Node.js & npm
- MySQL Database

### Installation Steps

```bash
# 1. Clone the repository
git clone https://github.com/SomnathM41i/Garud.git
cd Garud

# 2. Install PHP dependencies
composer install

# 3. Install frontend dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Configure your database in .env file
#    (Set DB_DATABASE, DB_USERNAME, DB_PASSWORD)

# 7. Run migrations (if any)
php artisan migrate

# 8. Build assets
npm run dev

# 9. Start development server
php artisan serve