<?php
require_once(__DIR__.'/extra/MySqlOp.class.php');
$fp=fopen('english.txt','r');

//questions
$db=MySqlOp::getInstance();
$count=0;
$counter=0;
$question=[

];
while(!feof($fp) && $count<=6){

    if($count==6)$count=0;
    $FileLine=trim(fgets($fp));
    if($count==0){
        $counter++;
        $questions[$counter]['question']=trim(preg_replace('/^(\d+.)/','',$FileLine));
    }
    else{
        $questions[$counter]['answers'][]=substr(trim(preg_replace('/(\w\))/','',$FileLine)),0,-1);
    }

    $count++;

}

foreach($questions as $key=>$value){
    $data['question']=$value['question'];
    $data['answers']=serialize($value['answers']);
    echo $db->AddRow('tests',$data),"\n";
}//answers


require_once(__DIR__.'/extra/MySqlOp.class.php');
$fp=fopen('english_answers.txt','r');


$db=MySqlOp::getInstance();

while(!feof($fp)){
    $FileLine=trim(fgets($fp));
    $answers[]=str_replace(["A","B","C","D","E"],[0,1,2,3,4],trim(preg_replace('/^(\d+.)/','',$FileLine)));
}
foreach($answers as $key=>$answer)
    echo $db->UpdateRow('tests',['correct'=>$answer],'id="'.($key+1).'"');




