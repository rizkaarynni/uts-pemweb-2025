FROM php:8.3-fpm

# ===============================
# Environment
# ===============================
ENV PS1="\u@\h:\w\\$ "
ENV TZ="Asia/Jakarta"
ENV COMPOSER_MEMORY_LIMIT='-1'

# ===============================
# System Dependencies
# ===============================
RUN apt-get update && apt-get install -y --no-install-recommends \
    libmemcached-dev \
    iputils-ping \
    telnet \
    netcat-openbsd \
    libreadline-dev \
    libgmp-dev \
    libzip-dev \
    libpq-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libssl-dev \
    libxml2-dev \
    libmagickwand-dev \
    openssh-server \
    git \
    cron \
    supervisor \
    nano \
    curl \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# ===============================
# PHP Extensions (API Ready)
# ===============================
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install \
        soap \
        exif \
        pcntl \
        intl \
        gmp \
        zip \
        pdo_mysql \
        pdo_pgsql \
        bcmath \
        gd

# ===============================
# PECL Extensions (API Support)
# ===============================
RUN pecl install \
        redis \
        mongodb-1.16.0 \
        imagick \
        xdebug \
        memcached \
    && docker-php-ext-enable \
        redis \
        mongodb \
        imagick \
        xdebug \
        memcached

# ===============================
# Composer
# ===============================
RUN curl -s https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# ===============================
# Laravel Scheduler (API Cron)
# ===============================
RUN echo "* * * * * root /usr/local/bin/php /var/www/html/artisan schedule:run >> /dev/null 2>&1" \
    > /etc/cron.d/laravel-scheduler && \
    chmod 0644 /etc/cron.d/laravel-scheduler

# ===============================
# Working Directory
# ===============================
WORKDIR /var/www/html

# ===============================
# PHP Config
# ===============================
ADD ./local.ini /usr/local/etc/php/conf.d

# ===============================
# Artisan Shortcuts
# ===============================
RUN echo '#!/bin/bash\nphp artisan "$@"' > /usr/bin/art && chmod +x /usr/bin/art && \
    echo '#!/bin/bash\nphp artisan migrate "$@"' > /usr/bin/migrate && chmod +x /usr/bin/migrate && \
    echo '#!/bin/bash\nphp artisan migrate:fresh --seed' > /usr/bin/fresh && chmod +x /usr/bin/fresh

# ===============================
# Entry Point
# ===============================
COPY ./docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh && \
    ln -s /usr/local/bin/docker-entrypoint.sh /

ENTRYPOINT ["docker-entrypoint.sh"]

EXPOSE 9000

CMD ["php-fpm"]
