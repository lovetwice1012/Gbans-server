<?php
$service = true;
if(!$service){
echo "server is down";
exit;
}

if(!isset($_POST["approval"])){

header( "HTTP/1.1 404 Not Found" );
echo "<html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this server.</p></body></html>";
exit;

}

header("Content-type: text/html; charset=utf-8");
 $db['host'] = 'localhost';
$db['user'] = 'root';
$db['pass'] = '';
$db['dbname'] = 'gbans';
$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

 
try{
$pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

$stmt = $pdo->prepare("UPDATE gbanlist SET approval = 'true' WHERE id = ?;");
$stmt->execute(array($_POST['id']));
    echo "success";
}catch (PDOException $e){
	echo "faild";
	die();
}
 
