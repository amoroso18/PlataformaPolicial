<p align="center"><a href="https://github.com/amoroso18/PlataformaPolicial" target="_blank"><img src="https://github.com/amoroso18/PlataformaPolicial/blob/master/public/images/sivipol/BannerSivipol.png?raw=true" width="400" alt="SIVIPOL Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## SIVIPOL

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

Para usarlo de manera local:

1. Tener instalado wampserver con PHP 8.1
2. Instalar composer
3. Tener descargado el proyecto en wamp
4. En el proyecto duplicar el archivo .env.example a .env
5. Crear una base de datos llamado laravel, en caso de cambiar el nombre, se reemplaza en la variable de entorno global en el archivo .env DB_DATABASE=laravel
6. Abrir una terminal en la carpeta del proyecto
7. usar el comando: composer install
8. usar el comando: php artisan key:generate
9. usar el comando: php artisan migrate:fresh --seed
10. para limpiar el cache: php artisan optimize:clear
11 para inicialiar el proyecto: php artisan serve

Para inicializar:
primero: Inicializar wampserser
segundo: Paso 6,10,11

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
