<?php
include_once('../config.php');

$dossier = $_POST['byfolder'];
$incharge = $_POST['byincharge'];
$archive = $_POST['byarchive'];
$dmd_dp = dateconvert($_POST['popup_container_dmd_dp_cal'],"mysql");
$dmd_fin = dateconvert($_POST['popup_container_dmd_fin_cal'],"mysql");
$ech_dp = dateconvert($_POST['popup_container_ech_dp_cal'],"mysql");
$ech_fin = dateconvert($_POST['popup_container_ech_fin_cal'],"mysql");

if ($dmd_dp!=="*" && $dmd_fin!=="*") {$dmdbetween = " AND (`taches`.`date` BETWEEN '$dmd_dp' AND '$dmd_fin')";}
if ($ech_dp!=="*" && $ech_fin!=="*") {$echbetween = " AND (`taches`.`deadline` BETWEEN '$ech_dp' AND '$ech_fin')";}

$query = "SELECT `taches`.`tacheid`, `taches`.`priority`, `taches`.`date`, `taches`.`deadline`, (SELECT `projects`.`title` FROM `projects` WHERE `projects`.`projectid` = `taches`.`project`) AS `projecttitle`, `taches`.`title`, `taches`.`description`, (SELECT `users`.`first` FROM `users` WHERE `taches`.`userid` = `users`.`userid`) AS `ownerfirst`, (SELECT `users`.`last` FROM `users` WHERE `taches`.`userid` = `users`.`userid`) AS `ownerlast`, (SELECT `users`.`first` FROM `users` WHERE `taches`.`incharge` = `users`.`userid`) AS `inchargefirst`, (SELECT `users`.`last` FROM `users` WHERE `taches`.`incharge` = `users`.`userid`) AS `inchargelast`, `taches`.`progress` FROM `taches` WHERE `taches`.`project` LIKE '$dossier' AND `taches`.`incharge` LIKE '$incharge'$dmdbetween$echbetween";

$queryresult = retrievetask($query);

$response = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><tasks>'.$queryresult.'</tasks>';
if(ob_get_length()) ob_clean();
header('Content-Type: text/xml');

echo $response;

/* functions */
function retrievetask($q){
        $mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

        if ($result = $mysqli->query($q)) {
                while ($row = $result->fetch_assoc()) {
                        $output .= "	<task id='".$row['tacheid']."'>
					<priority>".$row['priority']."</priority>
					<date>".dateconvert($row['date'],"fr")."</date>
					<deadline>".dateconvert($row['deadline'],"fr")."</deadline>
					<project>".$row['projecttitle']."</project>
					<title>".$row['title']."</title>
					<description>".$row['description']."</description>
					<owner>".$row['ownerfirst']." ".$row['ownerlast']."</owner>
					<incharge>".$row['inchargefirst']." ".$row['inchargelast']."</incharge>
					<progress>".$row['progress']."</progress>";
			$output .= retrieveutasks($row['tacheid']);
			$output .= "	</task>";
                }
                $result->close();
        }

        $mysqli->close();
        return $output;

}

function retrieveutasks($id){
       $mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

	$q = "SELECT `suites`.`suiteid`, `suites`.`title`, (SELECT `users`.`first` FROM `users` WHERE `suites`.`userid` = `users`.`userid`) AS `inchargefirst`, (SELECT `users`.`last` FROM `users` WHERE `suites`.`userid` = `users`.`userid`) AS `inchargelast`, `suites`.`deadline`, `suites`.`enddate` FROM `suites` WHERE `suites`.`tacheid` = $id";	

        if ($result = $mysqli->query($q)) {
                while ($row = $result->fetch_assoc()) {
			$output .= "	<utask id='".$row['suiteid']."'>
					<title>".$row['title']."</title>
					<incharge>".$row['inchargefirst']." ".$row['inchargelast']."</incharge>
					<deadline>".dateconvert($row['deadline'],"fr")."</deadline>
					<enddate>".dateconvert($row['enddate'],"fr")."</enddate>
					</utask>";
                }
                $result->close();
        }

        $mysqli->close();
        return $output;

}

function dateconvert($date, $format) {
        if($date==''){
        return "*";
        }else{
		switch ($format) {
			case "mysql":
			$date = explode("/",$date);
			$date = date("Y-m-d", strtotime($date[2]."-".$date[1]."-".$date[0]));
			break;

			case "fr":
			$date = explode("-",$date);
			$date = date("d-m-Y", strtotime($date[2]."-".$date[1]."-".$date[0]));
			break;
		}
        }
        return $date;
}

?>

