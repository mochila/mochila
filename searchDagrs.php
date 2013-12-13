<?php
    require_once("/home/nerraw/mochila/searchFunctions.php");
    $searchtype = $_GET["type"];
    $dagr_list = array();
    if($searchtype == "free"){
        $term = $_GET["term"];
        $dagr_list = free_search($term)
    } else if ($searchtype == "author"){
        $term = $_GET["term"];
        $dagr_list = author_search($term);
    } else if ($searchtype == "file type"){
        $term = $_GET["term"];
        $dagr_list = type_search($term);
    } else if ($searchtype == "keyword") {
        $term = $_GET["term"];
        $dagr_list = title_search($term);
    } else if ($searchtype == "orphan") {
        $term = $_GET["term"];
        $dagr_list = orphan_search($term);
    } else if ($searchtype == "reach") {
        $term = $_GET["term"];
        $dagr_list = reach_search($term);
    } else if ($searchtype == "sterile") {
        $term = $_GET["term"];
        $dagr_list = sterile_search($term);
    } else if ($searchtype == "tag") {
        $term = $_GET["term"];
        $dagr_list = tag_search($term);
    } else if ($searchtype == "date") {
        $term = $_GET["term"];
        $dagr_list = date_search($term);
    } else if ($searchtype == "date range") {
        $start = $_GET["term"]["start"];
        $end = $_GET["term"]["end"];
        if(strcmp($start, $end) <= 0){
            $dagr_list = date_range_search($start, $end);
        } else {
        }
    }






        




?>