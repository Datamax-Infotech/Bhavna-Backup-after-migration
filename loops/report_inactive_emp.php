<?php 
  session_start();
  require ("inc/header_session.php");
  require ("../mainfunctions/database.php");
  require ("../mainfunctions/general-functions.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Accounts Assigned to Inactive Reps Report - Inactive Employee - Assigned company</title>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
    <LINK rel='stylesheet' type='text/css' href='css/ucb_common_style.css' >
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
    </style>
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
      
    </script>
  </head>
  <body>
    <?php  include("inc/header.php"); ?>
    <div class="main_data_css">
      <div class="dashboard_heading" style="float: left;">
        <div style="float: left;">
          Accounts Assigned to Inactive Reps Report  
          <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
            <span class="tooltiptext">This report allows the user to see all company records (accounts) which are still assigned to an inactive account owner.</span>
          </div>
          <br>
        </div>
      </div>

      <?php  
        $all_inactive_emp = "";
        ?>
      <form method="get" name="rpt_leaderboard" action="report_inactive_emp.php">
        <table>
          <tr>
            <td>Select the employee:</td>
            <td>
              <select name="employee_lst" id="employee_lst">
                <option value="0">All Inactive employee</option>
                <?php 
                  $sql = "SELECT name, initials, employeeID FROM employees where status = 'Inactive' order by name" ;
				  db_b2b(); 
				  $result = db_query($sql);
                  while ($myrowsel = array_shift($result)) {
                  	echo "<option value=".$myrowsel["employeeID"]." ";
                  
                  	if(isset($_REQUEST["employee_lst"])) 
                  	{ 
                  		if ($myrowsel["employeeID"] == $_REQUEST["employee_lst"]) echo " selected ";
                  	}
                  	echo " >". $myrowsel["name"] . "</option>";
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
      <?php 
        if (isset($_REQUEST["employee_lst"])){
        	$eid = $_REQUEST["employee_lst"];
        	//echo "LIMIT" . $limit;
        	if ($_REQUEST["so"] == "A") {
        		$so = "D"; 
        	} 	
        	else {	
        		$so = "A";
        	}
        
        	if ($_REQUEST["sk"] != "" )
        	{
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
        			}else{ 
        				$tmp_sortorder = "companyInfo." . $_REQUEST["sk"]; 
        			}
        			
        			if ($so == "A") {
        				$tmp_sort = "D"; 
        			} 	
        			else {	
        				$tmp_sort = "A";
        			}
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
        			$skey = " ORDER BY companyInfo.last_date";
        		}
        
        		if ($_REQUEST["so"] != "") {
        			if ($_REQUEST["so"] == "A") {
        				$sord = " ASC";
        			} Else {
        				$sord = " DESC";
        			}
        		} ELSE {
        			$sord = " DESC";
        		}
        	}
        	else
        	{
        		$skey = " ORDER BY companyInfo.dateCreated " ;
        		$sord = " DESC"; 
        	}
        
        	$dt_view_qry = "SELECT * FROM status ORDER BY sort_order";
			db_b2b();
        	$dt_view_res = db_query($dt_view_qry);
        	while ($row = array_shift($dt_view_res)) {
        	 
        		$x = "Select companyInfo.shipCity, companyInfo.shipState, companyInfo.id AS I,  companyInfo.loopid AS LID, companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_date AS LD, companyInfo.next_date AS ND, employees.initials AS EI from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID Where companyInfo.status =" . $row["id"];
        		if ($eid == 0) {	
        			$x = $x . " AND ( employees.status = 'Inactive' ) ";
        		}else {
        			$x = $x . " AND ( companyInfo.assignedto = " . $eid . ")";
        		}
        
        		/*if ($_REQUEST["gc"] == 1) {
        			$x = $x . " AND companyInfo.haveNeed LIKE 'Need Boxes'";
        		}
        		if ($_REQUEST["gc"] == 2) {
        			$x = $x . " AND companyInfo.haveNeed LIKE 'Have Boxes'";
        		}*/
        
        		if ($period != "all") {
        			if ($period == "today") {
        				$x = $x . " AND companyInfo.next_date = CURDATE() ";
        			}
        			if ($period == "upcoming") {
        				$x = $x . " AND (companyInfo.next_date > '" . date('Y-m-d') . "' and companyInfo.next_date <= '" . date('Y-m-d', strtotime("+7 days")) . "')";
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
        
        		$x = $x . " GROUP BY companyInfo.id " . $skey . $sord . " ";
        		//echo "<br/>" . $x . "<br/><br/>";
        		if ($limit > 0 )
        		{
        			$xL = $x . " LIMIT 0, " . $limit;
					db_b2b();
        			$data_res = db_query($xL);
					db_b2b();
        			$data_res_No_Limit = db_query($x);
        			$show = "" . $limit;
        		} else
        		{
					db_b2b();
        			$data_res = db_query($x);
					db_b2b();
        			$data_res_No_Limit = db_query($x);
        			$show = "All";
        		}
        
        		if (tep_db_num_rows($data_res_No_Limit) > 0) {
        		?>
      <table width="1300" border="0" cellspacing="1" cellpadding="1">
      <tr align="center">
        <td colspan="14" bgcolor="#FFCCCC"><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b><?php echo $row["name"] . " - Total Records: " . tep_db_num_rows($data_res_No_Limit) . " - Showing: "; ?>
          <?php  if($limit > 0) {
				if($limit > tep_db_num_rows($data_res_No_Limit)) { echo tep_db_num_rows($data_res_No_Limit); } else { echo $limit; }?> <a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?" . $_SERVER['QUERY_STRING'] . "&limit=all");?>">Show All</a>
          <?php  } else { ?>All<?php  } ?>
          </b></font>
        </td>
      </tr>
      <?php  if (1==1 OR $limit == 100000 ) { ?>
      <tr>
        <td width="5%" bgcolor="#D9F2FF"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=dt&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"] . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"] ); ?>">DATE</a> </font></td>
        <td width="5%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=age&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">AGE</a></font></td>
        <td width="10%" bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=contact&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">CONTACT</a></font></td>
        <td width="21%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=cname&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"] . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"] ); ?>">COMPANY NAME</a></font></td>
        <td width="8%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">PHONE</font></td>
        <td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=city&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">CITY</a></font></td>
        <td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=state&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">STATE</a></font></td>
        <td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=zip&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">ZIP</a></font></td>
        <td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=ns&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">Next Step</a></font></td>
        <td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=lc&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">Last<br>Communication</a></font></td>
        <td bgcolor="#D9F2FF" align="center">
          <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
            <a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=nd&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">
              Next Communication
          </font>
        </td>
        <td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=ei&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">Assigned To</font></td>
      </tr>
      <?php 
        $forbillto_sellto = "";
         while ($data = array_shift($data_res)) {
        	$forbillto_sellto = $forbillto_sellto  . $data["I"] . ", ";
        
        	if ($data["NN"] != "") {
        		$nickname = $data["NN"];
        	}else {
        		$tmppos_1 = strpos($data["CO"], "-");
        		if ($tmppos_1 != false)
        		{
        			$nickname = $data["CO"];
        		}else {
        			if ($data["shipCity"] <> "" || $data["shipState"] <> "" ) 
        			{
        				$nickname = $data["CO"] . " - " . $data["shipCity"] . ", " . $data["shipState"] ;
        			}else { $nickname = $data["CO"]; }
        		}
        	}
        	
        ?>
      <tr valign="middle">
      <td width="5%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  if ($data["LID"] > 0) echo "<b>"; ?><?php echo timestamp_to_datetime($data["D"]); 
        ?></font></td>
      <td width="5%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  if ($data["LID"] > 0) echo "<b>"; ?><?php echo date_diff_new($data["D"], "NOW");
        ?> Days</font></td>
      <td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  if ($data["LID"] > 0) echo "<b>"; ?><?php echo $data["C"]?></font></td>
      <td width="21%" bgcolor="#E4E4E4"><a href="viewCompany.php?ID=<?php echo $data["I"]?>" target="_blank"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  if($data["LID"] > 0) echo "<b>"; ?><?php  echo $nickname;?><?php  if($data["LID"] > 0) echo "</b>"; ?></font></a></td>
      <td width="3%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  if ($data["LID"] > 0) echo "<b>"; ?><?php echo $data["PH"]?></font></td>
      <td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  if ($data["LID"] > 0) echo "<b>"; ?><?php echo $data["CI"]?></font></td>
      <td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  if ($data["LID"] > 0) echo "<b>"; ?><?php echo $data["ST"]?></font></td>
      <td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  if ($data["LID"] > 0) echo "<b>"; ?><?php echo $data["ZI"]?></font></td>
      <td width="15%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  if ($data["LID"] > 0) echo "<b>"; ?><?php echo $data["NS"]?></font></td>
      <td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  if ($data["LID"] > 0) echo "<b>"; ?><?php if ($data["LD"]!="") echo date('m/d/Y',strtotime($data["LD"]));?>
      <td width="10%" <?php  if ($data["ND"] == date('Y-m-d')) { ?> bgcolor="#00FF00" <?php  } elseif ($data["ND"] < date('Y-m-d') && $data["ND"] != "") { ?> bgcolor="#FF0000" <?php  } else { ?> bgcolor="#E4E4E4"  <?php  } ?> align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  if ($data["LID"] > 0) echo "<b>"; ?><?php if ($data["ND"]!="") echo date('m/d/Y',strtotime($data["ND"]));?>
      </font></td>
      <td  bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["EI"]?></font></td>
      </tr>
      <?php 
        } // of the inactive or reactive if
        ob_flush();
        }
        echo "</table><p></p>";
        }
        }
        
        //for Bill to 
        if ($_REQUEST["searchterm"] != ""){
        	$arrFields_billto = array("title","name","address","address2","city","state","zipcode","mainphone","directphone","cellphone","email","fax");
        	
        	//$qryfor_billto = " Select dateCreated, contact, ID, phone, company, nickname, city, state, zip, next_step, last_date, next_date from companyInfo where ID in (Select companyid from b2bbillto where ";
        	$qryfor_billto = " Select companyid from b2bbillto where ";
        	
        	$i = 1;
        	$qryfor_billto = $qryfor_billto . " ( ";
        	foreach ($arrFields_billto as $nm) {
        
        		if ($i == 1 ) { ; }
        		 ELSE {
        			$qryfor_billto = $qryfor_billto . " OR ";
        		}
        		$qryfor_billto = $qryfor_billto . " b2bbillto." . $nm . " LIKE '%" . $_REQUEST["searchterm"] . "%'";
        	$i++;
        	}	
        	$qryfor_billto = $qryfor_billto . " ) " . $_REQUEST["andor"] . " ";
        
        	if ($_REQUEST["andor"] == "AND") {
        	$qryfor_billto = $qryfor_billto . " TRUE  ";
        	} else {
        	$qryfor_billto = $qryfor_billto . " FALSE  ";
        	}
        
        	if ($_REQUEST["state"] != "ALL")
        	$qryfor_billto = $qryfor_billto . " AND b2bbillto.state LIKE '" . $_REQUEST["state"] . "') ";
        
        	$qryfor_billto_str = "";
			db_b2b();
        	$result_tmp = db_query($qryfor_billto);
        	while ($myrowsel_tmp = array_shift($result_tmp)) {
        		$qryfor_billto_str = $qryfor_billto_str + $myrowsel_tmp["companyid"] . ", ";
        	}
        	if ($qryfor_billto_str != "") {
        		$qryfor_billto_str = substr($qryfor_billto_str, 0, strlen($qryfor_billto_str) - 2); 
        	}
        
        	if ($forbillto_sellto != "") {
        		$forbillto_sellto = substr($forbillto_sellto, 0, strlen($forbillto_sellto) - 2); 
        	}
        
        	//for Sell to 
        	$arrFields_sellto = array("title","name","address","address2","city","state","zipcode","mainphone","directphone","cellphone","email","fax");
        	
        	//$qryfor_sellto = " Select dateCreated, contact, ID, phone, company, nickname, city, state, zip, next_step, last_date, next_date from companyInfo where ID in (Select companyid from b2bsellto where ";
        	$qryfor_sellto = " Select companyid from b2bsellto where ";
        
        	$i = 1;
        	$qryfor_sellto = $qryfor_sellto . " ( ";
        	foreach ($arrFields_sellto as $nm) {
        
        		if ($i == 1 ) { ; }
        		 ELSE {
        			$qryfor_sellto = $qryfor_sellto . " OR ";
        		}
        		$qryfor_sellto = $qryfor_sellto . " b2bsellto." . $nm . " LIKE '%" . $_REQUEST["searchterm"] . "%'";
        	$i++;
        	}	
        	$qryfor_sellto = $qryfor_sellto . " ) " . $_REQUEST["andor"] . " ";
        
        	if ($_REQUEST["andor"] == "AND") {
        	$qryfor_sellto = $qryfor_sellto . " TRUE  ";
        	} else {
        	$qryfor_sellto = $qryfor_sellto . " FALSE  ";
        	}
        
        	if ($_REQUEST["state"] != "ALL")
        	$qryfor_sellto = $qryfor_sellto . " AND b2bsellto.state LIKE '" . $_REQUEST["state"] . "') ";	
        	
        	$qryfor_sellto_str = "";
			db_b2b();
        	$result_tmp = db_query($qryfor_sellto);
        	while ($myrowsel_tmp = array_shift($result_tmp)) {
        		$qryfor_sellto_str = $qryfor_sellto_str + $myrowsel_tmp["companyid"] . ", ";
        	}
        
        	if ($qryfor_sellto_str != "") {
        		$qryfor_sellto_str = substr($qryfor_sellto_str, 0, strlen($qryfor_sellto_str) - 2); 
        	}
        
        	//Search the details in the Bill to 
        	//$qryfor_billto = "Select dateCreated, contact, ID, phone, company, nickname, city, state, zip, next_step, last_date, next_date from companyInfo where ID in (" . $qryfor_billto_str . ") or not ( $forbillto_sellto GROUP BY companyInfo.id " . $skey . $sord . " ";
        	if ($forbillto_sellto != "") {
        		$qryfor_billto = "Select shipCity, shipState , dateCreated, contact, ID, phone, company, nickname, city, state, zip, next_step, last_date, next_date from companyInfo where ID in (" . $qryfor_billto_str . ") and ID not in ( $forbillto_sellto ) GROUP BY companyInfo.id " . $skey . $sord . " ";
        	} else {
        		$qryfor_billto = "Select shipCity, shipState, dateCreated, contact, ID, phone, company, nickname, city, state, zip, next_step, last_date, next_date from companyInfo where ID in (" . $qryfor_billto_str . ") GROUP BY companyInfo.id " . $skey . $sord . " ";
        	}
        	
        	db_b2b();
        	$data_res = db_query($qryfor_billto);
			db_b2b();
        	$data_res_No_Limit = db_query($qryfor_billto);
        	$show = "All";
        
        	if (tep_db_num_rows($data_res_No_Limit) > 0) {
        	?>
      <table width="1300" border="0" cellspacing="1" cellpadding="1">
      <tr align="center">
        <td colspan="14" bgcolor="#FFCCCC"><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">
          <b><?php  echo "Record found in Bill To section: ". $row["name"] . " - Total Records: " . tep_db_num_rows($data_res_No_Limit) ; ?></b></font>
        </td>
      </tr>
      <?php  if (1==1 OR $limit == 100000 ) { ?>
      <tr>
        <td width="5%" bgcolor="#D9F2FF"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=dt&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"] . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"] ); ?>">DATE</a> </font></td>
        <td width="5%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=age&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">AGE</a></font></td>
        <td width="10%" bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=contact&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">CONTACT</a></font></td>
        <td width="21%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=cname&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"] . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"] ); ?>">COMPANY NAME</a></font></td>
        <!-- <td bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=nname&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">NICKNAME</a></font></td> -->
        <td width="8%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">PHONE</font></td>
        <td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=city&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">CITY</a></font></td>
        <td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=state&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">STATE</a></font></td>
        <td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=zip&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">ZIP</a></font></td>
        <td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=ns&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">Next Step</a></font></td>
        <td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=lc&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">Last<br>Communication</a></font></td>
        <td bgcolor="#D9F2FF" align="center">
          <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
            <a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=nd&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">
              Next Communication
          </font>
        </td>
        <td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php htmlentities($_SERVER['PHP_SELF']. "?sk=ei&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">Assigned To</font></td>
      </tr>
      <?php 
        while ($data = array_shift($data_res)) {
        if ($data["nickname"] != "") {
        	$nickname = $data["nickname"];
        }else {
        	$tmppos_1 = strpos($data["company"], "-");
        	if ($tmppos_1 != false)
        	{
        		$nickname = $data["company"];
        	}else {
        		if ($data["shipCity"] <> "" || $data["shipState"] <> "" ) 
        		{
        			$nickname = $data["company"] . " - " . $data["shipCity"] . ", " . $data["shipState"] ;
        		}else { $nickname = $data["company"]; }
        	}
        }
        
        ?>
      <tr valign="middle">
      <td width="5%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo timestamp_to_datetime($data["dateCreated"]); 
        ?></font></td>
      <td width="5%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo date_diff_new($data["dateCreated"], "NOW");
        ?> Days</font></td>
      <td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["contact"]?></font></td>
      <td width="21%" bgcolor="#E4E4E4"><a href="viewCompany.php?ID=<?php  echo $data["ID"]?>"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $nickname;?></font></a></td>
      <td width="3%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["phone"]?></font></td>
      <td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["city"]?></font></td>
      <td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["state"]?></font></td>
      <td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["zip"]?></font></td>
      <td width="15%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["next_step"];?></font></td>
      <td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($data["last_date"]!="") echo date('m/d/Y',strtotime($data["last_date"]));?>
      <td width="10%" <?php  if ($data["next_date"] == date('Y-m-d')) { ?> bgcolor="#00FF00" <?php  } elseif ($data["next_date"] < date('Y-m-d') && $data["next_date"] != "") { ?> bgcolor="#FF0000" <?php  } else { ?> bgcolor="#E4E4E4"  <?php  } ?> align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($data["next_date"]!="") echo date('m/d/Y',strtotime($data["next_date"]));?>
      </font></td>
      <td  bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["initials"]?></font></td>
      </tr>
      <?php 
        } // of the inactive or reactive if
        ob_flush();
        }
        echo "</table><p></p>";
        }
        //Search the details in the Bill to 
        
        //Search the details in the Sell to 
        if ($forbillto_sellto != "") {
        	$qryfor_sellto = "Select shipCity, shipState, dateCreated, contact, ID, phone, company, nickname, city, state, zip, next_step, last_date, next_date from companyInfo where ID in (" . $qryfor_sellto_str . ") and ID not in ( $forbillto_sellto ) GROUP BY companyInfo.id " . $skey . $sord . " ";
        } else {
        	$qryfor_sellto = "Select shipCity, shipState, dateCreated, contact, ID, phone, company, nickname, city, state, zip, next_step, last_date, next_date from companyInfo where ID in (" . $qryfor_sellto_str . ") GROUP BY companyInfo.id " . $skey . $sord . " ";
        }
		db_b2b();
        $data_res = db_query($qryfor_sellto);
		db_b2b();
        $data_res_No_Limit = db_query($qryfor_sellto);
        $show = "All";
        if (tep_db_num_rows($data_res_No_Limit) > 0) {
        ?>
      <table width="1300" border="0" cellspacing="1" cellpadding="1">
      <tr align="center">
        <td colspan="14" bgcolor="#FFCCCC"><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">
          <b><?php  echo "Record found in Sell To section: ". $row["name"] . " - Total Records: " . tep_db_num_rows($data_res_No_Limit) ; ?></b></font>
        </td>
      </tr>
      <?php  if (1==1 OR $limit == 100000 ) { ?>
      <tr>
        <td width="5%" bgcolor="#D9F2FF"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=dt&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"] . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"] ); ?>">DATE</a> </font></td>
        <td width="5%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=age&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">AGE</a></font></td>
        <td width="10%" bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=contact&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">CONTACT</a></font></td>
        <td width="21%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=cname&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"] . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"] ); ?>">COMPANY NAME</a></font></td>
        <!-- <td bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=nname&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">NICKNAME</a></font></td> -->
        <td width="8%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">PHONE</font></td>
        <td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=city&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">CITY</a></font></td>
        <td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=state&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">STATE</a></font></td>
        <td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=zip&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">ZIP</a></font></td>
        <td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=ns&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">Next Step</a></font></td>
        <td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=lc&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">Last<br>Communication</a></font></td>
        <td bgcolor="#D9F2FF" align="center">
          <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
            <a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=nd&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">
              Next Communication
          </font>
        </td>
        <td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=ei&so=" . $so . "&employee_lst=$eid&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">Assigned To</font></td>
      </tr>
      <?php 
        while ($data = array_shift($data_res)) {
        
        if ($data["nickname"] != "") {
        	$nickname = $data["nickname"];
        }else {
        	$tmppos_1 = strpos($data["company"], "-");
        	if ($tmppos_1 != false)
        	{
        		$nickname = $data["company"];
        	}else {
        		if ($data["shipCity"] <> "" || $data["shipState"] <> "" ) 
        		{
        			$nickname = $data["company"] . " - " . $data["shipCity"] . ", " . $data["shipState"] ;
        		}else { $nickname = $data["company"]; }
        	}
        }
        
        ?>
      <tr valign="middle">
      <td width="5%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo  timestamp_to_datetime($data["dateCreated"]); 
        ?></font></td>
      <td width="5%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo date_diff_new($data["dateCreated"], "NOW");
        ?> Days</font></td>
      <td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["contact"]?></font></td>
      <td width="21%" bgcolor="#E4E4E4"><a href="viewCompany.php?ID=<?php echo $data["ID"]?>"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  echo $nickname;?></font></a></td>
      <td width="3%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["phone"]?></font></td>
      <td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["city"]?></font></td>
      <td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["state"]?></font></td>
      <td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["zip"]?></font></td>
      <td width="15%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["next_step"]?></font></td>
      <td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($data["last_date"]!="") echo date('m/d/Y',strtotime($data["last_date"]));?>
      <td width="10%" <?php  if ($data["next_date"] == date('Y-m-d')) { ?> bgcolor="#00FF00" <?php  } elseif ($data["next_date"] < date('Y-m-d') && $data["next_date"] != "") { ?> bgcolor="#FF0000" <?php  } else { ?> bgcolor="#E4E4E4"  <?php  } ?> align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($data["next_date"]!="") echo date('m/d/Y',strtotime($data["next_date"]));?>
      </font></td>
      <td  bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["initials"]?></font></td>
      </tr>
      <?php 
        } // of the inactive or reactive if
        ob_flush();
        }
        echo "</table><p></p>";
        }
        }
        }
        ?>
    </div>
  </body>
</html>
