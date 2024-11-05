<?php
session_start();
ini_set("display_errors", "1");
error_reporting(E_ERROR);
require("inc/header_session.php");
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
?>
<html>
<head>
	<title>A/R Aging Detail Report</title>
	<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
	<link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
</head>
<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
<script LANGUAGE="JavaScript">
	document.write(getCalendarStyles());
</script>
<script LANGUAGE="JavaScript">
	var cal2xx = new CalendarPopup("listdiv");
	cal2xx.showNavigationDropdowns();
	function loadmainpg() {
		if (document.getElementById('date_from').value === "") {
			alert("Please select date From/To.");
			return false;
		}
	}
	function loadsumrypg() {
		var url = window.location.href;
		var datefrom = document.getElementById('date_from').value;
		var showall = document.getElementById('chk_showall');
		if (window.location.search.indexOf('?date_from=' + datefrom) > -1) {} else {
			url += '?date_from=' + datefrom;
		}
		if (showall.checked == true) {
			if (window.location.search.indexOf('&chk_showall=yes') > -1) {} else {
				url += '&chk_showall=yes';
			}
		}
		if (url.indexOf('?') > -1) {

			if (window.location.search.indexOf('&summary=yes') > -1) {} else {

				if (window.location.search.indexOf('?summary=yes') > -1) {
				} else {
					url += '&summary=yes';

				}
			}

		} else {
			if (window.location.search.indexOf('?summary=yes') > -1) {} else {
				url += '?summary=yes';
			}
		}
		window.location.href = url;

	}
	function trans_savetranslog(warehouse_id, transid, tmpcnt) {

		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();

		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				alert("Data saved.");

			}
		}
		logdetail = encodeURIComponent(document.getElementById("trans_notes" + tmpcnt).value);
		xmlhttp.open("GET", "rep_aging_savetranslog.php?tmpcnt=" + tmpcnt + "&warehouse_id=" + warehouse_id + "&transid=" + transid + "&logdetail=" + logdetail, true);
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
			n_offset = e_elem["windows" + s_coord];
			if (n_offset && e_elem.style.overflow == 'windows')
				n_pos -= n_offset;
			e_elem = e_elem.parentNode;
		}
		return n_pos;
	}
	function displaytrans_log(cnt, warehouse_id, rec_id) {
		document.getElementById("light").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
		document.getElementById('light').style.display = 'block';
		selectobject = document.getElementById("translog" + cnt);
		n_left = f_getPosition(selectobject, 'Left');
		n_top = f_getPosition(selectobject, 'Top');
		document.getElementById('light').style.left = (n_left - 600) + 'px';
		document.getElementById('light').style.top = n_top - 100 + 'px';
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}

		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>" + xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET", "displaytrans_log.php?warehouse_id=" + warehouse_id + "&rec_id=" + rec_id, true);
		xmlhttp.send();

	}
	//----------Open send template popup--------------------------------------

	function open_template_ag(summaryrep, warehouse_id, unqid, rec_id, cnt) {

		var selectobject = document.getElementById("agtempl" + cnt);

		var n_left = f_getPosition(selectobject, 'Left');

		var n_top = f_getPosition(selectobject, 'Top');

		document.getElementById('light').style.left = (n_left - 900) + 'px';

		document.getElementById('light').style.top = n_top - 100 + 'px';

		if (window.XMLHttpRequest) {

			xmlhttp = new XMLHttpRequest();

		} else { // code for IE6, IE5

			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

		}
		xmlhttp.onreadystatechange = function() {

			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

				document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" + xmlhttp.responseText;

				document.getElementById('light').style.display = 'block';

			}
		}
		xmlhttp.open("GET", "open_template_ag.php?summaryrep=" + summaryrep + "&unqid=" + unqid + "&warehouse_id=" + warehouse_id + "&rec_id=" + rec_id + "&cnt=" + cnt, true);

		xmlhttp.send();

	}

	function status_save(warehouse_id, rec_id, cnt) {

		if (window.XMLHttpRequest) {

			xmlhttp = new XMLHttpRequest();

		} else {

			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

		}

		xmlhttp.onreadystatechange = function() {

			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

				alert("Status Updated");

			}

		}
		xmlhttp.open("GET", "savetrans_status.php?warehouse_id=" + warehouse_id + "&rec_id=" + rec_id + "&transaction_status=" + document.getElementById("transaction_status" + cnt).value, true);

		xmlhttp.send();

	}

	function netterm_save(warehouse_id, rec_id, cnt) {
		if (window.XMLHttpRequest) {

			xmlhttp = new XMLHttpRequest();

		} else {

			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}

		xmlhttp.onreadystatechange = function() {

			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				alert("Net Term Updated");
			}
		}
		xmlhttp.open("GET", "report_aging_update_netterm.php?warehouse_id=" + warehouse_id + "&rec_id=" + rec_id + "&transaction_netterm=" + document.getElementById("terms" + cnt).value, true);
		xmlhttp.send();
	}

	function select_email_templ() {
		var x = document.getElementById("ag_template").value;
		if (x == "-1") {
			document.getElementById('show_templ' + x).style.display = "none";
		}
		for (var i = 1; i < 5; i++) {

			var n = i.toString();
			if (x == n) {
				document.getElementById('show_templ' + i).style.display = "block";

			} else {

				document.getElementById('show_templ' + i).style.display = "none";

			}

		}

	}

	function acc_list_popup() {
		var selectobject = document.getElementById("acclist");
		var n_left = f_getPosition(selectobject, 'Left');
		var n_top = f_getPosition(selectobject, 'Top');
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();

		} else {

			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 3 && xmlhttp.status == 200) {

				document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" + xmlhttp.responseText;

				document.getElementById('light').style.display = 'block';

				document.getElementById('light').style.left = n_left - 200 + 'px';

				document.getElementById('light').style.top = n_top + 10 + 'px';

			}

		}
		xmlhttp.open("GET", "authorized_cc_list.php", true);
		xmlhttp.send();
	}

	function online_inv_not_confirmed_list_popup() {
		var selectobject = document.getElementById("online_inv_confirmeddiv");
		var n_left = f_getPosition(selectobject, 'Left');
		var n_top = f_getPosition(selectobject, 'Top');
		document.getElementById('light').style.display = 'block';
		document.getElementById("light").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
		document.getElementById('light').style.left = n_left - 200 + 'px';
		document.getElementById('light').style.top = n_top + 10 + 'px';
		if (window.XMLHttpRequest) {

			xmlhttp = new XMLHttpRequest();

		} else {

			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 3 && xmlhttp.status == 200) {
				document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" + xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET", "ar_online_inv_not_confirmed_list.php", true);

		xmlhttp.send();

	}
	function inv_need_req_popup() {
		var selectobject = document.getElementById("inv_need_req");
		var n_left = f_getPosition(selectobject, 'Left');
		var n_top = f_getPosition(selectobject, 'Top');
		if (window.XMLHttpRequest) {

			xmlhttp = new XMLHttpRequest();

		} else {

			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

		}

		xmlhttp.onreadystatechange = function() {

			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

				document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" + xmlhttp.responseText;

				document.getElementById('light').style.display = 'block';

				document.getElementById('light').style.left = n_left - 200 + 'px';

				document.getElementById('light').style.top = n_top + 10 + 'px';

			}

		}

		xmlhttp.open("POST", "inv_need_req_list.php", true);

		xmlhttp.send();

	}


	function inv_paid_chk_popup() {
		var selectobject = document.getElementById("inv_paid_chk");
		var n_left = f_getPosition(selectobject, 'Left');
		var n_top = f_getPosition(selectobject, 'Top');
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 3 && xmlhttp.status == 200) {
				document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" + xmlhttp.responseText;
				document.getElementById('light').style.display = 'block';
				document.getElementById('light').style.left = n_left - 200 + 'px';
				document.getElementById('light').style.top = n_top + 10 + 'px';
			}
		}
		xmlhttp.open("POST", "inv_payment_list.php", true);
		xmlhttp.send();

	}
	function btnsendclick_templ(id) {
		var tmp_element1, tmp_element2, tmp_element3, tmp_element4, tmp_element5;
		tmp_element1 = document.getElementById("txtemailto" + id).value;
		tmp_element2 = document.getElementById("templ_email");
		tmp_element3 = document.getElementById("txtemailcc" + id).value;
		tmp_element4 = document.getElementById("txtemailsubject" + id).value;
		tmp_element5 = document.getElementById("hidden_reply_eml" + id).value;
		var warehouse_id = document.getElementById("warehouse_id_e" + id).value;
		var rec_id = document.getElementById("rec_id_e" + id).value;
		var summaryrep = document.getElementById("summaryrep").value;
		var inst = FCKeditorAPI.GetInstance("txtemailbody" + id);
		var emailtext = inst.GetHTML();
		tmp_element5.value = emailtext;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}

		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

				if (xmlhttp.responseText == "") {

					alert("Email Sent.");

				} else {

					alert(xmlhttp.responseText);

				}
				document.getElementById('light').style.display = 'none';

			}

		}
		xmlhttp.open("GET", "open_template_ag.php?txtemailto=" + tmp_element1 + "&unqid=" + id + "&warehouse_id=" + warehouse_id + "&rec_id=" + rec_id + "&txtemailcc=" + tmp_element3 + "&txtemailsubject=" + encodeURIComponent(tmp_element4) + "&summaryrep=" + summaryrep + "&txtemailattch=" + encodeURIComponent(document.getElementById("txtemailattch" + id).value) + "&hidden_sendemail=inemailmode&emlbody=" + encodeURIComponent(emailtext), true);
		xmlhttp.send();
	}

</script>
<style>
	.newtxttheam_withdot {
		font-family: Arial;
		font-size: 12px;
		padding: 4px;
		background-color: #EFEEE7;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;

	}
	.newtxttheam_withdot_child {
		font-family: Arial;
		font-size: 12px;
		background-color: #E9E8DC;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		padding: 4px;
		border: 1px solid #FFF4F4;
	}
	.newtxttheam_withdot_red {
		font-family: Arial;
		font-size: 12px;
		padding: 4px;
		background-color: #FA6868;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.newtxttheam_withdot_red_child {
		font-family: Arial;
		font-size: 12px;
		background-color: #FB9B9B;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}
	.newtxttheam_withdot_red1 {
		font-family: Arial;
		font-size: 12px;
		padding: 4px;
		background-color: #FA6868;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}
	.newtxttheam_withdot_red_child1 {
		font-family: Arial;
		font-size: 12px;
		background-color: #FB9B9B;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}
	.newtxttheam_withdot_red_child1 td {
		padding: 4px;
		border: 1px solid #FFF4F4;
	}

	.newtxttheam_withdot_red_child td {
		padding: 4px;
		border: 1px solid #FFF4F4;
	}
	.newtxttheam_withdot_yellow {
		font-family: Arial;
		font-size: 12px;
		padding: 4px;
		background-color: #ffffba;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;

	}

	.newtxttheam_withdot_yellow_child {
		font-family: Arial;
		font-size: 12px;
		background-color: #FCFCD9;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;

	}
	.newtxttheam_withdot_yellow_child td {
		padding: 4px;
		border: 1px solid #FFF4F4;
	}

	.newtxttheam_withdot_fred {
		font-family: Arial;

		font-size: 12px;

		padding: 4px;

		background-color: #FF0000;

		overflow: hidden;

		text-overflow: ellipsis;

		white-space: nowrap;

	}

	.newtxttheam_withdot_fred_child {

		font-family: Arial;

		font-size: 12px;

		background-color: #FF0000;

		overflow: hidden;

		text-overflow: ellipsis;

		white-space: nowrap;

		border: 1px solid #FFF4F4;

		padding: 4px;

	}



	.newtxttheam_withdot_lred {

		font-family: Arial;

		font-size: 12px;

		padding: 4px;

		background-color: #FFCCCC;

		overflow: hidden;

		text-overflow: ellipsis;

		white-space: nowrap;

	}



	.newtxttheam_withdot_lred_child {

		font-family: Arial;

		font-size: 12px;

		background-color: #FFCCCC;

		overflow: hidden;

		text-overflow: ellipsis;

		white-space: nowrap;

		border: 1px solid #FFF4F4;

		padding: 4px;

	}



	.newtxttheam_withdot_notes {

		width: 110px;

		font-family: Arial;

		font-size: 12px;

		background-color: #EFEEE7;

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

		z-index: 1002;

		overflow: auto;

	}



	table.summarytable {

		border-collapse: collapse;

	}



	table.summarytable td {

		border: 1px solid #CFCFCF;

		padding: 4px;

	}



	tr.nowrap_style td {

		white-space: nowrap;

	}

</style>



<body>

	<?php include("inc/header.php"); ?>



	<div class="main_data_css">

		<div class="dashboard_heading" style="float: left;">

			<div style="float: left;">

				<?php if ((isset($_REQUEST['summary'])) && $_REQUEST['summary'] == "View Summary Report") { ?>

					A/R Aging Summary Report

				<?php } else { ?>

					A/R Aging Detail Report

				<?php } ?>

			</div>

			&nbsp;<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>

				<span class="tooltiptext">

					This report shows the user all invoices which are not paid in full as of the date selected.

				</span>

			</div>



			<div style="height: 13px;">&nbsp;</div>

		</div>

		<div id="light" class="white_content"></div>

		<div id="fade" class="black_overlay"></div>



		<font size="2"> <a href="aging_summary_report.php">A/R Aging Summary</a> :

			<a href="report_aging.php">A/R Aging Detail</a> : <a href="report_aging_bulk_eamil.php">A/R Bulk Email Tool</a>

		</font><br>



		<hr>



		<form method="GET" name="rpt_leaderboard" action="report_aging.php">

			<table>

				<tr>

					<td align="center">A/R aging detail report as of:</td>

					<td align="center">

						<?php echo date("Y-m-d"); ?>

						<input type="hidden" name="date_from" id="date_from" size="10" value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : date("Y-m-d"); ?>">

						&nbsp;&nbsp;

						<input type="checkbox" name="chk_showall" id="chk_showall" value="yes" <?php if (!empty($_GET["chk_showall"])) {

																									echo " checked ";

																								} ?>>Show All

					</td>

					<td>



					</td>

					<td>

						<?php if ((isset($_REQUEST['summary'])) && $_REQUEST['summary'] == "View Summary Report") { ?>

							<input type=submit value="View Detail Report" name="show" onClick="javascript: return loadmainpg()">

						<?php } else { ?>

							<input type=submit value="View Summary Report" name="summary" onClick="loadmainpg()">

						<?php } ?>

					</td>

				</tr>

			</table>

		</form>

		<br>

		<?php

		db();

		$inv_qry = db_query("SELECT loop_transaction_buyer.id from loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id 

		WHERE loop_transaction_buyer.recycling_flg = 0 and loop_transaction_buyer.shipped = 1 and loop_bol_files.bol_shipment_received = 1 and inv_entered = 0 

		and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 and loop_transaction_buyer.id not in (select trans_rec_id from loop_invoice_details) GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id");

		$total_inv_need_requested = tep_db_num_rows($inv_qry);



		$invpmt = 0;

		$inv_paymt_query = db_query("SELECT inv_amount,loop_transaction_buyer.id As BI, loop_invoice_details.total as invt from loop_invoice_details INNER JOIN loop_transaction_buyer on loop_invoice_details.trans_rec_id=loop_transaction_buyer.id where terms='PrePaid' and inv_amount>0");

		while ($inv_rows = array_shift($inv_paymt_query)) {

			if ($inv_rows["inv_amount"] > $inv_rows["invt"]) {

				$invpmt = $invpmt + 1;

			}

		}

		?>

		Invoices That Need Requested (<a href='#' id="inv_need_req" onclick="inv_need_req_popup();"><?php echo $total_inv_need_requested; ?></a>) | Invoices Marked as Prepaid but Not Paid (<a href='#' id="inv_paid_chk" onclick="inv_paid_chk_popup();"><?php echo $invpmt; ?></a>)

		<?php

		$userid = 0;

		$user_qry = "SELECT id from loop_employees where initials = '" . $_COOKIE['userinitials'] . "'";

		$user_res = db_query($user_qry);



		while ($user_res_data = array_shift($user_res)) {

			$userid = $user_res_data["id"];

		}



		$sort_order_pre = "ASC";

		$date_from = date("Y-m-d");



		if (!isset($_GET['sort'])) {



			if (isset($_GET["date_from"])) {

				$date_from = $_GET["date_from"];

			}



			$total = 0;

			$current = 0;

			$mainval = 0;

			$current_tot = 0;

			$tc_1_tot = 0;

			$tc_2_tot = 0;

			$tc_3_tot = 0;

			$tc_4_tot = 0;

			$tc_1 = 0;

			$tc_2 = 0;

			$tc_3 = 0;

			$tc_4 = 0;

			$Total_tot = 0;

			$classnm = "newtxttheam_withdot";

			$amount = 0;



			$res = db_query("delete from loop_ar_reptemp_new where userid = " . $userid);



			$res = db_query("Update loop_transaction_buyer set trans_status = 99 where accounting_issue = 1 and trans_status not in (1,2,3,4)");



			$current_year = date("Y");

			$current_year = 2000;



			$x = "select loop_warehouse.company_name, b2bid, warehouse_id, loop_transaction_buyer.inv_amount as tot, loop_transaction_buyer.trans_status, loop_transaction_buyer.inv_file as inv_file, loop_transaction_buyer.inv_number as inv_number, loop_transaction_buyer.online_inv_confirmed, ";

			$x .= " loop_invoice_details.terms as term,";

			$x .= " loop_transaction_buyer.inv_date_of ,loop_transaction_buyer.id as I from loop_transaction_buyer";

			$x .= " inner join loop_warehouse on loop_transaction_buyer.warehouse_id=loop_warehouse.id";

			$x .= " left join loop_invoice_details";

			$x .= " on loop_transaction_buyer.id=loop_invoice_details.trans_rec_id ";

			$x .= " where DATE_FORMAT(STR_TO_DATE(loop_transaction_buyer.inv_date_of, '%m/%d/%Y'), '%Y-%m-%d') <='" . ($date_from) . "'";

			$x .= " and loop_transaction_buyer.no_invoice = 0 and inv_amount > 0 group by loop_transaction_buyer.id order by loop_warehouse.company_name";



			$inv_res = db_query($x);



			$num_row = tep_db_num_rows($inv_res);

			if ($num_row > 0) {

				while ($inv_row = array_shift($inv_res)) {

					$inv_date = $inv_row['inv_date_of'];

					$id = $inv_row['I'];

					$b2bid = $inv_row["b2bid"];

					$warehouse_id = $inv_row["warehouse_id"];



					//This is the payment Info for the Customer paying UCB

					$payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A FROM loop_buyer_payments WHERE trans_rec_id = " . $inv_row["I"];

					$payment_qry = db_query($payments_sql);

					$payment_val = 0;

					while ($payment = array_shift($payment_qry)) {

						if ($payment["A"]){

							$payment_val = $payment["A"];

						}

					}



					$invoice_paid = 0;

					$inv_row_tot = 0;

					if ($inv_row["tot"]){

						$inv_row_tot = $inv_row["tot"];

					}

					

					if (number_format($inv_row_tot, 2) == number_format($payment_val, 2) && $inv_row["tot"] != "") {

						$invoice_paid = 1;

					}



					if ($invoice_paid == 0) {



						$mainval = ($inv_row["tot"] - $payment_val);



						$todays_dt = date('m/d/Y');

						$date_10 = date('m/d/Y', strtotime($inv_date . "+ 10 days"));

						$date_15 = date('m/d/Y', strtotime($inv_date . "+ 15 days"));



						$date_20 = date('m/d/Y', strtotime($inv_date . "+ 20 days"));

						$date_25 = date('m/d/Y', strtotime($inv_date . "+ 25 days"));



						$date_30 = date('m/d/Y', strtotime($inv_date . "+ 30 days"));

						$date_45 = date('m/d/Y', strtotime($inv_date . "+ 45 days"));

						$date_60 = date('m/d/Y', strtotime($inv_date . "+ 60 days"));

						$date_75 = date('m/d/Y', strtotime($inv_date . "+ 75 days"));

						$date_90 = date('m/d/Y', strtotime($inv_date . "+ 90 days"));



						$date_30_11 = date('m/d/Y', strtotime($inv_date . "+ 30 days"));

						$date_30_1 = date('m/d/Y', strtotime($date_30_11 . ' first day of +1 month'));

						$date_45_11 = date('m/d/Y', strtotime($inv_date . "+ 45 days"));

						$date_45_1 = date('m/d/Y', strtotime($date_45_11 . ' first day of +1 month'));



						$date_120 = date('m/d/Y', strtotime($inv_date . "+ 120 days"));

						$date_120_11 = date('m/d/Y', strtotime($inv_date . "+ 120 days"));

						$date_120_1 = date('m/d/Y', strtotime($date_120_11 . 'first day of +1 month'));



						$dt_diff_10 = intval(str_replace(",", "", dateDiff($todays_dt, $date_10)));

						$dt_diff_15 = intval(str_replace(",", "", dateDiff($todays_dt, $date_15)));



						$dt_diff_20 = intval(str_replace(",", "", dateDiff($todays_dt, $date_20)));

						$dt_diff_25 = intval(str_replace(",", "", dateDiff($todays_dt, $date_25)));



						$dt_diff_30 = intval(str_replace(",", "", dateDiff($todays_dt, $date_30)));

						$dt_diff_45 = intval(str_replace(",", "", dateDiff($todays_dt, $date_45)));

						$dt_diff_60 = intval(str_replace(",", "", dateDiff($todays_dt, $date_60)));

						$dt_diff_75 = intval(str_replace(",", "", dateDiff($todays_dt, $date_75)));

						$dt_diff_90 = intval(str_replace(",", "", dateDiff($todays_dt, $date_90)));



						$dt_diff_120 = intval(str_replace(",", "", dateDiff($todays_dt, $date_120)));

						$dt_diff_120_1 = intval(str_replace(",", "", dateDiff($todays_dt, $date_120_1)));



						$dt_diff_30_1 = intval(str_replace(",", "", dateDiff($todays_dt, $date_30_1)));

						$dt_diff_45_1 = intval(str_replace(",", "", dateDiff($todays_dt, $date_45_1)));



						$past_duenn = dateDiff($todays_dt, $date_30_1);

						$past_duenn = str_replace(",", "", $past_duenn);



						$dt_other = intval(str_replace(",", "", dateDiff($todays_dt, $inv_date)));



						$days = dateDiff($todays_dt, $inv_date);

						$days = intval(str_replace(",", "", $days));



						$dt_10 = date('Y-m-d', strtotime($date_10));

						$dt_15 = date('Y-m-d', strtotime($date_15));



						$dt_20 = date('Y-m-d', strtotime($date_20));

						$dt_25 = date('Y-m-d', strtotime($date_25));



						$dt_30 = date('Y-m-d', strtotime($date_30));

						$dt_45 = date('Y-m-d', strtotime($date_45));

						$dt_60 = date('Y-m-d', strtotime($date_60));

						$dt_75 = date('Y-m-d', strtotime($date_75));

						$dt_90 = date('Y-m-d', strtotime($date_90));



						$dt_120 = date('Y-m-d', strtotime($date_120));

						$dt_120_1 = date('Y-m-d', strtotime($date_120_1));



						$dt_30_1 = date('Y-m-d', strtotime($date_30_1));

						$dt_45_1 = date('Y-m-d', strtotime($date_45_1));



						$dt = date('Y-m-d', strtotime($inv_date));

						$today = date('Y-m-d', strtotime($todays_dt));



						$term_days = 0;



						$tc_1 = 0;

						$tc_2 = 0;

						$tc_3 = 0;

						$tc_4 = 0;

						$tc_5 = 0;

						$tc_6 = 0;



						$tc_5_tot = 0;

						$tc_6_tot = 0;



						if ($inv_row['term'] == '') {

							$current = $mainval;

							$current_tot += $current;

							$tc_1 = 0;

							$tc_2 = 0;

							$tc_3 = 0;

							$tc_4 = 0;

						}



						$past_due = 0;

						if ($inv_row['term'] == 'Net 10') {

							$term_days = 10;

							$past_due = $days - 10;



							if ($dt_10 >= $today) {

								$current = $mainval;

								$current_tot += $current;

								$tc_1 = 0;

								$tc_2 = 0;

								$tc_3 = 0;

								$tc_4 = 0;

								$tc_5 = 0;

								$tc_6 = 0;

							} else {

								if ($dt_diff_10 >= 1 && $dt_diff_10 <= 30) {

									$tc_1 = $mainval;

									$tc_1_tot += $tc_1;

								} else {

									$current = 0;

									$tc_1 = 0;

								}



								if ($dt_diff_10 >= 31 && $dt_diff_10 <= 60) {

									$tc_2 = $mainval;

									$tc_2_tot += $tc_2;

								} else {

									$current = 0;

									$tc_2 = 0;

								}



								if ($dt_diff_10 >= 61 && $dt_diff_10 <= 90) {

									$tc_3 = $mainval;

									$tc_3_tot += $tc_3;

								} else {

									$current = 0;

									$tc_3 = 0;

								}



								if ($dt_diff_10 >= 91 && $dt_diff_10 <= 120) {

									$tc_4 = $mainval;

									$tc_4_tot += $tc_4;

								} else {

									$current = 0;

									$tc_4 = 0;

								}



								if ($dt_diff_10 >= 121 && $dt_diff_10 <= 150) {

									$tc_5 = $mainval;

									$tc_5_tot += $tc_5;

								} else {

									$current = 0;

									$tc_5 = 0;

								}

								if ($dt_diff_10 >= 151) {

									$tc_6 = $mainval;

									$tc_6_tot += $tc_6;

								} else {

									$current = 0;

									$tc_6 = 0;

								}

							}

						}



						if ($inv_row['term'] == 'Net 15') {

							$term_days = 15;

							$past_due = $days - 15;



							if ($dt_15 >= $today) {

								$current = $mainval;

								$current_tot += $current;

								$tc_1 = 0;

								$tc_2 = 0;

								$tc_3 = 0;

								$tc_4 = 0;

								$tc_5 = 0;

								$tc_6 = 0;

							} else {

								if ($dt_diff_15 >= 1 && $dt_diff_15 <= 30) {

									$tc_1 = $mainval;

									$tc_1_tot += $tc_1;

								} else {

									$current = 0;

									$tc_1 = 0;

								}



								if ($dt_diff_15 >= 31 && $dt_diff_15 <= 60) {

									$tc_2 = $mainval;

									$tc_2_tot += $tc_2;

								} else {

									$current = 0;

									$tc_2 = 0;

								}



								if ($dt_diff_15 >= 61 && $dt_diff_15 <= 90) {

									$tc_3 = $mainval;

									$tc_3_tot += $tc_3;

								} else {

									$current = 0;

									$tc_3 = 0;

								}



								if ($dt_diff_15 >= 91 && $dt_diff_15 <= 120) {

									$tc_4 = $mainval;

									$tc_4_tot += $tc_4;

								} else {

									$current = 0;

									$tc_4 = 0;

								}



								if ($dt_diff_15 >= 121 && $dt_diff_15 <= 150) {

									$tc_5 = $mainval;

									$tc_5_tot += $tc_5;

								} else {

									$current = 0;

									$tc_5 = 0;

								}

								if ($dt_diff_15 >= 151) {

									$tc_6 = $mainval;

									$tc_6_tot += $tc_6;

								} else {

									$current = 0;

									$tc_6 = 0;

								}

							}

						}



						if ($inv_row['term'] == 'Net 20') {

							if ($dt_20 >= $today) {

								$current = $mainval;

								$current_tot += $current;

								$tc_1 = 0;

								$tc_2 = 0;

								$tc_3 = 0;

								$tc_4 = 0;

							} else {

								if ($dt_diff_20 >= 1 && $dt_diff_20 <= 30) {

									$tc_1 = $mainval;

									$tc_1_tot += $tc_1;

								} else {

									$current = 0;

									$tc_1 = 0;

								}



								if ($dt_diff_20 >= 31 && $dt_diff_20 <= 60) {

									$tc_2 = $mainval;

									$tc_2_tot += $tc_2;

								} else {

									$current = 0;

									$tc_2 = 0;

								}



								if ($dt_diff_20 >= 61 && $dt_diff_20 <= 90) {

									$tc_3 = $mainval;

									$tc_3_tot += $tc_3;

								} else {

									$current = 0;

									$tc_3 = 0;

								}



								if ($dt_diff_20 >= 91) {

									$tc_4 = $mainval;

									$tc_4_tot += $tc_4;

								} else {

									$current = 0;

									$tc_4 = 0;

								}

							}

						}



						if ($inv_row['term'] == 'Net 25') {



							if ($dt_25 >= $today) {

								$current = $mainval;

								$current_tot += $current;

								$tc_1 = 0;

								$tc_2 = 0;

								$tc_3 = 0;

								$tc_4 = 0;

							} else {

								if ($dt_diff_25 >= 1 && $dt_diff_25 <= 30) {

									$tc_1 = $mainval;

									$tc_1_tot += $tc_1;

								} else {

									$current = 0;

									$tc_1 = 0;

								}



								if ($dt_diff_25 >= 31 && $dt_diff_25 <= 60) {

									$tc_2 = $mainval;

									$tc_2_tot += $tc_2;

								} else {

									$current = 0;

									$tc_2 = 0;

								}



								if ($dt_diff_25 >= 61 && $dt_diff_25 <= 90) {

									$tc_3 = $mainval;

									$tc_3_tot += $tc_3;

								} else {

									$current = 0;

									$tc_3 = 0;

								}



								if ($dt_diff_25 >= 91) {

									$tc_4 = $mainval;

									$tc_4_tot += $tc_4;

								} else {

									$current = 0;

									$tc_4 = 0;

								}

							}

						}





						if ($inv_row['term'] == 'Net 30') {

							$term_days = 30;

							$past_due = $days - 30;



							if ($dt_30 >= $today) {

								$current = $mainval;

								$current_tot += $current;

								$tc_1 = 0;

								$tc_2 = 0;

								$tc_3 = 0;

								$tc_4 = 0;

								$tc_5 = 0;

								$tc_6 = 0;

							} else {

								if ($dt_diff_30 >= 1 && $dt_diff_30 <= 30) {

									$tc_1 = $mainval;

									$tc_1_tot += $tc_1;

								} else {

									$current = 0;

									$tc_1 = 0;

								}



								if ($dt_diff_30 >= 31 && $dt_diff_30 <= 60) {

									$tc_2 = $mainval;

									$tc_2_tot += $tc_2;

								} else {

									$current = 0;

									$tc_2 = 0;

								}



								if ($dt_diff_30 >= 61 && $dt_diff_30 <= 90) {

									$tc_3 = $mainval;

									$tc_3_tot += $tc_3;

								} else {

									$current = 0;

									$tc_3 = 0;

								}

								if ($dt_diff_30 >= 91 && $dt_diff_30 <= 120) {

									$tc_4 = $mainval;

									$tc_4_tot += $tc_4;

								} else {

									$current = 0;

									$tc_4 = 0;

								}



								if ($dt_diff_30 >= 121 && $dt_diff_30 <= 150) {

									$tc_5 = $mainval;

									$tc_5_tot += $tc_5;

								} else {

									$current = 0;

									$tc_5 = 0;

								}

								if ($dt_diff_30 >= 151) {

									$tc_6 = $mainval;

									$tc_6_tot += $tc_6;

								} else {

									$current = 0;

									$tc_6 = 0;

								}

							}

						}



						if ($inv_row['term'] == 'Net 45') {

							$term_days = 45;

							$past_due = $days - 45;



							if ($dt_45 >= $today) {

								$current = $mainval;

								$current_tot += $current;

								$tc_1 = 0;

								$tc_2 = 0;

								$tc_3 = 0;

								$tc_4 = 0;

								$tc_5 = 0;

								$tc_6 = 0;

								$tc_6 = 0;

							} else {

								if ($dt_diff_45 >= 1 && $dt_diff_45 <= 30) {

									$tc_1 = $mainval;

									$tc_1_tot += $tc_1;

								} else {

									$current = 0;

									$tc_1 = 0;

								}



								if ($dt_diff_45 >= 31 && $dt_diff_45 <= 60) {

									$tc_2 = $mainval;

									$tc_2_tot += $tc_2;

								} else {

									$current = 0;

									$tc_2 = 0;

								}



								if ($dt_diff_45 >= 61 && $dt_diff_45 <= 90) {

									$tc_3 = $mainval;

									$tc_3_tot += $tc_3;

								} else {

									$current = 0;

									$tc_3 = 0;

								}



								if ($dt_diff_45 >= 91 && $dt_diff_45 <= 120) {

									$tc_4 = $mainval;

									$tc_4_tot += $tc_4;

								} else {

									$current = 0;

									$tc_4 = 0;

								}



								if ($dt_diff_45 >= 121 && $dt_diff_45 <= 150) {

									$tc_5 = $mainval;

									$tc_5_tot += $tc_5;

								} else {

									$current = 0;

									$tc_5 = 0;

								}

								if ($dt_diff_45 >= 151) {

									$tc_6 = $mainval;

									$tc_6_tot += $tc_6;

								} else {

									$current = 0;

									$tc_6 = 0;

								}

							}

						}



						if ($inv_row['term'] == 'Net 60') {

							$term_days = 60;

							$past_due = $days - 60;



							if ($dt_60 >= $today) {

								$current = $mainval;

								$current_tot += $current;

								$tc_1 = 0;

								$tc_2 = 0;

								$tc_3 = 0;

								$tc_4 = 0;

								$tc_5 = 0;

								$tc_6 = 0;

							} else {

								if ($dt_diff_60 >= 1 && $dt_diff_60 <= 30) {

									$tc_1 = $mainval;

									$tc_1_tot += $tc_1;

								} else {

									$current = 0;

									$tc_1 = 0;

								}



								if ($dt_diff_60 >= 31 && $dt_diff_60 <= 60) {

									$tc_2 = $mainval;

									$tc_2_tot += $tc_2;

								} else {

									$current = 0;

									$tc_2 = 0;

								}



								if ($dt_diff_60 >= 61 && $dt_diff_60 <= 90) {

									$tc_3 = $mainval;

									$tc_3_tot += $tc_3;

								} else {

									$current = 0;

									$tc_3 = 0;

								}



								if ($dt_diff_60 >= 91 && $dt_diff_60 <= 120) {

									$tc_4 = $mainval;

									$tc_4_tot += $tc_4;

								} else {

									$current = 0;

									$tc_4 = 0;

								}



								if ($dt_diff_60 >= 121 && $dt_diff_60 <= 150) {

									$tc_5 = $mainval;

									$tc_5_tot += $tc_5;

								} else {

									$current = 0;

									$tc_5 = 0;

								}

								if ($dt_diff_60 >= 151) {

									$tc_6 = $mainval;

									$tc_6_tot += $tc_6;

								} else {

									$current = 0;

									$tc_6 = 0;

								}

							}

						}



						if ($inv_row['term'] == 'Net 75') {

							$term_days = 75;

							$past_due = $days - 75;



							if ($dt_75 >= $today) {

								$current = $mainval;

								$current_tot += $current;

								$tc_1 = 0;

								$tc_2 = 0;

								$tc_3 = 0;

								$tc_4 = 0;

								$tc_5 = 0;

								$tc_6 = 0;

							} else {

								if ($dt_diff_75 >= 1 && $dt_diff_75 <= 30) {

									$tc_1 = $mainval;

									$tc_1_tot += $tc_1;

								} else {

									$current = 0;

									$tc_1 = 0;

								}



								if ($dt_diff_75 >= 31 && $dt_diff_75 <= 60) {

									$tc_2 = $mainval;

									$tc_2_tot += $tc_2;

								} else {

									$current = 0;

									$tc_2 = 0;

								}



								if ($dt_diff_75 >= 61 && $dt_diff_75 <= 90) {

									$tc_3 = $mainval;

									$tc_3_tot += $tc_3;

								} else {

									$current = 0;

									$tc_3 = 0;

								}



								if ($dt_diff_75 >= 91 && $dt_diff_75 <= 120) {

									$tc_4 = $mainval;

									$tc_4_tot += $tc_4;

								} else {

									$current = 0;

									$tc_4 = 0;

								}



								if ($dt_diff_75 >= 121 && $dt_diff_75 <= 150) {

									$tc_5 = $mainval;

									$tc_5_tot += $tc_5;

								} else {

									$current = 0;

									$tc_5 = 0;

								}

								if ($dt_diff_75 >= 151) {

									$tc_6 = $mainval;

									$tc_6_tot += $tc_6;

								} else {

									$current = 0;

									$tc_6 = 0;

								}

							}

						}



						if ($inv_row['term'] == 'Net 90') {

							$term_days = 90;

							$past_due = $days - 90;



							if ($dt_90 >= $today) {

								$current = $mainval;

								$current_tot += $current;

								$tc_1 = 0;

								$tc_2 = 0;

								$tc_3 = 0;

								$tc_4 = 0;

								$tc_5 = 0;

								$tc_6 = 0;

							} else {

								if ($dt_diff_90 >= 1 && $dt_diff_90 <= 30) {

									$tc_1 = $mainval;

									$tc_1_tot += $tc_1;

								} else {

									$current = 0;

									$tc_1 = 0;

								}



								if ($dt_diff_90 >= 31 && $dt_diff_90 <= 60) {

									$tc_2 = $mainval;

									$tc_2_tot += $tc_2;

								} else {

									$current = 0;

									$tc_2 = 0;

								}



								if ($dt_diff_90 >= 61 && $dt_diff_90 <= 90) {

									$tc_3 = $mainval;

									$tc_3_tot += $tc_3;

								} else {

									$current = 0;

									$tc_3 = 0;

								}



								if ($dt_diff_90 >= 91 && $dt_diff_90 <= 120) {

									$tc_4 = $mainval;

									$tc_4_tot += $tc_4;

								} else {

									$current = 0;

									$tc_4 = 0;

								}



								if ($dt_diff_90 >= 121 && $dt_diff_90 <= 150) {

									$tc_5 = $mainval;

									$tc_5_tot += $tc_5;

								} else {

									$current = 0;

									$tc_5 = 0;

								}

								if ($dt_diff_90 >= 151) {

									$tc_6 = $mainval;

									$tc_6_tot += $tc_6;

								} else {

									$current = 0;

									$tc_6 = 0;

								}

							}

						}



						if ($inv_row['term'] == 'Net 120') {

							$term_days = 120;

							$past_due = $days - 120;



							if ($dt_120 >= $today) {

								$current = $mainval;

								$current_tot += $current;

								$tc_1 = 0;

								$tc_2 = 0;

								$tc_3 = 0;

								$tc_4 = 0;

								$tc_5 = 0;

								$tc_6 = 0;

							} else {

								if ($dt_diff_120 >= 1 && $dt_diff_120 <= 30) {

									$tc_1 = $mainval;

									$tc_1_tot += $tc_1;

								} else {

									$current = 0;

									$tc_1 = 0;

								}



								if ($dt_diff_120 >= 31 && $dt_diff_120 <= 60) {

									$tc_2 = $mainval;

									$tc_2_tot += $tc_2;

								} else {

									$current = 0;

									$tc_2 = 0;

								}



								if ($dt_diff_120 >= 61 && $dt_diff_120 <= 90) {

									$tc_3 = $mainval;

									$tc_3_tot += $tc_3;

								} else {

									$current = 0;

									$tc_3 = 0;

								}



								if ($dt_diff_120 >= 91 && $dt_diff_120 <= 120) {

									$tc_4 = $mainval;

									$tc_4_tot += $tc_4;

								} else {

									$current = 0;

									$tc_4 = 0;

								}



								if ($dt_diff_120 >= 121 && $dt_diff_120 <= 150) {

									$tc_5 = $mainval;

									$tc_5_tot += $tc_5;

								} else {

									$current = 0;

									$tc_5 = 0;

								}

								if ($dt_diff_120 >= 151) {

									$tc_6 = $mainval;

									$tc_6_tot += $tc_6;

								} else {

									$current = 0;

									$tc_6 = 0;

								}

							}

						}



						if ($inv_row['term'] == 'Net 120 EOM +1' || $inv_row['term'] == "Net 120 EOM  1") {

							$term_days = dateDiff($inv_date, $date_120_1);



							$past_duetmp = date_diff(date_create(date("Y-m-d", strtotime($date_120_1))), date_create(date("Y-m-d", strtotime($todays_dt))));

							$past_due = $past_duetmp->format("%R%a");



							if ($dt_120_1 >= $today) {

								$current = $mainval;

								$current_tot += $current;

								$tc_1 = 0;

								$tc_2 = 0;

								$tc_3 = 0;

								$tc_4 = 0;

								$tc_5 = 0;

								$tc_6 = 0;

							} else {

								if ($dt_diff_120_1 >= 1 && $dt_diff_120_1 <= 30) {

									$tc_1 = $mainval;

									$tc_1_tot += $tc_1;

								} else {

									$current = 0;

									$tc_1 = 0;

								}



								if ($dt_diff_120_1 >= 31 && $dt_diff_120_1 <= 60) {

									$tc_2 = $mainval;

									$tc_2_tot += $tc_2;

								} else {

									$current = 0;

									$tc_2 = 0;

								}



								if ($dt_diff_120_1 >= 61 && $dt_diff_120_1 <= 90) {

									$tc_3 = $mainval;

									$tc_3_tot += $tc_3;

								} else {

									$current = 0;

									$tc_3 = 0;

								}



								if ($dt_diff_120_1 >= 91 && $dt_diff_120_1 <= 120) {

									$tc_4 = $mainval;

									$tc_4_tot += $tc_4;

								} else {

									$current = 0;

									$tc_4 = 0;

								}



								if ($dt_diff_120_1 >= 121 && $dt_diff_120_1 <= 150) {

									$tc_5 = $mainval;

									$tc_5_tot += $tc_5;

								} else {

									$current = 0;

									$tc_5 = 0;

								}

								if ($dt_diff_120_1 >= 151) {

									$tc_6 = $mainval;

									$tc_6_tot += $tc_6;

								} else {

									$current = 0;

									$tc_6 = 0;

								}

							}

						}



						if ($inv_row['term'] == 'Net 30 EOM +1' || $inv_row['term'] == "Net 30 EOM  1") {

							$term_days = dateDiff($inv_date, $date_30_1);

							$past_duetmp = date_diff(date_create(date("Y-m-d", strtotime($date_30_1))), date_create(date("Y-m-d", strtotime($todays_dt))));

							$past_due = $past_duetmp->format("%R%a");



							if ($dt_30_1 >= $today) {

								$current = $mainval;

								$current_tot += $current;

								$tc_1 = 0;

								$tc_2 = 0;

								$tc_3 = 0;

								$tc_4 = 0;

								$tc_5 = 0;

								$tc_6 = 0;

							} else {

								if ($dt_diff_30_1 >= 1 && $dt_diff_30_1 <= 30) {

									$tc_1 = $mainval;

									$tc_1_tot += $tc_1;

								} else {

									$current = 0;

									$tc_1 = 0;

								}



								if ($dt_diff_30_1 >= 31 && $dt_diff_30_1 <= 60) {

									$tc_2 = $mainval;

									$tc_2_tot += $tc_2;

								} else {

									$current = 0;

									$tc_2 = 0;

								}



								if ($dt_diff_30_1 >= 61 && $dt_diff_30_1 <= 90) {

									$tc_3 = $mainval;

									$tc_3_tot += $tc_3;

								} else {

									$current = 0;

									$tc_3 = 0;

								}



								if ($dt_diff_30_1 >= 91 && $dt_diff_30_1 <= 120) {

									$tc_4 = $mainval;

									$tc_4_tot += $tc_4;

								} else {

									$current = 0;

									$tc_4 = 0;

								}



								if ($dt_diff_30_1 >= 121 && $dt_diff_30_1 <= 150) {

									$tc_5 = $mainval;

									$tc_5_tot += $tc_5;

								} else {

									$current = 0;

									$tc_5 = 0;

								}



								if ($dt_diff_30_1 >= 151) {

									$tc_6 = $mainval;

									$tc_6_tot += $tc_6;

								} else {

									$current = 0;

									$tc_6 = 0;

								}

							}

						}



						if ($inv_row['term'] == 'Net 45 EOM +1' || $inv_row['term'] == "Net 45 EOM  1") {

							$term_days = dateDiff($inv_date, $date_45_1);

							$past_duetmp = date_diff(date_create(date("Y-m-d", strtotime($date_45_1))), date_create(date("Y-m-d", strtotime($todays_dt))));

							$past_due = $past_duetmp->format("%R%a");



							if ($dt_45_1 >= $today) {

								$current = $mainval;

								$current_tot += $current;

								$tc_1 = 0;

								$tc_2 = 0;

								$tc_3 = 0;

								$tc_4 = 0;

								$tc_5 = 0;

								$tc_6 = 0;

							} else {

								if ($dt_diff_45_1 >= 1 && $dt_diff_45_1 <= 30) {

									$tc_1 = $mainval;

									$tc_1_tot += $tc_1;

								} else {

									$current = 0;

									$tc_1 = 0;

								}



								if ($dt_diff_45_1 >= 31 && $dt_diff_45_1 <= 60) {

									$tc_2 = $mainval;

									$tc_2_tot += $tc_2;

								} else {

									$current = 0;

									$tc_2 = 0;

								}



								if ($dt_diff_45_1 >= 61 && $dt_diff_45_1 <= 90) {

									$tc_3 = $mainval;

									$tc_3_tot += $tc_3;

								} else {

									$current = 0;

									$tc_3 = 0;

								}



								if ($dt_diff_45_1 >= 91 && $dt_diff_45_1 <= 120) {

									$tc_4 = $mainval;

									$tc_4_tot += $tc_4;

								} else {

									$current = 0;

									$tc_4 = 0;

								}



								if ($dt_diff_45_1 >= 121 && $dt_diff_45_1 <= 150) {

									$tc_5 = $mainval;

									$tc_5_tot += $tc_5;

								} else {

									$current = 0;

									$tc_5 = 0;

								}



								if ($dt_diff_45_1 >= 151) {

									$tc_6 = $mainval;

									$tc_6_tot += $tc_6;

								} else {

									$current = 0;

									$tc_6 = 0;

								}

							}

						}



						if ($inv_row['term'] == '1% 10 Net 30' || $inv_row['term'] == '1% 15 Net 30') {

							$term_days = 30;

							$past_due = $days - 30;



							if ($dt_30 >= $today) {

								$current = $mainval;

								$current_tot += $current;

								$tc_1 = 0;

								$tc_2 = 0;

								$tc_3 = 0;

								$tc_4 = 0;

								$tc_5 = 0;

								$tc_6 = 0;

							} else {

								if ($dt_diff_30 >= 1 && $dt_diff_30 <= 30) {

									$tc_1 = $mainval;

									$tc_1_tot += $tc_1;

								} else {

									$current = 0;

									$tc_1 = 0;

								}



								if ($dt_diff_30 >= 31 && $dt_diff_30 <= 60) {

									$tc_2 = $mainval;

									$tc_2_tot += $tc_2;

								} else {

									$current = 0;

									$tc_2 = 0;

								}



								if ($dt_diff_30 >= 61 && $dt_diff_30 <= 90) {

									$tc_3 = $mainval;

									$tc_2_tot += $tc_3;

								} else {

									$current = 0;

									$tc_3 = 0;

								}



								if ($dt_diff_30 >= 91 && $dt_diff_30 <= 120) {

									$tc_4 = $mainval;

									$tc_4_tot += $tc_4;

								} else {

									$current = 0;

									$tc_4 = 0;

								}



								if ($dt_diff_30 >= 121 && $dt_diff_30 <= 150) {

									$tc_5 = $mainval;

									$tc_5_tot += $tc_5;

								} else {

									$current = 0;

									$tc_5 = 0;

								}

								if ($dt_diff_30 >= 151) {

									$tc_6 = $mainval;

									$tc_6_tot += $tc_6;

								} else {

									$current = 0;

									$tc_6 = 0;

								}

							}

						}



						if (($inv_row['term'] == 'Due On Receipt') || ($inv_row['term'] == 'PrePaid') || ($inv_row['term'] == 'Other-See Notes')) {

							$term_days = 0;

							$past_due = $days - 0;



							if ($dt >= $today) {

								$current = $mainval;

								$current_tot += $current;

								$tc_1 = 0;

								$tc_2 = 0;

								$tc_3 = 0;

								$tc_4 = 0;

								$tc_5 = 0;

								$tc_6 = 0;

							} else {

								if ($dt_other >= 1 && $dt_other <= 30) {

									$tc_1 = $mainval;

									$tc_1_tot += $tc_1;

								} else {

									$current = 0;

									$tc_1 = 0;

								}



								if ($dt_other >= 31 && $dt_other <= 60) {

									$tc_2 = $mainval;

									$tc_2_tot += $tc_2;

								} else {

									$current = 0;

									$tc_2 = 0;

								}



								if ($dt_other >= 61 && $dt_other <= 90) {

									$tc_3 = $mainval;

									$tc_3_tot += $tc_3;

								} else {

									$current = 0;

									$tc_3 = 0;

								}



								if ($dt_other >= 91 && $dt_other <= 120) {

									$tc_4 = $mainval;

									$tc_4_tot += $tc_4;

								} else {

									$current = 0;

									$tc_4 = 0;

								}



								if ($dt_other >= 121 && $dt_other <= 150) {

									$tc_5 = $mainval;

									$tc_5_tot += $tc_5;

								} else {

									$current = 0;

									$tc_5 = 0;

								}

								if ($dt_other >= 151) {

									$tc_6 = $mainval;

									$tc_6_tot += $tc_6;

								} else {

									$current = 0;

									$tc_6 = 0;

								}

							}

						}



						$Total = $current + $tc_1 + $tc_2 + $tc_3 + $tc_4 + $tc_5 + $tc_6;

						$Total_tot = $current_tot + $tc_1_tot + $tc_2_tot + $tc_3_tot + $tc_4_tot + $tc_5_tot + $tc_6_tot;



						$nickname = "";

						db_b2b();

						$sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $inv_row["b2bid"];

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

						$nickname = str_replace("'", "\'", $nickname);

						db();

						$message = "";

						$message_dt = "";

						$sql = "SELECT message, date FROM loop_transaction_notes WHERE company_id = " . $inv_row["warehouse_id"] . " AND  loop_transaction_notes.rec_id = " . $inv_row["I"] . " ORDER BY id DESC limit 1";

						$result_comp = db_query($sql);

						while ($row_comp = array_shift($result_comp)) {

							$message_dt = $row_comp["date"];

						}



						$comp_nm = $nickname;

						$qry =  "Insert into loop_ar_reptemp_new (userid, trans_status, term_days, loop_id, trans_rec_id, terms, inv_age, company_id, company_name, inv_number, inv_file, age, inv_current, inv_1_30, inv_31_60, inv_61_90, inv_91_120, inv_121_150, inv_gt_150, inv_total, notes, notes_date) ";

						$qry .= " values (" . $userid . ", " . $inv_row['trans_status'] . ", " . $term_days . " , " . $warehouse_id . ", '" . $id . "', '" . $inv_row['term'] . "', '" . $past_due . "', " . $inv_row["b2bid"] . ",'" . $comp_nm . "', '" . $inv_row['inv_number'] . "', '" .  $inv_row['inv_file'] . "', '" . $days . "', '" . $current . "', '" . $tc_1 . "', '" . $tc_2 . "', '" . $tc_3 . "', '" . $tc_4 . "','" . $tc_5 . "', '" . $tc_6 . "', '" . $Total . "', '', '" . $message_dt . "') ";



						$res = db_query($qry);

					}

				}

			}

		}



		function getdata(int $trans_status_main, string $trans_nm): string

		{

			global $userid;

			global $cnt;

			global $MGArray;

			global $sort_order_pre;



			$classnm = "newtxttheam_withdot";



			$s_str = "<tr >";

			$s_str .= " <td colspan='13'>&nbsp;</td>";

			$s_str .= "</tr>";



			$s_str .= "<tr>";

			$s_str .= " <td colspan='13' style='background: #2B1B17; color:#ffffff' align='left'>" . $trans_nm . "</td>";

			$s_str .= "</tr>";



			$s_str .= "<tr style='background: #2B1B17;' class='nowrap_style'>";

			if ((isset($_REQUEST['summary'])) && $_REQUEST['summary'] == "View Summary Report") {

				$sorturl = "report_aging.php?summary=View Summary Report";

				$s_str .= "	<td style='color:#ffffff' align='middle' width=8%>Company Name

		<a href='" . $sorturl . "&sort_order_pre=ASC&sort=company_id'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "&sort_order_pre=DESC&sort=company_id'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle' width=15%>Invoice Details</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle' width=5%>Total Invoices

		<a href='" . $sorturl . "&sort_order_pre=ASC&sort=total_inv_num'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "&sort_order_pre=DESC&sort=total_inv_num'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle' width=5%>Total invoices Past Due

		<a href='" . $sorturl . "&sort_order_pre=ASC&sort=total_inv_p_d'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "&sort_order_pre=DESC&sort=total_inv_p_d'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle' width=5%>Days Past Due of Oldest Invoice

		<a href='" . $sorturl . "&sort_order_pre=ASC&sort=p_d_oldest'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "&sort_order_pre=DESC&sort=p_d_oldest'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle' width=8%>Total

		<a href='" . $sorturl . "&sort_order_pre=ASC&sort=inv_total'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "&sort_order_pre=DESC&sort=inv_total'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle' width=6%>Total Past Due

		<a href='" . $sorturl . "&sort_order_pre=ASC&sort=total_p_d'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "&sort_order_pre=DESC&sort=total_p_d'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle' width=12%>Contact Details

		<a href='" . $sorturl . "&sort_order_pre=ASC&sort=contact_name'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "&sort_order_pre=DESC&sort=contact_name'><img src='images/sort_desc.png' wwidth='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' colspan=4 width='150px' width=8%>Send Template</td>";

			} else {

				$sorturl = "report_aging.php?";

				$s_str .= "	<td style='color:#ffffff' align='middle'>Sr.No.</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle'>Loop ID

		<a href='" . $sorturl . "sort_order_pre=ASC&sort=loop_id'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "sort_order_pre=DESC&sort=loop_id'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle'>Company Name

		<a href='" . $sorturl . "sort_order_pre=ASC&sort=company_id'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "sort_order_pre=DESC&sort=company_id'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle'>Invoice #

		<a href='" . $sorturl . "sort_order_pre=ASC&sort=inv_number'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "sort_order_pre=DESC&sort=inv_number'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle'>Terms

		<a href='" . $sorturl . "sort_order_pre=ASC&sort=terms'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "sort_order_pre=DESC&sort=terms'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle'>Invoice Age

		<a href='" . $sorturl . "sort_order_pre=ASC&sort=age'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "sort_order_pre=DESC&sort=age'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle'>Past Due

		<a href='" . $sorturl . "sort_order_pre=ASC&sort=past_due'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "sort_order_pre=DESC&sort=past_due'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle'>Total

		<a href='" . $sorturl . "sort_order_pre=ASC&sort=inv_total'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "sort_order_pre=DESC&sort=inv_total'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";



				$s_str .= "	<td style='color:#ffffff' width='50' align='middle'>Contact Details

		<a href='" . $sorturl . "sort_order_pre=ASC&sort=contact_name'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "sort_order_pre=DESC&sort=contact_name'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "<td style='color:#ffffff' align='middle'>Last Transaction Log Note</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle'>Transaction log Date

		<a href='" . $sorturl . "sort_order_pre=ASC&sort=notes_date'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "sort_order_pre=DESC&sort=notes_date'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle'>Status</td>";

				$s_str .= "	<td style='color:#ffffff' width='150px' >Send Template</td>";

			}



			$s_str .= "</tr>";



			$tot = 0;

			db();

			if ($_GET["chk_showall"] == "yes") {



				if ((isset($_REQUEST['summary'])) && $_REQUEST['summary'] == "View Summary Report") {

					$inv_res = db_query("Select *,company_id, sum(age) as invage, sum(inv_age) as pastdue, sum(inv_total) as invtot from loop_ar_reptemp_new where userid = " . $userid . " and trans_status = " . $trans_status_main . " group by company_id order by pastdue DESC");

				} else {

					$inv_res = db_query("Select * from loop_ar_reptemp_new where userid = " . $userid . " and trans_status = " . $trans_status_main . " order by inv_age desc, company_id asc");

				}

			} else {



				if ((isset($_REQUEST['summary'])) && $_REQUEST['summary'] == "View Summary Report") {

					$inv_res = db_query("Select *, company_id, sum(age) as invage, sum(inv_age) as pastdue, sum(inv_total) as invtot  from loop_ar_reptemp_new where userid = " . $userid . " and trans_status = " . $trans_status_main . " group by company_id order by pastdue DESC");

				} else {

					if ($trans_status_main == 1) {

						$inv_res = db_query("Select * from loop_ar_reptemp_new where userid = " . $userid . " and trans_status = " . $trans_status_main . " and (inv_age > 0) order by inv_age desc, company_id asc");

					} else if ($trans_status_main == 88) {

						$inv_res = db_query("Select * from loop_ar_reptemp_new where userid = " . $userid . " and (inv_total < 0) order by inv_age desc, company_id asc");

					} else {

						$inv_res = db_query("Select * from loop_ar_reptemp_new where userid = " . $userid . " and trans_status = " . $trans_status_main . "  order by inv_age desc, company_id asc");

					}

				}



				if ($trans_nm == "NO ACTION NEEDED: Invoices That Are Not Past Due and Require No Action From A/R (Yet)") {

					$inv_res = db_query("Select * from loop_ar_reptemp_new where userid = " . $userid . " and ((trans_status not in (2,3,4,99)) and (inv_age <= 0)) order by inv_age desc, company_id asc");

				}

			}

			$running_no = 0;

			$note_date = "";

			while ($inv_row = array_shift($inv_res)) {

				$trans_message = "";

				$sql_trans_message = "SELECT message FROM loop_transaction_notes WHERE company_id = " . $inv_row["loop_id"] . " AND  loop_transaction_notes.rec_id = " . $inv_row["trans_rec_id"] . " ORDER BY id DESC limit 1";

				$result_comp = db_query($sql_trans_message);

				while ($row_comp = array_shift($result_comp)) {

					$trans_message = $row_comp["message"];

				}



				$warehouse_id = 0;

				$sql = "SELECT id FROM loop_warehouse where b2bid = " . $inv_row["company_id"];

				$result_comp = db_query($sql);

				while ($row_comp = array_shift($result_comp)) {

					$warehouse_id = $row_comp["id"];

				}



				$contact_name = "";

				$contact_ph = "";

				$contact_email = "";

				db_b2b();

				$sql = "SELECT name, mainphone, email FROM b2bbillto where companyid = " . $inv_row["company_id"] . " order by billtoid limit 1";

				$result_comp = db_query($sql);

				while ($row_comp = array_shift($result_comp)) {

					if ($row_comp["name"] != "") {

						$contact_name = $row_comp["name"];

					}

					if ($row_comp["mainphone"] != "") {

						$contact_ph = $row_comp["mainphone"];

					}

					if ($row_comp["email"] != "") {

						$contact_email = $row_comp["email"];

					}

				}



				$trans_status_id = "";

				$order_issue = 0;

				db();

				$sql = "SELECT trans_status, order_issue FROM loop_transaction_buyer where id = " . $inv_row["trans_rec_id"];

				$result_comp = db_query($sql);

				while ($row_comp = array_shift($result_comp)) {

					$trans_status_id = $row_comp["trans_status"];

					$order_issue = $row_comp["order_issue"];

				}



				$trans_status = "";

				$sql = "SELECT trans_status FROM loop_trans_status where id = " . $trans_status_id;

				$result_comp = db_query($sql);

				while ($row_comp = array_shift($result_comp)) {

					$trans_status = $row_comp["trans_status"];

				}



				if ($order_issue == 1) {

					$classnm = "newtxttheam_withdot_red";

					$altclass = "newtxttheam_withdot_red_child";

				} else {

					$classnm = "newtxttheam_withdot";

					$altclass = "newtxttheam_withdot_child";

				}



				if ($inv_row["age"] == $inv_row["term_days"]) {

					$classnm = "newtxttheam_withdot_yellow";

					$altclass = "newtxttheam_withdot_yellow_child";

				}



				if ($inv_row["age"] > $inv_row["term_days"]) {

					$classnm = "newtxttheam_withdot_lred";

					$altclass = "newtxttheam_withdot_lred_child";

				}

				$pastdue_val = $inv_row["inv_age"];

				$pastdue_val = (int)$pastdue_val;

				if ($trans_status_main == 4 && $pastdue_val > 7) {

					$classnm = "newtxttheam_withdot_red1";

					$altclass = "newtxttheam_withdot_red_child1";

				}

				if ($inv_row["notes_date"] != "0000-00-00 00:00:00") {

					$notes_date = date("m/d/Y", strtotime($inv_row["notes_date"]));

					$today_date = date("m/d/Y");

					$diff = intval($today_date) - intval($notes_date);

					$datediff = intval($today_date) - intval($notes_date);



					$df = round($datediff / (60 * 60 * 24));

					$date1 = date_create($notes_date);

					$date2 = date_create($today_date);

					$diff = date_diff($date1, $date2);

					$nw = $diff->format("%a");

					if ($nw > 6) {

						$note_date = "<span style='color:#FF0000;'>" . $notes_date . "</span>";

					} else {

						$note_date = $notes_date;

					}

				}



				$parent_comp_id = 0;

				db_b2b();

				$online_invoicing_parent = "";

				$parent_child_compid = 0;

				$dt_view_qry = "SELECT parent_comp_id from companyInfo WHERE ID=" . $inv_row["company_id"];

				$dt_view_res = db_query($dt_view_qry);

				while ($dt_view_row = array_shift($dt_view_res)) {

					$parent_child_compid = $dt_view_row["parent_comp_id"];

				}



				if ($parent_child_compid > 0) {

					$dt_view_qry = "SELECT online_invoicing from companyInfo WHERE ID = " . $parent_child_compid;

					$dt_view_res = db_query($dt_view_qry);

					while ($dt_view_row = array_shift($dt_view_res)) {

						$online_invoicing_parent = $dt_view_row["online_invoicing"];

					}

				}



				$sql_onlineinv = "SELECT online_invoicing FROM companyInfo WHERE ID = " . $inv_row["company_id"];

				$rec_onlineinv = db_query($sql_onlineinv);

				$rec_onlineinvrow = array_shift($rec_onlineinv);



				if ($online_invoicing_parent != "" && ($rec_onlineinvrow["online_invoicing"] == "None" || $rec_onlineinvrow["online_invoicing"] == "")) {

					$online_invoicing = $online_invoicing_parent;

				} else {

					$online_invoicing = $rec_onlineinvrow["online_invoicing"];

				}

				db();

				$online_inv_confirmed = 0;

				$sql_inv = "SELECT online_inv_confirmed FROM loop_transaction_buyer where id = " . $inv_row["trans_rec_id"];

				$result_inv = db_query($sql_inv);

				while ($row_inv = array_shift($result_inv)) {

					$online_inv_confirmed = $row_inv["online_inv_confirmed"];

				}



				$s_str .= "<tr>";

				if ((isset($_REQUEST['summary'])) && $_REQUEST['summary'] == "View Summary Report") {

				} else {

					$running_no = $running_no + 1;

					$s_str .= "<td align='right' class=" . $classnm . ">" . $running_no . "</td>";

					$s_str .= "<td align='left' class=" . $classnm . "><a href='viewCompany.php?ID=" . $inv_row["company_id"] . "&show=transactions&warehouse_id=" . $inv_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $inv_row["loop_id"] . "&rec_id=" . $inv_row["trans_rec_id"] . "&display=buyer_payment' target='_blank'>" . $inv_row["trans_rec_id"] . "</a></td>";

				}

				if ((isset($_REQUEST['summary'])) && $_REQUEST['summary'] == "View Summary Report") {

					$s_str .= "<td align='left' class=" . $classnm . "><a href='viewCompany.php?ID=" . $inv_row["company_id"] . "&show=transactions&warehouse_id=" . $inv_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $inv_row["loop_id"] . "&rec_id=" . $inv_row["trans_rec_id"] . "&display=buyer_payment' target='_blank'>" . $inv_row["company_name"] . "</a></td>";

				} else {

					$s_str .= "<td align='left' class=" . $classnm . ">" . $inv_row["company_name"] . "</td>";

				}

				$total_past_due = 0;

				$total_invoice = 0;

				$p_d_oldest = 0;

				if ((isset($_REQUEST['summary'])) && $_REQUEST['summary'] == "View Summary Report") {

					$s_str .= "<td align='center' width='10' bgcolor='#F4F4F4'><table width=100% cellspacing=0 cellpadding=0>";



					$inv_resno = db_query("Select inv_number,age,inv_age,terms,inv_file from loop_ar_reptemp_new where userid = " . $userid . " and company_id=" . $inv_row["company_id"] . "");

					$total_invoice = tep_db_num_rows($inv_resno);



					$inv_resno1 = db_query("Select max(inv_age) AS Maxscore from loop_ar_reptemp_new where userid = " . $userid . " and inv_age>0 and company_id=" . $inv_row["company_id"]);

					$inv_p = array_shift($inv_resno1);

					$p_d_oldest = $inv_p["p_d_oldest"];



					while ($inv_no = array_shift($inv_resno)) {

						if ($inv_no["inv_age"] > 0) {

							$total_past_due = $total_past_due + 1;

							$altclass = "newtxttheam_withdot_lred_child";

						} else {

							$altclass = "newtxttheam_withdot_child";

						}

						$s_str .= "<tr><td class=" . $altclass . " width=20px><a href='files/" . $inv_no["inv_file"] . "' target='_blank'>" . $inv_no["inv_number"] . "</a></td><td class=" . $altclass . " width='10px'>" . $inv_no["terms"] . "</td><td align='right' class=" . $altclass . " width='10px'>" . $inv_no["age"] . "</td><td align='right' class=" . $altclass . " width='10px'>" . $inv_no["inv_age"] . "</td></tr>";

					}



					$s_str .= "</table></td>";

					$s_str .= "<td align='right' width='10' class=" . $classnm . ">" . $total_invoice . "</td>";

					$s_str .= "<td align='right' width='10' class=" . $classnm . ">" . $total_past_due . "</td>";

					$s_str .= "<td align='right' width='10' class=" . $classnm . ">" .  $inv_p["Maxscore"] . "</td>";

					$s_str .= "<td align='right' width='10' class=" . $classnm . ">$" .  number_format($inv_row["invtot"], 2) . "</td>";

					$s_str .= "<td align='right' width='10' class=" . $classnm . ">" .  $inv_row["pastdue"] . "</td>";

				} else {

					$s_str .= "<td align='right' class=" . $classnm . "><a href='files/" . $inv_row["inv_file"] . "' target='_blank'>" . $inv_row["inv_number"] . "</a></td>";

					$s_str .= "<td align='left' class=" . $classnm . ">";

					$s_str .= "<select name='terms' id='terms" . $cnt . "'>";





					$s_str .= "<option value='PrePaid' ";

					if ($inv_row['terms'] == 'PrePaid') {

						$s_str .= " selected ";

					}

					$s_str .= " > PrePaid </option>";



					$s_str .= "<option value='Due On Receipt'";

					if ($inv_row['terms'] == 'Due On Receipt') {

						$s_str .= " selected ";

					}

					$s_str .= " >Due On Receipt </option>

				 <option value='Net 10'";

					if ($inv_row['terms'] == 'Net 10') {

						$s_str .= " selected ";

					}

					$s_str .= " > Net 10 </option>

				 <option value='Net 15'";

					if ($inv_row['terms'] == 'Net 15') {

						$s_str .= " selected ";

					}

					$s_str .= " >Net 15</option>

				<option value='Net 20'";

					if ($inv_row['terms'] == 'Net 20') {

						$s_str .= " selected ";

					}

					$s_str .= " >Net 20</option>

				

				<option value='Net 25'";

					if ($inv_row['terms'] == 'Net 25') {

						$s_str .= " selected ";

					}

					$s_str .= " >Net 25</option>

				

				 <option value='Net 30'";

					if ($inv_row['terms'] == 'Net 30') {

						$s_str .= " selected ";

					}

					$s_str .= " >Net 30</option>

				 <option value='Net 45'";

					if ($inv_row['terms'] == 'Net 45') {

						$s_str .= " selected ";

					}

					$s_str .= " >Net 45</option>

				  <option value='Net 60'";

					if ($inv_row['terms'] == 'Net 60') {

						$s_str .= " selected ";

					}

					$s_str .= " >Net 60</option>

				  <option value='Net 75'";

					if ($inv_row['terms'] == 'Net 75') {

						$s_str .= " selected ";

					}

					$s_str .= " >Net 75</option>

				  <option value='Net 90'";

					if ($inv_row['terms'] == 'Net 90') {

						$s_str .= " selected ";

					}

					$s_str .= " >Net 90</option>

                

                  <option value='Net 120'";

					if ($inv_row['terms'] == 'Net 120') {

						$s_str .= " selected ";

					}

					$s_str .= " >Net 120</option>

                 <option value='Net 120 EOM +1'";

					if ($inv_row["terms"] == 'Net 120 EOM +1' || $inv_row['terms'] == "Net 120 EOM  1") {

						$s_str .= " selected ";

					}

					$s_str .= " >Net 120 EOM +1</option>";



					$s_str .= "<option value='Net 30 EOM +1'";

					if ($inv_row['terms'] == 'Net 30 EOM +1'  || $inv_row['terms'] == "Net 30 EOM  1") {

						$s_str .= " selected ";

					}

					$s_str .= " >Net 30 EOM +1</option>

					<option value='Net 45 EOM +1'";

					if ($inv_row['terms'] == 'Net 45 EOM +1'  || $inv_row['terms'] == "Net 45 EOM  1") {

						$s_str .= " selected ";

					}

					$s_str .= " >Net 45 EOM +1</option>";



					$s_str .= "<option value='1% 10 Net 30'";

					if ($inv_row['terms'] == '1% 10 Net 30') {

						$s_str .= " selected ";

					}

					$s_str .= " >1% 10 Net 30</option>

					<option value='1% 15 Net 30'";

					if ($inv_row['terms'] == '1% 15 Net 30') {

						$s_str .= " selected ";

					}

					$s_str .= " >1% 15 Net 30</option>

				 <option value='Other-See Notes'";

					if ($inv_row['terms'] == 'Other-See Notes') {

						$s_str .= " selected ";

					}

					$s_str .= " > Other-See Notes</option>

				 </select>";

					$s_str .= "<br><input type='button' id='nettermsave' name='nettermsave' value='Update' onclick='netterm_save(" . $warehouse_id . "," . $inv_row["trans_rec_id"] . ", " . $cnt . ")'/> </td>";



					$s_str .= "<td align='right' width='10' class=" . $classnm . ">" .  number_format($inv_row["age"], 0) . "</td>";

					$s_str .= "<td align='right' width='10' class=" . $classnm . ">" .  number_format($inv_row["inv_age"], 0) . "</td>";

					$s_str .= "<td align='right' width='10' class=" . $classnm . ">$" .  number_format($inv_row["inv_total"], 2) . "</td>";

				}

				$s_str .= "<td align='left' class=" . $classnm . ">" .  $contact_name . "<br>" . $contact_ph . "<br>" . $contact_email . "</td>";

				if ((isset($_REQUEST['summary'])) && $_REQUEST['summary'] == "View Summary Report") {

					$summaryrep = "1";

				} else {

					$summaryrep = "2";

					$s_str .= "<td align='left' class=" . $classnm . "><textarea id='trans_notes" . $cnt . "' name='trans_notes' cols='30' rows='4'>" . $trans_message . "</textarea><br><input type='button' id='logsave' name='logsave' value='Add Trans Log' onclick='trans_savetranslog(" . $warehouse_id . "," . $inv_row["trans_rec_id"] . ", " . $cnt . ")'/></td>";

					$s_str .= "<td align='center' class=" . $classnm . "><a id='translog" . $cnt . "' href='#' onclick='displaytrans_log(" . $cnt . ", " . $warehouse_id . ", " . $inv_row["trans_rec_id"] . "); return false;'> " . $note_date . "</a></td>";

				}

				db();

				if (!(isset($_REQUEST['summary']))) {

					$s_str .= "<td align='right' class=" . $classnm . ">";

					$s_str .= "<select name='transaction_status' id='transaction_status" . $cnt . "'>";

					$s_str .= "		<option value='0'>Please select</option>";

					$qry = "SELECT * FROM loop_trans_status order by trans_status";

					$dt_view_res = db_query($qry);

					while ($data_row = array_shift($dt_view_res)) {

						$s_str .= "<option value=" . $data_row["id"];

						if ($trans_status_id == $data_row["id"]) {

							$s_str .= " selected ";

						}

						$s_str .= " >" . $data_row["trans_status"] . "</option>";

					}

					$s_str .= "</select>";

					$s_str .= "<br><input type='button' id='statussave' name='statussave' value='Update' onclick='status_save(" . $warehouse_id . "," . $inv_row["trans_rec_id"] . ", " . $cnt . ")'/> </td>";

				}



				if ($online_invoicing == "None" || $online_invoicing == "") {

					$s_str .= "<td align='center' class=" . $classnm . "><form><br>

				<input type='button' id='agtempl" . $cnt . "' name='send_tmpl' value='Select Template' onclick='open_template_ag(" . $summaryrep . "," . $warehouse_id . "," . $inv_row["unqid"] . "," . $inv_row["trans_rec_id"] . ", " . $cnt . ")'/></form>";

				} else {



					$s_str .= "<td align='center' class=" . $classnm . ">Online Invoicing: " . $online_invoicing . "<br>";

					if ($online_inv_confirmed == 1) {

						$s_str .= "<font color='green'> Invoice is uploaded in <br>the Customer Online Invoicing System.</font>";

					} else {

						$s_str .= "<font color='red'> Invoice is not uploaded in <br>the Customer Online Invoicing System.</font>";

					}

				}

				$s_str .= "

			</td>";



				$s_str .= "</tr>";

				if ((isset($_REQUEST['summary'])) && $_REQUEST['summary'] == "View Summary Report") {

					$tot = $tot + $inv_row["invtot"];

				} else {

					$tot = $tot + $inv_row["inv_total"];

				}

				$cnt = $cnt + 1;

				$MGArray[] = array(

					'company_id' => $inv_row["company_id"], 'unqid' => $inv_row["unqid"], 'warehouse_id' => $inv_row["warehouse_id"], 'term_days' => $inv_row["term_days"],

					'loop_id' => $inv_row["loop_id"], 'trans_rec_id' => $inv_row["trans_rec_id"], 'company_name' => $inv_row["company_name"], 'userid' => $userid,

					'inv_file' => $inv_row["inv_file"], 'inv_number' => $inv_row["inv_number"], 'terms' => $inv_row["terms"], 'age' => $inv_row["age"], 'notes' => $trans_message, 'inv_age' => $inv_row["inv_age"], 'inv_total' => $inv_row["inv_total"], 'contact_name' => $contact_name, 'contact_ph' => $contact_ph, 'contact_email' => $contact_email, 'cnt' => $cnt, 'warehouse_id' => $warehouse_id, 'notes_date' => $notes_date, 'trans_status_main' => $trans_status_main, 'invage' => $inv_row["invage"], 'pastdue' => $inv_row["pastdue"], 'invtot' => $inv_row["invtot"], 'total_past_due' => $total_past_due, 'total_invoice' => $total_invoice, 'p_d_oldest' => $p_d_oldest, 'total_p_d' => $inv_row["pastdue"]

				);

			}



			$s_str .= "<tr>";

			$s_str .= " <td colspan='4'>&nbsp;</td><td align='right'>Total:</td><td align='left'><b>$" . number_format($tot, 2) . "</b></td><td colspan='6'>&nbsp;</td>";

			$s_str .= "</tr>";



			return $s_str;

		}



		function getdata_array(int $trans_status_main, string $trans_nm): string

		{

			global $cnt;

			global $MGArray;

			global $sort_order_pre;



			if ($_GET['sort'] == "notes_date" && $_GET['sort_order_pre'] == "ASC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['notes_date'];

				}

				array_multisort($MGArraysort_N, SORT_ASC, SORT_STRING, $MGArray);

			}

			if ($_GET['sort'] == "notes_date" && $_GET['sort_order_pre'] == "DESC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['notes_date'];

				}

				array_multisort($MGArraysort_N, SORT_DESC, SORT_STRING, $MGArray);

			}

			if ($_GET['sort'] == "contact_name" && $_GET['sort_order_pre'] == "ASC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['contact_name'];

				}

				array_multisort($MGArraysort_N, SORT_ASC, SORT_STRING, $MGArray);

			}

			if ($_GET['sort'] == "contact_name" && $_GET['sort_order_pre'] == "DESC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['contact_name'];

				}

				array_multisort($MGArraysort_N, SORT_DESC, SORT_STRING, $MGArray);

			}



			if ($_GET['sort'] == "loop_id" && $_GET['sort_order_pre'] == "ASC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['loop_id'];

				}

				array_multisort($MGArraysort_N, SORT_ASC, SORT_NUMERIC, $MGArray);

			}

			if ($_GET['sort'] == "loop_id" && $_GET['sort_order_pre'] == "DESC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['loop_id'];

				}

				array_multisort($MGArraysort_N, SORT_DESC, SORT_NUMERIC, $MGArray);

			}



			if ($_GET['sort'] == "inv_total" && $_GET['sort_order_pre'] == "ASC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['inv_total'];

				}

				array_multisort($MGArraysort_N, SORT_ASC, SORT_NUMERIC, $MGArray);

			}

			if ($_GET['sort'] == "inv_total" && $_GET['sort_order_pre'] == "DESC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['inv_total'];

				}

				array_multisort($MGArraysort_N, SORT_DESC, SORT_NUMERIC, $MGArray);

			}

			if ($_GET['sort'] == "past_due" && $_GET['sort_order_pre'] == "ASC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['inv_age'];

				}

				array_multisort($MGArraysort_N, SORT_ASC, SORT_NUMERIC, $MGArray);

			}

			if ($_GET['sort'] == "past_due" && $_GET['sort_order_pre'] == "DESC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['inv_age'];

				}

				array_multisort($MGArraysort_N, SORT_DESC, SORT_NUMERIC, $MGArray);

			}

			if ($_GET['sort'] == "age" && $_GET['sort_order_pre'] == "ASC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['age'];

				}

				array_multisort($MGArraysort_N, SORT_ASC, SORT_NUMERIC, $MGArray);

			}

			if ($_GET['sort'] == "age" && $_GET['sort_order_pre'] == "DESC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['age'];

				}

				array_multisort($MGArraysort_N, SORT_DESC, SORT_NUMERIC, $MGArray);

			}



			if ($_GET['sort'] == "terms" && $_GET['sort_order_pre'] == "ASC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['terms'];

				}

				array_multisort($MGArraysort_N, SORT_ASC, SORT_STRING, $MGArray);

			}

			if ($_GET['sort'] == "terms" && $_GET['sort_order_pre'] == "DESC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['terms'];

				}

				array_multisort($MGArraysort_N, SORT_DESC, SORT_STRING, $MGArray);

			}

			if ($_GET['sort'] == "inv_number" && $_GET['sort_order_pre'] == "ASC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['inv_number'];

				}

				array_multisort($MGArraysort_N, SORT_ASC, SORT_STRING, $MGArray);

			}

			if ($_GET['sort'] == "inv_number" && $_GET['sort_order_pre'] == "DESC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['inv_number'];

				}

				array_multisort($MGArraysort_N, SORT_DESC, SORT_STRING, $MGArray);

			}

			if ($_GET['sort'] == "company_id" && $_GET['sort_order_pre'] == "ASC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['company_name'];

				}

				array_multisort($MGArraysort_N, SORT_ASC, SORT_STRING, $MGArray);

			}

			if ($_GET['sort'] == "company_id" && $_GET['sort_order_pre'] == "DESC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['company_name'];

				}

				array_multisort($MGArraysort_N, SORT_DESC, SORT_STRING, $MGArray);

			}

			if ($_GET['sort'] == "total_inv_num" && $_GET['sort_order_pre'] == "ASC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['total_invoice'];

				}

				array_multisort($MGArraysort_N, SORT_ASC, SORT_STRING, $MGArray);

			}



			if ($_GET['sort'] == "total_inv_p_d" && $_GET['sort_order_pre'] == "DESC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['total_past_due'];

				}

				array_multisort($MGArraysort_N, SORT_DESC, SORT_STRING, $MGArray);

			}

			if ($_GET['sort'] == "total_inv_p_d" && $_GET['sort_order_pre'] == "ASC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['total_past_due'];

				}

				array_multisort($MGArraysort_N, SORT_ASC, SORT_STRING, $MGArray);

			}

			if ($_GET['sort'] == "p_d_oldest" && $_GET['sort_order_pre'] == "DESC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['p_d_oldest'];

				}

				array_multisort($MGArraysort_N, SORT_DESC, SORT_STRING, $MGArray);

			}

			if ($_GET['sort'] == "p_d_oldest" && $_GET['sort_order_pre'] == "ASC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['p_d_oldest'];

				}

				array_multisort($MGArraysort_N, SORT_ASC, SORT_STRING, $MGArray);

			}

			if ($_GET['sort'] == "total_p_d" && $_GET['sort_order_pre'] == "DESC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['total_p_d'];

				}

				array_multisort($MGArraysort_N, SORT_DESC, SORT_STRING, $MGArray);

			}

			if ($_GET['sort'] == "total_p_d" && $_GET['sort_order_pre'] == "ASC") {

				$MGArraysort_N = array();



				foreach ($MGArray as $MGArraytmp) {

					$MGArraysort_N[] = $MGArraytmp['total_p_d'];

				}

				array_multisort($MGArraysort_N, SORT_ASC, SORT_STRING, $MGArray);

			}



			$classnm = "newtxttheam_withdot";



			$s_str = "<tr >";

			$s_str .= " <td colspan='13'>&nbsp;</td>";

			$s_str .= "</tr>";



			$s_str .= "<tr>";

			$s_str .= " <td colspan='13' style='background: #2B1B17; color:#ffffff' align='left'>" . $trans_nm . "</td>";

			$s_str .= "</tr>";



			$s_str .= "<tr style='background: #2B1B17;' class='nowrap_style'>";



			if ((isset($_REQUEST['summary'])) && $_REQUEST['summary'] == "View Summary Report") {

				$sorturl = "report_aging.php?summary=View Summary Report";

				$s_str .= "	<td style='color:#ffffff' align='middle' width=8%>Company Name

		<a href='" . $sorturl . "&sort_order_pre=ASC&sort=company_id'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "&sort_order_pre=DESC&sort=company_id'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle' width=15%>Invoice Details</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle' width=5%>Total Invoices

		<a href='" . $sorturl . "&sort_order_pre=ASC&sort=total_inv_num'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "&sort_order_pre=DESC&sort=total_inv_num'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle' width=5%>Total invoices Past Due

		<a href='" . $sorturl . "&sort_order_pre=ASC&sort=total_inv_p_d'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "&sort_order_pre=DESC&sort=total_inv_p_d'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle' width=5%>Days Past Due of Oldest Invoice

		<a href='" . $sorturl . "&sort_order_pre=ASC&sort=p_d_oldest'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "&sort_order_pre=DESC&sort=p_d_oldest'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle' width=8%>Total

		<a href='" . $sorturl . "&sort_order_pre=ASC&sort=inv_total'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "&sort_order_pre=DESC&sort=inv_total'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle' width=6%>Total Past Due

		<a href='" . $sorturl . "&sort_order_pre=ASC&sort=total_p_d'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "&sort_order_pre=DESC&sort=total_p_d'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle' width=12%>Contact Details

		<a href='" . $sorturl . "&sort_order_pre=ASC&sort=contact_name'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "&sort_order_pre=DESC&sort=contact_name'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' width='150px' colspan=4 width=8%>Send Template</td>";

			} else {

				$sorturl = "report_aging.php?";

				$s_str .= "	<td style='color:#ffffff' align='middle'>Sr.No.</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle'>Loop ID

		<a href='" . $sorturl . "sort_order_pre=ASC&sort=loop_id'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "sort_order_pre=DESC&sort=loop_id'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle'>Company Name

		<a href='" . $sorturl . "sort_order_pre=ASC&sort=company_id'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "sort_order_pre=DESC&sort=company_id'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle'>Invoice #

		<a href='" . $sorturl . "sort_order_pre=ASC&sort=inv_number'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "sort_order_pre=DESC&sort=inv_number'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle'>Terms

		<a href='" . $sorturl . "sort_order_pre=ASC&sort=terms'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "sort_order_pre=DESC&sort=terms'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle'>Invoice Age

		<a href='" . $sorturl . "sort_order_pre=ASC&sort=age'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "sort_order_pre=DESC&sort=age'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle'>Past Due

		<a href='" . $sorturl . "&sort_order_pre=ASC&sort=past_due'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "&sort_order_pre=DESC&sort=past_due'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle'>Total

		<a href='" . $sorturl . "sort_order_pre=ASC&sort=inv_total'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "sort_order_pre=DESC&sort=inv_total'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' width='50' align='middle'>Contact Details

		<a href='" . $sorturl . "sort_order_pre=ASC&sort=contact_name'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "sort_order_pre=DESC&sort=contact_name'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle'>Last Transaction Log Note</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle'>Transaction log Date

		<a href='" . $sorturl . "sort_order_pre=ASC&sort=notes_date'><img src='images/sort_asc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>&nbsp;<a href='" . $sorturl . "sort_order_pre=DESC&sort=notes_date'><img src='images/sort_desc.png' width='6px' height='12px' style='-webkit-filter:invert(1);filter:invert(1);'></a>

		</td>";

				$s_str .= "	<td style='color:#ffffff' align='middle'>Status</td>";

				$s_str .= "	<td style='color:#ffffff' width='150px'>Send Template</td>";

			}

			$s_str .= "</tr>";



			$tot = 0;

			$running_no = 0;

			foreach ($MGArray as $MGArraytmp) {

				$warehouse_id = 0;

				db();

				if ($MGArraytmp["company_id"] > 0) {

					$sql = "SELECT id FROM loop_warehouse where b2bid = " . $MGArraytmp["company_id"];



					$result_comp = db_query($sql);

					while ($row_comp = array_shift($result_comp)) {

						$warehouse_id = $row_comp["id"];

					}



					$contact_name = "";

					$contact_ph = "";

					$contact_email = "";

					db_b2b();

					$sql = "SELECT name, mainphone, email FROM b2bbillto where companyid = " . $MGArraytmp["company_id"] . " order by billtoid limit 1";

					$result_comp = db_query($sql);

					while ($row_comp = array_shift($result_comp)) {

						if ($row_comp["name"] != "") {

							$contact_name = $row_comp["name"];

						}

						if ($row_comp["mainphone"] != "") {

							$contact_ph = $row_comp["mainphone"];

						}

						if ($row_comp["email"] != "") {

							$contact_email = $row_comp["email"];

						}

					}



					$trans_status_id = "";

					$order_issue = 0;

					db();

					$sql = "SELECT trans_status, order_issue FROM loop_transaction_buyer where id = " . $MGArraytmp["trans_rec_id"];

					$result_comp = db_query($sql);

					while ($row_comp = array_shift($result_comp)) {

						$trans_status_id = $row_comp["trans_status"];

						$order_issue = $row_comp["order_issue"];

					}



					$trans_status = "";

					$sql = "SELECT trans_status FROM loop_trans_status where id = " . $trans_status_id;

					$result_comp = db_query($sql);

					while ($row_comp = array_shift($result_comp)) {

						$trans_status = $row_comp["trans_status"];

					}



					$parent_comp_id = 0;

					$online_invoicing_parent = "";

					db_b2b();

					$parent_child_compid = 0;

					$dt_view_qry = "SELECT parent_comp_id from companyInfo WHERE ID=" . $MGArraytmp["company_id"];

					$dt_view_res = db_query($dt_view_qry);

					while ($dt_view_row = array_shift($dt_view_res)) {

						$parent_child_compid = $dt_view_row["parent_comp_id"];

					}



					if ($parent_child_compid > 0) {

						$dt_view_qry = "SELECT online_invoicing from companyInfo WHERE ID = " . $parent_child_compid;

						$dt_view_res = db_query($dt_view_qry);

						while ($dt_view_row = array_shift($dt_view_res)) {

							$online_invoicing_parent = $dt_view_row["online_invoicing"];

						}

					}



					$sql_onlineinv = "SELECT online_invoicing FROM companyInfo WHERE ID = " . $MGArraytmp["company_id"];

					$rec_onlineinv = db_query($sql_onlineinv);

					$rec_onlineinvrow = array_shift($rec_onlineinv);



					if ($online_invoicing_parent != "" && ($rec_onlineinvrow["online_invoicing"] == "None" || $rec_onlineinvrow["online_invoicing"] == "")) {

						$online_invoicing = $online_invoicing_parent;

					} else {

						$online_invoicing = $rec_onlineinvrow["online_invoicing"];

					}

					db();

					$online_inv_confirmed = 0;

					$sql_inv = "SELECT online_inv_confirmed FROM loop_transaction_buyer where id = " . $MGArraytmp["trans_rec_id"];

					$result_inv = db_query($sql_inv);

					while ($row_inv = array_shift($result_inv)) {

						$online_inv_confirmed = $row_inv["online_inv_confirmed"];

					}



					if ($order_issue == 1) {

						$classnm = "newtxttheam_withdot_red";

						$altclass = "newtxttheam_withdot_red_child";

					} else {

						$classnm = "newtxttheam_withdot";

						$altclass = "newtxttheam_withdot_child";

					}



					if ($MGArraytmp["age"] == $MGArraytmp["term_days"]) {

						$classnm = "newtxttheam_withdot_yellow";

						$altclass = "newtxttheam_withdot_yellow_child";

					}



					if ($MGArraytmp["age"] > $MGArraytmp["term_days"]) {

						$classnm = "newtxttheam_withdot_lred";

						$altclass = "newtxttheam_withdot_lred_child";

					}



					$notes_date = "";

					if ($MGArraytmp["notes_date"] != "0000-00-00 00:00:00") {

						$notes_date = date("m/d/Y", strtotime($MGArraytmp["notes_date"]));

					}



					$s_str .= "<tr>";



					if ((isset($_REQUEST['summary'])) && $_REQUEST['summary'] == "View Summary Report") {

					} else {

						$running_no = $running_no + 1;

						$s_str .= "<td align='right' class=" . $classnm . ">" . $running_no . "</td>";

						$s_str .= "<td align='left' class=" . $classnm . "><a href='viewCompany.php?ID=" . $MGArraytmp["company_id"] . "&show=transactions&warehouse_id=" . $MGArraytmp["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $MGArraytmp["loop_id"] . "&rec_id=" . $MGArraytmp["trans_rec_id"] . "&display=buyer_payment' target='_blank'>" . $MGArraytmp["trans_rec_id"] . "</a></td>";

					}

					if ((isset($_REQUEST['summary'])) && $_REQUEST['summary'] == "View Summary Report") {

						$s_str .= "<td align='left' class=" . $classnm . "><a href='viewCompany.php?ID=" . $MGArraytmp["company_id"] . "&show=transactions&warehouse_id=" . $MGArraytmp["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $MGArraytmp["loop_id"] . "&rec_id=" . $MGArraytmp["trans_rec_id"] . "&display=buyer_payment' target='_blank'>" . $MGArraytmp["company_name"] . "</a></td>";

					} else {

						$s_str .= "<td align='left' class=" . $classnm . ">" . $MGArraytmp["company_name"] . "</td>";

					}

					$total_past_due = 0;



					if ((isset($_REQUEST['summary'])) && $_REQUEST['summary'] == "View Summary Report") {

						$s_str .= "<td align='center' width='10' bgcolor='#F4F4F4'><table width=100% cellspacing=0 cellpadding=0>";

						$inv_resno = db_query("Select inv_number,age,inv_age,terms,inv_file from loop_ar_reptemp_new where userid = " . $MGArraytmp["userid"] . " and company_id=" . $MGArraytmp["company_id"] . "");

						$total_invoice = tep_db_num_rows($inv_resno);

						//

						$inv_resno1 = db_query("Select max(inv_age) AS Maxscore from loop_ar_reptemp_new where userid = " . $MGArraytmp["userid"] . " and inv_age>0 and company_id=" . $MGArraytmp["company_id"]);

						$inv_p = array_shift($inv_resno1);

						while ($inv_no = array_shift($inv_resno)) {

							if ($inv_no["inv_age"] > 0) {

								$total_past_due = $total_past_due + 1;

								$altclass = "newtxttheam_withdot_lred_child";

							} else {

								$altclass = "newtxttheam_withdot_child";

							}

							$s_str .= "<tr><td class=" . $altclass . " width=20px><a href='files/" . $inv_no["inv_file"] . "' target='_blank'>" . $inv_no["inv_number"] . "</a></td><td class=" . $altclass . " width='10px'>" . $inv_no["terms"] . "</td><td align='right' class=" . $altclass . " width='10px'>" . $inv_no["age"] . "</td><td align='right' class=" . $altclass . " width='10px'>" . $inv_no["inv_age"] . "</td></tr>";

						}

						$s_str .= "</table></td>";

						$s_str .= "<td align='right' width='10' class=" . $classnm . ">" . $total_invoice . "</td>";

						$s_str .= "<td align='right' width='10' class=" . $classnm . ">" . $total_past_due . "</td>";

						$s_str .= "<td align='right' width='10' class=" . $classnm . ">" .  $inv_p["Maxscore"] . "</td>";

						$s_str .= "<td align='right' width='10' class=" . $classnm . ">$" .  number_format($MGArraytmp["invtot"], 2) . "</td>";

						$s_str .= "<td align='right' width='10' class=" . $classnm . ">" .  $MGArraytmp["pastdue"] . "</td>";

					} else {

						$s_str .= "<td align='right' class=" . $classnm . "><a href='files/" . $MGArraytmp["inv_file"] . "' target='_blank'>" . $MGArraytmp["inv_number"] . "</a></td>";

						$s_str .= "<td align='left' class=" . $classnm . ">";

						$s_str .= "<select name='terms' id='terms" . $MGArraytmp["cnt"] . "'>";



						$s_str .= "<option value='PrePaid' ";

						if ($MGArraytmp["terms"] == 'PrePaid') {

							$s_str .= " selected ";

						}

						$s_str .= " > PrePaid </option>";



						$s_str .= "<option value='Due On Receipt'";

						if ($MGArraytmp["terms"] == 'Due On Receipt') {

							$s_str .= " selected ";

						}

						$s_str .= " >Due On Receipt </option>

				 <option value='Net 10'";

						if ($MGArraytmp["terms"] == 'Net 10') {

							$s_str .= " selected ";

						}

						$s_str .= " > Net 10 </option>

				 <option value='Net 15'";

						if ($MGArraytmp["terms"] == 'Net 15') {

							$s_str .= " selected ";

						}

						$s_str .= " >Net 15</option>

				

				<option value='Net 20'";

						if ($MGArraytmp['terms'] == 'Net 20') {

							$s_str .= " selected ";

						}

						$s_str .= " >Net 20</option>

				

				<option value='Net 25'";

						if ($MGArraytmp['terms'] == 'Net 25') {

							$s_str .= " selected ";

						}

						$s_str .= " >Net 25</option>

				

				 <option value='Net 30'";

						if ($MGArraytmp["terms"] == 'Net 30') {

							$s_str .= " selected ";

						}

						$s_str .= " >Net 30</option>

				 <option value='Net 45'";

						if ($MGArraytmp["terms"] == 'Net 45') {

							$s_str .= " selected ";

						}

						$s_str .= " >Net 45</option>

				  <option value='Net 60'";

						if ($MGArraytmp["terms"] == 'Net 60') {

							$s_str .= " selected ";

						}

						$s_str .= " >Net 60</option>

				  <option value='Net 75'";

						if ($MGArraytmp["terms"] == 'Net 75') {

							$s_str .= " selected ";

						}

						$s_str .= " >Net 75</option>

				  <option value='Net 90'";

						if ($MGArraytmp["terms"] == 'Net 90') {

							$s_str .= " selected ";

						}

						$s_str .= " >Net 90</option>

                

                 <option value='Net 120'";

						if ($MGArraytmp["terms"] == 'Net 120') {

							$s_str .= " selected ";

						}

						$s_str .= " >Net 120</option>

                 <option value='Net 120 EOM +1'";

						if ($MGArraytmp["terms"] == 'Net 120 EOM +1' || $MGArraytmp['terms'] == "Net 120 EOM  1") {

							$s_str .= " selected ";

						}

						$s_str .= " >Net 120 EOM +1</option>";



						$s_str .= "<option value='Net 30 EOM +1'";

						if ($MGArraytmp["terms"] == 'Net 30 EOM +1' || $MGArraytmp['terms'] == "Net 30 EOM  1") {

							$s_str .= " selected ";

						}

						$s_str .= " >Net 30 EOM +1</option>

				<option value='Net 45 EOM +1'";

						if ($MGArraytmp["terms"] == 'Net 45 EOM +1' || $MGArraytmp['terms'] == "Net 45 EOM  1") {

							$s_str .= " selected ";

						}

						$s_str .= " >Net 45 EOM +1</option>

				<option value='1% 10 Net 30'";

						if ($MGArraytmp["terms"] == '1% 10 Net 30') {

							$s_str .= " selected ";

						}

						$s_str .= " >1% 10 Net 30</option>

					<option value='1% 15 Net 30'";

						if ($MGArraytmp["terms"] == '1% 15 Net 30') {

							$s_str .= " selected ";

						}

						$s_str .= " >1% 15 Net 30</option>

				 <option value='Other-See Notes'";

						if ($MGArraytmp["terms"] == 'Other-See Notes') {

							$s_str .= " selected ";

						}

						$s_str .= " > Other-See Notes</option>

				 </select>";

						$s_str .= "<br><input type='button' id='nettermsave' name='nettermsave' value='Update' onclick='netterm_save(" . $MGArraytmp["warehouse_id"] . "," . $MGArraytmp["trans_rec_id"] . ", " . $MGArraytmp["cnt"] . ")'/> </td>";



						$s_str .= "<td align='right' width='10' class=" . $classnm . ">" .  number_format($MGArraytmp["age"], 0) . "</td>";

						$s_str .= "<td align='right' width='10' class=" . $classnm . ">" .  number_format($MGArraytmp["inv_age"], 0) . "</td>";

						$s_str .= "<td align='right' width='10' class=" . $classnm . ">$" .  number_format($MGArraytmp["inv_total"], 2) . "</td>";

					}







					$s_str .= "<td align='left' class=" . $classnm . ">" .  $MGArraytmp["contact_name"] . "<br>" . $MGArraytmp["contact_ph"] . "<br>" . $MGArraytmp["contact_email"] . "</td>";

					if ((isset($_REQUEST['summary'])) && $_REQUEST['summary'] == "View Summary Report") {

						$summaryrep = "1";

					} else {

						$summaryrep = "2";

						$s_str .= "<td align='left' class=" . $classnm . "><textarea id='trans_notes" . $MGArraytmp["cnt"] . "' name='trans_notes' cols='30' rows='4'>" . $MGArraytmp["notes"] . "</textarea><br><input type='button' id='logsave' name='logsave' value='Add Trans Log' onclick='trans_savetranslog(" . $MGArraytmp["warehouse_id"] . "," . $MGArraytmp["trans_rec_id"] . ", " . $MGArraytmp["cnt"] . ")'/></td>";

						$s_str .= "<td align='center' class=" . $classnm . "><a id='translog" . $MGArraytmp["cnt"] . "' href='#' onclick='displaytrans_log(" . $MGArraytmp["cnt"] . ", " . $MGArraytmp["warehouse_id"] . ", " . $MGArraytmp["trans_rec_id"] . "); return false;'> " . $MGArraytmp["notes_date"] . "</a></td>";

					}



					if ((isset($_REQUEST['summary'])) && $_REQUEST['summary'] == "View Summary Report") {

					} else {

						$s_str .= "<td align='right' class=" . $classnm . ">";

						$s_str .= "<select name='transaction_status' id='transaction_status" . $MGArraytmp["cnt"] . "'>";

						$s_str .= "		<option value='0'>Please select</option>";

						$qry = "SELECT * FROM loop_trans_status order by trans_status";

						$dt_view_res = db_query($qry);

						while ($data_row = array_shift($dt_view_res)) {

							$s_str .= "<option value=" . $data_row["id"];

							if ($trans_status_id == $data_row["id"]) {

								$s_str .= " selected ";

							}

							$s_str .= " >" . $data_row["trans_status"] . "</option>";

						}

						$s_str .= "</select>";

						$s_str .= "<br><input type='button' id='statussave' name='statussave' value='Update' onclick='status_save(" . $MGArraytmp["warehouse_id"] . "," . $MGArraytmp["trans_rec_id"] . ", " . $MGArraytmp["cnt"] . ")'/> </td>";

					}



					if ($online_invoicing == "None" || $online_invoicing == "") {

						$s_str .= "<td align='center' class=" . $classnm . "><form><br>

            	<input type='button' id='agtempl" . $MGArraytmp["cnt"] . "' name='send_tmpl' value='Select Template' onclick='open_template_ag(" . $summaryrep . ", " . $MGArraytmp["warehouse_id"] . ", " . $MGArraytmp["unqid"] . "," . $MGArraytmp["trans_rec_id"] . ", " . $MGArraytmp["cnt"] . ")'/></form></form>";

					} else {

						$s_str .= "<td align='center' class=" . $classnm . ">Online Invoicing: <strong>" . $online_invoicing . "</strong><br>";

						if ($online_inv_confirmed == 1) {

							$s_str .= "<font color='green'> Invoice is uploaded in <br>the Customer Online Invoicing System.</font>";

						} else {

							$s_str .= "<font color='red'> Invoice is not uploaded in <br>the Customer Online Invoicing System.</font>";

						}

					}

					$s_str .= "

			</td>";



					$s_str .= "</tr>";



					$tot = $tot + $MGArraytmp["inv_total"];

					$cnt = $cnt + 1;

				}

			}



			$s_str .= "<tr>";

			$s_str .= " <td colspan='4'>&nbsp;</td><td align='right'>Total:</td><td align='left'><b>$" . number_format($tot, 2) . "</b></td><td colspan='6'>&nbsp;</td>";

			$s_str .= "</tr>";



			return $s_str;

		}



		?>

		<table width="100%">

			<tr>

				<td width="55%">

					<table class="summarytable" width="100%">

						<tr style="background: #2B1B17;">

							<td colspan="8" style="color:#ffffff" align="center"><b>A/R Health Summary</b><br>

								As of <?php

										if ($_GET["date_from"] != "") {

											echo date('F d,Y',	 strtotime($_GET["date_from"]));

										} else {

											echo date('F d,Y');

										}

										?>



							</td>

						</tr>

						<tr style="background: #2B1B17;">

							<td style="color:#ffffff" align="middle">&nbsp;</td>

							<td style="color:#ffffff" align="middle">Invoices</td>

							<td style="color:#ffffff" align="middle">Amount ($)</td>

							<td style="color:#ffffff" align="middle">% of A/R</td>

						</tr>

						<?php

						$tot_inv_unpaid = 0;

						$tot_inv_pastdue = 0;

						$tot_inv_cnt = 0;

						$tot_inv_cnt_unpaid = 0;

						$sql = "SELECT sum(inv_current) as inv_current, sum(inv_1_30) as inv_1_30, sum(inv_31_60) as inv_31_60, sum(inv_61_90) as inv_61_90, sum(inv_91_120) as inv_91_120, sum(inv_121_150) as inv_121_150, sum(inv_gt_150) as inv_gt_150 FROM loop_ar_reptemp_new where userid = " . $userid . ""; //inv_gt_90

						$result_comp = db_query($sql);

						while ($row_comp = array_shift($result_comp)) {

							$tot_inv_unpaid = $tot_inv_unpaid + $row_comp["inv_current"];

							$tot_inv_unpaid = $tot_inv_unpaid + $row_comp["inv_1_30"];

							$tot_inv_unpaid = $tot_inv_unpaid + $row_comp["inv_31_60"];

							$tot_inv_unpaid = $tot_inv_unpaid + $row_comp["inv_61_90"];

							$tot_inv_unpaid = $tot_inv_unpaid + $row_comp["inv_91_120"];

							$tot_inv_unpaid = $tot_inv_unpaid + $row_comp["inv_121_150"];

							$tot_inv_unpaid = $tot_inv_unpaid + $row_comp["inv_gt_150"];



							$tot_inv_pastdue = $tot_inv_pastdue + $row_comp["inv_1_30"];

							$tot_inv_pastdue = $tot_inv_pastdue + $row_comp["inv_31_60"];

							$tot_inv_pastdue = $tot_inv_pastdue + $row_comp["inv_61_90"];

							$tot_inv_pastdue = $tot_inv_pastdue + $row_comp["inv_91_120"];

							$tot_inv_pastdue = $tot_inv_pastdue + $row_comp["inv_121_150"];

							$tot_inv_pastdue = $tot_inv_pastdue + $row_comp["inv_gt_150"];



							$inv_current_cnt = 0;

							$sql_child = "SELECT count(inv_current) as inv_current_cnt FROM loop_ar_reptemp_new where userid = " . $userid . " and inv_current <> 0";

							$result_child = db_query($sql_child);

							while ($row_child = array_shift($result_child)) {

								$inv_current_cnt = $row_child["inv_current_cnt"];

							}

							$inv_1_30_cnt = 0;

							$sql_child = "SELECT count(inv_1_30) as inv_1_30_cnt FROM loop_ar_reptemp_new where userid = " . $userid . " and inv_1_30 <> 0";

							$result_child = db_query($sql_child);

							while ($row_child = array_shift($result_child)) {

								$inv_1_30_cnt = $row_child["inv_1_30_cnt"];

							}

							$inv_31_60_cnt = 0;

							$sql_child = "SELECT count(inv_31_60) as inv_31_60_cnt FROM loop_ar_reptemp_new where userid = " . $userid . " and inv_31_60 <> 0";

							$result_child = db_query($sql_child);

							while ($row_child = array_shift($result_child)) {

								$inv_31_60_cnt = $row_child["inv_31_60_cnt"];

							}

							$inv_61_90_cnt = 0;

							$sql_child = "SELECT count(inv_61_90) as inv_61_90_cnt FROM loop_ar_reptemp_new where userid = " . $userid . " and inv_61_90 <> 0";

							$result_child = db_query($sql_child);

							while ($row_child = array_shift($result_child)) {

								$inv_61_90_cnt = $row_child["inv_61_90_cnt"];

							}

							$inv_91_120_cnt = 0;

							$sql_child = "SELECT count(inv_91_120) as inv_91_120_cnt FROM loop_ar_reptemp_new where userid = " . $userid . " and inv_91_120 <> 0";

							$result_child = db_query($sql_child);

							while ($row_child = array_shift($result_child)) {

								$inv_91_120_cnt = $row_child["inv_91_120_cnt"];

							}

							$inv_121_150_cnt = 0;

							$sql_child = "SELECT count(inv_121_150) as inv_121_150_cnt FROM loop_ar_reptemp_new where userid = " . $userid . " and inv_121_150 <> 0";

							$result_child = db_query($sql_child);

							while ($row_child = array_shift($result_child)) {

								$inv_121_150_cnt = $row_child["inv_121_150_cnt"];

							}

							$inv_gt_150_cnt = 0;

							$sql_child = "SELECT count(inv_gt_150) as inv_gt_150_cnt FROM loop_ar_reptemp_new where userid = " . $userid . " and inv_gt_150 <> 0";

							$result_child = db_query($sql_child);

							while ($row_child = array_shift($result_child)) {

								$inv_gt_150_cnt = $row_child["inv_gt_150_cnt"];

							}



							$tot_inv_cnt = $tot_inv_cnt + $inv_current_cnt;

							$tot_inv_cnt = $tot_inv_cnt + $inv_1_30_cnt;

							$tot_inv_cnt = $tot_inv_cnt + $inv_31_60_cnt;

							$tot_inv_cnt = $tot_inv_cnt + $inv_61_90_cnt;

							$tot_inv_cnt = $tot_inv_cnt + $inv_91_120_cnt;

							$tot_inv_cnt = $tot_inv_cnt + $inv_121_150_cnt;

							$tot_inv_cnt = $tot_inv_cnt + $inv_gt_150_cnt;



							$tot_inv_cnt_unpaid = $tot_inv_cnt_unpaid + $inv_1_30_cnt;

							$tot_inv_cnt_unpaid = $tot_inv_cnt_unpaid + $inv_31_60_cnt;

							$tot_inv_cnt_unpaid = $tot_inv_cnt_unpaid + $inv_61_90_cnt;

							$tot_inv_cnt_unpaid = $tot_inv_cnt_unpaid + $inv_91_120_cnt;

							$tot_inv_cnt_unpaid = $tot_inv_cnt_unpaid + $inv_121_150_cnt;

							$tot_inv_cnt_unpaid = $tot_inv_cnt_unpaid + $inv_gt_150_cnt;

						?>

							<tr>

								<td align="middle">Unpaid Invoices</td>

								<td align="right"><?php echo $tot_inv_cnt; ?></td>

								<td align="right">$<?php echo number_format($tot_inv_unpaid, 2); ?></td>

								<td align="right">&nbsp;</td>

							</tr>

							<tr>

								<td align="middle">Within Terms</td>

								<td align="right"><?php echo $inv_current_cnt; ?></td>

								<td align="right">$<?php echo number_format($row_comp["inv_current"], 2); ?></td>

								<td align="right"><?php echo number_format(($row_comp["inv_current"] / $tot_inv_unpaid) * 100, 2) . "%"; ?></td>

							</tr>

							<tr>

								<td align="middle">Past Due</td>

								<td align="right"><?php echo $tot_inv_cnt_unpaid; ?></td>

								<td align="right">$<?php echo number_format($tot_inv_pastdue, 2); ?></td>

								<td align="right"><?php echo number_format(($tot_inv_pastdue / $tot_inv_unpaid) * 100, 2) . "%"; ?></td>

							</tr>

							<?php

							//To get the paid figure 

							$paid_grand_total = 0;

							$paid_total = 0;

							$invoice_query = db_query("Select inv_total from loop_ar_reptemp_new where userid = " . $userid . " and (inv_age > 0) and trans_status = 4");

							while ($invoice_row = array_shift($invoice_query)) {

								$paid_total = $paid_total + 1;

								$paid_grand_total = $paid_grand_total + $invoice_row["inv_total"];

							}

							?>

							<tr>

								<td align="middle"><b>Past Due Outstanding</b></td>

								<td align="right"><b><?php echo $tot_inv_cnt_unpaid - $paid_total; ?></b></td>

								<td align="right"><b>$<?php echo number_format($tot_inv_pastdue - $paid_grand_total, 2); ?></b></td>

								<td align="right"><b><?php echo number_format((($tot_inv_pastdue - $paid_grand_total) / $tot_inv_unpaid) * 100, 2) . "%"; ?></b></td>

							</tr>

					</table>



					<br><br>



					<table class="summarytable" width="100%">

						<tr style="background: #2B1B17;">

							<td colspan="8" style="color:#ffffff" align="center"><b>A/R Terms Breakdown</b><br>

								As of <?php

										if ($_GET["date_from"] != "") {

											echo date('F d,Y',	 strtotime($_GET["date_from"]));

										} else {

											echo date('F d,Y');

										}

										?>

							</td>

						</tr>

						<tr style="background: #2B1B17;">

							<td style="color:#ffffff" align="middle">Terms</td>

							<td style="color:#ffffff" align="middle">Invoices</td>

							<td style="color:#ffffff" align="middle">Amount ($)</td>

							<td style="color:#ffffff" align="middle">% of A/R</td>

						</tr>

						<tr>

							<td align="middle">Within Terms</td>

							<td align="right"><?php echo $inv_current_cnt; ?></td>

							<td align="right">$<?php echo number_format($row_comp["inv_current"], 2); ?></td>

							<td align="right"><?php echo number_format(($row_comp["inv_current"] / $tot_inv_unpaid) * 100, 2) . "%"; ?></td>

						</tr>

						<tr>

							<td align="middle">1-30 Days Past Due</td>

							<td align="right"><?php echo $inv_1_30_cnt; ?></td>

							<td align="right">$<?php echo number_format($row_comp["inv_1_30"], 2); ?></td>

							<td align="right"><?php echo number_format(($row_comp["inv_1_30"] / $tot_inv_unpaid) * 100, 2) . "%"; ?></td>

						</tr>

						<tr>

							<td align="middle">31-60 Days Past Due</td>

							<td align="right"><?php echo $inv_31_60_cnt; ?></td>

							<td align="right">$<?php echo number_format($row_comp["inv_31_60"], 2); ?></td>

							<td align="right"><?php echo number_format(($row_comp["inv_31_60"] / $tot_inv_unpaid) * 100, 2) . "%"; ?></td>

						</tr>

						<tr>

							<td align="middle">61-90 Days Past Due</td>

							<td align="right"><?php echo $inv_61_90_cnt; ?></td>

							<td align="right">$<?php echo number_format($row_comp["inv_61_90"], 2); ?></td>

							<td align="right"><?php echo number_format(($row_comp["inv_61_90"] / $tot_inv_unpaid) * 100, 2) . "%"; ?></td>

						</tr>

						<tr>

							<td align="middle">>90 Days Past Due</td>

							<td align="right"><?php echo $inv_91_120_cnt + $inv_121_150_cnt + $inv_gt_150_cnt; ?></td>

							<td align="right">$<?php echo number_format($row_comp["inv_91_120"] + $row_comp["inv_121_150"] + $row_comp["inv_gt_150"], 2); ?></td>

							<td align="right"><?php echo number_format((($row_comp["inv_91_120"] + $row_comp["inv_121_150"] + $row_comp["inv_gt_150"]) / $tot_inv_unpaid) * 100, 2) . "%"; ?></td>

						</tr>

						<tr>

							<td align="right"><b>Totals</b></td>

							<td align="right"><b><?php echo $tot_inv_cnt; ?></b></td>

							<td align="right"><b>$<?php echo number_format($tot_inv_unpaid, 2); ?></b></td>

							<td align="middle">&nbsp;</td>

						</tr>

					</table>



				<?php

						}

				?>



				<br><br>



				<!--Status Summary Report-->

				<table class="summarytable" width="100%">

					<tr style="background: #2B1B17;">

						<td colspan="8" style="color:#ffffff" align="center"><b>A/R Health</b><br>

							As of <?php

									if ($_GET["date_from"] != "") {

										echo date('F d,Y',	 strtotime($_GET["date_from"]));

									} else {

										echo date('F d,Y');

									}

									?>



						</td>

					</tr>

					<tr style="background: #2B1B17;">

						<td style="color:#ffffff" align="middle">Status</td>

						<td style="color:#ffffff" align="middle">Invoices</td>

						<td style="color:#ffffff" align="middle">Amount($)</td>

						<td style="color:#ffffff" align="middle">% of A/R</td>

					</tr>



					<?php

					$status_query = db_query("select id from loop_trans_status");

					while ($status_row = array_shift($status_query)) {

						$status_id[] = $status_row["id"];

					}

					$status_id[] = 0;



					$grand_not_due_total = 0;

					$not_due_total = 0;

					$escal_not_due_total = 0;

					$p2p_not_due_total = 0;

					$paid_not_due_total = 0;

					$noaction_not_due_total = 0;

					$active_total = 0;

					$escal_total = 0;

					$p2p_total = 0;

					$paid_total = 0;

					$noaction_total = 0;

					$paid_grand_total = 0;

					$past_due_total = 0;

					$paid_past_due_total = 0;

					$p2p_past_due_total = 0;

					$noaction_past_due_total = 0;

					$grand_inv_conunt = 0;

					$escal_past_due_total = 0;

					$total_past_due1 = 0;

					$noaction_grand_total = 0;

					$p2p_grand_total = 0;

					$escal_grand_total = 0;

					$active_grand_total = 0;

					$status_length = count($status_id);

					db();

					for ($sl = 0; $sl < $status_length; $sl++) {

						$inv_conunt = 0;

						$total_cnt = 0;

						if ($status_id[$sl] == 0) {

							$invoice_query = db_query("Select * from loop_ar_reptemp_new where userid = " . $userid . " and ((trans_status not in (2,3,4,99)) and (inv_age <= 0)) ");

						} else {

							if ($status_id[$sl] == 1) {

								$invoice_query = db_query("Select * from loop_ar_reptemp_new where userid = " . $userid . " and (inv_age > 0) and trans_status = " . $status_id[$sl]);

							} else {

								$invoice_query = db_query("Select * from loop_ar_reptemp_new where userid = " . $userid . " and trans_status = " . $status_id[$sl]);

							}

						}

						while ($invoice_row = array_shift($invoice_query)) {

							$inv_conunt = $inv_conunt + 1;

							$sql = "SELECT trans_status, order_issue FROM loop_transaction_buyer where id = " . $invoice_row["trans_rec_id"];

							$result_comp = db_query($sql);

							while ($row_comp = array_shift($result_comp)) {

								$trans_status_id = $row_comp["trans_status"];

								$order_issue = $row_comp["order_issue"];

							}

							if ($status_id[$sl] == 1) {

								$active_total = $active_total + 1;



								if ($invoice_row["inv_age"] > 0) {

									$total_past_due1 = $total_past_due1 + 1;

									$past_due_total = $past_due_total + $invoice_row["inv_total"];

								} else {

									$not_due_total = $not_due_total + $invoice_row["inv_total"];

								}



								$active_grand_total = $active_grand_total + $invoice_row["inv_total"];

							}



							if ($status_id[$sl] == 2) {

								$escal_total = $escal_total + 1;



								if ($invoice_row["inv_age"] > 0) {



									$escal_past_due_total = $escal_past_due_total + $invoice_row["inv_total"];

								} else {

									$escal_not_due_total = $escal_not_due_total + $invoice_row["inv_total"];

								}

								$escal_grand_total = $escal_grand_total + $invoice_row["inv_total"];

							}

							if ($status_id[$sl] == 3) {

								$p2p_total = $p2p_total + 1;



								if ($invoice_row["inv_age"] > 0) {



									$p2p_past_due_total = $p2p_past_due_total + $invoice_row["inv_total"];

								} else {

									$p2p_not_due_total = $p2p_not_due_total + $invoice_row["inv_total"];

								}

								$p2p_grand_total = $p2p_grand_total + $invoice_row["inv_total"];

							}

							if ($status_id[$sl] == 4) {

								$paid_total = $paid_total + 1;



								if ($invoice_row["inv_age"] > 0) {



									$paid_past_due_total = $paid_past_due_total + $invoice_row["inv_total"];

								} else {

									$paid_not_due_total = $paid_not_due_total + $invoice_row["inv_total"];

								}

								$paid_grand_total = $paid_grand_total + $invoice_row["inv_total"];

							}

							if ($status_id[$sl] == 0) {

								$noaction_total = $noaction_total + 1;



								if ($invoice_row["inv_age"] > 0) {

									$noaction_past_due_total = $noaction_past_due_total + $invoice_row["inv_total"];

								} else {

									$noaction_not_due_total = $noaction_not_due_total + $invoice_row["inv_total"];

								}

								$noaction_grand_total = $noaction_grand_total + $invoice_row["inv_total"];

							}

							//

							$total_cnt = $total_cnt + $invoice_row["inv_total"];

						}

						$grand_inv_conunt = $grand_inv_conunt + $inv_conunt;

					}



					$grand_not_due_total = $not_due_total + $escal_not_due_total + $p2p_not_due_total + $paid_not_due_total + $noaction_not_due_total;

					$grand_past_due_total = $past_due_total + $escal_past_due_total + $p2p_past_due_total + $paid_past_due_total + $noaction_past_due_total;

					$grand_total = $active_grand_total + $escal_grand_total + $p2p_grand_total + $paid_grand_total + $noaction_grand_total;

					?>

					<tr>

						<td align="middle">Active</strong></td>

						<td align="right"><?php echo $active_total; ?></strong></td>

						<td align="right">$<?php echo number_format($active_grand_total, 2); ?></strong></td>

						<td align="right"><?php echo number_format(($active_grand_total / $grand_total) * 100, 2) . "%"; ?></td>

					</tr>

					<tr>

						<td align="middle">Escalated</strong></td>

						<td align="right"><?php echo $escal_total; ?></strong></td>

						<td align="right">$<?php echo number_format($escal_grand_total, 2); ?></strong></td>

						<td align="right"><?php echo number_format(($escal_grand_total / $grand_total) * 100, 2) . "%"; ?></td>

					</tr>

					<tr>

						<td align="middle">P2P</strong></td>

						<td align="right"><?php echo $p2p_total; ?></strong></td>

						<td align="right">$<?php echo number_format($p2p_grand_total, 2); ?></strong></td>

						<td align="right"><?php echo number_format(($p2p_grand_total / $grand_total) * 100, 2) . "%"; ?></td>

					</tr>

					<tr>

						<td align="middle">Paid</strong></td>

						<td align="right"><?php echo $paid_total; ?></strong></td>

						<td align="right">$<?php echo number_format($paid_grand_total, 2); ?></strong></td>

						<td align="right"><?php echo number_format(($paid_grand_total / $grand_total) * 100, 2) . "%"; ?></td>

					</tr>

					<tr>

						<td align="middle">No Action Needed</strong></td>

						<td align="right"><?php echo $noaction_total; ?></strong></td>

						<td align="right">$<?php echo number_format($noaction_grand_total, 2); ?></strong></td>

						<td align="right"><?php echo number_format(($noaction_grand_total / $grand_total) * 100, 2) . "%"; ?></td>

					</tr>

					<tr>

						<td align="right"><strong>Totals</strong></td>

						<td align="right"><strong><?php echo $grand_inv_conunt; ?></strong></td>

						<td align="right"><strong>$<?php echo number_format($grand_total, 2); ?></strong></td>

						<td align="right">&nbsp;</td>

					</tr>

				</table>



				<br><br>



				<!--Status Summary Report-->

				<table class="summarytable" width="100%">

					<tr style="background: #2B1B17;">

						<td colspan="8" style="color:#ffffff" align="center"><b>A/R track credits (negative Invoice amount)</b><br>

							As of <?php

									if ($_GET["date_from"] != "") {

										echo date('F d,Y',	 strtotime($_GET["date_from"]));

									} else {

										echo date('F d,Y');

									}

									?>



						</td>

					</tr>

					<tr style="background: #2B1B17;">

						<td style="color:#ffffff" align="middle">Status</td>

						<td style="color:#ffffff" align="middle">Invoices</td>

						<td style="color:#ffffff" align="middle">Amount($)</td>

						<td style="color:#ffffff" align="middle">% of A/R</td>

					</tr>



					<?php

					$status_query = db_query("select id from loop_trans_status");

					while ($status_row = array_shift($status_query)) {

						$status_id1[] = $status_row["id"];

					}

					$status_id1[] = 0;

					$grand_not_due_total = 0;

					$not_due_total = 0;

					$escal_not_due_total = 0;

					$p2p_not_due_total = 0;

					$paid_not_due_total = 0;

					$noaction_not_due_total = 0;

					$active_total = 0;

					$escal_total = 0;

					$p2p_total = 0;

					$paid_total = 0;

					$noaction_total = 0;

					$paid_grand_total = 0;

					$active_grand_total = 0;

					$escal_grand_total = 0;

					$p2p_grand_total = 0;

					$paid_grand_total = 0;

					$noaction_grand_total = 0;

					$grand_inv_conunt = 0;

					$past_due_total = 0;

					$paid_past_due_total = 0;

					$p2p_past_due_total = 0;

					$noaction_past_due_total = 0;

					$escal_past_due_total = 0;

					$total_past_due1 = 0;

					$status_length = count($status_id1);

					for ($sl = 0; $sl < $status_length; $sl++) {

						$inv_conunt = 0;

						$total_cnt = 0;

						if ($status_id1[$sl] == 0) {

							$invoice_query = db_query("Select * from loop_ar_reptemp_new where userid = '" . $userid . "' and ((trans_status not in (1,2,3,4,99))) and inv_total < 0 ");

						} else {

							if ($status_id1[$sl] == 1) {

								$invoice_query = db_query("Select * from loop_ar_reptemp_new where userid = '" . $userid . "' and inv_total < 0 and trans_status = '" . $status_id1[$sl] . "'");

							} else {

								$invoice_query = db_query("Select * from loop_ar_reptemp_new where userid = '" . $userid . "' and inv_total < 0 and trans_status = '" . $status_id1[$sl] . "'");

							}

						}

						while ($invoice_row = array_shift($invoice_query)) {

							$inv_conunt = $inv_conunt + 1;

							$sql = "SELECT trans_status, order_issue FROM loop_transaction_buyer where id = " . $invoice_row["trans_rec_id"];

							$result_comp = db_query($sql);

							while ($row_comp = array_shift($result_comp)) {

								$trans_status_id = $row_comp["trans_status"];

								$order_issue = $row_comp["order_issue"];

							}

							if ($status_id1[$sl] == 1) {

								$active_total = $active_total + 1;



								if ($invoice_row["inv_age"] > 0) {

									$total_past_due1 = $total_past_due1 + 1;

									$past_due_total = $past_due_total + $invoice_row["inv_total"];

								} else {

									$not_due_total = $not_due_total + $invoice_row["inv_total"];

								}



								$active_grand_total = $active_grand_total + $invoice_row["inv_total"];

							}



							if ($status_id1[$sl] == 2) {

								$escal_total = $escal_total + 1;



								if ($invoice_row["inv_age"] > 0) {



									$escal_past_due_total = $escal_past_due_total + $invoice_row["inv_total"];

								} else {

									$escal_not_due_total = $escal_not_due_total + $invoice_row["inv_total"];

								}

								$escal_grand_total = $escal_grand_total + $invoice_row["inv_total"];

							}

							if ($status_id1[$sl] == 3) {

								$p2p_total = $p2p_total + 1;



								if ($invoice_row["inv_age"] > 0) {



									$p2p_past_due_total = $p2p_past_due_total + $invoice_row["inv_total"];

								} else {

									$p2p_not_due_total = $p2p_not_due_total + $invoice_row["inv_total"];

								}

								$p2p_grand_total = $p2p_grand_total + $invoice_row["inv_total"];

							}

							//

							if ($status_id1[$sl] == 4) {

								$paid_total = $paid_total + 1;



								if ($invoice_row["inv_age"] > 0) {



									$paid_past_due_total = $paid_past_due_total + $invoice_row["inv_total"];

								} else {

									$paid_not_due_total = $paid_not_due_total + $invoice_row["inv_total"];

								}

								$paid_grand_total = $paid_grand_total + $invoice_row["inv_total"];

							}

							if ($status_id1[$sl] == 0) {

								$noaction_total = $noaction_total + 1;



								if ($invoice_row["inv_age"] > 0) {

									$noaction_past_due_total = $noaction_past_due_total + $invoice_row["inv_total"];

								} else {

									$noaction_not_due_total = $noaction_not_due_total + $invoice_row["inv_total"];

								}

								$noaction_grand_total = $noaction_grand_total + $invoice_row["inv_total"];

							}

							//

							$total_cnt = $total_cnt + $invoice_row["inv_total"];

						}

						$grand_inv_conunt = $grand_inv_conunt + $inv_conunt;

					}



					$grand_not_due_total = $not_due_total + $escal_not_due_total + $p2p_not_due_total + $paid_not_due_total + $noaction_not_due_total;

					$grand_past_due_total = $past_due_total + $escal_past_due_total + $p2p_past_due_total + $paid_past_due_total + $noaction_past_due_total;

					$grand_total = $active_grand_total + $escal_grand_total + $p2p_grand_total + $paid_grand_total + $noaction_grand_total;

					?>



					<tr>

						<td align="middle">Active</strong></td>

						<td align="right"><?php echo $active_total; ?></strong></td>

						<td align="right">$<?php echo number_format($active_grand_total, 2); ?></strong></td>

						<td align="right"><?php echo number_format(($active_grand_total / $grand_total) * 100, 2) . "%"; ?></td>

					</tr>

					<tr>

						<td align="middle">Escalated</strong></td>

						<td align="right"><?php echo $escal_total; ?></strong></td>

						<td align="right">$<?php echo number_format($escal_grand_total, 2); ?></strong></td>

						<td align="right"><?php echo number_format(($escal_grand_total / $grand_total) * 100, 2) . "%"; ?></td>

					</tr>

					<tr>

						<td align="middle">P2P</strong></td>

						<td align="right"><?php echo $p2p_total; ?></strong></td>

						<td align="right">$<?php echo number_format($p2p_grand_total, 2); ?></strong></td>

						<td align="right"><?php echo number_format(($p2p_grand_total / $grand_total) * 100, 2) . "%"; ?></td>

					</tr>

					<tr>

						<td align="middle">Paid</strong></td>

						<td align="right"><?php echo $paid_total; ?></strong></td>

						<td align="right">$<?php echo number_format($paid_grand_total, 2); ?></strong></td>

						<td align="right"><?php echo number_format(($paid_grand_total / $grand_total) * 100, 2) . "%"; ?></td>

					</tr>

					<tr>

						<td align="middle">No Action Needed</strong></td>

						<td align="right"><?php echo $noaction_total; ?></strong></td>

						<td align="right">$<?php echo number_format($noaction_grand_total, 2); ?></strong></td>

						<td align="right"><?php echo number_format(($noaction_grand_total / $grand_total) * 100, 2) . "%"; ?></td>

					</tr>



					<tr>

						<td align="right"><strong>Totals</strong></td>

						<td align="right"><strong><?php echo $grand_inv_conunt; ?></strong></td>

						<td align="right"><strong>$<?php echo number_format($grand_total, 2); ?></strong></td>

						<td align="right">&nbsp;</td>

					</tr>

				</table>



				</td>

				<td valign="top" width="30%" align="center">



					<table class="summarytable" width="90%">

						<tr style="background: #2B1B17;">

							<td colspan="8" style="color:#ffffff" align="center"><b>Pending Credit Applications</b><br></td>

						</tr>

						<?php

						$w_query = db_query("SELECT b2bid,company_name from loop_warehouse where credit_application_file<>'' and credit_application_net_term=''");

						while ($w_row = array_shift($w_query)) {

						?>

							<tr>

								<td>

									<a target="_blank" href="viewCompany.php?ID=<?php echo $w_row["b2bid"]; ?>&proc=View&searchcrit=&show=accounting&rec_type=Supplier">

										<?php echo $w_row["company_name"];  ?></a>

								</td>

							</tr>

						<?php

						}

						?>

					</table>



					<br><br>

					<?php

					$PendingCredit = 0;

					$sql = "SELECT DISTINCT(trans_rec_id) FROM `loop_transaction_buyer_cc`";

					$result = db_query($sql);

					while ($row = array_shift($result)) {

						$x = 0;

						$sql2 = "SELECT * FROM `loop_transaction_buyer_cc` WHERE trans_rec_id = " . $row["trans_rec_id"] . " ORDER BY transaction_type ASC ";

						$result2 = db_query($sql2);

						while ($row2 = array_shift($result2)) {



							if ($row2["transaction_type"] == "Authorize") {

								$x = 1;

							} else $x = 0;

						}



						if ($x == 1) {



							$sql3 = "SELECT shipped FROM `loop_transaction_buyer` WHERE id = '" . $row["trans_rec_id"] . "'";

							$result3 = db_query($sql3);

							while ($row3 = array_shift($result3)) {

								if ($row3["shipped"] == 1) {

									$PendingCredit = $PendingCredit + 1;

								}

							}

						}

					}



					?>

					<a href='#' id="acclist" onclick="acc_list_popup();">Authorized credit card list (<?php echo $PendingCredit; ?>)</a>



					<br><br>

					<table class="summarytable" width="90%">

						<tr style="background: #2B1B17;">

							<td colspan="8" style="color:#ffffff" align="center"><b>Online Invoices Not Uploaded Yet</b><br></td>

						</tr>

					</table>

					<br>

					<?php

					$OnlineInvoicesNotUploaded = 0;

					$sql = "SELECT ID, online_invoicing from companyInfo WHERE online_invoicing <> '' and online_invoicing <> 'None'";

					db_b2b();

					$result = db_query($sql);

					while ($row_main = array_shift($result)) {



						$sql1 = "SELECT loop_transaction_buyer.id as trans_rec_id, warehouse_id, loop_warehouse.b2bid, inv_number, inv_date_of FROM `loop_transaction_buyer` inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE `ignore` = 0 and invoice_paid = 0 and no_invoice = 0 and inv_entered = 1 and online_inv_confirmed = 0 and loop_warehouse.b2bid = '" . $row_main["ID"] . "'";

						db();

						$result1 = db_query($sql1);

						while ($row = array_shift($result1)) {

							$OnlineInvoicesNotUploaded = $OnlineInvoicesNotUploaded + 1;

						}

					}

					?>

					<a href='#' id="online_inv_confirmeddiv" onclick="online_inv_not_confirmed_list_popup();">Online Invoices Not Uploaded Yet list (<?php echo $OnlineInvoicesNotUploaded; ?>)</a>

				</td>

			</tr>

		</table>

		<?php

		if ($_GET["chk_showall"] == "yes") {

			echo "<center><h3 style='margin-bottom:0;'>All records are listed</h3></center>";

			echo "<br><div style='width:100%; text-align:right; margin-right:60px;'><a href='#' onclick='window.history.go(-1); return false;'>Back to Report</a></div>";

		} else {

			echo "<center><h3 style='margin-bottom:0;'>Selected records are listed</h3></center>";

		}

		if ($_GET["summary"] == "View Summary Report") {

			echo "<br><center><h3 style='margin-top:0;'>Summary Report</h3></center>";

		}

		if (isset($_GET["summary"])) {

		}

		?>

		<form method="GET" name="rpt_leaderboard1" action="report_aging.php">

			<input type="hidden" name="date_from" id="date_from" size="10" value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : date("Y-m-d"); ?>">

		</form>



		<table width="100%" cellpadding="1" cellspacing="1" class="main_table">

			<?php

			if (isset($_GET['sort'])) {

				$MGArray[] = array();

				$MGArray = $_SESSION['sortarrayn1'];

				echo getdata_array(1, "ACTIVE: Past Due Customers Who Are Being Actively Pursued For Payment From A/R");



				$MGArray[] = array();

				$MGArray = $_SESSION['sortarrayn6'];

				echo getdata_array(99, "Accounting Issues");



				$MGArray[] = array();

				$MGArray = $_SESSION['sortarrayn2'];

				echo getdata_array(2, "ESCALATED: Past Due Customers Who Are Causing Issues and UCB Leadership Is Involved");



				$MGArray[] = array();

				$MGArray = $_SESSION['sortarrayn7'];

				echo getdata_array(88, "Invoices with negative amounts (Credits).");



				$MGArray[] = array();

				$MGArray = $_SESSION['sortarrayn3'];

				echo getdata_array(3, "PROMISED TO PAY (P2P): Past Due Customers Who A/R Has Received Confirmation of Payment Being Sent");



				$MGArray[] = array();

				$MGArray = $_SESSION['sortarrayn4'];

				echo getdata_array(4, "PAID: Payment Received by UCB, But Not Recorded in System Yet");



				$MGArray[] = array();

				$MGArray = $_SESSION['sortarrayn5'];

				echo getdata_array(0, "NO ACTION NEEDED: Invoices That Are Not Past Due and Require No Action From A/R (Yet)");

			} else {



				$cnt = 0;

				$MGArray[] = array();

				echo getdata(1, "ACTIVE: Past Due Customers Who Are Being Actively Pursued For Payment From A/R");

				$_SESSION['sortarrayn1'] = $MGArray;



				unset($MGArray);

				$MGArray[] = array();

				echo getdata(99, "Accounting Issues");

				$_SESSION['sortarrayn6'] = $MGArray;



				unset($MGArray);

				$MGArray[] = array();

				echo getdata(2, "ESCALATED: Past Due Customers Who Are Causing Issues and UCB Leadership Is Involved");

				$_SESSION['sortarrayn2'] = $MGArray;



				unset($MGArray);

				$MGArray[] = array();

				echo getdata(88, "Invoices with negative amounts (Credits).");

				$_SESSION['sortarrayn2'] = $MGArray;



				unset($MGArray);

				$MGArray[] = array();

				echo getdata(3, "PROMISED TO PAY (P2P): Past Due Customers Who A/R Has Received Confirmation of Payment Being Sent");

				$_SESSION['sortarrayn3'] = $MGArray;



				unset($MGArray);

				$MGArray[] = array();

				echo getdata(4, "PAID: Payment Received by UCB, But Not Recorded in System Yet");

				$_SESSION['sortarrayn4'] = $MGArray;



				unset($MGArray);

				$MGArray[] = array();

				echo getdata(0, "NO ACTION NEEDED: Invoices That Are Not Past Due and Require No Action From A/R (Yet)");

				$_SESSION['sortarrayn5'] = $MGArray;

			}



			?>

		</table>



		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

		<script>

			function makeAjaxRequest(opts) {

				$.ajax({

					type: "POST",

					data: {

						opts: opts

					},

					url: "process_ajax.php",

					success: function(res) {

						$("#results").html("<p>$_POST contained: " + res + "</p>");

					}

				});

			}



			$("#ag_template").on("change", function() {

				var selected = $(this).val();

				alert(selected);

				makeAjaxRequest(selected);

			});

		</script>

	</div>

</body>



</html>