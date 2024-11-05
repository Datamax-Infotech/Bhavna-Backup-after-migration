<?php
require ("inc/header_session.php");
require ("../mainfunctions/database.php");
require ("../mainfunctions/general-functions.php");

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>B2B Demand Summary Report</title>
<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet"> 
<LINK rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
<style type="text/css">
.style7 {
	font-size: x-small;
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
.style12center {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	color: #333333;
	text-align: center;
}
.style12right {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	color: #333333;
	text-align: right;
}
.style12left {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	color: #333333;
	text-align: left;
}
.style13 {
	font-family: Arial, Helvetica, sans-serif;
}
.style14 {
	font-size: x-small;
}
.style15 {
	font-size: x-small;
}
.style16 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	background-color: #99FF99;
}
.style17 {
	font-size: 13px;
	padding: 3px;
	font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
	color: #333333;
}
.qty_freq_title{
	font-size: 14px;
	padding: 3px;
	font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
	color: #000000;
	font-weight: 600;
}
.display_row{
	font-size: 11px;
	padding: 3px;
	font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
	background: #EBEBEB;
}
.display_row a{
	color: #004CB3;
	font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
}

.display_row_alt{
	font-size: 11px;
	padding: 3px;
	font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
	background: #F7F7F7;
}
.display_row_alt a{
	color: #004CB3;
	font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
}
select, input {
font-family: Arial, Helvetica, sans-serif; 
font-size: 12px; 
color : #000000; 
font-weight: normal; 
}

table.datatable {
  border-collapse: collapse;
  background: #FFF;
}

/*table thead {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  background: #FFF;
  display: table;
  table-layout: fixed;
  border: solid 1px #000;
}*/
table.datatable tbody {
  margin-top: 24px;
}
table.datatable {
  /*border: 1px solid white;*/
}
table.datatable tr td,
table.datatable tr th {
  height: 20px;
  border: 1px solid white;
  /*padding: 5px;*/
}
	table.innertable {
  border-collapse: collapse;
  background: #FFF;
}
	table.innertable tr td,
	table.innertable tr th {
  height: 20px;
  border: 1px solid white;
  padding: 5px;
}

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
		width:1000px;
		z-index:1002;
		margin: 0px 0 0 0px; 
		padding: 10px 10px 10px 10px;
		border-color:black; 
		border-width:2px;
		overflow: auto;
	}
	
</style>
	<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT><SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
	<script LANGUAGE="JavaScript">document.write(getCalendarStyles());</script>
	<script LANGUAGE="JavaScript">
		var cal2xx = new CalendarPopup("listdiv");
		cal2xx.showNavigationDropdowns();
		var cal3xx = new CalendarPopup("listdiv");
		cal3xx.showNavigationDropdowns();
	</script>

	<script LANGUAGE="JavaScript">
		function f_getPosition (e_elemRef, s_coord) {
		var n_pos = 0, n_offset,
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

		function load_div(id){
			var element = document.getElementById("spanctr" + id); //replace elementId with your element's Id.

			var rect = element.getBoundingClientRect();
			var elementLeft,elementTop; //x and y
			var scrollTop = document.documentElement.scrollTop?
							document.documentElement.scrollTop:document.body.scrollTop;
			var scrollLeft = document.documentElement.scrollLeft?                   
							 document.documentElement.scrollLeft:document.body.scrollLeft;
			elementTop = rect.top+scrollTop;
			elementLeft = rect.left+scrollLeft;
		
			document.getElementById("light").innerHTML = document.getElementById("spanctr" + id).innerHTML;
			document.getElementById('light').style.display='block';
			document.getElementById('fade').style.display='block';

			document.getElementById('light').style.left='100px';
			document.getElementById('light').style.top=elementTop + 100 + 'px';
		
		}
				
		function close_div(){
			document.getElementById('light').style.display='none';
		}
	
	function show_quote_table(quote_id, companyID, box_type){
    	var selectobject = document.getElementById("quote_ui"+quote_id); 
		var n_left = f_getPosition(selectobject, 'Left');
		var n_top  = f_getPosition(selectobject, 'Top');
		document.getElementById('light').style.left = n_left - 0 + 'px';
		document.getElementById('light').style.top = n_top + 10 + 'px';
		document.getElementById('light').style.width = 750 + 'px';
		document.getElementById('light').style.height = 700 + 'px';
    	//
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center>"+xmlhttp.responseText;
		      	document.getElementById('light').style.display='block';
			}

		}
        xmlhttp.open("POST","b2b_demand_summary_entry_table.php?quote_id="+quote_id+"&companyID="+companyID+"&box_type="+box_type+"&showquotedata=1",true);
	    xmlhttp.send();
	}
		//
	function show_all_quotes(quote_id, companyID){
    	var selectobject = document.getElementById("all_quote"+quote_id); 
		var n_left = f_getPosition(selectobject, 'Left');
		var n_top  = f_getPosition(selectobject, 'Top');
		document.getElementById('light').style.left = n_left - 0 + 'px';
		document.getElementById('light').style.top = n_top + 10 + 'px';
		document.getElementById('light').style.width = 920 + 'px';
		document.getElementById('light').style.height = 700 + 'px';
		//
		document.getElementById('light').style.display='block';
		document.getElementById("light").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />"; 						
		
    	//
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center>"+xmlhttp.responseText;
		      	document.getElementById('light').style.display='block';
			}

		}
        xmlhttp.open("POST","b2b_demand_summary_all_quotes.php?quote_id="+quote_id+"&companyID="+companyID+"&showallquotes=1",true);
	    xmlhttp.send();
	}
	</script>
</head>

<style type="text/css">
	.main_data_css{
		margin: 0 auto;
		/*width: 100%;*/
		height: auto;
		clear: both !important;
		padding-top: 35px;
		margin-left: 10px;
		margin-right: 10px;
	}
	
	.search input {
		height: 24px !important;
	}	
	h2.boxtitle{
		font-size: 20px;
		font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
		margin-bottom: 4px;
		padding: 0px;
		color: #1E1E1E;
	}
</style>

<body>
<?php include("inc/header.php"); ?>
<div class="main_data_css">
	<div id="light" class="white_content"> </div>
	<div id="fade" class="black_overlay"></div>
	<div class="dashboard_heading" style="float: left;">
		<div style="float: left;">
			B2B Demand Summary Report   
		<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
		<span class="tooltiptext">This report shows the user all demand entries that UCB has the opportunity sell to, regardless of whether we satiate that demand or not...and sections it by the most valuable demand entries to the least. Thus, this report helps the user see the most valuable demand entries UCB has in it's entire demand pipeline.</span></div>
		<div style="height: 13px;">&nbsp;</div>
		</div>
	</div>
	
	<?php
	
	$time = strtotime(Date('Y-m-d'));
	$st_friday = $time;
	$st_friday_last = date('m/d/Y', strtotime('-6 days', $st_friday));

	$st_thursday_last = Date('m/d/Y');
	//$st_friday_last = '01/01/2019';
	$in_dt_range = "no";
	if( $_REQUEST["date_from"] !="" && $_REQUEST["date_to"] !=""){
		$date_from_val = date("Y-m-d", strtotime($_REQUEST["date_from"]));
		$date_to_val_org = date("Y-m-d", strtotime($_REQUEST["date_to"]));
		$date_to_val = date("Y-m-d", strtotime($_REQUEST["date_to"]));
		$in_dt_range = "yes";
		//
	}else{
		if (isset($_REQUEST["warehouse_id"]) || isset($_REQUEST["inv_id"])){
			$in_dt_range = "no";
			$date_from_val = date("Y-01-01", strtotime($st_friday_last));
			$date_to_val_org = date("Y-m-d", strtotime($st_thursday_last));
			$date_to_val = date("Y-m-d", strtotime($st_thursday_last));
		}else{
			$in_dt_range = "no";
			$date_from_val = date("Y-m-d", strtotime($st_friday_last));
			$date_to_val_org = date("Y-m-d", strtotime($st_thursday_last));
			$date_to_val = date("Y-m-d", strtotime($st_thursday_last));
		}	
	}
	
	if(strpos($_SERVER['HTTP_REFERER'], "dashboardnew_account_pipeline.php")){
		$_REQUEST["employee"] = $_COOKIE['userinitials'];
	}
	
	?>
	<form method="post" name="inv_frm" action="report_b2b_demand_summary_old.php">
		<table border="0">
			<tr>
				<td>Employee</td>
				<td>
					<select id="employee" name="employee">
						<option value="~">All</option>
						<?php 
						db_b2b();
						$getEmp = db_query("SELECT * FROM employees ORDER BY status ASC, name ASC");
						while ($rowsEmp = array_shift($getEmp)) {
						?>
							<option <?php if (isset($_REQUEST["employee"]) && $rowsEmp["initials"]==$_REQUEST["employee"]) echo " selected ";  ?> value="<?php echo $rowsEmp["initials"];?>"><?php if ($rowsEmp["status"] !='Active') {echo $rowsEmp["name"] . "(Inactive)";} else { echo $rowsEmp["name"];} ?></option>
						<?php
						}
						?>
					</select>
				</td>
				<td>
					Type:
				</td>
				<td>					
					<select id="box_type" name="box_type">	
						<option <?php if($_REQUEST["box_type"]=="All"){?>selected="selected"<?php } ?>  value="All">All</option>
						<option <?php if($_REQUEST["box_type"]=="Gaylord Totes"){?>selected="selected"<?php } ?>>Gaylord Totes</option>
						<option <?php if($_REQUEST["box_type"]=="Shipping Boxes"){?>selected="selected"<?php } ?>>Shipping Boxes</option>
						<option <?php if($_REQUEST["box_type"]=="Pallets"){?>selected="selected"<?php } ?>>Pallets</option>
						<option <?php if($_REQUEST["box_type"]=="Supersacks"){?>selected="selected"<?php } ?>>Supersacks</option>
						<option <?php if($_REQUEST["box_type"]=="Other"){?>selected="selected"<?php } ?>>Other</option>
					</select>
				</td>
				<td></td>
				<td>Territory:</td>
				<td width="5px;">&nbsp;</td>
				<td colspan="3">
					<?php if (isset($_REQUEST["btntool"])) { ?>
						<input type="checkbox" id="chkterritory_canada_east" name="chkterritory_canada_east" value="canada_east" <?php if ($_REQUEST["chkterritory_canada_east"] == "canada_east" ) { echo " checked ";} ?>/>Canada East
						<input type="checkbox" id="chkterritoryeast_reg" name="chkterritoryeast_reg" value="east_reg" <?php if ($_REQUEST["chkterritoryeast_reg"] == "east_reg") { echo " checked ";}?>/>East
						<input type="checkbox" id="chkterritorysouth_reg" name="chkterritorysouth_reg" value="south_reg"  <?php if ($_REQUEST["chkterritorysouth_reg"] == "south_reg") { echo " checked ";}?>/>South
						<input type="checkbox" id="chkterritorymidwest_reg" name="chkterritorymidwest_reg" value="midwest_reg" <?php if ($_REQUEST["chkterritorymidwest_reg"] == "midwest_reg") { echo " checked ";}?>/>Midwest
						<input type="checkbox" id="chkterritorynorthcenteral_reg" name="chkterritorynorthcenteral_reg" value="northcenteral_reg" <?php if ($_REQUEST["chkterritorynorthcenteral_reg"] == "northcenteral_reg" ) { echo " checked ";}?>/>North Central
						<input type="checkbox" id="chkterritorysouthcenteral_reg" name="chkterritorysouthcenteral_reg" value="southcenteral_reg" <?php if ($_REQUEST["chkterritorysouthcenteral_reg"] == "southcenteral_reg" ) { echo " checked ";}?>/>South Central

						<input type="checkbox" id="chkterritory_canada_west" name="chkterritory_canada_west" value="canada_west" <?php if ($_REQUEST["chkterritory_canada_west"] == "canada_west" ) { echo " checked ";}?>/>Canada West

						<input type="checkbox" id="chkterritorypacific_reg" name="chkterritorypacific_reg" value="pacific_reg" <?php if ($_REQUEST["chkterritorypacific_reg"] == "pacific_reg" ) { echo " checked ";}?>/>Pacific Northwest
						<input type="checkbox" id="chkterritorywestern_reg" name="chkterritorywestern_reg" value="western_reg" <?php if ($_REQUEST["chkterritorywestern_reg"] == "western_reg" ) { echo " checked ";}?>/>Western

						<input type="checkbox" id="chkterritorymexico_reg" name="chkterritorymexico_reg" value="mexico_reg" <?php if ($_REQUEST["chkterritorymexico_reg"] == "mexico_reg") { echo " checked ";}?>/>Mexico
					
					<?php }else{ 
						?>

						<input type="checkbox" id="chkterritory_canada_east" name="chkterritory_canada_east" value="canada_east" checked />Canada East
						<input type="checkbox" id="chkterritoryeast_reg" name="chkterritoryeast_reg" value="east_reg" checked />East
						<input type="checkbox" id="chkterritorysouth_reg" name="chkterritorysouth_reg" value="south_reg" checked  />South
						<input type="checkbox" id="chkterritorymidwest_reg" name="chkterritorymidwest_reg" value="midwest_reg" checked  />Midwest
						<input type="checkbox" id="chkterritorynorthcenteral_reg" name="chkterritorynorthcenteral_reg" value="northcenteral_reg" checked />North Central
						<input type="checkbox" id="chkterritorysouthcenteral_reg" name="chkterritorysouthcenteral_reg" value="southcenteral_reg" checked />South Central

						<input type="checkbox" id="chkterritory_canada_west" name="chkterritory_canada_west" value="canada_west" checked />Canada West

						<input type="checkbox" id="chkterritorypacific_reg" name="chkterritorypacific_reg" value="pacific_reg" checked />Pacific Northwest
						<input type="checkbox" id="chkterritorywestern_reg" name="chkterritorywestern_reg" value="western_reg" checked  />Western

						<input type="checkbox" id="chkterritorymexico_reg" name="chkterritorymexico_reg" value="mexico_reg" checked />Mexico
					<?php } ?>
					<?php 
						$prod_cnt = 0; 
					?>
					<input type="hidden" name="prod_cnt" id="prod_cnt" value="<?php echo $prod_cnt; ?>" > 
				</td>
				
				<td><input type="submit" name="btntool" name="btntool" value="Submit" /></td>
				<td><img src="images/usa_map_territories.png" width="200px" height="100px" style="object-fit: cover;"/>	  </td>
				
			</tr>
		</table>
	</form>
	
	<?php if (isset($_REQUEST["btntool"])) { ?>
		<?php
			
			//$chkterritory
			$State_where = "";
			$State_where1 = ""; $State_where_ucbq1 = ""; $State_where2 = ""; $State_where_ucbq2 = ""; $State_where3 = ""; $State_where_ucbq3 = "";
			$State_where4 = ""; $State_where_ucbq4 = ""; $State_where5 = ""; $State_where_ucbq5 = ""; $State_where6 = ""; $State_where_ucbq6 = "";
			$State_where7 = ""; $State_where_ucbq7 = ""; $State_where8 = ""; $State_where_ucbq8 = ""; $State_where9 = ""; $State_where_ucbq9 = "";
			$State_where10 = ""; $State_where_ucbq10 = ""; 
			if ($_REQUEST["chkterritoryeast_reg"] == "east_reg"){
				//$State_where1 .= " country = 'USA' and state in ('ME','NH','VT','MA','RI','CT','NY','PA','MD','VA','WV')";
				$State_where1.="territory='East'";
			}
			if ($_REQUEST["chkterritorysouth_reg"] == "south_reg"){
				//$State_where2 .= " country = 'USA' and state in ('NC','SC','GA','AL','MS','TN','FL')";
				$State_where2.="territory='South'";
			}
			if ($_REQUEST["chkterritorymidwest_reg"] == "midwest_reg"){
				//$State_where3 .= " country = 'USA' and state in ('MI','OH','IN','KY')";
				$State_where3.="territory='Midwest'";
			}
			if ($_REQUEST["chkterritorynorthcenteral_reg"] == "northcenteral_reg"){
				//$State_where4 .= " country = 'USA' and state in ('ND','SD','NE','MN','IA','IL','WI')";
				$State_where4.="territory='North Central'";
			}
			if ($_REQUEST["chkterritorysouthcenteral_reg"] == "southcenteral_reg"){
				//$State_where5 .= " country = 'USA' and state in ('LA','AR','MO','TX','OK','KS','CO','NM')";
				$State_where5.="territory='South Central'";
			}
			if ($_REQUEST["chkterritorypacific_reg"] == "pacific_reg"){
				//$State_where6 .= " country = 'USA' and state in ('WA','OR','ID','MT','WY','AK')";
				$State_where6.="territory='Pacific Northwest'";
			}
			if ($_REQUEST["chkterritorywestern_reg"] == "western_reg"){
				//$State_where7 .= " country = 'USA' and state in ('CA','NV','UT','AZ','HI')";
				$State_where7.="territory='West'";
			}
			if ($_REQUEST["chkterritory_canada_east"] == "canada_east"){
				//$State_where8 .= " country = 'Canada' and state in ('NB', 'NF', 'NS','ON', 'PE', 'QC')";
				$State_where8.="territory='Canada East'";
			}
			if ($_REQUEST["chkterritory_canada_west"] == "canada_west"){
				//$State_where9 .= " country = 'Canada' and state in ('AB', 'BC', 'MB', 'NT', 'NU', 'SK', 'YT' )";
				$State_where9.="territory='Canada West'";
			}

			if ($_REQUEST["chkterritorymexico_reg"] == "mexico_reg"){
				//$State_where10 .= " country = 'Mexico' and state in ('AG','BS','CH','CL','CM','CO','CS','DF','DG','GR','GT','HG','JA','ME','MI','MO','NA','NL','OA','PB','QE','QR','SI','SL','SO','TB','TL','TM','VE','ZA') ";
				$State_where10.="territory='Mexico'";
			}
			if ($_REQUEST["chkterritoryother_reg"] == "other_reg"){
				//$State_where10 .= " state not in ('ME','NH','VT','MA','RI','CT','NY','PA','MD','VA','WV','NC','SC','GA','AL','MS','TN','MI','OH','IN','KY','ND','SD','NE','MN','IA','IL','WI','LA','AR','MO','TX','OK','KS','CO','NM','WA','OR','ID','MT','WY','AK','CA','NV','UT','AZ','HI','AB','BC','LB','MB','NB','NF','NS','NU','NW','ON','PE','QC','SK','YU') ";
			}

			if ($State_where1 != ""){
				$State_where .= $State_where1 . " or ";
			}
			if ($State_where2 != ""){
				$State_where .= $State_where2 . " or ";
			}
			if ($State_where3 != ""){
				$State_where .= $State_where3 . " or ";
			}
			if ($State_where4 != ""){
				$State_where .= $State_where4 . " or ";
			}
			if ($State_where5 != ""){
				$State_where .= $State_where5 . " or ";
			}
			if ($State_where6 != ""){
				$State_where .= $State_where6 . " or ";
			}
			if ($State_where7 != ""){
				$State_where .= $State_where7 . " or ";
			}
			if ($State_where8 != ""){
				$State_where .= $State_where8 . " or ";
			}
			if ($State_where9 != ""){
				$State_where .= $State_where9 . " or ";
			}
			if ($State_where10 != ""){
				$State_where .= $State_where10 . " or ";
			}

			if ($State_where != ""){
				$State_where = substr($State_where, 0, strlen($State_where)-3);
			}
			
			$State_where_main = "";
			if ($State_where1 != "" || $State_where2 != ""  || $State_where3 != "" || $State_where4 != "" || $State_where5 != ""
				 || $State_where6 != "" || $State_where7 != "" || $State_where8 != "" || $State_where9 != "" || $State_where10 != ""){
				$State_where_main = " and ( " . $State_where . ") ";
			}
		
			if ($State_where1 != "" && $State_where2 != ""  && $State_where3 != "" && $State_where4 != "" && $State_where5 != ""
				 && $State_where6 != "" && $State_where7 != "" && $State_where8 != "" && $State_where9 != "" && $State_where10 != ""){
				 //to show all data
				$State_where_main = " ";
			}
		
			$box_type_cnt=0; 
			$gy = array(); $sb = array(); $pal = array(); $sup = array(); $other = array(); 
			$_SESSION['sortarraygy'] = ""; $_SESSION['sortarraysb'] = ""; $_SESSION['sortarraypal'] = "";  $_SESSION['sortarraysup'] = ""; $_SESSION['sortarrayother'] = ""; 
			//
			$box_type_arry = array();
			if($_REQUEST["box_type"]=="All")
			{
				$box_type_arry = array('Gaylord Totes','Shipping Boxes', 'Pallets', 'Supersacks', 'Other');
			}
			elseif($_REQUEST["box_type"]=="Gaylord Totes")
			{
				$box_type_arry = array('Gaylord Totes');
			}
			elseif($_REQUEST["box_type"]=="Shipping Boxes")
			{
				$box_type_arry = array('Shipping Boxes');
			}
			elseif($_REQUEST["box_type"]=="Pallets")
			{
				$box_type_arry = array('Pallets');
			}
			elseif($_REQUEST["box_type"]=="Supersacks")
			{
				$box_type_arry = array('Supersacks');
			}
			elseif($_REQUEST["box_type"]=="Other")
			{
				$box_type_arry = array('Other');
			}
			foreach($box_type_arry as $box_array)
			{
				$box_table = "";
				$frequency_order = "";
				$quantity_request = "";
				$prefix = "";
				$notes = "";
				if ($box_array == "Gaylord Totes")
				{ 
					$box_table = "quote_gaylord";
					$quantity_request="g_quantity_request";
					$frequency_order="g_frequency_order";
					$notes="g_item_note";
					$prefix="g";
				}
				if ($box_array == "Shipping Boxes")
				{ 
					$box_table = "quote_shipping_boxes"; 
					$quantity_request="sb_quantity_requested";
					$frequency_order="sb_frequency_order";
					$notes="sb_notes";
					$prefix="sb";
				}
				if ($box_array == "Pallets")
				{ 
					$box_table = "quote_pallets"; 
					$quantity_request="pal_quantity_requested";
					$frequency_order="pal_frequency_order";
					$notes="pal_note";
					$prefix="pal";
				}
				if ($box_array == "Supersacks")
				{ 
					$box_table = "quote_supersacks"; 
					$quantity_request="sup_quantity_requested";
					$frequency_order="sup_frequency_order";
					$notes="sup_notes";
					$prefix="sup";
				}
				if ($box_array == "Other")
				{ 
					$box_table = "quote_other"; 
					$quantity_request="other_quantity_requested";
					$frequency_order="other_frequency_order";
					$notes="other_note";
					$prefix="other";
				}
				$box_type_cnt=$box_type_cnt+1;
				?>
				<table cellSpacing="0" cellPadding="1" border="0" class="datatable" width="900px">
				<!--<tr vAlign="center" >
					<td align="center" ><h2 class="boxtitle" style="border-bottom: 1px double #BBBBBB;"><?php //echo $box_array; ?></h2></td>  
				</tr>-->
				<?php
				$qty_cnt=0;
				$quantity_arry = array('High Value Opportunity','Full Truckload','Half Truckload', 'Quarter Truckload', 'Other');
				//
				foreach($quantity_arry as $qty_array)
				{
					$show_done="no";
					$qty_cnt=$qty_cnt+1;
					$bgcolor = "";
					if($qty_cnt==1){
						$bgcolor="#98bcdf";
					}
					if($qty_cnt==2){
						$bgcolor="#d3f1c9";
					}
					if($qty_cnt==3){
						$bgcolor="#d9d1e9";
					}
					if($qty_cnt==4){
						$bgcolor="#f4cccc";
					}
					$order_arry = array('Multiple per Week','Multiple per Month', 'Once per Month', 'Multiple per Year', 'Once per Year', 'One-Time Purchase');
					
					foreach($order_arry as $order_array)
					{
						$show=0;
						if ($qty_array == "High Value Opportunity"){
							$bqry="SELECT * FROM quote_request INNER JOIN $box_table ON quote_request.quote_id = $box_table.quote_id WHERE high_value_target = 1 AND $frequency_order='". $order_array."'";						
						}else{
							$bqry="SELECT * FROM quote_request INNER JOIN $box_table ON quote_request.quote_id = $box_table.quote_id WHERE $quantity_request='". $qty_array."' AND $frequency_order='". $order_array."'";						
						}							
						//echo "<br /> nyn ->   ".$bqry;
						db();
						$bres = db_query($bqry);
						$srno=0;
						$num_rows=tep_db_num_rows($bres);
						$display_heading = "";
						$display_data = "";
						if($num_rows>0)
						{
							$display_data="yes";
							$display_heading="yes";
							$show=1;
							
						}
						
						if($show==1 && $show_done=="no"){
						?>
						<tr>
							<td align="left" ><h2 class="boxtitle"><?php echo $box_array." - <span style='color:#f29e00;'>".$qty_array; ?></span></h2></td>
						</tr> 	
						<?php
							$show=0;
							$show_done="yes";
						}
						if($display_heading=="yes"){
							$display_heading="no";
						?>
				
							<tr>
								<td class="qty_freq_title" bgcolor="<?php echo $bgcolor; ?>" align="center">Orders <?php echo $qty_array; ?>, <?php echo $order_array; ?></td>  
							</tr>
							<tr><td><table cellpadding="3" cellspacing="1" width="100%" class="innertable">
							<tr>
								<td class="style17" bgcolor="<?php echo $bgcolor; ?>" align="center">
									<b>Sr. No</b></td>
								<td class="style17" bgcolor="<?php echo $bgcolor; ?>" align="center">
									<b>Demand Entry ID</b></td>
								<td class="style17" bgcolor="<?php echo $bgcolor; ?>" align="center">
									<b>Demand Entry Date</b></td>
								<td class="style17" bgcolor="<?php echo $bgcolor; ?>" align="center">
									<b>Company Name</b></td>
								<td class="style17" bgcolor="<?php echo $bgcolor; ?>" align="center">
									<b>Territory</b></td>
								<td class="style17" bgcolor="<?php echo $bgcolor; ?>" align="center">
									<b>Rep</b></td>
								<td class="style17" bgcolor="<?php echo $bgcolor; ?>" align="center">
									<b>Ideal Size</b></td>
								<td class="style17" bgcolor="<?php echo $bgcolor; ?>" align="center">
									<b>Order Quantity</b></td>
								<td class="style17" bgcolor="<?php echo $bgcolor; ?>" align="center">
									<b>Frequency</b></td>
								<td class="style17" bgcolor="<?php echo $bgcolor; ?>" align="center">
									<b>Desired Price</b></td>
								<td class="style17" bgcolor="<?php echo $bgcolor; ?>" align="center">
									<b>What Used For</b></td>
								<td class="style17" bgcolor="<?php echo $bgcolor; ?>" align="center">
									<b>Also Needs Pallets</b></td>
								<td class="style17" bgcolor="<?php echo $bgcolor; ?>" align="center">
									<b>Notes</b></td>

							</tr>
				
						<?php
						}

						//echo "<pre>"; print_r($bres); echo "</pre>";
						while($brow=array_shift($bres))
						{
							if ($State_where_main != ""){
								$comp_qry = "Select ID from companyInfo where ID = '" . $brow["companyID"] . "' " . $State_where_main;
								//echo $comp_qry . "<br>";
								db_b2b();
								$comp_res = db_query($comp_qry);
								$comp_num_rows=tep_db_num_rows($comp_res);
								
								db();
							}else{
								$comp_num_rows = 1;
							}							
							
							if($comp_num_rows > 0){
								$row_cnt = 1;
								if ($row_cnt == 0){
									$display_row_css = "display_row";
									$row_cnt = 1;
								}else{
									$row_cnt = 0;	
									$display_row_css = "display_row_alt";
								}	
								$srno=$srno+1;
								//
								$company_name = get_nickname_val('', $brow["companyID"]);
								db_b2b();
								$query_comp = db_query("SELECT territory, employees.initials as empname, last_contact_date, employees.employeeID FROM companyInfo left join employees on employees.employeeID= companyInfo.assignedto where ID = '" . $brow["companyID"] . "'"); 
								$row_comp = array_shift($query_comp);
								$acc_owner = $row_comp["empname"];	
								$acc_ownerid = $row_comp["employeeID"];	
								//

								if($display_data=="yes"){
									if(isset($_REQUEST["employee"]) && $_REQUEST["employee"] == '~'){
										?>
										<tr>
											<td class="<?php echo $display_row_css; ?>" align="center">
												<a href='#' id='all_quote<?php echo $brow['quote_id']?>' onclick="show_all_quotes(<?php echo $brow['quote_id']?>,<?php echo $brow["companyID"]?>); return false;"><?php echo $srno; ?></a></td>
											<td class="<?php echo $display_row_css; ?>" align="left">
												<a href='#' id='quote_ui<?php echo $brow['quote_id']?>' onclick="show_quote_table(<?php echo $brow['quote_id']?>, <?php echo $brow["companyID"]?>, '<?php echo $box_array; ?>'); return false;"><?php echo $brow["quote_id"]; ?></a>
											</td>
											<td class="<?php echo $display_row_css; ?>" align="left">
												<?php echo date("m/d/Y" , strtotime($brow["quote_date"])); ?></td>
											<td class="<?php echo $display_row_css; ?>" align="left">
												<a href="viewCompany.php?ID=<?php echo $brow["companyID"];?>" target="_blank">
												<?php echo $company_name; ?>
												</a>		
											</td>
											<td class="<?php echo $display_row_css; ?>" align="left">
												<?php echo $row_comp["territory"]; ?></td>
											<td class="<?php echo $display_row_css; ?>" align="left">
												<?php echo $acc_owner; ?><?php //echo " - ".$brow['user_initials']; ?></td>
											<td class="<?php echo $display_row_css; ?>" align="left">
												<?php echo $brow[$prefix."_item_length"] . "x" . $brow[$prefix."_item_width"] . "x" . $brow[$prefix."_item_height"]; ?></td>
											<td class="<?php echo $display_row_css; ?>" align="left">
												<?php echo $brow["$quantity_request"]; ?></td>
											<td class="<?php echo $display_row_css; ?>" align="left">
												<?php echo $brow["$frequency_order"]; ?></td>
											<td class="<?php echo $display_row_css; ?>" align="right">
												<?php 
												$desired_price = "";
												if($box_array == "Gaylord Totes")
												{
													$desired_price=$brow["sales_desired_price_".$prefix]; 
												}
												else{
													$desired_price=$brow[$prefix."_sales_desired_price"]; 
												}
												if($desired_price!=""){
													echo "$".$desired_price;
												}
												else{
													echo "$0";
												}
												?></td>
											<td class="<?php echo $display_row_css; ?>" align="left">
												<?php echo $brow[$prefix."_what_used_for"]; ?></td>
											<td class="<?php echo $display_row_css; ?>" align="center">
												<?php 
												$need_pallets = "";
												if($box_array == "Gaylord Totes")
												{
													$need_pallets=$brow["need_pallets"];
												}
												else{
													$need_pallets=$brow[$prefix."need_pallets"];
												}
												echo $need_pallets;
												 ?></td>
											<td class="<?php echo $display_row_css; ?>" align="left">
												<?php echo $brow[$notes]; ?></td>
										</tr>
										<?php
									}else{ 
										if(isset($_REQUEST["employee"]) && $_REQUEST["employee"] == $acc_owner){
											?>
											<tr>
												<td class="<?php echo $display_row_css; ?>" align="center">
													<a href='#' id='all_quote<?php echo $brow['quote_id']?>' onclick="show_all_quotes(<?php echo $brow['quote_id']?>,<?php echo $brow["companyID"]?>); return false;"><?php echo $srno; ?></a></td>
												<td class="<?php echo $display_row_css; ?>" align="left">
													<a href='#' id='quote_ui<?php echo $brow['quote_id']?>' onclick="show_quote_table(<?php echo $brow['quote_id']?>, <?php echo $brow["companyID"]?>, '<?php echo $box_array; ?>'); return false;"><?php echo $brow["quote_id"]; ?></a>
												</td>
												<td class="<?php echo $display_row_css; ?>" align="left">
													<?php echo date("m/d/Y" , strtotime($brow["quote_date"])); ?></td>
												<td class="<?php echo $display_row_css; ?>" align="left">
													<a href="viewCompany.php?ID=<?php echo $brow["companyID"];?>" target="_blank">
													<?php echo $company_name; ?>
													</a>		
												</td>
												<td class="<?php echo $display_row_css; ?>" align="left">
													<?php echo $row_comp["territory"]; ?></td>
												<td class="<?php echo $display_row_css; ?>" align="left">
													<?php echo $acc_owner; ?><?php //echo " - ".$brow['user_initials']; ?></td>
												<td class="<?php echo $display_row_css; ?>" align="left">
													<?php echo $brow[$prefix."_item_length"] . "x" . $brow[$prefix."_item_width"] . "x" . $brow[$prefix."_item_height"]; ?></td>
												<td class="<?php echo $display_row_css; ?>" align="left">
													<?php echo $brow["$quantity_request"]; ?></td>
												<td class="<?php echo $display_row_css; ?>" align="left">
													<?php echo $brow["$frequency_order"]; ?></td>
												<td class="<?php echo $display_row_css; ?>" align="right">
													<?php 
													$desired_price = "";
													if($box_array == "Gaylord Totes")
													{
														$desired_price=$brow["sales_desired_price_".$prefix]; 
													}
													else{
														$desired_price=$brow[$prefix."_sales_desired_price"]; 
													}
													if($desired_price!=""){
														echo "$".$desired_price;
													}
													else{
														echo "$0";
													}
													?></td>
												<td class="<?php echo $display_row_css; ?>" align="left">
													<?php echo $brow[$prefix."_what_used_for"]; ?></td>
												<td class="<?php echo $display_row_css; ?>" align="center">
													<?php 
													$need_pallets = "";
													if($box_array == "Gaylord Totes")
													{
														$need_pallets=$brow["need_pallets"];
													}
													else{
														$need_pallets=$brow[$prefix."need_pallets"];
													}
													echo $need_pallets;
													 ?></td>
												<td class="<?php echo $display_row_css; ?>" align="left">
													<?php echo $brow[$notes]; ?></td>
											</tr>
											<?php
										}
									}
									?>	
				
									<?php
										//
									$box_l = "";
									$territory = "";
									if($box_type_cnt==1){
										$gy[]= array('companyID' => $brow["companyID"], 'acc_owner' => $acc_owner, 'quote_id' => $brow["quote_id"], 'box_size' => $brow[$prefix."_item_length"] . "x" . $brow[$prefix."_item_width"] . "x" . $brow[$prefix."_item_height"], 'territory' => $territory, 'box_l' => $box_l, 'quote_date' => $brow["quote_date"], 'company_name' => $company_name, 'quantity_request' => $brow["$quantity_request"], 'frequency_order' => $brow["$frequency_order"], 'desired_price' => $desired_price, 'what_used_for' => $brow[$prefix."_what_used_for"], 'need_pallets' => $need_pallets, 'notes' => $brow[$notes], 'qty_array' => $qty_array, 'order_array' => $order_array);
									}
									if($box_type_cnt==1){
										$gy[]= array('companyID' => $brow["companyID"], 'acc_owner' => $acc_owner, 'quote_id' => $brow["quote_id"], 'box_size' => $brow[$prefix."_item_length"] . "x" . $brow[$prefix."_item_width"] . "x" . $brow[$prefix."_item_height"], 'territory' => $territory, 'box_l' => $box_l, 'quote_date' => $brow["quote_date"], 'company_name' => $company_name, 'quantity_request' => $brow["$quantity_request"], 'frequency_order' => $brow["$frequency_order"], 'desired_price' => $desired_price, 'what_used_for' => $brow[$prefix."_what_used_for"], 'need_pallets' => $need_pallets, 'notes' => $brow[$notes], 'qty_array' => $qty_array, 'order_array' => $order_array);
									}
										//
								}
							}
						}
						if($display_data=="yes"){
						?>
				
						<tr><td colspan="12" height="18px"></td></tr>
						</table></td></tr>
					<?php
						}
						
						$display_data="no";
					}//End foreach order array
				}//End foreach  quantity_arry
				?>
				</table>
				<?php
			}
			//End foreach  box_type_arry
		?>
		<?php
	} //End if btntool
	?>		
	
	</div>	

</body>
</html>