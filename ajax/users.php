<?php

require("../config.php");
$mysqli = new mysqli($dbhost, $dbuser, $dbpwd, $db);

if (!($stmt = $mysqli->prepare("SELECT user FROM users ORDER BY user ASC"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}

$out_user    = NULL;
if (!$stmt->bind_result($out_user)) {
    echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}

$users =  array();
while ($stmt->fetch()) {
	$users[] = $out_user;	
}

$stmt->close();

$mysqli->close();

header('Content-Type: application/json');
echo json_encode($users); 

?>
