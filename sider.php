<?php
include("varibles.php");
$sql = "SELECT value FROM ${stack} WHERE name='inuse'";
$res = mysql_query($sql);
$row = mysql_fetch_row($res);
$inuse = $row[0];

$sql = "SELECT value FROM ${stack} WHERE name='current'";
$res = mysql_query($sql);
$row = mysql_fetch_row($res);
$current = $row[0];

$sql = "SELECT name FROM ${projs} ORDER BY id DESC";
$res = mysql_query($sql);

$count = 0;
while($row = mysql_fetch_row($res)){
	$projname = $row[0];
?>

<?php
	if($inuse == $projname){
?>
			<li class="active"><a href="#"><?=$projname?></a></li>
<?php
	}else{
		if($current == $projname){
?>
			<li class="current"><a href="#"><?=$projname?></a></li>
<?php
		}else{
?>
			<li><a href="#"><?=$projname?></a></li>
<?php
		}
	}
}
?>
