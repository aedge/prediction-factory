		
						echo '<tr id="row-'. $game->gameid .'" class="ui-widget-content">'. PHP_EOL;
						echo '<td id="date-'. $game->gameid .'" >'. $game->date .'</td>'. PHP_EOL;
						echo '<td id="datasplit-'. $game->gameid .'" >';						
						if($competition->datasplit == "group"){ echo $game->group; }else{ echo $game->week; } 
						echo '</td>'. PHP_EOL;
						echo '<td id="team1-'. $game->gameid .'" class="team1Cell" >'. $game->t1name .'</td>'. PHP_EOL;
						echo '<td id="scores-'. $game->gameid .'" >';
						if(($action == "results" || $action == "predictions") && $canEdit) {
							$gameIdList = $gameIdList . $game->gameid . ",";
							echo '<span class="inputScore"><input type="text" class="texttable ui-widget-content ui-corner-all" size="1" maxlength="2" id="txtTeam1score-'. $game->gameid .'" name="team1Score" value="'. $game->team1score .'" 	</span>';
						} else {
							echo '<span class="printScore">'. $game->team1score .'</span>';
						}
						echo '<span id="spnVersus">v</span>';
						if(($action == "results" || $action == "predictions") && $canEdit) {
							echo '<span class="inputScore"><input type="text" class="texttable ui-widget-content ui-corner-all" size="1" maxlength="2" id="txtTeam2score-' . $game->gameid . '" name="team2Score" value="'. $game->team2score .'" /></span>'; 	
						} else {
							echo '<span class="printScore">'. $game->team1score .'</span>';
						}
						echo '</td>'. PHP_EOL;
						echo '<td id="team2-'. $game->gameid .'" class="team1Cell" >'. $game->t2name .'</td>'. PHP_EOL;		
						if($action == "predictions"){
							$starCheck = "";
							$heartCheck = "";
							$noneCheck = "";
							$disabled = "";
							if(!$canEdit){
								$disabled = "disabled";
							}
							if($game->multiplier == "Star"){
								$starCheck = "checked";
								if($starList == ""){
									$starList = $game->gameid;
								} else {
									$starList = $starList . "," . $game->gameid;
								}
							}
							else if($game->multiplier == "Heart"){
								$heartCheck = "checked";
								if($heartList == ""){
									$heartList = $game->gameid;
								} else {
									$heartList = $heartList . "," . $game->gameid;
								}
							} else {
								$game->multiplier = "None";
								$noneCheck = "checked";
							}
							echo '<td id="multiplier-'. $game->gameid .'">';
							echo '<div class="multipliers">';
							echo '<input type="radio" name="multiplier-'. $game->gameid .'" title="None" id="radNone-'. $game->gameid .'" class="noneradio" value="none" '. $noneCheck  .' '. $disabled .' /><label for="radNone-'. $game->gameid .'" >None</label>';
							echo '<input type="radio" name="multiplier-'. $game->gameid .'" title="Star" id="radStar-'. $game->gameid .'" class="starradio" value="star" '. $starCheck  .' '. $disabled . ' /><label for="radStar-'. $game->gameid .'" >Star</label>';
							echo '<input type="radio" name="multiplier-'. $game->gameid .'" title="Heart" id="radHeart-'. $game->gameid .'" class="heartradio" value="heart" '. $heartCheck  .' '. $disabled .' /><label for="radHeart-'. $game->gameid .'" >Heart</label>';
							echo '<input type="hidden" id="currentMultiplier-'. $game->gameid  .'" value="rad'. $game->multiplier .'-'. $game->gameid .'" />';
							echo '</div>';
							echo '</td>'. PHP_EOL;
						}
						echo '</tr>'. PHP_EOL;
					}