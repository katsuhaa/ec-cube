
# nachumaru用
docker-composeをローカルで開発する場合の設定方法

## DockerFile
今のところSSL認証の設定で正式な認証ファイルが作れないため、
apache2の000-default.conf default-ssl.conf をコピーしないようにしなければなりません。
sslを無効にする場合はDockerfile.nosslをコピーしてつかってください
gitにDockerfileがコミットされているのでちょっとめんどくさいですが。。
バージョンアップ時に対応が必要になります　これなんとかしないと。。。

とりあえずdocker-compose -f docker-compose.nachu.local.ymlを起動すればOK
ローカルの場合、何も設定していないと思うので管理画面は admin password

## カスタマイズはnachumaru-dataのディレクトリ
templateはnadesignのディレクトリにまとまっていて、本番、開発時にtemplate/defaultにマウントするようにしています。
nadesignをtarしてデザインテンプレートにあげればデザインできるはず



