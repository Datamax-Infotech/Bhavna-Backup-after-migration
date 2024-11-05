<!DOCTYPE HTML>
<html>
<head>
	<title>Water Cron For Dashboard Selected Year</title>
	<link rel="stylesheet" href="sorter/style_rep.css" />
	<style type="text/css">
		.txtstyle_color {
			font-family: arial;
			font-size: 12;
			height: 16px;
			background: #ABC5DF;
		}
		.txtstyle {
			font-family: arial;
			font-size: 12;
		}
		.style7 {
			font-size: xx-small;

			font-family: Arial, Helvetica, sans-serif;

			color: #333333;

			background-color: #FFCC66;

		}

		.style5 {

			font-family: Arial, Helvetica, sans-serif;

			font-size: x-small;

			text-align: center;

			background-color: #99FF99;

		}



		.style6 {

			text-align: center;

			background-color: #99FF99;

		}



		.style2 {

			font-family: Arial, Helvetica, sans-serif;

			font-size: x-small;

		}



		.style3 {

			font-family: Arial, Helvetica, sans-serif;

			font-size: x-small;

			color: #333333;

		}



		.style8 {

			text-align: left;

			font-family: Arial, Helvetica, sans-serif;

			font-size: x-small;

			color: #333333;

		}



		.style11 {

			font-family: Arial, Helvetica, sans-serif;

			font-size: x-small;

			color: #333333;

			text-align: center;

		}



		.style10 {

			text-align: left;

		}



		.style12 {

			font-family: Arial, Helvetica, sans-serif;

			font-size: x-small;

			color: #333333;

			text-align: right;

		}



		.style13 {

			font-family: Arial, Helvetica, sans-serif;

		}



		.style14 {

			font-size: x-small;

		}



		.style15 {

			font-size: small;

		}



		.style16 {

			font-family: Arial, Helvetica, sans-serif;

			font-size: x-small;

			background-color: #99FF99;

		}



		.style17 {

			background-color: #99FF99;

		}



		select,

		input {

			font-family: Arial, Helvetica, sans-serif;

			font-size: 10px;

			color: #000000;

			font-weight: normal;

		}



		span.infotxt:hover {

			text-decoration: none;

			background: #ffffff;

			z-index: 6;

		}



		span.infotxt span {

			position: absolute;

			left: -9999px;

			margin: 20px 0 0 0px;

			padding: 3px 3px 3px 3px;

			z-index: 6;

		}



		span.infotxt:hover span {

			left: 45%;

			background: #ffffff;

		}



		span.infotxt span {

			position: absolute;

			left: -9999px;

			margin: 0px 0 0 0px;

			padding: 3px 3px 3px 3px;

			border-style: solid;

			border-color: black;

			border-width: 1px;

		}



		span.infotxt:hover span {

			margin: 18px 0 0 170px;

			background: #ffffff;

			z-index: 6;

		}



		span.infotxt_freight:hover {

			text-decoration: none;

			background: #ffffff;

			z-index: 6;

		}



		span.infotxt_freight span {

			position: absolute;

			left: -9999px;

			margin: 20px 0 0 0px;

			padding: 3px 3px 3px 3px;

			z-index: 6;

		}



		span.infotxt_freight:hover span {

			left: 0%;

			background: #ffffff;

		}



		span.infotxt_freight span {

			position: absolute;

			width: 850px;

			overflow: auto;

			height: 300px;

			left: -9999px;

			margin: 0px 0 0 0px;

			padding: 10px 10px 10px 10px;

			border-style: solid;

			border-color: white;

			border-width: 50px;

		}



		span.infotxt_freight:hover span {

			margin: 5px 0 0 50px;

			background: #ffffff;

			z-index: 6;

		}



		span.infotxt_freight2:hover {

			text-decoration: none;

			background: #ffffff;

			z-index: 6;

		}



		span.infotxt_freight2 span {

			position: absolute;

			left: -9999px;

			margin: 20px 0 0 0px;

			padding: 3px 3px 3px 3px;

			z-index: 6;

		}



		span.infotxt_freight2:hover span {

			left: 0%;

			background: #ffffff;

		}



		span.infotxt_freight2 span {

			position: absolute;

			width: 850px;

			overflow: auto;

			height: 300px;

			left: -9999px;

			margin: 0px 0 0 0px;

			padding: 10px 10px 10px 10px;

			border-style: solid;

			border-color: white;

			border-width: 50px;

		}



		span.infotxt_freight2:hover span {

			margin: 5px 0 0 500px;

			background: #ffffff;

			z-index: 6;

		}



		.black_overlay {

			display: none;

			position: absolute;

		}



		.white_content {

			display: none;

			position: absolute;

			padding: 5px;

			border: 2px solid black;

			background-color: white;

			overflow: auto;

			height: 600px;

			width: 850px;

			z-index: 1002;

			margin: 0px 0 0 0px;

			padding: 10px 10px 10px 10px;

			border-color: black;

			border-width: 2px;

			overflow: auto;

		}

	</style>

	<script LANGUAGE="JavaScript">



	</script>



	<LINK rel='stylesheet' type='text/css' href='one_style.css'>

	<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">

	<link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>

</head>



<?php 
ini_set('memory_limit', '256M'); // Increase memory limit to 256MB
/*
ini_set("display_errors", "1");
error_reporting(E_ALL);
*/

require_once("../mainfunctions/database.php");
require_once("../mainfunctions/general-functions.php");
db();
?>
<body>
	<?php include("inc/header.php"); ?>
	<div class="main_data_css">

		<div class="dashboard_heading" style="float: left;">

			<div style="float: left;">Water Cron For Dashboard Selected Year

				<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>

					<span class="tooltiptext">

						Water Cron For Dashboard Selected Year

					</span>

				</div>



				<div style="height: 13px;">&nbsp;</div>

			</div>

		</div>



		<form method="get" name="report_water_cron_fordash-selectedrecord" action="water_cron_fordash-selectedrecord.php">

			<table border="0">

				<tr>

					<td>Select Company:</td>

					<td>

						<select name="company_red" id="company_red">

							<option>Please Select</option>

							<?php 

							//$sql3 = "SELECT * FROM supplierdashboard_usermaster where activate_deactivate = 1 and parent_comp_flg = 0 order by user_name";

							$sql3 = "Select supplierdashboard_usermaster.companyid, loop_warehouse.b2bid, loop_warehouse.warehouse_name from supplierdashboard_usermaster inner join loop_warehouse on loop_warehouse.b2bid = supplierdashboard_usermaster.companyid

						    where activate_deactivate = 1 and parent_comp_flg = 0 group by supplierdashboard_usermaster.companyid";
							
							$result3 = db_query($sql3);

							while ($myrowsel3 = array_shift($result3)) {

								$nickname = get_nickname_val($myrowsel3["warehouse_name"], $myrowsel3["b2bid"]);

							?>

								<option value="<?php echo $myrowsel3["companyid"]; ?>" <?php if ($myrowsel3["companyid"] == $_REQUEST["company_red"] || $myrowsel3["companyid"] == $_REQUEST["b2b_comp_id"]) echo "selected"; ?>><?php echo $nickname; ?></option>

							<?php } ?>

						</select>

					</td>

					<td>Select Year:</td>

					<td>

						<select name="year_val" id="year_val">

							<option>Please Select</option>

							<?php 

							for ($year_cnt = 2000; $year_cnt <= date("Y"); $year_cnt++) {

							?>

								<option value="<?php echo $year_cnt; ?>" <?php if ($year_cnt == $_REQUEST["year_val"]  || $year_cnt == date("Y")) echo " selected "; ?>><?php echo $year_cnt; ?></option>

							<?php } ?>

						</select>

					</td>

					<td>

						<input type=submit value="Run Cron Job">

					</td>

				</tr>

			</table>

		</form>



		<?php 

		if ($_REQUEST["year_val"] != "") {

			$data_year = $_REQUEST["year_val"];

			$weight_in_pound_tot = 0;
			
			$query_mtd = "SELECT water_inventory.poundpergallon_value, weight_unit, water_boxes_report_data.id, Estimatedweight , Estimatedweight_value, water_boxes_report_data.unit_count, 

			water_boxes_report_data.WeightorNumberofPulls, value_each, weight, weight_in_pound, water_boxes_report_data.AmountUnit, water_inventory.AmountUnit as InvAmountUnit, water_boxes_report_data.AmountUnitEquivalent,

			Estimatedweight_peritem,  Estimatedweight_value_peritem from water_boxes_report_data inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID 

			inner join water_transaction on water_boxes_report_data.trans_rec_id = water_transaction.id where weight >0 ";

			//and water_boxes_report_data.warehouse_id = '1470' and (invoice_date >= '2023-01-01' AND invoice_date <= '2023-01-31 23:59:59') and water_boxes_report_data.outlet = 'WASTE TO ENERGY' and water_inventory.description = '275Gal Tote Non-RCRA Hazardous Waste Liquid (Cosmetic Waste)' 

			//and water_inventory.vendor = 1937 and water_boxes_report_data.outlet = 'Landfill'"; //and  water_inventory.description = 'Compactor - 30 Yd' and  and water_boxes_report_data.outlet = 'Waste To Energy' and water_boxes_report_data.id = 185550 

			$res = db_query($query_mtd);
	
			while ($row_mtd = array_shift($res)) {

				$weight_in_pound  = 0;

				$avg_price_per_pound = 0;

				if ($row_mtd["InvAmountUnit"] == "Tons" || $row_mtd["weight_unit"] == "Tons" || $row_mtd["AmountUnit"] == "Tons") {

					$weight_in_pound = floatval($row_mtd["weight"]) * 2000;

					//echo "in step 1 <br>";

				}



				//Case when entry person has change the unit

				if ($row_mtd["InvAmountUnit"] == "Tons" && $row_mtd["weight_unit"] == "Pounds" && $row_mtd["AmountUnit"] == "Tons") {

					$weight_in_pound = floatval($row_mtd["weight"]);

				}



				if ($row_mtd["InvAmountUnit"] == "Kilograms" || $row_mtd["weight_unit"] == "Kilograms" || $row_mtd["AmountUnit"] == "Kilograms") {

					$weight_in_pound = floatval($row_mtd["weight"]) * 2.20462;

					//echo "in step 2 <br>";

				}



				//If unit is blank then weight should be 0 || $row_mtd["AmountUnit"] == "Pounds"

				if ($row_mtd["InvAmountUnit"] == "Pounds" || $row_mtd["AmountUnit"] == "Pounds") {

					$weight_in_pound = $row_mtd["weight"];

					//echo "in step 3 <br>";

				}



				if ($row_mtd["InvAmountUnit"] == "Tons" || $row_mtd["AmountUnit"] == "Tons") {

					$avg_price_per_pound = floatval($row_mtd["value_each"]) / 2000;

					//echo "in step 4 <br>";

				}



				if ($row_mtd["InvAmountUnit"] == "Kilograms" || $row_mtd["AmountUnit"] == "Kilograms") {

					$avg_price_per_pound = floatval($row_mtd["value_each"]) * 2.20462;

					//echo "in step 5 <br>";

				}



				if ($row_mtd["InvAmountUnit"] == "Pounds" || $row_mtd["AmountUnit"] == "Pounds") {

					$avg_price_per_pound = floatval($row_mtd["value_each"]);

					//echo "in step 6 <br>";

				}



				if ($row_mtd["WeightorNumberofPulls"] != "By Weight") {

					if ($row_mtd["WeightorNumberofPulls"] == "By Number of Pulls") {

						if ($row_mtd["weight"] > 0 && $row_mtd["unit_count"] > 0) {

							if ($row_mtd["weight_unit"] == "Tons") {

								$weight_in_pound = (floatval($row_mtd["weight"]) * 2000) * floatval($row_mtd["unit_count"]);

								//echo "in step 7 <br>";

							}

							//If unit is blank then weight should be 0 || $row_mtd["weight_unit"] == "Pounds"

							if ($row_mtd["weight_unit"] == "Pounds") {

								$weight_in_pound = floatval($row_mtd["weight"]) * floatval($row_mtd["unit_count"]);

								//echo "in step 8 <br>";

							}

							if ($row_mtd["weight_unit"] == "Kilograms") {

								$weight_in_pound = (floatval($row_mtd["weight"]) * 2.20462) * floatval($row_mtd["unit_count"]);

								//echo "in step 9 <br>";

							}

						} else {

							if ($weight_in_pound == 0) {

								$weight_in_pound = floatval($row_mtd["weight"]);

								//echo "in step 10 <br>";

							}

						}

						$avg_price_per_pound = $row_mtd["value_each"];

					} elseif ($row_mtd["WeightorNumberofPulls"] == "Per Item") {

						if (($row_mtd["weight"] > 0) && ($row_mtd["unit_count"] > 0)) {

							

							if ($row_mtd["weight_unit"] == "Tons") {

								$weight_in_pound = (floatval($row_mtd["weight"]) * 2000) * floatval($row_mtd["unit_count"]);

								

							}



							if ($row_mtd["weight_unit"] == "Pounds") {

								$weight_in_pound = floatval($row_mtd["weight"]) * floatval($row_mtd["unit_count"]);

								//echo "in step 12 <br>";

							}



							if ($row_mtd["weight_unit"] == "Kilograms") {

								$weight_in_pound = (floatval($row_mtd["weight"]) * 2.20462) * floatval($row_mtd["unit_count"]);

								//echo "in step 13 <br>";

							}

						} else {

							if ($weight_in_pound == 0) {

								$weight_in_pound = $row_mtd["weight"];

								//echo "in step 14 <br>";

							}

						}

						$avg_price_per_pound = $row_mtd["value_each"];

					} else {

						if ($row_mtd["weight_unit"] == "Kilograms" || $row_mtd["weight_unit"] == "Tons") {

						} else {

							if ($weight_in_pound == 0) {

								$weight_in_pound = $row_mtd["weight"];

								//echo "in step 15 <br>";

							}

						}

						$avg_price_per_pound = $row_mtd["value_each"];

					}

				}



				if ($row_mtd["InvAmountUnit"] == "Gallon" || $row_mtd["AmountUnitEquivalent"] == "Gallon") {

					if ($row_mtd["unit_count"] > 0) {

						$weight_in_pound = (str_replace(",", "", $row_mtd["weight"]) * str_replace(",", "", $row_mtd["unit_count"])) * str_replace(",", "", $row_mtd["poundpergallon_value"]);

						//echo "in step 16 <br>";

					} else {

						$weight_in_pound = str_replace(",", "", $row_mtd["weight"]) * str_replace(",", "", $row_mtd["poundpergallon_value"]);

						//echo "in step 17 <br>";

					}



					//$weight_in_pound = $row_mtd["weight"] * $row_mtd["poundpergallon_value"];

				}





				$weight_in_pound_tot = $weight_in_pound_tot + $weight_in_pound;



				//echo "Update water_boxes_report_data set weight_in_pound = " . $weight_in_pound . ", avg_price_per_pound = '" . $avg_price_per_pound . "' where id = " . $row_mtd["id"] . "<br>";

				$res_ret = db_query("Update water_boxes_report_data set weight_in_pound = " .  str_replace(",", "" ,$weight_in_pound) . ", avg_price_per_pound = '" . $avg_price_per_pound . "' where id = " . $row_mtd["id"]);
				
			}



			$companyid = 0;

			$warehouse_id = 0;

			$company_name = "";

			$company_logo = "";

			$st_date = date($data_year . "-01-01");

			$end_date = date($data_year . "-12-31");

			

			//and loginid = 38  and loginid > 91 

			$sql = "SELECT companyid, loginid, parent_comp_flg FROM supplierdashboard_usermaster WHERE activate_deactivate = 1 and parent_comp_flg = 0 

			and companyid = " . $_REQUEST["company_red"] . " group by companyid order by loginid";

			//echo $sql . "<br>";
			$result_main = db_query($sql);
			
			while ($myrowsel = array_shift($result_main)) {

				$parent_comp_flg = $myrowsel['parent_comp_flg'];



				$landfill_diversion = 0;



				$tree_saved = 0;

				$warehouse_id = 0;

				$sql1 = "SELECT id, company_name FROM loop_warehouse WHERE b2bid=? ";

				$result1 = db_query($sql1, array("i"), array($myrowsel["companyid"]));
		
				while ($myrowsel1 = array_shift($result1)) {

					$warehouse_id = $myrowsel1["id"];

				}



				if ($parent_comp_flg == 1) {

					db_b2b();

					$vcsql = "select ID, loopid, parent_child, parent_comp_id from companyInfo where haveNeed = 'Have Boxes' and parent_comp_id=" . $myrowsel['companyid'];

					$vcresult = db_query($vcsql);

					while ($vcrow = array_shift($vcresult)) {

						$ch_id = $vcrow["loopid"];

						$warehouse_id .= "," . $ch_id;

					}

					db();

				}



				$sumtot = 0;

				$query_mtd  = "SELECT sum(weight_in_pound) as sumweight, water_inventory.Outlet, water_inventory.tree_saved_per_ton from water_boxes_report_data inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID ";

				$query_mtd .= " inner join water_transaction on water_boxes_report_data.trans_rec_id = water_transaction.id inner join water_vendors on water_transaction.vendor_id = water_vendors.id WHERE  water_vendors.id <> 844 and warehouse_id in (" . $warehouse_id . ") and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') ";

				$query_mtd .= " group by water_transaction.vendor_id, water_boxes_report_data.box_id order by water_vendors.Name, water_boxes_report_data.box_id";

				//echo "<br><br>" . "Step 1 " . $query_mtd . "<br>";

				$result = db_query($query_mtd);

				while ($row = array_shift($result)) {

					$sumtot = $sumtot + $row["sumweight"];

				}

				//echo "First " . $sumtot . "<br>";



				$tree_saved_val = 0;

				$query_mtd  = "SELECT sum(weight_in_pound) as sumweight, water_inventory.Outlet, water_inventory.tree_saved_per_ton from water_boxes_report_data inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID ";

				$query_mtd .= " inner join water_transaction on water_boxes_report_data.trans_rec_id = water_transaction.id inner join water_vendors on water_transaction.vendor_id = water_vendors.id WHERE water_vendors.id <> 844 and warehouse_id in (" . $warehouse_id . ") and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') ";

				$query_mtd .= " group by water_boxes_report_data.box_id order by water_vendors.Name, water_boxes_report_data.box_id";

				//echo "Step 2 " . $query_mtd . "<br>";

				$result = db_query($query_mtd);

				while ($row = array_shift($result)) {

					if ($row["tree_saved_per_ton"] > 0 && $row["sumweight"] > 0) {

						$tree_saved_val = $tree_saved_val + (($row["sumweight"] / 2000) * $row["tree_saved_per_ton"]);

						//echo "tree_saved_val - " . $tree_saved_val . " <br>";

					}

				

				}



				$tree_saved = $tree_saved_val;



				$query_mtd1 = "SELECT loop_boxes.vendor, loop_boxes.tree_saved_per_ton, loop_boxes.type, loop_boxes.bdescription, sum(sort_boxgoodvalue) as valueach, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) as totamt, sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";

				$query_mtd1 .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";

				$query_mtd1 .= " WHERE loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date . " 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";

				//echo "Step 3 " . $query_mtd . "<br>";

				$tree_saved_val = 0;

				$sumtot_chk = 0;

				$res1 = db_query($query_mtd1);

				while ($row_mtd1 = array_shift($res1)) {

					$sumtot = $sumtot + $row_mtd1["sumweight"];

					if ($row_mtd1["tree_saved_per_ton"] > 0 && $row_mtd1["sumweight"] > 0) {

						$sumtot_chk = $sumtot_chk + (($row_mtd1["sumweight"] / 2000) * $row_mtd1["tree_saved_per_ton"]);

					}



					

				}

				

				//Additional Fees

				$query_mtd = "SELECT water_vendors.Name as Vendorname, water_transaction.vendor_id, water_trans_addfees.add_fees_id, water_additional_fees.additional_fees_display, water_trans_addfees.id as addfeeid, sum(water_trans_addfees.add_fees * water_trans_addfees.add_fees_occurance) as addfees from water_transaction ";

				$query_mtd .= " inner join water_vendors on water_transaction.vendor_id = water_vendors.id ";

				$query_mtd .= " inner join water_trans_addfees on water_trans_addfees.trans_id = water_transaction.id ";

				$query_mtd .= " left join water_additional_fees on water_trans_addfees.add_fees_id = water_additional_fees.id ";

				$query_mtd .= " WHERE  water_vendors.id <> 844 and company_id in (" . $warehouse_id . ") and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') group by water_vendors.Name, water_additional_fees.additional_fees_display";

				//echo "Step 4 " . $query_mtd . "<br>";

				$othar_charges = 0;

				$vendor_nm = "";

				$add_fee_tot = 0;

				$first_rec = "n";

				$fees = 0;

				$res = db_query($query_mtd);

				while ($row_mtd = array_shift($res)) {

					$othar_charges = $othar_charges - $row_mtd["addfees"];

				}



				$query_mtd1 = "SELECT distinct loop_transaction.id , loop_transaction.freightcharge as freightcharge, loop_transaction.othercharge as othercharge from loop_transaction inner join loop_boxes_sort on loop_transaction.id = loop_boxes_sort.trans_rec_id ";

				$query_mtd1 .= " WHERE loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date . " 00:00:00' AND '" . $end_date . " 23:59:59'";

				//echo $query_mtd1 . "<br>";

				$res1 = db_query($query_mtd1);

				while ($row_mtd1 = array_shift($res1)) {

					$othar_charges = $othar_charges + $row_mtd1["freightcharge"];

					$othar_charges = $othar_charges + $row_mtd1["othercharge"];

				}



				$Recycling_tot = 0;

				$query_mtd = "SELECT sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";

				$query_mtd .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";

				$query_mtd .= " WHERE loop_boxes_sort.boxgood > 0 and loop_boxes.isbox LIKE 'N' and loop_boxes.type <> 'Waste-to-Energy' and loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date . " 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";



				//echo $query_mtd . "<br>";
				$res = db_query($query_mtd);

				while ($row_mtd = array_shift($res)) {

					$Recycling_tot = $Recycling_tot + $row_mtd["sumweight"];

				}



				$Ruse_tot = 0;

				$query_mtd = "SELECT sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";

				$query_mtd .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";

				$query_mtd .= " WHERE loop_boxes_sort.boxgood > 0 and loop_boxes.isbox LIKE 'Y' and loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date . " 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";



				//echo $query_mtd . "<br>";

				$res = db_query($query_mtd);

				while ($row_mtd = array_shift($res)) {

					$Ruse_tot = $Ruse_tot + $row_mtd["sumweight"];

				}



				$WasteToEnergy_tot = 0;

				$query_mtd = "SELECT sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";

				$query_mtd .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";

				$query_mtd .= " WHERE loop_boxes_sort.boxgood > 0 and loop_boxes.type = 'Waste-to-Energy' and loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date . " 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";



				//echo $query_mtd . "<br>";

				$res = db_query($query_mtd);

				while ($row_mtd = array_shift($res)) {

					$WasteToEnergy_tot = $WasteToEnergy_tot + $row_mtd["sumweight"];

				}
				$res = db_query("Delete from water_cron_summary_rep where warehouse_id = " . $warehouse_id . " and data_year = '" . $data_year . "'");

				$res = db_query("Delete from water_cron_fordash_piechart where warehouse_id = " . $warehouse_id . " and data_year = '" . $data_year . "'");

				$rec_added = "no";



				$outlet_array = array("Reuse", "Recycling", "Waste To Energy", "Incineration (No Energy Recovery)", "Landfill");



				$totalval_tot = 0;

				$weightval_tot = 0;

				$display_flg1 = "n";

				$display_flg2 = "n";

				$display_flg3 = "n";

				$weight_tot_reuse = 0;

				$display_order = 0;

				$arrlength = tep_db_num_rows($outlet_array);

				for ($arrycnt = 0; $arrycnt < $arrlength; $arrycnt++) {

					$display_order = $display_order + 1;



					$weightval = 0;

					$valueeachval = 0;

					$totalval = 0;

					$valueeachval_tot = 0;

					$weight_tot = 0;

					$amt_tot = 0;

					$tot_show = "y";

					$trans_rec_id_list = "";



					if ($outlet_array[$arrycnt] == "Recycling") {



						if ($display_flg1 == "n") {

							$query_mtd1 = "SELECT loop_boxes.vendor, loop_boxes.bdescription, sum(sort_boxgoodvalue) as valueach, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) as totamt, sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";

							$query_mtd1 .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";

							$query_mtd1 .= " WHERE loop_boxes.isbox LIKE 'N' and loop_boxes.type <> 'Waste-to-Energy' and loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date . " 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";

							//echo "Step 5 " . $query_mtd1 . "<br>";

							$res1 = db_query($query_mtd1);

							while ($row_mtd1 = array_shift($res1)) {

								//if ($row_mtd1['sumweight'] > 0){

								$weightval = $row_mtd1['sumweight'];

								$display_flg1 = "y";



								$weight_tot = $weight_tot + $weightval;

								$weightval_tot = $weightval_tot + $weightval;



								$totalval_tot = $totalval_tot + $row_mtd1["totamt"];

								$amt_tot = $amt_tot + $row_mtd1["totamt"];

								//}

							}

						}

					} else if ($outlet_array[$arrycnt] == "Reuse") {



						if ($display_flg2 == "n") {

							$query_mtd1 = "SELECT loop_boxes.vendor, loop_boxes.bdescription, sum(sort_boxgoodvalue) as valueach, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) as totamt, sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight, sum(boxgood + boxbad) as itemcount from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";

							$query_mtd1 .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";

							$query_mtd1 .= " WHERE loop_boxes.isbox LIKE 'Y' and loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date . " 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";

							//echo "Step 6 " . $query_mtd1 . "<br>";

							$res1 = db_query($query_mtd1);

							while ($row_mtd1 = array_shift($res1)) {

								$weightval = $row_mtd1['sumweight'];

								$display_flg2 = "y";

								$weight_tot = $weight_tot + $weightval;

								$weightval_tot = $weightval_tot + $weightval;

								$totalval_tot = $totalval_tot + $row_mtd1["totamt"];

								$amt_tot = $amt_tot + $row_mtd1["totamt"];

								$vendor_name = "Used Cardboard Boxes Inc";

								

							}

						}

					} else if ($outlet_array[$arrycnt] == "Waste To Energy") {



						if ($display_flg3 == "n") {

							$query_mtd1 = "SELECT loop_boxes.vendor, loop_boxes.bdescription, sum(sort_boxgoodvalue) as valueach, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) as totamt, sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight, sum(boxgood + boxbad) as itemcount from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";

							$query_mtd1 .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";

							$query_mtd1 .= " WHERE loop_boxes.type = 'Waste-to-Energy' and loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date . " 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";

							//echo "Step 7 " . $query_mtd1 . "<br>";

							$res1 = db_query($query_mtd1);

							while ($row_mtd1 = array_shift($res1)) {

								//$Ruse_tot = $Ruse_tot + $row_mtd1['sumweight'];

								//if ($row_mtd1['sumweight'] > 0){

								$weightval = $row_mtd1['sumweight'];



								$display_flg3 = "y";



								$weight_tot = $weight_tot + $weightval;

								$weightval_tot = $weightval_tot + $weightval;



								$totalval_tot = $totalval_tot + $row_mtd1["totamt"];

								$amt_tot = $amt_tot + $row_mtd1["totamt"];



								$vendor_name = "Used Cardboard Boxes Inc";

								//}

							}

						}

					}



					//echo "weight_tot" . $weight_tot . "<br>";

					$query_mtd  = "SELECT sum(weight_in_pound) as weightval, sum(avg_price_per_pound) as valueeachval, sum(total_value) as totalval, sum(unit_count) as itemcount, vendor_id, water_boxes_report_data.*, water_inventory.* from water_boxes_report_data inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID ";

					$query_mtd .= " inner join water_transaction on water_boxes_report_data.trans_rec_id = water_transaction.id inner join water_vendors on water_transaction.vendor_id = water_vendors.id WHERE water_vendors.id <> 844 and warehouse_id in (" . $warehouse_id . ") 

				and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') and water_inventory.Outlet = '" . $outlet_array[$arrycnt] . "'";

					$query_mtd .= " group by water_transaction.vendor_id, water_boxes_report_data.box_id order by water_vendors.Name, water_boxes_report_data.box_id";

					//and AmountUnitEquivalent <> 'Gallon'

					//echo "Step 8 " . $query_mtd . "<br>";

					$res = db_query($query_mtd);

					while ($row_mtd = array_shift($res)) {

						$weightval = $row_mtd["weightval"];



						$weight_tot = $weight_tot + $weightval;

						$weightval_tot = $weightval_tot + $weightval;



						if ($row_mtd["CostOrRevenuePerUnit"] == "Cost Per Unit" || $row_mtd["CostOrRevenuePerItem"] == "Cost Per Item" || $row_mtd["CostOrRevenuePerPull"] == "Cost Per Pull") {

							//echo "In the negative values <br>";

							$totalval_tot = $totalval_tot - floatval($row_mtd["totalval"]);

							$amt_tot = $amt_tot - floatval($row_mtd["totalval"]);

						} else {

							$totalval_tot = $totalval_tot + floatval($row_mtd["totalval"]);

							$amt_tot = $amt_tot + floatval($row_mtd["totalval"]);

						}

					}

					//echo "weight_tot - " . $weight_tot . "<br>";

					$weightval_reuse_gallon = 0;

					

					



					$per_val = 0;

					$per_val_2 = 0;

					if ($sumtot > 0) {

						$per_val = number_format(($weight_tot / $sumtot) * 100, 2) . "%";

						$per_val_2 = number_format(($weight_tot / $sumtot) * 100, 2);

						if ($outlet_array[$arrycnt] == "Recycling" || $outlet_array[$arrycnt] == "Reuse" ||  $outlet_array[$arrycnt] == "Waste To Energy") {

							$landfill_diversion = $landfill_diversion + $per_val_2;

						}

					}

					$outlet_tot[] = array('outlet' => $outlet_array[$arrycnt], 'tot' => $weight_tot, 'perc' => $per_val, 'totval' => $amt_tot);



					



					if ($outlet_array[$arrycnt] == 'Reuse') {

						$weight_tot_reuse = $weight_tot;

					}



					//commented on Feb 15 2024 $Recycling_tot . "', '" . $Ruse_tot . "', '" . $WasteToEnergy_tot 

					$res = db_query("Insert into water_cron_summary_rep (data_year, warehouse_id, outlet, weight_tot, perc_val, amount_tot, sumtot_weight, sumtot_amount, other_charges, Recycling_tot,

				Ruse_tot, WasteToEnergy_tot) select '" . $data_year . "', '" . $warehouse_id . "', '" . $outlet_array[$arrycnt] . "', '" . $weight_tot . "', '" . $per_val_2 . "', '" . $amt_tot . "', 

				'" . $sumtot . "' , '" . $totalval_tot . "', '" . $othar_charges . "', '0', '0', '0'");



					$rec_added = "yes";

					$res = db_query("Insert into water_cron_fordash_piechart (data_year, warehouse_id, sumtot, recycling_tot, ruse_tot, WasteToEnergy_tot , outlet, weight, display_order) 

				select '" . $data_year . "', " . $warehouse_id . ", " . $sumtot . ", 0, 0, 0, '" . $outlet_array[$arrycnt] . "', '" . $weight_tot . "', '" . $display_order . "'");

				}

				//For the Summary table in dash



				if ($sumtot_chk > 0) {

					$tree_saved_val = $tree_saved_val + $sumtot_chk;

				}



				//echo "$warehouse_id Tree_saved_val4 : " . $tree_saved_val . "<br>";



				$tree_saved = $tree_saved + $tree_saved_val;



				if ($rec_added == "no") {

					$res = db_query("Insert into water_cron_fordash_piechart (data_year, warehouse_id, sumtot, recycling_tot, ruse_tot, WasteToEnergy_tot , outlet, weight, display_order) select '" . $data_year . "'," . $warehouse_id . ", " . $sumtot . ", " . $Recycling_tot . ", " . $Ruse_tot . ", " . $WasteToEnergy_tot . ", 'Reuse', 0, 1");

					$res = db_query("Insert into water_cron_fordash_piechart (data_year, warehouse_id, sumtot, recycling_tot, ruse_tot, WasteToEnergy_tot , outlet, weight, display_order) select '" . $data_year . "'," . $warehouse_id . ", " . $sumtot . ", " . $Recycling_tot . ", " . $Ruse_tot . ", " . $WasteToEnergy_tot . ", 'Recycling', 0, 1");

				}



				//Total cal

				$weightval_tot_grand = 0;

				$total_cost = 0;

				$totalval_tot = 0;

				$net_finance = 0;

				//outlet <> '' and

				$result_vendor = db_query("SELECT water_vendors.Name, water_vendors.id FROM water_boxes_report_data inner join water_transaction on water_transaction.id = water_boxes_report_data.trans_rec_id inner join water_vendors on water_transaction.vendor_id = water_vendors.id where  water_vendors.id <> 844 and warehouse_id in (" . $warehouse_id . ") and invoice_date between ? and ? group by water_vendors.Name, water_vendors.id order by water_vendors.Name", array("s", "s"), array($st_date, $end_date));

				//echo "Step 8b " . "SELECT water_vendors.Name, water_vendors.id FROM water_boxes_report_data inner join water_transaction on water_transaction.id = water_boxes_report_data.trans_rec_id inner join water_vendors on water_transaction.vendor_id = water_vendors.id where warehouse_id in (" . $warehouse_id . ") and invoice_date between ? and ? group by water_vendors.Name, water_vendors.id order by water_vendors.Name"  . "<br>";

				while ($row_vendor = array_shift($result_vendor)) {

					$weightval_tot = 0;



					$query_mtd  = "SELECT total_value as totalval, vendor_id, water_boxes_report_data.*, water_inventory.* from water_boxes_report_data inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID ";

					$query_mtd .= " inner join water_transaction on water_boxes_report_data.trans_rec_id = water_transaction.id WHERE warehouse_id in (" . $warehouse_id . ") and water_transaction.vendor_id = " . $row_vendor["id"] . " and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') ";

					//echo $query_mtd . "<br>";

					$totalval_tot = 0;

					$res = db_query($query_mtd);

					while ($row_mtd = array_shift($res)) {

						if ($row_mtd["CostOrRevenuePerUnit"] == "Cost Per Unit" || $row_mtd["CostOrRevenuePerItem"] == "Cost Per Item" || $row_mtd["CostOrRevenuePerPull"] == "Cost Per Pull") {

							$totalval_tot = $totalval_tot - floatval($row_mtd["totalval"]);

						} else {

							$totalval_tot = $totalval_tot + floatval($row_mtd["totalval"]);

						}

					}

					//echo "totalval_tot - " . $totalval_tot . "<br>";



					$query_mtd = "SELECT water_vendors.Name as Vendorname, water_additional_fees.additional_fees_display, water_trans_addfees.id as addfeeid, sum(water_trans_addfees.add_fees * water_trans_addfees.add_fees_occurance) as addfees from water_transaction ";

					$query_mtd .= " inner join water_vendors on water_transaction.vendor_id = water_vendors.id ";

					$query_mtd .= " inner join water_trans_addfees on water_trans_addfees.trans_id = water_transaction.id ";

					$query_mtd .= " left join water_additional_fees on water_trans_addfees.add_fees_id = water_additional_fees.id ";

					$query_mtd .= " WHERE  water_vendors.id <> 844 and company_id in (" . $warehouse_id . ") and water_transaction.vendor_id = " . $row_vendor["id"] . " and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') group by water_vendors.Name, water_additional_fees.additional_fees_display";

					//echo $query_mtd . "<br>";

					$add_fees = 0;

					$res = db_query($query_mtd);

					while ($row_mtd = array_shift($res)) {

						//echo "Add fee: " . $row_mtd["addfees"] . "<br>";

						$add_fees = $add_fees + $row_mtd["addfees"];

						$totalval_tot = $totalval_tot - $row_mtd["addfees"];

					}

					//echo "totalval_tot - " . $totalval_tot . "<br>";



					$weightval_tot_grand = $weightval_tot_grand + $weightval_tot;

					$total_cost = $total_cost + $totalval_tot;

				}



				$ucb_item_totamt = 0;

				$query_mtd1 = "SELECT sum(loop_boxes.bweight * boxgood) as sumweight, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) as totamt from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";

				$query_mtd1 .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";

				$query_mtd1 .= " WHERE loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date . " 00:00:00' AND '" . $end_date . " 23:59:59'  group by boxgood";



				$res1 = db_query($query_mtd1);

				while ($row_mtd1 = array_shift($res1)) {

					$ucb_item_totamt = $ucb_item_totamt + $row_mtd1["totamt"];

				}

				//echo "ucb_item_totamt : " . $ucb_item_totamt . "<br>";



				$query_mtd1 = "SELECT distinct loop_transaction.id , loop_transaction.freightcharge as freightcharge, loop_transaction.othercharge as othercharge from loop_transaction inner join loop_boxes_sort on loop_transaction.id = loop_boxes_sort.trans_rec_id ";

				$query_mtd1 .= " WHERE loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date . " 00:00:00' AND '" . $end_date . " 23:59:59' ";
				$res1 = db_query($query_mtd1);

				while ($row_mtd1 = array_shift($res1)) {

					$ucb_item_totamt = $ucb_item_totamt + $row_mtd1["freightcharge"];

					$ucb_item_totamt = $ucb_item_totamt + $row_mtd1["othercharge"];

				}

				//echo "ucb_item_totamt : " . $ucb_item_totamt . "<br>";



				$total_cost = $total_cost + $ucb_item_totamt;



				//For High and Low value

				$MGArray = array();

				$weightval_tot_grand = 0;

				$totalval_tot = 0;

				$net_finance = 0;

				$result_vendor = db_query("SELECT water_vendors.Name, water_vendors.id, water_vendors.logo_image FROM water_boxes_report_data inner join water_transaction on water_transaction.id = water_boxes_report_data.trans_rec_id inner join water_vendors on water_transaction.vendor_id = water_vendors.id where  water_vendors.id <> 844 and outlet <> '' and warehouse_id in (" . $warehouse_id . ") and invoice_date between '" . $st_date . "' and '" . $end_date . "' group by water_vendors.Name, water_vendors.id order by water_vendors.Name");

				while ($row_vendor = array_shift($result_vendor)) {

					$weightval_tot = 0;



					$query_mtd  = "SELECT total_value as totalval, vendor_id, water_boxes_report_data.*, water_inventory.* from water_boxes_report_data inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID ";

					$query_mtd .= " inner join water_transaction on water_boxes_report_data.trans_rec_id = water_transaction.id WHERE warehouse_id in (" . $warehouse_id . ") and water_transaction.vendor_id = " . $row_vendor["id"] . " and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') ";

					$totalval_tot = 0;

					$res = db_query($query_mtd);

					while ($row_mtd = array_shift($res)) {

						if ($row_mtd["CostOrRevenuePerUnit"] == "Cost Per Unit" || $row_mtd["CostOrRevenuePerItem"] == "Cost Per Item" || $row_mtd["CostOrRevenuePerPull"] == "Cost Per Pull") {

							$totalval_tot = $totalval_tot - floatval($row_mtd["totalval"]);

						} else {

							$totalval_tot = $totalval_tot + floatval($row_mtd["totalval"]);

						}

					}



					$query_mtd = "SELECT water_vendors.Name as Vendorname, water_additional_fees.additional_fees_display, water_trans_addfees.id as addfeeid, sum(water_trans_addfees.add_fees * water_trans_addfees.add_fees_occurance) as addfees from water_transaction ";

					$query_mtd .= " inner join water_vendors on water_transaction.vendor_id = water_vendors.id ";

					$query_mtd .= " inner join water_trans_addfees on water_trans_addfees.trans_id = water_transaction.id ";

					$query_mtd .= " left join water_additional_fees on water_trans_addfees.add_fees_id = water_additional_fees.id ";

					$query_mtd .= " WHERE  water_vendors.id <> 844 and company_id in (" . $warehouse_id . ") and water_transaction.vendor_id = " . $row_vendor["id"] . " and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') group by water_vendors.Name, water_additional_fees.additional_fees_display";



					$res = db_query($query_mtd);

					while ($row_mtd = array_shift($res)) {

						//echo "Add fee: " . $row_mtd["addfees"] . "<br>";

						$totalval_tot = $totalval_tot - $row_mtd["addfees"];

					}



					$MGArray[] = array('vendor_id' => $row_vendor["id"], 'logo_image' => $row_vendor["logo_image"], 'totalval_tot' => $totalval_tot);

				}



				$query_mtd = "SELECT sum(loop_boxes.bweight * boxgood) as sumweight,  sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) as totamt from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";

				$query_mtd .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";

				$query_mtd .= " WHERE  loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date . " 00:00:00' AND '" . $end_date . " 23:59:59' ";

				//echo "Step 9 " . $query_mtd . "<br>";

				$res = db_query($query_mtd);

				$amt_tot = 0;

				$tot_show = "y";

				$trans_rec_id_list = "";

				while ($row_mtd = array_shift($res)) {

					$amt_tot = $amt_tot + $row_mtd["totamt"];

				}



				$query_mtd1 = "SELECT distinct loop_transaction.id , loop_transaction.freightcharge as freightcharge, loop_transaction.othercharge as othercharge from loop_transaction inner join loop_boxes_sort on loop_transaction.id = loop_boxes_sort.trans_rec_id ";

				$query_mtd1 .= " WHERE loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date . " 00:00:00' AND '" . $end_date . " 23:59:59'";

				//echo "Step 10 " . $query_mtd1 . "<br>";

				$ucb_otherchgs_tot = 0;

				$res1 = db_query($query_mtd1);
				while ($row_mtd1 = array_shift($res1)) {

					$ucb_otherchgs_tot = $ucb_otherchgs_tot + $row_mtd1["freightcharge"];

					$ucb_otherchgs_tot = $ucb_otherchgs_tot + $row_mtd1["othercharge"];

				}

				//echo "oth fee" . $ucb_otherchgs_tot . "<br>";

				$amt_tot = $amt_tot - $ucb_otherchgs_tot;

				$MGArray[] = array('vendor_id' => 0, 'logo_image' => 'ucblogo.jpg', 'totalval_tot' => $amt_tot);



				$MGArrayNew = $MGArray;



				$MGArraysort_I = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_I[] = $MGArraytmp['totalval_tot'];

				}

				array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);



				$costly_vendor = "";

				$costly_vendor_val = "";

				foreach ($MGArray as $MGArraytmp2) {

					if ($MGArraytmp2["totalval_tot"] != 0) {

						$costly_vendor = $MGArraytmp2["vendor_id"];

						$costly_vendor_val = $MGArraytmp2["totalval_tot"];

						break;

					}

				}



				$MGArraysort_2 = array();



				foreach ($MGArrayNew as $MGArraytmp1) {

					$MGArraysort_2[] = $MGArraytmp1['totalval_tot'];

				}

				array_multisort($MGArraysort_2, SORT_DESC, SORT_NUMERIC, $MGArrayNew);



				//echo $warehouse_id . "<br>";

				//print_r($MGArrayNew);

				//echo "<br>";



				$high_pay_vendor = "";

				$high_pay_vendor_val = "";

				foreach ($MGArrayNew as $MGArraytmp3) {

					if ($MGArraytmp3["totalval_tot"] != 0) {

						$high_pay_vendor = $MGArraytmp3["vendor_id"];

						$high_pay_vendor_val = $MGArraytmp3["totalval_tot"];

						break;

					}

				}



				if ($costly_vendor_val > 0 && $costly_vendor == $high_pay_vendor && $costly_vendor_val == $high_pay_vendor_val) {

					$costly_vendor = 0;

					$costly_vendor_val = 0;

				}

				$res = db_query("Delete from water_cron_fordash where warehouse_id = " . $warehouse_id . " and data_year = '" . $data_year . "'");



				$res = db_query("Insert into water_cron_fordash (data_year, warehouse_id, high_pay_vendor, costly_vendor, tree_saved, waste_financial, high_pay_vendor_val, costly_vendor_val, landfill_diversion) select '" . $data_year . "', '" . $warehouse_id . "', '" . $high_pay_vendor . "', '" . $costly_vendor . "', '" . $tree_saved . "', '" . $total_cost . "', '" . $high_pay_vendor_val . "' , '" . $costly_vendor_val . "', '" . $landfill_diversion . "'");



				//Invoice page related

				if ($data_year == date("Y")) {

					$selected_month = Date("m") - 1;

				}

				if ($data_year != date("Y")) {

					$selected_month = 12;

				}



				$loginid = $myrowsel["loginid"];

				$parent_comp_flg = $myrowsel['parent_comp_flg'];

				$companyid = $myrowsel["companyid"];



				$query_m = "Delete from vendor_report_greenchecks_cron where `comp_id` = '" . $warehouse_id . "' and year = '" . $data_year . "' and `parent_flg` = 0";

				$q_res = db_query($query_m);



				$query_mtd1 = "(SELECT water_vendors.Name, water_vendors.description, water_vendors.id as vendor from water_comp_vendor_list inner join water_vendors on water_vendors.id = water_comp_vendor_list.vendor_id

			WHERE comp_id = " . $warehouse_id . " group by water_vendors.id ORDER BY water_vendors.Name) order by Name";

				//echo $query_mtd1 . "<br>";

				$res1 = db_query($query_mtd1);

				while ($row_vendor = array_shift($res1)) {

					$vender_nm = $row_vendor['Name'];

					$main_material = $row_vendor['description'];

					$vendorid = $row_vendor['vendor'];

					//echo $vender_nm."--".$row_vendor['vendor']."<br>";

					for ($month_cnt = 1, $n = $selected_month; $month_cnt <= $n; $month_cnt++) {



						//echo $month_cnt."==";



						$query = "SELECT company_id, have_doubt, doubt, no_invoice_due_flg, amount from water_transaction where company_id = " . $warehouse_id . " and vendor_id = " . $row_vendor['vendor'] . " and Month(invoice_date) = " . $month_cnt . " and Year(invoice_date) = " . $data_year;



						//echo $query . "<br>";

						$final_tot = 0;

						$final_cnt = 0;

						$res = db_query($query);

						$rec_found = "no";

						$have_doubt = "";

						$doubt_txt = "";

						$no_invoice_due_flg = "";

						while ($row = array_shift($res)) {

							$final_tot = $final_tot + $row["amount"];

							$final_cnt = $final_cnt + 1;



							$rec_found = "yes";

							if ($row["have_doubt"] == 1) {

								$have_doubt = "yes";

								$doubt_txt = $row["doubt"];

							}



							if ($row["no_invoice_due_flg"] == 1) {

								$rec_found = "no";

								$no_invoice_due_flg = "yes";

							} else {

								$no_invoice_due_flg = "no";

							}

						}



						if ($rec_found == "yes") {

							if ($have_doubt == "yes") {

								$checkval = "info_needed";

							} else {

								$checkval = "successful";

							}

						} else {



							if ($no_invoice_due_flg != "yes") {

								$checkval = "pending";

							} else {

								$checkval = "not_due";

							}

						}



						$query_m = "INSERT INTO `vendor_report_greenchecks_cron` (`loginid`, `comp_id`, `parent_flg`,`vendor_id`, `vendor`, `description`, 

					`year`, `month1`, `check_val`, `month3`, `month4`, `month5`, `month6`, `month7`, `month8`, `month9`, `month10`, `month11`,

					`month12`, `no_of_entries`, `total`, `doubt_txt`) VALUES ('" . $loginid . "', '" . $warehouse_id . "', '0', '" . $vendorid . "', '" . str_replace("'", "\'", $vender_nm) . "', '" . str_replace("'", "\'", $main_material) . "', '" . $data_year . "', '" . $month_cnt . "', '" . $checkval . "', '', '', '', '', '', '', '', '', '', '', '" . $final_cnt . "', '" . $final_tot . "', '" . str_replace("'", "\'", ($doubt_txt)) . "')";

						$q_res = db_query($query_m);

					}

					for ($month_cnt = $selected_month, $n = 12; $month_cnt < $n; $month_cnt++) {

						$checkval = "not_due";

						//echo $checkval."<br>";

						//

						$months = $month_cnt + 1;

						$query_m = "INSERT INTO `vendor_report_greenchecks_cron` (`loginid`, `comp_id`, `parent_flg`,`vendor_id`, `vendor`, 

					`description`, `year`, `month1`, `check_val`, `month3`, `month4`, `month5`, `month6`, `month7`, `month8`, `month9`, 

					`month10`, `month11`, `month12`, `no_of_entries`, `total`, `doubt_txt`) VALUES ('" . $loginid . "', '" . $warehouse_id . "', '0', '" . $vendorid . "', '" . str_replace("'", "\'", $vender_nm) . "', '" . str_replace("'", "\'", $main_material) . "', '" . $data_year . "', '" . $months . "', '" . $checkval . "', '', '', '', '', '', '', '', '', '', '', '" . $final_cnt . "', '" . $final_tot . "', '" . str_replace("'", "\'", ($doubt_txt)) . "')";

						$q_res = db_query($query_m);

						//

					}

				}



				//For UsedCardboardBoxes

				$data_foun_ucb_firstrec = "no";

				$query = "SELECT loop_transaction.id from loop_transaction ";

				$query .= " WHERE loop_transaction.warehouse_id = " . $warehouse_id . " and sort_entered = 1 and DATE_FORMAT(loop_transaction.pr_requestdate_php, '%Y') = " . $data_year . "";

				$res = db_query($query);

				while ($row = array_shift($res)) {

					$data_foun_ucb_firstrec = "yes";

				}



				$str_rep = "";

				$data_no_printed_loop = "n";

				$grandtotal = 0;

				for ($month_cnt = 1, $n = 12; $month_cnt <= $n; $month_cnt++) {

					if ($month_cnt > $selected_month) {

						//$str_rep .= "<td class='colunm4' ><img src='images/grayout.jpg' /></td>";



						if ($data_foun_ucb_firstrec == "yes") {

							$query_m = "INSERT INTO `vendor_report_greenchecks_cron` (`loginid`, `comp_id`, `parent_flg`,`vendor_id`, `vendor`, 

						`description`, `year`, `month1`, `check_val`, `month3`, `month4`, `month5`, `month6`, `month7`, `month8`, `month9`, 

						`month10`, `month11`, `month12`, `no_of_entries`, `total`, `doubt_txt`) VALUES ('" . $loginid . "', '" . $warehouse_id . "', 

						'0', '0', 'UsedCardboardBoxes', 'Boxes', '" . $data_year . "', 

						'" . $month_cnt . "', 'not_due', '', '', '', '', '', '', '', '', '', '', '0', '0', '')";

							$q_res = db_query($query_m);

						}

					} else {

						$query = "SELECT loop_transaction.id from loop_transaction ";

						$query .= " WHERE loop_transaction.warehouse_id = " . $warehouse_id . " and sort_entered = 1 and DATE_FORMAT(loop_transaction.pr_requestdate_php, '%Y') = " . $data_year . " and DATE_FORMAT(loop_transaction.pr_requestdate_php, '%m') = " . $month_cnt . " ";

						//echo $query . "<br>";

						$res = db_query($query);

						$rec_found = "no";

						$final_cnt = 0;

						while ($row = array_shift($res)) {

							$rec_found = "yes";

							$final_cnt = $final_cnt + 1;

						}



						$data_no_printed_loop = "y";

						$checkval = "pending";



						if ($rec_found == "yes") {



							$start_date_child = date("Y-m-d", strtotime($data_year . "-" . $month_cnt . "-01 00:00:00"));

							$end_date_child =  date("Y-m-d H:i:s", strtotime($data_year . "-" . $month_cnt . "-" . date("t", strtotime($start_date_child)) . " 23:59:59"));



							$qry_child = "SELECT loop_transaction.*, loop_boxes.bdescription, loop_boxes_sort.boxgood, loop_boxes_sort.sort_boxgoodvalue, loop_boxes_sort.boxbad, loop_boxes.bweight FROM loop_boxes_sort 

						INNER JOIN loop_transaction ON loop_transaction.id = loop_boxes_sort.trans_rec_id 

						INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_transaction.warehouse_id = '" . $warehouse_id . "' 

						AND loop_transaction.pr_requestdate_php BETWEEN '" . $start_date_child . "' AND '" . $end_date_child . "' 

						AND (loop_boxes.isbox LIKE 'Y' or loop_boxes.isbox LIKE 'N') AND (loop_boxes_sort.boxgood > 0 OR loop_boxes_sort.boxbad > 0)  

						ORDER BY loop_transaction.id DESC";



							$res = db_query($qry_child);

							$grandtotal = 0;

							foreach ($res as $resK => $resV) {

								$boxProd = $resV["sort_boxgoodvalue"] * $resV["boxgood"];

								$grandtotal = $grandtotal + $boxProd;

							}

							$grandtotal = $grandtotal;

							$checkval = "successful";



							if ($data_foun_ucb_firstrec == "yes") {

								$query_m = "INSERT INTO `vendor_report_greenchecks_cron` (`loginid`, `comp_id`, `parent_flg`,`vendor_id`, `vendor`, 

							`description`, `year`, `month1`, `check_val`, `month3`, `month4`, `month5`, `month6`, `month7`, `month8`, `month9`, 

							`month10`, `month11`, `month12`, `no_of_entries`, `total`, `doubt_txt`) VALUES ('" . $loginid . "', '" . $warehouse_id . "', 

							'0', '0', 'UsedCardboardBoxes', 'Boxes', '" . $data_year . "', 

							'" . $month_cnt . "', 'successful', '', '', '', '', '', '', '', '', '', '', '" . $final_cnt . "', '" . $grandtotal . "', '')";

								$q_res = db_query($query_m);

							}

						} else {

							$str_rep .= "<td class='colunm4' ><img src='images/icon-02.jpg' /></td>";



							if ($data_foun_ucb_firstrec == "yes") {

								$query_m = "INSERT INTO `vendor_report_greenchecks_cron` (`loginid`, `comp_id`, `parent_flg`,`vendor_id`, `vendor`, 

							`description`, `year`, `month1`, `check_val`, `month3`, `month4`, `month5`, `month6`, `month7`, `month8`, `month9`, 

							`month10`, `month11`, `month12`, `no_of_entries`, `total`, `doubt_txt`) VALUES ('" . $loginid . "', '" . $warehouse_id . "', 

							'0', '0', 'UsedCardboardBoxes', 'Boxes', '" . $data_year . "', 

							'" . $month_cnt . "', 'pending', '', '', '', '', '', '', '', '', '', '', '0', '0', '')";

								$q_res = db_query($query_m);

							}

						}

					}

				}
				echo "All Cron data updated.";

			}

		}

		?>

	</div>

</body>



</html>