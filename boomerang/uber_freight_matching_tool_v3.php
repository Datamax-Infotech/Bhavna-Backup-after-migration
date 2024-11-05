<?php
if ($_REQUEST["inclient"] == 1) {
} else {
	require_once("inc/header_session.php");
}

require_once("../mainfunctions/database.php");
require_once("../mainfunctions/general-functions.php");

?>
<!DOCTYPE HTML>

<html>

<head>
    <title>Uber Freight</title>
    <link rel="stylesheet" href="sorter/style_rep.css" />

    <script>
    function showquote() {
        document.getElementById('uberfreightquote').style.display = "block";
    }

    function update_quote(cart_itemID) {
        parent.document.getElementById('shipfinal' + cart_itemID).value = document.getElementById('quote_amount')
            .innerHTML;
        parent.calcualteprofitloss(this.form, cart_itemID);
    }
    </script>
</head>

<body>

    <?php
	//Get the Client Token
	$url = 'https://login.uber.com/oauth/v2/token';

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "client_id=HIpNTYMMUZbj_KGeBqLST9D1FCZ6bT2B&client_secret=YFjURNQqmTqXRFdTOlyEUpba0Q3Rvq0ftcQ_ujFe&grant_type=client_credentials&scope=freight.loads");

	// In real life you should use something like:
	// curl_setopt($ch, CURLOPT_POSTFIELDS, 
	//          http_build_query(array('postvar1' => 'value1')));

	// Receive server response ...
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$res_Data = curl_exec($ch);

	$jsonData = json_decode($res_Data, true);

	curl_close($ch);

	$client_auth_code = "";
	if (isset($jsonData["code"])) {
		echo "<font color=red>Error: " . $jsonData["code"] . " " . $jsonData["message"] . " </font><br>";
		print_r(isset($quote_req));
		exit;
	} else {
		$client_auth_code = $jsonData["access_token"];
	}

	//$url = 'https://sandbox-api.uber.com/v1/freight/loads/quotes';
	$url = 'https://api.uber.com/v1/freight/loads/quotes';

	$box_inv_id = $_REQUEST["inv_b2b_id"];
	$compid = $_REQUEST["companyID"];
	$minfob = $_REQUEST["minfob"];

	$bweight = 0;
	$vendor_b2b_rescue = 0;
	$vendor_b2b_rescue_b2bid = 0;
	$box_weight = 0;
	$pickup_nm = "";
	$pickup_add1 = "";
	$pickup_add2 = "";
	$dropoff_country = "";
	$pickup_city = "";
	$pickup_state = "";
	$pickup_zip = "";
	$pickup_country = "";
	$cart_itemID = 0;
	$dropoff_nm = "";
	$dropoff_add1 = "";
	$dropoff_add2 = "";
	$box_id = 0;
	$box_qty = 0;
	$dropoff_city = "";
	$dropoff_state = "";
	$dropoff_zip = "";
	$box_description = "";
	$nowarehouse = "no";
	$box_warehouse_id = '';

	db_b2b();
	$sql_data = "Select loops_id, ID, vendor_b2b_rescue, description, quantity from inventory Where ID = ?";
	$dt_view_res_data = db_query($sql_data, array("i"), array($box_inv_id));
	while ($myrowsel_b2b = array_shift($dt_view_res_data)) {
		$box_id	= $myrowsel_b2b["loops_id"];
		$box_inv_id = $myrowsel_b2b["ID"];

		$vendor_b2b_rescue = $myrowsel_b2b["vendor_b2b_rescue"];
		$box_description = $myrowsel_b2b["description"];
		$box_qty = $myrowsel_b2b["quantity"];


		$bweight = 0;
		db();
		$sql_data_child = "Select bweight, box_warehouse_id from loop_boxes Where b2b_id = ?";
		$dt_view_res_data_child = db_query($sql_data_child, array("i"), array($myrowsel_b2b['ID']));
		while ($myrowsel_child = array_shift($dt_view_res_data_child)) {
			$bweight = $myrowsel_child["bweight"];
			$box_warehouse_id = $myrowsel_child["box_warehouse_id"];
		}

		$box_weight = $myrowsel_b2b["quantity"] * $bweight;
	}

	if ($vendor_b2b_rescue > 0 && $box_warehouse_id == 238) {
		$vendor_b2b_rescue = $vendor_b2b_rescue;
	}

	if ($box_warehouse_id > 0 && $box_warehouse_id != 238) {
		$vendor_b2b_rescue = $box_warehouse_id;
	}

	if ($box_warehouse_id == 0) {
		$nowarehouse = "yes";
		echo "<font color=red>Inventory Item does not have a selected Warehouse on the spec page, inform Inventory Manager ASAP</font>";
		exit;
	}

	db_b2b();
	$dt_view_res_data = db_query("Select * from companyInfo where loopid = ?", array("i"), array($vendor_b2b_rescue));
	while ($myrowsel_b2b = array_shift($dt_view_res_data)) {
		$vendor_b2b_rescue_b2bid = $myrowsel_b2b["ID"];
		$pickup_nm = strval(get_nickname_val($myrowsel_b2b["company"], $myrowsel_b2b["ID"]));
		//$pickup_nm = str_replace("-", " " , $pickup_nm);
		//$pickup_nm = str_replace(",", " " , $pickup_nm);
		$pickup_add1 = strval($myrowsel_b2b["shipAddress"]);
		$pickup_add2 = strval($myrowsel_b2b["shipAddress2"]);
		$pickup_city = strval($myrowsel_b2b["shipCity"]);
		$pickup_state = strval($myrowsel_b2b["shipState"]);
		$pickup_country = $myrowsel_b2b["shipcountry"];
		if (strtolower($myrowsel_b2b["shipcountry"]) == "usa") {
			$pickup_zip = substr(strval($myrowsel_b2b["shipZip"]), 0, 5);
		} else {
			$pickup_zip = $myrowsel_b2b["shipZip"];
		}
	}

	if ($vendor_b2b_rescue_b2bid == 0) {
		db();
		$dt_view_res_data = db_query("Select * from loop_warehouse where id = ?", array("i"), array($vendor_b2b_rescue));
		while ($myrowsel_b2b = array_shift($dt_view_res_data)) {
			$vendor_b2b_rescue_b2bid = $myrowsel_b2b["id"];
			$pickup_nm = $myrowsel_b2b["company_name"];
			$pickup_add1 = strval($myrowsel_b2b["company_address1"]);
			$pickup_add2 = strval($myrowsel_b2b["company_address2"]);
			$pickup_city = strval($myrowsel_b2b["company_city"]);
			$pickup_state = strval($myrowsel_b2b["company_state"]);
			$pickup_country = $myrowsel_b2b["warehouse_country"];
			if (strtolower($myrowsel_b2b["warehouse_country"]) == "usa" || $myrowsel_b2b["warehouse_country"] == "") {
				$pickup_zip = substr(strval($myrowsel_b2b["company_zip"]), 0, 5);
			} else {
				$pickup_zip = $myrowsel_b2b["shipZip"];
			}
		}
	}

	if ($_REQUEST["b2binvflg"] == "yes") {
		$compid = 99; //taken id which is not used
		$dropoff_nm = strval("B2B Inventory company");

		$dropoff_add1 = strval($_REQUEST["txtaddress"]);
		$dropoff_add2 = strval($_REQUEST["txtaddress2"]);
		$dropoff_city = strval($_REQUEST["txtcity"]);
		$dropoff_state = strval($_REQUEST["txtstate"]);
		$dropoff_country = "USA";
		$dropoff_zip = substr(strval($_REQUEST["txtzipcode"]), 0, 5);
	} else {
		db_b2b();
		$dt_view_res_data = db_query("Select * from companyInfo where ID = ?", array("i"), array($compid));
		while ($myrowsel_b2b = array_shift($dt_view_res_data)) {
			$dropoff_nm = strval(get_nickname_val($myrowsel_b2b["company"], $myrowsel_b2b["ID"]));

			$dropoff_add1 = strval($myrowsel_b2b["shipAddress"]);
			$dropoff_add2 = strval($myrowsel_b2b["shipAddress2"]);
			$dropoff_city = strval($myrowsel_b2b["shipCity"]);
			$dropoff_state = strval($myrowsel_b2b["shipState"]);
			$dropoff_country = $myrowsel_b2b["shipcountry"];
			if (strtolower($myrowsel_b2b["shipcountry"]) == "usa") {
				$dropoff_zip = substr(strval($myrowsel_b2b["shipZip"]), 0, 5);
			} else {
				$dropoff_zip = $myrowsel_b2b["shipZip"];
			}
		}
	}

	if ($pickup_nm == "") {
		$pickup_nm = "Facility pickup - " . $vendor_b2b_rescue_b2bid;
	}
	if ($dropoff_nm == "") {
		$dropoff_nm = "Facility dropoff - " . $compid;
	}

	if (
		$pickup_add1 == "" || $pickup_city == "" || $pickup_state == "" || $pickup_zip == "" ||
		$dropoff_add1 == "" || $dropoff_city == "" || $dropoff_state == "" || $dropoff_zip == ""
	) {
		$nowarehouse = "yes";
		echo "<font color=red>Pickup/Drop off address1/city/state/zip is blank, process terminated.</font>";
	}

	if ($client_auth_code == "") {
		$nowarehouse = "yes";
		echo "<font color=red>Uber Authentication token is empty, process terminated.</font>";
	}

	if ($nowarehouse == "no") {
		db_b2b();
		//Calculate the distance in miles
		//echo "pickup_zip - " . $pickup_zip . " Dropoff_zip - "  . $dropoff_zip . "<br>";
		$transit_time = 0;
		$transit_flg = 0;
		if ($pickup_zip != "" && $dropoff_zip != "") {
			if (strtolower($pickup_country) == "canada") {
				//$tmp_zipval = substr($row["location_zip"], 0, $tmppos_1);
				$tmp_zipval = str_replace(" ", "", $pickup_zip);
				$zipStr = "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
			} else {
				$zipStr = "Select * from ZipCodes WHERE zip = '" . intval($pickup_zip) . "'";
			}

			$res4 = db_query($zipStr);
			$objShipZip = array_shift($res4);

			$shipLat = $objShipZip["latitude"];
			$shipLong = $objShipZip["longitude"];

			$location_zip = $dropoff_zip;

			//$zipStr= "Select * from ZipCodes WHERE zip = " . remove_non_numeric($objInvmatch["location"]);
			$zipStr = "";
			$locLat = 0;
			$locLong = 0;
			if (strtolower($dropoff_country) == "canada") {
				//$tmp_zipval = substr($row["location_zip"], 0, $tmppos_1);
				$tmp_zipval = str_replace(" ", "", $location_zip);
				$zipStr = "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
			} else {
				$zipStr = "Select * from ZipCodes WHERE zip = '" . intval($location_zip) . "'";
			}

			$dt_view_res4 = db_query($zipStr);
			while ($ziploc = array_shift($dt_view_res4)) {
				$locLat = $ziploc["latitude"];

				$locLong = $ziploc["longitude"];
			}

			$distLat = ($shipLat - $locLat) * 3.141592653 / 180;
			$distLong = ($shipLong - $locLong) * 3.141592653 / 180;

			$distA = Sin($distLat / 2) * Sin($distLat / 2) + Cos($shipLat * 3.14159 / 180) * Cos($locLat * 3.14159 / 180) * Sin($distLong / 2) * Sin($distLong / 2);

			$distC = 2 * atan2(sqrt($distA), sqrt(1 - $distA));
			$distance = (int) (6371 * $distC * .621371192);

			$transit_time = ceil($distance / 700);

			$transit_flg = 1;
			//echo $transit_time . "<br>";
		}

		if ($transit_flg == 0) {
			//$transit_time = 1;
			echo "<font color=red>Cannot calculate freight rate, ship from or to addresses may not be correct, check that and try again, otherwise contact Freight Manager for an updated freight quote.</font>";
			exit;
		}

		$freight_str = "";
		$quote_amount_tot = 0;
		$ifany_err = "";

		$tmpcnt = 2; 				// as Pickup date after 2 Days

		$date = new DateTime(); // format: MM/DD/YYYY
		$datenxt = new DateTime(); // format: MM/DD/YYYY
		$interval = new DateInterval('P' . $tmpcnt . 'DT1H');
		$date->add($interval);
		$datenxt->add($interval);

		if ($date->format('N') == 6) {
			$date->modify('+2 day');
			$datenxt->modify('+2 day');
		}
		if ($date->format('N') == 7) {
			$date->modify('+1 day');
			$datenxt->modify('+1 day');
		}
		$starttime = $date->format('U');
		$endtime = $datenxt->format('U');
		$starttime = floatval($starttime);
		$endtime = floatval($endtime);

		$date2 = new DateTime();
		$date3 = new DateTime();
		$interval2 = new DateInterval('P' . $tmpcnt . 'DT1H');
		$date2->add($interval2);
		$date3->add($interval2);
		if ($date2->format('N') == 6) {
			$date2->modify('+2 day');
			$date3->modify('+2 day');
		}
		if ($date2->format('N') == 7) {
			$date2->modify('+1 day');
			$date3->modify('+1 day');
		}
		$interval3 = new DateInterval('P' . $transit_time . 'DT1H');
		$date2->add($interval3);
		$date3->add($interval3);

		//echo "To date - " . $date2->format('m/d/Y') . "<br>";

		$starttime_2 = $date2->format('U');
		$endtime_2 = $date3->format('U');
		$starttime_2 = floatval($starttime_2);
		$endtime_2 = floatval($endtime_2);

		$vendor_b2b_rescue_b2bid = strval($vendor_b2b_rescue_b2bid);
		$compid = strval($compid);

		$uber_qote_id = 0;
		$dt_view_res_data = db_query("Select max(unqid) as maxunqid from quoting_uber_freight_data");
		while ($myrowsel_b2b = array_shift($dt_view_res_data)) {
			$uber_qote_id = $myrowsel_b2b["maxunqid"];
			$uber_qote_id = $uber_qote_id + 1;
		}

		$stoparr = array(
			array(
				'sequence_number' => 1, 'type' => 'PICKUP', 'mode' => 'LIVE', 'facility' => array(
					'facility_id' => $vendor_b2b_rescue_b2bid, 'name' => $pickup_nm,
					'address' => array('line1' => $pickup_add1, 'line2' => $pickup_add2, 'city' => $pickup_city,  'principal_subdivision' => $pickup_state, 'postal_code' => $pickup_zip, 'country' => 'USA')
				),
				'appointment' => array('status' => 'NEEDED', 'start_time_utc' => $starttime, 'end_time_utc' => $endtime)
			),
			array(
				'sequence_number' => 2, 'type' => 'DROPOFF', 'mode' => 'LIVE',
				'facility' => array(
					'facility_id' => $compid, 'name' => $dropoff_nm,
					'address' => array('line1' => $dropoff_add1, 'line2' => $dropoff_add2, 'city' => $dropoff_city,  'principal_subdivision' => $dropoff_state, 'postal_code' => $dropoff_zip, 'country' => 'USA')
				),
				'appointment' => array('status' => 'NEEDED', 'start_time_utc' => $starttime_2, 'end_time_utc' => $endtime_2)
			)
		);

		$quote_req = array(
			'quote_id' => 'UCB_Qutoe_id' . $uber_qote_id, 'customer_id' => 'USEDCARDBOARDBOXES',
			'requirements' => array('vehicle_type' => 'DRY', 'weight' => array('amount' => $box_weight, 'unit' => 'LB')),
			'stops' => $stoparr, 'quote_type' => ''
		);

		//LHR_ONLY

		$ch = curl_init();
		//print_r($quote_req);

		$json = json_encode($quote_req);
		//var_dump($json);

		//echo "<br>";

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

		// Returns the data/output as a string instead of raw data
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//Set your auth headers
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: Bearer ' . $client_auth_code
		));

		$data = curl_exec($ch);

		$jsonData = json_decode($data, true);
		curl_close($ch);


		if (isset($jsonData["code"])) {
			echo "<font color=red>Error: " . $jsonData["code"] . " " . $jsonData["message"] . " </font><br>";
			$ifany_err = "yes";
		} else {
			$dateInLocal = date("m/d/Y H:i:s", strtotime($jsonData["expiration_time_utc"]));
			$quote_amount = $jsonData["price"]["amount"] / 100;
			$quote_amount_tot = $quote_amount_tot + $quote_amount;


			$freight_str .=  "<br>Response from Uber Freight " .
				"<br>Quote ID: " . $jsonData["uber_quote_id"] .
				"<br>Start Date: " . $date->format('m/d/Y') .
				"<br>End Date: " . $date2->format('m/d/Y') .
				"<br>Quote status: " . $jsonData["status"] .
				"<br><b>Quote amount: $" . number_format($quote_amount, 2) . "</b>" .
				"<br>Expiration Time: " . $dateInLocal .
				"<br>Notes : " . $jsonData["notes"] .
				"<br>";

			$res_ins_qry = "Insert into quoting_uber_freight_data (company_id, cart_item_id, box_id, box_inv_id, pickup_zip, dropoff_zip, box_weight, uber_quote_id, uber_quote_status, 
                                uber_quote_amount, uber_quote_exp_time, uber_quote_uuid, uber_quote_note, pickup_comp_id, pickup_nm, pickup_add1, pickup_add2, pickup_city, pickup_state, pickup_starttime,
	                        pickup_endtime, dropoff_nm, dropoff_add1, dropoff_add2, dropoff_city, dropoff_state, dropoff_starttime, dropoff_endtime, 
				transit_time, process_date_time ) select '" . $compid . "',
				'" . $cart_itemID . "', '" . $box_id . "', '" . $box_inv_id . "', '" . $pickup_zip . "', '" . $dropoff_zip . "', '" . $box_weight . "', '" . $jsonData["uber_quote_id"] . "', '" . $jsonData["status"] . "',
				'" . $quote_amount . "', '" . $jsonData["expiration_time_utc"] . "', '" . $jsonData["uber_quote_uuid"] . "', '" . str_replace("'", "\'", $jsonData["notes"]) . "', 
				'" . $vendor_b2b_rescue_b2bid . "', '" . str_replace("'", "\'", $pickup_nm) . "', '" . str_replace("'", "\'", $pickup_add1) . "', '" . str_replace("'", "\'", $pickup_add2) . "', '" . str_replace("'", "\'", $pickup_city) . "', '" . str_replace("'", "\'", $pickup_state) . "', '" . $starttime . "',
				'" . $endtime . "', '" . str_replace("'", "\'", $dropoff_nm) . "', '" . str_replace("'", "\'", $dropoff_add1) . "', '" . str_replace("'", "\'", $dropoff_add2) . "', '" . str_replace("'", "\'", $dropoff_city) . "', '" . str_replace("'", "\'", $dropoff_state) . "',
				'" . $starttime_2 . "', '" . $endtime_2 . "', '" . $transit_time . "', '" . date("Y-m-d H:i:s") . "'";

			//echo $res_ins_qry . "<br>";
			db_query($res_ins_qry);
		}

		if ($ifany_err == "") {

			$avg_quote_amount_tot_org = ($quote_amount_tot * 1.01);
			$avg_quote_amount_tot_org = str_replace(",", "", (string)$avg_quote_amount_tot_org);
			$avg_quote_amount_tot = number_format($quote_amount_tot * 1.01, 2);

			$finalval = $minfob + ($avg_quote_amount_tot_org / $box_qty);

			echo "<font size='4'><b>$" . number_format($finalval, 2) . "</b></font><br>$" . number_format($minfob, 2) . "/ea + $" . $avg_quote_amount_tot;

			echo '<input type="hidden" id="sales_price' . $box_inv_id . '" value="' . $finalval . '"> <input type="hidden" id="ship_final' . $box_inv_id . '" value="' . $avg_quote_amount_tot . '">';
		}
	}
	?>
</body>

</html>