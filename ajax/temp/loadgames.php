<?php

//include_once $_SERVER['DOCUMENT_ROOT'] . '/prediction/src/game.class.php';
include_once '/src/game.class.php';
include_once '/src/team.class.php';

if ((include 'checklogin.php') != 'OK') {
	exit('invalid login');
}

$competitionid = $_GET["competitionid"];
$split		   = $_GET["split"];
$action        = $_GET["action"];

if(($action == "setup") && ($user->accesslevel < 10)){
	echo '<div class="noAccess divPageContent"> You do not have access to this page </div>';
} else {
	$competition = game::loadCompetition($competitionid);
	if($competition->datasplit == "group"){
		$games = game::loadGamesByCompetitionGroup($competition->competitionid, $split);
	} else {
		$games = game::loadGamesByCompetitionWeek($competition->competitionid, $split);
	}	
}
$teams = team::loadTeamsByCompetition($competitionid);
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
				echo '<th> Date </th>';
				echo '<th>';						
				if($competition->datasplit == "group"){ echo "Group"; }else{ echo "Week"; } 
				echo '</th>';
				echo '<th> Game </th>';						
				echo '</tr>';
			?>
			</thead>		
			<tbody id="tblGameBody">			
			<?php if(isset($games)){
				foreach($games as $game) {
						echo '<tr class="normalRow">';
						echo '<td>'. $game->date;
						echo '<td>'. $game->group .'</td>';
						echo '<td>'. $game->t1name .'<span id="spnVersus">v</span>'. $game->t2name;
						echo '<div class="ui-corner-all" ><span id="spnDeleteIcon" class="ui-icon ui-icon-circle-close"></span><div></td>';				
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
	<form method="post" action="ajax/savegames.php" >
	<div id="divEditTable">
		<table id="tblGame">
			<thead id="tblGameHead">
				<tr>
					<th>Date</th>
					<th><?php if($competition->datasplit == "group"){ echo "Group"; }else{ echo "Week"; } ?></th>
					<th colspan="3"></th>
				</tr>
			</thead>		
			<tbody id="tblGameBody">			
			<?php if(isset($games)){
				foreach($games as $game) {
					echo '<tr class="normalRow">';
					echo '<td>'. $game->date;
					echo '<input type="hidden" id="hdnGameId'. $game->gameid .'" value="'. $game->gameid . '" />';
					echo '</td><td>';						
					if($competition->datasplit == "group"){ echo $game->group; }else{ echo $game->week; } 
					echo '</td>';
					echo '<td>'. $game->t1name .'</td>';
					echo '<td>';
					if($action == "results" || $action == "predictions") {
						echo '<span class="inputScore"><input type="text" id="txtTeam1score' . $game->gameid . '" value="'. $game->team1score .'" 	</span>';
					} else {
						echo '<span class="printScore">'. $game->team1score .'</span>';
					}
					echo '<span id="spnVersus">v</span>';
					if($action == "results" || $action == "predictions") {
						echo '<span class="inputScore"><input type="text" id="txtTeam1score' . $game->gameid . '" value="'. $game->team2score .'" /></span></td>'; 	
					} else {
						echo '<span class="printScore">'. $game->team1score .'</span>';
					}
                    echo '<td>'. $game->t2name .'</td>';						
					echo '</tr>';
			 	}
			} ?>
			</tbody>
		</table>
	</div>
	<?php if($action == "results" || $action == "predictions") { ?>
		<div id="bottomButtonBar" class="ui-widget-content">
			<button id="btnSave" type="submit"> Save </button>
		</div>
	<?php } ?>
	</form>
</div>
<?php
}
?>

