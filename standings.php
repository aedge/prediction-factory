<!DOCTYPE html> 
<html> 
<head> 
	<title>Prediction Factory</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.0-beta.1/jquery.mobile-1.3.0-beta.1.min.css" />
	<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.3.0-beta.1/jquery.mobile-1.3.0-beta.1.min.js"></script>
	<script src="/js/prediction.js"></script>
	<link rel="stylesheet" href="/css/prediction.css" />
<?php
include_once 'session.php';
include_once 'dbleague.class.php';

$leagueid = $_GET['lid'];
$league = dbleague::loadUserLeague($user->userid, $leagueid);

if(!isset($league) || empty($league)){
	header('location:login.php');
}

$standings = dbleague::loadLeagueStandings($leagueid);
$counter = 1;
?>
</head>
<body>
<div data-role="page">

	<div data-role="header">
		<h1>Prediction Factory</h1>
	</div><!-- /header -->

	<div data-role="content">
		<div class="content-primary">
		<?php if(isset($standings)){ ?>
			<h3><?php echo $league->name ?></h3>
			<table data-role="table" id="tblStandings" data-mode="reflow" class="ui-responsive table-stroke" style="display:table;">
				<thead>
					<tr>
						<th data-priority="1" style="width:15%">Rank</th>
						<th data-priority="persist" style="width:55%"> Name </th>
						<th data-priority="2" style="width:15%"> Points </th>
						<th data-priority="3" style="width:15%"> Accuracy </th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($standings as $standing){ 
					echo '<tr><td>'. $counter .'</td><td><a href="predictions.php?uid='. $user->userid .'" >'. $standing->name .'</a></td><td>'. $standing->totalpoints .'</td><td></td></tr>' .  PHP_EOL;	
					$counter++;
				} 
				?>
				</tbody>
			</table>
		<?php }	?>		
		</div>
	</div>
</div>
</body>
</html>
