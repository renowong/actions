<?php
session_start();
include_once('config.php');

closesession($_SESSION['sessionid']);

function closesession($s){
        $mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

	$query = "UPDATE `actionfaaa`.`sessions` SET `active` = '0' WHERE `sessionid` = '" . $s . "'";
	$mysqli->query($query);
	$mysqli->close();
	session_destroy();
	header('Location:index.php');

}
?>
