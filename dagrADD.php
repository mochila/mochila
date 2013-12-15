
<?php

include 'simple_html_dom.php';
require_once("guidGenerator.php");

function linkAdd($linkURL, $linkSize, $linkAuthor, $linkDate, $linkFileType, $linkTitle, $curLinkNum, $parentGUID, $mysqli) {
    $url_type_def = "url";
    // Insert link into DAGRS table
    $dagrInsrt = $mysqli->prepare("INSERT INTO DAGRS (DAGR_GUID, DAGR_TITLE, DAGR_DATE, DAGR_SIZE, DAGR_TYPE, DAGR_FILE_TYPE, DAGR_FILE_LOC, DAGR_AUTHOR, DAGR_PARENT_GUID) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $dagrInsrt->bind_param("sssisssss", $linkGUIDVALUE, $linkTitle, $linkDATEVALUE, $linkSIZEVALUE, $url_type_def, $linkFILETYPEVALUE, $linkURLVALUE, $linkAUTHORVALUE, $linkPARENTVALUE);
    
    
    
    $linkGUIDVALUE = guid();
//    if ($linkFileType == 'img') {
//        $linkTITLEVALUE = $parentTitle . '<img' . $curLinkNum . '>';
//    } else {
//        $linkTITLEVALUE = $parentTitle . '<link' . $curLinkNum . '>';
//    }
    $linkDATEVALUE = $linkDate;
    $linkSIZEVALUE = $linkSize;
    $linkFILETYPEVALUE = $linkFileType;
    $linkURLVALUE = $linkURL;
    $linkAUTHORVALUE = $linkAuthor;
    $linkPARENTVALUE = $parentGUID;
    
    echo $linkDATEVALUE."\n";
    echo $linkGUIDVALUE."\n";
    echo $linkSIZEVALUE."\n";
    echo $linkFILETYPEVALUE."\n";
    echo $linkURLVALUE."\n";
    echo $linkAUTHORVALUE."\n";
    echo $linkPARENTVALUE."\n";
    echo $linkURLVALUE."\n";
    echo($url_type_def);
    
    
    
    $dagrInsrt->execute();
    $dagrInsrt->close();
    
    // Insert link into the CHILD_DAGRS table
    //   $cdagrInsrt = $mysqli->prepare("INSERT INTO CHILD_DAGRS (PARENT_GUID, CHILD_GUID) VALUES(?,?)");
    //   
    //   $cdagrInsrt->bind_param("ss", $linkPARENTVALUE, $linkGUIDVALUE);
    
    //   $cdagrInsrt->execute();
    //   $cdagrInsrt->close();
    
    $mysqli->commit();
}


$mysqli = new mysqli("localhost", "root", "dude1313", "mochila_db");
$url_type_def = "url";
$targetURL = $_GET['q'];
$dagrTitle = $_GET['title'];
$dagrTags = $_GET['tags'];
$dagrAuthor = $_GET['author'];
$dagrPON = $_GET['parentOn'];
$dagrPD = $_GET['parentDagr'];


//$targetURL = "http://www.google.com";

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
    Use CURL to get the size and last modified date of html
***************************************************************/
// Get the size and date of the html
$curl = curl_init($targetURL);
curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
curl_setopt($curl, CURLOPT_HEADER,TRUE);
curl_setopt($curl, CURLOPT_NOBODY,TRUE);
curl_setopt($curl, CURLOPT_FILETIME,TRUE);
curl_setopt($curl, CURLOPT_URL, $targetURL);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

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

date_default_timezone_set("America/New_York");
$dt = new DateTime("@$dagrDateUNF");
$dagrDate = $dt->format('d/m/y');

$html = file_get_html($targetURL);
if($dagrTitle == "" || $dagrTitle == null){
    $domTitle = $html->find("title");
    $dagrTitle = $domTitle[0]->innertext;
}



/**************************************************************
   Add the dagr to the DAGRS table using a prepared statement.
**************************************************************/

// Prepare the statement

$dagrPARENTTYPE = "parent";
$stmt = $mysqli->prepare("INSERT INTO DAGRS (DAGR_GUID, DAGR_TITLE, DAGR_DATE, DAGR_SIZE, DAGR_TYPE, DAGR_FILE_TYPE, DAGR_FILE_LOC, DAGR_AUTHOR, DAGR_PARENT_GUID) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");

// Bind the varaibles
$stmt->bind_param("sssisssss", $dagrguidVALUE, $dagrtitleVALUE, $dagrdateVALUE, $dagrsizeVALUE, $dagrPARENTTYPE, $dagrtypeVALUE, $dagrlocVALUE, $dagrauthorVALUE, $dagrpguidVALUE);

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
//if ($dagrPON == 1) {
//   $addChildDAGRstmt = $mysqli->prepare("INSERT INTO CHILD_DAGRS (PARENT_GUID, CHILD_GUID) VALUES(?,?)");
//   
//   $addChildDAGRstmt->bind_param("ss", $dagrPGUIDVALUE, $dagrGUIDVALUE);
//   $dagrGUIDVALUE = $dagrGUID;
//   $dagrPGUIDVALUE = $dagrPGUID;
//   
//   $addChildDAGRstmt->execute();
//   $addChildDAGRstmt->close();
//}

/*************************************************************
  Get all links, images, and videos and add to the database
**************************************************************/

// Get all external links
$curLinkNum = 1;
$linkregex = "#(https?|ftp)://.#";
$linkList = $html->find('a');
foreach($linkList as $link) {
    if (preg_match($linkregex, $link->href)) {
        // Get the link
        $linkURL = $link->href;
        
        // Get the size  and date of the link
        $linkCurl = curl_init($linkURL);
        curl_setopt($linkCurl, CURLOPT_RETURNTRANSFER,TRUE);
        curl_setopt($linkCurl, CURLOPT_HEADER,TRUE);
        curl_setopt($linkCurl, CURLOPT_NOBODY,TRUE);
        curl_setopt($linkCurl, CURLOPT_FILETIME,TRUE);
        curl_setopt($linkCurl, CURLOPT_URL, $targetURL);
        curl_setopt($linkCurl, CURLOPT_FOLLOWLOCATION, 1);
        
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
        
        $linkAuthor = NULL;
        $linkPGUID = $dagrGUID;
        $linkFileType = 'HTML';
        $linkTitle = trim($link->plaintext);
        
        // Add the link to the database
        linkAdd($linkURL, $linkSize, $linkAuthor, $linkDate, $linkFileType, $linkTitle, $curLinkNum, $linkPGUID, $mysqli);
        $curLinkNum++;
    }
}

// Get all images
$curlImgNum = 1;
foreach($html->find('img') as $image) {
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
    $imgAuthor = NULL;
    $imgPGUID = $dagrGUID;
    $imgFileType = 'img';
    $imageTitle = trim($image->alt);
    
    // Add the image to the DAGR table
    linkAdd($imgURL, $imgSize, $imgAuthor, $imgDate, $imgFileType, $imageTitle, $curlImgNum, $imgPGUID, $mysqli);
    $curlImgNum++;
}

/***************************************************************
  Echo the variables back to the client
***************************************************************/

$responseMessage = "Received URL: $targetURL\nDAGR Title: $dagrTitle\nDAGR Tags: $dagrTags\nDAGR GUID: $dagrGUID\nDAGR Date: $dagrDate\nDAGR Size: $dagrSize\nDAGR Author: $dagrAuthor\nDAGR PGUID: $dagrPGUID\n";
echo $responseMessage;
echo "worked";
?>

