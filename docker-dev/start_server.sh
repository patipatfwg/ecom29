#!/bin/sh
docker rm -f makro_ecommerce_admin

docker-compose rm

docker-compose build makro_ecommerce_admin
docker-compose up -d makro_ecommerce_admin


sleep 5
docker exec -it makro_ecommerce_admin sh /etc/init.d/apache2 start


sleep 3

docker exec -it makro_ecommerce_admin bash /var/www/html/docker/web/start_script.sh

cd /var/log
mkdir admin
chmod -R 777 /var/log/admin