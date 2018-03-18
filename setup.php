<?php
include_once("conn.php");
include_once("varibles.php");

$sql = "CREATE TABLE IF NOT EXISTS ${users} (
id        INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
username  VARCHAR(20) NOT NULL,
password  VARCHAR(255) NOT NULL)";	
$res = mysql_query($sql);

$sql = "SELECT * FROM ${users} WHERE username='admin'";
$res = mysql_query($sql);
if(!$row = mysql_fetch_row($res)){
	$pwd = md5('admin');
	$sql = "INSERT INTO ${users} (username,password) VALUES ('admin', '${pwd}')";
	$res = mysql_query($sql);
}

$sql = "CREATE TABLE IF NOT EXISTS ${stack} (
name  VARCHAR(20) NOT NULL,
value VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL)";
$res = mysql_query($sql);

$sql = "CREATE TABLE IF NOT EXISTS ${projs} (
id        INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL)";
$res = mysql_query($sql);
?>
