<?php
$db = new mysqli("localhost", "root", "dude1313", "mochila_db");
$statement = $db->prepare("select DAGR_GUID, DAGR_TITLE from DAGRS");
$statement->execute();
$statement->bind_result($guid, $title);
$return_list = array();
while($statement->fetch()){
    $return_list[] = array("guid"=>$guid, "title"=>$title);
}
echo json_encode($return_list);
?>