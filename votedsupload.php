<?php
session_start();
if(!isset($_SESSION['admin'])){
	header('Content-Type:text/html; charset=utf-8');
	echo "错误!没有权限!";
	exit(0);
}
include("conn.php");

$proj = $_POST['proj'];
$mid  = $_POST['mid'];
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
for ($i = 2; $i <= $highestRow; $i++){
  $name = $sheet->getCell('B'.$i)->getValue();
  $zige = $sheet->getCell('C'.$i)->getValue();
  $year = $sheet->getCell('D'.$i)->getValue();
  $date = $sheet->getCell('E'.$i)->getValue();
  $note = $sheet->getCell('F'.$i)->getValue();

  $datex = ($date - 25569)*24*60*60;
  $date = date('Y-m-d', $datex); 

  

  $sql = "INSERT INTO ${proj}_voteds (majorid,name,zige,year,note,date) VALUES ('${mid}', '${name}', '${zige}', '${year}', '${note}', '${date}')";
  $res = mysql_query($sql);

}

echo urlencode($ret);
?>
