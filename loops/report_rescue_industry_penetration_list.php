<?php
$cron_rep_flg = "";

if ($_REQUEST["cronrun"] == "yes") {
  $cron_rep_flg = "yes";
} else {
  $cron_rep_flg = "no";
}

session_start();
if ($cron_rep_flg == "no") {
  require("inc/header_session.php");
}
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

	$time = strtotime(Date('Y-m-d'));

	if( $_REQUEST["date_from"] !="" && $_REQUEST["date_to"] !=""){
		$st_friday_last = date("m/d/Y", strtotime($_REQUEST["date_from"]));
		$st_thursday_last = date("m/d/Y", strtotime($_REQUEST["date_to"]));
		$in_dt_range = "yes";
	}else{
		
		if (date('l',$time) != "Friday") {
			$st_friday = strtotime('last friday', $time);
		} else {
			$st_friday = $time;
		}
		$st_friday_last = '01/01/' . date('Y');
		$st_thursday_last = Date('m/d/Y');
	}
	
	if ($cron_rep_flg == "yes") {
		$st_friday_last = '01/01/' . date('Y');
		$st_thursday_last = Date('m/d/Y');
	}
	
$rowcolor = "#E4E4E4";
?>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
  <title>Industry Penetration Report - Purchasing Records</title>
  <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap"
    rel="stylesheet">
  <LINK rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
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

    table.table_style {
      margin: 15px 0;
      width: 70%;
      white-space: nowrap;
    }

    table.table_style tr td {
      padding: 5px;
      font-family: Arial, Helvetica, sans-serif;
      font-size: x-small;
    }

    table.table_style tr:nth-child(2) td {
      font-weight: bold;
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
  </style>
</head>
<?php if ($cron_rep_flg == "no") { ?>
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

      document.getElementById("light").innerHTML =
        "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center>" +
        formtype + "</center><br/> <embed src='" + filename + "' width='800' height='800'>";
      document.getElementById('light').style.display = 'block';

      document.getElementById('light').style.left = n_left - 400 + 'px';
      document.getElementById('light').style.top = n_top + 10 + 'px';
    }

    function load_all_nocontacted(unqid, compid, scomplist, dt_from, dt_to) {
      //alert(scomplist);
      var selectobject = document.getElementById(unqid);
      var n_left = f_getPosition(selectobject, 'Left');
      var n_top = f_getPosition(selectobject, 'Top');

      document.getElementById('light').style.left = n_left + 10 + 'px';
      document.getElementById('light').style.top = n_top + 10 + 'px';

      document.getElementById('light').style.width = 450 + 'px';
      document.getElementById('light').style.height = 350 + 'px';
      //
      if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari

        xmlhttp = new XMLHttpRequest();
      } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById("light").innerHTML =
            "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
            xmlhttp.responseText;
          //
          document.getElementById('light').style.display = 'block';
        }
      }
      xmlhttp.open("GET", "penetration_all_contacted_list.php?showquotedata=1&compid=" + compid + "&scomplist=" +
        scomplist + "&dt_from=" + dt_from + "&dt_to=" + dt_to, true);
      xmlhttp.send();
    }

    //for quoted list
    function load_all_noquoted(unqid, compid, scomplist, dt_from, dt_to) {
      //alert(unqid);
      var selectobject = document.getElementById(unqid);
      var n_left = f_getPosition(selectobject, 'Left');
      var n_top = f_getPosition(selectobject, 'Top');

      document.getElementById('light').style.left = n_left + 10 + 'px';
      document.getElementById('light').style.top = n_top + 10 + 'px';

      document.getElementById('light').style.width = 450 + 'px';
      document.getElementById('light').style.height = 350 + 'px';
      //
      if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari

        xmlhttp = new XMLHttpRequest();
      } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById("light").innerHTML =
            "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
            xmlhttp.responseText;
          //
          document.getElementById('light').style.display = 'block';
        }
      }
      xmlhttp.open("GET", "rescue_penetration_all_quoted_list.php?showquotedata=1&compid=" + compid + "&scomplist=" +
        scomplist + "&dt_from=" + dt_from + "&dt_to=" + dt_to, true);
      xmlhttp.send();
    }

    //for quoted list
    function load_all_noofinv(unqid, compid, scomplist, dt_from, dt_to) {
      //alert(unqid);
      var selectobject = document.getElementById(unqid);
      var n_left = f_getPosition(selectobject, 'Left');
      var n_top = f_getPosition(selectobject, 'Top');

      document.getElementById('light').style.left = n_left + 10 + 'px';
      document.getElementById('light').style.top = n_top + 10 + 'px';

      document.getElementById('light').style.width = 450 + 'px';
      document.getElementById('light').style.height = 350 + 'px';
      //
      if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari

        xmlhttp = new XMLHttpRequest();
      } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById("light").innerHTML =
            "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
            xmlhttp.responseText;
          //
          document.getElementById('light').style.display = 'block';
        }
      }
      xmlhttp.open("GET", "rescue_penetration_all_quoted_list.php?noofinv=1&compid=" + compid + "&scomplist=" +
        scomplist + "&dt_from=" + dt_from + "&dt_to=" + dt_to, true);
      xmlhttp.send();
    }

    //For sold list
    function load_all_nosold(unqid, compid, scomplist, dt_from, dt_to) {
      // alert(scomplist);
      var selectobject = document.getElementById(unqid);
      var n_left = f_getPosition(selectobject, 'Left');
      var n_top = f_getPosition(selectobject, 'Top');

      document.getElementById('light').style.left = 710 + 'px';
      document.getElementById('light').style.top = n_top + 10 + 'px';

      document.getElementById('light').style.width = 450 + 'px';
      document.getElementById('light').style.height = 350 + 'px';
      //
      if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari

        xmlhttp = new XMLHttpRequest();
      } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById("light").innerHTML =
            "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
            xmlhttp.responseText;
          //
          document.getElementById('light').style.display = 'block';
        }
      }
      xmlhttp.open("GET", "rescue_penetration_all_sold_list.php?showquotedata=1&compid=" + compid + "&scomplist=" +
        scomplist + "&dt_from=" + dt_from + "&dt_to=" + dt_to, true);
      xmlhttp.send();
    }
  </script>
  <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
  <SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
  <script LANGUAGE="JavaScript">
    document.write(getCalendarStyles());
  </script>
  <script LANGUAGE="JavaScript">
    var cal2xx = new CalendarPopup("listdiv");
    cal2xx.showNavigationDropdowns();
  </script>
<?php } ?>
<link rel='stylesheet' type='text/css' href='one_style.css'>

<body>
  <?php
  if ($cron_rep_flg == "no") {
    include("inc/header.php");
  }
  ?>
  <div class="main_data_css">
    <div id="light" class="white_content"></div>
    <div id="fade" class="black_overlay"></div>
    <?php if ($cron_rep_flg == "no") { ?>
      <div class="dashboard_heading" style="float: left;">
        <div style="float: left;">
          Industry Penetration Report - Purchasing Records
          <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
            <span class="tooltiptext">This report shows the user all of the largest companies within each
              industry which generally is qualified to sell to UCB (Purchasing Records). If you are looking
              for industries which buy from UCB, then use the Industry Penetrations Report - Sales
              Records.</span>
          </div>
          <br>
        </div>
      </div>
    <?php } ?>

    <?php if ($cron_rep_flg == "no") { ?>
      <!-- <h3>Industry Penetration Report</h3> -->
      <form method="post" name="shippingtool" action="report_rescue_industry_penetration_list.php">
        <table border="0">
          <tr>
            <td>Industry:</td>
            <td>
              <?php
              $industry_id = $_REQUEST['industry_id'];
              ?>
              <select size="1" name="industry_id" id="industry_id" style="width:200px;">
                <option value="All">Select All</option>
                <?php
                $sellto_flg = 1;
                if ($haveNeed == "Need Boxes") {
                  $sellto_flg = 0;
                }

                $sql_parentrec = "(Select * from industry_master where active_flg = 1 and sellto_flg = 0) union all (select 99, 'Unclassified', 1,1,99, 0) order by sort_order";
                db_b2b();
                $view_parentrec = db_query($sql_parentrec);
                while ($rec_parentrec = array_shift($view_parentrec)) {
                  echo "<option value='" . $rec_parentrec["industry_id"] . "' ";
                  if ($rec_parentrec["industry_id"] == $industry_id)
                    echo " selected ";
                  echo " >" . $rec_parentrec["industry"] . "</option>";
                }
                ?>
              </select>
            </td>
            <td>Date Range Selector:</td>
            <td>
              From:
              <input type="text" name="date_from" id="date_from" size="10" value="<?php echo isset($_REQUEST['date_from']) ? $_REQUEST['date_from'] : $st_friday_last; ?>">
              <a href="#" onclick="cal2xx.select(document.shippingtool.date_from,'dtanchor2xx','MM/dd/yyyy'); return false;" name="dtanchor2xx" id="dtanchor2xx"><img border="0" src="images/calendar.jpg"></a>
              <div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
              To:
              <input type="text" name="date_to" id="date_to" size="10" value="<?php echo isset($_REQUEST['date_to']) ? $_REQUEST['date_to'] : $st_thursday_last; ?>">
              <a href="#" onclick="cal2xx.select(document.shippingtool.date_to,'dtanchor3xx','MM/dd/yyyy'); return false;" name="dtanchor3xx" id="dtanchor3xx"><img border="0" src="images/calendar.jpg"></a>
              <div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
            </td>
            <td>
              <input type="submit" name="btntool" value="Search" />
              <input type="hidden" name="hd_pgpost" id="hd_pgpost" value="" />
            </td>
          </tr>
        </table>
      </form>
      <div><i>To help UCB clearly see the activity and result we have achieved within each industry and across all
          industry we serve.<br><br>
          Note: Wait for <font color="red">Report</font> to complete, use the Sort option after the Report is
          completed. Rescue records are shown in <span style="background:#bcf5bc;">Green</span> </i>
      </div>
    <?php } ?>
    <?php
    if (isset($_REQUEST["date_from"]) && isset($_REQUEST["industry_id"]) && $_REQUEST['industry_id'] != 'All') {

      // function timestamp_to_date($d)
      // {
      //   $da = explode(" ", $d);
      //   $dp = explode("-", $da[0]);
      //   return $dp[1] . "/" . $dp[2] . "/" . $dp[0];
      // }

      // function timestamp_to_datetime($d)
      // {
      //   $da = explode(" ", $d);
      //   $dp = explode("-", $da[0]);
      //   $dh = explode(":", $da[1]);

      //   $x = $dp[1] . "/" . $dp[2] . "/" . $dp[0];

      //   if ($dh[0] == 12) {
      //     $x = $x . " " . ($dh[0] - 0) . ":" . $dh[1] . "PM CT";
      //   } elseif ($dh[0] == 0) {
      //     $x = $x . " 12:" . $dh[1] . "AM CT";
      //   } elseif ($dh[0] > 12) {
      //     $x = $x . " " . ($dh[0] - 12) . ":" . $dh[1] . "PM CT";
      //   } else {
      //     $x = $x . " " . ($dh[0]) . ":" . $dh[1] . "AM CT";
      //   }

      //   return $x;
      // }

      function add_date(string $givendate, int $day): string
      {
        $cd = strtotime($givendate);
        // $newdate = date('Y-m-d', mktime(date('m', $cd), date('d', $cd) + $day, date('Y', $cd)));

        $newdate = date('Y-m-d', mktime(0, 0, 0, (int)date('m', $cd), (int)date('d', $cd) + $day, (int)date('Y', $cd)));
        return $newdate;
      }

      // function date_diff_new($start, $end = "NOW")

      // {
      //   $sdate = strtotime($start);
      //   $edate = strtotime($end);

      //   $time = $edate - $sdate;
      //   if ($time >= 0 && $time <= 59) {
      //     // Seconds
      //     $timeshift = $time . ' seconds ';
      //   } else if ($time >= 60 && $time <= 3599) {
      //     // Minutes + Seconds
      //     $pmin = ($edate - $sdate) / 60;
      //     $premin = explode('.', $pmin);

      //     $presec = $pmin - $premin[0];
      //     $sec = $presec * 60;

      //     $timeshift = $premin[0] . ' min ' . round($sec, 0) . ' sec ';
      //   } elseif ($time >= 3600 && $time <= 86399) {
      //     // Hours + Minutes
      //     $phour = ($edate - $sdate) / 3600;
      //     $prehour = explode('.', $phour);

      //     $premin = $phour - $prehour[0];
      //     $min = explode('.', $premin * 60);

      //     $presec = '0.' . $min[1];
      //     $sec = $presec * 60;

      //     $timeshift = $prehour[0] . ' hrs ' . $min[0] . ' min ' . round($sec, 0) . ' sec ';
      //   } elseif ($time >= 86400) {
      //     // Days + Hours + Minutes
      //     $pday = ($edate - $sdate) / 86400;
      //     $preday = explode('.', $pday);

      //     $phour = $pday - $preday[0];
      //     $prehour = explode('.', $phour * 24);

      //     $premin = ($phour * 24) - $prehour[0];
      //     $min = explode('.', $premin * 60);

      //     $presec = '0.' . $min[1];
      //     $sec = $presec * 60;

      //     $timeshift = $preday[0];
      //   }
      //   return $timeshift;
      // }

      $in_dt_range = "no";
      if ($_REQUEST["date_from"] != "" && $_REQUEST["date_to"] != "") {
        $date_from_val = date("Y-m-d", strtotime($_REQUEST["date_from"]));
        $date_to_val = date("Y-m-d", strtotime($_REQUEST["date_to"]));
        $in_dt_range = "yes";
      }

      function getreportdata(
        int $eid,
        string $so_val,
        string $sk_val,
        string $dt_from,
        string $dt_to,
        string $industry_res
      ): void {
        global $tot_lead, $tot_lead_assign, $tot_lead_not_assign, $tot_contact, $tot_quotes_sent, $tot_deal_made;

        if ($so_val == "A") {
          $so = "D";
        } else {
          $so = "A";
        }

        if ($sk_val != "") {
          if ($eid > 0) {
            $tmp_sortorder = "";
            if ($sk_val == "dt") {
              $tmp_sortorder = "companyInfo.dateCreated";
            } elseif ($sk_val == "age") {
              $tmp_sortorder = "companyInfo.dateCreated";
            } elseif ($sk_val == "cname") {
              $tmp_sortorder = "companyInfo.company";
            } elseif ($sk_val == "qty") {
              $tmp_sortorder = "companyInfo.company";
            } elseif ($sk_val == "nname") {
              $tmp_sortorder = "companyInfo.nickname";
            } elseif ($sk_val == "nd") {
              $tmp_sortorder = "companyInfo.next_date";
            } elseif ($sk_val == "ns") {
              $tmp_sortorder = "companyInfo.next_step";
            } elseif ($sk_val == "ei") {
              $tmp_sortorder = "employees.initials";
            } elseif ($sk_val == "lc") {
              $tmp_sortorder = "companyInfo.company";
            } else {
              $tmp_sortorder = "companyInfo." . $sk_val;
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

          if ($sk_val == "dt") {
            $skey = " ORDER BY companyInfo.dateCreated";
          } elseif ($sk_val == "age") {
            $skey = " ORDER BY companyInfo.dateCreated";
          } elseif ($sk_val == "contact") {
            $skey = " ORDER BY companyInfo.contact";
          } elseif ($sk_val == "cname") {
            $skey = " ORDER BY companyInfo.company";
          } elseif ($sk_val == "nname") {
            $skey = " ORDER BY companyInfo.nickname";
          } elseif ($sk_val == "city") {
            $skey = " ORDER BY companyInfo.city";
          } elseif ($sk_val == "state") {
            $skey = " ORDER BY companyInfo.state";
          } elseif ($sk_val == "zip") {
            $skey = " ORDER BY companyInfo.zip";
          } elseif ($sk_val == "nd") {
            $skey = " ORDER BY companyInfo.next_date";
          } elseif ($sk_val == "ns") {
            $skey = " ORDER BY companyInfo.next_step";
          } elseif ($sk_val == "ei") {
            $skey = " ORDER BY employees.initials";
          } elseif ($sk_val == "lc") {
            $skey = " ORDER BY companyInfo.last_contact_date";
          }

          if ($so_val != "") {
            if ($so_val == "A") {
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

        $tmpdisplay_flg = "n";
        //companyInfo.haveNeed LIKE 'Need Boxes'
        $x = "Select companyInfo.shipCity , companyInfo.industry_id, companyInfo.noof_location, companyInfo.haveNeed, companyInfo.shipState, companyInfo.id AS I, companyInfo.howHear, companyInfo.loopid AS LID, companyInfo.contact AS C,  companyInfo.dateCreated AS D,  ";
        $x .= " companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, ";
        $x .= " companyInfo.next_step AS NS, companyInfo.last_contact_date AS LD, companyInfo.next_date AS ND, employees.initials AS EI, companyInfo.status from companyInfo LEFT OUTER JOIN ";
        //$x .= " employees ON companyInfo.assignedto = employees.employeeID Where companyInfo.haveNeed = 'Need Boxes' and companyInfo.dateCreated >= '" . $dt_from . "' and companyInfo.dateCreated <= '" . $dt_to . "' ";
        $x .= " employees ON companyInfo.assignedto = employees.employeeID Where companyInfo.haveNeed = 'Have Boxes'  AND companyInfo.status != 31";
        if ($industry_res == "All") {
          $x .= " and companyInfo.parent_child = 'Parent' GROUP BY companyInfo.id " . $skey . $sord . " ";
        } elseif ($industry_res == 99) {
          $x .= " and companyInfo.parent_child = 'Parent' and (companyInfo.industry_id = 0 or companyInfo.industry_id is null) GROUP BY companyInfo.id " . $skey . $sord . " ";
        } else {
          $x .= " and companyInfo.parent_child = 'Parent' and companyInfo.industry_id = '" . $industry_res . "' GROUP BY companyInfo.id " . $skey . $sord . " ";
        }

        db_b2b();
        $data_res = db_query($x);
        db_b2b();
        $data_res_No_Limit = db_query($x);

        if (tep_db_num_rows($data_res_No_Limit) > 0) {

    ?>
          <table width="70%" border="0" cellspacing="1" cellpadding="1" class="table_style">
            <tr>
              <td colspan="12" align="center" bgcolor="#D9F2FF">
                <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b>Industry Penetration
                    Report</b></font>
              </td>
            </tr>
            <?php
            $sorturl = "report_rescue_industry_penetration_list.php?industry_id=" . $_REQUEST['industry_id'] . "&date_from=" . $_REQUEST['date_from'] . "&date_to=" . $_REQUEST['date_to'];
            ?>
            <tr>
              <td width="21%" bgcolor="#D9F2FF">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Company Name&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=compname"><img
                      src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=compname"><img
                      src="images/sort_desc.png" width="6px;" height="12px;"></a></font>
              </td>
              <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">UCB Account Owner&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=acct_owner"><img
                      src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=acct_owner"><img
                      src="images/sort_desc.png" width="6px;" height="12px;"></a></font>
              </td>
              <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Industry&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=industry"><img
                      src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=industry"><img
                      src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font>
              </td>
              <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"># of Locations&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=noof_location"><img
                      src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=noof_location"><img
                      src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font>
              </td>
              <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"># of Locations in System&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=nooflocation_in"><img
                      src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=nooflocation_in"><img
                      src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font>
              </td>
              <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Locations Not in Sytem&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=nooflocation_notin"><img
                      src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=nooflocation_notin"><img
                      src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font>
              </td>
              <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Number Contacted&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=noocontacted"><img
                      src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=noocontacted"><img
                      src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font>
              </td>
              <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Number Have Inventory
                  Items&nbsp;<a href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=haveinv"><img
                      src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=haveinv"><img
                      src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font>
              </td>
              <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Number Quoted&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=noquoted"><img
                      src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=noquoted"><img
                      src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font>
              </td>
              <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Locations Sold&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=nosold"><img src="images/sort_asc.png"
                      width="6px;" height="12px;"></a>&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=nosold"><img
                      src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font>
              </td>
              <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Load sold&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=loadsold"><img
                      src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=loadsold"><img
                      src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font>
              </td>
              <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Total Revenue&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=totrev"><img src="images/sort_asc.png"
                      width="6px;" height="12px;"></a>&nbsp;<a
                    href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=totrev"><img
                      src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font>
              </td>
            </tr>
            <?php
            $forbillto_sellto = "";
            if (isset($_REQUEST["sort"]) && isset($_SESSION['sortarrayn']) &&  $_SESSION['sortarrayn'] != "") {
              //echo "Yes if 734";
            } else {
              $MGArray = array();
              while ($data = array_shift($data_res)) {
                $rowcolor = "#E4E4E4";
                $nickname = getnickname($data["CO"], $data["I"]);

                $industry = "";
                $nooflocation = 0;
                if ($data["industry_id"] == 99) {
                  $sql_parentrec = "Select industry from industry_master where active_flg = 1 and sellto_flg = 0 and (industry_id = 0 or industry_id is null)";
                } else {
                  $sql_parentrec = "Select industry from industry_master where active_flg = 1 and sellto_flg = 0 and industry_id = " . $data["industry_id"];
                }
                db_b2b();
                $view_parentrec = db_query($sql_parentrec);
                while ($rec_parentrec = array_shift($view_parentrec)) {
                  $industry = $rec_parentrec["industry"];
                }

                $scomp_list = "";
                $scomp_loopid_list = "";
                $scomp_list_l = "";
                $scomp_loopid_list_l = "";
                $sql_child = "SELECT ID, loopid FROM companyInfo where companyInfo.parent_child = 'Child' and companyInfo.status != 31 and parent_comp_id = " . $data["I"];
                db_b2b();
                $data_result = db_query($sql_child);
                while ($data_final = array_shift($data_result)) {
                  $scomp_list = $scomp_list . $data_final["ID"] . ",";
                  $scomp_list_l = $scomp_list_l . $data_final["ID"] . "-";

                  if ($data_final["loopid"] > 0) {
                    $scomp_loopid_list = $scomp_loopid_list . $data_final["loopid"] . ",";
                    $scomp_loopid_list_l = $scomp_loopid_list_l . $data_final["loopid"] . "-";
                  }
                  $nooflocation = $nooflocation + 1;
                }
                if ($scomp_list != "") {
                  $scomp_list = substr($scomp_list, 0, strlen($scomp_list) - 1);
                  $scomp_list_l = substr($scomp_list_l, 0, strlen($scomp_list_l) - 1);
                  //echo "scomp:-".$scomp_list_l."<br>";
                }
                if ($scomp_loopid_list != "") {
                  $scomp_loopid_list = substr($scomp_loopid_list, 0, strlen($scomp_loopid_list) - 1);
                  $scomp_loopid_list_l = substr($scomp_loopid_list_l, 0, strlen($scomp_loopid_list_l) - 1);
                  //echo "scomp loop:-".$scomp_loopid_list."<br>";
                }

                $nooflocation_notin = (int)$data["noof_location"] - $nooflocation;

                $noocontacted = 0;
                $noquoted = 0;
                $nosold = 0;
                $loadsold = 0;
                $totrev = 0;
                $noquoted1 = 0;
                $noofinvitem = 0;
                if ($scomp_list != "") {
                  //$sql_child = "SELECT companyID, count(*) as cnt FROM CRM WHERE type in ('phone', 'email') and companyID in (" . $scomp_list . ") and timestamp BETWEEN '" . Date("Y-m-d 00:00:00" , strtotime($dt_from)) . "' and '" . Date("Y-m-d 00:00:00" , strtotime($dt_to)) . "'";

                  $sql_child = "SELECT Distinct companyID FROM CRM WHERE duplicate_added_by_system = 0 and type in ('phone', 'email') and companyID in (" . $scomp_list . ") and timestamp BETWEEN '" . Date("Y-m-d 00:00:00", strtotime($dt_from)) . "' and '" . Date("Y-m-d 00:00:00", strtotime($dt_to)) . "'";

                  if ($data["I"] == 145657) {
                    //	echo $sql_child . "<br>";
                  }
                  db_b2b();
                  $data_result = db_query($sql_child);
                  $noocontacted = tep_db_num_rows($data_result);

                  $sql_child = "SELECT Distinct companyID FROM quote WHERE qstatus !=2 and companyID in (" . $scomp_list . ") and quoteDate BETWEEN '" . Date("Y-m-d 23:59:00", strtotime($dt_from)) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($dt_to)) . "'";
                  db_b2b();
                  $data_result = db_query($sql_child);
                  $noquoted = tep_db_num_rows($data_result);
                  while ($data_final = array_shift($data_result)) {
                    $noquoted1 = $noquoted1 + 1;
                  }

                  if ($scomp_loopid_list != "") {
                    $sql_child = "SELECT vendor_b2b_rescue FROM inventory WHERE vendor_b2b_rescue in (" . $scomp_loopid_list . ") group by vendor_b2b_rescue";
                    db_b2b();
                    $data_result = db_query($sql_child);
                    $noquoted = tep_db_num_rows($data_result);
                    while ($data_final = array_shift($data_result)) {
                      $noofinvitem = $noofinvitem + 1;
                    }
                  }

                  if ($scomp_loopid_list != "") {
                    $sql_child = "SELECT warehouse_id FROM loop_transaction WHERE warehouse_id in (" . $scomp_loopid_list . ") and usr_file <> '' and transaction_date BETWEEN '" . Date("Y-m-d 23:59:00", strtotime($dt_from)) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($dt_to)) . "' group by warehouse_id";
                    db();
                    $data_result = db_query($sql_child);
                    while ($data_final = array_shift($data_result)) {
                      $nosold = $nosold + 1;
                    }

                    $sql_child = "SELECT count(*) as cnt FROM loop_transaction WHERE warehouse_id in (" . $scomp_loopid_list . ") and usr_file <> '' and transaction_date BETWEEN '" . Date("Y-m-d 23:59:00", strtotime($dt_from)) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($dt_to)) . "'";
                    db();
                    $data_result = db_query($sql_child);
                    while ($data_final = array_shift($data_result)) {
                      $loadsold = $loadsold + $data_final["cnt"];
                    }

                    $sql_child = "SELECT usr_amount FROM loop_transaction inner join loop_seller_payment on loop_seller_payment.trans_rec_id = loop_transaction.id WHERE loop_transaction.warehouse_id in (" . $scomp_loopid_list . ") and STR_TO_DATE(add_date, '%m/%d/%Y') BETWEEN '" . Date("Y-m-d 23:59:00", strtotime($dt_from)) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($dt_to)) . "'";
                    db();
                    $data_result = db_query($sql_child);
                    while ($data_final = array_shift($data_result)) {
                      $totrev = $totrev + $data_final["amount"];
                    }
                  }
                  $compid = $data["I"];
                }
                $unqid = $unqid + 1;


            ?>
                <tr valign="middle">
                  <td width="21%" bgcolor="<?php echo $rowcolor ?>"><a target="_blank"
                      href="viewCompany.php?ID=<?php echo $data["I"] ?>">
                      <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo $nickname; ?><?php if ($data["LID"] > 0) echo "</b>"; ?>
                      </font>
                    </a></td>
                  <td width="15%" bgcolor="<?php echo $rowcolor ?>" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["EI"] ?></font>
                  </td>
                  <td width="15%" bgcolor="<?php echo $rowcolor ?>" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $industry ?></font>
                  </td>
                  <td bgcolor="<?php echo $rowcolor ?>" align="center"><?php echo $data["noof_location"] ?></td>
                  <td width="15%" bgcolor="<?php echo $rowcolor ?>" align="center"><?php echo $nooflocation ?></td>
                  <td width="15%" bgcolor="<?php echo $rowcolor ?>" align="center"><?php echo $nooflocation_notin ?></td>
                  <td width="10%" bgcolor="<?php echo $rowcolor ?>" align="center">
                    <a href='#' id='<?php echo $unqid; ?>'
                      onclick="load_all_nocontacted('<?php echo $unqid; ?>', '<?php echo $data["I"] ?>','<?php echo $scomp_list_l; ?>', '<?php echo $dt_from; ?>', '<?php echo $dt_to; ?>'); return false;"><?php echo $noocontacted ?></a>
                  </td>
                  <td width="10%" bgcolor="<?php echo $rowcolor ?>" align="center">
                    <a href='#' id='<?php echo "inv" . $unqid; ?>'
                      onclick="load_all_noofinv('<?php echo "inv" . $unqid; ?>', '<?php echo $data["I"] ?>','<?php echo $scomp_loopid_list; ?>', '<?php echo $dt_from; ?>', '<?php echo $dt_to; ?>'); return false;"><?php echo $noofinvitem ?></a>
                  </td>
                  <td width="10%" bgcolor="<?php echo $rowcolor ?>" align="center">
                    <a href='#' id='<?php echo "q" . $unqid; ?>'
                      onclick="load_all_noquoted('<?php echo "q" . $unqid; ?>', '<?php echo $data["I"] ?>','<?php echo $scomp_list_l; ?>', '<?php echo $dt_from; ?>', '<?php echo $dt_to; ?>'); return false;"><?php echo $noquoted ?></a>
                  </td>
                  <td width="10%" bgcolor="<?php echo $rowcolor ?>" align="center">
                    <a href='#' id='<?php echo "s" . $unqid; ?>'
                      onclick="load_all_nosold('<?php echo "s" . $unqid; ?>', '<?php echo $data["I"] ?>','<?php echo $scomp_loopid_list_l; ?>', '<?php echo $dt_from; ?>', '<?php echo $dt_to; ?>'); return false;"><?php echo $nosold ?></a>
                  </td>
                  <td width="10%" bgcolor="<?php echo $rowcolor ?>" align="center"><?php echo $loadsold ?></td>
                  <td width="10%" bgcolor="<?php echo $rowcolor ?>" align="right">$<?php echo number_format($totrev, 2) ?>
                  </td>
                </tr>
              <?php
                $MGArray[] = array(
                  'compid' => $compid,
                  'nickname' => $nickname,
                  'acct_owner' => $data["EI"],
                  'industry' => $industry,
                  'noof_location' => $data["noof_location"],
                  'nooflocation_in' => $nooflocation,
                  'noofinvitem' => $noofinvitem,
                  'nooflocation_notin' => $nooflocation_notin,
                  'noocontacted' => $noocontacted,
                  'noquoted' => $noquoted,
                  'nosold' => $nosold,
                  'loadsold' => $loadsold,
                  'totrev' => $totrev,
                  'lid' => $data["LID"],
                  'scomp_list_l' => $data["scomp_list_l"],
                  'scomp_loopid_list_l' => $data["scomp_loopid_list_l"],
                  'unqid' => $data["unqid"]
                );
              } // of the inactive or reactive if

              $_SESSION['sortarrayn'] = $MGArray;
              //echo $_SESSION['sortarrayn'] ;
            }
            //---------------------------------------------------------
            //Sort data
            //
            if (isset($_REQUEST["sort"])) {

              $MGArray = $_SESSION['sortarrayn'];
              //print_r($MGArray);

              /*foreach ($MGArray_data as $key => $row) { 
              $vc_array_compid[$key] = $row['compid']; 
              $vc_array_nickname[$key] = $row['nickname']; 
              $vc_array_acct_owner[$key] = $row['acct_owner']; 
              $vc_array_industry[$key] = $row['industry']; 
              $vc_array_noof_location[$key] = $row['noof_location']; 
              $vc_array_nooflocation_in[$key] = $row['nooflocation_in']; 
              $vc_array_nooflocation_notin[$key] = $row['$nooflocation_notin']; 
              $vc_array_noocontacted[$key] = $row['noocontacted']; 
              $vc_array_noquoted[$key] = $row['noquoted']; 
              $vc_array_nosold[$key] = $row['nosold']; 
              $vc_array_loadsold[$key] = $row['loadsold'];
              $vc_array_totrev[$key] = $row['totrev'];
              $vc_array_lid[$key] = $row['lid'];
          }//End foreach array*/
              //
              if ($_REQUEST['sort'] == "compname") {
                $MGArraysort_I = array();

                foreach ($MGArray as $MGArraytmp) {
                  $MGArraysort_I[] = $MGArraytmp['nickname'];
                }

                if ($_REQUEST['sort_order_pre'] == "ASC") {
                  array_multisort($MGArraysort_I, SORT_ASC, $MGArray);
                }
                if ($_REQUEST['sort_order_pre'] == "DESC") {
                  array_multisort($MGArraysort_I, SORT_DESC, $MGArray);
                }
                foreach ($MGArray as $MGArraytmp2) {
                  //echo $MGArraytmp2["nickname"];
                }
              }

              if ($_REQUEST['sort'] == "compid") {
                $MGArraysort_I = array();

                foreach ($MGArray as $MGArraytmp) {
                  $MGArraysort_I[] = $MGArraytmp['compid'];
                }

                if ($_REQUEST['sort_order_pre'] == "ASC") {
                  array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
                }
                if ($_REQUEST['sort_order_pre'] == "DESC") {
                  array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
                }
              }

              if ($_REQUEST['sort'] == "acct_owner") {
                $MGArraysort_I = array();

                foreach ($MGArray as $MGArraytmp) {
                  $MGArraysort_I[] = $MGArraytmp['acct_owner'];
                }

                if ($_REQUEST['sort_order_pre'] == "ASC") {
                  array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
                }
                if ($_REQUEST['sort_order_pre'] == "DESC") {
                  array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
                }
              }

              if ($_REQUEST['sort'] == "industry") {
                $MGArraysort_I = array();

                foreach ($MGArray as $MGArraytmp) {
                  $MGArraysort_I[] = $MGArraytmp['industry'];
                }

                if ($_REQUEST['sort_order_pre'] == "ASC") {
                  array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
                }
                if ($_REQUEST['sort_order_pre'] == "DESC") {
                  array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
                }
              }

              if ($_REQUEST['sort'] == "noof_location") {
                $MGArraysort_I = array();

                foreach ($MGArray as $MGArraytmp) {
                  $MGArraysort_I[] = $MGArraytmp['noof_location'];
                }

                if ($_REQUEST['sort_order_pre'] == "ASC") {
                  array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
                }
                if ($_REQUEST['sort_order_pre'] == "DESC") {
                  array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
                }
              }

              if ($_REQUEST['sort'] == "nooflocation_in") {
                $MGArraysort_I = array();

                foreach ($MGArray as $MGArraytmp) {
                  $MGArraysort_I[] = $MGArraytmp['nooflocation_in'];
                }

                if ($_REQUEST['sort_order_pre'] == "ASC") {
                  array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
                }
                if ($_REQUEST['sort_order_pre'] == "DESC") {
                  array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
                }
              }

              if ($_REQUEST['sort'] == "nooflocation_notin") {
                $MGArraysort_I = array();

                foreach ($MGArray as $MGArraytmp) {
                  $MGArraysort_I[] = $MGArraytmp['nooflocation_notin'];
                }

                if ($_REQUEST['sort_order_pre'] == "ASC") {
                  array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
                }
                if ($_REQUEST['sort_order_pre'] == "DESC") {
                  array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
                }
              }

              if ($_REQUEST['sort'] == "noocontacted") {
                $MGArraysort_I = array();

                foreach ($MGArray as $MGArraytmp) {
                  $MGArraysort_I[] = $MGArraytmp['noocontacted'];
                }

                if ($_REQUEST['sort_order_pre'] == "ASC") {
                  array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
                }
                if ($_REQUEST['sort_order_pre'] == "DESC") {
                  array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
                }
              }

              if ($_REQUEST['sort'] == "haveinv") {
                $MGArraysort_I = array();

                foreach ($MGArray as $MGArraytmp) {
                  $MGArraysort_I[] = $MGArraytmp['noofinvitem'];
                }

                if ($_REQUEST['sort_order_pre'] == "ASC") {
                  array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
                }
                if ($_REQUEST['sort_order_pre'] == "DESC") {
                  array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
                }
              }

              if ($_REQUEST['sort'] == "noquoted") {
                $MGArraysort_I = array();

                foreach ($MGArray as $MGArraytmp) {
                  $MGArraysort_I[] = $MGArraytmp['noquoted'];
                }

                if ($_REQUEST['sort_order_pre'] == "ASC") {
                  array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
                }
                if ($_REQUEST['sort_order_pre'] == "DESC") {
                  array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
                }
              }
              if ($_REQUEST['sort'] == "nosold") {
                $MGArraysort_I = array();

                foreach ($MGArray as $MGArraytmp) {
                  $MGArraysort_I[] = $MGArraytmp['nosold'];
                }

                if ($_REQUEST['sort_order_pre'] == "ASC") {
                  array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
                }
                if ($_REQUEST['sort_order_pre'] == "DESC") {
                  array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
                }
              }
              if ($_REQUEST['sort'] == "loadsold") {
                $MGArraysort_I = array();

                foreach ($MGArray as $MGArraytmp) {
                  $MGArraysort_I[] = $MGArraytmp['loadsold'];
                }

                if ($_REQUEST['sort_order_pre'] == "ASC") {
                  array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
                }
                if ($_REQUEST['sort_order_pre'] == "DESC") {
                  array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
                }
              }
              if ($_REQUEST['sort'] == "totrev") {
                $MGArraysort_I = array();

                foreach ($MGArray as $MGArraytmp) {
                  $MGArraysort_I[] = $MGArraytmp['totrev'];
                }

                if ($_REQUEST['sort_order_pre'] == "ASC") {
                  array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
                }
                if ($_REQUEST['sort_order_pre'] == "DESC") {
                  array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
                }
              }


              //Display sorted data in the table
              $unqid = 0;
              foreach ($MGArray as $MGArraytmp2) {
                //get all child comp list
				$rowcolor = "#E4E4E4";
                $scomp_list_l = "";
                $scomp_loopid_list_l = "";
                $scomp_loopid_list = "";
                $sql_child = "SELECT ID, loopid FROM companyInfo where companyInfo.parent_child = 'Child' and companyInfo.status != 31  and parent_comp_id = '" . $MGArraytmp2["compid"] . "'";
                db_b2b();
                $data_result = db_query($sql_child);
                while ($data_final = array_shift($data_result)) {

                  $scomp_list_l = $scomp_list_l . $data_final["ID"] . "-";

                  if ($data_final["loopid"] > 0) {
                    $scomp_loopid_list = $scomp_loopid_list . $data_final["loopid"] . ",";
                    $scomp_loopid_list_l = $scomp_loopid_list_l . $data_final["loopid"] . "-";
                  }
                  $nooflocation = $nooflocation + 1;
                }
                if ($scomp_list != "") {
                  // $scomp_list = substr($scomp_list, 0, strlen($scomp_list) - 1);
                  $scomp_list_l = substr($scomp_list_l, 0, strlen($scomp_list_l) - 1);
                  //echo "scomp:-".$scomp_list_l."<br>";
                }
                if ($scomp_loopid_list != "") {
                  $scomp_loopid_list = substr($scomp_loopid_list, 0, strlen($scomp_loopid_list) - 1);
                  $scomp_loopid_list_l = substr($scomp_loopid_list_l, 0, strlen($scomp_loopid_list_l) - 1);
                }
                //
                $unqid = $unqid + 1;
              ?>
                <tr valign="middle">
                  <td width="21%" bgcolor="<?php echo $rowcolor ?>"><a target="_blank"
                      href="viewCompany.php?ID=<?php echo $MGArraytmp2["compid"] ?>">
                      <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php if ($MGArraytmp2["lid"] > 0) echo "<b>"; ?><?php echo $MGArraytmp2["nickname"]; ?><?php if ($MGArraytmp2["lid"] > 0) echo "</b>"; ?>
                      </font>
                    </a></td>
                  <td width="15%" bgcolor="<?php echo $rowcolor ?>" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                      <?php echo $MGArraytmp2["acct_owner"] ?></font>
                  </td>
                  <td width="15%" bgcolor="<?php echo $rowcolor ?>" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                      <?php echo $MGArraytmp2["industry"] ?></font>
                  </td>
                  <td bgcolor="<?php echo $rowcolor ?>" align="center"><?php echo $MGArraytmp2["noof_location"] ?></td>
                  <td width="15%" bgcolor="<?php echo $rowcolor ?>" align="center">
                    <?php echo $MGArraytmp2["nooflocation_in"] ?></td>
                  <td width="15%" bgcolor="<?php echo $rowcolor ?>" align="center">
                    <?php echo $MGArraytmp2["nooflocation_notin"] ?></td>
                  <td width="10%" bgcolor="<?php echo $rowcolor ?>" align="center">
                    <a href='#' id='<?php echo $unqid; ?>'
                      onclick="load_all_nocontacted('<?php echo $unqid; ?>', '<?php echo $MGArraytmp2["compid"] ?>','<?php echo $scomp_list_l; ?>', '<?php echo $dt_from; ?>', '<?php echo $dt_to; ?>'); return false;"><?php echo $MGArraytmp2["noocontacted"] ?></a>
                  </td>
                  <td width="10%" bgcolor="<?php echo $rowcolor ?>" align="center">
                    <a href='#' id='<?php echo "inv" . $unqid; ?>'
                      onclick="load_all_noofinv('<?php echo "inv" . $unqid; ?>', '<?php echo $MGArraytmp2["compid"] ?>','<?php echo $scomp_loopid_list; ?>', '<?php echo $dt_from; ?>', '<?php echo $dt_to; ?>'); return false;"><?php echo $MGArraytmp2["noofinvitem"] ?></a>
                  </td>
                  <td width="10%" bgcolor="<?php echo $rowcolor ?>" align="center">
                    <a href='#' id='<?php echo "q" . $unqid; ?>'
                      onclick="load_all_noquoted('<?php echo "q" . $unqid; ?>', '<?php echo $MGArraytmp2["compid"] ?>','<?php echo $scomp_list_l; ?>', '<?php echo $dt_from; ?>', '<?php echo $dt_to; ?>'); return false;"><?php echo $MGArraytmp2["noquoted"] ?></a>
                  </td>
                  <td width="10%" bgcolor="<?php echo $rowcolor ?>" align="center">
                    <a href='#' id='<?php echo "s" . $unqid; ?>'
                      onclick="load_all_nosold('<?php echo "s" . $unqid; ?>', '<?php echo $MGArraytmp2["compid"] ?>','<?php echo $scomp_loopid_list_l; ?>', '<?php echo $dt_from; ?>', '<?php echo $dt_to; ?>'); return false;"><?php echo $MGArraytmp2["nosold"] ?></a>
                  </td>
                  <td width="10%" bgcolor="<?php echo $rowcolor ?>" align="center"><?php echo $MGArraytmp2["loadsold"] ?>
                  </td>
                  <td width="10%" bgcolor="<?php echo $rowcolor ?>" align="right">
                    $<?php echo number_format($MGArraytmp2["totrev"], 2) ?></td>
                </tr>
        <?php
              } //End foreach display data

              //
            } //End if (isset($_REQUEST["sort"]))

            //--------------------------------------------------------   
            echo "</table>";
          }
        }
        ?>
        <?php
        $tot_lead = 0;
        $tot_lead_assign = 0;
        $tot_lead_not_assign = 0;
        $tot_contact = 0;
        ?>
        <?php
        if (!isset($_REQUEST['sort_order_pre']) && !isset($_REQUEST['sort']) && $cron_rep_flg == "no" && date("m/d/Y", strtotime($date_from_val)) == '01/01/' . date('Y') && date("m/d/Y", strtotime($date_to_val)) == Date('m/d/Y')) {
          $rec_found = "no";
          $sql = "Select * from reports_cache_industry_penetration_rescue where industry_val = '" . $_REQUEST["industry_id"] . "'";
          db_b2b();
          $view = db_query($sql);
          while ($rec = array_shift($view)) {
            echo "<span style='font-size:14pt;'><i>Data last updated: " . timeAgo(date("m/d/Y H:i:s", strtotime($rec["sync_time"]))) . " (updates once a day)</i></span>";

            echo $rec["report_cache_str"];
          }
        } else {
			$req_so = "";
			if (isset($_REQUEST["so"])){
				$req_so = $_REQUEST["so"];
			}
			$req_sk = "";
			if (isset($_REQUEST["sk"])){
				$req_sk = $_REQUEST["sk"];
			}
          getreportdata(0, $req_so, $req_sk, $date_from_val, $date_to_val, $_REQUEST["industry_id"]);
        }

        ?>
        <?php ob_flush();
      } else if ($_REQUEST['industry_id'] == 'All') {

        //echo "<br /> ".$eid." / ".$_REQUEST["so"]." / ".$_REQUEST["sk"]." / ".$st_friday_last." / ".$st_thursday_last." / All";
        // $so_val = $so_val ?? '';
        // $sk_val = $sk_val ?? '';
        // $dt_from = $dt_from ?? '';
        // $dt_to = $dt_to ?? '';
        function getreportdata(int $eid, string $so_val, string $sk_val, string $dt_from, string $dt_to, string $industry_res): void
        {
          global $tot_lead, $tot_lead_assign, $tot_lead_not_assign, $tot_contact, $tot_quotes_sent, $tot_deal_made;

          if ($so_val == "A") {
            $so = "D";
          } else {
            $so = "A";
          }

          if ($sk_val != "") {
            if ($eid > 0) {
              $tmp_sortorder = "";
              if ($sk_val == "dt") {
                $tmp_sortorder = "companyInfo.dateCreated";
              } elseif ($sk_val == "age") {
                $tmp_sortorder = "companyInfo.dateCreated";
              } elseif ($sk_val == "cname") {
                $tmp_sortorder = "companyInfo.company";
              } elseif ($sk_val == "qty") {
                $tmp_sortorder = "companyInfo.company";
              } elseif ($sk_val == "nname") {
                $tmp_sortorder = "companyInfo.nickname";
              } elseif ($sk_val == "nd") {
                $tmp_sortorder = "companyInfo.next_date";
              } elseif ($sk_val == "ns") {
                $tmp_sortorder = "companyInfo.next_step";
              } elseif ($sk_val == "ei") {
                $tmp_sortorder = "employees.initials";
              } elseif ($sk_val == "lc") {
                $tmp_sortorder = "companyInfo.company";
              } else {
                $tmp_sortorder = "companyInfo." . $sk_val;
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

            if ($sk_val == "dt") {
              $skey = " ORDER BY companyInfo.dateCreated";
            } elseif ($sk_val == "age") {
              $skey = " ORDER BY companyInfo.dateCreated";
            } elseif ($sk_val == "contact") {
              $skey = " ORDER BY companyInfo.contact";
            } elseif ($sk_val == "cname") {
              $skey = " ORDER BY companyInfo.company";
            } elseif ($sk_val == "nname") {
              $skey = " ORDER BY companyInfo.nickname";
            } elseif ($sk_val == "city") {
              $skey = " ORDER BY companyInfo.city";
            } elseif ($sk_val == "state") {
              $skey = " ORDER BY companyInfo.state";
            } elseif ($sk_val == "zip") {
              $skey = " ORDER BY companyInfo.zip";
            } elseif ($sk_val == "nd") {
              $skey = " ORDER BY companyInfo.next_date";
            } elseif ($sk_val == "ns") {
              $skey = " ORDER BY companyInfo.next_step";
            } elseif ($sk_val == "ei") {
              $skey = " ORDER BY employees.initials";
            } elseif ($sk_val == "lc") {
              $skey = " ORDER BY companyInfo.last_contact_date";
            }

            if ($so_val != "") {
              if ($so_val == "A") {
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

          $tmpdisplay_flg = "n";
          //companyInfo.haveNeed LIKE 'Need Boxes'
          /**************************************************************************/
        ?>
          <table width="900px" border="0" cellspacing="1" cellpadding="1" class="table_style">
            <tr>
              <td colspan="12" align="center" bgcolor="#D9F2FF">
                <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b>Industry Penetration
                    Report</b></font>
              </td>
            </tr>
            <?php
            $sorturl = "report_industry_penetration_list.php?industry_id=" . $_REQUEST['industry_id'] . "&date_from=" . $_REQUEST['date_from'] . "&date_to=" . $_REQUEST['date_to'];
            ?>
            <tr>
              <td align="center" bgcolor="#D9F2FF">
                <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">&nbsp;</font>
              </td>
              <td align="center" bgcolor="#D9F2FF">
                <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">&nbsp;</font>
              </td>
              <td align="center" bgcolor="#D9F2FF">
                <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">&nbsp;</font>
              </td>
              <td align="center" bgcolor="#D9F2FF">
                <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">From</font>
              </td>
              <td align="center" bgcolor="#D9F2FF">
                <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">
                  <?php
                  //echo "here -> " . $dt_from;
                  echo date('m/d/Y', strtotime($dt_from));
                  ?></font>
              </td>
              <td align="center" bgcolor="#D9F2FF">
                <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">To</font>
              </td>
              <td align="center" bgcolor="#D9F2FF">
                <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">
                  <?php echo date('m/d/Y', strtotime($dt_to)); ?></font>
              </td>
              <td align="center" bgcolor="#D9F2FF">
                <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">&nbsp;</font>
              </td>
              <td align="center" bgcolor="#D9F2FF">
                <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">&nbsp;</font>
              </td>
              <td align="center" bgcolor="#D9F2FF">
                <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">&nbsp;</font>
              </td>
              <td align="center" bgcolor="#D9F2FF">
                <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">&nbsp;</font>
              </td>
              <!-- <td align="center" bgcolor="#D9F2FF"><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">&nbsp;</font></td> -->
            </tr>
            <tr>
              <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Industry
                </font>
              </td>
              <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"># of <br />Locations
                </font>
              </td>
              <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"># of <br />Locations <br />in
                  System
                </font>
              </td>
              <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"># of <br />Locations
                  <br />Contacted
                </font>
              </td>
              <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"># of Locations
                  <br />Contacted /<br># of Locations <br />in System (%)
                </font>
              </td>
              <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Number <br />Quoted
                </font>
              </td>
              <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Number <br />Have Inventory
                  Items
                </font>
              </td>
              <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Locations <br />Sold
                </font>
              </td>
              <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Locations sold/ <br /># of
                  location <br>in system (%)
                </font>
              </td>
              <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Load <br />sold
                </font>
              </td>
              <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Total Revenue
                </font>
              </td>
            </tr>
            <?php
            //and companyInfo.industry_id = '" . $industry_sel_one . "' 
            $x = "SELECT companyInfo.shipCity , companyInfo.industry_id, companyInfo.noof_location, companyInfo.haveNeed, companyInfo.shipState, companyInfo.ID AS I, companyInfo.howHear, companyInfo.loopid AS LID, companyInfo.contact AS C,  companyInfo.dateCreated AS D, companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_contact_date AS LD, companyInfo.next_date AS ND, employees.initials AS EI, companyInfo.status from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID WHERE companyInfo.haveNeed = 'Have Boxes'  AND companyInfo.status != 31 and companyInfo.parent_child = 'Parent' GROUP BY companyInfo.id " . $skey . $sord . " ";
            db_b2b();
            $data_res = db_query($x);
            $res_1 = array();
            foreach ($data_res as $data_resK => $data_resV) {
              $res_1[$data_resV['industry_id']][] = $data_resV;
            }
            //echo "<pre> res_1 -> "; print_r($res_1); echo "</pre>";


            $sql_child = "SELECT ID, loopid, parent_comp_id, parent_child FROM companyInfo WHERE companyInfo.status != 31 and (parent_child = 'Child' OR parent_child ='Parent') ORDER BY ID ASC ";
            db_b2b();
            $resParentChildCI = db_query($sql_child);

            $arrParentChildCI = array();
            foreach ($resParentChildCI as $resParentChildCIK => $resParentChildCIVal) {
              $arrParentChildCI[$resParentChildCIVal['parent_comp_id']][] = $resParentChildCIVal;
            }
            //echo "<pre> arrParentChildCI -> "; print_r($arrParentChildCI); echo "</pre>";


            $sql_child = "SELECT Distinct companyID FROM CRM WHERE duplicate_added_by_system = 0 and type in ('phone', 'email') and timestamp BETWEEN '" . Date("Y-m-d 00:00:00", strtotime($dt_from)) . "' and '" . Date("Y-m-d 00:00:00", strtotime($dt_to)) . "'";
            db_b2b();
            $resCRM = db_query($sql_child);


            $sql_child = "SELECT Distinct companyID FROM quote WHERE qstatus !=2 and quoteDate BETWEEN '" . Date("Y-m-d 23:59:00", strtotime($dt_from)) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($dt_to)) . "'";
            db_b2b();
            $resQuote = db_query($sql_child);

            $sql_child = "SELECT warehouse_id FROM loop_transaction WHERE usr_file <> '' and transaction_date BETWEEN '" . Date("Y-m-d 23:59:00", strtotime($dt_from)) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($dt_to)) . "' group by warehouse_id";
            db();
            $resLTBWarehouse = db_query($sql_child);


            $sql_child = "SELECT id, warehouse_id FROM loop_transaction WHERE usr_file <> '' and transaction_date BETWEEN '" . Date("Y-m-d 23:59:00", strtotime($dt_from)) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($dt_to)) . "'";
            db();
            $res = db_query($sql_child);
            $resLTBCnt = array();
            foreach ($res as $resK => $resV) {
              $resLTBCnt[$resV['warehouse_id']][] = $resV['id'];
            }

            //loop_transaction.warehouse_id in (" . $scomp_loopid_list . ") and
            $sql_child = "SELECT loop_transaction.usr_amount, loop_transaction.warehouse_id FROM loop_transaction INNER JOIN loop_seller_payment ON loop_seller_payment.trans_rec_id = loop_transaction.id WHERE  STR_TO_DATE(add_date, '%m/%d/%Y') BETWEEN '" . Date("Y-m-d 23:59:00", strtotime($dt_from)) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($dt_to)) . "'";
            db();
            $res = db_query($sql_child);

            $resLTBTotal = array();
            foreach ($res as $resK => $resV) {
              $resLTBTotal[$resV['warehouse_id']][] = $resV['usr_amount'];
            }

            $sql_parentrec = "SELECT * FROM industry_master WHERE active_flg = 1 AND sellto_flg = 0 ORDER BY sort_order";
            db_b2b();
            $view_parentrec = db_query($sql_parentrec);

            while ($rec_parentrec = array_shift($view_parentrec)) {
              $industry_sel_one = $rec_parentrec["industry_id"];
              $industry_name_one = $rec_parentrec["industry"];

              $data_res = $res_1[$rec_parentrec["industry_id"]];

              $data_res_No_Limit = $res_1[$rec_parentrec["industry_id"]];

              //echo "<br /> num rows -> ".tep_db_num_rows($data_res_No_Limit)." / ".$rec_parentrec["industry_id"];

              $total_nooflocation_intable = $total_nooflocation_insystem = 0;
              $total_nooflocation_notinsystem = $total_nosold = $total_noofinvitem = $total_loadsold = 0;
              $total_totrev =  $total_profitval = $total_noocontacted = 0;
              $total_noquoted =  0;

              if (tep_db_num_rows($data_res_No_Limit) > 0) {
                $forbillto_sellto = "";
                $MGArray = array();

                while ($data = array_shift($data_res)) {
                  $rowcolor = "#E4E4E4";

                  $nickname = getnickname($data["CO"], $data["I"]);

                  $industry = "";
                  $nooflocation = 0;

                  $scomp_list = "";
                  $scomp_loopid_list = "";
                  $scomp_list_l = "";
                  $scomp_loopid_list_l = "";
                  if ($data["I"] != "") {
                    $arrParentChildOne = $arrParentChildTwo = $data_result = array();
                    $arrParentChildOne[] = $arrParentChildCI[$data["I"]];
                    if (in_array($data["I"], array_column($arrParentChildCI[0], 'ID')) == true) {
                      $keyVal[] = array_search($data["I"], array_column($arrParentChildCI[0], 'ID'));
                    }
                    $arrParentChildTwo[] = $arrParentChildCI[0][$keyVal[0]];
                    if (!empty($arrParentChildOne)) {
                      $finalParentChildOne = array();
                      foreach ($arrParentChildOne as $Key => $Val) {
                        //echo "<pre> aaa "; print_r($Val); echo "</pre>";
                        $i = 0;
                        foreach ($Val as $K => $V) {
                          $finalParentChildOne[$i]['ID'] = $V['ID'];
                          $finalParentChildOne[$i]['loopid'] = $V['loopid'];
                          $i++;
                        }
                      }
                    }
                    if (!empty($arrParentChildTwo)) {
                      $finalParentChildTwo = array();
                      $i = 0;
                      foreach ($arrParentChildTwo as $Key => $Val) {
                        $finalParentChildTwo[$i]['ID'] = $Val['ID'];
                        $finalParentChildTwo[$i]['loopid'] = $Val['loopid'];
                      }
                    }
                    $data_result = array_merge($finalParentChildOne, $finalParentChildTwo);

                    while ($data_final = array_shift($data_result)) {
                      $scomp_list = $scomp_list . $data_final["ID"] . ",";
                      $scomp_list_l = $scomp_list_l . $data_final["ID"] . "-";

                      if ($data_final["loopid"] > 0) {
                        $scomp_loopid_list = $scomp_loopid_list . $data_final["loopid"] . ",";
                        $scomp_loopid_list_l = $scomp_loopid_list_l . $data_final["loopid"] . "-";
                      }
                      $nooflocation = $nooflocation + 1;
                    }
                  }
                  if ($scomp_list != "") {
                    $scomp_list = substr($scomp_list, 0, strlen($scomp_list) - 1);
                    $scomp_list_l = substr($scomp_list_l, 0, strlen($scomp_list_l) - 1);
                    //echo "scomp:-".$scomp_list_l."<br>";
                  }
                  if ($scomp_loopid_list != "") {
                    $scomp_loopid_list = substr($scomp_loopid_list, 0, strlen($scomp_loopid_list) - 1);
                    $scomp_loopid_list_l = substr($scomp_loopid_list_l, 0, strlen($scomp_loopid_list_l) - 1);
                  }

                  $nooflocation_notin = intval($data["noof_location"]) - $nooflocation;



                  $noocontacted = 0;
                  $noquoted = 0;
                  $noofinvitem = 0;
                  $nosold = 0;
                  $loadsold = 0;
                  $totrev = 0;
                  $noquoted1 = 0;
                  $profit_val = 0;
                  if ($scomp_list != "") {

                    if ($scomp_loopid_list != "") {
                      $sql_child_HasInv = "SELECT vendor_b2b_rescue FROM inventory WHERE vendor_b2b_rescue in (" . $scomp_loopid_list . ") group by vendor_b2b_rescue";
                      db_b2b();
                      $resHasInv = db_query($sql_child_HasInv);
                      $noofinvitem = tep_db_num_rows($resHasInv);
                    }

                    $arr_scomp_list = explode(",", $scomp_list);
                    //echo "<pre> resCRM -> "; print_r($resCRM); echo "</pre>";
                    $keyCrmVal = array();
                    foreach ($arr_scomp_list as $key => $value) {
                      if (in_array($value, array_column($resCRM, 'companyID')) == true) {
                        $keyCrmVal[] = array_search($value, array_column($resCRM, 'companyID'));
                      }
                    }
                    $data_result_crm = array();
                    $i = 0;
                    foreach ($keyCrmVal as $key => $value) {
                      $data_result_crm[$i]['companyID'] = $resCRM[$value]['companyID'];
                      $i++;
                    }
                    //echo "<pre> data_result_crm -> "; print_r($data_result_crm); echo "</pre>";
                    $noocontacted = tep_db_num_rows($data_result_crm);
                    //echo "<br> noocontacted -> ".$noocontacted;

                    //echo "<pre> resQuote -> "; print_r($resQuote); echo "</pre>";
                    $keyQuoteVal = array();
                    foreach ($arr_scomp_list as $key => $value) {
                      if (in_array($value, array_column($resQuote, 'companyID')) == true) {
                        $keyQuoteVal[] = array_search($value, array_column($resQuote, 'companyID'));
                      }
                    }
                    $data_result_quote = array();
                    $i = 0;
                    foreach ($keyQuoteVal as $key => $value) {
                      $data_result_quote[$i]['companyID'] = $resQuote[$value]['companyID'];
                      $i++;
                    }
                    $noquoted = tep_db_num_rows($data_result_quote);
                    while ($data_final = array_shift($data_result_quote)) {
                      $noquoted1 = $noquoted1 + 1;
                    }

                    if ($scomp_loopid_list != "") {

                      $arr_scomp_loopid_list = explode(",", $scomp_loopid_list);
                      $keyLTBWarehouseVal = array();

                      foreach ($arr_scomp_loopid_list as $key => $value) {
                        if (in_array($value, array_column($resLTBWarehouse, 'warehouse_id')) == true) {
                          $keyLTBWarehouseVal[] = array_search($value, array_column($resLTBWarehouse, 'warehouse_id'));
                        }
                      }
                      //echo "<pre> keyLTBWarehouseVal -> "; print_r($keyLTBWarehouseVal); echo "</pre>";
                      $data_result_warehouseid = array();
                      $i = 0;
                      foreach ($keyLTBWarehouseVal as $key => $value) {
                        $data_result_warehouseid[$i]['warehouse_id'] = $resLTBWarehouse[$value]['warehouse_id'];
                        $i++;
                      }
                      while ($data_final = array_shift($data_result_warehouseid)) {
                        $nosold = $nosold + 1;
                      }

                      $arrLTBCntVal = array();
                      foreach ($arr_scomp_loopid_list as $key => $value) {
                        if ($resLTBCnt[$value] !== null) {
                          $arrLTBCntVal[$value] = count($resLTBCnt[$value]);
                        } else {
                          $arrLTBCntVal[$value] = 0;
                        }
                      }
                      $data_result[0]['cnt'] = array_sum($arrLTBCntVal);
                      while ($data_final = array_shift($data_result)) {
                        $loadsold = $loadsold + $data_final["cnt"];
                      }

                      $arrLTBTotalVal = array();
                      $row = 0;
                      foreach ($arr_scomp_loopid_list as $key => $value) {
                        //echo "<br/> value -> ".$value;
                        //$value = 787;
                        $arrLTBTotalVal[$row] = $resLTBTotal[$value];
                        $row++;
                      }
                      $test = array();
                      for ($i = 0; $i < count($arrLTBTotalVal); $i++) {
                        if (!empty($arrLTBTotalVal[$i])) {
                          //echo "<pre> arrLTBTotalVal -> ".$i; print_r($arrLTBTotalVal[$i]); echo "</pre>";
                          $test = array_merge($test, $arrLTBTotalVal[$i]);
                        }
                      }
                      $finalArrLTBTot = array();
                      $i = 0;
                      foreach ($test as $K => $V) {
                        $finalArrLTBTot[$i]['usr_amount'] = $V;
                        $i++;
                      }
                      while ($data_final = array_shift($finalArrLTBTot)) {
                        $totrev = $totrev + $data_final["usr_amount"];
                        $profit_val = 0;
                      }
                    }
                    $compid = $data["I"];
                  }

                  $unqid = $unqid + 1;

                  $total_nooflocation_intable += intval($data["noof_location"]);
                  $total_nooflocation_insystem += $nooflocation;
                  $total_nooflocation_notinsystem += $nooflocation_notin;

                  $total_noocontacted += $noocontacted;
                  $total_noquoted += $noquoted;
                  $total_nosold += $nosold;
                  $total_noofinvitem += $noofinvitem;
                  $total_loadsold += $loadsold;
                  $total_totrev += $totrev;
                  $total_profitval += $profit_val;

                  //
                } // while closed

            ?>
                <tr>
                  <td bgcolor="#E4E4E4">
                    <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
                      <?php echo $industry_name_one; ?></font>
                  </td>
                  <td bgcolor="#E4E4E4" align="center"><?php echo $total_nooflocation_intable; ?></td>
                  <td bgcolor="#E4E4E4" align="center"><?php echo $total_nooflocation_insystem; ?></td>
                  <td bgcolor="#E4E4E4" align="center"><?php echo $total_noocontacted; ?></td>
                  <td bgcolor="#E4E4E4" align="center">
                    <?php echo number_format(($total_noocontacted * 100) / $total_nooflocation_insystem, 2); ?>%
                  </td>
                  <td bgcolor="#E4E4E4" align="center"><?php echo $total_noofinvitem; ?></td>
                  <td bgcolor="#E4E4E4" align="center"><?php echo $total_noquoted; ?></td>
                  <td bgcolor="#E4E4E4" align="center"><?php echo $total_nosold; ?></td>
                  <td bgcolor="#E4E4E4" align="center">
                    <?php echo number_format(($total_nosold * 100) / $total_nooflocation_insystem, 2); ?>%</td>
                  <td bgcolor="#E4E4E4" align="center"><?php echo $total_loadsold; ?></td>
                  <td bgcolor="#E4E4E4" align="right">$<?php echo number_format($total_totrev, 2); ?></td>
                  <!-- <td bgcolor="#E4E4E4" align="right">$<?php echo number_format($total_profitval, 2); ?></td> -->
                </tr>
            <?php
                $gtotal_intable += $total_nooflocation_intable;
                $gtotal_insystem += $total_nooflocation_insystem;
                $gtotal_contacted += $total_noocontacted;
                $gtotal_noofinvitem += $total_noofinvitem;
                $gtotal_quoted += $total_noquoted;
                $gtotal_nosold += $total_nosold;
                $gtotal_losold += $total_loadsold;
                $gtotal_totrev += $total_totrev;
                $gtotal_proval += $total_profitval;

                $MGArray[] = array('industry' => $industry_name_one, 'noof_location' => $total_nooflocation_intable, 'nooflocation_in' => $total_nooflocation_insystem, 'noocontacted' => $total_noocontacted, 'noquoted' => $total_noquoted, 'nosold' => $total_nosold, 'loadsold' => $total_loadsold, 'totrev' => $total_totrev);
              }  // if record found 
            }

            $_SESSION['sortarrayn'] = $MGArray;
            // while closed


            ?>
            <tr>
              <td bgcolor="#D9F2FF" align="right">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Total</font>
              </td>
              <td bgcolor="#D9F2FF" align="center"><?php echo $gtotal_intable; ?></td>
              <td bgcolor="#D9F2FF" align="center"><?php echo $gtotal_insystem; ?></td>
              <td bgcolor="#D9F2FF" align="center"><?php echo $gtotal_contacted; ?></td>
              <td bgcolor="#D9F2FF" align="center">
                <?php echo number_format(($gtotal_contacted * 100) / $gtotal_insystem, 2); ?>%</td>
              <td bgcolor="#D9F2FF" align="center"><?php echo $gtotal_noofinvitem; ?></td>
              <td bgcolor="#D9F2FF" align="center"><?php echo $gtotal_quoted; ?></td>
              <td bgcolor="#D9F2FF" align="center"><?php echo $gtotal_nosold; ?></td>
              <td bgcolor="#D9F2FF" align="center">
                <?php echo number_format(($gtotal_nosold * 100) / $gtotal_insystem, 2); ?>%</td>
              <td bgcolor="#D9F2FF" align="center"><?php echo $gtotal_losold; ?></td>
              <td bgcolor="#D9F2FF" align="right">$<?php echo number_format($gtotal_totrev, 2); ?></td>
              <!-- <td bgcolor="#D9F2FF" align="right">$<?php echo number_format($gtotal_proval, 2); ?></td> -->
            </tr>
          </table>
      <?php
          /**************************************************************************/
        } //function getreportdata ends here.

        if (!isset($_REQUEST['sort_order_pre']) && !isset($_REQUEST['sort']) && $cron_rep_flg == "no" && date("m/d/Y", strtotime($date_from_val)) == '01/01/' . date('Y') && date("m/d/Y", strtotime($date_to_val)) == Date('m/d/Y')) {
          $rec_found = "no";
          $sql = "Select * from reports_cache_industry_penetration where industry_val = '99'";
          db_b2b();
          $view = db_query($sql);
          while ($rec = array_shift($view)) {
            echo "<span style='font-size:14pt;'><i>Data last updated: " . timeAgo(date("m/d/Y H:i:s", strtotime($rec["sync_time"]))) . " (updates once a day)</i></span>";

            echo $rec["report_cache_str"];
          }
        } else {
          $req_so = "";
          if (isset($_REQUEST["so"])){
            $req_so = $_REQUEST["so"];
          }
          $req_sk = "";
          if (isset($_REQUEST["sk"])){
            $req_sk = $_REQUEST["sk"];
          }
			
          getreportdata(0, $req_so , $req_sk, $st_friday_last ?? '', $st_thursday_last ?? '', 'All');
        }
      }
      ?>
</body>

</html>