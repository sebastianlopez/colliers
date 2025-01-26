<p align="center"><img src="http://rhiss.net/front/images/logo.svg" width="300"></p>

<p align="center">
Paquete básico Laravel 8.0
</p>

### Pasos iniciales

Crear el archivo .env y asignar los valores correspondientes a DB_DATABASE, DB_USERNAME, DB_PASSWORD y ejecutar en consola:

1. composer update
2. php artisan migrate
3. php artisan db:seed
4. php artisan key:generate 

### Gulpfile

1. Ejecutar en la raíz del proyecto las 2 primeras líneas del inicio del archivo gulpfile.js.

* Todas las tareas js y sass tanto del admin como del front se encuentran en el archivo gulpfile.js en el directorio raíz.

### Convenciones de nomenclatura a usar

Por favor seguir las siguientes convenciones de código al usar este paquete básico:

- [Convenciones para Laravel](https://github.com/alexeymezenin/laravel-best-practices#follow-laravel-naming-conventions).
- [Convenciones para Javascript](https://www.nexedi.com/Javascript-Naming_Conventions).

#### Nota para producción

Asignar permisos 755 para las carpetas y 644 para los archivos.

© Copyright by [Rhiss.net](http://rhiss.net) 
