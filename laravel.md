### 关于远层一对多
```
public function hasManyThrough($related, $through, $firstKey = null, $secondKey = null, $localKey = null, $secondLocalKey = null)
第一个参数:关联的表
第二个参数:中间表
第三个参数:中间表外键A
第四个参数:关联表外键B
第五个参数:本地表的键C
第六个参数:中间表的键D

select related.*, though.A form related inner join though on through.D = related.B where through.A in (....);
```
### 关于中间表
```
$roles = User::find(1)->role;
foreach($roles as $role) {
  $role->piovt
}

$rolesArr = $role->toArray();
$rolesArr['pivot']['...']

User::find(1)->role()->wherePivot(中间表的条件筛选)->get();

//中间表的添加数据
User::find(1)->role()->attach([id数组]或对象)
```
