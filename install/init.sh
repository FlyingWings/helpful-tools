#!/bin/bash
location=`pwd`

com_pos=$location"/../composer.phar"


if [ ! -f "$com_pos" ]; then
    echo "Composer Downloading...\n"
    cd "$location/../" && curl -sS https://getcomposer.org/installer | php
else
    echo "Composer Existed\n"
fi


cd "$location/../" && php composer.phar install




