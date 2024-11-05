<?php
require("inc/header_session.php");
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

$initials =  $_COOKIE['userinitials'];
//$initials =  "WS" ;
$eid = $_COOKIE['b2b_id']; // this is the b2b.employees ID number, not the loop_employees ID number

if ($eid == 0) {
	$eid = 12;
} //$eid = 9;

if ($eid == 189) {
	$eid = 12;
}

db();
$x = "SELECT * from loop_employees WHERE b2b_id = " . $eid;
$viewres = db_query($x);
$row = array_shift($viewres);

$viewin = $pieces = explode(",", $row['views']);

$show_number = 250; //number of records to show.

?>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<title>Special OPS Report</title>
	<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
	<link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>

	<?php
	echo "<link rel='stylesheet' type='text/css' href='one_style.css' >";
	?>

	<script type="text/javascript">
		function update_nextstep(tmpcnt, mainid, empid, sortfield, sortorder, id, show, gc, ucbzw_flg) {
			if (document.getElementById("txt_nextstep" + tmpcnt).value == "") {
				alert("Please enter the Next Step details.");
				return;
			}
			//document.getElementById("sp_op_div_int" + tmpcnt).style.display = 'block';
			//document.getElementById("sp_op_div_int" + tmpcnt).innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />"; 						

			var nextstep_data = escape(document.getElementById("txt_nextstep" + tmpcnt).value);
			var nextcomm_data = document.getElementById("txt_nextcomm" + tmpcnt).value;

			var ucbzw_nextstep_data = "";
			var ucbzw_nextcomm_data = "";
			if (ucbzw_flg == 1) {
				var ucbzw_nextstep_data = escape(document.getElementById("txt_nextstep_ucbzw" + tmpcnt).value);
				var ucbzw_nextcomm_data = document.getElementById("txt_nextcomm_ucbzw" + tmpcnt).value;
			}

			var val_why_this_deal = escape(document.getElementById("txt_why_this_deal" + tmpcnt).value);
			var val_potential_annual_rev = document.getElementById("txt_potential_annual_rev" + tmpcnt).value;

			//document.location = "special_ops_report_update_test.php?companyID=" + mainid + "&txt_nextcomm="+ nextcomm_data + "&txt_nextstep=" + nextstep_data+"&empid="+empid+ "&sortfield="+ sortfield + "&sortorder=" + sortorder ;

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("sp_op_div" + tmpcnt).innerHTML = xmlhttp.responseText;
					//document.getElementById("sp_op_div_int" + tmpcnt).style.display = 'none';
				}
			}
			//alert(document.getElementById('totalcnt').value);

			xmlhttp.open("GET", "special_ops_report_update.php?companyID=" + mainid + "&ucbzw_flg=" + ucbzw_flg + "&ucbzw_nextstep_data=" + ucbzw_nextstep_data + "&ucbzw_nextcomm_data=" + ucbzw_nextcomm_data + "&txt_why_this_deal=" + val_why_this_deal + "&txt_potential_annual_rev=" + val_potential_annual_rev + "&txt_nextcomm=" + nextcomm_data + "&txt_nextstep=" + nextstep_data + "&empid=" + empid + "&sortfield=" + sortfield + "&sortorder=" + sortorder + "&id=" + id + "&show=" + show + "&gc=" + gc + "&tmpcnt=" + tmpcnt, true);
			xmlhttp.send();

		}

		function update_nextstepuzw(tmpcnt, mainid, empid, sortfield, sortorder, id, show, gc) {
			if (document.getElementById("txt_nextstep" + tmpcnt).value == "") {
				alert("Please enter the Next Step details.");
				return;
			}
			document.getElementById("sp_op_div_int" + tmpcnt).style.display = 'block';
			document.getElementById("sp_op_div_int" + tmpcnt).innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";

			var nextstep_data = escape(document.getElementById("txt_nextstep" + tmpcnt).value);
			var nextcomm_data = document.getElementById("txt_nextcomm" + tmpcnt).value;

			var val_why_this_deal = escape(document.getElementById("txt_why_this_deal" + tmpcnt).value);
			var val_potential_annual_rev = document.getElementById("txt_potential_annual_rev" + tmpcnt).value;


			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("sp_op_div" + tmpcnt).innerHTML = xmlhttp.responseText;
					document.getElementById("sp_op_div_int" + tmpcnt).style.display = 'none';
				}
			}

			xmlhttp.open("GET", "special_ops_report_update_uzw.php?companyID=" + mainid + "&txt_why_this_deal=" + val_why_this_deal + "&txt_potential_annual_rev=" + val_potential_annual_rev + "&txt_nextcomm=" + nextcomm_data + "&txt_nextstep=" + nextstep_data + "&empid=" + empid + "&sortfield=" + sortfield + "&sortorder=" + sortorder + "&id=" + id + "&show=" + show + "&gc=" + gc + "&tmpcnt=" + tmpcnt, true);
			xmlhttp.send();

		}

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

		function showtask(compid) {
			selectobject = document.getElementById("showcrmid" + compid);
			n_left = f_getPosition(selectobject, 'Left');
			n_top = f_getPosition(selectobject, 'Top');

			document.getElementById('light_todo').style.left = (n_left - 500) + 'px';
			document.getElementById('light_todo').style.top = n_top + 20 + 'px';

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("light_todo").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';document.getElementById('fade_todo').style.display='none'>Close</a> &nbsp;<center></center><br/>" + xmlhttp.responseText;
					document.getElementById("light_todo").style.display = 'block';

				}
			}

			xmlhttp.open("GET", "todolist_view_special_op.php?fromrep=y&compid=" + compid, true);
			xmlhttp.send();
		}

		function todoitem_edit(unqid, compid) {

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					selectobject = document.getElementById("showcrmid" + compid);
					n_left = f_getPosition(selectobject, 'Left');
					n_top = f_getPosition(selectobject, 'Top');

					document.getElementById("light_todo").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';>Close</a> &nbsp;<center></center><br/>" + xmlhttp.responseText;
					document.getElementById('light_todo').style.display = 'block';

					document.getElementById('light_todo').style.left = (n_left - 700) + 'px';
					document.getElementById('light_todo').style.top = n_top - 40 + 'px';
					document.getElementById('light_todo').style.width = 700 + 'px';
					document.getElementById('light_todo').style.height = 400 + 'px';

				}
			}



			xmlhttp.open("GET", "todolist_edit_data.php?compid=" + compid + "&unqid=" + unqid, true);
			xmlhttp.send();
		}

		function update_edit_item(unqid) {

			//document.getElementById("light_todo").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />"; 						

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}

			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

					document.getElementById("light_todo").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';document.getElementById('fade_todo').style.display='none'>Close</a> &nbsp;<center></center><br/>" + xmlhttp.responseText;
					document.getElementById('light_todo').style.display = 'block';
				}
			}

			var compid = document.getElementById('todo_companyID').value;
			var todo_message = document.getElementById('todo_message_edit').value;
			var todo_employee = document.getElementById('todo_employee_edit').value;
			var todo_date = document.getElementById('todo_date_edit').value;
			var task_priority = document.getElementById('task_priority_edit').value;

			xmlhttp.open("POST", "todolist_update.php?inedit_mode=1&unqid=" + unqid + "&compid=" + compid + "&todo_message=" + encodeURIComponent(todo_message) + "&todo_employee=" + todo_employee + "&todo_date=" + todo_date + "&task_priority=" + encodeURIComponent(task_priority), true);
			xmlhttp.send();
		}


		function showcrm(compid) {
			selectobject = document.getElementById("showcrmid" + compid);
			n_left = f_getPosition(selectobject, 'Left');
			n_top = f_getPosition(selectobject, 'Top');

			document.getElementById('light_todo').style.left = (n_left - 600) + 'px';
			document.getElementById('light_todo').style.top = n_top - 20 + 'px';

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("light_todo").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';document.getElementById('fade_todo').style.display='none'>Close</a> &nbsp;<center></center><br/>" + xmlhttp.responseText;
					document.getElementById("light_todo").style.display = 'block';
				}
			}

			xmlhttp.open("GET", "special_ops_report_crm.php?compid=" + compid, true);
			xmlhttp.send();
		}

		function addtodoitem() {
			document.getElementById("todo_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("todo_div").innerHTML = xmlhttp.responseText;
				}
			}

			var compid = document.getElementById('todo_companyID').value;
			var todo_message = document.getElementById('todo_message').value;
			var todo_employee = document.getElementById('todo_employee').value;
			var todo_date = document.getElementById('todo_date').value;
			var task_priority = document.getElementById('task_priority').value;

			xmlhttp.open("GET", "todolist_update.php?compid=" + compid + "&todo_message=" + encodeURIComponent(todo_message) + "&todo_employee=" + todo_employee + "&todo_date=" + todo_date + "&task_priority=" + encodeURIComponent(task_priority), true);
			xmlhttp.send();
		}

		function todoitem_markcomp(unqid, compid) {
			document.getElementById("todo_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("todo_div").innerHTML = xmlhttp.responseText;
				}
			}

			xmlhttp.open("GET", "todolist_update.php?compid=" + compid + "&unqid=" + unqid + "&markcomp=1", true);
			xmlhttp.send();
		}

		function update_opportunity_nextstep(recid, rnum) {

			var rowdata = document.getElementById("sp_opportunity_" + recid).innerHTML;
			var nxstep = document.getElementById("opp_nxtstep" + recid).value;
			var nxstepdt = document.getElementById("opp_nxtstep_date" + recid).value;
			document.getElementById("sp_opportunity_" + recid).innerHTML = "<td colspan='15'>Loading .....<img src='images/wait_animated.gif' /></td>";

			if (nxstep != "" && nxstepdt != "") {

				if (window.XMLHttpRequest) {
					xhr = new XMLHttpRequest();
				} else {
					xhr = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && xhr.status == 200) {

						if (xhr.responseText == 99999) {
							alert("No change in record.");
							document.getElementById("sp_opportunity_" + recid).innerHTML = rowdata;
						} else {
							alert("Record Updated.");
							document.getElementById("sp_opportunity_" + recid).innerHTML = xhr.responseText;
						}

					}
				}

				xhr.open("POST", "opportunity_update_record.php?recid=" + recid + "&nxstep=" + encodeURIComponent(nxstep) + "&nxstepdt=" + nxstepdt + "&rnum=" + rnum + "&record=update_record", true);
				xhr.send();

			} else {

				if (nxstep == "") {
					alert("Please fill Next step.");
					document.getElementById("sp_opportunity_" + recid).innerHTML = rowdata;
					return false;
				} else {
					alert("Please fill Next step date.");
					document.getElementById("sp_opportunity_" + recid).innerHTML = rowdata;
					return false;
				}

			}
		}

		function view_opportunity(recid) {
			selectobject = document.getElementById("sp_opportunity_" + recid);
			n_left = f_getPosition(selectobject, 'Left');
			n_top = f_getPosition(selectobject, 'Top');

			document.getElementById('light_todo').style.left = (n_left + 250) + 'px';
			document.getElementById('light_todo').style.top = n_top - 20 + 'px';

			if (window.XMLHttpRequest) {
				xhr = new XMLHttpRequest();
			} else {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}

			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4 && xhr.status == 200) {
					document.getElementById("light_todo").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';>Close</a> &nbsp;<center></center><br/>" + xhr.responseText;
					document.getElementById("light_todo").style.display = 'block';
				}
			}

			xhr.open("POST", "opportunity_view_record.php?recid=" + recid, true);
			xhr.send();
		}

		function edit_opportunity(recid, rno) {
			selectobject = document.getElementById("sp_opportunity_" + recid);
			n_left = f_getPosition(selectobject, 'Left');
			n_top = f_getPosition(selectobject, 'Top');

			document.getElementById('light_todo').style.left = (n_left + 250) + 'px';
			document.getElementById('light_todo').style.top = n_top - 20 + 'px';

			if (window.XMLHttpRequest) {
				xhr = new XMLHttpRequest();
			} else {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}

			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4 && xhr.status == 200) {
					document.getElementById("light_todo").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';>Close</a> &nbsp;<center></center><br/>" + xhr.responseText;
					document.getElementById("light_todo").style.display = 'block';
				}
			}

			xhr.open("POST", "opportunity_edit_record.php?recid=" + recid + "&rno=" + rno, true);
			xhr.send();
		}

		function get_margin() {
			var revenue = document.getElementById("opp_revenue").value;
			var profit = document.getElementById("opp_profit").value;

			if (revenue == "" || revenue == null || revenue == "0") {
				document.getElementById("opp_margin").value = "0%";
			} else {
				document.getElementById("opp_margin").value = Math.round((profit / revenue) * 100) + "%";
			}
		}

		function oppstage_change(typeid) {
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}

			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("opp_stage").innerHTML = xmlhttp.responseText;
				}
			}

			xmlhttp.open("GET", "opp_type_stage.php?opp_typeid=" + typeid, true);
			xmlhttp.send();
		}

		function opportunity_update_record(recid, rno) {
			var rowdata = document.getElementById("sp_opportunity_" + recid).innerHTML;

			document.getElementById("sp_opportunity_" + recid).innerHTML = "<td colspan='15'> Loading .....<img src='images/wait_animated.gif' /></td>";

			var owner = document.getElementById("opp_owner").value;
			var name = document.getElementById("opp_name").value;
			var description = document.getElementById("opp_description").value;
			var compid = document.getElementById("opp_company").value;
			var campaign = document.getElementById("opp_campaign").value;
			var typeid = document.getElementById("opp_type").value;
			var revenue = document.getElementById("opp_revenue").value;
			var profit = document.getElementById("opp_profit").value;
			var closedt = document.getElementById("close_date").value;
			var nxtstep = document.getElementById("opp_nextstep").value;
			var nxtdt = document.getElementById("nxstep_date").value;
			var stage = document.getElementById("opp_stage").value;
			var probability = document.getElementById("opp_probability").value;

			if (document.getElementById("opp_active").checked) {
				var actst = 1;

			} else {
				var actst = 0;
			}

			if (window.XMLHttpRequest) {
				xhr = new XMLHttpRequest();
			} else {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}

			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4 && xhr.status == 200) {
					document.getElementById("light_todo").style.display = 'none';
					alert("Record Updated Successfully");
					document.getElementById("sp_opportunity_" + recid).innerHTML = xhr.responseText;
				}
			}

			if ((typeid == 1 && stage == 8) || (typeid == 2 && stage == 17) || (typeid == 3 && stage == 25)) {
				if (confirm("Do you marked this opportunity as Complete.") == true) {
					var actst = 2;

					xhr.open("GET", "opportunity_edit_record_special.php?recid=" + recid + "&owner=" + owner + "&name=" + encodeURIComponent(name) + "&description=" + encodeURIComponent(description) + "&compid=" + compid + "&campaign=" + campaign + "&typeid=" + typeid + "&revenue=" + revenue + "&profit=" + profit + "&closedt=" + closedt + "&nxtstep=" + nxtstep + "&nxtdt=" + nxtdt + "&stage=" + stage + "&probability=" + probability + "&actst=" + actst + "&rno=" + rno + "&record=complete", true);
				}

			} else {



				xhr.open("GET", "opportunity_edit_record_special.php?recid=" + recid + "&owner=" + owner + "&name=" + encodeURIComponent(name) + "&description=" + encodeURIComponent(description) + "&compid=" + compid + "&campaign=" + campaign + "&typeid=" + typeid + "&revenue=" + revenue + "&profit=" + profit + "&closedt=" + closedt + "&nxtstep=" + nxtstep + "&nxtdt=" + nxtdt + "&stage=" + stage + "&probability=" + probability + "&actst=" + actst + "&rno=" + rno + "&record=update", true);
			}

			xhr.send();

		}

		function show_opportunity(compid) {

			var e = document.getElementById("opportunity_tbl" + compid);
			if (e.style.display == "none") {
				hide_all_opportunity();
				e.style.display = "block";
			} else {
				e.style.display = "none";
			}

		}

		function hide_all_opportunity() {
			var oppotbl = document.getElementsByClassName("opportunity_tbl");
			for (i = 0; i < oppotbl.length; i++) {
				oppotbl[i].style.display = "none";
			}
		}

		function showopportunity_crm(recid, compid) {
			selectobject = document.getElementById("opportunity_tbl" + compid);
			n_left = f_getPosition(selectobject, 'Left');
			n_top = f_getPosition(selectobject, 'Top');

			document.getElementById('light_todo').style.left = (n_left + 200) + 'px';
			document.getElementById('light_todo').style.top = n_top - 250 + 'px';

			if (window.XMLHttpRequest) {
				xhr = new XMLHttpRequest();
			} else {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}

			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4 && xhr.status == 200) {
					document.getElementById("light_todo").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';document.getElementById('fade_todo').style.display='none'>Close</a> &nbsp;<center></center><br/>" + xhr.responseText;
					document.getElementById("light_todo").style.display = 'block';
				}
			}

			xhr.open("GET", "special_ops_report_opportunity_crm.php?recid=" + recid + "&compid=" + compid, true);
			xhr.send();
		}

		function isNumberKey(evt) {
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if (charCode != 46 && charCode > 31 &&
				(charCode < 48 || charCode > 57))
				return false;

			return true;
		}
	</script>

	<script language="JavaScript" src="inc/CalendarPopup.js"></script>
	<script language="JavaScript">
		document.write(getCalendarStyles());
	</script>

	<script language="JavaScript">
		var cal1xx = new CalendarPopup("listdiv");

		cal1xx.showNavigationDropdowns();

		var cal1nxxrep = new CalendarPopup("listdivnew");

		cal1nxxrep.showNavigationDropdowns();

		var calclosedt = new CalendarPopup("listdiv_cdt");

		calclosedt.showNavigationDropdowns();

		var calnxdt = new CalendarPopup("listdiv_nxdt");

		calnxdt.showNavigationDropdowns();

		var ca21nxx = new CalendarPopup("listdiv1");

		ca21nxx.showNavigationDropdowns();
	</script>

	<style type="text/css">
		.nowrap_style {
			white-space: nowrap;
		}

		.innercol_opp {
			font-size: 10px;
			font-family: "Arial, Helvetica, sans-serif";
			color: #333333;
			background-color: #E4E4E4;
		}

		.px-10 {
			padding: 10px 10px 0 10px;
		}
	</style>

	<script language="JavaScript" SRC="inc/CalendarPopup.js"></script>
	<script language="JavaScript" SRC="inc/general.js"></script>

	<script language="JavaScript">
		document.write(getCalendarStyles());
	</script>

	<script LANGUAGE="JavaScript">
		var cal2xx = new CalendarPopup("listdiv");
		cal2xx.showNavigationDropdowns();
	</script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<style>
		.tooltiptext {
			font-size: 8px;
			font-style: italic;
		}


		.main_data_css {
			margin: 0 auto;
			width: 100%;
			height: auto;
			clear: both !important;
			padding-top: 35px;
			margin-left: 10px;
			margin-right: 10px;
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
			top: 8%;
			left: 10%;
			width: 60%;
			height: 80%;
			padding: 16px;
			border: 1px solid gray;
			background-color: white;
			z-index: 1002;
			overflow: auto;
		}

		span.infotxt {
			text-decoration: underline;
		}

		span.infotxt:hover {
			text-decoration: none;
			background: #ffffff;
			z-index: 6;
		}

		span.infotxt span {
			position: absolute;
			left: -9999px;
			width: 150px;
			color: black;
			margin-top: 15px;
			padding: 3px 3px 3px 3px;
			z-index: 6;
			border: 1px solid black;
		}

		span.infotxt:hover span {
			left: 13%;
			background: #ffffff;
		}
	</style>
</head>

<body>
	<div id="light_todo" class="white_content"></div>
	<div id="fade_todo" class="black_overlay"></div>

	<?php include("inc/header.php"); ?>

	<div class="main_data_css">
		<div class="dashboard_heading" style="float: left;">
			<div style="float: left;">Special OPS Report</div>
		</div>
		<i>This report shows the user all special opportunities (special ops) for B2B Sales and B2B Purchasing departments. For a proper explanation of what "Special Ops" is, and what defines an account as such, open the What is SpecOps SOP.</i>
		<br>
		<form method="post" name="sp_report" id="sp_report" action="special_ops_report.php">
			<table border="0" width="1400">
				<tr>
					<td colspan="2" align="left" height="50">
						<table>
							<tr>

								<td valign="top" width="800px">

									Show Records as:
									<select name="emp_filter" id="emp_filter">
										<option value="1" <?php if ($_REQUEST["emp_filter"] == 1) echo " selected "; ?>>Owner Only</option>
										<option value="2" <?php if ($_REQUEST["emp_filter"] == 2) echo " selected "; ?>>Viewable Only</option>
										<option value="3" <?php if ($_REQUEST["emp_filter"] == 3) echo " selected "; ?>>Owner and Viewable</option>
									</select>

									Employee:
									<select name="owner_name" id="owner_name">
										<option value="">All</option>
										<?php
										db();
										$ownqry = db_query("Select * from loop_employees where status='Active' and b2b_id > 0 order by name");
										while ($owner_row = array_shift($ownqry)) {
										?>
											<option value="<?php echo $owner_row["b2b_id"] ?>" <? if (isset($_REQUEST["owner_name"])) {
																									if ($_REQUEST["owner_name"] == $owner_row["b2b_id"]) {
																										echo " selected ";
																									}
																								} else {
																									if ($_COOKIE['b2b_id'] == $owner_row["b2b_id"]) {
																										echo " selected ";
																									}
																								} ?>><?php echo $owner_row["name"] ?></option>
										<?php
										}
										?>
									</select>

									<input type='submit' id='btnsubmit' name='btnsubmit' value="Load Report" />
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr style="padding-bottom:1px">
					<td width=5 valign=top style="padding-bottom:1px" border=1px>
					</td>

					<td width='1400' valign='top'>
						<?php

						$arr_new = array();
						if (isset($_REQUEST["btnsubmit"]) || isset($_REQUEST["sk"])) {
							$dt_view_qry = "Select * from status order by sort_order";
							db_b2b();
							$dt_view_res = db_query($dt_view_qry);
							while ($status_data = array_shift($dt_view_res)) {

								if ($_REQUEST["chk_status_" . $status_data["id"]] == 1) {
									$arr_new[] = $status_data["id"];
								}
							}

							$cnt_no = 0;

							//$_REQUEST["status_list"],
							showStatusesDashboard_opsrep($arr_new, $eid, $show_number, "all", $_REQUEST["owner_name"], $_REQUEST["date_from"], $_REQUEST["date_to"], $_REQUEST["emp_filter"]);
						}

						//$newspl_check_box, 
						function showStatusesDashboard_opsrep($arrVal, $eid, $limit, $period, $owner_name, $date_from, $date_to, $emp_filter)
						{
							//
						?>
							<table width="1400" border="0" cellspacing="1" cellpadding="1">

								<tr>
									<td bgcolor="#D9F2FF" width="5%" align="center">
										<font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
											Account Owner
											<a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=ei&so=A&show=" . $_REQUEST["show"] . "&owner_name=" . $owner_name . "&emp_filter=" . $emp_filter . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>"><img src='images/sort_asc.png' width='6px;' height='12px;'></a>
											<a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=ei&so=D&show=" . $_REQUEST["show"] . "&owner_name=" . $owner_name . "&emp_filter=" . $emp_filter . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>"><img src='images/sort_desc.png' width='6px;' height='12px;'></a>
										</font>
									</td>

									<td width="7%" bgcolor="#D9F2FF">
										<font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
											Account Status
											<a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=nstatus&so=A&show=" . $_REQUEST["show"] . "&owner_name=" . $owner_name . "&emp_filter=" . $emp_filter . "&btnsubmit=" . $_REQUEST["btnsubmit"] . "&statusid=" . $_REQUEST["statusid"] . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>"><img src='images/sort_asc.png' width='6px;' height='12px;'></a>
											<a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=nstatus&so=D&show=" . $_REQUEST["show"] . "&owner_name=" . $owner_name . "&emp_filter=" . $emp_filter . "&btnsubmit=" . $_REQUEST["btnsubmit"] . "&statusid=" . $_REQUEST["statusid"] . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>"><img src='images/sort_desc.png' width='6px;' height='12px;'></a>
										</font><br>
										<span class="tooltiptext">(Last Changed XXX Days Ago)</span>
									</td>

									<td width="7%" bgcolor="#D9F2FF">
										<font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
											Company name
											<a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=nname&so=A&show=" . $_REQUEST["show"] . "&owner_name=" . $owner_name . "&emp_filter=" . $emp_filter . "&btnsubmit=" . $_REQUEST["btnsubmit"] . "&statusid=" . $_REQUEST["statusid"] . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>"><img src='images/sort_asc.png' width='6px;' height='12px;'></a>
											<a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=nname&so=D&show=" . $_REQUEST["show"] . "&owner_name=" . $owner_name . "&emp_filter=" . $emp_filter . "&btnsubmit=" . $_REQUEST["btnsubmit"] . "&statusid=" . $_REQUEST["statusid"] . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>"><img src='images/sort_desc.png' width='6px;' height='12px;'></a>
										</font>
									</td>

									<td bgcolor="#D9F2FF" width="20%" align="center">
										<font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
											<?php if ($row["sales_flg"] == "3") { ?>
												UCBZW Next Step
												<a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=uzns&so=A&show=" . $_REQUEST["show"] . "&owner_name=" . $owner_name . "&emp_filter=" . $emp_filter . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>"><img src='images/sort_asc.png' width='6px;' height='12px;'></a>
												<a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=uzns&so=D&show=" . $_REQUEST["show"] . "&owner_name=" . $owner_name . "&emp_filter=" . $emp_filter . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>"><img src='images/sort_desc.png' width='6px;' height='12px;'></a>

											<?php } else { ?>
												Next Step
												<a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=ns&so=A&show=" . $_REQUEST["show"] . "&owner_name=" . $owner_name . "&emp_filter=" . $emp_filter . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>"><img src='images/sort_asc.png' width='6px;' height='12px;'></a>
												<a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=ns&so=D&show=" . $_REQUEST["show"] . "&owner_name=" . $owner_name . "&emp_filter=" . $emp_filter . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>"><img src='images/sort_desc.png' width='6px;' height='12px;'></a>
											<?php } ?>
										</font>
									</td>
									<td bgcolor="#D9F2FF" width="10%" align="center">
										<font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
											Opportunities
										</font>
									</td>
									<td bgcolor="#D9F2FF" width="9%" align="center">
										<font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
											Why This Deal?
											<a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=sort_why_this_deal&so=A&show=" . $_REQUEST["show"] . "&owner_name=" . $owner_name . "&emp_filter=" . $emp_filter . "&statusid=" . $_REQUEST["statusid"] . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>"><img src='images/sort_asc.png' width='6px;' height='12px;'></a>
											<a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=sort_why_this_deal&so=D&show=" . $_REQUEST["show"] . "&owner_name=" . $owner_name . "&emp_filter=" . $emp_filter . "&statusid=" . $_REQUEST["statusid"] . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>"><img src='images/sort_desc.png' width='6px;' height='12px;'></a>
										</font>
									</td>
									<td bgcolor="#D9F2FF" width="6%" align="center">
										<font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
											Potential Annual Revenue
											<a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=sort_potential_annual_rev&so=A&show=" . $_REQUEST["show"] . "&owner_name=" . $owner_name . "&emp_filter=" . $emp_filter . "&statusid=" . $_REQUEST["statusid"] . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>"><img src='images/sort_asc.png' width='6px;' height='12px;'></a>
											<a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=sort_potential_annual_rev&so=D&show=" . $_REQUEST["show"] . "&owner_name=" . $owner_name . "&emp_filter=" . $emp_filter . "&statusid=" . $_REQUEST["statusid"] . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>"><img src='images/sort_desc.png' width='6px;' height='12px;'></a>
										</font>
									</td>

									<td bgcolor="#D9F2FF" nowrap width="2%" align="center">
										<font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><b>Tasks</b></font>
									</td>
									<td bgcolor="#D9F2FF" width="9%" align="center">
										<font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
											Last Communication
											<a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=lc&so=A&show=" . $_REQUEST["show"] . "&owner_name=" . $owner_name . "&emp_filter=" . $emp_filter . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>"><img src='images/sort_asc.png' width='6px;' height='12px;'></a>
											<a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=lc&so=D&show=" . $_REQUEST["show"] . "&owner_name=" . $owner_name . "&emp_filter=" . $emp_filter . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>"><img src='images/sort_desc.png' width='6px;' height='12px;'></a>
										</font>
									</td>
									<td bgcolor="#D9F2FF" width="9%" align="center">
										<font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
											<?php if ($row["sales_flg"] == "3") { ?>
												UCBZW Next Communication
												<a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=uznd&so=A&show=" . $_REQUEST["show"] . "&owner_name=" . $owner_name . "&emp_filter=" . $emp_filter . "&statusid=" . $_REQUEST["statusid"] . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>"><img src='images/sort_asc.png' width='6px;' height='12px;'></a>
												<a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=uznd&so=D&show=" . $_REQUEST["show"] . "&owner_name=" . $owner_name . "&emp_filter=" . $emp_filter . "&statusid=" . $_REQUEST["statusid"] . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>"><img src='images/sort_desc.png' width='6px;' height='12px;'></a>
											<?php } else { ?>

												Next Communication
												<a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=nd&so=A&show=" . $_REQUEST["show"] . "&owner_name=" . $owner_name . "&emp_filter=" . $emp_filter . "&statusid=" . $_REQUEST["statusid"] . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>"><img src='images/sort_asc.png' width='6px;' height='12px;'></a>
												<a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=nd&so=D&show=" . $_REQUEST["show"] . "&owner_name=" . $owner_name . "&emp_filter=" . $emp_filter . "&statusid=" . $_REQUEST["statusid"] . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>"><img src='images/sort_desc.png' width='6px;' height='12px;'></a>
											<?php } ?>
										</font>
									</td>

									<td bgcolor="#D9F2FF" width="4%" align="center">
										<font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><b>Save</b></font>
									</td>
									<td bgcolor="#D9F2FF" width="1%" align="center">&nbsp;</td>
								</tr>
								<?php
								//

								if ($_REQUEST["sk"] != "") {
									if ($eid > 0) {
										$tmp_sortorder = "";
										if ($_REQUEST["sk"] == "dt") {
											$tmp_sortorder = "companyInfo.dateCreated";
										} elseif ($_REQUEST["sk"] == "age") {
											$tmp_sortorder = "companyInfo.dateCreated";
										} elseif ($_REQUEST["sk"] == "cname") {
											$tmp_sortorder = "companyInfo.company";
										} elseif ($_REQUEST["sk"] == "qty") {
											$tmp_sortorder = "companyInfo.company";
										} elseif ($_REQUEST["sk"] == "nname") {
											$tmp_sortorder = "companyInfo.nickname";
										} elseif ($_REQUEST["sk"] == "nd") {
											$tmp_sortorder = "companyInfo.next_date";
										} elseif ($_REQUEST["sk"] == "ns") {
											$tmp_sortorder = "companyInfo.next_step";
										} elseif ($_REQUEST["sk"] == "sort_why_this_deal") {
											$tmp_sortorder = "companyInfo.why_this_deal";
										} elseif ($_REQUEST["sk"] == "sort_potential_annual_rev") {
											$tmp_sortorder = "companyInfo.potential_annual_rev";
										} elseif ($_REQUEST["sk"] == "ei") {
											$tmp_sortorder = "employees.initials";
										} elseif ($_REQUEST["sk"] == "lc") {
											$tmp_sortorder = "companyInfo.last_contact_date";
										} elseif ($_REQUEST["sk"] == "nstatus") {
											$tmp_sortorder = "companyInfo.status";
										} else {
											$tmp_sortorder = "companyInfo." . $_REQUEST["sk"];
										}

										if ($so == "A") {
											$tmp_sort = "D";
										} else {
											$tmp_sort = "A";
										}
										$sql_qry = "update employees set sort_fieldname = '" . $tmp_sortorder . "', sort_order='" . $tmp_sort . "' where employeeID = " . $eid;
										db_b2b();
										db_query($sql_qry);
									}

									if ($_REQUEST["sk"] == "dt") {
										$skey = " ORDER BY companyInfo.dateCreated";
									} elseif ($_REQUEST["sk"] == "age") {
										$skey = " ORDER BY companyInfo.dateCreated";
									} elseif ($_REQUEST["sk"] == "contact") {
										$skey = " ORDER BY companyInfo.contact";
									} elseif ($_REQUEST["sk"] == "cname") {
										$skey = " ORDER BY companyInfo.company";
									} elseif ($_REQUEST["sk"] == "nname") {
										$skey = " ORDER BY companyInfo.nickname";
									} elseif ($_REQUEST["sk"] == "city") {
										$skey = " ORDER BY companyInfo.city";
									} elseif ($_REQUEST["sk"] == "state") {
										$skey = " ORDER BY companyInfo.state";
									} elseif ($_REQUEST["sk"] == "zip") {
										$skey = " ORDER BY companyInfo.zip";
									} elseif ($_REQUEST["sk"] == "nd") {
										$skey = " ORDER BY companyInfo.next_date";
									} elseif ($_REQUEST["sk"] == "ns") {
										$skey = " ORDER BY companyInfo.next_step";
									} elseif ($_REQUEST["sk"] == "uznd") {
										$skey = " ORDER BY companyInfo.ucbzw_next_date";
									} elseif ($_REQUEST["sk"] == "uzns") {
										$skey = " ORDER BY companyInfo.ucbzw_next_step";
									} elseif ($_REQUEST["sk"] == "sort_why_this_deal") {
										$skey = " order by  companyInfo.why_this_deal";
									} elseif ($_REQUEST["sk"] == "sort_potential_annual_rev") {
										$skey = " order by companyInfo.potential_annual_rev";
									} elseif ($_REQUEST["sk"] == "ei") {
										$skey = " ORDER BY employees.initials";
									} elseif ($_REQUEST["sk"] == "nstatus") {
										$skey = " ORDER BY companyInfo.status";
									} elseif ($_REQUEST["sk"] == "lc") {
										$skey = " ORDER BY companyInfo.last_contact_date";
									}

									if ($_REQUEST["so"] != "") {
										if ($_REQUEST["so"] == "A") {
											$sord = " ASC";
										}
										if ($_REQUEST["so"] == "D") {
											$sord = " DESC";
										}
									} else {
										$sord = " DESC";
									}
								} else {
									if ($eid > 0) {
										$sql_qry = "Select sort_fieldname, sort_order from employees where employeeID = " . $eid .  "";
										db_b2b();
										$dt_view_res = db_query($sql_qry);
										while ($row = array_shift($dt_view_res)) {
											if ($row["sort_fieldname"] != "") {
												if ($row["sort_order"] == "A") {
													$sord = " ASC";
												} else {
													$sord = " DESC";
												}
												$skey = " ORDER BY " . $row["sort_fieldname"];
											} else {
												$skey = " ORDER BY companyInfo.dateCreated ";
												$sord = " DESC";
											}
										}
									} else {
										$skey = " ORDER BY companyInfo.dateCreated ";
										$sord = " DESC";
									}
								}


								$x = "Select companyInfo.status as accstatus, companyInfo.why_this_deal, companyInfo.one_drive_url, companyInfo.special_ops, companyInfo.ucbzw_flg, companyInfo.potential_annual_rev, 
								companyInfo.shipCity, companyInfo.shipState, companyInfo.id AS I, companyInfo.contact AS C,  companyInfo.dateCreated AS D, companyInfo.company AS CO, 
								companyInfo.nickname AS NN, companyInfo.phone AS PH, companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, 
								companyInfo.last_contact_date AS LD, companyInfo.next_date AS ND, companyInfo.ucbzw_next_step AS UZWNS, companyInfo.ucbzw_next_date AS UZWND, 
								employees.initials AS EI from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID or companyInfo.ucbzw_account_owner = employees.employeeID 
								where companyInfo.status in (3,94,95,96,97,98,99,100,101,32,50,102,103,51,56,104,105,24,43,106,52,33,31,65,107,108,109,44,112,110,111,70,115,73,116,117,118,119,120,55,122,123,124,38,125,126,127,121,49,113,114)";

								$x .= " and companyInfo.special_ops = 1 ";



								if ($owner_name != "" && $emp_filter == 1) {
									$x .= " and (companyInfo.assignedto = " . $owner_name . " or companyInfo.ucbzw_account_owner =" . $owner_name . ") ";
								}
								if ($owner_name != "" && $emp_filter == 2) {
									$x = $x . " AND ( companyInfo.viewable1=" . $owner_name . " OR companyInfo.viewable2=" . $owner_name . "  OR companyInfo.viewable3=" . $owner_name . " OR companyInfo.viewable4=" . $owner_name . ")";
								}
								if ($owner_name != "" && $emp_filter == 3) {
									$x = $x . " AND ( companyInfo.assignedto = " . $owner_name . " or companyInfo.ucbzw_account_owner =" . $owner_name . " or companyInfo.viewable1=" . $owner_name . " OR companyInfo.viewable2=" . $owner_name . "  OR companyInfo.viewable3=" . $owner_name . " OR companyInfo.viewable4=" . $owner_name . ")";
								}

								if ($date_from != "" && $date_to != "") {
									$x .= " and (companyInfo.dateCreated >= '" . date("Y-m-d", strtotime($date_from)) . "' and companyInfo.dateCreated <= '" . date("Y-m-d", strtotime($date_to)) . "') ";
								}

								if ($_REQUEST["gc"] == 1) {
									$x = $x . " AND companyInfo.haveNeed LIKE 'Need Boxes'";
								}
								if ($_REQUEST["gc"] == 2) {
									$x = $x . " AND companyInfo.haveNeed LIKE 'Have Boxes'";
								}

								if ($period != "all") {
									if ($period == "today") {
										$x = $x . " AND companyInfo.next_date = CURDATE() ";
									}
									if ($period == "upcoming") {
										$x = $x . " AND (companyInfo.next_date >= '" . date('Y-m-d') . "' and companyInfo.next_date <= '" . date('Y-m-d', strtotime("+7 days")) . "')";
									}
									if ($period == "lastweek") {
										$x = $x . " AND (companyInfo.next_date <= '" . date('Y-m-d') . "' and companyInfo.next_date >= '" . date('Y-m-d', strtotime("-7 days")) . "')";
									}
									if ($period == "old") {
										$x = $x . " AND companyInfo.next_date < CURDATE() AND companyInfo.next_date > '1900-01-01'";
									}
									if ($period == "none") {
										$x = $x . " AND companyInfo.next_date IS NULL";
									}
								}

								if ($_REQUEST["show"] == "search") {
									$arrFields = array("contact", "contactTitle", "contact2", "contactTitle2", "company", "industry", "address", "address2", "city", "state", "zip", "country", "phone", "fax", "email", "website", "order_no", "choose", "ccheck", "billing_first_name", "billing_last_name", "billing_address1", "billing_address2", "billing_city", "billing_state", "billing_zip", "billing_question", "information", "help", "experience", "mail_lists", "card_owner", "shipContact", "shipTitle", "shipAddress", "shipAddress2", "shipCity", "shipState", "shipZip", "shipPhone", "status", "status2", "haveNeed", "notes", "dateCreated", "dateLastAccessed", "poNumber", "terms", "rep", "TBD", "shipDate", "via", "quoteNote", "howHear", "pickupDay", "pickupWeek", "lastPickup", "req_type", "vendor", "int_notes", "green_initiative", "nickname", "next_step");

									$st = explode(' ', $_REQUEST["searchterm"]);

									$x = $x . " AND ( ";

									foreach ($st as $sti) {
										$i = 1;
										$x = $x . " ( ";
										foreach ($arrFields as $nm) {

											if ($i == 1) {;
											} else {
												$x = $x . " OR ";
											}
											$x = $x . " companyInfo." . $nm . " LIKE '%" . $sti . "%'";
											$i++;
										}
										$x = $x . " ) " . $_REQUEST["andor"] . " ";
									}

									if ($_REQUEST["andor"] == "AND") {
										$x = $x . " TRUE ) ";
									} else {
										$x = $x . " FALSE ) ";
									}

									if ($_REQUEST["state"] != "ALL")
										$x = $x . " AND companyInfo.state LIKE '" . $_REQUEST["state"] . "' ";
								}

								$x = $x . " GROUP BY companyInfo.id " . $skey . $sord . " ";
								db_b2b();
								$data_res = db_query($x);
								$data_res_No_Limit = db_query($x);
								$show = "All";



								?>


								<?php
								while ($data = array_shift($data_res)) {

									$cnt_no = $cnt_no + 1;

									$last_status_chg = "";
									$sql_qry_ch = "Select changed_on from company_status_history where companyID = " . $data["I"] .  " order by 	unqid desc";
									db_b2b();
									$dt_view_res_ch = db_query($sql_qry_ch);
									while ($row_ch = array_shift($dt_view_res_ch)) {
										$last_status_chg = date("m/d/Y", strtotime($row_ch["changed_on"]));
									}
									if ($last_status_chg != "") {
										$days = " (" . number_format((strtotime(date("Y-m-d")) - strtotime($last_status_chg)) / (60 * 60 * 24)) . ")";
									} else {
										$days = "";
									}

									$attachment_str = "";
									$sql_qry_ch = "Select * from Attachments where companyID = " . $data["I"] .  " ORDER by ID DESC";
									db_b2b();
									$dt_view_res_ch = db_query($sql_qry_ch);
									while ($row_ch = array_shift($dt_view_res_ch)) {
										if ($row_ch["path"] != "") {
											$description = $row_ch["description"];
											if ($description == "") {
												$description = "link";
											}
											$attachment_str .= "<a style='color:#0000FF;' href='b2battachments/" . $row_ch["path"] . "' target='_blank'>" . $description . "</a>, ";
										}
									}

									$last_crm = "Last Comm.: " . date("m/d/Y", strtotime($data["LD"]));

									$last_crm_phone = "Last Call: ";
									$sql_qry_ch = "Select messageDate from CRM where companyID = " . $data["I"] .  " and type = 'phone' ORDER by ID DESC limit 1";
									db_b2b();
									$dt_view_res_ch = db_query($sql_qry_ch);
									while ($row_ch = array_shift($dt_view_res_ch)) {
										$last_crm_phone = "Last Call: " . $row_ch["messageDate"];
									}

									$last_crm_email = "Last Email: ";
									$sql_qry_ch = "Select messageDate from CRM where companyID = " . $data["I"] .  " and type = 'email' ORDER by ID DESC limit 1";
									db_b2b();
									$dt_view_res_ch = db_query($sql_qry_ch);
									while ($row_ch = array_shift($dt_view_res_ch)) {
										$last_crm_email = "Last Email: " . $row_ch["messageDate"];
									}

									$accstatus_name = "";
									$sql_qry_ch = "Select name from status where id = '" . $data["accstatus"] .  "'";
									db_b2b();
									$dt_view_res_ch = db_query($sql_qry_ch);
									while ($row_ch = array_shift($dt_view_res_ch)) {
										$accstatus_name = $row_ch["name"];
									}

									if ($data["LD"] != "") {
										//	$last_crm = date('m/d/Y',strtotime($data["LD"]));
									}

									if ($data["special_ops"] == 1) {
										$bgcolor_str = "#caeab5";
									} else {
										$bgcolor_str = "#E4E4E4";
									}
									//$tbgcolor_child ="#FFFFBF";

								?>
									<!--	<tr id="sp_op_div_int<?php echo $cnt_no; ?>" valign="middle" style="display:none;"><td colspan="7">&nbsp;<td></tr>-->
									<tr id="sp_op_div<?php echo $cnt_no ?>" valign="middle">
										<td width="5%" bgcolor="<?php echo $bgcolor_str; ?>" align="center">
											<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
												<?php echo $data["EI"]; ?>
											</font>
										</td>
										<td width="7%" bgcolor="<?php echo $bgcolor_str; ?>">
											<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
												<?php echo $accstatus_name; ?><br>

												<?php echo $days; ?>
											</font>
										</td>

										<td width="7%" bgcolor="<?php echo $bgcolor_str; ?>">
											<a target="_blank" href="viewCompany.php?ID=<?php echo $data["I"] ?>">
												<font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo get_nickname_val($data["CO"], $data["I"]) ?><?php $compname = get_nickname_val($data["CO"], $data["I"]); ?></font>
											</a>
										</td>

										<td width="20%" bgcolor="<?php echo $bgcolor_str; ?>" align="center">
											<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
												UCBoxes Next Step: <textarea type="text" cols="44" rows="3" id="txt_nextstep<?php echo $cnt_no ?>"><?php echo $data["NS"] ?></textarea>
												<?php if ($data["ucbzw_flg"] == 1) { ?>
													<br>UCBZW Next Step: <textarea type="text" cols="44" rows="3" id="txt_nextstep_ucbzw<?php echo $cnt_no ?>"><?php echo $data["UZWNS"] ?></textarea>
												<?php  } ?>

											</font>
										</td>

										<td bgcolor="<?php echo $bgcolor_str; ?>" width="10%" align="center">
											<?php

											$sql_qry = "SELECT * FROM opportunity_master WHERE opp_status <> 9 and opp_companyid =" . $data["I"];
											db_b2b();
											$oppres = db_query($sql_qry);
											while ($opprows = array_shift($oppres)) {

											?>
												<table cellpadding="3" cellspacing="1" width="95%">
													<tr>
														<td bgcolor="#F0F0F0">
															<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
																<a href="javascript:void(0);" onclick="show_opportunity(<?php echo $data["I"]; ?>)"><?php echo $opprows["opp_name"]; ?></a>
															</font>
														</td>
													</tr>
												</table>

											<?php
											}
											?>
										</td>

										<td width="9%" bgcolor="<?php echo $bgcolor_str; ?>" align="center">
											<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
												<textarea type="text" cols="15" rows="3" id="txt_why_this_deal<?php echo $cnt_no ?>"><?php echo $data["why_this_deal"] ?></textarea>
											</font>
										</td>
										<td width="6%" bgcolor="<?php echo $bgcolor_str; ?>" align="center">
											<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
												<input type="text" size="10" id="txt_potential_annual_rev<?php echo $cnt_no ?>" value="<?php echo $data["potential_annual_rev"] ?>" />
											</font>
										</td>

										<?php
										$x = "Select * from todolist where companyid = '" . $data["I"] . "' and status = 1";
										db();
										$oldfollowup = db_query($x);
										$task_due = tep_db_num_rows($oldfollowup);

										$x = "Select * from todolist where companyid = '" . $data["I"] . "' and status = 1 and due_date = '" . date("Y-m-d") . "'";
										db();
										$oldfollowup = db_query($x);
										$task_due_today = tep_db_num_rows($oldfollowup);

										$x = "Select * from todolist where companyid = '" . $data["I"] . "' and status = 1 and due_date < '" . date("Y-m-d") . "'";
										db();
										$oldfollowup = db_query($x);
										$task_due_pastdue = tep_db_num_rows($oldfollowup);
										?>
										<td width="2%" nowrap bgcolor="<?php echo $bgcolor_str; ?>" align="center">
											<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
												<a href="#" onclick="showtask(<?php echo $data["I"] ?>)"><?php echo "<font color=red>" . $task_due_pastdue . "</font>,<font color=#4b9952>" . $task_due_today . "</font>,<font color=black>" . $task_due . "</font>"; ?></a>
											</font>
										</td>

										<td width="9%" bgcolor="<?php echo $bgcolor_str; ?>" align="left">
											<a href="#" id="showcrmid<?php echo $data["I"] ?>" onclick="showcrm(<?php echo $data["I"] ?>); return false;">
												<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
													<?php
													echo $last_crm . "<br><br>" . $last_crm_phone . "<br><br>" . $last_crm_email;
													?>
												</font>
											</a>
										</td>

										<td width="9%" align="center" bgcolor="<?php echo $bgcolor_str; ?>">
											<div style="<?php if ($data["ND"] == date('Y-m-d')) { ?> background:#00FF00; <?php } elseif ($data["ND"] < date('Y-m-d') && $data["ND"] != "") { ?> background:#FF0000; <?php } else { ?> background:<?php echo $bgcolor_str; ?> <?php } ?>">
												<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
													UCBoxes Next Comm.:<input type="text" name="txt_nextcomm<?php echo $cnt_no ?>" id="txt_nextcomm<?php echo $cnt_no ?>" size="10" value="<?php if ($data["ND"] != "") echo date('m/d/Y', strtotime($data["ND"])); ?>" />
												</font>
												<a href="#" onclick="cal1xx.select(document.sp_report.txt_nextcomm<?php echo $cnt_no ?>,'anchor1xx<?php echo $cnt_no ?>','MM/dd/yyyy'); return false;" name="anchor1xx<?php echo $cnt_no ?>" id="anchor1xx<?php echo $cnt_no ?>">
													<img border="0" src="images/calendar.jpg"></a>
											</div>
											<?php if ($data["ucbzw_flg"] == 1) { ?>
												<br>
												<div style="<?php if ($data["UZWND"] == date('Y-m-d')) { ?> background:#00FF00; <?php } elseif ($data["UZWND"] < date('Y-m-d') && $data["UZWND"] != "") { ?> background:#FF0000; <?php } else { ?> background:<?php echo $bgcolor_str; ?> <?php } ?>">
													<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
														UCBZW Next Comm.:<input type="text" name="txt_nextcomm_ucbzw<?php echo $cnt_no ?>" id="txt_nextcomm_ucbzw<?php echo $cnt_no ?>" size="10" value="<?php if ($data["UZWND"] != "") echo date('m/d/Y', strtotime($data["UZWND"])); ?>" />
													</font>
													<a href="#" onclick="cal1xx.select(document.sp_report.txt_nextcomm_ucbzw<?php echo $cnt_no ?>,'ucbzw_anchor1xx<?php echo $cnt_no ?>','MM/dd/yyyy'); return false;" name="ucbzw_anchor1xx<?php echo $cnt_no ?>" id="ucbzw_anchor1xx<?php echo $cnt_no ?>">
														<img border="0" src="images/calendar.jpg"></a>
												</div>
											<?php } ?>
											<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
										</td>


										<td bgcolor="<?php echo $bgcolor_str; ?>" width="4%" align="center">
											<input style="cursor:pointer;" type="button" onclick="update_nextstep(<?php echo $cnt_no ?>,<?php echo $data["I"] ?>,<?php echo $eid ?>, '<?php echo $_REQUEST["sk"] ?>', '<?php echo $_REQUEST["so"] ?>', <?php echo $data["accstatus"] ?>, '<?php echo $_REQUEST["show"] ?>', '<?php echo $_REQUEST["gc"] ?>', <?php echo $data["ucbzw_flg"]; ?>);" value="Save" />
										</td>

										<?php $data_print = "n";
										if (isset($_REQUEST["currentid"])) {
											if ($_REQUEST["currentid"] == $data["I"]) {
												$data_print = "y"; ?>
												<td width="1%" bgcolor="<?php echo $bgcolor_str; ?>">
													<font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Data updated</font>
												</td>
											<?php 		}
										}

										if ($data_print == "n") { ?>
											<td width="1%" bgcolor="<?php echo $bgcolor_str; ?>">
											</td>
										<?php } ?>

									</tr>

									<tr>
										<td colspan='14'>
											<table id="opportunity_tbl<?php echo $data['I']; ?>" class="opportunity_tbl" width="100%" border="0" cellspacing="1" cellpadding="1">
												<tr>
													<th width="4%" class="innercol_opp">Sr. No.</th>
													<th width="5%" class="innercol_opp">Opportunity Owner</th>
													<th width="6%" class="innercol_opp">Opportunity Name</th>
													<th width="19%" class="innercol_opp">Opportunity<br>Next Step</th>
													<th width="9%" class="innercol_opp">Opportunity<br>Next Step Date</th>
													<th width="4%" class="innercol_opp">CRM</th>
													<th width="8%" class="innercol_opp">Opportunity<br>Type</th>
													<th width="6%" class="innercol_opp">Opportunity<br>Stage</th>
													<th width="6%" class="innercol_opp">Probability<br>to Close</th>
													<th width="7%" class="innercol_opp">Potential Revenue (Annualized) </th>
													<th width="8%" class="innercol_opp">Potential G. Profit (Annualized)</th>
													<th width="5%" class="innercol_opp">Potential<br>Margin</th>
													<th width="5%" class="innercol_opp">Opportunity Deadline</th>
													<th width="5%" class="innercol_opp">Save</th>
													<!-- <th width="5%" class="innercol_opp">Action</th> -->
													<th width="1%" class="innercol_opp">&nbsp;</th>
												</tr>
												<?php
												$qry_sql = "SELECT * FROM opportunity_master WHERE opp_status <> 9 and opp_companyid =" . $data["I"];
												db_b2b();
												$qry_res = db_query($qry_sql);
												$op_slno = 1;
												while ($orw = array_shift($qry_res)) {
													db_b2b();
													$sqlini = db_query("SELECT initials FROM employees WHERE employeeID =" . $orw['opp_owner']);
													$usrini = array_shift($sqlini);

													// Opportunity type
													$typesql = "SELECT opptype_name FROM opportunity_type WHERE id=" . $orw['opp_typeid'];
													db_b2b();
													$typeres = db_query($typesql);
													$typename = array_shift($typeres);

													// Opportunity Stage
													$stgsql = "SELECT * FROM opportunity_stage WHERE id=" . $orw['opp_stageid'];
													db_b2b();
													$stgres = db_query($stgsql);
													$stgarr = array_shift($stgres);

													// margin calculation
													$show_margin = round(($orw['opp_profit_amount'] / $orw['opp_revenue_amount']) * 100, 0);
												?>
													<tr id="sp_opportunity_<?php echo $orw['opp_id']; ?>">
														<td class="innercol_opp" align="center"><?php echo $op_slno; ?></td>
														<td class="innercol_opp" align="center">
															<?php echo $usrini['initials']; ?>
														</td>
														<td class="innercol_opp">
															<span class="infotxt">

																<a href="javascript:void(0)" onclick="edit_opportunity(<?php echo $orw['opp_id']; ?>, <?php echo $op_slno; ?>)">
																	<font size="1" color="#333333">
																		<?php echo $orw['opp_name']; ?>
																	</font>
																</a>
																<span>
																	<?php echo $orw['opp_description']; ?>
																</span>
															</span>

														</td>
														<td class="innercol_opp">
															<font size="1">
																<textarea type="text" cols="40" rows="3" name="opp_nxtstep<?php echo $orw['opp_id']; ?>" id="opp_nxtstep<?php echo $orw['opp_id']; ?>"><?php echo $orw['opp_next_step']; ?></textarea>
															</font>
														</td>

														<td class="innercol_opp">
															<div class="px-10" style="<?php if ($orw["opp_next_step_date"] == date('Y-m-d')) { ?> background:#00FF00; <?php } elseif ($orw["opp_next_step_date"] < date('Y-m-d') && $orw["opp_next_step_date"] != "") { ?> background:#FF0000; <?php } else { ?> background:<?php echo $bgcolor_str; ?> <?php } ?>">
																<input name="opp_nxtstep_date<?php echo $orw['opp_id']; ?>" id="opp_nxtstep_date<?php echo $orw['opp_id']; ?>" type="text" size="8" value="<?php echo (($orw['opp_next_step_date'] != "") ? date('m-d-Y', strtotime($orw['opp_next_step_date'])) : ""); ?>" style="width: 75px;">
																<a href="#" onclick="cal1xx.select(document.sp_report.opp_nxtstep_date<?php echo $orw['opp_id']; ?>,'nxdt_anchor<?php echo $orw['opp_id']; ?>','MM-dd-yyyy'); return false;" name="nxdt_anchor<?php echo $orw['opp_id']; ?>" id="nxdt_anchor<?php echo $orw['opp_id']; ?>">
																	<img border="0" src="images/calendar.jpg"></a>
															</div>
														</td>
														<td class="innercol_opp">
															<a href="javascript:void(0)" onclick="showopportunity_crm(<?php echo $orw["opp_id"]; ?>, <?php echo $data["I"] ?>)">
																<font size="1" color="#333333">CRM</font>
															</a>
														</td>
														<td class="innercol_opp"><?php echo $typename['opptype_name']; ?></td>
														<td class="innercol_opp">
															<?php echo $stgarr['oppstage_sort_order'] . ". " . $stgarr['oppstage_name']; ?>
														</td>
														<td class="innercol_opp" align="center"><?php echo $orw['opp_probability']; ?>%</td>
														<td class="innercol_opp" align="right">
															$<?php echo number_format($orw['opp_revenue_amount']); ?>
														</td>
														<td class="innercol_opp" align="right">
															$<?php echo number_format($orw['opp_profit_amount']); ?>
														</td>

														<td class="innercol_opp" align="center"><?php echo $show_margin; ?>%</td>
														<td class="innercol_opp" align="center">
															<div class="px-10" style="<?php if ($orw["opp_close_date"] == date('Y-m-d')) { ?> background:#00FF00; <?php } elseif ($orw["opp_close_date"] < date('Y-m-d') && $orw["opp_close_date"] != "") { ?> background:#FF0000; <?php } else { ?> background:<?php echo $bgcolor_str; ?> <?php } ?>">
																<?php if ($orw["opp_close_date"] < date('Y-m-d') && $orw["opp_close_date"] != "") { ?>
																	<font color="white">
																	<?php } ?>
																	<?php echo date('m/d/Y', strtotime($orw['opp_close_date'])); ?>
																	<?php if ($orw["opp_close_date"] < date('Y-m-d') && $orw["opp_close_date"] != "") { ?>
																	</font>
																<?php } ?>
															</div>
														</td>
														<td class="innercol_opp" align="center">
															<input style="cursor:pointer;" type="button" value="Save" onclick="update_opportunity_nextstep(<?php echo $orw['opp_id']; ?>, <?php echo $op_slno; ?>);" />
														</td>

														<td class="innercol_opp">&nbsp;</td>
													</tr>
												<?php
													$op_slno++;
												}
												?>
											</table>
										</td>
									</tr>

									<script type="text/javascript">
										hide_all_opportunity();
									</script>
								<?php

								} // of the inactive or reactive if
								ob_flush();

								?>
							</table>
						<?php
						}

						?>
					</td>
				</tr>
		</form>
		</table>

	</div>

</body>

</html>