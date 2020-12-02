<?php
$db['host'] = 'localhost';
$db['user'] = 'root';
$db['pass'] = '';
$db['dbname'] = 'gbans';
$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
$pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
//追加処理
function hascheckedip($ip){
$db['host'] = 'localhost:3306';
$db['user'] = 'passionalldb';
$db['pass'] = 'passionalldb';
$db['dbname'] = 'passionalldb';
$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
$pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
$stmt = $pdo->prepare("SELECT * FROM gbanchecklist WHERE ip = ?");
$stmt->execute(array($ip));
 
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if(count($result) > 0&& is_array($result)){
return true;
}else{
return false;
}
}
function isvailddata($cip,$uid){

if($cip!==null&&$uid!==null){
$regex = '/\A[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[0-5][a-fA-F0-9]{3}-[089aAbB][a-fA-F0-9]{3}-[a-fA-F0-9]{12}\z/';
if(filter_var( $cip, FILTER_VALIDATE_IP )&& preg_match($regex, $uid)){
return true;
}else{
return false;
}
}else{
return true;
}

}
function isvailduser($user){

$gt = $user;
	$gt_nospace = str_replace(' ', '%20', $gt);
	$url = "http://avatar.xboxlive.com/avatar/" . $gt_nospace . "/avatar-body.png";
	$res = get_headers($url);
	if(substr($res[0], 9, 3)==200){
        return true;
        }else{
        return false;
        }
}
function ipban(){
$db['host'] = 'localhost';
$db['user'] = 'root';
$db['pass'] = '';
$db['dbname'] = 'gbans';
$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
$pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
$stmt = $pdo->prepare('INSERT INTO `gbanipban`(`name`, `reason`,`user`,`ip`,`server`,`channel`) VALUES (:username, :reason, :user, :ip, :server, :channel)');
$stmt->bindParam(':username', $_SERVER["REMOTE_ADDR"], PDO::PARAM_STR);
$stmt->bindValue(':reason', "Non-existent user", PDO::PARAM_STR);
$stmt->bindValue(':user', "dosblock", PDO::PARAM_STR);
$stmt->bindValue(':ip', "dosblock", PDO::PARAM_STR);
$stmt->bindValue(':server', "gbans-main-server-safetysystem", PDO::PARAM_STR);
$stmt->bindValue(':channel', "safety-dosblock", PDO::PARAM_STR);
$stmt->execute();
}
try{
if(!hascheckedip($ip)){
echo"Don't access API directly.";
exit;
}
if(!isvailduser($_POST["user"])&&!isset($_POST['IP'])&&$_POST['user'] != "CONSOLE"){
ipban();
echo "you are blocked.";
exit;
}
if(!isvailduser($_POST["username"])){
echo "check spells and try again.";
exit;
}
if(isset($_POST["cip"])||isset($_POST["uid"])){

$cip = $_POST["cip"];
$uid = $_POST["uid"];
//echo "cip:".$cip.",uid:".$uid;
if(!isvailddata($cip,$uid)){
$allowpassdatacheck = true;
if(!$allowpassdatacheck){
echo "Post data is invaild.";
exit;
}else{
$cip;
$uid;
}
}
}else{
$cip;
$uid;

}

$stmt = $pdo->prepare('INSERT INTO `gbanlist`(`name`, `reason`,`user`,`ip`,`cip`,`uid`,`server`,`channel`) VALUES (:username, :reason, :user, :ip, :cip, :uid, :server, :channel)');
$stmt->bindParam(':username', $_POST["username"], PDO::PARAM_STR);
$stmt->bindParam(':reason', $_POST["reason"], PDO::PARAM_STR);
$stmt->bindParam(':user', $_POST["user"], PDO::PARAM_STR);
$stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
$stmt->bindParam(':cip', $cip, PDO::PARAM_STR);
$stmt->bindParam(':uid', $uid, PDO::PARAM_STR);
$stmt->bindParam(':server', $_POST["server"], PDO::PARAM_STR);
$stmt->bindParam(':channel', $_POST["channel"], PDO::PARAM_STR);
$stmt->execute();
echo "success";
}catch(Exception $e){
echo "faild";
echo $e->getMessage();
}
