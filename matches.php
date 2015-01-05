<?php
include_once '/home/andrewed/public_html/prediction/session.php';
include_once '/home/andrewed/public_html/prediction/src/dbgame.class.php';
include_once '/home/andrewed/public_html/prediction/src/dbteam.class.php';
include_once '/home/andrewed/public_html/prediction/src/dbleague.class.php';
include_once '/home/andrewed/public_html/prediction/src/dbuser.class.php';

$todaysDate  = date("Y-m-d");
$todaysDateTime = strtotime($todaysDate);

$dbgame = new dbgame();
$dbteam = new dbteam();
$dbleague = new dbleague();
$dbuser = new dbuser();

$competition = $dbgame->loadActiveCompetition();
$games = null;
if(isset($_POST['action'])){
	$action = $_POST['action'];
} else {
	$action = $_GET['action'];
}

$leagueid = 0;
$viewUserId = 0;

if(isset($_GET['leagueid'])){
	$leagueid = $_GET['leagueid'];
}
if(isset($_GET['userid'])){
	$viewUserId = $_GET['userid'];
} 


/** Page security logic **/
if($action == "results" && $user->accesslevel < 50) {
	header("location:home.php");
}

if($action == "predictions" && $leagueid != 0){
	if($dbleague->checkUserLeague($user->userid, $leagueid) == false) {
		header("location:home.php");
	}
}

if($action == "results" || $action == "predictions"){
	if(isset($_POST['gameIdList'])){
		$gameIdList  = $_POST['gameIdList'];
		$team1Scores = $_POST['team1ScoreList'];
		$team2Scores = $_POST['team2ScoreList'];
		$starGames   = $_POST['starList'];
		$heartGames  = $_POST['heartList'];
		$viewUserId  = $_POST['viewUserId'];
		
		if($viewUserId != 0){
			$saveUserId = $viewUserId;
		} else {
			$saveUserId = $user->userid;
		}
			
		if(($action == "predictions") && ($gameIdList != null)){
			$dbgame->createPredictions($saveUserId,$gameIdList,$team1Scores,$team2Scores,$starGames,$heartGames);
		}
		
		if(($action == "results") && ($gameIdList != null)){
			$dbgame->enterResults($gameIdList,$team1Scores,$team2Scores);
		}
	}
}

if ($action == "predictions") {
	if($viewUserId != 0) {
		$games = $dbgame->loadPredsByCompetition($competition->competitionid, $viewUserId);
	} else {
	$games = $dbgame->loadPredsByCompetition($competition->competitionid, $user->userid);
}
}

if($games == null){
	$games = $dbgame->loadGamesByCompetition($competition->competitionid, $competition->datasplit);
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

    <title>Prediction Factory | Predictions </title>

    <!-- Bootstrap core CSS -->
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Jumbotron Narrow theme -->
    <link href="/bootstrap/css/jumbotron-narrow.css" rel="stylesheet">
	
	<link href="/prediction/css/prediction.css" rel="stylesheet" >
	
	<!-- Bootstrap Javascript -->
	<script src="/js/jquery.min.js" type="text/javascript" ></script>
    <script src="/bootstrap/js/bootstrap.min.js" type="text/javascript" ></script>

<script src="/js/prediction.js"></script>

</head>
<body>
	<!-- Header Bar -->
	<div class="container">
	  <?php if($action == 'predictions') {$currenttab = 'predictions';} else {$currenttab = 'setup';} include '/home/andrewed/public_html/prediction/header.php'; ?>
	  
	  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-horizontal" >
		<div class="text-center bg-danger" id="divSaveError">
		</div>
		<?php
		if($viewUserId != 0) {
			$viewUser = $dbuser->loadUserId($viewUserId);
			echo "<h5 class='text-center'> Predictions made by <strong>". $viewUser->name ."</strong></h5>";
		}					
		?>
		<div class="panel-group" id="accordion">
		  <?php
		  
		  $currentgroup = "";
		  $todaysDate   = date("Y-m-d");
		  
		  echo '<div>';
		  echo '<div>';
		  
		  if(isset($games)){
			$firstGroup = true;
			$gameIdList = "";
			
			foreach($games as $game) {
				
				$canEditGame = false;					
			
				if($action == 'predictions'){
					if($user->accesslevel > '90'){
						$canEditGame = true;
						$gameIdList = $gameIdList . $game->gameid . ",";
					}
					else if(($game->date > $todaysDate) && (($game->userid == $user->userid) || (!isset($game->userid)))) {
						$canEditGame = true;
						$gameIdList = $gameIdList . $game->gameid . ",";
					}
				}
				else if($action == 'results') {
					$canEditGame = true;
					$gameIdList = $gameIdList . $game->gameid . ",";
				}
					
				if($currentgroup != $game->group){
					
					$currentgroup = $game->group;
					echo '</div>';
					echo '</div>';
					echo '<div class="panel panel-default">';
					echo '<div class="panel-heading"><h3 class="panel-title">';
					echo ' <a data-toggle="collapse" data-parent="#accordion" href="#collapse'. $game->gameid .'"> Group '. $game->group .' </a>';
					echo '</h3></div>';
					if($firstGroup){
						echo '<div id="collapse'. $game->gameid .'" class="panel-collapse collapse in">';
						$firstGroup = false;
					} else {
						echo '<div id="collapse'. $game->gameid .'" class="panel-collapse collapse">';
					}
					
				}
				echo '<div class="form-group">';
				echo '<div class="col-sm-2 text-center">';
				echo '<p>'. $game->date .'</p>';
				echo '</div>';
				echo '<input type="hidden" id="game'. $game->gameid .'" value="'. $game->gameid .'" />';
				echo '<div class="col-sm-8">';
				echo '<div class="col-xs-3 text-center">';
				echo '<label for="t1score'. $game->gameid .'">' . $game->t1name . '</label>';
				echo '</div>';
				echo '<div class="col-xs-6 text-center">';
				
				if($canEditGame){							
					echo '<input type="number" style="width:50px;display:inline;" id="t1score'. $game->gameid .'" placeholder="0" class="form-control" maxlength="2" value="' . $game->team1score .'"/>';
					echo '<span class="versusspan" style="text-align:center;">&nbsp; - &nbsp; </span>';
					echo '<input type="number" style="width:50px;display:inline;" id="t2score'. $game->gameid .'" placeholder="0" class="form-control" maxlength="2" value="'. $game->team2score .'"/>';
				} else {
					echo '<span style="text-align:center;">'. $game->team1score .'&nbsp; - &nbsp;'. $game->team2score .'</span>';
				}				
				echo '</div>';
				echo '<div class="col-xs-3 text-center" >';
				echo '<label for="t2score'. $game->gameid .'">' . $game->t2name . '</label>';
				echo '</div>';
				echo '</div>';
				if(($action != "results") && ($game->date <= $todaysDate)){
					echo '<div class="col-sm-2 text-center" >';
					echo '<span>'. $game->points .' pts</span>';
					echo '</div>';
				}					
				echo '</div>';
			}			
		  }
		  echo '</div>';
		  echo '</div>';
		  ?>	
		   		
		  <div class="panel panel-default">
		  <div class="text-center col-md-12 panel-body">
			<input type="hidden" id="hdnAction" name="action" value="<?php echo $action; ?>" />
			<input type="hidden" id="hdnGameIdList" name="gameIdList" value="<?php echo trim($gameIdList, ","); ?>" />
			<input type="hidden" id="hdnTeam1ScoreList" name="team1ScoreList" value="" />
			<input type="hidden" id="hdnTeam2ScoreList" name="team2ScoreList" value="" />
			<input type="hidden" id="hdnHeartList" name="heartList" value="" />
			<input type="hidden" id="hdnStarList" name="starList" value="" />
			<input type="hidden" id="hdnviewUserId" name="viewUserId" value="<?php if(isset($viewUserId)) { echo $viewUserId; } ?>" />
			
			<button type="submit" class="btn btn-primary" onClick="return saveValues();"> Save </button>
			<button type="reset"  class="btn btn-default"> Cancel </button>
			<?php if($action == 'results'){ ?>
				<button type="button" class="btn btn-default" onClick="processResults();"> Process Results </button>
			<?php } ?>
		  </div>
		  </div>
		  </form>
		</div>
	   
		  <div class="footer">
			<p>&copy; Andrew Edge 2014</p>
	      </div>	
	</div>
</body>
</html>
