# Usamos la imagen oficial de PHP con Apache
FROM php:8.1-apache

# Instalamos las extensiones requeridas y utilidades
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalamos Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecemos el directorio de trabajo
WORKDIR /var/www/html

# Copiamos el proyecto Laravel al directorio de trabajo
COPY . .

# Instalamos las dependencias del proyecto
RUN composer install

# Cambiamos los permisos para el storage de Laravel
RUN chown -R www-data:www-data storage

# Exponemos el puerto 80 para el servidor web
EXPOSE 80

# Configuramos Apache
RUN a2enmod rewrite
COPY .docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Arrancamos Apache en foreground
CMD ["apache2-foreground"]
