<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Uplifta CMS

This is a Laravel-based Content Management System for the Uplifta website. It is designed to make all site content dynamic and manageable via an admin panel. The frontend uses Tailwind CSS for a modern, responsive UI.

## Features
- Admin panel for managing services, success stories, and contact info
- Dynamic frontend rendering
- Tailwind CSS integration
- Secure authentication for admin access

## Setup
1. Copy `.env.example` to `.env` and set your database credentials.
2. Run `composer install` if dependencies are missing.
3. Run `php artisan migrate` to set up the database.
4. Run `php artisan serve` to start the development server.

## Customization
- Update content types and fields in the admin panel as needed.
- Extend with more features (blog, team, etc.) as your needs grow.

---
For more details, see the Laravel documentation: https://laravel.com/docs
