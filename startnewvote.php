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

$sql = "SELECT value FROM ${proj}_stack WHERE name='number'";
$res = mysql_query($sql);
$row = mysql_fetch_row($res);

$val = $row[0];
$val++;

$sql = "UPDATE ${proj}_stack SET value='${val}' WHERE name='number'";
$res = mysql_query($sql);

$sql = "CREATE TABLE ${proj}_judge${val} (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
voterid INT(11) NOT NULL,
majorid INT(11) NOT NULL,
votedid INT(11) NOT NULL)";
$res = mysql_query($sql);

$sql = "CREATE TABLE ${proj}_stat${val} (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
voterid INT(11) NOT NULL)";
$res = mysql_query($sql);
?>
