<?php
function add_dagr_to_list($list, $guid, $title, $date, $size, $type, $file_type, $file_loc, $author, $parent, $tag){
    if (key_exists($guid, $list)){
        $list[$guid]["tags"][] = $tag;
    } else {
        $list[$guid] = array("guid"=>$guid, "title"=>$title, "date"=>$date, "size"=>$size, "type"=>$type, "file_type"=>$file_type, "location"=>$file_loc, "author"=>$author, "parentGuid"=>$parent, "tags"=> array($tag));
    }
    return $list;
}

function format_dagr_list($list){
    $formatted_list = array();
    foreach($list as $dagr){
        $formatted_list[] = $dagr;
    }
    return $formatted_list;
}

function dagr_add($guid, $title, $date, $size, $type, $file_type, $location, $author, $parent){
    $db = new mysqli("localhost", "root", "dude1313", "mochila_db");
    $statement = $db->prepare("
    insert into DAGRS 
    values(?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $parent_size_update = $db->prepare("update DAGRS set DAGR_SIZE=DAGR_SIZE+? where DAGR_GUID=?");
    $statement->bind_param("sssisssss", $guid, $title, $date, $size, $type, $file_type, $location, $author, $parent);
    //    echo("binded");
    $statement->execute();
    
    $parent_size_update->bind_param("is", $size, $parent);
    $parent_size_update->execute();
    
    $db->commit();
    $statement->close();
    $db->close();
}



?>