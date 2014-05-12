<?php

class DB {

	private $conn;
	private $statement;

	function __construct()
	{
		$this->conn = new PDO('mysql:host=localhost;dbname=oauth-test', 'root', '');
	}

	function query($sql = '', $params = array())
	{
		$statement = $this->conn->prepare($sql);
		$statement->setFetchMode(PDO::FETCH_OBJ);
		$statement->execute($params);
		return $statement;
	}

	public function getInsertId()
	{
		return (int) $this->conn->lastInsertId();
	}

}