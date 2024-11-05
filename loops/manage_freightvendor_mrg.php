<?php 
require ("../mainfunctions/database.php");
require_once("../mainfunctions/general-functions.php");

session_start();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Freight Vendors Database | UsedCardboardBoxes</title>
	
	<LINK rel='stylesheet' type='text/css' href='one_style.css' >
	<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
	<link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
	<LINK rel='stylesheet' type='text/css' href='css/common_inline_style.css'>
</script>
	
</head>

<body>
<?php include("inc/header.php"); ?>

	<div class="main_data_css">
		<div class="dashboard_heading" style="float: left;">
			<div style="float: left;">Freight Vendors Database
			<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
			<span class="tooltiptext">
				This database is where all of the data for B2B freight vendors (brokers and carriers) are saved. Freight vendors are the companies that pickup and deliver our goods, and since UCB does not own our own assets (trucks/trailers/drivers), we have to utilize other companies.
			</span></div>

			<div style="height: 13px;">&nbsp;</div>				
			</div>
		</div>
<?php

/*------------------------------------------------
THE FOLLOWING ALLOWS GLOBALS = OFF SUPPORT
------------------------------------------------*/
	// $_GET VARIABLES
	foreach($_GET as $a=>$b){$$a=$b;} 

	// $_POST VARIABLES
	foreach($_POST as $a=>$b){$$a=$b;} 
/*------------------------------------------------
END GLOBALS OFF SUPPORT
------------------------------------------------*/

echo "<span class='font_family_Ariel font_size_2'>";
$sql_debug_mode=0;
error_reporting(E_WARNING|E_PARSE);
$SCRIPT_NAME = "";
if (isset($_SERVER['SCRIPT_NAME'])) {
	$SCRIPT_NAME = $_SERVER['SCRIPT_NAME'];
}else{
	$SCRIPT_NAME = $_SERVER['PHP_SELF'];   
}
//SET THESE VARIABLES TO CUSTOMIZE YOUR PAGE
$thispage	= $SCRIPT_NAME; //SET THIS TO THE NAME OF THIS FILE
$pagevars	= ""; //INSERT ANY "GET" VARIABLES HERE...
$allowedit		= "yes"; //SET TO "no" IF YOU WANT TO DISABLE EDITING
$allowaddnew	= "yes"; // SET TO "no" IF YOU WANT TO DISABLE NEW RECORDS
$allowview		= "no"; //SET TO "no" IF YOU WANT TO DISABLE VIEWING RECORDS
$allowdelete	= "yes"; //SET TO "no" IF YOU WANT TO DISABLE DELETING RECORDS

$addl_select_crit = "ORDER BY company_name"; //ADDL CRITERIA FOR SQL STATEMENTS (ADD/UPD/DEL).
$addl_update_crit = ""; //ADDITIONAL CRITERIA FOR UPDATE STATEMENTS.
$addl_insert_crit = ""; //ADDITIONAL CRITERIA FOR INSERT STATEMENTS.
$addl_insert_values = ""; //ADDITIONAL VALUES FOR INSERT STATEMENTS.

/*----------------------------------------------------------------------
FUNCTIONS
----------------------------------------------------------------------*/
$proc = $_REQUEST["proc"];
//$posting = $_REQUEST["post"];
$post = $_REQUEST["post"];
if ($proc == "") {
 if ($allowaddnew == "yes") { 
 ?>
<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
 <?php } 


/*---------------------------------------------------------------------------------------
BEGIN SEARCH SECTION 9991
---------------------------------------------------------------------------------------*/
/*-- SECTION: 9991FORM --*/
?>
<?php
if ($posting == "yes") {
	$pagenorecords = 500;  //THIS IS THE PAGE SIZE
	//IF NO PAGE
	if ($page == 0) {
	$myrecstart = 0;
	} else {
	$myrecstart = ($page * $pagenorecords);
	}


	/*-- SECTION: 9991SQL --*/
	if ($searchcrit == "") {
		$flag = "all";
		$sql = "SELECT * FROM loop_freightvendor";
		$sqlcount = "select count(*) as reccount from loop_freightvendor";
	} else {
		//IF THEY TYPED SEARCH WORDS
		$sqlcount = "select count(*) as reccount from loop_freightvendor WHERE (";
		$sql = "SELECT * FROM loop_freightvendor WHERE (";
		$sqlwhere = "

		 company_name like '%$searchcrit%' OR 
		 company_address1 like '%$searchcrit%' OR 
		 company_address2 like '%$searchcrit%' OR 
		 company_city like '%$searchcrit%' OR 
		 company_state like '%$searchcrit%' OR 
		 company_zip like '%$searchcrit%' OR 
		 company_broker like '%$searchcrit%' OR 
		 company_contact like '%$searchcrit%' OR 
		 company_phone like '%$searchcrit%' OR 
		 company_email like '%$searchcrit%' OR 
		 notes like '%$searchcrit%' OR 
		 other1 like '%$searchcrit%' OR 
		 other2 like '%$searchcrit%' OR 
		 other3 like '%$searchcrit%'"; 
		 //FINISH SQL STRING
	} //END IF SEARCHCRIT = "";

	$sql = $sql . " WHERE (1=1) $addl_select_crit ";
	$sqlcount = $sqlcount  . " WHERE (1=1) $addl_select_crit ";

	if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }

/*----------------------------------------------------------------
END PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
	//EXECUTE OUR SQL STRING FOR THE TABLE RECORDS
	db();
	$result = db_query($sql);
	if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
	
	if ($myrowsel = array_shift($result)) {
		$id = $myrowsel["id"];
		
		echo "<br><TABLE style='width:1000px'>";
		echo "	<tr class='text_align_center'><td colspan='10' class='style24' style='height: 16px'><strong>FREIGHT VENDOR SETUP</strong></td></tr>";
		
		$sorturl = "manage_freightvendor_mrg.php?posting=yes";
		$imgasc  = '<img src="images/sort_asc.png" width="6px;" height="12px;">';
		$imgdesc = '<img src="images/sort_desc.png" width="6px;" height="12px;">';
?>
		<tr>
			<th class="style12 bg_color_c0cdda" style="width:35px" ><strong>#</strong>
			</th>
			<th class="style12 bg_color_c0cdda" style="width:86px" ><strong>ID</strong> &nbsp;
				<a href="<?php echo $sorturl;?>&sort_order_pre=ASC&sort=ID"><?php echo $imgasc;?></a>&nbsp;
				<a href="<?php echo $sorturl;?>&sort_order_pre=DESC&sort=ID"><?php echo $imgdesc;?></a> 
			</th>
			<th class="style12 bg_color_c0cdda" style="width:58px" ><strong>Freight Company</strong> &nbsp;
				<a href="<?php echo $sorturl;?>&sort_order_pre=ASC&sort=company"><?php echo $imgasc;?></a>&nbsp;
				<a href="<?php echo $sorturl;?>&sort_order_pre=DESC&sort=company"><?php echo $imgdesc;?></a> 
			</th>
			<th class="style12 bg_color_c0cdda" style="width:110px" ><strong>Vendor Type</strong> &nbsp;
				<a href="<?php echo $sorturl;?>&sort_order_pre=ASC&sort=vendortype"><?php echo $imgasc;?></a>&nbsp;
				<a href="<?php echo $sorturl;?>&sort_order_pre=DESC&sort=vendortype"><?php echo $imgdesc;?></a> 
			</th>
			<th class="style12 bg_color_c0cdda" style="width:75px" ><strong>Drop Trailer Capabilities</strong> &nbsp;
				<a href="<?php echo $sorturl;?>&sort_order_pre=ASC&sort=droptrailercapabilities"><?php echo $imgasc;?></a>&nbsp;
				<a href="<?php echo $sorturl;?>&sort_order_pre=DESC&sort=droptrailercapabilities"><?php echo $imgdesc;?></a> 
			</th>
			<th class="style12 bg_color_c0cdda" style="width:75px" ><strong>State/Provinces Serviced</strong> &nbsp;
				<a href="<?php echo $sorturl;?>&sort_order_pre=ASC&sort=state_prov"><?php echo $imgasc;?></a>&nbsp;
				<a href="<?php echo $sorturl;?>&sort_order_pre=DESC&sort=state_prov"><?php echo $imgdesc;?></a> 
			</th>
			<th class="style12 bg_color_c0cdda" style="width:75px" ><strong>State/Provinces Serviced in Canada</strong> &nbsp;
				<a href="<?php echo $sorturl;?>&sort_order_pre=ASC&sort=state_prov_cn"><?php echo $imgasc;?></a>&nbsp;
				<a href="<?php echo $sorturl;?>&sort_order_pre=DESC&sort=state_prov_cn"><?php echo $imgdesc;?></a> 
			</th>
			<th class="style12 bg_color_c0cdda" style="width:75px" ><strong>State/Provinces Serviced in Mexico</strong> &nbsp;
				<a href="<?php echo $sorturl;?>&sort_order_pre=ASC&sort=state_prov_mex"><?php echo $imgasc;?></a>&nbsp;
				<a href="<?php echo $sorturl;?>&sort_order_pre=DESC&sort=state_prov_mex"><?php echo $imgdesc;?></a> 
			</th>
			<th class="style12 bg_color_c0cdda" style="width:75px;" ><strong>City, ST</strong>&nbsp;
				<a href="<?php echo $sorturl;?>&sort_order_pre=ASC&sort=citystate"><?php echo $imgasc;?></a>&nbsp;
				<a href="<?php echo $sorturl;?>&sort_order_pre=DESC&sort=citystate"><?php echo $imgdesc;?></a> 
			</th>
		</tr>
<?php
		
		$sr_no = 0;

	if (!isset($_REQUEST["sort"])){ 
		$MGArray_data = array();
		do
		{
  			//FORMAT THE OUTPUT OF THE SEARCH
  			$id = $myrowsel["id"];
  			
  			//SWITCH ROW COLORS
  			switch ($shade)
  			{
  				case "TBL_ROW_DATA_LIGHT":
					$shade = "TBL_ROW_DATA_DRK";
					break;
				case "TBL_ROW_DATA_DRK":
					$shade = "TBL_ROW_DATA_LIGHT";
					break;
				default:
					$shade = "TBL_ROW_DATA_DRK";
					break;
  			}//end switch shade
	
			?>
			<tr>
				<?php $sr_no = $sr_no + 1; ?>
				<TD CLASS='<?php echo $shade; ?> text_align_right'>
					<?php echo $sr_no; ?>
				</TD>
				<TD CLASS='<?php echo $shade; ?> text_align_center'>
					<a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=Edit&<?php echo $pagevars; ?>"><?php echo $myrowsel["id"]; ?></a>				
				</TD>
				<?php $company_name = $myrowsel["company_name"]; ?>
				<TD CLASS='<?php echo $shade; ?>'>
					<?php if (trim($company_name) != "") { ?> 
						<a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=View&<?php echo $pagevars; ?>"><?php echo $company_name;?> </a>				
					<?php } else { ?> 
						<a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=View&<?php echo $pagevars; ?>">View</a>				
					<?php } ?> 
				</TD>
					<?php $company_broker = $myrowsel["company_broker"]; ?>
				<TD CLASS='<?php echo $shade; ?>'>
					<?php echo $company_broker; ?> 
				</TD>
					<?php $drop_trailer_capabilities = $myrowsel["drop_trailer_capabilities"]; ?>
				<TD CLASS='<?php echo $shade; ?>'>
					<?php echo $drop_trailer_capabilities; ?> 
				</TD>
					<?php $states_serviced = $myrowsel["states_serviced"]; ?>
				<TD CLASS='<?php echo $shade; ?>'>
					<?php echo $states_serviced; ?> 
				</TD>
					<?php $states_serviced_in_canada = $myrowsel["states_serviced_in_canada"]; ?>
				<TD CLASS='<?php echo $shade; ?>'>
					<?php echo $states_serviced_in_canada; ?> 
				</TD>
					<?php $states_serviced_in_mexico = $myrowsel["states_serviced_in_mexico"]; ?>
				<TD CLASS='<?php echo $shade; ?>'>
					<?php echo $states_serviced_in_mexico; ?> 
				</TD>

				<?php $company_city = $myrowsel["company_city"]; ?>
				<?php 
					$citystate = $company_city;
					$citystate_sort = trim($myrowsel["company_state"]) . " " . trim($myrowsel["company_state"]);
					if (trim($myrowsel["company_state"]) != "" ) {
						$citystate = $company_city . ", ". $myrowsel["company_state"]; 
					}	
				?>
				<TD CLASS='<?php echo $shade; ?>'>
					<?php echo $citystate; ?> 
				</TD>
			</tr>
<?php
			$MGArray_data[] = array ('id' => $myrowsel["id"], 'company_name' => $myrowsel["company_name"], 
					  'company_broker' => $myrowsel["company_broker"], 'drop_trailer_capabilities' => $myrowsel["drop_trailer_capabilities"], 
					  'states_serviced' => $myrowsel["states_serviced"], 'states_serviced_in_canada' => $myrowsel["states_serviced_in_canada"], 
					  'states_serviced_in_mexico' => $myrowsel["states_serviced_in_mexico"], 'citystate' => $citystate, 'citystate_sort' => $citystate_sort);

		} while ($myrowsel = array_shift($result));
		
		$_SESSION['sortarrayn'] = $MGArray_data;
	
	} else { 
				
			$MGArray = $_SESSION['sortarrayn'];
			if($_REQUEST['sort'] == "ID")
			{
				$MGArraysort_I = array();

				foreach ($MGArray as $MGArraytmp) {
				$MGArraysort_I[] = $MGArraytmp['id'];
				}

				if ($_REQUEST['sort_order_pre'] == "ASC"){
					array_multisort($MGArraysort_I,SORT_ASC,SORT_NUMERIC,$MGArray); 
				}
				if ($_REQUEST['sort_order_pre'] == "DESC"){
					array_multisort($MGArraysort_I,SORT_DESC,SORT_NUMERIC,$MGArray); 
				}
			}
			
			if($_REQUEST['sort'] == "company")
			{
				$MGArraysort_I = array();

				foreach ($MGArray as $MGArraytmp) {
				$MGArraysort_I[] = $MGArraytmp['company_name'];
				}

				if ($_REQUEST['sort_order_pre'] == "ASC"){
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_REQUEST['sort_order_pre'] == "DESC"){
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}
			
			if($_REQUEST['sort'] == "vendortype")
			{
				$MGArraysort_I = array();

				foreach ($MGArray as $MGArraytmp) {
				$MGArraysort_I[] = $MGArraytmp['company_broker'];
				}

				if ($_REQUEST['sort_order_pre'] == "ASC"){
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_REQUEST['sort_order_pre'] == "DESC"){
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}
			if($_REQUEST['sort'] == "droptrailercapabilities")
			{
				$MGArraysort_I = array();

				foreach ($MGArray as $MGArraytmp) {
				$MGArraysort_I[] = $MGArraytmp['drop_trailer_capabilities'];
				}

				if ($_REQUEST['sort_order_pre'] == "ASC"){
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_REQUEST['sort_order_pre'] == "DESC"){
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}
			if($_REQUEST['sort'] == "state_prov")
			{
				$MGArraysort_I = array();

				foreach ($MGArray as $MGArraytmp) {
				$MGArraysort_I[] = $MGArraytmp['states_serviced'];
				}

				if ($_REQUEST['sort_order_pre'] == "ASC"){
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_REQUEST['sort_order_pre'] == "DESC"){
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}
			if($_REQUEST['sort'] == "state_prov_cn")
			{
				$MGArraysort_I = array();

				foreach ($MGArray as $MGArraytmp) {
				$MGArraysort_I[] = $MGArraytmp['states_serviced_in_canada'];
				}

				if ($_REQUEST['sort_order_pre'] == "ASC"){
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_REQUEST['sort_order_pre'] == "DESC"){
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}
			if($_REQUEST['sort'] == "state_prov_mex")
			{
				$MGArraysort_I = array();

				foreach ($MGArray as $MGArraytmp) {
				$MGArraysort_I[] = $MGArraytmp['states_serviced_in_mexico'];
				}

				if ($_REQUEST['sort_order_pre'] == "ASC"){
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_REQUEST['sort_order_pre'] == "DESC"){
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}
			
			if($_REQUEST['sort'] == "citystate")
			{
				$MGArraysort_I = array();

				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['citystate_sort'];
				}

				if ($_REQUEST['sort_order_pre'] == "ASC"){
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_REQUEST['sort_order_pre'] == "DESC"){
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
					
			}
			
			foreach ($MGArray as $MGArraytmp2) {
				$id = $MGArraytmp2["id"];
				switch ($shade)
				{
					case "TBL_ROW_DATA_LIGHT":
						$shade = "TBL_ROW_DATA_DRK";
						break;
					case "TBL_ROW_DATA_DRK":
						$shade = "TBL_ROW_DATA_LIGHT";
						break;
					default:
						$shade = "TBL_ROW_DATA_DRK";
						break;
				}
				?>
				<TR>
					<?php $sr_no = $sr_no + 1; ?>
					<TD CLASS='<?php echo $shade; ?> text_align_right'>
						<?php echo $sr_no; ?>
					</TD>
					<TD CLASS='<?php echo $shade; ?>'>
						<a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=Edit&<?php echo $pagevars; ?>"><?php echo $MGArraytmp2["id"]; ?></a>				
					</TD>
					<?php $company_name = $MGArraytmp2["company_name"]; ?>
					<TD CLASS='<?php echo $shade; ?>'>
						<?php if (trim($company_name) != "") { ?> 
							<a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=View&<?php echo $pagevars; ?>"><?php echo $company_name;?> </a>				
						<?php } else { ?> 
							<a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=View&<?php echo $pagevars; ?>">View</a>				
						<?php } ?> 
					</TD>
						<?php $company_broker = $MGArraytmp2["company_broker"]; ?>
					<TD CLASS='<?php echo $shade; ?>'>
						<?php echo $company_broker; ?> 
					</TD>
						<?php $drop_trailer_capabilities = $MGArraytmp2["drop_trailer_capabilities"]; ?>
					<TD CLASS='<?php echo $shade; ?>'>
						<?php echo $drop_trailer_capabilities; ?> 
					</TD>
						<?php $states_serviced = $MGArraytmp2["states_serviced"]; ?>
					<TD CLASS='<?php echo $shade; ?>'>
						<?php echo $states_serviced; ?> 
					</TD>
						<?php $states_serviced = $MGArraytmp2["states_serviced_in_canada"]; ?>
					<TD CLASS='<?php echo $shade; ?>'>
						<?php echo $states_serviced; ?> 
					</TD>
						<?php $states_serviced = $MGArraytmp2["states_serviced_in_mexico"]; ?>
					<TD CLASS='<?php echo $shade; ?>'>
						<?php echo $states_serviced; ?> 
					</TD>

					<?php $citystate = $MGArraytmp2["citystate"]; ?>
					<TD CLASS='<?php echo $shade; ?>'>
						<?php echo $citystate; ?> 
					</TD>
				</TR>
			<?php
			}
		}	
	
echo "</TABLE>";

} //END PROC == ""
} //END IF POSTING = YES
/*---------------------------------------------------------------------------------
END SEARCH SECTION 9991
---------------------------------------------------------------------------------*/

}// END IF PROC = ""

?>
<?php 

/*----------------------------------------------------------------------
ADD NEW RECORDS SECTION
----------------------------------------------------------------------*/
if ($proc == "New") {
?>
<!--<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>-->
<?php
 if ($allowaddnew == "yes") { 
 ?>
<!--<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>-->
 <?php } //END OF IF ALLOW ADDNEW 
/*-------------------------------------------------------------------------------
ADD NEW RECORD SECTION 9994
-------------------------------------------------------------------------------*/

if ($proc == "New") { 
echo "<a href=\"manage_freightvendor_mrg.php?posting=yes\">Back</a><br><br>";
echo "<DIV CLASS='PAGE_STATUS'>Adding Freight Vendor</DIV>";
	if ($post == "yes") {
/*
WE ARE ADDING A NEW RECORD SUBMITTED FROM THE FORM
NOW LOOP THROUGH ALL OF THE RECORDS AND OUTPUT A STRING
*/

/* FIX STRING */
$company_name = FixString($_REQUEST['company_name']);
		$company_address1 = FixString($_REQUEST['company_address1']);
		$company_address2 = FixString($_REQUEST['company_address2']);
		$company_city = FixString($_REQUEST['company_city']);
		$company_state = FixString($_REQUEST['company_state']);
		$company_zip = FixString($_REQUEST['company_zip']);
		$company_broker = FixString($_REQUEST['company_broker']);
		$drop_trailer_capabilities = FixString($_REQUEST['drop_trailer_capabilities']);
		$states_serviced = isset($_REQUEST['states_serviced']) ? implode(', ', $_REQUEST['states_serviced']) : "";
		$states_serviced_in_canada = isset($_REQUEST['states_serviced_in_canada']) ? implode(', ', $_REQUEST['states_serviced_in_canada']) : "";
		$states_serviced_in_mexico = isset($_REQUEST['states_serviced_in_mexico']) ? implode(', ', $_REQUEST['states_serviced_in_mexico']) : "";
		$company_contact = FixString($_REQUEST['company_contact']);
		$company_phone = FixString($_REQUEST['company_phone']);
		$company_email = FixString($_REQUEST['company_email']);
		$notes = FixString($_REQUEST['notes']);
		$other1 = FixString($_REQUEST['other1']);
		$other2 = FixString($_REQUEST['other2']);
		$other3 = FixString($_REQUEST['other3']);
		


/*-- SECTION: 9994SQL --*/
	$sql = "INSERT INTO loop_freightvendor (
company_name,
company_address1,
company_address2,
company_city,
company_state,
company_zip,
company_broker,
company_contact,
company_phone,
company_email,
notes,
other1,
other2,
other3,
drop_trailer_capabilities,
states_serviced,
states_serviced_in_canada,
states_serviced_in_mexico
 $addl_insert_crit ) VALUES ( '$company_name',
'$company_address1',
'$company_address2',
'$company_city',
'$company_state',
'$company_zip',
'$company_broker',
'$company_contact',
'$company_phone',
'$company_email',
'$notes',
'$other1',
'$other2',
'$other3',
'$drop_trailer_capabilities',
'$states_serviced',
'$states_serviced_in_canada',
'$states_serviced_in_mexico'
 $addl_insert_values )";
	if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }

	db();
	$result = db_query($sql);
	if (empty($result)) {
	echo "<DIV CLASS='SQL_RESULTS'>Record Inserted <a href=\"manage_freightvendor_mrg.php?posting=yes\">Continue</a></DIV>";
	
if (!headers_sent()){    //If headers not sent yet... then do php redirect
        header('Location: manage_freightvendor_mrg.php?posting=yes'); exit;
}
else
{
        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"manage_freightvendor_mrg.php?posting=yes\";";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=manage_freightvendor_mrg.php?posting=yes\" />";
        echo "</noscript>"; exit;
}//==== End -- Redirect
	} else {
	echo ThrowError("9994SQL",$sql, 'Producion', db());
	echo "Error inserting record (9994SQL)";
	}
//***** END INSERT SQL *****
} //END IF POST = YES FOR ADDING NEW RECORDS
/*-------------------------------------------------------------------------------
ADD NEW RECORD (CREATING)
-------------------------------------------------------------------------------*/
	if (!$post) {//THEN WE ARE ENTERING A NEW RECORD
	//SHOW THE ADD RECORD RECORD DATA INPUT FORM
	/*-- SECTION: 9994FORM --*/
	?>
	<FORM METHOD="POST" ACTION="<?php echo $thispage; ?>?proc=New&post=yes&<?php echo $pagevars; ?>">
	<TABLE class="text_align_left">
	<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>Freight Company Name:</B>
		</TD>
		<TD>
			<INPUT CLASS='TXT_BOX' type="text" NAME="company_name" SIZE="20">
		</TR>
		<TR>
			<TD CLASS='TBL_ROW_HDR'>
				<B>Contact Name:</B>
			</TD>
			<TD>
				<INPUT CLASS='TXT_BOX' type="text" NAME="company_contact" SIZE="20">
			</TD>
		</TR>
		<TR>
			<TD CLASS='TBL_ROW_HDR'>
				<B>Contact Phone:</B>
			</TD>
			<TD>
				<INPUT CLASS='TXT_BOX' type="text" NAME="company_phone" SIZE="20">
			</TD>
		</TR>
		<TR>
			<TD CLASS='TBL_ROW_HDR'>
				<B>Contact Email:</B>
			</TD>
			<TD>
				<INPUT CLASS='TXT_BOX' type="text" NAME="company_email" SIZE="20">
			</TD>
		</TR>
		
		<TR>
			<TD CLASS='TBL_ROW_HDR'>
				<B>Address One:</B>
			</TD>
			<TD>
				<INPUT CLASS='TXT_BOX' type="text" NAME="company_address1" SIZE="20">
			</TD>
		</TR>
		<TR>
			<TD CLASS='TBL_ROW_HDR'>
				<B>Address Two:</B>
			</TD>
			<TD>
				<INPUT CLASS='TXT_BOX' type="text" NAME="company_address2" SIZE="20">
			</TD>
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>City:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="company_city" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>State:</B>
		</TD>
		<TD>
			<select name="company_state" id="company_state">
				<?php
				db_b2b();
				$tableedit  = "SELECT * FROM zones where zone_country_id in (223,38,37) ORDER BY zone_country_id desc, zone_name";
				$dt_view_res = db_query($tableedit);
				while ($row_stat = array_shift($dt_view_res)) {
					?>
					<option 
					<?php 
					if ((trim($company_state) == trim($row_stat["zone_code"])) ||  (trim($company_state) == trim($row_stat["zone_name"])))
					  echo " selected ";
					  ?> value="<?php echo trim($row_stat["zone_code"])?>">
					  <?php echo $row_stat["zone_name"]?>
					   (<?php echo $row_stat["zone_code"]?>)
					</option>
					<?php
				}
				?>
			</select>
			
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>Postal Code:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="company_zip" SIZE="20">
		</TR>
		<TR>
			<TD CLASS='TBL_ROW_HDR'>
				<B>Vendor Type:</B>
			</TD>
			<TD>
			<SELECT NAME="company_broker" SIZE="1">
				<option value="Asset Based Carrier">Asset Based Carrier</option>
				<option value="Broker">Broker</option>
				<option value="Full Service (Assets + Brokerage)">Full Service (Assets + Brokerage)</option>
			</select>
			</TD>
		</TR>
		<TR>
			<TD CLASS='TBL_ROW_HDR'>
				<B>Drop Trailer Capabilities:</B>
			</TD>
			<TD>
				<select name="drop_trailer_capabilities" SIZE="1">
					<option value="Yes">Yes</option>
					<option value="No" selected >No</option>
				</select>
			</TD>
		</TR>
		<TR>
			<TD CLASS='TBL_ROW_HDR'>
				<B>States/Provinces Serviced in USA:</B>
			</TD>
			<TD>
				<Select id="states_serviced" name="states_serviced[]" multiple>
					<?php
					$txtselected ="";
					db();
					$sqlfrm = "SELECT * FROM state_master where usa_can_max_flg = 1 order by usa_can_max_flg, state";
					//echo $sqlfrm.".....874<br>";
					$state_masterarr = db_query($sqlfrm);
					while($row = array_shift($state_masterarr)){
						//echo " 887 ".$row["state"]." 888<br>";
						if(isset($_POST["states_serviced"]) && in_array($row["state"], $_POST["states_serviced"])){
							$txtselected = "selected";
						}else{
							$txtselected = "";
						}
						echo '<option value="'.$row["state"].'"  '.$txtselected.'>'.$row["state"].'</option>';
					}
					?>
				</select>
			</TD>
		</TR>

		<TR>
			<TD CLASS='TBL_ROW_HDR'>
				<B>States/Provinces Serviced in Canada:</B>
			</TD>
			<TD>
				<Select id="states_serviced_in_canada" name="states_serviced_in_canada[]" multiple>
					<?php
					$txtselected ="";
					$sqlfrm = "SELECT * FROM state_master where usa_can_max_flg = 2 order by usa_can_max_flg, state";
					$state_masterarr = db_query($sqlfrm);
					/*foreach($state_masterarr as $row){
						if(in_array($row["state"], $_POST["states_serviced_in_canada"])){
							$txtselected = "selected";
						}else{
							$txtselected = "";
						}
						echo '<option value="'.$row["state"].'"  '.$txtselected.'>'.$row["state"].'</option>';
					}*/
					while($row = array_shift($state_masterarr)){
						//echo " 887 ".$row["state"]." 888<br>";
						if(isset($_POST["states_serviced_in_canada"]) && in_array($row["state"], $_POST["states_serviced_in_canada"])){
							$txtselected = "selected";
						}else{
							$txtselected = "";
						}
						echo '<option value="'.$row["state"].'"  '.$txtselected.'>'.$row["state"].'</option>';
					}
					?>
				</select>
			</TD>
		</TR>

		<TR>
			<TD CLASS='TBL_ROW_HDR'>
				<B>States/Provinces Serviced in Mexico:</B>
			</TD>
			<TD>
				<Select id="states_serviced_in_mexico" name="states_serviced_in_mexico[]" multiple>
					<?php
					$txtselected ="";
					$sqlfrm = "SELECT * FROM state_master where usa_can_max_flg = 3 order by usa_can_max_flg, state";
					$state_masterarr = db_query($sqlfrm);
					/*
						foreach($state_masterarr as $row){
							if(in_array($row["state"], $_POST["states_serviced_in_mexico"])){
								$txtselected = "selected";
							}else{
								$txtselected = "";
							}
							echo '<option value="'.$row["state"].'"  '.$txtselected.'>'.$row["state"].'</option>';
						}
						*/
						while($row = array_shift($state_masterarr)){
							//echo " 887 ".$row["state"]." 888<br>";
							if(isset($_POST["states_serviced_in_mexico"]) && in_array($row["state"], $_POST["states_serviced_in_mexico"])){
								$txtselected = "selected";
							}else{
								$txtselected = "";
							}
							echo '<option value="'.$row["state"].'"  '.$txtselected.'>'.$row["state"].'</option>';
						}
					?>
				</select>
			</TD>
		</TR>
		
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>Notes:</B>
		</TD>
		<TD>
	<textarea  CLASS='TXT_BOX'NAME="notes" ROWS="4" cols="35"></TEXTAREA>
		</TR>
		<TR>
	<TD>
	</TD>
	<TD>
		<INPUT CLASS='BUTTON' TYPE="SUBMIT" VALUE="SAVE" NAME="SUBMIT">
		<INPUT CLASS='BUTTON' TYPE="RESET" VALUE="RESET" NAME="RESET">
	</TD>
 </TR>
	</TABLE>
	<BR>
	</FORM>

<?php
} //END if post=""
//***** END ADD NEW ENTRY FORM*****
} //END PROC == NEW


/*---------------------------------------------------------------------------------
END ADD SECTION 9994
---------------------------------------------------------------------------------*/}// END IF PROC = "NEW"
?>
<?php 
/*-------------------------------------------------
SEARCH AND ADD-NEW LINKS
-------------------------------------------------*/
if ($proc == "Edit") {
echo "<a href=\"manage_freightvendor_mrg.php?posting=yes\">Back</a><br><br>";
?>
<!--<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>-->
<?php
 if ($allowaddnew == "yes") { 
 ?>
<!--<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>-->
 <?php } //END OF IF ALLOW ADDNEW 

/*----------------------------------------------------------------------
EDIT RECORDS SECTION
----------------------------------------------------------------------*/
/*-------------------------------------------------------------------------------
EDIT RECORD SECTION 9993
-------------------------------------------------------------------------------*/

?>
<!--<DIV CLASS='PAGE_OPTIONS'>
	<?php if ($allowview == "yes") { ?><a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=View&<?php echo $pagevars; ?>">View</a>
	<?php } //END ALLOWVIEW ?><?php if ($allowedit == "yes") { ?>
	<a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=Edit&<?php echo $pagevars; ?>">Edit</a>
	<?php } //END ALLOWEDIT ?><?php if ($allowdelete == "yes") { ?>
	<a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=Delete&<?php echo $pagevars; ?>">Delete</a>
	<?php } //END ALLOWDELETE ?></span><span class='font_family_Ariel font_size_2'>
</DIV>
-->
<?php
//print_r($_REQUEST);
if ($proc == "Edit") {
	//echo "Yes Edit";
//SHOW THE EDIT RECORD RECORD PAGE
//******************************************************************//
if ($post == "yes") {
	echo "Yes Post";
//THEN WE ARE POSTING UPDATES TO A RECORD
//***** BEGIN UPDATE SQL*****

//REPLACE THE FIELD CONTENTS SO THEY DON'T MESS UP YOUR QUERY
//NOW LOOP THROUGH ALL OF THE RECORDS AND OUTPUT A STRING

/* FIX STRING */
//print_r($_REQUEST);
$company_name = FixString($_REQUEST['company_name']);
		$company_address1 = FixString($_REQUEST['company_address1']);
		$company_address2 = FixString($_REQUEST['company_address2']);
		$company_city = FixString($_REQUEST['company_city']);
		$company_state = FixString($_REQUEST['company_state']);
		$company_zip = FixString($_REQUEST['company_zip']);
		$company_broker = FixString($_REQUEST['company_broker']);
		$states_serviced = $_REQUEST['states_serviced'] ? implode(', ', $_REQUEST['states_serviced']) : "";
		$states_serviced_in_canada = isset($_REQUEST['states_serviced_in_canada']) ? implode(', ', $_REQUEST['states_serviced_in_canada']) : "";
		$states_serviced_in_mexico = $_REQUEST['states_serviced_in_mexico'] ? implode(', ', $_REQUEST['states_serviced_in_mexico']) : "";
		$drop_trailer_capabilities = FixString($_REQUEST['drop_trailer_capabilities']);
		$company_contact = FixString($_REQUEST['company_contact']);
		$company_phone = FixString($_REQUEST['company_phone']);
		$company_email = FixString($_REQUEST['company_email']);
		$notes = FixString($_REQUEST['notes']);
		$other1 = FixString($_REQUEST['other1']);
		$other2 = FixString($_REQUEST['other2']);
		$other3 = FixString($_REQUEST['other3']);
		
//SQL STRING
/*-- SECTION: 9993SQLUPD --*/
$sql = "UPDATE loop_freightvendor SET 
 company_name='$company_name',
 company_address1='$company_address1',
 company_address2='$company_address2',
 company_city='$company_city',
 company_state='$company_state',
 company_zip='$company_zip',
 company_broker='$company_broker',
 states_serviced = '$states_serviced',
 states_serviced_in_canada = '$states_serviced_in_canada',
 states_serviced_in_mexico = '$states_serviced_in_mexico',
 drop_trailer_capabilities='$drop_trailer_capabilities',
 company_contact='$company_contact',
 company_phone='$company_phone',
 company_email='$company_email',
 notes='$notes',
 other1='$other1',
 other2='$other2',
 other3='$other3'
$addl_update_crit 
	WHERE (id='$id') $addl_select_crit ";
	if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }

//echo $sql." <br>";
db();
$result = db_query($sql);
if (empty($result)) {
echo "<DIV CLASS='SQL_RESULTS'>Freight Vendor Updated  <a href=\"manage_freightvendor_mrg.php?posting=yes\">Continue</a></DIV>";

if (!headers_sent()){    //If headers not sent yet... then do php redirect
        header('Location: manage_freightvendor_mrg.php?posting=yes'); exit;
}
else
{
        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"manage_freightvendor_mrg.php?posting=yes\";";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=manage_freightvendor_mrg.php?posting=yes\" />";
        echo "</noscript>"; exit;
}//==== End -- Redirect
} else {
echo "Error Updating Record (9993SQLUPD)";
}
//***** END UPDATE SQL *****
} //END IF POST IS YES

/*-------------------------------------------------------------------------------
EDIT RECORD (FORM) - SHOW THE EDIT RECORD RECORD DATA INPUT FORM
-------------------------------------------------------------------------------*/
if ($post == "") { //THEN WE ARE EDITING A RECORD
echo "<DIV CLASS='PAGE_STATUS'>Editing Record</DIV>";


/*-- SECTION: 9993SQLGET --*/
db();
$sql = "SELECT * FROM loop_freightvendor WHERE (id = '$id') $addl_select_crit ";
if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
$result = db_query($sql) or die ("Error Retrieving Records (9993SQLGET)");
if ($myrow = array_shift($result)) {
do
{

$company_name = $myrow["company_name"];
$company_name = preg_replace("(\n)", "<BR>", $company_name);
$company_address1 = $myrow["company_address1"];
$company_address1 = preg_replace("(\n)", "<BR>", $company_address1);
$company_address2 = $myrow["company_address2"];
$company_address2 = preg_replace("(\n)", "<BR>", $company_address2);
$company_city = $myrow["company_city"];
$company_city = preg_replace("(\n)", "<BR>", $company_city);
$company_state = $myrow["company_state"];
$company_state = preg_replace("(\n)", "<BR>", $company_state);
$company_zip = $myrow["company_zip"];
$company_zip = preg_replace("(\n)", "<BR>", $company_zip);
$company_broker = $myrow["company_broker"];
$company_broker = preg_replace("(\n)", "<BR>", $company_broker);

$states_serviced = explode(", ", $myrow["states_serviced"]);
$states_serviced_in_canada = explode(", ", $myrow["states_serviced_in_canada"]);
$states_serviced_in_mexico = explode(", ", $myrow["states_serviced_in_mexico"]);

$drop_trailer_capabilities = $myrow["drop_trailer_capabilities"];
$drop_trailer_capabilities = preg_replace("(\n)", "<BR>", $drop_trailer_capabilities);
$company_contact = $myrow["company_contact"];
$company_contact = preg_replace("(\n)", "<BR>", $company_contact);
$company_phone = $myrow["company_phone"];
$company_phone = preg_replace("(\n)", "<BR>", $company_phone);
$company_email = $myrow["company_email"];
$company_email = preg_replace("(\n)", "<BR>", $company_email);
$notes = $myrow["notes"];
$notes = preg_replace("(\n)", "<BR>", $notes);
$other1 = $myrow["other1"];
$other1 = preg_replace("(\n)", "<BR>", $other1);
$other2 = $myrow["other2"];
$other2 = preg_replace("(\n)", "<BR>", $other2);
$other3 = $myrow["other3"];
$other3 = preg_replace("(\n)", "<BR>", $other3);
?>
<form method="post" action="<?php echo $thispage; ?>?proc=Edit&post=yes&<?php echo $pagevars; ?>">
<br>
<TABLE class="text_align_left">
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		Company Name:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_name' value='<?php echo$company_name; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		Contact Name:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_contact' value='<?php echo$company_contact; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		Contact Phone:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_phone' value='<?php echo$company_phone; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		Contact Email:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_email' value='<?php echo$company_email; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		Address One:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_address1' value='<?php echo$company_address1; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		Address Two:
	</TD>
	<TD>	
		<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_address2' value='<?php echo $company_address2; ?>' size='20'>
	</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		City:
	</TD>
	<TD>	
		<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_city' value='<?php echo $company_city; ?>' size='20'>
	</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		State:
	</TD>
	<TD>	
		<select name="company_state" id="company_state">
			<?php
			db_b2b();
			$tableedit  = "SELECT * FROM zones where zone_country_id in (223,38,37) ORDER BY zone_country_id desc, zone_name";
			$dt_view_res = db_query($tableedit);
			while ($row_stat = array_shift($dt_view_res)) {
				?>
				<option 
				<?php 
				if ((trim($company_state) == trim($row_stat["zone_code"])) ||  (trim($company_state) == trim($row_stat["zone_name"])))
				  echo " selected ";
				  ?> value="<?php echo trim($row_stat["zone_code"])?>">
				  <?php echo $row_stat["zone_name"]?>
				   (<?php echo $row_stat["zone_code"]?>)
				</option>
				<?php
			}
			?>
		</select>
		
	</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		Zip Code:
	</TD>
	<TD>	
		<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_zip' value='<?php echo $company_zip; ?>' size='20'>
	</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		Vendor Type:
	</TD>
	<TD>	
		<SELECT NAME="company_broker" SIZE="1">
			<option value='Asset Based Carrier' <?php if ($company_broker == "Asset Based Carrier") { echo " selected ";}?>>Asset Based Carrier</option>
			<option value='Broker' <?php if ($company_broker == "Broker") { echo " selected ";}?>>Broker</option>
			<option value='Full Service (Assets + Brokerage)' <?php if ($company_broker == "Full Service (Assets + Brokerage)") { echo " selected ";}?>>Full Service (Assets + Brokerage)</option>
		</select>
	</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>
		<B>Drop Trailer Capabilities:</B>
	</TD>
	<TD>
	<SELECT NAME="drop_trailer_capabilities" SIZE="1">
		<option value="Yes" <?php if ($drop_trailer_capabilities == "Yes") { echo " selected ";}?>>Yes</option>
		<option value="No" <?php if ($drop_trailer_capabilities == "No" || $drop_trailer_capabilities == "") { echo " selected ";}?>>No</option>
	</TD>
</TR>

	<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>States/Provinces Serviced in USA:</B>
		</TD>
		<TD>
			<Select id="states_serviced" name="states_serviced[]" multiple>
				<?php
				$txtselected ="";
				db();
				$sqlfrm = "SELECT * FROM state_master where usa_can_max_flg = 1 order by usa_can_max_flg, state";
				//echo $sqlfrm.".....1256<br>";
				$state_masterarr = db_query($sqlfrm);
				/*foreach($state_masterarr as $row){
					if(in_array($row["state"], $states_serviced)){
						$txtselected = "selected";
					}else{
						$txtselected = "";
					}
					echo '<option value="'.$row["state"].'"  '.$txtselected.'>'.$row["state"].'</option>';
				}
					*/

				while($row = array_shift($state_masterarr)){
					if(in_array($row["state"], $states_serviced)){
						$txtselected = "selected";
					}else{
						$txtselected = "";
					}
					echo '<option value="'.$row["state"].'"  '.$txtselected.'>'.$row["state"].'</option>';
				}
				?>
			</select>
		</TD>
	</TR>

	<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>States/Provinces Serviced in Canada:</B>
		</TD>
		<TD>
			<Select id="states_serviced_in_canada" name="states_serviced_in_canada[]" multiple>
				<?php
				$txtselected ="";
				$sqlfrm = "SELECT * FROM state_master where usa_can_max_flg = 2 order by usa_can_max_flg, state";
				$state_masterarr = db_query($sqlfrm);
				/*foreach($state_masterarr as $row){
					if(in_array($row["state"], $states_serviced_in_canada)){
						$txtselected = "selected";
					}else{
						$txtselected = "";
					}
					echo '<option value="'.$row["state"].'"  '.$txtselected.'>'.$row["state"].'</option>';
				}
					*/
				while($row = array_shift($state_masterarr)){
					//echo " 887 ".$row["state"]." 888<br>";
					if(in_array($row["state"], $states_serviced_in_canada)){
						$txtselected = "selected";
					}else{
						$txtselected = "";
					}
					echo '<option value="'.$row["state"].'"  '.$txtselected.'>'.$row["state"].'</option>';
				}
				?>
			</select>
		</TD>
	</TR>

	<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>States/Provinces Serviced in Mexico:</B>
		</TD>
		<TD>
			<Select id="states_serviced_in_mexico" name="states_serviced_in_mexico[]" multiple>
				<?php
				$txtselected ="";
				$sqlfrm = "SELECT * FROM state_master where usa_can_max_flg = 3 order by usa_can_max_flg, state";
				$state_masterarr = db_query($sqlfrm);
				
				while($row = array_shift($state_masterarr)){
					//echo " 887 ".$row["state"]." 888<br>";
					if(in_array($row["state"], $states_serviced_in_mexico)){
						$txtselected = "selected";
					}else{
						$txtselected = "";
					}
					echo '<option value="'.$row["state"].'"  '.$txtselected.'>'.$row["state"].'</option>';
				}
				?>
			</select>
		</TD>
	</TR>

<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		Notes:
	</TD>
	<TD>	
				<?php $notes=preg_replace( "(<BR>)", "", $notes ); ?>
		<textarea  CLASS='TXT_BOX' name="notes" rows="4" cols="35"><?php echo$notes; ?></textarea>
		</td>
</tr>
<tr>
	<td>
	</td>
	<td>
		<?php $id = $myrow["id"]; ?>
		<input type="hidden" value="<?php echo $id; ?>" name="id">
		<input CLASS="BUTTON" type="submit" value="Save" name="submit">
	</td>
</tr>
</table>

<BR>
</form>
<?php
} while ($myrow = array_shift($result));
} //END IF RESULTS
} //END IF POST IS "" (THIS IS THE END OF EDITING A RECORD)
//***** END EDIT FORM*****
} //END PROC == EDIT

/*-------------------------------------------------------------------------------
END EDIT RECORD SECTION 9993
-------------------------------------------------------------------------------*/}// END IF PROC = "EDIT"
?>
<?php 
/*-------------------------------------------------
SEARCH AND ADD-NEW LINKS
-------------------------------------------------*/
if ($proc == "View") {
?>
<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>
<?php
 if ($allowaddnew == "yes") { 
 ?>
<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
 <?php } //END OF IF ALLOW ADDNEW 

/*----------------------------------------------------------------------
VIEW RECORDS SECTION - VIEW SINGLE RECORDS
----------------------------------------------------------------------*/

/*-------------------------------------------------------------------------------
VIEW RECORD SECTION 9992
-------------------------------------------------------------------------------*/
if ($proc == "View") {
echo "<DIV CLASS='PAGE_STATUS'>Viewing Record</DIV>";
//***** BEGIN SEARCH RESULTS ****************************************************
//THEN WE ARE SHOWING THE RESULTS OF A SEARCH


/*-- SECTION: 9992SQL --*/
//IF NO SEARCH WORDS TYPED, SHOW ALL RECORDS
db();
$sql = "SELECT * FROM loop_freightvendor WHERE id='$id' $addl_select_crit ";
if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
	$result = db_query($sql);		
	if ($myrowsel = array_shift($result)) {
	do{
	$id = $myrowsel["id"];
		$company_name = $myrowsel["company_name"];
$company_address1 = $myrowsel["company_address1"];
$company_address2 = $myrowsel["company_address2"];
$company_city = $myrowsel["company_city"];
$company_state = $myrowsel["company_state"];
$company_zip = $myrowsel["company_zip"];
$company_broker = $myrowsel["company_broker"];
$states_serviced = $myrowsel["states_serviced"];
$states_serviced_in_canada = $myrowsel["states_serviced_in_canada"];
$states_serviced_in_mexico = $myrowsel["states_serviced_in_mexico"];
$company_contact = $myrowsel["company_contact"];
$drop_trailer_capabilities = $myrowsel["drop_trailer_capabilities"];
$company_phone = $myrowsel["company_phone"];
$company_email = $myrowsel["company_email"];
$notes = $myrowsel["notes"];
$other1 = $myrowsel["other1"];
$other2 = $myrowsel["other2"];
$other3 = $myrowsel["other3"];
?>
<TABLE>
<DIV CLASS='PAGE_OPTIONS'>
<?php if ($allowview == "yes") { ?><a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=View&<?php echo $pagevars; ?>">View</a>
<?php } //END ALLOWVIEW ?><?php if ($allowedit == "yes") { ?>
<a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=Edit&<?php echo $pagevars; ?>">Edit</a>
<?php } //END ALLOWEDIT ?><?php if ($allowdelete == "yes") { ?>
<a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=Delete&<?php echo $pagevars; ?>">Delete</a>
<?php } //END ALLOWDELETE ?><br></span><span class='font_family_Ariel font_size_2'>
</DIV>
	<TR>
		<TD CLASS='TBL_ROW_HDR'>COMPANY NAME:</TD>
		<TD ><DIV CLASS='TBL_ROW_DATA'><?php echo $company_name; ?> </DIV></TD>
	</TR>
	<TR><TD CLASS='TBL_ROW_HDR'>CONTACT NAME:</TD>
		<TD ><DIV CLASS='TBL_ROW_DATA'><?php echo $company_contact; ?> </DIV></TD>
	</TR>
		
	<TR>
		<TD CLASS='TBL_ROW_HDR'>CONTACT PHONE:</TD>
		<TD ><DIV CLASS='TBL_ROW_DATA'><?php echo $company_phone; ?> </DIV></TD>
	</TR>
	
	<TR><TD CLASS='TBL_ROW_HDR'>CONTACT EMAIL:</TD>
		<TD ><DIV CLASS='TBL_ROW_DATA'><?php echo $company_email; ?> </DIV></TD>
	</TR>

	<TR>
		<TD CLASS='TBL_ROW_HDR'>ADDRESS ONE:</TD>
		<TD ><DIV CLASS='TBL_ROW_DATA'><?php echo $company_address1; ?> </DIV></TD>
	</TR>

	<TR><TD CLASS='TBL_ROW_HDR'>ADDRESS TWO:</TD>
		<TD ><DIV CLASS='TBL_ROW_DATA'><?php echo $company_address2; ?> </DIV></TD>
	</TR>

	<TR><TD CLASS='TBL_ROW_HDR'>CITY:</TD>
		<TD ><DIV CLASS='TBL_ROW_DATA'><?php echo $company_city; ?> </DIV></TD>
	</TR>
		
	<TR><TD CLASS='TBL_ROW_HDR'>STATE:</TD>
		<TD ><DIV CLASS='TBL_ROW_DATA'><?php echo $company_state; ?> </DIV></TD>
	</TR>
	
	<TR>
		<TD CLASS='TBL_ROW_HDR'>ZIP CODE:</TD>
		<TD ><DIV CLASS='TBL_ROW_DATA'><?php echo $company_zip; ?> </DIV></TD>
	</TR>
	
	<TR><TD CLASS='TBL_ROW_HDR'>VENDOR TYPE:</TD>
		<TD ><DIV CLASS='TBL_ROW_DATA'><?php echo $company_broker; ?> </DIV></TD>
	</TR>

	<TR><TD CLASS='TBL_ROW_HDR'>DROP TRAILER CAPABILITIES:</TD>
		<TD ><DIV CLASS='TBL_ROW_DATA'><?php echo $drop_trailer_capabilities; ?> </DIV></TD>
	</TR>
	
	<TR><TD CLASS='TBL_ROW_HDR'>STATE/PROVINCES SERVICED:</TD>
		<TD ><DIV CLASS='TBL_ROW_DATA'><?php echo $states_serviced; ?> </DIV></TD>
	</TR>

	<TR><TD CLASS='TBL_ROW_HDR'>STATE/PROVINCES SERVICED IN CANADA:</TD>
		<TD ><DIV CLASS='TBL_ROW_DATA'><?php echo $states_serviced_in_canada; ?> </DIV></TD>
	</TR>

	<TR><TD CLASS='TBL_ROW_HDR'>STATE/PROVINCES SERVICED IN MEXICO:</TD>
		<TD ><DIV CLASS='TBL_ROW_DATA'><?php echo $states_serviced_in_mexico; ?> </DIV></TD>
	</TR>
	
	<TR>
		<TD CLASS='TBL_ROW_HDR'>NOTES:</TD>
		<TD ><DIV CLASS='TBL_ROW_DATA'><?php echo $notes; ?> </DIV></TD>
	</TR>
	
	<?php
	}
		while ($myrowsel = array_shift($result));
		echo "</TR>\n</TABLE>";
	} //IF RESULT

} //END OF PROC VIEW

/*-------------------------------------------------------------------------------
END VIEW RECORD SECTION 9992
-------------------------------------------------------------------------------*/

}// END IF PROC = "VIEW"
?>
<?php 
if ($proc == "Delete") {
echo "<a href=\"manage_freightvendor_mrg.php?posting=yes\">Back</a><br><br>";
?>
<!--<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>-->
<?php

 if ($allowaddnew == "yes") { 
 ?>

<!--<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>-->
 <?php } //END OF IF ALLOW ADDNEW 
 
/*----------------------------------------------------------------------
DELETE RECORD SECTION - THIS SECTION WILL CONFIRM/PERFORM DELETIONS
----------------------------------------------------------------------*/

/*-------------------------------------------------------------------------------
DELETE RECORD SECTION 9995
-------------------------------------------------------------------------------*/
?>
<DIV CLASS='PAGE_STATUS'>Deleting Record</DIV>
<?php
/*-- SECTION: 9995CONFIRM --*/
if (!$delete) {
?>
		<DIV CLASS='PAGE_OPTIONS'>
			<br><br>Are you sure you want to delete this Freight Vendor?<br><br>
						 <strong>THIS CANNOT BE UNDONE!</strong><BR><br>
			 <a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&delete=yes&proc=Delete&<?php echo $pagevars; ?>">Yes</a>
			   <a href="<?php echo $thispage; ?>?<?php echo $pagevars; ?>">No</a>
		</DIV>
	<?php
	} //IF !DELETE
	
if ($delete == "yes") {

	/*-- SECTION: 9995SQL --*/
	$sql = "DELETE FROM loop_freightvendor WHERE id='$id' $addl_select_crit ";
	if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
	db();
	$result = db_query($sql);		
	if (empty($result)) {
	echo "<DIV CLASS='SQL_RESULTS'>Successfully Deleted</DIV>";
	} else {
	echo "Error Deleting Record (9995SQL)";
	}
} //END IF $DELETE=YES
/*-------------------------------------------------------------------------------
END DELETE RECORD SECTION 9995
-------------------------------------------------------------------------------*/
}// END IF PROC = "DELETE"




?>
<BR>

<BR>
</span>


	</div>
	
	<link href="css/multiselect.css" rel="stylesheet"/>
	<script src="multiselect.min.js"></script>
	<script>
		document.multiselect('#states_serviced')
		.setCheckBoxClick("checkboxAll", function(target, args) {
		})
		.setCheckBoxClick("1", function(target, args) {
		});
		
		document.multiselect('#states_serviced_in_canada')
		.setCheckBoxClick("checkboxAll", function(target, args) {
		})
		.setCheckBoxClick("1", function(target, args) {
		});

		document.multiselect('#states_serviced_in_mexico')
		.setCheckBoxClick("checkboxAll", function(target, args) {
		})
		.setCheckBoxClick("1", function(target, args) {
		});
		
	</script>
	
</body>
</html>
