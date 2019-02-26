#!/bin/bash

cd `dirname $0`

php -S localhost:18888 -t ../public ../public/index.php

