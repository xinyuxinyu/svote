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
$name  = $_POST['name'];
$value = $_POST['value'];

$sql = "UPDATE ${proj}_stack SET value='${value}' WHERE name='${name}'";
$res = mysql_query($sql);
?>
