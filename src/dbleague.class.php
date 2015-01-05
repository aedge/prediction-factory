<?php 
include_once '/home/andrewed/public_html/prediction/src/db.class.php';

class dbleague
{

	protected $db;
	
	public function __construct(){
		$this->db = database::getInstance()->dbc;
	}	
	
	public function loadLeague($leagueid) {
		
		$query = $this->db->prepare("select * from league where leagueid = :leagueid ");
		$query->execute(array('leagueid' => $leagueid));
		$league = $query->fetch(PDO::FETCH_OBJ);				
		return $league;	
	}
	
	public function loadUsersleagues($userid) {
		
		$query = $this->db->prepare("select * from userleague,league where userleague.userid = :userid and league.leagueid = userleague.leagueid order by league.name");
		$query->execute(array('userid' => $userid));
		$results = $query->fetchAll(PDO::FETCH_OBJ);	
		
		return $results;
	}
	
	public function loadUserLeague($userid, $leagueid) {
		
		$query = $this->db->prepare("select league.name from userleague, league where userleague.userid = :userid and userleague.leagueid = :leagueid and league.leagueid = userleague.leagueid");
		$query->execute(array('userid' => $userid, 'leagueid' => $leagueid));
		$result = $query->fetch(PDO::FETCH_OBJ);	
		
		return $result;
	}
	
	public function loadLeagueStandings($leagueid) {
		
		$query = $this->db->prepare("select user.name, user.totalpoints, user.accuracy, user.userid from user, userleague where userleague.leagueid = :leagueid and user.userid = userleague.userid order by user.totalpoints desc");
		$query->execute(array('leagueid' => $leagueid));
		$results = $query->fetchAll(PDO::FETCH_OBJ);	
		
		return $results;
	}
			
	public function createleague($userid, $name, $description) {
		
		$sth = $this->db->prepare("insert into league (name, description, creatorid) values (:name, :description, :creatorid)");
		$data = array("name" => $name, "description" => $description, "creatorid" => $userid);
		$sth->execute($data);
		$leagueid = $this->db->lastInsertId();
		
		$code = md5($name . $leagueid . date("dmYhis"));
		$sth = $this->db->prepare("update league set password=:code where leagueid = :leagueid ");
		$sth->execute(array("code" => $code, "leagueid" => $leagueid));
		
		return $leagueid;		
	}
	
	public function joinLeague($userid,$code) {
		
		$query = $this->db->prepare("select leagueid from league where password=:code");
		$leagueid = 0;		
		$error = "";
		$query->execute(array("code" => $code));
		$result = $query->fetch(PDO::FETCH_OBJ);		
		$leagueid = $result->leagueid;
		if($leagueid == 0) {
			$error = "Could not find league with that code";
		}		
		else {
			$this->createUserleague($userid,$leagueid);
		}		
		return $error;		
	}	
	
	public function updateleague($leagueid,$name,$description) {
		
		$query = $this->db->prepare("update league set name=:name, password=:password where leagueid=:leagueid");
		$query->execute(array("name" => $name, "password" => $password, "leagueid" => $leagueid));					
	}	
		
	public function createUserleague($userid, $leagueid) {
		
		$query = $this->db->prepare("insert into userleague (userid,leagueid) values (:userid,:leagueid)");
		$query->execute(array("userid" => $userid, "leagueid" => $leagueid));
		
	}
	
	public function checkUserLeague($userid, $leagueid) {
		$query = $this->db->prepare("select * from userleague where userid = :userid and leagueid = :leagueid");
		$result = $query->execute(array("userid" => $userid, "leagueid" => $leagueid));
		
		if($result == null){
			return false;
		} else {
			return true;
		}		
	}
	
	public function deleteleague($leagueid) {
		
		$query = $this->db->prepare("delete from league where leagueid = :leagueid");
		$query->execute(array("leagueid" => $leagueid));
	}
	
	public function deleteUserleague($userid, $leagueid) {
		
		$query = $this->db->prepare("delete from userleague where userid = :userid and leagueid = :leagueid");
		$query->execute(array("leagueid" => $leagueid, "userid" => $userid));
		$query = $this->db->prepare("select count(userid) from userleague where leagueid = :leagueid");
		$result = $query->execute(array("leagueid" => $leagueid));
		$numUsers = $result[0];
		if($numUsers == 0){
			league::deleteleague($leagueid);
		}
	}
	
	public function createInvites($leagueid, $emailList){
		
		$emailArray     = explode(',', $emailList);

		$query = $this->db->prepare("insert ignore into leagueinvite (leagueid, email) values (:leagueid,:email)");
		
		for($i=0, $size=count($emailArray); $i<$size; $i++){
			$query->execute(array("leagueid" => $leagueid, "email" => $emailArray[$i]));			
		}	
	}
	
	public function loadInvites($leagueid) {
		$query = $this->db->prepare("select email, accepted from leagueinvite where leagueid = :leagueid");
		$query->execute(array('leagueid' => $leagueid));
		$results = $query->fetchAll(PDO::FETCH_OBJ);	
		
		return $results;
	}
	
	public function acceptInvite($leagueid, $email) {
		$query = $this->db->prepare("update leagueinvite set accepted=1 where leagueid=:leagueid and email=:email");
		$query->execute(array("leagueid" => $leagueid, "email" => $email));	
	}
	
	/** select statement to load all the users for all the leagues that you are associated with - might be useful later! 
	select leaguelist.name as leaguename, user.name as username, user.totalpoints from (select league.name, league.leagueid from league,userleague where league.leagueid = userleague.leagueid and userleague.userid = 11) as leaguelist inner join userleague on leaguelist.leagueid = userleague.leagueid  inner join user on user.userid = userleague.userid order by leaguelist.name, user.totalpoints desc **/
	
}

