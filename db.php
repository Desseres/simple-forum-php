<?php
// TODO: ???? session_start(); ???
session_start();

// TODO: przenieść config do pliku zewnętrznego który nie musi być w repo
$DB_host = "";
$DB_user = "";
$DB_pass = "";
$DB_name = "";

try
{
     $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass);
     $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
     echo $e->getMessage();
}

// TODO: Tutaj powinien być zastosowany autoload
include_once 'class.user.php';
$user = new USER($DB_con);

include_once 'class.forum.php';
$forum = new FORUM($DB_con);

include_once 'class.topic.php';
$topic = new TOPIC($DB_con);

include_once 'class.post.php';
$post = new POST($DB_con);

?>
