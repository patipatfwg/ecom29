#!/bin/bash
until nc -z localhost 27017
do
    sleep 1
done

echo "Start mongo container"

    mongoimport --db makro --collection users --drop --file /data/db_import/users.json
    mongoimport --db makro --collection menus --drop --file /data/db_import/menus.json
    mongoimport --db makro --collection positions --drop --file /data/db_import/positions.json

echo "End mongo container"
