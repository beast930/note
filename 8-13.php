<?php
查询构造器:
	get(),first(),pluck(),chunk(),聚合
	select(),distinct(),addSelect()
	selectRaw(),whereRaw(),havingRaw(),orderRaw()
	join():1.连接表的名字 2.本表属性 3.链接条件 4.连接表的属性
		$users = DB::table('users')->join('contacts', 'users.id', '=', 'contacts.user_id');
	leftJoin()
		$users = DB::table('users')->leftJoin('posts', 'users.id', '=', 'posts.user_id');
	crossJoin():交叉连接 生成笛卡尔积

	高级Join语句 join语句中第二个参数使用闭包
		DB::table('users')->join('contacts',function($join){
			$join->on('users.id', '=', 'contacts.user_id')
			->where('contacts.user_id', '>', 5);
		})->get();

	Unions
		$first = DB::table('users')->whereNull('first_name');
		$users = DB::table('users')->whereNull('last_name')->union($first)->get();

	where
		第二个参数:'>=','<>','like'
		whereBetween('字段', [min, max]);
		whereNotBetween
		whereIn('字段', [a,b,c])
		whereNotIn(), whereNull,whereNotNull
		whereDate('created_at', '2011-11-11');
		whereDay(),whereMonth,whereYear,whereTime
		比较两个字段的值:whereColumn('first_name','>','last_name');//参数可为多个数组
		DB::table('users')->where(..)->orWhere(function($query){
			$query->where(....)->where(....)
		})->get();
	Or

	orderBy(), latest(), oldest(), inRandomOrder()//查询结果随机排序
	groupBy(),having()

	条件语句
		when()://当第一个参数为true时才执行第二个回调函数,为false时执行第三个回调函数
		$users = DB::table('users')->when($role, function($query) use ($role){
			return $query->where('role_id', $role);
		}, function($query){
			return $query->orderBy('name');
		})->get();

	Insert()
		insertGetId()
	Update()
	increment(),decrement('字段', [int 自增多少,array 指定要更新的字段])
	delete(), truncate()

	shareLock():共享锁
	lockForUpdate():写锁

Eloquent 模型 
    可以使用all(), get()查询所有行
    find(),first()获取一条数据,findOrFail(), firstOrFail()\
    public function getDateFormat();
    public function asDateTime();

    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updation_date';

    chunk(int arg0, function($call))

    //cursor省略了一次循环，直接拿到结果集，自己循环，用于数据量较大时
    foreach ($test->where('id', '>', 2)->cursor() as $val){
    var_dump($val->toArray());
    }

    新建：1.::create(); 2.$test = new Test();$test->id = ...; $test->save(); 3.有模型实例，则$test->fill()
    更新：1.$test->id=...; $test->save(); 2.update()
    删除:1.delete();2.destory();

    软删除:
        $test->where('id', '>', 7)->delete();
        //是否已被软删除
        var_dump($test->trashed());
        //连被软删除的数据都一起加入查询
        dd($test->withTrashed()->where('id', '>', 7)->get());
        //onlyTrashed
        //恢复
        dd($test->onlyTrashed()->restore());
        dd($test->withTrashed()->where('id', 8)->restore());
        //强制删除
        $test->withTrashed()->find(9)->forceDelete();
    查询作用域 暂未学习

Eloquent 关联
    一对一:
        Test模型:
        public function grade(){
            return $this->hasOne('App\Grade', 'user_id', 'id');//1.要关联的表的命名空间 2.关联表的字段 3.本表字段
        }
        Grade模型:
        public function test(){
            return $this->belongsTo('App\Test', 'user_id', 'id');//1.关联表的命名空间 2.本表字段 3.关联表字段
        }
        定义好后, 在控制器中获取模型:
            echo $test->find(1)->grade->grade;//先获得关联的grade表,再从grade表中获取grade属性
            echo $grade->find(1)->test->sex;//现货区关联表test,再从test表中获取sex属性

    一对多:
        Test模型:
        public function grade(){
            return $this->hasMany('App\Grade', 'user_id', 'id');//1.要关联的表的命名空间 2.关联表的字段 3.本表字段
        }
        Grade模型:
        public function test(){
            return $this->belongsTo('App\Test', 'user_id', 'id');//1.关联表的命名空间 2.本表字段 3.关联表字段
        }
        定义好后, 在控制器中获取模型:
        $grades = $test->find(6)->grade;
        foreach($grades as $grade){echo $grade->grade;}
        echo $grade->find(1)->test->sex;//现货区关联表test,再从test表中获取sex属性
    多对多:
        One模型:
            public function Two(){
                //withPivot() 中间表对象Pivot有额外字段要使用该函数,否则默认只有两个
                //withTimestamps()
                //wherePivot('key', 'value'); wherePiovtIn(a, ['a','b']);
                //return $this->belongsToMany('\App\Two', 'one_two', 'one_id', 'two_id')->withPivot('ot_value');//1.命名空间 2.中间表名
                // 3.中间表的名字,与本模型的主键相关联 4.中间表的名字,与其他表的主键相关联
            }
        Two模型:
            public function One(){
                return $this->belongsToMany('\App\One', 'one_two', 'two_id', 'one_id')->withPivot('ot_value')->wherePivot('one_id', '>', 3);
        }
    远层关联
    多态关联













			
=======
<?php
查询构造器:
	get(),first(),pluck(),chunk(),聚合
	select(),distinct(),addSelect()
	selectRaw(),whereRaw(),havingRaw(),orderRaw()
	join():1.连接表的名字 2.本表属性 3.链接条件 4.连接表的属性
		$users = DB::table('users')->join('contacts', 'users.id', '=', 'contacts.user_id');
	leftJoin()
		$users = DB::table('users')->leftJoin('posts', 'users.id', '=', 'posts.user_id');
	crossJoin():交叉连接 生成笛卡尔积

	高级Join语句 join语句中第二个参数使用闭包
		DB::table('users')->join('contacts',function($join){
			$join->on('users.id', '=', 'contacts.user_id')
			->where('contacts.user_id', '>', 5);
		})->get();

	Unions
		$first = DB::table('users')->whereNull('first_name');
		$users = DB::table('users')->whereNull('last_name')->union($first)->get();

	where
		第二个参数:'>=','<>','like'
		whereBetween('字段', [min, max]);
		whereNotBetween
		whereIn('字段', [a,b,c])
		whereNotIn(), whereNull,whereNotNull
		whereDate('created_at', '2011-11-11');
		whereDay(),whereMonth,whereYear,whereTime
		比较两个字段的值:whereColumn('first_name','>','last_name');//参数可为多个数组
		DB::table('users')->where(..)->orWhere(function($query){
			$query->where(....)->where(....)
		})->get();
	Or

	orderBy(), latest(), oldest(), inRandomOrder()//查询结果随机排序
	groupBy(),having()

	条件语句
		when()://当第一个参数为true时才执行第二个回调函数,为false时执行第三个回调函数
		$users = DB::table('users')->when($role, function($query) use ($role){
			return $query->where('role_id', $role);
		}, function($query){
			return $query->orderBy('name');
		})->get();

	Insert()
		insertGetId()
	Update()
	increment(),decrement('字段', [int 自增多少,array 指定要更新的字段])
	delete(), truncate()

	shareLock():共享锁
	lockForUpdate():写锁

Eloquent 模型 
    可以使用all(), get()查询所有行
    find(),first()获取一条数据,findOrFail(), firstOrFail()\
    public function getDateFormat();
    public function asDateTime();

    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updation_date';

    chunk(int arg0, function($call))

    //cursor省略了一次循环，直接拿到结果集，自己循环，用于数据量较大时
    foreach ($test->where('id', '>', 2)->cursor() as $val){
    var_dump($val->toArray());
    }

    新建：1.::create(); 2.$test = new Test();$test->id = ...; $test->save(); 3.有模型实例，则$test->fill()
    更新：1.$test->id=...; $test->save(); 2.update()
    删除:1.delete();2.destory();

    软删除:
        $test->where('id', '>', 7)->delete();
        //是否已被软删除
        var_dump($test->trashed());
        //连被软删除的数据都一起加入查询
        dd($test->withTrashed()->where('id', '>', 7)->get());
        //onlyTrashed
        //恢复
        dd($test->onlyTrashed()->restore());
        dd($test->withTrashed()->where('id', 8)->restore());
        //强制删除
        $test->withTrashed()->find(9)->forceDelete();
    查询作用域 暂未学习

Eloquent 关联
    一对一:
        Test模型:
        public function grade(){
            return $this->hasOne('App\Grade', 'user_id', 'id');//1.要关联的表的命名空间 2.关联表的字段 3.本表字段
        }
        Grade模型:
        public function test(){
            return $this->belongsTo('App\Test', 'user_id', 'id');//1.关联表的命名空间 2.本表字段 3.关联表字段
        }
        定义好后, 在控制器中获取模型:
            echo $test->find(1)->grade->grade;//先获得关联的grade表,再从grade表中获取grade属性
            echo $grade->find(1)->test->sex;//现货区关联表test,再从test表中获取sex属性

    一对多:
        Test模型:
        public function grade(){
            return $this->hasMany('App\Grade', 'user_id', 'id');//1.要关联的表的命名空间 2.关联表的字段 3.本表字段
        }
        Grade模型:
        public function test(){
            return $this->belongsTo('App\Test', 'user_id', 'id');//1.关联表的命名空间 2.本表字段 3.关联表字段
        }
        定义好后, 在控制器中获取模型:
        $grades = $test->find(6)->grade;
        foreach($grades as $grade){echo $grade->grade;}
        echo $grade->find(1)->test->sex;//现货区关联表test,再从test表中获取sex属性
    多对多:
        One模型:
            public function Two(){
                //withPivot() 中间表对象Pivot有额外字段要使用该函数,否则默认只有两个
                //withTimestamps()
                //wherePivot('key', 'value'); wherePiovtIn(a, ['a','b']);
                //return $this->belongsToMany('\App\Two', 'one_two', 'one_id', 'two_id')->withPivot('ot_value');//1.命名空间 2.中间表名
                // 3.中间表的名字,与本模型的主键相关联 4.中间表的名字,与其他表的主键相关联
            }
        Two模型:
            public function One(){
                return $this->belongsToMany('\App\One', 'one_two', 'two_id', 'one_id')->withPivot('ot_value')->wherePivot('one_id', '>', 3);
        }
    远层关联
    多态关联

    composer
        1.在目录内创建composer.json文件, 写上想要下载的文件
        2.使用命令工具 composer install

