<?php
$menu = '<style media="all" type="text/css">@import "menu/menu_style.css";</style>' .
	'<!--[if IE]>' .
	'<style media="all" type="text/css">@import "menu/ie.css";</style>' .
	'<![endif]-->' .
	'<div class="nav">' .
	'	<div class="table">' .
	'		<ul class="select">' .
	'			<li><a href="main.php" target="_self"><b>T&acirc;ches</b></a>' .
	'				<div class="select_sub">' .
	'					<ul class="sub">' .
	'						<li><a href="main.php?edit=0" target="_self">Nouvelle t&acirc;che</a></li>' .
	'						<li><a href="extract.php" target="_self">Extraction</a></li>' .
	'					</ul>' .
	'				</div>' .
	'			</li>' .
	'		</ul>' .
	'		<ul class="select">' .
	'			<li><a href="main.php" target="_self"><b>Voir</b></a>' .
	'				<div class="select_sub">' .
	'					<ul class="sub">' .
	'					<li><a href="main.php" target="_self">Tout</a></li>' .
	'					<li><a href="main.php?archive=1" target="_self">Archives</a></li>' .
	'					<li><a href="main.php?project=0" target="_self">Sans dossier</a></li>';
$menu .= menugetprojects($Cuser->userid);
$menu .= '					</ul>' .
	'				</div>' .
	'			</li>' .
	'		</ul>' .
	'		<ul class="select">' .
	'			<li><a href="projects.php" target="_self"><b>G&eacute;rer</b></a>' .
	'				<div class="select_sub">' .
	'					<ul class="sub">' .
	'					<li><a href="projects.php" target="_self">Dossiers</a></li>' .
	'					<li><a href="users.php" target="_self">Utilisateurs</a></li>' .
	'					</ul>' .
	'				</div>' .
	'			</li>' .
	'		</ul>' .
	'	</div>' .
	'</div>';


	/* functions */

function menugetprojects($uid) {
	$mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }
	
	$project = getmyprojects($uid);
	foreach ($project as &$value){
		$pquery .= " OR `projectid` = '$value'";
	}

	$query = "SELECT * FROM `projects` WHERE `active` = 1 AND (`ownerid` = '$uid'$pquery) ORDER BY `projects`.`title`";
	if ($result = $mysqli->query($query)) {
		while ($row = $result->fetch_assoc()) {
			if($i==5){$break="<br/>";$i=1;}else{$break="";$i++;}	
			$output .= '<li><a href="main.php?project='.$row['projectid'].'" target="_self">'.$row['title'].'</a></li>'.$break;
		}
		$result->close();
	}

	$mysqli->close();

	return $output;
}


function getmyprojects($userid){
        $mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

        $query = "SELECT DISTINCT project FROM `actionfaaa`.`taches` " .
                "WHERE `incharge` = '" . $userid . "'";

	$rows = array();
        if($result = $mysqli->query($query)){
		while ($row = $result->fetch_row()) {
		       array_push($rows,$row[0]);
		}

        $result->close();
	}

        $mysqli->close();
        return $rows;

}
?>
