<?php

session_start();
// ini_set("display_errors", "1");

// error_reporting(E_ERROR);

require "inc/header_session.php";
require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

?>

<!DOCTYPE html>
<html>

<head>

    <title>Company Travel Proximity Report</title>
    <LINK rel='stylesheet' type='text/css' href='one_style.css'>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap"
        rel="stylesheet">
    <link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
    <style>
    .style12 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        color: #292929;
        font-weight: bold;
        white-space: nowrap;
    }

    .tdstyle {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11px;
        color: #333333;
    }

    table.sortable tr td {
        background-color: #EBEBEB;
    }
    </style>


</head>

<body>
    <?php include_once "inc/header.php"; ?>
    <div class="main_data_css">
        <div class="dashboard_heading" style="float: left;">
            <div style="float: left;">
                Company Travel Proximity Report
                <div class="tooltip">
                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                    <span class="tooltiptext">
                        This report allows the user to search for all companies within a certain proximity of a zip
                        code. This is useful for when someone travels. The user can see all companies that will be close
                        to the zip code entered and it can be easily decided if any visits should be made.
                    </span>
                </div>
                <div style="height: 13px;">&nbsp;</div>
            </div>
        </div>

        <table border="0" width="100%" cellspacing="0" cellpadding="5">
            <tr>
                <td valign="top"></td>
            </tr>
            <tr>

                <td valign="top">
                    <?php
                    $sort_order_pre = "ASC";
                    $asc_img = "";
                    $surl = "";
                    if ($_GET['sort_order_pre'] == "ASC") {
                        $sort_order_pre = "DESC";
                    } else {
                        $sort_order_pre = "ASC";
                    }

                    ?>

                    <form action="customer_transaction_report.php" method="get">
                        <table width="100%" border="0" cellspacing="2" cellpadding="2" bgcolor="#E4E4E4">
                            <tr align="center">
                                <td colspan="2" bgcolor="#C0CDDA">
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#0B0B0B">
                                        <strong>Customer's Transaction Report</strong>
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    Zipcode: <input name="zip" type="text" id="zip"
                                        value="<?= htmlspecialchars($_GET["zip"] ?? '') ?>" size="6" maxlength="7">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    How many miles to search for: <input type="text" name="miles"
                                        value="<?= htmlspecialchars($_GET["miles"] ?? '') ?>">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    Filter By: <select name="filterby">
                                        <option value="">Select One</option>
                                        <option value="1" <?= ($_GET['filterby'] ?? '') == '1' ? "selected" : "" ?>>
                                            Sales</option>
                                        <option value="2" <?= ($_GET['filterby'] ?? '') == '2' ? "selected" : "" ?>>
                                            Purchasing</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    Transaction Type:
                                    <select name="transactionType" id="transactionType">
                                        <option value="All">All</option>
                                        <option value='1'
                                            <?php echo ($_REQUEST['transactionType'] == '1') ? ' selected' : ''; ?>>
                                            UsedCardboardBoxes</option>
                                        <option value='2'
                                            <?php echo ($_REQUEST['transactionType'] == '2') ? ' selected' : ''; ?>>
                                            UCBPalletSolutions</option>
                                        <option value='3'
                                            <?php echo ($_REQUEST['transactionType'] == '3') ? ' selected' : ''; ?>>
                                            UCBZeroWaste</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="submit" value="Find Matches">
                                </td>
                            </tr>
                        </table>
                    </form>
                    <br>
                    <form name="frmstatusdashboard" id="frmstatusdashboard" method="post" action="">
                        <div>
                            <i>Note: Please wait until you see <font color="red">"END OF REPORT"</font> at the bottom of
                                the report, before using the sort option.</i>
                        </div>


                        <?php
                        $surl = "customer_transaction_report.php?zip=" . $_REQUEST['zip'] . "&miles=" . $_REQUEST['miles'] . "&filterby=" . $_REQUEST['filterby'] . "&transactionType=" . $_REQUEST['transactionType'] . "&sort=y&";
                        $asc_img = '<img src="images/sort_asc.png" width="6px;" height="12px;">';
                        $desc_img = '<img src="images/sort_desc.png" width="6px;" height="12px;">';
                        $MGArray_parent_child_data = array();
                        ?>
                        <table width="100%" border="0" cellspacing="2" cellpadding="5" bgcolor="#F3F3F3" id="table"
                            class="sortable">
                            <thead>
                                <tr>
                                    <th bgcolor="#ABC5DF" class="style12" style="white-space: nowrap;">Dist.
                                        from<br>Zipcode&nbsp;
                                        <a href="<?php echo $surl; ?>dist=asc"><?php echo $asc_img; ?></a>&nbsp;
                                        <a href="<?php echo $surl; ?>dist=desc"><?php echo $desc_img; ?></a>
                                    </th>
                                    <th bgcolor="#ABC5DF" class="style12" style="white-space: nowrap;">
                                        Company<br>Name&nbsp;

                                        <a href="<?php echo $surl; ?>comp_name=asc"><?php echo $asc_img; ?></a>&nbsp;
                                        <a href="<?php echo $surl; ?>comp_name=desc"><?php echo $desc_img; ?></a>

                                    </th>
                                    <th bgcolor="#ABC5DF" class="style12" style="white-space: nowrap;">
                                        Contact<br>Name&nbsp;
                                        <a href="<?php echo $surl; ?>contact=asc"><?php echo $asc_img; ?></a>&nbsp;
                                        <a href="<?php echo $surl; ?>contact=desc"><?php echo $desc_img; ?></a>
                                    </th>
                                    <th bgcolor="#ABC5DF" class="style12" style="white-space: nowrap;">
                                        UCB/ZW<br>Rep&nbsp;
                                        <a href="<?php echo $surl; ?>ucbzwrep=asc"><?php echo $asc_img; ?></a>&nbsp;
                                        <a href="<?php echo $surl; ?>ucbzwrep=desc"><?php echo $desc_img; ?></a>
                                    </th>
                                    <th bgcolor="#ABC5DF" class="style12" style="white-space: nowrap;">
                                        Account<br>Status&nbsp;
                                        <a href="<?php echo $surl; ?>actstatus=asc"><?php echo $asc_img; ?></a>&nbsp;
                                        <a href="<?php echo $surl; ?>actstatus=desc"><?php echo $desc_img; ?></a>
                                    </th>
                                    <th bgcolor="#ABC5DF" class="style12" style="white-space: nowrap;"> Industry&nbsp;
                                        <a href="<?php echo $surl; ?>industry=asc"><?php echo $asc_img; ?></a>&nbsp;
                                        <a href="<?php echo $surl; ?>industry=desc"><?php echo $desc_img; ?></a>
                                    </th>
                                    <th bgcolor="#ABC5DF" class="style12" style="white-space: nowrap;">
                                        Sales<br>Transactions&nbsp;
                                        <a href="<?php echo $surl; ?>trans_sales=asc"><?php echo $asc_img; ?></a>&nbsp;
                                        <a href="<?php echo $surl; ?>trans_sales=desc"><?php echo $desc_img; ?></a>
                                    </th>
                                    <th bgcolor="#ABC5DF" class="style12" style="white-space: nowrap;">
                                        Sales<br>Revenue&nbsp;
                                        <a
                                            href="<?php echo $surl; ?>trans_srevenue=asc"><?php echo $asc_img; ?></a>&nbsp;
                                        <a href="<?php echo $surl; ?>trans_srevenue=desc"><?php echo $desc_img; ?></a>
                                    </th>
                                    <th bgcolor="#ABC5DF" class="style12" style="white-space: nowrap;">
                                        Purchasing<br>Transactions&nbsp;
                                        <a href="<?php echo $surl; ?>trans_pur=asc"><?php echo $asc_img; ?></a>&nbsp;
                                        <a href="<?php echo $surl; ?>trans_pur=desc"><?php echo $desc_img; ?></a>
                                    </th>
                                    <th bgcolor="#ABC5DF" class="style12" style="white-space: nowrap;">
                                        Purchasing<br>Payments&nbsp;
                                        <a
                                            href="<?php echo $surl; ?>trans_payment=asc"><?php echo $asc_img; ?></a>&nbsp;
                                        <a href="<?php echo $surl; ?>trans_payment=desc"><?php echo $desc_img; ?></a>
                                    </th>
                                    <th bgcolor="#ABC5DF" class="style12" style="white-space: nowrap;">Parent&nbsp;
                                        <a href="<?php echo $surl; ?>parentc=asc"><?php echo $asc_img; ?></a>&nbsp;
                                        <a href="<?php echo $surl; ?>parentc=desc"><?php echo $desc_img; ?></a>
                                    </th>
                                    <th bgcolor="#ABC5DF" class="style12" style="white-space: nowrap;">Next Step&nbsp;
                                        <a href="<?php echo $surl; ?>next_step=asc"><?php echo $asc_img; ?></a>&nbsp;
                                        <a href="<?php echo $surl; ?>next_step=desc"><?php echo $desc_img; ?></a>
                                    </th>
                                    <th bgcolor="#ABC5DF" class="style12" style="white-space: nowrap;">
                                        Last<br>Communication&nbsp;
                                        <a href="<?php echo $surl; ?>last_date=asc"><?php echo $asc_img; ?></a>&nbsp;
                                        <a href="<?php echo $surl; ?>last_date=desc"><?php echo $desc_img; ?></a>
                                    </th>
                                    <th bgcolor="#ABC5DF" class="style12">Next<br>Communication&nbsp;
                                        <a href="<?php echo $surl; ?>next_date=asc"><?php echo $asc_img; ?></a>&nbsp;
                                        <a href="<?php echo $surl; ?>next_date=desc"><?php echo $desc_img; ?></a>
                                    </th>
                                    <th bgcolor="#ABC5DF" class="style12">Update</th>
                                </tr>
                                <?php
                                if (isset($_REQUEST['zip'])) {
                                    if (!isset($_REQUEST["sort"])) {

                                        $zipcan = 0;
                                        $tmppos_1 = strpos($_REQUEST['zip'], " ");

                                        $zip = $_REQUEST['zip'];
                                        $tmp_zipval = str_replace(" ", "", $zip);

                                        $zip_val = $tmp_zipval;

                                        $srtby = "";
                                        if ($_REQUEST['filterby'] != "") {
                                            if ($_REQUEST['filterby'] == "2") {
                                                $srtby = " AND haveNeed = 'Have Boxes'";
                                            } else {
                                                $srtby = " AND haveNeed = 'Need Boxes'";
                                            }
                                        }

                                        if (isset($_REQUEST['transactionType'])) {
                                            if ($_REQUEST['transactionType'] == 1) {
                                                $transTypeQry = " AND pallet_flg = 0 AND ucbzw_flg = 0 ";
                                            } elseif ($_REQUEST['transactionType'] == 2) {
                                                $transTypeQry = " AND pallet_flg= 1 ";
                                            } elseif ($_REQUEST['transactionType'] == 3) {
                                                $transTypeQry = " AND ucbzw_flg= 1 ";
                                            } else {
                                                $transTypeQry = "";
                                            }
                                        }

                                        db_b2b();
                                        $dt_view_rs = db_query("Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'");

                                        if (tep_db_num_rows($dt_view_rs) == 0) {

                                            $dt_view_rs = db_query("Select * from ZipCodes WHERE zip = '" . intval($zip_val) . "'");
                                        } else {
                                            $zipcan = 1;
                                        }

                                        if (tep_db_num_rows($dt_view_rs) == 0) {
                                            echo "<p><strong>No database match for provided ZIP Code.</strong> Please enter a new ZIP Code.</p>\n";
                                        } else {

                                            $row = array_shift($dt_view_rs);
                                            $lat1 = $row['latitude'];
                                            $lon1 = $row['longitude'];
                                            $d = 0;
                                            $r = 3959;

                                            if (isset($_REQUEST['miles']) && $_REQUEST['miles'] != '') {

                                                $d = $_REQUEST['miles'];
                                            }

                                            $latN = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(0))));
                                            $latS = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(180))));
                                            $lonE = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(90)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));
                                            $lonW = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(270)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));

                                            if ($zipcan == 1) {

                                                $query = "SELECT * FROM zipcodes_canada WHERE zip = '" . $_REQUEST['zip'] . "' or (latitude <= $latN AND latitude >= $latS AND longitude <= $lonE AND longitude >= $lonW) AND city != '' ORDER BY state, city, latitude, longitude";
                                                $zipcan == 0;
                                            } else {

                                                $query = "SELECT * FROM ZipCodes WHERE zip = '" . $_REQUEST['zip'] . "' or (latitude <= $latN AND latitude >= $latS AND longitude <= $lonE AND longitude >= $lonW) AND city != '' ORDER BY state, city, latitude, longitude";
                                            }

                                            if (!$rs = db_query($query)) {
                                                echo "<p><strong>There was an error selecting nearby ZIP Codes from the database.</strong></p>\n";
                                            } elseif (tep_db_num_rows($rs) == 0) {
                                                echo "<p><strong>No nearby ZIP Codes located within the distance specified.</strong> Please try a different distance.</p>\n";
                                            } else {

                                                while ($row = array_shift($rs)) {
                                                    $ziparr[] = $row["zip"];

                                                    db_b2b();
                                                    $res3 = db_query("Select * From companyInfo where status not in (31, 49, 24, 43, 44) and shipZip ='" . str_pad($row["zip"], 5, "0", STR_PAD_LEFT) . "' " . $srtby . $transTypeQry);
                                                    //  echo $res3;
                                                    while ($objMatchCo = array_shift($res3)) {
                                                        if ($objMatchCo["nickname"] != "") {
                                                            $nickname = $objMatchCo["nickname"];
                                                        } else {
                                                            $tmppos_1 = strpos($objMatchCo["company"], "-");
                                                            if ($tmppos_1 != false) {
                                                                $nickname = $objMatchCo["company"];
                                                            } else {
                                                                if ($objMatchCo["shipCity"] != "" || $objMatchCo["shipState"] != "") {
                                                                    $nickname = $objMatchCo["company"] . " - " . $objMatchCo["shipCity"] . ", " . $objMatchCo["shipState"];
                                                                } else {
                                                                    $nickname = $objMatchCo["company"];
                                                                }
                                                            }
                                                        }
                                                        $nickname = str_replace("'", "\'", $nickname);

                                                        $compid = $objMatchCo["ID"];

                                                        $UCB_ZW_Rep = "";

                                                        db_b2b();
                                                        $emp_rs = db_query("select employees.initials from employees where employees.employeeID=" . $objMatchCo["assignedto"]);

                                                        $emprow = array_shift($emp_rs);
                                                        $UCB_ZW_Rep = $emprow['initials'];

                                                        if (!empty($objMatchCo["ucbzw_account_owner"])) {
                                                            db_b2b();
                                                            $empres1 = db_query("select initials from employees where employees.employeeID='" . $objMatchCo["ucbzw_account_owner"] . "'");

                                                            $emprow1 = array_shift($empres1);

                                                            $UCB_ZW_Rep = $UCB_ZW_Rep . "/" . $emprow1["initials"];
                                                        }

                                                        $acc_status = "";
                                                        db_b2b();
                                                        $dt_view_res = db_query("SELECT name from status where id = '" . $objMatchCo['status'] . "'");

                                                        while ($myrow = array_shift($dt_view_res)) {
                                                            $acc_status = $myrow["name"];
                                                        }

                                                        $industry = "";
                                                        $industry_data_found = "";
                                                        db_b2b();
                                                        $indus_res = db_query("Select industry from industry_master where active_flg = 1 and industry_id = '" . $objMatchCo["industry_id"] . "'");

                                                        while ($indus_row = array_shift($indus_res)) {
                                                            $industry .= $indus_row["industry"] . "/";
                                                            $industry_data_found = "yes";
                                                        }
                                                        if ($objMatchCo["industry_other"] != "") {
                                                            $industry .= "/" . $objMatchCo["industry_other"];
                                                            $industry_data_found = "yes";
                                                        }
                                                        if ($industry_data_found == "") {
                                                            $industry .= "<span style='color:#FF0000;'>No Industry Selected</span>";
                                                        }

                                                        $tot_trans = 0;
                                                        $tot_trans_p = 0;
                                                        if ($objMatchCo['loopid'] != 0) {
                                                            db();
                                                            $dt_view_res = db_query("select count(id) as s_cnt from loop_transaction_buyer where `ignore` = 0 and warehouse_id = " . $objMatchCo['loopid']);

                                                            while ($myrow = array_shift($dt_view_res)) {
                                                                $tot_trans = $tot_trans + $myrow['s_cnt'];
                                                            }

                                                            if ($objMatchCo['link_sales_id'] > 0) {
                                                                db();
                                                                $dt_view_res = db_query("select count(id) as s_cnt from loop_transaction_buyer where `ignore` = 0 and warehouse_id = " . $objMatchCo['link_sales_id']);

                                                                while ($myrow = array_shift($dt_view_res)) {
                                                                    $tot_trans = $tot_trans + $myrow['s_cnt'];
                                                                }
                                                            }

                                                            if ($objMatchCo['link_purchasing_id'] > 0) {
                                                                db();
                                                                $dt_view_res1 = db_query("select count(id) as p_cnt from loop_transaction where `ignore` = 0 and warehouse_id = " . $objMatchCo['loopid']);

                                                                while ($myrow1 = array_shift($dt_view_res1)) {
                                                                    $tot_trans_p = $tot_trans_p + $myrow1['p_cnt'];
                                                                }
                                                            }

                                                            db();
                                                            $dt_view_res1 = db_query("select count(id) as p_cnt from loop_transaction where `ignore` = 0 and warehouse_id = " . $objMatchCo['loopid']);

                                                            while ($myrow1 = array_shift($dt_view_res1)) {
                                                                $tot_trans_p = $tot_trans_p + $myrow1['p_cnt'];
                                                            }
                                                        }

                                                        $summtd_SUMPO = 0;
                                                        $summtd_SUMPO_p = 0;
                                                        if ($objMatchCo["haveNeed"] == "Need Boxes") {

                                                            db();
                                                            $dt_view_res = db_query("SELECT loop_transaction_buyer.po_employee, transaction_date, total_revenue, loop_warehouse.b2bid, loop_warehouse.company_name, inv_date_of, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.warehouse_id = '" . $objMatchCo['loopid'] . "' AND loop_transaction_buyer.ignore < 1 order by loop_transaction_buyer.id");

                                                            while ($myrow = array_shift($dt_view_res)) {
                                                                $inv_amt_totake = $myrow["total_revenue"];

                                                                $summtd_SUMPO = $summtd_SUMPO + $inv_amt_totake;
                                                            }
                                                            if ($objMatchCo['link_purchasing_id'] > 0) {
                                                                db();
                                                                $dt_view_res = db_query("SELECT transaction_date, estimated_revenue, loop_warehouse.b2bid, loop_warehouse.company_name, loop_transaction.id, loop_transaction.warehouse_id FROM loop_transaction inner join loop_warehouse on loop_warehouse.id = loop_transaction.warehouse_id WHERE loop_transaction.warehouse_id = '" . $objMatchCo['link_purchasing_id'] . "' AND loop_transaction.ignore < 1 order by loop_transaction.id");

                                                                while ($myrow = array_shift($dt_view_res)) {
                                                                    $finalpaid_amt = 0;

                                                                    $inv_amt_totake = $myrow["estimated_revenue"];

                                                                    $summtd_SUMPO_p = $summtd_SUMPO_p + $inv_amt_totake;
                                                                }
                                                            }
                                                        } else {

                                                            db();
                                                            $dt_view_res = db_query("SELECT transaction_date, estimated_revenue, loop_warehouse.b2bid, loop_warehouse.company_name, loop_transaction.id, loop_transaction.warehouse_id FROM loop_transaction inner join loop_warehouse on loop_warehouse.id = loop_transaction.warehouse_id WHERE loop_transaction.warehouse_id = '" . $objMatchCo['loopid'] . "' AND loop_transaction.ignore < 1 order by loop_transaction.id");

                                                            while ($myrow = array_shift($dt_view_res)) {
                                                                $finalpaid_amt = 0;

                                                                $inv_amt_totake = $myrow["estimated_revenue"];

                                                                $summtd_SUMPO_p = $summtd_SUMPO_p + $inv_amt_totake;
                                                            }
                                                            if ($objMatchCo['link_sales_id'] > 0) {
                                                                db();
                                                                $dt_view_res = db_query("SELECT loop_transaction_buyer.po_employee, total_revenue, transaction_date, loop_warehouse.b2bid, loop_warehouse.company_name, inv_date_of, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.warehouse_id = '" . $objMatchCo['link_sales_id'] . "' AND loop_transaction_buyer.ignore < 1 order by loop_transaction_buyer.id");

                                                                while ($myrow = array_shift($dt_view_res)) {
                                                                    $inv_amt_totake = $myrow["total_revenue"];
                                                                    $summtd_SUMPO = $summtd_SUMPO + $inv_amt_totake;
                                                                }
                                                            }
                                                        }

                                                        $parentchild_txt = "";
                                                        if ($$objMatchCo["parent_child"] != "") {

                                                            if ($$objMatchCo["parent_child"] == "Parent") {

                                                                $parentchild_txt = "This is the Parent";
                                                            }

                                                            if ($$objMatchCo["parent_child"] == 'Child') {

                                                                $parentchild_txt = "<a href='viewCompany.php?ID=" . $objMatchCo["parent_comp_id"] . "' target='_blank'> " . get_nickname_val($objMatchCo["company"], $objMatchCo["parent_comp_id"]) . " </a>";
                                                            }
                                                        } else {
                                                            $parentchild_txt = "No Family Tree Setup";
                                                        }

                                                        $total_trans_sales = 0;

                                                        $total_trans_pur = 0;

                                                        $message_dt_sales = "";

                                                        $message_dt_purchase = "";

                                                        $distance = round(acos(sin(deg2rad($lat1)) * sin(deg2rad($row['latitude'])) + cos(deg2rad($lat1)) * cos(deg2rad($row['latitude'])) * cos(deg2rad($row['longitude']) - deg2rad($lon1))) * $r, 0);

                                                        if (!isset($_REQUEST["sort"])) {
                                ?>
                                <tr valign="middle" id="tbl_div<?php echo $compid ?>">
                                    <td class="tdstyle">
                                        <?php echo $distance; ?>
                                    </td>
                                    <td class="tdstyle">
                                        <a href="viewCompany.php?ID=<?php echo $compid ?>" target="_blank">
                                            <?php echo $nickname; ?></a>
                                    </td>
                                    <td class="tdstyle"><?php echo $objMatchCo['contact']; ?></td>
                                    <td class="tdstyle"><?php echo $UCB_ZW_Rep; ?></td>
                                    <td class="tdstyle"><?php echo $acc_status; ?></td>
                                    <td class="tdstyle"><?php echo $industry; ?></td>
                                    <td class="tdstyle"><?php echo $tot_trans; ?></td>
                                    <td class="tdstyle"><?php echo "$" . number_format($summtd_SUMPO, 2); ?></td>
                                    <td class="tdstyle"><?php echo $tot_trans_p; ?></td>
                                    <td class="tdstyle"><?php echo "$" . number_format($summtd_SUMPO_p, 2); ?></td>
                                    <td class="tdstyle"><?php echo $parentchild_txt; ?></td>
                                    <td class="tdstyle">
                                        <textarea name="note<?php echo $compid; ?>"
                                            id="note<?php echo $compid; ?>"><?php echo $objMatchCo["next_step"]; ?></textarea>
                                    </td>
                                    <td class="tdstyle"><?php if ($objMatchCo["last_contact_date"] != "") {
                                                                                        echo date("m/d/Y", strtotime($objMatchCo["last_contact_date"]));
                                                                                    } ?></td>
                                    <td class="tdstyle">
                                        <input type="text" size="8" name="txt_next_step_dt"
                                            id="txt_next_step_dt<?php echo $compid ?>"
                                            value="<?php if ($objMatchCo["next_date"] != "") {
                                                                                                                                echo date('m/d/Y', strtotime($objMatchCo["next_date"]));
                                                                                                                            }
                                                                                                                            ?>">
                                        <a href="#"
                                            onclick="cal2xx.select(document.frmstatusdashboard.txt_next_step_dt<?php echo $compid ?>,'dtanchor1xx<?php echo $compid ?>','yyyy-MM-dd'); return false;"
                                            name="dtanchor1xx" id="dtanchor1xx<?php echo $compid ?>"><img border="0"
                                                src="images/calendar.jpg"></a>

                                        <div ID="listdiv"
                                            STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                                        </div>
                                    </td>

                                    <td class="tdstyle">
                                        <input type="button" name="btnupdate" id="btnupdate" value="Update"
                                            onclick="update_details(<?php echo $compid ?>,<?php echo $distance ?>)">
                                    </td>
                                </tr>
                                <?php
                                                        }

                                                        $MGArray_parent_child_data[] = array(
                                                            'distance' => $distance,
                                                            'compid' => $compid,
                                                            'nickname' => $nickname,
                                                            'contact' => $objMatchCo['contact'],
                                                            'ucb_rep' => $UCB_ZW_Rep,
                                                            'actstatus' => $acc_status,
                                                            'industry' => $industry,
                                                            'sales_trans' => $tot_trans,
                                                            'sales_revenue' => $summtd_SUMPO,
                                                            'purchase_trans' => $tot_trans_p,
                                                            'purchase_payment' => $summtd_SUMPO_p,
                                                            'parent' => $parentchild_txt,
                                                            'shipCity' => $objMatchCo["shipCity"],
                                                            'shipState' => $objMatchCo["shipState"],
                                                            'shipZip' => $objMatchCo["shipZip"],
                                                            'next_step' => $objMatchCo["next_step"],
                                                            'last_date' => $objMatchCo["last_contact_date"],
                                                            'next_date' => $objMatchCo["next_date"],
                                                            'total_trans_sales' => $total_trans_sales,
                                                            'total_trans_pur' => $total_trans_pur,
                                                            'message_dt_sales' => $message_dt_sales,
                                                            'message_dt_purchase' => $message_dt_purchase,
                                                            'loopid' => $objMatchCo["loopid"]
                                                        );
                                                    }
                                                }
                                            }
                                        }

                                        $_SESSION['MGArray_parent_child_data'] = $MGArray_parent_child_data;
                                        ?>
                                <script>
                                window.location = "<?php echo $surl ?>&dist=asc";
                                </script>

                                <?php
                                    }
                                    $vc_array_distance = array();
                                    $vc_array_ucbZw = array();
                                    $vc_array_nickname = array();
                                    $vc_array_actStatus = array();
                                    $vc_array_industry = array();
                                    $vc_array_salesTrans = array();
                                    $vc_array_salesRevenue = array();
                                    $vc_array_purchaseTrans = array();
                                    $vc_array_total_purchasePayments = array();
                                    $vc_array_total_parent = array();
                                    $vc_array_next_step = array();
                                    $vc_array_next_date = array();
                                    $vc_array_contact = array();
                                    $vc_array_last_date = array();

                                    if (isset($_REQUEST["sort"])) {

                                        if (isset($_SESSION['MGArray_parent_child_data']) && $_SESSION['MGArray_parent_child_data'] != '') {

                                            $MGArray_parent_child_data = $_SESSION['MGArray_parent_child_data'];
                                        }


                                        foreach ($MGArray_parent_child_data as $key => $row) {

                                            $vc_array_distance[$key] = $row['distance'];
                                            $vc_array_nickname[$key] = $row['nickname'];
                                            $vc_array_contact[$key] = $row['contact'];
                                            $vc_array_ucbZw[$key] = $row['ucb_rep'];
                                            $vc_array_actStatus[$key] = $row['actstatus'];
                                            $vc_array_industry[$key] = $row['industry'];
                                            $vc_array_salesTrans[$key] = $row['sales_trans'];
                                            $vc_array_salesRevenue[$key] = $row['sales_revenue'];
                                            $vc_array_purchaseTrans[$key] = $row['purchase_trans'];
                                            $vc_array_total_purchasePayments[$key] = $row['purchase_payment'];
                                            $vc_array_total_parent[$key] = $row['parent'];

                                            $vc_array_next_step[$key] = $row['next_step'];
                                            $vc_array_last_date[$key] = $row['last_date'];
                                            $vc_array_next_date[$key] = $row['next_date'];
                                            $vc_array_initials[$key] = $row['initials'];

                                            $vc_array_total_trans_sales[$key] = $row['total_trans_sales'];
                                            $vc_array_total_trans_pur[$key] = $row['total_trans_pur'];
                                            $vc_array_message_dt_sales[$key] = $row['message_dt_sales'];
                                            $vc_array_message_dt_purchase[$key] = $row['message_dt_purchase'];
                                            $vc_array_compid[$key] = $row['compid'];
                                            $vc_array_loopid[$key] = $row['loopid'];
                                        }



                                        if ($_REQUEST["dist"] == "asc") {
                                            array_multisort($vc_array_distance, SORT_ASC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["dist"] == "desc") {
                                            array_multisort($vc_array_distance, SORT_DESC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["comp_name"] == "asc") {
                                            array_multisort($vc_array_nickname, SORT_ASC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["comp_name"] == "desc") {
                                            array_multisort($vc_array_nickname, SORT_DESC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["contact"] == "asc") {
                                            array_multisort($vc_array_contact, SORT_ASC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["contact"] == "desc") {
                                            array_multisort($vc_array_contact, SORT_DESC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["ucbzwrep"] == "asc") {
                                            array_multisort($vc_array_ucbZw, SORT_ASC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["ucbzwrep"] == "desc") {
                                            array_multisort($vc_array_ucbZw, SORT_DESC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["actstatus"] == "asc") {
                                            array_multisort($vc_array_actStatus, SORT_ASC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["actstatus"] == "desc") {
                                            array_multisort($vc_array_actStatus, SORT_DESC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["industry"] == "asc") {
                                            array_multisort($vc_array_industry, SORT_ASC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["industry"] == "desc") {
                                            array_multisort($vc_array_industry, SORT_DESC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["trans_sales"] == "asc") {
                                            array_multisort($vc_array_salesTrans, SORT_ASC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["trans_sales"] == "desc") {
                                            array_multisort($vc_array_salesTrans, SORT_DESC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["trans_srevenue"] == "asc") {
                                            array_multisort($vc_array_salesRevenue, SORT_ASC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["trans_srevenue"] == "desc") {
                                            array_multisort($vc_array_salesRevenue, SORT_DESC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["trans_pur"] == "asc") {
                                            array_multisort($vc_array_purchaseTrans, SORT_ASC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["trans_pur"] == "desc") {
                                            array_multisort($vc_array_purchaseTrans, SORT_DESC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["trans_payment"] == "asc") {
                                            array_multisort($vc_array_total_purchasePayments, SORT_ASC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["trans_payment"] == "desc") {
                                            array_multisort($vc_array_total_purchasePayments, SORT_DESC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["parentc"] == "asc") {
                                            array_multisort($vc_array_total_parent, SORT_ASC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["parentc"] == "desc") {
                                            array_multisort($vc_array_total_parent, SORT_DESC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["next_step"] == "asc") {
                                            array_multisort($vc_array_next_step, SORT_ASC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["next_step"] == "desc") {
                                            array_multisort($vc_array_next_step, SORT_DESC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["last_date"] == "asc") {
                                            array_multisort($vc_array_last_date, SORT_ASC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["last_date"] == "desc") {
                                            array_multisort($vc_array_last_date, SORT_DESC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["next_date"] == "asc") {
                                            array_multisort($vc_array_next_date, SORT_ASC, $MGArray_parent_child_data);
                                        } elseif ($_REQUEST["next_date"] == "desc") {
                                            array_multisort($vc_array_next_date, SORT_DESC, $MGArray_parent_child_data);
                                        }

                                        foreach ($MGArray_parent_child_data as $MGArraytmp2) {
                                        ?>
                                <tr valign="middle" id="tbl_div<?php echo $MGArraytmp2["compid"] ?>">
                                    <td class="tdstyle">
                                        <?php
                                                    echo $MGArraytmp2["distance"];
                                                    ?>
                                    </td>
                                    <td class="tdstyle">
                                        <a href="viewCompany.php?ID=<?php echo $MGArraytmp2["compid"] ?>"
                                            target="_blank"> <?php echo $MGArraytmp2["nickname"]; ?></a>
                                    </td>
                                    <td class="tdstyle">
                                        <?php echo $MGArraytmp2["contact"]; ?>
                                    </td>
                                    <td class="tdstyle">
                                        <?php echo $MGArraytmp2["ucb_rep"]; ?>
                                    </td>
                                    <td class="tdstyle">
                                        <?php echo $MGArraytmp2["actstatus"]; ?>
                                    </td>
                                    <td class="tdstyle">
                                        <?php echo $MGArraytmp2["industry"]; ?>
                                    </td>
                                    <td class="tdstyle">
                                        <?php echo $MGArraytmp2["sales_trans"]; ?>
                                    </td>
                                    <td class="tdstyle">
                                        <?php echo $MGArraytmp2["sales_revenue"]; ?>
                                    </td>
                                    <td class="tdstyle">
                                        <?php echo $MGArraytmp2["purchase_trans"]; ?>
                                    </td>
                                    <td class="tdstyle">
                                        <?php echo $MGArraytmp2["purchase_payment"]; ?>
                                    </td>
                                    <td class="tdstyle">
                                        <?php echo $MGArraytmp2["parent"]; ?>
                                    </td>
                                    <td class="tdstyle">
                                        <textarea name="note<?php echo $MGArraytmp2["compid"]; ?>"
                                            id="note<?php echo $MGArraytmp2["compid"]; ?>"><?php echo $MGArraytmp2["next_step"]; ?></textarea>

                                    </td>
                                    <td class="tdstyle">
                                        <?php if ($MGArraytmp2["last_date"] != "") {
                                                        echo date("m/d/Y", strtotime($MGArraytmp2["last_date"]));
                                                    } ?>
                                    </td>
                                    <td class="tdstyle">

                                        <input type="text" size="8" name="txt_next_step_dt"
                                            id="txt_next_step_dt<?php echo $MGArraytmp2["compid"] ?>"
                                            value="<?php if ($MGArraytmp2["loopid"] > 0) {
                                                                                                                            }
                                                                                                                            ?><?php if ($MGArraytmp2["next_date"] != "") {
                                                                                                                                    echo date('m/d/Y', strtotime($MGArraytmp2["next_date"]));
                                                                                                                                }
                                                                                                                                ?>">
                                        <a href="#"
                                            onclick="cal2xx.select(document.frmstatusdashboard.txt_next_step_dt<?php echo $MGArraytmp2["compid"] ?>,'dtanchor1xx<?php echo $MGArraytmp2["compid"] ?>','yyyy-MM-dd'); return false;"
                                            name="dtanchor1xx" id="dtanchor1xx<?php echo $MGArraytmp2["compid"] ?>"><img
                                                border="0" src="images/calendar.jpg"></a>

                                        <div ID="listdiv"
                                            STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                                        </div>
                                    </td>
                                    <td class="tdstyle">
                                        <input type="button" name="btnupdate" id="btnupdate" value="Update"
                                            onclick="update_details(<?php echo $MGArraytmp2["compid"] ?>,<?php echo $MGArraytmp2["distance"] ?>)">
                                    </td>
                                </tr>
                                <?php
                                        }
                                    }
                                }
                                ?>

                        </table>
                    </form>
                </td>
            </tr>
        </table>
        <div>
            <i>
                <font color="red">"END OF REPORT"</font>
            </i>
        </div>
    </div>
</body>
<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
<script LANGUAGE="JavaScript">
document.write(getCalendarStyles());
</script>
<script LANGUAGE="JavaScript">
var cal2xx = new CalendarPopup("listdiv");
cal2xx.showNavigationDropdowns();
</script>
<script type="text/javascript">
function update_details(comp_id, distance) {
    var notes_data = document.getElementById('note' + comp_id).value;
    var notes_date = document.getElementById('txt_next_step_dt' + comp_id).value;
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("tbl_div" + comp_id).innerHTML = xmlhttp.responseText;
        }
    };

    xmlhttp.open("GET", "update_note_transaction_data.php?updatedata=1&comp_id=" + comp_id + "&notes_data=" +
        encodeURIComponent(notes_data) + "&notes_date=" + encodeURIComponent(notes_date) + "&distance=" +
        encodeURIComponent(distance), true);
    xmlhttp.send();
}
</script>

</html>