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
$majorid = $_POST['majorid'];
$votedid = $_POST['votedid'];
$value   = $_POST['value'];

$sql = "SELECT value FROM ${proj}_stack WHERE name='number'";
$res = mysql_query($sql);
$row = mysql_fetch_row($res);

$ret = -1;
if($number != $row[0]){
  echo $ret;
  exit();
}

if($value == 0){
  $sql = "DELETE FROM ${proj}_judge${number} WHERE voterid='${voterid}' and majorid='${majorid}' and votedid='${votedid}'";
  $res = mysql_query($sql);
}

if($value == 1){
  $sql = "SELECT * FROM ${proj}_judge${number} WHERE voterid='${voterid}' and majorid='${majorid}' and votedid='${votedid}'";
  $res = mysql_query($sql);
  if(!$row = mysql_fetch_row($res)){
    $sql = "INSERT INTO ${proj}_judge${number} (voterid,majorid,votedid) VALUES ('${voterid}', '${majorid}', '${votedid}')";
    $res = mysql_query($sql);
  }
}

$sql = "SELECT COUNT(*) FROM ${proj}_judge${number} WHERE voterid='${voterid}'";
$res = mysql_query($sql);
$row = mysql_fetch_row($res);
$ret = $row[0];
echo $ret;
?>
