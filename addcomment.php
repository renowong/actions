<?php
session_start();
include_once('config.php');

$mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	
	$query = sprintf("INSERT INTO `actionfaaa`.`comments` (
		`commentid` ,
		`tacheid` ,
		`userid` ,
		`date` ,
		`comment`
		)
		VALUES (
		NULL , '%s', '%s', '%s', '%s'
		)",
		$_POST['taskid'],
		$_POST['userid'],
		date("Y-m-d"),
		$_POST['addcomment']);

	#echo $query;

	$mysqli->query($query);

	$lastid=$mysqli->insert_id;

	$query = "SELECT `comments`.`comment`, `comments`.`date`, `users`.`last`, `users`.`first` FROM `comments` INNER JOIN `users` ON `comments`.`userid` = `users`.`userid` WHERE `comments`.`commentid` = '$lastid'";

	$result = $mysqli->query($query);
	$row = $result->fetch_assoc();

	$comment = $row["comment"];
	$date = $row["date"];
	$last = $row["last"];
	$first = $row["first"];

	$mysqli->close();

	//$to =  getemails($_POST['taskid']);

	//sendmail($to,getuser($_POST['userid']),gettache($_POST['taskid']),$_POST['addcomment']);


$response = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
        '       <response>'.
        '               <result>'.$lastid.'</result>'.
        '               <comment>'.$comment.'</comment>'.
        '               <date>'.$date.'</date>'.
        '               <last>'.$last.'</last>'.
        '               <first>'.$first.'</first>'.
        '       </response>';
        if(ob_get_length()) ob_clean();
        header('Content-Type: text/xml');
echo $response;



/* functions */
function getemails($tacheid){
        $mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

	$query = "SELECT `taches`.`userid`, `taches`.`incharge` FROM `taches` WHERE `taches`.`tacheid` = $tacheid";

	if ($result = $mysqli->query($query)) {
		$ids = $result->fetch_row();
		$result->close();
        }
        

	foreach ($ids as &$value){
		$query = "SELECT `users`.`email` FROM `actionfaaa`.`users` WHERE `userid` = '$value'";
		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$output .= $row["email"].";";
			}
			$result->close();
		}
	}

        $mysqli->close();

        return $output;
}

function gettache($tacheid){
        $mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

        $query = "SELECT `title` FROM `actionfaaa`.`taches` WHERE `tacheid` = $tacheid LIMIT 1";

       #echo $query;

        if ($result = $mysqli->query($query)) {
                while ($row = $result->fetch_assoc()) {
                        $output = $row["title"];
                }
                $result->close();
        }

        $mysqli->close();

        return $output;
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

function sendmail($to,$user,$title,$com){
        $from_name = "Action!Faa'a";
        $from_email = "cell.informatique@mairiefaaa.pf";
        $headers = "From: $from_name <$from_email>\nMIME-Version: 1.0\nContent-Type: text/html; charset=UTF-8\n";
        $body = "<b><u>Tâche :</b></u> $title<br/><b><u>Utilisateur :</b></u> $user<br/><b><u>Dit :</b></u> $com<br/><b><u>Application :</u></b><a href=\"".APPPATH."\"> ".APPPATH."</a>";
	$subject = "Nouveau commmentaire sur une tâche vous concernant";

        #if (mail($to, $subject, $body, $headers)) { //remark to prevent sendmail
        header('Location:main.php');
        #} else {
        #echo "Erreur d'envoi du email, veuillez en informer l'administrateur…";
        #}
}
?>
