<?php

require("inc/header_session.php");
require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

require("functions_for_report_search_table.php");
?>
<!DOCTYPE HTML>

<html>

<head>
    <title>Accounts with No Account Owner Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap"
        rel="stylesheet">
    <LINK rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
    <meta http-equiv="refresh" content="1800" />
    <link rel="stylesheet" href="sorter/style_rep.css" />
    <style type="text/css">
        .txtstyle_color {
            font-family: arial;
            font-size: 12;
            height: 16px;
            background: #ABC5DF;
        }

        .txtstyle {
            font-family: arial;
            font-size: 12;
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

        select,
        input {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #000000;
            font-weight: normal;
        }

        span.infotxt:hover {
            text-decoration: none;
            background: #ffffff;
            z-index: 6;
        }

        span.infotxt span {
            position: absolute;
            left: -9999px;
            margin: 20px 0 0 0px;
            padding: 3px 3px 3px 3px;
            z-index: 6;
        }

        span.infotxt:hover span {
            left: 45%;
            background: #ffffff;
        }

        span.infotxt span {
            position: absolute;
            left: -9999px;
            margin: 0px 0 0 0px;
            padding: 3px 3px 3px 3px;
            border-style: solid;
            border-color: black;
            border-width: 1px;
        }

        span.infotxt:hover span {
            margin: 18px 0 0 170px;
            background: #ffffff;
            z-index: 6;
        }

        span.infotxt_freight:hover {
            text-decoration: none;
            background: #ffffff;
            z-index: 6;
        }

        span.infotxt_freight span {
            position: absolute;
            left: -9999px;
            margin: 20px 0 0 0px;
            padding: 3px 3px 3px 3px;
            z-index: 6;
        }

        span.infotxt_freight:hover span {
            left: 0%;
            background: #ffffff;
        }

        span.infotxt_freight span {
            position: absolute;
            width: 850px;
            overflow: auto;
            height: 300px;
            left: -9999px;
            margin: 0px 0 0 0px;
            padding: 10px 10px 10px 10px;
            border-style: solid;
            border-color: white;
            border-width: 50px;
        }

        span.infotxt_freight:hover span {
            margin: 5px 0 0 50px;
            background: #ffffff;
            z-index: 6;
        }

        span.infotxt_freight2:hover {
            text-decoration: none;
            background: #ffffff;
            z-index: 6;
        }

        span.infotxt_freight2 span {
            position: absolute;
            left: -9999px;
            margin: 20px 0 0 0px;
            padding: 3px 3px 3px 3px;
            z-index: 6;
        }

        span.infotxt_freight2:hover span {
            left: 0%;
            background: #ffffff;
        }

        span.infotxt_freight2 span {
            position: absolute;
            width: 850px;
            overflow: auto;
            height: 300px;
            left: -9999px;
            margin: 0px 0 0 0px;
            padding: 10px 10px 10px 10px;
            border-style: solid;
            border-color: white;
            border-width: 50px;
        }

        span.infotxt_freight2:hover span {
            margin: 5px 0 0 500px;
            background: #ffffff;
            z-index: 6;
        }

        .black_overlay {
            display: none;
            position: absolute;
        }

        .white_content {
            display: none;
            position: absolute;
            padding: 5px;
            border: 2px solid black;
            background-color: white;
            overflow: auto;
            height: 600px;
            width: 850px;
            z-index: 1002;
            margin: 0px 0 0 0px;
            padding: 10px 10px 10px 10px;
            border-color: black;
            border-width: 2px;
            overflow: auto;
        }

        .nowrap_style {
            white-space: nowrap;
        }
    </style>
    <script LANGUAGE="JavaScript">

    </script>

</head>

<body>
    <?php include("inc/header.php"); ?>
    <div class="main_data_css">
        <div class="dashboard_heading" style="float: left;">
            <div style="float: left;">
                Accounts with No Account Owner Report

                <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                    <span class="tooltiptext">This report allows the user to see all company records (accounts) which
                        are still assigned to an inactive account owner.</span>
                </div><br>
            </div>
        </div>

        <!-- 

<br/>
<table border="0" >
	<tr><td width="700px" align="center" style="font-size:24pt;"><strong>Show Unassigned Lead</strong></td>
	<td width="200px" align="right"><img src="images/image001.jpg" width="70" height="70"/></td></tr>
</table >
-->
        <?php

        function showarrays(array $p): string
        {
            $z = "";
            $count = count($p);
            for ($i = 0; $i < $count - 1; $i++) {
                $z .= $p[$i] . ", ";
            }
            $z .=  $p[$i];
            return $z;
        }

        // function date_diff_new($start, $end = "NOW")
        // {
        //     $sdate = strtotime($start);
        //     $edate = strtotime($end);

        //     $time = $edate - $sdate;
        //     if ($time >= 0 && $time <= 59) {
        //         // Seconds
        //         $timeshift = $time . ' seconds ';
        //     } elseif ($time >= 60 && $time <= 3599) {
        //         // Minutes + Seconds
        //         $pmin = ($edate - $sdate) / 60;
        //         $premin = explode('.', $pmin);

        //         $presec = $pmin - $premin[0];
        //         $sec = $presec * 60;

        //         $timeshift = $premin[0] . ' min ' . round($sec, 0) . ' sec ';
        //     } elseif ($time >= 3600 && $time <= 86399) {
        //         // Hours + Minutes
        //         $phour = ($edate - $sdate) / 3600;
        //         $prehour = explode('.', $phour);

        //         $premin = $phour - $prehour[0];
        //         $min = explode('.', $premin * 60);

        //         $presec = '0.' . $min[1];
        //         $sec = $presec * 60;

        //         $timeshift = $prehour[0] . ' hrs ' . $min[0] . ' min ' . round($sec, 0) . ' sec ';
        //     } elseif ($time >= 86400) {
        //         // Days + Hours + Minutes
        //         $pday = ($edate - $sdate) / 86400;
        //         $preday = explode('.', $pday);

        //         $phour = $pday - $preday[0];
        //         $prehour = explode('.', $phour * 24);

        //         $premin = ($phour * 24) - $prehour[0];
        //         $min = explode('.', $premin * 60);

        //         $presec = '0.' . $min[1];
        //         $sec = $presec * 60;

        //         $timeshift = $preday[0];
        //     }
        //     return $timeshift;
        // }

        // function timestamp_to_date($d)
        // {

        //     $da = explode(" ", $d);
        //     $dp = explode("-", $da[0]);
        //     return $dp[1] . "/" . $dp[2] . "/" . $dp[0];
        // }

        // function timestamp_to_datetime($d)
        // {

        //     $da = explode(" ", $d);
        //     $dp = explode("-", $da[0]);
        //     $dh = explode(":", $da[1]);

        //     $x = $dp[1] . "/" . $dp[2] . "/" . $dp[0];

        //     if ($dh[0] == 12) {
        //         $x = $x . " " . ($dh[0] - 0) . ":" . $dh[1] . "PM CT";
        //     } elseif ($dh[0] == 0) {
        //         $x = $x . " 12:" . $dh[1] . "AM CT";
        //     } elseif ($dh[0] > 12) {
        //         $x = $x . " " . ($dh[0] - 12) . ":" . $dh[1] . "PM CT";
        //     } else {
        //         $x = $x . " " . ($dh[0]) . ":" . $dh[1] . "AM CT";
        //     }

        //     return $x;
        // }


        function showStatusesDashboard_new(array $arrVal, int $eid, int $limit, string $period): void
        {

            if ($_REQUEST["so"] == "A") {
                $so = "D";
            } else {
                $so = "A";
            }

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
                    } elseif ($_REQUEST["sk"] == "ei") {
                        $tmp_sortorder = "employees.initials";
                    } elseif ($_REQUEST["sk"] == "lc") {
                        $tmp_sortorder = "companyInfo.company";
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
                } elseif ($_REQUEST["sk"] == "ei") {
                    $skey = " ORDER BY employees.initials";
                } elseif ($_REQUEST["sk"] == "lc") {
                    $skey = " ORDER BY companyInfo.last_contact_date";
                }

                if ($_REQUEST["so"] != "") {
                    if ($_REQUEST["so"] == "A") {
                        $sord = " ASC";
                    } else {
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

            $flag_assignto_viewby = 0;

            $tmpdisplay_flg = "n";
            if (showarrays($arrVal) == "") {
                $dt_view_qry = "SELECT * FROM status ORDER BY sort_order";
            } elseif (showarrays($arrVal) == "onlysales") {
                $dt_view_qry = "SELECT * FROM status where (sales_flg = 1 or sales_flg = 2) order by sort_order";
            } elseif (showarrays($arrVal) == "onlypurchasing") {
                $dt_view_qry = "SELECT * FROM status where (sales_flg = 0 or sales_flg = 2) order by sort_order";
            } else {
                $dt_view_qry = "SELECT * FROM status WHERE id IN ( " . showarrays($arrVal) .  " ) ORDER BY sort_order";
            }
            db_b2b();
            $dt_view_res = db_query($dt_view_qry);
            while ($row = array_shift($dt_view_res)) {

                $main_x = "Select companyInfo.ID, companyInfo.company, companyInfo.city, companyInfo.state, employees.initials AS EI 
		from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID  
		Where companyInfo.status =" . $row["id"] . " AND  companyInfo.assignedto = 0 ";
                $main_x = $main_x . " GROUP BY companyInfo.id " . $skey . $sord . " ";

                //echo $main_x . "<br>";

                display_report_data($main_x, 'report_show_unassign_lead', $row["name"], showarrays($arrVal));
            }
        }

        ?>

        <table width="100%">
            <tr>
                <td width="80%">
                    <!-- Load the page by default with old logic - do not apply date range-->
                    <table border="0">
                        <tr>
                            <td colspan="5" align="left">
                                <form method="get" name="rpt_leaderboard" action="report_show_unassign_lead.php">
                                    <table border="0">
                                        <tr>
                                            <td>Select Status:</td>

                                            <td>
                                                <select name="sel_status">
                                                    <option value="">All Records</option>
                                                    <option value="onlysales" <?php if ($_GET["sel_status"] == "onlysales") {
                                                                                    echo " selected ";
                                                                                } ?>>Sales Records Only</option>
                                                    <option value="onlypurchasing" <?php if ($_GET["sel_status"] == "onlypurchasing") {
                                                                                        echo " selected ";
                                                                                    }
                                                                                    ?>>Purchasing Records Only</option>
                                                    <?php
                                                    $tableedit  = "SELECT * FROM status where (sales_flg = 0 or sales_flg = 1 or sales_flg = 2)order by sort_order";
                                                    db_b2b();
                                                    $dt_view_res = db_query($tableedit);
                                                    while ($row = array_shift($dt_view_res)) {
                                                    ?>
                                                        <option value="<?php echo $row["id"]; ?>" <?php if ($_GET["sel_status"] == $row["id"]) {
                                                                                                        echo " selected ";
                                                                                                    } ?>>
                                                            <?php echo $row["name"]; ?>
                                                        </option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="submit" value="Run Report">
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </td>
                        </tr>
                        <?php
                        if (isset($_GET["sel_status"]) && $_GET["sel_status"] != "") {
                        ?>
                            <tr valign="top">
                                <td valign="top">
                                    <?php
                                    $arr = array($_GET["sel_status"]);
                                    showStatusesDashboard_new($arr, $eid, 0, "all");
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                </td>
            </tr>
        </table>

    </div>

</body>

</html>