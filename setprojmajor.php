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
$id    = $_POST['id'];
$opt   = $_POST['opt'];
$value = $_POST['value'];

$sql = "UPDATE ${proj}_majors SET ${opt}='${value}' WHERE id='${id}'";
$res = mysql_query($sql);
?>
