<?php 


function singular_delete($guid, $db){
    $children_statement = $db->prepare("update `DAGRS` set `DAGR_PARENT_GUID`=NULL where `DAGR_PARENT_GUID`= ?");
    $dagr_statement = $db->prepare("delete from `DAGRS` where `DAGR_GUID` = ?");
    $children_statement->bind_param("s", $guid);
    $dagr_statement->bind_param("s", $guid);
    $children_statement->execute();
    $dagr_statement->execute();
    $db->commit();
}

function recursive_delete($guid, $db){
    $dagr_statement = $db->prepare("delete from `DAGRS` where `DAGR_GUID` = ?");
    $dagr_statement->bind_param("s", $guid);
    $dagr_statement->execute();
    $db->commit();
}



function delete_dagr($guid, $recursive){
    $db = new mysqli("localhost", "root", "dude1313", "mochila_db"); 
    if($recursive){
        recursive_delete($guid, $db);
    } else {
        singular_delete($guid, $db);
    }
    $db->close();
}


$response_array = array();
$guid = $_POST["guid"];
$recursive = $_POST["recursive"];

if($guid != ""){
    delete_dagr($guid, $recursive == "true");
    $response_array["status"] = "success";
    $response_array["guid"] = $guid;
    
} else {
    $response_array["status"] = "error";
    $response_array["guid"] = $guid;
    $response_array["recursive"];
}

header("Content Type: application/json");
echo json_encode($response_array);  
    
    
    
    
    
?>