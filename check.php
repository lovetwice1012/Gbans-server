<?php
$service = true;
if(!$service){
echo "server is down";
exit;
}
if(!isset($_POST["check"])){

header( "HTTP/1.1 404 Not Found" );
echo "<html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this server.</p></body></html>";
exit;

}
if(!isset($_POST["username"])){
echo "no-username";
exit;
}

$ip = $_SERVER["REMOTE_ADDR"];

//dbデータ
$db['host'] = 'localhost';
$db['user'] = 'root';
$db['pass'] = '';
$db['dbname'] = 'gbans';
//データ取得
$username = $_POST['username'];
if(isset($_POST["cip"])||isset($_POST["uid"])){
$cip = $_POST["cip"];
$uid = $_POST["uid"];
}else{
$cip;
$uid;
}
$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
try {
$pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

$stmt = $pdo->prepare('INSERT INTO `gbanchecklist`(`name`,`ip`,`cip`,`uid`) VALUES (:username,:ip,:cip,:uid)');
$stmt->bindParam(':username', $_POST["username"], PDO::PARAM_STR);
$stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
$stmt->bindParam(':cip', $cip, PDO::PARAM_STR);
$stmt->bindParam(':uid', $uid, PDO::PARAM_STR);
$stmt->execute();

if(isset($cip)&&isset($uid)){
$stmt = $pdo->prepare("SELECT * FROM gbanlist WHERE (name = ? OR ip = ? OR uid = ?) AND approval = 'true'");
$stmt->execute(array($username,$cip,$uid));
 }else{

$stmt = $pdo->prepare("SELECT * FROM gbanlist WHERE name = ? AND approval = 'true'");
$stmt->execute(array($username));

}
$result = $stmt->fetch(PDO::FETCH_ASSOC);
//print_r($result);
//echo count($result);

if(count($result) > 0&& is_array($result)){
echo "Banned";
}else{
echo "safe";
}
} catch (PDOException $e) {
echo "gbanサーバーでエラーが発生しました";
echo $e->getMessage();
}
