Admin system for makro ecommerce
# Setup
## Start servers
After cloning this repository, issue following commands:
```
cd <path_to_makro_ecommerce_admin>/docker
./start_server.sh
```
If you run this command for the first time, it will build images for web and mongo containers and seeding the mongo container with some initial data. Things should work out of the box and ecommerce admin website will be available at [http://localhost:8054]() try loggin in using _username = admin_ and _password = admin_
## Rebuild server images
After running `./start_server.sh`, subsequence calls to this command will start containers using the existing images. If you make changes in either docker/web or docker/mongo dockerfile and want to rebuild container's images, use `./start_server.sh --clean` command. This will delete existing images and rebuild from
## Using external API
Default configuration starts web and mongo container without starting any API containers. If you want to connect to real API, follow this instructions: 

1. Clone API you want to use from [API Repository](https://bitbucket.org/account/user/dev2-egg/projects/MKO)

2. Following instruction in repository and start API container

3. Edit _<path_to_makro_ecommerce_admin>/docker/docker-compose.yml_ if this file is not available just duplicate from _docker-compose.yml.template_. Uncomment containers you want to use in _external_links_ section
# Troubleshoot
## Composer timeout
Depends on connection speed, `composer install` might be timeouted. If this occured when running `./start_server.sh`. Increase _config.process\_timeout_ in _composer.json_ to suit your need and run
`docker exec -it makro-ecommerce-admin-web composer install` to re-run `composer install`. Please note that `./start_server.sh` will not re-run `composer install` once the _vendor_ directory exists at project root.
## makro-ecommerce-admin-mongo exit immediately
On OSX _makro-ecommerce-admin-mongo_ might exit immediately and cause _makro-ecommerce-admin-web_ to fail to start because it cannot connect to a linked container. If you experience this issue try clearing docker volumes with following command:
`docker volume rm $(docker volume ls -qf dangling=true)`