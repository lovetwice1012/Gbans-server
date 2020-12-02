<?php
$service = true;
if(!$service){
echo "server is down";
exit;
}
if(!isset($_POST["ban"])){
echo "不正なアクセスです";
exit;
}
if(!isset($_POST["banip"])){
echo "no-ip";
exit;
}

$db['host'] = 'localhost';
$db['user'] = 'root';
$db['pass'] = '';
$db['dbname'] = 'gbans';
//追加処理
$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
$pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
try{



$stmt = $pdo->prepare("SELECT * FROM gbanipban WHERE name = ?");
$stmt->execute(array($_POST['IP']));
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if(count($result) > 0&& is_array($result)){
echo "Access denied";
exit;
}

$stmt = $pdo->prepare('INSERT INTO `gbanipban`(`name`, `reason`,`user`,`ip`,`server`,`channel`) VALUES (:username, :reason, :user, :ip, :server, :channel)');
$stmt->bindParam(':username', $_POST["banip"], PDO::PARAM_STR);
$stmt->bindParam(':reason', $_POST["reason"], PDO::PARAM_STR);
$stmt->bindParam(':user', $_POST["user"], PDO::PARAM_STR);
$stmt->bindParam(':ip', $_POST["IP"], PDO::PARAM_STR);
$stmt->bindParam(':server', $_POST["server"], PDO::PARAM_STR);
$stmt->bindParam(':channel', $_POST["channel"], PDO::PARAM_STR);
$stmt->execute();
echo "success";
}catch(Exception $e){
echo "faild";
//echo $e->getMessage();
}
