<?php
ini_set("display_errors", "1"); 
error_reporting(E_ERROR);
require("inc/header_session.php");
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
?>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<title>B2B Lead Tracking Report</title>
	<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
	<link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
	<style type="text/css">
		.txtstyle_color {
			font-family: arial;
			font-size: 12;
			height: 16px;
			background: #ABC5DF;
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
			top: 5%;
			left: 10%;
			width: 60%;
			height: 90%;
			padding: 16px;
			border: 1px solid gray;
			background-color: white;
			z-index: 1002;
			overflow: auto;
		}
		
		.sttbl{
			font-family: arial;
			font-size: 12;
			font-weight: 700;
			color: #333333;
		}

		.sttbl_a_right{
			font-family: arial;
			font-size: 12;
			color: #333333;
			text-align:right;
		}
		
		.detailTbltitle{
			background-color:#D9F2FF;
		}
		
		.fntsmall{
			font-size:11px;
		}
	</style>
</head>
<script language="JavaScript">
	function f_getPosition(e_elemRef, s_coord) {

		var n_pos = 0,
			n_offset,

			e_elem = e_elemRef;



		while (e_elem) {

			n_offset = e_elem["offset" + s_coord];

			n_pos += n_offset;

			e_elem = e_elem.offsetParent;

		}



		e_elem = e_elemRef;

		while (e_elem != document.body) {

			n_offset = e_elem["scroll" + s_coord];

			if (n_offset && e_elem.style.overflow == 'scroll')

				n_pos -= n_offset;

			e_elem = e_elem.parentNode;

		}

		return n_pos;

	}

	function show_file_inviewer_pos(filename, formtype, ctrlnm) {

		var selectobject = document.getElementById(ctrlnm);

		var n_left = f_getPosition(selectobject, 'Left');

		var n_top = f_getPosition(selectobject, 'Top');



		document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center>" + formtype + "</center><br/> <embed src='" + filename + "' width='800' height='800'>";

		document.getElementById('light').style.display = 'block';



		document.getElementById('light').style.left = n_left - 400 + 'px';

		document.getElementById('light').style.top = n_top + 10 + 'px';

	}
</script>

<script language="JavaScript" src="inc/CalendarPopup.js"></script>
<script language="JavaScript" src="inc/general.js"></script>

<script language="JavaScript">
	document.write(getCalendarStyles());
</script>

<script language="JavaScript">
	var cal2xx = new CalendarPopup("listdiv");

	cal2xx.showNavigationDropdowns();

	var cal3xx = new CalendarPopup("listdiv");

	cal3xx.showNavigationDropdowns();
</script>

<link rel='stylesheet' type='text/css' href='one_style.css'>

<body>

	<?php include("inc/header.php"); ?>
	<div class="main_data_css">
		<div id="light" class="white_content"></div>
		<div id="fade" class="black_overlay"></div>
		<div class="dashboard_heading" style="float: left;">
			<div style="float: left;">
				B2B Lead Tracking Report
				<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
					<span class="tooltiptext">This report allows the user to see all company records by the date they were entered into the system, and seeing where they are at in the sales and purchasing processes respectively.</span>
				</div><br>
			</div>
		</div>
		<?php

		$time = strtotime(Date('Y-m-d'));
		
		$st_friday = $time;

		$st_friday_last = date('m/d/Y', strtotime('-6 days', $st_friday));

		$st_thursday_last = Date('m/d/Y');

		$in_dt_range = "no";

		if ($_REQUEST["date_from"] != "" && $_REQUEST["date_to"] != "") {

			$date_from_val = date("Y-m-d", strtotime($_REQUEST["date_from"]));

			$date_to_val_org = date("Y-m-d", strtotime($_REQUEST["date_to"]));

			$date_to_val = date("Y-m-d", strtotime('+1 day', strtotime($_REQUEST["date_to"])));

			$in_dt_range = "yes";

			$assignid = $_REQUEST["assignid"];
		} else {

			$date_from_val = date("Y-m-d", strtotime($st_friday_last));

			$date_to_val_org = date("Y-m-d", strtotime($st_thursday_last));

			$date_to_val = date("Y-m-d", strtotime('+1 day', strtotime($st_thursday_last)));

			$assignid = "all";
		}

		?>
		<form method="post" name="shippingtool" action="report_lead_tracking.php">
			<table border="0">
				<tr>
					<td>Date Range Selector:</td>
					<td>
						From:
						<input type="text" name="date_from" id="date_from" size="10" value="<?php echo isset($_GET["date_from"]) ? $_GET["date_from"] : date("m/d/Y", strtotime($date_from_val)); ?>">

						<a href="#" onclick="cal2xx.select(document.shippingtool.date_from,'dtanchor2xx','MM/dd/yyyy'); return false;" name="dtanchor2xx" id="dtanchor2xx"><img border="0" src="images/calendar.jpg"></a>
	
						<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
						To:

						<input type="text" name="date_to" id="date_to" size="10" value="<?php echo isset($_GET["date_to"]) ? $_GET["date_to"] : date("m/d/Y", strtotime($date_to_val_org)); ?>">

						<a href="#" onclick="cal3xx.select(document.shippingtool.date_to,'dtanchor3xx','MM/dd/yyyy'); return false;" name="dtanchor3xx" id="dtanchor3xx"><img border="0" src="images/calendar.jpg"></a>

					</td>
					<td>
						<select size="1" name="assignid">
							<option selected value="all">All</option>

							<?php

							$arr = explode(",", $row["assignedto"]);

							$qassign = "SELECT * FROM employees WHERE status='Active' order by name";
							db_b2b();
							$dt_view_res_assign = db_query($qassign);

							while ($res_assign = array_shift($dt_view_res_assign)) {

							?>

								<option <?php if (
											isset($_REQUEST["assignid"]) &&
											$_REQUEST["assignid"] == $res_assign["employeeID"]
										) {
											echo "selected";
										} ?> value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"] ?>

								<?php

							}

								?>

						</select>
					</td>
					<td>
						<select name="accountType">
							<option value="sales" <?php echo ($_REQUEST["accountType"] == "sales")? " selected" : "";?>>Sales</option>
							<option value="sourcing"<?php echo ($_REQUEST["accountType"] == "sourcing")? " selected" : "";?>>Sourcing</option>
						</select>
						&nbsp;&nbsp;
						Show only MM Lead?<input type="checkbox" name="chk_show_mm_lead" id="chk_show_mm_lead" value="Yes" <? if ($_REQUEST["chk_show_mm_lead"] == "Yes") {	echo " checked ";} ?> />
					</td>
					<td>
						<input type="submit" name="btntool" value="Search" />
						<input type="hidden" name="hd_pgpost" id="hd_pgpost" value="" />
					</td>
				</tr>
			</table>
		</form>

		<div><i>Note: Wait for <font color="red">Report</font> to complete, use the Sort option after the Report is completed. Rescue records are shown in <span style="background:#bcf5bc;">Green</span> </i></div>

		
	<?php

		//Rescue rec -----------------------------



		$tot_lead = 0;
		$tot_lead_str = "";

		//
		$mm_lead = "";

		if($_REQUEST["chk_show_mm_lead"] == "Yes") {

			$mm_lead = " and (howHear = 'MM Lead: LinkedIN' or howHear = 'MM Lead: Other' or howHear = 'MM Lead to be Qualified') ";
		}
			
		if ($assignid == "all") {
			$empqry = "";
		} else {

			$empqry = " and companyInfo.assignedto= $assignid ";
		}
		
		$accountType = "sales";
		$accountTypeQry = " and companyInfo.haveNeed = 'Need Boxes' ";
		$statusIds = array(3, 94, 95, 96, 97, 98, 99, 100, 101, 32, 50, 102, 103, 51, 56, 104, 105, 24, 43, 106, 52, 33, 31);
		$rowcolor = "#E4E4E4";
		
		if($_REQUEST['accountType'] == 'sourcing'){
			$accountType = "sourcing";
			$accountTypeQry = "and companyInfo.haveNeed = 'Have Boxes' ";
			$statusIds = array(65, 107, 108, 109, 44, 112, 110, 111, 70, 115, 73, 116, 117, 118, 119, 120, 38, 122, 123, 124, 55, 125, 126, 127, 121, 49, 113, 114, 33, 31);
			$rowcolor = "#bcf5bc";
		}
			
			
		echo '<H2>'.strtoupper($accountType). '</H2>';
		
		echo '<table width="450" border="0" cellspacing="1" cellpadding="1">';
		$total_cnt = 0;
		db_b2b();
		foreach($statusIds as $val){
			$query = "Select companyInfo.status, status.name, count(DISTINCT  companyInfo.ID) as cnt from companyInfo "; 
			$query .= "LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID ";
			$query .= " INNER JOIN status ON companyInfo.status = status.id WHERE ";
			$query .= " (companyInfo.dateCreated >= '" . $date_from_val . "' and companyInfo.dateCreated <= '" . $date_to_val . "') ";
			$query .= " and companyInfo.status=". $val . " ";
			$query .= $accountTypeQry . $empqry . $mm_lead . " GROUP BY companyInfo.status";
			
			//echo $query . "<br>";
			$rec_found = 0;
			$result = db_query($query);
			while ($row = array_shift($result)) {
				$rec_found = 1;
				echo '<tr bgcolor="#D9F2FF"><td class="sttbl">';
				echo $row["name"] .'</td><td class="sttbl_a_right">';
				echo $row["cnt"] .'</td>';
				echo '</tr>';
				$total_cnt = $total_cnt + $row["cnt"];
			}
			
			if ($rec_found == 0){
				$query = "Select status.name from status WHERE ";
				$query .= " status.id =". $val . " ";
				
				$result = db_query($query);
				while ($row = array_shift($result)) {
					echo '<tr bgcolor="#D9F2FF"><td class="sttbl">';
					echo $row["name"] .'</td><td class="sttbl_a_right">';
					echo '0</td>';
					echo '</tr>';
				}
			
			}
		}
		echo '<tr bgcolor="#D9F2FF"><td class="sttbl">';
		echo '<b>Total:</b></td><td class="sttbl_a_right"><b>';
		echo $total_cnt . '</b></td>';
		echo '</tr>';
		echo '</table><br>';
		
		foreach($statusIds as $val){
			
			db_b2b();
			$crm_db = "SELECT name FROM status where id = " . $val;
			$crm_result = db_query($crm_db);
			while ($crm_data = array_shift($crm_result)) {
				$status_txt = $crm_data["name"];
			}
			
			$x = "Select companyInfo.id AS I, companyInfo.howHear, companyInfo.loopid AS LID, companyInfo.dateCreated AS D, ";
			$x .= " companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.city AS CI, companyInfo.state AS ST, ";
			$x .= " companyInfo.next_step AS NS, companyInfo.last_contact_date AS LD, companyInfo.next_date AS ND, ";
			$x .= " employees.initials AS EI from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID ";

			//$x .= " inner join quote on quote.companyID = companyInfo.ID ";
			$x .= " WHERE companyInfo.dateCreated >= '". $date_from_val ."' and companyInfo.dateCreated <= '". $date_to_val ."' ";
			$x .= " AND companyInfo.status=". $val . " ";
			$x .= $accountTypeQry ;
			if($empqry != '') $x .= $empqry ;				
			if($mm_lead != '') $x .= $mm_lead ;				
			$x .= " GROUP BY companyInfo.id ";
			//echo $x;
			$xresult = db_query($x);
			if(tep_db_num_rows($xresult)>0){
				$total_rows = 0;
				echo '<table width="650" border="0" cellspacing="1" cellpadding="1">';
				echo '<tr class="detailTbltitle sttbl">';
				echo '<th colspan="9">'. $status_txt .'</th>';
				echo '</tr>';
				echo '<tr class="detailTbltitle fntsmall">';
				echo '<td align="center">Sr. No.</td>';
				echo '<td align="center">Assign To</td>';
				echo '<td align="center">Age</td>';
				echo '<td align="center">Company Name</td>';
				echo '<td align="center">Status</td>';
				echo '<td align="center">Lead Come From</td>';
				echo '<td align="center">Next Step</td>';
				echo '<td align="center">Last Communication</td>';
				echo '<td align="center">Next Communication</td>';
				echo '</tr>';
				
				while($data = array_shift($xresult)){
					$nickname = getnickname($data["CO"], $data["I"]);
					$internal_flg = "no";
					$lead_from_str = "";

					$crm_db = "SELECT companyID FROM CRM where companyID = " . $data["I"] . " and message like '%via internal landing page%'";
					db_b2b();
					$crm_result = db_query($crm_db);

					while ($crm_data = array_shift($crm_result)) {

						$internal_flg = "yes";
					}

					if ($data["howHear"] == "") {

						if ($internal_flg == "yes") {

							$lead_from_str = "Internal";
						} else {

							$lead_from_str = "External";
						}
					} else {

						if ($internal_flg == "yes") {

							$lead_from_str = "Internal: " . $data["howHear"];
						} else {

							$lead_from_str = "External: " . $data["howHear"];
						}
					}
					
					$user_inactive = "no";

					$crm_db = "SELECT initials FROM loop_employees where initials = '" . $data["EI"] . "' and status = 'Inactive'";
					db();
					$crm_result = db_query($crm_db);

					while ($crm_data = array_shift($crm_result)) {

						$user_inactive = "yes";
					}

					$assign_str = "";

					if ($data["EI"] == "" || $user_inactive == "yes" || $data["EI"] == "MM") {

						if ($data["EI"] == "") {

							$assign_str = "<font face='Arial, Helvetica, sans-serif' size='1' color='red'>Not Assign</font>";

							$tot_lead_not_assign = $tot_lead_not_assign + 1;
						} else {

							$assign_str = "<font face='Arial, Helvetica, sans-serif' size='1' color='red'>" . $data["EI"] . "</font>";
						}
					} else {

						$tot_lead_assign = $tot_lead_assign + 1;

						$assign_str = "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>" . $data["EI"] . "</font>";
					}
					if ($data["LD"] == "") {

						$contact_yes_no = "no";

						$last_contact_str = "<font face='Arial, Helvetica, sans-serif' size='1' color='red'>Not contacted</font>";
					} else {

						$contact_yes_no = "yes";

						$last_contact_str = "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>" . date('m/d/Y', strtotime($data["LD"])) . "</font>";
					}
					$total_rows = $total_rows + 1;
		?>
					<tr valign="middle">
						<td bgcolor="<?php echo $rowcolor ?>" align="center">
							<font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $total_rows ?></font>
						</td>



						<td bgcolor="<?php echo $rowcolor ?>" align="center">
							<font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $assign_str ?></font>
						</td>



						<td width="5%" bgcolor="<?php echo $rowcolor ?>">
							<font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo date_diff_new($data["D"], "NOW");?> Days</font>
						</td>

						<td width="21%" bgcolor="<?php echo $rowcolor ?>"><a target="_blank" href="viewCompany.php?ID=<?php echo $data["I"] ?>">
								<font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo $nickname; ?><?php if ($data["LID"] > 0) echo "</b>"; ?></font>
							</a></td>

						<td width="15%" bgcolor="<?php echo $rowcolor ?>" align="center">
							<font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $status_txt ?></font>
						</td>



						<td width="15%" bgcolor="<?php echo $rowcolor ?>" align="center">
							<font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $lead_from_str ?></font>
						</td>


						<td width="15%" bgcolor="<?php echo $rowcolor ?>" align="center">
							<font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo $data["NS"] ?><?php if ($data["LID"] > 0) echo "</b>"; ?></font>
						</td>



						<td width="10%" bgcolor="<?php echo $rowcolor ?>" align="center">
							<font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $last_contact_str ?></font>
						</td>



						<td width="10%" <?php if ($data["ND"] == date('Y-m-d')) { ?> bgcolor="#00FF00" <?php } elseif ($data["ND"] < date('Y-m-d') && $data["ND"] != "") { ?> bgcolor="#FF0000" <?php } else { ?> bgcolor="<?php echo $rowcolor ?>" <?php } ?> align="center">
							<font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($data["LID"] > 0) echo "<b>"; ?><?php if ($data["ND"] != "") echo date('m/d/Y', strtotime($data["ND"])); ?>

							</font>
						</td>

					</tr>
		<?php
					
				}
				
				echo '<tr class="detailTbltitle fntsmall">';
				echo '<td colspan="9" align="right">Total = '. $total_rows .'</td>';
				echo '</tr>';
				echo '</table><br/>';
				
			}
			//
		}
			
			
?>

	</div>
</body>

</html>