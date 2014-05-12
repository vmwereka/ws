# OAuth 2.0 Example Resource Server

## Setup

1) Setup a MySQL database and edit db.php with the connection settings

2) Install [Composer](http://getcomposer.org/) and run `composer install`

3) Run the mysql.sql file in vendor/lncd/OAuth2/sql/mysql.sql

4) Run the following commands on the database:

	INSERT INTO `oauth_clients` (`id`, `secret`, `name`, `auto_approve`) VALUES	('I6Lh72kTItE6y29Ig607N74M7i21oyTo','dswREHV2YJjF7iL5Zr5ETEFBwGwDQYjQ','Hello World App',0);

	INSERT INTO `oauth_client_endpoints` (`id`, `client_id`, `redirect_uri`) VALUES (1,'I6Lh72kTItE6y29Ig607N74M7i21oyTo','http://client.dev/signin/redirect');

	INSERT INTO `oauth_scopes` (`id`, `scope`, `name`, `description`) VALUES (1,'user.basic','Basic user details','Returns basic user details (user ID and name)'), (2,'user.contact','User\'s contact details','Returns a user\'s contact details'), (3,'users.list','List users','Returns a list of users');

	INSERT INTO `oauth_sessions` (`id`, `client_id`, `redirect_uri`, `owner_type`, `owner_id`, `auth_code`, `access_token`, `refresh_token`, `access_token_expires`, `stage`, `first_requested`, `last_updated`) VALUES	(1,'I6Lh72kTItE6y29Ig607N74M7i21oyTo','http://client.dev/signin/redirect','client','1',NULL,'cheRL8oMIBQ6sYgnrv8L6M8vxDEEDMAcmdoUhpol',NULL,1461270703,'granted',1361265844,1361267103);

	INSERT INTO `oauth_session_scopes` (`id`, `session_id`, `scope_id`) VALUES (1,1,1),	(2,1,2);
	
	CREATE TABLE `users` (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `firstname` varchar(255) NOT NULL DEFAULT '',
	  `lastname` varchar(255) NOT NULL DEFAULT '',
	  `email` varchar(255) NOT NULL DEFAULT '',
	  `phone` varchar(255) NOT NULL DEFAULT '',
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `phone`) VALUES (1,'Alex','Bilbie','hello@example.com','01234567910'),	(2,'Jane','Doe','jdoe@example.com','01987654321');

The source code is fully documented and should be a good starting base for your own OAuth auth server.
