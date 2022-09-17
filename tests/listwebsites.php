<?php
// Lists all sites belonging to the session

// session starting
$session = \cyberpanelv4::startSession();

// outputs will come as an array
$websites = \cyberpanelv4::listWebsites($session);

// print if there is an error
if(\cyberpanelv4::$errors) {
    print_r(\cyberpanelv4::$errors);
} else {
    // array output
    print_r($websites);
}
?>
