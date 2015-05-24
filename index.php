<?php
require_once(__DIR__.'/extra/Templater.class.php');
require_once(__DIR__.'/extra/MySqlOp.class.php');

$smarty=Template::getInstance();

$db=MySqlOp::getInstance();
$db->GetRows('tests');
$data=$db->rows;
$keys=array_rand($data,20);
$result=[];
foreach($keys as $key){
    array_push($result,$data[$key]);
}




$smarty->assign('questions',$result);
echo $smarty->fetch('body.tpl');














