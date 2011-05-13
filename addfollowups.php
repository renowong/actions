<?php
include_once('config.php');

$tid = $_POST['taskid'];
$fid = $_POST['followups'];
$t = $_POST['followuptitle'];
$i = $_POST['followupincharge'];
$owner = $_POST['taskuser'];
$incharge = $_POST['taskincharge'];
(($_POST['deadlinef']!='') ? $d = "'".dateconvert($_POST['deadlinef'])."'" : $d="NULL");
(($_POST['enddatef']!='') ? $e = "'".dateconvert($_POST['enddatef'])."'" : $e="NULL");
$lastid = addtodb($tid,$t,$i,$d,$e,$fid);
removefromcopies($tid,$i); //remove from copies if in charge of followup
//if($owner!=$i && $incharge!=$i) addtocopies($tid,$i);

$response = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
	'	<response>'.
	'		<result>'.$lastid.'</result>'.
	'	</response>';
	if(ob_get_length()) ob_clean();
	header('Content-Type: text/xml');
echo $response;



/* functions */
function addtodb($tid,$t,$i,$d,$e,$fid){
        $mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

        if($fid==0){
		$query = "INSERT INTO `actionfaaa`.`suites` ( `suiteid` , `tacheid` , `title` , `userid` , `deadline` , `enddate`) VALUES ( NULL , '$tid', '$t', '$i', $d, $e)";
        }else{
		$query = "UPDATE `actionfaaa`.`suites` SET `title` = '$t', `userid` = '$i', `deadline` = $d, `enddate` = $e WHERE `suiteid` = $fid";
	}
	$mysqli->query($query);

	return $mysqli->insert_id;
        $mysqli->close();

}

function removefromcopies($tid,$i){ 
	$mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }
	
	$query = "SELECT `copies` FROM `actionfaaa`.`taches` WHERE `tacheid` = $tid";
	        if ($result = $mysqli->query($query)) {
                $row = $result->fetch_row();
                $result->close();
        }

        $copiesdata = $row[0];
	$copiesdata = str_replace("|".$i."|", "|", $copiesdata);
	
	$query = "UPDATE `actionfaaa`.`taches` SET `copies` = '$copiesdata' WHERE `tacheid` = $tid";
	$mysqli->query($query);

        $mysqli->close();

}

function dateconvert($d){
	$d = explode("/", $d);   # break to format yyyy-mm-dd
        $d = date("Y-m-d", strtotime($d[2]."-".$d[1]."-".$d[0]));
	return $d;
}

?>
