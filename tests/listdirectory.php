<?php
// Lists all files and folders belonging to the specified site and folder

// session starting
$session = \cyberpanelv4::startSession();

// outputs will come as an array
$directory = \cyberpanelv4::listPath($session, "example.com", "/home/www.example.com/public_html/");

// print if there is an error
if(\cyberpanelv4::$errors) {
    print_r(\cyberpanelv4::$errors);
} else {
    // array output
    print_r($directory);
}
?>
