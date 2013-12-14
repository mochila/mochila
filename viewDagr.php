<?php

$location = $_POST["location"];
$script = "cp ".$location." /var/www/uploads/";
$location = "/uploads".strrchr($location, "/");
shell_exec($script);
echo $location;

?>