<?php
session_start();
//if(!session_is_registered(myusername)){
if (!$_COOKIE['userloggedin']) {
    header("location:login.php");
}

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";
// function timestamp_to_date(string $d): string
// {
//     $da = explode(" ", $d);
//     $dp = explode("-", $da[0]);
//     return $dp[1] . "/" . $dp[2] . "/" . $dp[0];
// }

?>
<html>

<head>
    <style>
    .style12_new {
        font-size: x-small;
        font-family: Arial, Helvetica, sans-serif;
    }
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <title>Inventory Availability Updater Tool</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/managebox-stl.css" />

    <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
    <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="tagfiles/solnew.css">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap"
        rel="stylesheet">
    <link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>

    <script language="JavaScript" SRC="inc/CalendarPopup.js"></script>
    <script language="JavaScript" SRC="inc/general.js"></script>
    <script language="JavaScript">
    document.write(getCalendarStyles());
    </script>
    <script language="JavaScript">
    var cal1xx = new CalendarPopup("listdiv");
    cal1xx.showNavigationDropdowns();

    var cal_load_available_date = new CalendarPopup("listdiv_load_available_date");
    cal_load_available_date.showNavigationDropdowns();
    </script>

    <div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
    </div>

    <script language="javascript">
    function chkcharacter(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode == 34) {
            alert("Double quotes are not allowed.");
            return false;
        } else {
            return true;
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

    //New Gaylord Matching tool ver 3
    function display_matching_tool_gaylords_v3(tmpcnt, id, boxid, flg, viewflg, client_flg, load_all = 0, onlyftl = 0) {
        if (document.getElementById('inventory_v3_preord_top_' + tmpcnt).style.display == 'table-row') {
            document.getElementById('inventory_v3_preord_top_' + tmpcnt).style.display = 'none';
        } else {
            document.getElementById('inventory_v3_preord_top_' + tmpcnt).style.display = 'table-row';
        }

        document.getElementById("divtool_gaylords_v3" + tmpcnt).innerHTML =
            "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("divtool_gaylords_v3" + tmpcnt).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "quote_request_gaylords_tool_v3.php?showonly_box=1&dashpg=dash&ID=" + id + "&boxid=" +
            boxid +
            "&gbox=0&sort_g_location=1&g_timing=6&onlyftl=&display-allrec=&display_view=1&sort_g_tool2=&load_all=client_flg=",
            true);
        xmlhttp.send();
    }


    function display_av_load_data(tmpcnt, loop_inv_id) {
        if (document.getElementById('inventory_gayloard_preord_top_' + tmpcnt).style.display == 'table-row') {
            document.getElementById('inventory_gayloard_preord_top_' + tmpcnt).style.display = 'none';
        } else {
            document.getElementById('inventory_gayloard_preord_top_' + tmpcnt).style.display = 'table-row';
        }

        document.getElementById("div_av_load_data" + tmpcnt).innerHTML =
            "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("div_av_load_data" + tmpcnt).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "manage_box_next_load_available_date.php?gaylordstatuspg=1&id=" + loop_inv_id, true);
        xmlhttp.send();
    }

    function display_preoder_sel(tmpcnt, reccnt, box_id, wid) {
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

        xmlhttp.open("GET", "gaylordstatus_childtable.php?box_id=" + box_id + "&wid=" + wid + "&tmpcnt=" + tmpcnt,
            true);
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

    function displaytrans_log(cnt, compid) {
        document.getElementById("light").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
        document.getElementById('light').style.display = 'block';

        selectobject = document.getElementById("translog" + cnt);
        n_left = f_getPosition(selectobject, 'Left');
        n_top = f_getPosition(selectobject, 'Top');

        document.getElementById('light').style.left = (n_left + 50) + 'px';
        document.getElementById('light').style.top = n_top + 'px';

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>" +
                    xmlhttp.responseText;


            }
        }

        xmlhttp.open("GET", "display_inventory_notes.php?compid=" + compid, true);
        xmlhttp.send();
    }

    function update_details(cnt, comm_log_flg, box_type) {
        var add_para = "";
        var flyer_note_exist = document.getElementById('flyer_notes' + cnt);
        if (flyer_note_exist) {
            var flyer_notes = document.getElementById('flyer_notes' + cnt).value;
            add_para += '&flyer_notes=' + flyer_notes + '&box_type=' + box_type;
        }
        var after_actual_inventory_exist = document.getElementById('after_actual_inventory' + cnt);
        if (after_actual_inventory_exist) {
            var after_actual_inventory = document.getElementById('after_actual_inventory' + cnt).value;
            add_para += '&after_actual_inventory=' + after_actual_inventory;
        }
        var next_load_date_exist = document.getElementById('next_load_date' + cnt);
        if (next_load_date_exist) {
            var next_load_date = document.getElementById('next_load_date' + cnt).value;
            add_para += '&next_load_date=' + next_load_date + '&box_type=not_gaylords';
        }
        var box_id = document.getElementById('box_id' + cnt).value;
        var expected_loads_per_mo = document.getElementById('expected_loads_per_mo' + cnt).value;
        //var availability = document.getElementById('availability'+ cnt).value;
        var note = document.getElementById('note' + cnt).value;
        var b2b_status = document.getElementById('b2b_status' + cnt).value;

        var compid = 0;
        var typeid = "";
        var txtmessage = "";
        var txtemployee = "";
        var comm_upd = "";

        //if (comm_log_flg == 1){
        comm_upd = "1";
        var compid = document.getElementById('companyID' + cnt).value;
        var typeid = document.getElementById('type' + cnt).value;
        var txtmessage = document.getElementById('message' + cnt).value;
        var txtemployee = document.getElementById('employee' + cnt).value;
        //}else{
        //	comm_upd = "2";	
        //}

        //alert(selectedText);
        //alert(document.getElementById("gaylord_div" + cnt).innerHTML);
        document.getElementById("gaylord_div" + cnt).innerHTML =
            "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("gaylord_div" + cnt).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "add_gaylorddetails_upd_new.php?comm_upd=" + comm_upd + "&compid=" + compid + "&typeid=" +
            typeid + "&txtmessage=" + txtmessage + "&txtemployee=" + txtemployee + "&cnt=" + cnt + "&box_id=" +
            box_id + "&expected_loads_per_mo=" + expected_loads_per_mo + "&note=" + note + "&b2b_status=" +
            b2b_status + add_para, true);
        xmlhttp.send();
    }

    function displayemail(id, cnt) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                selectobject = document.getElementById("employee" + cnt);
                n_left = f_getPosition(selectobject, 'Left');
                n_top = f_getPosition(selectobject, 'Top');

                document.getElementById("light").innerHTML = xmlhttp.responseText;
                document.getElementById('light').style.display = 'block';

                document.getElementById('light').style.left = (n_left - 500) + 'px';
                document.getElementById('light').style.top = n_top - 40 + 'px';
            }
        }

        xmlhttp.open("GET", "get_crm_data.php?comp_b2bid=" + id, true);
        xmlhttp.send();
    }

    function displayboxdata(colid, sortflg, box_type_cnt) {
        document.getElementById("btype" + box_type_cnt).innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
        var box_type = document.getElementById("box_type").value;
        var owner_dd = document.getElementById("owner_dd").value;

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("btype" + box_type_cnt).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "gaylord_status_sort_data.php?colid=" + colid + "&sortflg=" + sortflg + "&box_type=" +
            box_type + "&owner_dd=" + owner_dd + "&box_type_cnt=" + box_type_cnt, true);
        xmlhttp.send();
    }
    </script>

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

    .search input {
        height: 24px !important;
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
    </style>

</head>

<body>
    <div id="light" class="white_content"></div>
    <div id="fade" class="black_overlay"></div>

    <?php

    $box_type_str_arr = array();
    $sort_order_pre = "ASC";
    if ($_GET['sort_order_pre'] == "ASC") {
        $sort_order_pre = "DESC";
    } else {
        $sort_order_pre = "ASC";
    }
    ?>

    <?php include("inc/header.php"); ?>
    <div class="main_data_css">
        <div class="dashboard_heading" style="float: left;">
            <div style="float: left;">Inventory Availability Updater Tool</div>
            &nbsp;<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                <span class="tooltiptext">The report allows the user to see all inventory items they own the
                    relationship of (direct ship only though), and allows them to edit the availability of each box.
                    This information will populate the sales matching tools for quoting research.</span>
            </div>
            <div style="height: 13px;">&nbsp;</div>
        </div>

        <a href="gaylordstatus_old.php">View Old Version</a>
        <br><br>

        <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="GET" class="display_title">
            <table>
                <tr>
                    <td class="new_text">
                        <strong>Select Relationship Owner:</strong>
                    </td>
                    <td class="new_text">

                        <select name="owner_dd" id="owner_dd">
                            <option selected value="all" <?php if (
                                                                isset($_REQUEST["owner_dd"]) &&
                                                                $_REQUEST["owner_dd"] == "all"
                                                            ) {
                                                                echo "selected";
                                                            } ?>>All</option>
                            <option value="0" <?php if (isset($_REQUEST["owner_dd"]) && $_REQUEST["owner_dd"] == "0") {
                                                    echo "selected";
                                                } ?>>Operations</option>
                            <?php

                            db_b2b();
                            $invqry = db_query("SELECT employeeID, loopID, employees.name from inventory inner join employees on employees.employeeID = inventory.supplier_owner where inventory.Active LIKE 'A' group by loopID order by employees.name ");
                            while ($inv_row = array_shift($invqry)) {
                            ?>
                            <option value="<?php echo $inv_row["employeeID"]; ?>" <?php if (
                                                                                            isset($_REQUEST["owner_dd"])
                                                                                            && $_REQUEST["owner_dd"] == $inv_row["employeeID"]
                                                                                        ) {
                                                                                            echo "selected";
                                                                                        } ?>>
                                <?php echo $inv_row["name"] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td class="new_text">
                        <strong>Type:</strong>
                    </td>
                    <td class="new_text">
                        <select class="form_component form_select_dd" name="box_type" id="box_type">
                            <option value="" <?php if ($_REQUEST["box_type"] == "") echo " selected "; ?>>All</option>
                            <option value="Gaylords"
                                <?php if ($_REQUEST["box_type"] == "Gaylords") echo " selected "; ?>>Gaylords</option>
                            <option value="Shipping boxes" <?php if ($_REQUEST["box_type"] == "Shipping boxes")
                                                                echo " selected "; ?>>Shipping boxes</option>
                            <option value="Pallets" <?php if ($_REQUEST["box_type"] == "Pallets") echo " selected "; ?>>
                                Pallets</option>
                            <option value="Supersacks" <?php if ($_REQUEST["box_type"] == "Supersacks") echo " selected ";
                                                        ?>>Supersacks</option>
                            <option value="Other" <?php if ($_REQUEST["box_type"] == "Other") echo " selected "; ?>>
                                Other
                            </option>
                        </select>
                    </td>
                    <td class="new_text">
                        <input type="submit" name="run_rep_btn" id="run_rep_btn" value="Submit">
                    </td>
                </tr>
            </table>
        </form>

        <div><i>Note: Please wait until you see <font color="red">"END OF REPORT"</font> at the bottom of the report,
                before using the sort option.</i></div>
        <br>

        <?php

        if (isset($_REQUEST["run_rep_btn"])) {
            //Sort code
            //
            $sorturl = "gaylordstatus.php?owner_dd=" . $_REQUEST["owner_dd"] . "&box_type=" . $_REQUEST["box_type"] . "&run_rep_btn=" . $_REQUEST["run_rep_btn"];

        ?>
        <form method="POST" name="frm_main">

            <?php

                $box_type_display = $_REQUEST["box_type"];

                $box_type_str = "";
                if ($_REQUEST["box_type"] == "Gaylords") {
                    $box_type_str = "inventory.box_type in ('Gaylord','GaylordUCB','Loop','PresoldGaylord') and ";
                    $box_type_str_arr = array("'Gaylord','GaylordUCB', 'PresoldGaylord', 'Loop'");
                }
                if ($_REQUEST["box_type"] == "Shipping boxes") {
                    $box_type_str = "inventory.box_type in ('LoopShipping','Box','Boxnonucb','Presold','Medium','Large','Xlarge') and ";
                    $box_type_str_arr = array("'LoopShipping','Box','Boxnonucb','Presold','Medium','Large','Xlarge'");
                }
                if ($_REQUEST["box_type"] == "Supersacks") {
                    $box_type_str = "inventory.box_type in ('SupersackUCB','SupersacknonUCB') and ";
                    $box_type_str_arr = array("'SupersackUCB','SupersacknonUCB'");
                }
                if ($_REQUEST["box_type"] == "Pallets") {
                    $box_type_str = "inventory.box_type in ('PalletsUCB','PalletsnonUCB') and ";
                    $box_type_str_arr = array("'PalletsUCB','PalletsnonUCB'");
                }
                if ($_REQUEST["box_type"] == "Other") {
                    $box_type_str = "inventory.box_type in ('DrumBarrelUCB','DrumBarrelnonUCB','Recycling','Other') and ";
                    $box_type_str_arr = array("'DrumBarrelUCB','DrumBarrelnonUCB','Recycling','Other'");
                }
                if ($_REQUEST["box_type"] == "") {
                    $box_type_str = "";
                    $box_type_str_arr = array("'Gaylord','GaylordUCB', 'PresoldGaylord', 'Loop'", "'LoopShipping','Box','Boxnonucb','Presold','Medium','Large','Xlarge','Boxnonucb'", "'PalletsUCB','PalletsnonUCB'", "'SupersackUCB','SupersacknonUCB'", "'DrumBarrelUCB','DrumBarrelnonUCB','Recycling','Other'");
                }
                // print_r($box_type_str_arr);
                //
                $box_type_cnt = 0;
                $srno = 0;
                foreach ($box_type_str_arr as $box_type_str_arr_tmp) {

                    if ($_REQUEST["box_type"] == "") {
                        $box_type_cnt = $box_type_cnt + 1;
                        //
                        if ($box_type_cnt == 1) {
                            $box_type = "Gaylord";
                        }
                        if ($box_type_cnt == 2) {
                            $box_type = "Shipping Boxes";
                        }
                        if ($box_type_cnt == 3) {
                            $box_type = "Pallets";
                        }
                        if ($box_type_cnt == 4) {
                            $box_type = "Supersacks";
                        }
                        if ($box_type_cnt == 5) {
                            $box_type = "Other";
                        }
                    } else {
                        $box_type = $_REQUEST["box_type"];
                        if ($box_type == "Gaylords") {
                            $box_type_cnt = 1;
                        }
                        if ($box_type == "Shipping boxes") {
                            $box_type_cnt = 2;
                        }
                        if ($box_type == "Pallets") {
                            $box_type_cnt = 3;
                        }
                        if ($box_type == "Supersacks") {
                            $box_type_cnt = 4;
                        }
                        if ($box_type == "Other") {
                            $box_type_cnt = 5;
                        }
                    }
                    //
                    if ((isset($_REQUEST["owner_dd"]) && $_REQUEST["owner_dd"] == "all") || (!isset($_REQUEST["owner_dd"]))) {
                        $dt_view_qry = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE box_type in (" . $box_type_str_arr_tmp . ") and inventory.Active LIKE 'A' ORDER BY inventory.availability DESC";
                    } else {
                        //$dt_view_qry = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE box_type in (" . $box_type_str_arr_tmp . ") and inventory.Active LIKE 'A' and inventory.owner=".$_REQUEST["owner_dd"]." ORDER BY inventory.availability DESC";
                        $dt_view_qry = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE box_type in (" . $box_type_str_arr_tmp . ") and inventory.Active LIKE 'A' and inventory.supplier_owner =" . $_REQUEST["owner_dd"] . " ORDER BY inventory.availability DESC";
                    }
                    //echo $dt_view_qry."<br>";
                    db_b2b();
                    $dt_view_res = db_query($dt_view_qry);
                    if (tep_db_num_rows($dt_view_res) > 0) {
                        while ($inv = array_shift($dt_view_res)) {

                            //
                            $srno = $srno + 1;
                            //

                            $sales_order_qty = 0;
                            $transaction_date = "";

                            $dt_so = "SELECT loop_transaction_buyer.transaction_date, loop_salesorders.qty AS sumqty FROM loop_salesorders ";
                            $dt_so .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
                            $dt_so .= " WHERE loop_transaction_buyer.shipped = 0 and loop_salesorders.box_id = " . $inv["loops_id"] . " order by transaction_date desc limit 1";
                            db();
                            $dt_res_so_item = db_query($dt_so);
                            while ($so_item_row = array_shift($dt_res_so_item)) {
                                $transaction_date = $so_item_row["transaction_date"];
                                if ($so_item_row["sumqty"] > 0) {
                                    $sales_order_qty = $so_item_row["sumqty"];
                                }
                            }

                            //
                            //
                            $b2b_ulineDollar = round($inv["ulineDollar"]);
                            $b2b_ulineCents = $inv["ulineCents"];
                            $b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
                            $b2b_fob = "$" . number_format($b2b_fob, 2);

                            $b2b_costDollar = round($inv["costDollar"]);
                            $b2b_costCents = $inv["costCents"];
                            $b2b_cost = $b2b_costDollar + $b2b_costCents;
                            $b2b_cost = "$" . number_format($b2b_cost, 2);
                            //
                            $b2b_status = "";
                            db();
                            $dt_res_so_item1 = db_query("select * from b2b_box_status where status_key='" . $inv["b2b_status"] . "'");
                            while ($so_item_row1 = array_shift($dt_res_so_item1)) {
                                $b2b_status = $so_item_row1["box_status"];
                            }
                            //
                            $vendor_name = "";

                            $supplier_owner_name = "";
                            $comqry1 = "select employeeID,employees.name as empname, employees.initials from employees where employeeID='" . $inv["supplier_owner"] . "'";
                            db_b2b();
                            $comres1 = db_query($comqry1);
                            while ($comrow1 = array_shift($comres1)) {
                                $supplier_owner_name = $comrow1["initials"];
                            }


                            $next_load_available_date = $inv["next_load_available_date"];
                            $next_load_available_date_display = "";
                            if ($next_load_available_date != "" && $next_load_available_date != "0000-00-00") {
                                $next_load_available_date_display = date("m/d/Y", strtotime($inv["next_load_available_date"]));
                            }

                            //
                            $qry_sku = "select * from loop_boxes where id=" . $inv["loops_id"];
                            $sku = "";
                            $after_po = 0;
                            $next_load_available_date = "";
                            $txt_after_po = "";
                            $expected_loads_per_mo = "";
                            $b2b_status = "";
                            $bpic_1 = "";
                            $flyer_notes = "";
                            db();
                            $dt_view_sku = db_query($qry_sku);
                            while ($sku_val = array_shift($dt_view_sku)) {
                                $loop_id = $sku_val['id'];
                                $boxes_pertrailer = $sku_val['boxes_per_trailer'];
                                $warehouse_id = $sku_val["box_warehouse_id"];
                                $after_po = $sku_val["after_po"];
                                $next_load_available_date = $sku_val["next_load_available_date"];
                                $lead_time = $sku_val["lead_time"];
                                $txt_after_po = $after_po;
                                $boxes_per_trailer = $sku_val["boxes_per_trailer"];
                                $expected_loads_per_mo = $sku_val["expected_loads_per_mo"];
                                $b2b_status = $sku_val["b2b_status"];
                                $flyer_notes = $sku_val['flyer_notes'];
                                $no_pic = 0;
                                if ($sku_val["bpic_1"] != "") {
                                    $no_pic = $no_pic + 1;
                                }
                                if ($sku_val["bpic_2"] != "") {
                                    $no_pic = $no_pic + 1;
                                }
                                if ($sku_val["bpic_3"] != "") {
                                    $no_pic = $no_pic + 1;
                                }
                                if ($sku_val["bpic_4"] != "") {
                                    $no_pic = $no_pic + 1;
                                }
                                if ($no_pic < 4) {
                                    $pic_color = "red";
                                } else {
                                    $pic_color = "#000";
                                }
                            }

                            //
                            $rec_found_box = "n";
                            $after_po_val_tmp = 0;
                            $dt_view_qry2 = "SELECT * from tmp_inventory_list_set2 where trans_id = " . $inv["loops_id"] . " order by warehouse, type_ofbox, Description";
                            db_b2b();
                            $dt_view_res_box = db_query($dt_view_qry2);
                            while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
                                $rec_found_box = "y";
                                $after_po_val_tmp = $dt_view_res_box_data["afterpo"];
                            }
                            //
                            if (isset($warehouse_id) == 238) {
                                $txt_after_po = $after_po;
                            } else {
                                if ($rec_found_box == "n") {
                                    $txt_after_po = $after_po;
                                } else {
                                    $txt_after_po = $after_po_val_tmp;
                                }
                            }

                            $order_col_color = "<font size=1>";
                            //echo $count . " cond 1 - " . $transaction_date . " " . $next_load_available_date . "<br>";
                            if ($transaction_date != "" && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00")) {
                                if (strtotime($transaction_date) < strtotime($next_load_available_date)) {
                                    $order_col_color = "<font color=red>";
                                }
                            }
                            $load_date_col_color = "style='font-size:10pt;'";
                            $load_date_col_color2 = "";
                            //echo $count . " cond 2 - " . $inv["b2b_status"] . " " . $next_load_available_date . "<br>";
                            if (($inv["b2b_status"] == '1.0' || $inv["b2b_status"] == '1.1' || $inv["b2b_status"] == '1.2') && ($next_load_available_date == "" || $next_load_available_date == "0000-00-00")) {
                                $load_date_col_color = "style='font-size:10pt;width:50px;background-color:#e8acac;'";
                                $load_date_col_color2 = "</span>";
                            }
                            //
                            $notes_date_col_color = "<font size=1>";
                            if ($inv["DT"] != "") {
                                $todays_dt = date('m/d/Y');
                                $notes_date_day = dateDiff($todays_dt, date('m/d/Y', strtotime($inv["DT"])));
                                //echo $count . " cond 3 - " . $todays_dt . " " . $inv["DT"] . " " . $notes_date_day . "<br>";
                                if ($notes_date_day <= 7) {
                                    $notes_date_col_color = "<font color=green>";
                                }
                                if ($notes_date_day > 7 && $notes_date_day <= 14) {
                                    $notes_date_col_color = "<font color=#FF9933>";
                                }
                                if ($notes_date_day > 14) {
                                    $notes_date_col_color = "<font color=red>";
                                }
                            }

                            $estimated_next_load = "";
                            //Buy Now, Load Can Ship In
                            if (isset($warehouse_id) == 238 && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00")) {
                                $now_date = time(); // or your date as well
                                $next_load_date = strtotime($next_load_available_date);
                                $datediff = $next_load_date - $now_date;
                                $no_of_loaddays = round($datediff / (60 * 60 * 24));

                                if ($no_of_loaddays < $lead_time) {
                                    if ($lead_time > 1) {
                                        //$estimated_next_load= "<font color=green>" . $lead_time . " Days</font>";
                                        $buynowshipin = $lead_time . "  Days";
                                    } else {
                                        //$estimated_next_load= "<font color=green>" . $lead_time . " Day</font>";
                                        $buynowshipin = $lead_time . "  Day";
                                    }
                                } else {
                                    if ($no_of_loaddays == -0) {
                                        //$estimated_next_load= "<font color=green>0 Day</font>";
                                        $buynowshipin = "0 Day";
                                    } else {
                                        //$estimated_next_load= "<font color=green>" . $no_of_loaddays . " Days</font>";
                                        $buynowshipin = $no_of_loaddays . " Day";
                                    }
                                }
                            } else {
                                if ($txt_after_po >= $boxes_per_trailer) {
                                    //=IF(B4>0,"NOW",ROUNDUP(((((B4/R4)*-1)+1)/D4)*4,0))

                                    if ($lead_time == 0) {
                                        //$estimated_next_load= "<font color=green>Now</font>";
                                        $buynowshipin = "Now";
                                    }

                                    if ($lead_time == 1) {
                                        //$estimated_next_load= "<font color=green>" . $lead_time . " Day</font>";
                                        $buynowshipin = $lead_time . " Day";
                                    }
                                    if ($lead_time > 1) {
                                        //$estimated_next_load= "<font color=green>" . $lead_time . " Days</font>";
                                        $buynowshipin = $lead_time . " Days";
                                    }
                                } else {
                                    if (($expected_loads_per_mo <= 0) && ($txt_after_po < $boxes_per_trailer)) {
                                        //$estimated_next_load= "<font color=red>Never (sell the " . $txt_after_po . ")</font>";
                                        $buynowshipin = "Never (sell the " . $txt_after_po . ")";
                                    } else {
                                        //$estimated_next_load=ceil((((($txt_after_po/$boxes_per_trailer)*-1)+1)/$expected_loads_per_mo)*4)." Weeks";
                                        $buynowshipin = ceil((((($txt_after_po / $boxes_per_trailer) * -1) + 1) / $expected_loads_per_mo) * 4) . " Weeks";
                                    }
                                }
                            }

                            if ($txt_after_po == 0 && $expected_loads_per_mo == 0) {
                                //$estimated_next_load= "<font color=red>Ask Purch Rep</font>";
                            }

                            $b2bstatuscolor = "";
                            $st_query = "select * from b2b_box_status where status_key='" . $b2b_status . "'";
                            db();
                            $st_res = db_query($st_query);
                            $st_row = array_shift($st_res);
                            $b2bstatus_name = $st_row["box_status"];
                            if ($st_row["status_key"] == "1.0" || $st_row["status_key"] == "1.1" || $st_row["status_key"] == "1.2") {
                                $b2bstatuscolor = "green";
                            } elseif ($st_row["status_key"] == "2.0" || $st_row["status_key"] == "2.1" || $st_row["status_key"] == "2.2") {
                                $b2bstatuscolor = "orange";
                                //$estimated_next_load= "<font color=red> Ask Purch Rep </font>";
                            }
                            $warehouse_id = $warehouse_id ?? '';
                            $lead_time = $lead_time ?? '';
                            $boxes_per_trailer = $boxes_per_trailer ?? '';


                            // $getEstimatedNextLoad = getEstimatedNextLoad($inv["loops_id"], $warehouse_id, $next_load_available_date, $lead_time, $lead_time, $txt_after_po, $boxes_per_trailer, $expected_loads_per_mo, $st_row["status_key"], 'yes');
                            // Ensure $boxes_per_trailer is an integer
                            // Ensure $boxes_per_trailer is an integer
                            // Ensure $boxes_per_trailer is an integer
                            $boxes_per_trailer = intval($boxes_per_trailer);

                            // Ensure $warehouse_id is an integer or set a default if it's null
                            $warehouse_id = is_numeric($warehouse_id) ? (int) $warehouse_id : 0;

                            // Ensure $expected_loads_per_mo is an integer or set a default if it's null or empty string
                            $expected_loads_per_mo = is_numeric($expected_loads_per_mo) ? (int) $expected_loads_per_mo : 0;

                            // Call the function with corrected arguments
                            $getEstimatedNextLoad = getEstimatedNextLoad(
                                $inv["loops_id"],
                                $warehouse_id,
                                $next_load_available_date,
                                $lead_time,
                                $lead_time,
                                $txt_after_po,
                                $boxes_per_trailer,
                                $expected_loads_per_mo,
                                $st_row["status_key"],
                                'yes'
                            );




                            $estimated_next_load = $getEstimatedNextLoad;

                            //
                            $contact_nm = "";
                            $comm_log_color = "<font size=1>";
                            if ($inv["vendor_b2b_rescue"] > 0) {
                                $comp_nm = "";
                                $comp_b2bid = "";
                                $q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = " . $inv["vendor_b2b_rescue"];
                                db();
                                $query = db_query($q1);
                                while ($fetch = array_shift($query)) {
                                    $comp_nm = $fetch['company_name'];
                                    $comp_b2bid = $fetch['b2bid'];
                                }

                                $comp_nm = isset($comp_nm) ? $comp_nm : '';

                                // Ensure $comp_b2bid is an integer or null
                                $comp_b2bid = is_numeric($comp_b2bid) ? (int) $comp_b2bid : null;

                                // Call the function with corrected arguments
                                $comp_nm = get_nickname_val($comp_nm, $comp_b2bid);
                                $warehouse = $comp_nm;

                                $last_contact_date = "";
                                $cdate = "";
                                $q1 = "SELECT contact, phone, email, last_contact_date FROM companyInfo where ID = '" . $comp_b2bid . "'";
                                db_b2b();
                                $query = db_query($q1);
                                while ($fetch = array_shift($query)) {
                                    $contact_nm = $fetch['contact'] . "<br>" . $fetch['phone'] . "<br>" . $fetch['email'];
                                    if ($fetch["last_contact_date"] != "") {
                                        $last_contact_date = date("m/d/Y", strtotime($fetch["last_contact_date"]));
                                        $cdate = $fetch["last_contact_date"];
                                    }
                                }

                                if ($cdate != "" || $cdate != "NULL") {
                                    $todays_dt = date('m/d/Y');
                                    $comm_log_date_day = dateDiff($todays_dt, $last_contact_date);
                                    //echo $count . " cond 4 - " . $todays_dt . " " . $last_contact_date . " " . $comm_log_date_day . "<br>";
                                    if ($comm_log_date_day <= 30) {
                                        $comm_log_color = "<font color=green size=1>";
                                    }
                                    if ($comm_log_date_day > 30 && $comm_log_date_day <= 90) {
                                        $comm_log_color = "<font color=#FF9933 size=1>";
                                    }
                                    if ($comm_log_date_day > 90) {
                                        $comm_log_color = "<font color=red size=1>";
                                    }
                                }
                            } else {

                                $vendor_b2b_rescue = $inv["V"];
                                $q1 = "SELECT Name FROM vendors where id = '$vendor_b2b_rescue'";
                                db_b2b();
                                $query = db_query($q1);
                                while ($fetch = array_shift($query)) {
                                    $vendor_name = $fetch["Name"];
                                }

                                $warehouse = $vendor_name;
                            }

                            $no_of_loads = 0;
                            $total_no_of_loads = 0;
                            $dt_view_qry2 = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_b2b_id = " . $inv["I"] . " and inactive_delete_flg = 0 order by load_available_date";
                            db();
                            $dt_view_res_box = db_query($dt_view_qry2);
                            while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
                                if ($dt_view_res_box_data["trans_rec_id"] == 0) {
                                    $no_of_loads = $no_of_loads + 1;
                                }
                                $total_no_of_loads = $total_no_of_loads + 1;
                            }

                            $box_type = $_REQUEST["box_type"];

                            if ($box_type_cnt == 1) {
                                $gy[] = array(
                                    'count' => $srno, 'I' => $inv["I"], 'box_type' => $box_type, 'no_of_loads' => $no_of_loads, 'total_no_of_loads' => $total_no_of_loads, 'contact_nm' => $contact_nm, 'vendor_b2b_rescue' => $inv["vendor_b2b_rescue"], 'after_actual_inventory' => $txt_after_po,
                                    'b2b_status' => $inv["b2b_status"], 'vendor_name' => $vendor_name, 'expected_loads_per_mo' => $inv["expected_loads_per_mo"],
                                    'warehouse' => $warehouse, 'loops_id' => $inv["loops_id"], 'V' => $inv["V"], 'description' => $inv["system_description"],
                                    'availability' => $inv["availability"], 'N' => $inv["N"], 'DT' => $inv["DT"], 'boxes_pertrailer' => $boxes_pertrailer, 'b2b_fob' => $b2b_fob, 'supplier_owner_name' => $supplier_owner_name,
                                    'next_load_available_date' => $next_load_available_date, 'next_load_available_date_display' => $next_load_available_date_display, 'buynowshipin' => $buynowshipin,
                                    'estimated_next_load' => $estimated_next_load, 'lead_time_for_first_load_can_ship' => $lead_time, 'load_date_col_color' => $load_date_col_color, 'sales_order_qty' => $sales_order_qty, 'no_pic' => $no_pic, 'pic_color' => $pic_color, 'flyer_notes' => $flyer_notes
                                );
                            }
                            //
                            if ($box_type_cnt == 2) {
                                $sb[] = array(
                                    'count' => $srno, 'I' => $inv["I"], 'box_type' => $box_type, 'no_of_loads' => $no_of_loads, 'total_no_of_loads' => $total_no_of_loads, 'contact_nm' => $contact_nm, 'vendor_b2b_rescue' => $inv["vendor_b2b_rescue"], 'after_actual_inventory' => $txt_after_po,
                                    'b2b_status' => $inv["b2b_status"], 'vendor_name' => $vendor_name, 'expected_loads_per_mo' => $inv["expected_loads_per_mo"],  'warehouse' => $warehouse, 'loops_id' => $inv["loops_id"], 'V' => $inv["V"], 'description' => $inv["system_description"],
                                    'availability' => $inv["availability"], 'N' => $inv["N"], 'DT' => $inv["DT"], 'boxes_pertrailer' => $boxes_pertrailer, 'b2b_fob' => $b2b_fob, 'supplier_owner_name' => $supplier_owner_name, 'next_load_available_date' => $next_load_available_date, 'next_load_available_date_display' => $next_load_available_date_display, 'buynowshipin' => $buynowshipin, 'estimated_next_load' => $estimated_next_load, 'load_date_col_color' => $load_date_col_color, 'sales_order_qty' => $sales_order_qty
                                );
                            }
                            //
                            if ($box_type_cnt == 3) {
                                $pal[] = array(
                                    'count' => $srno, 'I' => $inv["I"], 'box_type' => $box_type, 'no_of_loads' => $no_of_loads, 'total_no_of_loads' => $total_no_of_loads, 'contact_nm' => $contact_nm, 'vendor_b2b_rescue' => $inv["vendor_b2b_rescue"], 'after_actual_inventory' => $txt_after_po,
                                    'b2b_status' => $inv["b2b_status"], 'vendor_name' => $vendor_name, 'expected_loads_per_mo' => $inv["expected_loads_per_mo"], 'warehouse' => $warehouse, 'loops_id' => $inv["loops_id"], 'V' => $inv["V"], 'description' => $inv["system_description"],
                                    'availability' => $inv["availability"], 'N' => $inv["N"], 'DT' => $inv["DT"], 'boxes_pertrailer' => $boxes_pertrailer, 'b2b_fob' => $b2b_fob, 'supplier_owner_name' => $supplier_owner_name, 'next_load_available_date' => $next_load_available_date, 'next_load_available_date_display' => $next_load_available_date_display, 'buynowshipin' => $buynowshipin, 'estimated_next_load' => $estimated_next_load, 'load_date_col_color' => $load_date_col_color, 'sales_order_qty' => $sales_order_qty
                                );
                            }
                            //
                            if ($box_type_cnt == 4) {
                                $sup[] = array(
                                    'count' => $srno, 'I' => $inv["I"], 'box_type' => $box_type, 'no_of_loads' => $no_of_loads, 'total_no_of_loads' => $total_no_of_loads, 'contact_nm' => $contact_nm, 'vendor_b2b_rescue' => $inv["vendor_b2b_rescue"], 'after_actual_inventory' => $txt_after_po,
                                    'b2b_status' => $inv["b2b_status"], 'vendor_name' => $vendor_name, 'expected_loads_per_mo' => $inv["expected_loads_per_mo"],  'warehouse' => $warehouse, 'loops_id' => $inv["loops_id"], 'V' => $inv["V"], 'description' => $inv["system_description"],
                                    'availability' => $inv["availability"], 'N' => $inv["N"], 'DT' => $inv["DT"], 'boxes_pertrailer' => $boxes_pertrailer, 'b2b_fob' => $b2b_fob, 'supplier_owner_name' => $supplier_owner_name, 'next_load_available_date' => $next_load_available_date, 'next_load_available_date_display' => $next_load_available_date_display, 'buynowshipin' => $buynowshipin, 'estimated_next_load' => $estimated_next_load, 'load_date_col_color' => $load_date_col_color, 'sales_order_qty' => $sales_order_qty
                                );
                            }
                            if ($box_type_cnt == 5) {
                                $other[] = array(
                                    'count' => $srno, 'I' => $inv["I"], 'box_type' => $box_type, 'no_of_loads' => $no_of_loads, 'total_no_of_loads' => $total_no_of_loads, 'contact_nm' => $contact_nm, 'vendor_b2b_rescue' => $inv["vendor_b2b_rescue"], 'after_actual_inventory' => $txt_after_po,
                                    'b2b_status' => $inv["b2b_status"], 'vendor_name' => $vendor_name, 'expected_loads_per_mo' => $inv["expected_loads_per_mo"],  'warehouse' => $warehouse, 'loops_id' => $inv["loops_id"], 'V' => $inv["V"], 'description' => $inv["system_description"],
                                    'availability' => $inv["availability"], 'N' => $inv["N"], 'DT' => $inv["DT"], 'boxes_pertrailer' => $boxes_pertrailer, 'b2b_fob' => $b2b_fob, 'supplier_owner_name' => $supplier_owner_name, 'next_load_available_date' => $next_load_available_date, 'next_load_available_date_display' => $next_load_available_date_display, 'buynowshipin' => $buynowshipin, 'estimated_next_load' => $estimated_next_load, 'load_date_col_color' => $load_date_col_color, 'sales_order_qty' => $sales_order_qty
                                );
                            }
                            //
                            $_SESSION['sortarraygy'] = $gy;
                            $_SESSION['sortarraysb'] = $sb;
                            $_SESSION['sortarraysup'] = $sup;
                            $_SESSION['sortarraypal'] = $pal;
                            $_SESSION['sortarrayother'] = $other;
                            //	
                            //
                        } //End while
                    } //End if num rows
                } //End array
                //
                ?>
            <table cellSpacing="1" cellPadding="1" border="0" width="1400" class="sub_tables_s">
                <?php

                    $x = 0;
                    $boxtype_cnt = 0;
                    //$sorturl="dashboardnew.php?show=inventory_new&sort_g_view=".$sort_g_view."&sort_g_tool=".$sort_g_tool."&g_timing=".$g_timing;
                    $sorturl = "gaylordstatus_type.php?owner_dd=" . $_REQUEST["owner_dd"] . "&box_type=" . $_REQUEST["box_type"] . "&run_rep_btn=" . $_REQUEST["run_rep_btn"];
                    //
                    $srno = 0;
                    $box_name_arr = array('gy', 'sb', 'pal', 'sup', 'other');
                    foreach ($box_name_arr as $box_name) {
                        //
                        if ($box_name == "gy") {
                            $boxtype = "Gaylord";
                            $boxtype_cnt = 1;
                        }
                        if ($box_name == "sb") {
                            $boxtype = "Shipping Boxes";
                            $boxtype_cnt = 2;
                        }
                        if ($box_name == "pal") {
                            $boxtype = "Pallets";
                            $boxtype_cnt = 3;
                        }
                        if ($box_name == "sup") {
                            $boxtype = "Supersacks";
                            $boxtype_cnt = 4;
                        }
                        if ($box_name == "other") {
                            $boxtype = "Other";
                            $boxtype_cnt = 5;
                        }

                        //print_r($_SESSION['sortarraysup']);
                        //
                        $MGarray = $_SESSION['sortarray' . $box_name] ?? [];

                        $MGArraysort_I = array();

                        if (is_array($MGarray)) {
                            foreach ($MGarray as $MGArraytmp) {
                                $MGArraysort_I[] = $MGArraytmp['b2b_status'];
                            }

                            // Debugging to verify contents (optional)
                           /* echo '<pre>';
                            var_dump($MGArraysort_I);
                            var_dump($MGarray);
                            echo '</pre>';
                            */

                            // Perform array multisort
                            if (!empty($MGArraysort_I) && !empty($MGarray)) {
                                array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGarray);
                            }
                        } else {
                            // Initialize as an empty array if not an array
                            $MGarray = [];
                        }

                        // Print sorted array (optional)
                        // print_r($MGarray);

                        $total_rec_act = count($MGarray);

                        if ($total_rec_act > 0) {
                    ?>
                <tr align="middle">
                    <td colspan="16" class="section_heading" style="height: 16px"><strong>Box Availability -
                            <?php echo ($boxtype); ?>
                        </strong></td>
                </tr>
                <tr>
                    <td>
                        <div id="btype<?php echo $boxtype_cnt; ?>">
                            <table width="100%" cellspacing="1" cellpadding="2">
                                <tr vAlign="center">
                                    <td class="section_heading" width="50px"><a
                                            href='gaylordstatus.php?sort=count&sort_order_pre=<?php echo $sort_order_pre; ?>'>#</a>
                                    </td>
                                    <td class="section_heading" width="50px"><strong>Rep</strong>
                                        <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(1,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(1,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a>
                                        </font>
                                    </td>
                                    <td class="section_heading" width="50px"><strong>Available Loads</strong></td>
                                    <?php if ($_REQUEST["box_type"] != "Gaylords") { ?>
                                    <td class="section_heading" width="100px"><strong>Qty Avail Now</strong>
                                        <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(2,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(2,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a>
                                        </font>
                                    </td>
                                    <?php } ?>
                                    <td class="section_heading" width="100px"><strong>Expected # Loads/Month</strong>
                                        <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(3,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(3,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a>
                                        </font>
                                    </td>
                                    <?php if ($_REQUEST["box_type"] != "Gaylords") { ?>
                                    <td class="section_heading" width="100px"><strong>Next Load Avail Date</strong>
                                        <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(4,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(4,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a>
                                        </font>
                                    </td>
                                    <?php } ?>
                                    <td class="section_heading" width="100px">
                                        <strong><?php echo $_REQUEST["box_type"] == "Gaylords" ? "First Load Can Ship In" : "Buy Now, Load Can Ship In"; ?></strong>
                                        <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(5,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(5,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a>
                                        </font>
                                    </td>
                                    <td class="section_heading" width="100px"><strong>Per Truckload</strong>
                                        <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(6,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(6,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a>
                                        </font>
                                    </td>
                                    <td class="section_heading" width="100px"><strong>Min FOB</strong><br>
                                        <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(7,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(7,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a>
                                        </font>
                                    </td>
                                    <td class="section_heading" width="100px"><strong>B2B ID</strong><br>
                                        <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(8,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(8,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a>
                                        </font>
                                    </td>
                                    <td class="section_heading" width="155px"><strong>Supplier</strong><br>
                                        <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(9,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(9,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a>
                                        </font>
                                    </td>
                                    <td class="section_heading" width="150px"><strong>Description</strong>
                                        <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(10,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(10,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a>
                                        </font>
                                    </td>
                                    <td class="section_heading" width="50px"><strong>B2B Status</strong>
                                        <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(11,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(11,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a>
                                        </font>
                                    </td>
                                    <?php //if ($_REQUEST["box_type"] == "Gaylords") {?>
                                    <td class="section_heading" width="100px"><strong>Pic</strong></td>
                                    <?php //} ?>
                                    <td class="section_heading" width="150px">
                                        <strong><?php echo $_REQUEST["box_type"] == "Gaylords" ? "Sales Team Notes" : "Notes"; ?></strong>
                                        <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(12,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayboxdata(12,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a>
                                        </font>
                                    </td>
                                    <?php if ($_REQUEST["box_type"] == "Gaylords") {
                                                ?>
                                    <td class="section_heading" width="100px"><strong>Flyer Notes</strong></td>
                                    <?php } ?>
                                    <td class="section_heading" width="100px"><strong>Communication Log</strong></td>
                                    <td class="section_heading" width="50px"><strong>Update</strong></td>
                                </tr>
                                <?php
                                            $cellClr = '';
                                            $cellClrQAN = '';
                                            foreach ($MGarray as $MGArraytmp2) {

                                                $srno = $srno + 1;
                                                $date_now = date("Y-m-d"); // this format is string comparable
                                                $date_pre = date('Y-m-d', strtotime('-1 day', strtotime($date_now)));
                                                $date_week = date('Y-m-d', strtotime('+7 day', strtotime($date_now)));
                                                $selected_date = 0;
                                                if ($MGArraytmp2["next_load_available_date_display"] != '') {
                                                    $selected_date = date('Y-m-d', strtotime($MGArraytmp2["next_load_available_date_display"]));
                                                }
                                                //echo "<br/>".$MGArraytmp2["I"] . "/" . $date_pre." / ".$date_week." / ".$selected_date . "/". $MGArraytmp2["next_load_available_date_display"]; 
                                            ?>
                                <tr id="gaylord_div<?php echo $srno; ?>" vAlign="center">

                                    <td class="form_label" width="50px">
                                        <font size=1>
                                            <?php echo $srno; ?>
                                        </font>
                                        <input type=hidden name="box_id" id="box_id<?php echo $srno; ?>"
                                            value="<?php echo  $MGArraytmp2["I"]; ?>">
                                    </td>

                                    <td class="form_label" width="100px">
                                        <?php echo $MGArraytmp2["supplier_owner_name"]; ?>
                                    </td>

                                    <td class="form_label" width="50px">
                                        <?php
                                                        if (($MGArraytmp2["sales_order_qty"] > 0) && ($MGArraytmp2["box_type"] != "Gaylords")) { ?>
                                        <div onclick="display_preoder_sel(<?php echo $srno; ?>, <?php echo $MGArraytmp2["sales_order_qty"]; ?>,
                                            <?php echo $MGArraytmp2["loops_id"]; ?>,
                                            <?php echo $MGArraytmp2["vendor_b2b_rescue"]; ?>)"
                                            style="cursor: pointer;">
                                            <u>View</u>
                                        </div>
                                        <?php
                                                        } elseif (($MGArraytmp2["no_of_loads"] > 0) && ($MGArraytmp2["box_type"] == "Gaylords")) {
                                                        ?>
                                        <div onclick="display_av_load_data(<?php echo $srno; ?>, <?php echo $MGArraytmp2["I"]; ?>)" style="cursor: pointer;">
                                            <?php if ($MGArraytmp2["no_of_loads"] > 1) { ?>
                                            <u>
                                                <?php echo $MGArraytmp2["no_of_loads"]; ?> of
                                                <?php echo $MGArraytmp2["total_no_of_loads"]; ?> Loads
                                            </u>
                                            <?php } else { ?>
                                            <u>
                                                <?php echo $MGArraytmp2["no_of_loads"]; ?> of
                                                <?php echo $MGArraytmp2["total_no_of_loads"]; ?> Load
                                            </u>
                                            <?php }  ?>
                                        </div>
                                        <?php
                                                        } elseif (($MGArraytmp2["no_of_loads"] == 0) && ($MGArraytmp2["box_type"] == "Gaylords")) {
                                                        ?>
                                        <font color=red>0 of 0 Load</font>
                                        <?php
                                                        } else {
                                                            echo "";
                                                        }
                                                        ?>
                                        </font>
                                    </td>
                                    <?php
                                                    if ($_REQUEST["box_type"] != "Gaylords") {

                                                        $cellClrQAN = '';
                                                        if (($MGArraytmp2["after_actual_inventory"] == 0 || $MGArraytmp2["after_actual_inventory"] == '') && ($selected_date <= $date_week)) {
                                                            $cellClrQAN = '#e8acac';
                                                        } else if (($MGArraytmp2["after_actual_inventory"] == 0 || $MGArraytmp2["after_actual_inventory"] == '') && ($selected_date > $date_week)) {
                                                            $cellClrQAN = '';
                                                        }
                                                        if ($MGArraytmp2["next_load_available_date_display"] == '') {
                                                            $cellClrQAN = '';
                                                        }
                                                    ?>
                                    <td class="form_label" width="100px" bgcolor="<?php echo  $cellClrQAN; ?>">
                                        <input type="text" id="after_actual_inventory<?php echo $srno; ?>"
                                            name="after_actual_inventory" value="<?php echo $MGArraytmp2["after_actual_inventory"]; ?>" size="5">
                                    </td>
                                    <?php } ?>
                                    <td class="form_label" width="100px"><input type="text"
                                            id="expected_loads_per_mo<?php echo $srno; ?>" name="expected_loads_per_mo"
                                            value="<?php echo $MGArraytmp2["expected_loads_per_mo"]; ?>" size="5"></td>
                                    <?php
                                                    if ($_REQUEST["box_type"] != "Gaylords") {
                                                        $cellClr = '';
                                                        if (($MGArraytmp2["next_load_available_date_display"] == '' || $MGArraytmp2["next_load_available_date_display"] == '00/00/0000') && $MGArraytmp2["after_actual_inventory"] >= 0) {
                                                            $cellClr = '';
                                                        } else if (($date_pre > $selected_date) && $selected_date > 0) {
                                                            $cellClr = '#e8acac';
                                                        } else {
                                                            $cellClr = '';
                                                        }
                                                        //echo $MGArraytmp2["load_date_col_color"];
                                                    ?>
                                    <td class="form_label" width="100px" bgcolor="<?php echo  $cellClr; ?>">
                                        <?php //echo "date_pre: ". $date_pre . " | " . $selected_date . "<br>"; 
                                                            ?>
                                        <input size="10" type="text"
                                            id="ctrl_next_load_available_date<?php echo $srno; ?>"
                                            name="ctrl_next_load_available_date" value="<?php echo $MGArraytmp2["next_load_available_date_display"]; ?>">
                                        <a href="#"
                                            onclick="cal_load_available_date.select(document.frm_main.ctrl_next_load_available_date<?php echo $srno; ?>,'anchorload_available_date<?php echo $srno; ?>','yyyy-MM-dd'); return false;"
                                            name="anchorload_available_date"
                                            id="anchorload_available_date<?php echo $srno; ?>"><img border="0"
                                                src="images/calendar.jpg"></a>
                                        <div ID="listdiv_load_available_date"
                                            STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                                        </div>
                                    </td>
                                    <?php } ?>
                                    <td class="form_label" width="100px">
                                        <?php if ($_REQUEST["box_type"] != "Gaylords") {
                                                            echo $MGArraytmp2["estimated_next_load"];
                                                        } else {
                                                            echo get_lead_time_v3(6, $MGArraytmp2["loops_id"], $MGArraytmp2['lead_time_for_first_load_can_ship'], "");
                                                        }
                                                        ?>
                                    </td>
                                    <td class="form_label" width="100px">
                                        <?php echo $MGArraytmp2["boxes_pertrailer"]; ?>
                                    </td>
                                    <td class="form_label" width="100px">
                                        <?php echo $MGArraytmp2["b2b_fob"]; ?>
                                    </td>
                                    <td class="form_label" width="100px">
                                        <?php echo $MGArraytmp2["I"]; ?>
                                    </td>
                                    <?php
                                                    $contact_nm = "";
                                                    $comm_log_color = "<font size=1>";
                                                    if ($MGArraytmp2["vendor_b2b_rescue"] > 0) {
                                                        $comp_nm = "";
                                                        $comp_b2bid = "";
                                                        $q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = " . $MGArraytmp2["vendor_b2b_rescue"];
                                                        db();
                                                        $query = db_query($q1);
                                                        while ($fetch = array_shift($query)) {
                                                            $comp_nm = $fetch['company_name'];
                                                            $comp_b2bid = $fetch['b2bid'];
                                                        }
                                                        $comp_nm = get_nickname_val($comp_nm, $comp_b2bid);
                                                        $warehouse = $comp_nm;

                                                        $last_contact_date = "";
                                                        $cdate = "";
                                                        $q1 = "SELECT contact, phone, email, last_contact_date FROM companyInfo where ID = '" . $comp_b2bid . "'";
                                                        db_b2b();
                                                        $query = db_query($q1);
                                                        while ($fetch = array_shift($query)) {
                                                            $contact_nm = $fetch['contact'] . "<br>" . $fetch['phone'] . "<br>" . $fetch['email'];
                                                            if ($fetch["last_contact_date"] != "") {
                                                                $last_contact_date = date("m/d/Y", strtotime($fetch["last_contact_date"]));
                                                                $cdate = $fetch["last_contact_date"];
                                                            }
                                                        }

                                                        if ($cdate != "" || $cdate != "NULL") {
                                                            $todays_dt = date('m/d/Y');
                                                            $comm_log_date_day = dateDiff($todays_dt, $last_contact_date);
                                                            //echo $count . " cond 4 - " . $todays_dt . " " . $last_contact_date . " " . $comm_log_date_day . "<br>";
                                                            if ($comm_log_date_day <= 30) {
                                                                $comm_log_color = "<font color='green'>";
                                                            }
                                                            if ($comm_log_date_day > 30 && $comm_log_date_day <= 90) {
                                                                $comm_log_color = "<font color='#FF9933'>";
                                                            }
                                                            if ($comm_log_date_day > 90) {
                                                                $comm_log_color = "<font color='red'>";
                                                            }
                                                        }
                                                    ?>
                                    <td class="form_label" width="150px">
                                        <font size=1><a href="viewCompany.php?ID=<?php echo $comp_b2bid; ?>"
                                                target="_blank">
                                                <?php echo $comp_nm; ?>
                                            </a></font>
                                    </td>
                                    <?php } else {

                                                        $vendor_b2b_rescue = $MGArraytmp2["V"];
                                                        $q1 = "SELECT Name FROM vendors where id = '$vendor_b2b_rescue'";
                                                        db_b2b();
                                                        $query = db_query($q1);
                                                        while ($fetch = array_shift($query)) {
                                                            $vendor_name = $fetch["Name"];
                                                        }

                                                        $warehouse = $vendor_name;
                                                    ?>
                                    <td class="form_label" width="150px">
                                        <font size=1><a
                                                href="addVendor.php?act=edit&id=<?php echo $MGArraytmp2["V"]; ?>"
                                                target="_blank">
                                                <?php echo ($vendor_name); ?>
                                            </a></font>
                                    </td>
                                    <?php } ?>

                                    <td class="form_label" width="200px">
                                        <font size=1><a href="manage_box_b2bloop.php?id=<?php echo $MGArraytmp2["loops_id"]; ?>&proc=Edit&"
                                                target="_blank"><?php echo  $MGArraytmp2["description"]; ?></a>
                                            <br><br>
                                            <div onClick="display_matching_tool_gaylords_v3(<?php echo $srno; ?>, 0,<?php echo  $MGArraytmp2["I"]; ?>, 1, 1, 0)"
                                                style="cursor: pointer;">
                                                <u>Matching Tool v3.0 View</u>
                                            </div>
                                    </td>

                                    <td class="form_label" width="50px">
                                        <font size=1>
                                            <select name="b2b_status" id="b2b_status<?php echo $srno; ?>"
                                                style="width:150px;">
                                                <option value="">Select One</option>
                                                <?php
                                                                $st_query = "select * from b2b_box_status";
                                                                db();
                                                                $st_res = db_query($st_query);
                                                                while ($st_row = array_shift($st_res)) {
                                                                ?>
                                                <option <?php if ($st_row["status_key"] == $MGArraytmp2["b2b_status"])
                                                                                echo " selected "; ?> value="
                                                    <?php echo $st_row["status_key"]; ?>">
                                                    <?php echo $st_row["box_status"]; ?>
                                                </option>
                                                <?php
                                                                }
                                                                ?>
                                            </select>
                                    </td>
                                    <?php //if ($_REQUEST["box_type"] == "Gaylords") { ?>
                                    <td class="form_label"><span style="color:<?php echo $MGArraytmp2['pic_color']; ?>">
                                            <?php echo $MGArraytmp2['no_pic']; ?>
                                        </span></td>
                                    <?php //} ?>

                                    <td class="form_label" width="150px"><textarea rows=2 cols=20 name="note"
                                            onkeypress='return chkcharacter(event);'
                                            id="note<?php echo $srno; ?>"><?php echo $MGArraytmp2["N"]; ?></textarea>
                                        <br>
                                        <?php echo ($notes_date_col_color);
                                                        if ($MGArraytmp2["DT"] != "") echo timestamp_to_date($MGArraytmp2["DT"]); ?>
                                        </font>
                                        <a href="#" id="translog<?php echo ($comp_b2bid); ?>"
                                            onclick="displaytrans_log(<?php echo ($comp_b2bid); ?>,<?php echo ($comp_b2bid); ?>); return false;">
                                            <font size=1>Show Log</font>
                                        </a>
                                    </td>
                                    <?php if ($_REQUEST["box_type"] == "Gaylords") { ?>
                                    <td class="form_label" width="150px"><textarea rows=2 cols=20 name="flyer_notes"
                                            onkeypress='return chkcharacter(event);'
                                            id="flyer_notes<?php echo $srno; ?>"><?php echo $MGArraytmp2["flyer_notes"]; ?></textarea>
                                    </td>
                                    <?php } ?>


                                    <td class="form_label" width="100px">
                                        <?php if ($MGArraytmp2["vendor_b2b_rescue"] > 0) {
                                                        ?>
                                        <table width="96%" border="0" cellspacing="1" cellpadding="1"
                                            class="sub_tables_s">
                                            <tr valign="top">
                                                <td>
                                                    <input type=hidden name="companyID"
                                                        id="companyID<?php echo $srno; ?>"
                                                        value="<?php echo ($comp_b2bid) ?>">
                                                    <input type=hidden name="loopID" id="loopID<?php echo $srno; ?>"
                                                        value="<?php echo  $MGArraytmp2["vendor_b2b_rescue"] ?>">
                                                    <select size="1" name="type" id="type<?php echo $srno; ?>">
                                                        <option>email</option>
                                                        <option>phone</option>
                                                        <option>fax</option>
                                                        <option>meeting</option>
                                                        <option>note</option>
                                                        <option>other</option>
                                                    </select>&nbsp;

                                                    <textarea rows="5" name="message" id="message<?php echo $srno; ?>"
                                                        cols="15"></textarea>
                                                    <select size="1" name="employee" id="employee<?php echo $srno; ?>">
                                                        <option><?php echo  $_COOKIE["userinitials"] ?></option>
                                                        <?php
                                                                            $eq = "SELECT * FROM employees WHERE status='Active'";
                                                                            db_b2b();
                                                                            $dt_view_res1 = db_query($eq);
                                                                            while ($emp = array_shift($dt_view_res1)) {
                                                                            ?>
                                                        <option><?php echo  $emp["initials"] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    &nbsp;
                                                    <?php echo $comm_log_color; ?>
                                                    <?php echo ($last_contact_date); ?>
                                                    </font>
                                                    &nbsp;
                                                    <?php echo "<a href='#' onclick='displayemail(" . $comp_b2bid . ", " . $srno . "); return false;'>Last Entry</a>"; ?>
                                                    </font>
                                                </td>
                                            </tr>
                                        </table>
                                        <?php } ?>
                                    </td>
                                    <?php $b_type = $_REQUEST['box_type']; ?>
                                    <td class="form_label" width="50px"><input type="button" value="Update"
                                            onclick="update_details(<?php echo $srno; ?>,2, '<?php echo $b_type; ?>')">
                                    </td>

                                </tr>
                                <?php if ($MGArraytmp2["sales_order_qty"] > 0) { ?>
                                <tr id='inventory_preord_top_<?php echo $srno; ?>' align="middle" style="display:none;">
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td colspan="12"
                                        style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
                                        <div id="inventory_preord_middle_div_<?php echo $srno; ?>"></div>
                                    </td>
                                </tr>
                                <?php    } ?>

                                <?php if ($MGArraytmp2["no_of_loads"] > 0) { ?>
                                <tr id='inventory_gayloard_preord_top_<?php echo $srno; ?>' align="middle"
                                    style="display:none;">
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td colspan="12"
                                        style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
                                        <div id="div_av_load_data<?php echo $srno; ?>"></div>
                                    </td>
                                </tr>
                                <?php    } ?>

                                <tr id='inventory_v3_preord_top_<?php echo $srno; ?>' align="middle" colspan=''
                                    style="display:none;">
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td colspan="11"
                                        style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
                                        <div id="divtool_gaylords_v3<?php echo $srno; ?>"></div>
                                    </td>
                                </tr>

                                <?php
                                            }
                                            unset($_SESSION['sortarray' . $box_name]);
                                            ?>
                            </table>
                        </div>
                    </td>
                </tr>
                <?php
                        }
                        ?>
                <?php
                    }
                    ?>
            </table>
        </form>
        <div><i>
                <font color="red">"END OF REPORT"</font>
            </i></div>
        <p>&nbsp;</p>

        <?php
        }
        ?>


    </div>

</body>

</html>