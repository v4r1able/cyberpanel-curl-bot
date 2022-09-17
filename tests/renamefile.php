<?php
// Changes the name of the file for the specified site

// session starting
$session = \cyberpanelv4::startSession();

$existingname = "index.html";
$newname = "index.php";

// outputs will come as an array
$renamefile = \cyberpanelv4::renameFile($session, "example.com", "/home/www.example.com/public_html/", $existingname, $newname);

// print if there is an error
if(\cyberpanelv4::$errors) {
    print_r(\cyberpanelv4::$errors);
} else {
    // array output
    print_r($renamefile);
}
?>
