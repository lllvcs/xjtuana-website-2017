FROM php:7-fpm-alpine3.8

#-----------------------------------------
# Install required PHP extensions
RUN BUILD_PACKAGES="curl-dev libxml2-dev" \
  set -x \
  && apk add --no-cache ${BUILD_PACKAGES} \
  && docker-php-ext-install -j$(nproc) \
    curl \
    dom \
    iconv \
    pdo_mysql \
    simplexml \
    soap \
    sockets \
    zip \
  && apk del ${BUILD_PACKAGES}

#-----------------------------------------
# Install supervisor
RUN set -x \
  && apk add --no-cache \
    supervisor

#-----------------------------------------
# Modify php & php-fpm config
RUN set -x \
  #-----------------------------------------
  # Modify php config
  && echo "expose_php = false" >> $PHP_INI_DIR/php.ini \
  #-----------------------------------------
  # Modify php-fpm config
  && sed -i \
    -e "s/pm.max_children = 5/pm.max_children = 30/" \
    -e "s/pm.start_servers = 2/pm.start_servers = 6/" \
    -e "s/pm.min_spare_servers = 1/pm.min_spare_servers = 6/" \
    -e "s/pm.max_spare_servers = 3/pm.max_spare_servers = 30/" \
    /usr/local/etc/php-fpm.d/www.conf

RUN set -x \
  #-----------------------------------------
  # Set the crontab schedule
  && { \
    echo '#!/bin/sh'; \
    echo 'php /docker/www/artisan schedule:run >> /dev/stdout 2>&1'; \
  } | tee /etc/periodic/15min/laravel \
  && chmod +x /etc/periodic/15min/laravel

COPY ./supervisord.conf /docker/supervisord.conf
COPY ./entrypoint.sh /docker/entrypoint.sh

ENTRYPOINT ["/docker/entrypoint.sh"]
