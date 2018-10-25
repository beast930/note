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
