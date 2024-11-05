<?php 
require ("inc/header_session.php");
require ("../mainfunctions/database.php");
require ("../mainfunctions/general-functions.php"); 

session_start();
$sort_order_pre = "DESC";
if($_REQUEST['sort_order_pre'] == "ASC")
{
	$sort_order_pre = "DESC";
}else{
	$sort_order_pre = "ASC";
}

?>
<html>
  <head>
    <title>Red Line Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
    <LINK rel='stylesheet' type='text/css' href='css/ucb_common_style.css' >
  </head>
  <body>
    <?php include("inc/header.php"); ?>
    <div class="main_data_css">
      <div id="light" class="white_content"></div>
      <div id="fade" class="black_overlay"></div>
      <div class="dashboard_heading" style="float: left;">
        <div style="float: left;">
          Red Line Report 
          <div class="tooltip">
            <i class="fa fa-info-circle" aria-hidden="true"></i>
            <!-- <span class="tooltiptext">This report allows the user to see all accounts which are eligible to be lost due to transfer as they have not been contacted in over 90 days, but has not yet fallen to the sales call list report yet and been unassigned automatically.</span></div><br> -->
            <span class="tooltiptext">This report allows the user to see all accounts which haven't been contacted in over 3 months, as well as those that are on the verge of re-assignment due to mismanagement after 6 months.</span>
          </div>
          <br>
        </div>
      </div>
      <form method="get">
        <script>
          //function show_trash() 
          //{
          //	var chklist = document.getElementById('chktrash_three').checked;
          //	document.location = "dashboardnew.php?show=olderthan3months&chklist=" + chklist;
          //}
        </script>
        <?php
          //if (isset($_REQUEST["sort_fld"])){ 
          
          //}else{
          	//$res = db_query("delete from report_olderthan3month" , db() );
          //}
        ?>
        <table cellSpacing="1" cellPadding="1" border="0" >
          <tr>
            <td>
              Employee name: 
              <select name="employeeID[]" id="employeeID" multiple="multiple">
                <option value='ALL'>ALL</option>
                <?php
                  $d = "Select * from employees WHERE status = 'Active' ORDER BY name ASC";
				  db_b2b();
                  $result = db_query($d);
                  while ($row = array_shift($result)) 
                  {
                  	echo "<option ";
					echo in_array($row["employeeID"], isset($_REQUEST['employeeID']) ? $_REQUEST['employeeID'] : []) ? 'selected' : '';
                  	echo " value='" . $row["employeeID"] . "'>";
                  	echo $row["name"] . "</option>";
                  }
                  ?>
              </select>
              &nbsp;&nbsp;&nbsp;&nbsp;
              Show Trash, inactive, unqualified records:<input type="checkbox" value="true" name="chklist" id="chklist" <?php if ($_REQUEST["chklist"] == "true") {echo "checked";} ?>> 
              &nbsp;&nbsp;&nbsp;&nbsp;
              Show only records where we have transactions:<input type="checkbox" value="true" name="chklooprec" id="chklooprec" <?php if ($_REQUEST["chklooprec"] == "true") {echo "checked";}  else {echo "";}?>> 
              &nbsp;&nbsp;&nbsp;&nbsp;
              Show records: 
              <select name="selfrm" name="selfrm">
                <option value="sales" <?php if ($_REQUEST["selfrm"] == "sales") {echo " selected ";} ?>>Show only Sales Records</option>
                <option value="rescue" <?php if ($_REQUEST["selfrm"] == "rescue") {echo " selected ";}?>>Show only Purchasing Records</option>
                <option value="both" <?php if ($_REQUEST["selfrm"] == "both") {echo " selected ";}?> <?php if (!isset($_REQUEST["selfrm"])) {echo " selected ";}?> >Show both Sales and Purchasing Record</option>
              </select>
              <br>
              <br>
              Transaction Type: 
              <select name="transactionType" id="transactionType">
								<option value="All">All</option>
								<option value='1' <?php echo ($_REQUEST['transactionType'] == '1')? ' selected' : ''; ?>>UsedCardboardBoxes</option>
								<option value='2' <?php echo ($_REQUEST['transactionType'] == '2')? ' selected' : ''; ?>>UCBPalletSolutions</option>
								<option value='3' <?php echo ($_REQUEST['transactionType'] == '3')? ' selected' : ''; ?>>UCBZeroWaste</option>
							</select>
              <input type="submit" value="Run report" id="btnsubmit" style="cursor:pointer;">
            </td>
          </tr>
        </table>
        <div ><i>Note: Please wait until you see <font color="red">"END OF REPORT"</font> at the bottom of the report, before using the sort option.</i></div>
        <?php if(isset($_REQUEST["employeeID"]) || isset($_REQUEST["sort_fld"])) {?>
        <table width="1300" border="0" cellspacing="1" cellpadding="1">
          <tr align="center">
            <td colspan="13" bgcolor="#FFCCCC"><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">
              <b><?php echo "Older Than Three Months"?></b></font>
            </td>
          </tr>
          <tr>
            <td width="4%" bgcolor="#D9F2FF"  align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Sr. No.</font></td>
            <td width="8%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="Report_CRM_older_than3months.php?sort_fld=status&sort_order_pre=<?php echo $sort_order_pre;?>&chklist=<?php echo $_REQUEST["chklist"];?>&chklooprec=<?php echo $_REQUEST["chklooprec"];?>&transactionType=<?php echo $_REQUEST['transactionType']; ?>" style="color:#333333">STATUS</a></font></td>
            <td width="10%" bgcolor="#D9F2FF" align="center">
              <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                <a href="Report_CRM_older_than3months.php?sort_fld=contact&sort_order_pre=<?php echo $sort_order_pre;?>&chklist=<?php echo $_REQUEST["chklist"];?>&chklooprec=<?php echo $_REQUEST["chklooprec"];?>&transactionType=<?php echo $_REQUEST['transactionType']; ?>" style="color:#333333">
                  CONTACT
              </font>
            </td>
            <td width="21%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="Report_CRM_older_than3months.php?sort_fld=company_name&sort_order_pre=<?php echo $sort_order_pre;?>&chklist=<?php echo $_REQUEST["chklist"];?>&chklooprec=<?php echo $_REQUEST["chklooprec"];?>&transactionType=<?php echo $_REQUEST['transactionType']; ?>" style="color:#333333">COMPANY NAME</font></td>
            <td width="3%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="Report_CRM_older_than3months.php?sort_fld=phone&sort_order_pre=<?php echo $sort_order_pre;?>&chklist=<?php echo $_REQUEST["chklist"];?>&chklooprec=<?php echo $_REQUEST["chklooprec"];?>&transactionType=<?php echo $_REQUEST['transactionType']; ?>" style="color:#333333">PHONE</font></td>
            <td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="Report_CRM_older_than3months.php?sort_fld=city&sort_order_pre=<?php echo $sort_order_pre;?>&chklist=<?php echo $_REQUEST["chklist"];?>&chklooprec=<?php echo $_REQUEST["chklooprec"];?>&transactionType=<?php echo $_REQUEST['transactionType']; ?>" style="color:#333333">CITY</font></td>
            <td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="Report_CRM_older_than3months.php?sort_fld=state&sort_order_pre=<?php echo $sort_order_pre;?>&chklist=<?php echo $_REQUEST["chklist"];?>&chklooprec=<?php echo $_REQUEST["chklooprec"];?>&transactionType=<?php echo $_REQUEST['transactionType']; ?>" style="color:#333333">STATE</font></td>
            <td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="Report_CRM_older_than3months.php?sort_fld=zip&sort_order_pre=<?php echo $sort_order_pre;?>&chklist=<?php echo $_REQUEST["chklist"];?>&chklooprec=<?php echo $_REQUEST["chklooprec"];?>&transactionType=<?php echo $_REQUEST['transactionType']; ?>" style="color:#333333">ZIP</font></td>
            <td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="Report_CRM_older_than3months.php?sort_fld=acc_owner&sort_order_pre=<?php echo $sort_order_pre;?>&chklist=<?php echo $_REQUEST["chklist"];?>&chklooprec=<?php echo $_REQUEST["chklooprec"];?>&transactionType=<?php echo $_REQUEST['transactionType']; ?>" style="color:#333333">Account Owner</font></td>
            <td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="Report_CRM_older_than3months.php?sort_fld=nextstep&sort_order_pre=<?php echo $sort_order_pre;?>&chklist=<?php echo $_REQUEST["chklist"];?>&chklooprec=<?php echo $_REQUEST["chklooprec"];?>&transactionType=<?php echo $_REQUEST['transactionType']; ?>" style="color:#333333">Next Step</font></td>
            <td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="Report_CRM_older_than3months.php?sort_fld=last_Communication&sort_order_pre=<?php echo $sort_order_pre;?>&chklist=<?php echo $_REQUEST["chklist"];?>&chklooprec=<?php echo $_REQUEST["chklooprec"];?>&transactionType=<?php echo $_REQUEST['transactionType']; ?>" style="color:#333333">Last Communication</font></td>
            <td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="Report_CRM_older_than3months.php?sort_fld=next_communication&sort_order_pre=<?php echo $sort_order_pre;?>&chklist=<?php echo $_REQUEST["chklist"];?>&chklooprec=<?php echo $_REQUEST["chklooprec"];?>&transactionType=<?php echo $_REQUEST['transactionType']; ?>" style="color:#333333">Next Communication</font></td>
          </tr>
          <?php
            $MGArray = array();
            $empid_val = "";
            foreach ($_REQUEST["employeeID"] as $empid_val_ar)  
            	$empid_val.=$empid_val_ar.",";
            
            $empid_list = rtrim($empid_val, ", ");
            
            if (isset($_REQUEST["sort_fld"])){ 
            }else{
              $transTypeQry ="";
              if(isset($_REQUEST['transactionType'])){
                if($_REQUEST['transactionType'] == 1){
                  $transTypeQry = " AND pallet_flg = 0 AND ucbzw_flg = 0 ";
                }elseif($_REQUEST['transactionType'] == 2){
                  $transTypeQry = " AND pallet_flg= 1 ";
                }elseif($_REQUEST['transactionType'] == 3){
                  $transTypeQry = " AND ucbzw_flg= 1 ";
                }else{
                  $transTypeQry ="";
                }
              }
            	
            	$dt = date ('Y-m-d', strtotime ( '-90 days' ) );
            	if($_REQUEST["chklist"] == "true")
            	{
            		if($empid_list !="ALL" && ($empid_list != ""))
            		{
            			$x = "Select ";
            			$x.= " companyInfo.last_contact_date AS LD,companyInfo.status ,companyInfo.ID AS I,";
            			$x.= " companyInfo.loopid AS LID, companyInfo.contact AS C, companyInfo.dateCreated AS D,";
            			$x.= " companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH, companyInfo.assignedto, ";
            			$x.= " companyInfo.city AS CI, companyInfo.state AS ST, companyInfo.shipCity, companyInfo.shipState, companyInfo.zip AS ZI,";
            			$x.= " companyInfo.next_step AS NS, companyInfo.next_date AS ND";
            			$x.= " from companyInfo";
            			$x.= " Where last_contact_date <= '" . date('Y-m-d', strtotime ( '-91 days' )) . "' and companyInfo.assignedto in (". $empid_list . ") $transTypeQry  ";
            			//$x.= " Where (last_contact_date <= '" . date('Y-m-d', strtotime ( '-91 days' )) . "' and not (last_contact_date <= '" . date('Y-m-d', strtotime ( '-180 days' )) . "' and next_date <= '" . date('Y-m-d', strtotime ( '-15 days' )) . "')) ";
            			//$x.= " and companyInfo.assignedto in (". $empid_list . ")  ";
            			if ($_REQUEST["selfrm"] == "sales") { $x.= " and haveNeed = 'Need Boxes' ";}
            			if ($_REQUEST["selfrm"] == "rescue") { $x.= " and haveNeed = 'Have boxes' ";}
            			$x.= " order by  companyInfo.last_contact_date desc";
            		}
            		else
            		{
            			$x = "Select ";
            			$x.= " companyInfo.last_contact_date AS LD,companyInfo.status ,companyInfo.ID AS I,";
            			$x.= " companyInfo.loopid AS LID, companyInfo.contact AS C, companyInfo.dateCreated AS D,";
            			$x.= " companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.shipCity, companyInfo.shipState, companyInfo.phone AS PH, companyInfo.assignedto,";
            			$x.= " companyInfo.city AS CI, companyInfo.state AS ST, companyInfo.zip AS ZI,";
            			$x.= " companyInfo.next_step AS NS, companyInfo.next_date AS ND";
            			$x.= " from companyInfo ";
            			$x.= " Where last_contact_date <= '" . date('Y-m-d', strtotime ( '-91 days' )) . "' $transTypeQry";
            			//$x.= " Where (last_contact_date <= '" . date('Y-m-d', strtotime ( '-91 days' )) . "' and not (last_contact_date <= '" . date('Y-m-d', strtotime ( '-180 days' )) . "' and next_date <= '" . date('Y-m-d', strtotime ( '-15 days' )) . "')) ";
            			//$x.= " Where not (last_contact_date <= '" . date('Y-m-d', strtotime ( '-181 days' )) . "' and next_date <= '" . date('Y-m-d', strtotime ( '-15 days' )) . "') ";
            			if ($_REQUEST["selfrm"] == "sales") { $x.= " and haveNeed = 'Need Boxes' ";}
            			if ($_REQUEST["selfrm"] == "rescue") { $x.= " and haveNeed = 'Have boxes' ";}
            			$x.= " order by  companyInfo.last_contact_date desc";
            		}
            	}
            	else
            	{
            		if($empid_list !="ALL" && ($empid_list != ""))
            		{
            			$x = "Select ";
            			$x.= " companyInfo.last_contact_date AS LD,companyInfo.status ,companyInfo.ID AS I,";
            			$x.= " companyInfo.loopid AS LID, companyInfo.contact AS C, companyInfo.dateCreated AS D,";
            			$x.= " companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH, companyInfo.assignedto ,";
            			$x.= " companyInfo.city AS CI, companyInfo.state AS ST, companyInfo.shipCity, companyInfo.shipState, companyInfo.zip AS ZI,";
            			$x.= " companyInfo.next_step AS NS, companyInfo.next_date AS ND";
            			$x.= " from companyInfo";
            			$x.= " Where last_contact_date <= '" . date('Y-m-d', strtotime ( '-91 days' )) . "' and companyInfo.assignedto in (". $empid_list . ") and companyInfo.active=1 $transTypeQry ";
            			//$x.= " Where (last_contact_date <= '" . date('Y-m-d', strtotime ( '-91 days' )) . "' and not (last_contact_date <= '" . date('Y-m-d', strtotime ( '-180 days' )) . "' and next_date <= '" . date('Y-m-d', strtotime ( '-15 days' )) . "')) "; 
            			
            			if ($_REQUEST["selfrm"] == "sales") { $x.= " and status not in (97, 98, 99, 24, 43, 106, 33, 52, 31) and haveNeed = 'Need Boxes' ";}
            			if ($_REQUEST["selfrm"] == "rescue") { $x.= " and status not in (44, 112, 110, 49, 113, 114, 52, 31) and haveNeed = 'Have boxes' ";}
            			if ($_REQUEST["selfrm"] == "both") { $x.= " and status not in (97, 98, 99, 24, 43, 106, 33, 52, 31, 44, 112, 110, 49, 113, 114) ";}
            			$x.= " order by companyInfo.last_contact_date desc";
            		}
            		else
            		{
            			$x = "Select ";
            			$x.= " companyInfo.last_contact_date AS LD,companyInfo.status ,companyInfo.ID AS I,";
            			$x.= " companyInfo.loopid AS LID, companyInfo.contact AS C, companyInfo.dateCreated AS D,";
            			$x.= " companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH, companyInfo.assignedto, ";
            			$x.= " companyInfo.city AS CI, companyInfo.state AS ST, companyInfo.shipCity, companyInfo.shipState, companyInfo.zip AS ZI,";
            			$x.= " companyInfo.next_step AS NS, companyInfo.next_date AS ND";
            			$x.= " from companyInfo where companyInfo.active=1   ";
            			$x.= " and last_contact_date <= '" . date('Y-m-d', strtotime ( '-91 days' )) . "' and status not in (31, 24, 43, 44, 49) $transTypeQry";
            			
            			if ($_REQUEST["selfrm"] == "sales") { $x.= " and status not in (97, 98, 99, 24, 43, 106, 33, 52, 31) and haveNeed = 'Need Boxes' ";}
            			if ($_REQUEST["selfrm"] == "rescue") { $x.= " and status not in (44, 112, 110, 49, 113, 114, 52, 31) and haveNeed = 'Have boxes' ";}
            			if ($_REQUEST["selfrm"] == "both") { $x.= " and status not in (97, 98, 99, 24, 43, 106, 33, 52, 31, 44, 112, 110, 49, 113, 114) ";}
            			$x.= " order by companyInfo.last_contact_date desc";
            		}
            	}
            	
				db_b2b();
            	$dt_view_res_x = db_query($x);
            	$rep_order = "company_name";
            	if (isset($_REQUEST["sort_fld"])){
            		$rep_order = $_REQUEST["sort_fld"];
            	}
            	
            	$running_cnt = 0;
				db_b2b();
            	$dt_view_res_x = db_query($x);
            	while ($data = array_shift($dt_view_res_x)) 				
            	{
            		$status_nm = "";
            		$qry="select name from status where id = '". $data['status'] . "'";
					db_b2b();
            		$dt_view_res = db_query($qry);
            
            		while ($myrow = array_shift($dt_view_res)) 					
            		{						
            			$status_nm = $myrow["name"];					
            		}					
            		
            		$emp_nm = "";
            		$qry="select name from employees where employeeID = '". $data['assignedto'] . "'";
					db_b2b();
            		$dt_view_res = db_query($qry);
            		while ($myrow = array_shift($dt_view_res)) 					
            		{						
            			$emp_nm = $myrow["name"];					
            		}					
            		
            		$last_date_days_str = ""; 
            		$days = 0; $tmp_msg_dt = "";
            		if ($data["LD"] != ""){
            			$days = round((strtotime(date("Y-m-d")) - strtotime($data["LD"])) / (60 * 60 * 24));
            			$tmp_msg_dt = date('m/d/Y', strtotime($data['LD']));
            			$last_date_days_str = " (" . $days . " days ago)";
            		}	
            	
            			if($_REQUEST["chklooprec"] == "true"){
            				if ($data["LID"] > 0) {
            					$recfound = "n";
            					if($_REQUEST["selfrm"] == "sales" || $_REQUEST["selfrm"] == "both" ){
            						db();
									$dt_view_resloop = db_query("Select id from loop_transaction_buyer where warehouse_id = " . $data["LID"] . " limit 1");
            						while ($data_loop = array_shift($dt_view_resloop))
            						{
            							$running_cnt = $running_cnt +1;
            							
            							$recfound = "y";
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
            							
            							if ( trim($nickname) == ""){
            								$nickname = "Company name blank";
            							}
            						
            
            							?>
          <tr valign="middle">
          <td width="1%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $running_cnt;?></font></td>
          <td width="5%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $status_nm;?></font></td>
          <td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["C"]?></font></td>
          <td width="21%" bgcolor="#E4E4E4"><a href="viewCompany.php?ID=<?php echo $data["I"]?>" target="_blank"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo $nickname?><?php if ($data["LID"] > 0) echo "</b>"; ?></font></a></td>
          <td width="3%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["PH"]?></font></td>
          <td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["CI"]?></font></td>
          <td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["ST"]?></font></td>
          <td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["ZI"]?></font></td>
          <td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $emp_nm?></font></td>
          <td width="15%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["NS"]?></font></td>
          <td width="10%" <?php if ($days >= 91) { echo "bgcolor='#db8989'"; } else { echo "bgcolor='#E4E4E4'"; }?> align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $tmp_msg_dt . $last_date_days_str;?></font>
          <?php if ($days >= 181) { ?>
          <br>
          <b>At Risk of Re-assignment</b>
          <?php } ?>
          </td>
          <td width="10%" <?php if ($data["ND"] == date('Y-m-d')) { ?> bgcolor="#00FF00" <?php } elseif ($data["ND"] < date('Y-m-d') && $data["ND"] != "") { ?> bgcolor="#db8989" <?php } else { ?> bgcolor="#E4E4E4"  <?php } ?> align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($data["ND"]!="") echo date('m/d/Y',strtotime($data["ND"]));?></font></td>
          </tr>
          <?php
            $MGArray[] = array('I' => $data["I"], 'nickname' => $nickname, 'LID' => $data["LID"], 'C' => $data["C"], 'status_nm' => $status_nm,
            'PH' => $data["PH"], 'CI' => $data["CI"], 'ST' => $data["ST"], 'ZI' => $data["ZI"], 'emp_nm' => $emp_nm, 'NS' => $data["NS"],
            'days' => $days, 'tmp_msg_dt' => date('Y-m-d', strtotime($data['LD'])), 'last_date_days_str' => $last_date_days_str, 'ND' => $data["ND"]);
            }
            }
            
            if($_REQUEST["selfrm"] == "rescue" || $_REQUEST["selfrm"] == "both"){
            if ($recfound == "n"){
            
			db(); 
            $dt_view_resloop = db_query("Select id from loop_transaction where warehouse_id = " . $data["LID"] . " limit 1" );
            while ($data_loop = array_shift($dt_view_resloop))
            {
            $running_cnt = $running_cnt +1;
            
            $recfound = "y";
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
            
      
            if ( trim($nickname) == ""){
            	$nickname = "Company name blank";
            }
            
            ?>
          <tr valign="middle">
            <td width="1%" bgcolor="#E4E4E4" align="center" ><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $running_cnt;?></font></td>
            <td width="5%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $status_nm;?></font></td>
            <td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["C"]?></font></td>
            <td width="21%" bgcolor="#E4E4E4"><a href="viewCompany.php?ID=<?php echo $data["I"]?>" target="_blank"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo $nickname?><?php if ($data["LID"] > 0) echo "</b>"; ?></font></a></td>
            <td width="3%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["PH"]?></font></td>
            <td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["CI"]?></font></td>
            <td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["ST"]?></font></td>
            <td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["ZI"]?></font></td>
            <td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $emp_nm?></font></td>
            <td width="15%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["NS"]?></font></td>
            <td width="10%" <?php if ($days >= 91) { echo "bgcolor='#db8989'"; } else { echo "bgcolor='#E4E4E4'"; }?> align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $tmp_msg_dt . $last_date_days_str;?></font>
              <?php if ($days >= 181) { ?>
              <br>
              <b>At Risk of Re-assignment</b>
              <?php } ?>
            </td>
            <td width="10%" <?php if ($data["ND"] == date('Y-m-d')) { ?> bgcolor="#00FF00" <?php } elseif ($data["ND"] < date('Y-m-d') && $data["ND"] != "") { ?> bgcolor="#db8989" <?php } else { ?> bgcolor="#E4E4E4"  <?php } ?> align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($data["ND"]!="") echo date('m/d/Y',strtotime($data["ND"]));?></font></td>
          </tr>
          <?php
            $MGArray[] = array('I' => $data["I"], 'nickname' => $nickname, 'LID' => $data["LID"], 'C' => $data["C"], 'status_nm' => $status_nm,
            'PH' => $data["PH"], 'CI' => $data["CI"], 'ST' => $data["ST"], 'ZI' => $data["ZI"], 'emp_nm' => $emp_nm, 'NS' => $data["NS"],
            'days' => $days, 'tmp_msg_dt' => date('Y-m-d', strtotime($data['LD'])), 'last_date_days_str' => $last_date_days_str, 'ND' => $data["ND"]);
            
            }
            }
            }
            }
            
            }else{
            
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
            
            if ( trim($nickname) == ""){
            $nickname = "Company name blank";
            }
            
            $running_cnt = $running_cnt +1;
            
            ?>
          <tr valign="middle">
            <td width="1%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $running_cnt;?></font></td>
            <td width="5%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $status_nm;?></font></td>
            <td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["C"]?></font></td>
            <td width="21%" bgcolor="#E4E4E4"><a href="viewCompany.php?ID=<?php echo $data["I"]?>" target="_blank">
              <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo $nickname?><?php if ($data["LID"] > 0) echo "</b>"; ?></font></a>
            </td>
            <td width="3%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["PH"]?></font></td>
            <td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["CI"]?></font></td>
            <td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["ST"]?></font></td>
            <td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["ZI"]?></font></td>
            <td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $emp_nm?></font></td>
            <td width="15%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["NS"]?></font></td>
            <td width="10%" <?php if ($days >= 91) { echo "bgcolor='#db8989'"; } else { echo "bgcolor='#E4E4E4'"; }?> align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $tmp_msg_dt . $last_date_days_str;?></font>
              <?php if ($days >= 181) { ?>
              <br>
              <b>At Risk of Re-assignment</b>
              <?php } ?>
            </td>
            <td width="10%" <?php if ($data["ND"] == date('Y-m-d')) { ?> bgcolor="#00FF00" <?php } elseif ($data["ND"] < date('Y-m-d') && $data["ND"] != "") { ?> bgcolor="#db8989" <?php } else { ?> bgcolor="#E4E4E4"  <?php } ?> align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($data["ND"]!="") echo date('m/d/Y',strtotime($data["ND"]));?></font></td>
          </tr>
          <?php
            $MGArray[] = array('I' => $data["I"], 'nickname' => $nickname, 'LID' => $data["LID"], 'C' => $data["C"], 'status_nm' => $status_nm,
            'PH' => $data["PH"], 'CI' => $data["CI"], 'ST' => $data["ST"], 'ZI' => $data["ZI"], 'emp_nm' => $emp_nm, 'NS' => $data["NS"],
            'days' => $days, 'tmp_msg_dt' => date('Y-m-d', strtotime($data['LD'])), 'last_date_days_str' => $last_date_days_str, 'ND' => $data["ND"]);
            }
            
            //}
            
            }
            
            $_SESSION['sortarrayn'] = $MGArray;
            }
            
            if (isset($_REQUEST["sort_fld"])){ 
            $rep_order = "company_name";
            
            $MGArray = $_SESSION['sortarrayn'];
            
            if($_REQUEST['sort_fld'] == "status" && $_REQUEST['sort_order_pre'] == "ASC")
            {
            $MGArraysort_I = array();
            foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp["status_nm"];
            }
            array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
            }
            if($_REQUEST['sort_fld'] == "status" && $_REQUEST['sort_order_pre'] == "DESC")
            {
            $MGArraysort_I = array();
            foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp["status_nm"];
            }
            array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
            }
            
            if($_REQUEST['sort_fld'] == "contact" && $_REQUEST['sort_order_pre'] == "ASC")
            {
            $MGArraysort_I = array();
            foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp['C'];
            }
            array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
            }
            if($_REQUEST['sort_fld'] == "contact" && $_REQUEST['sort_order_pre'] == "DESC")
            {
            $MGArraysort_I = array();
            foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp['C'];
            }
            array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
            }
            
            if($_REQUEST['sort_fld'] == "company_name" && $_REQUEST['sort_order_pre'] == "ASC")
            {
            $MGArraysort_I = array();
            foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp["nickname"];
            }
            array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
            }
            if($_REQUEST['sort_fld'] == "company_name" && $_REQUEST['sort_order_pre'] == "DESC")
            {
            $MGArraysort_I = array();
            foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp["nickname"];
            }
            array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
            }
            
            if($_REQUEST['sort_fld'] == "phone" && $_REQUEST['sort_order_pre'] == "ASC")
            {
            $MGArraysort_I = array();
            foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp["PH"];
            }
            array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
            }
            if($_REQUEST['sort_fld'] == "phone" && $_REQUEST['sort_order_pre'] == "DESC")
            {
            $MGArraysort_I = array();
            foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp["PH"];
            }
            array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
            }
            
            if($_REQUEST['sort_fld'] == "city" && $_REQUEST['sort_order_pre'] == "ASC")
            {
            $MGArraysort_I = array();
            foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp["CI"];
            }
            array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
            }
            if($_REQUEST['sort_fld'] == "city" && $_REQUEST['sort_order_pre'] == "DESC")
            {
            $MGArraysort_I = array();
            foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp["CI"];
            }
            array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
            }
            
            if($_REQUEST['sort_fld'] == "state" && $_REQUEST['sort_order_pre'] == "ASC")
            {
            $MGArraysort_I = array();
            foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp["ST"];
            }
            array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
            }
            if($_REQUEST['sort_fld'] == "state" && $_REQUEST['sort_order_pre'] == "DESC")
            {
            $MGArraysort_I = array();
            foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp["ST"];
            }
            array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
            }
            
            if($_REQUEST['sort_fld'] == "zip" && $_REQUEST['sort_order_pre'] == "ASC")
            {
            $MGArraysort_I = array();
            foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp["ZI"];
            }
            array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
            }
            if($_REQUEST['sort_fld'] == "zip" && $_REQUEST['sort_order_pre'] == "DESC")
            {
            $MGArraysort_I = array();
            foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp["ZI"];
            }
            array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
            }
            
            if($_REQUEST['sort_fld'] == "acc_owner" && $_REQUEST['sort_order_pre'] == "ASC")
            {
            $MGArraysort_I = array();
            foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp["emp_nm"];
            }
            array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
            }
            if($_REQUEST['sort_fld'] == "acc_owner" && $_REQUEST['sort_order_pre'] == "DESC")
            {
            $MGArraysort_I = array();
            foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp["emp_nm"];
            }
            array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
            }
            
            if($_REQUEST['sort_fld'] == "nextstep" && $_REQUEST['sort_order_pre'] == "ASC")
            {
            $MGArraysort_I = array();
            foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp["NS"];
            }
            array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
            }
            if($_REQUEST['sort_fld'] == "nextstep" && $_REQUEST['sort_order_pre'] == "DESC")
            {
            $MGArraysort_I = array();
            foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp["NS"];
            }
            array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
            }
            
            if($_REQUEST['sort_fld'] == "last_Communication" && $_REQUEST['sort_order_pre'] == "ASC")
            {
            $MGArraysort_I = array();
            foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp["tmp_msg_dt"];
            }
            array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
            }
            if($_REQUEST['sort_fld'] == "last_Communication" && $_REQUEST['sort_order_pre'] == "DESC")
            {
            $MGArraysort_I = array();
            foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp["tmp_msg_dt"];
            }
            array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
            }
            
            if($_REQUEST['sort_fld'] == "next_communication" && $_REQUEST['sort_order_pre'] == "ASC")
            {
            $MGArraysort_I = array();
            foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp["ND"];
            }
            array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
            }
            if($_REQUEST['sort_fld'] == "next_communication" && $_REQUEST['sort_order_pre'] == "DESC")
            {
            $MGArraysort_I = array();
            foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp["ND"];
            }
            array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
            }
            
            $running_cnt = 0;
            foreach ($MGArray as $MGArraytmp2) {
            $running_cnt = $running_cnt + 1;
            ?>
          <tr valign="middle">
            <td width="1%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $running_cnt;?></font></td>
            <td width="5%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $MGArraytmp2["status_nm"];?></font></td>
            <td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $MGArraytmp2["C"]?></font></td>
            <td width="21%" bgcolor="#E4E4E4"><a href="viewCompany.php?ID=<?php echo $MGArraytmp2["I"]?>" target="_blank">
              <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php echo $MGArraytmp2["nickname"]?><?php if ($MGArraytmp2["LID"] > 0) echo "</b>"; ?></font></a>
            </td>
            <td width="3%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $MGArraytmp2["PH"]?></font></td>
            <td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $MGArraytmp2["CI"]?></font></td>
            <td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $MGArraytmp2["ST"]?></font></td>
            <td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $MGArraytmp2["ZI"]?></font></td>
            <td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $MGArraytmp2["emp_nm"]?></font></td>
            <td width="15%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $MGArraytmp2["NS"]?></font></td>
            <td width="10%" <?php if ($MGArraytmp2["days"] >= 91) { echo "bgcolor='#db8989'"; } else { echo "bgcolor='#E4E4E4'"; }?> align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $MGArraytmp2["tmp_msg_dt"] . $MGArraytmp2["last_date_days_str"];?></font>
              <?php if ($MGArraytmp2["days"] >= 181) { ?>
              <br>
              <b>At Risk of Re-assignment</b>
              <?php } ?>
            </td>
            <td width="10%" <?php if ($MGArraytmp2["ND"] == date('Y-m-d')) { ?> bgcolor="#00FF00" <?php } elseif ($MGArraytmp2["ND"] < date('Y-m-d') && $MGArraytmp2["ND"] != "") { ?> bgcolor="#db8989" <?php } else { ?> bgcolor="#E4E4E4"  <?php } ?> align="center"> <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($MGArraytmp2["ND"]!="") echo date('m/d/Y',strtotime($MGArraytmp2["ND"]));?></font></td>
          </tr>
          <?php
            }
            
            }
            
            ?>	
        </table>
        <?php if(isset($_REQUEST["employeeID"]) || isset($_REQUEST["sort_fld"])) {?>
        <div ><i><font color="red">"END OF REPORT"</font></i></div>
        <?php } ?>
        <?php } ?>
      </form>
    </div>
  </body>
</html>
