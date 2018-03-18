<?php
session_start();
if(!isset($_SESSION['admin'])){
	header('Content-Type:text/html; charset=utf-8');
	echo "错误!没有权限!";
	exit(0);
}
include("conn.php");
include("varibles.php");

$username = $_SESSION['username'];
$password = $_POST['password'];
$sql = "UPDATE ${users} SET password='${password}' WHERE username='${username}'";
$res = mysql_query($sql);
?>
