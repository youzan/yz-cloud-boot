#!/bin/bash

SERVER_MANAGE_PORT=8001

i=0
max_retry=10
while [ 1 ]
do
    health=`curl -Ss "http://127.0.0.1:$SERVER_MANAGE_PORT/health" | awk -F, '{print $1}' | awk -F: '{print $2}' | grep UP | wc -l`
    daemon=`ps -ef|grep "daemon.php start"|wc -l`
    if [ $health -eq 1 ] && [ $daemon -gt 1 ];then
        echo "healthCheck ok"
        exit 0
    fi
    sleep 6s
    i=`expr $i + 1`
    if [ $i -eq $max_retry ];then
        echo "healthCheck failed,exit"
        exit 1
    fi
done