#/bin/bash -x
# eccube mysql部分のバックアップ
# パスワード自動化のために.my.cnfを作成している
cd `dirname $0`
echo -e "[client]\nuser = ${MYSQL_USER}\npassword = ${MYSQL_PASSWORD}\n" > .my.cnf
echo -e "#!/bin/bash -x\ncd `dirname $0`\nmysqldump --no-tablespaces ${MYSQL_DATABASE} > /mnt/s3/nachumaru-database-backup" > nachumaru-mysql-backup.sh
chmod +x nachumaru-mysql-backup.sh
echo -e "#!/bin/bash -x\ncd `dirname $0`\ncat /mnt/s3/nachumaru-database-backup | mysql ${MYSQL_DATABASE}" > nachumaru-mysql-restore.sh
chmod +x nachumaru-mysql-restore.sh



