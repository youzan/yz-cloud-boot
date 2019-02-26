#!/bin/bash

cd `dirname $0`

if [ "$1" = "-d" ]; then
    php -dxdebug.remote_enable=1 -dxdebug.remote_mode=req -dxdebug.remote_port=9000 -dxdebug.remote_host=127.0.0.1 -S localhost:18888 -t ../public ../public/index.php
else
    php -S localhost:18888 -t ../public ../public/index.php
fi

