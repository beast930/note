<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/16
 * Time: 9:58
 */
//观oa系统知识点总结


/*
1.路由  想要设置多个路由文件\
    1).在routes文件夹中添加路由文件:web-admin.php, web-attendence.php, web-crm.php
    2).在\app\providers\routeServiceProvider.php的
    protected function mapWebRoutes()
    {
        Route::middleware('web')
        ->namespace($this->namespace)
        ->group(base_path('routes/web.php'));
    }
    中添加代码,添加以后如下:

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(function () {
                require base_path('routes/web.php');
                require base_path('routes/web-admin.php');
                require base_path('routes/web-attendance.php');
                require base_path('routes/web-crm.php');
            });
*/

/*
2.以安装Zizaco/Entrus包为例实现第三方包的扩展
    1)使用composer下载Zizaco包
    2)在config/app文件中配置
        'providers'//在 Laravel 应用程序的 config/app.php 配置文件中，providers 选项定义了应该被 Laravel 加载的服务提供者的列表。
        'aliases'
    3)使用php artisan vendor:publish //将所有第三方的配置文件拷贝过去
    4)在app\http\kernel中注册添加中间件
    5)迁移数据库 php artisan entrust:migration     php artisan migrate
*/

//3.使用保护路由

/*手动创建验证器,使用make方法
    $validator = \Validator::make($request->all(), [
        'captcha' => 'required|captcha'
    ]);
    if ($validator->fails()) {
        flash(trans('app.验证码错误'), 'danger');
        return $this->sendFailedLoginResponse($request);
    }*/

//服务容器
{
    /*use Illuminate\Contracts\Container;
    $container = Container::getInstance();//获取容器的实例
    基本:
        $instance = $container->make(MyClass::class);//Container 会自动实例化依赖的对象，所以它等同于:$instance = new MyClass(new AnotherClass());
        //如果 AnotherClass 也有 依赖，那么 Container 会递归注入它所需的依赖
    绑定接口到实现:
        interface MyInterface { }
        interface AnotherInterface {}
        然后声明实现这些接口的具体类。下面这个类不但实现了一个接口，还依赖了实现另一个接口的类实例：

        class MyClass implements MyInterface
        {
            private $dependency;

            // 依赖了一个实现 AnotherInterface 接口的类的实例
            public function __construct(AnotherInterface $dependency)
            {
                $this->dependency = $dependency;
            }
        }
        现在用 Container 的 bind() 方法来让每个 接口 和实现它的类一一对应起来：

        $container->bind(MyInterface::class, MyClass::class);
        $container->bind(AnotherInterface::class, AnotherClass::class);
        最后，用 接口名 而不是 类名 来传给 make():
        $instance = $container->make(MyInterface::class);*/

    //理解:容器依赖的如果是一个类而不是接口,则不用进行绑定,如果依赖的是一个接口,则需要进行接口与实现类的绑定,这样容器才会从接口自动找到实现类
    //https://segmentfault.com/a/1190000011560253
}

//问题:是如何重定向到loginController方法的   自带的


/*Guard 起到什么作用呢，这里可以举个栗子
Auth::check() 是判断用户是否登录的方法，如果使用的默认用户系统，那这样使用没问题。
但是使用两组用户的话，如何使用各组用户的功能呢？ Auth::guard('users')->check() 就是用来判断前台用户是否登录，而 Auth::guard('admins')->check() 就是用来判断后台用户是否登录的。
一般来说，你的 auth.php 配置文件中，会配置一个default用户组，一般为users，则使用users组用户时候不用指定guard，而使用其他组用户时候，则需要使用guard来指定使用的哪组用户。*/


/*访问指定 Guard 实例
你可以使用 Auth 门面的 guard 方法指定想要使用的 guard 实例，这种机制允许你在同一个应用中对不同的认证模型或用户表实现完全独立的用户认证。
传递给 guard 方法的 guard 名称对应配置文件 auth.php 中 guards 配置的某个键：
if (Auth::guard('admin')->attempt($credentials)) {
    //
}*/

//关联数组可以相加
/*$a = ['a'=>1, 'b'=>2];
$b = ['a'=>1, 'd'=>2];
var_dump($a+$b);//['a'=>1, 'b'=>2, 'd'=>2]*/

//$request->filled('remeber');//是否存在值且不为空


//Auth::guard()指定默认的用户组,credentials()之获取某几个字段,关联数组相加形成新数组,
//attempt([查询的关联数组], bool $remember)
/*if ($this->guard()->attempt($this->credentials($request) + ['status' => User::STATUS_ENABLE], $request->filled('remember'))) {
    return $this->sendLoginResponse($request);
}*/


//使用sms进行短信验证
1.composer require toplan/laravel-sms:~2.6
2.在config/app.php中 配置providers数组 和aliases数组
3.composer artisan vender:publish --provider=....
4.配置phpsms.php 的scheme数组....
5./**
 * <script src="{{ asset('js/laravel-sms.js') }}"></script>
    <script>
        $(function() {
        $('#sendVerifySmsButton').sms({
        token       : "{{csrf_token()}}",
        interval    : 60,
        requestData : {
        mobile : function () {
        return $('input[name=mobile]').val();
        },
        mobile_rule : 'mobile_required'
        }
        });
        });
    </script>
 */


