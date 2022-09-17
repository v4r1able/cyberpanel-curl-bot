<?php
// Create database user to specific site

// session starting
$session = \cyberpanelv4::startSession();

// outputs will come as an array
$createdatabase = \cyberpanelv4::createDatabase($session, "example.com", "database_name", "database_username", "database_password");

// print if there is an error
if(\cyberpanelv4::$errors) {
    print_r(\cyberpanelv4::$errors);
} else {
    // array output
    print_r($createdatabase);
}
?>
