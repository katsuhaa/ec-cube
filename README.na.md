
# nachumaru用
docker-composeをローカルで開発する場合の設定方法

## DockerFile
とりあえずdocker-compose -f docker-compose.nachu.local.ymlを起動すればOK
ローカルの場合、何も設定していないと思うので管理画面は admin password

## mail catcher
送信したメールをローカルで見えるようにするツール
localhost:1080 でアクセスして中身みる
.envに設定があるとdocker-compose.ymlのenvironment設定がうごかいないっぽい

# nachumaruでカスタマイズしてるときのエトセトラ
## カスタマイズはnachumaru-dataのディレクトリ
templateはnadesignのディレクトリにまとまっていて、本番、開発時にtemplate/defaultにマウントするようにしています。
nadesignをtarしてデザインテンプレートにあげればデザインできるはず

## ec-cubeの管理画面からのカスタマイズ　css jsについて
2022/1/8
この部分はgitで管理されているため一時的に変更はできるけどdockerの立ち上げ直しで元に戻ります。
どうしましょう？？　

## バックアップ方法をちゃんと定義しないと危ないぞ
2022/1/8
ec2でバックアップしているので全体は残っているけどちゃんと考えた方がいいでしょうね。

## nachumaru-webからメールを送るには
s-nailをインストール して smtpを指定しておくる
```
$ sudo apt get install s-nail
$ echo "test mail" | s-nail -s "Mail title" -S "smtp=smtp://172.31.46.184:25" -r "webmonkey@nachumaru.com" "monkey.pliers@gmail.com"
```
mail serverはweb serverのipアドレスを信頼させているから適当に送るので大丈夫なのですよ











