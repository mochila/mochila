<?php
//$mysqli = new mysqli("db_server", "username", "password", "mochila_db");
$targetURL = $_GET['q'];
$dagrTitle = $_GET['title'];
$dagrTags = $_GET['tags'];

// Generate a GUID
$dagrGUID = com_create_guid();

// Get the type of the input (will be HTML)
$dagrType = 'HTML';

// Get the parent GUID (if there is one)
$dagrPGUID = "ParentGUIDHere";


/***************************************************************
    Scrap the target URL to get metadata (date,size,author) for the DAGR.
***************************************************************/
/*$metaTags = get_meta_tags($targetURL);
$dagrAuthor = $metaTags['author'];

// Construct the current date (or get the current date)
$dagrDate = "2010-03-12";

// Get the size of the html
$dagrSize = 10;

// Get the author of the URL
$dagrAuthor = 'AuthorNameHere';

/**************************************************************
   Add the dagr to the database using a prepared statement.
**************************************************************/
/*$stmt = mysqli->prepare("INSERT INTO DAGRS (DAGR_GUID, DAGR_TITLE, DAGR_DATE, DAGR_SIZE, DAGR_FILE_TYPE, DAGR_FILE_LOC, DAGR_AUTHOR, DAGR_PARENT_GUID) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");

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

// Close the sqli connection
$mysqli->close();

//*****************************************************************
*/
$responseMessage = "Received URL: $targetURL\nDAGR Title: $dagrTitle\nDAGR Tags: $dagrTags\nDAGR GUID: $dagrGUID\n";
echo $responseMessage;
?>
