<?php 
$response_array = array();
$guid = $_POST["guid"];

if($guid != ""){
    $db = new mysqli("localhost", "root", "root", "mochila_db");
    $dagr_statement = $db->prepare("delete from `DAGRS` where `DAGR_GUID` =  ?");
    $dagr_children_update = $db->prepare("update `DAGRS` set `DAGR_PARENT_GUID`=NULL where `DAGR_PARENT_GUID`= ?");
    $dagr_children_list_update = $db->prepare("delete from `CHILD_DAGRS` where `PARENT_GUID` = ?");
    $tags_statement = $db->prepare("delete from `TAGS` where `DAGR_GUID` = ?");
    $dagr_statement->bind_param("s", $guid);
    $tags_statement->bind_param("s", $guid);
    $dagr_children_update->bind_param("s", $guid);
    $dagr_children_list_update->bind_param("s", $guid);
    $dagr_statement->execute();
    $tags_statement->execute();
    $dagr_children_update->execute();
    $dagr_children_list_update->execute();
    $db->commit();
    
    $response_array["status"] = "success";
    $response_array["guid"] = $guid;
    
} else {
    $response_array["status"] = "error";
    $response_array["guid"] = $guid;
    
    
}

header("Content Type: application/json");
echo json_encode($response_array);  
    
    
    
    
    
?>