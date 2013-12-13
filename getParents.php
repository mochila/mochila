<?php

function getParents() {
    $statement = null;
    $db = new mysqli("localhost", "root", "root", "mochila_db");
    $statement = $db->prepare("select `DAGR_GUID`,`DAGR_TITLE`,`DAGR_DATE`, `DAGR_SIZE`, `DAGR_TYPE`, `DAGR_FILE_TYPE`, `DAGR_FILE_LOC`, `DAGR_AUTHOR`, `DAGR_PARENT_GUID` from `DAGRS` where `DAGR_TYPE`='parent'");
    
    $statement->execute();
    $statement->bind_result($dagr_guid, $title, $date, $size, $type, $file_type, $loc, $author, $parent);
    $dagr_list = array();
    while($statement->fetch()){
        $dagr["guid"] = $dagr_guid;
        $dagr["title"] = $title;
        $dagr["date"] = $date;
        $dagr["size"] = $size;
        $dagr["type"] = $type;
        $dagr["file_type"] = $file_type;
        $dagr["location"] = $loc;
        $dagr["author"] = $author;
        $dagr["parentGuid"] = $parent;
        
        $dagr_list[]= $dagr;
        
        
    }
    $db->close();
    return $dagr_list;
}

$dagr_list = getParents();

echo json_encode($dagr_list);

?>
