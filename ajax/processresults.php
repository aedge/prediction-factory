<?php

include_once '/home/andrewed/public_html/prediction/src/dbgame.class.php';
include_once '/home/andrewed/public_html/prediction/src/dbuser.class.php';

$reqAccessLevel = 5;

$dbgame = new dbgame();
$dbuser = new dbuser();

$competitionid = $dbgame->loadActiveCompetition()->competitionid;
$processdate   = date("Y-m-d");

$games = $dbgame->loadGamesForUpdate($competitionid, $processdate);

foreach($games as $game){

	if($game->result != '' && $game->result != null){
		$predictions = $dbgame->loadPredsByGame($game->gameid);
		
	foreach($predictions as $prediction){
	
		if($prediction->team1score > $prediction->team2score){
			$prediction->result = 'hw';
		} else if($prediction->team1score < $prediction->team2score) {
			$prediction->result = 'aw';
		} else {
			$prediction->result = 'd';
		}	
		
		if($prediction->result == $game->result){
			if(($prediction->team1score == $game->team1score) && ($prediction->team2score == $game->team2score)) {
				$prediction->points = 6;
			} else if(($prediction->team1score == $game->team1score) || ($prediction->team2score == $game->team2score)) {
				$prediction->points = 4;
			} else {
				$prediction->points = 3;
			}			
		}		
		else if(($prediction->team1score == $game->team1score) || ($prediction->team2score == $game->team2score)){
			$prediction->points = 1;
		}
		
		$prediction->accuracy = $prediction->points / 6;
		
		if($prediction->multiplier == "Star"){
			$prediction->points = $prediction->points * 2;
		} else if($prediction->multiplier == "Heart"){
			$prediction->points = $prediction->points * 3;
		}			
	}
	}
	
	$dbgame->savePredictions($predictions);
}

$dbuser->updatePointsTotals();

echo "Processing Complete";
?>