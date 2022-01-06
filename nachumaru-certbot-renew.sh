#!/bin/sh -x
# dockerの中で証明書の更新をするスクリプト
# コマンドはシェルがないので絶対パス ttyがないため docker-compose exec に-Tオプション追加
cd `dirname $0`
/usr/local/bin/docker-compose -f docker-compose.nachu.yml exec -T ec-cube certbot renew
/usr/local/bin/docker-compose -f docker-compose.nachu.yml exec -T apachectl restart
cd -

