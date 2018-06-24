<?php
class Dbconnection
{
	private $host = 'localhost';
	private $user = 'root';
	private $password = '';
	private $dbname = 'core_php_restapi';
	private $conn;

	public function connect(){
		$this->conn = NULL;
		
		try {
			$this->conn = new PDO('mysql:host='.$this->host.';dbname='.$this->dbname,$this->user,$this->password);	
			// ativating error mode
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
		} catch (PDOException $e) {
			echo "Connection error: ".$e->getMessage();
		}
		return $this->conn;
	}
}