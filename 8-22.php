composer.json 管理composer依赖

1.安装依赖
package.json 管理node.js依赖 它包括了运行基本的 Mix 所需的内容
安装 Laravel Mix               npm install
运行 Mix                       npm run dev
在你的终端里持续运行             npm run watch-poll

2.编译
webpack.mix.js 是文件编译的入口,编译后映射到对应的目录里面

安装依赖-》在scss中import bootstrap -》使用npm run dev 进行编译（编译scss，按webpack映射目录）
可在webpack中添加响应的代码,使编译后进入public目录

yarn 与 npm

sass是编写css语言，写在scss中

NPM 是 Node.js 的包管理和任务管理工具，
Yarn 是用于替代现有的 NPM 客户端或者其他兼容 NPM 仓库的包管理工具
Laravel Mix 一款前端任务自动化管理工具

asset('public之后的路径')// asset函数自动定位到public文件

artisan make:controller ProjectsController --resource //将路由resource对应的方法写在控制器中