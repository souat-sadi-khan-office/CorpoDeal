<p align="center">
    <a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a>
</p>

<!-- <p align="center">
    <a href="https://github.com/laravel/framework/actions">
        <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
    </a>
</p> -->

## Setup CPL E-commerce App in your Computer 

1. PHP 8.2
2. MySQL 
3. Xampp - above PHP 8.2

### Pre-requirement

1. On your PHP ini file the below option must be enabled:
* Zip
* gd
* intl

### Get project from git
1. Open a folder on your directory. 
2. Under the folder open command prompt or git bash. 
3. type - 'git init'
4. type - 'git pull https://github.com/cplwali/CPL.ECOMMERCE.git'

### Update Env File
copy .env-example file and rename as .env 

### Creating Database

Go to your PHPMyAdmin and create a new database. Change the database name on the .env file. 

### Update the composer

Now under the project folder open command prompt or git bash and type 'composer update'. After updating composer type 'php artisan key:generate'. 

### Setup database migrate

Now on the terminal type 'php artisan migrate' <br>
After that type 'php artisan db:seed'