<?php
// Lists the database users of the specified site

// session starting
$session = \cyberpanelv4::startSession();

// outputs will come as an array
$listdatabase = \cyberpanelv4::listDatabase($session, "example.com");

// print if there is an error
if(\cyberpanelv4::$errors) {
    print_r(\cyberpanelv4::$errors);
} else {
    // array output
    print_r($listdatabase);
}
?>
