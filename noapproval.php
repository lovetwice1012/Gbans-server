<?php
$service = true;
if(!$service){
echo "server is down";
exit;
}

if(!isset($_POST["noapproval"])){
echo "不正なアクセスです";
exit;
}

header("Content-type: text/html; charset=utf-8");
 $db['host'] = 'localhost';
$db['user'] = 'root';
$db['pass'] = '';
$db['dbname'] = 'gbans';
$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

 
try{
	$dbh = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

	$sql = "SELECT * FROM gbanlist WHERE approval = 'false'";
	$statement = $dbh -> query($sql);
	

	while($row = $statement->fetch( PDO::FETCH_ASSOC )){
		echo "id:".$row['id'].", name:".$row['name'].", reason:".$row['reason'].", time:".$row['time'].", user:".$row['user'].", IP:".$row['ip'].", server:".$row['server'].", channel:".$row['channel']."\n";
	}
	
    
}catch (PDOException $e){
	print('Error:'.$e->getMessage());
	die();
}
 
