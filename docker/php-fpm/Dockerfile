FROM php:fpm-alpine

COPY wait-for-it.sh /usr/bin/wait-for-it
COPY entrypoint.sh /usr/bin/entrypoint

RUN chmod +x /usr/bin/wait-for-it
RUN chmod +x /usr/bin/entrypoint

RUN apk --update --no-cache add git

RUN docker-php-ext-install pdo_mysql

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www


EXPOSE 9000
CMD composer install ; wait-for-it database:3306; wait-for-it database:3307;  bin/console doctrine:database:create --if-not-exists ;  bin/console doctrine:database:create --connection=shard_two --if-not-exists ; bin/console doctrine:migrations:migrate  --no-interaction ; bin/console doctrine:migrations:migrate --no-interaction --em=shard_two;  php-fpm