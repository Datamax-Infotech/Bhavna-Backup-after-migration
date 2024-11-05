<?php 
	require ("inc/header_session.php");
	require ("../mainfunctions/database.php");
	require ("../mainfunctions/general-functions.php");

	function timestamp_to_date_gy($d)
	{
		$da = explode(" ",$d);
		$dp = explode("-", $da[0]);
		return $dp[1] . "/" . $dp[2] . "/" . $dp[0];
	}

	function feed($a,$b,$c)
	{

		$qry = "INSERT INTO loop_feed SET message = '" . str_replace("'", "\'" ,$a) . "', created_by = " . $b . ", created_for = " . $c;
		db();
		$res_newtrans = db_query($qry);
		return $res_newtrans;
	}

		if ($_REQUEST["txtmessage"] != "")
		{
			if ($_REQUEST["typeid"] == 'phone' || $_REQUEST["typeid"] == 'email'){
				$sql = "UPDATE companyInfo SET last_contact_date = '" . date("Y-m-d") . "' WHERE ID = " . $_REQUEST['compid'];
				db_b2b();
				$result = db_query($sql);
			}
			
	
			$arr_data = array(	'companyID' => formatdata($_REQUEST['compid']),
								'type' => formatdata($_REQUEST["typeid"]),
								'message' => formatdata($_REQUEST["txtmessage"]),
								'employee' => formatdata($_REQUEST["txtemployee"]),
								'file_name' => "",
								'messageDate' => date('m/d/Y'));

			$query1 = make_insert_query('CRM', $arr_data);
			db_b2b();
			db_query($query1);
		}

		if ($_REQUEST["note"] != "")
		{
			$arr_data = array(	'companyID' => formatdata($_REQUEST['compid']),
								'notes' => formatdata($_REQUEST["note"]),
								'box_b2b_id' => formatdata($_REQUEST["box_id"]),
								'entry_emp' => formatdata($_REQUEST["txtemployee"]),
								'entry_datetime' => date('Y-m-d H:i:s'));

			$query1 = make_insert_query('inventory_notes', $arr_data);
			db_b2b();
			db_query($query1);
		}
		//
		//----------------Update inventory log----------------------------------------
		$mod_box_notes="";
		$compid=$_REQUEST['compid'];
		$modify_emp=$_COOKIE['userinitials'];
		$modify_datetime=date('Y-m-d H:i:s');
		//
		$qry_b = "select * from loop_boxes where b2b_id =" . $_REQUEST["box_id"];	
		db();
		$dt_view_b = db_query($qry_b);
		$box_res = array_shift($dt_view_b); 	
			$leadtime = $box_res["lead_time"];
			$b2b_status = $box_res["b2b_status"];
			$loop_id = $box_res['id'];
			$boxb2b_id = $box_res['b2b_id'];
			$next_load_available_date = $box_res["next_load_available_date"];
			$txt_after_po = $box_res["after_po"]; 
			$expected_loads_per_mo = $box_res["expected_loads_per_mo"];
			$boxes_per_trailer = $box_res["boxes_per_trailer"];
			$flyer_notes = $box_res["flyer_notes"];
		//
		$qry_i = "select * from inventory where ID =" . $_REQUEST["box_id"];	
		db_b2b();
		$dt_view_i = db_query($qry_i);
		$res_i = array_shift($dt_view_i); 
			$notes = preg_replace("(\n)", "<BR>", $res_i["notes"]);
		//
			$new_notes = preg_replace("/'/", "\'", $_REQUEST["note"]);


		//save log----------------
		if(isset($_REQUEST['box_type']) && $_REQUEST['box_type']=="not_gaylords"){
			if($_REQUEST["after_actual_inventory"]!=$txt_after_po)
			{
				$mod_box_notes.="Qty Available Now changed from \' ".$txt_after_po. "\' to \'" .$_REQUEST["after_actual_inventory"]."\'<br>";
			}
			if($_REQUEST["next_load_date"]!=$next_load_available_date )
			{
			$mod_box_notes.="Next Load Available Date changed from \' ".$next_load_available_date. "\' to \'" .$_REQUEST["next_load_date"]."\'<br>";
			}
		}
		
		//
		if($_REQUEST["expected_loads_per_mo"]!=$expected_loads_per_mo )
		{
			$mod_box_notes.="Expected # of Loads/Mo changed from \' ".$expected_loads_per_mo. "\' to \'" .$_REQUEST["expected_loads_per_mo"]."\'<br>";
		}
		if($_REQUEST["b2b_status"]!=$b2b_status )
		{
			$mod_box_notes.="Box Status changed from \' ".$b2b_status. "\' to \'" .$_REQUEST["b2b_status"]."\'<br>";
		}
		
		if($new_notes!=$notes)
		{
			$mod_box_notes.="Internal Notes changed from \' ". $notes . "\' to \'" . $new_notes ."\'<br>";
		}
		if(isset($_REQUEST['box_type']) && $_REQUEST['box_type']=="Gaylords"){
			if($_REQUEST["flyer_notes"]!=$flyer_notes )
			{
				$mod_box_notes.="Flyer Notes changed from \' ".$flyer_notes. "\' to \'" .$_REQUEST["flyer_notes"]."\'<br>";
			}
		}
		//
		if($mod_box_notes!="")
		{
			$mod_qry="insert into inventory_modified_log (loop_box_id, b2b_box_id, company_id, modify_emp, modify_datetime, notes) values ('$loop_id', '$boxb2b_id', '$compid', '$modify_emp', '$modify_datetime', '$mod_box_notes')";
			db();
			$modi_res=db_query($mod_qry);
		}
		//
		//----------------End Update inventory log------------------------------------
		//
		db_b2b();
		db_query("UPDATE inventory SET after_actual_inventory = '" . $_REQUEST["after_actual_inventory"] . "' WHERE ID=" . $_REQUEST["box_id"]);
		//echo "SQL2"."UPDATE inventory SET after_actual_inventory = '" . $_REQUEST["after_actual_inventory"] . "' WHERE ID=" . $_REQUEST["box_id"]."<br>";

		db_query("UPDATE inventory SET expected_loads_per_mo = '" . $_REQUEST["expected_loads_per_mo"] . "' WHERE ID=" . $_REQUEST["box_id"]);
		//echo "SQL3"."UPDATE inventory SET expected_loads_per_mo = '" . $_REQUEST["expected_loads_per_mo"] . "' WHERE ID=" . $_REQUEST["box_id"]."<br>";
		
		//db_query("UPDATE inventory SET b2b_status = '" . $_REQUEST["b2b_status"] . "', availability = " . $_REQUEST["availability"] . " WHERE ID=" . $_REQUEST["box_id"] );
		db_query("UPDATE inventory SET b2b_status = '" . $_REQUEST["b2b_status"] . "', next_load_available_date = '" . $_REQUEST["next_load_date"] . "' WHERE ID=" . $_REQUEST["box_id"] );

		db_query("UPDATE inventory SET notes = '" . preg_replace("/'/", "\'", $_REQUEST["note"]) . "', date = '" . date("Y-m-d") . "' WHERE ID=" . $_REQUEST["box_id"]);	
		//echo "SQL5"."UPDATE inventory SET notes = '" . $_REQUEST["note"] . "', date = '" . date("Y-m-d") . "' WHERE id=" . $_REQUEST["box_id"]."<br>";;
			
		db_query("TRUNCATE boxesGaylordList");

		//availability = '" . $_REQUEST["availability"] . "',
		if(isset($_REQUEST['box_type']) && $_REQUEST['box_type']=="Gaylords"){
			db();
			db_query("UPDATE loop_boxes SET b2b_status = '" . $_REQUEST["b2b_status"] . "', expected_loads_per_mo = '" . $_REQUEST["expected_loads_per_mo"] . "', flyer_notes = '" . str_replace("'", "\'" ,$_REQUEST["flyer_notes"]) . "'  WHERE b2b_id =" . $_REQUEST["box_id"]);
		}else{
			db();
			db_query("UPDATE loop_boxes SET b2b_status = '" . $_REQUEST["b2b_status"] . "', next_load_available_date = '" . $_REQUEST["next_load_date"] . "', after_po = '" . $_REQUEST["after_actual_inventory"] . "', expected_loads_per_mo = '" . $_REQUEST["expected_loads_per_mo"] . "'  WHERE b2b_id =" . $_REQUEST["box_id"] );
		}
		 if($_REQUEST['company_id']!="")
		{
			$arr_data = array(	'box_id' => formatdata($_REQUEST["box_id"]),

				'company_id' => formatdata($_REQUEST["company_id"]),

				'status' => formatdata("W"));

			$query1 = make_insert_query('loop_waiting_list', $arr_data);
			//echo $query1 ."SQL6"."<br>";
			db();
			db_query($query1);
		}
		else
		{}
	
	$count = $_REQUEST["cnt"];

	$dt_view_qry_upd = "SELECT *, inventory.ID AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE inventory.ID = " . $_REQUEST["box_id"] . " AND inventory.Active LIKE 'A' ORDER BY inventory.availability DESC";
	db_b2b();
	$dt_view_res_upd = db_query($dt_view_qry_upd);
	while ($inv = array_shift($dt_view_res_upd)) {
		
			$sales_order_qty = 0; $transaction_date = "";
			
			
			$b2b_status = "";
			db();
			$dt_res_so_item1 = db_query("select * from b2b_box_status where status_key='" . $inv["b2b_status"] . "'");
			while ($so_item_row1 = array_shift($dt_res_so_item1)) 
			{
				$b2b_status = $so_item_row1["box_status"];
			}
			$vendor_name = "";		
			
			$supplier_owner_name = "";
			$comqry1="select employeeID,employees.name as empname, employees.initials from employees where employeeID='".$inv["supplier_owner"]."'";
			db_b2b();
			$comres1=db_query($comqry1);
			while($comrow1=array_shift($comres1))
			{
				$supplier_owner_name = $comrow1["initials"];
			}
			
			$next_load_available_date = $inv["next_load_available_date"];
			$next_load_available_date_display = "";
			if ($next_load_available_date != "" && $next_load_available_date != "0000-00-00"){
				$next_load_available_date_display = date("m/d/Y", strtotime($inv["next_load_available_date"]));
			}
		
			$b2b_ulineDollar = round($inv["ulineDollar"]);
			$b2b_ulineCents = $inv["ulineCents"];
			$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
			$b2b_fob = "$" . number_format($b2b_fob,2);
			
			$lead_time=$inv["lead_time"];
			$boxes_per_trailer = $inv["boxes_per_trailer"];
			$txt_after_po = $inv["after_po"];	
			$warehouse_id = $inv["box_warehouse_id"];
			
			$rec_found_box = "n"; $after_po_val_tmp = 0;
			$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where trans_id = " . $inv["loops_id"] . " order by warehouse, type_ofbox, Description";
			$dt_view_res_box = db_query($dt_view_qry );
			while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
				$rec_found_box = "y";
				$after_po_val_tmp = $dt_view_res_box_data["afterpo"];
			}
			
			if ($warehouse_id == 238){
				$txt_after_po = $inv["after_po"];
			}else{	
				if ($rec_found_box == "n"){
					$txt_after_po = $inv["after_po"];
				}else{
					$txt_after_po = $after_po_val_tmp;						
				}	
			}	
			
			$order_col_color = "<font size=1>";
			//echo $count . " cond 1 - " . $transaction_date . " " . $next_load_available_date . "<br>";
			if ($transaction_date != "" && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00")){
				if (strtotime($transaction_date) < strtotime($next_load_available_date)){
					$order_col_color = "<font color=red>";
				}	
			}
			
			$load_date_col_color = "style='font-size:10pt;'"; $load_date_col_color2 = "";
			//echo $count . " cond 2 - " . $inv["b2b_status"] . " " . $next_load_available_date . "<br>";
			if (($inv["b2b_status"] == '1.0' || $inv["b2b_status"] == '1.1' || $inv["b2b_status"] == '1.2') && ($next_load_available_date == "" || $next_load_available_date == "0000-00-00")){
				$load_date_col_color = "style='font-size:10pt;width:50px;background-color:#e8acac;'";
				$load_date_col_color2 = "</span>";
			}

			$notes_date_col_color = "<font size=1>"; 
			if ($inv["DT"] != ""){
				$todays_dt=date('m/d/Y');
				$notes_date_day = dateDiff($todays_dt, date('m/d/Y', strtotime($inv["DT"])));
				//echo $count . " cond 3 - " . $todays_dt . " " . $inv["DT"] . " " . $notes_date_day . "<br>";
				if ($notes_date_day <= 7){
					$notes_date_col_color = "<font color=green>";
				}	
				if ($notes_date_day > 7 && $notes_date_day <= 14){
					$notes_date_col_color = "<font color=#FEDC56>";
				}	
				if ($notes_date_day > 14){
					$notes_date_col_color = "<font color=red>";
				}	
			}
		//
			$qry_sku = "select id, boxes_per_trailer,flyer_notes,bpic_1, bpic_2,bpic_3, bpic_4 from loop_boxes where id=". $inv["loops_id"];	
				$sku = "";$no_pic = 0;$pic_color="";
				db();
				$dt_view_sku = db_query($qry_sku);
				while ($sku_val = array_shift($dt_view_sku)) 
				{
					$loop_id = $sku_val['id'];
					$boxes_pertrailer= $sku_val['boxes_per_trailer'];
					$flyer_notes=$sku_val['flyer_notes'];	
					if ($sku_val["bpic_1"] != "")
					{
						$no_pic = $no_pic+1;
					}
					if ($sku_val["bpic_2"] != "")
					{
						$no_pic = $no_pic+1;
					}
					if ($sku_val["bpic_3"] != "")
					{
						$no_pic = $no_pic+1;
					}
					if ($sku_val["bpic_4"] != "")
					{
						$no_pic = $no_pic+1;
					}
					if($no_pic<4){
						$pic_color="red";
					}
					else{
						$pic_color="#000";
					}
					
				}
			//
			$estimated_next_load = "";
			//Buy Now, Load Can Ship In
			if ($warehouse_id == 238 && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00"))
			{
				$now_date = time(); // or your date as well
				$next_load_date = strtotime($next_load_available_date);
				$datediff = $next_load_date - $now_date;
				$no_of_loaddays=round($datediff / (60 * 60 * 24));
				
				if($no_of_loaddays<$lead_time)
				{
					if($lead_time>1)
					{
						//$estimated_next_load= "<font color=green>" . $lead_time . " Days</font>";
					}
					else{
						//$estimated_next_load= "<font color=green>" . $lead_time . " Day</font>";
					}
					
				}
				else{
					if ($no_of_loaddays == -0){
						//$estimated_next_load= "<font color=green>0 Day</font>";
					}else{
						//$estimated_next_load= "<font color=green>" . $no_of_loaddays . " Days</font>";
					}						
				}
			}
			else{			
				if ($txt_after_po >= $boxes_per_trailer) {
					//=IF(B4>0,"NOW",ROUNDUP(((((B4/R4)*-1)+1)/D4)*4,0))

					if ($lead_time == 0){
						//$estimated_next_load= "<font color=green>Now</font>";
					}							

					if ($lead_time == 1){
						//$estimated_next_load= "<font color=green>" . $lead_time . " Day</font>";
					}							
					if ($lead_time > 1){
						//$estimated_next_load= "<font color=green>" . $lead_time . " Days</font>";
					}							
				}
				else{
					if (($inv["expected_loads_per_mo"] <= 0) && ($txt_after_po < $boxes_per_trailer)){
						//$estimated_next_load= "<font color=red>Never (sell the " . $txt_after_po . ")</font>";
					}else{
						//$estimated_next_load=ceil((((($txt_after_po/$boxes_per_trailer)*-1)+1)/$inv["expected_loads_per_mo"])*4)." Weeks";
					}
				}
			}	

			/*if ($txt_after_po == 0 && $inv["expected_loads_per_mo"] == 0 ) {
				$estimated_next_load= "<font color=red>Ask Purch Rep</font>";
			}*/
			//
			$getEstimatedNextLoad = getEstimatedNextLoad($inv["loops_id"], $warehouse_id, $next_load_available_date, $lead_time, $inv["lead_time"], $txt_after_po, $boxes_per_trailer, $inv["expected_loads_per_mo"], $inv["b2b_status"], 'yes') ;
			$estimated_next_load = $getEstimatedNextLoad;
			$no_of_loads = 0; $total_no_of_loads = 0;
			$dt_view_qry2 = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history 
			where inv_b2b_id = " . $inv["I"] . " and inactive_delete_flg = 0 order by load_available_date";
			db();
			$dt_view_res_box = db_query($dt_view_qry2);
			while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
				if ($dt_view_res_box_data["trans_rec_id"] == 0 ){ 
					$no_of_loads = $no_of_loads + 1;
				}	
				$total_no_of_loads = $total_no_of_loads + 1;
			}
			
		?>

		<tr id="gaylord_div<?php echo $count;?>" vAlign="center">
			<td class="form_label" width="50px"><font size=1><?php echo $count; ?></font>
				<input type=hidden name="box_id" id="box_id<?php echo $count;?>" value="<?php echo $inv["I"];?>">
			</td>	

			<td class="form_label" width="100px"><?php echo $supplier_owner_name;?></td>

			<td class="form_label" width="50px"><?php echo $order_col_color; ?>
			  	<?php
				if (($sales_order_qty > 0) && ($_REQUEST['box_type']!= "Gaylords")) {?>
					<div onclick="display_preoder_sel(<?php echo $count;?>, <?php echo $sales_order_qty;?>, <?php echo $inv["loops_id"];?>, <?php echo $inv["vendor_b2b_rescue"];?>)" style="cursor: pointer;">
					<u>View</u></div>
				<?php
				} elseif (($no_of_loads > 0) && ($_REQUEST['box_type'] == "Gaylords")) {
					?>
					<div onclick="display_av_load_data(<?php echo $srno;?>, <?php echo $inv["I"];?>)" style="cursor: pointer;">
						<?php if ($no_of_loads > 1) { ?>
							<u><?php echo $no_of_loads;?> of <?php echo $total_no_of_loads; ?> Loads</u>
						<?php } else { ?>	
							<u><?php echo $no_of_loads;?> of <?php echo $total_no_of_loads;?> Load</u>
						<?php }  ?>	
					</div>
				<?php
				} elseif (($no_of_loads == 0) && ($_REQUEST['box_type'] == "Gaylords")) {
					?>
					<font color=red>0 of 0 Load</font>
					<?php
				}else{
					echo "";
				}
				?>
				</font>
			</td>
			<?php if($_REQUEST["box_type"] != "Gaylords"){ ?>			
				<td class="form_label" width="100px"><input type="text" id="after_actual_inventory<?php echo $count;?>" name="after_actual_inventory" value="<?php echo $inv["after_actual_inventory"]; ?>" size="5"> </td>
			<?php } ?>
			<td class="form_label" width="100px"><input type="text" id="expected_loads_per_mo<?php echo $count;?>" name="expected_loads_per_mo"  value="<?php echo $inv["expected_loads_per_mo"]; ?>" size="5"></td>
			<?php
			if($_REQUEST["box_type"] != "Gaylords"){
				$date_now = date("Y-m-d"); // this format is string comparable
				$date_pre = date('Y-m-d', strtotime('-1 day', strtotime($date_now)));
				$date_week = date('Y-m-d', strtotime('+7 day', strtotime($date_now)));
				$selected_date = 0;
				if ($next_load_available_date_display != ''){
					$selected_date = date('Y-m-d', strtotime($next_load_available_date_display));
				}
				
				$cellClr = '';
				if( ($next_load_available_date_display == '' || $next_load_available_date_display == '00/00/0000') && $inv["after_actual_inventory"] >= 0 ){
					$cellClr = '';
				}else if( ($date_pre > $selected_date) && $selected_date > 0 ) {
					$cellClr = '#e8acac';
				}else {
					$cellClr = '';
				}
				//echo $MGArraytmp2["load_date_col_color"];
			?>
				<td class="form_label" width="100px" <?php echo $load_date_col_color;?>>
					<input size="10" type="text" id="ctrl_next_load_available_date<?php echo $count;?>" name="ctrl_next_load_available_date"  value="<?php echo $next_load_available_date_display; ?>">
					<a href="#" onclick="cal_load_available_date.select(document.frm_main.ctrl_next_load_available_date<?php echo $count;?>,'anchorload_available_date<?php echo $count;?>','yyyy-MM-dd'); return false;" name="anchorload_available_date" id="anchorload_available_date<?php echo $count;?>"><img border="0" src="images/calendar.jpg"></a>
					<div ID="listdiv_load_available_date" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
				</td>
			<?php } ?>
			<td class="form_label" width="100px">	
			<?php if($_REQUEST["box_type"] != "Gaylords"){ 
				echo $estimated_next_load;
			}else{
				echo get_lead_time_v3(6, $_REQUEST["box_id"], $leadtime,"");
			}?>
			</td>
			<td class="form_label" width="100px"><?php echo $boxes_pertrailer; ?></td>
			<td class="form_label" width="100px"><?php echo $b2b_fob; ?></td>
			<td class="form_label" width="100px"><?php echo $inv["I"]; ?></td>
			<?php 
			  $contact_nm = "";	$comm_log_color = "<font size=1>"; 
			  if ($inv["vendor_b2b_rescue"] > 0) {
				$comp_nm = ""; $comp_b2bid = ""; 				
				$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = " . $inv["vendor_b2b_rescue"];	
				db();
				$query = db_query($q1);				
				while($fetch = array_shift($query))				
				{					
					$comp_nm = $fetch['company_name'];					
					$comp_b2bid = $fetch['b2bid'];				
				}			
				$comp_nm = get_nickname_val($comp_nm, $comp_b2bid);
				$warehouse = $comp_nm;

				$last_contact_date = "";
				$q1 = "SELECT contact, phone, email, last_contact_date FROM companyInfo where ID = '" . $comp_b2bid . "'";	
				db_b2b();			
				$query = db_query($q1);				
				while($fetch = array_shift($query))				
				{					
					$contact_nm = $fetch['contact'] . "<br>" . $fetch['phone'] . "<br>" . $fetch['email'];				
					$last_contact_date = date("m/d/Y", strtotime($fetch["last_contact_date"]));
				}		

				if ($last_contact_date != ""){
					$todays_dt=date('m/d/Y');
					$comm_log_date_day = dateDiff($todays_dt, $last_contact_date);
					//echo $count . " cond 4 - " . $todays_dt . " " . $last_contact_date . " " . $comm_log_date_day . "<br>";
					if ($comm_log_date_day <= 30){
						$comm_log_color = "<font color=green>";
					}	
					if ($comm_log_date_day > 30 && $comm_log_date_day <= 90){
						$comm_log_color = "<font color=#FEDC56>";
					}	
					if ($comm_log_date_day > 90){
						$comm_log_color = "<font color=red>";
					}	
				}
			?>				
				<td class="form_label" width="150px"><font size=1><a href="viewCompany.php?ID=<?php echo $comp_b2bid; ?>" target="_blank"><?php echo $comp_nm; ?></a></font></td>
			<?php } else { 
			
				$vendor_b2b_rescue=$inv["V"];
				$q1 = "SELECT Name FROM vendors where id = '$vendor_b2b_rescue'";
				db_b2b();
				$query = db_query($q1);
				while($fetch = array_shift($query))
				{
					$vendor_name = $fetch["Name"];
				}
			
				$warehouse = $vendor_name;
			?>				
				<td class="form_label" width="150px"><font size=1><a href="addVendor.php?act=edit&id=<?php echo $inv["V"]; ?>" target="_blank"><?php echo $vendor_name; ?></a></font></td>	
			<?php } ?>						

			<!--<td class="form_label" width="200px"><font size=1><a href="manage_box_b2bloop.php?id=<?php //echo $inv["loops_id"]; ?>&proc=Edit&" target="_blank"><?php //=$inv["system_description"];?></a></td> -->
			<td class="form_label" width="200px">
				<font size=1><a href="manage_box_b2bloop.php?id=<?php echo $inv["loops_id"]; ?>&proc=Edit&" target="_blank"><?php echo $inv["system_description"];?></a>
				<br><br><div onClick="display_matching_tool_gaylords_v3(<?php echo $count;?>, 0,<?php echo $inv["I"];?>, 1, 1, 0)" style="cursor: pointer;">
					<u>Matching Tool v3.0 View</u>
				</div>
			</td>
			<td class="form_label" width="50px"><font size=1>
				<select name="b2b_status" id="b2b_status<?php echo $count;?>" style="width:150px;">
					<option value="">Select One</option>
					<?php
					$st_query="select * from b2b_box_status";
					db();
					$st_res = db_query($st_query);
					while ($st_row = array_shift($st_res)) {	
					?>
						<option <?php if ($st_row["status_key"] == $inv["b2b_status"] ) echo " selected "; ?>  value="<?php echo $st_row["status_key"]; ?>"><?php echo $st_row["box_status"]; ?></option>
					<?php
					}
					?>
				</select>			
			</td>
			<?php //if($_REQUEST["box_type"] == "Gaylords"){ ?>
				<td class="form_label"><span style="color:<?php echo $pic_color; ?>"><?php echo $no_pic; ?></span></td>
			<?php //} ?>
			<td class="form_label" width="250px"><textarea rows=2 cols=20 name="note" onkeypress='return chkcharacter(event);' id="note<?php echo $count;?>"><?php echo $inv["N"]; ?></textarea>
			<br><?php echo $notes_date_col_color; if ($inv["DT"] != "") echo timestamp_to_date_gy($inv["DT"]); ?></font>
				<a href="#" id="translog<?php echo $comp_b2bid; ?>" onclick="displaytrans_log(<?php echo $comp_b2bid; ?>,<?php echo $comp_b2bid; ?>); return false;"><font size=1>Show Log</font></a>		
			</td>
			<?php if($_REQUEST["box_type"] == "Gaylords"){ ?>
				<td class="form_label" width="250px"><textarea rows=2 cols=20 name="note" onkeypress='return chkcharacter(event);' id="flyer_note<?php echo $count;?>"><?php echo $flyer_notes ; ?></textarea>
			<?php } ?>
			<td class="form_label" width="200px">
				<?php if ($inv["vendor_b2b_rescue"] > 0) { 
				?>
				<table width="96%" border="0" cellspacing="1" cellpadding="1" class="sub_tables_s">
					<tr valign="top">
						<td >
							<input type=hidden name="companyID" id="companyID<?php echo $count;?>" value="<?php echo $comp_b2bid?>">
							<input type=hidden name="loopID" id="loopID<?php echo $count;?>" value="<?php echo $inv["vendor_b2b_rescue"]?>">
							<select size="1" name="type" id="type<?php echo $count;?>">
								<option>email</option>
								<option>phone</option>
								<option>fax</option>
								<option>meeting</option>
								<option>note</option>
								<option>other</option>
							</select>&nbsp;
						
							<textarea rows="5" name="message" id="message<?php echo $count;?>" cols="15"></textarea>
							<select size="1" name="employee" id="employee<?php echo $count;?>">
								<option><?php echo $_COOKIE["userinitials"]?></option>
								<?php
								$eq = "SELECT * FROM employees WHERE status='Active'";
								db_b2b();
								$dt_view_res1 = db_query($eq);
								while ($emp = array_shift($dt_view_res1)) {
								?>
									<option><?php echo $emp["initials"]?></option>
								<?php } ?>
							</select>
							&nbsp;
							<?php echo $comm_log_color;?>
							<?php echo $last_contact_date; ?>
							</font>
							&nbsp;
							<?php echo "<a href='#' onclick='displayemail(".$comp_b2bid.", " . $count ."); return false;'>Last Entry</a>"; ?>
							</font>
						</td>
					</tr>
				</table>
				<?php } ?>
			</td>
			<?php $b_type=$_REQUEST['box_type']; ?>				
			<td class="form_label" width="50px"><input type="button" value="Update" onclick="update_details(<?php echo $count; ?>,2, b_type)"></td>

			</tr>

			<?php	if ($sales_order_qty > 0) {?>			
				<tr id='inventory_preord_top_<?php echo $count;?>' align="middle" style="display:none;">
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
				  <td colspan="9" style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
						<div id="inventory_preord_middle_div_<?php echo $count;?>"></div>		
				  </td>
				</tr>
			<?php	} ?>

		</td>
		</tr>
		<?php
		$count++;
	} 
 ?>			