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

$sql = "SELECT * FROM ${projs} WHERE name='${projname}'";
$res = mysql_query($sql);

$ret = -1;
if(!($row = mysql_fetch_row($res))){
	$sql = "INSERT INTO ${projs} (name) VALUES ('${projname}')";
	$res = mysql_query($sql);

	$sql = "CREATE TABLE ${projname}_note (
name  VARCHAR(255) NOT NULL,
value VARCHAR(5000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL)";
	$res = mysql_query($sql);

    $sql = "INSERT INTO ${projname}_note (name,value) VALUES ('note', '无')";
	$res = mysql_query($sql);

	$sql = "CREATE TABLE ${projname}_stack (
name  VARCHAR(255) NOT NULL,
value VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL)";
	$res = mysql_query($sql);

    $sql = "INSERT INTO ${projname}_stack (name,value) VALUES ('voterzige', '全体在职研究员或相当专业技术职务人员')";
	$res = mysql_query($sql);

    $sql = "INSERT INTO ${projname}_stack (name,value) VALUES ('votedzige', '全体在读研究生')";
	$res = mysql_query($sql);

    $sql = "INSERT INTO ${projname}_stack (name,value) VALUES ('number', '1')";
	$res = mysql_query($sql);

    $sql = "INSERT INTO ${projname}_stack (name,value) VALUES ('maxvotenum', '20')";
	$res = mysql_query($sql);

	$sql = "CREATE TABLE ${projname}_majors (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
major VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
num INT(11) DEFAULT 1 NOT NULL,
nmin INT(11) DEFAULT 1 NOT NULL)";
	$res = mysql_query($sql);

    $sql = "CREATE TABLE ${projname}_judge1 (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
voterid INT(11) NOT NULL,
majorid INT(11) NOT NULL,
votedid INT(11) NOT NULL)";
	$res = mysql_query($sql);

    $sql = "CREATE TABLE ${projname}_stat1 (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
voterid INT(11) NOT NULL)";
	$res = mysql_query($sql);

	$majors = array('分析化学', '有机化学', '物理化学', '化学工程', '生物化工', '工业催化' );
	for($i = 0; $i < count($majors); $i++){
	  $sql = "INSERT INTO ${projname}_majors (major) VALUES ('${majors[$i]}')";
	  $res = mysql_query($sql);
	}

	$sql = "CREATE TABLE ${projname}_voteds (
id      INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
majorid INT(11) NOT NULL,
name    VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
zige    VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
year    INT(11) NOT NULL,
note    VARCHAR(5000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
date    VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL)";
	$res = mysql_query($sql);

	$sql = "CREATE TABLE ${projname}_voters (
id       INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
name     VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
account  VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
password VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
note     VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL)";
	$res = mysql_query($sql);

	$ret = 0;
}

echo $ret;
?>
