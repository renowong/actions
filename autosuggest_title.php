<?php
session_start();
include_once('config.php');
$id = $_SESSION['userid'];
$mysqli = new mysqli(DBSERVER, DBUSER, DBPWD, DB);
        /* check connection */
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }

$query = "SELECT `taches`.`title`, `taches`.`description` FROM `taches` WHERE `userid` = '$id' AND `active`='1'";
if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $aUsers[]=$row['title'];
        $aInfo[]=$row['description'];
    }
}
	
	$input = strtolower( $_GET['input'] );
	$len = strlen($input);
	$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 0;
	
	
	$aResults = array();
	$count = 0;
	
	if ($len)
	{
		for ($i=0;$i<count($aUsers);$i++)
		{
			// had to use utf_decode, here
			// not necessary if the results are coming from mysql
			//
			if (strtolower(substr(utf8_decode($aUsers[$i]),0,$len)) == $input)
			{
				$count++;
				$aResults[] = array( "id"=>($i+1) ,"value"=>htmlspecialchars($aUsers[$i]), "info"=>htmlspecialchars($aInfo[$i]) );
			}
			
			if ($limit && $count==$limit)
				break;
		}
	}
	
	
	
	
	
	header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header ("Pragma: no-cache"); // HTTP/1.0
	header("Content-Type: text/xml");

            echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?><results>";
            for ($i=0;$i<count($aResults);$i++)
            {
                    echo "<rs id=\"".$aResults[$i]['id']."\" info=\"".$aResults[$i]['info']."\">".$aResults[$i]['value']."</rs>";
            }
            echo "</results>";
?>