<?
	require './src/facebook.php';
	
	define('FB_HOME', 		"http://facebook.com/");
	define('FB_ROOT', 		"http://apps.facebook.com/westwoodpoll/");
	define('APP_ID', 		"103934743064122");
	define('APP_SECRET', 	"6770da0b7abbcccb483d55fab7584999");
	define('GROUP_ID',		"222964104464758");
	
	function fb_root($url="") {
		return FB_ROOT . $url;
	}

	function fb_home($url="") {
		return FB_HOME . $url;
	}

	$facebook = new Facebook(array(
	  'appId'  => APP_ID,
	  'secret' => APP_SECRET,
	  'cookie' => true,
	  'xfbml'  => true
	));
	$user = $facebook->getUser();

	if($user == null)
	{
		$url = $facebook->getLoginUrl(array(
			'canvas' 		=> 1,
			'fbconnect' 	=> 0,
			'next'			=> fb_root(),
			'cancel_url'	=> fb_home(),
			'redirect_uri' 	=> fb_root(),
			'scope'			=> 'read_stream,user_groups,email,publish_stream,manage_pages'
		));

		echo "<script type='text/javascript'>window.top.location = '$url';</script>";
	}
	else
	{
		// User is logged in and authorized, let's party.
		try {
			$GROUP_INFO = $facebook->api('/'.GROUP_ID);
 		} catch (FacebookApiException $e) { echo $e; }
		//print_r($GROUP_INFO);		

		try {
			$call = $facebook->api('/'.GROUP_ID.'/members');
 		} catch (FacebookApiException $e) { echo $e; }
		$MEMBER_INFO = $call['data'];
		//print_r($MEMBER_INFO);
		//echo "<br/>";
		
		$IS_ADMIN = 0;
		$USER_EMAIL = "svv9677@yahoo.com";
		$userFound = 0;
		foreach($MEMBER_INFO as $member) {
			//print_r($member);
			//echo "<br/>";
			if ($member['id'] == $user) {
				$userFound = 1;
				$IS_ADMIN = $member['administrator'];
				//echo "Found<br/>";
			}
		}
		
		// if the current user is not part of the group
		if ($userFound == 0)
		{
			echo "<br/><br/><h1>You need to be a member of <a target='_top' href='http://www.facebook.com/groups/westwood.united/'>Westwood United Cricket Club</a> group to use this app.. Please click the link for more information.</h1>";
			$user = 0;
		}
		else
		{
			try {
				$call = $facebook->api('/me');
			} catch (FacebookApiException $e) { echo $e; }
			
			$USER_EMAIL = $call['email'];
		}
	}	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////
	/* 
	AT THE END OF THIS FILE, IF THERE IS A VALID USER, WE HAVE THE FOLLOWING
		$GROUP_INFO - details about the group that include
			[id] => 222964104464758
			[version] => 1
			[owner] => Array
				(
					[name] => Rao Vadapalli
					[id] => 1844072761
				)
			[name] => Westwood United Cricket Club
			[description] => This group represents the professionals playing cricket in Southern California, under SCCA Division III.
			[privacy] => CLOSED
			[icon] => http://static.ak.fbcdn.net/rsrc.php/v1/yx/r/xVRsv6Dgj5z.png
			[updated_time] => 2012-02-05T06:08:01+0000
			[email] => westwood.united@groups.facebook.com			
		
		$MEMBER_INFO - details about the group's members that include
			Array
			(
				[0] => Array
					(
						[name] => Champion Chef
						[id] => 100000394321392
						[administrator] => 0
					)
				[1] => Array
					(
						[name] => Rao Vadapalli
						[id] => 1844072761
						[administrator] => 1
					)
			)		
	
		$IS_ADMIN - if the current user is admin for the group or not
		
		$USER_EMAIL - logged in user's email
	*/
	//////////////////////////////////////////////////////////////////////////////////////////////////
?>