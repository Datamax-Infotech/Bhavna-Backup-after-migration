<?php
/*
Page Name: report_mgmt_sales_assignments.php
Page created By: Amarendra Nath Singh
Page created On: 18-10-2024
Last Modified On: 
Last Modified By: Amarendra Singh
Change History:
Date			By            Description
==================================================================================================================
18-10-2024      Amarendra     This file is created for showing report of sales and source both type company.
==================================================================================================================
*/

session_start();
/*ini_set("display_errors", "1");
error_reporting(E_ALL);*/
require("inc/header_session.php");
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
?>
<!DOCTYPE html>
<html>

<head>
	<title>Sales Account Ownership Summary Report - UCB Sales Review Report</title>
	<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
	<link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
	<link rel="stylesheet" href="sorter/style_rep.css" />
	<style type="text/css">
		.txtstyle_color {
			font-family: arial;
			font-size: 14pt;
			font-weight:bold;
			height: 16px;
			background: #ABC5DF;
		}
		
		.float_left{float: left; }
		.frmContainer{padding: 10px 0;}
		.fontblod{font-weight:700;}
		.px-6{padding:0 15px 0 5px;}
	</style>
	<script type="text/javascript" src="sorter/jquery-latest.js"></script>
	<script type="text/javascript" src="sorter/jquery.tablesorter.js"></script>
</head>

<body>
	<?php include("inc/header.php"); ?>

	<div class="main_data_css">

		<div class="dashboard_heading" style="float: left;">
			<div style="float: left;">
				Sales Account Ownership Summary Report

				<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
					<span class="tooltiptext">This report shows the user all B2B sales records in the system summarized by account owner.</span>
				</div><br>
			</div>
		</div>
		<div style="clear:both;"></div>
		<div class="frmContainer">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
				<div class="float_left fontblod">Account Type: </div>
				<div class="float_left px-6">
				
					<select name="accountType">
						<option value="sales" <?php echo ($_REQUEST["accountType"] == "sales")? " selected" : "";?>>Sales</option>
						<option value="sourcing"<?php echo ($_REQUEST["accountType"] == "sourcing")? " selected" : "";?>>Sourcing</option>
					</select>
				</div>
				<div class="float_left fontblod">Status Type: </div>
				<div class="float_left px-6">
					<select name="statusType">
						<option value="1"<?php echo ($_REQUEST["statusType"] == 1)? " selected" : "";?>>Leads</option>
						<option value="2"<?php echo ($_REQUEST["statusType"] == 2)? " selected" : "";?>>Qualified Leads</option>
						<option value="3" <?php echo ($_REQUEST["statusType"] == 3)? " selected" : "";?>>Customers/Suppliers</option>
						<option value="4"<?php echo ($_REQUEST["statusType"] == 4)? " selected" : "";?>>Other</option>
					</select>
				</div>
				<div class="float_left fontblod">Transaction Type:</div>
				<div class="float_left px-6">
					<select name="transactionType" id="transactionType">
						<option value="All">All</option>
						<option value='1' <?php echo ($_REQUEST['transactionType'] == '1')? ' selected' : ''; ?>>UsedCardboardBoxes</option>
						<option value='2' <?php echo ($_REQUEST['transactionType'] == '2')? ' selected' : ''; ?>>UCBPalletSolutions</option>
						<option value='3' <?php echo ($_REQUEST['transactionType'] == '3')? ' selected' : ''; ?>>UCBZeroWaste</option>
					</select>	
				</div>
				<div class=""><input type="submit" name="" value="Submit" > </div>
			</form>
		</div>
		
		<?php 
			$accountType = "Sales";
			$statusType = "Leads";
			$companyType = "haveNeed='Need Boxes' AND ";
			$statusList = " AND status IN (3,94,95,96,97,98,99) ";
			$transTypeQry ="";
				if(isset($_REQUEST['transactionType'])){
					if($_REQUEST['transactionType'] == 1){
						$transTypeQry = " AND pallet_flg = 0 AND ucbzw_flg = 0 ";
					}elseif($_REQUEST['transactionType'] == 2){
						$transTypeQry = " AND pallet_flg= 1 ";
					}elseif($_REQUEST['transactionType'] == 3){
						$transTypeQry = " AND ucbzw_flg= 1 ";
					}else{
						$transTypeQry ="";
					}
				}
			if(isset($_REQUEST["accountType"]) && $_REQUEST["accountType"] == "sourcing"){
				$accountType = "Sourcing";
				if(isset($_REQUEST["statusType"]) && $_REQUEST["statusType"] == 2){
					$statusType = "Qualified Leads";
					$bgcolor = "#a4c2f4";
					$sqlMain = "SELECT assignedto, employeeID, name, count(assignedto) FROM companyInfo inner join employees on employees.employeeID = companyInfo.assignedto where haveNeed='Have Boxes' 
					AND companyInfo.status IN (111,70,115,73,116,117,118,119,120) $transTypeQry group by companyInfo.assignedto having count(assignedto) > 0 order by count(assignedto) desc";
					
					$companyType = "haveNeed='Have Boxes' AND ";
					$statusList = " AND status IN (111,70,115,73,116,117,118,119,120) ";
					
					
				}elseif(isset($_REQUEST["statusType"]) && $_REQUEST["statusType"] == 3){
					$statusType = "Suppliers";
					$bgcolor = "#6d9eeb";
					$sqlMain = "SELECT assignedto, employeeID, name, count(assignedto) FROM companyInfo inner join employees on employees.employeeID = companyInfo.assignedto where haveNeed='Have Boxes' 
					AND companyInfo.status IN (55,122,123,124,38,125,126,127,121,33,49,113,114) $transTypeQry group by companyInfo.assignedto having count(assignedto) > 0 order by count(assignedto) desc";
					
					$companyType = "haveNeed='Have Boxes' AND ";
					$statusList = " AND status IN (55,122,123,124,38,125,126,127,121,33,49,113,114) ";
					
					
				}elseif(isset($_REQUEST["statusType"]) && $_REQUEST["statusType"] == 4){
					$statusType = "Other";
					$bgcolor = "#F4CCCC";
					$sqlMain = "SELECT assignedto, employeeID, name, count(assignedto) FROM companyInfo inner join employees on employees.employeeID = companyInfo.assignedto where haveNeed='Have Boxes' 
					AND companyInfo.status IN (31) $transTypeQry group by companyInfo.assignedto having count(assignedto) > 0 order by count(assignedto) desc";
					
					$companyType = "haveNeed='Have Boxes' AND ";
					$statusList = " AND status IN (31) ";
					
				}else{
					$statusType = "Leads";
					$bgcolor = "#c9daf8";
					$sqlMain = "SELECT assignedto, employeeID, name, count(assignedto) FROM companyInfo inner join employees on employees.employeeID = companyInfo.assignedto where haveNeed='Have Boxes' 
					AND companyInfo.status IN (65,107,108,109,44,112,110) $transTypeQry group by companyInfo.assignedto having count(assignedto) > 0 order by count(assignedto) desc";
					
					$companyType = "haveNeed='Have Boxes' AND ";
					$statusList = " AND status IN (65,107,108,109,44,112,110) ";
					
				}
			}else{
				if(isset($_REQUEST["statusType"]) && $_REQUEST["statusType"] == 2){
					$statusType = "Qualified Leads";
					$bgcolor = "#a4c2f4";
					$sqlMain = "SELECT assignedto, employeeID, name, count(assignedto) FROM companyInfo inner join employees on employees.employeeID = companyInfo.assignedto where haveNeed='Need Boxes' AND companyInfo.status IN (100,101,32,50,102,103) $transTypeQry group by companyInfo.assignedto having count(assignedto) > 0 order by count(assignedto) desc";
					
					$companyType = "haveNeed='Need Boxes' AND ";
					$statusList = " AND status IN (100,101,32,50,102,103) ";
					
				}elseif(isset($_REQUEST["statusType"]) && $_REQUEST["statusType"] == 3){
					$statusType = "Customers";
					$bgcolor = "#6d9eeb";
					$sqlMain = "SELECT assignedto, employeeID, name, count(assignedto) FROM companyInfo inner join employees on employees.employeeID = companyInfo.assignedto where haveNeed='Need Boxes' AND companyInfo.status IN (51,56,104,105,33,24,43,106) $transTypeQry group by companyInfo.assignedto having count(assignedto) > 0 order by count(assignedto) desc";
					
					$companyType = "haveNeed='Need Boxes' AND ";
					$statusList = " AND status IN (51,56,104,105,33,24,43,106) ";
					
				}elseif(isset($_REQUEST["statusType"]) && $_REQUEST["statusType"] == 4){
					$statusType = "Other";
					$bgcolor = "#F4CCCC";
					$sqlMain = "SELECT assignedto, employeeID, name, count(assignedto) FROM companyInfo inner join employees on employees.employeeID = companyInfo.assignedto where haveNeed='Need Boxes' AND companyInfo.status IN (52,31) $transTypeQry group by companyInfo.assignedto having count(assignedto) > 0 order by count(assignedto) desc";
					
					$companyType = "haveNeed='Need Boxes' AND ";
					$statusList = " AND status IN (52,31) ";
					
				}else{
					$statusType = "Leads";
					$bgcolor = "#c9daf8";
					$sqlMain = "SELECT assignedto, employeeID, name, count(assignedto) FROM companyInfo inner join employees on employees.employeeID = companyInfo.assignedto 
					where haveNeed='Need Boxes' AND companyInfo.status IN (3,94,95,96,97,98,99) $transTypeQry group by companyInfo.assignedto having count(assignedto) > 0 order by count(assignedto) desc";
					
					$companyType = "haveNeed='Need Boxes' AND ";
					$statusList = " AND status IN (3,94,95,96,97,98,99) ";
					
				}
			}
			
			if($_SERVER['REQUEST_METHOD'] == "POST" || 1==1){
				$tbl_width = "1300px";
				if ($statusType == "Other"){ 
					$tbl_width = "600px";
				}
		?>
		<table border="0">
			<tr>
				<td valign="top">
					<table cellSpacing="1" cellPadding="1" border="0" width="<?php echo $tbl_width;?>">
						<tr>
							<th class="txtstyle_color"><?php echo $accountType;?> Assignments</th>
						</tr>
						<tr>
							<th style="background-color:<?php echo $bgcolor;?>"><?php echo $statusType;?></th>
						</tr>
					</table>
					<table cellSpacing="1" cellPadding="1" border="0" width="<?php echo $tbl_width;?>" id="table15" class="tablesorter">
						<thead>
							<tr>
								<th width="180px" bgColor='#E4EAEB'>Employee</th>
							<?php 
								if($statusType == "Qualified Leads"){ 
									if(isset($_REQUEST["accountType"]) && $_REQUEST["accountType"] == "sourcing"){
										echo '<th width="110px" bgColor="#E4EAEB">Documenting<br>Supply /<br>Volumes</th>';
										echo '<th width="110px" bgColor="#E4EAEB">Collecting<br>Opportunity<br>Data</th>';
										echo '<th width="110px" bgColor="#E4EAEB">Analyzing/<br>Proposing<br>Opportunity</th>';
										echo '<th width="110px" bgColor="#E4EAEB" style="border-left: 1px solid #000;  border-right: 1px solid #000;">Total Active<br>Qualified<br>Leads</th>';
										echo '<th width="30px" style="border-right: 1px solid #000;">&nbsp;</th>';
										echo '<th width="110px" bgColor="#F4CCCC">Back Burner <br>(need <br>more customers) </th>';
										echo '<th width="110px" bgColor="#F4CCCC">Back Burner <br>(need closer warehouse)</th>';
										echo '<th width="110px" bgColor="#F4CCCC">Back Burner <br>(not time to sell)</th>';
										echo '<th width="110px" bgColor="#F4CCCC">Back Burner <br>(not compelling for them)</th>';
										echo '<th width="110px" bgColor="#F4CCCC">Back Burner <br>(not compelling for us)</th>';
										echo '<th width="110px" bgColor="#F4CCCC">Unresponsive /<br>Disinterested,<br>Can\'t Convert</th>';
										echo '<th width="110px" bgColor="#F4CCCC" style="border-left: 1px solid #000;  border-right: 1px solid #000;">Total<br>Unqualified<br>Qualified<br>Leads</th>';
									}else{
										echo '<th width="110px" bgColor="#E4EAEB">Documenting<br>Demand /<br>Appetite</th>';
										echo '<th width="110px" bgColor="#E4EAEB">Collecting<br>Opportunity<br>Data</th>';
										echo '<th width="110px" bgColor="#E4EAEB">Analyzing/<br>Proposing<br>Opportunity</th>';
										echo '<th width="110px" bgColor="#E4EAEB" style="border-left: 1px solid #000;  border-right: 1px solid #000;">Total Active<br>Qualified<br>Leads</th>';
										echo '<th width="30px" style="border-right: 1px solid #000;">&nbsp;</th>';
										echo '<th width="110px" bgColor="#F4CCCC">Back Burner <br>(need to<br>source better<br>boxes) </th>';
										echo '<th width="110px" bgColor="#F4CCCC">Back Burner <br>(not time<br>to buy)</th>';
										echo '<th width="110px" bgColor="#F4CCCC">Unresponsive /<br>Disinterested,<br>Can\'t Convert</th>';
										echo '<th width="110px" bgColor="#F4CCCC" style="border-left: 1px solid #000;  border-right: 1px solid #000;">Total<br>Unqualified<br>Qualified<br>Leads</th>';
									}										
								}elseif($statusType == "Customers" || $statusType == "Suppliers"){ 
									if ($statusType == "Customers"){ 
										echo '<th width="110px" bgColor="#E4EAEB">Active w/<br>Multiple Open<br>Orders</th>';
										echo '<th width="110px" bgColor="#E4EAEB">Active w/ 1 Last<br>Open Order</th>';
										echo '<th width="110px" bgColor="#E4EAEB">Active w/ No<br>Open Orders</th>';
										echo '<th width="110px" bgColor="#E4EAEB" style="border-left: 1px solid #000;  border-right: 1px solid #000;">Total<br>Active Customers</th>';
										echo '<th width="30px" style="border-right: 1px solid #000;">&nbsp;</th>';
										echo '<th width="110px" bgColor="#E4EAEB">Lost (needs<br>resurrection)</th>';
										echo '<th width="110px" bgColor="#E4EAEB">Partner / Broker<br>/ Competitor</th>';
										echo '<th width="110px" bgColor="#F4CCCC">Inactive (out of<br>business)</th>';
										echo '<th width="110px" bgColor="#F4CCCC">Unqualified<br>(1-time purchase<br>or no longer<br>  buys)</th>';
										echo '<th width="110px" bgColor="#F4CCCC">Shit List (don\'t<br>sell to them)</th>';
										echo '<th width="110px" bgColor="#F4CCCC" style="border-left: 1px solid #000;  border-right: 1px solid #000;">Total Unqualified<br>Customers</th>';
									}else{
										echo '<th width="110px" bgColor="#E4EAEB">Contracted (exclusive, swaps)</th>';
										echo '<th width="110px" bgColor="#E4EAEB">Contracted (exclusive, live loads)</th>';
										echo '<th width="110px" bgColor="#E4EAEB">Contracted (first right of refusal)</th>';
										echo '<th width="110px" bgColor="#E4EAEB">Contracted (as available / needed)</th>';
										echo '<th width="110px" bgColor="#E4EAEB">No Contract (exclusive, swaps)</th>';
										echo '<th width="110px" bgColor="#E4EAEB">No Contract (exclusive, live loads)</th>';
										echo '<th width="110px" bgColor="#E4EAEB">No Contract (first right of refusal)</th>';
										echo '<th width="110px" bgColor="#E4EAEB">No Contract (as available / needed)</th>';
										
										echo '<th width="110px" bgColor="#E4EAEB" style="border-left: 1px solid #000;  border-right: 1px solid #000;">Total<br>Active Suppliers</th>';
										echo '<th width="30px" style="border-right: 1px solid #000;">&nbsp;</th>';
										echo '<th width="110px" bgColor="#E4EAEB">Lost (needs<br>resurrection)</th>';
										echo '<th width="110px" bgColor="#E4EAEB">Partner / Broker<br>/ Competitor</th>';
										echo '<th width="110px" bgColor="#F4CCCC">Inactive (out of<br>business)</th>';
										echo '<th width="110px" bgColor="#F4CCCC">Unqualified<br>(1-time purchase<br>or no longer<br>  buys)</th>';
										echo '<th width="110px" bgColor="#F4CCCC">Shit List (don\'t<br>sell to them)</th>';
										echo '<th width="110px" bgColor="#F4CCCC" style="border-left: 1px solid #000;  border-right: 1px solid #000;">Total Unqualified<br>Suppliers</th>';
									}
									
								}elseif($statusType == "Other"){ 
									if(isset($_REQUEST["accountType"]) && $_REQUEST["accountType"] == "sourcing"){
										echo '<th width="310px" bgColor="#E4EAEB">Trash</th>';
									}else{
										echo '<th width="110px" bgColor="#E4EAEB">B2C Relationship</th>';
										echo '<th width="310px" bgColor="#E4EAEB">Trash</th>';
										echo '<th width="110px" bgColor="#E4EAEB" style="border-left: 1px solid #000;  border-right: 1px solid #000;">Total Other</th>';
									}										
								}else{
									echo '<th width="110px" bgColor="#E4EAEB">Need to Find<br>Decision<br>Maker</th>';
									echo '<th width="110px" bgColor="#E4EAEB">Have Decision<br>Maker, No<br>Contact <br>Attempted</th>';
									echo '<th width="110px" bgColor="#E4EAEB">Contact<br>Attempted</th>';
									echo '<th width="110px" bgColor="#E4EAEB">Contact Made,<br>Nurturing</th>';
									echo '<th width="110px" bgColor="#E4EAEB" style="border-left: 1px solid #000;  border-right: 1px solid #000;">Total<br>Active Leads</th>';
									echo '<th width="30px" style="border-right: 1px solid #000;">&nbsp;</th>';
									echo '<th width="110px" bgColor="#F4CCCC">Unqualified<br>(doesn\'t meet<br>minimums)</th>';
									echo '<th width="110px" bgColor="#F4CCCC">Unqualified<br>(doesn\'t buy<br>boxes)</th>';
									echo '<th width="110px" bgColor="#F4CCCC">Unresponsive,<br>Can\'t Qualify</th>';
									echo '<th width="110px" bgColor="#F4CCCC" style="border-left: 1px solid #000; border-right: 1px solid #000;">Total<br>Unqualified Leads</th>';
								}
						?>
								
							</tr>
						</thead>
						<tbody>
						<?php
							
						$grandtot = 0;
						$emp_list = "";
						$col1 = 0; $col2 = 0; $col3 = 0;
						$col4 = 0; $col5 = 0; $col6 = 0;
						$col7 = 0; $col8 = 0; $col9 = 0;
						$col10 = 0; $col11 = 0; $col12 = 0;
						$col13 = 0; $col14 = 0; 

						db_b2b();
						//echo $sqlMain;
						$resulte = db_query($sqlMain);

						while ($rowemp = array_shift($resulte)) {

							$emp_list .= $rowemp["employeeID"] . ",";

							$tmp1 = $tmp2 = $tmp3 = $tmp4 = $tmp5 = $tmp6 = $tmp7 = $tmp8 = $tmp9 = $tmp10 = $tmp11 = 0;
							$tmp12 = $tmp13 = $tmp14 = $tmp15 = $tmp16 = $tmp17 = $tmp18 = $tmp19 = 0;
							$inurl = "report_show_assignments.php?show=status&eid=". $rowemp["employeeID"] ."&statusid=";
							$unassinurl = "report_show_assignments.php?show=status&eid=&statusid=";
							$sql = db_query("SELECT status, count(ID) as cnt FROM companyInfo WHERE ". $companyType." assignedto = '" . $rowemp["employeeID"] . "' ". $statusList . $transTypeQry." GROUP BY `status` ");
							//echo "SELECT status, count(ID) as cnt FROM companyInfo WHERE ". $companyType." assignedto = '" . $rowemp["employeeID"] . "' ". $statusList ." GROUP BY `status` <br>";
							$array = array_combine(array_column($sql, 'status'), array_column($sql, 'cnt'));
							
							echo '<tr bgcolor="#E4EAEB"><td>' . $rowemp["name"] . '</td><td align="right">';
							
							if($accountType == "Sourcing"){
								$inurl = "report_show_assignments.php?comp=purchasing&show=status&eid=". $rowemp["employeeID"] ."&statusid=";
								$unassinurl = "report_show_assignments.php?comp=purchasing&show=status&eid=&statusid=";
								
								if($statusType == "Qualified Leads"){ 
									if (array_key_exists('111', $array)) {
										$tmp1 = $array['111'];
									}
									if (array_key_exists('70', $array)) {
										$tmp2 = $array['70'];
									}
									if (array_key_exists('115', $array)) {
										$tmp3 = $array['115'];
									}
									$tmp4 = $tmp1 + $tmp2 + $tmp3;
									
									if (array_key_exists('73', $array)) {
										$tmp5 = $array['73'];
									}
									if (array_key_exists('116', $array)) {
										$tmp6 = $array['116'];
									}
									if (array_key_exists('117', $array)) {
										$tmp7 = $array['117'];
									}
									if (array_key_exists('118', $array)) {
										$tmp8 = $array['118'];
									}
									if (array_key_exists('119', $array)) {
										$tmp9 = $array['119'];
									}
									if (array_key_exists('120', $array)) {
										$tmp10 = $array['120'];
									}
									$tmp11 =  $tmp5 + $tmp6 + $tmp7 + $tmp8 + $tmp9 + $tmp10;
									
									$col1 = $col1 + $tmp1;
									echo "<a target='_blank' href='". $inurl ."111'>" . $tmp1 . "</a></td>";
								
									$col2 = $col2 + $tmp2;
									echo '<td align="right">';
									echo "<a target='_blank' href='". $inurl ."70'>" . $tmp2 . "</a>";
									echo '</td><td align="right">';
									
									$col3 = $col3 + $tmp3;
									echo "<a target='_blank' href='". $inurl ."115'>" . $tmp3 . "</a>";
									echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
									
									$col4 = $col4 + $tmp4;
									echo "<a target='_blank' href='". $inurl ."111,70,115'>" . $tmp4 . "</a>";
									echo '</td><td align="right" style="background-color:#FFF; border-right: 1px solid #000;"> &nbsp;';
									echo '</td><td align="right">';
									
									$col5 = $col5 + $tmp5;
									echo "<a target='_blank' href='". $inurl ."73'>" . $tmp5 . "</a>";
									echo '</td><td align="right">';
									
									$col6 = $col6 + $tmp6;
									echo "<a target='_blank' href='". $inurl ."116'>" . $tmp6 . "</a>";
									echo '</td><td align="right">';

									$col7 = $col7 + $tmp7;
									echo "<a target='_blank' href='". $inurl ."117'>" . $tmp7 . "</a>";
									echo '</td><td align="right">';
									
									$col8 = $col8 + $tmp8;
									echo "<a target='_blank' href='". $inurl ."118'>" . $tmp8 . "</a>";
									echo '</td><td align="right">';

									$col9 = $col9 + $tmp9;
									echo "<a target='_blank' href='". $inurl ."119'>" . $tmp9 . "</a>";
									echo '</td><td align="right">';

									$col10 = $col10 + $tmp10;
									echo "<a target='_blank' href='". $inurl ."120'>" . $tmp10 . "</a>";
									echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
									
									$col11 = $col11 + $tmp11;
									echo "<a target='_blank' href='". $inurl ."73,116,117,118,119,120'>" . $tmp11 . "</a>";
									echo "</td>";
									echo "</tr>";
								
								}elseif($statusType == "Suppliers"){
									
									if (array_key_exists('55', $array)) {
										$tmp1 = $array['55'];
									}
									if (array_key_exists('122', $array)) {
										$tmp2 = $array['122'];
									}
									if (array_key_exists('123', $array)) {
										$tmp3 = $array['123'];
									}
									if (array_key_exists('124', $array)) {
										$tmp4 = $array['124'];
									}
									if (array_key_exists('38', $array)) {
										$tmp5 = $array['38'];
									}
									if (array_key_exists('125', $array)) {
										$tmp6 = $array['125'];
									}
									if (array_key_exists('126', $array)) {
										$tmp7 = $array['126'];
									}
									if (array_key_exists('127', $array)) {
										$tmp8 = $array['127'];
									}
									$tmp9 = $tmp1 + $tmp2 + $tmp3 + $tmp4 + $tmp5 + $tmp6 + $tmp7 + $tmp8;
									
									if (array_key_exists('121', $array)) {
										$tmp10 = $array['121'];
									}
									if (array_key_exists('33', $array)) {
										$tmp11 = $array['33'];
									}
									if (array_key_exists('49', $array)) {
										$tmp12 = $array['49'];
									}
									if (array_key_exists('113', $array)) {
										$tmp13 = $array['113'];
									}
									if (array_key_exists('114', $array)) {
										$tmp14 = $array['114'];
									}
									$tmp15 = $tmp10 + $tmp11 + $tmp12 + $tmp13 + $tmp14;
									
									$col1 = $col1 + $tmp1;
									echo "<a target='_blank' href='". $inurl ."55'>" . $tmp1 . "</a></td>";
								
									$col2 = $col2 + $tmp2;
									echo '<td align="right">';
									echo "<a target='_blank' href='". $inurl ."122'>" . $tmp2 . "</a>";
									echo '</td><td align="right">';
									
									$col3 = $col3 + $tmp3;
									echo "<a target='_blank' href='". $inurl ."123'>" . $tmp3 . "</a>";
									echo '</td><td align="right" >';
									
									$col4 = $col4 + $tmp4;
									echo "<a target='_blank' href='". $inurl ."124'>" . $tmp4 . "</a>";
									echo '</td><td align="right" >';

									$col5 = $col5 + $tmp5;
									echo "<a target='_blank' href='". $inurl ."38'>" . $tmp5 . "</a>";
									echo '</td><td align="right" >';

									$col6 = $col6 + $tmp6;
									echo "<a target='_blank' href='". $inurl ."125'>" . $tmp6 . "</a>";
									echo '</td><td align="right" >';

									$col7 = $col7 + $tmp7;
									echo "<a target='_blank' href='". $inurl ."126'>" . $tmp7 . "</a>";
									echo '</td><td align="right" >';

									$col8 = $col8 + $tmp8;
									echo "<a target='_blank' href='". $inurl ."127'>" . $tmp8 . "</a>";
									echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';

									$col9 = $col9 + $tmp9;
									echo "<a target='_blank' href='". $inurl ."55,122,123,124,38,125,126,127'>" . $tmp9 . "</a>";
									echo '</td><td align="right" style="background-color:#FFF; border-right: 1px solid #000;"> &nbsp;';
									echo '</td><td align="right">';
									
									$col10 = $col10 + $tmp10;
									echo "<a target='_blank' href='". $inurl ."121'>" . $tmp10 . "</a>";
									echo '</td><td align="right">';
									$col11 = $col11 + $tmp11;
									
									echo "<a target='_blank' href='". $inurl ."33'>" . $tmp11 . "</a>";
									echo '</td><td align="right">';
									
									$col12 = $col12 + $tmp12;
									echo "<a target='_blank' href='". $inurl ."49'>" . $tmp12 . "</a>";
									echo '</td><td align="right">';
									
									$col13 = $col13 + $tmp13;
									echo "<a target='_blank' href='". $inurl ."113'>" . $tmp13 . "</a>";
									echo '</td><td align="right" >';
									
									$col14 = $col14 + $tmp14;
									echo "<a target='_blank' href='". $inurl ."114'>" . $tmp14 . "</a>";
									echo '</td><td align="right" style="border-left: 1px solid #000;border-right: 1px solid #000;">';
									$col15 = $col15 + $tmp15;
									echo "<a target='_blank' href='". $inurl ."121,33,49,113,114'>" . $tmp15 . "</a>";
									echo "</td>";
									echo "</tr>";
									
									
								}elseif($statusType == "Other"){
									if (array_key_exists('31', $array)) {
										$tmp2 = $array['31'];
									}
									$tmp3 = $tmp1 + $tmp2;
									
									$col2 = $col2 + $tmp2;
									echo "<a target='_blank' href='". $inurl ."31'>" . $tmp2 . "</a>";
									echo "</td>";
									echo "</tr>";
									
								}else{
									if (array_key_exists('65', $array)) {
										$tmp1 = $array['65'];
									}
									if (array_key_exists('107', $array)) {
										$tmp2 = $array['107'];
									}
									if (array_key_exists('108', $array)) {
										$tmp3 = $array['108'];
									}
									if (array_key_exists('109', $array)) {
										$tmp4 = $array['109'];
									}
									$tmp5 = $tmp1 + $tmp2 + $tmp3 + $tmp4;
									
									if (array_key_exists('44', $array)) {
										$tmp6 = $array['44'];
									}
									if (array_key_exists('112', $array)) {
										$tmp7 = $array['112'];
									}
									if (array_key_exists('110', $array)) {
										$tmp8 = $array['110'];
									}
									$tmp9 =  $tmp6 + $tmp7 + $tmp8;
									//3,107,108,109,44,112,110
									$col1 = $col1 + $tmp1;
									echo "<a target='_blank' href='". $inurl ."65'>" . $tmp1 . "</a></td>";
									echo '<td align="right">';
									
									$col2 = $col2 + $tmp2;
									echo "<a target='_blank' href='". $inurl ."107'>" . $tmp2 . "</a>";
									echo '</td><td align="right">';
									
									$col3 = $col3 + $tmp3;
									echo "<a target='_blank' href='". $inurl ."108'>" . $tmp3 . "</a>";
									echo '</td><td align="right">';
									
									$col4 = $col4 + $tmp4;
									echo "<a target='_blank' href='". $inurl ."109'>" . $tmp4 . "</a>";
									echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
									
									$col5 = $col5 + $tmp5;
									echo "<a target='_blank' href='". $inurl ."65,107,108,109'>" . $tmp5 . "</a>";
									echo '</td><td align="right" style="background-color:#FFF; border-right: 1px solid #000;"> &nbsp;';
									echo '</td><td align="right">';
									
									$col6 = $col6 + $tmp6;
									echo "<a target='_blank' href='". $inurl ."44'>" . $tmp6 . "</a>";
									echo '</td><td align="right">';
									
									$col7 = $col7 + $tmp7;
									echo "<a target='_blank' href='". $inurl ."112'>" . $tmp7 . "</a>";
									echo '</td><td align="right">';
									
									$col8 = $col8 + $tmp8;
									echo "<a target='_blank' href='". $inurl ."110'>" . $tmp8 . "</a>";
									echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
									
									$col9 = $col9 + $tmp9;
									echo "<a target='_blank' href='". $inurl ."44,112,110'>" . $tmp9 . "</a>";
									
									echo "</td>";
									echo "</tr>";
								}
							}else{
								
								if($statusType == "Qualified Leads"){ 
									if (array_key_exists('100', $array)) {
										$tmp1 = $array['100'];
									}
									if (array_key_exists('101', $array)) {
										$tmp2 = $array['101'];
									}
									if (array_key_exists('32', $array)) {
										$tmp3 = $array['32'];
									}
									$tmp4 = $tmp1 + $tmp2 + $tmp3;
									
									if (array_key_exists('50', $array)) {
										$tmp5 = $array['50'];
									}
									if (array_key_exists('102', $array)) {
										$tmp6 = $array['102'];
									}
									if (array_key_exists('103', $array)) {
										$tmp7 = $array['103'];
									}
									$tmp8 =  $tmp5 + $tmp6 + $tmp7;
								
									$col1 = $col1 + $tmp1;
									echo "<a target='_blank' href='". $inurl ."100'>" . $tmp1 . "</a></td>";
								
									$col2 = $col2 + $tmp2;
									echo '<td align="right">';
									echo "<a target='_blank' href='". $inurl ."101'>" . $tmp2 . "</a>";
									echo '</td><td align="right">';
									
									$col3 = $col3 + $tmp3;
									echo "<a target='_blank' href='". $inurl ."32'>" . $tmp3 . "</a>";
									echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
									
									$col4 = $col4 + $tmp4;
									echo "<a target='_blank' href='". $inurl ."100,101,32'>" . $tmp4 . "</a>";
									echo '</td><td align="right" style="background-color:#FFF; border-right: 1px solid #000;"> &nbsp;';
									echo '</td><td align="right">';
									
									$col5 = $col5 + $tmp5;
									echo "<a target='_blank' href='". $inurl ."50'>" . $tmp5 . "</a>";
									echo '</td><td align="right">';
									$col6 = $col6 + $tmp6;
									echo "<a target='_blank' href='". $inurl ."102'>" . $tmp6 . "</a>";
									
									echo '</td><td align="right">';
									$col7 = $col7 + $tmp7;
									echo "<a target='_blank' href='". $inurl ."103'>" . $tmp7 . "</a>";
									echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
									$col8 = $col8 + $tmp8;
									echo "<a target='_blank' href='". $inurl ."50,102,103'>" . $tmp8 . "</a>";
									echo "</td>";
									echo "</tr>";
								
								}elseif($statusType == "Customers"){
									if (array_key_exists('51', $array)) {
										$tmp1 = $array['51'];
									}
									if (array_key_exists('56', $array)) {
										$tmp2 = $array['56'];
									}
									if (array_key_exists('104', $array)) {
										$tmp3 = $array['104'];
									}
									$tmp4 = $tmp1 + $tmp2 + $tmp3;
									
									if (array_key_exists('105', $array)) {
										$tmp5 = $array['105'];
									}
									if (array_key_exists('33', $array)) {
										$tmp6 = $array['33'];
									}
									if (array_key_exists('24', $array)) {
										$tmp7 = $array['24'];
									}
									if (array_key_exists('43', $array)) {
										$tmp8 = $array['43'];
									}
									if (array_key_exists('106', $array)) {
										$tmp9 = $array['106'];
									}
									$tmp10 = $tmp5 + $tmp6 + $tmp7 + $tmp8 + $tmp9;
									
									$col1 = $col1 + $tmp1;
										
									echo "<a target='_blank' href='". $inurl ."51'>" . $tmp1 . "</a></td>";
								
									$col2 = $col2 + $tmp2;
									echo '<td align="right">';
									echo "<a target='_blank' href='". $inurl ."56'>" . $tmp2 . "</a>";
									echo '</td><td align="right">';
									
									$col3 = $col3 + $tmp3;
									echo "<a target='_blank' href='". $inurl ."104'>" . $tmp3 . "</a>";
									echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
									
									$col4 = $col4 + $tmp4;
									echo "<a target='_blank' href='". $inurl ."51,56,104'>" . $tmp4 . "</a>";
									echo '</td><td align="right" style="background-color:#FFF; border-right: 1px solid #000;"> &nbsp;';
									echo '</td><td align="right">';
									
									$col5 = $col5 + $tmp5;
									echo "<a target='_blank' href='". $inurl ."105'>" . $tmp5 . "</a>";
									echo '</td><td align="right">';
									$col6 = $col6 + $tmp6;
									
									echo "<a target='_blank' href='". $inurl ."33'>" . $tmp6 . "</a>";
									echo '</td><td align="right">';
									
									$col7 = $col7 + $tmp7;
									echo "<a target='_blank' href='". $inurl ."24'>" . $tmp7 . "</a>";
									echo '</td><td align="right">';
									
									$col8 = $col8 + $tmp8;
									echo "<a target='_blank' href='". $inurl ."43'>" . $tmp8 . "</a>";
									echo '</td><td align="right" >';
									
									$col9 = $col9 + $tmp9;
									echo "<a target='_blank' href='". $inurl ."106'>" . $tmp9 . "</a>";
									echo '</td><td align="right" style="border-left: 1px solid #000;border-right: 1px solid #000;">';
									$col10 = $col10 + $tmp10;
									echo "<a target='_blank' href='". $inurl ."105,33,24,43,106'>" . $tmp10 . "</a>";
									echo "</td>";
									echo "</tr>";
									
									
								}elseif($statusType == "Other"){
									if (array_key_exists('52', $array)) {
										$tmp1 = $array['52'];
									}
									if (array_key_exists('31', $array)) {
										$tmp2 = $array['31'];
									}
									$tmp3 = $tmp1 + $tmp2;
									
									$col1 = $col1 + $tmp1;
									echo "<a target='_blank' href='". $inurl ."52'>" . $tmp1 . "</a></td>";
									echo '<td align="right">';
									
									$col2 = $col2 + $tmp2;
									echo "<a target='_blank' href='". $inurl ."31'>" . $tmp2 . "</a>";
									echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
									
									$col3 = $col3 + $tmp3;
									echo "<a target='_blank' href='". $inurl ."52,31'>" . $tmp3 . "</a>";
									echo "</td>";
									echo "</tr>";
									
								}else{
									if (array_key_exists('3', $array)) {
										$tmp1 = $array['3'];
									}
									if (array_key_exists('94', $array)) {
										$tmp2 = $array['94'];
									}
									if (array_key_exists('95', $array)) {
										$tmp3 = $array['95'];
									}
									if (array_key_exists('96', $array)) {
										$tmp4 = $array['96'];
									}
									$tmp5 = $tmp1 + $tmp2 + $tmp3 + $tmp4;
									
									if (array_key_exists('97', $array)) {
										$tmp6 = $array['97'];
									}
									if (array_key_exists('98', $array)) {
										$tmp7 = $array['98'];
									}
									if (array_key_exists('99', $array)) {
										$tmp8 = $array['99'];
									}
									$tmp9 =  $tmp6 + $tmp7 + $tmp8;
								
									$col1 = $col1 + $tmp1;
									echo "<a target='_blank' href='report_show_assignments.php?show=status&statusid=3&eid=" . $rowemp["employeeID"] . "'>" . $tmp1 . "</a></td>";
								
									echo '<td align="right">';
									$col2 = $col2 + $tmp2;
									echo "<a target='_blank' href='report_show_assignments.php?show=status&statusid=94&eid=" . $rowemp["employeeID"] . "'>" . $tmp2 . "</a>";

									echo '</td><td align="right">';
									$col3 = $col3 + $tmp3;
									echo "<a target='_blank' href='report_show_assignments.php?show=status&statusid=95&eid=" . $rowemp["employeeID"] . "'>" . $tmp3 . "</a>";

									echo '</td><td align="right">';
									$col4 = $col4 + $tmp4;
									echo "<a target='_blank' href='report_show_assignments.php?show=status&statusid=96&eid=" . $rowemp["employeeID"] . "'>" . $tmp4 . "</a>";
									echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
									$col5 = $col5 + $tmp5;
									echo "<a target='_blank' href='report_show_assignments.php?show=status&statusid=3,94,95,96&eid=" . $rowemp["employeeID"] . "'>" . $tmp5 . "</a>";
									echo '</td><td align="right" style="background-color:#FFF; border-right: 1px solid #000;"> &nbsp;';
									echo '</td><td align="right">';
									$col6 = $col6 + $tmp6;
									echo "<a target='_blank' href='report_show_assignments.php?show=status&statusid=97&eid=" . $rowemp["employeeID"] . "'>" . $tmp6 . "</a>";
									
									echo '</td><td align="right">';
									$col7 = $col7 + $tmp7;
									echo "<a target='_blank' href='report_show_assignments.php?show=status&statusid=98&eid=" . $rowemp["employeeID"] . "'>" . $tmp7 . "</a>";
									echo '</td><td align="right">';
									$col8 = $col8 + $tmp8;
									echo "<a target='_blank' href='report_show_assignments.php?show=status&statusid=99&eid=" . $rowemp["employeeID"] . "'>" . $tmp8 . "</a>";
									echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
									$col9 = $col9 + $tmp9;
									echo "<a target='_blank' href='report_show_assignments.php?show=status&statusid=97,98,99&eid=" . $rowemp["employeeID"] . "'>" . $tmp9 . "</a>";
									
									echo "</td>";
									echo "</tr>";
								}
							}
						}


						$unassign_col1 = $unassign_col2 = $unassign_col3 = $unassign_col4 = $unassign_col5 = 0;
						$unassign_col6 = $unassign_col7 = $unassign_col8 = $unassign_col9 = $unassign_col10  = 0; $unassign_col11  = 0;
						$unassign_col12 = $unassign_col13 = $unassign_col14 = $unassign_col15 = 0;
						db_b2b();
						$sqlu = db_query("SELECT status, count(ID) as cnt FROM companyInfo WHERE ". $companyType." assignedto = '' ". $statusList." " .$transTypeQry ." GROUP BY `status` ");

						$array = array_combine(array_column($sqlu, 'status'), array_column($sqlu, 'cnt'));
						
						if($accountType == "Sourcing"){
							$unassinurl = "report_show_assignments.php?comp=purchasing&show=status&eid=&statusid=";
							if($statusType == "Qualified Leads"){ 
								if (array_key_exists('111', $array)) {
									$unassign_col1 = $unassign_col1 + $array['111'];
								}
								if (array_key_exists('70', $array)) {
									$unassign_col2 = $unassign_col2 + $array['70'];
								}
								if (array_key_exists('115', $array)) {
									$unassign_col3 = $unassign_col3 + $array['115'];
								}
								$unassign_col4 = $unassign_col1 + $unassign_col2 + $unassign_col3;
								
								if (array_key_exists('73', $array)) {
									$unassign_col5 = $unassign_col5 + $array['73'];
								}
								if (array_key_exists('116', $array)) {
									$unassign_col6 = $unassign_col6 + $array['116'];
								}
								if (array_key_exists('117', $array)) {
									$unassign_col7 = $unassign_col7 + $array['117'];
								}
								if (array_key_exists('118', $array)) {
									$unassign_col8 = $unassign_col8 + $array['118'];
								}
								if (array_key_exists('119', $array)) {
									$unassign_col9 = $unassign_col9 + $array['119'];
								}
								if (array_key_exists('120', $array)) {
									$unassign_col10 = $unassign_col10 + $array['120'];
								}
								$unassign_col11 = $unassign_col5 + $unassign_col6 + $unassign_col7 + $unassign_col8 + $unassign_col9 + $unassign_col10;
								
								echo '<tr bgcolor="#E4EAEB"><td align=left><b>Unassigned</b></td>';
								echo '<td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."111'>" . $unassign_col1 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."70'>" . $unassign_col2 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."115'>" . $unassign_col3 . "</a>";
								echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
								echo "<a target='_blank' href='". $unassinurl ."111,70,115'>" . $unassign_col4 . "</a>";
								echo '</td><td align="right" style="background-color:#FFF; border-right: 1px solid #000;">&nbsp;';
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."73'>" . $unassign_col5 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."116'>" . $unassign_col6 . "</a>"; 
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."117'>" . $unassign_col7 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."118'>" . $unassign_col8 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."119'>" . $unassign_col9 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."120'>" . $unassign_col10 . "</a>";
								echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
								echo "<a  target='_blank'href='". $unassinurl ."73,116,117,118,119,120'>" . $unassign_col11 . "</a>";
								echo "</td></tr>";
								echo "</tbody>";


								$emp_list = rtrim($emp_list, ",");
								$sorturl = "report_show_assignments_new.php?comp=purchasing&show=status&eid=" . $emp_list . "&transactionType=". $_REQUEST['transactionType']."&statusid=";
								
								echo '<tr bgcolor="#E4EAEB"><td align="right"><b>Total</b></td><td align="right"><strong>';
								echo "<a target='_blank' href='" . $sorturl . "111'>" . ($col1 + $unassign_col1) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "70'>" . ($col2 + $unassign_col2) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "115'>" . ($col3 + $unassign_col3) . "</a>";
								echo "</strong></td><td align='right' style='border-left: 1px solid #000; border-right: 1px solid #000;'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "111,70,115'>" . ($col4 + $unassign_col4) . "</a>";
								echo "</strong></td><td style='background-color:#FFF; border-right: 1px solid #000;'>";
								echo "</td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "73'>" . ($col5 + $unassign_col5) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "116'>" . ($col6 + $unassign_col6) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "117'>" . ($col7 + $unassign_col7) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "118'>" . ($col8 + $unassign_col8) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "119'>" . ($col9 + $unassign_col9) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "120'>" . ($col10 + $unassign_col10) . "</a>";
								echo "</strong></td><td align='right' style='border-left: 1px solid #000; border-right: 1px solid #000;'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "73,116,117,118,119,120'>" . ($col11 + $unassign_col11) . "</a>";
								echo "</strong></td>";

								echo "</tr>";
								
							}elseif($statusType == "Suppliers"){
							
								if (array_key_exists('55', $array)) {
									$unassign_col1 = $unassign_col1 + $array['55'];
								}
								if (array_key_exists('122', $array)) {
									$unassign_col2 = $unassign_col2 + $array['122'];
								}
								if (array_key_exists('123', $array)) {
									$unassign_col3 = $unassign_col3 + $array['123'];
								}
								if (array_key_exists('124', $array)) {
									$unassign_col4 = $unassign_col4 + $array['124'];
								}
								if (array_key_exists('38', $array)) {
									$unassign_col5 = $unassign_col5 + $array['38'];
								}
								if (array_key_exists('125', $array)) {
									$unassign_col6 = $unassign_col6 + $array['125'];
								}
								if (array_key_exists('126', $array)) {
									$unassign_col7 = $unassign_col7 + $array['126'];
								}
								if (array_key_exists('127', $array)) {
									$unassign_col8 = $unassign_col8 + $array['127'];
								}

								$unassign_col9 = $unassign_col1 + $unassign_col2 + $unassign_col3 + $unassign_col4 + $unassign_col5 + $unassign_col6 + $unassign_col7 + $unassign_col8;
								if (array_key_exists('121', $array)) {
									$unassign_col10 = $unassign_col10 + $array['121'];
								}
								if (array_key_exists('33', $array)) {
									$unassign_col11 = $unassign_col11 + $array['33'];
								}
								if (array_key_exists('49', $array)) {
									$unassign_col12 = $unassign_col12 + $array['49'];
								}
								if (array_key_exists('113', $array)) {
									$unassign_col13 = $unassign_col13 + $array['113'];
								}
								if (array_key_exists('114', $array)) {
									$unassign_col14 = $unassign_col14 + $array['114'];
								}
								$unassign_col15 = $unassign_col10 + $unassign_col11 + $unassign_col12 + $unassign_col13 + $unassign_col14;


								echo '<tr bgcolor="#E4EAEB"><td align=left><b>Unassigned</b></td>';
								echo '<td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."55'>" . $unassign_col1 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."122'>" . $unassign_col2 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."123'>" . $unassign_col3 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."124'>" . $unassign_col4 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."38'>" . $unassign_col5 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."125'>" . $unassign_col6 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."126'>" . $unassign_col7 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."127'>" . $unassign_col8 . "</a>";
								echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
								echo "<a target='_blank' href='". $unassinurl ."55,122,123,124,38,125,126,127'>" . $unassign_col9 . "</a>";
								echo '</td><td align="right" style="background-color:#FFF; border-right: 1px solid #000;">&nbsp;';
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."121'>" . $unassign_col10 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."33'>" . $unassign_col11 . "</a>"; 
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."49'>" . $unassign_col12 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."113'>" . $unassign_col13 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."114'>" . $unassign_col14 . "</a>";
								echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
								echo "<a  target='_blank'href='". $unassinurl ."121,33,49,113,114'>" . $unassign_col15 . "</a>";
								echo "</td></tr>";
								echo "</tbody>";


								$emp_list = rtrim($emp_list, ",");
								$sorturl = "report_show_assignments_new.php?comp=purchasing&show=status&eid=" . $emp_list . "&statusid=";
								
								echo '<tr bgcolor="#E4EAEB"><td align="right"><b>Total</b></td><td align="right"><strong>';
								echo "<a target='_blank' href='" . $sorturl . "55'>" . ($col1 + $unassign_col1) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "122'>" . ($col2 + $unassign_col2) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "123'>" . ($col3 + $unassign_col3) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "124'>" . ($col4 + $unassign_col4) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "38'>" .  ($col5 + $unassign_col5) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "125'>" . ($col6 + $unassign_col6) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "126'>" . ($col7 + $unassign_col7) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "127'>" . ($col8 + $unassign_col8) . "</a>";
								echo "</strong></td><td align='right' style='border-left: 1px solid #000; border-right: 1px solid #000;'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "55,122,123,124,38,125,126,127'>" . ($col9 + $unassign_col9) . "</a>";
								echo "</strong></td><td style='background-color:#FFF; border-right: 1px solid #000;'>";
								echo "</td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "121'>" . ($col10 + $unassign_col10) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "33'>" . ($col11 + $unassign_col11) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "49'>" . ($col12 + $unassign_col12) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "113'>" . ($col13 + $unassign_col13) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "114'>" . ($col14 + $unassign_col14) . "</a>";
								echo "</strong></td><td align='right' style='border-left: 1px solid #000; border-right: 1px solid #000;'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "121,33,49,113,114'>" . ($col15 + $unassign_col15) . "</a>";
								echo "</strong></td>";

								echo "</tr>";
								
							}elseif($statusType == "Other"){
								if (array_key_exists('31', $array)) {
									$unassign_col2 = $unassign_col2 + $array['31'];
								}
								$unassign_col3 = $unassign_col2;
								echo '<tr bgcolor="#E4EAEB"><td align=left><b>Unassigned</b></td>';
								echo '<td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."31'>" . $unassign_col2 . "</a>";
								echo '</td></tr>';
								
								$emp_list = rtrim($emp_list, ",");
								$sorturl = "report_show_assignments_new.php?comp=purchasing&show=status&eid=" . $emp_list . "&statusid=";
								
								echo '<tr bgcolor="#E4EAEB"><td align="right"><b>Total</b></td><td align="right"><strong>';
								echo "<a target='_blank' href='" . $sorturl . "31'>" . ($col2 + $unassign_col2) . "</a>";
								echo "</strong></td>";
								echo "</tr>";
								
								
							}else{	
								if (array_key_exists('65', $array)) {
									$unassign_col1 = $unassign_col1 + $array['65'];
								}
								if (array_key_exists('107', $array)) {
									$unassign_col2 = $unassign_col2 + $array['107'];
								}
								if (array_key_exists('108', $array)) {
									$unassign_col3 = $unassign_col3 + $array['108'];
								}
								if (array_key_exists('109', $array)) {
									$unassign_col4 = $unassign_col4 + $array['109'];
								}
								$unassign_col5 = $unassign_col1 + $unassign_col2 + $unassign_col3 + $unassign_col4;
								
								if (array_key_exists('44', $array)) {
									$unassign_col6 = $unassign_col6 + $array['44'];
								}
								if (array_key_exists('112', $array)) {
									$unassign_col7 = $unassign_col7 + $array['112'];
								}
								if (array_key_exists('110', $array)) {
									$unassign_col8 = $unassign_col8 + $array['110'];
								}
								$unassign_col9 = $unassign_col6 + $unassign_col7 + $unassign_col8;
								

								echo '<tr bgcolor="#E4EAEB"><td align=left><b>Unassigned</b></td>';
								echo '<td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."65'>" . $unassign_col1 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."107'>" . $unassign_col2 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."108'>" . $unassign_col3 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."109'>" . $unassign_col4 . "</a>";
								echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
								echo "<a target='_blank' href='". $unassinurl ."65,107,108,109'>" . $unassign_col5 . "</a>";
								echo '</td><td align="right" style="background-color:#FFF; border-right: 1px solid #000;">&nbsp;';
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."44'>" . $unassign_col6 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."112'>" . $unassign_col7 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."110'>" . $unassign_col8 . "</a>";
								echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
								echo "<a target='_blank' href='". $unassinurl ."44,112,110'>" . $unassign_col9 . "</a>";
								echo "</td></tr>";
								echo "</tbody>";


								$emp_list = rtrim($emp_list, ",");
								$sorturl = "report_show_assignments_new.php?comp=purchasing&show=status&eid=" . $emp_list . "&statusid=";
								
								echo '<tr bgcolor="#E4EAEB"><td align="right"><b>Total</b></td><td align="right"><strong>';
								echo "<a target='_blank' href='" . $sorturl . "65'>" . ($col1 + $unassign_col1) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "107'>" . ($col2 + $unassign_col2) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "108'>" . ($col3 + $unassign_col3) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "109'>" . ($col4 + $unassign_col4) . "</a>";
								echo "</strong></td><td align='right' style='border-left: 1px solid #000; border-right: 1px solid #000;'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "65,107,108,109'>" . ($col5 + $unassign_col5) . "</a>";
								echo "</strong></td><td style='background-color:#FFF; border-right: 1px solid #000;'>";
								echo "</td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "44'>" . ($col6 + $unassign_col6) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "112'>" . ($col7 + $unassign_col7) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "110'>" . ($col8 + $unassign_col8) . "</a>";
								echo "</strong></td><td align='right' style='border-left: 1px solid #000; border-right: 1px solid #000;'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "44,112,110'>" . ($col9 + $unassign_col9) . "</a>";
								echo "</strong></td>";

								echo "</tr>";
							
							}
							
							
						}else{
							if($statusType == "Qualified Leads"){ 
								if (array_key_exists('100', $array)) {
									$unassign_col1 = $unassign_col1 + $array['100'];
								}
								if (array_key_exists('101', $array)) {
									$unassign_col2 = $unassign_col2 + $array['101'];
								}
								if (array_key_exists('32', $array)) {
									$unassign_col3 = $unassign_col3 + $array['32'];
								}
								$unassign_col4 = $unassign_col1 + $unassign_col2 + $unassign_col3;
								
								if (array_key_exists('50', $array)) {
									$unassign_col5 = $unassign_col5 + $array['50'];
								}
								if (array_key_exists('102', $array)) {
									$unassign_col6 = $unassign_col6 + $array['102'];
								}
								if (array_key_exists('103', $array)) {
									$unassign_col7 = $unassign_col7 + $array['103'];
								}
								$unassign_col8 = $unassign_col5 + $unassign_col6 + $unassign_col7;
								
								echo '<tr bgcolor="#E4EAEB"><td align=left><b>Unassigned</b></td>';
								echo '<td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."100'>" . $unassign_col1 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."101'>" . $unassign_col2 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."32'>" . $unassign_col3 . "</a>";
								echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
								echo "<a target='_blank' href='". $unassinurl ."100,101,32'>" . $unassign_col4 . "</a>";
								echo '</td><td align="right" style="background-color:#FFF; border-right: 1px solid #000;">&nbsp;';
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."50'>" . $unassign_col5 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."102'>" . $unassign_col6 . "</a>"; 
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."103'>" . $unassign_col7 . "</a>";
								echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
								echo "<a  target='_blank'href='". $unassinurl ."50,102,103'>" . $unassign_col8 . "</a>";
								echo "</td></tr>";
								echo "</tbody>";


								$emp_list = rtrim($emp_list, ",");
								$sorturl = "report_show_assignments_new.php?show=status&eid=" . $emp_list . "&statusid=";
								
								echo '<tr bgcolor="#E4EAEB"><td align="right"><b>Total</b></td><td align="right"><strong>';
								echo "<a target='_blank' href='" . $sorturl . "100'>" . ($col1 + $unassign_col1) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "101'>" . ($col2 + $unassign_col2) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "32'>" . ($col3 + $unassign_col3) . "</a>";
								echo "</strong></td><td align='right' style='border-left: 1px solid #000; border-right: 1px solid #000;'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "100,101,32'>" . ($col4 + $unassign_col4) . "</a>";
								echo "</strong></td><td style='background-color:#FFF; border-right: 1px solid #000;'>";
								echo "</td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "50'>" . ($col5 + $unassign_col5) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "102'>" . ($col6 + $unassign_col6) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "103'>" . ($col7 + $unassign_col7) . "</a>";
								echo "</strong></td><td align='right' style='border-left: 1px solid #000; border-right: 1px solid #000;'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "50,102,103'>" . ($col8 + $unassign_col8) . "</a>";
								echo "</strong></td>";

								echo "</tr>";
							//}elseif($statusType == "Customers" || $statusType == "Supplier"){
							}elseif($statusType == "Customers"){
								
								if (array_key_exists('51', $array)) {
									$unassign_col1 = $unassign_col1 + $array['51'];
								}
								if (array_key_exists('56', $array)) {
									$unassign_col2 = $unassign_col2 + $array['56'];
								}
								if (array_key_exists('104', $array)) {
									$unassign_col3 = $unassign_col3 + $array['104'];
								}
								$unassign_col4 = $unassign_col1 + $unassign_col2 + $unassign_col3;
								if (array_key_exists('105', $array)) {
									$unassign_col5 = $unassign_col5 + $array['105'];
								}
								if (array_key_exists('33', $array)) {
									$unassign_col6 = $unassign_col6 + $array['33'];
								}
								if (array_key_exists('24', $array)) {
									$unassign_col7 = $unassign_col7 + $array['24'];
								}
								if (array_key_exists('43', $array)) {
									$unassign_col8 = $unassign_col8 + $array['43'];
								}
								if (array_key_exists('106', $array)) {
									$unassign_col9 = $unassign_col9 + $array['106'];
								}
								$unassign_col10 = $unassign_col5 + $unassign_col6 + $unassign_col7 + $unassign_col8 + $unassign_col9;


								echo '<tr bgcolor="#E4EAEB"><td align=left><b>Unassigned</b></td>';
								echo '<td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."51'>" . $unassign_col1 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."56'>" . $unassign_col2 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."104'>" . $unassign_col3 . "</a>";
								echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
								echo "<a target='_blank' href='". $unassinurl ."51,56,104'>" . $unassign_col4 . "</a>";
								echo '</td><td align="right" style="background-color:#FFF; border-right: 1px solid #000;">&nbsp;';
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."105'>" . $unassign_col5 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."33'>" . $unassign_col6 . "</a>"; 
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."24'>" . $unassign_col7 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."43'>" . $unassign_col8 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."106'>" . $unassign_col9 . "</a>";
								echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
								echo "<a  target='_blank'href='". $unassinurl ."105,33,24,43,106'>" . $unassign_col10 . "</a>";
								echo "</td></tr>";
								echo "</tbody>";


								$emp_list = rtrim($emp_list, ",");
								$sorturl = "report_show_assignments_new.php?show=status&eid=" . $emp_list . "&statusid=";
								
								echo '<tr bgcolor="#E4EAEB"><td align="right"><b>Total</b></td><td align="right"><strong>';
								echo "<a target='_blank' href='" . $sorturl . "51'>" . ($col1 + $unassign_col1) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "56'>" . ($col2 + $unassign_col2) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "104'>" . ($col3 + $unassign_col3) . "</a>";
								echo "</strong></td><td align='right' style='border-left: 1px solid #000; border-right: 1px solid #000;'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "51,56,104'>" . ($col4 + $unassign_col4) . "</a>";
								echo "</strong></td><td style='background-color:#FFF; border-right: 1px solid #000;'>";
								echo "</td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "105'>" . ($col5 + $unassign_col5) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "33'>" . ($col6 + $unassign_col6) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "24'>" . ($col7 + $unassign_col7) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "43'>" . ($col8 + $unassign_col8) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "106'>" . ($col9 + $unassign_col9) . "</a>";
								echo "</strong></td><td align='right' style='border-left: 1px solid #000; border-right: 1px solid #000;'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "105,33,24,43,106'>" . ($col10 + $unassign_col10) . "</a>";
								echo "</strong></td>";

								echo "</tr>";
								
							}elseif($statusType == "Other"){
								if (array_key_exists('52', $array)) {
									$unassign_col1 = $unassign_col1 + $array['52'];
								}
								if (array_key_exists('31', $array)) {
									$unassign_col2 = $unassign_col2 + $array['31'];
								}
								$unassign_col3 = $unassign_col1 + $unassign_col2;
								echo '<tr bgcolor="#E4EAEB"><td align=left><b>Unassigned</b></td>';
								echo '<td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."52'>" . $unassign_col1 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."31'>" . $unassign_col2 . "</a>";
								echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
								echo "<a target='_blank' href='". $unassinurl ."52,31'>" . $unassign_col3 . "</a>";
								echo '</td></tr>';
								
								$emp_list = rtrim($emp_list, ",");
								$sorturl = "report_show_assignments_new.php?show=status&eid=" . $emp_list . "&statusid=";
								
								echo '<tr bgcolor="#E4EAEB"><td align="right"><b>Total</b></td><td align="right"><strong>';
								echo "<a target='_blank' href='" . $sorturl . "52'>" . ($col1 + $unassign_col1) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "31'>" . ($col2 + $unassign_col2) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "52,31'>" . ($col3 + $unassign_col3) . "</a>";
								echo "</strong></td>";
								echo "</tr>";
								
								
							}else{
								if (array_key_exists('3', $array)) {
									$unassign_col1 = $unassign_col1 + $array['3'];
								}
								if (array_key_exists('94', $array)) {
									$unassign_col2 = $unassign_col2 + $array['94'];
								}
								if (array_key_exists('95', $array)) {
									$unassign_col3 = $unassign_col3 + $array['95'];
								}
								if (array_key_exists('96', $array)) {
									$unassign_col4 = $unassign_col4 + $array['96'];
								}
								
								$unassign_col5 = $unassign_col1 + $unassign_col2 + $unassign_col3 + $unassign_col4;
								if (array_key_exists('97', $array)) {
									$unassign_col6 = $unassign_col6 + $array['97'];
								}
								if (array_key_exists('98', $array)) {
									$unassign_col7 = $unassign_col7 + $array['98'];
								}
								if (array_key_exists('99', $array)) {
									$unassign_col8 = $unassign_col8 + $array['99'];
								}
								
								$unassign_col9 = $unassign_col6 + $unassign_col7 + $unassign_col8;


								echo '<tr bgcolor="#E4EAEB"><td align=left><b>Unassigned</b></td>';
								echo '<td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."3'>" . $unassign_col1 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."94'>" . $unassign_col2 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."95'>" . $unassign_col3 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."96'>" . $unassign_col4 . "</a>";
								echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
								echo "<a target='_blank' href='". $unassinurl ."3,94,95,96'>" . $unassign_col5 . "</a>";
								echo '</td><td align="right" style="background-color:#FFF; border-right: 1px solid #000;">&nbsp;';
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."97'>" . $unassign_col6 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."98'>" . $unassign_col7 . "</a>";
								echo '</td><td align="right">';
								echo "<a target='_blank' href='". $unassinurl ."99'>" . $unassign_col8 . "</a>";
								echo '</td><td align="right" style="border-left: 1px solid #000; border-right: 1px solid #000;">';
								echo "<a target='_blank' href='". $unassinurl ."97,98,99'>" . $unassign_col9 . "</a>";
								echo "</td></tr>";
								echo "</tbody>";


								$emp_list = rtrim($emp_list, ",");
								$sorturl = "report_show_assignments_new.php?show=status&eid=" . $emp_list . "&statusid=";
								
								echo '<tr bgcolor="#E4EAEB"><td align="right"><b>Total</b></td><td align="right"><strong>';
								echo "<a target='_blank' href='" . $sorturl . "3'>" . ($col1 + $unassign_col1) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "94'>" . ($col2 + $unassign_col2) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "95'>" . ($col3 + $unassign_col3) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "96'>" . ($col4 + $unassign_col4) . "</a>";
								echo "</strong></td><td align='right' style='border-left: 1px solid #000; border-right: 1px solid #000;'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "3,94,95,96'>" . ($col5 + $unassign_col5) . "</a>";
								echo "</strong></td><td style='background-color:#FFF; border-right: 1px solid #000;'>";
								echo "</td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "97'>" . ($col6 + $unassign_col6) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "98'>" . ($col7 + $unassign_col7) . "</a>";
								echo "</strong></td><td align='right'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "99'>" . ($col8 + $unassign_col8) . "</a>";
								echo "</strong></td><td align='right' style='border-left: 1px solid #000; border-right: 1px solid #000;'><strong>";
								echo "<a target='_blank' href='" . $sorturl . "97,98,99'>" . ($col9 + $unassign_col9) . "</a>";
								echo "</strong></td>";

								echo "</tr>";
								
							}
						}

						?>
					</table>

				</td>

			</tr>

		</table>
		
		<?php } // form submitted.?>
	</div>
</body>

</html>