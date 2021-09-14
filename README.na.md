
# nachumaru用
docker-composeで開発する場合の開発　設定方法

## DockerFile
今のところSSL認証の設定で正式な認証ファイルが作れないため、
apache2の000-default.conf default-ssl.conf をコピーしないようにしなければなりません。
sslを無効にする場合はDockerfile.nosslをコピーしてつかってください
gitにDockerfileがコミットされているのでちょっとめんどくさいですが。。
バージョンアップ時に対応が必要になります　これなんとかしないと。。。

## カスタマイズはnachumaru-dataのディレクトリ
templateはnadesignのディレクトリにまとまっていて、本番、開発時にtemplate/defaultにマウントするようにしています。
nadesignをtarしてデザインテンプレートにあげればデザインできるはず



