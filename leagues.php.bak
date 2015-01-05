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

<?php
include_once '/home/andrewed/public_html/prediction/session.php';
include_once '/home/andrewed/public_html/prediction/src/dbleague.class.php';
include_once '/home/andrewed/public_html/prediction/src/dbgame.class.php';

if(isset($_GET['id'])){
	$leagueid = $_GET['id'];
} else {
	$leagueid = 0;
}

$dbleague = new dbleague();
$dbgame   = new dbgame();

$leagues = $dbleague->loadUsersLeagues($user->userid);
$competition = $dbgame->loadActiveCompetition();
?>
</head>
<body>

	<div class="container">
	  <?php $currenttab = 'league'; include '/home/andrewed/public_html/prediction/header.php'; ?>
	  <div class="row">
		<div class="col-md-12">		  
		  <div class="panel-group" id="accordion">
		  <?php 
			if(isset($leagues)){ 
			
				foreach($leagues as $league){
				  $users = $dbleague->loadLeagueStandings($league->leagueid);
				  if($leagueid == 0){
					$leagueid = $league->leagueid;
				  }
		  ?>
				  <div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $league->leagueid; ?>">
								<?php echo $league->name; ?>
							</a>
						</h3>
					</div>
					<div id="collapse<?php echo $league->leagueid; ?>" class="panel-collapse <?php if($league->leagueid == $leagueid){ echo 'collapse in'; } else { echo 'collapse'; } ?>">
						<div class="panel-body" >
							<p><strong>Code: </strong> <?php echo $league->password; ?></p>
							<p><?php echo $league->description; ?></p>
						</div>
						<table class="table table-striped" >
							<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Total Points</th>
								<th>Accuracy (%)</th>
							</tr>
							</thead>
							</tbody>
							<?php
							if(isset($users)){
								$count = 1;		
								foreach($users as $user){
									echo '<tr>';
									echo '<td>'. $count .'</td>';
									echo '<td><a href="/prediction/matches.php?action=predictions&leagueid='. $league->leagueid .'&userid='. $user->userid .'">'. $user->name .'</a></td>';
									echo '<td>'. $user->totalpoints .'</td>';
									echo '<td>'. $user->accuracy .'</td>';
									echo '</tr>';
									$count++;
								}
								
							}
							?>
							</tbody>
						</table>
					</div>
				  </div>
			  
		  <?php 
			}
		  }
		  ?>
		  </div>
	    </div>
	  </div>
	  <div class="footer">
		<p>&copy; Andrew Edge 2014</p>
	  </div>
		  
		  
		
	</div>
</body>
</html>
