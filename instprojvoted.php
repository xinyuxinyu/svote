<?php
session_start();
if(!isset($_SESSION['admin'])){
	header('Content-Type:text/html; charset=utf-8');
	echo "错误!没有权限!";
	exit(0);
}
include("conn.php");
include("varibles.php");


$proj = $_POST['proj'];
$mid  = $_POST['mid'];
$name = $_POST['name'];
$zige = $_POST['zige'];
$year = $_POST['year'];
$date = $_POST['date'];
$note = $_POST['note'];

$sql = "INSERT INTO ${proj}_voteds (majorid,name,zige,year,note,date) VALUES ('${mid}', '${name}', '${zige}', '${year}', '${note}', '${date}')";
$res = mysql_query($sql);
?>
