<?php
设置可响应多个http请求
	Route::match(['get', 'post'], '/', function(){});
或
	Route::any('/', function(){});

CSRF 保护  在表单中{{ csrf_field() }}

重定向路由
	Route::redirect('/a', '/b', 301);
视图路由
	Route::view('/welcome', '视图名', ['key'=>'value']);
路由参数
	Route::get('user/{id}', function($id){return ...});
多个参数
	Route::get('posts/{post}/comments/{comment}', function($post, $comment){}});
可选参数
	Route::get('user/{id?}', function($id = 'root'){});
正则表达式约束
	Route::get('user/{id}', function($id){})->where('id', '[a-zA-Z]+');
	Route::get('user/{id}/{name}', function($id, $name){})->where(['id'=>'[a-zA-Z]+', 'name'=>'[a-zA-Z]+']);
全局约束
	在app/Providers/RouteServiceProvider中的boot方法中
	public function boot()
	{
	    Route::pattern('id', '[0-9]+');
	    parent::boot();
	}
	定义好之后，便会自动应用到所有使用该参数名称的路由
命名路由
	Route::get('user/profile', 'UserController@showProfile')->name('profile');
为命名路由生成链接
	Route::get('user/{id}/profile', function ($id) {})->name('profile');
	$url = route('profile', ['id' => 1]);
检查当前路由
	在中间件当中
	public function handle($request, Closure $next)
	{
	    if ($request->route()->named('profile')) {};
	    return $next($request);
	}
中间件与路由组的结合
	Route::middleware(['first', 'second'])->group(function(){
		Route::get('/', function(){});//使用了first和second的中间件
		Route::get('/user', function(){});//使用了first和second的中间件
	});
命名空间
	Route::namespace('Admin')->group(function(){
		Route::namespace('Admin\admin')->group(function(){});
	});
	/*当控制器的命名空间很长,可以将命名空间前一部分放在namespace中,上例到达App\Http\Controllers\Admin
	也有到达App\Http\Controllers\Admin\admin下的
	*/

再次在浏览器中访问http://laravel.app:8000/testNamedRoute时会跳转到http://laravel.app:8000/hello/laravelacademy
	Route::get('/testNamedRoute',function(){
	    return redirect()->route('academy');//若return route('academy')只是返回url地址而已
	});

子域名
	子域名可以通过domain关键字来设置：
	Route::domain('{service}.laravel.app')->group(function(){
	    Route::get('/write/laravelacademy',function($service){
	        return "Write FROM {$service}.laravel.app";
	    });
	});
	这样我们在浏览器中访问http://write.laravel.app:8000/write/laravelacademy，则输出Write FROM write.laravel.app

路由前缀
	Route::prefix('admin')->group(function () {
	    Route::get('users', function () {
	        // 匹配包含 "/admin/users" 的 URL
	    });
	});

隐式绑定
	{users}与变量名$users相同,且类型为App\User, 此时会发生自动注入
	Route::get('api/user/{users}', function(App\User $users))
	}
	自定义键名
		想绑定检索的时候的键名,可以在Eloquent模型的方法中重写getRouteKeyName(){return '自定义键名'}

显示绑定
	RouteServiceProvider中的boot方法中 使用Route::model('user', App\User::class);
	Route::get('profile/{user}', function (App\User $user) {});
	自定义逻辑
		同样在boot方法中
		Route::bind('user', function($value){return \App\User::where('name', $value)->find(2);});

表单伪造
	<inpu type='hidden' name='_method' value='PUT'>//伪造成PUT方法
	<input type='hidden' name='_token' value="csrf_token()">


/************************************************/
中间件
	Http/Middleware下的部件
	php artisan make:middleware CheckToken 生成Checktoken中间件文件
	理解中间件的最好方式就是将中间件看做 HTTP 请求到达目标动作之前必须经过的“层”，每一层都会检查请求并且可以完全拒绝它。
	1.在handle(Request $request, Closure $next)函数中添加判断
	2.在app/Http/Kernel.php的数组中以 'test' => \App\Http\Middleware\CheckToken::class添加
	3.Route::get('/', function () {\/\/})->middleware('token');//可以使用 middleware 方法将其分配到路由
	也可分配多个
		Route::get('/', function(){})->middleware('first', 'second');

中间件组
	在app/Http/Kernel.php提供的 $middlewareGroups 属性实现。
	web 和 api 两个中间件组
	默认情况下， RouteServiceProvider 自动将中间件组 web 应用到 routes/web.php 文件，将中间件组 api 应用到 routes/api.php：
	可以在http kernel里面
		protected $middlewareGroups = [
		    ........
		    'blog' => [
		        'token',
		    ]
		];
	在web.php中:
		Route::group(['middleware'=>['blog']], function(){
			Route::get('/', function(){});
			Route::view('/view', 'welcome', ['key'=>'value']);
		});
前置&后置 中间件
Terminable 中间件 有疑问

/****************************************************************/
控制器
	单个行为控制器,使用__invoke(),则无需在web.php中写方法名

控制器中间件
	将$this->middleware()放在构造函数中使用
	public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('log')->only('index');
        $this->middleware('subscribed')->except('store');
    }
使用闭包来为控制器注册中间件
	$this->middleware(function($request, $next){
		return $next($request);
	});

资源控制器
	Route::resource('photos', 'PhotosController');
	指定资源模型:php artisan make:controller PhotoController --resource --model=Photo
	伪造表单方法:{{ method_field('PUT') }}直接在视图中生成<input type="hidden" name="_method" value="PUT">
	部分资源路由:Route::resource('photos', 'PhotosController', ['only'=>['first', 'second']]);//只使用first,second方法
	Route::resource('photos', 'PhotosController', ['except'=>['first', 'second']]);//除了first,second方法
API资源路由
命名资源路由
	Route::resource('user', 'AdminUserController', ['parameters' => [
	    'user' => 'admin_user'
	]]);------------------>/user/{admin_user}
命名资源路由参数
本地化URI
	public function boot()
	{
	    Route::resourceVerbs([
	        'create' => 'crear',
	        'edit' => 'editar',
	    ]);
	}//使得路由变成 /user/crear, /user/editar
=======
<?php
设置可响应多个http请求
	Route::match(['get', 'post'], '/', function(){});
或
	Route::any('/', function(){});

CSRF 保护  在表单中{{ csrf_field() }}

重定向路由
	Route::redirect('/a', '/b', 301);
视图路由
	Route::view('/welcome', '视图名', ['key'=>'value']);
路由参数
	Route::get('user/{id}', function($id){return ...});
多个参数
	Route::get('posts/{post}/comments/{comment}', function($post, $comment){}});
可选参数
	Route::get('user/{id?}', function($id = 'root'){});
正则表达式约束
	Route::get('user/{id}', function($id){})->where('id', '[a-zA-Z]+');
	Route::get('user/{id}/{name}', function($id, $name){})->where(['id'=>'[a-zA-Z]+', 'name'=>'[a-zA-Z]+']);
全局约束
	在app/Providers/RouteServiceProvider中的boot方法中
	public function boot()
	{
	    Route::pattern('id', '[0-9]+');
	    parent::boot();
	}
	定义好之后，便会自动应用到所有使用该参数名称的路由
命名路由
	Route::get('user/profile', 'UserController@showProfile')->name('profile');
为命名路由生成链接
	Route::get('user/{id}/profile', function ($id) {})->name('profile');
	$url = route('profile', ['id' => 1]);
检查当前路由
	在中间件当中
	public function handle($request, Closure $next)
	{
	    if ($request->route()->named('profile')) {};
	    return $next($request);
	}
中间件与路由组的结合
	Route::middleware(['first', 'second'])->group(function(){
		Route::get('/', function(){});//使用了first和second的中间件
		Route::get('/user', function(){});//使用了first和second的中间件
	});
命名空间
	Route::namespace('Admin')->group(function(){
		Route::namespace('Admin\admin')->group(function(){});
	});
	/*当控制器的命名空间很长,可以将命名空间前一部分放在namespace中,上例到达App\Http\Controllers\Admin
	也有到达App\Http\Controllers\Admin\admin下的
	*/

再次在浏览器中访问http://laravel.app:8000/testNamedRoute时会跳转到http://laravel.app:8000/hello/laravelacademy
	Route::get('/testNamedRoute',function(){
	    return redirect()->route('academy');//若return route('academy')只是返回url地址而已
	});

子域名
	子域名可以通过domain关键字来设置：
	Route::domain('{service}.laravel.app')->group(function(){
	    Route::get('/write/laravelacademy',function($service){
	        return "Write FROM {$service}.laravel.app";
	    });
	});
	这样我们在浏览器中访问http://write.laravel.app:8000/write/laravelacademy，则输出Write FROM write.laravel.app

路由前缀
	Route::prefix('admin')->group(function () {
	    Route::get('users', function () {
	        // 匹配包含 "/admin/users" 的 URL
	    });
	});

隐式绑定
	{users}与变量名$users相同,且类型为App\User, 此时会发生自动注入
	Route::get('api/user/{users}', function(App\User $users))
	}
	自定义键名
		想绑定检索的时候的键名,可以在Eloquent模型的方法中重写getRouteKeyName(){return '自定义键名'}

显示绑定
	RouteServiceProvider中的boot方法中 使用Route::model('user', App\User::class);
	Route::get('profile/{user}', function (App\User $user) {});
	自定义逻辑
		同样在boot方法中
		Route::bind('user', function($value){return \App\User::where('name', $value)->find(2);});

表单伪造
	<inpu type='hidden' name='_method' value='PUT'>//伪造成PUT方法
	<input type='hidden' name='_token' value="csrf_token()">


/************************************************/
中间件
	Http/Middleware下的部件
	php artisan make:middleware CheckToken 生成Checktoken中间件文件
	理解中间件的最好方式就是将中间件看做 HTTP 请求到达目标动作之前必须经过的“层”，每一层都会检查请求并且可以完全拒绝它。
	1.在handle(Request $request, Closure $next)函数中添加判断
	2.在app/Http/Kernel.php的数组中以 'test' => \App\Http\Middleware\CheckToken::class添加
	3.Route::get('/', function () {\/\/})->middleware('token');//可以使用 middleware 方法将其分配到路由
	也可分配多个
		Route::get('/', function(){})->middleware('first', 'second');

中间件组
	在app/Http/Kernel.php提供的 $middlewareGroups 属性实现。
	web 和 api 两个中间件组
	默认情况下， RouteServiceProvider 自动将中间件组 web 应用到 routes/web.php 文件，将中间件组 api 应用到 routes/api.php：
	可以在http kernel里面
		protected $middlewareGroups = [
		    ........
		    'blog' => [
		        'token',
		    ]
		];
	在web.php中:
		Route::group(['middleware'=>['blog']], function(){
			Route::get('/', function(){});
			Route::view('/view', 'welcome', ['key'=>'value']);
		});
前置&后置 中间件
Terminable 中间件 有疑问

/****************************************************************/
控制器
	单个行为控制器,使用__invoke(),则无需在web.php中写方法名

控制器中间件
	将$this->middleware()放在构造函数中使用
	public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('log')->only('index');
        $this->middleware('subscribed')->except('store');
    }
使用闭包来为控制器注册中间件
	$this->middleware(function($request, $next){
		return $next($request);
	});

资源控制器
	Route::resource('photos', 'PhotosController');
	指定资源模型:php artisan make:controller PhotoController --resource --model=Photo
	伪造表单方法:{{ method_field('PUT') }}直接在视图中生成<input type="hidden" name="_method" value="PUT">
	部分资源路由:Route::resource('photos', 'PhotosController', ['only'=>['first', 'second']]);//只使用first,second方法
	Route::resource('photos', 'PhotosController', ['except'=>['first', 'second']]);//除了first,second方法
API资源路由
命名资源路由
	Route::resource('user', 'AdminUserController', ['parameters' => [
	    'user' => 'admin_user'
	]]);------------------>/user/{admin_user}
命名资源路由参数
本地化URI
	public function boot()
	{
	    Route::resourceVerbs([
	        'create' => 'crear',
	        'edit' => 'editar',
	    ]);
	}//使得路由变成 /user/crear, /user/editar
补充资源控制器