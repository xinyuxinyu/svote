<?php
session_start();
if (!isset($_SESSION['normal'])){
    header('Content-Type:text/html; charset=UTF-8');
    echo "错误!没有权限!";
    exit(0);
}

include_once("conn.php");
include_once("header.php");

$proj = $_SESSION['proj'];
$name = $_SESSION['name'];
$uid  = $_SESSION['userid'];

$sql = "SELECT value FROM ${proj}_stack WHERE name='number'";
$res = mysql_query($sql);
$row = mysql_fetch_row($res);
$number = $row[0];

$sql = "SELECT value FROM ${proj}_stack WHERE name='maxvotenum'";
$res = mysql_query($sql);
$row = mysql_fetch_row($res);
$maxvotenum = $row[0];

include_once("user-header.php");
?>
	<div id="voterid" style="display:none;"><?=$uid?></div>
	<div id="maxvotenum" style="display:none;"><?=$maxvotenum?></div>
	<div class="container-fluid">
        <div class="container">
			<table width="100%">
				<tr>
					<td>
				        <h1 class="page-header"><span id="show-proj-name"><?=$proj?></span>，第<span id="number"><?=$number?></span>次投票</h1>
					</td>
					<td class="text-center" style="font-size: 20px;">
						尊敬的<?=$name?>，您好
					</td>
				</tr>
			</table>

<?php
$sql = "SELECT * FROM ${proj}_stat${number} WHERE voterid='${uid}'";
$res = mysql_query($sql);
if($row = mysql_fetch_row($res)){
?>
			<h3 class="page-header">已完成投票，无法继续查看，谢谢！</h3>
<?php
exit();
}

$sql = "SELECT value FROM ${proj}_stack WHERE name='voterzige'";
$res = mysql_query($sql);
$row = mysql_fetch_row($res);
$voterzige = $row[0];

$sql = "SELECT value FROM ${proj}_stack WHERE name='votedzige'";
$res = mysql_query($sql);
$row = mysql_fetch_row($res);
$votedzige = $row[0];

?>
			<table width="100%" class="table table-bordered vert-align">
				<tbody>
					<tr>
						<th class="text-center" width="100px">选举人资格</th>
						<td><?=$voterzige?></td>
					</tr>
					<tr>
						<th class="text-center">候选人资格</th>
						<td><?=$votedzige?></td>
					</tr>
				</tbody>
			</table>

			<table width="80%" class="table table-bordered vert-align">
				<thead>
					<th colspan="100%" class="text-center">人员名单</th>
				</thead>
				<thead>
					<th class="text-center" width="80px">学科</th>
					<th class="text-center" width="50px">序号</th>
					<th class="text-center" width="160px">研究员姓名</th>
					<th class="text-center" width="120px">导师资格</th>
					<th class="text-center" width="80px">年龄</th>
					<th class="text-center" width="140px">出生日期</th>
					<th class="text-center">备注</th>
					<th class="text-center" width="80px">投票</th>
					<th class="text-center" width="80px">状态</th>
				</thead>

				<tbody>
<?php
$sql = "SELECT major,num,id,nmin FROM ${proj}_majors ORDER BY id";
$res = mysql_query($sql);
$color = 0;
while($row = mysql_fetch_row($res)){
  $major = $row[0];
  $num   = $row[1];
  $id    = $row[2];
  $nmin  = $row[3];

  $color++;
  if($color%2 == 1){
	$clstr = '#FFFFFF';
  }else{
	$clstr = '#BDBDBD';
  }

  $sqlin = "SELECT COUNT(*) FROM ${proj}_voteds WHERE majorid=${id}";
  $resin = mysql_query($sqlin);
  $rowin = mysql_fetch_row($resin);

  $selectperson = $rowin[0];
  $rowspan = $selectperson + 1;
?>
					<tr style="background:<?=$clstr?>;">
<?php
  if ($maxvotnum == 0) {
    #echo "<th rowspan=$rowspan class='text-center'><span id='majorname-$color'>$major</span><br>(共 $selectperson 人，<span style='color:green'>推荐 $num 人</span>，<span style='color:red'>需推选<span id='nmin-$color'> $nmin </span>人</span>)</th>";
    echo "<th rowspan=$rowspan class='text-center'><span id='majorname-$color'>$major</span><br>(共 $selectperson 人，<span style='color:red'>需投<span id='nmin-$color'> $nmin </span>票</span>)</th>";
  } else {
    echo "<th rowspan=$rowspan class='text-center'><span id='majorname-$color'>$major</span><br>(共 $selectperson 人，<span style='color:green'>建议推选 $num 人</span>，<span style='color:red'>最少推选<span id='nmin-$color'> $nmin </span>人</span>)</th>";
  }
?>
						<td style="display: none;" id="majorid-<?=$color?>"><?=$id?></td>
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
    $sqlin = "SELECT ${voteddb}.id,${voteddb}.name,${voteddb}.zige,${voteddb}.year,${voteddb}.note,${voteddb}.date,${judgedb}.id FROM
${voteddb} LEFT JOIN ${judgedb} ON ${voteddb}.majorid=${judgedb}.majorid and ${judgedb}.voterid=${uid} and ${voteddb}.id=${judgedb}.votedid WHERE ${voteddb}.majorid=${id} ORDER BY ${voteddb}.id";
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
	  $judgeid = $rowin[6]; if (is_null($judgeid)) $voteval=0;
	  $count++;
?>
						<tr style="background:<?=$clstr?>;">
							<td class="text-center"><?=$count?></td>
							<td class="text-center" id="voteds-name-<?=$id?>-<?=$idin?>"><?=$name?></td>
							<td class="text-center" id="voteds-zige-<?=$id?>-<?=$idin?>"><?=$zige?></td>
							<td class="text-center" id="voteds-year-<?=$id?>-<?=$idin?>"><?=$year?></td>
							<td class="text-center" id="voteds-date-<?=$id?>-<?=$idin?>"><?=$date?></td>
							<td class="text-center" id="voteds-note-<?=$id?>-<?=$idin?>"><?=$note?></td>
							<td class="text-center"><input id="voteds-vote-<?=$id?>-<?=$idin?>" type="checkbox" value=<?=$voteval?> <?php if($voteval==1) echo "checked"; ?>></td>
							<td class="text-center" id="voteds-vote-<?=$id?>-<?=$idin?>-stat"></td>
						</tr>
<?php
	}
}
?>
				</tbody>
			</table>
			<table width="100%">
				<tr>
  <td align="left" id='info'><h4>已投票学科数=总学科数，总投票数<=票数上限：<span id="currentmajornum"></span>=<span id="maxmajornum" class="unfinish"><?=$color?></span>，<span id="totvotenum"></span><=<span class="unfinish"><?=$maxvotenum?></span>(<span id="submit-status"></span>)</h4></td>
					<td align="right"><button class="btn btn-primary" id="submit">提交投票</button></td>
				<tr>
			</table>

<?php
$sql = "SELECT value FROM ${proj}_note WHERE name='note'";
$res = mysql_query($sql);
$row = mysql_fetch_row($res);
$note = $row[0];
?>
			<table width="100%" style="margin-top: 30px;">
				<thead>
					<th>备注：</th>
				</thead>
				<tbody>
					<tr>
						<td id="beizhuuser" width="100%"><?=$note?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
