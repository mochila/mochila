<?php
function add_dagr_to_list($list, $guid, $title, $date, $size, $type, $file_type, $file_loc, $author, $parent, $tag){
    if (key_exists($guid, $list)){
        $list[$guid]["tags"][] = $tag;
    } else {
        $list[$guid] = array("guid"=>$guid, "title"=>$title, "date"=>$date, "size"=>$size, "type"=>$type, "file_type"=>$file_type, "location"=>$file_loc, "author"=>$author, "parent"=>$parent, "tags"=> array($tag));
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


?>