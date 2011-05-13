<?php
include_once('config.php');
$treedaysleft = date("Y-m-d", strtotime("+3 days"));
getdaysleft($treedaysleft,false);

$duetoday = date("Y-m-d");
getdaysleft($duetoday,true);



/* functions */

function getdaysleft($d,$dday){
        $mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

        $query = "SELECT `taches`.`userid`, `taches`.`tacheid`, `taches`.`priority`, `taches`.`description`, `projects`.`title` AS `projecttitle`, `taches`.`title`, (SELECT `users`.`first` FROM `users` WHERE `taches`.`incharge` = `users`.`userid`) AS `inchargefirst`, (SELECT `users`.`last` FROM `users` WHERE `taches`.`incharge` = `users`.`userid`) AS `inchargelast`, (SELECT `users`.`email` FROM `users` WHERE `taches`.`incharge` = `users`.`userid`) AS `inchargeemail`, (SELECT `users`.`first` FROM `users` WHERE `taches`.`userid` = `users`.`userid`) AS `ownerfirst`, (SELECT `users`.`last` FROM `users` WHERE `taches`.`userid` = `users`.`userid`) AS `ownerlast`, `taches`.`deadline`, `taches`.`progress` FROM `taches` LEFT JOIN `projects` ON `taches`.`project` = `projects`.`projectid` WHERE `taches`.`deadline` = '" . $d . "' AND `taches`.`active` = '1'";

        echo $query;
	($dday ? $subject="Tâche dûe aujourd'hui!" : $subject="Tâche dûe dans 3 jours");	

	if ($result = $mysqli->query($query)) {
		while ($row = $result->fetch_assoc()) {
			sendmail($row['inchargeemail'],$row['ownerfirst']." ".$row['ownerlast'],$row['inchargefirst']." ".$row['inchargelast'],$subject,$row['projecttitle'],$row['title'],$row['description'],$row['deadline'],$row['progress']);
		}
		$result->close();
	}

        $mysqli->close();
        #return $row;
}

function sendmail($to,$user,$incharge,$subject,$project,$title,$description,$deadline,$progress){
        $from_name = "Action!Faa'a";
        $from_email = "cell.informatique@mairiefaaa.pf";
        $headers = "From: $from_name <$from_email>\nMIME-Version: 1.0\nContent-Type: text/html; charset=UTF-8\n";
        $body = "<b><u>Projet :</b></u> $project<br/><b><u>Tâche :</b></u> $title<br/><b><u>Description :</b></u> ".str_replace('\\','',$description)."<br/><b><u>Date due :</b></u> ".date("d/m/Y", strtotime($deadline))."<br/><b><u>Propri&eacute;taire de la t&acirc;che :</b></u> $user<br/><b><u>Assign&eacute; :</b></u> $incharge<br/><b><u>Avancement :</u></b> $progress%<br/><b><u>Application :</u></b><a href=\"".APPPATH."\"> ".APPPATH."</a>";

        mail($to, $subject, $body, $headers);
}


?>
