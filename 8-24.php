//使用tortoisegit完成合并冲突的工作
1.将已修改的commit到本地
2.从远程仓库pull下来 提示冲突-->点击解决-->点击确定  //产生合并的冲突文件
3.对冲突文件进行修改 commit本地
此时 一个解决了的冲突文件已经放在本地仓库了

//解决git clone 过慢
git config --global http.proxy http://127.0.0.1:34828
git config --global https.proxy https://127.0.0.1:34828
//解除
git config --global --unset http.proxy
git config --global --unset https.proxy

/********************************************************/
从github clone的代码使用流程:
$ composer install
完成后，我们需要建立.env文件，因为.env默认是github所忽略的文件：

$ cp .env.example .env
因为env.example中默认没有app key，所以我们在.env中生成新的app key：

$ php artisan key:generate
接下来打开我们刚复制的.env文件，将数据库信息填入相应的位置：

APP_ENV=local
APP_KEY=base64:H6RIhyLBY-SOME-KEY-HERE-FkzCvGdS8WOU=
APP_DEBUG=true
APP_LOG_LEVEL=debug
APP_URL=http://localhost
 
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=my_dbname
DB_USERNAME=homestead
DB_PASSWORD=secret
保存后，运行

$ php artisan migrate
进行数据库迁移，如果有seeder的话，运行

$ php artisan db:seed
进行seeding即可。

需要注意的是，原始项目数据库里的数据仍然需要自行拷贝。
