<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/15
 * Time: 11:19
 */
$arr = array('a','b','c','d','e');
$html = '';
$i=0;
foreach($arr as $key => $value){

    if($value=='b'){
        $html .= $value;
        continue; // 当 $value为b时，跳出本次循环
    }
    $i++;
    if($value=='c'){
        $html .= $value;
        break; // 当 $value为c时，终止循环
    }
    $html .= $value.$i;
}
echo $html; // 输出： ab