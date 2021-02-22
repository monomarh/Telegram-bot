#!/bin/sh
set -e

if [[ $APP_ENV = 'dev' ]]; then
  composer install --prefer-dist
else
  composer install --prefer-dist --no-dev
fi

exec docker-php-entrypoint "$@"
