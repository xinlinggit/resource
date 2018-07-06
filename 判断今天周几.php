<?php
// 第一种写法
$da = date("w"); 
if( $da == "1" ){ 
	echo "今天是星期一"; 
}else if( $da == "2" ){ 
	echo "今天是星期二"; 
}else if( $da == "3" ){ 
	echo "今天是星期三"; 
}else if( $da == "4" ){ 
	echo "今天是星期四"; 
}else if( $da == "5" ){ 
	echo "今天是星期五"; 
}else if( $da == "6" ){ 
	echo "今天是星期六"; 
}else if( $da == "0" ){ 
	echo "今天是星期日"; 
}else{ 
	echo "你输入有误!!"; 
}

// 第二种写法 
$ga = date("w"); 
switch( $ga ){ 
case 1 : echo "今天是星期一";break; 
case 2 : echo "今天是星期二";break; 
case 3 : echo "今天是星期三";break; 
case 4 : echo "今天是星期四";break; 
case 5 : echo "今天是星期五";break; 
case 6 : echo "今天是星期六";break; 
case 0 : echo "今天是星期日";break; 
default : echo "你输入有误！"; 
};
// 第三种写法 
echo "今天是星期" . mb_substr( "日一二三四五六",date("w"),1,"utf-8" );