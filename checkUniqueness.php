<?php
// Find all duplicate DAGRS (DAGRS with same location/size but different GUID)
$db = new mysqli("localhost", "root", "dude1313", "mochila_db");

// Add primary index that deletes duplicates for DAGR_FILE_LOC
$mysqli->query("ALTER IGNORE TABLE DAGRS ADD PRIMARY KEY(DAGR_FILE_LOC)");

// Remove the primary key
$mysqli->query("ALTER TABLE DAGRS DROP PRIMARY KEY");

// Delete all tuples in TAGS where the GUID is not in DAGRS
$mysqli->query("DELETE FROM TAGS WHERE DAGR_GUID NOT IN(SELECT DAGR_GUID FROM DAGRS)");

// Delete all tuples in CHILD_DAGRS where both GUID are not in DAGRS
$mysli->query("DELETE FROM CHILD_DAGRS where PARENT_GUID NOT IN(SELECT DAGR_GUID FROM DAGRS) OR CHILD_GUID NOT IN(SELECT DAGR_GUID FROM DAGRS)");

// Any tuples in DAGRS where the PARENT_GUID is no longer in DAGRS, set PARENT_GUID to NULL
$mysli->query("UPDATE DAGRS SET DAGR_PARENT_GUID=NULL WHERE DAGR_PARENT_GUID NOT IN(SELECT PARENT_GUID FROM CHILD_DAGRS)");
?>
