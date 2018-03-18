<?php
session_start();
if (!isset($_SESSION['admin'])){
    header('Content-Type:text/html; charset=UTF-8');
    echo "错误!没有权限!";
    exit(0);
}

include_once("conn.php");
include_once("header.php");
include("varibles.php");

$sql = "SELECT value FROM ${stack} WHERE name='current'";
$res = mysql_query($sql);
$row = mysql_fetch_row($res);
$proj = $row[0];

$sql = "SELECT value FROM ${proj}_stack WHERE name='number'";
$res = mysql_query($sql);
$row = mysql_fetch_row($res);
$number = $row[0];

$id = $_GET['id'];

$sql = "SELECT COUNT(*) FROM ${proj}_voters";
$res = mysql_query($sql);
$row = mysql_fetch_row($res);
$maxvoters = $row[0];

$sql = "SELECT COUNT(*) FROM ${proj}_stat${number}";
$res = mysql_query($sql);
$row = mysql_fetch_row($res);
$numvoters = $row[0];

?>

<title><?=$site?></title>

<script type="text/javascript" src="js/jquery-1.9.0.min.js"   ></script>	  
<script type="text/javascript" src="js/bootstrap-3.0.3.min.js"></script>

<link href="css/bootstrap-3.0.3.min.css" rel="stylesheet">
<link href="css/bootstrap-editable.css"  rel="stylesheet">	  
<link href="css/admin.css"               rel="stylesheet">

<div class="container-fluid">
	<div class="container">
<?php
if($id > $number){
?>
		<h3>只有<?=$number?>次投票，无法查询第<?=$id?>次投票。</h3>
<?php
exit();
}

$number = $id;
?>

		<h1 class="page-header"><?=$proj?>，第<?=$number?>次投票结果明细</h1>
		<table width="80%" class="table table-bordered vert-align">
<?php
$statdb = "${proj}_stat${number}";
$judgedb = "${proj}_judge${number}";
$votersdb = "${proj}_voters";
$votedsdb = "${proj}_voteds";
$majorsdb = "${proj}_majors";

$sql = "SELECT ${statdb}.voterid,(SELECT name FROM ${votersdb} WHERE ${votersdb}.id=${statdb}.voterid) FROM ${statdb} ORDER BY ${statdb}.voterid";
$res = mysql_query($sql);

$color = 0;
while($row = mysql_fetch_row($res)){
  $id   = $row[0];
  $name = $row[1];
  $color++;
  if($color%2 == 1){
	$clstr = '#FFFFFF';
  }else{
	$clstr = '#BDBDBD';
  }
?>
			<tr style="background:<?=$clstr?>;">
			   <th colspan="100%" class="text-center">投票<?=$color?>(投票人：<?=$name?>)</th>
			</tr>
			<tr style="background:<?=$clstr?>;">
				<th class="text-center" width="50px">序号</th>
				<th class="text-center" width="160px">姓名</th>
				<th class="text-center">专业</th>
			</tr>
<?php

  $sqlin = "SELECT ${judgedb}.votedid,${judgedb}.majorid,(SELECT ${votedsdb}.name FROM ${votedsdb} WHERE ${votedsdb}.id=${judgedb}.votedid),(SELECT ${majorsdb}.major FROM ${majorsdb} WHERE ${judgedb}.majorid=${majorsdb}.id) FROM ${judgedb} WHERE ${judgedb}.voterid=${id} ORDER BY ${judgedb}.majorid";
  $resin = mysql_query($sqlin);
  $count = 0;
  while($rowin = mysql_fetch_row($resin)){
    $count++;
    $votedname = $rowin[2];
    $majorname = $rowin[3];
?>
		   <tr style="background:<?=$clstr?>;">
				<td class="text-center"><?=$count?></td>
				<td class="text-center"><?=$votedname?></td>
				<td class="text-center"><?=$majorname?></td>
           </tr>
<?php
  }
?>
<?php
}
?>
		</table>