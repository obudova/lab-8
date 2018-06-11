FROM php:7.2-fpm-alpine3.6

MAINTAINER Olha Budova <olgabudova55@gmail.com>

WORKDIR /opt/app

RUN docker-php-ext-install mysqli pdo_mysql
# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin

COPY composer.json ./
COPY composer.lock ./

COPY . .

ENTRYPOINT ["sh", "/opt/app/entrypoint.sh"]
