<?php
// Edits the file of the specified site

// session starting
$session = \cyberpanelv4::startSession();

// content
$filecontent = "test content";

// outputs will come as an array
$editfile = \cyberpanelv4::editFile($session, "example.com", "/home/www.example.com/public_html/index.php", $filecontent);

// print if there is an error
if(\cyberpanelv4::$errors) {
    print_r(\cyberpanelv4::$errors);
} else {
    // array output
    print_r($editfile);
}
?>
