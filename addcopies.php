<?php
include_once('config.php');

$d = $_POST['copylist'];
$t = $_POST['taskid'];


addtodb($d,$t);

/* functions */
function addtodb($d,$t){
        $mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

        $query = "UPDATE `taches` SET `copies` = '" . $d . "' WHERE `tacheid` = '" . $t . "'";
        $mysqli->query($query);
        $mysqli->close();
}

?>
