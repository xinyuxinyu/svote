<?php
session_start();
if(!isset($_SESSION['admin'])){
	header('Content-Type:text/html; charset=utf-8');
	echo "错误!没有权限!";
	exit(0);
}
include("conn.php");
include("varibles.php");

$proj     = $_POST['proj'];
$name     = $_POST['name'];
$account  = $_POST['account'];
$password = $_POST['password'];
$note     = $_POST['note'];

$sql = "INSERT INTO ${proj}_voters (name,account,password,note) VALUES ('${name}', '${account}', '${password}', '${note}')";
$res = mysql_query($sql);
?>
