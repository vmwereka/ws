<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8">
  <title>Login</title>
 </head>
 <body>
 <?php
 if ($_SERVER['REQUEST_METHOD'] == 'POST') {
require_once '../include/common.php';

$server->requestToken();
// oauth_consumer_key 
// oauth_token
// xoauth_token_ttl
/* 
$options = array(
    'consumer_key' => 'd1108d8018e7633e98f48ae321286e780536b4faf',
    'consumer_secret' => 'a03785a915a0894f533cdc694275f016',
    'server_uri' => OAUTH_HOST,
    'request_token_uri' => OAUTH_HOST . '/request_token.php',
    'authorize_uri' => OAUTH_HOST . '/login.php',
    'access_token_uri' => OAUTH_HOST . '/access_token.php'
); */
}
else{

?>
  <form method="post"
   action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
   <fieldset>
    <div>
     <input type="text"  name="xoauth_token_ttl " value="d1108d8018e7633e98f48ae321286e780536b4faf">
	 <input type="text" name="oauth_token" value="a03785a915a0894f533cdc694275f016">
    </div>
   </fieldset>
   <input type="submit" value="Login">
  </form>
<?php
}
?>
 </body>
</html>
