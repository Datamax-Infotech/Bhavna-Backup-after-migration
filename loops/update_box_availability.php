<?php

ini_set("display_errors", "1");
error_reporting(E_ERROR);
require_once("inc/header_session.php");
require_once("../mainfunctions/database.php");
require_once("../mainfunctions/general-functions.php");


if ($_REQUEST["note"] != "") {
	$arr_data = array(
		'companyID' => formatdata($_REQUEST['compid'] ?? ''),
		'notes' => formatdata($_REQUEST["note"] ?? ''),
		'box_b2b_id' => formatdata($_REQUEST["box_id"] ?? ''),
		'entry_emp' => formatdata($_REQUEST["txtemployee"] ?? ''),
		'entry_datetime' => date('Y-m-d H:i:s')
	);

	$query1 = make_insert_query('inventory_notes', $arr_data);
	db_b2b();
	db_query($query1);
}

//
db();
db_query("UPDATE loop_transaction_buyer SET box_availability_update = 1 WHERE id=" . $_REQUEST["trans_id"]);
//

$next_load_date = date('Y-m-d', strtotime($_REQUEST["next_load_date"]));
$compid = $_REQUEST['compid'];
$modify_emp = $_COOKIE['userinitials'];
$modify_datetime = date('Y-m-d H:i:s');
//
$qry_b = "select * from loop_boxes where b2b_id = ?";
$dt_view_b = db_query($qry_b, array("i"), array($_REQUEST['box_id']));
$box_res = array_shift($dt_view_b);
$b2b_status = $box_res["b2b_status"];
$loop_id = $box_res['id'];
$boxb2b_id = $box_res['b2b_id'];
$next_load_available_date = $box_res["next_load_available_date"];
$txt_after_po = $box_res["after_po"];
$expected_loads_per_mo = $box_res["expected_loads_per_mo"];
$boxes_per_trailer = $box_res["boxes_per_trailer"];

//
db_b2b();
$qry_i = "select * from inventory where ID =?";
$dt_view_i = db_query($qry_i, array("i"), array($_REQUEST['box_id']));
$res_i = array_shift($dt_view_i);
$notes = preg_replace("(\n)", "<BR>", $res_i["notes"]);
$notes = str_replace("'", "\'", $notes);
//
$new_notes = preg_replace("/'/", "\'", $_REQUEST["note"]);


//save log----------------
if ($_REQUEST["after_actual_inventory"] != $txt_after_po) {
	$mod_box_notes .= "Qty Available Now changed from \' " . $txt_after_po . "\' to \'" . $_REQUEST["after_actual_inventory"] . "\'<br>";
}
//
if ($_REQUEST["expected_loads_per_mo"] != $expected_loads_per_mo) {
	$mod_box_notes .= "Expected # of Loads/Mo changed from \' " . $expected_loads_per_mo . "\' to \'" . $_REQUEST["expected_loads_per_mo"] . "\'<br>";
}
if ($_REQUEST["b2b_status"] != $b2b_status) {
	$mod_box_notes .= "Box Status changed from \' " . $b2b_status . "\' to \'" . $_REQUEST["b2b_status"] . "\'<br>";
}
if ($next_load_date != $next_load_available_date) {
	$mod_box_notes .= "Next Load Available Date changed from \' " . $next_load_available_date . "\' to \'" . $next_load_date . "\'<br>";
}
if ($new_notes != $notes) {
	$mod_box_notes .= "Internal Notes changed from \' " . $notes . "\' to \'" . $new_notes . "\'<br>";
}
//
if ($mod_box_notes != "") {

	db();
	$mod_qry = "insert into inventory_modified_log (loop_box_id, b2b_box_id, company_id, modify_emp, modify_datetime, notes) values ('$loop_id', '$boxb2b_id', '$compid', '$modify_emp', '$modify_datetime', '$mod_box_notes')";
	$modi_res = db_query($mod_qry);
}
//
//----------------End Update inventory log------------------------------------
//

db_b2b();
db_query("UPDATE inventory SET after_actual_inventory = '" . $_REQUEST["after_actual_inventory"] . "' WHERE ID=" . $_REQUEST["box_id"]);

db_query("UPDATE inventory SET expected_loads_per_mo = '" . $_REQUEST["expected_loads_per_mo"] . "' WHERE ID=" . $_REQUEST["box_id"]);

db_query("UPDATE inventory SET b2b_status = '" . $_REQUEST["b2b_status"] . "', next_load_available_date = '" . $_REQUEST["next_load_date"] . "' WHERE ID=" . $_REQUEST["box_id"]);

db_query("UPDATE inventory SET notes = '" . preg_replace("/'/", "\'", $_REQUEST["note"]) . "', date = '" . date("Y-m-d") . "' WHERE ID=" . $_REQUEST["box_id"]);

db_query("TRUNCATE boxesGaylordList");


db();
db_query("UPDATE loop_boxes SET b2b_status = '" . $_REQUEST["b2b_status"] . "', next_load_available_date = '" . $_REQUEST["next_load_date"] . "', after_po = '" . $_REQUEST["after_actual_inventory"] . "', expected_loads_per_mo = '" . $_REQUEST["expected_loads_per_mo"] . "' WHERE b2b_id =" . $_REQUEST["box_id"]);


if ($_REQUEST["next_load_date"] != "") {
	db();
	$sql = "SELECT virtual_inventory_company_id FROM loop_transaction_buyer where id = ?";
	$dt_view_res = db_query($sql, array("i"), array($_REQUEST['trans_id']));
	while ($resdata = array_shift($dt_view_res)) {
		$virtual_inventory_company_id = $resdata["virtual_inventory_company_id"];
	}
}



if ($_REQUEST['company_id'] != "") {
	$arr_data = array(
		'box_id' => formatdata($_REQUEST["box_id"]),

		'company_id' => formatdata($_REQUEST["company_id"]),

		'status' => formatdata("W")
	);

	$query1 = make_insert_query('loop_waiting_list', $arr_data);
	db();
	db_query($query1);
}

$count = $_REQUEST["cnt"];
db_b2b();
$dt_view_qry_upd = "SELECT *, inventory.ID AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE inventory.ID = " . $_REQUEST["box_id"] . " AND inventory.Active LIKE 'A' ORDER BY inventory.availability DESC";
$dt_view_res_upd = db_query($dt_view_qry_upd);
while ($inv = array_shift($dt_view_res_upd)) {

	$sales_order_qty = 0;
	$transaction_date = "";

	$b2b_status = "";
	db();
	$dt_res_so_item1 = db_query("select * from b2b_box_status where status_key='" . $inv["b2b_status"] . "'");
	while ($so_item_row1 = array_shift($dt_res_so_item1)) {
		$b2b_status = $so_item_row1["box_status"];
	}

	$vendor_name = "";
	$supplier_owner_name = "";

	db_b2b();
	$comqry1 = "select employeeID,employees.name as empname, employees.initials from employees where employeeID=?";
	$comres1 = db_query($comqry1, array("i"), array($inv['supplier_owner']));
	while ($comrow1 = array_shift($comres1)) {
		$supplier_owner_name = $comrow1["initials"];
	}

	$next_load_available_date = $inv["next_load_available_date"];
	$next_load_available_date_display = "";
	if ($next_load_available_date != "" && $next_load_available_date != "0000-00-00") {
		$next_load_available_date_display = date("m/d/Y", strtotime($inv["next_load_available_date"]));
	}

	$b2b_ulineDollar = round($inv["ulineDollar"]);
	$b2b_ulineCents = $inv["ulineCents"];
	$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
	$b2b_fob = "$" . number_format($b2b_fob, 2);

	$lead_time = $inv["lead_time"];
	$boxes_per_trailer = $inv["boxes_per_trailer"];
	$txt_after_po = $inv["after_po"];
	$warehouse_id = $inv["box_warehouse_id"];

	$rec_found_box = "n";
	$after_po_val_tmp = 0;
	$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where trans_id = " . $inv["loops_id"] . " order by warehouse, type_ofbox, Description";
	$dt_view_res_box = db_query($dt_view_qry);
	while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
		$rec_found_box = "y";
		$after_po_val_tmp = $dt_view_res_box_data["afterpo"];
	}

	if ($warehouse_id == 238) {
		$txt_after_po = $inv["after_po"];
	} else {
		if ($rec_found_box == "n") {
			$txt_after_po = $inv["after_po"];
		} else {
			$txt_after_po = $after_po_val_tmp;
		}
	}

	$order_col_color = "<font size=1>";
	if ($transaction_date != "" && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00")) {
		if (strtotime($transaction_date) < strtotime($next_load_available_date)) {
			$order_col_color = "<font color=red>";
		}
	}

	$load_date_col_color = "style='font-size:10pt;'";
	$load_date_col_color2 = "";
	if (($inv["b2b_status"] == '1.0' || $inv["b2b_status"] == '1.1' || $inv["b2b_status"] == '1.2') && ($next_load_available_date == "" || $next_load_available_date == "0000-00-00")) {
		$load_date_col_color = "style='font-size:10pt;width:50px;background-color:#e8acac;'";
		$load_date_col_color2 = "</span>";
	}

	$notes_date_col_color = "<font size=1>";
	if ($inv["DT"] != "") {
		$todays_dt = date('m/d/Y');
		$notes_date_day = dateDiff($todays_dt, date('m/d/Y', strtotime($inv["DT"])));

		if ($notes_date_day <= 7) {
			$notes_date_col_color = "<font color=green>";
		}
		if ($notes_date_day > 7 && $notes_date_day <= 14) {
			$notes_date_col_color = "<font color=#FEDC56>";
		}
		if ($notes_date_day > 14) {
			$notes_date_col_color = "<font color=red>";
		}
	}
	//
	$sku = "";
	db();
	$qry_sku = "select id, boxes_per_trailer from loop_boxes where id= ?";
	$dt_view_sku = db_query($qry_sku, array("i"), array($inv['loops_id']));
	while ($sku_val = array_shift($dt_view_sku)) {
		$loop_id = $sku_val['id'];
		$boxes_pertrailer = $sku_val['boxes_per_trailer'];
	}
	//
	$estimated_next_load = "";
	//Buy Now, Load Can Ship In
	if ($warehouse_id == 238 && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00")) {
		$now_date = time(); // or your date as well
		$next_load_date = strtotime($next_load_available_date);
		$datediff = $next_load_date - $now_date;
		$no_of_loaddays = round($datediff / (60 * 60 * 24));
	}

	$getEstimatedNextLoad = getEstimatedNextLoad($inv["loops_id"], $warehouse_id, $next_load_available_date, $lead_time, $inv["lead_time"], $txt_after_po, $boxes_per_trailer, $inv["expected_loads_per_mo"], $inv["b2b_status"], 'yes');
	$estimated_next_load = $getEstimatedNextLoad;

?>

	<tr id="gaylord_div<?php echo $count; ?>">
		<td height="13" class="style1 new_sty" align="left">
			<?php echo $count; ?><input type=hidden name="box_id" id="box_id<?php echo $count; ?>" value="<?php echo $inv["I"]; ?>"></td>
		<td height="13" class="style1 new_sty" align="left">
			<?php echo $supplier_owner_name; ?></td>
		<td height="13" class="style1 new_sty" align="left">
			<?php echo $order_col_color; ?>
			<?php
			if ($sales_order_qty > 0) { ?>
				<div onclick="display_preoder_sel(<?php echo $count; ?>, <?php echo $sales_order_qty; ?>, <?php echo $inv['loops_id']; ?>, <?php echo $inv['vendor_b2b_rescue']; ?>)" style="cursor: pointer;">
					<u>View<?php echo $sales_order_qty; ?></u>
				</div>
			<?php
			} else {
				echo "";
			}
			?>
		</td>
		<td height="13" class="style1 new_sty" align="left">
			<input type="text" id="after_actual_inventory<?php echo $count; ?>" name="after_actual_inventory" value="<?php echo $inv["after_actual_inventory"]; ?>" size="5" style="font-size: x-small;">
		</td>
		<td height="13" class="style1 new_sty" align="left">
			<input type="text" id="expected_loads_per_mo<?php echo $count; ?>" name="expected_loads_per_mo" value="<?php echo $inv["expected_loads_per_mo"]; ?>" size="5" style="font-size: x-small;">
		</td>
		<td height="13" class="style1 new_sty" align="left" <?php echo $load_date_col_color; ?>>
			<input size="10" type="text" id="ctrl_next_load_available_date<?php echo $count; ?>" name="ctrl_next_load_available_date" value="<?php echo $next_load_available_date_display; ?>" style="font-size: x-small;">
			<a href="#" onclick="cal_load_available_date.select(document.frm_main.ctrl_next_load_available_date<?php echo $count; ?>,'anchorload_available_date<?php echo $count; ?>','yyyy-MM-dd'); return false;" name="anchorload_available_date" id="anchorload_available_date<?php echo $count; ?>"><img border="0" src="images/calendar.jpg"></a>
			<div ID="listdiv_load_available_date" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
		</td>
		<td height="13" class="style1 new_sty" align="left">
			<?php echo $estimated_next_load; ?></td>
		<td height="13" class="style1 new_sty" align="left">
			<?php echo $boxes_pertrailer; ?></td>
		<td height="13" class="style1 new_sty" align="left">
			<?php echo $b2b_fob; ?></td>
		<td height="13" class="style1 new_sty" align="left">
			<?php echo $inv["I"]; ?></td>
		<?php
		$contact_nm = "";
		$comm_log_color = "<font size=1>";
		if ($inv["vendor_b2b_rescue"] > 0) {
			$comp_nm = "";
			$comp_b2bid = "";
			db();
			$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = ?";
			$query = db_query($q1, array("i"), array($inv['vendor_b2b_rescue']));
			while ($fetch = array_shift($query)) {
				$comp_nm = $fetch['company_name'];
				$comp_b2bid = $fetch['b2bid'];
			}
			$comp_nm = get_nickname_val($comp_nm, $comp_b2bid);
			$warehouse = $comp_nm;

			$last_contact_date = "";
			db_b2b();
			$q1 = "SELECT contact, phone, email, last_contact_date FROM companyInfo where ID =?";
			$query = db_query($q1, array("i"), array($comp_b2bid));
			while ($fetch = array_shift($query)) {
				$contact_nm = $fetch['contact'] . "<br>" . $fetch['phone'] . "<br>" . $fetch['email'];
				$last_contact_date = date("m/d/Y", strtotime($fetch["last_contact_date"]));
			}

			if ($last_contact_date != "") {
				$todays_dt = date('m/d/Y');
				$comm_log_date_day = dateDiff($todays_dt, $last_contact_date);

				if ($comm_log_date_day <= 30) {
					$comm_log_color = "<font color=green>";
				}
				if ($comm_log_date_day > 30 && $comm_log_date_day <= 90) {
					$comm_log_color = "<font color=#FEDC56>";
				}
				if ($comm_log_date_day > 90) {
					$comm_log_color = "<font color=red>";
				}
			}
		?>
			<td class="style1 new_sty" width="150px">
				<font size=1><a href="viewCompany.php?ID=<?php echo $comp_b2bid; ?>" target="_blank"><?php echo $comp_nm; ?></a></font>
			</td>
		<?php } else {

			$vendor_b2b_rescue = $inv["V"];
			db_b2b();
			$q1 = "SELECT Name FROM vendors where id =?";
			$query = db_query($q1, array("i"), array($vendor_b2b_rescue));
			while ($fetch = array_shift($query)) {
				$vendor_name = $fetch["Name"];
			}

			$warehouse = $vendor_name;
		?>
			<td class="style1 new_sty" width="150px">
				<font size=1><a href="addVendor.php?act=edit&id=<?php echo $inv["V"]; ?>" target="_blank"><?php echo $vendor_name; ?></a></font>
			</td>
		<?php } ?>

		<td height="13" class="style1 new_sty" align="left">
			<a href="manage_box_b2bloop.php?id=<?php echo $inv["loops_id"]; ?>&proc=Edit&" target="_blank"><?php echo $inv["system_description"]; ?></a>
		</td>
		<td height="13" class="style1 new_sty" align="left">
			<select name="b2b_status" id="b2b_status<?php echo $count; ?>" style="width:100px; font-size: x-small;">
				<option value="">Select One</option>
				<?php
				db();
				$st_query = "select * from b2b_box_status";
				$st_res = db_query($st_query);
				while ($st_row = array_shift($st_res)) {
				?>
					<option <?php if ($st_row["status_key"] == $inv["b2b_status"]) echo " selected "; ?> value="<?php echo $st_row["status_key"]; ?>"><?php echo $st_row["box_status"]; ?></option>
				<?php
				}
				?>
			</select>
		</td>
		<td height="13" class="style1 new_sty" align="left">
			<textarea name="note" id="note<?php echo $count; ?>" style="font-size: x-small;"><?php echo $inv["N"]; ?></textarea>
			<?php echo $notes_date_col_color;
			if ($inv["DT"] != "") echo timestamp_to_date($inv["DT"]); ?></font>
			<a href="#" id="translog<?php echo $inv["I"]; ?>" onclick="displaytrans_log(<?php echo $inv['I']; ?>,<?php echo $inv['I']; ?>); return false;">
				<font size=1>Show Log</font>
			</a>

		</td>
		<td class="style1 new_sty" width="50px">
			<input type=hidden name="companyID" id="companyID<?php echo $count; ?>" value="<?php echo $comp_b2bid; ?>">
			<input type=hidden name="loopID" id="loopID<?php echo $count; ?>" value="<?php echo $inv["vendor_b2b_rescue"]; ?>">
			<input type="button" value="Update" onclick="update_details(<?php echo $count; ?>,2)">
		</td>
	</tr>

	<?php if ($sales_order_qty > 0) { ?>
		<tr id='inventory_preord_top_<?php echo $count; ?>' align="middle" style="display:none;">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="9" style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
				<div id="inventory_preord_middle_div_<?php echo $count; ?>"></div>
			</td>
		</tr>
	<?php 	} ?>

	</td>
	</tr>
<?php
	$count++;
}
?>