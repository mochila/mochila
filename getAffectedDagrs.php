<?php
require_once("/home/nerraw/mochila/searchFunctions.php");
$guid = $_GET["guid"];
$dagr_list = reach_search($guid);

header("Content Type: application/json");
echo json_encode($dagr_list);

?>