<?php

$user1=$_GET["user1"];
$user2=$_GET["user2"];
$text=$_GET["text"];

require("../config.php");
$mysqli = new mysqli($dbhost, $dbuser, $dbpwd, $db);

if (!($stmt = $mysqli->prepare("INSERT INTO chat (time, user1, user2, text) VALUES(CURRENT_TIMESTAMP, ?, ?, ?)"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->bind_param("sss", $user1, $user2, $text)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}

$stmt->close();

$mysqli->close();

header('Content-Type: application/json');
echo json_encode(true); 

?>