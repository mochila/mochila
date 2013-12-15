<?php

$location = $_POST["location"];

if(file_exists($location)){
    $script = "cp ".$location." /var/www/uploads/";
    $location = "/uploads".strrchr($location, "/");
    shell_exec($script);
} else {
    $location =  "fileNotFound.php";
    
    
}
echo "http://coffeecupcoding.com/".$location;

?>