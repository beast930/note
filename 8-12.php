调用分页
	$students = Student::paginate(4);
	在模板中使用 $students->render();

在模板中判断路由名称 Request::getPathInfo('/student/create')

由于在laravel框架中有此要求：任何指向 web 中 POST, PUT 或 DELETE 路由的 HTML 表单请求都应该包含一个 CSRF 令牌，否则，这个请求将会被拒绝。    {{ csrf_field() }}

在表单中的
	<input type="text" name="student[name]" class="form-control" id="name" placeholder="请输入学生姓名"> student[name]不用student['name']

使用create方法存入数据库时,记住使用 protected $fillable = ['字段名'];
使用const UPDATED_AT = null,可以只添加created_at字段

redirect()->back();返回上一个页面

控制器表单验证
	全局的错误信息$errors
	@if(count($errors))
	<div class="alert alert-danger">
	    <ul>
	        @foreach($errors->all() as $err)
	        	<li>{{$err}}</li>
	        @endforeach
	    </ul>
	</div>
	@endif

	$this->validate($request, [验证的规则], [规则详情], [字段名称]);

	$this->validate($request, [
		'student.name'=>'required',
		'student.age'=>'required|integer',
		'student.sex'=>'required'
	],[
		'required'=>':attribute 必须填写',
		'integer'=>':attribute 必须为整数'
	],[
		'student.name'=>'姓名',
		'student.age'=>'年龄',
		'student.sex'=>'性别'
	]);

Validator类的验证
	$validator = \Validator::make($request->input(),[
		'student.name'=>'required',
		'student.age'=>'required|integer',
		'student.sex'=>'required'
	],[
		'required'=>':attribute 必须填写',
		'integer'=>':attribute 必须为整数'
	],[
		'student.name'=>'姓名',
		'student.age'=>'年龄',
		'student.sex'=>'性别'
	]);

	if ($validator->fails()) {
		return redirect()->back()->withErrors($validator);
	}

$errors小结
	$errors->all()//所有错误信息,可以用foreach循环输出
	$errors->first()//无参数时默认返回错误信息的第一条,指定参数则返回该名称的错误信息
	$errors->first('student.name')

数据保持
	在控制器中
		return redirect()->back()->withErrors($validator)->withInput();//加上withInput()
	在视图中
		{{old('student')['name']}}

处理数据库存的数据映射到界面
	在模型中:
		const SEX_UN = 'unknown';
	    const SEX_MAN = 'man';
	    const SEX_WMAN = 'woman';

	    public function sex($index = null){
	        $arr = [
	            SELF::SEX_UN=>'未知',
	            SELF::SEX_MAN=>'男',
	            SELF::SEX_WMAN=>'女'
	        ];
	        if ($index !== null) {
	            return array_key_exists($index, $arr)?$arr[$index]:'未知';
	        }
	        return $arr;
	    }
	在视图中:
		<td>{{$student->sex($student->sex)}}</td>

表单修改
	url('路由', [传递参数])