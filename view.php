<?
	connect_db();
	
	$ID = $_GET["id"];
	
	$query="SELECT * FROM poll_info WHERE id=" . $ID;
	$result = mysql_query($query);	
	if ($result == false)
	{
		die("Failed to get poll information!! " . mysql_error());
	}
	$record = array();
	$record["id"] = $ID;
	$record["date"] = mysql_result($result, 0, "date");
	$record["question"] = mysql_result($result, 0, "question");
	$record["status"] = mysql_result($result, 0, "status");
	$record["show_id"] = mysql_result($result, 0, "show_id");
	$record["show_vote"] = mysql_result($result, 0, "show_vote");
	$record["select_multiple"] = mysql_result($result, 0, "select_multiple");
	
	$record["choice1"] = mysql_result($result, 0, "choice1");
	$record["choice2"] = mysql_result($result, 0, "choice2");
	$record["choice3"] = mysql_result($result, 0, "choice3");
	$record["choice4"] = mysql_result($result, 0, "choice4");
	$record["choice5"] = mysql_result($result, 0, "choice5");
	$record["choice6"] = mysql_result($result, 0, "choice6");
	$record["choice7"] = mysql_result($result, 0, "choice7");
	$record["choice8"] = mysql_result($result, 0, "choice8");
	$record["choice9"] = mysql_result($result, 0, "choice9");
	$record["choice10"] = mysql_result($result, 0, "choice10");

	$query = "SELECT * FROM poll_data WHERE id = " . $ID . " AND user_id = " . $user;
	$result = mysql_query($query);	
	if ($result == false)
	{
		die("Failed to get poll data!! " . mysql_error());
	}
	$num = mysql_numrows($result);
	if ($num == 0)
	{
		$vote_exists = false;
		$submit_key = "VOTE";

		$user_input = array();
		$user_input["choice1"] = 0;
		$user_input["choice2"] = 0;
		$user_input["choice3"] = 0;
		$user_input["choice4"] = 0;
		$user_input["choice5"] = 0;
		$user_input["choice6"] = 0;
		$user_input["choice7"] = 0;
		$user_input["choice8"] = 0;
		$user_input["choice9"] = 0;
		$user_input["choice10"] = 0;
	}
	else
	{
		$vote_exists = true;
		$submit_key = "UPDATE";
	
		$user_input = array();
		$user_input["choice1"] = mysql_result($result, 0, "choice1");
		$user_input["choice2"] = mysql_result($result, 0, "choice2");
		$user_input["choice3"] = mysql_result($result, 0, "choice3");
		$user_input["choice4"] = mysql_result($result, 0, "choice4");
		$user_input["choice5"] = mysql_result($result, 0, "choice5");
		$user_input["choice6"] = mysql_result($result, 0, "choice6");
		$user_input["choice7"] = mysql_result($result, 0, "choice7");
		$user_input["choice8"] = mysql_result($result, 0, "choice8");
		$user_input["choice9"] = mysql_result($result, 0, "choice9");
		$user_input["choice10"] = mysql_result($result, 0, "choice10");
	}
	
	if ($record["select_multiple"] == 1)
		$option_type_string = "checkbox";
	else
		$option_type_string = "radio";
	
	if ($record["status"] == 0)
		$closed_poll_string = "disabled";
	else
		$closed_poll_string = "";
	////////////////////////////////////////////////////////////////////////////////////////////////
?>
	
	<h2>Question</h2>
	<h3><? echo $record["question"]; ?>
	<form name="theViewForm" target="_top" action="<? echo fb_root("index.php"); ?>" method="get">
	<input name="respond" type="hidden" value="VOTE" />
	<input name="vote_type" type="hidden" value="<? echo $option_type_string; ?>" />
	<input name="id" type="hidden" value="<? echo $ID; ?>" />
	<input name="user_id" type="hidden" value="<? echo $user; ?>" />
<?
	for ($i = 1; $i <= 10; $i++)
	{
		$key = "choice" . $i;
		if ($record[$key] != null) {
			if ($record[$key] != "") {
				if ($option_type_string == "radio")
				{
					echo '<input type="radio" name="answer" value="' . $key;
					if ($user_input[$key] == 1)
						echo '" checked ' . $closed_poll_string;
					else
						echo '"' . $closed_poll_string;
					echo '> ' . $record[$key] . '<br/>';
				}
				else if ($option_type_string == "checkbox")
				{
					echo '<input type="checkbox" name="' . $key . '" value="1"';
					if ($user_input[$key] == 1)
						echo ' checked ' . $closed_poll_string;
					else
						echo $closed_poll_string;
					echo '> ' . $record[$key] . '<br/>';
				}
			}
		}
	}

	if ($record["status"] == 1)
	{
		if ($vote_exists)
		{
			echo '<p>Your vote is shown above. You can change your vote until the poll is closed. </p>';
		}

		echo '<input type="submit" name="vote" value="' . $submit_key . '">';
	}
	else
		echo '<label>This poll is closed.</label>';

	echo '</form></h3>';

	$show_id = $record["show_id"];
	$show_vote = $record["show_vote"];
	global $IS_ADMIN;
	
	if ($IS_ADMIN)
	{
		$show_id = 1;
		$show_vote = 1;
	}

	$query = "SELECT * FROM poll_data WHERE id=" . $ID;
	$result = mysql_query($query);	
	if ($result == false)
	{
		die("Failed to get poll data!! " . mysql_error());
	}
	$num = mysql_numrows($result);

	$data = array();
	for($i=0; $i < $num; $i++)
	{
		$data[$i] = mysql_fetch_array($result, MYSQL_ASSOC);
	}
	
	if ($show_vote == 1)
	{
		$display_data = array();
		
		for ($i = 1; $i <= 10; $i++)
		{
			$key = "choice" . $i;
			if ($record[$key] != null) 
			{
				if ($record[$key] != "") 
				{
					$cnt = 0;
					$voters = array();
					for ($j = 0; $j < $num; $j++)
					{
						if ($data[$j][$key] == 1)
						{
							$voters[$cnt] = get_user_name_from_id( $data[$j]["user_id"] );
							$cnt ++;
						}
					}
					
					$display_data[$key]["count"] = $cnt;
					$display_data[$key]["voters"] = $voters;
				}
			}
		}
		
		if ($num == 1)
			echo '<h2>Responses: 1 person cast his/her vote.</h2><h3>';
		else
			echo '<h2>Responses: ' . $num . ' people cast their vote.</h2><h3>';
		for ($i = 1; $i <= 10; $i++)
		{
			$key = "choice" . $i;
			if ($record[$key] != null) 
			{
				if ($record[$key] != "") 
				{
					echo $record[$key] . ' : ' . $display_data[$key]["count"] . '<br/>';
					if ($show_id == 1)
					{
						echo '<ul>';
						for($t = 0; $t < $display_data[$key]["count"]; $t++)
						{
							echo '<li>' . $display_data[$key]["voters"][$t] . '</li>';
						}
						echo '</ul>';
					}
				}
			}
		}
		echo '</h3>';
	}
	mysql_close();
