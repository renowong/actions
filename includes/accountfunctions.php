<?php
include_once('config.php');

if (isset($_GET['delete'])) {deletetask($_GET['delete']);}

/* functions */
function deletetask($u){
	$mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

        $query = "DELETE FROM `users` WHERE `userid` = '" . $u . "'";
        $mysqli->query($query);
        $mysqli->close();
}
?>
