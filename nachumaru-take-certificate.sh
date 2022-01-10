#!/bin/sh -x
# 証明書取得用スクリプト dockerの中で証明書を取得させる
cd `dirname $0`
#強制的に再取得する場合は↓ ちなみに--force-renewal
#docker-compose -f docker-compose.nachu.yml exec ec-cube certbot certonly --force-renewal --webroot -w /var/www/html -d www.nachumaru.com -m monkey.pliers@gmail.com --agree-tos -n
# これはドライラン
#docker-compose -f docker-compose.nachu.yml exec ec-cube certbot certonly --dry-run --webroot -w /var/www/html -d www.nachumaru.com -m monkey.pliers@gmail.com --agree-tos -n
docker-compose -f docker-compose.nachu.yml exec ec-cube certbot certonly --webroot -w /var/www/html -d www.nachumaru.com -m monkey.pliers@gmail.com --agree-tos -n
if [ $? = 0 ]; then
    docker cp `docker-compose ps -q ec-cube`:/etc/letsencrypt ./nachumaru-data/
fi
cd -

