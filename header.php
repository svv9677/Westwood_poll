<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
	<title>Westwood Cricket Club - Polls</title>
	<?
	ini_set('session.use_trans_sid', 1);
	header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
	?>
	<link rel="stylesheet" href="images/MarketPlace.css" type="text/css" />
</head>

<body>

	<div id="fb-root"></div>

	<?
		include './backend.php';
		
		function connect_db()
		{
			$link = mysql_connect('svv9677.dot5hostingmysql.com', 'poll_user', 'poll_pass'); 
			if (!$link) { 
				die('Could not connect: ' . mysql_error()); 
			} 
			//echo 'Connected successfully'; 
			@mysql_select_db(westwood) or die( "Unable to select database" . mysql_error());
		}
		
		function send_mail($from, $to, $subject, $body)
		{
			$headers = 'From: ' . $from;
			$body = wordwrap($body, 70);
			if (mail($to, $subject, $body, $headers)) {
				return true;
			} else {
				return false;
			}
		}
		
		function get_user_name_from_id($id)
		{
			global $MEMBER_INFO;

			for ($i = 0; $i < count($MEMBER_INFO); $i++)
			{
				if ($MEMBER_INFO[$i]["id"] == $id)
					return $MEMBER_INFO[$i]["name"];
			}
			return "N/A";
		}
	?>
