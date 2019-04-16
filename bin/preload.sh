#!/bin/bash

SERVER_MANAGE_PORT=8001

i=0
max_retry=100
while [ 1 ]
do
    count=`curl -Ss "http://127.0.0.1:$SERVER_MANAGE_PORT/health" | awk -F, '{print $1}' | awk -F: '{print $2}' | grep UP | wc -l`
    if [ $count -eq 1 ];then
        echo "healthCheck ok"
        exit 0
    fi
    sleep 7s
    i=`expr $i + 1`
    if [ $i -eq $max_retry ];then
        echo "healthCheck failed,exit"
        exit 1
    fi
done