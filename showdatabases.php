<?php
    $con = mysql_connect("localhost","gsc","gsc1234");
    mysql_query("set names 'UTF8'");
	$res = mysql_query("show databases");
	while($row = mysql_fetch_row($res)){
	print_r($row);
	}
    //$db_selected = mysql_select_db("votesys", $con);
?>
