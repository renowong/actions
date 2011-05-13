<?php
session_start();
include_once('config.php');

$mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	if($_POST['userid']==0){	
	$query = sprintf("INSERT INTO `actionfaaa`.`users` (
		`userid` ,
		`login` ,
		`password` ,
		`email` ,
		`level` ,
		`first` ,
		`last` ,
		`active` ,
		`lastsession`
		)
		VALUES (
		NULL , '%s', '%s', '%s', '%s', '%s', '%s', '%s', NULL
		)",
		$_POST['login'],
		MD5($_POST['password']),
		$_POST['email'],
		$_POST['level'],
		$_POST['firstname'],
		$_POST['lastname'],
		$_POST['active']);
		
		$mysqli->query($query);

	} else {
		$query = sprintf("UPDATE `actionfaaa`.`users` SET
                `login`='%s' ,
                `email`='%s' ,
                `level`='%s' ,
                `first`='%s' ,
                `last`='%s' ,
                `active`='%s'
		WHERE `userid`='%s'",
                $_POST['login'],
                $_POST['email'],
                $_POST['level'],
                $_POST['firstname'],
                $_POST['lastname'],
                $_POST['active'],
		$_POST['userid']);	

		$mysqli->query($query);

		if($_POST['password']!=="******"){
			$query = sprintf("UPDATE `actionfaaa`.`users` SET `password`='%s' WHERE `userid`='%s'",
			MD5($_POST['password']),
			$_POST['userid']);

			$mysqli->query($query);
		}
	}

	header('Location:users.php');
?>
