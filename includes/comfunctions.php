<?php
include_once('../config.php');

deletecom($_GET['deletecom']);

$response = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
        '       <response>'.
        '               <result>'.$_GET['deletecom'].'</result>'.
        '       </response>';
        if(ob_get_length()) ob_clean();
        header('Content-Type: text/xml');
echo $response;

/* functions */
function deletecom($c){
	$mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

        $query = "DELETE FROM `comments` WHERE `commentid` = '" . $c . "'";
        $mysqli->query($query);
        $mysqli->close();

}
?>
