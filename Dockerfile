FROM wyveo/nginx-php-fpm:php82

COPY . /usr/share/nginx

COPY nginx.conf /etc/nginx/conf.d/default.conf

WORKDIR /usr/share/nginx

RUN composer install

RUN chown -R nginx:nginx .

RUN php artisan key:generate

EXPOSE 80
