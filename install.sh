#!/bin/bash

UIDVAL=$(id -u)
GIDVAL=$(id -g)
PROJECT_NAME=php-beauty
ADMIN_USERNAME=scn-admin
ADMIN_PASSWORD=scn-pwd

cd php-beauty/
docker-compose up -d
docker-compose exec php-fpm composer install
docker-compose exec php-fpm php ./vendor/bin/pimcore-install \
    --admin-username=$ADMIN_USERNAME \
    --admin-password=$ADMIN_PASSWORD \
    --mysql-host-socket=db \
    --mysql-username=$PROJECT_NAME \
    --mysql-password=${PROJECT_NAME}-pwd \
    --mysql-database=$PROJECT_NAME \
    --mysql-port=3306 \
    --no-interaction

# DataHub install and enable
docker-compose exec php-fpm bin/console pimcore:bundle:enable PimcoreDataHubBundle
docker-compose exec php-fpm bin/console pimcore:bundle:install PimcoreDataHubBundle
# ElasticSearch components install
docker-compose exec php-fpm bin/console pimcore:bundle:enable PimcoreEcommerceFrameworkBundle
docker-compose exec php-fpm bin/console pimcore:bundle:install PimcoreEcommerceFrameworkBundle

# Open admin dashboard at http://localhost:8080/admin/
