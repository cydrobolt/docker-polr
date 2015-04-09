<?php
require_once("lib-password.php");
function rstr($length = 34) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}
function hashpass($pass, $salt = "") {
    if (!$salt) {
        $salt = rstr(60);
    }    
    $opts = array(
        'cost' => 10,
        'salt' => $salt
    );   
    $hashed = password_hash($pass, PASSWORD_BCRYPT, $opts);
    return $hashed;
}
$dbhost = getenv("DB_HOST");
$dbuser = getenv("DB_USER");
if (!$dbuser) {
    $dbuser = getenv("MYSQL_ENV_MYSQL_USER");
}
$dbpass = getenv("DB_PASS");
if (!$dbpass) {
    $dbpass = getenv("MYSQL_ENV_MYSQL_PASSWORD");
}
$db = getenv("DB_DATABASE");
if (!$db) {
    $db = getenv("MYSQL_ENV_MYSQL_DATABASE");
}
$appurl = getenv("APP_URL");
$appname = getenv("APP_NAME");
$setuppass = hashpass(getenv("SETUP_PASSWORD"));
$regtype = getenv("REG_TYPE");
$ipmethod = getenv("IP_METHOD");
$recovery = "false";
$private = getenv("PRIVATE");
$theme = getenv("THEME");
$adminuser = getenv("ADMIN_USER");
$adminpass = hashpass(getenv("ADMIN_PASSWORD"));
$adminemail = getenv("ADMIN_EMAIL");
$data = '<?php '
    . '$host="' . $dbhost . '";'
    . '$user="' . $dbuser . '";'
    . '$passwd="' . $dbpass . '";'
    . '$db="' . $db . '";'
    . '$wsa="' . $appurl . '";'
    . '$wsn="' . $appname . '";'
    . '$wsb="' . date("F d Y") . '";'
    . '$ppass="' . $setuppass  . '";'
    . '$ip=' . $ipmethod . ';'
    . '$hp="' . sha1(rstr(30)) . '";'
    . '$regtype=' . $regtype . ';'
    . '$path="/";'
    . '$fpass=' . $recovery . ';'
    . '$li_shorten_only=' . $private . ';'
    . '$theme="' . $theme . '";'
    . '$unstr="' . rstr(50) . '";'
    . "?>\n";
file_put_contents("config.php", $data);
$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $db) ;
$mysqli->query('
 CREATE TABLE `api` (
   `valid` tinyint(1) NOT NULL,
   `email` varchar(50) NOT NULL,
   `apikey` varchar(70) NOT NULL,
   `quota` int(11) NOT NULL,
   PRIMARY KEY (`apikey`),
   UNIQUE KEY `email` (`email`),
   KEY `email_2` (`email`),
   KEY `valid` (`valid`),
   KEY `aindex` (`valid`,`email`)
 );');
$err = $mysqli->error;
if (strstr($err, "already exists")) {
    die();
}
 $mysqli->query('
 CREATE TABLE `auth` (
     `username` varchar(50) NOT NULL,
     `password` text NOT NULL,
     `email` varchar(65) NOT NULL,
     `rkey` varchar(65) NOT NULL,
     `role` varchar(37) NOT NULL,
     `valid` tinyint(1) NOT NULL DEFAULT "0",
     `uid` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
     `theme` varchar(65) NOT NULL,
     `ip` tinytext NOT NULL,
     KEY `valid` (`valid`),
     KEY `email3` (`email`),
     KEY `username2` (`username`)
 );');

$mysqli->query('
CREATE TABLE `redirinfo` (
   `rurl` varchar(80) NOT NULL,
   `rid` smallint(200) NOT NULL AUTO_INCREMENT,
   `baseval` varchar(30) NOT NULL,
   `ip` varchar(90) NOT NULL,
   `iscustom` varchar(4) NOT NULL,
   `user` tinytext NOT NULL,
   `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
   `country` varchar(10) NOT NULL,
   `lkey` tinytext NOT NULL,
   `clicks` int(11) NOT NULL,
   `pw` int(120) NOT NULL,
   `etc` text,
   `etc2` text,
   PRIMARY KEY (`rid`),
   KEY `rurl` (`rurl`),
   KEY `baseval` (`baseval`),
   KEY `baseval_2` (`baseval`),
   KEY `rurl_2` (`rurl`),
   KEY `ip` (`ip`),
   KEY `iscustom` (`iscustom`),
   KEY `rurl_3` (`rurl`,`rid`,`baseval`,`ip`,`iscustom`)
 );');
 $mysqli->query('
 CREATE TABLE `redirinfo-temp` (
   `rurl` varchar(80) NOT NULL,
   `rid` smallint(200) NOT NULL AUTO_INCREMENT,
   `baseval` varchar(30) NOT NULL,
   `ip` varchar(90) NOT NULL,
   `iscustom` varchar(4) NOT NULL,
   `user` tinytext NOT NULL,
   `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
   `country` tinytext NOT NULL,
   `theme` varchar(65) NOT NULL,
   `clicks` int(11) NOT NULL,
   `pw` int(120) NOT NULL,
   `etc` text,
   `etc2` text,
   PRIMARY KEY (`rid`),
   KEY `rurl` (`rurl`),
   KEY `baseval` (`baseval`),
   KEY `baseval_2` (`baseval`),
   KEY `rurl_2` (`rurl`),
   KEY `ip` (`ip`),
   KEY `iscustom` (`iscustom`),
   KEY `rurl_3` (`rurl`,`rid`,`baseval`,`ip`,`iscustom`)
 );');
$nr = sha1(rstr(50));
$mysqli->query("INSERT INTO auth (username,email,password,rkey,valid,role) VALUES ('{$adminuser}','{$adminemail}','{$adminpass}','{$nr}','1','adm')");
?>
