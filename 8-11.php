<?php

路由参数绑定
	Route::get('/{id}', 'xxxController@info');
	public info($id){...}
连接数据库
	在config/database.php 结合 .env.php可以进行配置
	在控制器中使用Db类进行使用
		DB::select();DB::update();DB::insert();DB::delete();
		可以使用预绑定
		$student = DB::update('update test set name=?,pwd=? where id=?', ['hello', 'hello', 3]);

查询构造器
	增加数据
		//插入一条数据
    	/*$student = DB::table('test')->insert([
    		'id'=>4, 'name'=>'xiaosi', 'pwd'=>'xiaosi'
    	]);*/
    	//插入多条数据
    	/*$student = DB::table('test')->insert([
    		['name'=>'xiaowu', 'pwd'=>'xiaowu'],
    		['name'=>'xiaoliu', 'pwd'=>'xiaoliu']
    	]);*/
    	insertGetId();

    修改数据
    	//更新
    	// $student = DB::table('test')->where(['id'=>'6'])->update(['name'=>'xiaoliu', 'pwd'=>'six']);
    	// 默认全部自增1
    	// $student = DB::table('test')->increment('id');
    	// $student = DB::table('test')->increment('id',3);//自增3
    	// $student = DB::table('test')->where(['name'=>'xiaoliu'])->increment('id', 2, ['name'=>'waqiu']);//在自增的时候完成对字段的修改
    	
    删除数据
    	$student = DB::table('test')->where('id', '>=', 3)->delete();

    查询数据
    	//查询
    	// $student = DB::table('test')->get();//获取表的全部数据
    	// $student = DB::table('test')->first();//获取一条数据
    	// $student = DB::table('test')->whereRaw('id>=? and pwd=?', [5, 'six'])->get();//多个条件查询用whereRaw()
    	// $student = DB::table('test')->pluck('name', 'pwd');//返回指定字段,第二个参数为数组的键,形成一维数组
    	// $student = DB::table('test')->select('name')->get();
    	
    	//分块查询,一次查询3条记录
    	echo "<pre>";
    	DB::table('test')->orderBy('id')->chunk(3, function($stus){
    		foreach ($stus as $stu) {
    			$stu->name = 'student_'.$stu->name;
    			var_dump($stu);
    		}
    	});\
    聚合函数
    	sum(),max(),min(),avg(),sum()

/***********************************************/
使用orm模型完成数据库操作
	// $student = test::all();
	// $student = test::find(1)->toArray();
	// findOrFail()
	
	//聚合函数也可使用
	test::orderBy('id')->chunk(2, function($stu){
		var_dump($stu->toArray());
	});
	//dd($student);
	
在model中的属性与方法
	//指定表明
    protected $table = 'test';
    //指定主键
    protected $primaryKey = 'id';
    //允许自动在updated_at,created_at字段添加
    public $timestamps = true;
    //在添加时可以以什么样式存进去
    protected function getDateFormat(){
    	return time();
    }
    //取出来时时间戳不自动转换成日期
    protected function asDateTime($val){
    	return $val;
    }
    //create方法允许添加的字段名
    protected $fillable = ['name', 'pwd'];
    //不允许添加的字段名
    protected $guarded = [];

在控制器的方法
	$test = new test();
	/*$test->name = 'add2';
	$test->pwd = 'pwd';
	$test->save();*/

	/*echo $test::find(11)->created_at;
	echo date('H:i:s', $test::find(11)->created_at);*/

	/*$test::create([
		'name'=>'add2',
		'pwd'=>'pwd2'
	]);*/
	使用firstOrCreate方法若存在则不创建,若不存在则创建
	/*$student = $test::firstOrCreate([
		'name'=>'add2','pwd'=>'pwd2'
	]);*/
	使用firstOrNew方法若不存在则显示出来,但不创建在数据库中,用save方法可以存储
	$student = $test::firstOrNew([
		'name'=>'add4'
	]);
	$student->save();
	dd($student);

以上为添加操作

//更新方法1:先查找,修改,保存
$student = $test::find(1);
$student->pwd = 'world';
$student->save();
//更新2:使用update
$test::where('id', '<', 8)->update(['pwd'=>'root']);


//模型删除
$del = $test::find(8);
$del->delete();
//主键删除
$test::destory(1,2,3...);
//条件删除
$test::where('id', '>', 8)->delete();

/********************************************/
layouts/default.blade.php--------->基本模板
@yield('content')
@yield('sentence', 'default')
static_pages/home.blade.php------->继承模板
@extend('layouts.default')
@section('content')
	@parent
	put your content
@stop

@yield其继承者不能使用 @parent

include/include.blade.php-------->被包含模板
<p>{{$key}},i was included view *-*</p>

@include('xxx', ['key'=>'value'])//将变量可以分配到要包含的文件中


模板中输出变量  {{ $name }}
模板中输出php代码  {{ time() }} {{ $name or 'default'}} @{{ $name }}

模板中的流程控制语句
@if(php语句)
	your output
@elseif
	your output
@else
	your output
@endif

@unless 相当于if的取反

@for($i=0;$i<10;$i++)
@endfor

@foreach($students as $student)
@endforeach

@forelse($students as $student)
	语句
@empty
	如果$student为空 ,则进行这里面的语句
@endforelse

url('路由的名字'),route('路由的别名'),action('控制器@方法名')---->都生成url

/******************************************/
Request
	echo $request->input('name', 'default');
	if (!$request->has('pwd')) {
		echo 'no pwd';
	}
	// dd($request->all());
	echo $request->method();
	var_dump($request->ismethod('get'));
	var_dump($request->ajax());
	var_dump($request->is('/'));//判断路由名称是否为括号内的
	var_dump($request->url());//获得不带参数的url值
    $request->fullUrl();//获取带参数的完整url
    $request->path();//获取路由
Session
	$request->session()->put('key', 'value');
	以数组的形式存入session
	$request->session()->put(['k1'=>'v2']);
	$request->session()->get('key', 'default');
	辅助函数session()->put()...
	静态方法 Session::get(),Session::put()

    Session::put('k1', 'v1');
    Session::put(['k2'=>'v2']);
    Session::push('k3', 'v3');
    Session::push('k3', 'v4');//结果;'K3'=>['v3','v4']
    Session::pull('k3');//取出session数据,下次就访问不到
    Session::all();
    Session::has('k2');
    Session::forget('k2');//删除指定键
    Session::flash('k1','v1');//临时session,读完一次,下次就访问不到

Response
    //json
    /*$arr = [
        'a'=>'root',
        'b'=>'root'
    ];
    return response()->json($arr);*/
    //重定向
    //with带上信息跳转,原理是使用了Session::flash(),被跳转的页面使用Session::get()获取
    return redirect('/about')->with(['message3'=>'world','message2'=>'world']);//路由名
    //控制器@方法
    return redirect()->action('StaticPagesController@about')->with(['message3'=>'world','message2'=>'world']);
    //路由别名
    return redirect()->route('pre_about')->with(['message3'=>'world','message2'=>'world']);

Middleware
    1.php artisan make:middleware test
    2.handle($request, $next)
    3.app/http/Kernel.php 注册绑定
    4.Route::middleware('test')->group(function(){Route::get('/',....)});