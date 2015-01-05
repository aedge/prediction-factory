<div class="header">
	<ul class="nav nav-pills pull-right">
	    <li class="<?php if($currenttab == 'home'){ echo 'active'; } ?>"><a href="/prediction/home.php">Home</a></li>
		<li class="dropdown <?php if($currenttab == 'league'){ echo 'active'; } ?>">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
			 Leagues <span class="caret"></span>
			</a>
		<ul class="dropdown-menu">
		  <li role="presentation"><a role="menuitem" tabindex="-1" href="/prediction/leagues.php">View</a></li>
		  <li role="presentation"><a role="menuitem" tabindex="-1" href="/prediction/league.php?action=add">Create</a></li>
		  <li role="presentation"><a role="menuitem" tabindex="-1" href="/prediction/league.php?action=join">Join</a></li>
		</ul>
	  </li>
	  <li class="<?php if($currenttab == 'predictions'){ echo 'active'; } ?>"><a href="/prediction/matches.php?action=predictions">Predictions</a></li>
	  <?php if($user->accesslevel >= 50) { ?>		  
		  <li class="dropdown <?php if($currenttab == 'setup'){ echo 'active'; } ?>">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				 Setup <span class="caret"></span>
				</a>
			<ul class="dropdown-menu">
			  <li role="presentation"><a role="menuitem" tabindex="-1" href="/prediction/matches.php?action=results">Results</a></li>
			  <li role="presentation"><a role="menuitem" tabindex="-1" href="/prediction/resetpassword.php">Reset Passwords</a></li>
			</ul>
		  </li>
			<?php } ?>
	  <li><a href="/prediction/logout.php">Logout</a></li>
		</ul>
	<h3 class="text-muted">Prediction Factory</h3>
</div>