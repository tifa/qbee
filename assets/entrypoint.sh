#!/bin/sh
set -e

# import db
mysql -u ${MYSQL_USER} -h ${MYSQL_HOST} -p${MYSQL_PASSWORD} -e "SOURCE /app/db/data.sql"

# start apache
apache2ctl -D BACKGROUND

# sleep
sleep infinity
