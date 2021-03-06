<?php
include_once '/home/andrewed/public_html/prediction/session.php';
include_once '/home/andrewed/public_html/prediction/src/dbleague.class.php';
include_once '/home/andrewed/public_html/prediction/src/dbgame.class.php';
include_once '/home/andrewed/public_html/prediction/src/emailsender.class.php';


$dbgame = new dbgame();
$dbleague = new dbleague();
$emailSender = new emailsender();
$competition = $dbgame->loadActiveCompetition();

if(isset($_POST['save']) && $_POST['save'] != "" ){

	$action      = $_POST['action'];
	$saveSuccess = "";
	
	if($action == "add" || $action == "edit"){
	
		$name		 = $_POST['name'];
		$description = $_POST['description'];
		$invites 	 = $_POST['invites'];
		$leagueid    = $_POST['leagueid'];
		$saveError   = "";
		
		if($name == "" || $name == null) {
			$saveError = "League name cannot be blank";
		}
		
		if($saveError == "") {
			if($action == "add") {
				$leagueid = $dbleague->createLeague($user->userid, $name, $description);
				if($leagueid == 0){
					$saveError = "Error creating league, data could not be saved";
				} else {
					$dbleague->createUserleague($user->userid, $leagueid);
					$action = "edit";
					if($invites != ""){
						$dbleague->createInvites($leagueid, $invites);
					}
					$saveSuccess = "League has been created";
				}			
			} else {
				$dbleague->updateLeague($leagueid, $name, $description);
				if($invites != ""){
						$dbleague->createInvites($leagueid, $invites);
				}
			}		
		}
	}else if($action == "join") {
			
		$code        = $_POST['code'];
		$saveError   = "";
		
		if($code == "" || $code == null) {
			$saveError = "Please enter league code";
		}
				
		if($saveError == "") {
			$saveError = $dbleague->joinLeague($user->userid, $code);
			
			if($saveError == "") {
				$saveSuccess = "Successfully added to league";				
			}			
			$leagueid = 0;
		}		
	}	
} else if(isset($_POST['sendinvites']) && $_POST['sendinvites'] != "") {

	$emailSender->sendLeagueInvites($leagueid);
	
} else {
	if(isset($_GET['action'])){
		$action = $_GET['action'];
	} else {
		$action = "edit";
	}
	if(isset($_GET['id'])){
		$leagueid = $_GET['id'];
	} else {
		$leagueid = 0;
	}
}

$pagetitle = "";
if($action == "add"){
	$pagetitle = "Create League";
} else if($action == "join") {
	$pagetitle = "Join League";
} else {
	$pagetitle = "Edit League";	
}

if($leagueid != 0){
	$league = $dbleague->loadLeague($leagueid);		
	if($user->userid != $league->creatorid){
		echo '<p class="bg-danger"> You do not have access to this record </p>';
		exit;
	} else {
		$invites = $dbleague->loadInvites($leagueid);
		$inviteList = "";
		$acceptedList = "";
		if(isset($invites)){
			foreach($invites as $invite){
				$inviteList = $inviteList . "," . $invite->email;
				if($invite->accepted){
					$acceptedList = $acceptedList . "," . $invite->email;
				}
			}
		}
	}
}

?>
<!DOCTYPE html> 
<html> 
<head> 
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Andrew Edge">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title>Prediction Factory | Leagues </title>

    <!-- Bootstrap core CSS -->
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Jumbotron Narrow theme -->
    <link href="/bootstrap/css/jumbotron-narrow.css" rel="stylesheet">
	
	<link href="/prediction/css/prediction.css" rel="stylesheet" >
	
	<!-- Bootstrap Javascript -->
	<script src="/js/jquery.min.js" type="text/javascript" ></script>
    <script src="/bootstrap/js/bootstrap.min.js" type="text/javascript" ></script>
	
	<!-- Bootstrap Input tags -->
	<link href="/bootstrap/css/jquery.tagsinput.css" rel="stylesheet">
	<script src="/bootstrap/js/jquery.tagsinput.min.js" type="text/javascript" ></script>

	<script type="text/javascript">
		var inviteList = "<?php if(isset($inviteList)) { echo $inviteList; } ?>";
		var acceptedList = "<?php if(isset($acceptedList)) { echo $acceptedList; } ?>";
	
		$(function() {
			$('#inLeagueInvites').tagsInput({
				width: 'auto'
				/**
				onChange: function(elem, elem_tags)
				{
					var languages = ['php','ruby','javascript'];
					$('.tag', elem_tags).each(function()
					{
						if($(this).text().search(new RegExp('\\b(' + languages.join('|') + ')\\b')) >= 0)
							$(this).css('background-color', 'yellow');
					});
				}
				**/
			});
		});
	</script>

</head>
<body>
	<div class="container">
	<?php $currenttab = 'league'; include '/home/andrewed/public_html/prediction/header.php'; ?>
	  <div class="row">
		<div class="col-md-12">	
			<form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo $pagetitle; ?></h3>
				</div>
				<div class="panel-body">					
					<?php
					if(isset($saveError) && $saveError != ""){
						echo '<p class="bg-danger">'. $saveError .'</p>';
					}
					
					if(isset($saveSuccess) && $saveSuccess != ""){
						echo '<p class="bg-success">'. $saveSuccess .'</p>';
					}
					
					if($action == "add"){
					?>				
				
						<div class="form-group">
							<label for="inLeagueName">Name</label>
							<input type="text" id="inLeagueName" name="name" class="form-control" placeholder="Name" value="<?php if(isset($league->name)){ echo $league->name; } ?>" required autofocus>
						</div>
						
						<?php if(isset($league->password)){ ?>
						<div class="form-group">
							<label for="inLeagueDesc">Code</label>
							<p class="form-control-static"><?php echo $league->password; ?></p>
						</div>	
						<?php } ?>
						
						<div class="form-group">
							<label for="inLeagueDesc">Description</label>
							<input type="text" id="inLeagueDesc" name="description" class="form-control" placeholder="Description" value="<?php if(isset($league->description)){ echo $league->description; } ?>"  >
						</div>
						
						<div class="form-group">
							<label for="inLeagueInvites">Enter emails to invite people to join the league </label>
							<input type="text" id="inLeagueInvites" class="tags form-control" name="invites" placeholder="Enter email addresses" value="<?php if(isset($inviteList)){ echo $inviteList; } ?>" >
						</div>
						
						<div class="center-block">
							<button type="submit" class="btn btn-primary" name="save" value="save" > <?php if($action == "add") { echo "Create"; } else { echo "Save"; } ?></button>
							<button type="submit" class="btn btn-default" name="sendinvites" value="sendinvites" > Send Invites </button>
							<button type="reset" class="btn btn-default" > Cancel </button>
							<input type="hidden" name="action" id="hdnAction" value="<?php echo $action; ?>" />
							<input type="hidden" name="leagueid" id="hdnLeagueId" value="<?php echo $leagueid; ?>" />
						</div>
					<?php 
					} else if ($action == "join") {
					?>
					
						<div class="form-group">
							<label for="inLeagueName">Code</label>
							<input type="text" id="inLeagueCode" name="code" class="form-control" placeholder="League Code" value="" required autofocus>
						</div>
						
						<div class="text-center col-md-12">
							<button type="submit" class="btn btn-primary" name="save" value="save" > Join </button>
							<button type="reset" class="btn btn-default" > Cancel </button>
							<input type="hidden" name="action" id="hdnAction" value="<?php echo $action; ?>" />
						</div>
					
					<?php
					}
					?>
				</div>
			</div>
			</form>
	    </div>
	  </div>
	  <div class="footer">
		<p>&copy; Andrew Edge 2014</p>
	  </div>	
  </div>
</body>
</html>
