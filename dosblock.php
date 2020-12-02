<?php
$db['host'] = "localhost";  
$db['user'] = "root";  
$db['pass'] = "";  
$db['dbname'] = "gbans"; 
$targetDir = './log/';
$counts = glob($targetDir. DIRECTORY_SEPARATOR . "*.*");
//echo count($counts);
$targetDir1 = './nowblocking/';
$counts1 = glob($targetDir1. DIRECTORY_SEPARATOR . "*.*");
//echo count($counts);

$cktimes = file_get_contents('./blocktime', false, null);
if(time() - 86400 >= $cktimes){
unlink("./blocktime");
file_put_contents("./blocktime", time() . PHP_EOL, FILE_APPEND | LOCK_EX);
$dir = "./nowblocking/";
if (!$handle=opendir($dir)) die("fail load");
while($filename=readdir($handle))
{
 if(!preg_match("/^\./", $filename))
 {
 if (!unlink("$dir/$filename")) die("fail delete");
 }
}
}

$file = "./log/".$_SERVER["REMOTE_ADDR"] ;

file_put_contents($file, time() . PHP_EOL, FILE_APPEND | LOCK_EX);

$lineSize = 11;
$maxRow = 101;
$limitTime = 86400;
$readByte = $lineSize * $maxRow;
$readContent = file_get_contents($file, false, null, filesize($file) - $readByte);
$lines = explode(PHP_EOL, $readContent);
array_shift($lines);
if ($lines[0] + $limitTime < time() || count($lines) < $maxRow) {
if(count($counts) > 3){
echo 'server is down';
exit;
}
require_once("./banscript.php");
exit;
}
$files = "./nowblocking/".$_SERVER["REMOTE_ADDR"] ;

file_put_contents($files, time() . PHP_EOL, FILE_APPEND | LOCK_EX);

$filess = "./blocktime" ;

file_put_contents($filess, time() . PHP_EOL, FILE_APPEND | LOCK_EX);

$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
$pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
$stmt = $pdo->prepare('INSERT INTO `gbanipban`(`name`, `reason`,`user`,`ip`,`server`,`channel`) VALUES (:username, :reason, :user, :ip, :server, :channel)');
$stmt->bindParam(':username', $_SERVER["REMOTE_ADDR"], PDO::PARAM_STR);
$stmt->bindValue(':reason', "too many accesses", PDO::PARAM_STR);
$stmt->bindValue(':user', "dosblock", PDO::PARAM_STR);
$stmt->bindValue(':ip', "dosblock", PDO::PARAM_STR);
$stmt->bindValue(':server', "gbans-main-server-safetysystem", PDO::PARAM_STR);
$stmt->bindValue(':channel', "safety-dosblock", PDO::PARAM_STR);
$stmt->execute();
echo 'you are blocked.(too many accesses)';
exit;
