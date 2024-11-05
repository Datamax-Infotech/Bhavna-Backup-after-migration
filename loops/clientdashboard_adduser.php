<?php
require_once("inc/header_session.php");
require_once("../mainfunctions/database.php");
require_once("../mainfunctions/general-functions.php");

db();

if ((isset($_REQUEST["existing"])) && ($_REQUEST["existing"] = "new")) {
	//

	//
	$res1 = db_query("SELECT * FROM clientdashboard_usermaster WHERE user_name='" . $_REQUEST["clientdash_username"] . "'");
	while ($fetch_data1 = array_shift($res1)) {
		$newpassword = $fetch_data1["password"];
	}
	//
	// $strQuery = "Insert into clientdashboard_usermaster (companyid, user_name, password, client_email, activate_deactivate) values (" . $_REQUEST["hidden_companyid"] . ", '";
	// $strQuery .= $_REQUEST["clientdash_username"] . "', '" . $newpassword . "', '" . $_REQUEST["clientdash_username"] . "', 1)";


	if (isset($_REQUEST['boomrang']) && $_REQUEST['boomrang'] == "Boom_beta") {
		// Insert only companyid and user_name
		$strQuery = "INSERT INTO clientdashboard_usermaster (companyid, user_id) VALUES (" . $_REQUEST["hidden_companyid"] . ", '" . $_REQUEST["clientdash_userid"] . "')";
	} else {
		// Insert the new user with the existing password
		$strQuery = "INSERT INTO clientdashboard_usermaster (companyid, user_name, password, client_email, activate_deactivate) VALUES (" . $_REQUEST["hidden_companyid"] . ", '" . $_REQUEST["clientdash_username"] . "', '" . $newpassword . "', '" . $_REQUEST["clientdash_username"] . "', 1)";
	}

	$result = db_query($strQuery);
	//
	// echo "<script type=\"text/javascript\">";
	// echo "window.location.href=\"clientdashboard_setup.php?ID=" . $_REQUEST["hidden_companyid"] . "\";";
	// echo "</script>";
	// echo "<noscript>";
	// echo "<meta http-equiv=\"refresh\" content=\"0;url=clientdashboard_setup.php?ID=" . $_REQUEST["hidden_companyid"] . "\" />";
	// echo "</noscript>";
	// exit;
	// Check the result of the query
	if ($result) {
		// Redirect based on 'boomrang' value
		if (isset($_REQUEST['boomrang']) && $_REQUEST['boomrang'] == "Boom_beta") {
			echo "<script type=\"text/javascript\">";
			echo "window.location.href=\"clientdashboard_setup.php?ID=" . $_REQUEST["hidden_companyid"] . "&displaydata=Boom_beta\";";
			echo "</script>";
			echo "<noscript>";
			echo "<meta http-equiv=\"refresh\" content=\"0;url=clientdashboard_setup.php?ID=" . $_REQUEST["hidden_companyid"] . "&displaydata=Boom_beta\" />";
			echo "</noscript>";
		} else {
			echo "<script type=\"text/javascript\">";
			echo "window.location.href=\"clientdashboard_setup.php?ID=" . $_REQUEST["hidden_companyid"] . "\";";
			echo "</script>";
			echo "<noscript>";
			echo "<meta http-equiv=\"refresh\" content=\"0;url=clientdashboard_setup.php?ID=" . $_REQUEST["hidden_companyid"] . "\" />";
			echo "</noscript>";
		}
	} else {
		// Handle the error in case the query fails
		echo "Error: Unable to insert user.";
	}

	exit;
} else {

	$res = db_query("Select user_name from clientdashboard_usermaster where user_name = '" . $_REQUEST["clientdash_username"] . "'");
	$rec_found = "no";
	while ($fetch_data = array_shift($res)) {
		$rec_found = "yes";

		echo "<script type=\"text/javascript\">";
		echo "window.location.href=\"clientdashboard_setup.php?ID=" . $_REQUEST["hidden_companyid"] . "&usrnm=" . $_REQUEST["clientdash_username"] . "&duprec=yes\";";
		echo "</script>";
		echo "<noscript>";
		echo "<meta http-equiv=\"refresh\" content=\"0;url=clientdashboard_setup.php?ID=" . $_REQUEST["hidden_companyid"] . "&usrnm=" . $_REQUEST["clientdash_username"] . "&duprec=yes\" />";
		echo "</noscript>";
		exit;
	}
	if ($rec_found = "no") {

		if (isset($_REQUEST['boomrang']) && $_REQUEST['boomrang'] == "Boom_beta") {
			// Insert only companyid and user_name
			$strQuery = "INSERT INTO clientdashboard_usermaster (companyid, user_id) VALUES (" . $_REQUEST["hidden_companyid"] . ", '" . $_REQUEST["clientdash_userid"] . "')";
		} else {
			// Insert the new user with the existing password
			$strQuery = "INSERT INTO clientdashboard_usermaster (companyid, user_name, password, client_email, activate_deactivate) VALUES (" . $_REQUEST["hidden_companyid"] . ", '" . $_REQUEST["clientdash_username"] . "', '" . $newpassword . "', '" . $_REQUEST["clientdash_username"] . "', 1)";
		}
		// $strQuery = "Insert into clientdashboard_usermaster (companyid, user_name, password, client_email, activate_deactivate) values (" . $_REQUEST["hidden_companyid"] . ", '";
		// $strQuery .= $_REQUEST["clientdash_username"] . "', '" . $_REQUEST["clientdash_pwd"] . "', '" . $_REQUEST["clientdash_username"] . "', 1)";

		$result = db_query($strQuery);

		// echo "<script type=\"text/javascript\">";
		// echo "window.location.href=\"clientdashboard_setup.php?ID=" . $_REQUEST["hidden_companyid"] . "\";";
		// echo "</script>";
		// echo "<noscript>";
		// echo "<meta http-equiv=\"refresh\" content=\"0;url=clientdashboard_setup.php?ID=" . $_REQUEST["hidden_companyid"] . "\" />";
		// echo "</noscript>";
		// exit;
		// if ($result) {
		// Redirect based on 'boomrang' value
		if (isset($_REQUEST['boomrang']) && $_REQUEST['boomrang'] == "Boom_beta") {
			echo "<script type=\"text/javascript\">";
			echo "window.location.href=\"clientdashboard_setup.php?ID=" . $_REQUEST["hidden_companyid"] . "&displaydata=Boom_beta\";";
			echo "</script>";
			echo "<noscript>";
			echo "<meta http-equiv=\"refresh\" content=\"0;url=clientdashboard_setup.php?ID=" . $_REQUEST["hidden_companyid"] . "&displaydata=Boom_beta\" />";
			echo "</noscript>";
		} else {
			echo "<script type=\"text/javascript\">";
			echo "window.location.href=\"clientdashboard_setup.php?ID=" . $_REQUEST["hidden_companyid"] . "\";";
			echo "</script>";
			echo "<noscript>";
			echo "<meta http-equiv=\"refresh\" content=\"0;url=clientdashboard_setup.php?ID=" . $_REQUEST["hidden_companyid"] . "\" />";
			echo "</noscript>";
		}
		// } else {
		// 	// Handle the error in case the query fails
		// 	echo "Error: Unable to insert user.";
		// 	echo $strQuery;
		// 	print_r($result);
		// }
	}
}
