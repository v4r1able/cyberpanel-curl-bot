<?php
// Deleting files or folders of the specified site

// session starting
$session = \cyberpanelv4::startSession();

$willbedeleted = array(
  "index.php", // file 
  "testfolder" // folder
);

// outputs will come as an array
$deletefolderorfile = \cyberpanelv4::deleteFolderOrFile($session, "example.com", "/home/www.example.com/public_html/", $willbedeleted, 1);

// print if there is an error
if(\cyberpanelv4::$errors) {
    print_r(\cyberpanelv4::$errors);
} else {
    // array output
    print_r($deletefolderorfile);
}
?>
