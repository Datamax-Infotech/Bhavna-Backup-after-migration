<?php
ini_set("display_errors", "1");
error_reporting(E_ERROR);
   session_start();
   require_once ("inc/header_session.php");
   require_once ("../mainfunctions/database.php");
   require_once ("../mainfunctions/general-functions.php");
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
   
?>
<html>
   <head>
      <title>Hide from Call List Report</title>
   </head>
   <script language="JavaScript" SRC="inc/CalendarPopup.js"></script><script language="JavaScript" SRC="inc/general.js"></script>
   <script language="JavaScript">document.write(getCalendarStyles());</script>
   <script>
      function showcontact_details(compid, search_keyword)
      {
      	if (window.XMLHttpRequest)
      	{
      	  xmlhttp=new XMLHttpRequest();
      	}else{
      	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      	}
      	  xmlhttp.onreadystatechange=function()
      	{
      		if (xmlhttp.readyState==4 && xmlhttp.status==200)
      		{
      			selectobject = document.getElementById("com_contact"+compid);
      
      			n_left = f_getPosition(selectobject, 'Left');
      
      			n_top  = f_getPosition(selectobject, 'Top');
      
      			document.getElementById("light_todo").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';document.getElementById('fade_todo').style.display='none'>Close</a> &nbsp;<center></center><br/>" + xmlhttp.responseText;
      
      			document.getElementById('light_todo').style.display='block';
      
      			document.getElementById('light_todo').style.left= (n_left + 50) + 'px';
      
      			document.getElementById('light_todo').style.top=n_top - 40 + 'px';
      
      			document.getElementById('light_todo').style.width= 400 + 'px';
      
      		}
      	}
    
      	xmlhttp.open("GET","dashboard-search-contact.php?compid="+compid + "&search_keyword=" + encodeURIComponent(search_keyword) ,true);
      	xmlhttp.send();		
      }
      
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
      
   </script> 
   <style>
      .new_search_tbl{
      border-collapse: collapse;
      }
      .new_search_tbl td, .new_search_tbl th{
      border: 1px solid #FFF;
      padding: 3px;
      font-size: 12px;
      font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
      }
      .new_search_tbl td a{
      font-size: 12px;
      font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
      font-weight: normal;
      }
      .outer-container{
      width: 100%;
      margin: 0 auto;
      }
      .container{
      padding: 10px;
      }	
      .content{
      margin: 0 auto;
      width:100%;
      display:grid;
      }
      .txtstyle, .txtstyle_color
      {
      font-family:arial;
      font-size:13;
      font-weight:700;
      height:16px; 
      background:#ABC5DF;
      text-align: center;
      }
      .center
      {
      text-align: center;
      }
      .left
      {
      text-align: left;
      }
      table.datatable {
      border: 1px solid white;
      }
      table.datatable tr td,
      table.datatable tr th {
      height: 20px;
      border: 1px solid white;
      padding: 5px;
      }
      table.datatable tr:nth-child(even) td{
      background-color: #e4e4e4;
      }
      table.datatable tr:nth-child(odd) td{
      background-color: #F5F5F5;
      }
   </style>
   <link rel='stylesheet' type='text/css' href='css/ucb_common_style.css' >
   <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
   <body>
      <?php require_once ("inc/header.php"); ?>
      <br>
      <div class="outer-container">
         <div class="container">
            <div class="dashboard_heading" style="float: left;">
               <div style="float: left;">
                  Hide from Call List Report
                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                     <span class="tooltiptext">This report shows all of the "Hide from Call List" companies.</span>
                  </div>
                  <br>
               </div>
            </div>
            <form method="POST" name="reportvendorpayment" id="reportvendorpayment" action="<?php echo $_SERVER['PHP_SELF'];?>">
               <table>
                  <tr>
                     <td>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Search by
                        <select name="sp_dd" id="sp_dd">
                           <option value="">Sales and Purchasing</option>
                           <option value="s" <?php if($_POST["sp_dd"]=="s"){ echo "selected"; }else{} ?> >Sales records</option>
                           <option value="p" <?php if($_POST["sp_dd"]=="p"){ echo "selected"; }else{} ?> >Purchasing records</option>
                        </select>
                     </td>
                     <td>
                        
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Transaction Type:
                        <select name="transactionType" id="transactionType">
                           <option value="All">All</option>
                           <option value='1' <?php echo ($_REQUEST['transactionType'] == '1')? ' selected' : ''; ?>>UsedCardboardBoxes</option>
                           <option value='2' <?php echo ($_REQUEST['transactionType'] == '2')? ' selected' : ''; ?>>UCBPalletSolutions</option>
                           <option value='3' <?php echo ($_REQUEST['transactionType'] == '3')? ' selected' : ''; ?>>UCBZeroWaste</option>
                        </select>	
                     </td>
                     <td>
                        <input type="submit" value="Run Report" onClick="javascript: return loadmainpg()">
                        <input type="hidden" id="reprun" name="reprun" value="yes">
                     </td>
                  </tr>
               </table>
            </form>
            <br>
            <div class="content">
               <?php
                  function highlightSearchKeywords($text, $keyword) {  
                  
                  	$wordsAry = explode(" ", $keyword);
                  
                  	$wordsCount = count($wordsAry);
                  
                  
                  
                  	$actual_data_Ary = explode(" ", trim($text));
                  
                  	$actual_data_cnt = count($actual_data_Ary);
                  
                  	
                  
                  	$new_str = "";
                  
                  	for($cnt_1=0;$cnt_1 < $actual_data_cnt;$cnt_1++) {
                  
                  		$char_match_flg = "no";
                  
                  		for ($i=0; $i<$wordsCount; $i++) {
                  
                  			
                  
                  			if (strlen($wordsAry[$i]) > 1){
                  
                  				$pos = strpos(strtolower($actual_data_Ary[$cnt_1]), strtolower($wordsAry[$i]));
                  
                  				if ($pos !== false && strlen($actual_data_Ary[$cnt_1]) > 0){
                  
                  					$char_match_flg = "yes";
                  
                  					$new_str .=  "<span style='font-weight:bold; background:#FFFE63;'>" . $actual_data_Ary[$cnt_1] . "</span>" . " ";
                  
                  				}	
                  
                  			}	
                  
                  		}
                  
                  		if ($char_match_flg == "no")
                  
                  		{
                  
                  			$new_str .= $actual_data_Ary[$cnt_1] . " ";
                  
                  		}			
                  
                  	}
                  
                  	
                  
                  	return trim($new_str);
                  
                  }
                  
                  
                  
                  if(isset($_REQUEST["reprun"]) && $_REQUEST["reprun"] == "yes"){
                  
                  
                  
                  	$sp_dd = $_REQUEST["sp_dd"];
                  
                  	$sorturl=htmlentities($_SERVER['PHP_SELF']. "?reprun=yes&sp_dd=".$_REQUEST['sp_dd']."&transactionType=".$_REQUEST['transactionType']);
                  
                  	
                  
                  	?>
               <table class="new_search_tbl" cellpadding="0" cellspacing="0" width="100%">
                  <tr>
                     <td bgcolor="#D9F2FF">#</td>
                     <td width="21%" bgcolor="#D9F2FF">
                        COMPANY NAME
                        <a href="<?php echo $sorturl; ?>&sort=cname&so=A"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>
                        <a href="<?php echo $sorturl; ?>&sort=cname&so=D"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>	
                     </td>
                     <td width="5%" bgcolor="#D9F2FF">
                        Contact Name
                        <a href="<?php echo $sorturl; ?>&sort=contactnm&so=A"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>
                        <a href="<?php echo $sorturl; ?>&sort=contactnm&so=D"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>	
                     </td>
                     <td bgcolor="#D9F2FF" align="center">
                        UCB/ZW Rep
                        <a href="<?php echo $sorturl; ?>&sort=ei&so=A"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>
                        <a href="<?php echo $sorturl; ?>&sort=ei&so=D"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                     </td>
                     <td bgcolor="#D9F2FF" align="center">
                        Account Status
                        <a href="<?php echo $sorturl; ?>&sort=acc_status&so=A"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>
                        <a href="<?php echo $sorturl; ?>&sort=acc_status&so=D"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                     </td>
                     <td width="5%" bgcolor="#D9F2FF">Industry
                        <a href="<?php echo $sorturl; ?>&sort=inds&so=A"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>
                        <a href="<?php echo $sorturl; ?>&sort=inds&so=D"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                     </td>
                     <td bgcolor="#D9F2FF" align="center">
                        Sales Transactions
                        <a href="<?php echo $sorturl; ?>&sort=s_trans&so=A"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>
                        <a href="<?php echo $sorturl; ?>&sort=s_trans&so=D"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>	
                     </td>
                     <td width="5%" bgcolor="#D9F2FF" align="center">
                        Sales<br>Revenue
                        <a href="<?php echo $sorturl; ?>&sort=s_revenue&so=A"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>
                        <a href="<?php echo $sorturl; ?>&sort=s_revenue&so=D"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>	
                     </td>
                     <td width="5%" bgcolor="#D9F2FF" align="center">Purchasing<br>Transactions<a href="<?php echo $sorturl; ?>&sort=p_trans&so=A"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>
                        <a href="<?php echo $sorturl; ?>&sort=p_trans&so=D"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                     </td>
                     <td width="5%" bgcolor="#D9F2FF" align="center">
                        Purchasing<br>Payments<br><a href="<?php echo $sorturl; ?>&sort=p_pay&so=A"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>
                        <a href="<?php echo $sorturl; ?>&sort=p_pay&so=D"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                     </td>
                     <td  width="10%" bgcolor="#D9F2FF" align="center">
                        Parent
                        <a href="<?php echo $sorturl; ?>&sort=pc&so=A"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>
                        <a href="<?php echo $sorturl; ?>&sort=pc&so=D"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                     </td>
                     <td bgcolor="#D9F2FF" align="center">
                        Next Step
                        <a href="<?php echo $sorturl; ?>&sort=ns&so=A"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>
                        <a href="<?php echo $sorturl; ?>&sort=ns&so=D"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                     </td>
                     <td bgcolor="#D9F2FF" align="center">
                        Last Communication
                        <a href="<?php echo $sorturl; ?>&sort=lc&so=A"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>
                        <a href="<?php echo $sorturl; ?>&sort=lc&so=D"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                     </td>
                     <td bgcolor="#D9F2FF" align="center">
                        Next Communication
                        <a href="<?php echo $sorturl; ?>&sort=nc&so=A"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>
                        <a href="<?php echo $sorturl; ?>&sort=nc&so=D"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                     </td>
                  </tr>
                  <?php
                     $str="";
                     
                     if((isset($sp_dd)) && ($sp_dd!=="")){
                     
                     	if($sp_dd == "s"){
                     
                     		$str="haveNeed='Need Boxes' and ";
                     
                     	}
                     
                     	elseif($sp_dd == "p"){
                     
                     		$str="haveNeed='Have Boxes' and ";
                     
                     	}
                     
                     	else{
                     
                     		$str="";
                     
                     	}
                     
                     }
                     
                     
                     
                     $final_comp_list = array();
                     
                     
                     
                     $srno=0;
                     
                     $main_qry = "Select companyInfo.ID, companyInfo.company, companyInfo.city, companyInfo.state, employees.initials AS EI from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID  where ".$str." sales_purc_team_ignore_com=1 and companyInfo.status not in (31,24,86, 49) $transTypeQry ";
                     
                     db_b2b();
                     
                     $qry_res=db_query($main_qry);
                     
                     while($rows = array_shift($qry_res)){
                     
                     	$srno=$srno+1;
                     
                     	$final_comp_list[] = $rows["ID"];					
                     
                     }
                     
                     ?>	
                 	 <?php
                     //var_dump($final_comp_list);
                     
                     $running_cnt = 0;
                     
                     foreach($final_comp_list as $comp_list_arr)
                     {
                     
                     	foreach ($searchkey_array as $arrayData_value) {
                     
                     	  // Check if the array key exists in '$arrayData1'.
                     
                     	  if (array_key_exists($comp_list_arr, $arrayData_value)) {
                     
                     		// If it exists, add it to the '$results_array'.
                     
                     		$results_array[$comp_list_arr][] = $arrayData_value[$comp_list_arr];
                     
                     	  }
                     
                     	}
                 
                     	$salescomp = "";
                     
                     	$sql = "SELECT parent_child,parent_comp_id, loopid, haveNeed, contact, on_hold, ucbzw_flg FROM companyInfo Where ID  ='".$comp_list_arr."' $transTypeQry";
						db_b2b();
                     	$result_tmp = db_query($sql);	
                     
                     	while ($myrowsel_tmp = array_shift($result_tmp)) {
                     
                     		if($myrowsel_tmp["haveNeed"] == "Need Boxes"){
                     
                     			$salescomp = "yes"; 
                     
                     		}else{
                     
                     			$salescomp = "no";		
                     
                     		}	
                     
                     	}
                     
                     	$link_id_str = "";
                     
                     	if ($_REQUEST["search_res_sales"] == ""){
                     
                     		if ($salescomp == "yes"){
                     
                     			$link_id_str = " and  link_sales_id <> companyInfo.ID";
                     
                     		}else{
                     
                     			//$link_id_str = " and link_purchasing_id <> companyInfo.ID";
                     
                     			$link_id_str = " and link_sales_id <> companyInfo.ID";
                     
                     		}
                     
                     	}	
                     
                     	//
                     
                     	$tot_trans = 0;$tot_trans_p = 0;
                     
                     	$total_summtd_p=0; $total_summtd_s=0; $total_s_trans=0; $total_p_trans=0;
                     
                     	$summtd_SUMPO = 0; 
                     
                     	$cmp_qry = "Select companyInfo.shipCity , companyInfo.shipState, companyInfo.shipZip, companyInfo.ucbzw_next_step AS UZWNS, companyInfo.ucbzw_next_date AS UZWND, companyInfo.id AS I, companyInfo.loopid AS LID, companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH, companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.status, companyInfo.last_contact_date AS LD, companyInfo.next_date AS ND,  companyInfo.special_ops, companyInfo.ucbzw_flg, companyInfo.industry_id, companyInfo.ucbzw_account_status, companyInfo.ucbzw_account_owner, companyInfo.haveNeed, companyInfo.industry_other, companyInfo.parent_child, companyInfo.assignedto, companyInfo.parent_comp_id, companyInfo.link_sales_id, companyInfo.link_purchasing_id from companyInfo where companyInfo.ID ='".$comp_list_arr."' $link_id_str $transTypeQry order by dateCreated";
                     
						db_b2b();
                     	$cmp_res=db_query($cmp_qry);
                     
                     	while($data=array_shift($cmp_res))
                     
                     	{
                     
                     		//
                     
                     		if($data["haveNeed"] == "Need Boxes"){
                     
                     			$salescomp_ind_com = "yes"; 
                     
                     		}else{
                     
                     			$salescomp_ind_com = "no";
                     
                     		}	
                     
                     		//UCB_ZW rep and water account
                     
                     	
                     
                     			$empqry="select initials from employees where employees.employeeID='".$data["assignedto"]."'";
								db_b2b();
                     			$empres=db_query($empqry);
                     
                     			$emprow=array_shift($empres);
                     
                     			$UCB_ZW_Rep=$emprow["initials"];
                     
                     			$UCB_ZW_Rep1=$emprow["initials"];
                     
                     			//
                     
                     		if($data["ucbzw_flg"]==0){
                     
                     			$water_rec="No";
                     
                     		}
                     
                     		else{
                     
                     			$empqry="select initials from employees where employees.employeeID='".$data["ucbzw_account_owner"]."'";
								db_b2b();
                     			$empres=db_query($empqry);
                     
                     			$emprow=array_shift($empres);
                     
                     			//
                     
                     			$empqry="select initials from employees where employees.employeeID='".$data["ucbzw_account_owner"]."'";
								db_b2b();
                     			$empres=db_query($empqry);
                     
                     			$emprow=array_shift($empres);
                     
                     			//
                     
                     			$UCB_ZW_Rep_w=$emprow["initials"];
                     
                     			$UCB_ZW_Rep=$UCB_ZW_Rep. "/".$UCB_ZW_Rep_w;
                     
                     			//
                     
                     			$water_rec="Yes";
                     
                     			//
                     
                     		}
                     
                     		if($UCB_ZW_Rep1=="")
                     
                     		{
                     
                     			//$UCB_ZW_Rep="<span style='color:#FF0000;'>No Rep</span>";
                     
                     			$UCB_ZW_Rep="No Rep";
                     
                     		}
                     
                     		if($UCB_ZW_Rep1=="" && $UCB_ZW_Rep_w=="")
                     
                     		{
                     
                     			$UCB_ZW_Rep="No Rep";
                     
                     		}
                     
                     		//
                     
                     		if($data["industry_id"]!=""){
                     
                     			//
                     
                     			$indus_qry = "Select industry from industry_master where active_flg = 1 and industry_id = '" . $data["industry_id"]."'";
                     
								db_b2b();
                     
                     			$indus_res = db_query($indus_qry);
                     
                     			while ($indus_row = array_shift($indus_res)) {
                     
                     				$industry = $indus_row["industry"];
                     
                     			}
                     
                     			if ($data["industry_other"] != ""){
                     
                     				$industry = $data["industry_other"];
                     
                     			}	
                     
                     		}
                     
                     		else{
                     
                     			$industry = "No Industry Selected";
                     
                     		}
                     
                     		//Parent or child or none
                     
                     		$parentchild=$data["parent_child"];
                     
                     		//echo $parentchild."<br>";
                     
                     		if($parentchild!="")
                     
                     		{
                     
                     			if($parentchild=="Parent")
                     
                     			{
                     
                     				$parentchild_txt="This is the Parent";
                     
                     				$parent="yes";
                     
                     			}
                     
                     			if ($parentchild == 'Child') {
                     
                     				$parent_compname=$data["CO"];
                     
                     				$parent_comp_id=$data["parent_comp_id"];
                     
                     				//echo $data["CO"];
                     
                     				$parentchild_txt = "<a href='viewCompany.php?ID=$parent_comp_id' target='_blank'> " . get_nickname_val($data["CO"], $parent_comp_id)." </a>";
                     
                     				$parent="no";
                     
                     			}
                     
                     		}
                     
                     		else{
                     
                     			$parentchild_txt="No Family Tree Setup";
                     
                     			$parent="no";
                     
                     		}
                     
                     		//
                     
                     		if($data["special_ops"]==0){
                     
                     			$specialops="No";
                     
                     		}
                     
                     		else{
                     
                     			$specialops="Yes";
                     
                     		}
                     
                     		//
                     
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
                     
                     		//Total transactions
                     
                     		$link_sid=0; $link_pid=0;
                     
                     		if($data['link_purchasing_id']>0 && $salescomp_ind_com == "yes" )
                     
                     		{
                     
                     			$lqry="select ID, loopid, assignedto, industry_id, industry_other from companyInfo where ID=".$data['link_purchasing_id'].$transTypeQry;
								db_b2b();
                     			$l_res = db_query($lqry);
                     
                     			$lrows = array_shift($l_res);
                     
                     			$link_pid=$lrows["loopid"];
                     
                     			
                     
                     			$empqry="select initials from employees where employees.employeeID='".$lrows["assignedto"]."'";
								db_b2b();
                     			$empres=db_query($empqry);
                     
                     			$emprow=array_shift($empres);
                     
                     			$UCB_ZW_Rep .= "/" . $emprow["initials"];
                     
                     			
                     
                     			$industry_data_found = "";
                     
                     			$indus_qry = "Select industry from industry_master where active_flg = 1 and industry_id = '" . $lrows["industry_id"]."'";
								db_b2b();
                     			$indus_res = db_query($indus_qry);
                     
                     			while ($indus_row = array_shift($indus_res)) {
                     
                     				$industry .= "/" . $indus_row["industry"];
                     
                     				$industry_data_found = "yes";
                     
                     			}
                     
                     			if ($lrows["industry_other"] != ""){
                     
                     				$industry .= "/" . $lrows["industry_other"];
                     
                     				$industry_data_found = "yes";
                     
                     			}	
                     
                     			if ($industry_data_found == ""){
                     
                     				$industry .= "/<span style='color:#FF0000;'>No Industry Selected</span>";
                     
                     			}	
                     
                     		}
                     
                     		
                     
                     		if($data['link_sales_id']>0 && $salescomp_ind_com == "no")
                     
                     		{
                     
                     			$lqry="select ID, loopid, assignedto, industry_id, industry_other from companyInfo where ID=".$data['link_sales_id'] .$transTypeQry;
								db_b2b();
                     			$l_res = db_query($lqry);
                     
                     			$lrows = array_shift($l_res);
                     
                     			$link_sid=$lrows["loopid"];
                     
                     
                     
                     			$empqry="select initials from employees where employees.employeeID='".$lrows["assignedto"]."'";
								db_b2b();
                     			$empres=db_query($empqry);
                     
                     			$emprow=array_shift($empres);
                     
                     			$UCB_ZW_Rep .= "/" . $emprow["initials"];
                     
                     
                     
                     			$industry_data_found = "";
                     
                     			$indus_qry = "Select industry from industry_master where active_flg = 1 and industry_id = '" . $lrows["industry_id"]."'";
								db_b2b();
                     			$indus_res = db_query($indus_qry);
                     
                     			while ($indus_row = array_shift($indus_res)) {
                     
                     				$industry .= "/" . $indus_row["industry"];
                     
                     				$industry_data_found = "yes";
                     
                     			}
                     
                     			if ($lrows["industry_other"] != ""){
                     
                     				$industry .= "/" . $lrows["industry_other"];
                     
                     				$industry_data_found = "yes";
                     
                     			}	
                     
                     			if ($industry_data_found == ""){
                     
                     				$industry .= "/<span style='color:#FF0000;'>No Industry Selected</span>";
                     
                     			}	
                     
                     		}
                     
                     		
                     
                     		if ($data['LID'] != 0){
                     
                     			$qry="select count(id) as s_cnt from loop_transaction_buyer where `ignore` = 0 and warehouse_id = ". $data['LID'];
								db();
                     			$dt_view_res = db_query($qry);
                     
                     			while ($myrow = array_shift($dt_view_res)) 
                     
                     			{
                     
                     				$tot_trans = $tot_trans + $myrow['s_cnt'];
                     
                     			}
                     
                     			//
                     
                     			if($data['link_purchasing_id']>0)
                     
                     			{
                     
                     				$qry1="select count(id) as p_cnt from loop_transaction where `ignore` = 0 and warehouse_id = ". $link_pid;
                     
								    db();
                     				$dt_view_res1 = db_query($qry1);
                     
                     				while ($myrow1 = array_shift($dt_view_res1)) 
                     
                     				{
                     
                     					$tot_trans_p = $tot_trans_p + $myrow1['p_cnt'];
                     
                     				}
                     
                     			}
                     
                     
                     
                     			$qry1="select count(id) as p_cnt from loop_transaction where `ignore` = 0 and warehouse_id = ". $data['LID'];
                     
								db();
                     			$dt_view_res1 = db_query($qry1);
                     
                     			while ($myrow1 = array_shift($dt_view_res1)) 
                     
                     			{
                     
                     				$tot_trans_p = $tot_trans_p + $myrow1['p_cnt'];
                     
                     			}
                     
                     			//
                     
                     			if($data['link_sales_id']>0)
                     
                     			{
                     
                     				$qry="select count(id) as s_cnt from loop_transaction_buyer where `ignore` = 0 and warehouse_id = ". $data['link_sales_id'];
									
									db();
                     				$dt_view_res = db_query($qry);
                     
                     				while ($myrow = array_shift($dt_view_res)) 
                     
                     				{
                     
                     					$tot_trans = $tot_trans + $myrow['s_cnt'];
                     
                     				}
                     
                     			}
                     
                     		}
                     
                     		//
                     
                     		$tot_rev = 0;  $summtd_SUMPO = 0;$tot_rev_p = 0;  $summtd_SUMPO_p = 0;  //po_poorderamount
                     
                     		if ($salescomp_ind_com == "yes") {
                     
                     			$qry_s = "SELECT loop_transaction_buyer.po_employee, transaction_date, total_revenue, loop_warehouse.b2bid, loop_warehouse.company_name, inv_date_of, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.warehouse_id = '". $data['LID'] . "' AND loop_transaction_buyer.ignore < 1 order by loop_transaction_buyer.id";
                     
								db();
                     			$dt_view_res = db_query($qry_s);
                     
                     			while ($myrow = array_shift($dt_view_res)) 
                     
                     			{
                     
                     				$inv_amt_totake= $myrow["total_revenue"];
                     
                     
                     
                     				$summtd_SUMPO = $summtd_SUMPO + $inv_amt_totake;
                     
                     			}
                     
                     			if($link_pid>0)
                     
                     			{
                     
                     				$qry_p = "SELECT transaction_date, estimated_revenue, loop_warehouse.b2bid, loop_warehouse.company_name, loop_transaction.id, loop_transaction.warehouse_id FROM loop_transaction inner join loop_warehouse on loop_warehouse.id = loop_transaction.warehouse_id WHERE loop_transaction.warehouse_id = '". $link_pid . "' AND loop_transaction.ignore < 1 order by loop_transaction.id";
                     
                     				
									db();
                     				$dt_view_res = db_query($qry_p);
                     
                     				while ($myrow = array_shift($dt_view_res)) 
                     
                     				{
                     
                     					$finalpaid_amt = 0; 
                     
                     
                     
                     					$inv_amt_totake= $myrow["estimated_revenue"];
                     
                     
                     
                     					$summtd_SUMPO_p = $summtd_SUMPO_p + $inv_amt_totake;
                     
                     				}
                     
                     			}
                     
                     		}
                     
                     		else{
                     
                     			$qry_p = "SELECT transaction_date, estimated_revenue, loop_warehouse.b2bid, loop_warehouse.company_name, loop_transaction.id, loop_transaction.warehouse_id FROM loop_transaction inner join loop_warehouse on loop_warehouse.id = loop_transaction.warehouse_id WHERE loop_transaction.warehouse_id = '". $data['LID'] . "' AND loop_transaction.ignore < 1 order by loop_transaction.id";
                     
								db();
                     			$dt_view_res = db_query($qry_p);
                     
                     				while ($myrow = array_shift($dt_view_res)) 
                     
                     				{
                     
                     					$finalpaid_amt = 0; 
                     
                     
                     
                     					$inv_amt_totake= $myrow["estimated_revenue"];
                     
                     
                     
                     					$summtd_SUMPO_p = $summtd_SUMPO_p + $inv_amt_totake;
                     
                     				}
                     
                     			if($link_sid>0)
                     
                     			{
                     
                     				$qry_s = "SELECT loop_transaction_buyer.po_employee, total_revenue, transaction_date, loop_warehouse.b2bid, loop_warehouse.company_name, inv_date_of, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.warehouse_id = '". $link_sid . "' AND loop_transaction_buyer.ignore < 1 order by loop_transaction_buyer.id";
                     
                     				db();
                     
                     				$dt_view_res = db_query($qry_s);
                     
                     				while ($myrow = array_shift($dt_view_res)) 
                     
                     				{
                     
                     					$inv_amt_totake = $myrow["total_revenue"];
                     
                     					$summtd_SUMPO = $summtd_SUMPO + $inv_amt_totake;
                     
                     				}
                     
                     			}
                     
                     		}
                     
                     
                     
                     		$acc_status = "";
                     
                     		$qry_s = "SELECT name from status where id = '". $data['status'] . "'";
							
							db_b2b();
                     		$dt_view_res = db_query($qry_s);
                     
                     		while ($myrow = array_shift($dt_view_res)) 
                     
                     		{
                     
                     			$acc_status = $myrow["name"];
                     
                     		}
                     
                     
                     
                     		if(!isset($_REQUEST["sort"]))
                     
                     			{
                     
                     			$bg_color = "#E4E4E4";
                     
                     			if ($tot_trans >0 || $tot_trans_p >0){
                     
                     				$bg_color = "#D0CECE";
                     
                     			}	
                     
                     			
                     
                     			$show_rec = "yes";
                     
                     			if ($_REQUEST["chk_loop_only"] == true){
                     
                     				$show_rec = "no";
                     
                     				if ($tot_trans >0 && $tot_trans >0){
                     
                     					$show_rec = "yes";
                     
                     				}	
                     
                     			}
                     
                     			
                     
                     			if ($show_rec == "yes"){
                     
                     				$running_cnt = $running_cnt + 1;
                     
                     				
                     
                     				
                     
                     		?>
                  <tr valign="middle" id="tbl_div<?php echo $data["I"]?>">
                     <td bgcolor="<?php echo $bg_color;?>">
                        <?php echo $running_cnt;?>
                     </td>
                     <td width="21%" bgcolor="<?php echo $bg_color;?>">
                        <!-- Popup display start -->
                        <div id="showCompDt<?php echo $data['I']; ?>" style="display:none;">
                           <?php 
                              echo "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';>Close</a>";
                              
                              
                              
                              $show_child_comp_img = "";
                              
                              if ($salescomp == "yes"){
                              
                              $qryCompDt =  "SELECT A.company AS comp1, B.company AS comp2, A.ID AS comp1Id, B.ID AS comp2ID, A.special_ops AS comp1SpecialOps, B.special_ops AS comp2SpecialOps, A.ucbzw_flg AS comp1UcbzwFlg, B.ucbzw_flg AS comp2UcbzwFlg, A.parent_child AS comp1ParentChild, B.parent_child AS comp2ParentChild, A.link_sales_id, A.link_purchasing_id FROM companyInfo A, companyInfo B WHERE A.ID = '".$data['I']."' AND B.ID = '".$data['link_purchasing_id']."'";
                              
                              
							  db_b2b();
                              $cmpDtRes=db_query($qryCompDt);
                              
                              while($cmpDt = array_shift($cmpDtRes)) { 
                              
                              	$nickname1 = get_nickname_val($cmpDt["comp1"], $cmpDt["comp1Id"]);
                              
                              	
                              
                              	$nickname2 = get_nickname_val($cmpDt["comp2"], $cmpDt["comp2ID"]);
                              
                              ?>
                           <table width="500px" >
                              <tr>
                                 <td bgcolor="<?php echo $bg_color;?>" colspan="4">Which do you want?</td>
                              </tr>
                              <tr>
                                 <td bgcolor="<?php echo $bg_color;?>">Sales Record : </td>
                                 <td bgcolor="<?php echo $bg_color;?>">
                                    <a href="viewCompany.php?ID=<?php echo $cmpDt["comp1Id"]?>" target='_blank'><?php echo $nickname1; ?></a>
                                    <?php if($cmpDt["comp1SpecialOps"] != 0) { ?>
                                    &nbsp;&nbsp;<img src="images/comp_search_icons/special_ops_ic.jpg" class="image_icon">
                                    <?php 
                                       $show_child_comp_img = '<img src="images/comp_search_icons/special_ops_ic.jpg" class="image_icon">';
                                       
                                       } ?>
                                    <?php if($cmpDt["comp1UcbzwFlg"] != 0 ) { ?>
                                    &nbsp;&nbsp;<img src="images/comp_search_icons/water_ic.jpg" class="image_icon">
                                    <?php 
                                       $show_child_comp_img .= '<img src="images/comp_search_icons/water_ic.jpg" class="image_icon">';
                                       
                                       } ?>
                                    <?php if($cmpDt["comp1ParentChild"] == 'Parent') { ?>
                                    &nbsp;&nbsp;<img src="images/comp_search_icons/parent_ic.jpg" class="image_icon">
                                    <?php 
                                       $show_child_comp_img .= '<img src="images/comp_search_icons/parent_ic.jpg" class="image_icon">';
                                       
                                       } ?>
                                 </td>
                              </tr>
                              <tr>
                                 <td bgcolor="<?php echo $bg_color;?>">Purchasing Record :</td>
                                 <td bgcolor="<?php echo $bg_color;?>">
                                    <a href="viewCompany.php?ID=<?php echo $cmpDt["comp2ID"]?>" target='_blank'><?php echo $nickname2; ?></a>
                                    <?php if($cmpDt["comp2SpecialOps"] != 0) { ?>
                                    &nbsp;&nbsp;<img src="images/comp_search_icons/special_ops_ic.jpg" class="image_icon">
                                    <?php 
                                       $show_child_comp_img = '<img src="images/comp_search_icons/special_ops_ic.jpg" class="image_icon">';
                                       
                                       } ?>
                                    <?php if($cmpDt["comp2UcbzwFlg"] != 0 ) { ?>
                                    &nbsp;&nbsp;<img src="images/comp_search_icons/water_ic.jpg" class="image_icon">
                                    <?php 
                                       $show_child_comp_img .= '<img src="images/comp_search_icons/water_ic.jpg" class="image_icon">';
                                       
                                       } ?>
                                    <?php if($cmpDt["comp2ParentChild"] == 'Parent') { ?>
                                    &nbsp;&nbsp;<img src="images/comp_search_icons/parent_ic.jpg" class="image_icon">
                                    <?php 
                                       $show_child_comp_img .= '<img src="images/comp_search_icons/water_ic.jpg" class="image_icon">';
                                       
                                       } ?>
                                 </td>
                              </tr>
                           </table>
                           <?php
                              }
                              
                              
                              
                              }else{
                              
                              $qryCompDt =  "SELECT A.company AS comp1, B.company AS comp2, A.ID AS comp1Id, B.ID AS comp2ID, A.special_ops AS comp1SpecialOps, B.special_ops AS comp2SpecialOps, A.ucbzw_flg AS comp1UcbzwFlg, B.ucbzw_flg AS comp2UcbzwFlg, A.parent_child AS comp1ParentChild, B.parent_child AS comp2ParentChild, A.link_sales_id, A.link_purchasing_id FROM companyInfo A, companyInfo B WHERE A.ID = '".$data['I']."' AND B.ID = '".$data['link_sales_id']."'";
                              
                              
                              db_b2b();
                              $cmpDtRes=db_query($qryCompDt);
                              
                              while($cmpDt = array_shift($cmpDtRes)) { 
                              
                              	$nickname1 = get_nickname_val($cmpDt["comp1"], $cmpDt["comp1Id"]);
                              
                              	
                              
                              	$nickname2 = get_nickname_val($cmpDt["comp2"], $cmpDt["comp2ID"]);
                              
                              ?>
                           <table width="500px" >
                              <tr>
                                 <td bgcolor="<?php echo $bg_color;?>" colspan="4">Which do you want?</td>
                              </tr>
                              <tr>
                                 <td bgcolor="<?php echo $bg_color;?>">Sales Record :</td>
                                 <td bgcolor="<?php echo $bg_color;?>">
                                    <a href="viewCompany.php?ID=<?php echo $cmpDt["comp2ID"]?>" target='_blank'><?php echo $nickname2; ?></a>
                                    <?php if($cmpDt["comp2SpecialOps"] != 0) { ?>
                                    &nbsp;&nbsp;<img src="images/comp_search_icons/special_ops_ic.jpg" class="image_icon">
                                    <?php 
                                       $show_child_comp_img = '<img src="images/comp_search_icons/special_ops_ic.jpg" class="image_icon">';
                                       
                                       } ?>
                                    <?php if($cmpDt["comp2UcbzwFlg"] != 0 ) { ?>
                                    &nbsp;&nbsp;<img src="images/comp_search_icons/water_ic.jpg" class="image_icon">
                                    <?php 
                                       $show_child_comp_img .= '<img src="images/comp_search_icons/water_ic.jpg" class="image_icon">';
                                       
                                       } ?>
                                    <?php if($cmpDt["comp2ParentChild"] == 'Parent') { ?>
                                    &nbsp;&nbsp;<img src="images/comp_search_icons/parent_ic.jpg" class="image_icon">
                                    <?php 
                                       $show_child_comp_img .= '<img src="images/comp_search_icons/water_ic.jpg" class="image_icon">';
                                       
                                       } ?>
                                 </td>
                              </tr>
                              <tr>
                                 <td bgcolor="<?php echo $bg_color;?>">Purchasing Record : </td>
                                 <td bgcolor="<?php echo $bg_color;?>">
                                    <a href="viewCompany.php?ID=<?php echo $cmpDt["comp1Id"]?>" target='_blank'><?php echo $nickname1; ?></a>
                                    <?php if($cmpDt["comp1SpecialOps"] != 0) { ?>
                                    &nbsp;&nbsp;<img src="images/comp_search_icons/special_ops_ic.jpg" class="image_icon">
                                    <?php
                                       $show_child_comp_img = '<img src="images/comp_search_icons/special_ops_ic.jpg" class="image_icon">';
                                       
                                       } ?>
                                    <?php if($cmpDt["comp1UcbzwFlg"] != 0 ) { ?>
                                    &nbsp;&nbsp;<img src="images/comp_search_icons/water_ic.jpg" class="image_icon">
                                    <?php
                                       $show_child_comp_img .= '<img src="images/comp_search_icons/water_ic.jpg" class="image_icon">';
                                       
                                       } ?>
                                    <?php if($cmpDt["comp1ParentChild"] == 'Parent') { ?>
                                    &nbsp;&nbsp;<img src="images/comp_search_icons/parent_ic.jpg" class="image_icon">
                                    <?php 
                                       $show_child_comp_img .= '<img src="images/comp_search_icons/parent_ic.jpg" class="image_icon">';
                                       
                                       } ?>
                                 </td>
                              </tr>
                           </table>
                           <?php
                              }
                              
                              
                              
                              }									
                              
                              ?>
                        </div>
                        <!-- Popup display end -->    							
                        <?php
                           $new_nickname = highlightSearchKeywords($nickname,$searchcrit);
                           
                           if(($data['link_sales_id'] > 0 || $data['link_purchasing_id'] > 0) && ($_REQUEST["search_res_sales"] == "")){
                           
                           ?>
                        <a href="javascript:void(0)" id="compid<?php echo $data['I']; ?>" onclick="openCompPopup(<?php echo $data['I']; ?>)"  ><?php echo $new_nickname; ?></a>
                        &nbsp;&nbsp;<img src="images/comp_search_icons/sales_purchasing_icon.jpg" class="image_icon">
                        <?php 
                           echo $show_child_comp_img;
                           
                           }else{ ?>
                        <a href="viewCompany.php?ID=<?php echo $data["I"]?>" target='_blank'><?php echo $new_nickname; ?></a>&nbsp;&nbsp;
                        <?php
                           if($water_rec=="Yes")
                           
                           {?>
                        &nbsp;&nbsp;<img src="images/comp_search_icons/water_ic.jpg" class="image_icon">
                        <?php
                           }
                           
                           } ?>
                        <?php
                           if($specialops=="Yes")
                           
                           {
                           
                           	?><img src="images/comp_search_icons/special_ops_ic.jpg" class="image_icon"><?php
                           }
                           
                           
                           
                           if($parent=="yes")
                           
                           {
                           
                           ?>&nbsp;&nbsp;<img src="images/comp_search_icons/parent_ic.jpg" class="image_icon"><?php
                           }
                           
                           ?>	
                     </td>
                     <td bgcolor="<?php echo $bg_color;?>" align="center">
                        <?php $font_str_tag = ""; $font_str_tag2 = "";
                           if ($found_in_contact == "yes") {
                           
                           	$font_str_tag = "<span style='background:#FFFE63;'>";
                           
                           	$font_str_tag2 = "</span>";
                           
                           }
                           
                           ?>
                        <a href="#" id="com_contact<? echo $data["I"]; ?>" onclick="showcontact_details(<?php echo $data["I"]; ?>, '<?php echo $searchcrit; ?>'); return false;"><?php echo $font_str_tag;?><img src="images/contact_plus.png" width='13' height='13' /><?php echo $font_str_tag2;?></a><?php echo highlightSearchKeywords($data["C"],$searchcrit); ?>
                     </td>
                     <td bgcolor="<?php echo $bg_color;?>" align="center"><?php echo $UCB_ZW_Rep?></td>
                     <td bgcolor="<?php echo $bg_color;?>" align="center"><?php echo $acc_status; ?></td>
                     <td bgcolor="<?php echo $bg_color;?>" align="left"><?php echo highlightSearchKeywords($industry,$searchcrit); ?></td>
                     <td bgcolor="<?php echo $bg_color;?>" align="center"><?php echo $tot_trans; ?></td>
                     <td bgcolor="<?php echo $bg_color;?>" align="right">$<?php echo number_format($summtd_SUMPO,0); ?></td>
                     <td bgcolor="<?php echo $bg_color;?>" align="center"><?php echo $tot_trans_p; ?></td>
                     <td bgcolor="<?php echo $bg_color;?>" align="right">$<?php echo number_format($summtd_SUMPO_p,0); ?></td>
                     <td bgcolor="<?php echo $bg_color;?>" align="left"><?php echo highlightSearchKeywords($parentchild_txt, $searchcrit); ?></td>
                     <td bgcolor="<?php echo $bg_color;?>" align="left">
                        <?php if ($data["UZWNS"] != "" ) { 
                           echo "<b>UCBoxes Next Step:</b> " . highlightSearchKeywords($data["NS"],$searchcrit);
                           
                           echo "<br><br><b>UCBZW Next Step:</b> " . highlightSearchKeywords($data["UZWNS"],$searchcrit); 
                           
                           }else{
                           
                           echo highlightSearchKeywords($data["NS"],$searchcrit);
                           
                           }
                           
                           ?>
                     </td>
                     <td  bgcolor="<?php if (date('Y-m-d',strtotime($data["LD"])) == date('Y-m-d')) { echo "#00FF00"; } elseif ( date('Y-m-d',strtotime($data["LD"])) < date('Y-m-d', strtotime("-90 days")) ) { echo "#FF0000"; } else { echo $bg_color; }?>" align="left">
                        <?php if ($data["LD"]!="") { echo date('m/d/Y',strtotime($data["LD"])); }?>
                     </td>
                     <?php if ($data["UZWND"]!="" && $data["UZWND"] != "1969-12-31") {?>
                     <td align="center" bgcolor="<?php echo $bg_color;?>">
                        <?php if ($data["UZWND"]!="") {?>
                        <div style="<?php if ($data["ND"] == date('Y-m-d')) { ?> background:#00FF00; <?php } elseif ($data["ND"] < date('Y-m-d') && $data["ND"] != "") { ?> background:#FF0000; <?php } else { ?> background:<?php echo $bgcolor_str;?> <?php } ?>" >
                           <?php if ($data["LID"] > 0) ?><?php if ($data["ND"]!="") echo "<b>UCBoxes Next Comm.:</b> " . date('m/d/Y',strtotime($data["ND"]));?>
                        </div>
                        <div style="<?php if ($data["UZWND"] == date('Y-m-d')) { ?> background:#00FF00; <?php } elseif ($data["UZWND"] < date('Y-m-d') && $data["UZWND"] != "") { ?> background:#FF0000; <?php } else { ?> background:<?php echo $bgcolor_str;?> <?php } ?>" >
                           <?php if ($data["UZWND"]!="" ) echo "<br><br><b>UCBZW Next Comm.:</b> ". date('m/d/Y',strtotime($data["UZWND"]));?>
                        </div>
                     </td>
                     <?php		}
                        }else{?>
                     <td align="center" bgcolor="<?php if ($data["ND"] == date('Y-m-d')) { echo "#00FF00"; } elseif ($data["ND"] < date('Y-m-d') && $data["ND"] != "") { echo "#FF0000"; } else { echo $bgcolor_str; } ?>">
                        <?php if ($data["LID"] > 0) ?><?php if ($data["ND"]!="") echo date('m/d/Y',strtotime($data["ND"]));?>
                     </td>
                     <?php }?>
                  </tr>
                  <?php
                     }
                     
                     }
                     
                     
                     
                     $MGArray_s[] = array('comp_b2bid' => $data["I"], 'contactnm' => $data["C"], 'acc_status' => $acc_status, 'comp_loopid' => $data["LID"], 'special_ops_flg' => $specialops, 
                     
                     'water_rec_flg' => $water_rec, 'parent_flg' => $parent, 'UCB_ZW_Rep' => $UCB_ZW_Rep, 'companyname' => $nickname, 'industry' => $industry, 
                     
                     'total_trans' => $tot_trans,'total_trans_p' => $tot_trans_p, 'summtd_SUMPO' => $summtd_SUMPO, 'summtd_SUMPO_p' => $summtd_SUMPO_p, 'next_step' => $data["NS"], 
                     
                     'last_date' => $data["LD"], 'next_date' => $data["ND"], 'parentchild' => $parentchild, 'parentchild_txt' => $parentchild_txt,'results_value1' => $results_value1); 
                     
                     
                     
                     //
                     
                     $_SESSION['sortarrayn_sort'] = $MGArray_s;
                     
                     //
                     
                     // print_r($MGArray_s);
                     
                     //
                     
                     }//end while loop
                     
                     }//End foreach
                     
                     
                     
                     if(isset($_REQUEST["sort"]))
                     
                     {
                     
                     $MGArray = $_SESSION['sortarrayn_sort'];
                     
                     // print_r($MGArray);
                     
                     //company name
                     
                     if($_REQUEST['sort'] == "cname")
                     
                     {
                     
                     $MGArraysort_I = array();
                     
                     
                     
                     foreach ($MGArray as $MGArraytmp) {
                     
                     $MGArraysort_I[] = $MGArraytmp['companyname'];
                     
                     
                     
                     }
                     
                     
                     
                     if ($_REQUEST['so'] == "A"){
                     
                     array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
                     
                     
                     
                     }
                     
                     if ($_REQUEST['so'] == "D"){
                     
                     array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
                     
                     }
                     
                     //
                     
                     }
                     
                     if($_REQUEST['sort'] == "acc_status")
                     
                     {
                     
                     $MGArraysort_I = array();
                     
                     
                     
                     foreach ($MGArray as $MGArraytmp) {
                     
                     $MGArraysort_I[] = $MGArraytmp['acc_status'];
                     
                     }
                     
                     
                     
                     if ($_REQUEST['so'] == "A"){
                     
                     array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
                     
                     
                     
                     }
                     
                     if ($_REQUEST['so'] == "D"){
                     
                     array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
                     
                     }
                     
                     //
                     
                     }
                     
                     if($_REQUEST['sort'] == "ei")
                     
                     {
                     
                     $MGArraysort_I = array();
                     
                     
                     
                     foreach ($MGArray as $MGArraytmp) {
                     
                     $MGArraysort_I[] = $MGArraytmp['UCB_ZW_Rep'];
                     
                     
                     
                     }
                     
                     
                     
                     if ($_REQUEST['so'] == "A"){
                     
                     array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
                     
                     
                     
                     }
                     
                     if ($_REQUEST['so'] == "D"){
                     
                     array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
                     
                     }
                     
                     }
                     
                     if($_REQUEST['sort'] == "inds")
                     
                     {
                     
                     $MGArraysort_I = array();
                     
                     
                     
                     foreach ($MGArray as $MGArraytmp) {
                     
                     $MGArraysort_I[] = $MGArraytmp['industry'];
                     
                     
                     
                     }
                     
                     
                     
                     if ($_REQUEST['so'] == "A"){
                     
                     array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
                     
                     
                     
                     }
                     
                     if ($_REQUEST['so'] == "D"){
                     
                     array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
                     
                     }
                     
                     }
                     
                     if($_REQUEST['sort'] == "contactnm")
                     
                     {
                     
                     $MGArraysort_I = array();
                     
                     
                     
                     foreach ($MGArray as $MGArraytmp) {
                     
                     $MGArraysort_I[] = $MGArraytmp['contactnm'];
                     
                     
                     
                     }
                     
                     
                     
                     if ($_REQUEST['so'] == "A"){
                     
                     array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
                     
                     
                     
                     }
                     
                     if ($_REQUEST['so'] == "D"){
                     
                     array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
                     
                     }
                     
                     }
                     
                     if($_REQUEST['sort'] == "s_trans")
                     
                     {
                     
                     $MGArraysort_I = array();
                     
                     
                     
                     foreach ($MGArray as $MGArraytmp) {
                     
                     $MGArraysort_I[] = $MGArraytmp['total_trans'];
                     
                     
                     
                     }
                     
                     
                     
                     if ($_REQUEST['so'] == "A"){
                     
                     array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
                     
                     
                     
                     }
                     
                     if ($_REQUEST['so'] == "D"){
                     
                     array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
                     
                     }
                     
                     }
                     
                     if($_REQUEST['sort'] == "p_trans")
                     
                     {
                     
                     $MGArraysort_I = array();
                     
                     
                     
                     foreach ($MGArray as $MGArraytmp) {
                     
                     $MGArraysort_I[] = $MGArraytmp['total_trans_p'];
                     
                     
                     
                     }
                     
                     
                     
                     if ($_REQUEST['so'] == "A"){
                     
                     array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
                     
                     
                     
                     }
                     
                     if ($_REQUEST['so'] == "D"){
                     
                     array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
                     
                     }
                     
                     }
                     
                     if($_REQUEST['sort'] == "s_revenue")
                     
                     {
                     
                     $MGArraysort_I = array();
                     
                     
                     
                     foreach ($MGArray as $MGArraytmp) {
                     
                     $MGArraysort_I[] = $MGArraytmp['summtd_SUMPO'];
                     
                     
                     
                     }
                     
                     if ($_REQUEST['so'] == "A"){
                     
                     array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
                     
                     }
                     
                     if ($_REQUEST['so'] == "D"){
                     
                     array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
                     
                     }
                     
                     }
                     
                     if($_REQUEST['sort'] == "p_pay")
                     
                     {
                     
                     $MGArraysort_I = array();
                     
                     
                     
                     foreach ($MGArray as $MGArraytmp) {
                     
                     $MGArraysort_I[] = $MGArraytmp['summtd_SUMPO_p'];
                     
                     
                     
                     }
                     
                     if ($_REQUEST['so'] == "A"){
                     
                     array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
                     
                     }
                     
                     if ($_REQUEST['so'] == "D"){
                     
                     array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
                     
                     }
                     
                     }
                     
                     if($_REQUEST['sort'] == "pc")
                     
                     {
                     
                     $MGArraysort_I = array();
                     
                     
                     
                     foreach ($MGArray as $MGArraytmp) {
                     
                     $MGArraysort_I[] = $MGArraytmp['parentchild'];
                     
                     
                     
                     }
                     
                     if ($_REQUEST['so'] == "A"){
                     
                     array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
                     
                     }
                     
                     if ($_REQUEST['so'] == "D"){
                     
                     array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
                     
                     }
                     
                     }
                     
                     if($_REQUEST['sort'] == "ns")
                     
                     {
                     
                     $MGArraysort_I = array();
                     
                     
                     
                     foreach ($MGArray as $MGArraytmp) {
                     
                     $MGArraysort_I[] = $MGArraytmp['next_step'];
                     
                     
                     
                     }
                     
                     if ($_REQUEST['so'] == "A"){
                     
                     array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
                     
                     }
                     
                     if ($_REQUEST['so'] == "D"){
                     
                     array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
                     
                     }
                     
                     }
                     
                     if($_REQUEST['sort'] == "lc")
                     
                     {
                     
                     $MGArraysort_I = array();
                     
                     
                     
                     foreach ($MGArray as $MGArraytmp) {
                     
                     $MGArraysort_I[] = $MGArraytmp['last_date'];
                     
                     
                     
                     }
                     
                     if ($_REQUEST['so'] == "A"){
                     
                     array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
                     
                     }
                     
                     if ($_REQUEST['so'] == "D"){
                     
                     array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
                     
                     }
                     
                     }
                     
                     if($_REQUEST['sort'] == "nc")
                     
                     {
                     
                     $MGArraysort_I = array();
                     
                     
                     
                     foreach ($MGArray as $MGArraytmp) {
                     
                     $MGArraysort_I[] = $MGArraytmp['next_date'];
                     
                     
                     
                     }
                     
                     if ($_REQUEST['so'] == "A"){
                     
                     array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
                     
                     }
                     
                     if ($_REQUEST['so'] == "D"){
                     
                     array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
                     
                     }
                     
                     }
                     
                     
                     
                     $running_cnt = 0;
                     
                     foreach ($MGArray as $MGArraytmp2) { 
                     
                     
                     
                     $bg_color = "#E4E4E4";
                     
                     $tot_trans = $MGArraytmp2["total_trans"];
                     
                     $tot_trans_p = $MGArraytmp2["total_trans_p"];
                     
                     if ($tot_trans >0 || $tot_trans_p >0){
                     
                     $bg_color = "#D0CECE";
                     
                     }	
                     
                     
                     
                     $show_rec = "yes";
                     
                     if ($_REQUEST["chk_loop_only"] == true){
                     
                     $show_rec = "no";
                     
                     if ($tot_trans >0 && $tot_trans >0){
                     
                     	$show_rec = "yes";
                     
                     }	
                     
                     }
                     
                     
                     
                     if ($show_rec == "yes"){
                     
                     $running_cnt = $running_cnt + 1;
                     
                     ?>
                  <tr valign="middle" id="tbl_div<?php echo $MGArraytmp2["comp_b2bid"]?>">
                     <td bgcolor="<?php echo $bg_color;?>">
                        <?php echo $running_cnt;?>
                     </td>
                     <td width="21%" bgcolor="<?php echo $bg_color;?>"><a href="viewCompany.php?ID=<?php echo $MGArraytmp2["comp_b2bid"]?>" target='_blank'><?php echo highlightSearchKeywords($MGArraytmp2["companyname"],$searchcrit); ?></a>&nbsp;&nbsp;
                        <?php
                           if($MGArraytmp2["special_ops_flg"]=="Yes")
                           
                           {
                           
                           ?>
                        <img src="images/comp_search_icons/special_ops_ic.jpg" class="image_icon">
                        <?php
                           }
                           
                           
                           
                           if($MGArraytmp2["water_rec_flg"]=="Yes")
                           
                           {
                           
                           ?>&nbsp;&nbsp;<img src="images/comp_search_icons/water_ic.jpg" class="image_icon"><?php
                           }
                           
                           if($MGArraytmp2["parent_flg"]=="yes")
                           
                           {
                           
                           ?>&nbsp;&nbsp;<img src="images/comp_search_icons/parent_ic.jpg" class="image_icon"><?php
                           }
                           
                           ?>	
                     </td>
                     <td bgcolor="<?php echo $bg_color;?>" align="center"><a href="#" id="com_contact<?php echo $MGArraytmp2["comp_b2bid"]; ?>" onclick="showcontact_details(<?php echo $MGArraytmp2["comp_b2bid"]; ?>, '<?php echo $searchcrit; ?>'); return false;" ><?php echo highlightSearchKeywords($MGArraytmp2["contactnm"],$searchcrit); ?></td>
                     <td bgcolor="<?php echo $bg_color;?>" align="center"><?php echo $MGArraytmp2["UCB_ZW_Rep"]?></td>
                     <td bgcolor="<?php echo $bg_color;?>" align="center"><?php echo $MGArraytmp2["acc_status"]?></td>
                     <td bgcolor="<?php echo $bg_color;?>" align="left"><?php echo highlightSearchKeywords($MGArraytmp2["industry"],$searchcrit); ?></td>
                     <td bgcolor="<?php echo $bg_color;?>" align="center"><?php echo $MGArraytmp2["total_trans"]; ?></td>
                     <td bgcolor="<?php echo $bg_color;?>"align="right">$<?php echo number_format($MGArraytmp2["summtd_SUMPO"],2); ?></td>
                     <td bgcolor="<?php echo $bg_color;?>" align="center"><?php echo $MGArraytmp2["total_trans_p"]; ?></td>
                     <td bgcolor="<?php echo $bg_color;?>" align="right">$<?php echo number_format($MGArraytmp2["summtd_SUMPO_p"],2); ?></td>
                     <td bgcolor="<?php echo $bg_color;?>" align="left"><?php echo highlightSearchKeywords($MGArraytmp2["parentchild_txt"],$searchcrit); ?></td>
                     <td bgcolor="<?php echo $bg_color;?>" align="left"><?php echo highlightSearchKeywords($MGArraytmp2["next_step"],$searchcrit); ?></td>
                     <td  bgcolor="<?php echo $bg_color;?>" align="left"><?php if ($MGArraytmp2["last_date"]!="") echo date('m/d/Y',strtotime($MGArraytmp2["last_date"]));?></td>
                     <td <?php if ($MGArraytmp2["next_date"] == date('Y-m-d')) { ?> bgcolor="#00FF00" <?php } elseif ($MGArraytmp2["next_date"] < date('Y-m-d') && $MGArraytmp2["next_date"] != "") { ?> bgcolor="#FF0000" <?php } else { ?> bgcolor="<?php echo $bg_color;?>"  <?php } ?> align="center"><?php if ($MGArraytmp2["comp_loopid"] > 0) ?><?php if ($MGArraytmp2["next_date"]!="") echo date('m/d/Y',strtotime($MGArraytmp2["next_date"]));?></td>
                  </tr>
                  <?php	
                     }
                     
                     }//End foreach array
                     
                     }//end if request sort
                     
                     
                     
                     ?>
               </table>
               <?php
                  }//End if POST
                  
                  ?>
            </div>
         </div>
      </div>
   </body>
</html>