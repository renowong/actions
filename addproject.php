<?php
session_start();
include_once('config.php');

$mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	
	if($_POST['projectid']==0) {
		$query = sprintf("INSERT INTO `actionfaaa`.`projects` (
		`projectid` ,
		`title` ,
		`description` ,
		`active`,
		`ownerid`
		)
		VALUES (
		NULL , '%s', '%s', '%s', '%s'
		)",
		$_POST['title'],
		$_POST['description'],
		1,
		$_POST['userid']);
		
	}else{
		$query = sprintf("UPDATE `actionfaaa`.`projects` SET `title` = '%s', `description` = '%s' WHERE `projectid` = %s",
		$_POST['title'],
		$_POST['description'],
		$_POST['projectid']
		);
	}

	#echo $query;

	$mysqli->query($query);

	header('Location:projects.php');
?>
