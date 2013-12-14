<?php
require_once("common.php");

$guid = $_POST["guid"];
$title = $_POST["title"];
$author = $_POST["author"];
$parent = $_POST["parent"];
$tags = $_POST["tags"];

    


if($parent == "" || $parent == "-1"){
    $parent = NULL;
} else if(strpos($parent, "-1 ") === 0){
    
    $parent_title = substr($parent, 3);
    $parent = guid();
    dagr_add($parent, $parent_title, date('Y-m-d H:i:s'), 0, "parent", "parent", NULL, $author, NULL); 
    
}


//DAGR update

$db = new mysqli("localhost", "root", "dude1313", "mochila_db");
$statement = $db->prepare("update DAGRS set DAGR_TITLE=?, DAGR_AUTHOR=?, DAGR_PARENT_GUID=? where DAGR_GUID=?");
$statement->bind_param("ssss", $title, $author, $parent, $guid);
$statement->execute();

//Tag insertion

//Delete all old tags
$statement = $db->prepare("delete from TAGS where DAGR_GUID=?");
$statement->bind_param("s", $guid);
$statement->execute();

//insert new tags
$statement = $db->prepare("insert into TAGS values(?, ?)");
foreach($tags as $tag){
    $statement->bind_param("ss", $guid, $tag);
    $statement->execute();
}

$db->close();


    

?>