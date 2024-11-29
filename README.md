
## About Project
- [Laravel](https://laravel.com) v10

A simple integration of FilamentPHP for a Laravel project.
Project is for controlling your Ads and their Ad templates.
## Third Party Libraries:
- [FilamentPHP](https://filamentphp.com) v3



## Installation

Create a database in your phpMyAdmin import a dump from `database/filament.sql`, edit .env.example with your
database credentials and run the following commands:

- composer install
- npm install
- php artisan db:seed
- php artisan serve

Most of data is included into demo database if you want to use it.

## Usage

Visit [http://localhost:8000/admin](http://localhost:8000/admin) and login.

For users 

- Super admin ( email: super@super.com, password: super )
- Admin ( email: admin@admin.com, password: admin )
- Super admin ( email: editor@editor.com, password: editor )
- Super admin ( email: viewer@viewer.com, password: viewer )

## TODO

- Add some kind of documentation.
- Add tests.
- Fix an issue when try to upgrade laravel to version 11 with artisan command.


