<?php
// Deletes the database

// session starting
$session = \cyberpanelv4::startSession();

// outputs will come as an array
$deletedatabase = \cyberpanelv4::deleteDatabase($session, "database_name");

// print if there is an error
if(\cyberpanelv4::$errors) {
    print_r(\cyberpanelv4::$errors);
} else {
    // array output
    print_r($deletedatabase);
}
?>
