#!/bin/bash
source /etc/apache2/envvars
/usr/sbin/apache2 -DFOREGROUND
echo '192.168.120.174 alpha-api-makro-cdn.eggdigital.com' >> /etc/hosts
echo '192.168.120.174 alpha-dynamic-makro-cdn.eggdigital.com' >> /etc/hosts
echo '192.168.120.174 alpha-admin-makro-cdn.eggdigital.com' >> /etc/hosts