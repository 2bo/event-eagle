#!/usr/bin/env bash

# This script for amazon linux2 production environment
echo "started_at: `date`"

SITES=(connpass doorkeeper)

# 3ヶ月先までのイベントを取得
i=0
while [ $i -lt 3 ]
do
    for site in ${SITES[@]}; do
        ym=`date --date "$i month" +%Y%m`
        echo "php artisan fetch:$site $ym"
        php /var/www/event-eagle/src/artisan fetch:$site $ym
        php /var/www/event-eagle/src/artisan tagging:event
    done
    let i++
done
echo "php artisan tagging:event"

echo "finished_at: `date`"
