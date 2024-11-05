<?php
	function getUserIP() {
		$ip = '';

		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			// IP from shared internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			// IP passed from a proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			// Standard remote address
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		// If there are multiple IPs (for example, from a proxy), take the first one
		return explode(',', $ip)[0];
	}

//	$usr_ip = getUserIP();
//$usr_ip == "119.95.101.196" ||
//	if ($usr_ip == "112.209.28.68" || $usr_ip == "111.125.231.225" || $usr_ip == "103.226.85.245"  || $usr_ip == "103.173.194.80" || $usr_ip == "103.129.206.57" || $usr_ip == "152.58.50.9"){

		function db(): mysqli
		{
			$dbuser		= "usedcardboardbox_production_usr";
			$dbserver	= "localhost";
			$dbpass		= "YtoA#[I[^.Ay";
			$dbname		= "usedcardboardbox_production";

			//CONNECTION STRING
			$con_db = 'db_link';
			global $$con_db;
			$$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
			return $$con_db;
		}

		function db_b2b(): mysqli
		{
			$dbuser		= "usedcardboardbox_b2b_usr";
			$dbserver	= "localhost";
			$dbpass		= "0JX+o3u4PM_l";
			$dbname		= "usedcardboardbox_b2b";

			//CONNECTION STRING
			$con_db = 'db_link';
			global $$con_db;
			$$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
			return $$con_db;
		}

		function db_email(): mysqli
		{
			$dbuser		= "usedcardboardbox_ucbemailusr";
			$dbserver	= "localhost";
			$dbpass		= "^qqWSB,Q-=6}";
			$dbname		= "usedcardboardbox_ucbemail";

			//CONNECTION STRING
			$con_db = 'db_link';
			global $$con_db;
			return $$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		}

		function db_b2c_email_new(): mysqli
		{
			$dbuser		= "usedcardboardbox_b2cemluser";
			$dbserver	= "localhost";
			$dbpass		= "6k%b}n}e%4$3";
			$dbname		= "usedcardboardbox_b2c_email";

			//CONNECTION STRING
			$con_db = 'db_link';
			global $$con_db;
			return $$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		}


		function db_project_mgmt(): mysqli
		{
			$dbuser		= "usedcardboardbox_prjmgmt_usr";
			$dbserver	= "localhost";
			$dbpass		= "yMm3N=QsNus!";
			$dbname		= "usedcardboardbox_project_mgmt";

			//CONNECTION STRING
			$con_db = 'db_link';
			global $$con_db;
			return $$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		}

		function db_water_inbox_email(): mysqli
		{
			$dbuser		= "usedcardboardbox_water_inbox_usr";
			$dbserver	= "localhost";
			$dbpass		= "e$%=H^H$7Rt4";
			$dbname		= "usedcardboardbox_water_inbox_email";

			//CONNECTION STRING
			$con_db = 'db_link';
			global $$con_db;
			return $$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		}
	/*}else {
		$page_nm = "downtime_UsedCardboardBoxes.html";
		if (!headers_sent()) {
			header('Location: ' . $page_nm);
			exit;
		} else {
			echo "<script type=\"text/javascript\">";
			echo "window.location.href=\"" . $page_nm . "\";";
			echo "</script>";
			echo "<noscript>";
			echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $page_nm . "\" />";
			echo "</noscript>";
			exit;
		}
	}*/
?>