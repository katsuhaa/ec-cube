
# nachumaru用
docker-composeをローカルで開発する場合の設定方法

## DockerFile
とりあえずdocker-compose -f docker-compose.nachu.local.ymlを起動すればOK
ローカルの場合、何も設定していないと思うので管理画面は admin password

## カスタマイズはnachumaru-dataのディレクトリ
templateはnadesignのディレクトリにまとまっていて、本番、開発時にtemplate/defaultにマウントするようにしています。
nadesignをtarしてデザインテンプレートにあげればデザインできるはず

## mail catcher
送信したメールをローカルで見えるようにするツール
localhost:1080 でアクセスして中身みる
.envに設定があるとdocker-compose.ymlのenvironment設定がうごかいないっぽい




