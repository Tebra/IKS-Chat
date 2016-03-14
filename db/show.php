<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>Show</title>
</head>

<body>

<?php
require("../config.php");
$mysqli = new mysqli($dbhost, $dbuser, $dbpwd, $db);

echo '<h1>Users</h1>';
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    return;
}

if (!($stmt = $mysqli->prepare("SELECT user,password FROM users"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
if (!$stmt->bind_result($out_user, $out_password)) {
    echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
while ($stmt->fetch()) {
	echo "<p>";
	echo "User: $out_user , Password: $out_password";
	echo "</p>";
}
$stmt->close();


echo '<h1>Chat</h1>';
if (!($stmt = $mysqli->prepare("SELECT time, user1, user2, text FROM chat ORDER BY time DESC"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
if (!$stmt->bind_result($out_time, $out_user1, $out_user2, $out_text)) {
    echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}

while ($stmt->fetch()) {
	echo "<p>";
	echo "Time: $out_time, User1: $out_user1, User2: $out_user2, Text: $out_text";
	echo "</p>";
}
$stmt->close();

$mysqli->close();

?>
</body>

</html>
