<?php
require_once '../include/common.php';
// include('path/to/oauth.php');
include('C:\xampp\htdocs\ws\test\vendor\oauth-php\oauth-php\library\OAuthRequester.php');	
// define('OAUTH_HOST', 'http://' . $_SERVER['SERVER_NAME']);
define('OAUTH_HOST', 'http://localhost/ws/'  );
$id = 7;
 
// Init the OAuthStore
$options = array(
    'consumer_key' => 'd1108d8018e7633e98f48ae321286e780536b4faf',
    'consumer_secret' => 'a03785a915a0894f533cdc694275f016',
    'server_uri' => OAUTH_HOST,
    'request_token_uri' => OAUTH_HOST . '/request_token.php',
    'authorize_uri' => OAUTH_HOST . '/login.php',
    'access_token_uri' => OAUTH_HOST . '/access_token.php'
);
OAuthStore::instance('Session', $options);
 
if (empty($_GET['oauth_token'])) {
    // get a request token
    $tokenResultParams = OauthRequester::requestRequestToken($options['consumer_key'], $id);
 
    header('Location: ' . $options['authorize_uri'] .
        '?oauth_token=' . $tokenResultParams['token'] . 
        '&oauth_callback=' . urlencode('http://' .
            $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']));
}
else {
    // get an access token
    $oauthToken = $_GET['oauth_token'];
    $tokenResultParams = $_GET;
    OAuthRequester::requestAccessToken($options['consumer_key'],
        $tokenResultParams['oauth_token'], $id, 'POST', $_GET);
    $request = new OAuthRequester(OAUTH_HOST . '/test_request.php',
        'GET', $tokenResultParams);
    $result = $request->doRequest(0);
    if ($result['code'] == 200) {
        var_dump($result['body']);
    }
    else {
        echo 'Error';
    }
}