#!/bin/sh

# import db
MYSQL_PWD=${MYSQL_PASSWORD} mysql -u ${MYSQL_USER} -h mysql < /app/db/data.sql

# start apache
apache2ctl -D BACKGROUND

# sleep
sleep infinity
