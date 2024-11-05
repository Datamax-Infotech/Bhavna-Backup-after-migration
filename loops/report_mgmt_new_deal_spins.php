<?php
require("inc/header_session.php");
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

db();
$pallet_str = "";
	/*$pallet_str = " and Leaderboard = 'B2B' ";
	if(isset($_REQUEST["pallet_flg"]) && $_REQUEST["pallet_flg"] == "yes"){
		$pallet_str = " and Leaderboard = 'PALLETS' ";
	}	
		*/

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>New Deal Spin Summary Report - UCB Sales Review Report</title>
<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet"> 
<LINK rel='stylesheet' type='text/css' href='css/ucb_common_style.css' >
<meta http-equiv="refresh" content="1800" />	
<link rel="stylesheet" href="sorter/style_rep.css" />
<style type="text/css">

.txtstyle_color
{
font-family:arial;
font-size:12;
height: 16px; 
background:#ABC5DF;
}

.txtstyle
{
	font-family:arial;
	font-size:12;
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
select, input {
font-family: Arial, Helvetica, sans-serif; 
font-size: 10px; 
color : #000000; 
font-weight: normal; 
}

	span.infotxt:hover {text-decoration: none; background: #ffffff; z-index: 6; }
	span.infotxt span {position: absolute; left: -9999px; margin: 20px 0 0 0px; padding: 3px 3px 3px 3px; z-index: 6;}
	span.infotxt:hover span {left: 45%; background: #ffffff;} 
	span.infotxt span {position: absolute; left: -9999px; margin: 0px 0 0 0px; padding: 3px 3px 3px 3px; border-style:solid; border-color:black; border-width:1px;}
	span.infotxt:hover span {margin: 18px 0 0 170px; background: #ffffff; z-index:6;} 
	
	span.infotxt_freight:hover {text-decoration: none; background: #ffffff; z-index: 6; }
	span.infotxt_freight span {position: absolute; left: -9999px; margin: 20px 0 0 0px; padding: 3px 3px 3px 3px; z-index: 6;}
	span.infotxt_freight:hover span {left: 0%; background: #ffffff;} 
	span.infotxt_freight span {position: absolute; width:850px; overflow:auto; height:300px; left: -9999px; margin: 0px 0 0 0px; padding: 10px 10px 10px 10px; border-style:solid; border-color:white; border-width:50px;}
	span.infotxt_freight:hover span {margin: 5px 0 0 50px; background: #ffffff; z-index:6;} 
	
	span.infotxt_freight2:hover {text-decoration: none; background: #ffffff; z-index: 6; }
	span.infotxt_freight2 span {position: absolute; left: -9999px; margin: 20px 0 0 0px; padding: 3px 3px 3px 3px; z-index: 6;}
	span.infotxt_freight2:hover span {left: 0%; background: #ffffff;} 
	span.infotxt_freight2 span {position: absolute; width:850px; overflow:auto; height:300px; left: -9999px; margin: 0px 0 0 0px; padding: 10px 10px 10px 10px; border-style:solid; border-color:white; border-width:50px;}
	span.infotxt_freight2:hover span {margin: 5px 0 0 500px; background: #ffffff; z-index:6;} 

	.black_overlay{
		display: none;
		position: absolute;
	}
	.white_content {
		display: none;
		position: absolute;
		padding: 5px;
		border: 2px solid black;
		background-color: white;
		overflow:auto;
		height:600px;
		width:850px;
		z-index:1002;
		margin: 0px 0 0 0px; 
		padding: 10px 10px 10px 10px;
		border-color:black; 
		border-width:2px;
		overflow: auto;
	}
	

</style>	
	<script type="text/javascript" src="sorter/jquery-latest.js"></script>
	<script type="text/javascript" src="sorter/jquery.tablesorter.js"></script>

	<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT><SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
	<script LANGUAGE="JavaScript">document.write(getCalendarStyles());</script>
	<script LANGUAGE="JavaScript">
		var cal2xx = new CalendarPopup("listdiv");
		cal2xx.showNavigationDropdowns();
		
		function loadmainpg() 
		{
			if(document.getElementById('date_from').value !="" && document.getElementById('date_to').value !="")
			{
				  //document.frmactive.action = "adminpg.php";
			}
			else
			{
				  alert("Please select date From/To.");
				  return false;
			}
		}
		
		function load_div(id){
			//var gpos = getAbsPosition(document.getElementById(id)); 			

			var element = document.getElementById(id); //replace elementId with your element's Id.
			var rect = element.getBoundingClientRect();
			var elementLeft,elementTop; //x and y
			var scrollTop = document.documentElement.scrollTop?
							document.documentElement.scrollTop:document.body.scrollTop;
			var scrollLeft = document.documentElement.scrollLeft?                   
							 document.documentElement.scrollLeft:document.body.scrollLeft;
			elementTop = rect.top+scrollTop;
			elementLeft = rect.left+scrollLeft;
		
			document.getElementById("light").innerHTML = document.getElementById(id).innerHTML;
			document.getElementById('light').style.display='block';
			document.getElementById('fade').style.display='block';

			document.getElementById('light').style.left='100px';
			document.getElementById('light').style.top=elementTop + 100 + 'px';
		
			/*alert(document.getElementById(id).style.display);
			if (document.getElementById(id).style.display == "none"){
				document.getElementById(id).style.display = 'block';
			}else{
				document.getElementById(id).style.display = 'none';
			}*/
		}
		
		
		function close_div(){
			document.getElementById('light').style.display='none';
		}
	</script>

</head>

<body>
<?php include("inc/header.php"); ?>
	<div id="light" class="white_content">
	</div>
	<div id="fade" class="black_overlay"></div>

<div class="main_data_css">

	<div class="dashboard_heading" style="float: left;">
		<div style="float: left;">
			New Deal Spin Summary Report

		<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
		<span class="tooltiptext">This report shows the user all of the new deals closed and paid in full by customers. The report will then convert all of the deals into spins based on order size. Only 1st transactions counts for each customer.</span></div><br>
		</div>

	</div>
<!--  	
<table border="0" >
	<tr><td width="700px" align="center" style="font-size:24pt;"><strong>UCB Sales Review Report</strong></td>
	<td width="200px" align="right"><img src="images/image001.jpg" width="70" height="70"/></td></tr>
</table >
-->
	<form method="get" name="rpt_leaderboard" action="report_mgmt_new_deal_spins.php">
		<table border="0">
			<tr>
				<td>Date Range Selector:</td>
				<td>
						From: 
							<input type="text" name="date_from" id="date_from" size="10" value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : ''; ?>" > 
							<a href="#" onclick="cal2xx.select(document.rpt_leaderboard.date_from,'dtanchor2xx','MM/dd/yyyy'); return false;" name="dtanchor2xx" id="dtanchor2xx"><img border="0" src="images/calendar.jpg"></a>
							<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>		
						To: 
							<input type="text" name="date_to" id="date_to" size="10" value="<?php echo isset($_GET['date_to']) ? $_GET['date_to'] : ''; ?>" > 
							<a href="#" onclick="cal2xx.select(document.rpt_leaderboard.date_to,'dtanchor3xx','MM/dd/yyyy'); return false;" name="dtanchor3xx" id="dtanchor3xx"><img border="0" src="images/calendar.jpg"></a>
							<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>		
				</td>
				<td>
					<input type=submit value="Run Report">
				</td>
			</tr>
		</table>
	</form>

<?php
	$tot_cnt_thismonth_s = 0; $tot_cnt_lastmonth = 0;  $spinmonth_arr_cnt = array(); $tot_cnt_thismonth_all_s = 0; $tot_cnt_month_all_s = 0;
	$tot_cnt_month_s1 = 0; $tot_cnt_month_s2 = 0; $tot_cnt_month_s3 = 0; $tot_cnt_month_s4 = 0; $tot_cnt_month_s5 = 0;
	$tot_cnt_month_s6 = 0; $tot_cnt_month_s7 = 0; $tot_cnt_month_s8 = 0; $tot_cnt_month_s9 = 0; $tot_cnt_month_s10 = 0;
	$tot_cnt_month_s11 = 0; $tot_cnt_month_s12 = 0;
	$sr_last_s = 1; $sr_last_less_s = 1;
	$sr_s1 = 1;$sr_s2 = 1;$sr_s3 = 1;$sr_s4 = 1;$sr_s5 = 1;$sr_s6 = 1;$sr_s7 = 1;$sr_s8 = 1;$sr_s9 = 1;
	$sr_s10 = 1;$sr_s11 = 1;$sr_s12 = 1;$sr_less_s1 = 1;$sr_less_s2 = 1;$sr_less_s3 = 1;$sr_less_s4 = 1;$sr_less_s5 = 1;
	$sr_less_s6 = 1;$sr_less_s7 = 1;$sr_less_s8 = 1;$sr_less_s9 = 1;$sr_less_s10 = 1;$sr_less_s11 = 1;$sr_less_s12 = 1;
	$lisofdetails_s1 = ""; $lisofdetails_s2 = ""; $lisofdetails_s3 = ""; $lisofdetails_s4 = ""; $lisofdetails_s5 = ""; 
	$lisofdetails_s6 = ""; $lisofdetails_s7 = ""; $lisofdetails_s8 = ""; $lisofdetails_s9 = ""; $lisofdetails_s10 = ""; 
	$lisofdetails_s11 = ""; $lisofdetails_s12 = ""; $lisofdetails_s13 = ""; $lisofdetails_s14 = ""; $lisofdetails_s15 = ""; $total_bonus=0; 

	$lisofdetails_s1 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s1 .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

	$lisofdetails_s2 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s2 .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

	$lisofdetails_s3 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s3 .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

	$lisofdetails_s4 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s4 .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

	$lisofdetails_s5 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s5 .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

	$lisofdetails_s6 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s6 .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

	$lisofdetails_s7 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s7 .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

	$lisofdetails_s8 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s8 .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

	$lisofdetails_s9 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s9 .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

	$lisofdetails_s10 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s10 .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

	$lisofdetails_s11 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s11 .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

	$lisofdetails_s12 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s12 .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
    //
    $lisofdetails_s1_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s1_less .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

	$lisofdetails_s2_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s2_less .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

	$lisofdetails_s3_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s3_less .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

	$lisofdetails_s4_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s4_less .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

	$lisofdetails_s5_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s5_less .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

	$lisofdetails_s6_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s6_less .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

	$lisofdetails_s7_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s7_less .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

	$lisofdetails_s8_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s8_less .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

	$lisofdetails_s9_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s9_less .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

	$lisofdetails_s10_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s10_less .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

	$lisofdetails_s11_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s11_less .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

	$lisofdetails_s12_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails_s12_less .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
    //
    //
	$lisofdetails1all_last_s = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	$lisofdetails1all_last_s .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
    //
        $lisofdetails1all_last_s_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
	   $lisofdetails1all_last_s_less .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
    //
	
	if (isset($_REQUEST["date_from"])){
?>
	<table cellSpacing="1" cellPadding="1" border="0" width="1000">
		<tr>
			<td bgColor='#EAF1DD' align="center" colspan="2"><strong>New Deal Spins (No. of Deals >= 2,000 and 2 New Deals = 1 Spin)</strong></td>
		</tr>
		<tr>
			<td bgColor='#EAF1DD' width="300"><strong><u>Employee</u></strong></td>
			<td bgColor='#EAF1DD' width="80"><strong><u><center><?php echo $_REQUEST["date_from"] . " - " . $_REQUEST["date_to"];?></center></u></strong></td>
		</tr>

<?php	
		$date_from = date("Y-m-d", strtotime($_REQUEST["date_from"]));
		$date_to = date("Y-m-d", strtotime($_REQUEST["date_to"]));
		if(isset($_REQUEST["pallet_flg"]) && $_REQUEST["pallet_flg"] == "yes"){
			$sql = "SELECT * FROM loop_employees WHERE pallet_leaderboard = 1 and status = 'Active' ORDER BY quota DESC";
		}else{
			$sql = "SELECT * FROM loop_employees WHERE leaderboard = 1 and status = 'Active' ORDER BY quota DESC";
		}		
    //echo $sql;	
    db();
		$result = db_query($sql);
		
		
		while ($rowemp = array_shift($result))
		{
			$initials = $rowemp["initials"];
     		 //echo $initials. "-------------<br>";
			$total_bonus_all=0;
			?>
			<tr><td bgColor='#EAF1DD'><?php echo $rowemp["name"]; ?></td>
			<?php 
				$tot_cnt_month_s = 0; $tot_cnt_month_all_s = 0;$amt_s_all_less=0; $amt_s_all=0;
				
				$lisofdetails1all_s = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
				$lisofdetails1all_s .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
				
				$tot_cnt_thismonth_s = 0; $total_bonus=0;
				$tot_cnt_month_s = $tot_cnt_month_s + 1;
				
				$tmpnewdt = date("Y-m-t" , strtotime(date('Y', strtotime($tmp_mpnth)) . "-" . date('m', strtotime($tmp_mpnth)) . "-01"));
		
				$lisofdetails_s = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
				$lisofdetails_s .= "<tr><td class='txtstyle_color' colspan=4><b>New Deals >= $2,000</b></td></tr>";
				$lisofdetails_s .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
				//
				$lisofdetails_s_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
				$lisofdetails_s_less .= "<tr><td class='txtstyle_color' colspan=4><b>New Deals < $2,000 (Not Part of Spin Calculation)</b></td></tr>";
				$lisofdetails_s_less .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

				$dt_view_qry = "SELECT distinct loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name, po_poorderamount AS SUMPO FROM loop_transaction_buyer 
				INNER JOIN loop_buyer_payments on loop_buyer_payments.trans_rec_id = loop_transaction_buyer.id INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id where `ignore` = 0 
				and no_invoice = 0 and po_employee = '$initials'  $pallet_str and (loop_buyer_payments.inv_receiptdate between '" . $date_from . "' and '" . $date_to . "')";
			
				//echo $dt_view_qry . "<br>";
				$amt_s = 0; $first_rec_cnt_s =0;$first_rec_cnt_spin=0;$amt_s_less = 0;
       			db();
				$dt_view_res = db_query($dt_view_qry);
				
				$sr_s1_all_dt = 1; $sr_less_s1_all_dt = 1;
				while ($dt_view_row = array_shift($dt_view_res)) {
					//and po_employee = '$initials'
					$fdq = "SELECT id AS I FROM loop_transaction_buyer WHERE warehouse_id = " . $dt_view_row["warehouse_id"] . " and `ignore` = 0 
					and no_invoice = 0 ORDER BY I asc LIMIT 0,1";
					$fd_res = db_query($fdq );
					$first_deal_s = 0;
					while ($fd_row = array_shift($fd_res)) {
						if ($fd_row["I"] == $dt_view_row["id"]){
							//if($dt_view_row["SUMPO"]>= 2000)
						  //  {
								$first_deal_s = 1;
						   // }
							break;
						}
					} 
					
					/*$first_deal_s = 0;
					$fdq = "SELECT id AS I, po_employee FROM loop_transaction_buyer WHERE warehouse_id = " . $dt_view_row["warehouse_id"] . " and `ignore` = 0 and no_invoice = 0 ORDER BY I ASC";
					//echo $fdq . "<br>";
					$fd_res = db_query($fdq);
					$gCount = tep_db_num_rows($fd_res);
					if ($gCount == 1){
						while ($fd_row = array_shift($fd_res)) {
							if ($fd_row["po_employee"] == $initials)
							{
								$first_deal_s = 1;
							}
						}
					}*/
					
					/*while ($dt_view_row1 = array_shift($dt_view_res)) {
						
							$fdq2 = "SELECT id AS I FROM loop_transaction_buyer WHERE warehouse_id = " . $dt_view_row["warehouse_id"] . " and `ignore` = 0 and no_invoice = 0 ORDER BY I ASC LIMIT 0,1";
							$fd_res = db_query($fdq2,db() );
							$first_deal_s = 0; $first_deal_id = 0;
							while ($fd_row = array_shift($fd_res)) {
								if ($fd_row["I"] == $dt_view_row1["I"]){
									$first_deal_s = 1;
									$first_deal_id = $dt_view_row1["I"];
									break;
								}
							} 
					}*/						
						
					
					
					if ($first_deal_s == 1)
					{
						//echo $fdq . " " . $dt_view_row["SUMPO"] . "<br>";
						if($dt_view_row["SUMPO"]>= 2000)
						{
						  //$amt_s = $amt_s + $dt_view_row["SUMPO"];
						  $first_rec_cnt_spin = $first_rec_cnt_spin + 1;
						}
						
						$tot_onroad_s = $tot_onroad_s + $dt_view_row["SUMPO"];
						$first_rec_cnt_s = $first_rec_cnt_s + 1;
						$tot_cnt_thismonth_s = $tot_cnt_thismonth_s + 1;
						$tot_cnt_thismonth_all_s = $tot_cnt_thismonth_all_s + 1;
						$tot_cnt_month_all_s = $tot_cnt_month_all_s + 1;
						
						$nickname = "";
						if ($dt_view_row["b2bid"] > 0) {
							db_b2b();
							$sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $dt_view_row["b2bid"];
							$result_comp = db_query($sql);
							while ($row_comp = array_shift($result_comp)) {
								if ($row_comp["nickname"] != "") {
									$nickname = $row_comp["nickname"];
								}else {
									$tmppos_1 = strpos($row_comp["company"], "-");
									if ($tmppos_1 != false)
									{
										$nickname = $row_comp["company"];
									}else {
										if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "" ) 
										{
											$nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"] ;
										}else { $nickname = $row_comp["company"]; }
									}
								}
							}
							db();
						}else {
							$nickname = $dt_view_row["warehouse_name"];
						}
						 if($dt_view_row["SUMPO"]>= 2000)
						{
							$lisofdetails_s .= "<tr><td bgColor='#E4EAEB'> ".($sr_s1_all_dt++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";

							$lisofdetails1all_s .= "<tr><td bgColor='#E4EAEB'>&nbsp;</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";

							$lisofdetails1all_last_s .= "<tr><td bgColor='#E4EAEB'>".($sr_last_s++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
							$amt_s = $amt_s + $dt_view_row["SUMPO"];
							$amt_s_all= $amt_s_all + $dt_view_row["SUMPO"];
							$amt_s_lastall= $amt_s_lastall + $dt_view_row["SUMPO"];
						}
						else{
							$lisofdetails_s_less .= "<tr><td bgColor='#E4EAEB'>".($sr_less_s1_all_dt++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";

							$lisofdetails1all_s_less .= "<tr><td bgColor='#E4EAEB'>&nbsp;</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";

							$lisofdetails1all_last_s_less .= "<tr><td bgColor='#E4EAEB'>".($sr_last_less_s++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
							//
							$amt_s_less = $amt_s_less + $dt_view_row["SUMPO"];
							$amt_s_all_less= $amt_s_all_less + $dt_view_row["SUMPO"];
							$amt_s_lastall_less= $amt_s_lastall_less + $dt_view_row["SUMPO"];
						}
						
						
						if ($tot_cnt_month_s == 1){
							if($dt_view_row["SUMPO"]>= 2000)
							{
								$lisofdetails_s1 .= "<tr><td bgColor='#E4EAEB'></td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
								//
								$month_total_amount1=$month_total_amount1 + $dt_view_row["SUMPO"];
							}
							else{
								$lisofdetails_s1_less .= "<tr><td bgColor='#E4EAEB'></td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
								$month_total_amount1_less=$month_total_amount1_less + $dt_view_row["SUMPO"];
							}
							
						}
					}
				}
				//
				 $bonus=($first_rec_cnt_spin - $first_rec_cnt_spin % 2) / 2;
				//
				if ($amt_s > 0){
					$lisofdetails_s .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt_s,0) . "</td></tr>";
				}
				 if ($amt_s_less > 0){
					//
					$lisofdetails_s_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt_s_less,0) . "</td></tr>";
				}
				$lisofdetails_s .= "</table></span>";
				$lisofdetails_s_less .= "</table></span>";
				$unqid = $unqid + 1;
				  //
				$total_bonus_all=$bonus+$total_bonus_all;
				$total_bonus=$bonus+$total_bonus;
				//
				if ($amt_s <= 0){
					$lisofdetails_s="";
				}
				if($amt_s_less <= 0){
					$lisofdetails_s_less="";
				}
				//  
				echo "<td bgColor='#EAF1DD' align='right'><a href='#' onclick='load_div(". $unqid . $rowemp["id"] . "); return false;'>" .$bonus. "&nbsp;&nbsp;<strong>(".$first_rec_cnt_s.")</strong></font></a>";//first_rec_cnt_s
				echo "<span id='". $unqid . $rowemp["id"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisofdetails_s . "<br>".$lisofdetails_s_less."</span></td>";
				
				if ($tot_cnt_month_s == 1){
					$tot_cnt_month_s1 = $tot_cnt_month_s1 + $tot_cnt_thismonth_s;
					$total_month_bonus1=$total_bonus+$total_month_bonus1;
				}
					
			if ($amt_s_all > 0){
				$lisofdetails1all_s .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt_s_all,0) . "</td></tr>";
						
			}
			if ($amt_s_all_less > 0){
				//
				$lisofdetails1all_s_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt_s_all_less,0) . "</td></tr>";
			}
			
				$lisofdetails1all_s .= "</table></span>";
				$lisofdetails1all_s_less .= "</table></span>";
				
			 //
			?>
			</tr>
			
		<?php }
			
		?>
		 </tr>

		<?php
			 if ($month_total_amount1 > 0){
				$lisofdetails_s1 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount1,0) . "</td></tr>";
						
			}
			if ($month_total_amount1_less > 0){
				//
				$lisofdetails_s1_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount1_less,0) . "</td></tr>";
			}
		
			$lisofdetails_s1 .= "</table></span>";
			//
			$lisofdetails_s1_less .= "</table></span>";
			//
	?>		
			<tr>
				<td bgColor='#EAF1DD' align="right"><strong>Total</strong></td>
				<td bgColor='#EAF1DD' align='right'><a href='#' onclick='load_div(988888); return false;'><strong>
					<?php echo $total_month_bonus1."&nbsp;&nbsp;(".number_format($tot_cnt_month_s1,0).")"; ?>
					</strong></font></a>
				<span id='988888' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>
					<?php echo $lisofdetails_s1; ?><br><?php echo $lisofdetails_s1_less; ?></span></td>

				<?php
					if ($amt_s_lastall > 0){
						//
						$lisofdetails1all_last_s .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt_s_lastall,0) . "</td></tr>";
					}
					if ($amt_s_lastall_less > 0){
						//
						$lisofdetails1all_last_s_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt_s_lastall_less,0) . "</td></tr>";
					}
			//
					$lisofdetails1all_last_s .= "</table></span>";	
					$lisofdetails1all_last_s_less .= "</table></span>";
			 $total_spins_all_month=$total_month_bonus1+$total_month_bonus2+$total_month_bonus3+$total_month_bonus4+$total_month_bonus5+$total_month_bonus6+$total_month_bonus7+$total_month_bonus8+$total_month_bonus9+$total_month_bonus10+$total_month_bonus11+$total_month_bonus12;
				?>
				
			</tr>
<?php
	//date range
	}else{	?>
	
		<table cellSpacing="1" cellPadding="1" border="0" width="1000">
		<tr>
			<td bgColor='#EAF1DD' align="center" colspan="14"><strong>New Deal Spins (No. of Deals >= 2,000 and 2 New Deals = 1 Spin)</strong></td>
		</tr>
		<tr>
			<td bgColor='#EAF1DD' width="300"><strong><u>Employee</u></strong></td>
			<?php	for ($spindeal_month = 11; $spindeal_month > 0; $spindeal_month--) {
					$spinmonth_arr[] = date("Y-m-1", strtotime( date( 'Y-m-01' )." -$spindeal_month months"));
			?>
				<td bgColor='#EAF1DD' width="80"><strong><u><center><?php echo date("m/Y", strtotime( date( 'Y-m-01' )." -$spindeal_month months"));?></center></u></strong></td>
			<?php }	?>
			<?php 
				$spinmonth_arr[] = date("Y-m-1");
			?>
				<td bgColor='#EAF1DD' width="80"><strong><u><center><?php echo date("m/Y", strtotime(date( 'Y-m-01' )));?></center></u></strong></td>			
			<td bgColor='#EAF1DD' width="80"><strong><u><center>Total</center></u></strong></td>			
		</tr>
<?php		
		/*
		if(isset($_REQUEST["pallet_flg"]) && $_REQUEST["pallet_flg"] == "yes"){
			$sql = "SELECT * FROM loop_employees WHERE pallet_leaderboard = 1 and status = 'Active' ORDER BY quota DESC";
		}else{
			$sql = "SELECT * FROM loop_employees WHERE leaderboard = 1 and status = 'Active' ORDER BY quota DESC";
		}
			*/
			$sql = "SELECT * FROM loop_employees WHERE  status = 'Active' ORDER BY quota DESC";
		
    db();
		$result = db_query($sql);
		while ($rowemp = array_shift($result)) {
			$initials = $rowemp["initials"];
			$total_bonus_all=0;
			$sr_s1_all = 1;  $sr_less_s1_all = 1;
	?>
			<tr><td bgColor='#EAF1DD'><?php echo $rowemp["name"]; ?></td>
			<?php 
				$tot_cnt_month_s = 0; $tot_cnt_month_all_s = 0;$amt_s_all_less=0; $amt_s_all=0;

				$lisofdetails1all_s = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
				$lisofdetails1all_s .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
				
				//
				$lisofdetails1all_s_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
				$lisofdetails1all_s_less .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

				foreach($spinmonth_arr as $tmp_mpnth) {
					$tot_cnt_thismonth_s = 0; $total_bonus=0;
					$tot_cnt_month_s = $tot_cnt_month_s + 1;
					
					$tmpnewdt = date("Y-m-t" , strtotime(date('Y', strtotime($tmp_mpnth)) . "-" . date('m', strtotime($tmp_mpnth)) . "-01"));
			
					$lisofdetails_s = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
					$lisofdetails_s .= "<tr><td class='txtstyle_color' colspan=4><b>New Deals >= $2,000</b></td></tr>";
					$lisofdetails_s .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
					//
					$lisofdetails_s_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
					$lisofdetails_s_less .= "<tr><td class='txtstyle_color' colspan=4><b>New Deals < $2,000 (Not Part of Spin Calculation)</b></td></tr>";
					$lisofdetails_s_less .= "<tr><td class='txtstyle_color'>SrNo</td><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

					$dt_view_qry = "SELECT distinct loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name, po_poorderamount AS SUMPO 
					FROM loop_transaction_buyer INNER JOIN loop_buyer_payments on loop_buyer_payments.trans_rec_id = loop_transaction_buyer.id INNER JOIN loop_warehouse 
					ON loop_transaction_buyer.warehouse_id = loop_warehouse.id where `ignore` = 0 and no_invoice = 0 
					and po_employee = '$initials'  $pallet_str and (loop_buyer_payments.inv_receiptdate between '" . $tmp_mpnth . "' and '" . $tmpnewdt . "')";
					//echo $dt_view_qry . "<br>";
					$amt_s = 0; $first_rec_cnt_s =0; $first_rec_cnt_spin=0;$amt_s_less = 0;
					$sr_less_s = 1; $sr_s = 1; 
          			db();
					$dt_view_res = db_query($dt_view_qry);
					while ($dt_view_row = array_shift($dt_view_res)) {

						/*
						$fdq = "SELECT id AS I FROM loop_transaction_buyer WHERE warehouse_id = " . $dt_view_row["warehouse_id"] . " and `ignore` = 0 and no_invoice = 0 and po_employee = '$initials' ORDER BY I ASC LIMIT 0,1";
						$fd_res = db_query($fdq );
						$first_deal_s = 0;
						while ($fd_row = array_shift($fd_res)) {
							if ($fd_row["I"] == $dt_view_row["id"]){
								//if($dt_view_row["SUMPO"]>= 2000)
							  //  {
									$first_deal_s = 1;
							   // }
								break;
							}
						} 
						*/
						$first_deal_s = 0;
						$fdq = "SELECT id AS I, po_employee FROM loop_transaction_buyer WHERE warehouse_id = " . $dt_view_row["warehouse_id"] . " and `ignore` = 0 and no_invoice = 0 ORDER BY I ASC";
						$fd_res = db_query($fdq);
						$gCount = tep_db_num_rows($fd_res);
						if ($gCount == 1){
							while ($fd_row = array_shift($fd_res)) {
								if ($fd_row["po_employee"] == $initials)
								{
									$first_deal_s = 1;
								}
							}
						}
						
						if ($first_deal_s == 1)
						{
							 if($dt_view_row["SUMPO"]>= 2000)
							{
							  //$amt_s = $amt_s + $dt_view_row["SUMPO"];
							  $first_rec_cnt_spin = $first_rec_cnt_spin + 1;
							}
							
							$tot_onroad_s = $tot_onroad_s + $dt_view_row["SUMPO"];
							$first_rec_cnt_s = $first_rec_cnt_s + 1;
							$tot_cnt_thismonth_s = $tot_cnt_thismonth_s + 1;
							$tot_cnt_thismonth_all_s = $tot_cnt_thismonth_all_s + 1;
							$tot_cnt_month_all_s = $tot_cnt_month_all_s + 1;
							
							$nickname = "";
							if ($dt_view_row["b2bid"] > 0) {
								db_b2b();
								$sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $dt_view_row["b2bid"];
								$result_comp = db_query($sql);
								while ($row_comp = array_shift($result_comp)) {
									if ($row_comp["nickname"] != "") {
										$nickname = $row_comp["nickname"];
									}else {
										$tmppos_1 = strpos($row_comp["company"], "-");
										if ($tmppos_1 != false)
										{
											$nickname = $row_comp["company"];
										}else {
											if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "" ) 
											{
												$nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"] ;
											}else { $nickname = $row_comp["company"]; }
										}
									}
								}
								db();
							}else {
								$nickname = $dt_view_row["warehouse_name"];
							}
							 if($dt_view_row["SUMPO"]>= 2000)
							{
								$lisofdetails_s .= "<tr><td bgColor='#E4EAEB'>".($sr_s++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";

								$lisofdetails1all_s .= "<tr><td bgColor='#E4EAEB'>".($sr_s1_all++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";

								$lisofdetails1all_last_s .= "<tr><td bgColor='#E4EAEB'>".($sr_last_s++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
								$amt_s = $amt_s + $dt_view_row["SUMPO"];
								$amt_s_all= $amt_s_all + $dt_view_row["SUMPO"];
								$amt_s_lastall= $amt_s_lastall + $dt_view_row["SUMPO"];
							}
							else{
								$lisofdetails_s_less .= "<tr><td bgColor='#E4EAEB'>".($sr_less_s++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";

								$lisofdetails1all_s_less .= "<tr><td bgColor='#E4EAEB'>".($sr_less_s1_all++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";

								$lisofdetails1all_last_s_less .= "<tr><td bgColor='#E4EAEB'>".($sr_last_less_s++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
								//
								$amt_s_less = $amt_s_less + $dt_view_row["SUMPO"];
								$amt_s_all_less= $amt_s_all_less + $dt_view_row["SUMPO"];
								$amt_s_lastall_less= $amt_s_lastall_less + $dt_view_row["SUMPO"];
							}
							
							
							if ($tot_cnt_month_s == 1){
								if($dt_view_row["SUMPO"]>= 2000)
								{
									$lisofdetails_s1 .= "<tr><td bgColor='#E4EAEB'>".($sr_s1++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									//
									$month_total_amount1=$month_total_amount1 + $dt_view_row["SUMPO"];
								}
								else{
									$lisofdetails_s1_less .= "<tr><td bgColor='#E4EAEB'>".($sr_less_s1++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount1_less=$month_total_amount1_less + $dt_view_row["SUMPO"];
								}
								
							}
							if ($tot_cnt_month_s == 2){
								if($dt_view_row["SUMPO"]>= 2000)
								{
									$lisofdetails_s2 .= "<tr><td bgColor='#E4EAEB'>".($sr_s2++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount2=$month_total_amount2 + $dt_view_row["SUMPO"];
								}
								else{
									$lisofdetails_s2_less .= "<tr><td bgColor='#E4EAEB'>".($sr_less_s2++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount2_less=$month_total_amount2_less + $dt_view_row["SUMPO"];
								}
									
							}
							if ($tot_cnt_month_s == 3){
								if($dt_view_row["SUMPO"]>= 2000)
								{
									$lisofdetails_s3 .= "<tr><td bgColor='#E4EAEB'>".($sr_s3++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount3=$month_total_amount3 + $dt_view_row["SUMPO"];
								}
								else{
									$lisofdetails_s3_less .= "<tr><td bgColor='#E4EAEB'>".($sr_less_s3++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount3_less=$month_total_amount3_less + $dt_view_row["SUMPO"];
								}
							}
							if ($tot_cnt_month_s == 4){
								if($dt_view_row["SUMPO"]>= 2000)
								{
								$lisofdetails_s4 .= "<tr><td bgColor='#E4EAEB'>".($sr_s4++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount4=$month_total_amount4 + $dt_view_row["SUMPO"];
								}
								else{
									$lisofdetails_s4_less .= "<tr><td bgColor='#E4EAEB'>".($sr_less_s4++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount4_less=$month_total_amount4_less + $dt_view_row["SUMPO"];
								}
							}
							if ($tot_cnt_month_s == 5){
								if($dt_view_row["SUMPO"]>= 2000)
								{
									$lisofdetails_s5 .= "<tr><td bgColor='#E4EAEB'>".($sr_s5++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount5=$month_total_amount5 + $dt_view_row["SUMPO"];
								}
								else{
									$lisofdetails_s5_less .= "<tr><td bgColor='#E4EAEB'>".($sr_less_s5++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount5_less=$month_total_amount5_less + $dt_view_row["SUMPO"];
								}
							}
							if ($tot_cnt_month_s == 6){
								if($dt_view_row["SUMPO"]>= 2000)
								{
									$lisofdetails_s6 .= "<tr><td bgColor='#E4EAEB'>".($sr_s6++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount6=$month_total_amount6 + $dt_view_row["SUMPO"];
								}
								else{
									$lisofdetails_s6_less .= "<tr><td bgColor='#E4EAEB'>".($sr_less_s6++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount6_less=$month_total_amount6_less + $dt_view_row["SUMPO"];
								}
							}
							if ($tot_cnt_month_s == 7){
								if($dt_view_row["SUMPO"]>= 2000)
								{
									$lisofdetails_s7 .= "<tr><td bgColor='#E4EAEB'>".($sr_s7++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount7=$month_total_amount7 + $dt_view_row["SUMPO"];
								}
								else{
									$lisofdetails_s7_less .= "<tr><td bgColor='#E4EAEB'>".($sr_less_s7++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount7_less=$month_total_amount7_less + $dt_view_row["SUMPO"];
								}
							}
							if ($tot_cnt_month == 8){
								if($dt_view_row["SUMPO"]>= 2000)
								{
									$lisofdetails_s8 .= "<tr><td bgColor='#E4EAEB'>".($sr_s8++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount8=$month_total_amount8 + $dt_view_row["SUMPO"];
								}
								else{
									$lisofdetails_s8_less .= "<tr><td bgColor='#E4EAEB'>".($sr_less_s8++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount8_less=$month_total_amount8_less + $dt_view_row["SUMPO"];
								}
							}
							if ($tot_cnt_month_s == 9){
								if($dt_view_row["SUMPO"]>= 2000)
								{
									$lisofdetails_s9 .= "<tr><td bgColor='#E4EAEB'>".($sr_s9++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount9=$month_total_amount9 + $dt_view_row["SUMPO"];
								}
								else{
									$lisofdetails_s9_less .= "<tr><td bgColor='#E4EAEB'>".($sr_less_s9++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount9_less=$month_total_amount9_less + $dt_view_row["SUMPO"];
								}
							}
							if ($tot_cnt_month_s == 10){
								if($dt_view_row["SUMPO"]>= 2000)
								{
									$lisofdetails_s10 .= "<tr><td bgColor='#E4EAEB'>".($sr_s10++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount10=$month_total_amount10 + $dt_view_row["SUMPO"];
								}
								else{
									$lisofdetails_s10_less .= "<tr><td bgColor='#E4EAEB'>".($sr_less_s10++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount10_less=$month_total_amount10_less + $dt_view_row["SUMPO"];
								}
									
							}
							if ($tot_cnt_month_s == 11){
								if($dt_view_row["SUMPO"]>= 2000)
								{
									$lisofdetails_s11 .= "<tr><td bgColor='#E4EAEB'>".($sr_s11++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount11=$month_total_amount11 + $dt_view_row["SUMPO"];
								}
								else{
									$lisofdetails_s11_less .= "<tr><td bgColor='#E4EAEB'>".($sr_less_s11++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount11_less=$month_total_amount11_less + $dt_view_row["SUMPO"];
								}
									
							}
							if ($tot_cnt_month_s == 12){
								if($dt_view_row["SUMPO"]>= 2000)
								{
									$lisofdetails_s12 .= "<tr><td bgColor='#E4EAEB'>".($sr_s12++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount12=$month_total_amount12 + $dt_view_row["SUMPO"];
								}
								else{
									$lisofdetails_s12_less .= "<tr><td bgColor='#E4EAEB'>".($sr_less_s12++)."</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $dt_view_row["warehouse_id"] ."&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>". $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"],0) . "</td></tr>";
									$month_total_amount12_less=$month_total_amount12_less + $dt_view_row["SUMPO"];
								}
							}						
						}
					}
					//
					 $bonus=($first_rec_cnt_spin - $first_rec_cnt_spin % 2) / 2;
					//
					if ($amt_s > 0){
						$lisofdetails_s .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt_s,0) . "</td></tr>";
					}
					 if ($amt_s_less > 0){
						//
						$lisofdetails_s_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt_s_less,0) . "</td></tr>";
					}
					$lisofdetails_s .= "</table></span>";
					$lisofdetails_s_less .= "</table></span>";
					$unqid = $unqid + 1;
					  //
					$total_bonus_all=$bonus+$total_bonus_all;
					$total_bonus=$bonus+$total_bonus;
					//
					if ($amt_s <= 0){
					$lisofdetails_s="";
					}
					if($amt_s_less <= 0){
					$lisofdetails_s_less="";
					}
					//  
					echo "<td bgColor='#EAF1DD' align='right'><a href='#' onclick='load_div(". $unqid . $rowemp["id"] . "); return false;'>" .$bonus. "&nbsp;&nbsp;<strong>(".$first_rec_cnt_s.")</strong></font></a>";//first_rec_cnt_s
					echo "<span id='". $unqid . $rowemp["id"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisofdetails_s . "<br>".$lisofdetails_s_less."</span></td>";
					
					if ($tot_cnt_month_s == 1){
						$tot_cnt_month_s1 = $tot_cnt_month_s1 + $tot_cnt_thismonth_s;
						$total_month_bonus1=$total_bonus+$total_month_bonus1;
					}
					if ($tot_cnt_month_s == 2){
						$tot_cnt_month_s2 = $tot_cnt_month_s2 + $tot_cnt_thismonth_s;
						$total_month_bonus2=$total_bonus+$total_month_bonus2;
					}
					if ($tot_cnt_month_s == 3){
						$tot_cnt_month_s3 = $tot_cnt_month_s3 + $tot_cnt_thismonth_s;
						$total_month_bonus3=$total_bonus+$total_month_bonus3;
					}
					if ($tot_cnt_month_s == 4){
						$tot_cnt_month_s4 = $tot_cnt_month_s4 + $tot_cnt_thismonth_s;
						$total_month_bonus4=$total_bonus+$total_month_bonus4;
					}
					if ($tot_cnt_month_s == 5){
						$tot_cnt_month_s5 = $tot_cnt_month_s5 + $tot_cnt_thismonth_s;
						$total_month_bonus5=$total_bonus+$total_month_bonus5;
					}
					if ($tot_cnt_month_s == 6){
						$tot_cnt_month_s6 = $tot_cnt_month_s6 + $tot_cnt_thismonth_s;
						$total_month_bonus6=$total_bonus+$total_month_bonus6;
					}
					if ($tot_cnt_month_s == 7){
						$tot_cnt_month_s7 = $tot_cnt_month_s7 + $tot_cnt_thismonth_s;
						$total_month_bonus7=$total_bonus+$total_month_bonus7;
					}
					if ($tot_cnt_month_s == 8){
						$tot_cnt_month_s8 = $tot_cnt_month_s8 + $tot_cnt_thismonth_s;
						$total_month_bonus8=$total_bonus+$total_month_bonus8;
					}
					if ($tot_cnt_month_s == 9){
						$tot_cnt_month_s9 = $tot_cnt_month_s9 + $tot_cnt_thismonth_s;
						$total_month_bonus9=$total_bonus+$total_month_bonus9;
					}
					if ($tot_cnt_month_s == 10){
						$tot_cnt_month_s10 = $tot_cnt_month_s10 + $tot_cnt_thismonth_s;
						$total_month_bonus10=$total_bonus+$total_month_bonus10;
					}
					if ($tot_cnt_month_s == 11){
						$tot_cnt_month_s11 = $tot_cnt_month_s11 + $tot_cnt_thismonth_s;
						$total_month_bonus11=$total_bonus+$total_month_bonus11;
					}
					if ($tot_cnt_month_s == 12){
						$tot_cnt_month_s12 = $tot_cnt_month_s12 + $tot_cnt_thismonth_s;
						$total_month_bonus12=$total_bonus+$total_month_bonus12;
					}
					
			}
			if ($amt_s_all > 0){
				$lisofdetails1all_s .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt_s_all,0) . "</td></tr>";
						
			}
			if ($amt_s_all_less > 0){
				//
				$lisofdetails1all_s_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt_s_all_less,0) . "</td></tr>";
			}
			
				$lisofdetails1all_s .= "</table></span>";
				$lisofdetails1all_s_less .= "</table></span>";
				
			 //
		   
			?>
			
			<td bgColor='#EAF1DD' align='right'><a href='#' onclick='load_div(999888<?php echo $rowemp["id"]; ?>); return false;'><strong><?php echo number_format($total_bonus_all,0)."&nbsp;&nbsp;(".$tot_cnt_month_all_s.")"; //$tot_cnt_month_all_s ?></strong></font></a>
			<span id='999888<?php echo $rowemp["id"]; ?>' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a><?php echo $lisofdetails1all_s; ?><br><?php echo $lisofdetails1all_s_less; ?></span></td>
			
		<?php }
			
		?>
		 </tr>

		<?php
			 if ($month_total_amount1 > 0){
				$lisofdetails_s1 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount1,0) . "</td></tr>";
						
			}
			if ($month_total_amount1_less > 0){
				//
				$lisofdetails_s1_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount1_less,0) . "</td></tr>";
			}
			if ($month_total_amount2 > 0){
				$lisofdetails_s2 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount2,0) . "</td></tr>";
						
			}
			if ($month_total_amount2_less > 0){
				//
				$lisofdetails_s2_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount2_less,0) . "</td></tr>";
			}
			if ($month_total_amount3 > 0){
				$lisofdetails_s3 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount3,0) . "</td></tr>";
						
			}
			if ($month_total_amount3_less > 0){
				//
				$lisofdetails_s3_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount3_less,0) . "</td></tr>";
			}
			if ($month_total_amount4 > 0){
				$lisofdetails_s4 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount4,0) . "</td></tr>";
						
			}
			if ($month_total_amount4_less > 0){
				//
				$lisofdetails_s4_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount4_less,0) . "</td></tr>";
			}
			if ($month_total_amount5 > 0){
				$lisofdetails_s5 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount5,0) . "</td></tr>";
						
			}
			if ($month_total_amount5_less > 0){
				//
				$lisofdetails_s5_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount5_less,0) . "</td></tr>";
			}
			if ($month_total_amount6 > 0){
				$lisofdetails_s6 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount6,0) . "</td></tr>";
						
			}
			if ($month_total_amount6_less > 0){
				//
				$lisofdetails_s6_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount6_less,0) . "</td></tr>";
			}
			if ($month_total_amount7 > 0){
				$lisofdetails_s7 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount7,0) . "</td></tr>";
						
			}
			if ($month_total_amount7_less > 0){
				//
				$lisofdetails_s7_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount7_less,0) . "</td></tr>";
			}
			if ($month_total_amount8 > 0){
				$lisofdetails_s8 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount8,0) . "</td></tr>";
						
			}
			if ($month_total_amount8_less > 0){
				//
				$lisofdetails_s8_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount8_less,0) . "</td></tr>";
			}
			if ($month_total_amount9 > 0){
				$lisofdetails_s9 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount9,0) . "</td></tr>";
						
			}
			if ($month_total_amount9_less > 0){
				//
				$lisofdetails_s9_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount9_less,0) . "</td></tr>";
			}
			if ($month_total_amount10 > 0){
				$lisofdetails_s10 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount10,0) . "</td></tr>";
						
			}
			if ($month_total_amount10_less > 0){
				//
				$lisofdetails_s10_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount10_less,0) . "</td></tr>";
			}
			if ($month_total_amount11 > 0){
				$lisofdetails_s11 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount11,0) . "</td></tr>";
						
			}
			if ($month_total_amount11_less > 0){
				//
				$lisofdetails_s11_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount11_less,0) . "</td></tr>";
			}
			if ($month_total_amount12 > 0){
				$lisofdetails_s12 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount12,0) . "</td></tr>";
						
			}
			if ($month_total_amount12_less > 0){
				//
				$lisofdetails_s12_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount12_less,0) . "</td></tr>";
			}
			
		
			$lisofdetails_s1 .= "</table></span>";
			$lisofdetails_s2 .= "</table></span>";
			$lisofdetails_s3 .= "</table></span>";
			$lisofdetails_s4 .= "</table></span>";
			$lisofdetails_s5 .= "</table></span>";
			$lisofdetails_s6 .= "</table></span>";
			$lisofdetails_s7 .= "</table></span>";
			$lisofdetails_s8 .= "</table></span>";
			$lisofdetails_s9 .= "</table></span>";
			$lisofdetails_s10 .= "</table></span>";
			$lisofdetails_s11 .= "</table></span>";
			$lisofdetails_s12 .= "</table></span>";
			//
			$lisofdetails_s1_less .= "</table></span>";
			$lisofdetails_s2_less .= "</table></span>";
			$lisofdetails_s3_less .= "</table></span>";
			$lisofdetails_s4_less .= "</table></span>";
			$lisofdetails_s5_less .= "</table></span>";
			$lisofdetails_s6_less .= "</table></span>";
			$lisofdetails_s7_less .= "</table></span>";
			$lisofdetails_s8_less .= "</table></span>";
			$lisofdetails_s9_less .= "</table></span>";
			$lisofdetails_s10_less .= "</table></span>";
			$lisofdetails_s11_less .= "</table></span>";
			$lisofdetails_s12_less .= "</table></span>";
			//
	?>		
			<tr>
				<td bgColor='#EAF1DD' align="right"><strong>Total</strong></td>
				<td bgColor='#EAF1DD' align='right'><a href='#' onclick='load_div(988888); return false;'><strong>
					<?php echo $total_month_bonus1."&nbsp;&nbsp;(".number_format($tot_cnt_month_s1,0).")"; ?>
					</strong></font></a>
				<span id='988888' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>
					<?php echo $lisofdetails_s1; ?><br><?php echo $lisofdetails_s1_less; ?></span></td>


				<td bgColor='#EAF1DD' align='right'><a href='#' onclick='load_div(988881); return false;'><strong>
					<?php echo $total_month_bonus2."&nbsp;&nbsp;(".number_format($tot_cnt_month_s2,0).")"; ?></strong></font></a>
				<span id='988881' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>
					<?php echo $lisofdetails_s2; ?><br><?php echo $lisofdetails_s2_less; ?></span></td>

				<td bgColor='#EAF1DD' align='right'><a href='#' onclick='load_div(988882); return false;'><strong>
					<?php echo $total_month_bonus3."&nbsp;&nbsp;(".number_format($tot_cnt_month_s3,0).")"; ?></strong></font></a>
				<span id='988882' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>
					<?php echo $lisofdetails_s3; ?><br><?php echo $lisofdetails_s3_less; ?></span></td>

				<td bgColor='#EAF1DD' align='right'><a href='#' onclick='load_div(988883); return false;'><strong>
					<?php echo $total_month_bonus4."&nbsp;&nbsp;(".number_format($tot_cnt_month_s4,0).")"; ?></strong></font></a>
				<span id='988883' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>
					<?php echo $lisofdetails_s4; ?><br><?php echo $lisofdetails_s4_less; ?></span></td>

				<td bgColor='#EAF1DD' align='right'><a href='#' onclick='load_div(988884); return false;'><strong>
					<?php echo $total_month_bonus5."&nbsp;&nbsp;(".number_format($tot_cnt_month_s5,0).")"; ?></strong></font></a>
				<span id='988884' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>
					<?php echo $lisofdetails_s5; ?><br><?php echo $lisofdetails_s5_less; ?></span></td>

				<td bgColor='#EAF1DD' align='right'><a href='#' onclick='load_div(988885); return false;'><strong>
					<?php echo $total_month_bonus6."&nbsp;&nbsp;(".number_format($tot_cnt_month_s6,0).")"; ?></strong></font></a>
				<span id='988885' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>
					<?php echo $lisofdetails_s6; ?><br><?php echo $lisofdetails_s6_less; ?></span></td>

				<td bgColor='#EAF1DD' align='right'><a href='#' onclick='load_div(988886); return false;'><strong>
					<?php echo $total_month_bonus7."&nbsp;&nbsp;(".number_format($tot_cnt_month_s7,0).")"; ?></strong></font></a>
				<span id='988886' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>
					<?php echo $lisofdetails_s7; ?><br><?php echo $lisofdetails_s7_less; ?></span></td>

				<td bgColor='#EAF1DD' align='right'><a href='#' onclick='load_div(988887); return false;'><strong>
					<?php echo $total_month_bonus8."&nbsp;&nbsp;(".number_format($tot_cnt_month_s8,0).")"; ?></strong></font></a>
				<span id='988887' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>
					<?php echo $lisofdetails_s8; ?><br><?php echo $lisofdetails_s8_less; ?></span></td>

				<td bgColor='#EAF1DD' align='right'><a href='#' onclick='load_div(988818); return false;'><strong>
					<?php echo $total_month_bonus9."&nbsp;&nbsp;(".number_format($tot_cnt_month_s9,0).")"; ?></strong></font></a>
				<span id='988818' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>
					<?php echo $lisofdetails_s9; ?><br><?php echo $lisofdetails_s9_less; ?></span></td>

				<td bgColor='#EAF1DD' align='right'><a href='#' onclick='load_div(988828); return false;'><strong>
					<?php echo $total_month_bonus10."&nbsp;&nbsp;(".number_format($tot_cnt_month_s10,0).")"; ?></strong></font></a>
				<span id='988828' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>
					<?php echo $lisofdetails_s10; ?><br><?php echo $lisofdetails_s10_less; ?></span></td>

				<td bgColor='#EAF1DD' align='right'><a href='#' onclick='load_div(988838); return false;'><strong>
					<?php echo $total_month_bonus11."&nbsp;&nbsp;(".number_format($tot_cnt_month_s11,0).")"; ?></strong></font></a>
				<span id='988838' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>
					<?php echo $lisofdetails_s11; ?><br><?php echo $lisofdetails_s11_less; ?></span></td>

				<td bgColor='#EAF1DD' align='right'><a href='#' onclick='load_div(988848); return false;'><strong>
					<?php echo $total_month_bonus12."&nbsp;&nbsp;(".number_format($tot_cnt_month_s12,0).")"; ?></strong></font></a>
				<span id='988848' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>
					<?php echo $lisofdetails_s12; ?><br><?php echo $lisofdetails_s12_less; ?></span></td>


				<?php
					if ($amt_s_lastall > 0){
						//
						$lisofdetails1all_last_s .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt_s_lastall,0) . "</td></tr>";
					}
					if ($amt_s_lastall_less > 0){
						//
						$lisofdetails1all_last_s_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt_s_lastall_less,0) . "</td></tr>";
					}
			//
					$lisofdetails1all_last_s .= "</table></span>";	
					$lisofdetails1all_last_s_less .= "</table></span>";
			 		$total_spins_all_month=$total_month_bonus1+$total_month_bonus2+$total_month_bonus3+$total_month_bonus4+$total_month_bonus5+$total_month_bonus6+$total_month_bonus7+$total_month_bonus8+$total_month_bonus9+$total_month_bonus10+$total_month_bonus11+$total_month_bonus12;
				?>
			
				<td bgColor='#EAF1DD' align='right'><a href='#' onclick='load_div(997988); return false;'><strong>
					<?php echo $total_spins_all_month."&nbsp;&nbsp;(".number_format($tot_cnt_thismonth_all_s,0).")"; ?></strong></font></a>
				<span id='997988' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a><?php echo $lisofdetails1all_last_s; ?><br><?php echo  $lisofdetails1all_last_s_less; ?><br></span></td>	
				
			</tr>
	<?php } ?>			
			
	</table>
</div>
</body>
</html>