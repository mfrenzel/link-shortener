#!/bin/sh

composer install
wait-for-it database:3306
wait-for-it database2:3306
bin/console doctrine:migrations:migrate --no-interaction
bin/console doctrine:migrations:migrate --no-interaction --em=shard_two
exec "$@"