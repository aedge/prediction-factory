<?php
include_once '/home/andrewed/public_html/prediction/session.php';
include_once '/home/andrewed/public_html/prediction/src/dbleague.class.php';
include_once '/home/andrewed/public_html/prediction/src/dbgame.class.php';

$dbleague = new dbleague();
$dbgame   = new dbgame();

$leagues = $dbleague->loadUsersLeagues($user->userid);
$competition = $dbgame->loadActiveCompetition();
$predictions = $dbgame->loadUpcomingPredsByCompetition($competition->competitionid, $user->userid);

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

    <title>Prediction Factory | Home </title>

    <!-- Bootstrap core CSS -->
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Jumbotron Narrow theme -->
    <link href="/bootstrap/css/jumbotron-narrow.css" rel="stylesheet">
	
	<link rel="stylesheet" href="/owl-carousel/owl.carousel.css">
	<link rel="stylesheet" href="/owl-carousel/owl.theme.css">
	
	<link rel="stylesheet" href="/prediction/css/prediction.css">

</head>
<body>
	<!-- Header Bar -->
	<div class="container">
	  <?php $currenttab = 'home'; include '/home/andrewed/public_html/prediction/header.php'; ?>
	  
	  <div class="jumbotron">
		<div id="owl-example" class="owl-carousel">
		  <div>
		<h2> <?php echo $competition->name; ?> Predictions </h2>
        <p class="lead">
			<?php echo "Hi " . $user->name . ", You have " . $user->totalpoints . " points and your predictions are " . $user->accuracy . "% accurate"; ?> 
		</p>
      </div>
		  <div>
			 <h2> A New Challenger! </h2>
			 <p> BBC Pundit and housewives favourite <strong> Mark Lawrenson</strong> has joined the prediction factory! View the league standings to see how you measure up to the ex-Liverpool defender </p>
		  </div>
		</div> 
	  </div>
	  
	  <div class="row marketing">
		<div class="col-lg-6">
          <div class="panel panel-default">
			<div class="panel-heading"><h3 class="panel-title">Your Leagues</h3></div>
			<div class="panel-body">
			  <?php if(isset($leagues)){ ?>
				<div class="list-group">
					<?php 
						foreach($leagues as $league){
							echo '<a href="/prediction/leagues.php?id=' . $league->leagueid . '" class="list-group-item">' . $league->name . '</a>';
						}
					?>
				</div>
			  <?php } ?>
			  <div class="text-right"><a href="/prediction/leagues.php"> View All </a></div>
			</div>
		  </div>		  
        </div>
		<div class="col-lg-6">
          <div class="panel panel-default">
			<div class="panel-heading"><h3 class="panel-title"> Upcoming Predictions </h3></div>
			<div class="panel-body">
			  <?php 
			  if(isset($predictions)){
					echo '<ul class="list-group">';
					foreach($predictions as $pred){
						$matchDetails = $pred->t1name.' '.$pred->team1score.' - '.$pred->team2score.' '.$pred->t2name;
						echo '<li class="list-group-item">'.$matchDetails.'</li>';
					}
					echo '</ul>';
			  } else {
					echo "<a href='/prediction/matches.php'>Your haven't made your predictions yet, Click here to make your predictions before the competition starts on " . $competition->startdate;
			  } 
			  ?>
			  <div class="text-right"><a href="/prediction/matches.php?action=predictions">View All</a></div>
			</div>
		  </div>		  
        </div>
      </div>
	  
	  <div class="footer">
        <p>&copy; Andrew Edge 2014</p>
      </div>
	  
	  
	</div>
</body>

<!-- Bootstrap Javascript -->
<script src="/js/jquery.min.js" type="text/javascript" ></script>
<script src="/bootstrap/js/bootstrap.min.js" type="text/javascript" ></script>

<script src="/owl-carousel/owl.carousel.js"></script>

<script>
$(document).ready(function() { 
  $("#owl-example").owlCarousel({
	items: 1,
	itemsDesktop : [1199,1],
    itemsDesktopSmall : [980,1],
    itemsTablet: [768,1],
    itemsTabletSmall: false,
    itemsMobile : [479,1],
  });
 
});
</script>
</html>