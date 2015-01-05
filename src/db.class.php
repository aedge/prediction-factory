<?php

	define("DB_USER", "andrewed_pre");		 // <-- mysql db user
	define("DB_PASSWORD", "jam00pred");		     //	 <-- mysql db password
	define("DB_NAME", "andrewed_prediction");	     // <-- mysql db pname
	define("DB_HOST", "localhost");			 // <-- mysql server host
	
	class database
	{
		public $dbc;
		private static $instance;
		
			 
		private function __construct()
		{
		
			$host = DB_HOST;
			$dbname = DB_NAME;
			$user = DB_USER;
			$pass = DB_PASSWORD;
			
			try{
				$this->dbc = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);  
			} 
			catch(PDOException $e){
				echo $e->getMessage();
			}	
		}
	 
		//singleton pattern
		public static function getInstance()
		{
			if (!isset(self::$instance))
			{
				$object = __CLASS__;
				self::$instance = new $object;
			}
			return self::$instance;
		}
	}	
?>