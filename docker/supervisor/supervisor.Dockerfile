FROM php:8.2-fpm-alpine

WORKDIR /var/www/pegadaian

RUN mkdir -p /var/log/supervisor/

RUN apk --no-cache add postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql

RUN apk update && apk add --no-cache supervisor

RUN mkdir -p "/etc/supervisor/logs"

# COPY ./docker/supervisor/supervisord.conf /etc/supervisor/conf.d
# RUN chown -R www-data:www-data /etc/supervisor/conf.d

CMD ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisor/conf.d/supervisord.conf"]
# CMD ["/usr/bin/supervisord"]
