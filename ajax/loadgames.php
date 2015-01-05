<?php

//include_once $_SERVER['DOCUMENT_ROOT'] . '/prediction/src/game.class.php';
include_once 'dbgame.class.php';
include_once 'dbteam.class.php';

if ((include 'checklogin.php') != 'OK') {
	exit('invalid login');
}

$split		   = $_GET["split"];
$action        = $_GET["action"];

if(($action == "setup"){
	if ($user->accesslevel < 10)){
		echo '<div class="noAccess divPageContent"> You do not have access to this page </div>';
	} else {
		global $competition;
		if($split != ""){
			if($competition->datasplit == "group"){
				$games = dbgame::loadGamesByCompetitionGroup($competition->competitionid, $split);
			} else {
				$games = dbgame::loadGamesByCompetitionWeek($competition->competitionid, $split);
			}
		} else {
			$games = dbgame::loadGamesByCompetition($competition->competitionid, $competition->datasplit);
		}
	}
} else if ($action == "predictions") {
	$games = dbgame::loadPredsByCompetition($competition->competitionid, $userid);
}
$teams = dbteam::loadTeamsByCompetition($competitionid);
if($action == "setup") {
?>

<div class="divPageContent">
	<div class="ui-widget-content">
		<button id="btnNewGame" type="button" onclick="newLine('New');"> New Game </button>
	</div>	
	<div id="divEditTable" class="ui-widget-content" >
		<table id="tblGame" class="editTable">
			<thead id="tblGameHead">
			<?php
				echo '<tr class="headerRow ui-widget-header">';
				echo '<th width="15%"> Date </th>';
				echo '<th width="10%">';						
				if($competition->datasplit == "group"){ echo "Group"; }else{ echo "Week"; } 
				echo '</th>';
				echo '<th width="75%"> Game </th>';						
				echo '</tr>';
			?>
			</thead>		
			<tbody id="tblGameBody">			
			<?php if(isset($games)){
				foreach($games as $game) {
						echo '<tr class="normalRow ui-widget-content" >';
						echo '<td>'. $game->date;
						echo '<td>'. $game->group .'</td>';
						echo '<span id="spnGameDets"><td>'. $game->t1name .'<span id="spnVersus">v</span>'. $game->t2name . '</span>';
						echo '<span id="spnRowButtons'. $game->gameid .'" class="ui-icon ui-icon-circle-close"></span></td>';				
						echo '</tr>';
			 	}
			} ?>				
			</tbody>					
		</table>		
	</div>
	<input type="hidden" id="hdnSelectedRow" value="" />
	<input type="hidden" id="hdnNumNewLines" value="0" />
	<input type="hidden" id="hdnAction" name="action" value="<?php echo $action ?>" />
	<input type="hidden" id="hdnCompetitionId" name="competitionid" value="<?php echo $competitionid ?>" />
	<div id ="divHiddenHtml">
	<?php 
		echo '<select id="sel[id]GameTeam[Num]" name="gameTeam[Num]">';
		if(isset($teams)){			
			foreach($teams as $team){
				echo '<option value="'. $team->teamid .'">'. $team->name .'</option>';
			}
	    } else {
			echo '<option value=""> Please setup teams </option>';
	    } 	
		echo '</select>';
	?>	
	</div>
	</form>
</div>
<?php
} else {
?>
<div class="divPageContent">
	<form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" >
	<div id="divEditTable" class="ui-widget-content">
		<table id="tblGame">
			<thead id="tblGameHead">
				<tr class="headerRow ui-widget-header" >
					<th>Date</th>
					<th><?php if($competition->datasplit == "group"){ echo "Group"; }else{ echo "Week"; } ?></th>
					<th colspan="3">Game</th>
				</tr>
			</thead>		
			<tbody id="tblGameBody">			
			<?php if(isset($games)){
				$gameIdList = "";
				foreach($games as $game) {
					$gameIdList = $gameIdList . $game->gameid . ",";
					echo '<tr class="normalRow">';
					echo '<td>'. $game->date;
					echo '<input type="hidden" name="gameid" id="hdnGameId'. $game->gameid .'" value="'. $game->gameid . '" />';
					echo '</td><td>';						
					if($competition->datasplit == "group"){ echo $game->group; }else{ echo $game->week; } 
					echo '</td>';
					echo '<td>'. $game->t1name .'</td>';
					echo '<td>';
					if($action == "results" || $action == "predictions") {
						echo '<span class="inputScore"><input type="text" class="texttable ui-widget-content ui-corner-all" size="1" maxlength="2" id="txtTeam1score' . $game->gameid . '" name="team1Score" value="'. $game->team1score .'" 	</span>';
					} else {
						echo '<span class="printScore">'. $game->team1score .'</span>';
					}
					echo '<span id="spnVersus">v</span>';
					if($action == "results" || $action == "predictions") {
						echo '<span class="inputScore"><input type="text" class="texttable ui-widget-content ui-corner-all" size="1" maxlength="2" id="txtTeam2score' . $game->gameid . '" name="team2Score" value="'. $game->team2score .'" /></span></td>'; 	
					} else {
						echo '<span class="printScore">'. $game->team1score .'</span>';
					}
                    echo '<td>'. $game->t2name .'</td>';						
					echo '</tr>';
			 	}
			} ?>
			</tbody>
		</table>
		<input type="hidden" id="hdnTeam1ScoreList" name="team1ScoreList" value="" />
		<input type="hidden" id="hdnTeam2ScoreList" name="team2ScoreList" value="" />
		<input type="hidden" id="hdnMultiplierList" name="multiplierList" value="" />
		<input type="hidden" id="hdnProcess" name="process" value="" />
		<input type="hidden" id="hdnGameIdList" name="gameIdList" value="<?php echo $gameIdList ?>" />
	</div>
	<?php if($action == "results" || $action == "predictions") { ?>
		<div id="divButtonBar" >
			<button id="btnSave" type="submit"> Save </button>
		</div>
	<?php } ?>
	</form>
</div>
<?php
}
?>

