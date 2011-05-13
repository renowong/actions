<?php
session_start();
include_once('config.php');

$mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	
	$deadline = explode("/", $_POST['deadline']);	# break to format yyyy-mm-dd
	$deadline = date("Y-m-d", strtotime($deadline[2]."-".$deadline[1]."-".$deadline[0]));
	$datedmd = explode("/", $_POST['date']);	# break to format yyyy-mm-dd
	$datedmd = date("Y-m-d", strtotime($datedmd[2]."-".$datedmd[1]."-".$datedmd[0]));

	($_POST['submittype']=="Archiver" ? $active=0 : $active=1);
	if($_POST['enddate']===""){
		$enddate="NULL";
	} else{
		$enddate = explode("/", $_POST['enddate']);
		$enddate = "'".date("Y-m-d", strtotime($enddate[2]."-".$enddate[1]."-".$enddate[0]))."'";
	}
	
	if($_POST['taskid']==0){
		$type = "Nouvelle Tâche"; //for the email
		$query = sprintf("INSERT INTO `actionfaaa`.`taches` (
		`tacheid` ,
		`date` ,
		`deadline` ,
		`project` ,
		`title` ,
		`userid` ,
		`incharge` ,
		`description` ,
		`progress` ,
		`active`,
		`enddate`
		)
		VALUES (
		NULL , '%s', '%s', '%d', '%s', '%d', '%d', '%s', '%d', '%d', %s
		)",
		$datedmd,
		$deadline,
		$_POST['project'],
		$_POST['title'],
		$_SESSION['userid'],
		$_POST['incharge'],
		$_POST['description'],
		$_POST['progress'],
		$active,
		$enddate);
	}else{
		$type = "Modification de Tâche"; //for the email
		$query = sprintf("UPDATE `actionfaaa`.`taches` SET 
                `date` = '%s',
                `deadline` = '%s',
                `project` = '%d',
                `title` = '%s',
                `incharge` = '%s',
                `description` = '%s',
                `progress` = '%s',
                `active` = '%s',
		`enddate` = %s WHERE `tacheid` = '%s'",
		$datedmd,
		$deadline,
                $_POST['project'],
                $_POST['title'],
                $_POST['incharge'],
                $_POST['description'],
                $_POST['progress'],
		$active,
                $enddate,
		$_POST['taskid']);
	}
	#echo $query;
	
	$mysqli->query($query);
	$sendto = ($_POST['userid']==0 ? $_SESSION['userid'] : $_POST['userid']);

	sendmail(getemail($_POST['incharge'])."; ".getemail($sendto),getuser($sendto),getuser($_POST['incharge']),$type,$_POST['title'],$_POST['description'],date("Y-m-d", strtotime($deadline[2]."-".$deadline[1]."-".$deadline[0])),$_POST['progress'],$active);



function sendmail($to,$user,$incharge,$subject,$title,$description,$deadline,$progress,$archive){
	$from_name = "Action!Faa'a";
	$from_email = "cell.informatique@mairiefaaa.pf";
	$headers = "From: $from_name <$from_email>\nMIME-Version: 1.0\nContent-Type: text/html; charset=UTF-8\n";
	$body = "<b><u>Tâche :</b></u> $title<br/><b><u>Description :</b></u> ".str_replace('\\','',$description)."<br/><b><u>Date due :</b></u> ".date("d/m/Y", strtotime($deadline))."<br/><b><u>Propri&eacute;taire de la t&acirc;che :</b></u> $user<br/><b><u>Assign&eacute; :</b></u> $incharge<br/><b><u>Avancement :</u></b> $progress%<br/><b><u>Application :</u></b><a href=\"".APPPATH."\"> ".APPPATH."</a>";

	($archive==0 ? $subject .= " (ARCHIVAGE)" : "");
	#if (mail($to, $subject, $body, $headers)) { //remark to prevent sendmail
	header('Location:main.php');
	#} else {
	#echo "Erreur d'envoi du email, veuillez en informer l'administrateur…";
	#}
}

function getemail($userid){
        $mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

        $query = "SELECT `email` FROM `actionfaaa`.`users` WHERE `userid` = $userid LIMIT 1";

#       echo $query;

        if ($result = $mysqli->query($query)) {
                while ($row = $result->fetch_assoc()) {
                        $output = $row["email"];
                }
                $result->close();
        }

        $mysqli->close();

        return $output;
}

function getuser($userid){
        $mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

        $query = "SELECT `first`,`last` FROM `actionfaaa`.`users` WHERE `userid` = $userid LIMIT 1";

#       echo $query;

        if ($result = $mysqli->query($query)) {
                while ($row = $result->fetch_assoc()) {
                        $output = $row["first"]." ".$row["last"];
                }
                $result->close();
        }

        $mysqli->close();

        return $output;
}

?>
