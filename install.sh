#!/bin/bash

UIDVAL=$(id -u)
GIDVAL=$(id -g)
PROJECT_NAME=php-beauty
ADMIN_USERNAME=scn-admin
ADMIN_PASSWORD=scn-pwd

docker run -u $UIDVAL:$GIDVAL --rm -v `pwd`:/var/www/html pimcore/pimcore:PHP8.1-fpm composer create-project pimcore/skeleton $PROJECT_NAME

# Changing database credentionals
# Changing uid and gid
# Changing nginx port mapping
sed -i -e "s/MYSQL_ROOT_PASSWORD=ROOT/MYSQL_ROOT_PASSWORD=${PROJECT_NAME}-pwd/" \
    -e "s/MYSQL_DATABASE=pimcore/MYSQL_DATABASE=${PROJECT_NAME}/" \
    -e "s/MYSQL_USER=pimcore/MYSQL_USER=${PROJECT_NAME}/" \
    -e "s/MYSQL_PASSWORD=pimcore/MYSQL_PASSWORD=${PROJECT_NAME}-pwd/" \
    -e "s/#user: '1000:1000' # set to your uid:gid/user: '$UIDVAL:$GIDVAL'/" \
    -e 's/"80:80"/"8080:80"/' \
    -e "/^volumes.*/i\    elasticsearch:\n \
         user: '$UIDVAL:$GIDVAL'\n \
         image: docker.elastic.co/elasticsearch/elasticsearch:8.2.3\n \
         container_name: elasticsearch\n \
         environment:\n \
            - xpack.security.enabled=false\n \
            - discovery.type=single-node\n \
         ulimits:\n \
            memlock:\n \
                soft: -1\n \
                hard: -1\n \
         volumes:\n \
            - elasticsearch-data:/usr/share/elasticsearch/data\n \
         ports:\n \
            - 9200:9200\n \
            - 9300:9300\n \
         depends_on:\n \
            - php-fpm\n" $PROJECT_NAME/docker-compose.yaml

echo '    elasticsearch-data:' >> $PROJECT_NAME/docker-compose.yaml

cd $PROJECT_NAME
docker-compose up -d
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
docker-compose exec php-fpm composer require pimcore/data-hub
docker-compose exec php-fpm bin/console pimcore:bundle:enable PimcoreDataHubBundle
docker-compose exec php-fpm bin/console pimcore:bundle:install PimcoreDataHubBundle
docker-compose exec php-fpm composer require elasticsearch/elasticsearch
docker-compose exec php-fpm bin/console pimcore:bundle:enable PimcoreEcommerceFrameworkBundle
docker-compose exec php-fpm bin/console pimcore:bundle:install PimcoreEcommerceFrameworkBundle


# Open admin dashboard at http://localhost:8080/admin/
