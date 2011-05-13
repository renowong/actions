<?php
$tasklist = gettasks($Cuser->userid,$_GET['project'],$_GET['order'],$_GET['dir'],$_GET['archive']);
/* functions */
function gettasks($user,$project=NULL,$order=NULL,$dir=NULL,$archive=NULL){
        $mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

	if (isset($project)) {$project = " AND `taches`.`project` = '".$project."'";};
	if (isset($order)) {$order= " ORDER BY `".$order."` ".$dir;};
	if ($archive==1) {$active= " AND `taches`.`active` = '0'";}else{$active= " AND `taches`.`active` = '1'";};


	$query = "SELECT `taches`.`userid`, `taches`.`tacheid`, `taches`.`date`, `taches`.`description`, `projects`.`title` AS `projecttitle`, `taches`.`title`, (SELECT `users`.`first` FROM `users` WHERE `taches`.`incharge` = `users`.`userid`) AS `inchargefirst`, (SELECT `users`.`last` FROM `users` WHERE `taches`.`incharge` = `users`.`userid`) AS `inchargelast`, (SELECT `users`.`first` FROM `users` WHERE `taches`.`userid` = `users`.`userid`) AS `ownerfirst`, (SELECT `users`.`last` FROM `users` WHERE `taches`.`userid` = `users`.`userid`) AS `ownerlast`, `taches`.`deadline`, `taches`.`progress` FROM `taches` LEFT JOIN `projects` ON `taches`.`project` = `projects`.`projectid` WHERE (`taches`.`userid` = '" . $user . "' OR `taches`.`incharge` = '" . $user ."')".$project.$active.$order;


	#echo $query;
        
	($archive==1 ? $archive=1 : $archive=0); //if this is archive then...
	if ($result = $mysqli->query($query)) {
		while ($row = $result->fetch_assoc()) {
			$countresult = $mysqli->query("SELECT `commentid` FROM `comments` WHERE `tacheid` = '" . $row["tacheid"] ."'");
			$count = $countresult->num_rows;
			$color = getcolor($row["deadline"],$row["progress"]);
			($_SESSION['userid']!==$row["userid"] ? $lock = true : $lock = false);
			$row["description"] = str_replace("'","&apos;",$row["description"]); //rectify the apostrophy
			$output .= "<tr id='tr".$row["tacheid"]."' title='<u>D&eacute;cision</u> : ".$row["description"]."' class='$color'>
			<td style='text-align:left;'>".$row["projecttitle"]."</td>
			<td style='text-align:left;'>".$row["title"]."</td>
			<td>".$row["ownerfirst"]." ".$row["ownerlast"]."</td>
			<td>".$row["inchargefirst"]." ".$row["inchargelast"]."</td>
			<td>".date("d-m-Y",strtotime($row["date"]))."</td>
			<td>".date("d-m-Y",strtotime($row["deadline"]))."</td>
			<td><a href='main.php?edit=".$row["tacheid"]."&archive=$archive&com=1'>".$count." <img src='images/b_disc.png'></a></td>
			<td style='text-align:left;padding-left:5px;'><img src='images/percentImage.png' alt='".$row["progress"]."%' class='percentImage' style='background-position: -".(120/100)*(100-$row["progress"])."px 0pt;' /> ".$row["progress"]."%</td>
			<td><a href='main.php?edit=".$row["tacheid"]."&archive=$archive'><img src='images/b_edit.png'></a>
			<a href='javascript:void(0)'>".($lock ? "" : "<img src='images/b_dele.png' id='".$row["tacheid"]."' class='b_dele'>")."</a></td></tr>";	
		$countresult->close();
		}
		$result->close();
	}


        //next get task incharge
        $query = "SELECT `suites`.`title` AS `ftitle`, `suites`.`suiteid`, `taches`.`userid`, `taches`.`tacheid`, `taches`.`date`, `taches`.`description`, `projects`.`title` AS `projecttitle`, `taches`.`title`, (SELECT `users`.`first` FROM `users` WHERE `taches`.`incharge` = `users`.`userid`) AS `inchargefirst`, (SELECT `users`.`last` FROM `users` WHERE `taches`.`incharge` = `users`.`userid`) AS `inchargelast`, (SELECT `users`.`first` FROM `users` WHERE `taches`.`userid` = `users`.`userid`) AS `ownerfirst`, (SELECT `users`.`last` FROM `users` WHERE `taches`.`userid` = `users`.`userid`) AS `ownerlast`, `taches`.`deadline`, `taches`.`progress` FROM `taches` LEFT JOIN `projects` ON `taches`.`project` = `projects`.`projectid` RIGHT JOIN `suites` ON `suites`.`tacheid` = `taches`.`tacheid` WHERE `suites`.`userid` = '" . $user . "' AND NOT `taches`.`userid` = '" . $user . "' AND NOT `taches`.`incharge` = '" . $user . "'".$project.$active.$order;

        //idem plus haut
        ($archive==1 ? $archive=1 : $archive=0); //if this is archive then...
        if ($result = $mysqli->query($query)) {
                        if($result->num_rows>0) $output.="<tr><td colspan=\"9\">----- pour suivi -----</td></tr>";
                while ($row = $result->fetch_assoc()) {
                        $countresult = $mysqli->query("SELECT `commentid` FROM `comments` WHERE `tacheid` = '" . $row["tacheid"] ."'");
                        $count = $countresult->num_rows;
                        $color = "bgblue";
                        ($_SESSION['userid']!==$row["userid"] ? $lock = true : $lock = false);
                        $row["description"] = str_replace("'","&apos;",$row["description"]); //rectify the apostrophy
                        $output .= "<tr id='tr".$row["tacheid"]."' title='<u>D&eacute;cision</u> : ".$row["description"]."' class='$color'>
                        <td style='text-align:left;'>".$row["projecttitle"]."</td>
                        <td style='text-align:left;'>".$row["title"]."<br/>===> ".$row["ftitle"]."</td>
                        <td>".$row["ownerfirst"]." ".$row["ownerlast"]."</td>
                        <td>".$row["inchargefirst"]." ".$row["inchargelast"]."</td>
                        <td>".date("d-m-Y",strtotime($row["date"]))."</td>
                        <td>".date("d-m-Y",strtotime($row["deadline"]))."</td>
                        <td><a href='main.php?edit=".$row["tacheid"]."&archive=$archive&com=1'>".$count." <img src='images/b_disc.png'></a></td>
                        <td style='text-align:left;padding-left:5px;'><img src='images/percentImage.png' alt='".$row["progress"]."%' class='percentImage' style='background-position: -".(120/100)*(100-$row["progress"])."px 0pt;' /> ".$row["progress"]."%</td>
                        <td><a href='main.php?edit=".$row["tacheid"]."&archive=$archive&followup=".$row["suiteid"]."'><img src='images/b_info.png'></a></td></tr>";
                $countresult->close();
                }
                $result->close();
        }

	//next get task in copy
	$query = "SELECT `taches`.`userid`, `taches`.`tacheid`, `taches`.`date`, `taches`.`description`, `projects`.`title` AS `projecttitle`, `taches`.`title`, (SELECT `users`.`first` FROM `users` WHERE `taches`.`incharge` = `users`.`userid`) AS `inchargefirst`, (SELECT `users`.`last` FROM `users` WHERE `taches`.`incharge` = `users`.`userid`) AS `inchargelast`, (SELECT `users`.`first` FROM `users` WHERE `taches`.`userid` = `users`.`userid`) AS `ownerfirst`, (SELECT `users`.`last` FROM `users` WHERE `taches`.`userid` = `users`.`userid`) AS `ownerlast`, `taches`.`deadline`, `taches`.`progress` FROM `taches` LEFT JOIN `projects` ON `taches`.`project` = `projects`.`projectid` WHERE `taches`.`copies` LIKE '%|" . $user . "|%' AND NOT `taches`.`incharge` = '" . $user . "'".$project.$active.$order;
	
	//idem plus haut
        ($archive==1 ? $archive=1 : $archive=0); //if this is archive then...
        if ($result = $mysqli->query($query)) {
			if($result->num_rows>0) $output.="<tr><td colspan=\"9\">----- en copie -----</td></tr>";
                while ($row = $result->fetch_assoc()) {
                        $countresult = $mysqli->query("SELECT `commentid` FROM `comments` WHERE `tacheid` = '" . $row["tacheid"] ."'");
                        $count = $countresult->num_rows;
                        $color = "bgyellow";
                        ($_SESSION['userid']!==$row["userid"] ? $lock = true : $lock = false);
                        $row["description"] = str_replace("'","&apos;",$row["description"]); //rectify the apostrophy
                        $output .= "<tr id='tr".$row["tacheid"]."' title='<u>D&eacute;cision</u> : ".$row["description"]."' class='$color'>
                        <td style='text-align:left;'>".$row["projecttitle"]."</td>
                        <td style='text-align:left;'>".$row["title"]."</td>
                        <td>".$row["ownerfirst"]." ".$row["ownerlast"]."</td>
                        <td>".$row["inchargefirst"]." ".$row["inchargelast"]."</td>
                        <td>".date("d-m-Y",strtotime($row["date"]))."</td>
                        <td>".date("d-m-Y",strtotime($row["deadline"]))."</td>
                        <td><a href='main.php?edit=".$row["tacheid"]."&archive=$archive&com=1'>".$count." <img src='images/b_disc.png'></a></td>
                        <td style='text-align:left;padding-left:5px;'><img src='images/percentImage.png' alt='".$row["progress"]."%' class='percentImage' style='background-position: -".(120/100)*(100-$row["progress"])."px 0pt;' /> ".$row["progress"]."%</td>
                        <td><a href='main.php?edit=".$row["tacheid"]."&archive=$archive'><img src='images/b_info.png'></a></td></tr>";
                $countresult->close();
                }
                $result->close();
        }

        $mysqli->close();

        return $output;
}

function getcolor($edate,$progress){
	$cdate = strtotime("now");
	$edate = strtotime($edate);
	$diff = ($edate - $cdate)/3600/24;
	switch ($diff) {
	case ($diff<1);
		$output = "bgred";
	break;
	case ($diff<3);
		$output = "bgorange";
	break;
	default;
		$output = "bgwhite";
	}
	if($progress==100){$output = "";}
	return $output;
}
?>
