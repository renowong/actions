<?php
/* headers */
session_start();
include_once('config.php');
if (isset($_GET['sessionid'])) {$_SESSION['sessionid']=$_GET['sessionid'];}
checksession($_SESSION['sessionid']);


/* init */
include_once('class_user.php');
$aruser =  getuserinfo($_SESSION['sessionid']);

$Cuser = new User;
$Cuser->userid = $aruser[0];
$_SESSION['userid'] = $aruser[0];
$Cuser->login = $aruser[1];
$Cuser->email = $aruser[3];
$Cuser->level = $aruser[4];
$Cuser->first= $aruser[5];
$Cuser->last= $aruser[6];

$logoninfo = "<div id=\"logoninfo\"><img id=\"logo\" src=\"images/actionsmini.png\" />Utilisateur : $Cuser->first $Cuser->last | <input onClick=\"javascript:window.location.href='logout.php';\" type=\"button\" value=\"Quitter\"/><br/>Date du Jour : ".date("d/m/Y")."</div>";
/* functions */
function getuserinfo($s){
	$mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	$query = "SELECT userid FROM `sessions` " .
		"WHERE `sessionid` = '" . $s . "'";
	
	if ($result = $mysqli->query($query)) {
                $row = $result->fetch_row();
                $result->close();
        }

	$query = "SELECT * FROM `users` " .
                "WHERE `userid` = '" . $row[0] . "'";
        
        if ($result = $mysqli->query($query)) {
                $row = $result->fetch_row();
                $result->close();
        }

	$mysqli->close();
	return $row;
}


function checksession($s){
	$mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	$query = "SELECT datetime FROM `sessions` " .
		"WHERE `sessionid` = '" . $s . "' AND `active` = 1";

	if ($result = $mysqli->query($query)) {
		$row = $result->fetch_row();
		$result->close();
	}

	$lastdate = $row[0];
	$date = date('Y-m-d H:i:s');

	$diffsec = strtotime($date) - strtotime($lastdate);
	$diffmin = $diffsec / 60;

#	echo $diffmin;
	if ($diffmin>60) {
		$query = "UPDATE `actionfaaa`.`sessions` SET `active` = '0' WHERE `sessionid` = '" . $s . "'";
		$mysqli->query($query);
		$mysqli->close();
		header('Location:index.php?expired=1');
	} else {
		$query = "UPDATE `actionfaaa`.`sessions` SET `datetime` = '".date('Y-m-d H:i:s')."' WHERE `sessionid` = '" . $s . "'";
		$mysqli->query($query);
		$mysqli->close();
	}

#	echo $diffmin;
}
?>
