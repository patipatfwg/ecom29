#!/bin/sh
DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
echo $DIR

cp -n docker-compose.yml.template docker-compose.yml

# Delete existing images and rebuild if --clean is specified
while [ $# -gt 0 ]
do
    case $1 in
        --clean )   shift
                    docker-compose down --rmi all -v
                    ;;
    esac
    shift
done

docker-compose up -d

# Seed data to mongo
docker exec -it makro-ecommerce-admin-mongo bash /start_script.sh

# Run composer install only when ../vendor does not exist
if [ ! -d ../vendor ]; then
  docker exec -it makro-ecommerce-admin-web composer install
fi

sleep 3
docker exec -it makro-ecommerce-admin-web bash /start_script.sh

# Laravel
chmod -R 777 $DIR/../storage