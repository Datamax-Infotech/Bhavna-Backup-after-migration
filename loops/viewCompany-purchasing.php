<?php

ini_set("display_errors", "1");
error_reporting(E_ERROR);
require_once("inc/header_session.php");
require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

//require "tablefunctions_mrg_purchasing.php";

echo "<LINK rel='stylesheet' type='text/css' href='one_style_mrg.css' >";

if (isset($_REQUEST["warehouse_id"])) {
    $warehouse_id = $_REQUEST["warehouse_id"];
}
if (isset($_REQUEST["id"])) {
    $warehouse_id = $_REQUEST["id"];
}
if (isset($_REQUEST["ID"])) {
    $warehouse_id = $_REQUEST["ID"];
}
$rec_id = 0;
if (isset($_REQUEST["rec_id"])) {
    $rec_id = $_REQUEST["rec_id"];
}

$display_val = "";
if (isset($_REQUEST["display"])) {
    $display_val = $_REQUEST["display"];
}

//
$nickname_title = "";
db_b2b();
$qry_1 = "Select company, nickname from companyInfo Where ID = '" . $_REQUEST["ID"] . "'";
$dt_view_1 = db_query($qry_1);

while ($rows = array_shift($dt_view_1)) {
    if ($rows["nickname"] == "") {
        $nickname_title = $rows["company"];
    } else {
        $nickname_title = $rows["nickname"];
    }
}

db();
$qry_2 = "Select b2bid from loop_warehouse where b2bid = '" . $_REQUEST["ID"] . "'";
$dt_view_2 = db_query($qry_2);
$duplicate_chk = tep_db_num_rows($dt_view_2);

?>

<html>

<head>
    <style type="text/css">
        .main_data_css {
            margin: 0 auto;
            width: 100%;
            height: auto;
            clear: both !important;
            padding-top: 35px;
            margin-left: 10px;
            margin-right: 10px;
        }

        pre {
            /* height: 200px; */
            width: 380px;
            overflow: auto;
            font-size: 8pt;
            text-align: left;
            overflow-x: auto;
            /* Use horizontal scroller if needed; for Firefox 2, 
	notwhite-space: pre-wrap;	/* css-3 */
            white-space: -moz- pre-wrap !important;
            /* Mozilla, since 1999 */
            word-wrap: break-word;
            /* Internet Explorer 5.5+ */
            margin: 0px 0px 0px 0px;
            padding: 5px 5px 3px 5px;
            white-space: normal;
            /* crucial for IE 6, maybe 7? */
        }

        .input-color {
            width: 40px;
            height: 40px;
            display: inline-block;
            background-color: #ccc;
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

        .white_content_details {
            display: none;
            position: absolute;
            top: 0%;
            left: 10%;
            width: 50%;
            height: auto;
            padding: 16px;
            border: 1px solid gray;
            background-color: white;
            z-index: 1002;
            overflow: auto;
            box-shadow: 8px 8px 5px #888888;
        }


        .white_content_reminder {
            display: none;
            position: absolute;
            top: 10%;
            left: 10%;
            width: 70%;
            height: 85%;
            padding: 16px;
            border: 1px solid gray;
            background-color: white;
            z-index: 1002;
            overflow: auto;
            box-shadow: 8px 8px 5px #888888;
        }

        .white_content_gaylord_new {
            display: none;
            position: absolute;
            top: 0%;
            left: 0%;
            width: 1200px;
            height: 520px;
            padding: 16px;
            border: 1px solid gray;
            background-color: white;
            z-index: 1002;
            -moz-box-shadow: 6px 6px 6px 6px #888888;
            -webkit-box-shadow: 6px 6px 6px 6px #888888;
            box-shadow: 6px 6px 6px 6px #888888;
            filter: progid:DXImageTransform.Microsoft.DropShadow(OffX=6, OffY=6, Color=#888888);
        }

        .white_content_gaylord_new1 {
            display: none;
            position: absolute;
            top: 0%;
            left: 0%;
            width: 1200px;
            height: 520px;
            padding: 16px;
            border: 1px solid gray;
            background-color: white;
            z-index: 1002;
            -moz-box-shadow: 6px 6px 6px 6px #888888;
            -webkit-box-shadow: 6px 6px 6px 6px #888888;
            box-shadow: 6px 6px 6px 6px #888888;
            filter: progid:DXImageTransform.Microsoft.DropShadow(OffX=6, OffY=6, Color=#888888);
        }

        .white_content_gaylord {
            display: none;
            position: absolute;
            top: 0%;
            left: 0%;
            width: 600px;
            height: 520px;
            padding: 16px;
            border: 1px solid gray;
            background-color: white;
            z-index: 1002;
            -moz-box-shadow: 6px 6px 6px 6px #888888;
            -webkit-box-shadow: 6px 6px 6px 6px #888888;
            box-shadow: 6px 6px 6px 6px #888888;
            filter: progid:DXImageTransform.Microsoft.DropShadow(OffX=6, OffY=6, Color=#888888);
        }

        .white_content_quota {
            display: none;
            position: absolute;
            top: 0%;
            left: 0%;
            border: 1px solid gray;
            background-color: white;
            z-index: 1002;
            -moz-box-shadow: 6px 6px 6px 6px #888888;
            -webkit-box-shadow: 6px 6px 6px 6px #888888;
            box-shadow: 6px 6px 6px 6px #888888;
            filter: progid:DXImageTransform.Microsoft.DropShadow(OffX=6, OffY=6, Color=#888888);
            width: 800px;
        }

        .txt_style12 {
            font-size: xx-small;
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
        }

        .txt_style12_bold {
            font-size: 8pt;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            text-align: center;
        }

        .scrollit {
            overflow: auto;
            width: 1200px;
            height: 450px;
        }

        .show_iframe {
            width: 900px;
        }

        .show_iframe_sales {
            width: 950px;
        }

        .show_iframe_compinfo {
            width: 1335px;
        }

        .show_trans_iframe {
            width: 752px;
        }

        .dtclasslog {
            top: 140px !important;
            left: 300px !important;
        }
    </style>

    <title><?php echo $nickname_title; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" src="js/jquery.js"></script>
    <script language="JavaScript" src="gen_functions.js"></script>
    <link rel="stylesheet" type="text/css" media="all" href="js/calendar-win2k-1.css" title="win2k-1">
    <script language="JavaScript" type="text/javascript" src="js/calendar.js"></script>
    <script language="JavaScript" type="text/javascript" src="js/calendar-en.js"></script>
    <script type="text/javascript" src="js/calendar-setup.js"></script>

    <link rel="stylesheet" type="text/css" href="tcal.css" />
    <script type="text/javascript" src="tcal.js"></script>
    <script type="text/javascript">
        function resizeIframe(iframe) {
            // alert(iframe.contentWindow.document.body.scrollHeight);
            iframe.height = iframe.contentWindow.document.body.scrollHeight + 400 + "px";
        }

        function resizeIframeTrans(iframe) {
            // alert(iframe.contentWindow.document.body.scrollHeight);
            iframe.style.width = window.screen.width + "px";
            iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 100 + "px";
            //show_trans
        }

        function resizeIframe1(iframe) {
            iframe.height = iframe.contentWindow.document.body.scrollHeight + 100 + "px";
        }

        function resizeIframeA(iframe) {
            iframe.height = iframe.contentWindow.document.body.scrollHeight + 100 + "px";
        }
    </script>

    <script LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></script>
    <script LANGUAGE="JavaScript" SRC="inc/general.js"></script>
    <script LANGUAGE="JavaScript">
        document.write(getCalendarStyles());
    </script>
    <script LANGUAGE="JavaScript">
        var logeditdt = new CalendarPopup("loglistdiv_edit");
        logeditdt.showNavigationDropdowns();
    </script>

    <script language="javascript">
        function update_edit_item(unqid) {

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    //document.getElementById("todo_div").innerHTML = xmlhttp.responseText;
                    document.getElementById("show_crm").contentWindow.location.reload(true);
                    document.getElementById('light_reminder').style.display = 'none';
                }
            }

            var compid = document.getElementById('todo_companyID').value;
            var todo_message = document.getElementById('todo_message_edit').value;
            var todo_employee = document.getElementById('todo_employee_edit').value;
            var todo_date = document.getElementById('todo_date_edit').value;
            var task_priority = document.getElementById('task_priority_edit').value;

            xmlhttp.open("GET", "todolist_update.php?inedit_mode=1&unqid=" + unqid + "&compid=" + compid +
                "&todo_message=" + encodeURIComponent(todo_message) + "&todo_employee=" + todo_employee +
                "&todo_date=" + todo_date + "&task_priority=" + encodeURIComponent(task_priority), true);
            xmlhttp.send();
        }

        function show_ops_dt() {
            document.getElementById('tbl_ops_delivery_dt_display').style.display = 'none';
            document.getElementById('tbl_ops_delivery_dt').style.display = 'block';
        }

        function collapse_CRM(selval) {
            if (selval == 1) {
                document.getElementById('div_CRM_child').style.display = 'block';
            } else {
                document.getElementById('div_CRM_child').style.display = 'none';
            }
        }

        function expand_CRM(d, wid, comid, rectype, show_trans) {
            document.getElementById("div_CRM_child").innerHTML =
                "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                    document.getElementById("div_CRM_child").innerHTML = xmlhttp.responseText;
                    document.getElementById('div_CRM_child').style.display = 'block';

                }
            }

            xmlhttp.open("POST", "viewCompany-CRM.php?loopid=" + wid + "&b2bid=" + comid + "&rec_type=" + rectype +
                "&show_trans=" + show_trans, true);
            xmlhttp.send();
        }

        function update_ops_delivery_dt(trans_rec_id) {
            document.getElementById('tbl_ops_delivery_dt_display').style.display = 'block';
            document.getElementById('tbl_ops_delivery_dt').style.display = 'none';
            document.getElementById("tbl_ops_delivery_dt_display").innerHTML =
                "<br><br>Loading .....<img src='images/wait_animated.gif' />";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("tbl_ops_delivery_dt_display").innerHTML = xmlhttp.responseText;
                }
            }
            var po_delivery_dt_tmp = document.getElementById('ops_delivery_dt').value;
            xmlhttp.open("GET", "ops_deliverydt_update.php?trans_rec_id=" + trans_rec_id + "&ops_delivery_dt=" +
                po_delivery_dt_tmp, true);
            xmlhttp.send();
        }

        function log_update_edit(unqid, compid) {
            //document.getElementById("log_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    //document.getElementById("show_crm").document.getElementById("log_div").innerHTML = xmlhttp.responseText;
                    document.getElementById("show_crm").contentWindow.location.reload(true);
                    document.getElementById('light').style.display = 'none';
                }
            }

            var logsMessage = document.getElementById('log_message_edit').value;
            var logsEmployee = document.getElementById('log_employee_edit').value;
            var logsDate = document.getElementById('log_date_edit').value;
            var logsPriority = document.getElementById('log_priority_edit').value;

            xmlhttp.open("GET", "logs_details_update.php?inedit_mode=1&unqid=" + unqid + "&logsCompanyID=" + compid +
                "&logsMessage=" + encodeURIComponent(logsMessage) + "&logsEmployee=" + logsEmployee + "&logsDate=" +
                logsDate + "&logsPriority=" + encodeURIComponent(logsPriority), true);
            xmlhttp.send();
        }


        function btnsendeml_sendrecordlinkbtn() {
            var tmp_element1, tmp_element2, tmp_element3, tmp_element4, tmp_element5;

            tmp_element1 = document.getElementById("txtemailto").value;

            tmp_element2 = document.getElementById("email_reminder");

            tmp_element3 = document.getElementById("txtemailcc").value;

            tmp_element4 = document.getElementById("txtemailsubject").value;

            tmp_element5 = document.getElementById("hidden_reply_eml");

            if (tmp_element1.value == "") {
                alert("Please enter the To Email address.");
                return false;
            }

            if (tmp_element4.value == "") {
                alert("Please enter the Email Subject.");
                return false;
            }

            if (tmp_element3.value == "") {
                alert("Please enter the Cc Email address.");
                return false;
            }


            var inst = FCKeditorAPI.GetInstance("txtemailbody");
            var emailtext = inst.GetHTML();

            tmp_element5.value = emailtext;
            //alert(tmp_element5.value);
            document.getElementById("hidden_sendemail").value = "inemailmode";
            tmp_element2.submit();

        }

        function display_sales_order_sel(tmpcnt, box_id) {
            if (document.getElementById('inventory_preord_top_' + tmpcnt).style.display == 'table-row') {
                document.getElementById('inventory_preord_top_' + tmpcnt).style.display = 'none';
            } else {
                document.getElementById('inventory_preord_top_' + tmpcnt).style.display = 'table-row';
            }

            document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML =
                "<br><br>Loading .....<img src='images/wait_animated.gif' />";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML = xmlhttp.responseText;
                }
            }

            xmlhttp.open("GET", "sales_order_inv_orders_data.php?box_id=" + box_id + "&tmpcnt=" + tmpcnt, true);
            xmlhttp.send();
        }


        function savetranslog(warehouse_id, transid, tmpcnt, box_id) {
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    alert("Data saved.");
                    document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML = xmlhttp.responseText;
                }
            }

            logdetail = document.getElementById("trans_notes" + transid + tmpcnt).value;
            opsdate = document.getElementById("ops_delivery_date" + transid + tmpcnt).value;

            xmlhttp.open("GET", "gaylordstatus_savetranslog.php?box_id=" + box_id + "&tmpcnt=" + tmpcnt + "&warehouse_id=" +
                warehouse_id + "&transid=" + transid + "&logdetail=" + logdetail + "&opsdate=" + opsdate, true);
            xmlhttp.send();
        }

        function show_po_dt() {
            document.getElementById('tbl_po_delivery_dt_display').style.display = 'none';
            document.getElementById('tbl_po_delivery_dt').style.display = 'block';
        }

        function update_po_delivery_dt(trans_rec_id) {
            //alert(selectedText);
            document.getElementById('tbl_po_delivery_dt_display').style.display = 'block';
            document.getElementById('tbl_po_delivery_dt').style.display = 'none';
            document.getElementById("tbl_po_delivery_dt_display").innerHTML =
                "<br><br>Loading .....<img src='images/wait_animated.gif' />";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("tbl_po_delivery_dt_display").innerHTML = xmlhttp.responseText;
                }
            }
            var po_delivery_dt_tmp = document.getElementById('po_delivery_dt').value;
            xmlhttp.open("GET", "po_deliverydt_update.php?trans_rec_id=" + trans_rec_id + "&po_delivery_dt=" +
                po_delivery_dt_tmp, true);
            xmlhttp.send();
        }


        function timedCount() {
            //initv = strTrim(document.intNotes.green_initiative.value);
            intnotes = strTrim(document.intNotes.int_notes.value);
            companyID = strTrim(document.intNotes.companyID.value);
            if (intnotes == "") {} else {
                url = "updateIntNotesAjax.asp?int_notes=" + intnotes + "&companyID=" + companyID;
                $.get(url, function(data) {
                    document.getElementById('msgNote').innerHTML = data;
                });
            }
        }

        function delete_box(id) {
            if (confirm('Are you sure to delete the Box?')) {
                self.location = 'viewCompany.asp?action=dbox&id=<?php echo $_REQUEST["ID"] ?>&bid=' + id;
            }
        }

        function DK() {
            document.intNotes.submit();
        }

        function displayemail(id) {
            document.getElementById("light").innerHTML = document.getElementById("emlmsg" + id).innerHTML;
            document.getElementById('light').style.display = 'block';
            document.getElementById('fade').style.display = 'block';
        }

        function editcompany() {
            document.getElementById('display_cmp').style.display = 'none';
            document.getElementById('display_cmp_edit').style.display = 'block';
        }

        function updateactiveflg(flg) {
            if (flg == 0) {
                if (confirm("Do you wish to mark the company as Inactive?") == true) {
                    frmactiveflg.submit();
                }
            } else {
                if (confirm("Do you wish to mark the company as Active?") == true) {
                    frmactiveflg.submit();
                }
            }
        }

        function updateonholdflg(flg) {
            if (flg == 0) {
                if (confirm("Do you want to update On hold flag?") == true) {
                    document.comp_putonhold.submit();
                }
            } else {
                if (confirm("Do you want to remove On hold flag?") == true) {
                    document.comp_putonhold.submit();
                }
            }
        }

        function Add_quote_manual_fun() {
            var cnt = document.getElementById("add_quote_manual_cnt").value;
            cnt = parseInt(cnt);
            cnt = cnt + 1;
            var sstr = "<tr align='center' bgcolor='#E4E4E4'>";
            sstr = sstr + "<td >";
            sstr = sstr + "<input type='text' name='box_desc_" + cnt + "' id='box_desc_" + cnt + "' size='70' />";
            sstr = sstr + "</td>";
            sstr = sstr + "<td>";
            sstr = sstr + "<input type='text' name='box_salesp_" + cnt + "' id='box_salesp_" + cnt + "' size='10' />";
            sstr = sstr + "</td>";
            sstr = sstr + "<td>";
            sstr = sstr + "<input type='text' name='box_qty_" + cnt + "' id='box_qty_" + cnt + "' size='10' />";
            sstr = sstr + "</td>";
            sstr = sstr + "</tr>";

            document.getElementById("quote_manual").innerHTML = document.getElementById("quote_manual").innerHTML + " " +
                sstr;
            document.getElementById("add_quote_manual_cnt").value = cnt;
        }


        function toggleContent() {
            var contentId = document.getElementById("quote_content");
            var contenttxt = document.getElementById("btn_quote");

            if (contentId.style.display == "none") {
                contentId.style.display = "block";
                contenttxt.innerHTML = "Hide";
            } else {
                contentId.style.display = "none";
                contenttxt.innerHTML = "Show";
            }
        }

        function industry_chg() {
            var industry_txt = document.getElementById("industry_id").value;

            if (industry_txt == 13 || industry_txt == 19) {
                industry_txt_td.style.display = "block";
            } else {
                industry_txt_td.style.display = "none";
            }
        }

        function parent_ch_chg() {
            var parent_child_txt = document.getElementById("parent_child").value;

            if (parent_child_txt == "Child") {
                parent_child_td.style.display = "block";
            }
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

        function btnsendemlclick(tmpcnt) {
            var tmp_element1, tmp_element2, tmp_element3, tmp_element4, tmp_element5;

            tmp_element1 = document.getElementById("txtemailto").value;

            tmp_element2 = document.getElementById("email_reminder");

            tmp_element3 = document.getElementById("txtemailcc").value;

            tmp_element4 = document.getElementById("txtemailsubject").value;

            tmp_element5 = document.getElementById("hidden_reply_eml");

            if (tmp_element1.value == "") {
                alert("Please enter the To Email address.");
                return false;
            }

            if (tmp_element4.value == "") {
                alert("Please enter the Email Subject.");
                return false;
            }

            if (tmp_element3.value == "") {
                alert("Please enter the Cc Email address.");
                return false;
            }


            var inst = FCKeditorAPI.GetInstance("txtemailbody");
            var emailtext = inst.GetHTML();

            tmp_element5.value = emailtext;
            //alert(tmp_element5.value);
            document.getElementById("hidden_sendemail").value = "inemailmode";
            tmp_element2.submit();

        }



        function updategaylordbox() {
            bid = document.getElementById("bid").value;
            //alert(bid);
            id = document.getElementById("id").value;

            shape_rect = "";
            if (document.getElementById("shape_rect").checked == true) {
                shape_rect = 'on';
            }
            shape_oct = "";
            if (document.getElementById("shape_oct").checked == true) {
                shape_oct = 'on';
            }
            wall_2 = "";
            if (document.getElementById("wall_2").checked == true) {
                wall_2 = 'on';
            }
            wall_3 = "";
            if (document.getElementById("wall_3").checked == true) {
                wall_3 = 'on';
            }
            wall_4 = "";
            if (document.getElementById("wall_4").checked == true) {
                wall_4 = 'on';
            }
            wall_5 = "";
            if (document.getElementById("wall_5").checked == true) {
                wall_5 = 'on';
            }
            wall_6 = "";
            if (document.getElementById("wall_6").checked == true) {
                wall_6 = 'on';
            }
            wall_7 = "";
            if (document.getElementById("wall_7").checked == true) {
                wall_7 = 'on';
            }
            wall_8 = "";
            if (document.getElementById("wall_8").checked == true) {
                wall_8 = 'on';
            }
            top_nolid = "";
            if (document.getElementById("top_nolid").checked == true) {
                top_nolid = 'on';
            }
            top_partial = "";
            if (document.getElementById("top_partial").checked == true) {
                top_partial = 'on';
            }
            top_full = "";
            if (document.getElementById("top_full").checked == true) {
                top_full = 'on';
            }
            top_hinged = "";
            if (document.getElementById("top_hinged").checked == true) {
                top_hinged = 'on';
            }
            top_remove = "";
            if (document.getElementById("top_remove").checked == true) {
                top_remove = 'on';
            }
            bottom_no = "";
            if (document.getElementById("bottom_no").checked == true) {
                bottom_no = 'on';
            }
            bottom_partial = "";
            if (document.getElementById("bottom_partial").checked == true) {
                bottom_partial = 'on';
            }
            bottom_partialsheet = "";
            if (document.getElementById("bottom_partialsheet").checked == true) {
                bottom_partialsheet = 'on';
            }
            bottom_fullflap = "";
            if (document.getElementById("bottom_fullflap").checked == true) {
                bottom_fullflap = 'on';
            }
            bottom_interlocking = "";
            if (document.getElementById("bottom_interlocking").checked == true) {
                bottom_interlocking = 'on';
            }
            bottom_tray = "";
            if (document.getElementById("bottom_tray").checked == true) {
                bottom_tray = 'on';
            }
            vents_no = "";
            if (document.getElementById("vents_no").checked == true) {
                vents_no = 'on';
            }

            vents_yes = "";
            if (document.getElementById("vents_yes").checked == true) {
                vents_yes = 'on';
            }
            box_pallet = "";
            if (document.getElementById("box_pallet").checked == true) {
                box_pallet = 'on';
            }
            if (document.getElementById("shape")) {
                shape = document.getElementById("shape").value;
            } else {
                shape = "";
            }
            if (document.getElementById("wall")) {
                wall = document.getElementById("wall").value;
            } else {
                wall = "";
            }
            if (document.getElementById("thetop")) {
                thetop = document.getElementById("thetop").value;
            } else {
                thetop = "";
            }
            if (document.getElementById("bottom")) {
                bottom = document.getElementById("bottom").value;
            } else {
                bottom = "";
            }
            if (document.getElementById("vents")) {
                vents = document.getElementById("vents").value;
            } else {
                vents = "";
            }
            if (document.getElementById("box_condition")) {
                box_condition = document.getElementById("box_condition").value;
            } else {
                box_condition = "";
            }
            if (document.getElementById("quantity")) {
                quantity = document.getElementById("quantity").value;
            } else {
                quantity = "";
            }
            if (document.getElementById("frequency")) {
                frequency = document.getElementById("frequency").value;
            } else {
                frequency = "";
            }
            if (document.getElementById("previous_contents")) {
                previous_contents = document.getElementById("previous_contents").value;
            } else {
                previous_contents = "";
            }
            if (document.getElementById("largest_qty")) {
                largest_qty = document.getElementById("largest_qty").value;
            } else {
                largest_qty = "";
            }
            if (document.getElementById("loading")) {
                loading = document.getElementById("loading").value;
            } else {
                loading = "";
            }
            if (document.getElementById("price_beat")) {
                price_beat = document.getElementById("price_beat").value;
            } else {
                price_beat = "";
            }
            if (document.getElementById("delivery_date")) {
                delivery_date = document.getElementById("delivery_date").value;
            } else {
                delivery_date = "";
            }
            height_range_min = "";
            height_range_max = "";
            if (document.getElementById("height_range_min")) {
                height_range_min = document.getElementById("height_range_min").value;
            } else {
                height_range_min = "";
            }
            if (document.getElementById("height_range_max")) {
                height_range_max = document.getElementById("height_range_max").value;
            } else {
                height_range_max = "";
            }

            //
            if (document.getElementById("glength")) {
                glength = document.getElementById("glength").value;
            } else {
                glength = "";
            }
            if (document.getElementById("gwidth")) {
                gwidth = document.getElementById("gwidth").value;
            } else {
                gwidth = "";
            }
            if (document.getElementById("gheight")) {
                gheight = document.getElementById("gheight").value;
            } else {
                gheight = "";
            }
            if (document.getElementById("gbox_size_id")) {
                gbox_size_id = document.getElementById("gbox_size_id").value;
            } else {
                gbox_size_id = "";
            }
            //alert(gbox_size_id);
            //

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("divGaylordBox").innerHTML = xmlhttp.responseText;
                    document.getElementById("divGaylordBox").style.display = 'block';
                    document.getElementById("divGaylordBoxEdit").style.display = 'none';
                }
            }

            xmlhttp.open("POST", "editTables_dynamic.php?editTable=boxesGaylord&bid=" + bid + "&id=" + id + "&shape=" +
                shape + "&wall=" + wall + "&thetop=" + thetop + "&bottom=" + bottom + "&vents=" + vents +
                "&box_condition=" + box_condition + "&quantity=" + quantity + "&frequency=" + frequency +
                "&previous_contents=" + previous_contents + "&largest_qty=" + largest_qty + "&loading=" + loading +
                "&price_beat=" + price_beat + "&delivery_date=" + delivery_date + "&shape_rect=" + shape_rect +
                "&shape_oct=" + shape_oct + "&wall_2=" + wall_2 + "&wall_3=" + wall_3 + "&wall_4=" + wall_4 +
                "&wall_5=" + wall_5 + "&wall_6=" + wall_6 + "&wall_7=" + wall_7 + "&wall_8=" + wall_8 + "&top_nolid=" +
                top_nolid + "&top_partial=" + top_partial + "&top_full=" + top_full + "&top_hinged=" + top_hinged +
                "&top_remove=" + top_remove + "&bottom_no=" + bottom_no + "&bottom_partial=" + bottom_partial +
                "&bottom_partialsheet=" + bottom_partialsheet + "&bottom_fullflap=" + bottom_fullflap +
                "&bottom_interlocking=" + bottom_interlocking + "&bottom_tray=" + bottom_tray + "&vents_no=" +
                vents_no + "&vents_yes=" + vents_yes + "&box_pallet=" + box_pallet + "&height_range_min=" +
                height_range_min + "&height_range_max=" + height_range_max + "&glength=" + glength + "&gwidth=" +
                gwidth + "&gheight=" + gheight + "&gbox_size_id=" + gbox_size_id, true);
            //xmlhttp.open("POST","editTables_dynamic.php?editTable=boxesGaylord&bid="+bid+"&id="+id+"&shape_rect="+shape_rect,true);			
            xmlhttp.send();
        }

        function loadmainpg() {
            selectboxes = document.getElementById("selectboxes");
            var value = selectboxes.options[selectboxes.selectedIndex].value;
            document.getElementById("txt").value = value;

            if (value == 'Gaylord Boxes') {
                document.getElementById("Gaylord").style.display = "block";
                document.getElementById("Shipping").style.display = "none";
            } else if (value == 'Shipping Boxes') {
                document.getElementById("Shipping").style.display = "block";
                document.getElementById("Gaylord").style.display = "none";
            } else if (value == 'Please Select') {
                document.getElementById("Shipping").style.display = "none";
                document.getElementById("Gaylord").style.display = "none";
            }
        }

        function updateBoxRescueAddnew() {
            compid = document.getElementById("editcompanyid").value;

            if (document.getElementById("selectboxes")) {
                selectboxes = document.getElementById("selectboxes").value;
            } else {
                selectboxes = "";
            }
            if (document.getElementById("shape")) {
                shape = document.getElementById("shape").value;
            } else {
                shape = "";
            }
            if (document.getElementById("top")) {
                top1 = document.getElementById("top").value;
            } else {
                top1 = "";
            }
            if (document.getElementById("bottom")) {
                bottom1 = document.getElementById("bottom").value;
            } else {
                bottom1 = "";
            }
            if (document.getElementById("vents")) {
                vents = document.getElementById("vents").value;
            } else {
                vents = "";
            }
            if (document.getElementById("wall")) {
                wall = document.getElementById("wall").value;
            } else {
                wall = "";
            }
            if (document.getElementById("previous_contents")) {
                previous_contents = document.getElementById("previous_contents").value;
            } else {
                previous_contents = "";
            }
            if (document.getElementById("box_condition")) {
                box_condition = document.getElementById("box_condition").value;
            } else {
                box_condition = "";
            }
            if (document.getElementById("frequency")) {
                frequency = document.getElementById("frequency").value;
            } else {
                frequency = "";
            }
            if (document.getElementById("no_of_rescue")) {
                no_of_rescue = document.getElementById("no_of_rescue").value;
            } else {
                no_of_rescue = "";
            }

            if (document.getElementById("length_lside")) {
                length_lside = document.getElementById("length_lside").value;
            } else {
                length_lside = "";
            }
            if (document.getElementById("width")) {
                width1 = document.getElementById("width").value;
            } else {
                width1 = "";
            }
            if (document.getElementById("height")) {
                height1 = document.getElementById("height").value;
            } else {
                height1 = "";
            }
            if (document.getElementById("wall_sh")) {
                wall_sh = document.getElementById("wall_sh").value;
            } else {
                wall_sh = "";
            }
            if (document.getElementById("box_condition_sh")) {
                box_condition_sh = document.getElementById("box_condition_sh").value;
            } else {
                box_condition_sh = "";
            }
            if (document.getElementById("req_another_box")) {
                req_another_box = document.getElementById("req_another_box").value;
            } else {
                req_another_box = "";
            }
            if (document.getElementById("frequency_sh")) {
                frequency_sh = document.getElementById("frequency_sh").value;
            } else {
                frequency_sh = "";
            }
            if (document.getElementById("no_of_rescue_sh")) {
                no_of_rescue_sh = document.getElementById("no_of_rescue_sh").value;
            } else {
                no_of_rescue_sh = "";
            }

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("divBoxRescue").innerHTML = xmlhttp.responseText;
                    document.getElementById("divBoxRescue").style.display = 'block';
                    document.getElementById("divBoxRescueNew").style.display = 'none';
                }
            }
            xmlhttp.open("POST", "editTables_dynamic.php?boxrescue_add=yes&companyid=" + compid + "&selectboxes=" +
                selectboxes + "&shape=" + shape + "&top1=" + top1 + "&bottom1=" + bottom1 + "&vents=" + vents +
                "&wall=" + wall + "&previous_contents=" + previous_contents + "&box_condition=" + box_condition +
                "&frequency=" + frequency + "&no_of_rescue=" + no_of_rescue + "&length_lside=" + length_lside +
                "&width1=" + width1 + "&height1=" + height1 + "&wall_sh=" + wall_sh + "&box_condition_sh=" +
                box_condition_sh + "&req_another_box=" + req_another_box + "&frequency_sh=" + frequency_sh +
                "&no_of_rescue_sh=" + no_of_rescue_sh, true);

            xmlhttp.send();
        }

        function updateBoxRescueEditnew(ctrlid, recid) {
            compid = document.getElementById("editcompanyid_new").value;

            if (document.getElementById("selectboxes" + ctrlid)) {
                selectboxes = document.getElementById("selectboxes" + ctrlid).value;
            } else {
                selectboxes = "";
            }
            if (document.getElementById("shape" + ctrlid)) {
                shape = document.getElementById("shape" + ctrlid).value;
            } else {
                shape = "";
            }
            if (document.getElementById("top" + ctrlid)) {
                top1 = document.getElementById("top" + ctrlid).value;
            } else {
                top1 = "";
            }
            if (document.getElementById("bottom" + ctrlid)) {
                bottom1 = document.getElementById("bottom" + ctrlid).value;
            } else {
                bottom1 = "";
            }
            if (document.getElementById("vents" + ctrlid)) {
                vents = document.getElementById("vents" + ctrlid).value;
            } else {
                vents = "";
            }
            if (document.getElementById("wall" + ctrlid)) {
                wall = document.getElementById("wall" + ctrlid).value;
            } else {
                wall = "";
            }
            if (document.getElementById("previous_contents" + ctrlid)) {
                previous_contents = document.getElementById("previous_contents" + ctrlid).value;
            } else {
                previous_contents = "";
            }
            if (document.getElementById("box_condition" + ctrlid)) {
                box_condition = document.getElementById("box_condition" + ctrlid).value;
            } else {
                box_condition = "";
            }
            if (document.getElementById("frequency" + ctrlid)) {
                frequency = document.getElementById("frequency" + ctrlid).value;
            } else {
                frequency = "";
            }
            if (document.getElementById("no_of_rescue" + ctrlid)) {
                no_of_rescue = document.getElementById("no_of_rescue" + ctrlid).value;
            } else {
                no_of_rescue = "";
            }

            if (document.getElementById("length_lside" + ctrlid)) {
                length_lside = document.getElementById("length_lside" + ctrlid).value;
            } else {
                length_lside = "";
            }
            if (document.getElementById("width" + ctrlid)) {
                width1 = document.getElementById("width" + ctrlid).value;
            } else {
                width1 = "";
            }
            if (document.getElementById("height" + ctrlid)) {
                height1 = document.getElementById("height" + ctrlid).value;
            } else {
                height1 = "";
            }
            if (document.getElementById("wall_sh" + ctrlid)) {
                wall_sh = document.getElementById("wall_sh" + ctrlid).value;
            } else {
                wall_sh = "";
            }
            if (document.getElementById("box_condition_sh" + ctrlid)) {
                box_condition_sh = document.getElementById("box_condition_sh" + ctrlid).value;
            } else {
                box_condition_sh = "";
            }
            if (document.getElementById("req_another_box" + ctrlid)) {
                req_another_box = document.getElementById("req_another_box" + ctrlid).value;
            } else {
                req_another_box = "";
            }
            if (document.getElementById("frequency_sh" + ctrlid)) {
                frequency_sh = document.getElementById("frequency_sh" + ctrlid).value;
            } else {
                frequency_sh = "";
            }
            if (document.getElementById("no_of_rescue_sh" + ctrlid)) {
                no_of_rescue_sh = document.getElementById("no_of_rescue_sh" + ctrlid).value;
            } else {
                no_of_rescue_sh = "";
            }

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("divBoxRescue_child" + ctrlid).innerHTML = xmlhttp.responseText;
                    document.getElementById("divBoxRescue_child" + ctrlid).style.display = 'block';
                    document.getElementById("divBoxRescue_child_edit" + ctrlid).style.display = 'none';
                }
            }

            xmlhttp.open("POST", "editTables_dynamic.php?boxrescue_edit=yes&companyid=" + compid + "&recid=" + recid +
                "&ctrlid=" + ctrlid + "&selectboxes=" + selectboxes + "&shape=" + shape + "&top1=" + top1 +
                "&bottom1=" + bottom1 + "&vents=" + vents + "&wall=" + wall + "&previous_contents=" +
                previous_contents + "&box_condition=" + box_condition + "&frequency=" + frequency + "&no_of_rescue=" +
                no_of_rescue + "&length_lside=" + length_lside + "&width1=" + width1 + "&height1=" + height1 +
                "&wall_sh=" + wall_sh + "&box_condition_sh=" + box_condition_sh + "&req_another_box=" +
                req_another_box + "&frequency_sh=" + frequency_sh + "&no_of_rescue_sh=" + no_of_rescue_sh, true);
            xmlhttp.send();
        }

        //
        //Gaylord edit
        function updateGaylordBoxRescueEditnew(ctrlid, recid) {
            //
            var g_length = "",
                g_width = "",
                g_height = "";
            var gay_length = document.getElementsByName('gay_length[]');
            var gay_width = document.getElementsByName('gay_width[]');
            var gay_height = document.getElementsByName('gay_height[]');
            for (var i = 0, iLen = gay_length.length; i < iLen; i++) {
                g_length += gay_length[i].value + "-";
            }
            for (var i = 0, iLen = gay_width.length; i < iLen; i++) {
                g_width += gay_width[i].value + "-";
            }
            for (var i = 0, iLen = gay_height.length; i < iLen; i++) {
                g_height += gay_height[i].value + "-";
            }

            //

            var compid = document.getElementById("comp_id").value;
            var gaylord_box_id = document.getElementById("gaylord_box_id").value;
            var shape = document.getElementById("shape" + ctrlid).value;
            var top = document.getElementById("top" + ctrlid).value;
            var bottom = document.getElementById("bottom" + ctrlid).value;
            var vents = document.getElementById("vents" + ctrlid).value;
            var wall = document.getElementById("wall" + ctrlid).value;
            var previous_contents = document.getElementById("previous_contents" + ctrlid).value;

            var box_condition = document.getElementById("box_condition" + ctrlid).value;
            //alert(gaylord_box_id);
            //var req_another_box = document.getElementById("req_another_box"+ctrlid).value;

            var frequency = document.getElementById("frequency" + ctrlid).value;

            var no_of_rescue = document.getElementById("no_of_rescue" + ctrlid).value;
            var g_box_size_id = document.getElementById("g_box_size_id").value;

            //frequency = document.getElementById("frequency"+ctrlid).value;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    // alert(xmlhttp.responseText);
                    //document.getElementById("divBoxShipRescue_child"+ctrlid).innerHTML = xmlhttp.responseText;

                    document.getElementById("divBoxGaylordRescue_child" + ctrlid).style.display = 'block';
                    document.getElementById("divBoxGaylordRescue_child_edit" + ctrlid).style.display = 'none';
                    $("#divBoxGaylordRescue_child" + ctrlid).load(window.location.href + " #divBoxGaylordRescue_child" +
                        ctrlid);
                }
            }

            xmlhttp.open("POST", "editTables_dynamic_new.php?boxrescuegaylord_edit=yes&companyid=" + compid +
                "&gaylord_box_id=" + gaylord_box_id + "&ctrlid=" + ctrlid + "&shape=" + shape + "&top=" + top +
                "&bottom=" + bottom + "&vents=" + vents + "&wall=" + wall + "&previous_contents=" + previous_contents +
                "&box_condition=" + box_condition + "&frequency=" + frequency + "&no_of_rescue=" + no_of_rescue +
                "&g_box_size_id=" + g_box_size_id + "&g_length=" + g_length + "&g_width=" + g_width + "&g_height=" +
                g_height, true);
            xmlhttp.send();
        }
        //
        //pallet edit
        function updatePalletBoxRescueEditnew(ctrlid, recid) {
            //
            var p_length = "",
                p_width = "",
                p_height = "";
            var pal_length = document.getElementsByName('pal_length[]');
            var pal_width = document.getElementsByName('pal_width[]');

            for (var i = 0, iLen = pal_length.length; i < iLen; i++) {
                p_length += pal_length[i].value + "-";
            }
            for (var i = 0, iLen = pal_width.length; i < iLen; i++) {
                p_width += pal_width[i].value + "-";
            }


            //
            var compid = document.getElementById("comp_id").value;

            var pallet_box_id = document.getElementById("pallet_box_id").value;

            var wall = document.getElementById("wall" + ctrlid).value;

            var box_condition = document.getElementById("box_condition" + ctrlid).value;
            var req_another_box = document.getElementById("req_another_box" + ctrlid).value;
            var frequency = document.getElementById("frequency" + ctrlid).value;
            var quantity = document.getElementById("quantity" + ctrlid).value;
            var p_box_size_id = document.getElementById("p_box_size_id").value;

            //frequency = document.getElementById("frequency"+ctrlid).value;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    // alert(xmlhttp.responseText);
                    //document.getElementById("divBoxShipRescue_child"+ctrlid).innerHTML = xmlhttp.responseText;

                    document.getElementById("divBoxPalletRescue_child" + ctrlid).style.display = 'block';
                    document.getElementById("divBoxPalletRescue_child_edit" + ctrlid).style.display = 'none';
                    $("#divBoxPalletRescue_child" + ctrlid).load(window.location.href + " #divBoxPalletRescue_child" +
                        ctrlid);
                }
            }

            xmlhttp.open("POST", "editTables_dynamic_new.php?boxpalrescue_edit=yes&companyid=" + compid +
                "&pallet_box_id=" + pallet_box_id + "&ctrlid=" + ctrlid + "&wall=" + wall + "&box_condition=" +
                box_condition + "&req_another_box=" + req_another_box + "&frequency=" + frequency + "&quantity=" +
                quantity + "&p_box_size_id=" + p_box_size_id + "&p_length=" + p_length + "&p_width=" + p_width, true);
            xmlhttp.send();
        }
        //
        //supersack edit
        function updateSuperBoxRescueEditnew(ctrlid, recid) {
            //
            var sk_length = "",
                sk_width = "",
                sk_height = "";
            var sup_length = document.getElementsByName('sup_length[]');
            var sup_width = document.getElementsByName('sup_width[]');

            for (var i = 0, iLen = sup_length.length; i < iLen; i++) {
                sk_length += sup_length[i].value + "-";
            }
            for (var i = 0, iLen = sup_width.length; i < iLen; i++) {
                sk_width += sup_width[i].value + "-";
            }


            //
            var compid = document.getElementById("comp_id").value;

            var super_box_id = document.getElementById("super_box_id").value;

            var wall = document.getElementById("wall" + ctrlid).value;

            var box_condition = document.getElementById("box_condition" + ctrlid).value;
            var req_another_box = document.getElementById("req_another_box" + ctrlid).value;
            var frequency = document.getElementById("frequency" + ctrlid).value;
            var quantity = document.getElementById("quantity" + ctrlid).value;
            var sup_box_size_id = document.getElementById("sup_box_size_id").value;

            //frequency = document.getElementById("frequency"+ctrlid).value;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    // alert(xmlhttp.responseText);
                    //document.getElementById("divBoxShipRescue_child"+ctrlid).innerHTML = xmlhttp.responseText;

                    document.getElementById("divBoxSuperRescue_child" + ctrlid).style.display = 'block';
                    document.getElementById("divBoxSuperRescue_child_edit" + ctrlid).style.display = 'none';
                    $("#divBoxSuperRescue_child" + ctrlid).load(window.location.href + " #divBoxSuperRescue_child" +
                        ctrlid);
                }
            }

            xmlhttp.open("POST", "editTables_dynamic_new.php?boxsuprescue_edit=yes&companyid=" + compid + "&super_box_id=" +
                super_box_id + "&ctrlid=" + ctrlid + "&wall=" + wall + "&box_condition=" + box_condition +
                "&req_another_box=" + req_another_box + "&frequency=" + frequency + "&quantity=" + quantity +
                "&sup_box_size_id=" + sup_box_size_id + "&sk_length=" + sk_length + "&sk_width=" + sk_width, true);
            xmlhttp.send();
        }
        //
        //
        function updateBoxReqAddnew() {
            compid = document.getElementById("editcompanyid").value;

            /*if (document.getElementById("length")){ 		length = document.getElementById("length").value; } else { length= "";}
            if (document.getElementById("length_max")){ 		length_max = document.getElementById("length_max").value; } else { length_max= "";}
            if (document.getElementById("width")){ 		width = document.getElementById("width").value; } else { width= "";}
            if (document.getElementById("width_max")){ 		width_max = document.getElementById("width_max").value; } else { width_max= "";}
            if (document.getElementById("height")){ 		height = document.getElementById("height").value; } else { height= "";}
            if (document.getElementById("height_max")){ 		height_max = document.getElementById("height_max").value; } else { height_max= "";}*/
            if (document.getElementById("wall")) {
                wall = document.getElementById("wall").value;
            } else {
                wall = "";
            }
            if (document.getElementById("quantityshipbox")) {
                quantity = document.getElementById("quantityshipbox").value;
            } else {
                quantity = "";
            }
            if (document.getElementById("frequency_boxreq")) {
                frequency = document.getElementById("frequency_boxreq").value;
            } else {
                frequency = "";
            }
            if (document.getElementById("delivery_date_boxreq")) {
                delivery_date = document.getElementById("delivery_date_boxreq").value;
            } else {
                delivery_date = "";
            }
            //
            if (document.getElementById("length")) {
                length = document.getElementById("length").value;
            } else {
                length = "";
            }
            if (document.getElementById("width")) {
                width = document.getElementById("width").value;
            } else {
                width = "";
            }
            if (document.getElementById("heigth")) {
                heigth = document.getElementById("heigth").value;
            } else {
                heigth = "";
            }

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("divBoxReq").innerHTML = xmlhttp.responseText;
                    document.getElementById("divBoxReq").style.display = 'block';
                    document.getElementById("divBoxReqNew").style.display = 'none';
                }
            }

            xmlhttp.open("POST", "editTables_dynamic.php?boxreq_add=yes&companyid=" + compid + "&length=" + length +
                "&length_max=" + length_max + "&width=" + width + "&width_max=" + width_max + "&height=" + height +
                "&height_max=" + height_max + "&wall=" + wall + "&quantity=" + quantity + "&frequency=" + frequency +
                "&delivery_date=" + delivery_date + "&length=" + length + "&width=" + width + "&height=" + height, true);
            xmlhttp.send();
        }

        function shippingbox_addnew() {
            document.getElementById('length').value = '';
            //document.getElementById('length_max').value=''; 
            document.getElementById('width').value = '';
            //document.getElementById('width_max').value=''; 
            document.getElementById('height').value = '';
            //document.getElementById('height_max').value=''; 
            document.getElementById('wall').value = '';
            document.getElementById('quantityshipbox').value = '';
            document.getElementById('frequency_boxreq').value = '';
            document.getElementById('delivery_date_boxreq').value = '';
            document.getElementById('quantityshipbox').value = '';


            document.getElementById('divBoxReqNew').style.display = 'block';
            document.getElementById('divBoxReq').style.display = 'none';
        }


        function updateBoxReqEditnew(ctrlid, recid) {
            compid = document.getElementById("editcompanyid_new").value;

            var length = document.getElementById("length" + ctrlid).value;
            var width = document.getElementById("width" + ctrlid).value;
            var height = document.getElementById("height" + ctrlid).value;
            var box_size_id = document.getElementById("box_size_id" + ctrlid).value;
            /*if (document.getElementById("length"+ctrlid)){ 		length1 = document.getElementById("length"+ctrlid).value; } else { length1= "";}
            if (document.getElementById("length_max"+ctrlid)){ 		length_max = document.getElementById("length_max"+ctrlid).value; } else { length_max= "";}
            if (document.getElementById("width"+ctrlid)){ 		width1 = document.getElementById("width"+ctrlid).value; } else { width1= "";}
            if (document.getElementById("width_max"+ctrlid)){ 		width_max = document.getElementById("width_max"+ctrlid).value; } else { width_max= "";}
            if (document.getElementById("height"+ctrlid)){ 		height1 = document.getElementById("height"+ctrlid).value; } else { height1= "";}
            if (document.getElementById("height_max"+ctrlid)){ 		height_max = document.getElementById("height_max"+ctrlid).value; } else { height_max= "";}*/
            if (document.getElementById("wall" + ctrlid)) {
                wall = document.getElementById("wall" + ctrlid).value;
            } else {
                wall = "";
            }
            if (document.getElementById("quantity" + ctrlid)) {
                quantity = document.getElementById("quantity" + ctrlid).value;
            } else {
                quantity = "";
            }
            if (document.getElementById("frequency" + ctrlid)) {
                frequency = document.getElementById("frequency" + ctrlid).value;
            } else {
                frequency = "";
            }
            if (document.getElementById("delivery_date" + ctrlid)) {
                delivery_date = document.getElementById("delivery_date" + ctrlid).value;
            } else {
                delivery_date = "";
            }

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("divBoxReq_child" + ctrlid).innerHTML = xmlhttp.responseText;
                    document.getElementById("divBoxReq_child" + ctrlid).style.display = 'block';
                    document.getElementById("divBoxReq_child_edit" + ctrlid).style.display = 'none';
                }
            }

            //xmlhttp.open("POST","editTables_dynamic.php?boxreq_edit=yes&companyid="+compid+"&recid="+recid+"&ctrlid="+ctrlid+"&length="+length1+"&length_max="+length_max+"&width="+width1+"&width_max="+width_max+"&height="+height1+"&height_max="+height_max+"&wall="+wall+"&quantity="+quantity+"&frequency="+frequency+"&delivery_date="+delivery_date,true);	
            xmlhttp.open("POST", "editTables_dynamic.php?boxreq_edit1=yes&companyid=" + compid + "&recid=" + recid +
                "&ctrlid=" + ctrlid + "&length=" + length + "&width=" + width + "&height=" + height + "&wall=" + wall +
                "&quantity=" + quantity + "&box_size_id=" + box_size_id + "&frequency=" + frequency +
                "&delivery_date=" + delivery_date, true);
            xmlhttp.send();
        }
        //pallet request
        function updateBoxReqEditPallet(ctrlid, recid) {
            var compid = document.getElementById("editcompanyid_new").value;

            var length = document.getElementById("plength" + ctrlid).value;
            var width = document.getElementById("pwidth" + ctrlid).value;

            var pallet_boxid = document.getElementById("pallet_boxid" + ctrlid).value;
            var pbox_size_id = document.getElementById("pbox_size_id" + ctrlid).value;

            if (document.getElementById("quantity" + ctrlid)) {
                quantity = document.getElementById("quantity" + ctrlid).value;
            } else {
                quantity = "";
            }


            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    // alert(xmlhttp.responseText);
                    //document.getElementById("divBoxReqP_child"+ctrlid).innerHTML = xmlhttp.responseText;
                    document.getElementById("divBoxReqP_child" + ctrlid).style.display = 'block';
                    document.getElementById("divBoxReqP_child_edit" + ctrlid).style.display = 'none';
                    $("#divBoxReqP_child" + ctrlid).load(window.location.href + " #divBoxReqP_child" + ctrlid);
                }
            }

            xmlhttp.open("POST", "editTables_dynamic_new.php?pboxreq_edit1=yes&companyid=" + compid + "&recid=" + recid +
                "&ctrlid=" + ctrlid + "&length=" + length + "&width=" + width + "&quantity=" + quantity +
                "&pbox_size_id=" + pbox_size_id + "&pallet_boxid=" + pallet_boxid, true);
            xmlhttp.send();
        }
        //
        //supersack request
        function updateBoxReqEditSupersk(ctrlid, recid) {
            var compid = document.getElementById("editcompanyid_new").value;

            var length = document.getElementById("slength" + ctrlid).value;
            var width = document.getElementById("swidth" + ctrlid).value;

            var sup_boxid = document.getElementById("sup_boxid" + ctrlid).value;
            var sbox_size_id;

            if (document.getElementById("sbox_size_id" + ctrlid)) {
                sbox_size_id = document.getElementById("sbox_size_id" + ctrlid).value;
            } else {
                sbox_size_id = "";
            }

            if (document.getElementById("quantity" + ctrlid)) {
                quantity = document.getElementById("quantity" + ctrlid).value;
            } else {
                quantity = "";
            }
            //alert(ctrlid);

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    // alert(xmlhttp.responseText);
                    //document.getElementById("divBoxReqP_child"+ctrlid).innerHTML = xmlhttp.responseText;
                    document.getElementById("divBoxReqSup_child" + ctrlid).style.display = 'block';
                    document.getElementById("divBoxReqSup_child_edit" + ctrlid).style.display = 'none';
                    $("#divBoxReqSup_child" + ctrlid).load(window.location.href + " #divBoxReqSup_child" + ctrlid);
                }
            }

            xmlhttp.open("POST", "editTables_dynamic_new.php?supboxreq_edit1=yes&companyid=" + compid + "&recid=" + recid +
                "&ctrlid=" + ctrlid + "&length=" + length + "&width=" + width + "&quantity=" + quantity +
                "&sbox_size_id=" + sbox_size_id + "&sup_boxid=" + sup_boxid, true);
            xmlhttp.send();
        }
        //

        function reminder_popup_set5(compid, rec_id, warehouse_id, rec_type) {
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    selectobject = document.getElementById("reminder_popup_set5_btn");
                    n_left = f_getPosition(selectobject, 'Left');
                    n_top = f_getPosition(selectobject, 'Top');

                    document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                    document.getElementById('light_reminder').style.display = 'block';

                    document.getElementById('light_reminder').style.left = (n_left - 100) + 'px';
                    document.getElementById('light_reminder').style.top = n_top - 40 + 'px';
                    document.getElementById('light_reminder').style.width = 1100 + 'px';
                }
            }

            xmlhttp.open("POST", "sendemail_b2bsurvey.php?compid=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" +
                warehouse_id + "&rec_type=" + rec_type, true);
            xmlhttp.send();
        }

        function show_reply(tmpcnt) {
            //alert(document.getElementById("reminder_details" + tmpcnt));
            var selectobject = document.getElementById("reminder_details" + tmpcnt);

            //selectobject.innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />"; 				

            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');

            document.getElementById("light_details").innerHTML = document.getElementById("remider_rep_popup" + tmpcnt)
                .innerHTML;
            document.getElementById('light_details').style.display = 'block';
            //document.getElementById('fade').style.display='block';

            document.getElementById('light_details').style.left = n_left + 'px';
            document.getElementById('light_details').style.top = n_top + 20 + 'px';
        }

        function reminder_popup(unq_quote_id, quote_id) {
            var selectedText, selectobject, rec_type, skillsSelect, n_left, n_top;
            skillsSelect = document.getElementById("quote_status" + quote_id);
            selectedText = skillsSelect.options[skillsSelect.selectedIndex].value;
            selectobject = document.getElementById("B1" + "_" + quote_id);
            id = document.getElementById("companyID").value;
            rec_type = document.getElementById("rec_type").value;

            if (selectedText == 10) {

                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        n_left = f_getPosition(selectobject, 'Left');
                        n_top = f_getPosition(selectobject, 'Top');
                        document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                        document.getElementById('light_reminder').style.display = 'block';
                        //document.getElementById('fade').style.display='block';

                        document.getElementById('light_reminder').style.left = n_left + 'px';
                        document.getElementById('light_reminder').style.top = n_top + 20 + 'px';
                    }
                }

                xmlhttp.open("POST", "fckeditor_reminder_email.php?unq_quote_id=" + unq_quote_id + "&quote_id=" + quote_id +
                    "&quote_status=" + selectedText + "&companyid=" + id + "&B1=B1" + "&rec_type=" + rec_type, true);
                xmlhttp.send();
            } else {
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        alert("Data Updated");
                    }
                }

                xmlhttp.open("POST", "updateQuoteStatus_mrg.php?quote_id=" + quote_id + "&quote_status=" + selectedText +
                    "&B1=B1", true);
                xmlhttp.send();

            }
        }

        function reminder_popup_newtest(unq_quote_id, quote_id) {
            var selectedText, selectobject, rec_type, skillsSelect, n_left, n_top;
            skillsSelect = document.getElementById("quote_status" + quote_id);
            selectedText = skillsSelect.options[skillsSelect.selectedIndex].value;

            selectobject = document.getElementById("B1" + "_" + quote_id);
            id = document.getElementById("companyID").value;
            rec_type = document.getElementById("rec_type").value;

            if (selectedText == 10) {
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        n_left = f_getPosition(selectobject, 'Left');
                        n_top = f_getPosition(selectobject, 'Top');
                        document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                        document.getElementById('light_reminder').style.display = 'block';
                        //document.getElementById('fade').style.display='block';

                        document.getElementById('light_reminder').style.left = n_left + 'px';
                        document.getElementById('light_reminder').style.top = n_top + 20 + 'px';
                    }
                }

                xmlhttp.open("POST", "fckeditor_reminder_email.php?unq_quote_id=" + unq_quote_id + "&quote_id=" + quote_id +
                    "&quote_status=" + selectedText + "&companyid=" + id + "&B1=B1" + "&rec_type=" + rec_type, true);
                xmlhttp.send();
            }
            if (selectedText == 8) {

                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        n_left = f_getPosition(selectobject, 'Left');
                        n_top = f_getPosition(selectobject, 'Top');
                        document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                        document.getElementById('light_reminder').style.display = 'block';
                        document.getElementById('light_reminder').style.width = '450';
                        document.getElementById('light_reminder').style.height = '800';
                        //document.getElementById('fade').style.display='block';

                        document.getElementById('light_reminder').style.left = n_left + 'px';
                        document.getElementById('light_reminder').style.top = n_top + 20 + 'px';
                    }
                }

                xmlhttp.open("POST", "createpo_from_quote.php?unq_quote_id=" + unq_quote_id + "&quote_id_org=" + quote_id +
                    "&quote_status=" + selectedText + "&companyid=" + id + "&B1=B1" + "&rec_type=" + rec_type, true);
                xmlhttp.send();
            } else {
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        alert("Data Updated");
                    }
                }

                xmlhttp.open("POST", "updateQuoteStatus_mrg.php?quote_id=" + quote_id + "&quote_status=" + selectedText +
                    "&B1=B1", true);
                xmlhttp.send();

            }
        }

        function onsubmitform_quotepo() {
            var msgstr = "";

            var form, list, index, item, checkedCount;

            checkedCount = 0;
            quotesel = "no";
            form = document.getElementById('addpo_fromquote');
            list = form.getElementsByTagName('input');
            for (index = 0; index < list.length; ++index) {
                item = list[index];
                if (item.getAttribute('type') === "checkbox" &&
                    item.checked &&
                    item.name === "txtquotesel[]") {
                    ++checkedCount;
                }
                if (item.getAttribute('type') === "checkbox" &&
                    item.name === "txtquotesel[]") {
                    quotesel = "yes";
                }
            }

            if (document.getElementById("file").value == "") {
                msgstr = msgstr + "Purchase Order\r\n";
            }
            if (document.getElementById("txtponumber").value == "") {
                msgstr = msgstr + "PO Number\r\n";
            }
            if (document.getElementById("cmbpoterms").value == "") {
                msgstr = msgstr + "PO Terms\r\n";
            }
            if (document.getElementById("txtpoorderamount").value == "") {
                msgstr = msgstr + "PO Amount\r\n";
            }
            if (checkedCount == 0 && quotesel == "yes") {
                msgstr = msgstr + "Please select any one quote item\r\n";
            }

            if (msgstr != "") {
                alert("Following required fields needs to be filled out:\r\n" + msgstr);
                return false;
            } else {
                return true;
            }
        }

        function showccfields() {
            if (document.getElementById('po_payment_method').value == "Credit Card") {
                document.getElementById('ccfield_1').style.display = 'block';
                document.getElementById('ccfield_2').style.display = 'block';
                document.getElementById('ccfield_3').style.display = 'block';
                document.getElementById('ccfield_4').style.display = 'block';
            } else {
                document.getElementById('ccfield_1').style.display = 'none';
                document.getElementById('ccfield_2').style.display = 'none';
                document.getElementById('ccfield_3').style.display = 'none';
                document.getElementById('ccfield_4').style.display = 'none';
            }
        }

        function quote_request_send_email(quote_id, compid, quote_rq_id) {
            var selectedText, selectobject, rec_type, skillsSelect, n_left, n_top;
            selectobject = document.getElementById("sendemailtrfacc");
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');
            //alert(quote_id+quote_rq_id);
            document.getElementById('light').style.left = 300 + 'px';
            document.getElementById('light').style.top = n_top + 30 + 'px';
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                    document.getElementById("light").innerHTML =
                        "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
                        xmlhttp.responseText;

                    document.getElementById('light').style.display = 'block';

                }
            }
            xmlhttp.open("POST", "quote_request_to_customer_email.php?company_id=" + compid + "&quote_item_id=" + quote_id +
                "&quote_rq_item=" + quote_rq_id, true);
            xmlhttp.send();
        }
        //deny email
        function quote_deny_send_email(quote_id, compid) {
            var selectedText, selectobject, skillsSelect, n_left, n_top;
            selectobject = document.getElementById("button_status" + quote_id);
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');
            //alert(quote_id+quote_rq_id);
            document.getElementById('light').style.left = 300 + 'px';
            document.getElementById('light').style.top = n_top + 30 + 'px';
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                    document.getElementById("light").innerHTML =
                        "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
                        xmlhttp.responseText;

                    document.getElementById('light').style.display = 'block';
                    document.getElementById('light').style.width = 730 + 'px';
                    document.getElementById('light').style.height = 550 + 'px';
                    document.getElementById('light').style.left = n_left + 10 + 'px';
                    document.getElementById('light').style.top = n_top + 10 + 'px';


                }
            }
            xmlhttp.open("POST", "quote_deny_send_email.php?company_id=" + compid + "&quote_id=" + quote_id, true);
            xmlhttp.send();
        }
        //
        function quote_to_customer(unq_quote_id, quote_id) {
            var selectedText, selectobject, rec_type, skillsSelect, n_left, n_top;
            skillsSelect = document.getElementById("quote_status" + quote_id);
            selectedText = skillsSelect.options[skillsSelect.selectedIndex].value;
            selectobject = document.getElementById("B1" + "_" + quote_id);
            id = document.getElementById("companyID").value;
            rec_type = document.getElementById("rec_type").value;

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    n_left = f_getPosition(selectobject, 'Left');
                    n_top = f_getPosition(selectobject, 'Top');
                    document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                    document.getElementById('light_reminder').style.display = 'block';

                    document.getElementById('light_reminder').style.left = n_left + 'px';
                    document.getElementById('light_reminder').style.top = n_top + 20 + 'px';
                }
            }

            xmlhttp.open("POST", "fckeditor_quote_to_customer_email.php?unq_quote_id=" + unq_quote_id + "&quote_id=" +
                quote_id + "&quote_status=" + selectedText + "&companyid=" + id + "&B1=B1" + "&rec_type=" + rec_type,
                true);
            xmlhttp.send();
        }



        function quote_to_customer(unq_quote_id, quote_id) {
            var selectedText, selectobject, rec_type, skillsSelect, n_left, n_top;
            skillsSelect = document.getElementById("quote_status" + quote_id);
            selectedText = skillsSelect.options[skillsSelect.selectedIndex].value;
            selectobject = document.getElementById("B1" + "_" + quote_id);
            id = document.getElementById("companyID").value;
            rec_type = document.getElementById("rec_type").value;

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    n_left = f_getPosition(selectobject, 'Left');
                    n_top = f_getPosition(selectobject, 'Top');
                    document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                    document.getElementById('light_reminder').style.display = 'block';

                    document.getElementById('light_reminder').style.left = n_left + 'px';
                    document.getElementById('light_reminder').style.top = n_top + 20 + 'px';
                }
            }

            xmlhttp.open("POST", "fckeditor_quote_to_customer_email.php?unq_quote_id=" + unq_quote_id + "&quote_id=" +
                quote_id + "&quote_status=" + selectedText + "&companyid=" + id + "&B1=B1" + "&rec_type=" + rec_type,
                true);
            xmlhttp.send();
        }

        function btnsendemlclick_eml_p4() {
            var tmp_element1, tmp_element2, tmp_element3, tmp_element4, tmp_element5;

            tmp_element1 = document.getElementById("txtemailto").value;

            tmp_element2 = document.getElementById("email_reminder_sch_p4");

            tmp_element3 = document.getElementById("txtemailcc").value;

            tmp_element4 = document.getElementById("txtemailsubject").value;

            tmp_element5 = document.getElementById("hidden_reply_eml");

            if (tmp_element1.value == "") {
                alert("Please enter the To Email address.");
                return false;
            }

            if (tmp_element4.value == "") {
                alert("Please enter the Email Subject.");
                return false;
            }

            if (tmp_element3.value == "") {
                alert("Please enter the Cc Email address.");
                return false;
            }


            var inst = FCKeditorAPI.GetInstance("txtemailbody");
            var emailtext = inst.GetHTML();

            tmp_element5.value = emailtext;
            //alert(tmp_element5.value);
            document.getElementById("hidden_sendemail").value = "inemailmode";
            tmp_element2.submit();

        }

        function reminder_popup_set5(compid, rec_id, warehouse_id, rec_type) {
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    selectobject = document.getElementById("reminder_popup_set5_btn");
                    n_left = f_getPosition(selectobject, 'Left');
                    n_top = f_getPosition(selectobject, 'Top');

                    document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                    document.getElementById('light_reminder').style.display = 'block';

                    document.getElementById('light_reminder').style.left = (n_left - 100) + 'px';
                    document.getElementById('light_reminder').style.top = n_top - 40 + 'px';
                    document.getElementById('light_reminder').style.width = 1100 + 'px';
                }
            }

            xmlhttp.open("POST", "sendemail_b2bsurvey.php?compid=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" +
                warehouse_id + "&rec_type=" + rec_type, true);
            xmlhttp.send();
        }

        function settheflg() {
            if (document.getElementById("item").value == "Delivery") {
                document.getElementById("item_delivery_flg").value = "yes";
                document.getElementById("tr_shipping_quote1").style.display = "none";
            } else {
                document.getElementById("item_delivery_flg").value = "no";
                document.getElementById("tr_shipping_quote1").style.display = "inline";
            }
        }

        function display_gaylords_child(id, flg, n_left, n_top) {
            var selectobject = document.getElementById("lightbox");
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');

            var sstr = "";
            sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
            sstr = sstr + "<tr align='center'>";
            sstr = sstr + "<td bgcolor='#FF9900'>";
            sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>GAYLORD MATCHING TOOL</font>";
            sstr = sstr + "&nbsp;&nbsp;";
            sstr = sstr +
                "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
            sstr = sstr + "<br>";
            if (flg == 0) {
                sstr = sstr +
                    "Below list only display 'Available Now', 'Available & Urgent', 'Available >= 1 TL', 'Available < 1 TL', 'Check Loops' boxes &nbsp;&nbsp;";
                sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_gaylords_child(" + id +
                    ", 1," + n_left + "," + n_top + ")'>Display Only Available Boxes</a>";
            } else {
                sstr = sstr + "Below list display all the boxes &nbsp;&nbsp;";
                sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_gaylords_child(" + id +
                    ", 0," + n_left + "," + n_top + ")'>Display All Boxes</a>";
            }
            sstr = sstr + "</td>";
            sstr = sstr + "</tr>";
            sstr = sstr + "</table>";

            var selectobject = document.getElementById("lightbox");
            //var n_left = f_getPosition(selectobject, 'Left');
            //var n_top  = f_getPosition(selectobject, 'Top');
            document.getElementById('light_gaylord_new').style.display = 'block';
            document.getElementById('light_gaylord_new').style.left = n_left + 20 + 'px';
            document.getElementById('light_gaylord_new').style.top = n_top + 20 + 'px';

            document.getElementById("light_gaylord_new").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("light_gaylord_new").innerHTML = sstr + xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "gaylords_mrg.php?ID=" + id + "&display-allrec=" + flg, true);
            xmlhttp.send();
        }

        function display_gaylords_autoload(id, flg) {
            var sstr = "";
            sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
            sstr = sstr + "<tr align='center'>";
            sstr = sstr + "<td bgcolor='#FF9900'>";
            sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>GAYLORD MATCHING TOOL</font>";
            sstr = sstr + "&nbsp;&nbsp;";
            sstr = sstr +
                "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
            sstr = sstr + "<br>";
            if (flg == 0) {
                sstr = sstr +
                    "Below list display 'Available Now', 'Available & Urgent', 'Available >= 1 TL', 'Available < 1 TL', 'Check Loops' boxes &nbsp;&nbsp;";
                sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_gaylords_child(" + id +
                    ", 1 ,0,0)'>Display Only Available Boxes</a>";
            } else {
                sstr = sstr + "Below list display all the boxes &nbsp;&nbsp;";
                sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_gaylords_child(" + id +
                    ", 0,0,0)'>Display All Boxes</a>";
            }
            sstr = sstr + "</td>";
            sstr = sstr + "</tr>";
            sstr = sstr + "</table>";

            if (window.XMLHttpRequest) {
                xmlhttpauto = new XMLHttpRequest();
            } else {
                xmlhttpauto = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttpauto.onreadystatechange = function() {
                if (xmlhttpauto.readyState == 4 && xmlhttpauto.status == 200) {
                    document.getElementById("light_gaylord_new").innerHTML = sstr + xmlhttpauto.responseText;
                    document.getElementById("gayloardtoolautoload").innerHTML = "Data loaded.";
                }
            }
            xmlhttpauto.open("GET", "gaylords_mrg.php?ID=" + id + "&display-allrec=" + flg, true);
            xmlhttpauto.send();
        }

        function display_gaylords(id, flg) {
            if (document.getElementById("light_gaylord_new").innerHTML == "") {
                var selectobject = document.getElementById("lightbox");
                var n_left = f_getPosition(selectobject, 'Left');
                var n_top = f_getPosition(selectobject, 'Top');
                document.getElementById('light_gaylord_new').style.display = 'block';
                document.getElementById('light_gaylord_new').style.left = n_left + 20 + 'px';
                document.getElementById('light_gaylord_new').style.top = n_top + 20 + 'px';

                document.getElementById("light_gaylord_new").innerHTML =
                    "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

                var sstr = "";
                sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
                sstr = sstr + "<tr align='center'>";
                sstr = sstr + "<td bgcolor='#FF9900'>";
                sstr = sstr +
                    "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>GAYLORD MATCHING TOOL</font>";
                sstr = sstr + "&nbsp;&nbsp;";
                sstr = sstr +
                    "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
                sstr = sstr + "<br>";
                if (flg == 0) {
                    sstr = sstr +
                        "Below list display 'Available Now', 'Available & Urgent', 'Available >= 1 TL', 'Available < 1 TL', 'Check Loops' boxes &nbsp;&nbsp;";
                    sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_gaylords_child(" +
                        id + ", 1 ," + n_left + "," + n_top + ")'>Display Only Available Boxes</a>";
                } else {
                    sstr = sstr + "Below list display all the boxes &nbsp;&nbsp;";
                    sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_gaylords_child(" +
                        id + ", 0," + n_left + "," + n_top + ")'>Display All Boxes</a>";
                }
                sstr = sstr + "</td>";
                sstr = sstr + "</tr>";
                sstr = sstr + "</table>";

                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        document.getElementById("light_gaylord_new").innerHTML = sstr + xmlhttp.responseText;
                    }
                }
                xmlhttp.open("GET", "gaylords_mrg.php?ID=" + id + "&display-allrec=" + flg, true);
                xmlhttp.send();
            } else {
                var selectobject = document.getElementById("lightbox");
                var n_left = f_getPosition(selectobject, 'Left');
                var n_top = f_getPosition(selectobject, 'Top');
                document.getElementById('light_gaylord_new').style.display = 'block';
                document.getElementById('light_gaylord_new').style.left = n_left + 20 + 'px';
                document.getElementById('light_gaylord_new').style.top = n_top + 20 + 'px';
            }
        }

        //New gaylord matching tool
        function display_new_gaylords_autoload(id, flg) {
            var sstr = "";
            sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
            sstr = sstr + "<tr align='center'>";
            sstr = sstr + "<td bgcolor='#FF9900'>";
            sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>GAYLORD MATCHING TOOL</font>";
            sstr = sstr + "&nbsp;&nbsp;";
            sstr = sstr +
                "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
            sstr = sstr + "<br>";
            sstr = sstr + "Matching Criteria&nbsp;&nbsp;";
            sstr = sstr + "<br>";
            //if (flg == 0) {

            sstr = sstr + "<select name='sort_g_tool' id='sort_g_tool' onChange='display_new_gaylords_child(" + id + "," +
                this.value + ",0,0)'><option value='1'";

            if (flg == 1) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">Matching Criteria</option><option value='2'";
            if (flg == 2) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">Matching Criteria & Available NOW</option><option value='5'";
            if (flg == 5) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">All Boxes (No filter)</option></select>";

            sstr = sstr + "</td>";
            sstr = sstr + "</tr>";
            sstr = sstr + "</table>";

            if (window.XMLHttpRequest) {
                xmlhttpauto_new = new XMLHttpRequest();
            } else {
                xmlhttpauto_new = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttpauto_new.onreadystatechange = function() {
                if (xmlhttpauto_new.readyState == 4 && xmlhttpauto_new.status == 200) {
                    document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttpauto.responseText;
                    document.getElementById("gayloardtoolautoload1").innerHTML = "Data loaded.";
                }
            }


            xmlhttpauto_new.open("GET", "sales_gaylords.php?ID=" + id + "&display-allrec=" + flg, true);
            xmlhttpauto_new.send();
        }
        //

        function display_new_gaylords(id, boxid, flg) {
            //if (document.getElementById("light_gaylord_new1").innerHTML == "") 
            //{

            var selectobject = document.getElementById("lightbox" + boxid);
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');
            document.getElementById('light_gaylord_new1').style.display = 'block';
            document.getElementById('light_gaylord_new1').style.left = n_left + 20 + 'px';
            document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';
            document.getElementById('light_gaylord_new1').style.height = 580 + 'px';

            document.getElementById("light_gaylord_new1").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

            var sstr = "";
            sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
            sstr = sstr + "<tr align='center'>";
            sstr = sstr + "<td bgcolor='#FF9900'>";
            sstr = sstr +
                "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>NEW GAYLORD MATCHING TOOL</font>";
            sstr = sstr + "&nbsp;&nbsp;";
            sstr = sstr +
                "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
            sstr = sstr + "Matching Criteria&nbsp;&nbsp;";
            sstr = sstr + "<br>";
            //if (flg == 0) {

            sstr = sstr + "<select name='sort_g_tool' id='sort_g_tool' onChange='display_new_gaylords_child(" + id + "," +
                this.value + "," + boxid + "," + n_left + "," + n_top + ")'><option value='1'";

            if (flg == 1) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">Matching Criteria</option><option value='2'";
            if (flg == 2) {
                sstr = sstr + "selected";
            }
            sstr = sstr + ">Matching Criteria & Available NOW</option><option value='5'";
            if (flg == 5) {
                sstr = sstr + "selected";
            }
            sstr = sstr + ">All Boxes (No filter)</option></select>";

            sstr = sstr + "</td>";
            sstr = sstr + "</tr>";
            sstr = sstr + "</table>";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "sales_gaylords.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flg, true);
            xmlhttp.send();
            //}
            /*else 
            {
            	var selectobject = document.getElementById("lightbox"); 
            	var n_left = f_getPosition(selectobject, 'Left');
            	var n_top  = f_getPosition(selectobject, 'Top');
            	document.getElementById('light_gaylord_new1').style.display='block';
            	document.getElementById('light_gaylord_new1').style.left = n_left + 20 + 'px';
            	document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';
            }*/
        }

        function display_new_gaylords_child(id, flg, boxid, n_left, n_top) {
            var flgs = document.getElementById("sort_g_tool").value;
            //alert(flgs);
            //
            var selectobject = document.getElementById("lightbox" + boxid);
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');

            var sstr = "";
            sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
            sstr = sstr + "<tr align='center'>";
            sstr = sstr + "<td bgcolor='#FF9900'>";
            sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>GAYLORD MATCHING TOOL</font>";
            sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
            sstr = sstr +
                "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
            //
            if (flgs == 1) {
                sstr = sstr + "Matching Criteria&nbsp;&nbsp;";
            }
            if (flgs == 2) {
                sstr = sstr +
                    "Below list only display 'Available Now', 'Available & Urgent', 'Available >= 1 TL', 'Available <= 1 TL' and UCB Owned inventory &nbsp;&nbsp;";
            }

            if (flgs == 5) {
                sstr = sstr + "Below list display all boxes &nbsp;&nbsp;";
            }
            //
            sstr = sstr + "<br>";
            //if (flg == 0) {

            sstr = sstr + "<select name='sort_g_tool' id='sort_g_tool' onChange='display_new_gaylords_child(" + id + "," +
                this.value + "," + boxid + "," + n_left + "," + n_top + ")'><option value='1'";

            if (flgs == 1) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">Matching Criteria</option><option value='2'";
            if (flgs == 2) {
                sstr = sstr + "selected";
            }
            sstr = sstr + ">Matching Criteria & Available NOW</option><option value='5'";
            if (flgs == 5) {
                sstr = sstr + "selected";
            }
            sstr = sstr + ">All Boxes (No filter)</option></select>";

            sstr = sstr + "</td>";
            sstr = sstr + "</tr>";
            sstr = sstr + "</table>";

            if (flgs == 2 || flgs == 5) {
                sstr = sstr + "<table width='100%' border='0' cellspacing='1' cellpadding='1' ><tr>";
                sstr = sstr + "<td colspan='14'><font face='Arial, Helvetica, sans-serif' size='1'>";
                sstr = sstr + "<a href='#' onmouseover=" + String.fromCharCode(34) +
                    "Tip('Program search for the neareast Box location based on the Ship To Zipcode. This is done based on the Zip code Latitude and longitude values stored in the database. ";

                if (flgs == 2) {
                    sstr = sstr + "And shown boxes based on Matching Criteria & Available NOW.')";
                }
                if (flgs == 5) {
                    sstr = sstr + "And shown All Boxes (No filter).')";
                }
                sstr = sstr + String.fromCharCode(34) + " onmouseout='UnTip()'>How it Works</a></font></table>";
            }

            var selectobject = document.getElementById("lightbox" + boxid);
            //var n_left = f_getPosition(selectobject, 'Left');
            //var n_top  = f_getPosition(selectobject, 'Top');
            document.getElementById('light_gaylord_new1').style.display = 'block';
            document.getElementById('light_gaylord_new1').style.left = n_left + 20 + 'px';
            document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';

            document.getElementById("light_gaylord_new1").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "sales_gaylords.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flgs, true);
            xmlhttp.send();
        }
        //
        function display_new_gaylords_all(id, boxid, flg) {
            //if (document.getElementById("light_gaylord_new1").innerHTML == "") 
            //{

            var selectobject = document.getElementById("lightbox0");
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');
            document.getElementById('light_gaylord_new1').style.display = 'block';
            document.getElementById('light_gaylord_new1').style.left = n_left + 20 + 'px';
            document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';
            document.getElementById('light_gaylord_new1').style.height = 580 + 'px';

            document.getElementById("light_gaylord_new1").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

            var sstr = "";
            sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
            sstr = sstr + "<tr align='center'>";
            sstr = sstr + "<td bgcolor='#FF9900'>";
            sstr = sstr +
                "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>NEW GAYLORD MATCHING TOOL</font>";
            sstr = sstr + "&nbsp;&nbsp;";
            sstr = sstr +
                "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
            sstr = sstr + "Matching Criteria&nbsp;&nbsp;";
            sstr = sstr + "<br>";
            //if (flg == 0) {

            sstr = sstr + "<select name='sort_g_tool' id='sort_g_tool' onChange='display_new_gaylords_child(" + id + "," +
                this.value + "," + boxid + "," + n_left + "," + n_top + ")'><option value='1'";

            if (flg == 1) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">Matching Criteria</option><option value='2'";
            if (flg == 2) {
                sstr = sstr + "selected";
            }
            sstr = sstr + ">Matching Criteria & Available NOW</option><option value='5'";
            if (flg == 5) {
                sstr = sstr + "selected";
            }
            sstr = sstr + ">All Boxes (No filter)</option></select>";

            sstr = sstr + "</td>";
            sstr = sstr + "</tr>";
            sstr = sstr + "</table>";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "sales_gaylords.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flg, true);
            xmlhttp.send();
        }

        //Quote request matching tool for Gaylord
        function display_request_gaylords(id, boxid, flg) {
            //if (document.getElementById("light_gaylord_new1").innerHTML == "") 
            //{

            var selectobject = document.getElementById("lightbox");
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');
            document.getElementById('light_gaylord_new1').style.display = 'block';
            document.getElementById('light_gaylord_new1').style.left = n_left + 20 + 'px';
            document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';
            document.getElementById('light_gaylord_new1').style.height = 580 + 'px';

            document.getElementById("light_gaylord_new1").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

            var sstr = "";
            sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
            sstr = sstr + "<tr align='center'>";
            sstr = sstr + "<td bgcolor='#FF9900'>";
            sstr = sstr +
                "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>NEW GAYLORD MATCHING TOOL</font>";
            sstr = sstr + "&nbsp;&nbsp;";
            sstr = sstr +
                "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
            sstr = sstr + "Matching Criteria&nbsp;&nbsp;";
            sstr = sstr + "<br>";
            //if (flg == 0) {

            sstr = sstr + "<select name='sort_g_tool' id='sort_g_tool' onChange='display_request_gaylords_child(" + id +
                "," + this.value + "," + boxid + "," + n_left + "," + n_top + ")'><option value='1'";

            if (flg == 1) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">Matching Criteria</option><option value='2'";
            if (flg == 2) {
                sstr = sstr + "selected";
            }
            sstr = sstr + ">Matching Criteria & Available NOW</option><option value='5'";
            if (flg == 5) {
                sstr = sstr + "selected";
            }
            sstr = sstr + ">All Boxes (No filter)</option></select>";

            sstr = sstr + "</td>";
            sstr = sstr + "</tr>";
            sstr = sstr + "</table>";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                    document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "quote_request_gaylords.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flg, true);
            xmlhttp.send();

        }

        function display_request_gaylords_child(id, flg, boxid, n_left, n_top) {
            var flgs = document.getElementById("sort_g_tool").value;
            //alert(flgs);
            //
            var selectobject = document.getElementById("lightbox");
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');

            var sstr = "";
            sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
            sstr = sstr + "<tr align='center'>";
            sstr = sstr + "<td bgcolor='#FF9900'>";
            sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>GAYLORD MATCHING TOOL</font>";
            sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
            sstr = sstr +
                "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
            //
            if (flgs == 1) {
                sstr = sstr + "Matching Criteria&nbsp;&nbsp;";
            }
            if (flgs == 2) {
                sstr = sstr +
                    "Below list only display 'Available Now', 'Available & Urgent', 'Available >= 1 TL', 'Available <= 1 TL' and UCB Owned inventory &nbsp;&nbsp;";
            }

            if (flgs == 5) {
                sstr = sstr + "Below list display all boxes &nbsp;&nbsp;";
            }
            //
            sstr = sstr + "<br>";
            //if (flg == 0) {

            sstr = sstr + "<select name='sort_g_tool' id='sort_g_tool' onChange='display_request_gaylords_child(" + id +
                "," + this.value + "," + boxid + "," + n_left + "," + n_top + ")'><option value='1'";

            if (flgs == 1) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">Matching Criteria</option><option value='2'";
            if (flgs == 2) {
                sstr = sstr + "selected";
            }
            sstr = sstr + ">Matching Criteria & Available NOW</option><option value='5'";
            if (flgs == 5) {
                sstr = sstr + "selected";
            }
            sstr = sstr + ">All Boxes (No filter)</option></select>";

            sstr = sstr + "</td>";
            sstr = sstr + "</tr>";
            sstr = sstr + "</table>";

            if (flgs == 2 || flgs == 5) {
                sstr = sstr + "<table width='100%' border='0' cellspacing='1' cellpadding='1' ><tr>";
                sstr = sstr + "<td colspan='14'><font face='Arial, Helvetica, sans-serif' size='1'>";
                sstr = sstr + "<a href='#' onmouseover=" + String.fromCharCode(34) +
                    "Tip('Program search for the neareast Box location based on the Ship To Zipcode. This is done based on the Zip code Latitude and longitude values stored in the database. ";

                if (flgs == 2) {
                    sstr = sstr + "And shown boxes based on Matching Criteria & Available NOW.')";
                }
                if (flgs == 5) {
                    sstr = sstr + "And shown All Boxes (No filter).')";
                }
                sstr = sstr + String.fromCharCode(34) + " onmouseout='UnTip()'>How it Works</a></font></table>";
            }

            var selectobject = document.getElementById("lightbox");
            //var n_left = f_getPosition(selectobject, 'Left');
            //var n_top  = f_getPosition(selectobject, 'Top');
            document.getElementById('light_gaylord_new1').style.display = 'block';
            document.getElementById('light_gaylord_new1').style.left = n_left + 20 + 'px';
            document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';

            document.getElementById("light_gaylord_new1").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "quote_request_gaylords.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flgs, true);
            xmlhttp.send();
        }
        //End quote request matchin tool for Gaylord

        //Pallets matching tool
        function display_new_pallets(id, boxid, flg) {
            var selectobject = document.getElementById("lightbox_new_pallets");
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');
            document.getElementById('light_pallets_new').style.display = 'block';
            document.getElementById('light_pallets_new').style.left = n_left + 20 + 'px';
            document.getElementById('light_pallets_new').style.top = n_top + 20 + 'px';
            document.getElementById('light_pallets_new').style.height = 580 + 'px';

            document.getElementById("light_pallets_new").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

            var sstr = "";
            sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
            sstr = sstr + "<tr align='center'>";
            sstr = sstr + "<td bgcolor='#FF9900'>";
            sstr = sstr +
                "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>NEW PALLETS MATCHING TOOL</font>";
            sstr = sstr + "&nbsp;&nbsp;";
            sstr = sstr +
                "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_pallets_new').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
            /*sstr = sstr + "Matching Criteria&nbsp;&nbsp;";
            sstr = sstr + "<br>";
            //if (flg == 0) {
            //alert(boxid);
            sstr = sstr + "<select name='sort_g_tool' id='sort_g_tool' onChange='display_new_pallets_child(" + id + "," + this.value + "," + boxid + "," + n_left + "," + n_top + ")'><option value='1'";

            if(flg==1){
               sstr = sstr + " selected ";
            } 
            sstr = sstr +">Matching Criteria</option><option value='2'";
            if(flg==2){
               sstr = sstr +"selected";
            }  
            sstr = sstr +">Matching Criteria & Available NOW</option><option value='5'";
            if(flg==5){
               sstr = sstr +"selected";
            }  
            sstr = sstr +">All Boxes (No filter)</option></select>";*/

            sstr = sstr + "</td>";
            sstr = sstr + "</tr>";
            //sstr = sstr + String.fromCharCode(34) + " onmouseout='UnTip()'>How it Works</a></font></table>";
            sstr = sstr + "</table>";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("light_pallets_new").innerHTML = sstr + xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "sales_pallets.php?ID=" + id + "&boxid=" + boxid + "&display-allrec=" + flg, true);
            xmlhttp.send();
        }

        function display_new_pallets_child(id, flg, boxid, n_left, n_top) {
            var flgs = document.getElementById("sort_g_tool").value;
            //alert(flgs);
            //
            var selectobject = document.getElementById("lightbox");
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');

            var sstr = "";
            sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
            sstr = sstr + "<tr align='center'>";
            sstr = sstr + "<td bgcolor='#FF9900'>";
            sstr = sstr +
                "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>NEW PALLETS MATCHING TOOL</font>";
            sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
            sstr = sstr +
                "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_pallets_new').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
            //
            /*if(flgs==1){
            sstr = sstr + "Matching Criteria&nbsp;&nbsp;";
        }
        if(flgs==2){
            sstr = sstr + "Below list only display 'Available Now', 'Available & Urgent', 'Available >= 1 TL', 'Available <= 1 TL' and UCB Owned inventory &nbsp;&nbsp;";
        }

        if(flgs==5){
            sstr = sstr + "Below list display all boxes &nbsp;&nbsp;";
        }
        //
		sstr = sstr + "<br>";
		//if (flg == 0) {
            
        sstr = sstr + "<select name='sort_g_tool' id='sort_g_tool' onChange='display_new_pallets_child(" + id + "," + this.value + "," + boxid + "," + n_left + "," + n_top + ")'><option value='1'"; 
                
       if(flgs==1){
           sstr = sstr + " selected ";
       } 
       sstr = sstr +">Matching Criteria</option><option value='2'";
       if(flgs==2){
           sstr = sstr +"selected";
       }  
        sstr = sstr +">Matching Criteria & Available NOW</option><option value='5'";
        if(flgs==5){
           sstr = sstr +"selected";
       }  
        sstr = sstr +">All Boxes (No filter)</option></select>";*/

            sstr = sstr + "</td>";
            sstr = sstr + "</tr>";
            sstr = sstr + "</table>";

            /*if(flgs==2 || flgs==5){
            	sstr = sstr + "<table width='100%' border='0' cellspacing='1' cellpadding='1' ><tr>";
            	sstr = sstr + "<td colspan='14'><font face='Arial, Helvetica, sans-serif' size='1'>";
            	sstr = sstr + "<a href='#' onmouseover=" + String.fromCharCode(34) + "Tip('Program search for the neareast Box location based on the Ship To Zipcode. This is done based on the Zip code Latitude and longitude values stored in the database. ";

            	if(flgs==2){
            		sstr = sstr + "And shown boxes based on Matching Criteria & Available NOW.')";
            	} 
            	if(flgs==5){
            		sstr = sstr + "And shown All Boxes (No filter).')";
            	} 
            	//sstr = sstr + String.fromCharCode(34) + " onmouseout='UnTip()'>How it Works</a></font></table>";
            	sstr = sstr + "</table>";
            }*/

            var selectobject = document.getElementById("lightbox_new_pallets");
            //var n_left = f_getPosition(selectobject, 'Left');
            //var n_top  = f_getPosition(selectobject, 'Top');
            document.getElementById('light_pallets_new').style.display = 'block';
            document.getElementById('light_pallets_new').style.left = n_left + 20 + 'px';
            document.getElementById('light_pallets_new').style.top = n_top + 20 + 'px';

            document.getElementById("light_pallets_new").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("light_pallets_new").innerHTML = sstr + xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "sales_pallets.php?ID=" + id + "&boxid=" + boxid + "&display-allrec=" + flgs, true);
            xmlhttp.send();
        }
        //
        function display_new_pallets_all(id, boxid, flg) {
            var selectobject = document.getElementById("lightbox_new_pallets0");
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');
            document.getElementById('light_pallets_new').style.display = 'block';
            document.getElementById('light_pallets_new').style.left = n_left + 20 + 'px';
            document.getElementById('light_pallets_new').style.top = n_top + 20 + 'px';
            document.getElementById('light_pallets_new').style.height = 580 + 'px';

            document.getElementById("light_pallets_new").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

            var sstr = "";
            sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
            sstr = sstr + "<tr align='center'>";
            sstr = sstr + "<td bgcolor='#FF9900'>";
            sstr = sstr +
                "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>NEW PALLETS MATCHING TOOL</font>";
            sstr = sstr + "&nbsp;&nbsp;";
            sstr = sstr +
                "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_pallets_new').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
            sstr = sstr + "</td></tr>";
            //sstr = sstr + String.fromCharCode(34) + " onmouseout='UnTip()'>How it Works</a></font></table>";
            sstr = sstr + "</table>";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("light_pallets_new").innerHTML = sstr + xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "sales_pallets.php?ID=" + id + "&boxid=" + boxid + "&display-allrec=" + flg, true);
            xmlhttp.send();
        }
        //End pallets matching tool
        //
        function upd_boxes_warehouse_data(comp_id, warehouse_id) {
            var selectobject = document.getElementById("updatelist");
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');
            document.getElementById('light_boxupd').style.display = 'block';
            document.getElementById('light_boxupd').style.left = n_left - 200 + 'px';
            document.getElementById('light_boxupd').style.top = n_top + 20 + 'px';

            document.getElementById("light_boxupd").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

            var sstr = "";
            sstr =
                "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_boxupd').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
            sstr = sstr + "<br>";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("light_boxupd").innerHTML = sstr + xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "boxes_warehouse_data.php?ID=" + comp_id + "&warehouse_id=" + warehouse_id, true);
            xmlhttp.send();
        }

        function Add_boxes_warehouse_data(warehouse_id, box_id) {
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    alert("Box Added.");
                    document.getElementById("div_inv_items").innerHTML = xmlhttp.responseText;
                    document.getElementById("updbox_action_div" + box_id).innerHTML =
                        "<input type='button' name='btnadd' value='Add' onclick='Remove_boxes_warehouse_data(" +
                        warehouse_id + ", " + box_id + ")'>";
                }
            }
            xmlhttp.open("GET", "upd_boxes_warehouse_data.php?warehouse_id=" + warehouse_id + "&boxid=" + box_id +
                "&upd_action=1", true);
            xmlhttp.send();
        }

        function Remove_boxes_warehouse_data(warehouse_id, box_id) {
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    alert("Box Removed.");
                    document.getElementById("div_inv_items").innerHTML = xmlhttp.responseText;
                    document.getElementById("updbox_action_div" + box_id).innerHTML =
                        "<input type='button' name='btnadd' value='Add' onclick='Add_boxes_warehouse_data(" +
                        warehouse_id + ", " + box_id + ")'>";
                }
            }
            xmlhttp.open("GET", "upd_boxes_warehouse_data.php?warehouse_id=" + warehouse_id + "&boxid=" + box_id +
                "&upd_action=2", true);
            xmlhttp.send();
        }

        function addgaylord(companyid, inventoryid) {
            document.getElementById('light_gaylord').style.display = 'none';
            //document.getElementById("quota_boxes_maindiv").style.display = "none";
            document.getElementById("quota_boxes_maindiv").innerHTML =
                "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("quota_boxes_maindiv").innerHTML = xmlhttp.responseText;
                }
            }
            xmlhttp.open("POST", "box_quoting_mrg_mysqli.php?ID=" + companyid + "&inventoryID=" + inventoryid +
                "&addgayloard=yes", true);
            xmlhttp.send();
        }

        function popup_general_update(frm, flg, transid, warehouse_id) {
            notesdata = document.getElementById('frm_orderissue_notes').value;
            est_cost = 0;
            reason = 0;
            if (flg == 0) {
                est_cost = document.getElementById('frm_orderissue_est_cost').value;
                reason = document.getElementById('frm_orderissue_reason').value;
            }

            document.getElementById("popup_window").innerHTML =
                "<br/><br/>Updating .....<img src='images/wait_animated.gif' />" + document.getElementById("popup_window")
                .innerHTML;

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    alert("Data updated");
                    document.getElementById('hd_frm_orderissue_close').onclick();
                    document.getElementById('frm_orderissue_td').innerHTML = xmlhttp.responseText;

                    document.getElementById('frm_trans_notes_td').innerHTML =
                        "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

                    if (window.XMLHttpRequest) {
                        xmlhttp_child = new XMLHttpRequest();
                    } else {
                        xmlhttp_child = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp_child.onreadystatechange = function() {
                        if (xmlhttp_child.readyState == 4 && xmlhttp_child.status == 200) {
                            document.getElementById('frm_trans_notes_td').innerHTML = xmlhttp_child.responseText;
                        }
                    }

                    xmlhttp_child.open("GET", "search_result_include_crm_forajax.php?warehouse_id=" + warehouse_id +
                        "&rec_id=" + transid + "&rec_type=Supplier", true);
                    xmlhttp_child.send();

                }
            }

            xmlhttp.open("GET", "loop_popup_general_update.php?warehouse_id=" + warehouse_id + "&rec_id=" + transid +
                "&hd_frm_orderissue_frm=" + frm + "&hd_frm_orderissue_flg=" + flg + "&hd_frm_orderissue_transid=" +
                transid + "&hd_frm_orderissue_warehouse_id=" + warehouse_id + "&frm_orderissue_notes=" + notesdata +
                "&est_cost=" + est_cost + "&reason=" + reason, true);
            xmlhttp.send();
        }

        function show_popup_general(frm, flg, ctrlid, transid, warehouse_id) {
            var selectobject = document.getElementById(ctrlid);
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');
            document.getElementById('popup_window').style.display = 'block';
            document.getElementById('popup_window').style.left = n_left + 50 + 'px';
            document.getElementById('popup_window').style.top = n_top + 20 + 'px';
            document.getElementById('popup_window').style.width = '300px';
            document.getElementById('popup_window').style.height = '300px';

            document.getElementById("popup_window").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("popup_window").innerHTML = xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "loop_show_popup_general.php?frm=" + frm + "&flg=" + flg + "&transid=" + transid +
                "&warehouse_id=" + warehouse_id, true);
            xmlhttp.send();

        }

        function display_shipping_tool(id, flg, boxid, ctrlid) {
            var selectobject = document.getElementById("lightbox_shipping" + ctrlid);
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');
            document.getElementById('light_shipping').style.display = 'block';
            document.getElementById('light_shipping').style.left = n_left - 200 + 'px';
            document.getElementById('light_shipping').style.top = n_top + 20 + 'px';

            document.getElementById("light_shipping").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

            var sstr = "";
            sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
            sstr = sstr + "<tr align='center'>";
            sstr = sstr + "<td bgcolor='#C0CDDA'>";
            sstr = sstr +
                "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>SHIPPING BOX MATCHING TOOL</font>";
            sstr = sstr + "&nbsp;&nbsp;";
            sstr = sstr +
                "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_shipping').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
            sstr = sstr + "<br>";
            if (flg == 0) {
                sstr = sstr +
                    "Below list display 'Available Now', 'Available & Urgent', 'Available >= 1 TL', 'Available < 1 TL', 'Check Loops' boxes &nbsp;&nbsp;";
                sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_shipping_child(" + id +
                    ", 1 ," + boxid + "," + ctrlid + "," + n_left + "," + n_top + ")'>Display only Available boxes</a>";
            } else {
                sstr = sstr + "Below list display all the boxes &nbsp;&nbsp;";
                sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_shipping_child(" + id +
                    ", 0," + boxid + "," + ctrlid + "," + n_left + "," + n_top + ")'>Display All Boxes</a>";
            }
            sstr = sstr + "</td>";
            sstr = sstr + "</tr>";
            sstr = sstr + "</table>";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("light_shipping").innerHTML = sstr + xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "shipping_tool.php?ID=" + id + "&boxid=" + boxid + "&display-allrec=" + flg, true);
            xmlhttp.send();
        }

        function display_shipping_child(id, flg, boxid, ctrlid, n_left, n_top) {
            var sstr = "";
            sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
            sstr = sstr + "<tr align='center'>";
            sstr = sstr + "<td bgcolor='#C0CDDA'>";
            sstr = sstr +
                "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>SHIPPING BOX MATCHING TOOL</font>";
            sstr = sstr + "&nbsp;&nbsp;";
            sstr = sstr +
                "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_shipping').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
            sstr = sstr + "<br>";
            if (flg == 0) {
                sstr = sstr +
                    "Below list only display 'Available Now', 'Available & Urgent', 'Available >= 1 TL', 'Available < 1 TL', 'Check Loops' boxes &nbsp;&nbsp;";
                sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_shipping_child(" + id +
                    ", 1," + boxid + "," + ctrlid + "," + n_left + "," + n_top + ")'>Display only Available boxes</a>";
            } else {
                sstr = sstr + "Below list display all the boxes &nbsp;&nbsp;";
                sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_shipping_child(" + id +
                    ", 0," + boxid + "," + ctrlid + "," + n_left + "," + n_top + ")'>Display All Boxes</a>";
            }
            sstr = sstr + "</td>";
            sstr = sstr + "</tr>";
            sstr = sstr + "</table>";

            var selectobject = document.getElementById("lightbox");
            //var n_left = f_getPosition(selectobject, 'Left');
            //var n_top  = f_getPosition(selectobject, 'Top');
            document.getElementById('light_shipping').style.display = 'block';
            document.getElementById('light_shipping').style.left = n_left - 200 + 'px';
            document.getElementById('light_shipping').style.top = n_top + 20 + 'px';

            document.getElementById("light_shipping").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("light_shipping").innerHTML = sstr + xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "shipping_tool.php?ID=" + id + "&boxid=" + boxid + "&display-allrec=" + flg, true);
            xmlhttp.send();
        }
        //Function for new shipping tool
        function display_new_shipping_tool(id, flg, boxid) {
            //alert(boxid);
            var selectobject = document.getElementById("lightbox_new_shipping" + boxid);
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');
            document.getElementById('light_new_shipping').style.display = 'block';
            document.getElementById('light_new_shipping').style.left = n_left + 'px';
            document.getElementById('light_new_shipping').style.top = n_top + 20 + 'px';

            document.getElementById("light_new_shipping").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

            var sstr = "";
            sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
            sstr = sstr + "<tr align='center'>";
            sstr = sstr + "<td bgcolor='#C0CDDA'>";
            sstr = sstr +
                "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>SHIPPING BOX MATCHING TOOL</font>";
            sstr = sstr + "&nbsp;&nbsp;";
            sstr = sstr +
                "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none'; document.getElementById('fade').style.display='none'>Close</a>";
            sstr = sstr + "<br>";
            sstr = sstr + "Matching Criteria&nbsp;&nbsp;";
            sstr = sstr + "<br>";
            //if (flg == 0) {

            sstr = sstr + "<select name='sort_sb_tool' id='sort_sb_tool' onChange='display_new_shipping_child(" + id + "," +
                this.value + "," + boxid + "," + n_left + "," + n_top + ")'><option value='1'";

            if (flg == 1) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">Matching Criteria</option><option value='2'";
            if (flg == 2) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">Matching Criteria & Available NOW</option><option value='5'";
            if (flg == 5) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">All Boxes (No filter)</option></select>";
            sstr = sstr + "</td>";
            sstr = sstr + "</tr>";
            sstr = sstr + "</table>";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("light_new_shipping").innerHTML = sstr + xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "sales_shipping_tool.php?ID=" + id + "&boxid=" + boxid + "&display-allrec=" + flg, true);
            xmlhttp.send();
        }

        function display_new_shipping_child(id, flg, boxid, n_left, n_top) {
            var flgs = document.getElementById("sort_sb_tool").value;
            var selectobject = document.getElementById("lightbox");
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');
            //
            var sstr = "";
            sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
            sstr = sstr + "<tr align='center'>";
            sstr = sstr + "<td bgcolor='#C0CDDA'>";
            sstr = sstr +
                "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>SHIPPING BOX MATCHING TOOL</font>";
            sstr = sstr + "&nbsp;&nbsp;";
            sstr = sstr +
                "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
            sstr = sstr + "<br>";
            sstr = sstr + "Matching Criteria&nbsp;&nbsp;";
            sstr = sstr + "<br>";
            //if (flg == 0) {

            sstr = sstr + "<select name='sort_sb_tool' id='sort_sb_tool' onChange='display_new_shipping_child(" + id + "," +
                this.value + "," + boxid + "," + n_left + "," + n_top + ")'><option value='1'";

            if (flgs == 1) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">Matching Criteria</option><option value='2'";
            if (flgs == 2) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">Matching Criteria & Available NOW</option><option value='5'";
            if (flgs == 5) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">All Boxes (No filter)</option></select>";
            sstr = sstr + "</td>";
            sstr = sstr + "</tr>";
            sstr = sstr + "</table>";

            if (flgs == 2 || flgs == 5) {
                sstr = sstr + "<table width='100%' border='0' cellspacing='1' cellpadding='1' ><tr>";
                sstr = sstr + "<td colspan='14'><font face='Arial, Helvetica, sans-serif' size='1'>";
                sstr = sstr + "<a href='#' onmouseover=" + String.fromCharCode(34) +
                    "Tip('Program search for the neareast Box location based on the Ship To Zipcode. This is done based on the Zip code Latitude and longitude values stored in the database. ";

                if (flgs == 2) {
                    sstr = sstr + "And shown boxes based on Matching Criteria & Available NOW.')";
                }
                if (flgs == 5) {
                    sstr = sstr + "And shown All Boxes (No filter).')";
                }
                sstr = sstr + String.fromCharCode(34) + " onmouseout='UnTip()'>How it Works</a></font></table>";
            }

            var selectobject = document.getElementById("lightbox");
            //var n_left = f_getPosition(selectobject, 'Left');
            //var n_top  = f_getPosition(selectobject, 'Top');
            document.getElementById('light_new_shipping').style.display = 'block';
            document.getElementById('light_new_shipping').style.left = n_left + 'px';
            document.getElementById('light_new_shipping').style.top = n_top + 20 + 'px';

            document.getElementById("light_new_shipping").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("light_new_shipping").innerHTML = sstr + xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "sales_shipping_tool.php?ID=" + id + "&boxid=" + boxid + "&display-allrec=" + flgs, true);
            xmlhttp.send();
        }
        //
        //Function for new shipping tool
        function display_new_shipping_tool_all(id, flg, boxid) {
            //alert(boxid);
            var selectobject = document.getElementById("lightbox_new_shipping" + boxid);
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');
            document.getElementById('light_new_shipping').style.display = 'block';
            document.getElementById('light_new_shipping').style.left = n_left + 'px';
            document.getElementById('light_new_shipping').style.top = n_top + 20 + 'px';

            document.getElementById("light_new_shipping").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

            var sstr = "";
            sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
            sstr = sstr + "<tr align='center'>";
            sstr = sstr + "<td bgcolor='#C0CDDA'>";
            sstr = sstr +
                "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>SHIPPING BOX MATCHING TOOL</font>";
            sstr = sstr + "&nbsp;&nbsp;";
            sstr = sstr +
                "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none'; document.getElementById('fade').style.display='none'>Close</a>";
            sstr = sstr + "<br>";
            sstr = sstr + "Matching Criteria&nbsp;&nbsp;";
            sstr = sstr + "<br>";
            //if (flg == 0) {

            sstr = sstr + "<select name='sort_sb_tool' id='sort_sb_tool' onChange='display_new_shipping_child(" + id + "," +
                this.value + "," + boxid + "," + n_left + "," + n_top + ")'><option value='1'";

            if (flg == 1) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">Matching Criteria</option><option value='2'";
            if (flg == 2) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">Matching Criteria & Available NOW</option><option value='5'";
            if (flg == 5) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">All Boxes (No filter)</option></select>";
            sstr = sstr + "</td>";
            sstr = sstr + "</tr>";
            sstr = sstr + "</table>";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("light_new_shipping").innerHTML = sstr + xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "sales_shipping_tool.php?ID=" + id + "&boxid=" + boxid + "&display-allrec=" + flg, true);
            xmlhttp.send();
        }
        //End new shipping tool
        //
        //Display quote request shipping matching tool
        function display_request_shipping_tool(id, flg, boxid) {
            //alert(boxid);
            var selectobject = document.getElementById("lightbox_req_shipping" + boxid);
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');
            document.getElementById('light_new_shipping').style.display = 'block';
            document.getElementById('light_new_shipping').style.left = n_left + 'px';
            document.getElementById('light_new_shipping').style.top = n_top + 20 + 'px';

            document.getElementById("light_new_shipping").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

            var sstr = "";
            sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
            sstr = sstr + "<tr align='center'>";
            sstr = sstr + "<td bgcolor='#C0CDDA'>";
            sstr = sstr +
                "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>SHIPPING BOX MATCHING TOOL</font>";
            sstr = sstr + "&nbsp;&nbsp;";
            sstr = sstr +
                "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none'; document.getElementById('fade').style.display='none'>Close</a>";
            sstr = sstr + "<br>";
            sstr = sstr + "Matching Criteria&nbsp;&nbsp;";
            sstr = sstr + "<br>";
            //if (flg == 0) {

            sstr = sstr + "<select name='sort_sb_tool' id='sort_sb_tool' onChange='display_request_shipping_child(" + id +
                "," + this.value + "," + boxid + "," + n_left + "," + n_top + ")'><option value='1'";

            if (flg == 1) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">Matching Criteria</option><option value='2'";
            if (flg == 2) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">Matching Criteria & Available NOW</option><option value='5'";
            if (flg == 5) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">All Boxes (No filter)</option></select>";
            sstr = sstr + "</td>";
            sstr = sstr + "</tr>";
            sstr = sstr + "</table>";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("light_new_shipping").innerHTML = sstr + xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "quote_request_shipping_tool.php?ID=" + id + "&boxid=" + boxid + "&display-allrec=" + flg,
                true);
            xmlhttp.send();
        }

        function display_request_shipping_child(id, flg, boxid, n_left, n_top) {
            var flgs = document.getElementById("sort_sb_tool").value;
            var selectobject = document.getElementById("lightbox");
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');
            //
            var sstr = "";
            sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
            sstr = sstr + "<tr align='center'>";
            sstr = sstr + "<td bgcolor='#C0CDDA'>";
            sstr = sstr +
                "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>SHIPPING BOX MATCHING TOOL</font>";
            sstr = sstr + "&nbsp;&nbsp;";
            sstr = sstr +
                "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
            sstr = sstr + "<br>";
            sstr = sstr + "Matching Criteria&nbsp;&nbsp;";
            sstr = sstr + "<br>";
            //if (flg == 0) {

            sstr = sstr + "<select name='sort_sb_tool' id='sort_sb_tool' onChange='display_new_shipping_child(" + id + "," +
                this.value + "," + boxid + "," + n_left + "," + n_top + ")'><option value='1'";

            if (flgs == 1) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">Matching Criteria</option><option value='2'";
            if (flgs == 2) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">Matching Criteria & Available NOW</option><option value='5'";
            if (flgs == 5) {
                sstr = sstr + " selected ";
            }
            sstr = sstr + ">All Boxes (No filter)</option></select>";
            sstr = sstr + "</td>";
            sstr = sstr + "</tr>";
            sstr = sstr + "</table>";

            if (flgs == 2 || flgs == 5) {
                sstr = sstr + "<table width='100%' border='0' cellspacing='1' cellpadding='1' ><tr>";
                sstr = sstr + "<td colspan='14'><font face='Arial, Helvetica, sans-serif' size='1'>";
                sstr = sstr + "<a href='#' onmouseover=" + String.fromCharCode(34) +
                    "Tip('Program search for the neareast Box location based on the Ship To Zipcode. This is done based on the Zip code Latitude and longitude values stored in the database. ";

                if (flgs == 2) {
                    sstr = sstr + "And shown boxes based on Matching Criteria & Available NOW.')";
                }
                if (flgs == 5) {
                    sstr = sstr + "And shown All Boxes (No filter).')";
                }
                sstr = sstr + String.fromCharCode(34) + " onmouseout='UnTip()'>How it Works</a></font></table>";
            }

            var selectobject = document.getElementById("lightbox");
            //var n_left = f_getPosition(selectobject, 'Left');
            //var n_top  = f_getPosition(selectobject, 'Top');
            document.getElementById('light_new_shipping').style.display = 'block';
            document.getElementById('light_new_shipping').style.left = n_left + 'px';
            document.getElementById('light_new_shipping').style.top = n_top + 20 + 'px';

            document.getElementById("light_new_shipping").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("light_new_shipping").innerHTML = sstr + xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "quote_request_shipping_tool.php?ID=" + id + "&boxid=" + boxid + "&display-allrec=" + flgs,
                true);
            xmlhttp.send();
        }

        //end quote request shipping matching tool

        function add_invitem(inv_id, companyID) {
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    alert("Item Added");
                    updateQuotingcart(companyID);
                }
            }
            xmlhttp.open("GET", "add_invitem_mysqli.php?inv_id=" + inv_id + "&companyID=" + companyID, true);
            xmlhttp.send();
        }

        function updateQuotingcart(companyid) {
            //document.getElementById("quota_boxes_maindiv").style.display = "none";
            divshow_quoting = document.getElementById("show_quoting");

            var innerDoc = (divshow_quoting.contentDocument) ?
                divshow_quoting.contentDocument :
                divshow_quoting.contentWindow.document;

            //document.getElementById("quota_boxes_maindiv").style.display = "none";
            innerDoc.getElementById("quota_boxes_maindiv").innerHTML =
                "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    innerDoc.getElementById("quota_boxes_maindiv").innerHTML = xmlhttp.responseText;
                }
            }

            xmlhttp.open("POST", "box_quoting_mrg_mysqli.php?ID=" + companyid, true);

            xmlhttp.send();

        }

        function display_add_invitem(companyID, salesflg) {
            var selectobject = document.getElementById("lightbox");
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');
            document.getElementById('light_addinventoryitem').style.display = 'block';
            document.getElementById('light_addinventoryitem').style.left = n_left + 20 + 'px';
            document.getElementById('light_addinventoryitem').style.top = n_top + 20 + 'px';
            document.getElementById('light_addinventoryitem').style.width = '1200px';

            document.getElementById("light_addinventoryitem").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("light_addinventoryitem").innerHTML = xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "display_add_invitem_mysqli.php?companyID=" + companyID + "&salesflg=y", true);
            xmlhttp.send();
        }


        function show_file_inviewer(filename, formtype) {
            document.getElementById("light").innerHTML =
                "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center>" +
                formtype + "</center><br/> <embed src='" + filename + "' width='800' height='800'>";
            document.getElementById('light').style.display = 'block';
            document.getElementById('fade').style.display = 'block';
        }

        /*function show_file_inviewer_pos(filename, formtype, ctrlnm){

		var selectobject = document.getElementById(ctrlnm); 
		var n_left = f_getPosition(selectobject, 'Left');
		var n_top  = f_getPosition(selectobject, 'Top');

		document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center>" + formtype +	"</center><br/> <embed src='"+ filename + "' width='800' height='800'>";
		document.getElementById('light').style.display='block';

		document.getElementById('light').style.left = n_left + 10 + 'px';
		document.getElementById('light').style.top = n_top + 10 + 'px';
	}
*/
        function show_data_inviewer(urlstr, formtype, ctrlnm) {
            var selectobject = document.getElementById(ctrlnm);
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("light").innerHTML =
                        "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
                        xmlhttp.responseText;
                    document.getElementById('light').style.display = 'block';

                    document.getElementById('light').style.left = n_left + 10 + 'px';
                    document.getElementById('light').style.top = n_top + 10 + 'px';
                }
            }

            xmlhttp.open("POST", urlstr, true);

            xmlhttp.send();

        }
        //

        function show_quote_req_inviewer_pos(companyid, ctrlnm) {

            var selectobject = document.getElementById(ctrlnm);
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    // alert(ctrlnm);
                    document.getElementById("light").innerHTML =
                        "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
                        xmlhttp.responseText;
                    document.getElementById('light').style.display = 'block';

                    document.getElementById('light').style.left = n_left + 10 + 'px';
                    document.getElementById('light').style.top = n_top + 10 + 'px';
                }
            }

            xmlhttp.open("GET", "quote_requested_add.php?quoteid=" + ctrlnm + "&companyid=" + companyid, true);

            xmlhttp.send();
        }
        //
        function deny_quote_req(companyid, ctrlnm) {

            var selectobject = document.getElementById(ctrlnm);
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    // alert(ctrlnm);
                    document.getElementById("light").innerHTML =
                        "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
                        xmlhttp.responseText;
                    document.getElementById('light').style.display = 'block';
                    document.getElementById('light').style.width = 430 + 'px';
                    document.getElementById('light').style.height = 180 + 'px';

                    document.getElementById('light').style.left = n_left + 10 + 'px';
                    document.getElementById('light').style.top = n_top + 10 + 'px';
                }
            }

            xmlhttp.open("GET", "quote_deny_reason.php?quoteid=" + ctrlnm + "&companyid=" + companyid, true);

            xmlhttp.send();
        }
        //
        function show_deny_info(ctrlnm, companyid) {

            var selectobject = document.getElementById("quotesdeny" + ctrlnm);
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    // alert(ctrlnm);
                    document.getElementById("light").innerHTML =
                        "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
                        xmlhttp.responseText;
                    document.getElementById('light').style.display = 'block';
                    document.getElementById('light').style.width = 430 + 'px';
                    document.getElementById('light').style.height = 180 + 'px';

                    document.getElementById('light').style.left = n_left + 10 + 'px';
                    document.getElementById('light').style.top = n_top + 10 + 'px';
                }
            }

            xmlhttp.open("GET", "quote_deny_reason_info.php?quoteid=" + ctrlnm + "&companyid=" + companyid, true);

            xmlhttp.send();
        }
        //
        //


        $(document).keydown(function(e) {
            // ESCAPE key pressed
            if (e.keyCode == 27) {
                $('#light_quota').hide();
            }
        })

        $(document).keydown(function(e) {
            // ESCAPE key pressed
            if (e.keyCode == 27) {
                $('#light_gaylord').hide();
            }
        });

        function addFreightfun(companyid) {
            divshow_quoting = document.getElementById("show_quoting");

            var innerDoc = (divshow_quoting.contentDocument) ?
                divshow_quoting.contentDocument :
                divshow_quoting.contentWindow.document;

            document.getElementById('light_quota').style.display = 'none';
            //document.getElementById("quota_boxes_maindiv").style.display = "none";
            innerDoc.getElementById("quota_boxes_maindiv").innerHTML =
                "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    innerDoc.getElementById("quota_boxes_maindiv").innerHTML = xmlhttp.responseText;
                }
            }

            xmlhttp.open("POST", "box_quoting_mrg_mysqli.php?ID=" + companyid + "&addFreight=yes", true);

            xmlhttp.send();

        }

        function update_quotnew(companyid, itemID, lengthInch, lengthNumerator, lengthDenominator, widthInch,
            widthNumerator,
            widthDenominator, depthInch, depthNumerator, depthDenominator, description, burstECT, item, boxNumber, newUsed,
            salePrice,
            cost, vendor, taxable, quantity, quantity_per_pallet, shipfinalvendor, shipfinal, ctrlidcart) {
            //var val= document.getElementById("update").value = "yes";	
            document.getElementById('light_quota').style.display = 'none';
            //document.getElementById("quota_boxes_maindiv").style.display = "none";
            document.getElementById("quota_boxes_maindiv").innerHTML =
                "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("quota_boxes_maindiv").innerHTML = xmlhttp.responseText;
                    //alert(xmlhttp.responseText);
                }
            }

            var lengthInch = encodeURIComponent(document.getElementById("lengthInch").value);
            var lengthNumerator = encodeURIComponent(document.getElementById("lengthNumerator").value);
            var lengthDenominator = encodeURIComponent(document.getElementById("lengthDenominator").value);
            var widthInch = encodeURIComponent(document.getElementById("widthInch").value);
            var widthNumerator = encodeURIComponent(document.getElementById("widthNumerator").value);
            var widthDenominator = encodeURIComponent(document.getElementById("widthDenominator").value);
            var depthInch = encodeURIComponent(document.getElementById("depthInch").value);
            var depthNumerator = encodeURIComponent(document.getElementById("depthNumerator").value);
            var depthDenominator = encodeURIComponent(document.getElementById("depthDenominator").value);
            var description = encodeURIComponent(document.getElementById("description").value);
            var burstECT = 0;
            var item = encodeURIComponent(document.getElementById("item").value);
            var boxNumber = 0;
            var newUsed = encodeURIComponent(document.getElementById("newUsed").value);
            var salePrice = 0;
            if (document.getElementById("salePrice" + ctrlidcart)) {
                var salePrice = encodeURIComponent(document.getElementById("salePrice" + ctrlidcart).value);
            }
            var cost = 0;
            var vendor = 0;
            var taxable = 0;
            if (document.getElementById("Taxable")) {
                var taxable = encodeURIComponent(document.getElementById("Taxable").value);
            }
            var quantity = 0;
            if (document.getElementById("quantitycart" + ctrlidcart)) {
                var quantity = encodeURIComponent(document.getElementById("quantitycart" + ctrlidcart).value);
            }
            var quantity_per_pallet = 0;
            var shipfinalvendor = 0;
            if (document.getElementById("shipfinalvendor")) {
                var shipfinalvendor = encodeURIComponent(document.getElementById("shipfinalvendor").value);
            }

            var shipfinal = 0;
            if (document.getElementById("shipfinal" + ctrlidcart)) {
                var shipfinal = encodeURIComponent(document.getElementById("shipfinal" + ctrlidcart).value);
            }

            var tmpvar = ("ID=" + companyid + "&itemID=" + itemID + "&lengthInch=" + lengthInch + "&lengthNumerator=" +
                lengthNumerator + "&lengthDenominator=" + lengthDenominator + "&widthInch=" + widthInch +
                "&widthNumerator=" + widthNumerator + "&widthDenominator=" + widthDenominator + "&depthInch=" +
                depthInch + "&depthNumerator=" + depthNumerator + "&depthDenominator=" + depthDenominator +
                "&description=" + description + "&burstECT=" + burstECT + "&item=" + item + "&boxNumber=" + boxNumber +
                "&newUsed=" + newUsed + "&salePrice=" + salePrice + "&cost=" + cost + "&vendor=" + vendor +
                "&taxable=" + taxable + "&quantity=" + quantity + "&quantity_per_pallet=" + quantity_per_pallet +
                "&shipfinalvendor=" + shipfinalvendor + "&shipfinal=" + shipfinal + "&update=yes");

            xmlhttp.open("POST", "box_quoting_mrg_mysqli.php?" + tmpvar, true);

            xmlhttp.send();
        }


        function createquote(companyID) {

            //document.getElementById("quota_boxes_maindiv").style.display = "none";
            document.getElementById("quota_boxes_maindiv").innerHTML =
                "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("quota_boxes_maindiv").innerHTML = xmlhttp.responseText;

                }
            }

            var quoteType = document.getElementById("quoteType").value;
            var poNumber = document.getElementById("poNumber").value;
            var terms = document.getElementById("terms").value;
            var free_shipping = document.getElementById("free_shipping").value;
            var rep = document.getElementById("rep").value;
            var TBD = document.getElementById("TBD").value;
            var shipdate = document.getElementById("date_of_activity").value;
            var via = document.getElementById("via").value;
            var notes = document.getElementById("notes").value;

        }

        function calcualteprofitloss(form, ctrlidcart) {
            if (document.getElementById("item_delivery_flg").value == "no") {
                qty = document.getElementById("quantitycart" + ctrlidcart).value;
                cost = document.getElementById("cost" + ctrlidcart).value;
                ship_quote = document.getElementById("shipfinal" + ctrlidcart).value;
                salePrice = parseFloat(document.getElementById("salePrice" + ctrlidcart).value.replace(/,/g, ''));
                minfob = document.getElementById("minfob" + ctrlidcart).value;

                val_1 = (qty * salePrice);
                val_2 = (qty * cost);

                cal = val_1 - val_2;
                //alert(val_1);

                finalcal = cal - ship_quote;

                if (qty != "" && minfob != "" && ship_quote != "") {
                    min_delv_cost_tmp = ship_quote / qty;
                    min_delv_cost = parseFloat(minfob) + min_delv_cost_tmp;
                    min_delv_cost = min_delv_cost.toFixed(2);
                    salePrice = parseFloat(salePrice);
                    if (salePrice >= min_delv_cost) {
                        document.getElementById("min_delv_cost" + ctrlidcart).innerHTML =
                            "<font face='Arial, Helvetica, sans-serif' size='1' color='green'>$" + min_delv_cost +
                            "</font>";
                    } else {
                        document.getElementById("min_delv_cost" + ctrlidcart).innerHTML =
                            "<font face='Arial, Helvetica, sans-serif' size='1' color='red'>$" + min_delv_cost + "</font>";
                    }
                }

                if (qty != "" && salePrice != "" && ship_quote != "") {
                    document.getElementById("profit" + ctrlidcart).value = finalcal.toFixed(2);
                }
                if (val_1 != "" && document.getElementById("profit" + ctrlidcart).value != "") {
                    //alert(document.getElementById("profit").value);
                    margin_val = (document.getElementById("profit" + ctrlidcart).value / (qty * salePrice)).toFixed(2);
                    document.getElementById("margin" + ctrlidcart).value = margin_val * 100;
                }
            }
        }

        function email_body(temp) {
            if (document.getElementById('email_div' + temp).style.display == 'none') {
                document.getElementById('email_div' + temp).style.display = 'block';
            } else if (document.getElementById('email_div' + temp).style.display == 'block') {
                document.getElementById('email_div' + temp).style.display = 'none';
            }
        }

        function isNumberKey_neg(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                if (charCode == 46 || charCode == 44 || charCode == 45) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        }

        function freight_cal(b2bid) {
            //alert(b2bid);

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("ifr").contentDocument.write(xmlhttp.responseText);
                }
            }

            xmlhttp.open("POST", "freight_calculator.php?b2bid=" + b2bid, true);

            xmlhttp.send();

        }

        function isSpace(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode == 32)
                return false;

            return true;
        }

        function pickup_appointment_sendmail(compid, rec_id, warehouse_id, rec_type) {
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    selectobject = document.getElementById("pickup_appointment_email");
                    n_left = f_getPosition(selectobject, 'Left');
                    n_top = f_getPosition(selectobject, 'Top');

                    document.getElementById("light").innerHTML = xmlhttp.responseText;
                    document.getElementById('light').style.display = 'block';

                    document.getElementById('light').style.left = (n_left + 50) + 'px';
                    document.getElementById('light').style.top = n_top + 50 + 'px';
                    document.getElementById('light').style.width = 1100 + 'px';
                }
            }

            xmlhttp.open("POST", "sendemail_pickup_appointment.php?compid=" + compid + "&rec_id=" + rec_id +
                "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type, true);
            xmlhttp.send();
        }
    </script>

</head>

<!-- style="background-color:#DCEDC2" -->

<body>

    <script type="text/javascript" src="wz_tooltip.js"></script>
    <div id="light" class="white_content"></div>
    <div id="fade" class="black_overlay"></div>
    <div id="light_details" class="white_content_details"></div>
    <div id="light_gaylord" class="white_content_gaylord_new"></div>
    <div id="light_gaylord_new" class="white_content_gaylord_new"></div>
    <!--For new Gaylord tool-->
    <div id="light_gaylord_new1" class="white_content_gaylord_new1"></div>
    <div id="light_pallets_new" class="white_content_gaylord_new1"></div>

    <!--\\\\\\\\\\\\-->
    <div id="light_boxupd" class="white_content_gaylord_new"></div>

    <div id="popup_window" class="white_content_gaylord_new"></div>

    <div id="light_shipping" class="white_content_gaylord_new"></div>
    <div id="light_new_shipping" class="white_content_gaylord_new"></div>
    <div id="light_addinventoryitem" class="white_content_gaylord_new"></div>
    <div id="light_quota" class="white_content_quota"></div>
    <div id="light_reminder" class="white_content_reminder"></div>
    <?php include("inc/header.php"); ?>
    <br>
    <div class="main_data_css">
        <?php
        //echo Date("m/d/Y H:i:s") . "<br>";

        if (isset($_REQUEST["quoteid"])) {
            $quotes_archive_date = "";
            db();
            $query = "SELECT variablevalue FROM tblvariable where variablename = 'quotes_archive_date'";
            $dt_view_res3 = db_query($query);
            while ($objQuote = array_shift($dt_view_res3)) {
                $quotes_archive_date = $objQuote["variablevalue"];
            }

            $quote_filename = "";
            db_b2b();
            $sql = "SELECT companyID, filename, quoteDate FROM quote WHERE ID = " . $_REQUEST["quoteid"];
            $result = db_query($sql);
            if ($myrowsel = array_shift($result)) {
                $quote_filename = $myrowsel["filename"];
            }

            $archeive_date = new DateTime(date("Y-m-d", strtotime($quotes_archive_date)));
            $quote_date = new DateTime(date("Y-m-d", strtotime($myrowsel["quoteDate"])));

            if ($quote_date < $archeive_date) {

                echo "<h4>Quote Search result &nbsp; 
				<a target='_blank' href='https://usedcardboardboxes.sharepoint.com/:b:/r/sites/LoopsCRMEmailAttachments/Shared%20Documents/quotes/before_oct_18_2022/" . $quote_filename . "'>" . $quote_filename . "</a>
			</h4>";
            } else {
                echo "<h4>Quote Search result &nbsp; <a target='_blank' href='quotes/" . $quote_filename . "'>" . $quote_filename . "</a></h4>";
            }
        }
        ?>

        <table width="100%">
            <tr>
                <td align="left" valign="middle">
                    <?php
                    $status = "";
                    $freightupdates = 1;
                    $negotiated_rate = "";
                    db_b2b();
                    $qry_1 = "Select company,active,haveNeed, loopid, on_hold, freightupdates, status from companyInfo Where ID =?";
                    //echo $qry_1;

                    $ucb_account_status = "";
                    $dt_view_1 = db_query($qry_1, array("i"), array($_REQUEST["ID"]));
                    if (tep_db_num_rows($dt_view_1) > 0) {
                        while ($rows = array_shift($dt_view_1)) {
                            $company = $rows['company'];
                            $active_1 = $rows["active"];
                            $status = $rows["haveNeed"];
                            $freightupdates = $rows["freightupdates"];
                            $ucb_account_status = $rows['status'] == 38 || $rows['status'] == 122 || $rows['status'] == 123 || $rows['status'] == 124 ? " - Contracted" : ""; 
                            if ($status == "Need Boxes") {
                    ?>
                                <a
                                    href="viewCompany-purchasing.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=summary&rec_type=<?php echo $rec_type; ?>">
                                    <font face="Arial, Helvetica, sans-serif" size="5" color='#00008b'>
                                        <b><?php echo $company; ?></b> 
                                    </font>
                                   
                                </a>
                                <font face="Arial, Helvetica, sans-serif" color="#625D5D" size="5"><b><?php echo $ucb_account_status; ?></b></font>
                            <?php  } else { ?>
                                <a
                                    href="viewCompany-purchasing.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=summary&rec_type=<?php echo $rec_type; ?>">
                                    <font face="Arial, Helvetica, sans-serif" size="5" color="#3EA99F">
                                        <b><?php echo $company; ?></b>
                                    </font>
                                </a>
                                <font face="Arial, Helvetica, sans-serif" color="#625D5D" size="5"><b><?php echo $ucb_account_status; ?></b></font>
                            <?php  } ?>

                            <?php if ($active_1 == 0) { ?>
                                <Font Face='Arial, Helvetica, sans-serif' size='5' color="red"><b>&nbsp;INACTIVE</b>
                                    <font>
                                    <?php  }

                                if ($rows["on_hold"] == 1) { ?>
                                        <Font Face='Arial, Helvetica, sans-serif' size='5' color="red"><b>&nbsp;ON HOLD</b>
                                            <font>
                                            <?php  }

                                        if ($duplicate_chk > 1) { ?>
                                                <Font Face='Arial, Helvetica, sans-serif' size='2' color="red"><b>&nbsp;Something is
                                                        wrong with this Company Record, please inform Zac Fratkin or Marina Pianucci
                                                        via email</b>
                                                    <font>
                                                        <?php  }

                                                    if ($rows['loopid'] > 0) {

                                                        if ($status == "Need Boxes") { ?>
                                                            <Font Face='Arial, Helvetica, sans-serif' color='#625D5D' size='5'>
                                                                <b>&nbsp;[Sales]</b>
                                                                <font>
                                                                <?php  } else if ($status == "Water") { ?>
                                                                    <Font Face='Arial, Helvetica, sans-serif' color='#625D5D' size='5'>
                                                                        <b>&nbsp;[Water]</b>
                                                                        <font>
                                                                        <?php  } else { ?>
                                                                            <Font Face='Arial, Helvetica, sans-serif' color='#625D5D'
                                                                                size='5'><b>&nbsp;[Purchasing] </b>
                                                                                <font>
                                                                                <?php  }
                                                                        } else {
                                                                            if ($status == "Need Boxes") { ?>
                                                                                    <Font Face='Arial, Helvetica, sans-serif'
                                                                                        color='#625D5D' size='5'><b>&nbsp;[Sales]</b>
                                                                                        <font>
                                                                                        <?php  } else if ($status == "Water") { ?>
                                                                                            <Font Face='Arial, Helvetica, sans-serif'
                                                                                                color='#625D5D' size='5'>
                                                                                                <b>&nbsp;[Water]</b>
                                                                                                <font>
                                                                                                <?php  } else { ?>
                                                                                                    <Font
                                                                                                        Face='Arial, Helvetica, sans-serif'
                                                                                                        color='#625D5D' size='5'>
                                                                                                        <b>&nbsp;[Purchasing]</b>
                                                                                                        <font>
                                                                                                    <?php  }
                                                                                            }
                                                                                        }
                                                                                    } else {

                                                                                        db();
                                                                                        $qry_2 = "Select company_name,active,rec_type from loop_warehouse Where id = " . $_REQUEST["ID"];
                                                                                        //echo $qry_2;
                                                                                        $dt_view_2 = db_query($qry_2);
                                                                                        while ($myrow = array_shift($dt_view_2)) {
                                                                                            $status = '';
                                                                                            $active_2 = $myrow["active"];
                                                                                            $company_name = $myrow['company_name'];
                                                                                            $rec_type = $myrow['rec_type'];
                                                                                                    ?>

                                                                                                    <?php if ($rec_type == "Supplier") { ?>
                                                                                                        <a
                                                                                                            href="viewCompany-purchasing.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=summary&rec_type=<?php echo $rec_type; ?>">
                                                                                                            <font
                                                                                                                face="Arial, Helvetica, sans-serif"
                                                                                                                size="5"
                                                                                                                color="#00008b">
                                                                                                                <b><?php echo $company_name; ?></b>
                                                                                                        </a>
                                                                                                        </font>
                                                                                                    <?php  } else { ?>
                                                                                                        <a
                                                                                                            href="viewCompany-purchasing.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=summary&rec_type=<?php echo $rec_type; ?>">
                                                                                                            <font
                                                                                                                face="Arial, Helvetica, sans-serif"
                                                                                                                size="5"
                                                                                                                color="#3EA99F">
                                                                                                                <b><?php echo $company_name; ?></b>
                                                                                                        </a>
                                                                                                    </font>
                                                                                                <?php  } ?>


                                                                                                <?php if ($active_2 == 0) { ?>
                                                                                                    <Font
                                                                                                        Face='Arial, Helvetica, sans-serif'
                                                                                                        size='5' color="red">
                                                                                                        <b>&nbsp;INACTIVE</b>
                                                                                                        <font>
                                                                                                        <?php  }

                                                                                                    if ($rec_type == "Supplier") { ?>
                                                                                                            <Font
                                                                                                                Face='Arial, Helvetica, sans-serif'
                                                                                                                color='#625D5D'
                                                                                                                size='5'>
                                                                                                                <b>&nbsp;[Sales]</b>
                                                                                                                <font>
                                                                                                                <?php  } else { ?>
                                                                                                                    <Font
                                                                                                                        Face='Arial, Helvetica, sans-serif'
                                                                                                                        color='#625D5D'
                                                                                                                        size='5'>
                                                                                                                        <b>&nbsp;[Purchasing]</b>
                                                                                                                        <font>
                                                                                                                <?php  }
                                                                                                        }
                                                                                                    }
                                                                                                                ?>
                </td>
            </tr>
        </table>

        <?php

        db();
        $parent_child_flg = "";
        $recfound = "no";
        $addl_select_crit = "AND bs_status != 'Neither' ORDER BY warehouse_name";
        $sql = "SELECT * FROM loop_warehouse WHERE b2bid=" . $_REQUEST["ID"] . " $addl_select_crit ";
        //echo $sql."<br>";
        $result = db_query($sql);

        if ($result) {
            $numofrows = tep_db_num_rows($result);
        } else {
            //die(mysql_error());
        }

        //echo $numofrows;
        if ($numofrows > 0) {
            $loopid = 0;
            $recfound = "no";
            if ($myrowsel = array_shift($result)) {
                $recfound = "yes";
                $loopid = $myrowsel["id"];
                $b2bid = $myrowsel["b2bid"];
                //echo $b2bid."**";
                $company_name = $myrowsel["company_name"];
                $company_address1 = $myrowsel["company_address1"];
                $company_address2 = $myrowsel["company_address2"];
                $company_city = $myrowsel["company_city"];
                $company_state = $myrowsel["company_state"];
                $company_zip = $myrowsel["company_zip"];
                $company_phone = $myrowsel["company_phone"];
                $company_email = $myrowsel["company_email"];

                $acc_email = $myrowsel["accounting_email"];
                $acc_contact = $myrowsel["accounting_contact"];
                $acc_phone = $myrowsel["accounting_phone"];

                $company_terms = $myrowsel["company_terms"];
                $company_contact = $myrowsel["company_contact"];
                $warehouse_name = $myrowsel["warehouse_name"];
                $warehouse_address1 = $myrowsel["warehouse_address1"];
                $warehouse_address2 = $myrowsel["warehouse_address2"];
                $warehouse_city = $myrowsel["warehouse_city"];
                $warehouse_state = $myrowsel["warehouse_state"];
                $warehouse_zip = $myrowsel["warehouse_zip"];
                $warehouse_contact = $myrowsel["warehouse_contact"];
                $warehouse_contact_phone = $myrowsel["warehouse_contact_phone"];
                $warehouse_contact_email = $myrowsel["warehouse_contact_email"];
                $warehouse_manager = $myrowsel["warehouse_manager"];
                $warehouse_manager_phone = $myrowsel["warehouse_manager_phone"];
                $warehouse_manager_email = $myrowsel["warehouse_manager_email"];
                $dock_details = $myrowsel["dock_details"];
                $warehouse_notes = $myrowsel["warehouse_notes"];
                $rec_type = $myrowsel["rec_type"];
                $bs_status = $myrowsel["bs_status"];
                $last_activity = $myrowsel["last_activity"];
                $other1 = $myrowsel["other1"];
                $other2 = $myrowsel["other2"];
                $other3 = $myrowsel["other3"];

                $overall_revenue_comp = $myrowsel["overall_revenue_comp"];
                $noof_location = $myrowsel["noof_location"];
            }
        } else {
            db();
            $sql = "SELECT * FROM loop_warehouse WHERE id=" . $_REQUEST["ID"] . " $addl_select_crit ";
            $result = db_query($sql);
            if ($result) {
                $numofrows = tep_db_num_rows($result);
            } else {
                //die(mysql_error());
            }
            if ($numofrows > 0) {
                $loopid = 0;
                $recfound = "no";
                if ($myrowsel = array_shift($result)) {
                    $recfound = "yes";
                    $loopid = $myrowsel["id"];
                    $b2bid = $myrowsel["id"];
                    $company_name = $myrowsel["company_name"];
                    $company_address1 = $myrowsel["company_address1"];
                    $company_address2 = $myrowsel["company_address2"];
                    $company_city = $myrowsel["company_city"];
                    $company_state = $myrowsel["company_state"];
                    $company_zip = $myrowsel["company_zip"];
                    $company_phone = $myrowsel["company_phone"];
                    $company_email = $myrowsel["company_email"];

                    $acc_email = $myrowsel["accounting_email"];
                    $acc_contact = $myrowsel["accounting_contact"];
                    $acc_phone = $myrowsel["accounting_phone"];

                    $company_terms = $myrowsel["company_terms"];
                    $company_contact = $myrowsel["company_contact"];
                    $warehouse_name = $myrowsel["warehouse_name"];
                    $warehouse_address1 = $myrowsel["warehouse_address1"];
                    $warehouse_address2 = $myrowsel["warehouse_address2"];
                    $warehouse_city = $myrowsel["warehouse_city"];
                    $warehouse_state = $myrowsel["warehouse_state"];
                    $warehouse_zip = $myrowsel["warehouse_zip"];
                    $warehouse_contact = $myrowsel["warehouse_contact"];
                    $warehouse_contact_phone = $myrowsel["warehouse_contact_phone"];
                    $warehouse_contact_email = $myrowsel["warehouse_contact_email"];
                    $warehouse_manager = $myrowsel["warehouse_manager"];
                    $warehouse_manager_phone = $myrowsel["warehouse_manager_phone"];
                    $warehouse_manager_email = $myrowsel["warehouse_manager_email"];
                    $dock_details = $myrowsel["dock_details"];
                    $warehouse_notes = $myrowsel["warehouse_notes"];
                    $rec_type = $myrowsel["rec_type"];
                    $bs_status = $myrowsel["bs_status"];
                    $last_activity = $myrowsel["last_activity"];
                    $other1 = $myrowsel["other1"];
                    $other2 = $myrowsel["other2"];
                    $other3 = $myrowsel["other3"];

                    $overall_revenue_comp = $myrowsel["overall_revenue_comp"];
                    $noof_location = $myrowsel["noof_location"];
                }
            } else {
                $b2bid = 0;
                db_b2b();
                $sql = "SELECT ID, parent_child FROM companyInfo Where ID =" . $_REQUEST["ID"];
                $result_tmp = db_query($sql);
                while ($myrowsel_tmp = array_shift($result_tmp)) {
                    $b2bid = $_REQUEST["ID"];
                }
            }
        }
        $id = $loopid;

        $haveNeed_flg = "";
        $loopidfromcomp = 0;
        if ($b2bid > 0) {
            $parent_child_compid = 0;
            db_b2b();
            $sql = "SELECT parent_child,parent_comp_id, haveNeed, loopid FROM companyInfo Where ID =" . $b2bid;
            $result_tmp = db_query($sql);
            while ($myrowsel_tmp = array_shift($result_tmp)) {
                $parent_child_flg = $myrowsel_tmp["parent_child"];
                $parent_child_compid = $myrowsel_tmp["parent_comp_id"];
                $haveNeed_flg = $myrowsel_tmp["haveNeed"];
                $loopidfromcomp = $myrowsel_tmp["loopid"];
            }

            //Check the Parent child
            $parent_child_cnt = "";
            $parent_child_cnt_cal = 0;
            if ($parent_child_flg != "") {
                if ($parent_child_flg == "Parent") {
                    $sql_pc = "SELECT count(*) as cnt FROM companyInfo Where  `active` = 1 and status <> 31 and link_sales_id <> ID and parent_comp_id =" . $b2bid . " and parent_child = 'Child'";
                } else {
                    $sql_pc = "SELECT count(*) as cnt FROM companyInfo Where  `active` = 1 and status <> 31 and link_sales_id <> ID and parent_comp_id =" . $parent_child_compid;
                }

                $result_pc = db_query($sql_pc);
                while ($myrowsel_pc = array_shift($result_pc)) {
                    $parent_child_cnt = "(" . ($myrowsel_pc["cnt"] + 1) . ")";
                    $parent_child_cnt_cal  = $myrowsel_pc["cnt"];
                }
            }
        }

        ?>


        <br>

        <a style="color:#0000FF;"
            href="viewCompany-purchasing.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=Quoting&rec_type=<?php echo $rec_type; ?>">Quoting</a>
        &nbsp;&nbsp;&nbsp;
        <?php if ($recfound == "no") {
        } else { ?>
            <a style="color:#0000FF;"
                href="viewCompany-purchasing.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=transactions&rec_type=<?php echo $rec_type; ?>">Transactions</a>
            &nbsp;&nbsp;&nbsp;
        <?php  } ?>
        <a style="color:#0000FF;"
            href="viewCompany-purchasing.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=accounting&rec_type=<?php echo $rec_type; ?>">Accounting</a>
        &nbsp;&nbsp;&nbsp;
        <?php if ($status == "Have Boxes") { ?>
            <a style="color:#0000FF;"
                href="viewCompany-purchasing.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=watertransactions&rec_type=<?php echo $rec_type; ?>">Water</a>
            &nbsp;&nbsp;&nbsp;

            <a style="color:#0000FF;"
                href="viewCompany-purchasing.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=watersalesinvoices&rec_type=<?php echo $rec_type; ?>">Water
                Sales Invoices</a> &nbsp;&nbsp;&nbsp;

            <a style="color:#0000FF;"
                href="viewCompany-purchasing.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=water-initiatives&rec_type=<?php echo $rec_type; ?>">Water
                Initiatives</a> &nbsp;&nbsp;&nbsp;
        <?php  } ?>

        <a style="color:#0000FF;"
            href="viewCompany-purchasing.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=recycling&rec_type=<?php echo $rec_type; ?>&purchasing=yes">Recycling</a>
        &nbsp;&nbsp;&nbsp;

        <a target="_blank" style="color:#0000FF;"
            href="report_inbound_inventory_summary.php?warehouse_id=<?php echo $loopidfromcomp; ?>">Inbound Summary</a>
        &nbsp;&nbsp;&nbsp;

        <?php if ($recfound == "no") {
        ?>
            <a href="converttoloops_mrg.php?ID=<?php echo $b2bid; ?>&show=summary">Start First
                Transaction</a>&nbsp;&nbsp;&nbsp;
        <?php  } else {
        }
        if ($parent_child_flg != "") {
        ?>
            <a style="color:#0000FF;"
                href="viewCompany-purchasing.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=parentchild&rec_type=<?php echo $rec_type; ?>">Family
                Tree <?php echo $parent_child_cnt; ?></a> &nbsp;&nbsp;&nbsp;
        <?php  } ?>

        <?php
        $client_dash_nm = "";
        $client_dash_displaynm = "";
        $client_dash_displaynm1 = "";
        if ($haveNeed_flg == "Need Boxes") {
            $client_dash_nm = "clientdashboard_setup.php";
            $client_dash_displaynm = "Setup Client Dashboard";
            $client_dash_displaynm1 = "Manage Client Dashboard";

            //Client dashboard Setup - added my MNM on July-31-14	
            db();
            $res = db_query("Select user_name from clientdashboard_usermaster where companyid = " . $_REQUEST["ID"]);
            $rec_found = "no";
            while ($fetch_data = array_shift($res)) {
                $rec_found = "yes";
            }

            if ($rec_found == "no") {
        ?>
                <a style='color:#0000FF;' href="<?php echo $client_dash_nm; ?>?ID=<?php echo $_REQUEST["ID"]; ?>">
                    <font color="blue"><?php echo $client_dash_displaynm; ?></font>
                </a>
            <?php
            } else {
            ?>
                <a style='color:#0000FF;' href="<?php echo $client_dash_nm; ?>?ID=<?php echo $_REQUEST["ID"]; ?>">
                    <font color="blue"><?php echo $client_dash_displaynm1; ?></font>
                </a>
            <?php     }
        }

        if ($haveNeed_flg == "Have Boxes") {
            $client_dash_nm = "water_supplierdashboard_setup.php";
            $client_dash_displaynm = "Setup SUPPLIER Dashboard";
            $client_dash_displaynm1 = "Manage SUPPLIER Dashboard";

            $loop_id_tmp = 0;
            db();
            $res = db_query("Select id from loop_warehouse where b2bid = " . $_REQUEST["ID"]);
            while ($fetch_data = array_shift($res)) {
                $loop_id_tmp = $fetch_data["id"];
            }

            $res = db_query("Select * from loop_dashboards where company_id = " . $loop_id_tmp);
            $rec_found = "no";
            $dash_link = "";
            while ($fetch_data = array_shift($res)) {
                $rec_found = "yes";
                $dash_link = $fetch_data["webaddress"];
            }

            $res = db_query("Select user_name from supplier_dash_usermaster where companyid = " . $_REQUEST["ID"]);
            $rec_found = "no";
            while ($fetch_data = array_shift($res)) {
                $rec_found = "yes";
            }

            if ($rec_found == "no") {
            ?>
                <a style='color:#0000FF;' href="supplierdashboard_setup_new.php?ID=<?php echo $_REQUEST["ID"]; ?>">
                    <font color="blue">Setup Client Dashboard</font>
                </a>
            <?php
            } else {
            ?>
                <a style='color:#0000FF;' href="supplierdashboard_setup_new.php?ID=<?php echo $_REQUEST["ID"]; ?>">
                    <font color="blue">Manage Client Dashboard</font>
                </a>
            <?php     } ?>

            &nbsp;&nbsp;<a style='color:#0000FF;'
                href="viewCompany-purchasing.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=freightsetup&rec_type=<?php echo $rec_type; ?>">
                <font color="blue">Inbound Freight Setup</font>
            </a>
        <?php      }

        db();
        $resGetData = db_query("SELECT loop_transaction_buyer.id AS I, loop_transaction_buyer.warehouse_id from loop_transaction_buyer_order_issue INNER JOIN loop_transaction_buyer ON loop_transaction_buyer_order_issue.trans_id = loop_transaction_buyer.id INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_transaction_buyer.ignore = 0 AND loop_transaction_buyer.virtual_inventory_company_id = '" . $loopidfromcomp . "' order by order_issue_start_date_time");

        $numRowsGetData = tep_db_num_rows($resGetData);
        $rowsGetData = array_shift($resGetData);
        $numRowsSupplierDtls = 0;

        ?>
        &nbsp;&nbsp;<a style='color:#0000FF;'
            href="report_search_order_issue.php?purchasing_warehouse_id=<?php echo $loopidfromcomp; ?>" target="_blank">
            <font color="blue">Order Issue History (<?php echo $numRowsGetData; ?>)</font>
        </a>

        <?php
        db_b2b();
        $opp_rec = "(0)";
        $opp_sql = db_query("SELECT count(opp_id) as oppcnt FROM opportunity_master WHERE opp_companyid =" . $_REQUEST['ID'] . " AND opp_status != 9");
        $opp_res = array_shift($opp_sql);
        $opp_rec = "(" . $opp_res['oppcnt'] . ")";

        //Show count of only active campaign records
        $camp_num = "(0)";
        $camp_sql = db_query("SELECT count(id) as campcnt FROM campaign_master WHERE camp_companyid =" . $_REQUEST['ID'] . " AND camp_status = 1");
        $camp_res = array_shift($camp_sql);
        $camp_rec = "(" . $camp_res['campcnt'] . ")";

        ?>

        &nbsp;&nbsp;<a style="color:#0000FF;"
            href="viewCompany-purchasing.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=Opportunity&rec_type=<?php echo $rec_type; ?>">Opportunities
            <?php echo $opp_rec; ?></a> &nbsp;&nbsp;

        &nbsp;&nbsp;<a style="color:#0000FF;"
            href="viewCompany-purchasing.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=campaign&rec_type=<?php echo $rec_type; ?>">Campaign
            <?php echo $camp_rec; ?></a> &nbsp;&nbsp;
        <br><br>
        <?php
        if ($_GET["rec_type"] == 'Supplier' || $_GET["rec_type"] == '') {
        ?>
            <table width="100%" border="0">
                <tr>
                    <td valign="top">
                        <iframe frameborder="0" onload="iframemainCompLoaded()" scrolling="auto" style="border: none"
                            height="450" class="show_iframe_compinfo" id="show_compinfo"
                            src="viewComp_info.php?id=<?php echo $loopid; ?>&ID=<?php echo $b2bid; ?>&status=<?php echo $status; ?>"></iframe>
                    </td>
                    <td valign="top" width="12"><br></td>
                    <td valign="top" width="400">&nbsp;</td>
                </tr>
            </table>
        <?php  } ?>

        <?php
        if ($_GET["rec_type"] == 'Manufacturer') {
        ?>
            <table width="100%" border="0">
                <tr>
                    <td>
                        <iframe frameborder="0" onload="iframemainCompLoaded()" scrolling="auto" style="border: none"
                            height="400" class="show_iframe_compinfo" id="show_compinfo"
                            src="viewComp_info.php?id=<?php echo $loopid; ?>&ID=<?php echo $b2bid; ?>&status=<?php echo $status; ?>"></iframe>
                    </td>
                </tr>
            <?php
        } ?>
            </font>

            <script>
                function iframemainCompLoaded() {
                    ifrmaeobj = document.getElementById("show_compinfo");
                    var objheight = ifrmaeobj.contentWindow.document.body.offsetHeight;
                    objheight = objheight + 50;
                    ifrmaeobj.style.height = objheight + 'px';
                }
            </script>

            </td>
            </tr>
            </table>
            <style>
                .view_comp_arrange {
                    display: flex;
                    width: 100%;
                }

                .class_w100 {
                    width: 100%;
                }

                .class_w50 {
                    width: 50%;
                }

                .class_w0 {
                    width: 0%;
                }

                @media screen and (max-width: 992px) {
                    .view_comp_arrange {
                        display: block;
                        width: 100%;
                    }

                    .class_w50 {
                        width: 50%;
                    }
                }
            </style>
            <div class="view_comp_arrange">
                <?php
                $cls_sec1 = "class_w50";
                $cls_sec2 = "class_w50";

                if ($_REQUEST["show"] == "water-initiatives") {
                    $cls_sec1 = "class_w100";
                    $cls_sec2 = "class_w100";
                }

                if ($_REQUEST["show"] == "water-initiatives") {
                    $cls_sec1 = "class_w0";
                    $cls_sec2 = "class_w0";
                }

                if ($_REQUEST["show"] == "watersalesinvoices") {
                    $cls_sec1 = "class_w100";
                    $cls_sec2 = "class_w100";
                }

                if ($_REQUEST["show"] == "accounting") {
                    $cls_sec1 = "class_w50";
                    $cls_sec2 = "class_w50";
                }

                ?>
                <div class="section1 <?php echo $cls_sec1 ?>">
                    <?php if ($_REQUEST["show"] == "transactions") {
                        $showstyle = "display:none ";
                    }
                    if ($_REQUEST["show"] == "parentchild") {
                        require("viewCompany_parentchild_mysqli.php"); //Parent Child
                    } else {
                        if ($_REQUEST["show"] != "watertransactions" && $_REQUEST["show"] != "transactions" && $_REQUEST["show"] != "water-initiatives" && $_REQUEST["show"] != "recycling" && $_REQUEST["show"] != "accounting" && $_REQUEST["show"] != "freightsetup" && $_REQUEST["show"] != "watersalesinvoices" && $_REQUEST["show"] != "Opportunity" && $_REQUEST["show"] != "OpportunityTask"  && $_REQUEST["show"] != "campaign" && $_REQUEST["show"] != "campaignTask") {
                            //require ("viewCompany_func2_quoting.php"); //Quoting

                    ?>
                            <iframe frameborder="0" scrolling="auto" onload="resizeIframe(this)"
                                style="<?php echo $showstyle; ?>; border: none" height="1000" class="show_iframe" id="show_quoting"
                                src="viewCompany_func2_quoting.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=Quoting&rec_type=<?php echo $rec_type; ?>&status=<?php echo $status; ?>"></iframe>

                        <?php

                        }
                        if ($_REQUEST["show"] == "transactions") {
                        ?>
                            <iframe frameborder="0" scrolling="auto" onload="resizeIframeTrans(this)" style="border: none;"
                                id="show_trans"
                                src="viewCompany_func1_transaction.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=transactions&rec_type=<?php echo $rec_type; ?>&status=<?php echo $status; ?>&warehouse_id=<?php echo $id; ?>&id=<?php echo $id; ?>&rec_id=<?php echo $_REQUEST["rec_id"]; ?>&display=<?php echo $_REQUEST["display"]; ?>"></iframe>

                        <?php

                        }
                        if ($_REQUEST["show"] == "accounting") {

                            require("viewCompany_funcaccounting_mysqli.php"); //Accounting
                            //
                        }
                    }
                    if ($_REQUEST["show"] == "watertransactions") {

                        ?>
                        <iframe frameborder="0" scrolling="auto" onload="resizeIframe1(this)"
                            style="<?php echo $showstyle; ?>; border: none" height="1000" id="watertransactions"
                            src="viewCompany_func_water-mysqli.php?id=<?php echo $loopid; ?>&b2bid=<?php echo $b2bid; ?>&ID=<?php echo $_REQUEST['ID']; ?>&warehouse_id=<?php echo $id; ?>&searchcrit=&show=watertransactions&rec_type=<?php echo $rec_type; ?>&status=<?php echo $status; ?>&purchasing=yes"></iframe>
                    <?php

                    }
                    //
                    if ($_REQUEST["show"] == "watersalesinvoices") {
                        //include ("viewCompany_func_water-sales-invoices.php"); //Water sales invoices
                    ?>
                        <iframe frameborder="0" scrolling="auto" onload="resizeIframe1(this)"
                            style="<?php echo $showstyle; ?>; border: none" height="1000" width="1250" id="watersalesinvoices"
                            src="viewCompany_func_water-sales-invoices.php?id=<?php echo $loopid; ?>&b2bid=<?php echo $b2bid; ?>&ID=<?php echo $_REQUEST['ID']; ?>&warehouse_id=<?php echo $id; ?>&searchcrit=&show=watersalesinvoices&rec_type=<?php echo $rec_type; ?>&status=<?php echo $status; ?>&purchasing=yes"></iframe>
                    <?php
                        //require ("viewCompany_func_water-mysqli.php");
                    }
                    //

                    if ($_REQUEST["show"] == "water-initiatives") {
                    ?>
                        <iframe frameborder="0" scrolling="auto" style="width:1400px !important; border: none;" height="1000"
                            id="waterinitiatives"
                            src="viewCompany_func_water-initiatives.php?id=<?php echo $loopid; ?>&b2bid=<?php echo $b2bid; ?>&ID=<?php echo $_REQUEST['ID']; ?>&warehouse_id=<?php echo $id; ?>&searchcrit=&show=water-initiatives&rec_type=<?php echo $rec_type; ?>&status=<?php echo $status; ?>&purchasing=yes"></iframe>
                    <?php
                    }

                    if ($_REQUEST["show"] == "Opportunity") {
                    ?>
                        <iframe frameborder="0" scrolling="auto" onload="resizeIframe(this)"
                            style="<?php echo $showstyle; ?>; border: none" height="1000" class="show_iframe_compinfo"
                            id="opportunity"
                            src="viewCompany_opportunity.php?ID=<?php echo $_REQUEST['ID']; ?>&warehouse_id=<?php echo $id; ?>&searchcrit=&show=Opportunity&rec_type=<?php echo $rec_type; ?>"></iframe>
                    <?php
                    }
                    //OpportunityTask
                    if ($_REQUEST["show"] == "OpportunityTask") {
                    ?>
                        <iframe frameborder="0" scrolling="auto" onload="resizeIframe1(this)"
                            style="<?php echo $showstyle; ?>; border: none" height="1000" class="show_iframe_compinfo"
                            id="opportunityTask"
                            src="viewCompany_opportunityTask.php?ID=<?php echo $_REQUEST['ID']; ?>&warehouse_id=<?php echo $id; ?>&searchcrit=&show=OpportunityTask&rec_type=<?php echo $rec_type; ?>"></iframe>
                    <?php
                    }

                    if ($_REQUEST["show"] == "campaign") {
                    ?>
                        <iframe frameborder="0" scrolling="auto" onload="resizeIframe(this)"
                            style="<?php echo $showstyle; ?>; border: none" height="1000" class="show_iframe_compinfo"
                            id="campaign"
                            src="viewCompany_campaign.php?ID=<?php echo $_REQUEST['ID']; ?>&warehouse_id=<?php echo $id; ?>&searchcrit=&show=campaign&rec_type=<?php echo $rec_type; ?>"></iframe>
                    <?php
                    }
                    //campaignTask
                    if ($_REQUEST["show"] == "campaignTask") {
                    ?>
                        <iframe frameborder="0" scrolling="auto" onload="resizeIframe1(this)"
                            style="<?php echo $showstyle; ?>; border: none" height="1000" class="show_iframe_compinfo"
                            id="campaignTask"
                            src="campaignTask.php?ID=<?php echo $_REQUEST['ID']; ?>&warehouse_id=<?php echo $id; ?>&searchcrit=&show=campaignTask&rec_type=<?php echo $rec_type; ?>"></iframe>
                    <?php
                    }

                    if ($_REQUEST["show"] == "recycling") {
                    ?>
                        <iframe frameborder="0" scrolling="auto" onload="resizeIframe1(this)"
                            style="<?php echo $showstyle; ?>; border: none" height="1000" class="show_iframe"
                            id="show_recycling"
                            src="viewCompany_func_recycling_mysqli.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=recycling&rec_type=<?php echo $rec_type; ?>&status=<?php echo $status; ?>&purchasing=yes"></iframe>
                    <?php  }

                    if ($_REQUEST["show"] == "freightsetup") {
                    ?>
                        <iframe frameborder="0" scrolling="auto" onload="resizeIframe1(this)"
                            style="<?php echo $showstyle; ?>; border: none" height="1000" class="show_iframe"
                            id="show_freightsetup"
                            src="viewCompany_func_freightsetup_mysqli.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=freightsetup&rec_type=<?php echo $rec_type; ?>&status=<?php echo $status; ?>&purchasing=yes"></iframe>
                    <?php  } ?>
                </div>

                <?php if (($_REQUEST["show"] != "transactions") && ($_REQUEST["show"] != "watersalesinvoices")) { ?>
                    <div class="section2 <?php echo $cls_sec2 ?>">
                        <?php
                        if (($_REQUEST["show"] != "parentchild") && ($_REQUEST["show"] != "water-initiatives") && ($_REQUEST["show"] != "Opportunity") && ($_REQUEST["show"] != "OpportunityTask")  && ($_REQUEST["show"] != "campaign") && ($_REQUEST["show"] != "campaignTask")) {
                        ?>
                            <iframe frameborder="0" scrolling="auto" onload="resizeIframe(this)" style="border: none" height="1000"
                                id="show_crm" src="viewCompany-purchasing_func3.php?id=<?php echo $loopid; ?>&b2bid=<?php echo $b2bid; ?>&ID=<?php echo $_REQUEST['ID']; ?>&showorg=<?php echo $_REQUEST['show']; ?>&rec_type=<?php echo $_REQUEST['rec_type']; ?>&show=communications"></iframe>
                        <?php
                        }
                        //}  

                        $id = $loopid; //get_loop_id($_REQUEST["ID"]);


                        ?>
                    </div>
                <?php  } ?>

                <?php if ($_REQUEST["show"] == "watersalesinvoices") { ?>

                    <div class="section2">
                        <form method="POST" action="updateIntNotes_mrg_mysqli.php" id="intNotes" name="intNotes">
                            <table border="0" width="600" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="100%" id="msgNote">
                                        <input type=hidden name="id" value="<?php echo $_REQUEST["ID"]; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" align="left" bgcolor="#E4E4E4">
                                        <?php
                                        $int_notes = "";
                                        $assignedto = "";
                                        $chk_water_flg = 0;
                                        $ucbzw_account_owner = "";
                                        db_b2b();
                                        $qry_1 = "Select int_notes, assignedto, ucbzw_flg,ucbzw_account_owner from companyInfo Where ID = '" . $_REQUEST["ID"] . "'";
                                        $dt_view_1 = db_query($qry_1);
                                        while ($rows = array_shift($dt_view_1)) {
                                            $int_notes = $rows["int_notes"];
                                            $assignedto = $rows["assignedto"];
                                            $chk_water_flg = $rows["ucbzw_flg"];
                                            $ucbzw_account_owner = $rows["ucbzw_account_owner"];
                                        }

                                        $emp_nm = "";
                                        $qassign = "SELECT name FROM employees WHERE status='Active' and employeeID = '" . $assignedto . "'";
                                        $dt_view_res_assign = db_query($qassign);
                                        while ($res_assign = array_shift($dt_view_res_assign)) {
                                            $emp_nm = $res_assign["name"];
                                        }

                                        ?>
                                        <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b>UCB Account
                                                Owner:</b> <?php echo $emp_nm; ?></font><br>

                                        <?php if ($chk_water_flg == 1) {
                                            $emp_nm = "";
                                            $qassign = "SELECT name FROM employees WHERE status='Active' and employeeID = '" . $ucbzw_account_owner . "'";
                                            $dt_view_res_assign = db_query($qassign);
                                            while ($res_assign = array_shift($dt_view_res_assign)) {
                                                $emp_nm = $res_assign["name"];
                                            }
                                        ?>
                                            <br><br>
                                            <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b>UCBZeroWaste
                                                    Account Owner:</b> <?php echo $emp_nm; ?></font>

                                        <?php     } ?>
                                        <br>
                                        <hr style="color:white;">
                                        <b>Internal Notes </b>
                                        <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><br />
                                            (These notes apply to the account in general.)
                                        </font><br>
                                        <textarea rows="16" name="int_notes" cols="3"
                                            style="width:90%"><?php echo $int_notes ?></textarea> <br /><input
                                            style="cursor:pointer;" type="Submit" value="Update" name="B1">
                                        <?php
                                        if ($_REQUEST["noteadd"] == "yes") {
                                            echo "<font size='1' color='#ff0000'>Note updated successfully</font>";
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </form>
                        <br />

                    </div>

                <?php  } ?>
            </div>



    </div>
</body>

</html>