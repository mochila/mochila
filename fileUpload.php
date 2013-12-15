<?php
require_once("guidGenerator.php");
require_once("common.php");


//dagr_add("1234", "Random", "2013-12-12", "10000000", "file", "pdf", "adfadf"."/"."1234", "WARE", "4");

$curr_url = $_POST["currUrl"];
$author = $_POST["author"];
$parent_guid = $_POST["parent"];
if($parent_guid == "" || $parent_guid == "-1"){
    $parent_guid = NULL;
} else if(strpos($parent_guid, "-1 ") === 0){
    
    $parent_title = substr($parent_guid, 3);
    $parent_guid = guid();
    dagr_add($parent_guid, $parent_title, date('Y-m-d H:i:s'), 0, "parent", "parent", NULL, $author, NULL); 
    
}

$storage_location = "/home/mochila/uploads/".$parent_guid;




//echo (json_encode($_FILES["user-files"]));
foreach($_FILES["user-files"]["error"] as $key=>$error){ 
    if($error > 0 ){ 
//        echo json_encode($_FILES["user-files"]["name"][$key]);
    } else {
        $guid = guid();
        $name = $_FILES["user-files"]["name"][$key];
        $tmp_name = $_FILES["user-files"]["tmp_name"][$key];
        $size = $_FILES["user-files"]["size"][$key]/1024;
        $type = substr(strrchr($name, "."), 1);
        $dagr_type = "file";
        $file_location = $storage_location.$name;
        $date = date('Y-m-d H:i:s');
        
        //Check if parent exists else create the directory
        if(!(file_exists($storage_location))){
            mkdir($storage_location);
            chmod($storage_location, 0777);
        }
        
        
        
        
        // Move to permanent Storage
        $worked = move_uploaded_file($tmp_name, $storage_location."/".$name);
        
        //Add Dagr
        if($worked){
            chmod($storage_location."/".$name, 0777);
            dagr_add($guid, $name, $date, $size, $dagr_type, $type, $storage_location."/".$name, $author, $parent_guid);
        }
        
    }
}

header("Location: ".$curr_url);
?>