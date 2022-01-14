#/bin/bash -x
# このファイルのカレントディレクトリ移動
cd `dirname $0`
tar -xvf /mnt/s3/nachumaru-upload.tar.bz2
if [ $? -ne 0 ]; then
    exit 1
fi
tar -xvf /mnt/s3/nachumaru-user_data.tar.bz2
if [ $? -ne 0 ]; then
    exit 1
fi


# database アクセス用コンフファイルの作成
docker cp nachumaru-mysql-setconf.sh `docker-compose -f docker-compose.nachu.yml ps -q mysql`:/root/
if [ $? -ne 0 ]; then
    exit 1
fi
docker-compose -f docker-compose.nachu.yml exec -u root mysql bash /root/nachumaru-mysql-setconf.sh
if [ $? -ne 0 ]; then
    exit 1
fi

#docker-compose -f docker-compose.nachu.yml exec -u root mysql bash /root/nachumaru-mysql-backup.sh
if [ $? -ne 0 ]; then
    exit 1
fi



