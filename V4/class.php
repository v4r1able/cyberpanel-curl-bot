<?php
/*
Title: CyberPanel Web API - cURL Bot
Author: v4r1able
Web: leventemre.com
*/
class cyberpanelv4 {

public static $errors = array();

// All settings
public static $settings = [
    "panel_address" => "https://demo.cyberpanel.net/",
    "username" => "demoadmin",
    "password" => "cyberpanel123"
];

public static function getCookies($result) {
    preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
    $cookies = array();
    foreach($matches[1] as $item) {
    parse_str($item, $cookie);
    $cookies = array_merge($cookies, $cookie);
    }
    return $cookies;
}

public static function startSession() {

    $csrftoken = curl_init();
    curl_setopt($csrftoken, CURLOPT_URL, self::$settings["panel_address"]);
    curl_setopt($csrftoken, CURLOPT_HEADER, 1);
    curl_setopt($csrftoken, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36");
    curl_setopt($csrftoken, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($csrftoken, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($csrftoken, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($csrftoken, CURLOPT_SSL_VERIFYPEER, false);
    
    $csrftoken_response = curl_exec($csrftoken);

    $csrftoken = self::getCookies($csrftoken_response)["csrftoken"];

    $login = curl_init();
    curl_setopt($login, CURLOPT_URL, self::$settings["panel_address"]."/verifyLogin");
    curl_setopt($login, CURLOPT_POST, 1);
    curl_setopt($login, CURLOPT_POSTFIELDS, json_encode([
        "username" => self::$settings["username"],
        "password" => self::$settings["password"],
        "languageSelection" => "english",
        "twofa" => 1
    ]));
    curl_setopt($login, CURLOPT_HTTPHEADER, array(
        'X-CSRFToken: '.$csrftoken,
        'Content-Type: application/json'
    ));
    curl_setopt($login, CURLOPT_HEADERFUNCTION, function($login, $headerLine) {
        global $cookies;
        if (preg_match('/^Set-Cookie:\s*([^;]*)/mi', $headerLine, $cookie) == 1)
            $cookies[] = $cookie;
        return strlen($headerLine);
    });
    curl_setopt($login, CURLOPT_COOKIE, "csrftoken=".$csrftoken.";");
    curl_setopt($login, CURLOPT_REFERER, self::$settings["panel_address"]);
    curl_setopt($login, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36");
    curl_setopt($login, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($login, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($login, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($login, CURLOPT_SSL_VERIFYPEER, false);

    $verifyLogin = curl_exec($login);
    $verifyLogin = json_decode($verifyLogin, true);

    if($verifyLogin["loginStatus"]==0) {
    array_push(self::$errors, "Login error: ".$verifyLogin["error_message"]);
    } else {
    return array(
        "csrftoken" => $csrftoken,
        "sessionid" => $logincookies["sessionid"]
    );;
    }

}

public static function listWebsites($session) {

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, self::$settings["panel_address"]."/websites/fetchWebsitesList");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'X-CSRFToken: '.$session["csrftoken"],
        'Content-Type: application/json'
    ));
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
        "page" => 1,
        "recordsToShow" => 100
    ]));
    curl_setopt($curl, CURLOPT_COOKIE, "sessionid=".$session["sessionid"]."; csrftoken=".$session["csrftoken"].";");
    curl_setopt($curl, CURLOPT_REFERER, self::$settings["panel_address"]);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $listWebsites = curl_exec($curl);

    $listWebsites = json_decode($listWebsites, true);

    if($listWebsites["listWebSiteStatus"]==1) {
    return $listWebsites["data"];
    } else {
    return $listWebsites["error_message"];
    }

}

public static function createDatabase($session, $website, $dbname, $dbusername, $dbpassword) {

    $webUserName = preg_replace('/-/', "", $website);
    $webUserName = explode(".", $webUserName)[0];

    if(strlen($webUserName)>5) {
     $webUserName = substr($webUserName, 0, 4);
    }

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, self::$settings["panel_address"]."/dataBases/submitDBCreation");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'X-CSRFToken: '.$session["csrftoken"],
        'Content-Type: application/json'
    ));
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
        "webUserName" => $webUserName,
        "databaseWebsite" => $website,
        "dbName" => $dbname,
        "dbUsername" => $dbusername,
        "dbPassword" => $dbpassword
    ]));
    curl_setopt($curl, CURLOPT_COOKIE, "sessionid=".$session["sessionid"]."; csrftoken=".$session["csrftoken"].";");
    curl_setopt($curl, CURLOPT_REFERER, self::$settings["panel_address"]);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $createDatabase = curl_exec($curl);

    $createDatabase = json_decode($createDatabase, true);

    if($createDatabase["status"]==1) {
    return $createDatabase["createDBStatus"];
    } else {
    return $createDatabase["error_message"];
    } 

}

public static function deleteDatabase($session, $dbname) {
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, self::$settings["panel_address"]."/dataBases/submitDatabaseDeletion");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'X-CSRFToken: '.$session["csrftoken"],
        'Content-Type: application/json'
    ));
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
        "dbName" => $dbname
    ]));
    curl_setopt($curl, CURLOPT_COOKIE, "sessionid=".$session["sessionid"]."; csrftoken=".$session["csrftoken"].";");
    curl_setopt($curl, CURLOPT_REFERER, self::$settings["panel_address"]);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $deleteDatabase = curl_exec($curl);

    $deleteDatabase = json_decode($deleteDatabase, true);

    print_r($deleteDatabase);

    if($deleteDatabase["status"]==1) {
    return $deleteDatabase["deleteStatus"];
    } else {
    return $deleteDatabase["error_message"];
    } 

}

public static function listDatabases($session, $website) {

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, self::$settings["panel_address"]."/dataBases/fetchDatabases");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'X-CSRFToken: '.$session["csrftoken"],
        'Content-Type: application/json'
    ));
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
        "databaseWebsite" => $website
    ]));
    curl_setopt($curl, CURLOPT_COOKIE, "sessionid=".$session["sessionid"]."; csrftoken=".$session["csrftoken"].";");
    curl_setopt($curl, CURLOPT_REFERER, self::$settings["panel_address"]);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $listDatabases = curl_exec($curl);

    $listDatabases = json_decode($listDatabases, true);

    if($listDatabases["status"]==1) {
    return $listDatabases["data"];
    } else {
    return $listDatabases["error_message"];
    }

}


public static function listPath($session, $website, $path) {

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, self::$settings["panel_address"]."/filemanager/controller");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'X-CSRFToken: '.$session["csrftoken"],
        'Content-Type: application/json'
    ));
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
        "completeStartingPath" => $path,
        "method" => "list",
        "domainRandomSeed" => "",
        "domainName" => $website
    ]));
    curl_setopt($curl, CURLOPT_COOKIE, "sessionid=".$session["sessionid"]."; csrftoken=".$session["csrftoken"].";");
    curl_setopt($curl, CURLOPT_REFERER, self::$settings["panel_address"]);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $listPath = curl_exec($curl);

    $listPath = json_decode($listPath, true);

    if($listPath["status"]==1) {
    unset($listPath["status"]);
    return $listPath;
    } else {
    return $listPath["error_message"];
    }

}


public static function editFile($session, $website, $filename, $filecontent) {

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, self::$settings["panel_address"]."/filemanager/controller");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'X-CSRFToken: '.$session["csrftoken"],
        'Content-Type: application/json'
    ));
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
        "fileName" => $filename,
        "method" => "writeFileContents",
        "fileContent" => $filecontent,
        "domainRandomSeed" => "",
        "domainName" => $website
    ]));
    curl_setopt($curl, CURLOPT_COOKIE, "sessionid=".$session["sessionid"]."; csrftoken=".$session["csrftoken"].";");
    curl_setopt($curl, CURLOPT_REFERER, self::$settings["panel_address"]);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $editFile = curl_exec($curl);

    $editFile = json_decode($editFile, true);

    if($editFile["status"]==1) {
    return $editFile;
    } else {
    return $listPath["error_message"];
    }

}


public static function renameFile($session, $website, $basepath, $existingname, $newfilename) {

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, self::$settings["panel_address"]."/filemanager/controller");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'X-CSRFToken: '.$session["csrftoken"],
        'Content-Type: application/json'
    ));
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
        "basePath" => $basepath,
        "existingName" => $existingname,
        "newFileName" => $newfilename,
        "method" => "rename",
        "domainRandomSeed" => "",
        "domainName" => $website
    ]));
    curl_setopt($curl, CURLOPT_COOKIE, "sessionid=".$session["sessionid"]."; csrftoken=".$session["csrftoken"].";");
    curl_setopt($curl, CURLOPT_REFERER, self::$settings["panel_address"]);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $renameFile = curl_exec($curl);

    $renameFile = json_decode($renameFile, true);

    if($renameFile["status"]==1) {
    return $renameFile;
    } else {
    return $renameFile["error_message"];
    }

}

public static function deleteFolderOrFile($session, $website, $path, $fileorfolders, $skiptrash) {

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, self::$settings["panel_address"]."/filemanager/controller");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'X-CSRFToken: '.$session["csrftoken"],
        'Content-Type: application/json'
    ));
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
        "path" => $path,
        "fileAndFolders" => $fileorfolders,
        "method" => "deleteFolderOrFile",
        "skipTrash" => $skiptrash,
        "domainRandomSeed" => "",
        "domainName" => $website
    ]));
    curl_setopt($curl, CURLOPT_COOKIE, "sessionid=".$session["sessionid"]."; csrftoken=".$session["csrftoken"].";");
    curl_setopt($curl, CURLOPT_REFERER, self::$settings["panel_address"]);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $deleteFolderOrFile = curl_exec($curl);

    $deleteFolderOrFile = json_decode($deleteFolderOrFile, true);

    print_r($deleteFolderOrFile);

    if($deleteFolderOrFile["status"]==1) {
    return $deleteFolderOrFile;
    } else {
    return $deleteFolderOrFile["error_message"];
    }

}

public static function systemStatus($session) {

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, self::$settings["panel_address"]."/base/getSystemStatus");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'X-CSRFToken: '.$session["csrftoken"],
        'Content-Type: application/json'
    ));
    curl_setopt($curl, CURLOPT_COOKIE, "sessionid=".$session["sessionid"]."; csrftoken=".$session["csrftoken"].";");
    curl_setopt($curl, CURLOPT_REFERER, self::$settings["panel_address"]);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $systemStatus = curl_exec($curl);

    $systemStatus = json_decode($systemStatus, true);

    return $systemStatus;

}

}
?>
