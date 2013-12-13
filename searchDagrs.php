<?php
require_once("/home/nerraw/mochila/searchFunctions.php");
$searchtype = $_GET["type"];
$result_list = array();
$dagr_list = array();
if($searchtype == "free"){
    $term = $_GET["term"]; 
    $result_list = free_search($term);
} else if ($searchtype == "author"){
    $term = $_GET["term"];
    $result_list = author_search($term);
} else if ($searchtype == "type"){
    $term = $_GET["term"];
    $result_list = type_search($term);
} else if ($searchtype == "title") {
    $term = $_GET["term"];
    $result_list = title_search($term);
} else if ($searchtype == "orphan") {
    $result_list = orphan_search();
} else if ($searchtype == "reach") {
    $term = $_GET["term"];
    $result_list = reach_search($term);
} else if ($searchtype == "sterile") {
    $result_list = sterile_search();
} else if ($searchtype == "tag") {
    $term = $_GET["term"];
    $result_list = tag_search($term);
} else if ($searchtype == "date") {
    $term = $_GET["term"];
    $result_list = date_search($term);
} else if ($searchtype == "time") {
    $start = $_GET["start"];
    $end = $_GET["end"];
    if(strcmp($start, $end) <= 0){
        $result_list = time_range_search($start, $end);
    }
}

$dagr_list["parent"] = array("guid" => null, "title" => null, "date" => null, "size" => 0, "type" => "parent", "file_type" => null, "location" => null, "parentGuid" => null, "tags" => array());
$dagr_list["children"] = $result_list;

?>


<?php include("main.php"); ?>