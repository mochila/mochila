<?php
//$db = new mysqli("localhost", "root", "dude1313", "mochila_db");
//$type = "%pdf%";
//
//$statement = $db->prepare("select * from DAGRS where DAGR_FILE_TYPE LIKE 'png'");
////$statement->bind_param("s", $type);
//$statement->bind_result($guid, $title, $date, $size, $type, $file_type, $loc, $author, $parent);
//$statement->execute();
//while($statement->fetch()){
//    echo $title."<br/>";
//}
//
//echo "Is this thing on";
//
//$db->close();

require_once("common.php");


function title_search($title){
    $db = new mysqli("localhost", "root", "dude1313", "mochila_db");
    $statement = $db->prepare(
        "select * from 
        DAGRS left join TAGS using (DAGR_GUID) 
        where DAGR_TITLE LIKE ?");
    $statement->bind_param("s", $title);
    $statement->bind_result($guid, $title, $date, $size, $type, $file_type, $loc, $author, $parent, $tag);
    $statement->execute();
    $dagr_list = array();
    while($statement->fetch()){
        //$dagr_list = add_dagr_to_list($dagr_list, $guid, $title, $date, $size, $type, $file_type, $loc, $author, $parent, $tag);
        echo $guid;
    }
    $statement->close();
    $dagr_list = format_dagr_list($dagr_list);
    echo json_encode($dagr_list);
    return $dagr_list;
    $db->close();
    
}

title_search("New_Parent");
?>