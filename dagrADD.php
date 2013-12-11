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

$targetURL = $_GET['q'];
$dagrTitle = $_GET['title'];
$dagrTags = $_GET['tags'];

// Generate a GUID
$dagrGUID = guid();

// Get the type of the input (will be HTML)
$dagrType = 'HTML';

// Get the parent GUID (if there is one)
$dagrPGUID = "ParentGUIDHere";


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
   Add the dagr to the database using a prepared statement.
**************************************************************/

$mysqli = new mysqli("localhost", "root", "dude1313", "mochila_db");$stmt = $mysqli->prepare("INSERT INTO DAGRS (DAGR_GUID, DAGR_TITLE, DAGR_DATE, DAGR_SIZE, DAGR_FILE_TYPE, DAGR_FILE_LOC, DAGR_AUTHOR, DAGR_PARENT_GUID) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");

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

$responseMessage = "Received URL: $targetURL\nDAGR Title: $dagrTitle\nDAGR Tags: $dagrTags\nDAGR GUID: $dagrGUID\nDAGR Date: $dagrDate\nDAGR Size: $dagrSize\nDAGR Author: $dagrAuthor\nDAGR PGUID: $dagrPGUID\n";
echo $responseMessage;
?>
