<?php
require ("inc/header_session.php");
require ("../mainfunctions/database.php");
require ("../mainfunctions/general-functions.php");

session_start();
$chkinitials =  $_COOKIE['userinitials'];
//
db_b2b();

$matchStr_e= "Select * from employees WHERE initials='".$chkinitials."'";

$res_e = db_query($matchStr_e);
$row_e=array_shift($res_e);
$logged_emp_id=$row_e["employeeID"];
?>

<html>

<head>

<title>Inventory Item Quoting History</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet"> 

<link rel='stylesheet' type='text/css' href='css/ucb_common_style.css' >
	
<script language="JavaScript" src="js/jquery.js"></script>

<script language="JavaScript" src="gen_functions.js"></script>

<style type="text/css">
	
	.black_overlay{
		display: none;
		position: absolute;
		top: 0%;
		left: 0%;
		width: 100%;
		height: 100%;
		background-color: gray;
		z-index:1001;
		-moz-opacity: 0.8;
		opacity:.80;
		filter: alpha(opacity=80);
	}

	.white_content {
		display: none;
		position: absolute;
		top: 5%;
		left: 10%;
		width: 60%;
		height: 70%;
		padding: 16px;
		border: 1px solid gray;
		background-color: white;
		z-index:1002;
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
		z-index:1002;
		overflow: auto;
		box-shadow: 8px 8px 5px #888888;
	}
	table.sortable {
  		border-collapse: collapse;
	}

	.main_data_css{
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
	
table.sortable td, table.sortable th {
  border: 1px solid #FFF;
  /*padding: 8px;*/
}
 table.sortable tr th{
        white-space: nowrap;
    }
  </style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
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
<script language="javascript">

	function loadfrm(field_val){

		if (field_val == 1) {

			document.getElementById("sort").value = "company_name";		

		}

		if (field_val == 2) {

			document.getElementById("sort").value = "account_owner";		

		}

		if (field_val == 3) {

			document.getElementById("sort").value = "selltoemail";		

		}

		if (field_val == 4) {

			document.getElementById("sort").value = "lastcrmdt";		

		}
		if (field_val == 5) {			document.getElementById("sort").value = "acc_status";				
        }		
        if (field_val == 6) {			
            document.getElementById("sort").value = "quote_date";				
        }

		

		document.frmrep.submit();

	}
    //
    function show_file_inviewer_pos(filename, formtype, ctrlnm){

		var selectobject = document.getElementById(ctrlnm); 
		var n_left = f_getPosition(selectobject, 'Left');
		var n_top  = f_getPosition(selectobject, 'Top');

        
		document.getElementById("light").innerHTML= "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center>" + formtype +	"</center><br/> <embed src='"+ filename + "' width='800' height='800'>";
		document.getElementById('light').style.display='block';

		document.getElementById('light').style.left = 500 + 'px';
		document.getElementById('light').style.top = n_top + 10 + 'px';
	}

</script>


</head>



<body bgcolor="#FFFFFF" text="#333333" link="#333333" vlink="#666666" alink="#333333">

<script type="text/javascript" src="wz_tooltip.js"></script>
<div id="light" class="white_content"></div>


<?php include("inc/header.php"); ?>
	
<div class="main_data_css">
	
	<div class="dashboard_heading" style="float: left;">
		<div style="float: left;">Inventory Item Quoting History</div>
		&nbsp;<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
		<span class="tooltiptext">This report shows the user all of the instances where this inventory item was quoted and the status of those quotes.</span></div>
		<div style="height: 13px;">&nbsp;</div>
	</div>
	
<table border="0" width="100%" cellspacing="0" cellpadding="5">
  <tr>
    <td  valign="top">

<form action=report_find_quoters.php method=get>
<!-- 
<h2>INVENTORY ITEM QUOTING HISTORY</h2>
<div style="margin-bottom: 15px;">This report shows the user all of the instances where this inventory item was quoted and the status of those quotes.</div>
-->
<div ><i>Note: Please wait until you see <font color="red">"END OF REPORT"</font> at the bottom of the report, before using the sort option.</i></div>

<table width="1100px" border="0" cellspacing="2" cellpadding="2" bgcolor="#E4E4E4">





<tr align="center">

<td colspan="2" bgcolor="#C0CDDA"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333">GAYLORDS</font></td>

</tr>

<tr align="center">

<td colspan="2" align="left" bgcolor="#C0CDDA"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333">

<?php
db_b2b();
$matchStr2= "Select * from inventory";
$res = db_query($matchStr2);
?>
   
<select name="inventoryID">
<?php

while ($row = array_shift($res)) {
	echo "<option value='" . $row["ID"] . "'";
	if ($row["ID"] == $_REQUEST["inventoryID"]) echo " selected ";

	echo " >";

	$tipStr = $row["lengthInch"]. " x " . $row["widthInch"]. " x " . $row["depthInch"] . " " . $row["description"];

	echo $tipStr . "</option>";
}
?>
</select>

</font></td>

</tr>

<tr align="center">

<td colspan="2" align="left" bgcolor="#C0CDDA"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333">

<?php
//onclick="if(this.checked){this.form.submit()}else{this.form.submit()}"
db_b2b();

$matchStr2= "Select * from employees WHERE status LIKE 'Active' ORDER by name ASC";

$res = db_query($matchStr2);

?>

<select name="employeeID">

<option value="0">All</option>

<?php



while ($row = array_shift($res)) {



echo "<option value='" . $row["employeeID"] . "' ";

if ($row["employeeID"] == $_REQUEST["employeeID"]) echo " selected ";

echo " >";





echo $row["name"] . "</option>"; //.$row["employeeID"]



}
?>
</select>

</font></td>

</tr>

<tr>
	<td align="left">
		<input type=submit value="Find Matches">
	</td>
    <td align="right">
		<input type="checkbox" name="showall_emp" id="showall_emp" value="yes" <?php if (empty($_REQUEST["showall_emp"])){ } else { echo " checked "; }?> onclick="if(this.checked){this.form.submit()}else{this.form.submit()}" >Show only my record(s)
	</td>
</tr>

</table>

</form>



<form name="frmrep" action="report_find_quoters.php" method="post">
<br>
 <?php 
    $sorturl="report_find_quoters.php?inventoryID=".$_REQUEST['inventoryID']."&employeeID=".$_REQUEST['employeeID']; 
	if (isset($_REQUEST["showall_emp"])){ 
            $sorturl.="&showall_emp=yes";
	}else{
	
	} 
?>
<table width="1100px" border="0" cellspacing="2" cellpadding="2" bgcolor="#E4E4E4" class="sortable">
	<tr align="center">
		<th bgcolor="#C0CDDA" style="font-weight:bold;background:#C0CDDA; padding:5px 15px; border:0 none;cursor:pointer;">Sr. No.</th>
		
		<th bgColor="#C0CDDA" style="height: 16px" align="middle">
			<strong>Quote Date</strong>
			  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=quote_date"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=quote_date"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
			</font> 
		   
		</th>
		
		<th bgColor="#C0CDDA" style="height: 16px" align="middle">
			<strong>Quote ID</strong>
			  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=quote_id"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=quote_id"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
			</font> 
		   
		</th>

		<th bgColor="#C0CDDA" style="height: 16px" align="middle">
			<strong>Company</strong>
			  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=company_name"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=company_name"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
			</font>  
			
		   </th>	

		<th bgColor="#C0CDDA" style="height: 16px" align="middle">
			<strong>Rep</strong>
			  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=account_owner"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=account_owner"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
			</font>  
		  </th>	

		<th bgColor="#C0CDDA" style="height: 16px" align="middle">
			<strong>Last Contact Date</strong>
			  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=lastcrmdt"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=lastcrmdt"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
			</font> 
			</th>	

		<th bgColor="#C0CDDA" style="height: 16px" align="middle">
			<strong>Industry</strong>
			  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=industry_sort"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=industry_sort"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
			</font> 
		</th>	

		<th bgColor="#C0CDDA" style="height: 16px" align="middle">
			<strong>Account Status</strong>
			  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=acc_status"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=acc_status"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
			</font> 
		</th>
		<th bgColor="#C0CDDA" style="height: 16px" align="middle">
			 <strong>Quoted <br>FOB <br>Price</strong>
			  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=qut_price"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=qut_price"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
		</th>
		<th bgColor="#C0CDDA" style="height: 16px" align="middle">
			 <strong>Quoted <br>Delivered<br> Price</strong>
			  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=qut_del_price"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=qut_del_price"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
		</th>
		<th bgColor="#C0CDDA" style="height: 16px" align="middle">
			 <strong>Quoted Status</strong>
			  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=qut_status"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=qut_status"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
		</th>

	</tr>
<?php

if ($_REQUEST["inventoryID"] != "" && (!isset($_REQUEST["sort"]))) {
 //   
 	db();
  
	$quotes_archive_date = "";
	$query = "SELECT variablevalue FROM tblvariable where variablename = 'quotes_archive_date'";
	$dt_view_res3 = db_query($query);
	while ($objQuote= array_shift($dt_view_res3)) {
		$quotes_archive_date = $objQuote["variablevalue"];
	}

  if ($_REQUEST["showall_emp"] == "yes"){
	db();
	$queryb=db_query("select * from loop_manage_quote_buy_temp where report_name='report_find_quoters'  AND employeeid = '" .$logged_emp_id."' AND gid=".$_REQUEST["inventoryID"]); 
  }else{
	db();
	$queryb=db_query("select * from loop_manage_quote_buy_temp where report_name='report_find_quoters'  AND employeeid = '" .$_REQUEST["employeeID"]."' AND gid=".$_REQUEST["inventoryID"]); 
  }
    
  $numrec=tep_db_num_rows($queryb);
  if($numrec>0)
  {
	//
	//echo "Yes";
	$srno =0;
		//
	while($objGBmatch=array_shift($queryb))
	{
		$nickname=$objGBmatch["company"];
		$lastcrmdt=$objGBmatch["last_contact_date"];
		$acc_status=$objGBmatch["account_status"]; 	
		$qt=$objGBmatch["quote"];
		$quote_date=$objGBmatch["quote_date"];
		$srno = $srno +1;
		//
		if($qt!="")
		{
			 if (strpos($qt, '|') !== false) {
				$qstr=explode("|",$qt);
				 $qtid=$qstr[0];
				 $qtf=$qstr[1];
				 if($qtf!="")
				 {
					$archeive_date = new DateTime(date("Y-m-d", strtotime($quotes_archive_date)));
					$quote_date = new DateTime(date("Y-m-d", strtotime($quote_rows1["quoteDate"])));
					
					if ($quote_date < $archeive_date){
						$link = "<a target='_blank' id='quotespdf".$qtid."' href='https://usedcardboardboxes.sharepoint.com/:b:/r/sites/LoopsCRMEmailAttachments/Shared%20Documents/quotes/before_oct_18_2022/" . $qtf . "'>";
					}else{
						$link="<a href='#' id='quotespdf".$qtid."' onclick=\"show_file_inviewer_pos('quotes/".$qtf."', 'Quote', 'quotespdf".$qtid."'); return false;\">";
					}
					 
				 }
			  }
			else
			{
				if ($objGBmatch["quotetype"]=="Quote") {
					$link="<a target='_blank' href='fullquote_mrg.php?ID=".$qt."'>";
					$quoteinfo=$qt;
				} 
				elseif ($objGBmatch["quotetype"]=="Quote Select") {
					$link="<a href='http://b2b.usedcardboardboxes.com/b2b5/quoteselect.asp?ID=".$qt."' target='_blank'>";
					$quoteinfo=$qt;
				}
			}
			$g_no_of_quote_sent1 = "";
			if ($link != "") {
				$new_quote_id=$qtid;
				$g_no_of_quote_sent1=$link.$new_quote_id."</a>";

			}

            db_b2b();
            $getIndustryFromCompanyInfo = db_query("SELECT industry_id FROM companyInfo WHERE ID = ".$objGBmatch["companyid"]."");
            while ($row_comp = array_shift($getIndustryFromCompanyInfo)){
                if($row_comp["industry_id"] != ''){
					db_b2b();
                    $view_parentrec = db_query("SELECT industry FROM industry_master WHERE active_flg = 1 AND industry_id = ".$row_comp["industry_id"]."");
                    while ($rec_parentrec = array_shift($view_parentrec)) {
                        $industry = $rec_parentrec["industry"];
                    }
                }
            }
		}
		?>
		<tr>
			<td> <?php echo $srno;?> </td>
			
			<td>
				<?php echo $quote_date;?>
			</td>
			
			<td>
				<?php
				echo $g_no_of_quote_sent1;
				?>
			</td>

			<td> <a href="viewCompany.php?ID=<?php echo $objGBmatch["companyid"]?>" target="_blank"><?php echo $nickname;?> </a></td>

			<td> <?php echo $objGBmatch["account_owner"];?> </td>

			<td> <?php echo $lastcrmdt;?> </td>

			<td> <?php echo $industry;?> </td>

			<td> <?php echo $acc_status;?> </td>
			
			<td> $<?php echo $objGBmatch["quote_price"];?> </td>
			<td> $<?php echo $objGBmatch["quoted_delivered_price"];?> </td>
			
			<td> <?php echo $objGBmatch["quote_status"];?> </td>
			
		</tr>	
       <?php 
		if ($lastcrmdt != ""){
			$lastcrmdt_sort = date("Y-m-d" , strtotime($lastcrmdt));
			$lastcrmdt = date("m/d/Y" , strtotime($lastcrmdt));
		}else{
			$lastcrmdt_sort = "";
			$lastcrmdt = "";
		}	
		
		$MGArray[] = array('companyID' => $objGBmatch["companyid"], 'company_name' => trim($nickname), 'selltoemail' => trim($objGBmatch["sell_to_email"]),
		'quoted_delivered_price' => $objGBmatch["quoted_delivered_price"], 'acc_status' => $acc_status, 'account_owner' => trim($objGBmatch["account_owner"]),'lastcrmemp' => trim($lastcrmemp),'lastcrmdt' => $lastcrmdt, 'industry' => $industry,'quote_date' => $quote_date,
		'g_no_of_quote_sent1' => $g_no_of_quote_sent1, 'lastcrmdt_sort' => $lastcrmdt_sort, 'new_quote_id' => $new_quote_id, 'quote_price' => $objGBmatch["quote_price"], 'quote_status' => $objGBmatch["quote_status"]);
	}
	$_SESSION['sortarrayn'] = $MGArray;
      ?>
 <?php 
}
else{
                 
	if ($_REQUEST["showall_emp"] == "yes"){
		$matchStr= "Select boxes.quantity, boxes.salePrice, boxes.shipfinal , quote_to_item.quote_id, companyInfo.ID as companyID, companyInfo.status, companyInfo.last_date, companyInfo.nickname, companyInfo.last_contact_date, companyInfo.company, companyInfo.shipCity, companyInfo.shipState, companyInfo.email, companyInfo.industry_id, employees.name, employees.initials from boxes INNER JOIN companyInfo ON boxes.companyID = companyInfo.id INNER JOIN quote_to_item ON boxes.ID = quote_to_item.item_id INNER JOIN employees ON companyInfo.assignedto = employees.employeeID WHERE companyInfo.status <> 31 and inventoryID = " . $_REQUEST["inventoryID"] . " AND employeeID = '" . $logged_emp_id."' group by boxes.companyID";    
	}
	else{
		if($_REQUEST["employeeID"]>0)
		{
			$matchStr= "Select boxes.quantity, boxes.salePrice, boxes.shipfinal, quote_to_item.quote_id, companyInfo.ID as companyID, companyInfo.status, companyInfo.last_date, companyInfo.last_contact_date, companyInfo.nickname, companyInfo.company, companyInfo.shipCity, companyInfo.shipState, companyInfo.email, companyInfo.industry_id, employees.name, employees.initials from boxes INNER JOIN companyInfo ON boxes.companyID = companyInfo.id INNER JOIN quote_to_item ON boxes.ID = quote_to_item.item_id INNER JOIN employees ON companyInfo.assignedto = employees.employeeID WHERE companyInfo.status <> 31 and inventoryID = " . $_REQUEST["inventoryID"] . " and employeeID = '" . $_REQUEST["employeeID"]."' group by boxes.companyID";
			
		}
		else{
			$matchStr= "Select boxes.quantity, boxes.salePrice, boxes.shipfinal, quote_to_item.quote_id, companyInfo.ID as companyID, companyInfo.status, companyInfo.last_date, companyInfo.last_contact_date, companyInfo.nickname, companyInfo.company, companyInfo.shipCity, companyInfo.shipState, companyInfo.email, companyInfo.industry_id, employees.name, employees.initials from boxes INNER JOIN companyInfo ON boxes.companyID = companyInfo.id INNER JOIN quote_to_item ON boxes.ID = quote_to_item.item_id INNER JOIN employees ON companyInfo.assignedto = employees.employeeID WHERE companyInfo.status <> 31 and inventoryID = " . $_REQUEST["inventoryID"] . " group by boxes.companyID";
		}
  	}    

	$matchStr = $matchStr . " ORDER BY name, company";
	db_b2b();
	$res1 = db_query($matchStr);
	$sort_order_pre = "DESC";

	if($_GET['sort_order_pre'] == "ASC")
	{
		$sort_order_pre = "DESC";
	}else{
		$sort_order_pre = "ASC";
	}
	
	$x = 1;

	while ($objGBmatch = array_shift($res1)){
		
		$lastcrmdt = ""; $lastcrmemp = "";
		$quoted_delivered_price = 0;
		$delivery_included = "no";
          
		$cmp_id= $objGBmatch["companyID"];
		$link = ""; $quote_date= "";
		db_b2b();
		$query5 = "SELECT ID, filename, quoteDate, qstatus, quote_total, poNumber, quoteType FROM quote WHERE ID=" . $objGBmatch["quote_id"] . " ORDER BY ID DESC LIMIT 1";
		$dt_view_res3 = db_query($query5);
		while ($quote_rows1= array_shift($dt_view_res3)) 
		{
			if ($quote_rows1["filename"] != "") {
				$qtid=$quote_rows1["ID"];
				$qtf=$quote_rows1["filename"];
				$archeive_date = new DateTime(date("Y-m-d", strtotime($quotes_archive_date)));
				$quote_date = new DateTime(date("Y-m-d", strtotime($quote_rows1["quoteDate"])));
				
				if ($quote_date < $archeive_date){
					$link = "<a target='_blank' id='quotespdf".$qtid."' href='https://usedcardboardboxes.sharepoint.com/:b:/r/sites/LoopsCRMEmailAttachments/Shared%20Documents/quotes/before_oct_18_2022/" . $qtf . "'>";
				}else{
					$link="<a href='#' id='quotespdf".$qtid."' onclick=\"show_file_inviewer_pos('quotes/".$qtf."', 'Quote', 'quotespdf".$qtid."'); return false;\">";
				}

			 } else {
				if ($quote_rows1["quoteType"]=="Quote") {
					$link="<a target='_blank' href='fullquote_mrg.php?ID=".$quote_rows1["ID"]."'>";
					} elseif ($quote_rows1["quoteType"]=="Quote Select") {
					$link="<a href='http://b2b.usedcardboardboxes.com/b2b5/quoteselect.asp?ID=".$quote_rows1["ID"]."' target='_blank'>";
				}
			}

			$quote_date=date('Y-m-d', strtotime($quote_rows1["quoteDate"])); 
			$qid=$quote_rows1["ID"];
			
			if($objQuote["quoteType"]=="PO"){
				$quoteid_rem = $quote_rows1["poNumber"];
			}
			else{
				$quoteid_rem = ($quote_rows1["ID"] + 3770);
			}
			db_b2b();
			$boxSql = "Select * from quote_status Where qid='".$quote_rows1["qstatus"]."'";
			$dt_view_res4 = db_query($boxSql);
			$quote_status = "Open";
			while ($objQStatus= array_shift($dt_view_res4)) {
				$quote_status = $objQStatus["status_name"];
			}	

			$boxSql = "Select boxes.quantity, boxes.salePrice, boxes.shipfinal from boxes INNER JOIN companyInfo ON boxes.companyID = companyInfo.id INNER JOIN quote_to_item ON boxes.ID = quote_to_item.item_id INNER JOIN employees ON companyInfo.assignedto = employees.employeeID WHERE companyInfo.status <> 31 and boxes.item = 'Delivery' and quote_to_item.quote_id = '" . $objGBmatch["quote_id"] . "' group by boxes.companyID";
			db_b2b();
			$dt_view_res4 = db_query($boxSql);
			while ($objQStatus= array_shift($dt_view_res4)) {
				$quoted_delivered_price = ($objQStatus["salePrice"]/$objGBmatch["quantity"])+$objGBmatch["salePrice"];
				$delivery_included = "yes";
				
			}	
			
		}	

		$g_no_of_quote_sent1 = "";
		if ($link != "") {
			$new_quote_id=$quoteid_rem;
			$g_no_of_quote_sent1=$link.$new_quote_id."</a>";
		}

		if ($objGBmatch["shipfinal"] != "" ) {
			$quote_price = (($objGBmatch["salePrice"] * $objGBmatch["quantity"]) - $objGBmatch["shipfinal"])/$objGBmatch["quantity"];
		}else{
			$quote_price = $objGBmatch["salePrice"];
		}
		
		if ($objGBmatch["last_contact_date"] != ""){
			$lastcrmdt = date("Y/m/d", strtotime($objGBmatch["last_contact_date"]));
		}
		if ($delivery_included == "no"){
			$quoted_delivered_price = $objGBmatch["salePrice"];
		}	

		$acc_status = "";

		db_b2b();

		$result_2 = db_query("select name from status where id = " . $objGBmatch["status"]);

		while ($myrowsel_2 = array_shift($result_2)) {

			$acc_status = $myrowsel_2["name"];

		}
        

		$nickname = "";

		if ($objGBmatch["nickname"] != "") {

			$nickname = $objGBmatch["nickname"];

		}else {

			$tmppos_1 = strpos($objGBmatch["company"], "-");

			if ($tmppos_1 != false)

			{

				$nickname = $objGBmatch["company"];

			}else {

				if ($objGBmatch["shipCity"] <> "" || $objGBmatch["shipState"] <> "" ) 

				{

					$nickname = $objGBmatch["company"] . " - " . $objGBmatch["shipCity"] . ", " . $objGBmatch["shipState"] ;

				}else { $nickname = $objGBmatch["company"]; }

			}

		}

		

		if ($lastcrmdt != ""){

			$lastcrmdt_sort = date("Y-m-d" , strtotime($lastcrmdt));

			$lastcrmdt = date("m/d/Y" , strtotime($lastcrmdt));

		}else{

			$lastcrmdt_sort = "";

			$lastcrmdt = "";

		}	
        
        if($objGBmatch["industry_id"] != ''){

			db_b2b();

            $view_parentrec = db_query("SELECT industry FROM industry_master WHERE active_flg = 1 AND industry_id = ".$objGBmatch["industry_id"]."");
            while ($rec_parentrec = array_shift($view_parentrec)) {
                $industry = $rec_parentrec["industry"];
            }
        }
	?>	

	<tr>
			<td> <?php echo $x;?> </td>
		
			<td>
			<?php 		if (date('m/d/Y',strtotime($quote_date)) == "12/31/1969")			echo "Quote Removed";		else		echo date('m/d/Y', strtotime($quote_date));?>
			</td>
			
			<td>
				<?php
				echo $g_no_of_quote_sent1;
				?>
			</td>		

			<td> <a href="viewCompany.php?ID=<?php echo $objGBmatch["companyID"]?>" target="_blank"><?php echo $nickname;?> </a></td>

			<td> <?php echo $objGBmatch["initials"];?> </td>

			<td> <?php echo $lastcrmdt;?> </td>

			<td> <?php echo $industry;?> </td>

			<td> <?php echo $acc_status;?> </td>
			<td align="right"> $<?php echo number_format($quote_price,2);?>
			</td>
			<td align="right"> $<?php echo number_format($quoted_delivered_price,2);?>
			<td >
				<?php echo $quote_status;?>
			</td>
			</td>
			
		
	</tr>		
<?php
$x++;

		$MGArray[] = array('companyID' => $objGBmatch["companyID"], 'company_name' => trim($nickname), 'selltoemail' => trim($objGBmatch["email"]), 'acc_status' => $acc_status, 'account_owner' => trim($objGBmatch["name"]), 'lastcrmemp' => trim($lastcrmemp),'lastcrmdt' => $lastcrmdt, 'industry' => $industry, 'quote_date' => $quote_date,
		'quoted_delivered_price'=> $quoted_delivered_price, 'g_no_of_quote_sent1' => $g_no_of_quote_sent1, 'lastcrmdt_sort' => $lastcrmdt_sort, 'new_quote_id' => $new_quote_id, 'quote_price' => $quote_price, 'quote_status' => $quote_status);

	}

	$_SESSION['sortarrayn'] = $MGArray;
}
	?>
</table>

	<div ><i><font color="red">"END OF REPORT"</font></i></div>

<?php
}

if (isset($_REQUEST["sort"])) {
	$sort_order_pre = "DESC";
	if($_POST['sort_order_pre'] == "ASC")
	{
		$sort_order_pre = "DESC";
	}else{
		$sort_order_pre = "ASC";
	}

	$x = 1;
	$MGArray = $_SESSION['sortarrayn'];
	if($_REQUEST['sort'] == "companyID" && $_REQUEST['sort_order_pre'] == "ASC")
	{
		$MGArraysort_I = array();

		foreach ($MGArray as $MGArraytmp) {
			$MGArraysort_I[] = $MGArraytmp['companyID'];
		}

		array_multisort($MGArraysort_I,SORT_ASC,SORT_NUMERIC,$MGArray); 

	}



	if($_REQUEST['sort'] == "companyID" && $_REQUEST['sort_order_pre'] == "DESC")

	{

		$MGArraysort_I = array();

		 

		foreach ($MGArray as $MGArraytmp) {

		$MGArraysort_I[] = $MGArraytmp['companyID'];

		}

		array_multisort($MGArraysort_I,SORT_DESC,SORT_NUMERIC,$MGArray); 

	}

	if($_REQUEST['sort'] == "company_name" && $_REQUEST['sort_order_pre'] == "ASC")

	{

		$MGArraysort_B = array();

		 

		foreach ($MGArray as $MGArraytmp) {

		$MGArraysort_B[] = $MGArraytmp['company_name'];

		}

		array_multisort($MGArraysort_B, SORT_ASC,SORT_STRING, $MGArray); 

	} 

	if($_REQUEST['sort'] == "company_name" && $_REQUEST['sort_order_pre'] == "DESC")

	{

		$MGArraysort_B = array();

		 

		foreach ($MGArray as $MGArraytmp) {

		$MGArraysort_B[] = $MGArraytmp['company_name'];

		}

		array_multisort($MGArraysort_B, SORT_DESC,SORT_STRING, $MGArray); 

	} 				

	if($_REQUEST['sort'] == "account_owner" && $_REQUEST['sort_order_pre'] == "ASC")

	{

		$MGArraysort_B = array();

		 

		foreach ($MGArray as $MGArraytmp) {

		$MGArraysort_B[] = $MGArraytmp['account_owner'];

		}

		array_multisort($MGArraysort_B, SORT_ASC,SORT_STRING, $MGArray); 

	} 

	if($_REQUEST['sort'] == "account_owner" && $_REQUEST['sort_order_pre'] == "DESC")

	{

		$MGArraysort_B = array();

		 

		foreach ($MGArray as $MGArraytmp) {

		$MGArraysort_B[] = $MGArraytmp['account_owner'];

		}

		array_multisort($MGArraysort_B, SORT_DESC,SORT_STRING, $MGArray); 

	} 				

	if($_REQUEST['sort'] == "selltoemail" && $_REQUEST['sort_order_pre'] == "ASC")

	{

		$MGArraysort_B = array();

		 

		foreach ($MGArray as $MGArraytmp) {

		$MGArraysort_B[] = $MGArraytmp['selltoemail'];

		}

		array_multisort($MGArraysort_B, SORT_ASC,SORT_STRING, $MGArray); 

	} 

	if($_REQUEST['sort'] == "selltoemail" && $_REQUEST['sort_order_pre'] == "DESC")

	{

		$MGArraysort_B = array();

		 

		foreach ($MGArray as $MGArraytmp) {

		$MGArraysort_B[] = $MGArraytmp['selltoemail'];

		}

		array_multisort($MGArraysort_B, SORT_DESC,SORT_STRING, $MGArray); 

	} 				

	if($_REQUEST['sort'] == "lastcrmdt" && $_REQUEST['sort_order_pre'] == "ASC")
	{
		$MGArraysort_B = array();
		 
		foreach ($MGArray as $MGArraytmp) {
			$MGArraysort_B[] = $MGArraytmp['lastcrmdt_sort'];
		}

		array_multisort($MGArraysort_B, SORT_ASC,SORT_STRING, $MGArray); 
	} 

	if($_REQUEST['sort'] == "lastcrmdt" && $_REQUEST['sort_order_pre'] == "DESC")
	{
		$MGArraysort_B = array();

		foreach ($MGArray as $MGArraytmp) {

		$MGArraysort_B[] = $MGArraytmp['lastcrmdt_sort'];

		}

		array_multisort($MGArraysort_B, SORT_DESC,SORT_STRING, $MGArray); 

	} 				


	if($_REQUEST['sort'] == "industry_sort")	{
		$MGArraysort_I = array();		
		foreach ($MGArray as $MGArraytmp) {
			$MGArraysort_I[] = $MGArraytmp['industry'];		
		}		
		
		if ($_REQUEST['sort_order_pre'] == "ASC") {
			array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 		
		}		
		
		if ($_REQUEST['sort_order_pre'] == "DESC") {
			array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 		
		}	
	}
    
	if($_REQUEST['sort'] == "acc_status")	{
		$MGArraysort_I = array();		
		foreach ($MGArray as $MGArraytmp) {
			$MGArraysort_I[] = $MGArraytmp['acc_status'];		
		}		
		
		if ($_REQUEST['sort_order_pre'] == "ASC") {
			array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 		
		}		
		
		if ($_REQUEST['sort_order_pre'] == "DESC") {
			array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 		
		}	
	}	
	
	if($_REQUEST['sort'] == "quote_date")	{
		$MGArraysort_I = array();		
		foreach ($MGArray as $MGArraytmp) {
			$MGArraysort_I[] = $MGArraytmp['quote_date'];		
		}		
		
		if ($_REQUEST['sort_order_pre'] == "ASC") {
			array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 		
		}		
		
		if ($_REQUEST['sort_order_pre'] == "DESC") {
			array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 		
		}	
	}
	if($_REQUEST['sort'] == "qut_status")	{
		$MGArraysort_I = array();		
		foreach ($MGArray as $MGArraytmp) {
			$MGArraysort_I[] = $MGArraytmp['quote_status'];		
		}		
		
		if ($_REQUEST['sort_order_pre'] == "ASC") {
			array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 		
		}		
		
		if ($_REQUEST['sort_order_pre'] == "DESC") {
			array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 		
		}	
	}
	if($_REQUEST['sort'] == "qut_price")	{
		$MGArraysort_I = array();		
		foreach ($MGArray as $MGArraytmp) {
			$MGArraysort_I[] = $MGArraytmp['quote_price'];		
		}		
		
		if ($_REQUEST['sort_order_pre'] == "ASC") {
			array_multisort($MGArraysort_I,SORT_ASC,SORT_NUMERIC,$MGArray); 		
		}		
		
		if ($_REQUEST['sort_order_pre'] == "DESC") {
			array_multisort($MGArraysort_I,SORT_DESC,SORT_NUMERIC,$MGArray); 		
		}	
	}
	if($_REQUEST['sort'] == "qut_del_price")	{
		$MGArraysort_I = array();		
		foreach ($MGArray as $MGArraytmp) {
			$MGArraysort_I[] = $MGArraytmp['quoted_delivered_price'];		
		}		
		
		if ($_REQUEST['sort_order_pre'] == "ASC") {
			array_multisort($MGArraysort_I,SORT_ASC,SORT_NUMERIC,$MGArray); 		
		}		
		
		if ($_REQUEST['sort_order_pre'] == "DESC") {
			array_multisort($MGArraysort_I,SORT_DESC,SORT_NUMERIC,$MGArray); 		
		}	
	}
	
	if($_REQUEST['sort'] == "quote_id")	{
		$MGArraysort_I = array();		
		foreach ($MGArray as $MGArraytmp) {
			$MGArraysort_I[] = $MGArraytmp['new_quote_id'];		
		}		
		
		if ($_REQUEST['sort_order_pre'] == "ASC") {
			array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 		
		}		
		
		if ($_REQUEST['sort_order_pre'] == "DESC") {
			array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 		
		}	
	}
?>

	<br>

	<!--<table width="1100px" border="0" cellspacing="2" cellpadding="2" bgcolor="#E4E4E4">

	<tr align="center">

		<td bgcolor="#C0CDDA" style="font-weight:bold;background:#C0CDDA; padding:5px 15px; border:0 none;cursor:pointer;">Sr. No.</td>

		<th bgColor="#C0CDDA" style="height: 16px" align="middle"><input type="button" value="Company" onclick="loadfrm(1)" style="font-weight:bold;background:#C0CDDA; padding:5px 15px; text-decoration:underline; border:0 none;cursor:pointer;"/></th>	

		<th bgColor="#C0CDDA" style="height: 16px" align="middle"><input type="button" value="Account Owner" onclick="loadfrm(2)" style="font-weight:bold;background:#C0CDDA; padding:5px 15px; text-decoration:underline; border:0 none;cursor:pointer;"/></th>	

		<th bgColor="#C0CDDA" style="height: 16px" align="middle"><input type="button" value="Sell To Email" onclick="loadfrm(3)" style="font-weight:bold;background:#C0CDDA; padding:5px 15px; text-decoration:underline; border:0 none;cursor:pointer;"/></th>	

		<th bgColor="#C0CDDA" style="height: 16px" align="middle"><input type="button" value="Last Contact Date" onclick="loadfrm(4)" style="font-weight:bold;background:#C0CDDA; padding:5px 15px; text-decoration:underline; border:0 none;cursor:pointer;"/></th>	

		<th bgColor="#C0CDDA" style="height: 16px" align="middle"><input type="button" value="Account Status" onclick="loadfrm(5)" style="font-weight:bold;background:#C0CDDA; padding:5px 15px; text-decoration:underline; border:0 none;cursor:pointer;"/></th>	
        <th bgColor="#C0CDDA" style="height: 16px" align="middle"><input type="button" value="Quote" onclick="loadfrm(5)" style="font-weight:bold;background:#C0CDDA; padding:5px 15px; text-decoration:underline; border:0 none;cursor:pointer;"/></th>	
        <th bgColor="#C0CDDA" style="height: 16px" align="middle"><input type="button" value="Quote Date" onclick="loadfrm(6)" style="font-weight:bold;background:#C0CDDA; padding:5px 15px; text-decoration:underline; border:0 none;cursor:pointer;"/></th>	

	</tr>

-->

<?php	

foreach ($MGArray as $MGArraytmp2) {?>			

	<tr>

		<td><?php echo $x;?></td>
		 <td>
       		 <?php	
				if (date('m/d/Y',strtotime($MGArraytmp2["quote_date"])) == "12/31/1969")			echo "Quote Removed";		else	echo date('m/d/Y',strtotime($MGArraytmp2["quote_date"]));		?>
    	</td>
		
		<td>
			<?php echo $MGArraytmp2["g_no_of_quote_sent1"];    ?>
		</td>

		<td> <a href="viewCompany.php?ID=<?php echo $MGArraytmp2["companyID"]?>" target="_blank"><?php echo $MGArraytmp2["company_name"];?> </a></td>

		<td><?php echo $MGArraytmp2["account_owner"];?> </td>

		<td> <?php echo $MGArraytmp2["lastcrmdt"];?></td>

		<td> <?php echo $MGArraytmp2["industry"];?></td>

		<td> <?php echo $MGArraytmp2["acc_status"];?></td>

		<td align="right">$<?php echo number_format($MGArraytmp2["quote_price"],2);?></td>
		<td align="right">$<?php echo number_format($MGArraytmp2["quoted_delivered_price"],2);?></td>
		
		<td><?php echo $MGArraytmp2["quote_status"];?></td>
	</tr>		



	<?php

	$x++;

	}?>

	

</table>

	<div ><i><font color="red">"END OF REPORT"</font></i></div>

<?php } ?>

	<input type="hidden" name="inventoryID" id="inventoryID" value="<?php echo $_REQUEST["inventoryID"]; ?>"/>

	<input type="hidden" name="employeeID" id="employeeID" value="<?php echo $_REQUEST["employeeID"]; ?>"/>

	<input type="hidden" name="sort" id="sort" value="lastcrmdt"/>

	<input type="hidden" name="sort_order_pre" id="sort_order_pre" value="<?php echo $sort_order_pre; ?>"/>



</form>

</div>

</body>

</html>