<?php

require 'Slim/Slim.php';

$app = new Slim();

$app->get('/posts', 'getPosts');
$app->get('/posts/:id',	'getPost');
$app->get('/posts/search/:query', 'findByName');
$app->post('/posts', 'addPost');
$app->put('/posts/:id', 'updatePost');
$app->delete('/posts/:id',	'deletePost');

$app->run();

function getPosts() {
	$sql = "select * FROM posts ORDER BY name";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$posts = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"post": ' . json_encode($posts) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getPost($id) {
	$sql = "SELECT * FROM posts WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$post = $stmt->fetchObject();  
		$db = null;
		echo json_encode($post); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function addPost() {
	// error_log('addPost\n', 3, '/var/tmp/php.log');	
	$request = Slim::getInstance()->request();
	$post = json_decode($request->getBody());
	$sql = "INSERT INTO posts (name, URL,description,other) VALUES (:name,:URL,:description,:other)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("name", $post->name);
		$stmt->bindParam("URL", $post->URL);
		$stmt->bindParam("other", $post->other);
		$stmt->bindParam("description", $post->description);
		$stmt->execute();
		$post->id = $db->lastInsertId();
		$db = null;
		echo json_encode($post); 
	} catch(PDOException $e) {
		//error_log($e->getMessage(), 3, '/tmp/php.log');
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function updatePost($id) {
	$request = Slim::getInstance()->request();
	$body = $request->getBody();
	$post = json_decode($body);
	$sql = "UPDATE post SET name=:name, URL=:URL, other=:other, description=:description WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("name", $post->name);
		$stmt->bindParam("URL", $post->URL);
		$stmt->bindParam("description", $post->description);
		$stmt->bindParam("other", $post->other);
		// $stmt->bindParam("year", $post->year);
		// $stmt->bindParam("description", $post->description);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		echo json_encode($post); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function deletePost($id) {
	$sql = "DELETE FROM posts WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function findByName($query) {
	$sql = "SELECT * FROM posts WHERE UPPER(name) LIKE :query ORDER BY name";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$query = "%".$query."%";  
		$stmt->bindParam("query", $query);
		$stmt->execute();
		$posts = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"post": ' . json_encode($posts) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getConnection() {
	$dbhost="127.0.0.1";
	$dbuser="root";
	$dbpass="";
	$dbname="ws";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

?>