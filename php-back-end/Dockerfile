# Dockerfile
FROM php:8.3-apache

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
      /etc/apache2/sites-available/*.conf \
      /etc/apache2/apache2.conf \
    && sed -ri 's!<Directory /var/www/html>!<Directory ${APACHE_DOCUMENT_ROOT}>!g' \
      /etc/apache2/apache2.conf

RUN apt-get update \
  && apt-get install -y --no-install-recommends \
       libonig-dev \
       libxml2-dev \
       libcurl4-openssl-dev \
       libzip-dev \
       unzip \
  && docker-php-ext-install \
       pdo_mysql \
       mbstring \
       xml \
       curl \
       zip \
  && a2enmod rewrite \
  && apt-get purge -y --auto-remove \
       libonig-dev \
       libxml2-dev \
       libcurl4-openssl-dev \
       libzip-dev \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

# 4) Copia o php.ini customizado
COPY php-back-end/php.ini /usr/local/etc/php/php.ini

COPY back-end/ /var/www/html/

COPY php-back-end/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# ajusta o entrypoint e mantém o CMD original para subir o Apache
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
RUN chown -R www-data:www-data /var/www/html \
  && chmod -R 755 /var/www/html

EXPOSE 80
