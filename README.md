# CyberPanel cURL Bot

* All you need to do is to enter the panel address, username and password of the cyberpanel user via $settings in "class.php".
* The "startSession" function logs into the cyberpanel and starts a session with your information, and other functions act using this session.
* You can see how all functions are used and what information is entered via the "tests" folder.
* Works on php 5.6.0 or higher

Add the "cyberpanelv4" class to your file
-----------------------
```php
$directory = __DIR__;
require($directory . '/V4/class.php');
```

Simple Examples
===========

Start session and list user websites
-----------------------

```php
// session starting
$session = \cyberpanelv4::startSession();

// outputs will come as an array.
$websites = \cyberpanelv4::listWebsites($session);

// print if there is an error
if(\cyberpanelv4::$errors) {
    print_r(\cyberpanelv4::$errors);
} else {
    // array output
    print_r($websites);
}
```

Start session and list directory
-----------------------

```php
// session starting
$session = \cyberpanelv4::startSession();

// outputs will come as an array.
$directory = \cyberpanelv4::listPath($session, "example.com", "/home/www.example.com/public_html/");

// print if there is an error
if(\cyberpanelv4::$errors) {
    print_r(\cyberpanelv4::$errors);
} else {
    // array output
    print_r($directory);
}
```

Save session with $_SESSION
-----------------------
```php
// php session start
session_start();

// session starting
$session = \cyberpanelv4::startSession();

// print if there is an error
if(\cyberpanelv4::$errors) {
    print_r(\cyberpanelv4::$errors);
} else {
    // save the session
    $_SESSION["cyberpanel_session"] = $session;
}
```


Use the saved session
-----------------------
```php
// php session start
session_start();

$saved_session = $_SESSION["cyberpanel_session"];

// example
// outputs will come as an array.
$websites = \cyberpanelv4::listWebsites($saved_session);

// print if there is an error
if(\cyberpanelv4::$errors) {
    print_r(\cyberpanelv4::$errors);
} else {
    // array output
    print_r($websites);
}
```
