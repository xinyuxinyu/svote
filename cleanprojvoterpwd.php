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
$id  = $_POST['id'];

$sql = "UPDATE ${proj}_voters SET password='' WHERE id='${id}'";
$res = mysql_query($sql);
?>
