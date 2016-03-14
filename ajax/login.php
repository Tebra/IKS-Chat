<?php
require("../config.php");

$user=$_GET["user"];
$password=$_GET["password"];

$mysqli = new mysqli($dbhost, $dbuser, $dbpwd, $db);

if (!($stmt = $mysqli->prepare("DELETE FROM users WHERE user = ?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->bind_param("s", $user)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}

if (!($stmt = $mysqli->prepare("INSERT INTO users VALUES(?, ?)"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->bind_param("ss", $user, $password)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}

$stmt->close();

$mysqli->close();

echo json_encode(true); 

?>