<?php
session_start();
if(!isset($_SESSION['normal'])){
	header('Content-Type:text/html; charset=utf-8');
	echo "错误!没有权限!";
	exit(0);
}
include("conn.php");
include("varibles.php");

$proj    = $_POST['proj'];
$number  = $_POST['number'];
$voterid = $_POST['voterid'];

$sql = "INSERT INTO ${proj}_stat${number} (voterid) VALUES ('${voterid}')";
$res = mysql_query($sql);
?>
