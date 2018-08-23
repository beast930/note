<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/15
 * Time: 10:19
 */
//样式scss的编译
{
    /**
     * 1. 只进行一次编译npm run dev
     * 2. 实时检测修改，进行编译 npm run watch-poll
     */
}

//表单验证
{
    //验证规则,规则翻译,字段翻译
    public function store(Request $request){
        $this->validate($request, [
            'name'=>'required|max:50',
            'email'=>'required|email|unique:users|max:255',
            'password'=>'required|confirmed|min:6',
            'password_confirmation'=>'required'
        ],[
            'required'=>':attribute必须填写',
            'confirmed'=>'密码必须一致'
        ],[
            'name'=>'姓名',
            'email'=>'邮箱',
            'password'=>'密码',
            'password_confirmation'=>'确认密码'
        ]);
    }

    //添加语言包
    //composer require "overtrue/laravel-lang:~3.0"
    //在config/app.php中配置
        'locale' => 'zh-CN',
}

/**
 * 使用闪存也可用session()->flash()
 * 跳转携带多个信息
 * return redirect()->route('users.show', $user->id)->with([
    'success'=>'欢迎，您将在这里开启一段新的旅程~',
    'warning'=>'有点小问题哦'
    ]);
 */

//可以使用循环遍历不同的提示信息
/*@foreach(['danger', 'warning', 'success', 'info'] as $msg)
    @if(session()->has($msg))
        <div class="flash-message">
            <p class="alert alert-{{ $msg }}">
                {{ session()->get($msg) }}
            </p>
        </div>
    @endif
@endforeach*/

//使用加密的hash函数
    bcrypt();

/**
 * composer dump-autoload  不会下载任何东西。它只是重新生成需要包含在项目中的所有类的列表（autoload_classmap.php）
 * 当打了 composer dump-autoload -o 之后，composer 就会提前加载需要的类并提前返回。这样大大减少了 IO 和深层次的 loop。
 */

//Auth用户认证
{

}
