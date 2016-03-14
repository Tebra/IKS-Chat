<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>Setup</title>
</head>

<body>

<?php
require("../config.php");
$mysqli = new mysqli($dbhost, $dbuser, $dbpwd, $db);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    return;
}
if (!$mysqli->query("DROP TABLE IF EXISTS users") ||
    !$mysqli->query("CREATE TABLE users(user VARCHAR(32), password VARCHAR(32))")) {
    echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
    return;
}
if (!$mysqli->query("DROP TABLE IF EXISTS chat") ||
    !$mysqli->query("CREATE TABLE chat(id int NOT NULL AUTO_INCREMENT, time TIMESTAMP, user1 VARCHAR(32), user2 VARCHAR(32), text VARCHAR(256))")) {
    echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
    return;
}

echo "Ok";

?>
</body>

</html>
