<?php
/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: amitj $:  Author of last commit
$Date: 2008-03-14 08:00:11 $:  Date of last commit

DbLib to proivide library method to the clients

$Id: DbLib.php,v 1.2 2008-03-14 08:00:11 amitj Exp $: 

*/

class DbLib{

	private $userName = 'shiksha';
	private $password = 'shiKm7Iv80l';
	private $serverIP = 'localhost';
	private $dbName = 'shiksha';
	private $hostname= 'localhost';


	public function getUserName($appID){
		return $this->userName;
	}
	
	public function getUserPassword($appID){
		return $this->password;
	}
	
	public function getServerIP($appID){
		return $this->serverIP;
	}

	public function getDbName($appID){
		return $this->dbName;
	}


}
?>
