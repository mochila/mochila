<?php


  // Find all duplicate DAGRS (DAGRS with same location/size but different GUID)
function check_duplicate($new_type, $new_location){

    $is_duplicate = false;
    $db = new mysqli("localhost", "root", "dude1313", "mochila_db");

    $statement = $db->prepare("select DAGR_GUID, DAGR_FILE_LOC, DAGR_TYPE, DAGR_SIZE from DAGRS where DAGR_TYPE LIKE ? || DAGR_TYPE ='parent'");
    $statement->bind_param("s", $new_type);

    $statement->bind_result($guid, $location, $type, $size);
    $statement->execute();

    $new_hash_value = null;

    if($new_type == "file"){
	$new_hash_value = hash_file("md5", $new_location);
    } else {
	$new_hash_value = hash("md5", $new_location);
    }

    
    
    while($statement->fetch()){
	//HASH location
	$hash_value = null;
	//If file then hash file contents
	if($type == "file"){
	    $hash_value = hash_file("md5", $location);
	}else {
	    $hash_value = hash("md5", $location);
	}

	if($hash_value == $new_hash_value){
	    $is_duplicate = true;
	    break;
	}

	//echo $new_location." == ".$location;
    }

    $db->close();
    return $is_duplicate;

    
  }


// Add primary index that deletes duplicates for DAGR_FILE_LOC
/* $mysqli->query("ALTER IGNORE TABLE DAGRS ADD PRIMARY KEY(DAGR_FILE_LOC)"); */

/* // Remove the primary key */
/* $mysqli->query("ALTER TABLE DAGRS DROP PRIMARY KEY"); */

/* // Delete all tuples in TAGS where the GUID is not in DAGRS */
/* $mysqli->query("DELETE FROM TAGS WHERE DAGR_GUID NOT IN(SELECT DAGR_GUID FROM DAGRS)"); */

/* // Delete all tuples in CHILD_DAGRS where both GUID are not in DAGRS */
/* $mysli->query("DELETE FROM CHILD_DAGRS where PARENT_GUID NOT IN(SELECT DAGR_GUID FROM DAGRS) OR CHILD_GUID NOT IN(SELECT DAGR_GUID FROM DAGRS)"); */

/* // Any tuples in DAGRS where the PARENT_GUID is no longer in DAGRS, set PARENT_GUID to NULL */
/* $mysli->query("UPDATE DAGRS SET DAGR_PARENT_GUID=NULL WHERE DAGR_PARENT_GUID NOT IN(SELECT PARENT_GUID FROM CHILD_DAGRS)"); */
    ?>
