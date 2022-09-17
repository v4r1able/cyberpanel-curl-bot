<?php
// Shows system status

// session starting
$session = \cyberpanelv4::startSession();

// outputs will come as an array
$systemstatus = \cyberpanelv4::systemStatus($session);

// print if there is an error
if(\cyberpanelv4::$errors) {
    print_r(\cyberpanelv4::$errors);
} else {
    // array output
    print_r($systemstatus);
}
?>
