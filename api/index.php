<?php

require 'Slim/Slim.php';

$app = new Slim();

$app->get('/posts', 'getPosts');
$app->get('/post/:id/:id2',	'getPost');
$app->get('/posts/search/:query', 'findByName');
$app->post('/posts', 'addPost');
$app->put('/posts/:id', 'updatePost');
$app->delete('/posts/:id',	'deletePost');
$app->post('/feedByUser/:id', 'addFeed');
$app->get('/getUserFeeds/:id','getUserFeeds');
$app->post('/addFeed/:id','addFeed');
$app->post('/subscribe/:id', 'subscribeTofeed');
$app->get('/getDetailsFeed/:id','getDetailsFeed');
$app->put('/updateFeed/:id','updateFeed');
$app->post('/tagPost/:id','tagPost');
$app->get('/showPostsByTag/:id','showPostsByTag');
$app->get('/getUserTags/:id','getUserTags');
$app->get('/getSharedPost/:id','getSharedPost');
$app->get('/sharePost/:id/:idpost/:value','sharePost');
$app->get('/deleteFeed/:id',	'deleteFeed');
$app->run();


function deleteFeed($id){
	$sql = "DELETE FROM feeds WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam(":id", $id);
		// $stmt->bindParam("idUser", $idUser);
		$stmt->execute();
		$db = null;
		echo json_encode(array("result"=>"Feed Deleted Succefully"));
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}

}

function getSharedPost($id){
	$sql="
	SELECT P.* 
	FROM  posts P JOIN postmeta PM 
	WHERE PM.user_id=:id AND PM.postmeta_shared='1' AND PM.post_id=P.post_id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		// $post = $stmt->fetchObject();  
		$post = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($post); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
//http://localhost/ws/api/sharePost/3/3/1
function sharePost($id,$idpost,$value){

	//add post->id selected in the form 
	$request = Slim::getInstance()->request();
	//$body = $request->getBody();
	//$post = json_decode($body);
	$sql="INSERT INTO postmeta(user_id,post_id,postmeta_shared) VALUES (:id,:idpost,:value)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":idpost", $idpost);
		$stmt->bindParam(":value", $value);
		$stmt->execute();
		$db = null;
		echo json_encode(array("result"=>"success","shared"=>$value)); 
	} catch(PDOException $e) {
		//error_log($e->getMessage(), 3, '/tmp/php.log');
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
	
}


//Tests
function getUserTags($id){
	$sql = "SELECT T.name  
	FROM posts P JOIN tag T JOIN tagmap TM
	WHERE P.user_id=:id AND  P.user_id=TM.user_id AND T.tag_id=TM.tag_id AND TM.post_id=P.post_id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		// $post = $stmt->fetchObject();  
		$post = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($post); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}


}
//End test

function showPostsByTag($id){
	$request = Slim::getInstance()->request();
	$body = $request->getBody();
	$post = json_decode($body);
	//require id of user
	$sql = "
	SELECT P.post_id,P.feed_id,P.post_description 
	FROM posts P JOIN tag T JOIN tag_map TM
	WHERE P.user_id=:id AND T.tag_id=TM.tag_id AND TM.post_id=P.post_id AND T.name=:name AND TM.user_id=P.user_id ";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->bindParam("name", $post->name);
		$stmt->execute();
		// $post = $stmt->fetchObject();  
		$post = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($post); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
	
}

function tagPost($id){
	$request = Slim::getInstance()->request();
	$body = $request->getBody();
	$post = json_decode($body);
	$sql = "
	INSERT INTO tag (name)
	SELECT * FROM (SELECT ':name') AS tmp
	WHERE NOT EXISTS (
	SELECT name FROM tag WHERE name = ':name'
	) LIMIT 1";
	
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("name", $post->name);
		$stmt->execute();
		$post->id = $db->lastInsertId();
		$db = null;
		echo json_encode($post); 
	} catch(PDOException $e) {
		//error_log($e->getMessage(), 3, '/tmp/php.log');
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
	$sql2="INSERT INTO tag_map (tag_id,post_id) VALUES(:id2,:id)";//
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql2);  
		$stmt->bindParam("id2", $post->id);
		$stmt->bindParam("id", $id);//post_id
		$stmt->execute();
		$post->id = $db->lastInsertId();
		$db = null;
		echo json_encode($post); 
	} catch(PDOException $e) {
		//error_log($e->getMessage(), 3, '/tmp/php.log');
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
function updateFeed($id){
	$request = Slim::getInstance()->request();
	$body = $request->getBody();
	$post = json_decode($body);
	// echo json_encode($id); 
	$sql = "UPDATE feeds SET status=:statuschosen WHERE idUser=:id AND id=:idfeed";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("statuschosen", $post->statuschosen);
		$stmt->bindParam("idfeed", $post->idfeed);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		echo json_encode($post); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}    
}

function getUserFeeds($id){
	$sql = "SELECT * FROM feeds WHERE idUser=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		// $post = $stmt->fetchObject();  
		$post = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($post); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
function addFeed($id) {
	// error_log('addPost\n', 3, '/var/tmp/php.log');	
	$request = Slim::getInstance()->request();
	$post = json_decode($request->getBody());
	$x = new SimpleXmlElement($post->URL, NULL, TRUE);
	if($x->channel->title){
		$title=$x->channel->title;
	}
	$sql = "INSERT INTO feeds (URL,idUser,title) VALUES (:URL,:id,:title)";
	
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("URL", $post->URL);
		$stmt->bindParam("title", $title);
		$stmt->bindParam("id", $id);//id parametre passe en URL
		$stmt->execute();
		$post->id = $db->lastInsertId();
		$db = null;
		echo json_encode($post); 
	} catch(PDOException $e) {
		//error_log($e->getMessage(), 3, '/tmp/php.log');
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
	
	$sql2="INSERT INTO posts (feed_id,post_time,post_author,post_pubdate,post_description,post_title,post_link,post_guid,post_source_title) 
	VALUES (:idFeed,:pubDate,:author,:pubDate,:description,:title,:link,:guid,:source)";
	
	try {
		$db = getConnection();
		$stmt2 = $db->prepare($sql2);  
		if($x->channel->item ){
			foreach($x->channel->item as $entry) {
				$a=array(
				':idFeed'=>$post->id,
				':pubDate'=>$entry->pubDate,
				':author'=>$entry->author,
				':pubDate'=>$entry->pubDate,
				':description'=>$entry->description,
				':title'=>$entry->title,
				':link'=>$entry->link,
				'guid'=>$entry->guid,
				':source'=>$entry->source
				);
				$stmt2->execute($a);
			}
		}
		
		// $a->post_id = $db->lastInsertId();
		//$post2 = $db->lastInsertId();
		$db = null;
		//echo json_encode($a); 
	} catch(PDOException $e) {
		//error_log($e->getMessage(), 3, '/tmp/php.log');
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
function subscribeToFeed($id){
	$request = Slim::getInstance()->request();
	$post = json_decode($request->getBody());
	// $sql = "INSERT INTO subscriptions (user_id,feed_id,feed_url,feed_title,feed_htmlurl,feed_description,feed_cache,last_update,last_update_status,last_token) VALUES ('')";
	$sql = "INSERT INTO subscriptions (user_id,feed_id,feed_url,feed_title) VALUES (:id,:idFeed)";
	
}

function getDetailsFeed($id){
	$request = Slim::getInstance()->request();
	$post = json_decode($request->getBody());
	//Load from Jquery
	$sql = "SELECT * FROM posts WHERE feed_id=:id";//id is the the feed id of course
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		//$post = $stmt->fetchObject();  
		$post = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($post); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
function getPosts() {
	$sql = "select * FROM posts2 ORDER BY post_title";
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

function getPost($id,$id2) {
	$sql = "SELECT * FROM posts WHERE post_id=:id AND feed_id=:id2";//id2 as feed_id
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->bindParam("id2", $id2);
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
	$sql = "INSERT INTO posts (post_title, post_link,post_description,post_source_title) VALUES (:name,:URL,:description,:other)";
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
	$sql = "SELECT * FROM posts WHERE UPPER(post_title) LIKE :query ORDER BY post_title";
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