<?php
include_once('config.php');

if (isset($_GET['delete'])) {deletetask($_GET['delete']);}

if (isset($_GET['deletef'])) {deletefollowup($_GET['deletef']);}
/* functions */
function deletetask($t){
	$mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

        $query = "DELETE FROM `taches` WHERE `tacheid` = '" . $t . "'";
        $mysqli->query($query);
	
	$query = "DELETE FROM `suites` WHERE `tacheid` = '" . $t . "'";
	$mysqli->query($query);
        $mysqli->close();
}


function deletefollowup($f){
        $mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

        $query = "DELETE FROM `suites` WHERE `suiteid` = '" . $f . "'";
        $mysqli->query($query);
        $mysqli->close();
}

?>
