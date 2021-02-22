#!/bin/sh
set -e

if [[ $APP_ENV != 'test' ]]; then
  echo >&2 "Waiting for database connection..."
  until nc -z ${PSQL_HOST} ${PSQL_PORT}; do
      sleep 2
  done
fi

if [[ $APP_ENV = 'prod' ]]; then
  composer install --prefer-dist --no-dev
else
  composer install --prefer-dist
fi

exec docker-php-entrypoint "$@"
