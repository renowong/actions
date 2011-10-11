<?php

include_once('includes/class_task.php');
//include_once('includes/class_followups.php');
if (($_GET['edit'])>0){
	$artask =  gettaskinfo($_GET['edit']);
	$cTask = new Task;
	$cTask->date = date("d/m/Y",strtotime($artask[1]));
	$cTask->deadline = date("d/m/Y",strtotime($artask[2]));
	$cTask->project = $artask[3];
	$cTask->title = $artask[4];
	$cTask->description = $artask[5];
	$cTask->incharge = $artask[6];
	$cTask->progress = $artask[7];
	$cTask->comments = getcominfo($_GET['edit'],$_SESSION['userid'],$_GET['archive']);
	($artask[8]==0 ? $cTask->active = " checked" : $cTask->active = "");
	$cTask->userid = $artask[9];
	($artask[10]!==NULL ? $cTask->enddate=date("d/m/Y",strtotime($artask[10])) : $cTask->enddate='');
	$cTask->copies = $artask[11];


	$arfollowup = getfollowupinfo($_GET['edit']);
	
}

if ($_GET['com']==1) {$tabcom = " tabbertabdefault";}

if (($_GET['edit']>0) && ($_SESSION['userid']!==$cTask->userid)) {$lock = true;}
if (preg_match('/\|'.$_SESSION['userid'].'\|/', $cTask->copies)) {$lockinfo = true;};
if ((($_SESSION['userid']!==$cTask->userid)) && ($_SESSION['userid']!==$cTask->incharge)) {$lockfup = true;};



$addtask = "

<div id=\"newtaskdiv\">
<div class=\"tabber\">
<div class=\"tabbertab\">
<h3>G&eacute;n&eacute;ralit&eacute;s</h3>
	<form method=\"post\" action=\"addtask.php\" name=\"frmadd\" id=\"frmadd\">
	<table id=\"newtasktable\">
		<tr>
			<th>Date dmd <img src=\"images/lock.png\" class=\"".($lock ? "" : "hidden")."\" border=\"0\" alt=\"Verrouill&eacute;\" /></th>
                        <td>
				<input name=\"date\" id=\"date\" type=\"text\" size=\"10\" value=\"".($cTask->date=='' ? date('d/m/Y') : $cTask->date)."\" readonly/>".($lock ? "" : " <a href=\"javascript:NewCal('date','ddmmyyyy');\"><img src=\"images/calendar.png\" border=\"0\" alt=\"Choississez une date\"></a>")."
                        </td>
			<th>Dossier <img src=\"images/lock.png\" class=\"".($lock ? "" : "hidden")."\" border=\"0\" alt=\"Verrouill&eacute;\" /></th>
			<td>
				<select name=\"project\"".($lock ? " onFocus='this.oldIndex=this.selectedIndex;' onchange='javascript:this.selectedIndex=this.oldIndex;'" : "").">
					<option value=\"0\">Sans Dossier</option>";
$addtask .= buildprojectslist($cTask->project,$Cuser->userid,$_GET['edit']); //list all projects
$addtask .= "			</select>
			</td>
		</tr>
		<tr>
			<th>Situation <img src=\"images/lock.png\" class=\"".($lock ? "" : "hidden")."\" border=\"0\" alt=\"Verrouill&eacute;\" /></th>
			<td colspan=\"3\">
			<div id=\"wrapper\"><div id=\"content\">
			<div>
			<form method=\"get\" action=\"\" class=\"asholder\">
			<input type=\"hidden\" id=\"testid\" value=\"\" style=\"font-size: 10px; width: 20px;\" disabled=\"disabled\" />
			</form>
			</div></div></div>
			<input name=\"title\" id=\"title\" type=\"text\" size=\"40\" maxlength=\"50\" value=\"$cTask->title\"".($lock ? " readonly" : "")."/></td>
		</tr>
		<tr>
			<th>Action/D&eacute;cision <img src=\"images/lock.png\" class=\"".($lock ? "" : "hidden")."\" border=\"0\" alt=\"Verrouill&eacute;\" /></th>
			<td colspan=\"3\"><textarea cols=\"40\" rows=\"4\" name=\"description\"".($lock ? " readonly" : "").">$cTask->description</textarea>
			 </td>
		</tr>
		<tr>
			<th>Responsable <img src=\"images/lock.png\" class=\"".($lock ? "" : "hidden")."\" border=\"0\" alt=\"Verrouill&eacute;\" /></th>
			<td>
				<select name=\"incharge\" id=\"incharge\"".($lock ? " onFocus='this.oldIndex=this.selectedIndex;' onchange='javascript:this.selectedIndex=this.oldIndex;'" : "").">";
$addtask .= buildinchargelist($cTask->incharge); 
$addtask .= "			</select>
			</td>
			<th>&Eacute;ch&eacute;ance <img src=\"images/lock.png\" class=\"".($lock ? "" : "hidden")."\" border=\"0\" alt=\"Verrouill&eacute;\" /></th>
                        <td><input name=\"deadline\" id=\"deadline\" type=\"text\" size=\"10\" value=\"".($cTask->deadline=='' ? date('d/m/Y') : $cTask->deadline)."\" readonly/>".($lock ? "" : " <a href=\"javascript:NewCal('deadline','ddmmyyyy');\"><img src=\"images/calendar.png\" border=\"0\" alt=\"Choississez une date\"></a>")."</td>
		</tr>
		<tr>
			<th>Avancement <img src=\"images/lock.png\" class=\"".($lockinfo ? "" : "hidden")."\" border=\"0\" alt=\"Verrouill&eacute;\" /></th>
                        <td>
                                <select name=\"progress\" id=\"progress\"".($lockinfo ? " onFocus='this.oldIndex=this.selectedIndex;' onchange='javascript:this.selectedIndex=this.oldIndex;'" : "").">";
$addtask .= buildpercentage($cTask->progress);
$addtask .= "
                                </select>

                        </td>
			<th>Fait le <img src=\"images/lock.png\" id=\"enddatelock\" class=\"".($lockinfo ? "" : "hidden")."\" border=\"0\" alt=\"Verrouill&eacute;\" /></th>
			<td><input name=\"enddate\" id=\"enddate\" type=\"text\" size=\"10\" value=\"".($cTask->enddate=='' ? '' : $cTask->enddate)."\" readonly/><a href=\"javascript:NewCal('enddate','ddmmyyyy');\"> <img src=\"images/calendar.png\" id=\"imgenddate\" class=\"hidden\" border=\"0\" alt=\"Choississez une date\" /></a></td>
		</tr>
		<tr>
			<td colspan=\"5\">
				<input type=\"hidden\" id=\"owner\" value=\"".($lock ? 0 : 1)."\"/>
				<input type=\"hidden\" name=\"userid\" value=\"".(isset($cTask->userid) ? $cTask->userid : 0)."\"/>
				<input type=\"hidden\" name=\"taskid\" value=\"".$_GET['edit']."\"/>
				<input type=\"button\" class=\"cancel\" value=\"Fermer\" onclick=\"javascript:window.location.href='main.php?archive=".$_GET['archive']."';\"/> <input type=\"submit\" id=\"enregistrer\" name=\"submittype\" class=\"hidden\" value=\"Enregistrer\" ".($lockinfo ? "disabled" : "")." /> <input type=\"submit\" id=\"archiver\" name=\"submittype\" class=\"hidden\" value=\"Archiver\"/>
			</td>
		</tr>
	</table>
	</form>
</div>";
/* Add followups tab */
if ($_GET['edit']>0) {
$addtask .= "<div class=\"tabbertab\" title=\"Suite à donner\">
        <form method=\"post\" action=\"addfollowups.php\" name=\"frmaddfollowup\" id=\"frmaddfollowup\">
	<!-- Hidden informations --><div id=\"hiddendata\">";
	for ($i=0;$i<count($arfollowup);$i++){
		$hiddeninfo .= "<input type=\"hidden\" name=\"followuptitle$i\" id=\"followuptitle$i\" value=\"".$arfollowup[$i][1]."\">";
		$hiddeninfo .= "<input type=\"hidden\" name=\"followupincharge$i\" id=\"followupincharge$i\" value=\"".$arfollowup[$i][2]."\">";
		$hiddeninfo .= "<input type=\"hidden\" name=\"followupfirstname$i\" id=\"followupfirstname$i\" value=\"".$arfollowup[$i][3]."\">";
		$hiddeninfo .= "<input type=\"hidden\" name=\"followuplastname$i\" id=\"followuplastname$i\" value=\"".$arfollowup[$i][4]."\">";
		$hiddeninfo .= "<input type=\"hidden\" name=\"followupdeadline$i\" id=\"followupdeadline$i\" value=\"".dateconvert($arfollowup[$i][5])."\">";
		$hiddeninfo .= "<input type=\"hidden\" name=\"followupenddate$i\" id=\"followupenddate$i\" value=\"".dateconvert($arfollowup[$i][6])."\">";
	}
		$hiddeninfo .= "<input type=\"hidden\" name=\"followupcount\" id=\"followupcount\" value=\"$i\">";
		$hiddeninfo .= "<input type=\"hidden\" name=\"forcedcopies\" id=\"forcedcopies\" value=\"".forcedcopylist($_GET['edit'])."\">";
$addtask .= $hiddeninfo;	
$addtask .= "</div><!-- end hidden info -->
		<table id=\"followuptable\">
			<tr>
                                <th>Suites à donner <img src=\"images/lock.png\" class=\"".($lockfup ? "" : "hidden")."\" border=\"0\" alt=\"Verrouill&eacute;\" /></th>
                        <td colspan=\"3\">
                                <select name=\"followups\" id=\"followups\"> ".($lockfup ? "<option value='0'>Veuillez sélectionner la suite à donner</option>" : "<option value='0'>Nouvelle suite à donner</option>");
$addtask .= buildfollowuplist($selected,$arfollowup);
$addtask .= "                  </select>
                        </td>
                        </tr>
			<tr>
                        <th>Description <img src=\"images/lock.png\" class=\"".($lockfup ? "" : "hidden")."\" border=\"0\" alt=\"Verrouill&eacute;\" /></th>
                        <td colspan=\"3\"><input type=\"text\" size=\"50\" name=\"followuptitle\" id=\"followuptitle\" ".($lockfup ? "readonly" : "")."/>
                         </td>
			</tr>
			<tr>
				<th>Responsable <img src=\"images/lock.png\" class=\"".($lockfup ? "" : "hidden")."\" border=\"0\" alt=\"Verrouill&eacute;\" /></th>
                        <td colspan=\"3\">
                                <select name=\"followupincharge\" id=\"followupincharge\"".($lockfup ? " onFocus='this.oldIndex=this.selectedIndex;' onchange='javascript:this.selectedIndex=this.oldIndex;'" : "").">";
$addtask .= buildinchargelist();
$addtask .= "                  </select>
                        </td>
                        </tr>
			<tr>
			<th>&Eacute;ch&eacute;ance <img src=\"images/lock.png\" class=\"".($lockfup ? "" : "hidden")."\" border=\"0\" alt=\"Verrouill&eacute;\" /></th>
                        <td><input name=\"deadlinef\" id=\"deadlinef\" type=\"text\" size=\"10\" value=\"\" readonly/>".($lockfup ? "" : " <a href=\"javascript:NewCal('deadlinef','ddmmyyyy');\"><img src=\"images/calendar.png\" border=\"0\" alt=\"Choississez une date\"></a>")."</td>

			<th>Fait le <img src=\"images/lock.png\" id=\"enddatelock\" class=\"hidden\" border=\"0\" alt=\"Verrouill&eacute;\" /></th>
                        <td><input name=\"enddatef\" id=\"enddatef\" type=\"text\" size=\"10\" value=\"\" readonly/><a href=\"javascript:NewCal('enddatef','ddmmyyyy');\"> <img src=\"images/calendar.png\" id=\"imgenddatef\" border=\"0\" alt=\"Choississez une date\" class=\"hidden\" /></a></td>
                        </tr>
			<tr>
                        <td colspan=\"5\">
				<input type=\"hidden\" name=\"taskid\" value=\"".$_GET['edit']."\"/>
				<input type=\"hidden\" name=\"taskuser\" value=\"".$cTask->userid."\"/>
				<input type=\"hidden\" name=\"currentuserid\" id=\"currentuserid\" value=\"".$_SESSION['userid']."\"/>
				<input type=\"hidden\" name=\"taskincharge\" value=\"".$cTask->incharge."\"/>
				<input type=\"hidden\" id=\"followuptodelete\" name=\"followuptodelete\" value=\"\" />
				<input type=\"button\" class=\"cancel\" value=\"Fermer\" onclick=\"javascript:window.location.href='main.php?archive=".$_GET['archive']."';\"/> <input type=\"button\" id=\"enregistrerfollowup\" name=\"enregistrerfollowup\" class=\"hidden\" value=\"Enregistrer\" onclick=\"javascript:followuptrigger(this);\" /> <input type=\"button\" id=\"deletefollowup\" name=\"deletefollowup\" class=\"fdelete hidden\" value=\"Supprimer\" onclick=\"javascript:followupdelete();\" />
                        </td>
			</tr>

		</table>
        </form>
        </div>";
};

/* Add copies tab */
if ($_GET['edit']>0 && !$lock) {
$addtask .= "<div class=\"tabbertab\" title=\"Copies à\">
        <form method=\"post\" action=\"addcopies.php\" name=\"frmaddcopies\" id=\"frmaddcopies\">
                <table id=\"copiestable\">
                        <tr>
                                <th>Sélectionner les personnes à mettre en copies (maintenir CRTL appuyé pour en sélectionner plusieurs)</th>
                        <td>
				<select id=\"selectnames\" name=\"selectnames\" size=\"15\" multiple=\"multiple\" onchange=\"javascript:copytrigger(this);\"><option>Aucune copie</option>";
$addtask .= buildcopylist($cTask->copies);
$addtask .= "			</select>
			</td>
                        </tr>

                </table>
	<input type=\"hidden\" name=\"copylist\" id=\"copylist\" value=\"\" />
	<input type=\"hidden\" name=\"taskid\" value=\"".$_GET["edit"]."\" />
        </form>
        </div>";
};



/* Add comm tab */
if ($_GET['edit']>0 || $_GET['com']==1) {
$addtask .= "<div class=\"tabbertab$tabcom\" title=\"Commentaires\">
	<form method=\"post\" action=\"addcomment.php\" name=\"frmaddcom\" id=\"frmaddcom\">
	<table id=\"commentstable\">
	<tr>
	<td><div id=\"ifrcommentsdiv\"></div></td>
	</tr>
	<tr>
	<td><textarea cols=\"70\" rows=\"2\" id=\"addcomment\" name=\"addcomment\">Cliquez ici pour ajouter un nouveau commentaire</textarea><br/>
	<input type=\"hidden\" name=\"userid\" value=\"$Cuser->userid\"/>
	<input type=\"hidden\" name=\"taskid\" value=\"".$_GET['edit']."\"/>
	<input type=\"button\" class=\"cancel\" value=\"Fermer\" onclick=\"javascript:window.location.href='main.php?archive=".$_GET['archive']."';\"/>
	<input type=\"button\" name=\"ajouter\" id=\"ajouter\" value=\"Ajouter\" class=\"hidden\"/ onclick=\"javascript:commentstrigger(this);\" /></td>
	</tr>
	</table>
	</form>
	</div>";
};

/* Close up tab divs */
$addtask .= "</div>
</div>";

/* functions */

function buildpriority($select) {
	for ($count=1;$count<=3;$count+=1) {
		($select==$count ? $selected=" selected" : $selected="");
		$output .= "<option value=\"$count\"$selected>Priorit&eacute; $count</option>";	
	}
	return $output;
}

function buildpercentage($select) {
	for ($count=0;$count<=100;$count+=5) {
		($select==$count ? $selected=" selected" : $selected="");
		$output .= "<option value=\"$count\"$selected>$count %</option>";	
	}
	return $output;
}


function buildinchargelist($i=0) {
	$mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
	/* check connection */
	if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();}

        $query = "SELECT * FROM `users` WHERE `users`.`active` = '1' ORDER BY `last`";

	if ($result = $mysqli->query($query)) {
		while ($row = $result->fetch_assoc()) {
			($i==$row["userid"] ? $select=" selected" : $select="");
			$output .= "<option value='".$row["userid"]."'$select>".$row["last"]." ".$row["first"]."</option>";
		}
		$result->close();
	}

	$mysqli->close();
	return $output;
}

function forcedcopylist($tid){
	$mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();}

        $query = "SELECT `userid` FROM `suites` WHERE `suites`.`tacheid` = '$tid'";
	$output = "|";
        if ($result = $mysqli->query($query)) {
                while ($row = $result->fetch_assoc()) {
                        $output .= $row['userid']."|";
                }
                $result->close();
        }

        $mysqli->close();
        return $output;
}

function buildfollowuplist($selected,$arfollowup) {
	for ($i=0;$i<count($arfollowup);$i++){
		$output .= "<option value='".$arfollowup[$i][0]."'$select>".$arfollowup[$i][1]."</option>";
	}
	
        return $output;
}

function buildcopylist($l) {
        $mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();}

        $query = "SELECT * FROM `users` WHERE `users`.`active` = '1' ORDER BY `last`";

        if ($result = $mysqli->query($query)) {
                while ($row = $result->fetch_assoc()) {
                        //($i==$row["userid"] ? $select=" selected" : $select="");
			((preg_match('/\|'.$row['userid'].'\|/', $l)) ? $select=" selected" : $select="");
			if ($row['userid']!=$_SESSION['userid']) $output .= "<option value='".$row["userid"]."'$select>".$row["last"]." ".$row["first"]."</option>";
                }
                $result->close();
        }

        $mysqli->close();
        return $output;
}

function buildprojectslist($p,$uid,$listall) {
	$mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
	/* check connection */
	if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();}

        $project = getmyprojects($uid); //function from menu.php
        if($listall!=='0'){ //if new, then don't show other's projects
		foreach ($project as &$value){
                	$pquery .= " OR `projectid` = '$value'";
        	}
	}

	#$query = "SELECT * FROM `projects` WHERE `projects`.`active` = '1'";
	#if ($filter) {$query .=" AND `ownerid` = '$uid'";}

	$query = "SELECT * FROM `projects` WHERE `active` = 1 AND (`ownerid` = '$uid'$pquery) ORDER BY `projects`.`title`";
	#echo $query;
	
	if ($result = $mysqli->query($query)) {
		while ($row = $result->fetch_assoc()) {
			($p==$row["projectid"] ? $select=" selected" : $select="");
			$output .= "<option value='".$row["projectid"]."'$select>".$row["title"]."</option>";
		}
		$result->close();
	}

	$mysqli->close();
	return $output;
}


function gettaskinfo($t){
        $mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

        $query = "SELECT `tacheid`,`date`,`deadline`,`project`,`title`,`description`,`incharge`,`progress`,`active`,`userid`,`enddate`,`copies` FROM `taches` " .
                "WHERE `tacheid` = '" . $t . "'";

        if ($result = $mysqli->query($query)) {
                $row = $result->fetch_row();
                $result->close();
        }

        $mysqli->close();
        return $row;
}

function getfollowupinfo($t){
        $mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

        $query = "SELECT `suites`.`suiteid`,`suites`.`title`,`suites`.`userid`,`users`.`first`,`users`.`last`,`suites`.`deadline`,`suites`.`enddate` FROM `suites` INNER JOIN `users` ON `suites`.`userid`=`users`.`userid` WHERE `suites`.`tacheid` = '" . $t . "'";
        if ($result = $mysqli->query($query)) {
		$i=0;
		while ($row = $result->fetch_row()){
			$ar[$i] = $row;	
			$i++;
		}
                $result->close();
        }

        $mysqli->close();
        return $ar;
}


function getcominfo($t,$u,$l){
        $mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

        $query = "SELECT `comments`.`comment`,`comments`.`date`,`comments`.`commentid`,`users`.`first`,`users`.`last`,`users`.`userid` FROM " .
	"`comments` INNER JOIN `users` ON `comments`.`userid`=`users`.`userid` WHERE `comments`.`tacheid` = '" . $t . "'";

	if ($result = $mysqli->query($query)) {
                while ($row = $result->fetch_assoc()) {
			$row['comment']=preg_replace('/\r\n/', "<br/>", trim($row['comment']));
			if($row['userid']==$u && !$l){$candelete="<a href='javascript:deletecom(".$row['commentid'].")'><img border='0' src='images/b_delx.png'/></a>";}else{$candelete="";}
                        $output .= "<div id='com".$row['commentid']."'><p><span style='font-size:10px;font-style:italic;'>".$row['date']." ".$row['last']." ".$row['first']."</span> $candelete<br/><span style='font-size:12px'><< ".$row['comment']." >></span></p><hr/></div>";
                }
                $result->close();
        }	

        $mysqli->close();
        return $output;
}

function dateconvert($date) {
	if($date==''){
	return;
	}else{
		$date = explode("-",$date);
		$date = date("d/m/Y", strtotime($date[2]."-".$date[1]."-".$date[0]));
	}
	return $date;
}
