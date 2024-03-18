#!/bin/sh
set -e

ANSI_GREEN='\033[0;32m'
ANSI_RESET='\033[0m'

symbols="⠋ ⠙ ⠹ ⠸ ⠼ ⠴ ⠦ ⠧ ⠇ ⠏"
index=1

while :; do
  if mysql -h"mysql" -u${MYSQL_USER} -p${MYSQL_PWD} -e 'SELECT 1' > /dev/null 2>&1; then
    break
  fi

  for i in $(seq 1 10); do
    symbol=$(echo "$symbols" | cut -d ' ' -f $index)
    >&2 printf "\r %s Waiting for MySQL" "$symbol"
    index=$((index % 10 + 1))
    sleep .1
  done
done

>&2 echo "\r ${ANSI_GREEN}✔${ANSI_RESET} MySQL is ready   "
