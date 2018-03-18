<?php
session_start();
if(!isset($_SESSION['admin'])){
	header('Content-Type:text/html; charset=utf-8');
	echo "错误!没有权限!";
	exit(0);
}
include("conn.php");
include("varibles.php");

$name  = $_POST['name'];
$value = $_POST['value'];

$sql = "SELECT * FROM ${stack} WHERE name='${name}'";
$res = mysql_query($sql);

if($row = mysql_fetch_row($res)){
	if($name == 'inuse' && $value == $row[1]){
		$value = '';
	}
	$sql = "UPDATE ${stack} SET value='${value}' WHERE name='${name}'";
}else{
    $sql = "INSERT INTO ${stack} (name,value) VALUES ('${name}', '${value}')";
}

$res = mysql_query($sql);
?>
