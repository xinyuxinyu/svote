<?php

session_start();
if(!isset($_SESSION['admin'])){
	header('Content-Type:text/html; charset=utf-8');
	echo "错误!没有权限!";
	exit(0);
}
include("conn.php");
include("varibles.php");

$projname = $_POST['projname'];

$sql = "SELECT value FROM ${projname}_stack WHERE name='number'";
$res = mysql_query($sql);
$row = mysql_fetch_row($res);
$val = $row[0];

for($i = 1; $i <= $val; $i++){
  $sql = "DROP TABLE ${projname}_judge${i}";
  $res = mysql_query($sql);

  $sql = "DROP TABLE ${projname}_stat${i}";
  $res = mysql_query($sql);
}

$sql = "DROP TABLE ${projname}_note";
$res = mysql_query($sql);

$sql = "DROP TABLE ${projname}_stack";
$res = mysql_query($sql);

$sql = "DROP TABLE ${projname}_majors";
$res = mysql_query($sql);

$sql = "DROP TABLE ${projname}_voteds";
$res = mysql_query($sql);

$sql = "DROP TABLE ${projname}_voters";
$res = mysql_query($sql);

$sql = "DELETE FROM ${projs} WHERE name='$projname'";
$res = mysql_query($sql);

$sql = "UPDATE ${stack} SET value='' WHERE name='current'";
$res = mysql_query($sql);

$sql = "SELECT value FROM ${stack} WHERE name='inuse'";
$res = mysql_query($sql);
$row = mysql_fetch_row($res);
$inuse = $row[0];

if($projname == $inuse){
	$sql = "UPDATE ${stack} SET value='' WHERE name='inuse'";
	$res = mysql_query($sql);
}
?>
