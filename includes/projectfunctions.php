<?php
include_once('config.php');

if (isset($_GET['delete'])) {deleteproject($_GET['delete']);}

/* functions */
function deleteproject($p){
	$mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

        $query = "DELETE FROM `projects` WHERE `projectid` = '" . $p . "'";
        $mysqli->query($query);
        $query = "UPDATE `taches` SET `project` = 0 WHERE `project` = '" . $p . "'";
        $mysqli->query($query);
        $mysqli->close();
}
?>
