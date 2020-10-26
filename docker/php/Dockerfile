FROM php:7.4-fpm
ARG userId
RUN apt-get update && apt-get install -y vim zlib1g-dev libzip-dev libpq-dev git libicu-dev libxml2-dev

RUN docker-php-ext-install opcache
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install xml
RUN docker-php-ext-install zip
RUN docker-php-ext-install pcntl

# Xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

# NPM and node
RUN apt-get update && apt-get install -y gnupg2
RUN curl -sL https://deb.nodesource.com/setup_12.x -n | bash - && apt-get install -y nodejs
RUN groupadd --gid $userId node && useradd --uid $userId --gid node --shell /bin/bash --create-home docker

RUN curl https://getcomposer.org/download/1.10.6/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer

WORKDIR /var/www/app

# Install dependencies
RUN composer install
RUN npm install
RUN ./node_modules/.bin/encore dev
RUN php bin/console doctrine:migrations:diff && php bin/console doctrine:migrations:migrate
RUN php bin/console doctrine:fixtures:load
