<?php
ini_set('display_errors', true);
error_reporting(-1);

// Include the Composer autoloader
include 'vendor/autoload.php';

// Include the storage models
include 'model_scope.php';
include 'model_session.php';

// Include the user model
include 'model_user.php';

// New Slim app
$app = new \Slim\Slim();

// Initiate the Request handler
$request = new \OAuth2\Util\Request();

// Initiate the auth server with the models
$server = new \OAuth2\ResourceServer(new SessionModel, new ScopeModel);

// Function called when each endpoint is called to check the access token
//  exists and is valid
$checkToken = function () use ($server) {

	return function() use ($server)
	{
		// Test for token existance and validity
    	try {
    		$server->isValid();
    	}

    	// The access token is missing or invalid...
    	catch (\OAuth2\Exception\InvalidAccessTokenException $e)
    	{
    		$app = \Slim\Slim::getInstance();
    		$res = $app->response();
			$res['Content-Type'] = 'application/json';
			$res->status(403);

			$res->body(json_encode(array(
				'error'	=>	$e->getMessage()
			)));
    	}
    };

};



// This first endpoint requires an access token but has no scope limitations
$app->get('/', $checkToken(), function() use ($server, $app) {

	$res = $app->response();
	$res['Content-Type'] = 'application/json';

	$res->body(json_encode(array(
		'error'	=>	null,
		'result'	=>	array(
			'message'	=>	'A route which requires an access token, but is not limited by scope'
		)
	)));

});


// Only an access token that represents a user can be used on this endpoint.
$app->get('/user', $checkToken(), function () use ($server, $app) {

	$user_model = new UserModel();

	// Check the access token's owner is a user
	if ($server->getOwnerType() === 'user')
	{
		// Get the access token owner's ID
		$userId = $server->getOwnerId();

		$user = $user_model->getUser($userId);

		// If the user can't be found return 404
		if ( ! $user)
		{
			$res = $app->response();
			$res->status(404);
			$res['Content-Type'] = 'application/json';
			$res->body(json_encode(array(
				'error' => 'Resource owner not found'
			)));
		}

		// A user has been found
		else
		{
			// Basic response
			$response = array(
				'error' => null,
				'result'	=>	array(
					'user_id'	=>	$user['id'],
					'firstname'	=>	$user['firstname'],
					'lastname'	=>	$user['lastname']
				)
			);

			// If the acess token has the "user.contact" access token include
			//  an email address and phone numner
			if ($server->hasScope('user.contact'))
			{
				$response['result']['email'] = $user['email'];
				$response['result']['phone'] = $user['phone'];
			}

			// Respond
			$res = $app->response();
			$res['Content-Type'] = 'application/json';

			$res->body(json_encode($response));
		}
	}

	// The access token isn't owned by a user
	else
	{
		$res = $app->response();
		$res->status(403);
		$res['Content-Type'] = 'application/json';
		$res->body(json_encode(array(
			'error' => 'Only access tokens representing users can use this endpoint'
		)));
	}

});


// An endpoint that will respond to any valid access token
$app->get('/user/:id', $checkToken(), function ($id) use ($server, $app) {

	$user_model = new UserModel();

	$user = $user_model->getUser($id);

	if ( ! $user)
	{
		$res = $app->response();
		$res->status(404);
		$res['Content-Type'] = 'application/json';
		$res->body(json_encode(array(
			'error' => 'User not found'
		)));
	}

	else
	{
		// Basic response
		$response = array(
			'error' => null,
			'result'	=>	array(
				'user_id'	=>	$user['id'],
				'firstname'	=>	$user['firstname'],
				'lastname'	=>	$user['lastname']
			)
		);

		// If the acess token has the "user.contact" access token include
		//  an email address and phone numner
		if ($server->hasScope('user.contact'))
		{
			$response['result']['email'] = $user['email'];
			$response['result']['phone'] = $user['phone'];
		}

		// Respond
		$res = $app->response();
		$res['Content-Type'] = 'application/json';

		$res->body(json_encode($response));
	}

});

// Only clients (i.e. applications) can hit up this endpoint
$app->get('/users', $checkToken(), function () use ($server, $app) {

	$user_model = new UserModel();

	$users = $user_model->getUsers();

	// Check the access token owner is a client
	if ($server->getOwnerType() === 'client' && $server->hasScope('users.list'))
	{
		$response = array(
			'error' => null,
			'results'	=>	array()
		);

		$i = 0;
		foreach ($users as $k => $v)
		{
			// Basic details
			$response['results'][$i]['user_id'] = $v['id'];
			$response['results'][$i]['firstname'] = $v['firstname'];
			$response['results'][$i]['lastname'] = $v['lastname'];

			// Include additional details with the right scope
			if ($server->hasScope('user.contact'))
			{
				$response['results'][$i]['email'] = $v['email'];
				$response['results'][$i]['phone'] = $v['phone'];
			}

			$i++;
		}

		$res = $app->response();
		$res['Content-Type'] = 'application/json';

		$res->body(json_encode($response));
	}

	// Access token owner isn't a client or doesn't have the correct scope
	else
	{
		$res = $app->response();
		$res->status(403);
		$res['Content-Type'] = 'application/json';
		$res->body(json_encode(array(
			'error' => 'Only access tokens representing clients can use this endpoint'
		)));
	}

});


// Run the app
$app->run();