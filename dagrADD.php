<?php
include 'simple_html_dom.php';

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

function linkAdd($linkURL, $linkSize, $linkAuthor, $linkDate, $linkFileType, $parentTitle, $curLinkNum, $parentGUID, $mysqli) {
  // Insert link into DAGRS table
  $dagrInsrt = $mysqli->prepare("INSERT INTO DAGRS (DAGR_GUID, DAGR_TITLE, DAGR_DATE, DAGR_SIZE, DAGR_FILE_TYPE, DAGR_FILE_LOC, DAGR_AUTHOR, DAGR_PARENT_GUID) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");

  $dagrInsrt->bind_param("sssissss", $linkGUIDVALUE, $linkTITLEVALUE, $linkDATEVALUE, $linkSIZEVALUE, $linkFILETYPEVALUE, $linkURLVALUE, $linkAUTHORVALUE, $linkPARENTVALUE);
  
  $linkGUIDVALUE = guid();
  if ($linkFileType == 'img') {
    $linkTITLEVALUE = $parentTitle . '<img' . $curLinkNum . '>';
  } else {
    $linkTITLEVALUE = $parentTitle . '<link' . $curLinkNum . '>';
  }
  $linkDATEVALUE = $linkDate;
  $linkSIZEVALUE = $linkSize;
  $linkFILETYPEVALUE = $linkFileType;
  $linkURLVALUE = $linkURL;
  $linkAUTHORVALUE = $linkAuthor;
  $linkPARENTVALUE = $parentGUID;

  $dagrInsrt->execute();
  $dagrInsrt->close();

  // Insert link into the CHILD_DAGRS table
  $cdagrInsrt = $mysqli->prepare("INSERT INTO CHILD_DAGRS (PARENT_GUID, CHILD_GUID) VALUES(?,?)");

  $cdagrInsrt->bind_param("ss", $linkPARENTVALUE, $linkGUIDVALUE);

  $cdagrInsrt->execute();
  $cdagrInsrt->close();
}

$mysqli = new mysqli("localhost", "root", "dude1313", "mochila_db");

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
// Get the size and date of the html
$curl = curl_init($targetURL);
curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
curl_setopt($curl, CURLOPT_HEADER,TRUE);
curl_setopt($curl, CURLOPT_NOBODY,TRUE);
curl_setopt($curl, CURLOPT_FILETIME,TRUE);

$curlResult = curl_exec($curl);
$curlinfo = curl_getinfo($curl);

curl_close($curl);

$dagrDateUNF = $curlinfo['filetime'];
$dagrSize = $curlinfo['download_content_length'];

if ($dagrSize == -1) {
  $dagrSize = NULL;
}

$dagrDate;
if ($dagrDateUNF == -1) {
  $dagrDateUNF = time();
}
$dt = new DateTime("@$dagrDateUNF");
$dagrDate = $dt->format('d/m/y');


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
if ($dagrTags != NULL) {
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
}

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

/*************************************************************
  Get all links, images, and videos and add to the database
**************************************************************/
$html = file_get_html($targetURL);

// Get all external links
$curLinkNum = 1;
$linkregex = "#(https?|ftp)://.#";
foreach($html->find('a') as $link) {
  if (preg_match($linkregex, $link->href)) {
    // Get the link
    $linkURL = $link->href;

    // Get the size  and date of the link
    $linkCurl = curl_init($linkURL);
    curl_setopt($linkCurl, CURLOPT_RETURNTRANSFER,TRUE);
    curl_setopt($linkCurl, CURLOPT_HEADER,TRUE);
    curl_setopt($linkCurl, CURLOPT_NOBODY,TRUE);
    curl_setopt($linkCurl, CURLOPT_FILETIME,TRUE);
    curl_exec($linkCurl);
    $linkCurlinfo = curl_getinfo($linkCurl);
    
    $linkDateUNF = $linkCurlinfo['filetime'];
    $linkSize = $linkCurlinfo['download_content_length'];
    $linkDateUNF = curl_getinfo($linkCurl, CURLINFO_FILETIME);

    if ($linkSize == -1) {
      $linkSize = NULL;
    }

    if ($linkDateUNF == -1) {
     $linkDateUNF = time();
    }
    
    $linkdt = new DateTime("@$linkDateUNF");
    $linkDate = $linkdt->format('d/m/y');

    curl_close($linkCurl);

    $linkAuthor = "Link Author Here";
    $linkPGUID = $dagrGUID;
    $linkFileType = 'HTML';

    // Add the link to the database
    linkAdd($linkURL, $linkSize, $linkAuthor, $linkDate, $linkFileType, $dagrTitle, $curLinkNum, $linkPGUID, $mysqli);
    $curLinkNum++;
  }
}

// Get all images
$curlImgNum = 1;
foreach($html->find('img') as $image) {
  echo $image->src;
  // Append the image src to the end of the targetURL
  $imgURL = $targetURL . $image->src;
  // Get the size and last modified date of image
  $imgCurl = curl_init($imgURL);
  curl_setopt($imgCurl, CURLOPT_RETURNTRANSFER,TRUE);
  curl_setopt($imgCurl, CURLOPT_HEADER,TRUE);
  curl_setopt($imgCurl, CURLOPT_NOBODY,TRUE);
  curl_setopt($imgCurl, CURLOPT_FILETIME,TRUE);
  curl_exec($imgCurl);
  $imgCurlinfo = curl_getinfo($imgCurl);
    
  $imgDateUNF = $imgCurlinfo['filetime'];
  $imgSize = $imgCurlinfo['download_content_length'];
  $imgDateUNF = curl_getinfo($imgCurl, CURLINFO_FILETIME);

  if ($imgSize == -1) {
    $imgSize = NULL;
  }

  if ($imgDateUNF == -1) {
   $imgDateUNF = time();
  }
    
  $imgdt = new DateTime("@$imgDateUNF");
  $imgDate = $imgdt->format('d/m/y');

  curl_close($imgCurl);  

  // Get the author, parent GUID, and file type
  $imgAuthor = "Image author here";
  $imgPGUID = $dagrGUID;
  $imgFileType = 'img';

  // Add the image to the DAGR table
  linkAdd($imgURL, $imgSize, $imgAuthor, $imgDate, $imgFileType, $dagrTitle, $curlImgNum, $imgPGUID, $mysqli);
  $curlImgNum++;
}

/***************************************************************
  Echo the variables back to the client
***************************************************************/

$responseMessage = "Received URL: $targetURL\nDAGR Title: $dagrTitle\nDAGR Tags: $dagrTags\nDAGR GUID: $dagrGUID\nDAGR Date: $dagrDate\nDAGR Size: $dagrSize\nDAGR Author: $dagrAuthor\nDAGR PGUID: $dagrPGUID\n";
//echo $responseMessage;
?>
