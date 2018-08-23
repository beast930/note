<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/14
 * Time: 8:55
 */

//Request
{
    $request->has();//判断是否存在该参数
    $request->only();
    $request->except('_token');//‘_token'字段排除

    //闪存操作
    $request->flash();//闪存  配合模板中{{old()}}的使用
    $request->flashExcept('password');//除了密码其他写入闪存

    return redirect()->back()->with('errors', '长度应大于5')->withInput(
        $request->except('password')
    );//效果与flashExcept相同

    //文件上传操作
    public function file(Request $request){
    if ($request->isMethod('post')){
        if ($request->file('file')->isVaild()){//判断是否上传了文件
            $ext = $request->file('file')->getClientOriginalExtension();//获取文件后缀
            $newFileName = time().rand().$ext;//形成新的文件
            $request->file('file')->move('./uploads', $newFileName);//将文件移到指定的目录
        }else{
            return redirect()->back();
        }
    }
    return view('test.form');
    }


    //config/filesystem文件进行配置 storage_path是存在storage目录下的
    $file = $request->file('file');
    if ($file->isValid()){
        $ext = $file->getClientOriginalExtension();
        $realPath = $file->getRealPath();//获取临时路径的绝对路径
        $newFileName = time().rand().'.'.$ext;
        $bool = Storage::disk('local')->put($newFileName, file_get_contents($realPath));//在'local'磁盘下 配置文件详见config/filesystem
        var_dump($bool);
    }




    //cookie
    public function setCookie(Request $request){
        return response('hello')->cookie('k1', 'v1', 1000);//方法1
        \Cookie::queue('k2', 'v2', 10000);//方法2
    }

    public function getCookie(Request $request){
        echo $request->cookie('k1');//方法1
        echo \Cookie::get('k2');//方法2
    }
}
//Laravel中的用户认证
{
    php artisan make:auth
    php artisan migrate //数据迁移
}

//数据迁移
{
    php artisan make:migration create_students_table --create=students//生成迁移文件
    php artisan migrate//执行迁移文件
    php artisan migrate:refresh

    Schema::create('students', function (Blueprint $table) {
        $table->increments('id')->unsigned();
        $table->string('name', 255)->nullable(false)->default('')->comment('姓名');//name varchar(255) not null default '' comment '姓名';
        //$table->char('name', 255)->nullable(false)->default('')->comment('姓名');// char(255)
        $table->integer('age')->unsigned()->nullable(false)->default(0)->comment('年龄');
        $table->integer('sex')->unsigend()->nullable()->default(10)->comment('性别');
        $table->integer('created_at')->nullable(false)->default(0)->comment('新增时间');
        $table->integer('updated_at')->nullable(false)->default(0)->comment('修改时间');
    });
}

//数据填充
{
    //php artisan make:seeder StudentsTableSeeder;//生成填充文件
    //php artisan db:seed --class=StudentsTableSeeder //进行数据填充
    //在DatabaseSeeder中://可进行批量的将各个填充类一起执行
        public function run()
        {
            // $this->call(UsersTableSeeder::class);
            $this->call(StudentsTableSeeder::class);
        }

    //StudentsTableSeeder:
        public function run()
        {
            DB::table('students')->insert([
                ['name'=>'a1', 'age'=>13, 'sex'=>20, 'created_at'=>time(), 'updated_at'=>time()],
                ['name'=>'a2', 'age'=>18, 'sex'=>10, 'created_at'=>time(), 'updated_at'=>time()]
            ]);
        }

}

//数据缓存
{
    public function setCache(){
        Cache::put('c1', 'v1', 1000);//添加
        var_dump(Cache::add('c1', 'v2', 1000));//添加,若已存在则报错
        Cache::forever('c2', 'v2');//添加,无过期
        var_dump(Cache::has('c2'));//是否存在
    }

    public function getCache(){
        echo Cache::get('c1');//获取
        echo Cache::get('c2');
        echo Cache::pull('c2');//获取一次后,删除
        echo Cache::forget('c2');//删除
    }
}

//发送邮件
{
    //记得查看config/mail.php 和修改.env文件
    public function mailTest(){
        //第一种:
        /*\Mail::raw('hello', function ($message){
            $message->from('13676143049@163.com', 'wangyingjie');
            $message->subject('test');//主题
            $message->to('930055912@qq.com');
        });*/

        //第二中:
        \Mail::send('test.form','', function ($message){
            $message->subject('test');//主题
            $message->to('930055912@qq.com');
        });
    }
}

//队列运用
{
    /**
     * 1.修改.env文件QUEUE_DRIVER=database 运用数据库
     * 2. php artisan queue:table //创建迁移文件create_jobs_table
     * 3. php artisan migrate//执行迁移文件 创建数据库
     * 4. php artisan make:job SendEmail //创建任务类
     * 5. 在任务类的handle方法中实现要执行的语句
     *      public function handle()
            {
                \Mail::raw('进行队列测试', function($message){
                $message->subject('队列测试');
                $message->to($this->email);
                });
            }
     * 6. 在控制器的方法
     *      public function queueMail(){
                dispatch(new SendEmail('930055912@qq.com'));
            }
     * 7. 建立失败的迁移文件  php artisan queue:fail-table
     * 8. 创建失败迁移数据库  php artisan migrate
     * 9. php artisan queue:failed //显示错误的任务数据
     * 10. php artisan queue:retry 'id号'/all
     * 11. 删除一条错误任务: php artisan queue:forget 'id号'
     *     删除全部错误任务:php artisan queue:flush
     */
}

//csrf保护
{
    //在表单中
        {{csrf_field()}}
    //csrf白名单
        //在中间件VerifyCsrfToken中的except属性添加不用csrf的路由名
        protected $except = [
            '/test/*'
        ];
}
