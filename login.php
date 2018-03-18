<?php
    header('Content-Type:text/html; charset=utf-8');
    session_start();
    session_unset();

    include("conn.php");
    include("varibles.php");

    $username = $_POST['username'];
    $password = $_POST['password'];

	$mps = md5($password);
	$sql = "SELECT * FROM ${users} WHERE username='${username}'"; 
	$res = mysql_query($sql);

	if($row = mysql_fetch_row($res)){
		$sps = $row[2];
		if($sps == $mps){
			$_SESSION['username'] = $username;
			$_SESSION['admin']    = "admin";
			header("Location: admin.php");
		}else{
			echo "错误! 用户:${username} 密码错误！";
			exit();
		}
	}else{
        $sql = "SELECT value FROM ${stack} WHERE name='inuse'";
        $res = mysql_query($sql);
		$row = mysql_fetch_row($res);
        $proj = $row[0];

		if($proj == ''){
		  echo "目前还没有投票任务开始！请联系管理员。";
		  exit();
		}

        $dbbase = $proj."_voters";

	    $sql = "SELECT * FROM $dbbase WHERE account='${username}'"; 
	    $res = mysql_query($sql);
        if($row = mysql_fetch_row($res)){
        	$uid = $row[0];
		$name = $row[1];
		$sps = $row[3];
		if($sps != '' && $sps != $mps){
	        	echo "错误! 用户:${username} 密码错误！";
        	exit();
        }

		if($sps == ''){
			$userx=explode("@dicp.ac.cn",$username);
			$url="http://ms.dicp.ac.cn/emailcheck.php?TEmail=${userx[0]}&TPass=${password}";
			$f=file_get_contents($url)+0;

			if($f != 0){
		        	echo "错误! 用户:${username} 密码错误！";
	        	exit();
		}
	}

	    	$_SESSION['username'] = $username;
	        $_SESSION['normal']   = "normal";
	        $_SESSION['userid']   = $uid;
	        $_SESSION['proj']     = $proj;
	        $_SESSION['name']     = $name;
        	header("Location: user.php");
        }else{
			echo "错误! 用户:${username} 不存在。";
			exit();
		}
    } 
?>
