<?php
/* functions */
function getaccounts($l,$u){
        $mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }
	

	switch ($l) {
		case 1:
		break;
		case 2:
		$filter = " WHERE `users`.`level` = '3' OR `users`.`userid` = '$u'";
		break;
		case 3:
		$filter = " WHERE `users`.`userid` = '$u'";
		break;
	}
	$query = "SELECT * FROM `actionfaaa`.`users`".$filter;
	
	#echo $query;
        
	if ($result = $mysqli->query($query)) {
		while ($row = $result->fetch_assoc()) {
			($u==$row["userid"] ? $lock=true : $lock=false);
			$output .= "<tr id='tr".$row["userid"]."'><td>".$row["login"]."</td>
			<td style='text-align:left;'>".$row["last"]."</td>
			<td style='text-align:left;'>".$row["first"]."</td>
			<td>".$row["email"]."</td>
			<td>".$row["level"]."</td>
			<td>".$row["active"]."</td>
			<td>".$row["lastsession"]."</td>
			<td>
			<a href='users.php?edit=".$row["userid"]."'><img src='images/b_edit.png'></a>
			<a href='javascript:void(0)'>".($lock ? "" : "<img src='images/b_dele.png' id='".$row["userid"]."' class='b_dele'>")."</a></td></tr>";	
		}
		$result->close();
	}

        $mysqli->close();

        return $output;
}
?>
