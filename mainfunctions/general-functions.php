<?php
function get_result_new(mysqli_stmt $Statement): mixed
{
	$RESULT = [];
	$PARAMS = [];
	$Statement->store_result();
	for ($i = 0; $i < $Statement->num_rows; $i++) {
		$Metadata = $Statement->result_metadata();
		$PARAMS = [];
		if (is_object($Metadata)) {
			while ($Field = $Metadata->fetch_field()) {
				if (is_object($Field) && property_exists($Field, 'name')) {
					$RESULT[$i][$Field->name] = null; // Initialize the array element
					$PARAMS[] = &$RESULT[$i][$Field->name];
				}
			}
		}
		call_user_func_array([$Statement, 'bind_result'], $PARAMS);
		$Statement->fetch();
	}
	return $RESULT;
}

/**
 * @param string $query
 * @param array<string>|null $param_type
 * @param array<mixed>|null $param_data
 * @return array<array<string, mixed>>
 */
function db_query(string $query, ?array $param_type = [], ?array $param_data = []): mixed
{

	$link = 'db_link';
	global ${$link};

	//echo "Qry = " . $query . "<br>";
	/*if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
		error_log('QUERY ' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
	}*/
	if ($param_type != "" && is_array($param_type)) {
		$param_type = implode('', $param_type); // Join array elements with a string
		$a_params = array_merge([$param_type], $param_data); // Merge two arrays
	}
	$resultnew = ${$link}->prepare($query) or tep_db_error($query, mysqli_errno(${$link}), mysqli_error(${$link}));
	if ($param_type) {
		$resultnew->bind_param($param_type, ...$param_data);
	}


	if (!$resultnew->execute())
		echo "Execute failed: (" . $resultnew->errno . ") " . $resultnew->error;

	$result = get_result_new($resultnew);

	/*if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
		$result_error = mysqli_error(${$link});
		//error_log('RESULT ' . $result . ' ' . $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
		error_log('RESULT '. $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
	}
	*/
	return $result;
}

/**
 * @param array<mixed> $db_query
 * @return int
 */
function tep_db_num_rows(array $db_query): int
{
	return sizeof($db_query);
}

function tep_db_close(string $link = 'db_link'): bool
{
	global ${$link};

	//return mysql_close($$link);
	return mysqli_close(${$link});
}

function tep_db_error(string $query, int $errno, string $error): never
{
	die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
}

function tep_db_insert_id(): int|string
{
	$link = 'db_link';
	global ${$link};
	return mysqli_insert_id(${$link});
}

function FixString(?string $strtofix): string
{ //THIS FUNCTION ESCAPES SPECIAL CHARACTERS FOR INSERTING INTO SQL
	$strtofix = addslashes($strtofix);
	$strtofix = str_replace("<", "&#60;", $strtofix);
	$strtofix = str_replace("'", "&#39;", $strtofix);
	$strtofix = preg_replace("/(\n)/", "<br>", $strtofix);
	return $strtofix;
} //end FixString

// // Encryption and Decryption
function encryptstr(string $encryptValue): string|false
{
	$ciphering = "AES-128-CTR";
	$iv_length = openssl_cipher_iv_length($ciphering);
	$options = 0;
	$cryption_iv = '1234567891011121';
	$cryption_key = "U1C!2B3l4@o5o#6p7";

	$encryption = openssl_encrypt(
		$encryptValue,
		$ciphering,
		$cryption_key,
		$options,
		$cryption_iv
	);

	return $encryption;
}

function decryptstr(string $decryptValue): string|false
{

	$ciphering = "AES-128-CTR";
	$iv_length = openssl_cipher_iv_length($ciphering);
	$options = 0;
	$cryption_iv = '1234567891011121';
	$cryption_key = "U1C!2B3l4@o5o#6p7";

	$decryption = openssl_decrypt(
		$decryptValue,
		$ciphering,
		$cryption_key,
		$options,
		$cryption_iv
	);

	return $decryption;
}
function encrypt_url(string $encryptValue): string|false
{
	$ciphering = "AES-128-CTR";
	$iv_length = openssl_cipher_iv_length($ciphering);
	$options = 0;
	$cryption_iv = '1234567891045679';
	$cryption_key = "*UcB!278#sup&82";

	$encryption = openssl_encrypt(
		$encryptValue,
		$ciphering,
		$cryption_key,
		$options,
		$cryption_iv
	);

	return base64_encode($encryption);
}

function decrypt_url(string $decryptValue): string|false
{

	if (is_numeric($decryptValue)){
		$decryption = $decryptValue;
	}else{
	
		$ciphering = "AES-128-CTR";
		$iv_length = openssl_cipher_iv_length($ciphering);
		$options = 0;
		$cryption_iv = '1234567891045679';
		$cryption_key = "*UcB!278#sup&82";

		$decodedData = base64_decode($decryptValue);

		//$decryption = openssl_decrypt ($decryptValue, $ciphering, $cryption_key, $options, $cryption_iv);
		$decryption = openssl_decrypt($decodedData, $ciphering, $cryption_key, $options, $cryption_iv);
	}

	return $decryption;
}

function redirect(string $a): void
{
	if (!headers_sent()) {
		header('Location: ' . $a);
		exit;
	} else {
		echo "<script type=\"text/javascript\">";
		echo "window.location.href=\"" . $a . "\";";
		echo "</script>";
		echo "<noscript>";
		echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $a . "\" />";
		echo "</noscript>";
		exit;
	}
}

function get_nickname_val(?string $warehouse_name, ?int $b2bid): ?string
{
	$nickname = "";
	if ($b2bid > 0) {
		db_b2b();
		$sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $b2bid;
		$result_comp = db_query($sql);
		while ($row_comp = array_shift($result_comp)) {
			if ($row_comp["nickname"] != "") {
				$nickname = $row_comp["nickname"];
			} else {
				$tmppos_1 = strpos($row_comp["company"], "-");
				if ($tmppos_1 != false) {
					$nickname = $row_comp["company"];
				} else {
					if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
						$nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
					} else {
						$nickname = $row_comp["company"];
					}
				}
			}
		}
		db();
	} else {
		$nickname = $warehouse_name;
	}

	return $nickname;
}
function encrypt_password(string|int|float $txt): string
{
	//$key = "1sw54@$sa$offj";
	$key = "1sw54@dsaoffj";
	$ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
	$iv = openssl_random_pseudo_bytes($ivlen);
	$ciphertext_raw = openssl_encrypt($txt, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
	$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
	$ciphertext = base64_encode($iv . $hmac . $ciphertext_raw);
	return $ciphertext;
}

function decrypt_password(string|int|float $txt): string
{
	//$key = "1sw54@$sa$offj";
	$key = "1sw54@dsaoffj";
	$c = base64_decode($txt);
	$ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
	$iv = substr($c, 0, $ivlen);
	$hmac = substr($c, $ivlen, $sha2len = 32);
	$ciphertext_raw = substr($c, $ivlen + $sha2len);
	$original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
	$calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
	if (hash_equals($hmac, $calcmac)) // timing attack safe comparison
	{
		return $original_plaintext;
	} else {
		return "";
	}
}

function get_territory(?string $state_name): string
{
	$territory = "";
	$canada_east = array('NB', 'NF', 'NS', 'ON', 'PE', 'QC');
	$east = array('ME', 'NH', 'VT', 'MA', 'RI', 'CT', 'NY', 'PA', 'MD', 'VA', 'WV');
	$south = array('NC', 'SC', 'GA', 'AL', 'MS', 'TN', 'FL');
	$midwest = array('MI', 'OH', 'IN', 'KY');
	$north_central = array('ND', 'SD', 'NE', 'MN', 'IA', 'IL', 'WI');
	$south_central = array('LA', 'AR', 'MO', 'TX', 'OK', 'KS', 'CO', 'NM');
	$canada_west = array('AB', 'BC', 'MB', 'NT', 'NU', 'SK', 'YT');
	$pacific_northwest = array('WA', 'OR', 'ID', 'MT', 'WY', 'AK');
	$west = array('CA', 'NV', 'UT', 'AZ', 'HI');
	//$canada=array();
	$mexico = array('AG', 'BS', 'CH', 'CL', 'CM', 'CO', 'CS', 'DF', 'DG', 'GR', 'GT', 'HG', 'JA', 'ME', 'MI', 'MO', 'NA', 'NL', 'OA', 'PB', 'QE', 'QR', 'SI', 'SL', 'SO', 'TB', 'TL', 'TM', 'VE', 'ZA');
	$territory_sort = 99;
	if (in_array($state_name, $canada_east, TRUE)) {
		$territory = "Canada East";
		$territory_sort = 1;
	} elseif (in_array($state_name, $east, TRUE)) {
		$territory = "East";
		$territory_sort = 2;
	} elseif (in_array($state_name, $south, TRUE)) {
		$territory = "South";
		$territory_sort = 3;
	} elseif (in_array($state_name, $midwest, TRUE)) {
		$territory = "Midwest";
		$territory_sort = 4;
	} else if (in_array($state_name, $north_central, TRUE)) {
		$territory = "North Central";
		$territory_sort = 5;
	} elseif (in_array($state_name, $south_central, TRUE)) {
		$territory = "South Central";
		$territory_sort = 6;
	} elseif (in_array($state_name, $canada_west, TRUE)) {
		$territory = "Canada West";
		$territory_sort = 7;
	} elseif (in_array($state_name, $pacific_northwest, TRUE)) {
		$territory = " Pacific Northwest";
		$territory_sort = 8;
	} elseif (in_array($state_name, $west, TRUE)) {
		$territory = "West";
		$territory_sort = 9;
	}
	/*elseif(!empty($canada) && in_array($state_name, $canada, TRUE))
	{ 
		$territory="Canada";
		$territory_sort=10;
	}*/ elseif (in_array($state_name, $mexico, TRUE)) {
		$territory = "Mexico";
		$territory_sort = 11;
	}

	return $territory;
}
function get_loop_box_id(string|int $b2b_id): string
{
	/////////////////////////////////////////// GET INITIALS FROM ID
	$dt_so = "SELECT * FROM loop_boxes WHERE b2b_id = " . $b2b_id;
	db();
	$dt_res_so = db_query($dt_so);
	$box_id = "";
	while ($so_row = array_shift($dt_res_so)) {
		if ($so_row["id"] > 0)
			$box_id = $so_row["id"];
	}
	return $box_id;
}
function get_b2b_box_id(int|string $loop_id): string
{
	$dt_so = "SELECT * FROM loop_boxes WHERE id = " . $loop_id;
	db();
	$box_id = "";
	$dt_res_so = db_query($dt_so);
	while ($so_row = array_shift($dt_res_so)) {
		$box_id =  $so_row["b2b_id"];
	}
	return $box_id;
}

function getnickname(string $warehouse_name, int $b2bid): string
{
	$nickname = "";
	if ($b2bid > 0) {
		$sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $b2bid;
		db_b2b();
		$result_comp = db_query($sql);
		while ($row_comp = array_shift($result_comp)) {
			if ($row_comp["nickname"] != "") {
				$nickname = $row_comp["nickname"];
			} else {
				$tmppos_1 = strpos($row_comp["company"], "-");
				if ($tmppos_1 != false) {
					$nickname = $row_comp["company"];
				} else {
					if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
						$nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
					} else {
						$nickname = $row_comp["company"];
					}
				}
			}
		}
	} else {
		$nickname = $warehouse_name;
	}
	if ($nickname == "") {
		$nickname = "Blank";
	}
	return $nickname;
}

function get_b2bEcomm_boxType_BasicDetails(string $box_type, int $returnVal): string
{
	$boxid_text = "";
	$palletUnitPr = "";
	$qrtrUnitPr = "";
	$halfUnitPr = "";
	$fullUnitPr = "";
	$idTitle = "";
	$pgTitle = "";
	$browserTitle = "";
	$rowb2b = array('onlineDollar' => 0, 'onlineCents' => 0);
	if (in_array(strtolower(trim($box_type)), array_map('strtolower', array("Gaylord", "GaylordUCB", "Loop", "PresoldGaylord")))) {
		$browserTitle 	= "Buy Gaylord Totes";
		$pgTitle		= "Buy Gaylord Totes";
		$idTitle		= "Gaylord ID";
		$fullUnitPr 	= $rowb2b["onlineDollar"] + $rowb2b["onlineCents"];
		$halfUnitPr 	= $fullUnitPr + 1.50;
		$qrtrUnitPr 	= $fullUnitPr + 3.00;
		$palletUnitPr 	= $fullUnitPr + 6.00;
		$boxid_text		= "Gaylord";
	} elseif (in_array(strtolower(trim($box_type)), array_map('strtolower', array("Medium", "Large", "Xlarge", "LoopShipping", "Box", "Boxnonucb", "Presold")))) {
		$browserTitle 	= "Buy Shipping Boxes";
		$pgTitle		= "Buy Shipping Boxes";
		$idTitle		= "Shipping Box ID";
		$fullUnitPr 	= $rowb2b["onlineDollar"] + $rowb2b["onlineCents"];
		$halfUnitPr 	= $fullUnitPr + 0.25;
		$qrtrUnitPr 	= $fullUnitPr + 0.50;
		$palletUnitPr	= $fullUnitPr + 1.00;
		$boxid_text		= "Shipping Box";
	} elseif (in_array(strtolower(trim($box_type)), array_map('strtolower', array("SupersackUCB", "SupersacknonUCB")))) {
		$browserTitle 	= "Buy Super Sacks";
		$pgTitle		= "Buy Super Sacks";
		$idTitle		= "Super Sack ID";
		$fullUnitPr 	= $rowb2b["onlineDollar"] + $rowb2b["onlineCents"];
		$halfUnitPr 	= $fullUnitPr + 0.50;
		$qrtrUnitPr 	= $fullUnitPr + 1.00;
		$palletUnitPr	= $fullUnitPr + 2.00;
		$boxid_text		= "Super Sack";
	} elseif (in_array(strtolower(trim($box_type)), array_map('strtolower', array("PalletsUCB", "PalletsnonUCB")))) {
		$browserTitle 	= "Buy Pallets";
		$pgTitle 		= "Buy Pallets";
		$idTitle		= "Pallet ID";
		$fullUnitPr 	= $rowb2b["onlineDollar"] + $rowb2b["onlineCents"];
		$halfUnitPr 	= $fullUnitPr + 1.00;
		$qrtrUnitPr 	= $fullUnitPr + 2.00;
		$palletUnitPr	= $fullUnitPr + 4.00;
		$boxid_text		= "Pallet";
	} elseif (in_array(strtolower(trim($box_type)), array_map('strtolower', array("Recycling", "DrumBarrelUCB", "DrumBarrelnonUCB", "Waste-to-Energy", "Other", " "))) || (strtolower(trim($box_type)) == strtolower(trim('Recycling')))) {
		$browserTitle 	= "Buy Items";
		$pgTitle 		= "Buy Items";
		$idTitle		= "Item ID";
		$fullUnitPr 	= $rowb2b["onlineDollar"] + $rowb2b["onlineCents"];
		$halfUnitPr 	= $fullUnitPr;
		$qrtrUnitPr 	= $fullUnitPr;
		$palletUnitPr	= $fullUnitPr;
		$boxid_text		= "Item";
	}
	if ($returnVal == 1) {
		return $browserTitle;
	} else if ($returnVal == 2) {
		return $pgTitle;
	} else if ($returnVal == 3) {
		return $idTitle;
	} elseif ($returnVal == 4) {
		return $fullUnitPr;
	} else if ($returnVal == 5) {
		return $halfUnitPr;
	} else if ($returnVal == 6) {
		return $qrtrUnitPr;
	} else if ($returnVal == 7) {
		return $palletUnitPr;
	} else if ($returnVal == 8) {
		return $boxid_text;
	} else {
		return "";
	}
}

function get_lead_time_v3(string|int $g_timing, string|null $box_id_list, string|null $inventory_lead_time, string $g_timing_enter_dt): string
{
	//To get the Shipsinweek
	$no_of_loads = 0;
	$shipsinweek = "";
	$to_show_rec = "";
	$total_no_of_loads = 0;
	if ($g_timing == 4) {
		$to_show_rec = "";
		$next_2_week_date = date("Y-m-d", strtotime("+2 week"));
		$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id in (" . $box_id_list . ") and inactive_delete_flg = 0 
		and (load_available_date <= '" . $next_2_week_date . "') order by load_available_date";
		//echo $dt_view_qry . "<br>";
		db();
		$dt_view_res_box = db_query($dt_view_qry);
		while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
			if ($dt_view_res_box_data["trans_rec_id"] == 0) {
				$no_of_loads = $no_of_loads + 1;
				$to_show_rec = "y";
			}
			$total_no_of_loads = $total_no_of_loads + 1;

			if ($no_of_loads == 1) {
				$now_date = time();
				$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
				$datediff = $next_load_date - $now_date;
				$shipsinweek_org = round($datediff / (60 * 60 * 24));
				//echo $inventory_lead_time . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
				if ($inventory_lead_time > $shipsinweek_org) {
					$shipsinweekval = $inventory_lead_time;
				} else {
					$shipsinweekval = $shipsinweek_org;
				}
				if ($shipsinweekval == 0) {
					$shipsinweekval = 1;
				}
				if ($shipsinweekval >= 10) {
					$shipsinweek = round($shipsinweekval / 7) . " weeks";
				}
				if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
					$shipsinweek = $shipsinweekval . " days";
				}
				if ($shipsinweekval == 1) {
					$shipsinweek = $shipsinweekval . " day";
				}
			}
		}
	}

	//Can ship next month a date range of the 1st day of next month to last day of next month 
	if ($g_timing == 7) {
		$to_show_rec = "";
		$next_month_date = date("Y-m-t");
		$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id in (" . $box_id_list . ") and inactive_delete_flg = 0 
		and (load_available_date <= '" . $next_month_date . "')
		order by load_available_date";
		//echo $dt_view_qry . "<br>";
		db();
		$dt_view_res_box = db_query($dt_view_qry);
		while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
			if ($dt_view_res_box_data["trans_rec_id"] == 0) {
				$no_of_loads = $no_of_loads + 1;
				$to_show_rec = "y";
			}
			$total_no_of_loads = $total_no_of_loads + 1;

			if ($no_of_loads == 1) {
				$now_date = time();
				$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
				$datediff = $next_load_date - $now_date;
				$shipsinweek_org = round($datediff / (60 * 60 * 24));
				//echo $inventory_lead_time . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
				if ($inventory_lead_time > $shipsinweek_org) {
					$shipsinweekval = $inventory_lead_time;
				} else {
					$shipsinweekval = $shipsinweek_org;
				}
				if ($shipsinweekval == 0) {
					$shipsinweekval = 1;
				}
				if ($shipsinweekval >= 10) {
					$shipsinweek = round($shipsinweekval / 7) . " weeks";
				}
				if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
					$shipsinweek = $shipsinweekval . " days";
				}
				if ($shipsinweekval == 1) {
					$shipsinweek = $shipsinweekval . " day";
				}
			}
		}
		//echo "in step 7 " . $to_show_rec . "<br>";	
	}

	//Can ship next month
	if ($g_timing == 8) {
		$to_show_rec = "";
		$next_month_date = date("Y-m-t", strtotime("+1 month"));
		$dt_view_qry = "SELECT load_available_date, trans_rec_id  from loop_next_load_available_history where inv_loop_id in (" . $box_id_list . ") and inactive_delete_flg = 0 
		and (load_available_date between '" . date("Y-m-1", strtotime("+1 month")) . "' and '" . $next_month_date . "') order by load_available_date";
		//echo $dt_view_qry . "<br>";
		db();
		$dt_view_res_box = db_query($dt_view_qry);
		while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
			if ($dt_view_res_box_data["trans_rec_id"] == 0) {
				$no_of_loads = $no_of_loads + 1;
				$to_show_rec = "y";
			}
			$total_no_of_loads = $total_no_of_loads + 1;

			if ($no_of_loads == 1) {
				$now_date = time();
				$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
				$datediff = $next_load_date - $now_date;
				$shipsinweek_org = round($datediff / (60 * 60 * 24));
				//echo $inventory_lead_time . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
				if ($inventory_lead_time > $shipsinweek_org) {
					$shipsinweekval = $inventory_lead_time;
				} else {
					$shipsinweekval = $shipsinweek_org;
				}
				if ($shipsinweekval == 0) {
					$shipsinweekval = 1;
				}
				if ($shipsinweekval >= 10) {
					$shipsinweek = round($shipsinweekval / 7) . " weeks";
				}
				if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
					$shipsinweek = $shipsinweekval . " days";
				}
				if ($shipsinweekval == 1) {
					$shipsinweek = $shipsinweekval . " day";
				}
			}
		}
	}

	//Enter ship by date = Take user input of 1 date
	if ($g_timing == 9 && $g_timing_enter_dt != '') {
		$to_show_rec = "";
		$next_month_date = date("Y-m-d", strtotime($g_timing_enter_dt));
		$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id in (" . $box_id_list . ") and inactive_delete_flg = 0 
		and load_available_date <= '" . $next_month_date . "' order by load_available_date";
		//echo $dt_view_qry . "<br>";
		db();
		$dt_view_res_box = db_query($dt_view_qry);
		while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
			if ($dt_view_res_box_data["trans_rec_id"] == 0) {
				$no_of_loads = $no_of_loads + 1;
				$to_show_rec = "y";
			}
			$total_no_of_loads = $total_no_of_loads + 1;

			if ($no_of_loads == 1) {
				$now_date = time();
				$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
				$datediff = $next_load_date - $now_date;
				$shipsinweek_org = round($datediff / (60 * 60 * 24));
				//echo $inventory_lead_time . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
				if ($inventory_lead_time > $shipsinweek_org) {
					$shipsinweekval = $inventory_lead_time;
				} else {
					$shipsinweekval = $shipsinweek_org;
				}
				if ($shipsinweekval == 0) {
					$shipsinweekval = 1;
				}
				if ($shipsinweekval >= 10) {
					$shipsinweek = round($shipsinweekval / 7) . " weeks";
				}
				if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
					$shipsinweek = $shipsinweekval . " days";
				}
				if ($shipsinweekval == 1) {
					$shipsinweek = $shipsinweekval . " day";
				}
			}
		}
	}

	if ($g_timing == 6) {
		$to_show_rec = "";
		$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id in (" . $box_id_list . ") and inactive_delete_flg = 0 
		order by load_available_date";
		//echo $dt_view_qry . "<br>";
		db();
		$dt_view_res_box = db_query($dt_view_qry);
		while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
			if ($dt_view_res_box_data["trans_rec_id"] == 0) {
				$no_of_loads = $no_of_loads + 1;
				$to_show_rec = "y";
			}
			$total_no_of_loads = $total_no_of_loads + 1;

			if ($no_of_loads == 1) {
				$now_date = time();
				$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
				$datediff = $next_load_date - $now_date;
				$shipsinweek_org = round($datediff / (60 * 60 * 24));
				if (($inventory_lead_time > $shipsinweek_org) || ($shipsinweek_org < 0)) {
					$shipsinweekval = $inventory_lead_time;
				} else {
					$shipsinweekval = $shipsinweek_org;
				}

				if ($shipsinweekval == 0) {
					$shipsinweekval = 1;
				}
				//echo $inv["ID"] . " | " . $inventory_lead_time . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . "|" . $shipsinweekval . " <br>";

				if ($shipsinweekval >= 10) {
					$shipsinweek = round($shipsinweekval / 7) . " weeks";
				}
				if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
					$shipsinweek = $shipsinweekval . " days";
				}
				if ($shipsinweekval == 1) {
					$shipsinweek = $shipsinweekval . " day";
				}
			}
		}
	}

	if ($g_timing == 5) {
		$next_2_week_date = date("Y-m-d", strtotime("+3 day"));
		$to_show_rec = "";
		$dt_view_qry = "SELECT load_available_date, trans_rec_id  from loop_next_load_available_history where inv_loop_id in (" . $box_id_list . ") and inactive_delete_flg = 0 
		and (load_available_date <= '" . $next_2_week_date . "') order by load_available_date";
		//echo $dt_view_qry . "<br>";
		db();
		$dt_view_res_box = db_query($dt_view_qry);
		while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
			if ($dt_view_res_box_data["trans_rec_id"] == 0) {
				$no_of_loads = $no_of_loads + 1;
				$to_show_rec = "y";
			}
			$total_no_of_loads = $total_no_of_loads + 1;

			if ($no_of_loads == 1) {
				$now_date = time();
				$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
				$datediff = $next_load_date - $now_date;
				$shipsinweek_org = round($datediff / (60 * 60 * 24));
				//echo $inventory_lead_time . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
				if ($inventory_lead_time > $shipsinweek_org) {
					$shipsinweekval = $inventory_lead_time;
				} else {
					$shipsinweekval = $shipsinweek_org;
				}
				if ($shipsinweekval == 0) {
					$shipsinweekval = 1;
				}
				if ($shipsinweekval >= 10) {
					$shipsinweek = round($shipsinweekval / 7) . " weeks";
				}
				if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
					$shipsinweek = $shipsinweekval . " days";
				}
				if ($shipsinweekval == 1) {
					$shipsinweek = $shipsinweekval . " day";
				}
			}
		}
	}
	return $shipsinweek;
}
function number_of_working_dates(string $from, int $days): string
{
	$workingDays = [1, 2, 3, 4, 5]; # date format = N (1 = Monday, ...)
	$holidayDays = ['*-12-25', '*-01-01', '2013-12-24', '2013-12-25']; # variable and fixed holidays

	$ret_dates = "";
	$from = new DateTime($from);
	while ($days) {
		$from->modify('+1 day');

		if (!in_array($from->format('N'), $workingDays)) continue;
		if (in_array($from->format('Y-m-d'), $holidayDays)) continue;
		if (in_array($from->format('*-m-d'), $holidayDays)) continue;

		$ret_dates = $from->format('Y-m-d');
		$days--;
	}
	return $ret_dates;
}
/*B2B ECOMM SET BROWSER TITLE, PAGE TITLE, ID TITLE & UNIT PRICE ENDS */

function sendemail_php_function(array|null $files, string $path, string $mailto, string $scc, string $sbcc, string $from_mail, string $from_name, string $replyto, string $subject, string $message): string
{

	// Check if the class 'PHPMailerAutoload' exists
	if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
		// If not, include the file
		//require '../loops/phpmailer/PHPMailerAutoload.php';

		require '../loops/phpmailer/src/Exception.php';
		require '../loops/phpmailer/src/PHPMailer.php';
		require '../loops/phpmailer/src/SMTP.php';

		//use PHPMailer\PHPMailer\PHPMailer;
		//use PHPMailer\PHPMailer\Exception;
	}

	//Code to send mail
	//$mail = new PHPMailer(true);
	$mail = new \PHPMailer\PHPMailer\PHPMailer(true);
	$mail->isSMTP();
	$mail->Host = 'smtp.office365.com';
	//$mail->Host = '62.231.109.208.host.secureserver.net';
	$mail->Port       = 587;
	$mail->SMTPSecure = 'tls';
	$mail->SMTPAuth   = true;

	if ($from_mail == "UCBZWEmail@UCBZeroWaste.com"){
		$mail->Username = "UCBZWEmail@UCBZeroWaste.com";
		$mail->Password = "#UCBgrn4652";
	}else{
		$mail->Username = "ucbemail@usedcardboardboxes.com";
		$mail->Password = "WH@ToGap$222";
	}
	
	$mail->SetFrom($from_mail, $from_name);
	$mail->addReplyTo($replyto, $from_name);
	//
	if ($mailto != "") {
		$cc_flg = "";
		$tmppos_1 = strpos($mailto, ",");
		if ($tmppos_1 != false) {
			$cc_ids = explode(",", $mailto);

			foreach ($cc_ids as $cc_ids_tmp) {
				if ($cc_ids_tmp != "") {
					$mail->addAddress($cc_ids_tmp);
					$cc_flg = "y";
				}
			}
		}

		$tmppos_1 = strpos($mailto, ";");
		if ($tmppos_1 != false) {
			$cc_flg = "";
			$cc_ids1 = explode(";", $mailto);

			foreach ($cc_ids1 as $cc_ids_tmp2) {
				if ($cc_ids_tmp2 != "") {
					$mail->addAddress($cc_ids_tmp2);
					$cc_flg = "y";
				}
			}
		}

		if ($cc_flg == "") {
			$mail->addAddress($mailto, $mailto);
		}
	}

	if ($sbcc != "") {
		$cc_flg = "";

		$tmppos_1 = strpos($sbcc, ",");
		if ($tmppos_1 != false) {
			$cc_ids = explode(",", $sbcc);
			foreach ($cc_ids as $cc_ids_tmp) {
				if ($cc_ids_tmp != "") {
					$mail->AddBCC($cc_ids_tmp);
					$cc_flg = "y";
				}
			}
		}

		$tmppos_1 = strpos($sbcc, ";");
		if ($tmppos_1 != false) {
			$cc_flg = "";
			$cc_ids1 = explode(";", $sbcc);
			foreach ($cc_ids1 as $cc_ids_tmp2) {
				if ($cc_ids_tmp2 != "") {
					$mail->AddBCC($cc_ids_tmp2);
					$cc_flg = "y";
				}
			}
		}

		if ($cc_flg == "") {
			$mail->AddBCC($sbcc, $sbcc);
		}
	}

	if ($scc != "") {
		$cc_flg = "";
		$tmppos_1 = strpos($scc, ",");
		if ($tmppos_1 != false) {
			$cc_ids = explode(",", $scc);

			foreach ($cc_ids as $cc_ids_tmp) {
				if ($cc_ids_tmp != "") {
					$mail->AddCC($cc_ids_tmp);
					$cc_flg = "y";
				}
			}
		}

		$tmppos_1 = strpos($scc, ";");
		if ($tmppos_1 != false) {
			$cc_flg = "";
			$cc_ids1 = explode(";", $scc);
			foreach ($cc_ids1 as $cc_ids_tmp2) {
				if ($cc_ids_tmp2 != "") {
					$mail->AddCC($cc_ids_tmp2);

					$cc_flg = "y";
				}
			}
		}

		if ($cc_flg == "") {
			$mail->AddCC($scc, $scc);
		}
	}
	if ($files != "null") {
		for ($x = 0; $x < count($files); $x++) {
			$mail->addAttachment($path . $files[$x]);
		}
	}

	$mail->IsHTML(true);
	$mail->Encoding = 'base64';
	$mail->CharSet = "UTF-8";
	$mail->Subject = $subject;
	$mail->Body    = $message;
	$mail->AltBody = $message;
	if (!$mail->send()) {
		return 'emailerror';
	} else {
		return 'emailsend';
	}
}




function send_transactionlog_email($warehouseid, $transid, $rec_type, $buyer_view)
{
	db();
	$b2bid = 0;
	$sql = "SELECT b2bid, warehouse_name from loop_warehouse where id = ?";
	$result_comp = db_query($sql, array("i"), array($warehouseid));
	while ($row_comp = array_shift($result_comp)) {
		$b2bid = $row_comp["b2bid"];
		$notes_company = get_nickname_val($row_comp["warehouse_name"], $b2bid);
	}

	$po_employee = "";
	$sql = "SELECT po_employee from loop_transaction_buyer where id = ?";
	$result_comp = db_query($sql, array("i"), array($transid));
	while ($row_comp = array_shift($result_comp)) {
		$po_employee = $row_comp["po_employee"];
	}

	if ($po_employee == "") {
		db_b2b();
		$sql = "SELECT assignedto from companyInfo where ID = ?";
		$result_comp = db_query($sql, array("i"), array($b2bid));
		while ($row_comp = array_shift($result_comp)) {
			$assignedto = $row_comp["assignedto"];
		}

		$acc_owner_email = "";
		$sql = "SELECT email FROM employees WHERE employees.status='Active' and employeeID = ?";
		$result_comp = db_query($sql, array("i"), array($assignedto));
		while ($row_comp = array_shift($result_comp)) {
			$acc_owner_email = $row_comp["email"];
		}
	} else {
		db();
		$acc_owner_email = "";
		$sql = "SELECT email FROM loop_employees WHERE status='Active' and initials = ?";
		$result_comp = db_query($sql, array("s"), array($po_employee));
		while ($row_comp = array_shift($result_comp)) {
			$acc_owner_email = $row_comp["email"];
		}
	}

	if ($acc_owner_email != "") {
		//
		db();
		$sql = "SELECT message,date,loop_employees.name FROM loop_transaction_notes";
		$sql .= " INNER JOIN loop_employees ON loop_transaction_notes.employee_id = loop_employees.id";
		$sql .= " WHERE loop_transaction_notes.company_id = '" .  $warehouseid . "' AND";
		$sql .= " loop_transaction_notes.rec_id = '" . $transid . "' order by loop_transaction_notes.date DESC";
		//echo $sql."<br><br>";
		$result = db_query($sql);

		$tdno = 0;
		$str_email = "";
		$str_email = "<html><head></head><body bgcolor='#E7F5C2'><table border=0 align='center' cellpadding='0' width='700px' bgcolor='#E7F5C2'><tr><td ><p align='center'><img width='650' height='166' src='https://loops.usedcardboardboxes.com/images/ucb-banner1.jpg'></p></td></tr><tr><td><br>";
		$str_email .= "<table width='700px' cellSpacing='1' cellPadding='3'><tr><th colspan=3>TRANSACTION LOG UPDATES</th></tr>";

		$str_email .= "<tr><td bgColor='#98bcdf' colspan=3><font face='Arial, Helvetica, sans-serif' size='1'><strong>Company Name: <a href='https://loops.usedcardboardboxes.com/viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $warehouseid . "&rec_type=" . $rec_type . "&proc=View&searchcrit=&id=" . $warehouseid . "&rec_id=" . $transid . "&display=" . $buyer_view . "'>" . $notes_company . "</a></strong></font></td></tr>";

		$str_email .= "<tr><td bgColor='#ABC5DF'><font face='Arial, Helvetica, sans-serif' size='1'><strong>Date/Time<strong></font></td><td bgColor='#ABC5DF'><font face='Arial, Helvetica, sans-serif' size='1'><strong>Employee</strong></font></td><td bgColor='#ABC5DF'><font face='Arial, Helvetica, sans-serif' size='1'><strong>Notes</strong></font></td></tr>";
		//
		while ($myrowsel = array_shift($result)) {

			$the_log_date = $myrowsel["date"];
			$yearz = substr("$the_log_date", 0, 4);
			$monthz = substr("$the_log_date", 4, 2);
			$dayz = substr("$the_log_date", 6, 2);

			$tdno = $tdno + 1;
			if ($tdno == 1) {
				$tdbgcolor = "#d1cfce";
			} else {
				$tdbgcolor = "#e4e4e4";
			}
			//
			//$str_email = "<b>Transaction Log Update</b>:<br>";
			$str_email .= "<tr><td bgColor='" . $tdbgcolor . "'><font face='Arial, Helvetica, sans-serif' size='1'>" . $the_log_date . "</font></td><td bgColor='" . $tdbgcolor . "'><font face='Arial, Helvetica, sans-serif' size='1'>" . $myrowsel['name'] . "</font></td>";

			$str_email .= "<td bgColor='" . $tdbgcolor . "'><font face='Arial, Helvetica, sans-serif' size='1'>" . $myrowsel['message'] . "</font></td><tr><tr><td height='7px' colspan=4></td></tr>";
		}
		$str_email .= "</table></td></tr><tr><td><p align='center'><img width='650' height='87' src='http://loops.usedcardboardboxes.com/images/ucb-footer1.jpg'></p></td></tr><tr><td width='23'><p>&nbsp; </p></td><td width='682'><p>&nbsp; </p></td></tr></table></body></html>";

		$emlstatus = sendemail_php_function([], '', $acc_owner_email, "", "", "ucbemail@usedcardboardboxes.com", "Operations Usedcardboardboxes", "operations@usedcardboardboxes.com", "Transaction Log Update for " . $notes_company . " - " . $transid, $str_email);
	}
}


/**
 * @param string[] $files
 */
function sendemail_attachment(array $files, string $path, string $mailto, string $scc, string $sbcc, string $from_mail, string $from_name, string $replyto, string $subject, string $message): string
{
	$uid = md5(uniqid((string)time()));
	$header = "From: " . $from_name . " <" . $from_mail . ">\r\n";
	$header .= "Cc: " . $scc . "\r\n";
	$header .= "Bcc: " . $sbcc . "\r\n";

	if (count($files) > 0) {
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\r\n";
		$header .= "This is a multi-part message in MIME format.\r\n";
		$header .= "--" . $uid . "\r\n";
	}

	$header .= "Content-type:text/html; charset=iso-8859-1\r\n";
	$header .= "Content-Transfer-Encoding: 7bit\r\n";
	$header .= $message . "\r\n";

	for ($x = 0; $x < count($files); $x++) {
		$file_size = filesize($path . $files[$x]);
		$handle = fopen($path . $files[$x], "rb");
		$content = fread($handle, $file_size);
		fclose($handle);
		$content = chunk_split(base64_encode($content));
		$header .= "--" . $uid . "\r\n";
		$header .= "Content-Type: application/octet-stream; name=\"" . $files[$x] . "\"\r\n";
		$header .= "Content-Transfer-Encoding: base64\r\n";

		$header .= "Content-Disposition: attachment; filename=\"" . $files[$x] . "\"\r\n";

		$header .= $content . "\r\n";
		$header .= "\n";
	}
	if (count($files) > 0) {
		$header .= "--" . $uid . "--";
	}

	if (mail($mailto, $subject, "", $header)) {
		return "emailsend";
	} else {
		return "emailerror";
	}
}

//Added ny Amarendra 
function ThrowError(string $err_type, string $err_descr, string $dbname, callable $db_connection_function): string
{ //THIS FUNCTION PROVIDES ERROR DESCRIPTIONS
	switch ($err_type) {
		case "9991SQLresultcount":
		case "9994SQL":
			$conn = $db_connection_function();
			return "<BR><B>ERROR:  --> " . mysqli_error($conn) . "</b>
						<BR>LOCATION: $err_type 
						<BR>DESCRIPTION: Unable to execute SQL statement.
						<BR>Please Check the Following:
						<BR>- Ensure the Table(s) Exists in database $dbname
						<BR>- Ensure All Fields Exist in the Table(s)
						
						<BR>- SQL STATEMENT: $err_descr<BR>
						<BR>- MYSQL ERROR: " . mysqli_error($conn);
			break;
		default:
			return "UNKNOWN ERROR TYPE: $err_type 
						<BR>DESCR: $err_descr<BR>";
			break;
	}
}

function getLeadTime(int $loopId, int $quantity_availableValue_new, ?int $leadTimeInNumber): string
{
	$found_rec_inALT = "";
	$found_rec_inALT_dt = "";
	$found_rec_inALT_days = 0;
	$twoweek_days = "+ 14 Days";
	db();
	$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id = '" . $loopId . "' and inactive_delete_flg = 0 
	and (load_available_date <= '" . date("Y-m-d", strtotime($twoweek_days)) . "') and trans_rec_id = 0 order by load_available_date limit 1";

	$dt_view_res_box = db_query($dt_view_qry);
	while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
		$found_rec_inALT = "yes";
		$found_rec_inALT_dt = $dt_view_res_box_data["load_available_date"];

		$currentDate = new DateTime();
		$otherDate = new DateTime($found_rec_inALT_dt);
		if ($otherDate > $currentDate) {
			$interval = $currentDate->diff($otherDate);
			$found_rec_inALT_days = $interval->days;
			$found_rec_inALT_days = $found_rec_inALT_days + 1;
		}
	}

	$box_lead_time = "";
	if ($quantity_availableValue_new == 0) {
		$box_lead_time = "";
	} else {
		//echo "found_rec_inALT | " . $found_rec_inALT . " | " . $found_rec_inALT_days . " | " . $loopId . "<br>";

		if ($found_rec_inALT == "yes") {
			if ($found_rec_inALT_days > $leadTimeInNumber) {
				$leadTimeInNumber = $found_rec_inALT_days;
			}
		}

		if ($leadTimeInNumber == 1 || $leadTimeInNumber == 0) {
			$box_lead_time = "1 Day";
		} else {
			if ($leadTimeInNumber < 7) {
				$box_lead_time = $leadTimeInNumber . " Days";
			}
		}
		//
		if ($leadTimeInNumber >= 7) {
			$lead_timeWeek = round($leadTimeInNumber / 7);
			if ($lead_timeWeek == 1) {
				$box_lead_time = $lead_timeWeek . " Week";
			} else {
				$box_lead_time = $lead_timeWeek . " Weeks";
			}
		}
	}

	return $box_lead_time;
}


function getEstimatedNextLoad(int $loopId, int $warehouseId, mixed $nextLoadAvailableDate, ?int $leadTime, ?int $myrowLeadTime, ?int $txtafterPo, ?int $boxesPerTrailer, float $myrowExpectedLoadsPerMo, mixed $st_rowStatusKey, string $updateQryAction = 'no'): string
{
	//echo "<br /> function pg  -> ".$loopId." / ".$warehouseId." / ". $nextLoadAvailableDate." / ". $leadTime." / ". $myrowLeadTime." / ". $txtafterPo." / ". $boxesPerTrailer." / ". $myrowExpectedLoadsPerMo." / ". $st_rowStatusKey ;
	if ($warehouseId == 238 && ($nextLoadAvailableDate != "" && $nextLoadAvailableDate != "0000-00-00")){
		$now_date = time(); // or your date as well
		$next_load_date = strtotime($nextLoadAvailableDate);
		$datediff = $next_load_date - $now_date;
		$no_of_loaddays = floatval(round($datediff / (60 * 60 * 24))+1);
		
		if($no_of_loaddays < floatval($leadTime))
		{
			if(floatval($myrowLeadTime)>1)
			{
				$estimated_next_load= "<font color=green>" . $myrowLeadTime . " Days</font>";
			}
			else{
				$estimated_next_load= "<font color=green>" . $myrowLeadTime . " Day</font>";
			}
			
		}
		else{
			if ($no_of_loaddays == -0){
				$estimated_next_load= "<font color=green>0 Day</font>";
			}else{
				//commented on 2 dec 2021 as per teams chat with Zac $estimated_next_load= "<font color=green>" . $no_of_loaddays . " Days</font>";
				$estimated_next_load = $no_of_loaddays . " Days";
			}						
		}
	} else{			
		if ($txtafterPo >= $boxesPerTrailer) {
			//=IF(B4>0,"NOW",ROUNDUP(((((B4/R4)*-1)+1)/D4)*4,0))

			if (floatval($myrowLeadTime) == 0){
				$estimated_next_load= "<font color=green>Now</font>";
			}							

			if (floatval($myrowLeadTime) == 1){
				$estimated_next_load= "<font color=green>" . $myrowLeadTime . " Day</font>";
			}							
			if (floatval($myrowLeadTime) > 1){
				$estimated_next_load= "<font color=green>" . $myrowLeadTime . " Days</font>";
			}							
		}
		else{
			//echo "next_load_available_date - " . $txtafterPo . " - " . $boxesPerTrailer . " - " . $myrowExpectedLoadsPerMo .  "<br>";
			if ((floatval($myrowExpectedLoadsPerMo) <= 0) && (floatval($txtafterPo) < floatval($boxesPerTrailer))){
				$estimated_next_load= "<font color=red>Never (sell the " . $txtafterPo . ")</font>";
			}else{
				//echo "next_load_available_date - " . $txtafterPo . " - " . $boxesPerTrailer . " - " . $myrowExpectedLoadsPerMo .  "<br>";
				$estimated_next_load=ceil(((((floatval($txtafterPo)/floatval($boxesPerTrailer))*-1)+1)/floatval($myrowExpectedLoadsPerMo))*4)." Weeks";
			}
		}
	}	

	//echo "In step " . $estimated_next_load . " " . $txtafterPo . " " . $boxesPerTrailer . "<br>";
	if (floatval($txtafterPo) == 0 && floatval($myrowExpectedLoadsPerMo) == 0 ) {
		$estimated_next_load= "<font color=red>Ask Purch Rep</font>";
	}
	//

	/*$b2b_status = $myrow["b2b_status"];
	$b2bstatuscolor="";
	$st_query="select * from b2b_box_status where status_key='".$b2b_status."'";
	$st_res = db_query($st_query, db() );
	$st_row = array_shift($st_res);
	$b2bstatus_name=$st_row["box_status"];*******/
	if($st_rowStatusKey=="1.0" || $st_rowStatusKey=="1.1" || $st_rowStatusKey=="1.2"){
		$b2bstatuscolor="green";
	}
	elseif($st_rowStatusKey=="2.0" || $st_rowStatusKey=="2.1" || $st_rowStatusKey=="2.2"){
		$b2bstatuscolor="orange";
		$estimated_next_load= "<font color=red> Ask Purch Rep </font>";
	}
	
	if($updateQryAction == 'yes'){
		db();
		db_query("UPDATE loop_boxes SET buy_now_load_can_ship_in = '".$estimated_next_load."' WHERE id = '".$loopId."'");
		
		db_b2b();
		db_query("UPDATE inventory SET buy_now_load_can_ship_in = '".$estimated_next_load."' WHERE loops_id = '".$loopId."'");
	}
	
	return $estimated_next_load;
}

function make_insert_query(string $table_name, array $arr_data): string
{
	$fieldname = "";
	$fieldvalue = "";
	foreach ($arr_data as $fldname => $fldval) {
		$fieldname = ($fieldname == "") ? $fldname : $fieldname . ',' . $fldname;
		$fieldvalue = ($fieldvalue == "") ? "'" . formatdata($fldval) . "'" : $fieldvalue . ",'" . formatdata($fldval) . "'";
	}
	$query1 = "INSERT INTO " . $table_name . " ($fieldname) VALUES($fieldvalue)";
	return $query1;
}

// function formatdata(string $data): string|null
// {
// 	return addslashes(trim($data));
// }
function formatdata(?string $data): ?string
{
	if ($data === null) {
		return null;
	}
	return addslashes(trim($data));
}

function get_user_initials(int $id): string
{
	db_b2b();
	$sql = db_query("SELECT `initials` FROM `employees` WHERE `employeeID` = ? ", array("i"), array($id));
	$res = array_shift($sql);
	return $res['initials'];
}

function timestamp_to_date(string $d): string
{

	$da = explode(" ", $d);

	$dp = explode("-", $da[0]);

	return $dp[0] . "/" . $dp[1] . "/" . $dp[2];
}

function timestamp_to_date_usf(string $d): string
{

	$da = explode(" ", $d);

	$dp = explode("-", $da[0]);

	return $dp[1] . "/" . $dp[2] . "/" . $dp[0];
}


function timestamp_to_datetime(string $d): string
{

	$da = explode(" ", $d);
	$dp = explode("-", $da[0]);
	$dh = explode(":", $da[1]);

	$x = $dp[1] . "/" . $dp[2] . "/" . $dp[0];

	if ($dh[0] == 12) {
		$x = $x . " " . ($dh[0] - 0) . ":" . $dh[1] . "PM CT";
	} elseif ($dh[0] == 0) {
		$x = $x . " 12:" . $dh[1] . "AM CT";
	} elseif ($dh[0] > 12) {
		$x = $x . " " . ($dh[0] - 12) . ":" . $dh[1] . "PM CT";
	} else {
		$x = $x . " " . ($dh[0]) . ":" . $dh[1] . "AM CT";
	}

	return $x;
}

function dateDiff(string $start, string $end): string
{
	$start_ts = strtotime($start);
	$end_ts = strtotime($end);
	$diff = $start_ts - $end_ts;
	return number_format(abs($diff / 86400));
}

function date_diff_new(string $start, string $end = "NOW"): string
{
	$sdate = strtotime($start);
	$edate = strtotime($end);

	$time = $edate - $sdate;
	if ($time >= 0 && $time <= 59) {
		// Seconds
		$timeshift = $time . ' seconds ';
	} elseif ($time >= 60 && $time <= 3599) {
		// Minutes + Seconds
		$pmin = ($edate - $sdate) / 60;
		$premin = explode('.', $pmin);

		$presec = $pmin - $premin[0];
		$sec = $presec * 60;

		$timeshift = $premin[0] . ' min ' . round($sec, 0) . ' sec ';
	} elseif ($time >= 3600 && $time <= 86399) {
		// Hours + Minutes
		$phour = ($edate - $sdate) / 3600;
		$prehour = explode('.', $phour);

		$premin = $phour - $prehour[0];
		$min = explode('.', $premin * 60);

		$presec = '0.' . $min[1];
		$sec = $presec * 60;

		$timeshift = $prehour[0] . ' hrs ' . $min[0] . ' min ' . round($sec, 0) . ' sec ';
	} elseif ($time >= 86400) {
		// Days + Hours + Minutes
		$pday = ($edate - $sdate) / 86400;
		$preday = explode('.', $pday);

		$phour = $pday - $preday[0];
		$prehour = explode('.', $phour * 24);

		$premin = ($phour * 24) - $prehour[0];
		$min = explode('.', $premin * 60);

		$presec = '0.' . $min[1];
		$sec = $presec * 60;

		$timeshift = $preday[0];
	}
	return $timeshift;
}

function timeAgo(string $timestamp): string
{
	$datetime1 = new DateTime("now");
	$datetime2 = date_create($timestamp);
	$diff = date_diff($datetime1, $datetime2);
	$timemsg = '';
	if ($diff->y > 0) {
		$timemsg = $diff->y . ' year' . ($diff->y > 1 ? "'s" : '');
	} else if ($diff->m > 0) {
		$timemsg = $diff->m . ' month' . ($diff->m > 1 ? "'s" : '');
	} else if ($diff->d > 0) {
		$timemsg = $diff->d . ' day' . ($diff->d > 1 ? "'s" : '');
	} else if ($diff->h > 0) {
		$timemsg = $diff->h . ' hour' . ($diff->h > 1 ? "'s" : '');
	} else if ($diff->i > 0) {
		$timemsg = $diff->i . ' minute' . ($diff->i > 1 ? "'s" : '');
	} else if ($diff->s > 0) {
		$timemsg = $diff->s . ' second' . ($diff->s > 1 ? "'s" : '');
	}

	$timemsg = $timemsg . ' ago';
	return $timemsg;
}

function maintain_transaction_log(int $intRecId, string $strMessage): void
{
	db();
	$qry = "SELECT loop_warehouse.id FROM loop_warehouse INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.warehouse_id = loop_warehouse.id  WHERE loop_transaction_buyer.id = '" . $intRecId . "'";
	$qryRes = db_query($qry);

	while ($qryRow = array_shift($qryRes)) {
		$company_id = $qryRow['id'];
	}
	$rec_type		= 'Supplier';
	$employee_id 	= $_COOKIE['employeeid'];

	db_query("Insert into loop_transaction_notes(company_id, rec_type, rec_id, message, employee_id) select '" . $company_id . "', '" . $rec_type . "' , '" . $intRecId . "', '" . $strMessage . "', '" . $employee_id . "'");
}

function getQtyAvNow(int $loopId, ?int $warehouse_id, ?int $leadTimeInNumber, ?int $ship_ltl, ?float $after_actual_inventory, ?int $b2b_quantity, ?int $actual_qty_calculated, ?int $sales_order_qty_val): float
{
	$quantity_availableValue_new = 0;

	$leadTimestr_days = "";
	if ($leadTimeInNumber == 1) {
		$leadTimestr_days = "+" . $leadTimeInNumber . " Day";
	}

	if ($leadTimeInNumber < 7 && $leadTimeInNumber != 1) {
		$leadTimestr_days = "+" . $leadTimeInNumber . " Days";
	}

	if ($leadTimeInNumber >= 7) {
		$leadTimeInWeek = round($leadTimeInNumber / 7);
		if ($leadTimeInWeek == 1) {
			$leadTimestr_days = "+" . $leadTimeInWeek . " Week";
		} else {
			$leadTimestr_days = "+" . $leadTimeInWeek . " Weeks";
		}
	}

	$twoweek_days = "+ 14 Days";

	db();
	$no_of_loads = 0;
	$sel_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id = '" . $loopId . "' and inactive_delete_flg = 0 
	and (load_available_date <= '" . date("Y-m-d", strtotime($twoweek_days)) . "') and trans_rec_id = 0 order by load_available_date";
	$sel_res = db_query($sel_qry);
	while ($res_box_row = array_shift($sel_res)) {
		$no_of_loads = $no_of_loads + 1;
	}

	if ($no_of_loads > 0) {

		$load_quantity = 0;
		if ($no_of_loads != 0) {
			$load_quantity = $no_of_loads * $b2b_quantity;
		}

		if ($ship_ltl == 1 && $after_actual_inventory > $load_quantity) {
			$quantity_availableValue_new = $after_actual_inventory;
		} else if ($after_actual_inventory > $load_quantity) {
			$quantity_availableValue_new = $after_actual_inventory;
		} else {
			$quantity_availableValue_new = $load_quantity;
		}
	} else {

		$quantity_availableValue_new = $actual_qty_calculated - $sales_order_qty_val;
	}

	if ($quantity_availableValue_new < 0) {
		$quantity_availableValue_new = 0;
	}

	return $quantity_availableValue_new;
}

function getEstimatedNextLoad_New(?int $loopId, ?int $warehouseId, ?string $nextLoadAvailableDate, ?int $leadTime, ?int $myrowLeadTime, ?float $txtafterPo, ?string $boxesPerTrailer, ?float $myrowExpectedLoadsPerMo, ?string $st_rowStatusKey, ?string $updateQryAction = 'no'): string
{

	if ($leadTime == 0) {
		$leadTime = 1;
	}
	db();

	$twoweek_days = "+ 14 Days";
	$qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id = ? and inactive_delete_flg = 0 
	and (load_available_date <= '" . date("Y-m-d", strtotime($twoweek_days)) . "') and trans_rec_id = 0 order by load_available_date";
	$resarr = db_query($qry, array("i"), array($loopId));

	if (!empty($resarr)) {
		$history_res = array_shift($resarr);

		$currentDate = new DateTime();
		$otherDate = new DateTime($history_res['load_available_date']);
		$interval = $currentDate->diff($otherDate);
		$no_of_loaddays = $interval->days;
		$no_of_loaddays = $no_of_loaddays + 1;

		//echo "no_of_loaddays " . $no_of_loaddays . "|" . $history_res['load_available_date'] . "<br>";
		if ($no_of_loaddays > 0) {
			if ($no_of_loaddays > $leadTime) {
				$leadTimeInNumber = $no_of_loaddays;
			} else {
				$leadTimeInNumber = $leadTime;
			}
		} else {
			$leadTimeInNumber = $leadTime;
		}

		if ($otherDate < $currentDate) {
			//echo "datediff < sys date <br>";
			$leadTimeInNumber = $leadTime;
		}
		//echo "datediff " . $leadTime . " | " . $no_of_loaddays . " | " . $leadTimeInNumber . "<br>";
		//
		if ($leadTimeInNumber == 1) {
			$estimated_next_load = "<span color=green>" . $leadTimeInNumber . " Day</span>";
		}
		//
		if ($leadTimeInNumber < 7 && $leadTimeInNumber != 1) {
			$estimated_next_load = "<span color=green>" . $leadTimeInNumber . " Days</span>";
		}
		//
		if ($leadTimeInNumber >= 7) {
			$leadTimeInWeek = round($leadTimeInNumber / 7);
			if ($leadTimeInWeek == 1) {
				$estimated_next_load = $leadTimeInWeek . " Week";
			} else {
				$estimated_next_load = $leadTimeInWeek . " Weeks";
			}
		}
	} else {

		//if ($warehouseId == 238 && ($nextLoadAvailableDate != "" && $nextLoadAvailableDate != "0000-00-00")){
		if ($warehouseId == 238) {

			$estimated_next_load = "<span color=red>Inquire</span>";
		} else {

			if ($txtafterPo >= $boxesPerTrailer) {

				if ($myrowLeadTime == 0) {
					$estimated_next_load = "<font color=green>Now</font>";
				}

				if ($myrowLeadTime == 1) {
					$estimated_next_load = "<font color=green>" . $myrowLeadTime . " Day</font>";
				}
				if ($myrowLeadTime > 1) {
					$estimated_next_load = "<font color=green>" . $myrowLeadTime . " Days</font>";
				}
			} else {
				if (($myrowExpectedLoadsPerMo <= 0) && ($txtafterPo < $boxesPerTrailer)) {
					$estimated_next_load = "Inquire";
				} else if ($txtafterPo == 0 && $myrowExpectedLoadsPerMo == 0) {
					$estimated_next_load = "Inquire";
				} else {
					$loadInWeek = 1;
					$loadInWeek = ceil((((($txtafterPo / $boxesPerTrailer) * -1) + 1) / $myrowExpectedLoadsPerMo) * 4);
					if ($loadInWeek == 1) {
						$estimated_next_load = $loadInWeek . " Week";
					}
					if ($loadInWeek > 1) {
						$estimated_next_load = $loadInWeek . " Weeks";
					}
				}
			}
		}
	}

	//

	if ($updateQryAction == 'yes') {
		db();
		db_query("UPDATE loop_boxes SET buy_now_load_can_ship_in = '" . $estimated_next_load . "' WHERE id = '" . $loopId . "'");
		db_b2b();
		db_query("UPDATE inventory SET buy_now_load_can_ship_in = '" . $estimated_next_load . "' WHERE loops_id = '" . $loopId . "'");
	}

	return $estimated_next_load;
}




function waterUserVisitedTo(int $waterUserLoginId, string $pageName): void
{
	//echo "<br /> waterUserLoginId - ".$waterUserLoginId." / pageName - ".$pageName;
	//exit();
	$machineIP = $_SERVER['REMOTE_ADDR'];
	$browserDetails = $_SERVER['HTTP_USER_AGENT'];
	db();
	db_query("INSERT INTO water_login_user_visit_to(water_login_user_id, machine_ip, browser_details, visit_to, record_date) VALUES(" . $waterUserLoginId . ", '" . $machineIP . "', '" . $browserDetails . "', '" . $pageName . "', '" . date('Y-m-d h:i:s') . "')");
}


function getnickname_warehouse_new(string $warehouse_name, int $loopid): string
{
	$nickname = "";
	if ($loopid > 0) {
		db_b2b();
		$sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where loopid = ?";
		$result_comp = db_query($sql, array("i"), array($loopid));
		while ($row_comp = array_shift($result_comp)) {
			if ($row_comp["nickname"] != "") {
				$nickname = $row_comp["nickname"];
			} else {
				$tmppos_1 = strpos($row_comp["company"], "-");
				if ($tmppos_1 != false) {
					$nickname = $row_comp["company"];
				} else {
					if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
						$nickname = $row_comp["company"] .
							" - " . $row_comp["shipCity"] .
							", " . $row_comp["shipState"];
					} else {
						$nickname = $row_comp["company"];
					}
				}
			}
		}
		db();
	} else {
		$nickname = $warehouse_name;
	}

	return $nickname;
}


function getnickname_warehouse(string $warehouse_name, int $loopid): string
{
	$nickname = "";
	if ($loopid > 0) {
		db_b2b();
		$sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where loopid = ?";
		$result_comp = db_query($sql, array("i"), array($loopid));
		while ($row_comp = array_shift($result_comp)) {
			if ($row_comp["nickname"] != "") {
				$nickname = $row_comp["nickname"];
			} else {
				$tmppos_1 = strpos($row_comp["company"], "-");
				if ($tmppos_1 != false) {
					$nickname = $row_comp["company"];
				} else {
					if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
						$nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
					} else {
						$nickname = $row_comp["company"];
					}
				}
			}
		}
		db();
	} else {
		$nickname = $warehouse_name;
	}

	return $nickname;
}

function FixFilename($strtofix)
{ //THIS FUNCTION ESCAPES SPECIAL CHARACTERS FOR INSERTING INTO SQL

	$strtofix = str_replace("<", "_", $strtofix);

	$strtofix = str_replace("'", "_", $strtofix);

	$strtofix = str_replace("#", "_", $strtofix);

	$strtofix = str_replace(" ", "_", $strtofix);

	$strtofix = str_replace("(\n)", "<BR>", $strtofix);

	return $strtofix;
}
