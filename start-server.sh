#!/bin/bash
IP_LOCAL="$(hostname -I | xargs)"
PUERTO="8080"
TARGET="$IP_LOCAL:$PUERTO"
/usr/bin/php -S $TARGET
