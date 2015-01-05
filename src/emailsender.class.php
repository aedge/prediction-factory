<?php

include_once 'dbleague.class.php';

class emailSender {

	public function __construct(){
	}
	
	public function sendLeagueInvites($leagueid){
	
		$dbleague = new dbleague();
		$invites  = $dbleague->loadInvites($leagueid);
		
		if(isset($invites)){
		
			foreach($invites as $invite) {
				
				if($invite->email != "" && $invite->email != null){
					mail($invite->email, "Prediction Factory Invite", "", "From:info@thePredictionFactory.com"); 
				}
			}
		
		}
	
	}

}

?>