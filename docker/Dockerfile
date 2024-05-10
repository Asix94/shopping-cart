FROM php:8.2-fpm-alpine as base

RUN wget https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions \
    -O /usr/local/bin/install-php-extensions \
  && chmod +x /usr/local/bin/install-php-extensions \
  && install-php-extensions pcntl intl soap zip pdo_mysql sockets gd bcmath redis imagick ftp \
  && apk add curl \
  && rm -fr /var/cache/*

FROM base as development

WORKDIR /opt/app

RUN install-php-extensions @composer xdebug \
  && mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" \
  && apk add bash \
  && rm -fr /var/cache/*

ADD docker/dev/php/conf.d/* /usr/local/etc/php/conf.d/
ADD docker/dev/php/php-fpm.d/ /usr/local/etc/php-fpm.d/

FROM --platform=linux/amd64 nginx:1.25-alpine as nginx
ENV NGINX_SERVER_PORT=8080
ENV API_FPM_HOST=fpm

# Copy nginx configuration
COPY docker/dev/nginx/default.template /opt/default.template
COPY --chmod=755 docker/dev/nginx/entrypoint.sh /bin/entrypoint

ENTRYPOINT ["entrypoint"]