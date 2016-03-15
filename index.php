<?
	include './header.php';
	
	if ($user != null)
	{
		$COMMAND = $_GET["command"];
		$RESPOND = $_GET["respond"];
		
		echo '<div id="wrap">';
		echo '<div id="header-photo">';
		echo '<h1 id="logo-text"><a target="_top" href="./index.php" title="">Westwood Polls</a></h1>';
		echo '<h2 id="slogan">be proactive... it only takes a few minutes to vote</h2>';
		echo '</div>';
		
		echo '<div id="nav"><ul>';
		echo '<li><a target="_top" href="' . fb_root() . '">Home</a></li>';
		echo '<li><a target="_top" href="' . fb_root("index.php?command=CREATE") . '">Create Poll</a></li>';
		echo '<li><a target="_blank" href="http://www.facebook.com/groups/westwood.united/">Visit The Group</a></li>';
		echo '</ul></div>';
		
		echo '<div id="content-wrap"><div id="main">';

		/////////////////////////////////////////////////////////////////////////////////////////////////
		// we need to respond to a submitted query
		if ($RESPOND != null)
		{
			// take corresponding action
			if ($RESPOND == "CREATE")
			{
				connect_db();
				
				$query = "INSERT INTO poll_info VALUES (null, '" . $_GET["date"] . "', 1, " . $_GET["show_id"] . ", "
														. $_GET["show_vote"] . ", " . $_GET["select_multiple"] . ", '" . $_GET["question"] . "', '"
														. $_GET["choice1"] . "', '" . $_GET["choice2"] . "', '"
														. $_GET["choice3"] . "', '" . $_GET["choice4"] . "', '"
														. $_GET["choice5"] . "', '" . $_GET["choice6"] . "', '"
														. $_GET["choice7"] . "', '" . $_GET["choice8"] . "', '"
														. $_GET["choice9"] . "', '" . $_GET["choice10"] . "')";
				$result = mysql_query($query);	
				if ($result == false) {
					echo "<h3>Unable to create poll. Error: " . mysql_error() . "</h3>";
				} else {
					try {
						$attachment = array('message' => 'Please vote your choice, we have a new poll!',
										'name' => 'Westwood Polls',
										'caption' => "New Poll",
										'link' => 'http://apps.facebook.com/westwoodpoll',
										'description' => $_GET["question"],
										'actions' => array(array('name' => 'View polls',
														  'link' => 'http://apps.facebook.com/westwoodpoll'))
										);
					$result = $facebook->api('/'.GROUP_ID.'/feed/', 'post', $attachment);
					} catch (FacebookApiException $e) { echo $e; }
					if($result) {
						echo "<h2>Poll created successfully and posted on your cricket club group.</h2<h2>Use the links above to navigate further.</h2>";
					} else {
						echo "<h2>Poll created successfully. </h2><h3>Error posting to your group, please check permissions!</h3><h2>Use the links above to navigate further.</h2>";
					}
				}
				
				mysql_close();
			}
			else if ($RESPOND == "CLOSE")
			{
				connect_db();
				$id = $_GET["id"];
				
				$query = "UPDATE poll_info SET status=0 WHERE id = " . $id;
				$result = mysql_query($query);	
				if ($result == false) {
					echo "<h3>Unable to close poll. Error: " . mysql_error() . "</h3>";
				} else {
				?>
					<h2>Poll is now closed. </h2>
					<h3>Since you are an adiministrator, you can view the results of the poll by clicking its link on the home page.</h3>
					<h2>Use the links above to navigate further.</h2>
				<?}
				
				mysql_close();
			}
			else if ($RESPOND == "DELETE")
			{
				connect_db();
				$id = $_GET["id"];
				
				$query = "DELETE FROM poll_info WHERE id = " . $id;
				$result = mysql_query($query);	
				if ($result == false) {
					echo "<h3>Unable to delete poll. Error: " . mysql_error() . "</h3>";
				} else {
					echo "<h2>Poll has been deleted.</h2>";
					echo "<h2>Use the links above to navigate further.</h2>";
				}
				
				mysql_close();
			}
			else if ($RESPOND == "VOTE")
			{
				if ($_GET["vote_type"] == "radio")
				{
					for ($i = 1; $i <= 10; $i++)
					{
						$key = "choice" . $i;
						if ($_GET["answer"] == $key)
							$data[$key] = 1;
						else
							$data[$key] = 0;
					}
				} else if ($_GET["vote_type"] == "checkbox")
				{
					for ($i = 1; $i <= 10; $i++)
					{
						$key = "choice" . $i;
						if (isSet($_GET[$key]))
							$data[$key] = $_GET[$key];
						else
							$data[$key] = 0;
					}
				}
				
				connect_db();
				if ($_GET["vote"] == "VOTE")
				{
					$query = "INSERT INTO poll_data VALUES (" . $_GET["id"] . ", " . $_GET["user_id"] . ", " 
															. $data["choice1"] . ", " . $data["choice2"] . ", "
															. $data["choice3"] . ", " . $data["choice4"] . ", "
															. $data["choice5"] . ", " . $data["choice6"] . ", "
															. $data["choice7"] . ", " . $data["choice8"] . ", "
															. $data["choice9"] . ", " . $data["choice10"] . ")";
					$result = mysql_query($query);	
					//echo $query;
					if ($result == false) {
						die("Unable to cast vote. Error: " . mysql_error());
					}
				} 
				else if ($_GET["vote"] == "UPDATE")
				{
					$query = "UPDATE poll_data SET choice1 = " . $data["choice1"] . ", choice2 = " . $data["choice2"] . ", choice3 = "
															. $data["choice3"] . ", choice4 = " . $data["choice4"] . ", choice5 = "
															. $data["choice5"] . ", choice6 = " . $data["choice6"] . ", choice7 = "
															. $data["choice7"] . ", choice8 = " . $data["choice8"] . ", choice9 = "
															. $data["choice9"] . ", choice10 = " . $data["choice10"] . " WHERE id = " . $_GET["id"] . " AND user_id = " . $_GET["user_id"];
					$result = mysql_query($query);	
					//echo $query;
					if ($result == false) {
						die("Unable to update vote. Error: " . mysql_error());
					}
				}
				
				mysql_close();
				echo '<script type="text/javascript">window.top.location = "' . fb_root("index.php?command=VIEW&id=") . $_GET["id"] . '";</script>';
			}
		}
		// we need to display options for user action
		else 
		{
			if ($COMMAND == null ) {
				include './generic.php';
			} else if ($COMMAND == "GENERIC") {
				include './generic.php';
			} else if ($COMMAND == "CREATE") {
				include './create.php';
			} else if ($COMMAND == "VIEW") {
				include './view.php';
			}
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////
		
		echo '</div></div>';
		echo '<div id="footer-wrap"><div id="footer"><p>Maintained and hosted at Subbarao-Vadapalli.info</p></div></div>';
		echo '</div>';
	}
?>

</body>
</html>
