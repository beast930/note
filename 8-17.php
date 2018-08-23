<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/17
 * Time: 13:53
 */
//安装使用entrust
{
    /*1.composer.json获取
    2.config\app.php 进行providers设置
    3.在kernel安装中间件
    4.php artisan vendor:publish
    5.php artisan entrust:migration生成迁移文件
    6.php artisan migrate
        生成roles — 用户角色
        permissions — 用户权限
        role_user — 角色和user多对多的关系
        permission_role — 权限和角色的多对多关系
    7.创建各个模型*/
}

//entrust用户认证的方式
{
    //创建角色
    /*$owner = new Role();
    $owner->name         = 'owner';
    $owner->display_name = 'Project Owner'; // optional
    $owner->description  = 'User is the owner of a given project'; // optional
    $owner->save();

    $admin = new Role();
    $admin->name         = 'admin';
    $admin->display_name = 'User Administrator'; // optional
    $admin->description  = 'User is allowed to manage and edit other users'; // optional
    $admin->save();

    //找出用户
    $user = User::where('name', '=', 'wang')->first();
    //为该用户添加角色
    $user->attachRole($admin);//因为\App\User模型use 了EntrustUserTrait,所以可以使用里面的方法 将该用户与角色的id绑定


    //创建权限
    $createPost = new Permission();
    $createPost->name         = 'create-post';
    $createPost->display_name = 'Create Posts'; // optional
    $createPost->description  = 'create new blog posts'; // optional
    $createPost->save();

    $editUser = new Permission();
    $editUser->name         = 'edit-user';
    $editUser->display_name = 'Edit Users'; // optional
    $editUser->description  = 'edit existing users'; // optional
    $editUser->save();

    //为角色添加权限
    //$admin->attachPermission($createPost);
    // equivalent to $admin->perms()->sync(array($createPost->id));
    $owner->attachPermissions(array($createPost, $editUser)); //此时,在permission_role中间表中已记录了permission_id与对应的role_id
    // equivalent to $owner->perms()->sync(array($createPost->id, $editUser->id));

    $arr= [$user->hasRole('owner'),   // false
    $user->hasRole('admin'),  // true
    $user->can('edit-user'),   // false
    $user->can('create-post')]; // true
    var_dump($arr);*/


    //注意:用户与角色绑定后不能重复绑定相同的角色
    /*$admin = Role::where('name', 'admin')->first();
    $createPost = Permission::where('name', 'create-post')->first();
    $admin->attachPermission($createPost);//绑定
    $user = User::where('name', 'wang')->first();
    //$user->attachRole($admin);
    var_dump($user->can('create-post'));*/


    /*// match any admin permission
    $user->can("admin.*"); // true

    // match any permission about users
    $user->can("*_users"); // true*/
}

//trans('app.hello'); 在resource/lang/app.php中找到'hello'=>'你好'

//去除权限 $user->detachRole($role);
//$role->perms()->get()->toArray();//找到该用户所对应的权限
/**
 * $disables = $olds->diff($news);//找出存在与旧数组不存在与新数组的权限,这意味着关闭该权限
 * $enables = $news->diff($olds);//找出存在与新数组而不存在与旧数组的权限,这意味着开启该权限
 *
 * $role->attachPermissions($enables);
   $role->detachPermissions($disables);
 *
 */