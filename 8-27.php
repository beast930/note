1.(function($){...})(jQuery)
  定义后jQuery函数,在调用时使用,实际是将jQuery对象作为实参传递进去, 用于写jQuery插件
  与function($){} 不一样, 这是在页面加载的时候就开始执行了


2.$.fn.extend(object)     jQuery.fn.extend(object)
  $.fn.extend() 函数为jQuery扩展一个或多个实例属性和方法(主要用于扩展方法)。
  提示：jQuery.fn是jQuery的原型对象，其extend()方法用于为jQuery的原型添加新的属性和方法。这些方法可以在jQuery实例对象上调用

  $(function () { 
      $.fn.extend({
          check: function() {
              return this.each(function() {
                  this.checked = true;
              });
          },
          uncheck: function() {
              return this.each(function() {
                  this.checked = false;
              });
          }
      });
      // 使用新创建的.check() 方法
      $( "input[type='checkbox']" ).check();
  })


3.jQuery.extend()
  jQuery.extend() 函数用于将一个或多个对象的内容合并到目标对象。
