<?php
$projectlist = getprojects($_GET['order'],$_GET['dir'],$Cuser->userid);
/* functions */
function getprojects($order=NULL,$dir=NULL,$id){
        $mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

	if (isset($order)) {$order= " ORDER BY `".$order."` ".$dir;};
	$query = "SELECT `projects`.`projectid`, `projects`.`title`, `projects`.`description`, `projects`.`active` FROM `projects` WHERE `ownerid` = '$id'".$order;

	#echo $query;
        
	if ($result = $mysqli->query($query)) {
		while ($row = $result->fetch_assoc()) {
			$countresult = $mysqli->query("SELECT `tacheid` FROM `taches` WHERE `project` = '" . $row["projectid"] ."'");
			$count = $countresult->num_rows;
			if ($count==0) {
				$mesg = "Etes vous s&ucirc;r(e) de vouloir supprimer ce dossier?";
			} else {
				$mesg = "Etes vous s&ucirc;r(e) de vouloir supprimer ce dossier? Les t&acirc;ches rattach&eacute;es &agrave; ce dossier se retrouveront &apos;Sans dossier&apos;. Il y a actuellement $count t&acirc;che(s) recens&eacute;e(s).";
			}
			$output .= "<tr id='tr".$row["projectid"]."'>
			<td>".$row["title"]."<input type=\"hidden\" id=\"hid_title_".$row["projectid"]."\" name=\"hid_title_".$row["projectid"]."\" value=\"".$row["title"]."\"></td>
			<td style='text-align:left;'>".$row["description"]."<input type=\"hidden\" name=\"hid_description_".$row["projectid"]."\" value=\"".$row["description"]."\"></td>
			<td>".($row["active"]=1 ? "actif" : "inactif")."<input type=\"hidden\" name=\"hid_active_".$row["projectid"]."\" value=\"".$row["active"]."\"></td>
			<td>$count</td>
			<td>
			<a href='javascript:void(0);' projectid='".$row["projectid"]."' class='edittrigger'>
			<img src='images/b_edit.png'></a>
			<a href='javascript:void(0);' onclick='confirmation(\"".$mesg."\",\"projects.php?delete=".$row["projectid"]."\");'>
				<img src='images/b_dele.png'>
			</a></td></tr>";	
		$countresult->close();
		}
		$result->close();
	}

	$mysqli->close();

	return $output;
}
?>
