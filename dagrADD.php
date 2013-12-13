<?php
function guid(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12)
                .chr(125);// "}"
        return $uuid;
    }
}

$mysqli = new mysqli("localhost", "root", "", "mochila_db");

$targetURL = $_GET['q'];
$dagrTitle = $_GET['title'];
$dagrTags = $_GET['tags'];
$dagrPON = $_GET['parentOn'];
$dagrPD = $_GET['parentDagr'];

// Generate a GUID
$dagrGUID = guid();

// Get the type of the input (will be HTML)
$dagrType = 'HTML';
$dagrPGUID = NULL;

/*************************************************************
    Get the parent DAGR if one was selected
**************************************************************/
if ($dagrPON == 1) {
  $getParentStmt = $mysqli->prepare("Select DAGR_GUID from DAGRS where DAGR_TITLE = ?");

  // Bind parameters
  $getParentStmt->bind_param("s", $dagrPDVALUE);
  $dagrPDVALUE = $dagrPD;
  $getParentStmt->bind_result($dagrPGUID);

  // Execute statement
  $getParentStmt->execute();

  // Get the result
  $getParentStmt->fetch();

  $getParentStmt->close();
}

/***************************************************************
    Scrape the target URL to get metadata (date,size,author) for the DAGR.
***************************************************************/
/*$html = file_get_html($targetURL);
//echo $html->plaintext
*/
// Construct the current date (or get the current date)
$dagrDate = "2010-03-12";

// Get the size of the html
$curl = curl_init($targetURL);
curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
curl_exec($curl);
$dagrSize = curl_getinfo($curl, CURLINFO_SIZE_DOWNLOAD);

// Get the author of the URL
$dagrAuthor = 'AuthorNameHere';

/**************************************************************
   Add the dagr to the DAGRS table using a prepared statement.
**************************************************************/

// Prepare the statement
$stmt = $mysqli->prepare("INSERT INTO DAGRS (DAGR_GUID, DAGR_TITLE, DAGR_DATE, DAGR_SIZE, DAGR_FILE_TYPE, DAGR_FILE_LOC, DAGR_AUTHOR, DAGR_PARENT_GUID) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");

// Bind the varaibles
$stmt->bind_param("sssissss", $dagrguidVALUE, $dagrtitleVALUE, $dagrdateVALUE, $dagrsizeVALUE, $dagrtypeVALUE, $dagrlocVALUE, $dagrauthorVALUE, $dagrpguidVALUE);

$dagrguidVALUE = $dagrGUID;
$dagrtitleVALUE = $dagrTitle;
$dagrdateVALUE = $dagrDate;
$dagrsizeVALUE = $dagrSize;
$dagrtypeVALUE = $dagrType;
$dagrlocVALUE = $targetURL;
$dagrauthorVALUE = $dagrAuthor;
$dagrpguidVALUE = $dagrPGUID;

// Execute the statement
$stmt->execute();
$stmt->close();

/***************************************************************
  Add the tags for the dagr to the TAGS tables
****************************************************************/

// Prepare the statement
$addTags = $mysqli->prepare("INSERT INTO TAGS (DAGR_GUID, TAG_TITLE) VALUES(?,?)");

// Bind the parameters
$addTags->bind_param("ss", $dagrGUIDVALUE, $tagVALUE);
$dagrGUIDVALUE = $dagrGUID;

// Split up the tags string into multiple strings
$allTags = explode(";", $dagrTags);
foreach ($allTags as $tag) {
  $tagVALUE = $tag;
  // Execute the statement
  $addTags->execute();
}

// Close the statement
$addTags->close();

/***************************************************************
  Add the GUID to the CHILD_DAGRS table if applicable
***************************************************************/
if ($dagrPON == 1) {
  $addChildDAGRstmt = $mysqli->prepare("INSERT INTO CHILD_DAGRS (PARENT_GUID, CHILD_GUID) VALUES(?,?)");

  $addChildDAGRstmt->bind_param("ss", $dagrPGUIDVALUE, $dagrGUIDVALUE);
  $dagrGUIDVALUE = $dagrGUID;
  $dagrPGUIDVALUE = $dagrPGUID;

  $addChildDAGRstmt->execute();
  $addChildDAGRstmt->close();
}

/***************************************************************
  Echo the variables back to the client
***************************************************************/

$responseMessage = "Received URL: $targetURL\nDAGR Title: $dagrTitle\nDAGR Tags: $dagrTags\nDAGR GUID: $dagrGUID\nDAGR Date: $dagrDate\nDAGR Size: $dagrSize\nDAGR Author: $dagrAuthor\nDAGR PGUID: $dagrPGUID\n";
echo $responseMessage;
?>
