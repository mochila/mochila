<?php
// Get all the titles of DAGRS from the database
$mysqli = new mysqli("localhost", "root", "dude1313", "mochila_db");

$result = $mysqli->query("SELECT DAGR_TITLE FROM DAGRS WHERE DAGR_TYPE='parent'");

// Create an array from the result set
$rows = array();
while($row = $result->fetch_assoc()) {
  $rows[] = $row;
}

$mysqli->close();

// Echo the json encoding of the array
echo json_encode($rows);
?>
