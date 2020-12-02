<?php

$service = true;
if(!$service){
echo "server is down";
exit;
}
if(!isset($_POST["unban"])){
echo "不正なアクセスです";
exit;
}
if(!isset($_POST["username"])){
echo "no-username";
exit;
}

$ip = $_SERVER["REMOTE_ADDR"];

$db['host'] = 'localhost';
$db['user'] = 'root';
$db['pass'] = '';
$db['dbname'] = 'gbans';
//追加処理
$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
$pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

$stmt = $pdo->prepare("SELECT * FROM gbanipban WHERE name = ?");
$stmt->execute(array($ip));
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if(count($result) > 0&& is_array($result)){
echo "Access denied";
exit;
}


require_once("./dosblock2.php");
