FROM php:8.3-apache

RUN a2enmod rewrite

# Configurar Apache para servir index.php y deshabilitar listado de directorios
RUN echo '<Directory /var/www/html>\n\
    Options -Indexes +FollowSymLinks\n\
    AllowOverride All\n\
    DirectoryIndex index.php\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/custom.conf \
    && a2enconf custom

COPY . /var/www/html/

EXPOSE 80
