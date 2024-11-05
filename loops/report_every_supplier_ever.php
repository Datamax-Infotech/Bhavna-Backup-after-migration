<?php
/*
Page Name: report_every_supplier_ever.php
Page created By: Amarendra Nath Singh
Page created On: 25-10-2024
Last Modified On: 
Last Modified By: Amarendra Singh
Change History:
Date			By            Description
==================================================================================================================
25-10-2024      Amarendra     This file is created for showing report of all Supplier.
==================================================================================================================
1320(2) Team needs to have a report that show a full list of all suppliers. This report shows the user all suppliers we have ever bought from since the beginning. This means transactions > 0

1. Name the report as "Every Supplier Ever".
2. Looks of the report (table columns) is like the table when you search a record using the search bar. See snip below. We would like for this report to show exactly all data that is shown in search results as demonstrated on the snip.
3a. On the report, add a dropdown filter at the top of the table which the dropdown option is all employees (primary sort by active/inactive, secondary sort alphabetical). We would like to be able to filter this report by employee but also have an option to filter by ALL using a drop down menu
3b. Add another filter for [Leaderboard] drop down value. Be sure to have "ALL" option default.
4. Add a "Load Report" button on the right side of the dropdown filter which when clicked, table with records will show below based on the employee on the dropdown.
5. Make sure to add sorting arrows per column and make sure it is working.

Find a way to filter by UsedCardboardBoxes vs UCBPalletSolutions (UCBPS) vs UCBZeroWaste (UCBZW) transactions

*/
session_start();
require_once("inc/header_session.php");
require_once("../mainfunctions/database.php");
require_once("../mainfunctions/general-functions.php");

?>
<!DOCTYPE html>
<html>

<head>
	<title>Every Supplier Ever</title>
	<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
	<link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
	<style>
	
	.tblSupplier tr th{
		background-color: #D9F2FF;
	}
	
	</style>
	
	
</head>

<body>
<div id="light" class="white_content"></div>
<div id="fade" class="black_overlay"></div>
	<?php include("inc/header.php"); ?>
	<div class="main_data_css">
		<div class="dashboard_heading" style="float: left;">
			<div style="float: left;">
				Every Supplier Ever
				<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
					<span class="tooltiptext">Every Supplier Ever.</span>
				</div>
				<br>
			</div>
		</div>
		<div>
			<form method="POST" name="frm_every_supplier_ever" id="frm_every_supplier_ever" action="<?php echo $_SERVER['PHP_SELF'];?>">
				<table>
					<tr>
						<td>Select Employee:</td>
						<td>
							<select name="empid" id="empid">
								<option value="All">All</option>
								<?php
								db();
								$empList = "";
								$sql = "SELECT * FROM loop_employees order by status, name";
								$result = db_query($sql);
								while ($myrowsel = array_shift($result)) {
									$empList .=  $myrowsel['b2b_id'] . ",";
									
									echo "<option value=" . $myrowsel["b2b_id"] . " ";

									if (isset($_REQUEST["empid"])) {
										if ($myrowsel["b2b_id"] == $_REQUEST["empid"]) echo " selected ";
									}
									if ($myrowsel["status"] != 'Active') {
										echo " >" . $myrowsel["name"] . "(Inactive)</option>";
									} else {
										echo " >" . $myrowsel["name"] . "</option>";
									}
								}
								?>
							</select>
							
						</td>
						
						<td>Leaderboard:</td>
						<td>
							<select name="leaderboard" id="leaderboard">
								<option value="All">All</option>
								<option <?php echo ($_REQUEST['leaderboard'] == 'B2B')? ' selected' : ''; ?>>B2B</option>
								<option <?php echo ($_REQUEST['leaderboard'] == 'B2C')? ' selected' : ''; ?>>B2C</option>
								<option <?php echo ($_REQUEST['leaderboard'] == 'GMI')? ' selected' : ''; ?>>GMI</option>
								<option <?php echo ($_REQUEST['leaderboard'] == 'UCBZW')? ' selected' : ''; ?>>UCBZW</option>
								<option <?php echo ($_REQUEST['leaderboard'] == 'PALLETS')? ' selected' : ''; ?>>PALLETS</option>
							</select>	
						</td>
						
						<td>Transaction Type:</td>
						<td>
							<select name="transactionType" id="transactionType">
								<option value="All">All</option>
								<option value='1' <?php echo ($_REQUEST['transactionType'] == '1')? ' selected' : ''; ?>>UsedCardboardBoxes</option>
								<option value='2' <?php echo ($_REQUEST['transactionType'] == '2')? ' selected' : ''; ?>>UCBPalletSolutions</option>
								<option value='3' <?php echo ($_REQUEST['transactionType'] == '3')? ' selected' : ''; ?>>UCBZeroWaste</option>
							</select>	
						</td>
						
						<td><input type="submit" name="submit" value="Load Report">
							<input type="hidden" id="reprun" name="reprun" value="yes">
						</td>
					</tr>
				</table>
			</form>
		</div>
		<br>
		<br>
		<div style="">
	<?php 
		//show result 
		if(isset($_REQUEST['submit']) || isset($_REQUEST["sort"])){
			
			$leaderboardQry = "";
			if(isset($_REQUEST['leaderboard'])){
				if ($_REQUEST["leaderboard"] != 'All'){
					$leaderboardQry = " AND Leaderboard = '". $_REQUEST["leaderboard"] ."' ";	
				}
			}
			
			$sorturl="?empid=". $_REQUEST["empid"] ."&leaderboard=". $_REQUEST["leaderboard"] ."&transactionType=". $_REQUEST['transactionType'] ."&sort=";
			$ascArrow = '<img src="images/sort_asc.png" width="6px;" height="12px;">';
			$descArrow = '<img src="images/sort_desc.png" width="6px;" height="12px;">';
	?>
			<table class="tblSupplier" width="1500px">
			  <thead>
				<tr>
					<th width="40px">#</th>
					<th width="60px">Employee Name
						<a href="<?php echo $sorturl;?>empName&so=A"><?php echo $ascArrow;?></a>
						<a href="<?php echo $sorturl;?>empName&so=D"><?php echo $descArrow;?></a>
					</th>
					<th width="160px">Company Name
						<a href="<?php echo $sorturl;?>companyName&so=A"><?php echo $ascArrow;?></a>
						<a href="<?php echo $sorturl;?>companyName&so=D"><?php echo $descArrow;?></a>
					</th>
					<th width="160px">Contact Name
						<a href="<?php echo $sorturl;?>contactName&so=A"><?php echo $ascArrow;?></a>
						<a href="<?php echo $sorturl;?>contactName&so=D"><?php echo $descArrow;?></a>
					</th>
					<th width="40px">UCB/ZW Rep
						<a href="<?php echo $sorturl;?>ucbzwRep&so=A"><?php echo $ascArrow;?></a>
						<a href="<?php echo $sorturl;?>ucbzwRep&so=D"><?php echo $descArrow;?></a>
					</th>
					<th width="100px">Account <br>Status
						<a href="<?php echo $sorturl;?>accountStatus&so=A"><?php echo $ascArrow;?></a>
						<a href="<?php echo $sorturl;?>accountStatus&so=D"><?php echo $descArrow;?></a>
					</th>
					<th width="160px">Industry
						<a href="<?php echo $sorturl;?>industry&so=A"><?php echo $ascArrow;?></a>
						<a href="<?php echo $sorturl;?>industry&so=D"><?php echo $descArrow;?></a>
					</th>
					<th width="70px">Sales<br>Transactions
						<a href="<?php echo $sorturl;?>salesTransaction&so=A"><?php echo $ascArrow;?></a>
						<a href="<?php echo $sorturl;?>salesTransaction&so=D"><?php echo $descArrow;?></a>
					</th>
					<th width="70px">Sales<br>Revenue
						<a href="<?php echo $sorturl;?>salesRevenue&so=A"><?php echo $ascArrow;?></a>
						<a href="<?php echo $sorturl;?>salesRevenue&so=D"><?php echo $descArrow;?></a>
					</th>
					<th width="70px">Purchasing<br>Transactions
						<a href="<?php echo $sorturl;?>sourceTransaction&so=A"><?php echo $ascArrow;?></a>
						<a href="<?php echo $sorturl;?>sourceTransaction&so=D"><?php echo $descArrow;?></a>
					</th>
					<th width="70px">Purchasing<br>Payments
						<a href="<?php echo $sorturl;?>sourcePayment&so=A"><?php echo $ascArrow;?></a>
						<a href="<?php echo $sorturl;?>sourcePayment&so=D"><?php echo $descArrow;?></a>
					</th>
					<th width="70px">Parent
						<a href="<?php echo $sorturl;?>parent&so=A"><?php echo $ascArrow;?></a>
						<a href="<?php echo $sorturl;?>parent&so=D"><?php echo $descArrow;?></a>
					</th>
					<th>Next Step
						<a href="<?php echo $sorturl;?>nextStep&so=A"><?php echo $ascArrow;?></a>
						<a href="<?php echo $sorturl;?>nextStep&so=D"><?php echo $descArrow;?></a>
					</th>
					<th width="88px">Last<br>Communication
						<a href="<?php echo $sorturl;?>lastCommunication&so=A"><?php echo $ascArrow;?></a>
						<a href="<?php echo $sorturl;?>lastCommunication&so=D"><?php echo $descArrow;?></a>
					</th>
					<th width="88px">Next<br>Communication
						<a href="<?php echo $sorturl;?>nextCommunication&so=A"><?php echo $ascArrow;?></a>
						<a href="<?php echo $sorturl;?>nextCommunication&so=D"><?php echo $descArrow;?></a>
					</th>
				</tr>
			  </thead>
			  <tbody>
	<?php
			if (isset($_REQUEST["sort"])) {
				$MGArray = $_SESSION['sortarrayn'];
			
				if($_REQUEST['sort'] == "empName")
				{
					$MGArraysort_I = array();

					foreach ($MGArray as $MGArraytmp) {
						$MGArraysort_I[] = $MGArraytmp['empName'];

					}

					if ($_REQUEST['so'] == "A"){
						array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 

					}
					if ($_REQUEST['so'] == "D"){
						array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
					}
				}
				if($_REQUEST['sort'] == "companyName")
				{
					$MGArraysort_I = array();

					foreach ($MGArray as $MGArraytmp) {
						$MGArraysort_I[] = $MGArraytmp['nickname'];

					}

					if ($_REQUEST['so'] == "A"){
						array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 

					}
					if ($_REQUEST['so'] == "D"){
						array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
					}
				}
				if($_REQUEST['sort'] == "contactName")
				{
					$MGArraysort_I = array();

					foreach ($MGArray as $MGArraytmp) {
						$MGArraysort_I[] = $MGArraytmp['contactName'];

					}

					if ($_REQUEST['so'] == "A"){
						array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 

					}
					if ($_REQUEST['so'] == "D"){
						array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
					}
					 
				} 	
				if($_REQUEST['sort'] == "accountStatus")
				{
					$MGArraysort_I = array();

					foreach ($MGArray as $MGArraytmp) {
						$MGArraysort_I[] = $MGArraytmp['statusName'];
					}

					if ($_REQUEST['so'] == "A"){
						array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 

					}
					if ($_REQUEST['so'] == "D"){
						array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
					}
					 
				}
				if($_REQUEST['sort'] == "ucbzwRep")
				{
					$MGArraysort_I = array();

					foreach ($MGArray as $MGArraytmp) {
						$MGArraysort_I[] = $MGArraytmp['UCB_ZW_Rep'];
					}

					if ($_REQUEST['so'] == "A"){
						array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
					}
					if ($_REQUEST['so'] == "D"){
						array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
					}
				}
				if($_REQUEST['sort'] == "industry")
				{
					$MGArraysort_I = array();

					foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['industry'];

					}

					if ($_REQUEST['so'] == "A"){
						array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 

					}
					if ($_REQUEST['so'] == "D"){
						array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
					}
				}		
				if($_REQUEST['sort'] == "salesTransaction")
				{
					$MGArraysort_I = array();

					foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['total_trans'];

					}

					if ($_REQUEST['so'] == "A"){
						array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 

					}
					if ($_REQUEST['so'] == "D"){
						array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
					}
				}
				if($_REQUEST['sort'] == "sourceTransaction")
				{
					$MGArraysort_I = array();

					foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['total_trans_p'];

					}

					if ($_REQUEST['so'] == "A"){
						array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 

					}
					if ($_REQUEST['so'] == "D"){
						array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
					}
				}
				if($_REQUEST['sort'] == "salesRevenue")
				{
					$MGArraysort_I = array();

					foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['summtd_SUMPO'];

					}
					if ($_REQUEST['so'] == "A"){
						array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
					}
					if ($_REQUEST['so'] == "D"){
						array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
					}
				}
				if($_REQUEST['sort'] == "sourcePayment")
				{
					$MGArraysort_I = array();

					foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['summtd_SUMPO_p'];

					}
					if ($_REQUEST['so'] == "A"){
						array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
					}
					if ($_REQUEST['so'] == "D"){
						array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
					}
				}
				if($_REQUEST['sort'] == "parent")
				{
					$MGArraysort_I = array();

					foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['parentchild_txt'];

					}
					if ($_REQUEST['so'] == "A"){
						array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
					}
					if ($_REQUEST['so'] == "D"){
						array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
					}
				}
				if($_REQUEST['sort'] == "nextStep")
				{
					$MGArraysort_I = array();

					foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['NS'];

					}
					if ($_REQUEST['so'] == "A"){
						array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
					}
					if ($_REQUEST['so'] == "D"){
						array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
					}
				}
				if($_REQUEST['sort'] == "lastCommunication")
				{
					$MGArraysort_I = array();

					foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['LD'];

					}
					if ($_REQUEST['so'] == "A"){
						array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
					}
					if ($_REQUEST['so'] == "D"){
						array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
					}
				}
				if($_REQUEST['sort'] == "nextCommunication")
				{
					$MGArraysort_I = array();

					foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['ND'];

					}
					if ($_REQUEST['so'] == "A"){
						array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
					}
					if ($_REQUEST['so'] == "D"){
						array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
					}
				}
			
				$num = 0;
				$alternate_cnt = 0;
				foreach ($MGArray as $data) { 

					$bg_color = "#E4E4E4";
					$tot_trans = $data["total_trans"];
					$tot_trans_p = $data["total_trans_p"];
					
					//if ($tot_trans >0 || $tot_trans_p >0){
					//	$bg_color = "#D0CECE";
					//}
					
					if ($alternate_cnt == 0)
					{
						$bg_color = "#E4E4E4";
						$alternate_cnt = 1;
					}else{
						$bg_color = "#D0CECE";
						$alternate_cnt = 0;
					}
					
					echo '<tr><td bgcolor="'. $bg_color .'">'. $num +=1 ;
					echo '</td><td bgcolor="'. $bg_color .'">'. $data["empName"] ;
					echo '</td><td bgcolor="'.$bg_color .'"><a href="viewCompany.php?ID='.$data['companyID'].'" target="_blank">'.$data["nickname"];
					echo '</a></td><td bgcolor="'. $bg_color .'">'.$data['contactName'];
					echo '</td><td bgcolor="'. $bg_color .'">'.$data["UCB_ZW_Rep"];
					echo '</td><td bgcolor="'. $bg_color .'">'.$data["statusName"];
					echo '</td><td bgcolor="'. $bg_color .'">'.$data["industry"];
					echo '</td><td bgcolor="'. $bg_color .'">';
					echo '<a href="javascript:void(0)" onclick="load_div('. $num . $data["companyID"] .'); return false;">';
					echo '<font color="#000">'. $tot_trans . '</font></a>';
					echo '<span id="' . $num . $data["companyID"] . '" style="display:none;"><a href="javascript:void(0)"  ';
					echo 'onclick="close_div(); return false;">Close</a>'. $data["lisoftrans"] .'</span>';
					echo '</td><td bgcolor="'. $bg_color .'">$'.number_format($data["summtd_SUMPO"],0);
					echo '</td><td bgcolor="'. $bg_color .'">'.$tot_trans_p;
					echo '</td><td bgcolor="'. $bg_color .'">$'.number_format($data["summtd_SUMPO_p"],0);
					echo '</td><td bgcolor="'. $bg_color .'">'.$data["parentchild_txt"];
					echo '</td><td bgcolor="'. $bg_color .'">';
						if ($data["UZWNS"] != "" ) { 
							echo "<b>UCBoxes Next Step:</b> " . $data["NS"];
							echo "<br><br><b>UCBZW Next Step:</b> " . $data["UZWNS"]; 
						}else{
							echo $data["NS"];
						}
					
					echo '</td><td bgcolor="';
					if (date('Y-m-d',strtotime($data["LD"])) == date('Y-m-d')) { echo "#00FF00"; 
					} elseif ( date('Y-m-d',strtotime($data["LD"])) < date('Y-m-d', strtotime("-90 days")) ) { echo "#FF0000"; 
					} else { echo $bg_color; }
					
					echo '">';
					if ($data["LD"]!="")  echo date('m/d/Y',strtotime($data["LD"])); 
					echo '</td>';
					
					if($data["UZWND"] != "" && $data["UZWND"] != "1969-12-31") {
					
						echo '<td align="center" bgcolor="'. $bg_color .'">';
						
						if ($data["UZWND"]!= "") {
							echo '<div style="';
							if($data["ND"] == date('Y-m-d')) { echo 'background:#00FF00'; 
							} elseif ($data["ND"] < date('Y-m-d') && $data["ND"] != "") { echo 'background:#FF0000'; 
							} else { echo 'background:'.  $bg_color; }
							echo '">';
							if ($data["LID"] > 0) if ($data["ND"]!="") echo "<b>UCBoxes Next Comm.:</b> " . date('m/d/Y',strtotime($data["ND"]));
							echo '</div><div style="';
							if ($data["UZWND"] == date('Y-m-d')) { echo 'background:#00FF00'; 
							} elseif ($data["UZWND"] < date('Y-m-d') && $data["UZWND"] != "") { echo 'background:#FF0000'; 
							} else { echo 'background:'.$bg_color; } 
							echo '">';
							if ($data["UZWND"]!="" ) echo "<br><br><b>UCBZW Next Comm.:</b> ". date('m/d/Y',strtotime($data["UZWND"]));
							echo '</div>';
						}
					
					}else{
						
						if($data["ND"] == date("Y-m-d")) { $bgcolor_str = "#00FF00"; 
						}elseif($data["ND"] < date('Y-m-d') && $data["ND"] != "") { $bgcolor_str = "#FF0000"; 
						}else{ $bgcolor_str = $bg_color; }
						echo '<td bgcolor="'.$bgcolor_str.'" align="center">';
						if ($data["LID"] > 0) if ($data["ND"]!="") echo date('m/d/Y',strtotime($data["ND"]));
						echo '</td>';
						
						
					}
					
					echo '</tr>';
					
				}
				
			}
			else{
				$empQry = "";
				$empList = rtrim($empList, ',');
				$MGArray = array();
				
				if(isset($_REQUEST['empid'])){
					if($_REQUEST['empid'] == "All"){

					}else{
						$empQry  = " AND (companyInfo.assignedto=". $_REQUEST['empid'] ." OR companyInfo.viewable1=" . $_REQUEST['empid'] . " OR companyInfo.viewable2=" . $_REQUEST['empid'] . " OR companyInfo.viewable3=" . $_REQUEST['empid'] . " OR companyInfo.viewable4=" . $_REQUEST['empid'] .")";
						//$empQry  = " AND companyInfo.assignedto=". $_REQUEST['empid'] ." ";
					}
				}
				
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
				
				
				db_b2b();
				//echo $empList . "<br>";
				
				$x = 'Select companyInfo.id AS I, companyInfo.loopid AS LID, companyInfo.contact AS C,  companyInfo.dateCreated AS D, companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS,
				companyInfo.shipCity , companyInfo.shipState, companyInfo.shipZip, companyInfo.ucbzw_next_step AS UZWNS, companyInfo.ucbzw_next_date AS UZWND, companyInfo.status, companyInfo.last_contact_date AS LD, companyInfo.next_date AS ND,  companyInfo.special_ops, companyInfo.ucbzw_flg, companyInfo.industry_id, 
				companyInfo.ucbzw_account_status, companyInfo.ucbzw_account_owner, companyInfo.haveNeed, companyInfo.industry_other, companyInfo.parent_child, companyInfo.assignedto, 
				companyInfo.parent_comp_id, companyInfo.link_sales_id, companyInfo.link_purchasing_id, companyInfo.pallet_flg from companyInfo WHERE companyInfo.loopid > 0 and companyInfo.haveNeed="Have Boxes"' . $transTypeQry . $empQry . ' ORDER BY companyInfo.nickname';
				
				//$x .= ' limit 1000' ;
				//echo $x . "<br>";
				$result1 = db_query($x);
				$num = 0; $alternate_cnt = 0;
				
				while($data = array_shift($result1)){
					
					//if($data["haveNeed"] == "Need Boxes"){
					//	$salescomp_ind_com = "yes"; 
					//}else{
						$salescomp_ind_com = "no";
					//}	
					//UCB_ZW rep and water account
				
					$empqry="select initials from employees where employees.employeeID='".$data["assignedto"]."'";
					$empres=db_query($empqry);
					$emprow=array_shift($empres);
					$UCB_ZW_Rep=$emprow["initials"];
					$UCB_ZW_Rep1=$emprow["initials"];
						
					if($data["ucbzw_flg"]==0){
						$water_rec="No";
					}
					else{
						$empqry="select initials from employees where employees.employeeID='".$data["ucbzw_account_owner"]."'";
						$empres=db_query($empqry);
						$emprow=array_shift($empres);
						//
						$empqry="select initials from employees where employees.employeeID='".$data["ucbzw_account_owner"]."'";
						$empres=db_query($empqry);
						$emprow=array_shift($empres);
						//
						$UCB_ZW_Rep_w=$emprow["initials"];
						$UCB_ZW_Rep=$UCB_ZW_Rep. "/".$UCB_ZW_Rep_w;
						//
						$water_rec="Yes";
						//
					}
					if($UCB_ZW_Rep1=="")
					{
						//$UCB_ZW_Rep="<span style='color:#FF0000;'>No Rep</span>";
						$UCB_ZW_Rep="No Rep";
					}
					if($UCB_ZW_Rep1=="" && $UCB_ZW_Rep_w=="")
					{
						$UCB_ZW_Rep="No Rep";
					}
					//
					if($data["industry_id"]!=""){
						//
						$indus_qry = "Select industry from industry_master where active_flg = 1 and industry_id = '" . $data["industry_id"]."'";

						$indus_res = db_query($indus_qry);
						while ($indus_row = array_shift($indus_res)) {
							$industry = $indus_row["industry"];
						}
						if ($data["industry_other"] != ""){
							$industry = $data["industry_other"];
						}	
					}
					else{
						$industry = "No Industry Selected";
					}
					//Parent or child or none
					$parentchild=$data["parent_child"];
					
					if($parentchild!="")
					{
						if($parentchild=="Parent")
						{
							$parentchild_txt="This is the Parent";
							$parent="yes";
						}
						if ($parentchild == 'Child') {
							$parent_compname=$data["CO"];
							$parent_comp_id=$data["parent_comp_id"];
							//echo $data["CO"];
							$parentchild_txt = "<a href='viewCompany.php?ID=$parent_comp_id' target='_blank'> " . getnickname($data["CO"], $parent_comp_id)." </a>";
							$parent="no";
						}
					}
					else{
						$parentchild_txt="No Family Tree Setup";
						$parent="no";
					}
					
					//
					if ($data["NN"] != "") {
						$nickname = $data["NN"];
					}else {
						$tmppos_1 = strpos($data["CO"], "-");
						if ($tmppos_1 != false)
						{
							$nickname = $data["CO"];
						}else {
							if ($data["shipCity"] <> "" || $data["shipState"] <> "" ) 
							{
								$nickname = $data["CO"] . " - " . $data["shipCity"] . ", " . $data["shipState"] ;
							}else { $nickname = $data["CO"]; }
						}
					}
					
					//Total transactions
					$link_sid=0; $link_pid=0;
					if($data['link_sales_id'] > 0 && $salescomp_ind_com == "no")
					{
						db_b2b();	
						$lqry = "Select ID, loopid, assignedto, industry_id, industry_other from companyInfo where ID=".$data['link_sales_id'];
						$l_res = db_query($lqry  );
						while ($lrows = array_shift($l_res)) {
							$link_sid = $lrows["loopid"];
						}

						$empqry = "Select initials from employees where employees.employeeID='".$lrows["assignedto"]."'";
						$empres=db_query($empqry);
						$emprow=array_shift($empres);
						$UCB_ZW_Rep .= "/" . $emprow["initials"];

						$industry_data_found = "";
						$indus_qry = "Select industry from industry_master where active_flg = 1 and industry_id = '" . $lrows["industry_id"]."'";
						$indus_res = db_query($indus_qry);
						while ($indus_row = array_shift($indus_res)) {
							$industry .= "/" . $indus_row["industry"];
							$industry_data_found = "yes";
						}
						if ($lrows["industry_other"] != ""){
							$industry .= "/" . $lrows["industry_other"];
							$industry_data_found = "yes";
						}	
						if ($industry_data_found == ""){
							$industry .= "/<span style='color:#FF0000;'>No Industry Selected</span>";
						}	
					}
					
					tep_db_close();
					$tot_trans = 0;
					$tot_trans_p = 0; $tot_trans_p_flg = 0;
					
					
					db();
					$qry1 = "Select count(id) as p_cnt from loop_transaction where `ignore` = 0 and warehouse_id = ". $data['LID'];
					//echo "CC2 - " . $qry1 . "<br>";
					$dt_view_res1 = db_query($qry1  );
					while ($myrow1 = array_shift($dt_view_res1)) 
					{
						$tot_trans_p = $tot_trans_p + floatval($myrow1['p_cnt']);
					}
					if ($tot_trans_p > 0)
					{
						$tot_trans_p_flg = 1;
					}
					
					//it is mandatory to have sourcing cnt is > 0
					if ($leaderboardQry != ""){
						$tot_trans_p_flg = 0; // if Leaderboard filter is selected
						//echo "Purchasing qry 1 - <br>";
					}
					
					//$data['link_sales_id']>0 && $link_sid > 0 &&
					if ( $tot_trans_p > 0 && $leaderboardQry != "")
					{
						//echo "leaderboardQry - " . $leaderboardQry . "<br>";
						//and warehouse_id = ". $link_sid . "
						$qry = "select count(id) as s_cnt from loop_transaction_buyer where `ignore` = 0 ". $leaderboardQry ." and 
						loop_transaction_buyer.virtual_inventory_trans_id in (Select id from loop_transaction where `ignore` = 0 and warehouse_id = ". $data['LID'] . ")";
						//echo "Purchasing qry - " . $qry . "<br>";
						$dt_view_res = db_query($qry  );
						while ($myrow = array_shift($dt_view_res)) 
						{
							$tot_trans = $tot_trans + floatval($myrow['s_cnt']);
							if ($leaderboardQry != "" && $tot_trans > 0 ){
								//echo "Purchasing qry 2 - <br>";
								$tot_trans_p_flg = 1;
							}
						}
					}

					$tot_rev = 0;  $summtd_SUMPO = 0;$tot_rev_p = 0; $inv_amt_totake =0;  $summtd_SUMPO_p = 0;  //po_poorderamount
					if ($data['LID'] > 0){
						$qry_p = "SELECT transaction_date, estimated_revenue, loop_warehouse.b2bid, loop_warehouse.company_name, loop_transaction.id, loop_transaction.warehouse_id 
						FROM loop_transaction inner join loop_warehouse on loop_warehouse.id = loop_transaction.warehouse_id WHERE loop_transaction.warehouse_id = '". $data['LID'] . "' AND loop_transaction.ignore < 1 order by loop_transaction.id";
						//echo "QQ - " . $qry_p . "<br>";
						$dt_view_res = db_query($qry_p  );
						while ($myrow = array_shift($dt_view_res)) 
						{
							$inv_amt_totake= floatval($myrow["estimated_revenue"]);
							$summtd_SUMPO_p = $summtd_SUMPO_p + $inv_amt_totake;
						}
					}

					if($link_sid>0)
					{
						$qry_s = "SELECT loop_transaction_buyer.po_employee, total_revenue, transaction_date, loop_warehouse.b2bid, loop_warehouse.company_name, inv_date_of, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.warehouse_id = '". $link_sid . "' ". $leaderboardQry ." AND loop_transaction_buyer.ignore < 1 order by loop_transaction_buyer.id";
						//
						$dt_view_res = db_query($qry_s  );
						while ($myrow = array_shift($dt_view_res)) 
						{
							$inv_amt_totake = floatval($myrow["total_revenue"]);
							$summtd_SUMPO = $summtd_SUMPO + $inv_amt_totake;
						}
					}
					
					$empName = '';
					if(isset($_REQUEST["empid"]) && $_REQUEST["empid"] != 'All'){
						if($_REQUEST["empid"] == 0){
							$empName = "(Inactive)";
						}else{
							$sqlemp = "SELECT name, status FROM loop_employees WHERE b2b_id='".$_REQUEST["empid"]."'";
							$resultemp = db_query($sqlemp);
							while ($myrowsel = array_shift($resultemp)) {
								if ($myrowsel["status"] != 'Active') {
									$empName = $myrowsel["name"] . "(Inactive)";
								} else {
									$empName = $myrowsel["name"];
								}
							}
						}
					}else{
						$sqlemp = "SELECT name, status FROM loop_employees WHERE b2b_id='".$data["assignedto"]."'";
						$resultemp = db_query($sqlemp);
						while ($myrowsel = array_shift($resultemp)) {
							if ($myrowsel["status"] != 'Active') {
								$empName = $myrowsel["name"] . "(Inactive)";
							} else {
								$empName = $myrowsel["name"];
							}
						}
					}
					
					tep_db_close();
					$acc_status = "";
					db_b2b();
					$qry_s = "SELECT name from status where id = '". $data['status'] . "'";
					$dt_view_res = db_query($qry_s);
					while ($myrow = array_shift($dt_view_res)) 
					{
						$acc_status = $myrow["name"];
					}
					
					//echo "tot_trans_p_flg = " . $tot_trans_p_flg . " | " . $data['I'] . " | " . $link_sid . "<br>";
					if ($tot_trans_p_flg == 1){
						
						//if ($tot_trans >0 || $tot_trans_p >0){
						if ($alternate_cnt == 0)
						{
							$bg_color = "#E4E4E4";
							$alternate_cnt = 1;
						}else{
							$bg_color = "#D0CECE";
							$alternate_cnt = 0;
						}
						
						echo '<tr><td bgcolor="'. $bg_color .'">'. $num +=1 ;
						echo '</td><td bgcolor="'. $bg_color .'">'. $empName ;
						echo '</td><td bgcolor="'.$bg_color .'"><a href="viewCompany.php?ID='.$data['I'].'" target="_blank">'.$nickname;
						echo '</a></td><td bgcolor="'. $bg_color .'">'.$data['C'];
						echo '</td><td bgcolor="'. $bg_color .'">'.$UCB_ZW_Rep;
						echo '</td><td bgcolor="'. $bg_color .'">'.$acc_status;
						echo '</td><td bgcolor="'. $bg_color .'">'.$industry;
						echo '</td><td bgcolor="'. $bg_color .'">'.$tot_trans;
						echo '</td><td bgcolor="'. $bg_color .'">$'.number_format($summtd_SUMPO,0);
						echo '</td><td bgcolor="'. $bg_color .'">'.$tot_trans_p;
						echo '</td><td bgcolor="'. $bg_color .'">$'.number_format($summtd_SUMPO_p,0);
						echo '</td><td bgcolor="'. $bg_color .'">'.$parentchild_txt;
						echo '</td><td bgcolor="'. $bg_color .'">';
							if ($data["UZWNS"] != "" ) { 
								echo "<b>UCBoxes Next Step:</b> " . $data["NS"];
								echo "<br><br><b>UCBZW Next Step:</b> " . $data["UZWNS"]; 
							}else{
								echo $data["NS"];
							}
						
						echo '</td><td bgcolor="';
						if (date('Y-m-d',strtotime($data["LD"])) == date('Y-m-d')) { echo "#00FF00"; 
						} elseif ( date('Y-m-d',strtotime($data["LD"])) < date('Y-m-d', strtotime("-90 days")) ) { echo "#FF0000"; 
						} else { echo $bg_color; }
						
						echo '">';
						if ($data["LD"]!="")  echo date('m/d/Y',strtotime($data["LD"])); 
						
						
						echo '</td>';
						
						if($data["UZWND"] != "" && $data["UZWND"] != "1969-12-31") {
						
							echo '<td align="center" bgcolor="'. $bg_color .'">';
							
							if ($data["UZWND"]!= "") {
								echo '<div style="';
								if($data["ND"] == date('Y-m-d')) { echo 'background:#00FF00'; 
								} elseif ($data["ND"] < date('Y-m-d') && $data["ND"] != "") { echo 'background:#FF0000'; 
								} else { echo 'background:'.  $bg_color; }
								echo '">';
								if ($data["LID"] > 0) if ($data["ND"]!="") echo "<b>UCBoxes Next Comm.:</b> " . date('m/d/Y',strtotime($data["ND"]));
								echo '</div><div style="';
								if ($data["UZWND"] == date('Y-m-d')) { echo 'background:#00FF00'; 
								} elseif ($data["UZWND"] < date('Y-m-d') && $data["UZWND"] != "") { echo 'background:#FF0000'; 
								} else { echo 'background:'.$bg_color; } 
								echo '">';
								if ($data["UZWND"]!="" ) echo "<br><br><b>UCBZW Next Comm.:</b> ". date('m/d/Y',strtotime($data["UZWND"]));
								echo '</div>';
							}
							echo '</td>';
							
						}else{
							
							if($data["ND"] == date("Y-m-d")) { $bgcolor_str = "#00FF00"; 
							}elseif($data["ND"] < date('Y-m-d') && $data["ND"] != "") { $bgcolor_str = "#FF0000"; 
							}else{ $bgcolor_str = $bg_color; }
							echo '<td bgcolor="'.$bgcolor_str.'" align="center">';
							if ($data["LID"] > 0) if ($data["ND"]!="") echo date('m/d/Y',strtotime($data["ND"]));
							echo '</td>';
							
							
						}
						
						echo '</tr>';
						
						$MGArray[] = array( 'empName' => $empName, 'companyID' => $data["I"], 'nickname' => $nickname, 
										'contactName' => $data["C"], 'UCB_ZW_Rep' => $UCB_ZW_Rep, 'statusName' => $acc_status, 
										'industry' => $industry, 'total_trans' => $tot_trans, 'lisoftrans' => $lisoftrans, 
										'summtd_SUMPO' => $summtd_SUMPO, 'total_trans_p' => $tot_trans_p, 'LID' => $data["LID"], 
										'summtd_SUMPO_p' => $summtd_SUMPO_p, 'parentchild_txt' => $parentchild_txt, 
										'NS' => $data["NS"], 'UZWNS' => $data["UZWNS"], 'LD' => $data["LD"], 'ND' => $data["ND"], 
										'UZWND' => $data["UZWND"]
										);
						
					}
					ob_flush();
				}
				$_SESSION['sortarrayn'] = $MGArray;
			}
		}	// if form submitted end here		
		?>    
			  </tbody>
			</table>
		</div>
		
	</div>
	<br>
</body>
</html>