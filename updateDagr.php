<?php
require_once("common.php");
require_once("guidGenerator.php");

$guid = $_POST["guid"];
$title = $_POST["title"];
$author = $_POST["author"];
$parent_dagr = $_POST["parent"];
$tags = $_POST["tags"];


    


if($parent_dagr == "" || $parent_dagr == "-1"){
    $parent_dagr = NULL;
} else if(strpos($parent_dagr, "-1 ") === 0){
    echo "Does it work";
    $parent_title = substr($parent_dagr, 3);
    $parent_dagr = guid();
    dagr_add($parent_dagr, $parent_title, date('Y-m-d H:i:s'), 0, "parent", "parent", NULL, $author, NULL); 
    
}


//DAGR update

$db = new mysqli("localhost", "root", "dude1313", "mochila_db");
$statement = $db->prepare("update DAGRS set DAGR_TITLE=?, DAGR_AUTHOR=?, DAGR_PARENT_GUID=? where DAGR_GUID=?");
$statement->bind_param("ssss", $title, $author, $parent_dagr, $guid);
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