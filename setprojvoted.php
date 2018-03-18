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
$vid   = $_POST['vid'];
$opt   = $_POST['opt'];
$value = $_POST['value'];

$sql = "UPDATE ${proj}_voteds SET ${opt}='${value}' WHERE id='${vid}'";
$res = mysql_query($sql);
?>
