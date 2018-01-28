#!/bin/bash
IP_LOCAL="$(hostname -I | xargs)"
PUERTO="8000"
TARGET="$IP_LOCAL:$PUERTO"
/usr/bin/php -S $TARGET
