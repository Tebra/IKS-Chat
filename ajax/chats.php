<?php

$user1=$_GET["user1"];
$user2=$_GET["user2"];

require("../config.php");
$mysqli = new mysqli($dbhost, $dbuser, $dbpwd, $db);

if (!($stmt = $mysqli->prepare("SELECT id, time, user1, user2, text FROM chat WHERE (user1=? AND user2=?) OR (user1=? AND user2=?) ORDER BY time ASC"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->bind_param("ssss", $user1, $user2, $user2, $user1)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
if (!$stmt->bind_result($out_id, $out_time, $out_user1, $out_user2, $out_text)) {
    echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}

$chats =  array();
while ($stmt->fetch()) {
	$chats[] = array('id' => $out_id, 'time' => $out_time, 'user1' => $out_user1, 'user2' => $out_user2, 'text' => $out_text );	
}

$stmt->close();

$mysqli->close();

echo json_encode($chats); 

?>