<?php 
include_once '/home/andrewed/public_html/prediction/src/db.class.php';

class dbuser
{

	protected $db;
	
	public function __construct(){
		$this->db = database::getInstance()->dbc;
	}

	public function loadUser($email, $password){
		$query = $this->db->prepare("select * from user where email = :email");
		$query->execute(array('email' => $email));
		$user  = $query->fetch(PDO::FETCH_OBJ);
		
		if($user != null){
			$hashedPassword = hash("sha256", $password . $user->pwordsalt);
			if($user->password == $hashedPassword){
				return $user;		
			}
		}		
		return null;
	}
	
	public function checkUser($email){
		$userid = 0;
		$query = $this->db->prepare("select userid from user where email = :email");
		$query->execute(array('email' => $email));
		$user = $query->fetch(PDO::FETCH_OBJ);	
		
		if($user != null){
			return $user->userid;		
		} else {
			return null;
		}
	}
	
	public function loadUserList() {
		$query = $this->db->prepare("select userid, name from user;");
		$query->execute();
		$results  = $query->fetchAll(PDO::FETCH_OBJ);
		
		return $results;
	}
	
	public function updatePointsTotals() {
		$query = $this->db->prepare("update user set totalpoints = (select sum(points) from prediction where prediction.userid = user.userid), accuracy = (select avg(accuracy * 100) from prediction, game where prediction.userid = user.userid and prediction.gameid = game.gameid and game.result != '') ");
		
		$query->execute();
	}
	
	public function updateUserPassword($userid, $password, $salt){
		$query = $this->db->prepare("update user set password = :password, pwordsalt = :pwordsalt where userid = :userid");
		$data = array('userid' => $userid, 'password' => $password, 'pwordsalt' => $salt);
		$query->execute($data);
	}
	
	public function loadUserID($userid){
		
		$query = $this->db->prepare("select * from user where userid = :userid");
		$query->execute(array('userid' => $userid));
		$user  = $query->fetch(PDO::FETCH_OBJ);	
		return $user;				
	}

	public function createUser($email,$password,$name,$salt){
		
		$sth = $this->db->prepare("insert into user (`password`,`email`,`name`,`pwordsalt`) values (:password,:email,:name,:pwordsalt)");
		$data = array('email' => $email, 'password' => $password, 'name' => $name, 'pwordsalt' => $salt);
		$sth->execute($data);
		$userid = $this->db->lastInsertId();
		return $userid;
		
	}
	
	public function generateSalt() {
		$saltLength = 6;
		$intermediateSalt = md5(uniqid(rand(), true));
		$salt = substr($intermediateSalt, 0, $saltLength);
		
		return $salt;
	}
	
	private function str_replace_once($str_pattern, $str_replacement, $string){	
		if (strpos($string, $str_pattern) !== false){
			$occurrence = strpos($string, $str_pattern);
			return substr_replace($string, $str_replacement, strpos($string, $str_pattern), strlen($str_pattern));
		}
	   
		return $string;
    } 
}
?>