FROM php:8.2-fpm-alpine

RUN mkdir -p /var/log/supervisor/

RUN apk --no-cache add postgresql-dev

RUN apk update

RUN set -eux; \
    apk upgrade; \
    apk update; \
    apk add --no-cache\
        bash \
        libzip-dev \
        zip \
        unzip \
        supervisor \
        git \
        curl \
        libmemcached-dev \
        libpq-dev \
        libwebp-dev \
        libpng-dev \
        libxpm-dev \
        libmcrypt-dev

RUN docker-php-ext-install \
        gd pdo pdo_pgsql pgsql pdo_mysql zip sockets bcmath opcache

RUN apk update && apk add --no-cache supervisor

RUN mkdir -p "/etc/supervisor/logs"

COPY ./docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

COPY ./docker/supervisor/mycronjob.txt /var/spool/cron/crontabs/root

COPY ./docker/supervisor/entry.bash /usr/sbin
RUN chmod 777 /usr/sbin/entry.bash
# Apply cron job


RUN touch /var/log/cron.log
RUN chmod -R 777 /var/log/cron.log
ENTRYPOINT /usr/sbin/entry.bash
CMD ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisor/supervisord.conf"]
