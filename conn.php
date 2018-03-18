<?php
    $con = mysql_connect("localhost","gsc","gsc1234");
    mysql_query("set names 'UTF8'");
    $db_selected = mysql_select_db("votesys", $con);
?>
