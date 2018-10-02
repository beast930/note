### http请求行: 
* GET / HTTP/1.1

### 请求头部:
* Host: php.net
* User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36
* Accept: text/html

### 响应行
![enter description here](./images/捕获_3.PNG "捕获")

### 响应头部
![enter description here](./images/捕获_4.PNG "捕获")

#### laravel
使用request()->header();可以显示请求头部
上传文件,修改表单的enctype属性, 就修改了请求同步的content-type的值

### 同源策略和跨域共享
#### 1.使用 jsonp
客户端:
``` 
function callbackFunction(param){...}

<script type="text/javascript" src="http://www.runoob.com/try/ajax/jsonp.php?jsoncallback=callbackFunction"></script>
```

服务端:
``` 
<?php
header('Content-type: application/json');
//获取回调函数名
$jsoncallback = htmlspecialchars($_REQUEST ['jsoncallback']);
//json数据
$json_data = '["customername1","customername2"]';
//输出jsonp格式的数据
echo $jsoncallback . "(" . $json_data . ")";
?>
```

答案就藏在服务端的代码中，当服务端支持JSONP技术时，会做如下一些设置：

* 识别请求的URL，提取callback参数的值，并动态生成一个执行该参数值（一个函数）的JavaScript语句；
* 将需要返回的数据放入动态生成的函数中，等待其加在到页面时被执行；

> 最后，我们还要对JSONP技术再强调两点：
>JSONP技术与AJAX技术无关：虽然同样牵扯到跨域获取资源这个主题，但我们应该已经清楚的看到，JSONP的本质是绕过AJAX获取资源的机制，使用原始的src属性获取异域资源；
>JSONP技术存在一下三点缺陷：
>* 无法发送POST请求，也就是说JSONP技术只能用于请求异域资源，无法上传数据或修改异域数据；
>* 无法监测JSONP请求是否失败；
>* 可能存在安全隐患：别忘了，JSONP之所以能成功获取异域服务器资源，靠的是服务器动态生成了回调函数，并在页面中执行，那么如果服务器在原有的回调函数下再添加些别的恶意JavaScript代码会怎样？当然也会被执行！所以在使用JSONP技术时，一定要确保请求资源的服务器是值得信赖的；

