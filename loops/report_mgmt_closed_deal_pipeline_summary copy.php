<?php
ini_set("display_errors", "1");
error_reporting(E_ERROR);
if ($_REQUEST["no_sess"] == "yes") {
} else {
	require("inc/header_session.php");
}

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

$sel_cdeal = "n";

if (isset($_REQUEST["sel_cdeal"]) && $_REQUEST["sel_cdeal"] == 2) {
	$date_from = isset($_REQUEST["date_from"]) ? strtotime($_REQUEST["date_from"]) : strtotime(date('01/01/Y'));
	$date_to = isset($_REQUEST["date_to"]) ? strtotime($_REQUEST["date_to"]) : strtotime(date('m/d/Y'));

	$date_from = date('Y-m-d', $date_from);
	//$date_to = date('Y-m-d', $date_to + 86400);
	$date_to = date('Y-m-d 23:59:59', $date_to);

	if ($date_from > $date_to) {
		echo "<font size=20>Nice Try, David - You thought I would not catch an error where the start date comes after the end date.</font>";
	} else {
		$sel_cdeal = "y";
	}
} else {
	$date_from = date('Y-01-01');
	$date_to = date('Y-m-d 23:59:59');
}

$pallet_str = "  and Leaderboard = 'B2B' ";
if (isset($_REQUEST["pallet_flg"]) && $_REQUEST["pallet_flg"] == "yes") {
	$pallet_str = " and Leaderboard = 'PALLETS' ";
}
?>
<!DOCTYPE html>
<html>

<head>
	<title>Closed Deal Pipeline Summary Report</title>
	<!-- <meta http-equiv="refresh" content="1800" /> -->
	<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>

	<style type="text/css">
		.txtstyle_color {
			font-family: arial;
			font-size: 12;
			height: 16px;
			background: #ABC5DF;
		}

		.left {
			float: left;
			padding: 0 15px 10px 4px;
		}

		#dt_from_to {
			display: none;
		}

		a,
		a:visied,
		a:hover {
			color: blue;
		}

		.black_overlay {
			display: none;
			position: absolute;
			top: 0%;
			left: 0%;
			width: 100%;
			height: 100%;
			background-color: gray;
			z-index: 1001;
			-moz-opacity: 0.8;
			opacity: .80;
			filter: alpha(opacity=80);
		}

		.white_content {
			display: none;
			position: absolute;
			padding: 5px;
			border: 2px solid black;
			background-color: white;
			overflow: auto;
			height: 500px;
			width: 950px;
			z-index: 1002;
			margin: 0px 0 0 0px;
			padding: 10px 10px 10px 10px;
			border-color: black;
			border-width: 2px;
			overflow: auto;
		}

		table {
			font-size: 12;
		}

		table th {
			background-color: #ABC5DF;
		}

		table table tr:nth-child(even) {
			background-color: #efefef;
		}

		table table tr:nth-child(odd) {
			background-color: #D9D9D9;
		}

		a,
		a:visied,
		a:hover {
			color: blue;
		}
	</style>

	<script language="JavaScript" src="inc/CalendarPopup.js"></script>
	<script language="JavaScript" src="inc/general.js"></script>
	<script language="JavaScript">
		document.write(getCalendarStyles());
	</script>
	<script language="JavaScript">
		var cal = new CalendarPopup("listdiv");
		cal.showNavigationDropdowns();

		function load_div(id) {
			var element = document.getElementById(id); //replace elementId with your element's Id.
			var rect = element.getBoundingClientRect();
			var elementLeft, elementTop; //x and y
			var scrollTop = document.documentElement.scrollTop ?
				document.documentElement.scrollTop : document.body.scrollTop;
			var scrollLeft = document.documentElement.scrollLeft ?
				document.documentElement.scrollLeft : document.body.scrollLeft;
			elementTop = rect.top + scrollTop;
			elementLeft = rect.left + scrollLeft;

			document.getElementById("light").innerHTML = document.getElementById(id).innerHTML;
			document.getElementById('light').style.display = 'block';
			document.getElementById('fade').style.display = 'block';
			document.getElementById('light').style.left = '100px';
			document.getElementById('light').style.top = elementTop + 100 + 'px';
		}


		function close_div() {
			document.getElementById('light').style.display = 'none';
			document.getElementById('fade').style.display = 'none';
		}

		function show_date_fields(e) {
			//alert(e);
			if (e == 2) {
				document.getElementById("dt_from_to").style.display = "block";
			} else {
				document.getElementById("dt_from_to").style.display = "none";
				document.getElementById("date_from").style.display = "";
				document.getElementById("date_to").style.display = "";
			}
		}
	</script>
</head>

<body>
	<?php include("inc/header.php"); ?>
	<div id="fade" class="black_overlay"></div>
	<div id="light" class="white_content"></div>

	<div class="main_data_css">
		<div class="dashboard_heading" style="float: left;">
			<div style="float: left;">
				Closed Deal Pipeline Summary Report
				<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
					<span class="tooltiptext">
						This report shows the user all outstanding B2B sales transactions and what part of the order-to-cash cycle they are in. Every transaction entered starts at the left, and works itself to the right in the process sequentially. You can also use a date range tool and understand what orders are planned to be delivered within the date range, to better understand how much remains to be done to hit quota when you back out what we already plan to deliver.
					</span>
				</div>

				<div style="height: 13px;">&nbsp;</div>
			</div>
		</div>
		<div>
			<form name="cls_deal_pp_history" id="cls_deal_pp_history" action="report_mgmt_closed_deal_pipeline_summary.php" method="GET">
				<table cellSpacing="1" cellPadding="1" border="0" width="1230">
					<tr>
						<th colspan="8" align="left">
							Closed Deal Pipeline Summary -History
						</th>
					</tr>
					<tr>
						<td width="80px;">Select Year:</td>
						<td width="60px;" align="left">
							<select name="year_val" id="year_val">
								<?php
								for ($year_cnt = date("Y") - 4; $year_cnt <= date("Y"); $year_cnt++) {
								?>
									<option value="<?php echo $year_cnt; ?>" <?php if ($year_cnt == $_REQUEST["year_val"]  || $year_cnt == date("Y")) echo " selected "; ?>><?php echo $year_cnt; ?></option>
								<?php } ?>
							</select>
						</td>
						<td width="80px;">Month:</td>
						<td width="60px;" align="left">
							<select name="month_val" id="month_val">
								<?php
								for ($month_cnt = 1; $month_cnt <= 12; $month_cnt++) {
								?>
									<option value="<?php echo $month_cnt; ?>" <?php if ($month_cnt == $_REQUEST["month_val"]) echo " selected "; ?>><?php echo $month_cnt; ?></option>
								<?php } ?>
							</select>
						</td>
						<!-- code by dipesh start -->
						<td width="80px;">LeaderBoard:</td>
						<td width="60px;" align="left">
							<select name="leaderboard" id="leaderboard">
								<option value="b2b" <?php if ($_REQUEST["leaderboard"] == "b2b") echo " selected "; ?>>B2B</option>
								<option value="PALLETS" <?php if ($_REQUEST["leaderboard"] == "PALLETS") echo " selected "; ?>>PALLETS</option>
							</select>
						</td>
						<!-- code by dipesh  -->
						<td width="80px;">Monthly/Weekly:</td>
						<td width="60px;" align="left">
							<select name="week_month_flg" id="week_month_flg">
								<option>Please Select</option>
								<option value="weekly" <?php if ($_REQUEST["month_val"] == "weekly") echo " selected "; ?>>Weekly</option>
								<option value="monthly" <?php if ($_REQUEST["month_val"] == "monthly") echo " selected "; ?>>Monthly</option>
							</select>
						</td>
						<td><input type="submit" id="btnsubmitrep" name="btnsubmitrep" value="Submit" /></td>
					</tr>
				</table>
			</form>
			<?php if (isset($_REQUEST["btnsubmitrep"])) {
				//report_frequency = 'WEEK'
				$report_frequency_str = " ";
				if ($_REQUEST["week_month_flg"] == "weekly") {
					$report_frequency_str = " and report_frequency = 'WEEK' ";
				}
				if ($_REQUEST["week_month_flg"] == "monthly") {
					$report_frequency_str = " and report_frequency = 'MONTH' ";
				}

				function RevenueANDGProfitData($qryResult)
				{
					while ($row = array_shift($qryResult)) {
						$year = $month = $type_name = $date_range = "";
						$year = $row["report_year"];
						$report_frequency = $row["report_frequency"];
						$month = str_pad($row["report_month"], 2, "0", STR_PAD_LEFT);
						$leaderboard = $row['leaderboard'];
						if ($row["report_type"] == 1) {
							$type_name = "Revenue";
						} else {
							$type_name = "Gross Profit";
						}

						if ($row["report_range"] == 1) {
							$date_range = "All as of Today";
						} else {
							$dt = $row["date_range"];
							$date_range = "Planned Delivery within date<br>range $dt";
						}

						echo '<tr><td>' . $report_frequency . '</td>';
						echo '<td>' . $year . '</td><td>';
						echo $month . '</td><td style="text-align: center">';
						echo strtoupper($leaderboard) . '</td><td>';
						echo $type_name . '</td><td>';
						echo $date_range . '</td><td  style="text-align: center">';
						echo '<a href="report_mgmt_closed_deal_pipeline_one.php?id=' . $row["id"] . '" target="_blank">View Report</a>';
						echo '</td></tr>';
					}
				}
			?>
				<table cellSpacing="1" cellPadding="1" border="0" width="1230">
					<tr>
						<td align="left" valign="top">
							<table cellSpacing="1" cellPadding="1" border="0" width="600">
								<tr>
									<th colspan="7">Start of week Revenue Data</th>
								</tr>
								<tr>
									<th>Type</th>
									<th>Year</th>
									<th>Month</th>
									<th>Leaderboard</th>
									<th>Type</th>
									<th>Date</th>
									<th>View</th>
								</tr>
								<?php
								db();
								$qry = db_query("SELECT * FROM closed_pipeline where report_year = '" . $_REQUEST["year_val"] . "' and leaderboard='" . $_REQUEST["leaderboard"] . "' and week_type = 1 and report_type = 1 and report_month = '" . $_REQUEST["month_val"] . "'  $report_frequency_str ORDER BY id DESC");
								RevenueANDGProfitData($qry);
								?>
							</table>
						</td>
						<td align="left" valign="top">
							<table cellSpacing="1" cellPadding="1" border="0" width="600">
								<tr>
									<th colspan="7">Start of week Gross Profit Data</th>
								</tr>
								<tr>
									<th>Type</th>
									<th>Year</th>
									<th>Month</th>
									<th>Leaderboard</th>
									<th>Type</th>
									<th>Date</th>
									<th>View</th>
								</tr>
								<?php
								db();
								$qry = db_query("SELECT * FROM closed_pipeline where report_year = '" . $_REQUEST["year_val"] . "' and leaderboard='" . $_REQUEST["leaderboard"] . "' and week_type = 1 and report_type != 1 and  report_month = '" . $_REQUEST["month_val"] . "'  $report_frequency_str ORDER BY id DESC");
								RevenueANDGProfitData($qry);
								?>
							</table>
						</td>
					</tr>
					<tr>
						<td align="left" valign="top">
							<table cellSpacing="1" cellPadding="1" border="0" width="600">
								<tr>
									<th colspan="7">End of week Revenue Data</th>
								</tr>
								<tr>
									<th>Type</th>
									<th>Year</th>
									<th>Month</th>
									<th>Leaderboard</th>
									<th>Type</th>
									<th>Date</th>
									<th>View</th>
								</tr>
								<?php
								db();
								$qry = db_query("SELECT * FROM closed_pipeline where report_year = '" . $_REQUEST["year_val"] . "' and leaderboard='" . $_REQUEST["leaderboard"] . "' and week_type = 2 and report_type = 1 and report_month = '" . $_REQUEST["month_val"] . "'  $report_frequency_str ORDER BY id DESC");
								RevenueANDGProfitData($qry);
								?>
							</table>
						</td>
						<td align="left" valign="top">
							<table cellSpacing="1" cellPadding="1" border="0" width="600">
								<tr>
									<th colspan="7">End of week Gross Profit Data</th>
								</tr>
								<tr>
									<th>Type</th>
									<th>Year</th>
									<th>Month</th>
									<th>Leaderboard</th>
									<th>Type</th>
									<th>Date</th>
									<th>View</th>
								</tr>
								<?php
								db();
								$qry = db_query("SELECT * FROM closed_pipeline where report_year = '" . $_REQUEST["year_val"] . "' and leaderboard='" . $_REQUEST["leaderboard"] . "' and week_type = 2 and report_type != 1 and  report_month = '" . $_REQUEST["month_val"] . "'  $report_frequency_str ORDER BY id DESC");
								RevenueANDGProfitData($qry);
								?>
							</table>
						</td>
					</tr>
				</table>
			<?php } ?>
		</div>
		<br><br>
		<div>
			<form name="cls_deal_pp" action="report_mgmt_closed_deal_pipeline_summary.php" method="GET">
				<table cellSpacing="1" cellPadding="1" border="0" width="1230">
					<tr>
						<th colspan="8" align="left">
							Closed Deal Pipeline Summary
						</th>
					</tr>
				</table>
				<div class="left" width="">
					<select name="protype" id="protype">
						<option value="1" <?php echo ($_REQUEST['protype'] == 1) ? 'selected' : ''; ?>>Revenue</option>
						<option value="2" <?php echo ($_REQUEST['protype'] == 2) ? 'selected' : ''; ?>>Gross Profit</option>
					</select>
				</div>
				<div class="left" width="250px">
					<select name="sel_cdeal" id="sel_cdeal" onchange="show_date_fields(this.value)">
						<option value="1" <?php echo ($_REQUEST['sel_cdeal'] == 1) ? 'selected' : ''; ?>>ALL as of today</option>
						<option value="2" <?php echo ($_REQUEST['sel_cdeal'] == 2) ? 'selected' : ''; ?>>Planned delivery within date range</option>
					</select>
				</div>
				<div class="left" id="dt_from_to" width="350">
					From:
					<input type="text" name="date_from" id="date_from" size="10" value="<?php echo $_REQUEST['date_from'] ?? ''; ?>">
					<a href="#" onclick="cal.select(document.cls_deal_pp.date_from,'dtanchor1','MM/dd/yyyy'); return false;" name="dtanchor1" id="dtanchor1"><img border="0" src="images/calendar.jpg"></a>
					<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
					To:
					<input type="text" name="date_to" id="date_to" size="10" value="<?php echo $_REQUEST['date_to'] ?? ''; ?>">
					<a href="#" onclick="cal.select(document.cls_deal_pp.date_to,'dtanchor2','MM/dd/yyyy'); return false;" name="dtanchor2" id="dtanchor2"><img border="0" src="images/calendar.jpg"></a>
					<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
				</div>
				<?php
				if ($sel_cdeal == "y") {
					echo "<script>show_date_fields(2);</script>";
				}
				?>
				<div class="left" width="30">
					<input type="submit" id="btnsubmit" name="btnsubmit" value="Run Report">
					<input type="hidden" id="pallet_flg" name="pallet_flg" value="<?php echo $_REQUEST['pallet_flg']; ?>">
				</div>
			</form>
			<br>
		</div>

		<?php if (isset($_REQUEST["btnsubmit"])) {
			if ($_REQUEST["protype"] == 1) {
				$reve_gprofit = "showing Revenue";
			}
			if ($_REQUEST["protype"] == 2) {
				$reve_gprofit = "showing Gross Profit";
			}
		?>
			<table cellSpacing="1" cellPadding="1" border="0" width="1200">
				<tr>
					<td bgColor='#EAF1DD' align="center" colspan="16"><strong>Closed Deal Pipeline Summary <?php echo $reve_gprofit; ?>
							<?php
							if ($sel_cdeal == "y") {

								echo date("m/d/Y", strtotime($date_from)) . " to " . date("m/d/Y", strtotime($_REQUEST["date_to"]));
							} else {
								echo "As of " . date("m/d/Y");
							}
							?> </strong></td>
				</tr>
				<tr>
					<td bgColor='#EAF1DD' width="250"><strong><u>Employee</u></strong></td>
					<td bgColor='#EAF1DD' width="60"><strong><u>
								<center>PO Not Entered Yet</center>
							</u></strong></td>
					<td bgColor='#EAF1DD' width="60"><strong><u>
								<center>PO Uploaded,<br>Initial Steps Incomplete</center>
							</u></strong></td>
					<td bgColor='#EAF1DD' width="60"><strong><u>
								<center>Customer Not Ready<br>Pre-Order)</center>
							</u></strong></td>
					<td bgColor='#EAF1DD' width="60"><strong><u>
								<center>Customer Ready,<br>Checking Inventory</center>
							</u></strong></td>
					<td bgColor='#EAF1DD' width="60"><strong><u>
								<center>Need to Enter<br>into TMS</center>
							</u></strong></td>
					<td bgColor='#EAF1DD' width="60"><strong><u>
								<center>Need to Tender Lane</center>
							</u></strong></td>
					<td bgColor='#EAF1DD' width="60"><strong><u>
								<center>Lane Tendered,<br>Set Dock Appointments</center>
							</u></strong></td>
					<td bgColor='#EAF1DD' width="60"><strong><u>
								<center>BOL Needs Created<br>and Shipped</center>
							</u></strong></td>
					<td bgColor='#EAF1DD' width="60"><strong><u>
								<center>On The Road</center>
							</u></strong></td>
					<!--<td bgColor='#EAF1DD' width="60"><strong><u><center>Delivered, Needs Survey</center></u></strong></td>-->
					<td bgColor='#EAF1DD' width="60"><strong><u>
								<center>Request Invoice</center>
							</u></strong></td>
					<td bgColor='#FFF2D0' width="60"><strong><u>
								<center>Need QB Invoice<br>Uploaded</center>
							</u></strong></td>
					<td bgColor='#FFF2D0' width="60"><strong><u>
								<center>Awaiting Payment</center>
							</u></strong></td>
					<td bgColor='#FFF2D0' width="60"><strong><u>
								<center>Double Checks for Payroll</center>
							</u></strong></td>
					<td bgColor='#FFF2D0' width="60"><strong><u>
								<center>Completed Double Checks for Payroll</center>
							</u></strong></td>

				</tr>
				<?php
				$unqid = 99999;

				$span_header_str = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='940'>";
				$span_header_str .= "<tr><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Customer</td><td class='txtstyle_color'>Supplier</td><td class='txtstyle_color'>Description</td><td class='txtstyle_color'>Original Planned Delivery Date</td><td class='txtstyle_color'>Most Recent Planned Delivery Date</td><td class='txtstyle_color'>Actual Delivery Date</td><td class='txtstyle_color'>";
				if ($_REQUEST["protype"] == 2) {
					$span_header_str .= "Gross Profit";
				} else {
					$span_header_str .= "PO Amount";
				}
				$span_header_str .= "</td><td class='txtstyle_color'>Profit Margin</td></tr>";
				$span_bottom_str = "</span></table>";

				$sql = "SELECT * FROM loop_employees WHERE status = 'Active' ORDER BY quota DESC";
				db();
				$result = db_query($sql);
				$DATArray = array();
				while ($rowemp = array_shift($result)) {
					$initials = $rowemp["initials"];

					$emp_filter = " and loop_transaction_buyer.po_employee = '$initials'";

					$dt_view_qry = "SELECT po_employee, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount as SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt,
				loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
					$dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
					$dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM 
				loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
					if ($sel_cdeal == "y") {
						$dt_view_qry .= "WHERE loop_transaction_buyer.shipped = 0 $emp_filter  $pallet_str and loop_transaction_buyer.po_date = ''";
						$dt_view_qry .= " and loop_transaction_buyer.po_delivery_dt between '" . $date_from . "' and '" . $date_to . "' and proof_of_delivery = ''";
						$dt_view_qry .= " and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
					} else {

						$dt_view_qry .= "WHERE loop_transaction_buyer.shipped = 0 $emp_filter $pallet_str and loop_transaction_buyer.po_date = '' and loop_transaction_buyer.ignore = 0 and good_to_ship = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
					}

					$dt_view_res = db_query($dt_view_qry);
					$amt2 = 0;
					$lisofdetails2 = "";

					while ($dt_view_row = array_shift($dt_view_res)) {

						if ($_REQUEST['protype'] == 2) {
							$freight_cost = $dt_view_row["po_freight"];
							$po_poorderamount = $dt_view_row["SUMPO"];
							$salesorder_qty = 0;
							$payment_val = 0;

							$get_sales_order = db_query("Select loop_salesorders.qty, loop_boxes.b2b_id, loop_boxes.boxgoodvalue from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($dtt_view_res = array_shift($get_sales_order)) {
								$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
								$boxgoodvalue = $dtt_view_res["boxgoodvalue"];
								$boxgoodvaluearray	= explode(".", $boxgoodvalue);
								$boxgoodvalueDollar = $boxgoodvaluearray[0];
								$boxgoodvalueCents	= "0." . $boxgoodvaluearray[1];

								db_b2b();

								$get_box_data = db_query("Select ulineDollar, ulineCents, costDollar, costCents, overhead_costDollar, overhead_costCents from inventory where ID = " .  $dtt_view_res["b2b_id"]);
								while ($box_data_res = array_shift($get_box_data)) {

									$b2b_ovh_costDollar = round($box_data_res["overhead_costDollar"]);
									$b2b_ovh_costCents = $box_data_res["overhead_costCents"];

									$b2b_costDollar = floatval($boxgoodvalueDollar) + floatval($b2b_ovh_costDollar);
									$b2b_costCents = $boxgoodvalueCents + $b2b_ovh_costCents;

									$b2b_cost = $b2b_costDollar + $b2b_costCents;
								}
							}
							db();
							$sales_qry = db_query("Select qty from loop_salesorders_manual WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($dtt_view_res = array_shift($sales_qry)) {
								$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
							}

							$cogs_val = $b2b_cost * $salesorder_qty;
							$cogs = (-$freight_cost) - $cogs_val;

							$gross_profit = $po_poorderamount + $cogs;

							$payment_val = $gross_profit;
							$amt2 = $amt2 + $gross_profit;
						} else {
							$payment_val = $dt_view_row["SUMPO"];
							$amt2 = $amt2 + $dt_view_row["SUMPO"];
						}

						$bdescription = "";
						$supplier_name = "";
						db();
						$selqry1 = db_query("Select loop_salesorders.box_id, loop_boxes.bdescription, loop_boxes.b2b_id from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
						while ($selres1 = array_shift($selqry1)) {
							$bdescription .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $selres1['box_id'] . "&proc=View'>" . $selres1["bdescription"] . "</a><br>";
							db_b2b();
							$selqry2 = db_query("Select vendor_b2b_rescue from inventory where ID = " . $selres1["b2b_id"]);
							$selres2 = array_shift($selqry2);
							$vendor_b2b_rescue = $selres2["vendor_b2b_rescue"];

							$sname = "";
							db();
							$selqry3 = db_query("SELECT id, company_name, b2bid FROM loop_warehouse where id=" . $vendor_b2b_rescue);
							$selres3 = array_shift($selqry3);
							if (!empty($selres3)) {
								$sname = getnickname($selres3['company_name'], $selres3["b2bid"]);
								$supplier_name .= "<a target='_blank' href='viewCompany.php?ID=" . $selres3["b2bid"] . "'>" . $sname . " (Loop ID: " . $selres3["id"] . " B2B ID:" . $selres3["b2bid"] . ")</a><br>";
							}
						}
						$bdescription = substr($bdescription, 0, strlen($bdescription) - 4);
						$supplier_name = substr($supplier_name, 0, strlen($supplier_name) - 4);
						$vendor_pay = 0;
						$inv_amount = 0;
						db();
						$selqry4 = "SELECT loop_transaction_buyer_payments.estimated_cost from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["id"];
						$selres4 = db_query($selqry4);
						$num_rows1 = tep_db_num_rows($selres4);
						if ($num_rows1 > 0) {

							while ($row4 = array_shift($selres4)) {
								$vendor_pay += $row4["estimated_cost"];
							}
						}

						$inv_amount = $dt_view_row["F"];

						$invoice_amt = 0;
						db();
						$selqry6 = "SELECT * FROM loop_invoice_items WHERE trans_rec_id = " . $dt_view_row["id"] . " ORDER BY id ASC";
						$selres6 = db_query($selqry6);
						while ($inv_row = array_shift($selres6)) {
							$invoice_amt += $inv_row["quantity"] * $inv_row["price"];
						}
						if ($invoice_amt == 0) {
							$invoice_amt = $inv_amount;
						}
						if ($inv_amount == 0 && $invoice_amt > 0) {
							$inv_amount = $invoice_amt;
						}

						$profit_val = number_format(floatval(str_replace(",", "", strval($inv_amount))) - floatval(str_replace(",", "", strval($vendor_pay))), 2);
						$profit_val = str_replace(",", "", $profit_val);
						$inv_amount = str_replace(",", "", $inv_amount);

						$profit_val_per = "";
						if (floatval($inv_amount) > 0) {
							$profit_val_per = abs((floatval(str_replace(",", "", $profit_val)) * 100) / floatval(str_replace(",", "", $inv_amount)));
						}

						if ($profit_val_per != "") {
							if ($profit_val_per >= 30) {
								if ($profit_val < 0) {
									$profit_val_per = "<font color='#008000'>-" . number_format($profit_val_per, 2) . "%</font>";
								} else {
									$profit_val_per = "<font color='#008000'>" . number_format($profit_val_per, 2) . "%</font>";
								}
							} else if ($profit_val_per < 30) {
								if ($profit_val < 0) {
									$profit_val_per = "<font color='#EE3838'>-" . number_format($profit_val_per, 2) . "%</font>";
								} else {
									$profit_val_per = "<font color='#EE3838'>" . number_format($profit_val_per, 2) . "%</font>";
								}
							}
						}

						//$nickname = "";
						//$nickname = getnickname($dt_view_row["warehouse_name"], $dt_view_row["b2bid"]);
						$warehouse_name = $dt_view_row["warehouse_name"] ?? '';  // Default to an empty string if null
						$nickname = getnickname($warehouse_name, $dt_view_row["b2bid"]);


						$org_delivery_dt = "";
						$po_delivery_dt = "";
						$actualDel_dt = "";
						if ($dt_view_row["po_delivery_dt"] != "") {
							$h_qry = "select planned_delivery_dt from planned_delivery_date_history where trans_id=" . $dt_view_row["id"] . " order by id ASC limit 1";
							$h_res = db_query($h_qry);
							$cnt_rw1 = tep_db_num_rows($h_res);
							if ($cnt_rw1 > 0) {
								while ($row1 = array_shift($h_res)) {
									$org_delivery_dt = date('m/d/Y', strtotime($row1["planned_delivery_dt"]));
								}
							} else {
								$org_delivery_dt = date('m/d/Y', strtotime($dt_view_row['po_delivery_dt']));
							}

							$po_delivery_dt = date('m/d/Y', strtotime($dt_view_row["po_delivery_dt"]));
						}
						db();
						$sql_act = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $dt_view_row["id"];
						$sql_res = db_query($sql_act);
						$cnt_rw1 = tep_db_num_rows($sql_res);
						if ($cnt_rw1 > 0) {
							while ($row = array_shift($sql_res)) {
								if ($row["bol_shipment_received_date"] != "") {
									$actualDel_dt = date('m/d/Y', strtotime($row["bol_shipment_received_date"]));
								}
							}
						}
						$warehouse_name = $dt_view_row["warehouse_name"] ?? '';
						$lisofdetails2 .= "<tr><td bgColor='#E4EAEB'>" . $initials . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td>
					<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $supplier_name . "</td><td bgColor='#E4EAEB'>" . $bdescription . "</td>
					<td bgColor='#E4EAEB'>" . $org_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actualDel_dt . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($payment_val, 0) . "</td><td bgColor='#E4EAEB'>" . $profit_val_per . "</td></tr>";
					}

					// for col 3 ( PO Uploaded, Initial Steps Incomplete)
					$dt_view_qry = "SELECT loop_warehouse.warehouse_name, po_employee, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
					$dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
					$dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";

					if ($sel_cdeal == "y") {
						$dt_view_qry .= "WHERE po_sent_to_supplier_flg = 0 $emp_filter  and so_entered = 0  $pallet_str and sent_to_supplier = 0 and loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.po_date <> '' ";
						$dt_view_qry .= " and loop_transaction_buyer.po_delivery_dt between '" . $date_from . "' and '" . $date_to . "' and proof_of_delivery = ''";
						$dt_view_qry .= " and loop_transaction_buyer.ignore = 0 and good_to_ship = 0 and Preorder = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
					} else {

						$dt_view_qry .= "WHERE po_sent_to_supplier_flg = 0 $emp_filter and so_entered = 0  $pallet_str and sent_to_supplier = 0 and loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.po_date <> '' and loop_transaction_buyer.ignore = 0 and good_to_ship = 0 and Preorder = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
					}

					$dt_view_res = db_query($dt_view_qry);
					$amt3 = 0;
					$lisofdetails3 = "";

					while ($dt_view_row = array_shift($dt_view_res)) {
						if ($_REQUEST['protype'] == 2) {
							$freight_cost = $dt_view_row["po_freight"];
							$po_poorderamount = $dt_view_row["SUMPO"];
							$salesorder_qty = 0;
							$payment_val = 0;

							$get_sales_order = db_query("Select loop_salesorders.qty, loop_boxes.b2b_id, loop_boxes.boxgoodvalue from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($dtt_view_res = array_shift($get_sales_order)) {
								$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
								$boxgoodvalue = $dtt_view_res["boxgoodvalue"];
								$boxgoodvaluearray	= explode(".", $boxgoodvalue);
								$boxgoodvalueDollar = $boxgoodvaluearray[0];
								$boxgoodvalueCents	= "0." . $boxgoodvaluearray[1];

								db_b2b();

								$get_box_data = db_query("Select ulineDollar, ulineCents, costDollar, costCents, overhead_costDollar, overhead_costCents  from inventory where ID = " .  $dtt_view_res["b2b_id"]);
								while ($box_data_res = array_shift($get_box_data)) {

									$b2b_ovh_costDollar = round($box_data_res["overhead_costDollar"]);
									$b2b_ovh_costCents = $box_data_res["overhead_costCents"];

									$b2b_costDollar = floatval($boxgoodvalueDollar) + $b2b_ovh_costDollar;
									$b2b_costCents = $boxgoodvalueCents + $b2b_ovh_costCents;

									$b2b_cost = $b2b_costDollar + $b2b_costCents;
								}
							}
							db();
							$sales_qry = db_query("Select qty from loop_salesorders_manual WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($dtt_view_res = array_shift($sales_qry)) {
								$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
							}

							$cogs_val = $b2b_cost * $salesorder_qty;
							$cogs = (-$freight_cost) - $cogs_val;

							$gross_profit = $po_poorderamount + $cogs;

							$payment_val = $gross_profit;
							$amt3 = $amt3 + $gross_profit;
						} else {
							$payment_val = $dt_view_row["SUMPO"];
							$amt3 = $amt3 + $dt_view_row["SUMPO"];
						}

						$bdescription = "";
						$supplier_name = "";
						db();
						$selqry1 = db_query("Select loop_salesorders.box_id, loop_boxes.bdescription, loop_boxes.b2b_id from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
						while ($selres1 = array_shift($selqry1)) {
							$bdescription .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $selres1['box_id'] . "&proc=View'>" . $selres1["bdescription"] . "</a><br>";
							db_b2b();
							$selqry2 = db_query("Select vendor_b2b_rescue from inventory where ID = " . $selres1["b2b_id"]);
							$selres2 = array_shift($selqry2);
							$vendor_b2b_rescue = $selres2["vendor_b2b_rescue"];

							$sname = "";
							db();
							$selqry3 = db_query("SELECT id, company_name, b2bid FROM loop_warehouse where id=" . $vendor_b2b_rescue);
							$selres3 = array_shift($selqry3);
							if (!empty($selres3)) {
								$sname = getnickname($selres3['company_name'], $selres3["b2bid"]);
								$supplier_name .= "<a target='_blank' href='viewCompany.php?ID=" . $selres3["b2bid"] . "'>" . $sname . " (Loop ID: " . $selres3["id"] . " B2B ID:" . $selres3["b2bid"] . ")</a><br>";
							}
						}

						$bdescription = substr($bdescription, 0, strlen($bdescription) - 4);
						$supplier_name = substr($supplier_name, 0, strlen($supplier_name) - 4);
						$vendor_pay = 0;
						$inv_amount = 0;
						db();
						$selqry4 = "SELECT loop_transaction_buyer_payments.estimated_cost from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["id"];
						$selres4 = db_query($selqry4);
						$num_rows1 = tep_db_num_rows($selres4);
						if ($num_rows1 > 0) {

							while ($row4 = array_shift($selres4)) {
								$vendor_pay += $row4["estimated_cost"];
							}
						}

						$inv_amount = $dt_view_row["F"];

						$invoice_amt = 0;
						db();
						$selqry6 = "SELECT * FROM loop_invoice_items WHERE trans_rec_id = " . $dt_view_row["id"] . " ORDER BY id ASC";
						$selres6 = db_query($selqry6);
						while ($inv_row = array_shift($selres6)) {
							$invoice_amt += $inv_row["quantity"] * $inv_row["price"];
						}
						if ($invoice_amt == 0) {
							$invoice_amt = $inv_amount;
						}
						if ($inv_amount == 0 && $invoice_amt > 0) {
							$inv_amount = $invoice_amt;
						}

						$profit_val = number_format(floatval(str_replace(",", "", $inv_amount)) - floatval(str_replace(",", "", strval($vendor_pay))), 2);
						$profit_val = str_replace(",", "", $profit_val);
						$inv_amount = str_replace(",", "", $inv_amount);

						$profit_val_per = "";
						if (floatval($inv_amount) > 0) {
							$profit_val_per = abs((floatval($profit_val) * 100) / floatval($inv_amount));
						}

						if ($profit_val_per != "") {
							if ($profit_val_per >= 30) {
								if ($profit_val < 0) {
									$profit_val_per = "<font color='#008000'>-" . number_format($profit_val_per, 2) . "%</font>";
								} else {
									$profit_val_per = "<font color='#008000'>" . number_format($profit_val_per, 2) . "%</font>";
								}
							} else if ($profit_val_per < 30) {
								if ($profit_val < 0) {
									$profit_val_per = "<font color='#EE3838'>-" . number_format($profit_val_per, 2) . "%</font>";
								} else {
									$profit_val_per = "<font color='#EE3838'>" . number_format($profit_val_per, 2) . "%</font>";
								}
							}
						}

						////$nickname = "";
						$nickname = getnickname($dt_view_row["warehouse_name"], $dt_view_row["b2bid"]);

						$org_delivery_dt = "";
						$po_delivery_dt = "";
						$actualDel_dt = "";
						if ($dt_view_row["po_delivery_dt"] != "") {
							db();
							$h_qry = "select planned_delivery_dt from planned_delivery_date_history where trans_id=" . $dt_view_row["id"] . " order by id ASC limit 1";
							$h_res = db_query($h_qry);
							$cnt_rw1 = tep_db_num_rows($h_res);
							if ($cnt_rw1 > 0) {
								while ($row1 = array_shift($h_res)) {
									$org_delivery_dt = date('m/d/Y', strtotime($row1["planned_delivery_dt"]));
								}
							} else {
								$org_delivery_dt = date('m/d/Y', strtotime($dt_view_row['po_delivery_dt']));
							}

							$po_delivery_dt = date('m/d/Y', strtotime($dt_view_row["po_delivery_dt"]));
						}

						$sql_act = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $dt_view_row["id"];
						$sql_res = db_query($sql_act);
						$cnt_rw1 = tep_db_num_rows($sql_res);
						if ($cnt_rw1 > 0) {
							while ($row = array_shift($sql_res)) {
								if ($row["bol_shipment_received_date"] != "") {
									$actualDel_dt = date('m/d/Y', strtotime($row["bol_shipment_received_date"]));
								}
							}
						}

						$lisofdetails3 .= "<tr><td bgColor='#E4EAEB'>" . $initials . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $supplier_name . "</td><td bgColor='#E4EAEB'>" . $bdescription . "</td>
					<td bgColor='#E4EAEB'>" . $org_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actualDel_dt . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($payment_val, 0) . "</td><td bgColor='#E4EAEB'>" . $profit_val_per . "</td></tr>";
					}

					// for col 4 (Customer Not Ready Pre-Order)
					$dt_view_qry = "SELECT po_employee, ops_delivery_date, booked_delivery_cost, loop_warehouse.warehouse_name, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
					$dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
					$dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";

					if ($sel_cdeal == "y") {
						$dt_view_qry .= "WHERE loop_transaction_buyer.po_date <> '' $emp_filter  $pallet_str and good_to_ship = 0 and Preorder = 1 and loop_transaction_buyer.ignore = 0";
						$dt_view_qry .= " and loop_transaction_buyer.po_delivery_dt between '" . $date_from . "' and '" . $date_to . "' and proof_of_delivery = ''";
						$dt_view_qry .= "  GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
					} else {

						$dt_view_qry .= "WHERE loop_transaction_buyer.po_date <> '' $emp_filter and loop_transaction_buyer.ignore = 0  $pallet_str and good_to_ship = 0 and Preorder = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
					}

					$dt_view_res = db_query($dt_view_qry);
					$amt4 = 0;
					$lisofdetails4 = "";

					while ($dt_view_row = array_shift($dt_view_res)) {
						if ($_REQUEST['protype'] == 2) {
							$freight_cost = $dt_view_row["po_freight"];
							$po_poorderamount = $dt_view_row["SUMPO"];
							$salesorder_qty = 0;
							$payment_val = 0;

							$get_sales_order = db_query("Select loop_salesorders.qty, loop_boxes.b2b_id, loop_boxes.boxgoodvalue from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($dtt_view_res = array_shift($get_sales_order)) {
								$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
								$boxgoodvalue = $dtt_view_res["boxgoodvalue"];
								$boxgoodvaluearray	= explode(".", $boxgoodvalue);
								$boxgoodvalueDollar = $boxgoodvaluearray[0];
								$boxgoodvalueCents	= "0." . $boxgoodvaluearray[1];


								db_b2b();

								$get_box_data = db_query("Select ulineDollar, ulineCents, costDollar, costCents, overhead_costDollar, overhead_costCents  from inventory where ID = " .  $dtt_view_res["b2b_id"]);
								while ($box_data_res = array_shift($get_box_data)) {

									$b2b_ovh_costDollar = round($box_data_res["overhead_costDollar"]);
									$b2b_ovh_costCents = $box_data_res["overhead_costCents"];

									$b2b_costDollar = floatval($boxgoodvalueDollar) + $b2b_ovh_costDollar;
									$b2b_costCents = $boxgoodvalueCents + $b2b_ovh_costCents;

									$b2b_cost = $b2b_costDollar + $b2b_costCents;
								}
							}
							db();
							$sales_qry = db_query("Select qty from loop_salesorders_manual WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($dtt_view_res = array_shift($sales_qry)) {
								$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
							}

							$cogs_val = $b2b_cost * $salesorder_qty;
							$cogs = (-$freight_cost) - $cogs_val;

							$gross_profit = $po_poorderamount + $cogs;

							$payment_val = $gross_profit;
							$amt4 = $amt4 + $gross_profit;
						} else {
							$payment_val = $dt_view_row["SUMPO"];
							$amt4 = $amt4 + $dt_view_row["SUMPO"];
						}

						$bdescription = "";
						$supplier_name = "";
						db();
						$selqry1 = db_query("Select loop_salesorders.box_id, loop_boxes.bdescription, loop_boxes.b2b_id from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
						while ($selres1 = array_shift($selqry1)) {
							$bdescription .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $selres1['box_id'] . "&proc=View'>" . $selres1["bdescription"] . "</a><br>";
							db_b2b();
							$selqry2 = db_query("Select vendor_b2b_rescue from inventory where ID = " . $selres1["b2b_id"]);
							$selres2 = array_shift($selqry2);
							$vendor_b2b_rescue = $selres2["vendor_b2b_rescue"];

							$sname = "";
							db();
							$selqry3 = db_query("SELECT id, company_name, b2bid FROM loop_warehouse where id=" . $vendor_b2b_rescue);
							$selres3 = array_shift($selqry3);
							if (!empty($selres3)) {
								$sname = getnickname($selres3['company_name'], $selres3["b2bid"]);
								$supplier_name .= "<a target='_blank' href='viewCompany.php?ID=" . $selres3["b2bid"] . "'>" . $sname . " (Loop ID: " . $selres3["id"] . " B2B ID:" . $selres3["b2bid"] . ")</a><br>";
							}
						}
						$bdescription = substr($bdescription, 0, strlen($bdescription) - 4);
						$supplier_name = substr($supplier_name, 0, strlen($supplier_name) - 4);
						$vendor_pay = 0;
						$inv_amount = 0;
						db();
						$selqry4 = "SELECT loop_transaction_buyer_payments.estimated_cost from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["id"];
						$selres4 = db_query($selqry4);
						$num_rows1 = tep_db_num_rows($selres4);
						if ($num_rows1 > 0) {

							while ($row4 = array_shift($selres4)) {
								$vendor_pay += $row4["estimated_cost"];
							}
						}

						$inv_amount = $dt_view_row["F"];

						$invoice_amt = 0;
						db();
						$selqry6 = "SELECT * FROM loop_invoice_items WHERE trans_rec_id = " . $dt_view_row["id"] . " ORDER BY id ASC";
						$selres6 = db_query($selqry6);
						while ($inv_row = array_shift($selres6)) {
							$invoice_amt += $inv_row["quantity"] * $inv_row["price"];
						}
						if ($invoice_amt == 0) {
							$invoice_amt = $inv_amount;
						}
						if ($inv_amount == 0 && $invoice_amt > 0) {
							$inv_amount = $invoice_amt;
						}

						$profit_val = number_format(floatval(str_replace(",", "", strval($inv_amount))) - floatval(str_replace(",", "", strval($vendor_pay))), 2);
						$profit_val = str_replace(",", "", $profit_val);
						$inv_amount = str_replace(",", "", $inv_amount);

						$profit_val_per = "";
						if (floatval($inv_amount) > 0) {
							$profit_val_per = abs((floatval($profit_val) * 100) / floatval($inv_amount));
						}

						if ($profit_val_per != "") {
							if ($profit_val_per >= 30) {
								if ($profit_val < 0) {
									$profit_val_per = "<font color='#008000'>-" . number_format($profit_val_per, 2) . "%</font>";
								} else {
									$profit_val_per = "<font color='#008000'>" . number_format($profit_val_per, 2) . "%</font>";
								}
							} else if ($profit_val_per < 30) {
								if ($profit_val < 0) {
									$profit_val_per = "<font color='#EE3838'>-" . number_format($profit_val_per, 2) . "%</font>";
								} else {
									$profit_val_per = "<font color='#EE3838'>" . number_format($profit_val_per, 2) . "%</font>";
								}
							}
						}

						////$nickname = "";
						$nickname = getnickname($dt_view_row["warehouse_name"], $dt_view_row["b2bid"]);

						$org_delivery_dt = "";
						$po_delivery_dt = "";
						$actualDel_dt = "";
						if ($dt_view_row["po_delivery_dt"] != "") {
							$h_qry = "select planned_delivery_dt from planned_delivery_date_history where trans_id=" . $dt_view_row["id"] . " order by id ASC limit 1";
							db();
							$h_res = db_query($h_qry);
							$cnt_rw1 = tep_db_num_rows($h_res);
							if ($cnt_rw1 > 0) {
								while ($row1 = array_shift($h_res)) {
									$org_delivery_dt = date('m/d/Y', strtotime($row1["planned_delivery_dt"]));
								}
							} else {
								$org_delivery_dt = date('m/d/Y', strtotime($dt_view_row['po_delivery_dt']));
							}

							$po_delivery_dt = date('m/d/Y', strtotime($dt_view_row["po_delivery_dt"]));
						}

						$sql_act = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $dt_view_row["id"];
						$sql_res = db_query($sql_act);
						$cnt_rw1 = tep_db_num_rows($sql_res);
						if ($cnt_rw1 > 0) {
							while ($row = array_shift($sql_res)) {
								if ($row["bol_shipment_received_date"] != "") {
									$actualDel_dt = date('m/d/Y', strtotime($row["bol_shipment_received_date"]));
								}
							}
						}

						$lisofdetails4 .= "<tr><td bgColor='#E4EAEB'>" . $initials . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $supplier_name . "</td><td bgColor='#E4EAEB'>" . $bdescription . "</td>
					<td bgColor='#E4EAEB'>" . $org_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actualDel_dt . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($payment_val, 0) . "</td><td bgColor='#E4EAEB'>" . $profit_val_per . "</td></tr>";
					}

					// for col 5 (Customer Ready, Checking Inventory)
					$dt_view_qry = "SELECT po_employee, ops_delivery_date, loop_warehouse.warehouse_name, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
					$dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
					$dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";

					if ($sel_cdeal == "y") {
						$dt_view_qry .= "WHERE so_entered = 1 $emp_filter  and loop_transaction_buyer.shipped = 0 $pallet_str and loop_transaction_buyer.Preorder = 0 ";
						$dt_view_qry .= " and loop_transaction_buyer.po_delivery_dt between '" . $date_from . "' and '" . $date_to . "' and proof_of_delivery = ''";
						$dt_view_qry .= " and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
					} else {

						$dt_view_qry .= "WHERE so_entered = 1 $emp_filter and loop_transaction_buyer.shipped = 0  $pallet_str and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
					}

					$dt_view_res = db_query($dt_view_qry);
					$amt5 = 0;
					$lisofdetails5 = "";

					while ($dt_view_row = array_shift($dt_view_res)) {
						if ($_REQUEST['protype'] == 2) {
							$freight_cost = $dt_view_row["po_freight"];
							$po_poorderamount = $dt_view_row["SUMPO"];
							$salesorder_qty = 0;
							$payment_val = 0;

							$get_sales_order = db_query("Select loop_salesorders.qty, loop_boxes.b2b_id, loop_boxes.boxgoodvalue from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($dtt_view_res = array_shift($get_sales_order)) {
								$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
								$boxgoodvalue = $dtt_view_res["boxgoodvalue"];
								$boxgoodvaluearray	= explode(".", $boxgoodvalue);
								$boxgoodvalueDollar = $boxgoodvaluearray[0];
								$boxgoodvalueCents	= "0." . $boxgoodvaluearray[1];


								db_b2b();

								$get_box_data = db_query("Select ulineDollar, ulineCents, costDollar, costCents, overhead_costDollar, overhead_costCents  from inventory where ID = " .  $dtt_view_res["b2b_id"]);
								while ($box_data_res = array_shift($get_box_data)) {

									$b2b_ovh_costDollar = round($box_data_res["overhead_costDollar"]);
									$b2b_ovh_costCents = $box_data_res["overhead_costCents"];

									$b2b_costDollar = floatval($boxgoodvalueDollar) + $b2b_ovh_costDollar;
									$b2b_costCents = $boxgoodvalueCents + $b2b_ovh_costCents;

									$b2b_cost = $b2b_costDollar + $b2b_costCents;
								}
							}
							db();
							$sales_qry = db_query("Select qty from loop_salesorders_manual WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($dtt_view_res = array_shift($sales_qry)) {
								$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
							}

							$cogs_val = $b2b_cost * $salesorder_qty;
							$cogs = (-$freight_cost) - $cogs_val;

							$gross_profit = $po_poorderamount + $cogs;

							$payment_val = $gross_profit;
							$amt5 = $amt5 + $gross_profit;
						} else {
							$payment_val = $dt_view_row["SUMPO"];
							$amt5 = $amt5 + $dt_view_row["SUMPO"];
						}

						$bdescription = "";
						$supplier_name = "";
						db();
						$selqry1 = db_query("Select loop_salesorders.box_id, loop_boxes.bdescription, loop_boxes.b2b_id from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
						while ($selres1 = array_shift($selqry1)) {
							$bdescription .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $selres1['box_id'] . "&proc=View'>" . $selres1["bdescription"] . "</a><br>";
							db_b2b();
							$selqry2 = db_query("Select vendor_b2b_rescue from inventory where ID = " . $selres1["b2b_id"]);
							$selres2 = array_shift($selqry2);
							$vendor_b2b_rescue = $selres2["vendor_b2b_rescue"];

							$sname = "";
							db();
							$selqry3 = db_query("SELECT id, company_name, b2bid FROM loop_warehouse where id=" . $vendor_b2b_rescue);
							$selres3 = array_shift($selqry3);
							if (!empty($selres3)) {
								$sname = getnickname($selres3['company_name'], $selres3["b2bid"]);
								$supplier_name .= "<a target='_blank' href='viewCompany.php?ID=" . $selres3["b2bid"] . "'>" . $sname . " (Loop ID: " . $selres3["id"] . " B2B ID:" . $selres3["b2bid"] . ")</a><br>";
							}
						}
						$bdescription = substr($bdescription, 0, strlen($bdescription) - 4);
						$supplier_name = substr($supplier_name, 0, strlen($supplier_name) - 4);
						$vendor_pay = 0;
						$inv_amount = 0;
						db();
						$selqry4 = "SELECT loop_transaction_buyer_payments.estimated_cost from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["id"];
						$selres4 = db_query($selqry4);
						$num_rows1 = tep_db_num_rows($selres4);
						if ($num_rows1 > 0) {

							while ($row4 = array_shift($selres4)) {
								$vendor_pay += $row4["estimated_cost"];
							}
						}

						$inv_amount = $dt_view_row["F"];

						$invoice_amt = 0;
						db();
						$selqry6 = "SELECT * FROM loop_invoice_items WHERE trans_rec_id = " . $dt_view_row["id"] . " ORDER BY id ASC";
						$selres6 = db_query($selqry6);
						while ($inv_row = array_shift($selres6)) {
							$invoice_amt += $inv_row["quantity"] * $inv_row["price"];
						}
						if ($invoice_amt == 0) {
							$invoice_amt = $inv_amount;
						}
						if ($inv_amount == 0 && $invoice_amt > 0) {
							$inv_amount = $invoice_amt;
						}

						$profit_val = number_format(floatval(str_replace(",", "", strval($inv_amount))) - floatval(str_replace(",", "", strval($vendor_pay))), 2);
						$profit_val = str_replace(",", "", $profit_val);
						$inv_amount = str_replace(",", "", $inv_amount);

						$profit_val_per = "";
						if (floatval($inv_amount) > 0) {
							$profit_val_per = abs((floatval(str_replace(",", "", $profit_val)) * 100) / floatval(str_replace(",", "", $inv_amount)));
						}

						if ($profit_val_per != "") {
							if ($profit_val_per >= 30) {
								if ($profit_val < 0) {
									$profit_val_per = "<font color='#008000'>-" . number_format($profit_val_per, 2) . "%</font>";
								} else {
									$profit_val_per = "<font color='#008000'>" . number_format($profit_val_per, 2) . "%</font>";
								}
							} else if ($profit_val_per < 30) {
								if ($profit_val < 0) {
									$profit_val_per = "<font color='#EE3838'>-" . number_format($profit_val_per, 2) . "%</font>";
								} else {
									$profit_val_per = "<font color='#EE3838'>" . number_format($profit_val_per, 2) . "%</font>";
								}
							}
						}

						//$nickname = "";
						$nickname = getnickname($dt_view_row["warehouse_name"], $dt_view_row["b2bid"]);

						$org_delivery_dt = "";
						$po_delivery_dt = "";
						$actualDel_dt = "";
						if ($dt_view_row["po_delivery_dt"] != "") {
							$h_qry = "select planned_delivery_dt from planned_delivery_date_history where trans_id=" . $dt_view_row["id"] . " order by id ASC limit 1";
							db();
							$h_res = db_query($h_qry);
							$cnt_rw1 = tep_db_num_rows($h_res);
							if ($cnt_rw1 > 0) {
								while ($row1 = array_shift($h_res)) {
									$org_delivery_dt = date('m/d/Y', strtotime($row1["planned_delivery_dt"]));
								}
							} else {
								$org_delivery_dt = date('m/d/Y', strtotime($dt_view_row['po_delivery_dt']));
							}

							$po_delivery_dt = date('m/d/Y', strtotime($dt_view_row["po_delivery_dt"]));
						}

						$sql_act = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $dt_view_row["id"];
						$sql_res = db_query($sql_act);
						$cnt_rw1 = tep_db_num_rows($sql_res);
						if ($cnt_rw1 > 0) {
							while ($row = array_shift($sql_res)) {
								if ($row["bol_shipment_received_date"] != "") {
									$actualDel_dt = date('m/d/Y', strtotime($row["bol_shipment_received_date"]));
								}
							}
						}

						$lisofdetails5 .= "<tr><td bgColor='#E4EAEB'>" . $initials . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $supplier_name . "</td><td bgColor='#E4EAEB'>" . $bdescription . "</td>
					<td bgColor='#E4EAEB'>" . $org_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actualDel_dt . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($payment_val, 0) . "</td><td bgColor='#E4EAEB'>" . $profit_val_per . "</td></tr>";
					}

					// for col 6 (Need to Enter into TMS)
					$dt_view_qry = "SELECT po_employee, customerpickup_ucbdelivering_flg, loop_warehouse.warehouse_name, booking_freight_email_ignore, lane_tms_ignore, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
					$dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
					$dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
					if ($sel_cdeal == "y") {
						$dt_view_qry .= "WHERE customerpickup_ucbdelivering_flg = 0 $emp_filter  $pallet_str and loop_transaction_buyer.id not in (select trans_rec_id from loop_transaction_freight group by trans_rec_id) and so_entered = 1 and loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.Preorder = 0 ";
						$dt_view_qry .= " and loop_transaction_buyer.po_delivery_dt between '" . $date_from . "' and '" . $date_to . "' and proof_of_delivery = ''";
						$dt_view_qry .= " and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
					} else {

						$dt_view_qry .= "WHERE customerpickup_ucbdelivering_flg = 0 $emp_filter  $pallet_str and loop_transaction_buyer.id not in (select trans_rec_id from loop_transaction_freight group by trans_rec_id) and so_entered = 1 and loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.ops_delivery_date";
					}

					$dt_view_res = db_query($dt_view_qry);
					$amt6 = 0;
					$lisofdetails6 = "";

					while ($dt_view_row = array_shift($dt_view_res)) {
						if ($_REQUEST['protype'] == 2) {
							$freight_cost = $dt_view_row["po_freight"];
							$po_poorderamount = $dt_view_row["SUMPO"];
							$salesorder_qty = 0;
							$payment_val = 0;

							$get_sales_order = db_query("Select loop_salesorders.qty, loop_boxes.b2b_id, loop_boxes.boxgoodvalue from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($dtt_view_res = array_shift($get_sales_order)) {
								$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
								$boxgoodvalue = $dtt_view_res["boxgoodvalue"];
								$boxgoodvaluearray	= explode(".", $boxgoodvalue);
								$boxgoodvalueDollar = $boxgoodvaluearray[0];
								$boxgoodvalueCents	= "0." . $boxgoodvaluearray[1];


								db_b2b();

								$get_box_data = db_query("Select ulineDollar, ulineCents, costDollar, costCents, overhead_costDollar, overhead_costCents  from inventory where ID = " .  $dtt_view_res["b2b_id"]);
								while ($box_data_res = array_shift($get_box_data)) {

									$b2b_ovh_costDollar = round($box_data_res["overhead_costDollar"]);
									$b2b_ovh_costCents = $box_data_res["overhead_costCents"];

									$b2b_costDollar = floatval($boxgoodvalueDollar) + $b2b_ovh_costDollar;
									$b2b_costCents = $boxgoodvalueCents + $b2b_ovh_costCents;

									$b2b_cost = $b2b_costDollar + $b2b_costCents;
								}
							}
							db();
							$sales_qry = db_query("Select qty from loop_salesorders_manual WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($dtt_view_res = array_shift($sales_qry)) {
								$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
							}

							$cogs_val = $b2b_cost * $salesorder_qty;
							$cogs = (-$freight_cost) - $cogs_val;

							$gross_profit = $po_poorderamount + $cogs;

							$payment_val = $gross_profit;
							$amt6 = $amt6 + $gross_profit;
						} else {
							$payment_val = $dt_view_row["SUMPO"];
							$amt6 = $amt6 + $dt_view_row["SUMPO"];
						}

						$bdescription = "";
						$supplier_name = "";
						db();
						$selqry1 = db_query("Select loop_salesorders.box_id, loop_boxes.bdescription, loop_boxes.b2b_id from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
						while ($selres1 = array_shift($selqry1)) {
							$bdescription .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $selres1['box_id'] . "&proc=View'>" . $selres1["bdescription"] . "</a><br>";
							db_b2b();
							$selqry2 = db_query("Select vendor_b2b_rescue from inventory where ID = " . $selres1["b2b_id"]);
							$selres2 = array_shift($selqry2);
							$vendor_b2b_rescue = $selres2["vendor_b2b_rescue"];

							$sname = "";
							db();
							$selqry3 = db_query("SELECT id, company_name, b2bid FROM loop_warehouse where id=" . $vendor_b2b_rescue);
							$selres3 = array_shift($selqry3);
							if (!empty($selres3)) {
								$sname = getnickname($selres3['company_name'], $selres3["b2bid"]);
								$supplier_name .= "<a target='_blank' href='viewCompany.php?ID=" . $selres3["b2bid"] . "'>" . $sname . " (Loop ID: " . $selres3["id"] . " B2B ID:" . $selres3["b2bid"] . ")</a><br>";
							}
						}
						$bdescription = substr($bdescription, 0, strlen($bdescription) - 4);
						$supplier_name = substr($supplier_name, 0, strlen($supplier_name) - 4);
						$vendor_pay = 0;
						$inv_amount = 0;
						db();
						$selqry4 = "SELECT loop_transaction_buyer_payments.estimated_cost from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["id"];
						$selres4 = db_query($selqry4);
						$num_rows1 = tep_db_num_rows($selres4);
						if ($num_rows1 > 0) {

							while ($row4 = array_shift($selres4)) {
								$vendor_pay += $row4["estimated_cost"];
							}
						}

						$inv_amount = $dt_view_row["F"];

						$invoice_amt = 0;
						db();
						$selqry6 = "SELECT * FROM loop_invoice_items WHERE trans_rec_id = " . $dt_view_row["id"] . " ORDER BY id ASC";
						$selres6 = db_query($selqry6);
						while ($inv_row = array_shift($selres6)) {
							$invoice_amt += $inv_row["quantity"] * $inv_row["price"];
						}
						if ($invoice_amt == 0) {
							$invoice_amt = $inv_amount;
						}
						if ($inv_amount == 0 && $invoice_amt > 0) {
							$inv_amount = $invoice_amt;
						}

						$profit_val = number_format((float)str_replace(",", "", strval($inv_amount)) - (float)str_replace(",", "", strval($vendor_pay)), 2);
						$profit_val = str_replace(",", "", $profit_val);
						$inv_amount = str_replace(",", "", $inv_amount);

						$profit_val_per = "";
						if ((float)$inv_amount > 0) {
							$profit_val_per = abs((float)$profit_val * 100 / (float)$inv_amount);
						}

						if ($profit_val_per != "") {
							if ($profit_val_per >= 30) {
								if ($profit_val < 0) {
									$profit_val_per = "<font color='#008000'>-" . number_format($profit_val_per, 2) . "%</font>";
								} else {
									$profit_val_per = "<font color='#008000'>" . number_format($profit_val_per, 2) . "%</font>";
								}
							} else if ($profit_val_per < 30) {
								if ($profit_val < 0) {
									$profit_val_per = "<font color='#EE3838'>-" . number_format($profit_val_per, 2) . "%</font>";
								} else {
									$profit_val_per = "<font color='#EE3838'>" . number_format($profit_val_per, 2) . "%</font>";
								}
							}
						}

						//$nickname = "";
						$nickname = getnickname($dt_view_row["warehouse_name"], $dt_view_row["b2bid"]);

						$org_delivery_dt = "";
						$po_delivery_dt = "";
						$actualDel_dt = "";
						if ($dt_view_row["po_delivery_dt"] != "") {
							$h_qry = "select planned_delivery_dt from planned_delivery_date_history where trans_id=" . $dt_view_row["id"] . " order by id ASC limit 1";
							$h_res = db_query($h_qry);
							$cnt_rw1 = tep_db_num_rows($h_res);
							if ($cnt_rw1 > 0) {
								while ($row1 = array_shift($h_res)) {
									$org_delivery_dt = date('m/d/Y', strtotime($row1["planned_delivery_dt"]));
								}
							} else {
								$org_delivery_dt = date('m/d/Y', strtotime($dt_view_row['po_delivery_dt']));
							}

							$po_delivery_dt = date('m/d/Y', strtotime($dt_view_row["po_delivery_dt"]));
						}

						$sql_act = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $dt_view_row["id"];
						$sql_res = db_query($sql_act);
						$cnt_rw1 = tep_db_num_rows($sql_res);
						if ($cnt_rw1 > 0) {
							while ($row = array_shift($sql_res)) {
								if ($row["bol_shipment_received_date"] != "") {
									$actualDel_dt = date('m/d/Y', strtotime($row["bol_shipment_received_date"]));
								}
							}
						}

						$lisofdetails6 .= "<tr><td bgColor='#E4EAEB'>" . $initials . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $supplier_name . "</td><td bgColor='#E4EAEB'>" . $bdescription . "</td>
					<td bgColor='#E4EAEB'>" . $org_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actualDel_dt . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($payment_val, 0) . "</td><td bgColor='#E4EAEB'>" . $profit_val_per . "</td></tr>";
					}

					// for col 7 (Need to Tender Lane)
					$dt_view_qry = "SELECT po_employee, ops_delivery_date, loop_warehouse.warehouse_name, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
					$dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
					$dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
					if ($sel_cdeal == "y") {
						$dt_view_qry .= "WHERE customerpickup_ucbdelivering_flg = 2 $emp_filter  $pallet_str and (customerpickup_ucbdelivering_flg = 1 or (customerpickup_ucbdelivering_flg = 2 and tender_lane_ignore = 0 and loop_transaction_buyer.id not in (select trans_rec_id from loop_transaction_buyer_freightview group by trans_rec_id))) and so_entered = 1";
						$dt_view_qry .= " and loop_transaction_buyer.po_delivery_dt between '" . $date_from . "' and '" . $date_to . "' and proof_of_delivery = ''";
						$dt_view_qry .= " and loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.ops_delivery_date";
					} else {

						$dt_view_qry .= "WHERE customerpickup_ucbdelivering_flg = 2 $emp_filter  $pallet_str and (customerpickup_ucbdelivering_flg = 1 or (customerpickup_ucbdelivering_flg = 2 and tender_lane_ignore = 0 and loop_transaction_buyer.id not in (select trans_rec_id from loop_transaction_buyer_freightview group by trans_rec_id))) and so_entered = 1 and loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.ops_delivery_date";
					}

					$dt_view_res = db_query($dt_view_qry);
					$amt7 = 0;
					$lisofdetails7 = "";

					while ($dt_view_row = array_shift($dt_view_res)) {
						if ($_REQUEST['protype'] == 2) {
							$freight_cost = $dt_view_row["po_freight"];
							$po_poorderamount = $dt_view_row["SUMPO"];
							$salesorder_qty = 0;
							$payment_val = 0;

							$get_sales_order = db_query("Select loop_salesorders.qty, loop_boxes.b2b_id, loop_boxes.boxgoodvalue from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($dtt_view_res = array_shift($get_sales_order)) {
								$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
								$boxgoodvalue = $dtt_view_res["boxgoodvalue"];
								$boxgoodvaluearray	= explode(".", $boxgoodvalue);
								$boxgoodvalueDollar = $boxgoodvaluearray[0];
								$boxgoodvalueCents	= "0." . $boxgoodvaluearray[1];

								db_b2b();

								$get_box_data = db_query("Select ulineDollar, ulineCents, costDollar, costCents, overhead_costDollar, overhead_costCents  from inventory where ID = " .  $dtt_view_res["b2b_id"]);
								while ($box_data_res = array_shift($get_box_data)) {

									$b2b_ovh_costDollar = round($box_data_res["overhead_costDollar"]);
									$b2b_ovh_costCents = $box_data_res["overhead_costCents"];

									$b2b_costDollar = floatval($boxgoodvalueDollar) + $b2b_ovh_costDollar;
									$b2b_costCents = $boxgoodvalueCents + $b2b_ovh_costCents;

									$b2b_cost = $b2b_costDollar + $b2b_costCents;
								}
							}
							db();
							$sales_qry = db_query("Select qty from loop_salesorders_manual WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($dtt_view_res = array_shift($sales_qry)) {
								$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
							}

							$cogs_val = $b2b_cost * $salesorder_qty;
							$cogs = (-$freight_cost) - $cogs_val;

							$gross_profit = $po_poorderamount + $cogs;

							$payment_val = $gross_profit;
							$amt7 = $amt7 + $gross_profit;
						} else {
							$payment_val = $dt_view_row["SUMPO"];
							$amt7 = $amt7 + $dt_view_row["SUMPO"];
						}

						$bdescription = "";
						$supplier_name = "";
						db();
						$selqry1 = db_query("Select loop_salesorders.box_id, loop_boxes.bdescription, loop_boxes.b2b_id from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
						while ($selres1 = array_shift($selqry1)) {
							$bdescription .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $selres1['box_id'] . "&proc=View'>" . $selres1["bdescription"] . "</a><br>";
							db_b2b();
							$selqry2 = db_query("Select vendor_b2b_rescue from inventory where ID = " . $selres1["b2b_id"]);
							$selres2 = array_shift($selqry2);
							$vendor_b2b_rescue = $selres2["vendor_b2b_rescue"];

							$sname = "";
							db();
							$selqry3 = db_query("SELECT id, company_name, b2bid FROM loop_warehouse where id=" . $vendor_b2b_rescue);
							$selres3 = array_shift($selqry3);
							if (!empty($selres3)) {
								$sname = getnickname($selres3['company_name'], $selres3["b2bid"]);
								$supplier_name .= "<a target='_blank' href='viewCompany.php?ID=" . $selres3["b2bid"] . "'>" . $sname . " (Loop ID: " . $selres3["id"] . " B2B ID:" . $selres3["b2bid"] . ")</a><br>";
							}
						}
						$bdescription = substr($bdescription, 0, strlen($bdescription) - 4);
						$supplier_name = substr($supplier_name, 0, strlen($supplier_name) - 4);
						$vendor_pay = 0;
						$inv_amount = 0;
						db();
						$selqry4 = "SELECT loop_transaction_buyer_payments.estimated_cost from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["id"];
						$selres4 = db_query($selqry4);
						$num_rows1 = tep_db_num_rows($selres4);
						if ($num_rows1 > 0) {

							while ($row4 = array_shift($selres4)) {
								$vendor_pay += $row4["estimated_cost"];
							}
						}

						$inv_amount = $dt_view_row["F"];

						$invoice_amt = 0;
						db();
						$selqry6 = "SELECT * FROM loop_invoice_items WHERE trans_rec_id = " . $dt_view_row["id"] . " ORDER BY id ASC";
						$selres6 = db_query($selqry6);
						while ($inv_row = array_shift($selres6)) {
							$invoice_amt += $inv_row["quantity"] * $inv_row["price"];
						}
						if ($invoice_amt == 0) {
							$invoice_amt = $inv_amount;
						}
						if ($inv_amount == 0 && $invoice_amt > 0) {
							$inv_amount = $invoice_amt;
						}

						$profit_val = number_format(floatval(str_replace(",", "", strval($inv_amount))) - floatval(str_replace(",", "", strval($vendor_pay))), 2);
						$profit_val = str_replace(",", "", $profit_val);
						$inv_amount = str_replace(",", "", $inv_amount);

						$profit_val_per = "";
						if (floatval($inv_amount) > 0) {
							$profit_val_per = abs((floatval(str_replace(",", "", $profit_val)) * 100) / floatval(str_replace(",", "", $inv_amount)));
						}

						if ($profit_val_per != "") {
							if ($profit_val_per >= 30) {
								if ($profit_val < 0) {
									$profit_val_per = "<font color='#008000'>-" . number_format($profit_val_per, 2) . "%</font>";
								} else {
									$profit_val_per = "<font color='#008000'>" . number_format($profit_val_per, 2) . "%</font>";
								}
							} else if ($profit_val_per < 30) {
								if ($profit_val < 0) {
									$profit_val_per = "<font color='#EE3838'>-" . number_format($profit_val_per, 2) . "%</font>";
								} else {
									$profit_val_per = "<font color='#EE3838'>" . number_format($profit_val_per, 2) . "%</font>";
								}
							}
						}

						//$nickname = "";
						$nickname = getnickname($dt_view_row["warehouse_name"], $dt_view_row["b2bid"]);

						$org_delivery_dt = "";
						$po_delivery_dt = "";
						$actualDel_dt = "";
						if ($dt_view_row["po_delivery_dt"] != "") {
							$h_qry = "select planned_delivery_dt from planned_delivery_date_history where trans_id=" . $dt_view_row["id"] . " order by id ASC limit 1";
							db();
							$h_res = db_query($h_qry);
							$cnt_rw1 = tep_db_num_rows($h_res);
							if ($cnt_rw1 > 0) {
								while ($row1 = array_shift($h_res)) {
									$org_delivery_dt = date('m/d/Y', strtotime($row1["planned_delivery_dt"]));
								}
							} else {
								$org_delivery_dt = date('m/d/Y', strtotime($dt_view_row['po_delivery_dt']));
							}

							$po_delivery_dt = date('m/d/Y', strtotime($dt_view_row["po_delivery_dt"]));
						}

						$sql_act = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $dt_view_row["id"];
						$sql_res = db_query($sql_act);
						$cnt_rw1 = tep_db_num_rows($sql_res);
						if ($cnt_rw1 > 0) {
							while ($row = array_shift($sql_res)) {
								if ($row["bol_shipment_received_date"] != "") {
									$actualDel_dt = date('m/d/Y', strtotime($row["bol_shipment_received_date"]));
								}
							}
						}

						$lisofdetails7 .= "<tr><td bgColor='#E4EAEB'>" . $initials . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $supplier_name . "</td><td bgColor='#E4EAEB'>" . $bdescription . "</td>
					<td bgColor='#E4EAEB'>" . $org_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actualDel_dt . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($payment_val, 0) . "</td><td bgColor='#E4EAEB'>" . $profit_val_per . "</td></tr>";
					}

					// for col 8 (Lane Tendered, Set Dock Appointments)
					$dt_view_qry = "SELECT po_employee, ops_delivery_date, booked_delivery_cost, loop_warehouse.warehouse_name, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
					$dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
					$dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
					if ($sel_cdeal == "y") {
						$dt_view_qry .= "WHERE customerpickup_ucbdelivering_flg > 0 $emp_filter and (customerpickup_ucbdelivering_flg = 1 or (customerpickup_ucbdelivering_flg = 2 and tender_lane_ignore = 1 or loop_transaction_buyer.id in (select trans_rec_id from loop_transaction_buyer_freightview group by trans_rec_id))) ";
						$dt_view_qry .= " and ((freight_assign_eml_ignore = 0 and loop_transaction_buyer.id not in (select trans_rec_id from loop_transaction_buyer_ship_eml_data where freight_assigned_email_flg = 1 group by trans_rec_id)) and (broker_needs_pickup_eml_ignore = 0 and loop_transaction_buyer.id not in (select trans_rec_id from loop_transaction_buyer_ship_eml_data where broker_needs_pickup_email_flg = 1 group by trans_rec_id)) ";
						$dt_view_qry .= " and loop_transaction_buyer.po_delivery_dt between '" . $date_from . "' and '" . $date_to . "' and proof_of_delivery = ''";
						$dt_view_qry .= " and (dock_appt_eml_ignore = 0  $pallet_str and loop_transaction_buyer.id not in (select trans_rec_id from loop_transaction_freight group by trans_rec_id))) and so_entered = 1 and loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.ops_delivery_date";
					} else {

						$dt_view_qry .= "WHERE customerpickup_ucbdelivering_flg > 0 $emp_filter and (customerpickup_ucbdelivering_flg = 1 or (customerpickup_ucbdelivering_flg = 2 and tender_lane_ignore = 1 or loop_transaction_buyer.id in (select trans_rec_id from loop_transaction_buyer_freightview group by trans_rec_id))) ";
						$dt_view_qry .= " and ((freight_assign_eml_ignore = 0 and loop_transaction_buyer.id not in (select trans_rec_id from loop_transaction_buyer_ship_eml_data where freight_assigned_email_flg = 1 group by trans_rec_id)) and (broker_needs_pickup_eml_ignore = 0 and loop_transaction_buyer.id not in (select trans_rec_id from loop_transaction_buyer_ship_eml_data where broker_needs_pickup_email_flg = 1 group by trans_rec_id)) ";
						$dt_view_qry .= " and (dock_appt_eml_ignore = 0  $pallet_str and loop_transaction_buyer.id not in (select trans_rec_id from loop_transaction_freight group by trans_rec_id))) and so_entered = 1 and loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.ops_delivery_date";
					}

					$dt_view_res = db_query($dt_view_qry);
					$amt8 = 0;
					$lisofdetails8 = "";

					while ($dt_view_row = array_shift($dt_view_res)) {
						if ($_REQUEST['protype'] == 2) {
							$freight_cost = $dt_view_row["po_freight"];
							$po_poorderamount = $dt_view_row["SUMPO"];
							$salesorder_qty = 0;
							$payment_val = 0;

							$get_sales_order = db_query("Select loop_salesorders.qty, loop_boxes.b2b_id, loop_boxes.boxgoodvalue from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($dtt_view_res = array_shift($get_sales_order)) {
								$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
								$boxgoodvalue = $dtt_view_res["boxgoodvalue"];
								$boxgoodvaluearray	= explode(".", $boxgoodvalue);
								$boxgoodvalueDollar = $boxgoodvaluearray[0];
								$boxgoodvalueCents	= "0." . $boxgoodvaluearray[1];

								db_b2b();

								$get_box_data = db_query("Select ulineDollar, ulineCents, costDollar, costCents, overhead_costDollar, overhead_costCents  from inventory where ID = " .  $dtt_view_res["b2b_id"]);
								while ($box_data_res = array_shift($get_box_data)) {

									$b2b_ovh_costDollar = round($box_data_res["overhead_costDollar"]);
									$b2b_ovh_costCents = $box_data_res["overhead_costCents"];

									$b2b_costDollar = floatval($boxgoodvalueDollar) + $b2b_ovh_costDollar;
									$b2b_costCents = $boxgoodvalueCents + $b2b_ovh_costCents;

									$b2b_cost = $b2b_costDollar + $b2b_costCents;
								}
							}
							db();
							$sales_qry = db_query("Select qty from loop_salesorders_manual WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($dtt_view_res = array_shift($sales_qry)) {
								$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
							}

							$cogs_val = $b2b_cost * $salesorder_qty;
							$cogs = (-$freight_cost) - $cogs_val;

							$gross_profit = $po_poorderamount + $cogs;

							$payment_val = $gross_profit;
							$amt8 = $amt8 + $gross_profit;
						} else {
							$payment_val = $dt_view_row["SUMPO"];
							$amt8 = $amt8 + $dt_view_row["SUMPO"];
						}

						$bdescription = "";
						$supplier_name = "";
						db();
						$selqry1 = db_query("Select loop_salesorders.box_id, loop_boxes.bdescription, loop_boxes.b2b_id from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
						while ($selres1 = array_shift($selqry1)) {
							$bdescription .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $selres1['box_id'] . "&proc=View'>" . $selres1["bdescription"] . "</a><br>";
							db_b2b();
							$selqry2 = db_query("Select vendor_b2b_rescue from inventory where ID = " . $selres1["b2b_id"]);
							$selres2 = array_shift($selqry2);
							$vendor_b2b_rescue = $selres2["vendor_b2b_rescue"];

							$sname = "";
							db();
							$selqry3 = db_query("SELECT id, company_name, b2bid FROM loop_warehouse where id=" . $vendor_b2b_rescue);
							$selres3 = array_shift($selqry3);
							if (!empty($selres3)) {
								$sname = getnickname($selres3['company_name'], $selres3["b2bid"]);
								$supplier_name .= "<a target='_blank' href='viewCompany.php?ID=" . $selres3["b2bid"] . "'>" . $sname . " (Loop ID: " . $selres3["id"] . " B2B ID:" . $selres3["b2bid"] . ")</a><br>";
							}
						}
						$bdescription = substr($bdescription, 0, strlen($bdescription) - 4);
						$supplier_name = substr($supplier_name, 0, strlen($supplier_name) - 4);
						$vendor_pay = 0;
						$inv_amount = 0;
						db();
						$selqry4 = "SELECT loop_transaction_buyer_payments.estimated_cost from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["id"];
						$selres4 = db_query($selqry4);
						$num_rows1 = tep_db_num_rows($selres4);
						if ($num_rows1 > 0) {

							while ($row4 = array_shift($selres4)) {
								$vendor_pay += $row4["estimated_cost"];
							}
						}

						$inv_amount = $dt_view_row["F"];

						$invoice_amt = 0;
						db();
						$selqry6 = "SELECT * FROM loop_invoice_items WHERE trans_rec_id = " . $dt_view_row["id"] . " ORDER BY id ASC";
						$selres6 = db_query($selqry6);
						while ($inv_row = array_shift($selres6)) {
							$invoice_amt += $inv_row["quantity"] * $inv_row["price"];
						}
						if ($invoice_amt == 0) {
							$invoice_amt = $inv_amount;
						}
						if ($inv_amount == 0 && $invoice_amt > 0) {
							$inv_amount = $invoice_amt;
						}

						$profit_val = number_format(floatval(str_replace(",", "", strval($inv_amount))) - floatval(str_replace(",", "", strval($vendor_pay))), 2);
						$profit_val = str_replace(",", "", $profit_val);
						$inv_amount = str_replace(",", "", $inv_amount);

						$profit_val_per = "";
						if (floatval($inv_amount) > 0) {
							$profit_val_per = abs((floatval($profit_val) * 100) / floatval($inv_amount));
						}
						if ($profit_val_per != "") {
							if ($profit_val_per >= 30) {
								if ($profit_val < 0) {
									$profit_val_per = "<font color='#008000'>-" . number_format($profit_val_per, 2) . "%</font>";
								} else {
									$profit_val_per = "<font color='#008000'>" . number_format($profit_val_per, 2) . "%</font>";
								}
							} else if ($profit_val_per < 30) {
								if ($profit_val < 0) {
									$profit_val_per = "<font color='#EE3838'>-" . number_format($profit_val_per, 2) . "%</font>";
								} else {
									$profit_val_per = "<font color='#EE3838'>" . number_format($profit_val_per, 2) . "%</font>";
								}
							}
						}

						//$nickname = "";
						$nickname = getnickname($dt_view_row["warehouse_name"], $dt_view_row["b2bid"]);

						$org_delivery_dt = "";
						$po_delivery_dt = "";
						$actualDel_dt = "";
						if ($dt_view_row["po_delivery_dt"] != "") {
							$h_qry = "select planned_delivery_dt from planned_delivery_date_history where trans_id=" . $dt_view_row["id"] . " order by id ASC limit 1";
							db();
							$h_res = db_query($h_qry);
							$cnt_rw1 = tep_db_num_rows($h_res);
							if ($cnt_rw1 > 0) {
								while ($row1 = array_shift($h_res)) {
									$org_delivery_dt = date('m/d/Y', strtotime($row1["planned_delivery_dt"]));
								}
							} else {
								$org_delivery_dt = date('m/d/Y', strtotime($dt_view_row['po_delivery_dt']));
							}

							$po_delivery_dt = date('m/d/Y', strtotime($dt_view_row["po_delivery_dt"]));
						}

						$sql_act = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $dt_view_row["id"];
						$sql_res = db_query($sql_act);
						$cnt_rw1 = tep_db_num_rows($sql_res);
						if ($cnt_rw1 > 0) {
							while ($row = array_shift($sql_res)) {
								if ($row["bol_shipment_received_date"] != "") {
									$actualDel_dt = date('m/d/Y', strtotime($row["bol_shipment_received_date"]));
								}
							}
						}

						$lisofdetails8 .= "<tr><td bgColor='#E4EAEB'>" . $initials . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $supplier_name . "</td><td bgColor='#E4EAEB'>" . $bdescription . "</td>
					<td bgColor='#E4EAEB'>" . $org_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actualDel_dt . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($payment_val, 0) . "</td><td bgColor='#E4EAEB'>" . $profit_val_per . "</td></tr>";
					}

					// for col 9 (BOL Needs Created and Shipped)
					$dt_view_qry = "SELECT loop_freightvendor.company_name as freightbroker, loop_warehouse.warehouse_name, po_employee, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
					$dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
					$dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
					$dt_view_qry .= " left JOIN loop_transaction_freight ON loop_transaction_buyer.id = loop_transaction_freight.trans_rec_id ";
					$dt_view_qry .= " left JOIN loop_freightvendor ON loop_transaction_freight.broker_id = loop_freightvendor.id ";
					if ($sel_cdeal == "y") {
						$dt_view_qry .= "WHERE (bol_create = 0 or loop_transaction_buyer.shipped = 0) $emp_filter and (customerpickup_ucbdelivering_flg = 1 or (customerpickup_ucbdelivering_flg = 2 and tender_lane_ignore = 1 or loop_transaction_buyer.id in (select trans_rec_id from loop_transaction_buyer_freightview group by trans_rec_id))) $pallet_str and so_entered = 1 and loop_transaction_buyer.Preorder = 0 ";
						$dt_view_qry .= " and loop_transaction_buyer.po_delivery_dt between '" . $date_from . "' and '" . $date_to . "' and proof_of_delivery = ''";
						$dt_view_qry .= " and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_freightvendor.company_name, loop_transaction_freight.date";
					} else {

						$dt_view_qry .= "WHERE (bol_create = 0 or loop_transaction_buyer.shipped = 0) $emp_filter and (customerpickup_ucbdelivering_flg = 1 or (customerpickup_ucbdelivering_flg = 2 and tender_lane_ignore = 1 or loop_transaction_buyer.id in (select trans_rec_id from loop_transaction_buyer_freightview group by trans_rec_id))) $pallet_str and so_entered = 1 and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_freightvendor.company_name, loop_transaction_freight.date";
					}
					//echo $dt_view_qry . "<br>";
					$dt_view_res = db_query($dt_view_qry);
					$amt9 = 0;
					$lisofdetails9 = "";

					while ($dt_view_row = array_shift($dt_view_res)) {
						if ($_REQUEST['protype'] == 2) {
							$freight_cost = $dt_view_row["po_freight"];
							$po_poorderamount = $dt_view_row["SUMPO"];
							$salesorder_qty = 0;
							$payment_val = 0;

							$get_sales_order = db_query("Select loop_salesorders.qty, loop_boxes.b2b_id, loop_boxes.boxgoodvalue from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($dtt_view_res = array_shift($get_sales_order)) {
								$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
								$boxgoodvalue = $dtt_view_res["boxgoodvalue"];
								$boxgoodvaluearray	= explode(".", $boxgoodvalue);
								$boxgoodvalueDollar = $boxgoodvaluearray[0];
								$boxgoodvalueCents	= "0." . $boxgoodvaluearray[1];

								db_b2b();

								$get_box_data = db_query("Select ulineDollar, ulineCents, costDollar, costCents, overhead_costDollar, overhead_costCents  from inventory where ID = " .  $dtt_view_res["b2b_id"]);
								while ($box_data_res = array_shift($get_box_data)) {

									$b2b_ovh_costDollar = round($box_data_res["overhead_costDollar"]);
									$b2b_ovh_costCents = $box_data_res["overhead_costCents"];

									$b2b_costDollar = floatval($boxgoodvalueDollar) + $b2b_ovh_costDollar;
									$b2b_costCents = $boxgoodvalueCents + $b2b_ovh_costCents;

									$b2b_cost = $b2b_costDollar + $b2b_costCents;
								}
							}
							db();
							$sales_qry = db_query("Select qty from loop_salesorders_manual WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($dtt_view_res = array_shift($sales_qry)) {
								$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
							}

							$cogs_val = $b2b_cost * $salesorder_qty;
							$cogs = (-$freight_cost) - $cogs_val;

							$gross_profit = $po_poorderamount + $cogs;

							$payment_val = $gross_profit;
							$amt9 = $amt9 + $gross_profit;
						} else {
							$payment_val = $dt_view_row["SUMPO"];
							$amt9 = $amt9 + $dt_view_row["SUMPO"];
						}

						$bdescription = "";
						$supplier_name = "";
						db();
						$selqry1 = db_query("Select loop_salesorders.box_id, loop_boxes.bdescription, loop_boxes.b2b_id from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
						while ($selres1 = array_shift($selqry1)) {
							$bdescription .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $selres1['box_id'] . "&proc=View'>" . $selres1["bdescription"] . "</a><br>";

							db_b2b();
							$selqry2 = db_query("Select vendor_b2b_rescue from inventory where ID = " . $selres1["b2b_id"]);
							$selres2 = array_shift($selqry2);
							$vendor_b2b_rescue = $selres2["vendor_b2b_rescue"];

							$sname = "";
							db();
							$selqry3 = db_query("SELECT id, company_name, b2bid FROM loop_warehouse where id=" . $vendor_b2b_rescue);
							$selres3 = array_shift($selqry3);
							if (!empty($selres3)) {
								$sname = getnickname($selres3['company_name'], $selres3["b2bid"]);
								$supplier_name .= "<a target='_blank' href='viewCompany.php?ID=" . $selres3["b2bid"] . "'>" . $sname . " (Loop ID: " . $selres3["id"] . " B2B ID:" . $selres3["b2bid"] . ")</a><br>";
							}
						}
						$bdescription = substr($bdescription, 0, strlen($bdescription) - 4);
						$supplier_name = substr($supplier_name, 0, strlen($supplier_name) - 4);
						$vendor_pay = 0;
						$inv_amount = 0;
						db();
						$selqry4 = "SELECT loop_transaction_buyer_payments.estimated_cost from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["id"];
						$selres4 = db_query($selqry4);
						$num_rows1 = tep_db_num_rows($selres4);
						if ($num_rows1 > 0) {

							while ($row4 = array_shift($selres4)) {
								$vendor_pay += $row4["estimated_cost"];
							}
						}

						$inv_amount = $dt_view_row["F"];

						$invoice_amt = 0;
						db();
						$selqry6 = "SELECT * FROM loop_invoice_items WHERE trans_rec_id = " . $dt_view_row["id"] . " ORDER BY id ASC";
						$selres6 = db_query($selqry6);
						while ($inv_row = array_shift($selres6)) {
							$invoice_amt += $inv_row["quantity"] * $inv_row["price"];
						}
						if ($invoice_amt == 0) {
							$invoice_amt = $inv_amount;
						}
						if ($inv_amount == 0 && $invoice_amt > 0) {
							$inv_amount = $invoice_amt;
						}

						$profit_val = number_format(floatval(str_replace(",", "", strval($inv_amount))) - floatval(str_replace(",", "", strval($vendor_pay))), 2);
						$profit_val = str_replace(",", "", $profit_val);
						$inv_amount = str_replace(",", "", $inv_amount);

						$profit_val_per = "";
						if (floatval($inv_amount) > 0) {
							$profit_val_per = abs((floatval($profit_val) * 100) / floatval($inv_amount));
						}

						if ($profit_val_per != "") {
							if ($profit_val_per >= 30) {
								if ($profit_val < 0) {
									$profit_val_per = "<font color='#008000'>-" . number_format($profit_val_per, 2) . "%</font>";
								} else {
									$profit_val_per = "<font color='#008000'>" . number_format($profit_val_per, 2) . "%</font>";
								}
							} else if ($profit_val_per < 30) {
								if ($profit_val < 0) {
									$profit_val_per = "<font color='#EE3838'>-" . number_format($profit_val_per, 2) . "%</font>";
								} else {
									$profit_val_per = "<font color='#EE3838'>" . number_format($profit_val_per, 2) . "%</font>";
								}
							}
						}

						//$nickname = "";
						$nickname = getnickname($dt_view_row["warehouse_name"], $dt_view_row["b2bid"]);

						$org_delivery_dt = "";
						$po_delivery_dt = "";
						$actualDel_dt = "";
						if ($dt_view_row["po_delivery_dt"] != "") {
							$h_qry = "select planned_delivery_dt from planned_delivery_date_history where trans_id=" . $dt_view_row["id"] . " order by id ASC limit 1";
							db();
							$h_res = db_query($h_qry);
							$cnt_rw1 = tep_db_num_rows($h_res);
							if ($cnt_rw1 > 0) {
								while ($row1 = array_shift($h_res)) {
									$org_delivery_dt = date('m/d/Y', strtotime($row1["planned_delivery_dt"]));
								}
							} else {
								$org_delivery_dt = date('m/d/Y', strtotime($dt_view_row['po_delivery_dt']));
							}

							$po_delivery_dt = date('m/d/Y', strtotime($dt_view_row["po_delivery_dt"]));
						}

						$sql_act = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $dt_view_row["id"];
						$sql_res = db_query($sql_act);
						$cnt_rw1 = tep_db_num_rows($sql_res);
						if ($cnt_rw1 > 0) {
							while ($row = array_shift($sql_res)) {
								if ($row["bol_shipment_received_date"] != "") {
									$actualDel_dt = date('m/d/Y', strtotime($row["bol_shipment_received_date"]));
								}
							}
						}

						$lisofdetails9 .= "<tr><td bgColor='#E4EAEB'>" . $initials . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $supplier_name . "</td><td bgColor='#E4EAEB'>" . $bdescription . "</td>
					<td bgColor='#E4EAEB'>" . $org_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actualDel_dt . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($payment_val, 0) . "</td><td bgColor='#E4EAEB'>" . $profit_val_per . "</td></tr>";
					}

					// for col 10 (On The Road)
					$dt_view_qry = "SELECT loop_freightvendor.company_name as freightbroker, loop_warehouse.warehouse_name, po_employee, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
					$dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
					$dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
					$dt_view_qry .= "INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id ";
					$dt_view_qry .= " left JOIN loop_transaction_freight ON loop_transaction_buyer.id = loop_transaction_freight.trans_rec_id ";
					$dt_view_qry .= " left JOIN loop_freightvendor ON loop_transaction_freight.broker_id = loop_freightvendor.id ";
					if ($sel_cdeal == "y") {
						$dt_view_qry .= "WHERE loop_bol_files.bol_shipped = 1 $emp_filter  $pallet_str and loop_bol_files.bol_shipment_received = 0 and bol_create = 1 and loop_transaction_buyer.shipped = 1 and loop_transaction_buyer.Preorder = 0";
						$dt_view_qry .= " and loop_transaction_buyer.po_delivery_dt between '" . $date_from . "' and '" . $date_to . "' and proof_of_delivery = ''";
						$dt_view_qry .= " and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_freightvendor.company_name, loop_transaction_freight.booked_delivery_date";
					} else {

						$dt_view_qry .= " WHERE loop_bol_files.bol_shipped = 1 $emp_filter  $pallet_str and loop_bol_files.bol_shipment_received = 0 and bol_create = 1 and loop_transaction_buyer.shipped = 1 and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_freightvendor.company_name, loop_transaction_freight.booked_delivery_date";
					}

					$dt_view_res = db_query($dt_view_qry);
					$amt10 = 0;
					$lisofdetails10 = "";
					$lisofdetails11 = "";
					$amt11 = 0;

					while ($dt_view_row = array_shift($dt_view_res)) {
						if ($_REQUEST['protype'] == 2) {
							$freight_cost = $dt_view_row["po_freight"];
							$po_poorderamount = $dt_view_row["SUMPO"];
							$salesorder_qty = 0;
							$payment_val = 0;

							$get_sales_order = db_query("Select loop_salesorders.qty, loop_boxes.b2b_id, loop_boxes.boxgoodvalue from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($dtt_view_res = array_shift($get_sales_order)) {
								$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
								$boxgoodvalue = $dtt_view_res["boxgoodvalue"];
								$boxgoodvaluearray	= explode(".", $boxgoodvalue);
								$boxgoodvalueDollar = $boxgoodvaluearray[0];
								$boxgoodvalueCents	= "0." . $boxgoodvaluearray[1];


								db_b2b();

								$get_box_data = db_query("Select ulineDollar, ulineCents, costDollar, costCents, overhead_costDollar, overhead_costCents  from inventory where ID = " .  $dtt_view_res["b2b_id"]);
								while ($box_data_res = array_shift($get_box_data)) {

									$b2b_ovh_costDollar = round($box_data_res["overhead_costDollar"]);
									$b2b_ovh_costCents = $box_data_res["overhead_costCents"];

									$b2b_costDollar = floatval($boxgoodvalueDollar) + floatval($b2b_ovh_costDollar);
									$b2b_costCents = $boxgoodvalueCents + $b2b_ovh_costCents;

									$b2b_cost = $b2b_costDollar + $b2b_costCents;
								}
							}
							db();
							$sales_qry = db_query("Select qty from loop_salesorders_manual WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($dtt_view_res = array_shift($sales_qry)) {
								$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
							}

							$cogs_val = $b2b_cost * $salesorder_qty;
							$cogs = (-$freight_cost) - $cogs_val;

							$gross_profit = $po_poorderamount + $cogs;

							$payment_val = $gross_profit;
							$amt10 = $amt10 + $gross_profit;
						} else {
							$payment_val = $dt_view_row["SUMPO"];
							$amt10 = $amt10 + $dt_view_row["SUMPO"];
						}

						$bdescription = "";
						$supplier_name = "";
						db();
						$selqry1 = db_query("Select loop_salesorders.box_id, loop_boxes.bdescription, loop_boxes.b2b_id from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
						while ($selres1 = array_shift($selqry1)) {
							$bdescription .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $selres1['box_id'] . "&proc=View'>" . $selres1["bdescription"] . "</a><br>";
							db_b2b();
							$selqry2 = db_query("Select vendor_b2b_rescue from inventory where ID = " . $selres1["b2b_id"]);
							$selres2 = array_shift($selqry2);
							$vendor_b2b_rescue = $selres2["vendor_b2b_rescue"];

							$sname = "";
							db();
							$selqry3 = db_query("SELECT id, company_name, b2bid FROM loop_warehouse where id=" . $vendor_b2b_rescue);
							$selres3 = array_shift($selqry3);
							if (!empty($selres3)) {
								$sname = getnickname($selres3['company_name'], $selres3["b2bid"]);
								$supplier_name .= "<a target='_blank' href='viewCompany.php?ID=" . $selres3["b2bid"] . "'>" . $sname . " (Loop ID: " . $selres3["id"] . " B2B ID:" . $selres3["b2bid"] . ")</a><br>";
							}
						}
						$bdescription = substr($bdescription, 0, strlen($bdescription) - 4);
						$supplier_name = substr($supplier_name, 0, strlen($supplier_name) - 4);
						$vendor_pay = 0;
						$inv_amount = 0;
						db();
						$selqry4 = "SELECT loop_transaction_buyer_payments.estimated_cost from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["id"];
						$selres4 = db_query($selqry4);
						$num_rows1 = tep_db_num_rows($selres4);
						if ($num_rows1 > 0) {

							while ($row4 = array_shift($selres4)) {
								$vendor_pay += $row4["estimated_cost"];
							}
						}

						$inv_amount = $dt_view_row["F"];

						$invoice_amt = 0;
						db();
						$selqry6 = "SELECT * FROM loop_invoice_items WHERE trans_rec_id = " . $dt_view_row["id"] . " ORDER BY id ASC";
						$selres6 = db_query($selqry6);
						while ($inv_row = array_shift($selres6)) {
							$invoice_amt += $inv_row["quantity"] * $inv_row["price"];
						}
						if ($invoice_amt == 0) {
							$invoice_amt = $inv_amount;
						}
						if ($inv_amount == 0 && $invoice_amt > 0) {
							$inv_amount = $invoice_amt;
						}


						$profit_val = number_format(floatval(str_replace(",", "", $inv_amount)) - floatval(str_replace(",", "", strval($vendor_pay))), 2);
						$inv_amount = str_replace(",", "", $inv_amount);

						$profit_val_per = "";
						if (floatval($inv_amount) > 0) {
							$profit_val_per = abs((floatval($profit_val) * 100) / floatval($inv_amount));
						}

						if ($profit_val_per != "") {
							if ($profit_val_per >= 30) {
								if ($profit_val < 0) {
									$profit_val_per = "<font color='#008000'>-" . number_format($profit_val_per, 2) . "%</font>";
								} else {
									$profit_val_per = "<font color='#008000'>" . number_format($profit_val_per, 2) . "%</font>";
								}
							} else if ($profit_val_per < 30) {
								if ($profit_val < 0) {
									$profit_val_per = "<font color='#EE3838'>-" . number_format($profit_val_per, 2) . "%</font>";
								} else {
									$profit_val_per = "<font color='#EE3838'>" . number_format($profit_val_per, 2) . "%</font>";
								}
							}
						}

						//$nickname = "";
						$nickname = getnickname($dt_view_row["warehouse_name"], $dt_view_row["b2bid"]);

						$org_delivery_dt = "";
						$po_delivery_dt = "";
						$actualDel_dt = "";
						if ($dt_view_row["po_delivery_dt"] != "") {
							$h_qry = "select planned_delivery_dt from planned_delivery_date_history where trans_id=" . $dt_view_row["id"] . " order by id ASC limit 1";
							db();
							$h_res = db_query($h_qry);
							$cnt_rw1 = tep_db_num_rows($h_res);
							if ($cnt_rw1 > 0) {
								while ($row1 = array_shift($h_res)) {
									$org_delivery_dt = date('m/d/Y', strtotime($row1["planned_delivery_dt"]));
								}
							} else {
								$org_delivery_dt = date('m/d/Y', strtotime($dt_view_row['po_delivery_dt']));
							}

							$po_delivery_dt = date('m/d/Y', strtotime($dt_view_row["po_delivery_dt"]));
						}

						$sql_act = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $dt_view_row["id"];
						$sql_res = db_query($sql_act);
						$cnt_rw1 = tep_db_num_rows($sql_res);
						if ($cnt_rw1 > 0) {
							while ($row = array_shift($sql_res)) {
								if ($row["bol_shipment_received_date"] != "") {
									$actualDel_dt = date('m/d/Y', strtotime($row["bol_shipment_received_date"]));
								}
							}
						}

						$lisofdetails10 .= "<tr><td bgColor='#E4EAEB'>" . $initials . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $supplier_name . "</td><td bgColor='#E4EAEB'>" . $bdescription . "</td>
					<td bgColor='#E4EAEB'>" . $org_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actualDel_dt . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($payment_val, 0) . "</td><td bgColor='#E4EAEB'>" . $profit_val_per . "</td></tr>";
					}

					// for col 12 (Request Invoice)
					$dt_view_qry = "SELECT po_file, ops_delivery_date, po_employee, loop_warehouse.warehouse_name, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
					$dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
					$dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
					$dt_view_qry .= "INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id ";
					if ($sel_cdeal == "y") {
						$dt_view_qry .= "WHERE loop_transaction_buyer.recycling_flg = 0 $emp_filter  $pallet_str and loop_transaction_buyer.shipped = 1 and loop_bol_files.bol_shipment_received = 1 and inv_entered = 0";
						$dt_view_qry .= " and loop_transaction_buyer.po_delivery_dt between '" . $date_from . "' and '" . $date_to . "' and proof_of_delivery = ''";
						$dt_view_qry .= " and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 and loop_transaction_buyer.id not in (select trans_rec_id from loop_invoice_details) GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
					} else {

						$dt_view_qry .= "WHERE loop_transaction_buyer.recycling_flg = 0 $emp_filter  $pallet_str and loop_transaction_buyer.shipped = 1 and loop_bol_files.bol_shipment_received = 1 and inv_entered = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 and loop_transaction_buyer.id not in (select trans_rec_id from loop_invoice_details) GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
					}
					//echo "Q Req inv = " .$dt_view_qry . "<br>";
					$dt_view_res = db_query($dt_view_qry);
					$amt12 = 0;
					$lisofdetails12 = "";

					while ($dt_view_row = array_shift($dt_view_res)) {
						if ($_REQUEST['protype'] == 2) {
							$freight_cost = $dt_view_row["po_freight"];
							$po_poorderamount = $dt_view_row["SUMPO"];
							$salesorder_qty = 0;
							$payment_val = 0;

							$get_sales_order = db_query("Select loop_salesorders.qty, loop_boxes.b2b_id, loop_boxes.boxgoodvalue from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($dtt_view_res = array_shift($get_sales_order)) {
								$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
								$boxgoodvalue = $dtt_view_res["boxgoodvalue"];
								$boxgoodvaluearray	= explode(".", $boxgoodvalue);
								$boxgoodvalueDollar = $boxgoodvaluearray[0];
								$boxgoodvalueCents	= "0." . $boxgoodvaluearray[1];

								db_b2b();

								$get_box_data = db_query("Select ulineDollar, ulineCents, costDollar, costCents, overhead_costDollar, overhead_costCents  from inventory where ID = " .  $dtt_view_res["b2b_id"]);
								while ($box_data_res = array_shift($get_box_data)) {

									$b2b_ovh_costDollar = round($box_data_res["overhead_costDollar"]);
									$b2b_ovh_costCents = $box_data_res["overhead_costCents"];

									$b2b_costDollar = floatval($boxgoodvalueDollar) + floatval($b2b_ovh_costDollar);
									$b2b_costCents = $boxgoodvalueCents + $b2b_ovh_costCents;

									$b2b_cost = $b2b_costDollar + $b2b_costCents;
								}
							}
							db();
							$sales_qry = db_query("Select qty from loop_salesorders_manual WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($dtt_view_res = array_shift($sales_qry)) {
								$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
							}

							$cogs_val = $b2b_cost * $salesorder_qty;
							$cogs = (-$freight_cost) - $cogs_val;

							$gross_profit = $po_poorderamount + $cogs;

							$payment_val = $gross_profit;
							$amt12 = $amt12 + $gross_profit;
						} else {
							$payment_val = $dt_view_row["SUMPO"];
							$amt12 = $amt12 + $dt_view_row["SUMPO"];
						}

						$bdescription = "";
						$supplier_name = "";
						db();
						$selqry1 = db_query("Select loop_salesorders.box_id, loop_boxes.bdescription, loop_boxes.b2b_id from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
						while ($selres1 = array_shift($selqry1)) {
							$bdescription .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $selres1['box_id'] . "&proc=View'>" . $selres1["bdescription"] . "</a><br>";

							db_b2b();
							$selqry2 = db_query("Select vendor_b2b_rescue from inventory where ID = " . $selres1["b2b_id"]);
							$selres2 = array_shift($selqry2);
							$vendor_b2b_rescue = $selres2["vendor_b2b_rescue"];

							$sname = "";
							db();
							$selqry3 = db_query("SELECT id, company_name, b2bid FROM loop_warehouse where id=" . $vendor_b2b_rescue);
							$selres3 = array_shift($selqry3);
							if (!empty($selres3)) {
								$sname = getnickname($selres3['company_name'], $selres3["b2bid"]);
								$supplier_name .= "<a target='_blank' href='viewCompany.php?ID=" . $selres3["b2bid"] . "'>" . $sname . " (Loop ID: " . $selres3["id"] . " B2B ID:" . $selres3["b2bid"] . ")</a><br>";
							}
						}
						$bdescription = substr($bdescription, 0, strlen($bdescription) - 4);
						$supplier_name = substr($supplier_name, 0, strlen($supplier_name) - 4);
						$vendor_pay = 0;
						$inv_amount = 0;
						db_b2b();
						$selqry4 = "SELECT loop_transaction_buyer_payments.estimated_cost from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["id"];
						db();
						$selres4 = db_query($selqry4);
						$num_rows1 = tep_db_num_rows($selres4);
						if ($num_rows1 > 0) {

							while ($row4 = array_shift($selres4)) {
								$vendor_pay += $row4["estimated_cost"];
							}
						}

						$inv_amount = $dt_view_row["F"];

						$invoice_amt = 0;
						db_b2b();
						$selqry6 = "SELECT * FROM loop_invoice_items WHERE trans_rec_id = " . $dt_view_row["id"] . " ORDER BY id ASC";
						db();
						$selres6 = db_query($selqry6);
						while ($inv_row = array_shift($selres6)) {
							$invoice_amt += $inv_row["quantity"] * $inv_row["price"];
						}
						if ($invoice_amt == 0) {
							$invoice_amt = $inv_amount;
						}
						if ($inv_amount == 0 && $invoice_amt > 0) {
							$inv_amount = $invoice_amt;
						}


						$profit_val = number_format(floatval(str_replace(",", "", strval($inv_amount))) - floatval(str_replace(",", "", strval($vendor_pay))), 2);
						$profit_val = str_replace(",", "", $profit_val);
						$inv_amount = str_replace(",", "", $inv_amount);

						$profit_val_per = "";
						if (floatval($inv_amount) > 0) {
							$profit_val_per = abs((floatval($profit_val) * 100) / floatval($inv_amount));
						}

						if ($profit_val_per != "") {
							if ($profit_val_per >= 30) {
								if ($profit_val < 0) {
									$profit_val_per = "<font color='#008000'>-" . number_format($profit_val_per, 2) . "%</font>";
								} else {
									$profit_val_per = "<font color='#008000'>" . number_format($profit_val_per, 2) . "%</font>";
								}
							} else if ($profit_val_per < 30) {
								if ($profit_val < 0) {
									$profit_val_per = "<font color='#EE3838'>-" . number_format($profit_val_per, 2) . "%</font>";
								} else {
									$profit_val_per = "<font color='#EE3838'>" . number_format($profit_val_per, 2) . "%</font>";
								}
							}
						}

						//$nickname = "";
						$nickname = getnickname($dt_view_row["warehouse_name"], $dt_view_row["b2bid"]);

						$org_delivery_dt = "";
						$po_delivery_dt = "";
						$actualDel_dt = "";
						if ($dt_view_row["po_delivery_dt"] != "") {
							$h_qry = "select planned_delivery_dt from planned_delivery_date_history where trans_id=" . $dt_view_row["id"] . " order by id ASC limit 1";
							db();
							$h_res = db_query($h_qry);
							$cnt_rw1 = tep_db_num_rows($h_res);
							if ($cnt_rw1 > 0) {
								while ($row1 = array_shift($h_res)) {
									$org_delivery_dt = date('m/d/Y', strtotime($row1["planned_delivery_dt"]));
								}
							} else {
								$org_delivery_dt = date('m/d/Y', strtotime($dt_view_row['po_delivery_dt']));
							}

							$po_delivery_dt = date('m/d/Y', strtotime($dt_view_row["po_delivery_dt"]));
						}

						$sql_act = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $dt_view_row["id"];
						$sql_res = db_query($sql_act);
						$cnt_rw1 = tep_db_num_rows($sql_res);
						if ($cnt_rw1 > 0) {
							while ($row = array_shift($sql_res)) {
								if ($row["bol_shipment_received_date"] != "") {
									$actualDel_dt = date('m/d/Y', strtotime($row["bol_shipment_received_date"]));
								}
							}
						}

						$lisofdetails12 .= "<tr><td bgColor='#E4EAEB'>" . $initials . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $supplier_name . "</td><td bgColor='#E4EAEB'>" . $bdescription . "</td>
					<td bgColor='#E4EAEB'>" . $org_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actualDel_dt . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($payment_val, 0) . "</td><td bgColor='#E4EAEB'>" . $profit_val_per . "</td></tr>";
					}

					// for col 13 (Need QB Invoice Uploaded)

					$dt_view_qry = "SELECT po_file, ops_delivery_date, po_employee, loop_warehouse.warehouse_name, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
					$dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
					$dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
					$dt_view_qry .= "INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id ";

					if ($sel_cdeal == "y") {
						$dt_view_qry .= "WHERE loop_transaction_buyer.inv_amount = 0 $emp_filter  $pallet_str and loop_transaction_buyer.shipped = 1 and loop_bol_files.bol_shipment_received = 0 and inv_entered = 0";
						$dt_view_qry .= " and loop_transaction_buyer.po_delivery_dt between '" . $date_from . "' and '" . $date_to . "' and proof_of_delivery = ''";
						$dt_view_qry .= " and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 and loop_transaction_buyer.id in (select trans_rec_id from loop_invoice_details) GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
					} else {
						$dt_view_qry .= "WHERE loop_transaction_buyer.inv_amount = 0 $emp_filter  $pallet_str and loop_transaction_buyer.shipped = 1 and loop_bol_files.bol_shipment_received = 1 and inv_entered = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 and loop_transaction_buyer.id in (select trans_rec_id from loop_invoice_details) GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
					}

					$dt_view_res = db_query($dt_view_qry);
					$amt13 = 0;
					$lisofdetails13 = "";

					while ($dt_view_row = array_shift($dt_view_res)) {

						if ($_REQUEST['protype'] == 2) {
							$freight_cost = $dt_view_row["po_freight"];
							$po_poorderamount = $dt_view_row["SUMPO"];
							$salesorder_qty = 0;
							$payment_val = 0;

							$get_sales_order = db_query("Select loop_salesorders.qty, loop_boxes.b2b_id, loop_boxes.boxgoodvalue from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($dtt_view_res = array_shift($get_sales_order)) {
								$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
								$boxgoodvalue = $dtt_view_res["boxgoodvalue"];
								$boxgoodvaluearray	= explode(".", $boxgoodvalue);
								$boxgoodvalueDollar = $boxgoodvaluearray[0];
								$boxgoodvalueCents	= "0." . $boxgoodvaluearray[1];

								db_b2b();

								$get_box_data = db_query("Select ulineDollar, ulineCents, costDollar, costCents, overhead_costDollar, overhead_costCents  from inventory where ID = " .  $dtt_view_res["b2b_id"]);
								while ($box_data_res = array_shift($get_box_data)) {

									$b2b_ovh_costDollar = round($box_data_res["overhead_costDollar"]);
									$b2b_ovh_costCents = $box_data_res["overhead_costCents"];

									$b2b_costDollar = floatval($boxgoodvalueDollar) + $b2b_ovh_costDollar;
									$b2b_costCents = $boxgoodvalueCents + $b2b_ovh_costCents;

									$b2b_cost = $b2b_costDollar + $b2b_costCents;
								}
							}
							db();
							$get_sales_order = db_query("Select qty from loop_salesorders_manual WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($dtt_view_res = array_shift($get_sales_order)) {
								$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
							}

							$cogs_val = $b2b_cost * $salesorder_qty;
							$cogs = (-$freight_cost) - $cogs_val;

							$gross_profit = $po_poorderamount + $cogs;

							$payment_val = $gross_profit;
							$amt13 = $amt13 + $gross_profit;
						} else {
							if ($dt_view_row["F"] > 0) {
								$payment_val = $dt_view_row["F"];
								$amt13 = $amt13 + $dt_view_row["F"];
							} else {
								$payment_val = $dt_view_row["SUMPO"];
								$amt13 = $amt13 + $dt_view_row["SUMPO"];
							}
						}

						$bdescription = "";
						$supplier_name = "";
						db();
						$selqry1 = db_query("Select loop_salesorders.box_id, loop_boxes.bdescription, loop_boxes.b2b_id from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
						while ($selres1 = array_shift($selqry1)) {
							$bdescription .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $selres1['box_id'] . "&proc=View'>" . $selres1["bdescription"] . "</a><br>";
							db_b2b();
							$selqry2 = db_query("Select vendor_b2b_rescue from inventory where ID = " . $selres1["b2b_id"]);
							$selres2 = array_shift($selqry2);
							$vendor_b2b_rescue = $selres2["vendor_b2b_rescue"];

							$sname = "";
							db();
							$selqry3 = db_query("SELECT id, company_name, b2bid FROM loop_warehouse where id=" . $vendor_b2b_rescue);
							$selres3 = array_shift($selqry3);
							if (!empty($selres3)) {
								$sname = getnickname($selres3['company_name'], $selres3["b2bid"]);
								$supplier_name .= "<a target='_blank' href='viewCompany.php?ID=" . $selres3["b2bid"] . "'>" . $sname . " (Loop ID: " . $selres3["id"] . " B2B ID:" . $selres3["b2bid"] . ")</a><br>";
							}
						}
						$bdescription = substr($bdescription, 0, strlen($bdescription) - 4);
						$supplier_name = substr($supplier_name, 0, strlen($supplier_name) - 4);
						$vendor_pay = 0;
						$inv_amount = 0;
						db();
						$selqry4 = "SELECT loop_transaction_buyer_payments.estimated_cost from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["id"];
						$selres4 = db_query($selqry4);
						$num_rows1 = tep_db_num_rows($selres4);
						if ($num_rows1 > 0) {

							while ($row4 = array_shift($selres4)) {
								$vendor_pay += $row4["estimated_cost"];
							}
						}

						$inv_amount = $dt_view_row["F"];

						$invoice_amt = 0;
						db();
						$selqry6 = "SELECT * FROM loop_invoice_items WHERE trans_rec_id = " . $dt_view_row["id"] . " ORDER BY id ASC";
						$selres6 = db_query($selqry6);
						while ($inv_row = array_shift($selres6)) {
							$invoice_amt += $inv_row["quantity"] * $inv_row["price"];
						}
						if ($invoice_amt == 0) {
							$invoice_amt = $inv_amount;
						}
						if ($inv_amount == 0 && $invoice_amt > 0) {
							$inv_amount = $invoice_amt;
						}


						$profit_val = number_format(floatval(str_replace(",", "", strval($inv_amount))) - floatval(str_replace(",", "", strval($vendor_pay))), 2);
						$profit_val = str_replace(",", "", $profit_val);
						$inv_amount = str_replace(",", "", $inv_amount);

						$profit_val_per = "";
						if (intval($inv_amount) > 0) {
							$inv_amount = floatval($inv_amount);
							$profit_val_per = abs((floatval($profit_val) * 100) / floatval($inv_amount));
						}

						if ($profit_val_per != "") {
							if ($profit_val_per >= 30) {
								if ($profit_val < 0) {
									$profit_val_per = "<font color='#008000'>-" . number_format($profit_val_per, 2) . "%</font>";
								} else {
									$profit_val_per = "<font color='#008000'>" . number_format($profit_val_per, 2) . "%</font>";
								}
							} else if ($profit_val_per < 30) {
								if ($profit_val < 0) {
									$profit_val_per = "<font color='#EE3838'>-" . number_format($profit_val_per, 2) . "%</font>";
								} else {
									$profit_val_per = "<font color='#EE3838'>" . number_format($profit_val_per, 2) . "%</font>";
								}
							}
						}

						//$nickname = "";
						$nickname = getnickname($dt_view_row["warehouse_name"], $dt_view_row["b2bid"]);

						$org_delivery_dt = "";
						$po_delivery_dt = "";
						$actualDel_dt = "";
						if ($dt_view_row["po_delivery_dt"] != "") {
							$h_qry = "select planned_delivery_dt from planned_delivery_date_history where trans_id=" . $dt_view_row["id"] . " order by id ASC limit 1";
							db();
							$h_res = db_query($h_qry);
							$cnt_rw1 = tep_db_num_rows($h_res);
							if ($cnt_rw1 > 0) {
								while ($row1 = array_shift($h_res)) {
									$org_delivery_dt = date('m/d/Y', strtotime($row1["planned_delivery_dt"]));
								}
							} else {
								$org_delivery_dt = date('m/d/Y', strtotime($dt_view_row['po_delivery_dt']));
							}

							$po_delivery_dt = date('m/d/Y', strtotime($dt_view_row["po_delivery_dt"]));
						}

						$sql_act = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $dt_view_row["id"];
						db();
						$sql_res = db_query($sql_act);
						$cnt_rw1 = tep_db_num_rows($sql_res);
						if ($cnt_rw1 > 0) {
							while ($row = array_shift($sql_res)) {
								if ($row["bol_shipment_received_date"] != "") {
									$actualDel_dt = date('m/d/Y', strtotime($row["bol_shipment_received_date"]));
								}
							}
						}

						$lisofdetails13 .= "<tr><td bgColor='#E4EAEB'>" . $initials . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $supplier_name . "</td><td bgColor='#E4EAEB'>" . $bdescription . "</td>
					<td bgColor='#E4EAEB'>" . $org_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actualDel_dt . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($payment_val, 0) . "</td><td bgColor='#E4EAEB'>" . $profit_val_per . "</td></tr>";
					}

					// for col 14 (Awaiting Payment)
					$dt_view_qry = "SELECT po_file, ops_delivery_date, po_employee, loop_warehouse.warehouse_name, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
					$dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
					$dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
					$dt_view_qry .= "INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id ";
					if ($sel_cdeal == "y") {
						$dt_view_qry .= "WHERE loop_bol_files.bol_shipment_received = 0 and loop_transaction_buyer.shipped = 1 $emp_filter  $pallet_str AND loop_transaction_buyer.inv_amount > 0 and pmt_entered = 0 ";
						$dt_view_qry .= " and loop_transaction_buyer.po_delivery_dt between '" . $date_from . "' and '" . $date_to . "' and proof_of_delivery = ''";
						$dt_view_qry .= " AND loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.invoice_paid = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
					} else {

						$dt_view_qry .= "WHERE loop_transaction_buyer.shipped = 1 $emp_filter  $pallet_str AND loop_transaction_buyer.inv_amount > 0 and pmt_entered = 0 AND loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.invoice_paid = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
					}

					$dt_view_res = db_query($dt_view_qry);
					$amt14 = 0;
					$lisofdetails14 = "";

					while ($dt_view_row = array_shift($dt_view_res)) {
						$add_in_array = "yes";

						$payment_val = 0;
						$invoice_paid = 0;
						$payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A FROM loop_buyer_payments WHERE trans_rec_id = " . $dt_view_row["id"];
						$payment_qry = db_query($payments_sql);
						while ($payment = array_shift($payment_qry)) {
							$payment_val = $payment["A"];
						}


						$payment1 = number_format($dt_view_row["F"], 2);

						if (!is_null($payment_val)) {

							$payment2 = number_format($payment_val, 2);
						}

						$payment1 = str_replace(",", "", $payment1);
						$payment2 = str_replace(",", "", $payment2);
						if ($payment1 == $payment2 && $payment1 > 0) {
							$invoice_paid = 1;
						}
						if ($dt_view_row["no_invoice"] == 1) {
							$invoice_paid = 1;
						}

						if ($invoice_paid == 1) {
							$add_in_array = "no";
						}


						if ($add_in_array == "yes") {
							if ($_REQUEST['protype'] == 2) {
								$freight_cost = $dt_view_row["po_freight"];
								$po_poorderamount = $dt_view_row["SUMPO"];
								$salesorder_qty = 0;
								$payment_val = 0;

								$get_sales_order = db_query("Select loop_salesorders.qty, loop_boxes.b2b_id, loop_boxes.boxgoodvalue from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
								while ($dtt_view_res = array_shift($get_sales_order)) {
									$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
									$boxgoodvalue = $dtt_view_res["boxgoodvalue"];
									$boxgoodvaluearray	= explode(".", $boxgoodvalue);
									$boxgoodvalueDollar = $boxgoodvaluearray[0];
									$boxgoodvalueCents	= "0." . $boxgoodvaluearray[1];


									db_b2b();

									$get_box_data = db_query("Select ulineDollar, ulineCents, costDollar, costCents, overhead_costDollar, overhead_costCents  from inventory where ID = " .  $dtt_view_res["b2b_id"]);
									while ($box_data_res = array_shift($get_box_data)) {

										$b2b_ovh_costDollar = round($box_data_res["overhead_costDollar"]);
										$b2b_ovh_costCents = $box_data_res["overhead_costCents"];

										$b2b_costDollar = floatval($boxgoodvalueDollar) + $b2b_ovh_costDollar;
										$b2b_costCents = $boxgoodvalueCents + $b2b_ovh_costCents;

										$b2b_cost = $b2b_costDollar + $b2b_costCents;
									}
								}
								db();
								$sales_qry = db_query("Select qty from loop_salesorders_manual WHERE trans_rec_id = " .  $dt_view_row["id"]);
								while ($dtt_view_res = array_shift($sales_qry)) {
									$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
								}

								$cogs_val = $b2b_cost * $salesorder_qty;
								$cogs = (-$freight_cost) - $cogs_val;

								$gross_profit = $po_poorderamount + $cogs;

								$payment_val = $gross_profit;
								$amt14 = $amt14 + $gross_profit;
							} else {
								$payment_val = $dt_view_row["F"];
								$amt14 = $amt14 + $dt_view_row["F"];
							}

							$bdescription = "";
							$supplier_name = "";
							db();
							$selqry1 = db_query("Select loop_salesorders.box_id, loop_boxes.bdescription, loop_boxes.b2b_id from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($selres1 = array_shift($selqry1)) {
								$bdescription .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $selres1['box_id'] . "&proc=View'>" . $selres1["bdescription"] . "</a><br>";

								db_b2b();
								$selqry2 = db_query("Select vendor_b2b_rescue from inventory where ID = " . $selres1["b2b_id"]);
								$selres2 = array_shift($selqry2);
								$vendor_b2b_rescue = $selres2["vendor_b2b_rescue"];

								$sname = "";
								db();
								$selqry3 = db_query("SELECT id, company_name, b2bid FROM loop_warehouse where id=" . $vendor_b2b_rescue);
								$selres3 = array_shift($selqry3);
								if (!empty($selres3)) {
									$sname = getnickname($selres3['company_name'], $selres3["b2bid"]);
									$supplier_name .= "<a target='_blank' href='viewCompany.php?ID=" . $selres3["b2bid"] . "'>" . $sname . " (Loop ID: " . $selres3["id"] . " B2B ID:" . $selres3["b2bid"] . ")</a><br>";
								}
							}
							$bdescription = substr($bdescription, 0, strlen($bdescription) - 4);
							$supplier_name = substr($supplier_name, 0, strlen($supplier_name) - 4);
							$vendor_pay = 0;
							$inv_amount = 0;
							db();
							$selqry4 = "SELECT loop_transaction_buyer_payments.estimated_cost from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["id"];
							$selres4 = db_query($selqry4);
							$num_rows1 = tep_db_num_rows($selres4);
							if ($num_rows1 > 0) {

								while ($row4 = array_shift($selres4)) {
									$vendor_pay += $row4["estimated_cost"];
								}
							}

							$inv_amount = $dt_view_row["F"];

							$invoice_amt = 0;
							db();
							$selqry6 = "SELECT * FROM loop_invoice_items WHERE trans_rec_id = " . $dt_view_row["id"] . " ORDER BY id ASC";
							$selres6 = db_query($selqry6);
							while ($inv_row = array_shift($selres6)) {
								$invoice_amt += $inv_row["quantity"] * $inv_row["price"];
							}
							if ($invoice_amt == 0) {
								$invoice_amt = $inv_amount;
							}
							if ($inv_amount == 0 && $invoice_amt > 0) {
								$inv_amount = $invoice_amt;
							}


							$profit_val = number_format(floatval(str_replace(",", "", strval($inv_amount))) - floatval(str_replace(",", "", strval($vendor_pay))), 2);
							$profit_val = str_replace(",", "", $profit_val);
							$inv_amount = str_replace(",", "", $inv_amount);

							$profit_val_per = "";
							if (floatval($inv_amount) > 0) {
								$inv_amount = str_replace(",", "", strval($inv_amount));
								$profit_val_per = abs((floatval(str_replace(",", "", $profit_val)) * 100) / floatval(str_replace(",", "", $inv_amount)));
							}

							if ($profit_val_per != "") {
								if ($profit_val_per >= 30) {
									if ($profit_val < 0) {
										$profit_val_per = "<font color='#008000'>-" . number_format($profit_val_per, 2) . "%</font>";
									} else {
										$profit_val_per = "<font color='#008000'>" . number_format($profit_val_per, 2) . "%</font>";
									}
								} else if ($profit_val_per < 30) {
									if ($profit_val < 0) {
										$profit_val_per = "<font color='#EE3838'>-" . number_format($profit_val_per, 2) . "%</font>";
									} else {
										$profit_val_per = "<font color='#EE3838'>" . number_format($profit_val_per, 2) . "%</font>";
									}
								}
							}

							//$nickname = "";
							$nickname = getnickname($dt_view_row["warehouse_name"], $dt_view_row["b2bid"]);

							$org_delivery_dt = "";
							$po_delivery_dt = "";
							$actualDel_dt = "";
							if ($dt_view_row["po_delivery_dt"] != "") {
								$h_qry = "select planned_delivery_dt from planned_delivery_date_history where trans_id=" . $dt_view_row["id"] . " order by id ASC limit 1";
								db();
								$h_res = db_query($h_qry);
								$cnt_rw1 = tep_db_num_rows($h_res);
								if ($cnt_rw1 > 0) {
									while ($row1 = array_shift($h_res)) {
										$org_delivery_dt = date('m/d/Y', strtotime($row1["planned_delivery_dt"]));
									}
								} else {
									$org_delivery_dt = date('m/d/Y', strtotime($dt_view_row['po_delivery_dt']));
								}

								$po_delivery_dt = date('m/d/Y', strtotime($dt_view_row["po_delivery_dt"]));
							}

							$sql_act = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $dt_view_row["id"];
							$sql_res = db_query($sql_act);
							$cnt_rw1 = tep_db_num_rows($sql_res);
							if ($cnt_rw1 > 0) {
								while ($row = array_shift($sql_res)) {
									if ($row["bol_shipment_received_date"] != "") {
										$actualDel_dt = date('m/d/Y', strtotime($row["bol_shipment_received_date"]));
									}
								}
							}

							$lisofdetails14 .= "<tr><td bgColor='#E4EAEB'>" . $initials . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $supplier_name . "</td><td bgColor='#E4EAEB'>" . $bdescription . "</td>
						<td bgColor='#E4EAEB'>" . $org_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actualDel_dt . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($payment_val, 0) . "</td><td bgColor='#E4EAEB'>" . $profit_val_per . "</td></tr>";
						}
					}

					// for col 15 (Double Checks for Payroll)
					$dt_view_qry = "SELECT po_file, ops_delivery_date, loop_warehouse.warehouse_name, po_employee, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
					$dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
					$dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
					if ($sel_cdeal == "y") {
						$dt_view_qry .= " INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id  
					WHERE loop_bol_files.bol_shipment_received = 0 and loop_transaction_buyer.double_checked = 0 $emp_filter  $pallet_str and loop_transaction_buyer.shipped = 1 AND inv_entered = 1 AND commission_paid = 0 ";
						$dt_view_qry .= " and loop_transaction_buyer.po_delivery_dt between '" . $date_from . "' and '" . $date_to . "' and proof_of_delivery = ''";
						$dt_view_qry .= " AND loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
					} else {

						$dt_view_qry .= "WHERE loop_transaction_buyer.double_checked = 0 $emp_filter  $pallet_str and loop_transaction_buyer.shipped = 1 AND inv_entered = 1 AND commission_paid = 0 AND loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
					}

					$dt_view_res = db_query($dt_view_qry);
					$amt15 = 0;
					$lisofdetails15 = "";

					while ($dt_view_row = array_shift($dt_view_res)) {
						$add_in_array = "yes";

						$payment_val = 0;
						$invoice_paid = 0;
						$payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A FROM loop_buyer_payments WHERE trans_rec_id = " . $dt_view_row["id"];
						$payment_qry = db_query($payments_sql);
						while ($payment = array_shift($payment_qry)) {
							$payment_val = $payment["A"];
						}
						$payment1 = number_format($dt_view_row["F"], 2);

						if (!is_null($payment_val)) {
							$payment2 = number_format($payment_val, 2);
						}

						$payment1 = str_replace(",", "", $payment1);
						$payment2 = str_replace(",", "", $payment2);
						if ($payment1 == $payment2 && $payment1 > 0) {
							$invoice_paid = 1;
						}
						if ($dt_view_row["no_invoice"] == 1) {
							$invoice_paid = 1;
						}

						if ($invoice_paid == 1) {
							$add_in_array = "yes";
						} else {
							$add_in_array = "no";
						}


						if ($add_in_array == "yes") {
							if ($_REQUEST['protype'] == 2) {
								$freight_cost = $dt_view_row["po_freight"];
								$po_poorderamount = $dt_view_row["SUMPO"];
								$salesorder_qty = 0;
								$payment_val = 0;

								$get_sales_order = db_query("Select loop_salesorders.qty, loop_boxes.b2b_id, loop_boxes.boxgoodvalue from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
								while ($dtt_view_res = array_shift($get_sales_order)) {
									$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
									$boxgoodvalue = $dtt_view_res["boxgoodvalue"];
									$boxgoodvaluearray	= explode(".", $boxgoodvalue);
									$boxgoodvalueDollar = $boxgoodvaluearray[0];
									$boxgoodvalueCents	= "0." . $boxgoodvaluearray[1];


									db_b2b();

									$get_box_data = db_query("Select ulineDollar, ulineCents, costDollar, costCents, overhead_costDollar, overhead_costCents  from inventory where ID = " .  $dtt_view_res["b2b_id"]);
									while ($box_data_res = array_shift($get_box_data)) {

										$b2b_ovh_costDollar = round($box_data_res["overhead_costDollar"]);
										$b2b_ovh_costCents = $box_data_res["overhead_costCents"];

										$b2b_costDollar = floatval($boxgoodvalueDollar) + $b2b_ovh_costDollar;
										$b2b_costCents = $boxgoodvalueCents + $b2b_ovh_costCents;

										$b2b_cost = $b2b_costDollar + $b2b_costCents;
									}
								}
								db();
								$sales_qry = db_query("Select qty from loop_salesorders_manual WHERE trans_rec_id = " .  $dt_view_row["id"]);
								while ($dtt_view_res = array_shift($sales_qry)) {
									$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
								}

								$cogs_val = $b2b_cost * $salesorder_qty;
								$cogs = (-$freight_cost) - $cogs_val;

								$gross_profit = $po_poorderamount + $cogs;

								$payment_val = $gross_profit;
								$amt15 = $amt15 + $gross_profit;
							} else {

								$amt15 = $amt15 + $payment_val;
							}

							$bdescription = "";
							$supplier_name = "";
							db();
							$selqry1 = db_query("Select loop_salesorders.box_id, loop_boxes.bdescription, loop_boxes.b2b_id from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($selres1 = array_shift($selqry1)) {
								$bdescription .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $selres1['box_id'] . "&proc=View'>" . $selres1["bdescription"] . "</a><br>";

								db_b2b();
								$selqry2 = db_query("Select vendor_b2b_rescue from inventory where ID = " . $selres1["b2b_id"]);
								$selres2 = array_shift($selqry2);
								$vendor_b2b_rescue = $selres2["vendor_b2b_rescue"];

								$sname = "";
								db();
								$selqry3 = db_query("SELECT id, company_name, b2bid FROM loop_warehouse where id=" . $vendor_b2b_rescue);
								$selres3 = array_shift($selqry3);
								if (!empty($selres3)) {
									$sname = getnickname($selres3['company_name'], $selres3["b2bid"]);
									$supplier_name .= "<a target='_blank' href='viewCompany.php?ID=" . $selres3["b2bid"] . "'>" . $sname . " (Loop ID: " . $selres3["id"] . " B2B ID:" . $selres3["b2bid"] . ")</a><br>";
								}
							}
							$bdescription = substr($bdescription, 0, strlen($bdescription) - 4);
							$supplier_name = substr($supplier_name, 0, strlen($supplier_name) - 4);
							$vendor_pay = 0;
							$inv_amount = 0;
							db();
							$selqry4 = "SELECT loop_transaction_buyer_payments.estimated_cost from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["id"];
							$selres4 = db_query($selqry4);
							$num_rows1 = tep_db_num_rows($selres4);
							if ($num_rows1 > 0) {

								while ($row4 = array_shift($selres4)) {
									$vendor_pay += $row4["estimated_cost"];
								}
							}

							$inv_amount = $dt_view_row["F"];

							$invoice_amt = 0;
							db();
							$selqry6 = "SELECT * FROM loop_invoice_items WHERE trans_rec_id = " . $dt_view_row["id"] . " ORDER BY id ASC";
							$selres6 = db_query($selqry6);
							while ($inv_row = array_shift($selres6)) {
								$invoice_amt += $inv_row["quantity"] * $inv_row["price"];
							}
							if ($invoice_amt == 0) {
								$invoice_amt = $inv_amount;
							}
							if ($inv_amount == 0 && $invoice_amt > 0) {
								$inv_amount = $invoice_amt;
							}


							$profit_val = number_format(floatval(str_replace(",", "", strval($inv_amount))) - floatval(str_replace(",", "", strval($vendor_pay))), 2);
							$profit_val = str_replace(",", "", $profit_val);
							$inv_amount = str_replace(",", "", $inv_amount);

							$profit_val_per = "";
							if (floatval($inv_amount) > 0) {
								$profit_val_per = abs((floatval(str_replace(",", "", $profit_val)) * 100) / floatval(str_replace(",", "", $inv_amount)));
							}

							if ($profit_val_per != "") {
								if ($profit_val_per >= 30) {
									if ($profit_val < 0) {
										$profit_val_per = "<font color='#008000'>-" . number_format($profit_val_per, 2) . "%</font>";
									} else {
										$profit_val_per = "<font color='#008000'>" . number_format($profit_val_per, 2) . "%</font>";
									}
								} else if ($profit_val_per < 30) {
									if ($profit_val < 0) {
										$profit_val_per = "<font color='#EE3838'>-" . number_format($profit_val_per, 2) . "%</font>";
									} else {
										$profit_val_per = "<font color='#EE3838'>" . number_format($profit_val_per, 2) . "%</font>";
									}
								}
							}

							//$nickname = "";
							$nickname = getnickname($dt_view_row["warehouse_name"], $dt_view_row["b2bid"]);

							$org_delivery_dt = "";
							$po_delivery_dt = "";
							$actualDel_dt = "";
							if ($dt_view_row["po_delivery_dt"] != "") {
								$h_qry = "select planned_delivery_dt from planned_delivery_date_history where trans_id=" . $dt_view_row["id"] . " order by id ASC limit 1";
								db();
								$h_res = db_query($h_qry);
								$cnt_rw1 = tep_db_num_rows($h_res);
								if ($cnt_rw1 > 0) {
									while ($row1 = array_shift($h_res)) {
										$org_delivery_dt = date('m/d/Y', strtotime($row1["planned_delivery_dt"]));
									}
								} else {
									$org_delivery_dt = date('m/d/Y', strtotime($dt_view_row['po_delivery_dt']));
								}

								$po_delivery_dt = date('m/d/Y', strtotime($dt_view_row["po_delivery_dt"]));
							}

							$sql_act = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $dt_view_row["id"];
							$sql_res = db_query($sql_act);
							$cnt_rw1 = tep_db_num_rows($sql_res);
							if ($cnt_rw1 > 0) {
								while ($row = array_shift($sql_res)) {
									if ($row["bol_shipment_received_date"] != "") {
										$actualDel_dt = date('m/d/Y', strtotime($row["bol_shipment_received_date"]));
									}
								}
							}

							if (!is_null($payment_val)) {

								$lisofdetails15 .= "<tr><td bgColor='#E4EAEB'>" . $initials . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $supplier_name . "</td><td bgColor='#E4EAEB'>" . $bdescription . "</td>
								<td bgColor='#E4EAEB'>" . $org_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actualDel_dt . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($payment_val, 0) . "</td><td bgColor='#E4EAEB'>" . $profit_val_per . "</td></tr>";
							}
						}
					}

					// for col 16 (Completed Double Checks for Payroll) 
					$dt_view_qry = "SELECT po_file, ops_delivery_date, po_employee, loop_warehouse.warehouse_name, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
					$dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
					$dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
					if ($sel_cdeal == "y") {
						$dt_view_qry .= " INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id  
					WHERE loop_bol_files.bol_shipment_received = 0 and loop_transaction_buyer.double_checked = 1 $emp_filter  $pallet_str and loop_transaction_buyer.shipped = 1 AND inv_entered = 1 AND commission_paid = 0 ";
						$dt_view_qry .= " and loop_transaction_buyer.po_delivery_dt between '" . $date_from . "' and '" . $date_to . "' and proof_of_delivery = '' ";
						$dt_view_qry .= " AND loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
					} else {

						$dt_view_qry .= "WHERE loop_transaction_buyer.double_checked = 1 $emp_filter  $pallet_str and loop_transaction_buyer.shipped = 1 AND inv_entered = 1 AND commission_paid = 0 AND loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
					}

					$dt_view_res = db_query($dt_view_qry);
					$amt16 = 0;
					$tot_trans = 0;
					$tot1 = 0;
					$tot2 = 0;
					$tot3 = 0;
					$tot4 = 0;
					$tot13 = 0;
					$col2detail = 0;
					$col3detail = 0;
					$col5detail = 0;
					$tot6 = 0;
					$col6detail = 0;
					$col11detail = 0;
					$col4detail = 0;
					$col13detail = 0;
					$tot11 = 0;
					$tot14 = 0;
					$col14detail = 0;
					$tot15 = 0;
					$col15detail = 0;
					$tot16 = 0;
					$tot7 = 0;
					$col7detail = 0;
					$tot8 = 0;
					$tot5 = 0;
					$tot10 = 0;
					$col10detail = 0;
					$col8detail = 0;
					$tot9 = 0;
					$col9detail = 0;
					$col16detail = 0;
					$tot12 = 0;
					$col12detail = 0;

					$lisofdetails16 = "";

					while ($dt_view_row = array_shift($dt_view_res)) {
						$add_in_array = "yes";

						$payment_val = 0;
						$invoice_paid = 0;
						$payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A FROM loop_buyer_payments WHERE trans_rec_id = " . $dt_view_row["id"];
						$payment_qry = db_query($payments_sql);
						while ($payment = array_shift($payment_qry)) {
							$payment_val = $payment["A"];
						}
						$payment1 = number_format($dt_view_row["F"], 2);
						$payment2 = number_format($payment_val, 2);
						$payment1 = str_replace(",", "", $payment1);
						$payment2 = str_replace(",", "", $payment2);
						if ($payment1 == $payment2 && $payment1 > 0) {
							$invoice_paid = 1;
						}
						if ($dt_view_row["no_invoice"] == 1) {
							$invoice_paid = 1;
						}

						if ($invoice_paid == 1) {
							$add_in_array = "yes";
						} else {
							$add_in_array = "no";
						}


						if ($add_in_array == "yes") {
							if ($_REQUEST['protype'] == 2) {
								$freight_cost = $dt_view_row["po_freight"];
								$po_poorderamount = $dt_view_row["SUMPO"];
								$salesorder_qty = 0;
								$payment_val = 0;

								$get_sales_order = db_query("Select loop_salesorders.qty, loop_boxes.b2b_id, loop_boxes.boxgoodvalue from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
								while ($dtt_view_res = array_shift($get_sales_order)) {
									$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
									$boxgoodvalue = $dtt_view_res["boxgoodvalue"];
									$boxgoodvaluearray	= explode(".", $boxgoodvalue);
									$boxgoodvalueDollar = $boxgoodvaluearray[0];
									$boxgoodvalueCents	= "0." . $boxgoodvaluearray[1];


									db_b2b();

									$get_box_data = db_query("Select ulineDollar, ulineCents, costDollar, costCents, overhead_costDollar, overhead_costCents  from inventory where ID = " .  $dtt_view_res["b2b_id"]);
									while ($box_data_res = array_shift($get_box_data)) {

										$b2b_ovh_costDollar = round($box_data_res["overhead_costDollar"]);
										$b2b_ovh_costCents = $box_data_res["overhead_costCents"];

										$b2b_costDollar = floatval($boxgoodvalueDollar) + $b2b_ovh_costDollar;
										$b2b_costCents = $boxgoodvalueCents + $b2b_ovh_costCents;

										$b2b_cost = $b2b_costDollar + $b2b_costCents;
									}
								}
								db();
								$sales_qry = db_query("Select qty from loop_salesorders_manual WHERE trans_rec_id = " .  $dt_view_row["id"]);
								while ($dtt_view_res = array_shift($sales_qry)) {
									$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
								}

								$cogs_val = $b2b_cost * $salesorder_qty;
								$cogs = (-$freight_cost) - $cogs_val;

								$gross_profit = $po_poorderamount + $cogs;

								$payment_val = $gross_profit;
								$amt16 = $amt16 + $gross_profit;
							} else {

								$amt16 = $amt16 + $payment_val;
							}

							$tot_trans = $tot_trans + 1;
							$bdescription = "";
							$supplier_name = "";
							db();
							$selqry1 = db_query("Select loop_salesorders.box_id, loop_boxes.bdescription, loop_boxes.b2b_id from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
							while ($selres1 = array_shift($selqry1)) {
								$bdescription .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $selres1['box_id'] . "&proc=View'>" . $selres1["bdescription"] . "</a><br>";
								db_b2b();
								$selqry2 = db_query("Select vendor_b2b_rescue from inventory where ID = " . $selres1["b2b_id"]);
								$selres2 = array_shift($selqry2);
								$vendor_b2b_rescue = $selres2["vendor_b2b_rescue"];

								$sname = "";
								db();
								$selqry3 = db_query("SELECT id, company_name, b2bid FROM loop_warehouse where id=" . $vendor_b2b_rescue);
								$selres3 = array_shift($selqry3);
								if (!empty($selres3)) {
									$sname = getnickname($selres3['company_name'], $selres3["b2bid"]);
									$supplier_name .= "<a target='_blank' href='viewCompany.php?ID=" . $selres3["b2bid"] . "'>" . $sname . " (Loop ID: " . $selres3["id"] . " B2B ID:" . $selres3["b2bid"] . ")</a><br>";
								}
							}
							$bdescription = substr($bdescription, 0, strlen($bdescription) - 4);
							$supplier_name = substr($supplier_name, 0, strlen($supplier_name) - 4);
							$vendor_pay = 0;
							$inv_amount = 0;
							db();
							$selqry4 = "SELECT loop_transaction_buyer_payments.estimated_cost from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["id"];
							$selres4 = db_query($selqry4);
							$num_rows1 = tep_db_num_rows($selres4);
							if ($num_rows1 > 0) {

								while ($row4 = array_shift($selres4)) {
									$vendor_pay += $row4["estimated_cost"];
								}
							}

							$inv_amount = $dt_view_row["F"];

							$invoice_amt = 0;
							db();
							$selqry6 = "SELECT * FROM loop_invoice_items WHERE trans_rec_id = " . $dt_view_row["id"] . " ORDER BY id ASC";
							$selres6 = db_query($selqry6);
							while ($inv_row = array_shift($selres6)) {
								$invoice_amt += $inv_row["quantity"] * $inv_row["price"];
							}
							if ($invoice_amt == 0) {
								$invoice_amt = $inv_amount;
							}
							if ($inv_amount == 0 && $invoice_amt > 0) {
								$inv_amount = $invoice_amt;
							}


							$profit_val = number_format(floatval(str_replace(",", "", strval($inv_amount))) - floatval(str_replace(",", "", strval($vendor_pay))), 2);
							$profit_val = str_replace(",", "", $profit_val);
							$inv_amount = str_replace(",", "", $inv_amount);

							$profit_val_per = "";
							if (floatval($inv_amount) > 0) {
								$profit_val_per = abs((floatval(str_replace(",", "", $profit_val)) * 100) / floatval(str_replace(",", "", $inv_amount)));
							}

							if ($profit_val_per != "") {
								if ($profit_val_per >= 30) {
									if ($profit_val < 0) {
										$profit_val_per = "<font color='#008000'>-" . number_format($profit_val_per, 2) . "%</font>";
									} else {
										$profit_val_per = "<font color='#008000'>" . number_format($profit_val_per, 2) . "%</font>";
									}
								} else if ($profit_val_per < 30) {
									if ($profit_val < 0) {
										$profit_val_per = "<font color='#EE3838'>-" . number_format($profit_val_per, 2) . "%</font>";
									} else {
										$profit_val_per = "<font color='#EE3838'>" . number_format($profit_val_per, 2) . "%</font>";
									}
								}
							}

							//$nickname = "";
							$nickname = getnickname($dt_view_row["warehouse_name"], $dt_view_row["b2bid"]);

							$org_delivery_dt = "";
							$po_delivery_dt = "";
							$actualDel_dt = "";
							if ($dt_view_row["po_delivery_dt"] != "") {
								db();
								$h_qry = "select planned_delivery_dt from planned_delivery_date_history where trans_id=" . $dt_view_row["id"] . " order by id ASC limit 1";
								$h_res = db_query($h_qry);
								$cnt_rw1 = tep_db_num_rows($h_res);
								if ($cnt_rw1 > 0) {
									while ($row1 = array_shift($h_res)) {
										$org_delivery_dt = date('m/d/Y', strtotime($row1["planned_delivery_dt"]));
									}
								} else {
									$org_delivery_dt = date('m/d/Y', strtotime($dt_view_row['po_delivery_dt']));
								}

								$po_delivery_dt = date('m/d/Y', strtotime($dt_view_row["po_delivery_dt"]));
							}

							$sql_act = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $dt_view_row["id"];
							$sql_res = db_query($sql_act);
							$cnt_rw1 = tep_db_num_rows($sql_res);
							if ($cnt_rw1 > 0) {
								while ($row = array_shift($sql_res)) {
									if ($row["bol_shipment_received_date"] != "") {
										$actualDel_dt = date('m/d/Y', strtotime($row["bol_shipment_received_date"]));
									}
								}
							}

							$lisofdetails16 .= "<tr><td bgColor='#E4EAEB'>" . $initials . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $supplier_name . "</td><td bgColor='#E4EAEB'>" . $bdescription . "</td>
						<td bgColor='#E4EAEB'>" . $org_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actualDel_dt . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($payment_val, 0) . "</td><td bgColor='#E4EAEB'>" . $profit_val_per . "</td></tr>";
						}
					}



					$rowcheck = $amt2 + $amt3 + $amt4 + $amt5 + $amt6 + $amt7 + $amt8 + $amt9 + $amt10 + $amt11 + $amt12 + $amt13 + $amt14 + $amt15 + $amt16;
					// Keep in array
					if ($rowcheck > 0) {
						$DATArray[] = array(
							'name' => $rowemp["name"],
							'col2amt' => $amt2,
							'col2' => $lisofdetails2,
							'col3amt' => $amt3,
							'col3' => $lisofdetails3,
							'col4amt' => $amt4,
							'col4' => $lisofdetails4,
							'col5amt' => $amt5,
							'col5' => $lisofdetails5,
							'col6amt' => $amt6,
							'col6' => $lisofdetails6,
							'col7amt' => $amt7,
							'col7' => $lisofdetails7,
							'col8amt' => $amt8,
							'col8' => $lisofdetails8,
							'col9amt' => $amt9,
							'col9' => $lisofdetails9,
							'col10amt' => $amt10,
							'col10' => $lisofdetails10,
							'col11amt' => $amt11,
							'col11' => $lisofdetails11,
							'col12amt' => $amt12,
							'col12' => $lisofdetails12,
							'col13amt' => $amt13,
							'col13' => $lisofdetails13,
							'col14amt' => $amt14,
							'col14' => $lisofdetails14,
							'col15amt' => $amt15,
							'col15' => $lisofdetails15,
							'col16amt' => $amt16,
							'col16' => $lisofdetails16
						);
					}
				}


				foreach ($DATArray as $myrow) {
				?>
					<tr>
						<td bgColor="#EAF1DD"><?php echo $myrow['name'] ?></td>

						<td bgColor="#EAF1DD" align="right">
							<a href='#' onclick="load_div('closedealcol2<?php echo $unqid; ?>'); return false;">$<?php echo number_format($myrow['col2amt'], 0); ?></font></a>
							<span id='closedealcol2<?php echo $unqid; ?>' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
								<?php echo $span_header_str; ?>
								<?php echo $myrow['col2']; ?>
								<?php if ($myrow['col2amt'] > 0) { ?>
					<tr>
						<td bgColor='#ABC5DF'>&nbsp;</td>
						<td bgColor='#ABC5DF'>&nbsp;</td>
						<td bgColor='#ABC5DF'>&nbsp;</td>
						<td bgColor='#ABC5DF'>&nbsp;</td>
						<td bgColor='#ABC5DF'>&nbsp;</td>
						<td bgColor='#ABC5DF'>&nbsp;</td>
						<td bgColor='#ABC5DF'>&nbsp;</td>
						<td bgColor='#ABC5DF'>&nbsp;</td>
						<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($myrow['col2amt'], 0); ?></td>
						<td bgColor='#ABC5DF'>&nbsp;</td>
					</tr>
				<?php } ?>
				<?php echo $span_bottom_str; ?>
				</span>
				</td>

				<td bgColor="#EAF1DD" align="right">
					<a href='#' onclick="load_div('closedealcol3<?php echo $unqid; ?>'); return false;">$<?php echo number_format($myrow['col3amt'], 0); ?></font></a>
					<span id='closedealcol3<?php echo $unqid; ?>' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
						<?php echo $span_header_str; ?>
						<?php echo $myrow['col3']; ?>
						<?php if ($myrow['col3amt'] > 0) { ?>
							<tr>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($myrow['col3amt'], 0); ?></td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
							</tr>
						<?php } ?>
						<?php echo $span_bottom_str; ?>
					</span>
				</td>

				<td bgColor="#EAF1DD" align="right">
					<a href='#' onclick="load_div('closedealcol4<?php echo $unqid; ?>'); return false;">$<?php echo number_format($myrow['col4amt'], 0); ?></font></a>
					<span id='closedealcol4<?php echo $unqid; ?>' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
						<?php echo $span_header_str; ?>
						<?php echo $myrow['col4']; ?>
						<?php if ($myrow['col4amt'] > 0) { ?>
							<tr>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($myrow['col4amt'], 0); ?></td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
							</tr>
						<?php } ?>
						<?php echo $span_bottom_str; ?>
					</span>
				</td>

				<td bgColor="#EAF1DD" align="right">
					<a href='#' onclick="load_div('closedealcol5<?php echo $unqid; ?>'); return false;">$<?php echo number_format($myrow['col5amt'], 0); ?></font></a>
					<span id='closedealcol5<?php echo $unqid; ?>' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
						<?php echo $span_header_str; ?>
						<?php echo $myrow['col5']; ?>
						<?php if ($myrow['col5amt'] > 0) { ?>
							<tr>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($myrow['col5amt'], 0); ?></td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
							</tr>
						<?php } ?>
						<?php echo $span_bottom_str; ?>
					</span>
				</td>

				<td bgColor="#EAF1DD" align="right">
					<a href='#' onclick="load_div('closedealcol6<?php echo $unqid; ?>'); return false;">$<?php echo number_format($myrow['col6amt'], 0); ?></font></a>
					<span id='closedealcol6<?php echo $unqid; ?>' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
						<?php echo $span_header_str; ?>
						<?php echo $myrow['col6']; ?>
						<?php if ($myrow['col6amt'] > 0) { ?>
							<tr>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($myrow['col6amt'], 0); ?></td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
							</tr>
						<?php } ?>
						<?php echo $span_bottom_str; ?>
					</span>
				</td>

				<td bgColor="#EAF1DD" align="right">
					<a href='#' onclick="load_div('closedealcol7<?php echo $unqid; ?>'); return false;">$<?php echo number_format($myrow['col7amt'], 0); ?></font></a>
					<span id='closedealcol7<?php echo $unqid; ?>' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
						<?php echo $span_header_str; ?>
						<?php echo $myrow['col7']; ?>
						<?php if ($myrow['col7amt'] > 0) { ?>
							<tr>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($myrow['col7amt'], 0); ?></td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
							</tr>
						<?php } ?>
						<?php echo $span_bottom_str; ?>
					</span>
				</td>

				<td bgColor="#EAF1DD" align="right">
					<a href='#' onclick="load_div('closedealcol8<?php echo $unqid; ?>'); return false;">$<?php echo number_format($myrow['col8amt'], 0); ?></font></a>
					<span id='closedealcol8<?php echo $unqid; ?>' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
						<?php echo $span_header_str; ?>
						<?php echo $myrow['col8']; ?>
						<?php if ($myrow['col8amt'] > 0) { ?>
							<tr>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($myrow['col8amt'], 0); ?></td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
							</tr>
						<?php } ?>
						<?php echo $span_bottom_str; ?>
					</span>
				</td>

				<td bgColor="#EAF1DD" align="right">
					<a href='#' onclick="load_div('closedealcol9<?php echo $unqid; ?>'); return false;">$<?php echo number_format($myrow['col9amt'], 0); ?></font></a>
					<span id='closedealcol9<?php echo $unqid; ?>' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
						<?php echo $span_header_str; ?>
						<?php echo $myrow['col9']; ?>
						<?php if ($myrow['col9amt'] > 0) { ?>
							<tr>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($myrow['col9amt'], 0); ?></td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
							</tr>
						<?php } ?>
						<?php echo $span_bottom_str; ?>
					</span>
				</td>

				<td bgColor="#EAF1DD" align="right">
					<a href='#' onclick="load_div('closedealcol10<?php echo $unqid; ?>'); return false;">$<?php echo number_format($myrow['col10amt'], 0); ?></font></a>
					<span id='closedealcol10<?php echo $unqid; ?>' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
						<?php echo $span_header_str; ?>
						<?php echo $myrow['col10']; ?>
						<?php if ($myrow['col10amt'] > 0) { ?>
							<tr>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($myrow['col10amt'], 0); ?></td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
							</tr>
						<?php } ?>
						<?php echo $span_bottom_str; ?>
					</span>
				</td>

				<td bgColor="#EAF1DD" align="right">
					<a href='#' onclick="load_div('closedealcol12<?php echo $unqid; ?>'); return false;">$<?php echo number_format($myrow['col12amt'], 0); ?></font></a>
					<span id='closedealcol12<?php echo $unqid; ?>' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
						<?php echo $span_header_str; ?>
						<?php echo $myrow['col12']; ?>
						<?php if ($myrow['col12amt'] > 0) { ?>
							<tr>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($myrow['col12amt'], 0); ?></td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
							</tr>
						<?php } ?>
						<?php echo $span_bottom_str; ?>
					</span>
				</td>

				<td bgColor="#FFF2D0" align="right">
					<a href='#' onclick="load_div('closedealcol13<?php echo $unqid; ?>'); return false;">$<?php echo number_format($myrow['col13amt'], 0); ?></font></a>
					<span id='closedealcol13<?php echo $unqid; ?>' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
						<?php echo $span_header_str; ?>
						<?php echo $myrow['col13']; ?>
						<?php if ($myrow['col13amt'] > 0) { ?>
							<tr>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($myrow['col13amt'], 0); ?></td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
							</tr>
						<?php } ?>
						<?php echo $span_bottom_str; ?>
					</span>
				</td>

				<td bgColor="#FFF2D0" align="right">
					<a href='#' onclick="load_div('closedealcol14<?php echo $unqid; ?>'); return false;">$<?php echo number_format($myrow['col14amt'], 0); ?></font></a>
					<span id='closedealcol14<?php echo $unqid; ?>' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
						<?php echo $span_header_str; ?>
						<?php echo $myrow['col14']; ?>
						<?php if ($myrow['col14amt'] > 0) { ?>
							<tr>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($myrow['col14amt'], 0); ?></td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
							</tr>
						<?php } ?>
						<?php echo $span_bottom_str; ?>
					</span>
				</td>

				<td bgColor="#FFF2D0" align="right">
					<a href='#' onclick="load_div('closedealcol15<?php echo $unqid; ?>'); return false;">$<?php echo number_format($myrow['col15amt'], 0); ?></font></a>
					<span id='closedealcol15<?php echo $unqid; ?>' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
						<?php echo $span_header_str; ?>
						<?php echo $myrow['col15']; ?>
						<?php if ($myrow['col15amt'] > 0) { ?>
							<tr>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($myrow['col15amt'], 0); ?></td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
							</tr>
						<?php } ?>
						<?php echo $span_bottom_str; ?>
					</span>
				</td>

				<td bgColor="#FFF2D0" align="right">
					<a href='#' onclick="load_div('closedealdivn<?php echo $unqid; ?>'); return false;">$<?php echo number_format($myrow['col16amt'], 0); ?></font></a>
					<span id='closedealdivn<?php echo $unqid; ?>' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
						<?php echo $span_header_str; ?>
						<?php echo $myrow['col16']; ?>
						<?php if ($myrow['col16amt'] > 0) { ?>
							<tr>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
								<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($myrow['col16amt'], 0); ?></td>
								<td bgColor='#ABC5DF'>&nbsp;</td>
							</tr>
						<?php } ?>
						<?php echo $span_bottom_str; ?>
					</span>
				</td>
				<tr>
				<?php


					$tot2 += $myrow['col2amt'];
					$col2detail .= $myrow['col2'];
					$tot3 += $myrow['col3amt'];
					$col3detail .= $myrow['col3'];
					$tot4 += $myrow['col4amt'];
					$col4detail .= $myrow['col4'];
					$tot5 += $myrow['col5amt'];
					$col5detail .= $myrow['col5'];
					$tot6 += $myrow['col6amt'];
					$col6detail .= $myrow['col6'];
					$tot7 += $myrow['col7amt'];
					$col7detail .= $myrow['col7'];
					$tot8 += $myrow['col8amt'];
					$col8detail .= $myrow['col8'];
					$tot9 += $myrow['col9amt'];
					$col9detail .= $myrow['col9'];
					$tot10 += $myrow['col10amt'];
					$col10detail .= $myrow['col10'];
					$tot11 += $myrow['col11amt'];
					$col11detail .= $myrow['col11'];
					$tot12 += $myrow['col12amt'];
					$col12detail .= $myrow['col12'];
					$tot13 += $myrow['col13amt'];
					$col13detail .= $myrow['col13'];
					$tot14 += $myrow['col14amt'];
					$col14detail .= $myrow['col14'];
					$tot15 += $myrow['col15amt'];
					$col15detail .= $myrow['col15'];
					$tot16 += $myrow['col16amt'];
					$col16detail .= $myrow['col16'];
					$unqid++;
				}
				?>

				<tr>
					<td bgColor="#EAF1DD" align="right"><strong>Total</strong></td>

					<td bgColor="#EAF1DD" align="right">
						<a href='#' onclick="load_div('total2n'); return false;"><strong>$<?php echo number_format($tot2, 0); ?></strong></font></a>
						<span id='total2n' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
							<?php echo $span_header_str; ?>
							<?php echo $col2detail; ?>
							<?php if ($tot2 > 0) { ?>
				<tr>
					<td bgColor='#ABC5DF'></td>
					<td bgColor='#ABC5DF'></td>
					<td bgColor='#ABC5DF'></td>
					<td bgColor='#ABC5DF'></td>
					<td bgColor='#ABC5DF'></td>
					<td bgColor='#ABC5DF'></td>
					<td bgColor='#ABC5DF'></td>
					<td bgColor='#ABC5DF'></td>
					<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($tot2, 0); ?></td>
					<td bgColor='#ABC5DF'></td>
				</tr>
			<?php } ?>
			<?php echo $span_bottom_str; ?>
			</span>
			</td>

			<td bgColor="#EAF1DD" align="right">
				<a href='#' onclick="load_div('total3n'); return false;"><strong>$<?php echo number_format($tot3, 0); ?></strong></font></a>
				<span id='total3n' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
					<?php echo $span_header_str; ?>
					<?php echo $col3detail; ?>
					<?php if ($tot3 > 0) { ?>
						<tr>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($tot3, 0); ?></td>
							<td bgColor='#ABC5DF'></td>
						</tr>
					<?php } ?>
					<?php echo $span_bottom_str; ?>
				</span>
			</td>

			<td bgColor="#EAF1DD" align="right">
				<a href='#' onclick="load_div('total4n'); return false;"><strong>$<?php echo number_format($tot4, 0); ?></strong></font></a>
				<span id='total4n' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
					<?php echo $span_header_str; ?>
					<?php echo $col4detail; ?>
					<?php if ($tot4 > 0) { ?>
						<tr>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($tot4, 0); ?></td>
							<td bgColor='#ABC5DF'></td>
						</tr>
					<?php } ?>
					<?php echo $span_bottom_str; ?>
				</span>
			</td>

			<td bgColor="#EAF1DD" align="right">
				<a href='#' onclick="load_div('total5n'); return false;"><strong>$<?php echo number_format($tot5, 0); ?></strong></font></a>
				<span id='total5n' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
					<?php echo $span_header_str; ?>
					<?php echo $col5detail; ?>
					<?php if ($tot5 > 0) { ?>
						<tr>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($tot5, 0); ?></td>
							<td bgColor='#ABC5DF'></td>
						</tr>
					<?php } ?>
					<?php echo $span_bottom_str; ?>
				</span>
			</td>

			<td bgColor="#EAF1DD" align="right">
				<a href='#' onclick="load_div('total6n'); return false;"><strong>$<?php echo number_format($tot6, 0); ?></strong></font></a>
				<span id='total6n' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
					<?php echo $span_header_str; ?>
					<?php echo $col6detail; ?>
					<?php if ($tot6 > 0) { ?>
						<tr>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($tot6, 0); ?></td>
							<td bgColor='#ABC5DF'></td>
						</tr>
					<?php } ?>
					<?php echo $span_bottom_str; ?>
				</span>
			</td>

			<td bgColor="#EAF1DD" align="right">
				<a href='#' onclick="load_div('total7n'); return false;"><strong>$<?php echo number_format($tot7, 0); ?></strong></font></a>
				<span id='total7n' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
					<?php echo $span_header_str; ?>
					<?php echo $col7detail; ?>
					<?php if ($tot7 > 0) { ?>
						<tr>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($tot7, 0); ?></td>
							<td bgColor='#ABC5DF'></td>
						</tr>
					<?php } ?>
					<?php echo $span_bottom_str; ?>
				</span>
			</td>

			<td bgColor="#EAF1DD" align="right">
				<a href='#' onclick="load_div('total8n'); return false;"><strong>$<?php echo number_format($tot8, 0); ?></strong></font></a>
				<span id='total8n' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
					<?php echo $span_header_str; ?>
					<?php echo $col8detail; ?>
					<?php if ($tot8 > 0) { ?>
						<tr>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($tot8, 0); ?></td>
							<td bgColor='#ABC5DF'></td>
						</tr>
					<?php } ?>
					<?php echo $span_bottom_str; ?>
				</span>
			</td>

			<td bgColor="#EAF1DD" align="right">
				<a href='#' onclick="load_div('total9n'); return false;"><strong>$<?php echo number_format($tot9, 0); ?></strong></font></a>
				<span id='total9n' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
					<?php echo $span_header_str; ?>
					<?php echo $col9detail; ?>
					<?php if ($tot9 > 0) { ?>
						<tr>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($tot9, 0); ?></td>
							<td bgColor='#ABC5DF'></td>
						</tr>
					<?php } ?>
					<?php echo $span_bottom_str; ?>
				</span>
			</td>

			<td bgColor="#EAF1DD" align="right">
				<a href='#' onclick="load_div('total10n'); return false;"><strong>$<?php echo number_format($tot10, 0); ?></strong></font></a>
				<span id='total10n' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
					<?php echo $span_header_str; ?>
					<?php echo $col10detail; ?>
					<?php if ($tot10 > 0) { ?>
						<tr>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($tot10, 0); ?></td>
							<td bgColor='#ABC5DF'></td>
						</tr>
					<?php } ?>
					<?php echo $span_bottom_str; ?>
				</span>
			</td>

			<td bgColor="#EAF1DD" align="right">
				<a href='#' onclick="load_div('total12n'); return false;"><strong>$<?php echo number_format($tot12, 0); ?></strong></font></a>
				<span id='total12n' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
					<?php echo $span_header_str; ?>
					<?php echo $col12detail; ?>
					<?php if ($tot12 > 0) { ?>
						<tr>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($tot12, 0); ?></td>
							<td bgColor='#ABC5DF'></td>
						</tr>
					<?php } ?>
					<?php echo $span_bottom_str; ?>
				</span>
			</td>

			<td bgColor="#EAF1DD" align="right">
				<a href='#' onclick="load_div('total13n'); return false;"><strong>$<?php echo number_format($tot13, 0); ?></strong></font></a>
				<span id='total13n' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
					<?php echo $span_header_str; ?>
					<?php echo $col13detail; ?>
					<?php if ($tot13 > 0) { ?>
						<tr>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($tot13, 0); ?></td>
							<td bgColor='#ABC5DF'></td>
						</tr>
					<?php } ?>
					<?php echo $span_bottom_str; ?>
				</span>
			</td>

			<td bgColor="#EAF1DD" align="right">
				<a href='#' onclick="load_div('total14n'); return false;"><strong>$<?php echo number_format($tot14, 0); ?></strong></font></a>
				<span id='total14n' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
					<?php echo $span_header_str; ?>
					<?php echo $col14detail; ?>
					<?php if ($tot14 > 0) { ?>
						<tr>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($tot14, 0); ?></td>
							<td bgColor='#ABC5DF'></td>
						</tr>
					<?php } ?>
					<?php echo $span_bottom_str; ?>
				</span>
			</td>

			<td bgColor="#EAF1DD" align="right">
				<a href='#' onclick="load_div('total15n'); return false;"><strong>$<?php echo number_format($tot15, 0); ?></strong></font></a>
				<span id='total15n' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
					<?php echo $span_header_str; ?>
					<?php echo $col15detail; ?>
					<?php if ($tot15 > 0) { ?>
						<tr>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($tot15, 0); ?></td>
							<td bgColor='#ABC5DF'></td>
						</tr>
					<?php } ?>
					<?php echo $span_bottom_str; ?>
				</span>
			</td>

			<td bgColor="#EAF1DD" align="right">
				<a href='#' onclick="load_div('total16n'); return false;"><strong>$<?php echo number_format($tot16, 0); ?></strong></font></a>
				<span id='total16n' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
					<?php echo $span_header_str; ?>
					<?php echo $col16detail; ?>
					<?php if ($tot16 > 0) { ?>
						<tr>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF'></td>
							<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($tot16, 0); ?></td>
							<td bgColor='#ABC5DF'></td>
						</tr>
					<?php } ?>
					<?php echo $span_bottom_str; ?>
				</span>
			</td>
			<tr>
				<?php
				$summary_pipeline = $tot1 + $tot2 + $tot3 + $tot4 + $tot5 + $tot6 + $tot7 + $tot8 + $tot9 + $tot10 + $tot12;
				$summary_ar_commissions = $tot13 + $tot14 + $tot15 + $tot16;
				$grand_total = $summary_pipeline + $summary_ar_commissions;

				?>
			</table>
			<br>

			<?php if ($sel_cdeal != "y") { ?>
				<table width="400" cellspacing="1" cellpadding="1" border="0">
					<tr align="center" bgcolor="#ABC5DF">
						<td colspan="2"><strong>Summary</strong></td>
					</tr>
					<tr bgcolor="#eeeeee">
						<td>Pipeline Total (Pre-Invoicing)</td>
						<td align="right">$<?php echo number_format($summary_pipeline, 0); ?></td>
					</tr>
					<tr bgcolor="#eeeeee">
						<td>A/R & Commissions Totals</td>
						<td align="right">$<?php echo number_format($summary_ar_commissions, 0); ?></td>
					</tr>
					<tr bgcolor="#e4e4e4">
						<td>Grand Total</td>
						<td align="right">$<?php echo number_format($grand_total, 0); ?></td>
					</tr>
				</table>
			<?php
			}


			if ($sel_cdeal == "y") {
				$summary_quota = $summary_revenue = $summary_fulfillment_issues = $remainder = 0;
				$quota3 = $summary_profit_val = 0;

				$date_to2 = $_REQUEST["date_to"];

				$st_date = $date_from;
				$end_date = $date_to;
				$end_date2 = $date_to2;

				$begin = new DateTime($st_date);
				$end   = new DateTime($end_date2);

				for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
					$start_Dt_tmp = $datecnt->format("Y-m-d");

					$tbl_nm = "";
					if ($_REQUEST['protype'] == 2) {
						if (isset($_REQUEST["pallet_flg"]) && $_REQUEST["pallet_flg"] == "yes") {
							$newsel = "Select quota_month, quota, deal_quota from employee_quota_overall_pallet_gprofit where
							quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
						} else {
							$newsel = "Select quota_month, quota, deal_quota from employee_quota_overall_sales_gp where 
							quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
						}
					} else {
						if (isset($_REQUEST["pallet_flg"]) && $_REQUEST["pallet_flg"] == "yes") {
							$newsel = "Select quota_month, quota, deal_quota from employee_quota_overall_pallet_sale where
							quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
						} else {
							$newsel = "Select quota_month, quota, deal_quota from employee_quota_overall where b2borb2c = 'b2b' 
							and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
						}
					}
					db();
					$result_empq = db_query($newsel);
					while ($rowemp_empq = array_shift($result_empq)) {
						$quota_one_day = $rowemp_empq["quota"] / date('t', strtotime($start_Dt_tmp));

						$quota3 = $quota3 + $quota_one_day;
					}

					if ($_REQUEST['protype'] == 2) {
						db();
						$goalsql = db_query("Select quota_month, quota, deal_quota from employee_quota_overall_stretch_gprofit where 
						quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m"));
					} else {
						db();
						$goalsql = db_query("Select quota_month, quota, deal_quota from employee_quota_overall_stretch_g where 
						quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m"));
					}
					while ($stquota = array_shift($goalsql)) {
						$quota_per_day = $stquota["quota"] / date('t', strtotime($start_Dt_tmp));
						$summary_stretch = $summary_stretch + $quota_per_day;
					}
				}

				$summary_quota += $quota3;

				$sqlmtd = "SELECT loop_transaction_buyer.inv_number, po_delivery_dt, fulfillment_issue, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id,
				loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight,loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer 
				left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse
				on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 $pallet_str AND 
				loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp 
				else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $st_date . "' AND '" . date('Y-m-d', strtotime($_REQUEST["date_to"])) . " 23:59:59'";


				$lisofdetails1_fulfillment_issue = "";
				$resultmtd = db_query($sqlmtd);
				while ($summtd = array_shift($resultmtd)) {

					if ($_REQUEST['protype'] == 2) {
						$finalpaid_amt = 0;
						$inv_amt_totake = 0;
						if ($finalpaid_amt == 0 && floatval($summtd["invsent_amt"]) > 0) {
							$inv_amt_totake = str_replace(",", "", $summtd["invsent_amt"]);
						}

						if ($finalpaid_amt == 0 && (int)$summtd["invsent_amt"] == 0 && floatval($summtd["inv_amount"]) > 0) {
							$inv_amt_totake = str_replace(",", "", $summtd["inv_amount"]);
						}

						$estimated_cost = 0;
						db();
						$qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
						FROM loop_transaction_buyer 
						LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
						INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
						WHERE loop_transaction_buyer.Leaderboard = 'B2B' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
						and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
						$resB2bCogs = db_query($qryB2bCogs);

						while ($resB2bCogs_row = array_shift($resB2bCogs)) {
							$estimated_cost = str_replace(",", "", $resB2bCogs_row['estimated_cost']);
						}

						$profit_val = ($inv_amt_totake - $estimated_cost);

						$summary_revenue = $summary_revenue + str_replace(",", "", number_format($profit_val, 0));
					} else {
						$finalpaid_amt = 0;
						$inv_amt_totake = 0;
						if ($finalpaid_amt == 0 && floatval($summtd["invsent_amt"]) > 0) {
							$inv_amt_totake = str_replace(",", "", $summtd["invsent_amt"]);
						}

						if ($finalpaid_amt == 0 && floatval($summtd["invsent_amt"]) == 0 && floatval($summtd["inv_amount"]) > 0) {
							$inv_amt_totake = str_replace(",", "", $summtd["inv_amount"]);
						}

						$summary_revenue = $summary_revenue + str_replace(",", "", number_format($inv_amt_totake, 0));
					}
				}
				//echo "At Line 4024 summary_revenue".$summary_revenue;

				$remainder = floatval(str_replace(",", "", number_format($summary_quota, 0))) - floatval(str_replace(",", "", number_format($summary_revenue, 0))) - floatval(str_replace(",", "", number_format($summary_pipeline, 0)));

				$remainder_stretch = floatval(str_replace(",", "", number_format($summary_stretch, 0))) - floatval(str_replace(",", "", number_format($summary_revenue, 0))) - floatval(str_replace(",", "", number_format($summary_pipeline, 0)));

				//For Order fulfillment_issue
				$dt_view_qry = "SELECT loop_warehouse.warehouse_name, po_employee, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
				$dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
				$dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";

				if ($sel_cdeal == "y") {
					$dt_view_qry .= "WHERE Leaderboard = 'B2B' and fulfillment_issue = 1 ";
					$dt_view_qry .= " and loop_transaction_buyer.po_delivery_dt between '" . $date_from . "' and '" . $date_to . "' and proof_of_delivery = ''";
					$dt_view_qry .= " and loop_transaction_buyer.ignore = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
				} else {

					$dt_view_qry .= "WHERE fulfillment_issue = 1 $pallet_str and loop_transaction_buyer.ignore = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
				}
				$dt_view_res = db_query($dt_view_qry);
				while ($dt_view_row = array_shift($dt_view_res)) {
					if ($_REQUEST['protype'] == 2) {
						$freight_cost = $dt_view_row["po_freight"];
						$po_poorderamount = $dt_view_row["SUMPO"];
						$salesorder_qty = 0;
						$payment_val = 0;

						$get_sales_order = db_query("Select loop_salesorders.qty, loop_boxes.b2b_id, loop_boxes.boxgoodvalue from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
						while ($dtt_view_res = array_shift($get_sales_order)) {
							$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
							$boxgoodvalue = $dtt_view_res["boxgoodvalue"];
							$boxgoodvaluearray	= explode(".", $boxgoodvalue);
							$boxgoodvalueDollar = $boxgoodvaluearray[0];
							$boxgoodvalueCents	= "0." . $boxgoodvaluearray[1];

							db_b2b();

							$get_box_data = db_query("Select ulineDollar, ulineCents, costDollar, costCents, overhead_costDollar, overhead_costCents  from inventory where ID = " .  $dtt_view_res["b2b_id"]);
							while ($box_data_res = array_shift($get_box_data)) {

								$b2b_ovh_costDollar = round($box_data_res["overhead_costDollar"]);
								$b2b_ovh_costCents = $box_data_res["overhead_costCents"];

								$b2b_costDollar = floatval($boxgoodvalueDollar) + floatval($b2b_ovh_costDollar);
								$b2b_costCents = $boxgoodvalueCents + $b2b_ovh_costCents;

								$b2b_cost = $b2b_costDollar + $b2b_costCents;
							}
						}
						db();
						$sales_qry = db_query("Select qty from loop_salesorders_manual WHERE trans_rec_id = " .  $dt_view_row["id"]);
						while ($dtt_view_res = array_shift($sales_qry)) {
							$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
						}

						$cogs_val = $b2b_cost * $salesorder_qty;
						$cogs = (-$freight_cost) - $cogs_val;

						$gross_profit = $po_poorderamount + $cogs;

						$payment_val = $gross_profit;
						$summary_fulfillment_issues = $summary_fulfillment_issues + $gross_profit;
					} else {
						$payment_val = $dt_view_row["SUMPO"];
						$summary_fulfillment_issues = $summary_fulfillment_issues + $dt_view_row["SUMPO"];
					}

					$bdescription = "";
					$supplier_name = "";
					db();
					$selqry1 = db_query("Select loop_salesorders.box_id, loop_boxes.bdescription, loop_boxes.b2b_id from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["id"]);
					while ($selres1 = array_shift($selqry1)) {
						$bdescription .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $selres1['box_id'] . "&proc=View'>" . $selres1["bdescription"] . "</a><br>";
						db_b2b();
						$selqry2 = db_query("Select vendor_b2b_rescue from inventory where ID = " . $selres1["b2b_id"]);
						$selres2 = array_shift($selqry2);
						$vendor_b2b_rescue = $selres2["vendor_b2b_rescue"];

						$sname = "";
						db();
						$selqry3 = db_query("SELECT id, company_name, b2bid FROM loop_warehouse where id=" . $vendor_b2b_rescue);
						$selres3 = array_shift($selqry3);
						if (!empty($selres3)) {
							$sname = getnickname($selres3['company_name'], $selres3["b2bid"]);
							$supplier_name .= "<a target='_blank' href='viewCompany.php?ID=" . $selres3["b2bid"] . "'>" . $sname . " (Loop ID: " . $selres3["id"] . " B2B ID:" . $selres3["b2bid"] . ")</a><br>";
						}
					}
					$bdescription = substr($bdescription, 0, strlen($bdescription) - 4);
					$supplier_name = substr($supplier_name, 0, strlen($supplier_name) - 4);
					$vendor_pay = 0;
					$inv_amount = 0;
					db();
					$selqry4 = "SELECT loop_transaction_buyer_payments.estimated_cost from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["id"];
					$selres4 = db_query($selqry4);
					$num_rows1 = tep_db_num_rows($selres4);

					if ($num_rows1 > 0) {

						while ($row4 = array_shift($selres4)) {
							$vendor_pay += $row4["estimated_cost"];
						}
					}

					$inv_amount = $dt_view_row["F"];

					$invoice_amt = 0;
					db();
					$selqry6 = "SELECT * FROM loop_invoice_items WHERE trans_rec_id = " . $dt_view_row["id"] . " ORDER BY id ASC";
					$selres6 = db_query($selqry6);
					while ($inv_row = array_shift($selres6)) {
						$invoice_amt += $inv_row["quantity"] * $inv_row["price"];
					}
					if ($invoice_amt == 0) {
						$invoice_amt = $inv_amount;
					}
					if ($inv_amount == 0 && $invoice_amt > 0) {
						$inv_amount = $invoice_amt;
					}

					$profit_val = number_format(floatval(str_replace(",", "", strval($inv_amount))) - floatval(str_replace(",", "", strval($vendor_pay))), 2);
					$profit_val = str_replace(",", "", $profit_val);
					$inv_amount = str_replace(",", "", $inv_amount);

					$profit_val_per = "";
					if (floatval($inv_amount) > 0) {
						$profit_val_per = abs((floatval(str_replace(",", "", $profit_val)) * 100) / floatval(str_replace(",", "", $inv_amount)));
					}

					if ($profit_val_per != "") {
						if ($profit_val_per >= 30) {
							if ($profit_val < 0) {
								$profit_val_per = "<font color='#008000'>-" . number_format($profit_val_per, 2) . "%</font>";
							} else {
								$profit_val_per = "<font color='#008000'>" . number_format($profit_val_per, 2) . "%</font>";
							}
						} else if ($profit_val_per < 30) {
							if ($profit_val < 0) {
								$profit_val_per = "<font color='#EE3838'>-" . number_format($profit_val_per, 2) . "%</font>";
							} else {
								$profit_val_per = "<font color='#EE3838'>" . number_format($profit_val_per, 2) . "%</font>";
							}
						}
					}

					//$nickname = "";
					$nickname = getnickname($dt_view_row["warehouse_name"], $dt_view_row["b2bid"]);

					$org_delivery_dt = "";
					$po_delivery_dt = "";
					$actualDel_dt = "";
					if ($dt_view_row["po_delivery_dt"] != "") {
						$h_qry = "select planned_delivery_dt from planned_delivery_date_history where trans_id=" . $dt_view_row["id"] . " order by id ASC limit 1";
						db();
						$h_res = db_query($h_qry);
						$cnt_rw1 = tep_db_num_rows($h_res);
						if ($cnt_rw1 > 0) {
							while ($row1 = array_shift($h_res)) {
								$org_delivery_dt = date('m/d/Y', strtotime($row1["planned_delivery_dt"]));
							}
						} else {
							$org_delivery_dt = date('m/d/Y', strtotime($dt_view_row['po_delivery_dt']));
						}

						$po_delivery_dt = date('m/d/Y', strtotime($dt_view_row["po_delivery_dt"]));
					}

					$sql_act = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $dt_view_row["id"];
					$sql_res = db_query($sql_act);
					$cnt_rw1 = tep_db_num_rows($sql_res);
					if ($cnt_rw1 > 0) {
						while ($row = array_shift($sql_res)) {
							if ($row["bol_shipment_received_date"] != "") {
								$actualDel_dt = date('m/d/Y', strtotime($row["bol_shipment_received_date"]));
							}
						}
					}

					$lisofdetails1_fulfillment_issue .= "<tr><td bgColor='#E4EAEB'>" . $initials . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $supplier_name . "</td><td bgColor='#E4EAEB'>" . $bdescription . "</td>
					<td bgColor='#E4EAEB'>" . $org_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actualDel_dt . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($payment_val, 0) . "</td><td bgColor='#E4EAEB' align='right'>" . $profit_val_per . "</td></tr>";
				}

				$remainder_wo_issues = floatval(str_replace(",", "", number_format($remainder, 0))) + floatval(str_replace(",", "", number_format($summary_fulfillment_issues, 0)));

				$remainder_wo_issues_stretch = floatval(str_replace(",", "", number_format($remainder_stretch, 0))) + floatval(str_replace(",", "", number_format($summary_fulfillment_issues, 0)));

			?>
				<table width="400" cellspacing="1" cellpadding="1" border="0">
					<tr align="center" bgcolor="#ABC5DF">
						<td colspan="3">
							<strong>Draw Down Summary from <?php echo date("m/d/Y", strtotime($st_date)); ?> to <?php echo date("m/d/Y", strtotime($_REQUEST["date_to"])); ?></strong>
						</td>
					</tr>
					<tr bgcolor="#eeeeee">
						<td>&nbsp;</td>
						<td align="center"><strong>Quota</strong></td>
						<td align="center"><strong>Stretch</strong></td>
					</tr>
					<tr bgcolor="#eeeeee">
						<td>Goal</td>
						<td align="right">$<?php echo number_format($summary_quota, 0); ?></td>
						<td align="right">$<?php echo number_format($summary_stretch, 0); ?></td>
					</tr>
					<tr bgcolor="#eeeeee">
						<td><?php echo (($_REQUEST['protype'] == 2) ? 'Gross Profit' : 'Revenue'); ?></td>
						<td align="right">$<?php echo number_format($summary_revenue, 0); ?></td>
						<td align="right">$<?php echo number_format($summary_revenue, 0); ?></td>
					</tr>
					<tr bgcolor="#eeeeee">
						<td>Pipeline</td>
						<td align="right">$<?php echo number_format($summary_pipeline, 0); ?></td>
						<td align="right">$<?php echo number_format($summary_pipeline, 0); ?></td>
					</tr>
					<tr bgcolor="#e4e4e4">
						<td>Remainder</td><!-- [Quota] - [Revenue] - [Pipeline] -->
						<td align="right">$<?php echo number_format($remainder, 0); ?></td>
						<td align="right">$<?php echo number_format($remainder_stretch, 0); ?></td>
					</tr>

					<tr bgcolor="#eeeeee">
						<td>&nbsp;</td><!-- [Quota] - [Revenue] - [Pipeline] -->
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
					</tr>

					<tr bgcolor="#eeeeee">
						<td>Fulfillment Issues</td>
						<td align="right">
							<a href='#' onclick="load_div('divfulfillment_issues'); return false;"><strong>$<?php echo number_format($summary_fulfillment_issues, 0); ?></strong></font></a>
							<span id='divfulfillment_issues' style='display:none;'><a href='#' onclick="close_div(); return false;">Close</a>
								<?php echo $span_header_str; ?>
								<?php echo $lisofdetails1_fulfillment_issue; ?>
								<?php if ($summary_fulfillment_issues > 0) { ?>
					<tr>
						<td bgColor='#ABC5DF'></td>
						<td bgColor='#ABC5DF'></td>
						<td bgColor='#ABC5DF'></td>
						<td bgColor='#ABC5DF'></td>
						<td bgColor='#ABC5DF'></td>
						<td bgColor='#ABC5DF'></td>
						<td bgColor='#ABC5DF'></td>
						<td bgColor='#ABC5DF'></td>
						<td bgColor='#ABC5DF' align='right'>$<?php echo number_format($summary_fulfillment_issues, 0); ?></td>
						<td bgColor='#ABC5DF'></td>
					</tr>
				<?php } ?>
				<?php echo $span_bottom_str; ?>
				</span>
				</td>
				<td align="right">
					<a href='#' onclick="load_div('divfulfillment_issues'); return false;"><strong>$<?php echo number_format($summary_fulfillment_issues, 0); ?></strong></font></a>
				</td>
				</tr>
				<tr bgcolor="#e4e4e4">
					<td>Remainder w/o Issues</td>
					<td align="right">$<?php echo number_format($remainder_wo_issues, 0); ?></td>
					<td align="right">$<?php echo number_format($remainder_wo_issues_stretch, 0); ?></td>
				</tr>
				</table>
		<?php	}
		}
		?>
	</div>
</body>

</html>