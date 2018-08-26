// Call the $foo->bar() method with 2 arguments
$foo = new foo;
call_user_func_array(array($foo, "bar"), array("three", "four"));

//laravel sql语句带括号
//use 将$ids传入闭包函数的参数, 使用where的回调函数将使sql语句包含括号
$data = Leave::where(function ($query) use ($ids){
            $query->whereIn('leave_id', $ids)->orWhereRaw('review_user_id = '.\Auth::user()->user_id);
        })
            ->whereRaw($scope->getWhere())
            ->orderBy('created_at', 'desc')
            ->paginate(30);
