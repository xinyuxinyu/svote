<?php
session_start();
if(!isset($_SESSION['admin'])){
	header('Content-Type:text/html; charset=utf-8');
	echo "错误!没有权限!";
	exit(0);
}
include("conn.php");
include("varibles.php");


$proj  = $_POST['proj'];
$major = $_POST['major'];
$num   = $_POST['num'];

$sql = "INSERT INTO ${proj}_majors (major,num) VALUES ('${major}', '${num}')";
$res = mysql_query($sql);
?>
