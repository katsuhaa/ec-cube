#!/bin/sh -x
# 証明書取得用スクリプト dockerの中で証明書を取得させる
cd `dirname $0`
docker-compose -f docker-compose.nachu.yml exec ec-cube certbot certonly --webroot -w /var/www/html -d www.nachumaru.com -m monkey.pliers@gmail.com --agree-tos -n
cd -

