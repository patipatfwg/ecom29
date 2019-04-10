FROM eggdevop/docker-apache-php:php7

RUN mkdir /data
RUN mkdir /data/log
RUN mkdir /data/log/admin

RUN chmod -R 777 /data

ADD sites-enabled/vhost.conf /etc/apache2/sites-enabled/

# Set Composer OAuth Config
RUN COMPOSER_AUTH=auth.json

# Set Log Path
RUN mkdir /var/log/admin
RUN chmod 777 /var/log/admin