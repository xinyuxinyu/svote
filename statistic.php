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
		<h1 class="page-header"><?=$proj?>，第<?=$number?>次投票结果</h1>
		<table width="80%" class="table table-bordered vert-align">
			<thead>
				<th class="text-center" width="80px">学科</th>
				<th class="text-center" width="50px">序号</th>
				<th class="text-center" width="160px">姓名</th>
				<th class="text-center" width="120px">组别</th>
				<th class="text-center" width="80px">志愿</th>
				<th class="text-center" width="140px">文章总数</th>
				<th class="text-center">备注</th>
				<th class="text-center" width="80px">票数</th>
			</thead>		
			<tbody>
<?php
$sql = "SELECT major,num,id FROM ${proj}_majors ORDER BY id";
$res = mysql_query($sql);
$color = 0;
while($row = mysql_fetch_row($res)){
  $major = $row[0];
  $num   = $row[1];
  $id    = $row[2];

  $color++;
  if($color%2 == 1){
	$clstr = '#FFFFFF';
  }else{
	$clstr = '#BDBDBD';
  }

  $sqlin = "SELECT COUNT(*) FROM ${proj}_voteds WHERE majorid=${id}";
  $resin = mysql_query($sqlin);
  $rowin = mysql_fetch_row($resin);
  $rowspan = $rowin[0] + 1;
?>
				<tr style="background:<?=$clstr?>;">
					<th rowspan="<?=$rowspan?>" class="text-center"><?=$major?></th>
					<td style="display: none;"></td>
					<td style="display: none;"></td>
					<td style="display: none;"></td>
					<td style="display: none;"></td>
					<td style="display: none;"></td>
					<td style="display: none;"></td>
					<td style="display: none;"></td>
					<td style="display: none;"></td>
				</tr>
<?php
    $voteddb = "${proj}_voteds";
    $judgedb = "${proj}_judge${number}";
    $statdb  = "${proj}_stat${number}";
    $sqlin = "SELECT ${voteddb}.id,${voteddb}.name,${voteddb}.zige,${voteddb}.year,${voteddb}.note,${voteddb}.date,
(SELECT COUNT(*) FROM ${judgedb} WHERE ${judgedb}.votedid=${voteddb}.id
and EXISTS (SELECT ${statdb}.voterid FROM ${statdb} WHERE ${statdb}.voterid=${judgedb}.voterid)) as votenum
FROM ${voteddb}
WHERE ${voteddb}.majorid='${id}'
ORDER BY votenum DESC";
    $resin = mysql_query($sqlin);
	$count = 0;
    while($rowin = mysql_fetch_row($resin)){
	  $voteval = 1;
	  $idin = $rowin[0];
	  $name = $rowin[1];
	  $zige = $rowin[2];
      $year = $rowin[3];
      $note = $rowin[4];
      $date = $rowin[5];
	  $votenum = $rowin[6];
	  $count++;
?>
				<tr style="background:<?=$clstr?>;">
					<td class="text-center"><?=$count?></td>
					<td class="text-center"><?=$name?></td>
					<td class="text-center"><?=$zige?></td>
					<td class="text-center"><?=$year?></td>
					<td class="text-center"><?=$date?></td>
					<td class="text-center"><?=$note?></td>
					<td class="text-center"><?=$votenum?></td>
				</tr>
<?php
	}
}
?>
			</tbody>
		</table>
		<table width="100%">
			<tr>
				<td align="left">
					<h3>备注：本次投票，投票人共计<?=$maxvoters?>人，实际投票<?=$numvoters?>人。</h3>
				</td>
				<td>
  					<a href="details.php?id=<?=$number?>" style="font-size:20px" target="_blank">投票明细</a>
				</td>
			</tr>
		</table>
	</div>
</div>
