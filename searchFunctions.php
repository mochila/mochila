<?php
require_once("/home/nerraw/mochila/common.php");

function execute_text_search($statement){
    $statement->execute();
    $statement->bind_result($guid, $title, $date, $size, $type, $file_type, $loc, $author, $parent, $tag);
    $dagr_list = array();
    while($statement->fetch()){
        $dagr_list = add_dagr_to_list($dagr_list, $guid, $title, $date, $size, $type, $file_type, $loc, $author, $parent, $tag);
    }
    $dagr_list = format_dagr_list($dagr_list);
    return $dagr_list;
    
}

function free_search($term){
    
    $dagr_list = array();
    if($term != null && $term != ""){
        $db = new mysqli("localhost", "root", "dude1313", "mochila_db");
        $statement = $db->prepare(
            "select * from 
        DAGRS natural join TAGS 
        where DAGR_TITLE LIKE ?
        or DAGR_FILE_TYPE LIKE ?
        or DAGR_FILE_LOC LIKE ?
        or DAGR_AUTHOR LIKE ?
        or TAG_TITLE LIKE ?");
        $term = "%".$term."%";
        $statement->bind_param("sssss", $term, $term, $term, $term, $term);
        $dagr_list = execute_text_search($statement);
        $db->close();
    }
    return $dagr_list;
}

function author_search($term) {
    $db = new mysqli("localhost", "root" , "root", "mochila_db");
    $statement = $db->prepare(
        "select * from 
        DAGRS natural join TAGS 
        where DAGRS.DAGR_GUID=TAGS.DAGR_GUID and LOWER(DAGR_AUTHOR) LIKE LOWER(?)");
    $statement->bind_param("s", $term);
    $dagr_list = execute_text_search($statement);
    $db->close();
    return $dagr_list;
}

function date_search($date){
    $db = new mysqli("localhost", "root" , "root", "mochila_db");
    $statement = $db->prepare(
        "select * from 
        DAGRS natural join TAGS 
        where DAGRS.DAGR_GUID=TAGS.DAGR_GUID and DAGR_DATE=?");
    $statement->bind_param("s", $date);
    $dagr_list = execute_text_search($statement);
    $db->close();
    return $dagr_list;
}

function type_search($type){
    $db = new mysqli("localhost", "root" , "root", "mochila_db");
    $statement = $db->prepare(
        "select * from 
        DAGRS natural join TAGS 
        where DAGR_TYPE LIKE ? or DAGR_FILE_TYPE LIKE ?");
    $statement->bind_param("ss", $type, $type);
    $dagr_list = execute_text_search($statement);
    $db->close();
    return $dagr_list;
    
}

function title_search($title){
    $db = new mysqli("localhost", "root" , "root", "mochila_db");
    $statement = $db->prepare(
        "select * from 
        DAGRS natural join TAGS 
        where DAGR_TITLE LIKE ?");
    $statement->bind_param("s", $title);
    $dagr_list = execute_text_search($statement);
    $db->close();
    return $dagr_list;
}

function orphan_search(){
    $db = new mysqli("localhost", "root" , "root", "mochila_db");
    $statement = $db->prepare(
        "select * from 
        DAGRS natural join TAGS 
        where DAGR_PARENT_GUID is NULL");
    $dagr_list = execute_text_search($statement);
    $db->close();
    return $dagr_list;
}

function reach_search_helper($dagr_list, $guid, $level){
    $reach_list = array();
    if($dagr_list[$guid] == null 
       || $dagr_list[$guid]["type"] != "parent" || $level == 0){
        $reach_list[] = $dagr_list[$guid];
        
        
    } else {
        $reach_list[] = $dagr_list[$guid];
        foreach($dagr_list as $dagr){
            if($dagr["parentGuid"] == $guid){
                $sub_list = reach_search_helper($dagr_list, $dagr["guid"], $level - 1);
                
                
                $reach_list = array_merge_recursive($reach_list, $sub_list);       
            }
            
        }
        
    }
    return $reach_list;
}

function reach_search($dagr_title) {
    $db = new mysqli("localhost", "root" , "root", "mochila_db");
    $statement = $db->prepare(
        "select * from 
        DAGRS natural join TAGS");
    $statement->execute();
    $statement->bind_result($guid, $title, $date, $size, $type, $file_type, $loc, $author, $parent, $tag);
    $super_parent = null;
    $dagr_list = array();
    while($statement->fetch()){
        if (strcasecmp($dagr_title,$title) == 0){
            $super_parent = $guid;
        }
        $dagr_list = add_dagr_to_list($dagr_list, $guid, $title, $date, $size, $type, $file_type, $loc, $author, $parent, $tag);
        
    }
    $db->close();
    
    $reach_list = array();
    
    if ($super_parent != null){
        $reach_list = reach_search_helper($dagr_list, $super_parent, 5);
    }
    
    return $reach_list;
}

function size_search($size){
    $db = new mysqli("localhost", "root" , "root", "mochila_db");
    $statement = $db->prepare(
        "select * from 
        DAGRS natural join TAGS 
        where DAGR_SIZE=?");
    $statement->bind_param("s", $size);
    $dagr_list = execute_text_search($statement);
    $db->close();
    return $dagr_list;
}

function sterile_search(){
    $db = new mysqli("localhost", "root" , "root", "mochila_db");
    $statement = $db->prepare(
        "select * 
      from DAGRS natural join TAGS
      where DAGR_TYPE LIKE 'parent'
      and DAGR_GUID not in (
      select distinct d1.DAGR_GUID 
      from DAGRS d1, DAGRS d2 
      where d1.DAGR_GUID = d2.DAGR_PARENT_GUID)");
    
    $dagr_list = execute_text_search($statement);
    $db->close();
    return $dagr_list;
}

function tag_search($tag){
    $db = new mysqli("localhost", "root", "dude1313", "mochila_db");
    $statement = $db->prepare(
        "select * 
        from DAGRS natural join TAGS
        where TAG_TITLE = ?");
    
    $statement->bind_param("s", $tag);
    $dagr_list = execute_text_search($statement);
    $db->close();
    return $dagr_list;
}

function time_range_search($start_time, $end_time){
    $db = new mysqli("localhost", "root", "dude1313", "mochila_db");
    $statement = $db->prepare(
        "select * 
        from DAGRS natural join TAGS
        where DAGR_DATE >= ? and DAGR_DATE <= ?");
    
    $statement->bind_param("ss", $start_time, $end_time);
    $dagr_list = execute_text_search($statement);
    $db->close();
    return $dagr_list;
}





?>