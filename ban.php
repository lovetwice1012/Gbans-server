<?php

$service = true;
if(!$service){
echo "server is down";
exit;
}
if(!isset($_POST["ban"])){

header( "HTTP/1.1 404 Not Found" );
echo "<html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this server.</p></body></html>";
exit;
}
if(!isset($_POST["username"])){
echo "no-username";
exit;
}

if(!isset($_POST['IP']) || $_POST['IP']!= "official-bot"){
$ip = $_SERVER["REMOTE_ADDR"];
}else{
$ip = $_POST['IP'];
}
$db['host'] = 'localhost';
$db['user'] = 'root';
$db['pass'] = '';
$db['dbname'] = 'gbans';

$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
$pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

$stmt = $pdo->prepare("SELECT * FROM gbanuban WHERE name = ?");
$stmt->execute(array($_POST['user']));
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if(count($result) > 0&& is_array($result)){
echo "Access denied (user).";
exit;
}

$stmt = $pdo->prepare("SELECT * FROM gbanipban WHERE name = ?");
$stmt->execute(array($ip));
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if(count($result) > 0&& is_array($result)){
echo "Access denied (ip).";
exit;
}
if(isset($_POST['server'])){
$stmt = $pdo->prepare("SELECT * FROM gbanserverban WHERE name = ?");
$stmt->execute(array($_POST['server']));
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if(count($result) > 0&& is_array($result)){
echo "Access denied (server).";
exit;
}
}
/*
$stmt = $pdo->prepare("SELECT * FROM gbanchecklist WHERE name = ? AND ip = ?");
$stmt->execute(array($_POST['username'],$ip));
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if(count($result) == 0 || !is_array($result)){
echo "You have never checked this user.";
exit;
}
*/
require_once("./dosblock.php");
