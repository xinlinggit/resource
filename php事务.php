<?php
thinkphp 回滚
$m=D('YourModel');//或者是M();

$m2=D('YouModel2');
$m->startTrans();//在第一个模型里启用就可以了，或者第二个也行
$result=$m->where('删除条件')->delete();
$result2=m2->where('删除条件')->delete();
if($result && $result2){
$m->commit();//成功则提交
}else{
$m->rollback();//不成功，则回滚！
}

注意：MySQL数据库必须是Innodb和Bdb才能支持事务。