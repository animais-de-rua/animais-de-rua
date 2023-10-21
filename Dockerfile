FROM php:7.3.28-alpine3.14

RUN docker-php-ext-install pdo pdo_mysql
RUN apk add npm

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer


COPY . /app

WORKDIR /app

RUN composer install --ignore-platform-reqs
RUN npm install && npm run prod
