# Nombre del Proyecto
Una breve descripción de tu proyecto.

Este proyecto de tesis fue realizado para la VIII dirección regional del Servicio de Impuestos Internos en Chile.

# Tecnologías utilizadas
- Laravel
- XAMPP (Apache y MySQL)
- Composer

# Programadores

- Francisco Munoz: Desarrollador FullStack y Jefe de proyecto
- Ricardo Flores: Desarrollador FullStack y arquitecto de base de datos
- Jorge Valdivia: Desarrollador Front-end
- Benjamin Barriga: Desarrollador y tester de calidad

## Tabla de Contenidos

- [Instalación](#instalación)
- [Uso](#uso)
- [Contribución](#contribución)
- [Licencia](#licencia)

## Instalación

### Instrucciones de instalación y configuración del entorno

En caso de un formateo del equipo, diríjase al anexo de “Linkografía” para ver los enlaces y descargar las dependencias anteriormente mencionadas y siga los siguientes pasos:

#### Descarga e instalación de XAMPP

1. Diríjase a la página oficial de XAMPP a través del enlace de la linkografía y seleccione el apartado de descargas.
2. Descargue la instalación marcada en la siguiente imagen. Debe contener la versión de PHP 8.2.4.
3. Ejecute el archivo de instalación y siga los pasos indicados en él.

#### Activación de extensiones para PHP

Para el correcto funcionamiento del sistema y sus librerías, es necesario activar las siguientes extensiones del lenguaje PHP:

1. Mbstring
2. Gd
3. Zip

Las cuales vienen desactivadas por defecto. Para activarlas necesita seguir los siguientes pasos:

1. Abra el panel de control y haga clic en la opción para abrir el archivo de configuración de PHP.
2. Una vez abierto el archivo, baje hasta encontrar la sección de extensiones (Referencia: Línea 892).
3. Active las extensiones mencionadas anteriormente borrando los caracteres “;” situados antes de los comandos. Finalmente, guarde y cierre el archivo de configuración de PHP.

#### Configuración de Apache

1. Abra la aplicación de XAMPP y diríjase al archivo de configuración.
2. Cambie el puerto en el que estará corriendo el servidor (se recomienda el “8012”).
3. Cambie el “DocumentRoot” y el “Directory” de Apache a la ubicación “C:/www” (este será el directorio donde se instalará el SIAV2.5).
4. Cree el VirtualHost especificando el mismo puerto y la carpeta del proyecto.
5. Guarde y cierre el archivo de configuración de Apache.
6. Reinicie el servidor de Apache.

#### Configuración de MySQL (Base de datos)

1. Inicie el servicio de “MySQL” desde XAMPP.
2. Ingrese a `http://localhost:8012/phpmyadmin` y cree una nueva base de datos e importe la base entregada por los desarrolladores.
3. Cree un usuario llamado “INFORMATICA” con permisos de administración y creación de respaldos de base de datos:
    ```sql
    CREATE USER 'INFORMATICA'@'localhost' IDENTIFIED BY 'password_de_informatica';
    GRANT ALL PRIVILEGES ON *.* TO 'INFORMATICA'@'localhost' WITH GRANT OPTION;
    FLUSH PRIVILEGES;
    ```
4. Cree un usuario llamado “CLIENTE” con permisos para acceder a la base de datos del sistema SIAv2.5:
    ```sql
    CREATE USER 'CLIENTE'@'localhost' IDENTIFIED BY 'password_del_cliente';
    GRANT ALL PRIVILEGES ON base_de_datos_X.* TO 'CLIENTE'@'localhost';
    FLUSH PRIVILEGES;
    ```
5. Configure el acceso por “cookie” en el archivo de configuración de “mysql”.

#### Descarga e instalación de Composer

1. Diríjase a la página oficial de Composer a través del enlace `https://getcomposer.org`.
2. Descargue e instale Composer siguiendo los pasos indicados.

#### Instrucciones de instalación del sistema

1. Diríjase al directorio raíz del proyecto y abra una terminal.
2. Ejecute el comando `composer install` para descargar todas las librerías utilizadas en el proyecto.
3. Copie el archivo `.env.example` y renómbrelo a `.env`.
4. Configure el archivo `.env` con los siguientes elementos:
    - APP_DEBUG: `false`
    - APP_URL: IP de la computadora seguida de “:8012”
    - SESSION_DRIVER: `database`
    - DB_DATABASE: Nombre de la base de datos, DB_USERNAME y DB_PASSWORD: Credenciales de la cuenta “CLIENTE”.
5. Genere una llave segura de aplicación con el comando: `php artisan key:generate`.

### Instrucciones para respaldar la base de datos

1. Ingrese al motor de la base de datos y diríjase a la base de datos actual del sistema.
2. Vaya a la pestaña “Exportar” y guarde la exportación en formato SQL o CSV.

#### Crear una nueva base de datos (Opcional)

Siga los pasos de la sección de configuración de entorno para crear una nueva base de datos y otorgar los permisos necesarios a los usuarios “INFORMATICA” y “CLIENTE”. Configure nuevamente el archivo `.env` con el nuevo nombre de la base de datos.

## Uso

Ejemplos e instrucciones sobre cómo utilizar tu proyecto.

## Contribución

Este proyecto es exclusivo para la VIII dirección regional del Servicio de Impuestos Internos en Chile y los programadores involucrados. No se aceptan contribuciones externas en este momento.

## Licencia

Esta licencia es exclusiva para el Servicio de Impuestos Internos en Chile. Los desarrolladores también pueden utilizar el código bajo los términos de esta licencia.
