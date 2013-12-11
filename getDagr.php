<?php

function getTags($guid){
    $db = new mysqli("localhost", "root", "root", "mochila_db");
    $statement = $db->prepare("select `TAG_TITLE` from `TAGS` where `DAGR_GUID`=?");
    $statement->bind_param("s", $guid);
    $statement->execute();
    $statement->bind_result($title);
    $tags_list = null;    
    while($statement->fetch()){
        $tags_list[] = $title;
        
    }
    return $tags_list;
}

function getDagr($guid){
    $statement = null;
    $db = new mysqli("localhost", "root", "root", "mochila_db");
    
    if ($guid == null){
        $statement = $db->prepare("select `DAGR_GUID`,`DAGR_TITLE`,`DAGR_DATE`, `DAGR_SIZE`,`DAGR_TYPE`, `DAGR_FILE_TYPE`, `DAGR_FILE_LOC`, `DAGR_AUTHOR`, `DAGR_PARENT_GUID` from `DAGRS` where `DAGR_PARENT_GUID` is NULL");
    } else{
        $statement = $db->prepare("select `DAGR_GUID`,`DAGR_TITLE`,`DAGR_DATE`, `DAGR_SIZE`, `DAGR_TYPE`,`DAGR_FILE_TYPE`, `DAGR_FILE_LOC`, `DAGR_AUTHOR`, `DAGR_PARENT_GUID` from `DAGRS` where `DAGR_GUID`=? OR `DAGR_PARENT_GUID`=?");
        $statement->bind_param("ss", $guid, $guid);
    }    // echo $statement;
    $statement->execute();
    $statement->bind_result($dagr_guid, $title, $date, $size, $type, $file_type, $loc, $author, $parent);
    $dagr_list = null;
    
    while($statement->fetch()){
        //$dagr_list[] = "{ guid: ".$dagr_guid.", title: ".$title.", date: ".$date.", size: ".$size.", location: ".$loc.", author: ".$author.", parentGuid: ".$parent."}";
        $dagr["guid"] = $dagr_guid;
        $dagr["title"] = $title;
        $dagr["date"] = $date;
        $dagr["size"] = $size;
        $dagr["type"] = $type;
        $dagr["file_type"] = $file_type;
        $dagr["location"] = $loc;
        $dagr["author"] = $author;
        $dagr["parentGuid"] = $parent;
        $dagr["tags"] = getTags($dagr_guid);
        
        if($dagr_guid == $guid){
            $dagr_list["parent"] = $dagr;
        } else {
            $dagr_list["children"][] = $dagr;
        }
        
    }
    $db->close();
    return $dagr_list;
}

$guid = $_GET["guid"];
$dagr_list = getDagr($guid);
//ho json_encode($dagr_list);

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Mochila</title>
        <?php include("header.inc"); ?>
        <script>
            var $dagr_info = <?php echo json_encode($dagr_list); ?>;
            console.log($dagr_info);
        </script>
    </head>
    <body>
        
        <?php include("navbar.inc"); ?>
        
        <?php include("html/dagrlist.html"); ?>   
        
        <?php include("html/add-tag-modal.html"); ?>
        
        <?php include("html/dagrListTemplate.html"); ?>
        <?php include("html/dagrMetadataTemplate.html") ?>
        
        <?php include("footer.inc"); ?>
        
        
        
    </body>
</html>


