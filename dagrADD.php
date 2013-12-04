<?php
$targetURL = $_GET['q'];
$dagrTitle = $_GET['title'];
$dagrTags = $_GET['tags'];
$responseMessage = "Received URL: ", $targetURL, "\nDAGR Title: ", $dagrTitle, "\nDAGR Tags: ", $dagrTags, "\n";
echo $responseMessage;
?>
