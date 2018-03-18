<?php
session_start();
if(!isset($_SESSION['admin'])){
	header('Content-Type:text/html; charset=utf-8');
	echo "错误!没有权限!";
	exit(0);
}
include("conn.php");

$proj = $_POST['proj'];
$info = pathinfo($_FILES["file"]["name"]);
$type = $info['extension'];
$name = $_FILES["file"]["tmp_name"];

$version = '5';
if($type == 'xlsx'){
  $version = '2007';
}

include_once 'PHPExcel/PHPExcel/IOFactory.php';
$reader   = PHPExcel_IOFactory::createReader('Excel'.$version);
$PHPExcel = $reader->load($name);
$sheet    = $PHPExcel->getSheet(0);
$highestRow    = $sheet->getHighestRow(); 
$highestColumm = $sheet->getHighestColumn();
 
function blank($str){
  if(preg_match('/^\s+$/', $str) || $str == ''){
    return true;
  }else{
    return false;
  }
}

$ret  = '';
$fail = ",该投票人录入失败!\n";
for ($i = 2; $i <= $highestRow; $i++){
  $name    = $sheet->getCell('A'.$i)->getValue();
  $account = $sheet->getCell('B'.$i)->getValue();
  $note    = $sheet->getCell('C'.$i)->getValue();

  if(blank($account)){
    $ret .= "${name}：帐号未给出$fail";
    continue;
  }

  $sql = "SELECT * FROM ${proj}_voters WHERE account='${account}'";
  $res = mysql_query($sql);

  if(!$row = mysql_fetch_row($res)){
  	$sql = "INSERT INTO ${proj}_voters (name,account,note) VALUES ('$name', '$account', '$note')";
	$res = mysql_query($sql);
  }else{
    $ret .= "${name}：帐号(${account})已存在$fail";
    continue;
  }
}

echo urlencode($ret);
?>
