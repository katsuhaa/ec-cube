#/bin/bash -x
# このファイルのカレントディレクトリ移動
cd `dirname $0`
docker-compose -f docker-compose.nachu.yml exec -u root ec-cube tar -cvf /mnt/s3/nachumaru-upload.tar.bz2 -C /var/www/html/html upload
if [ $? -ne 0 ]; then
    exit 1
fi
docker-compose -f docker-compose.nachu.yml exec -u root ec-cube tar -cvf /mnt/s3/nachumaru-user_data.tar.bz2 -C /var/www/html/html user_data
if [ $? -ne 0 ]; then
    exit 1
fi

# database アクセス用コンフファイルの作成
docker-compose -f docker-compose.nachu.yml exec -u root mysql bash /root/nachumaru-mysql-backupconf.sh
if [ $? -ne 0 ]; then
    exit 1
fi

# database バックアップ
docker-compose -f docker-compose.nachu.yml exec -u root mysql bash /root/nachumaru-mysql-backup.sh
if [ $? -ne 0 ]; then
    exit 1
fi

echo "Backup complete"




