FROM php:7.2-fpm-alpine3.6

MAINTAINER Olha Budova <olgabudova55@gmail.com>

WORKDIR /opt/app

RUN apk update && apk add nginx

#NGINX
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin

COPY composer.json ./
COPY composer.lock ./

RUN composer install --no-scripts --no-autoloader
COPY . .
RUN composer dump-autoload --optimize

RUN chown -R nginx:nginx .

CMD /usr/sbin/nginx
