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
>* 无法发送POST请求，也就是说JSONP技术只
简单ajax请求能用于请求异域资源，无法上传数据或修改异域数据；
>* 无法监测JSONP请求是否失败；
>* 可能存在安全隐患：别忘了，JSONP之所以能成功获取异域服务器资源，靠的是服务器动态生成了回调函数，并在页面中执行，那么如果服务器在原有的回调函数下再添加些别的恶意JavaScript代码会怎样？当然也会被执行！所以在使用JSONP技术时，一定要确保请求资源的服务器是值得信赖的；

#### 2.使用CORS
简单ajax请求:

当浏览器检测到一个简单的跨域AJAX请求，浏览器会首先为我们添加一个头部信息：Origin它的值为请求发送代码所在的源（希望你还记得，一个源由“协议”，“域名和端口”组成）。类似这样：
``` 
GET /cors HTTP/1.1
Origin: http://api.bob.com
Host: api.alice.com
Accept-Language: en-US
Connection: keep-alive
User-Agent: Mozilla/5.0 ...
```

而当这样的一条HTTP请求发送到服务端时，服务端会检测该请求报头中的Origin字段的值是否在许可范围内，如果的确是服务端认可的域，那么服务端会在响应报文中添加如下字段：

* Access-Control-Allow-Origin（必须）：该字段用来告知浏览器服务端接受的能够发送跨域AJAX请求的域，它的值要么是该次AJAX请求报头中由浏览器自动添加的Origin值，要么还可以是一个\*号，表示可以接受任意的域名请求；
* Access-Control-Allow-Credentials（可选）：该字段用来告知浏览器是否允许客户端向服务端发送Cookie。默认情况下，CORS规范会阻止跨域AJAX向服务端发送Cookie，因此该字段默认值为false，当你显式的将该字段值设置为true时，则表示允许此次跨域AJAX向服务端发送Cookie。
* Access-Control-Expose-Headers（可选）：该字段用来向客户端暴露可获取的响应头；

复杂ajax请求:

> 复杂“的AJAX跨域请求一共会发送两次HTTP请求，其中第一次为”查询请求“，第二次才是我们正式的”AJAX跨域请求“。为什么多出了一次”查询请求“呢？道理其实很简单，我们想象一下当发送”复杂“的AJAX跨域请求时，浏览器最先拿到请求开始识别，然后发现这个请求并不“单纯”（不满足简单跨域AJAX请求标准），于是感到十分疑惑的浏览器会试探的沿着请求的地址向服务端发问，询问服务端是否允许异域的客户端向它发送额外的请求信息，这一次“发问”，即是第一次HTTP请求，即“查询请求”。而服务端当然也会这次“发问”给出相应的回答，然后浏览器就会根据回答的结果决定是否继续发送该跨域AJAX请求。

[参考:https://segmentfault.com/a/1190000012302319](https://segmentfault.com/a/1190000012302319)

> 三次握手：
> * 第一次握手：客户端发送syn包(syn=x)到服务器，并进入SYN_SEND状态，等待服务器确认；
> * 第二次握手：服务器收到syn包，必须确认客户的SYN（ack=x+1），同时自己也发送一个SYN包（syn=y），即SYN+ACK包，此时服务器进入SYN_RECV状态；
> * 第三次握手：客户端收到服务器的SYN＋ACK包，向服务器发送确认包ACK(ack=y+1)，此包发送完毕，客户端和服务器进入ESTABLISHED状态，完成三次握手。
握手过程中传送的包里不包含数据，三次握手完毕后，客户端与服务器才正式开始传送数据。理想状态下，TCP连接一旦建立，在通信双方中的任何一方主动关闭连接之前，TCP 连接都将被一直保持下去。

>四次握手
与建立连接的“三次握手”类似，断开一个TCP连接则需要“四次握手”。
> * 第一次挥手：主动关闭方发送一个FIN，用来关闭主动方到被动关闭方的数据传送，也就是主动关闭方告诉被动关闭方：我已经不会再给你发数据了(当然，在fin包之前发送出去的数据，如果没有收到对应的ack确认报文，主动关闭方依然会重发这些数据)，但是，此时主动关闭方还可以接受数据。
> * 第二次挥手：被动关闭方收到FIN包后，发送一个ACK给对方，确认序号为收到序号+1（与SYN相同，一个FIN占用一个序号）。
> * 第三次挥手：被动关闭方发送一个FIN，用来关闭被动关闭方到主动关闭方的数据传送，也就是告诉主动关闭方，我的数据也发送完了，不会再给你发数据了。
> * 第四次挥手：主动关闭方收到FIN后，发送一个ACK给被动关闭方，确认序号为收到序号+1，至此，完成四次挥手。

