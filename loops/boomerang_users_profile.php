<?php
require("inc/header_session.php");
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

function encrypt_password_new(string $txt): string
{
    $key = 'hastb47#1skdsjh';
    $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($txt, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
    $ciphertext = base64_encode($iv . $hmac . $ciphertext_raw);
    return $ciphertext;
}

$user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
//echo "<br /> buyer_seller_flg - ".$buyer_seller_flg;
db();
$res = db_query("Select activate_deactivate from boomerang_user_section_details where user_id = $user_id and section_id = 6 and activate_deactivate = 1");
$close_inv_flg = 0;
while ($fetch_data = array_shift($res)) {
    $close_inv_flg = 1;
}
db();
$res = db_query("Select activate_deactivate from boomerang_user_section_details where user_id = $user_id and section_id = 7 and activate_deactivate = 1");
$setup_hide_flg = 0;
while ($fetch_data = array_shift($res)) {
    $setup_hide_flg = 1;
}

db();
$res = db_query("Select activate_deactivate from boomerang_user_section_details where user_id = $user_id and section_id = 8 and activate_deactivate = 1");
$setup_boxprofile_inv_flg = 0;
while ($fetch_data = array_shift($res)) {
    $setup_boxprofile_inv_flg = 1;
}


?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Boomerang Portal Setup</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <style type="text/css">
        .tooltip {
            position: relative;
            display: inline-block;
        }

        .fa-info-circle {
            font-size: 9px;
            color: #767676;
        }

        .fa {
            display: inline-block;
            font: normal normal normal 14px/1 FontAwesome;
            font-size: inherit;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 250px;
            background-color: #464646;
            color: #fff;
            text-align: left;
            border-radius: 6px;
            padding: 5px 7px;
            position: absolute;
            z-index: 1;
            top: -5px;
            left: 110%;
            /* white-space: nowrap; */
            font-size: 12px;
            font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif !important;
        }

        .tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            top: 35%;
            right: 100%;
            margin-top: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: transparent black transparent transparent;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
        }

        .tooltip_large {
            position: relative;
            display: inline-block;
        }

        .tooltip_large .tooltiptext_large {
            visibility: hidden;
            width: 400px;
            background-color: #464646;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 7px;
            position: absolute;
            z-index: 1;
            top: -5px;
            left: 110%;
        }

        .tooltip_large .tooltiptext_large::after {
            content: "";
            position: absolute;
            top: 10%;
            right: 100%;
            margin-top: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: transparent black transparent transparent;
        }

        .tooltip_large:hover .tooltiptext_large {
            visibility: visible;
        }

        /*right tip*/

        .tooltip_right {
            position: relative;
            display: inline-block;
        }

        .tooltip_right .tooltiptext_right {
            visibility: hidden;
            width: 250px;
            background-color: black;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 7px;
            position: absolute;
            z-index: 1;
            top: -5px;
            right: 110%;
            font-size: 11px;
        }

        .tooltip_right .tooltiptext_right::after {
            content: " ";
            position: absolute;
            top: 30%;
            left: 100%;
            /* To the right of the tooltip */
            margin-top: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: transparent transparent transparent black;
        }

        .tooltip_right:hover .tooltiptext_right {
            visibility: visible;
        }

        /*--------*/

        .fa-info-circle {
            font-size: 9px;
            color: #767676;
        }

        .white_content {
            display: none;
            position: absolute;
            padding: 5px;
            border: 2px solid black;
            background-color: white;
            z-index: 1002;
            overflow: auto;
        }

        .textbox-label {
            background: transperant;
            border: none;
            width: 300px;
            min-width: 90px;
            max-width: 300px;
            transition: width 0.25s;
        }

        .color_red {
            color: red;
        }

        .hide_error {
            display: none;
        }

        .table_boomerang_portal {
            width: 85%;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12;
            border: none;
            background-color: #F6F8E5;
            margin: 0px auto;
        }

        .align_center {
            text-align: center;
        }

        .bg_C1C1C1 {
            background-color: #C1C1C1;
        }

        .tbl_border,
        .tbl_border td,
        .tbl_border tr {
            border: solid 1px #C8C8C8;
            border-collapse: collapse;
        }

        .card p {
            margin-bottom: 0px;
        }

        .card .card-body {
            padding: .5rem;
        }
    </style>
    <LINK rel='stylesheet' type='text/css' href='one_style.css'>
    <link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <link rel='stylesheet' type='text/css' href='js_new/bootstrap.min.css'>
    <script src="js_new/jquery.min.js"></script>
    <script src="js_new/bootstrap.min.js"></script>

</head>
<?php include("inc/header.php"); ?>
<div class="main_data_css">
    <div style="height: 13px;">&nbsp;</div>
    <select multiple name="companies[]" id="all_companies" style="display:none; ">
        <option>Select Companies</option>
        <?php
        db_b2b();
        $select_sales_comp = db_query("SELECT ID, company FROM companyInfo where haveNeed = 'Need Boxes' and active = 1 and company <>'' and loopid > 0 ORDER BY nickname");
        while ($row = array_shift($select_sales_comp)) {
            echo "<option value='" . $row['ID'] . "'>" . get_nickname_val($row['company'], $row["ID"]) . "</option>";
        }

        ?>
    </select>
    <div style="border-bottom: 1px solid #C8C8C8; padding-bottom: 10px;">
        <img src="images/boomerang-logo.jpg" alt="moving boxes"> &nbsp;&nbsp; &nbsp;&nbsp;
        <a target="_blank" href="https://boomerang.usedcardboardboxes.com/index.php?param1=<?php echo urlencode(encrypt_password_new($user_id)); ?>&repchk=yes">
            See User's portal</a>
        <span class="color_red">*Do NOT give this link out to customers! It is a "back door" to the portal ONLY FOR
            YOU!</span>
    </div>
    <div id="light" class="white_content"> </div>
    <table class="table_boomerang_portal">
        <tr>
            <td colspan="6" style="background:#E8EEA8; text-align:center"><strong>Boomerang Portal User Setup</strong>
                </font>
                &nbsp;<a href="boomerang_users_setup.php">Close</a>
            </td>
        </tr>
        <tr>
            <td colspan="6" class="bg_C1C1C1 align_center">User Profile</td>
        </tr>
        <tr>
            <td colspan="6">
                <div id="manager_dropdown_hidden" style="display: none">
                    <select name="manager" class="manager_ids">
                        <option>Select Manager</option>
                        <?php
                        db();
                        $select_sales_comp = db_query("SELECT id,username FROM loop_employees");
                        while ($row = array_shift($select_sales_comp)) {
                        ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['username']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <form class='save_user'>
                <table class="tbl_border" style="width: 100%">
                    <tr>
                        <td>First Name</td>
                        <td>Last Name</td>
                        <td>Company Name</td>
                        <td>Email</td>
                        <td>Phone</td>
                        <td>Manager</td>
                        <td>Status</td>
                        <td>Blocked</td>
                        <td>Action</td>
                    </tr>
                    <?php
                    db();
                    //echo "SELECT * FROM boomerang_usermaster where loginid = '" . $user_id . "'";
                    $select_users = db_query("SELECT boomerang_usermaster.*,loop_employees.username FROM boomerang_usermaster JOIN loop_employees ON loop_employees.id = boomerang_usermaster.manager_id  where loginid = '" . $user_id . "'");
                    $row = array_shift($select_users);
                    echo "<tr id='userrowid_" . $row['loginid'] . "'>
									<td>" . $row['user_name'] . "</td>
									<td>" . $row['user_last_name'] . "</td>
									<td>" . $row['user_company'] . "</td>
									<td>" . $row['user_email'] . "</td>
									<td>" . $row['user_phone'] . "</td>
                                    <td>" . $row['username'] . "</td>
									<td>" . ($row['user_status'] == 1 ? 'Active' : 'Inactive') . "</td>
									<td>" . ($row['user_block'] == 0 ? 'Unblocked' : 'Blocked') . "</td>
									<td>
										<button type='button' user_id='" . $row['loginid'] . "' class='edit_user'>Edit</button>
										<button type='button' user_id='" . $row['loginid'] . "' user_status='" . $row['user_status'] . "' class='delete_user'>" . ($row['user_status'] == 1 ? 'Mark as Inactive' : 'Unmark as Inactive') . "</button>
									</td>	
									</tr>";
                    ?>
                </table>
                    </form>

                <table class="tbl_border" id="tbl_comp_id" style="width: 50%">
                    <tr>
                        <td colspan="11" style="background: #FFF">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="bg_C1C1C1 align_center">Related Company Records</td>
                    </tr>

                    <tr>
                        <td colspan=2>
                            <input type="text" id="comp_b2bid" name="comp_b2bid" placeholder="Enter Company B2B ID">
                            <button type="button" user_id="<?php echo $user_id; ?>" class='add_comp_b2bid'>Add B2B
                                IDs</button>
                        </td>
                    </tr>

                    <tr>
                        <td style="width:50px;">B2B ID</td>
                        <td style="width:120px;">Company Nickname</td>
                        <td style="width:50px;">&nbsp;</td>
                    </tr>
                    <?php
                    db();
                    $select_user_companies = db_query("SELECT id, company_id FROM boomerang_user_companies where user_id = '" . $user_id . "'");
                    $company_list = "";
                    while ($row1 = array_shift($select_user_companies)) {
                        db_b2b();
                        $company_name = db_query("SELECT company, ID FROM companyInfo where ID = '" . $row1['company_id'] . "'");
                        $company_name_row = array_shift($company_name);
                        echo "<tr >
							  <td style='width:50px !important;'>" . $row1['company_id'] . "</td>
							  <td style='width:150px !important;'><a target='_blank' href='viewCompany.php?ID=" . $company_name_row["ID"] . "'>" . get_nickname_val($company_name_row['company'], $company_name_row["ID"]) . "</a></td>
							  <td style='width:50px !important;'> <button type='button' compuser_id='" . $user_id . "' comp_id_to_remove='" . $row1['id'] . "' comp_b2bid_to_remove='" . $row1['company_id'] . "' class='remove_compid'>Remove</button> </td>
							  </tr>";
                    }
                    ?>
                </table>

                <br><br>

                <table class="tbl_border" style="width: 50%">
                    <tr>
                        <td colspan="11" class="bg_C1C1C1 align_center">User Address</td>

                    <tr>
                        <td>

                            <div class="container-fluid mb-4">
                                <div class="row justify-content-start">
                                    <div class="col-md-12">
                                        <div class="text-right mb-3 mt-3">
                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addAddressModal">Add Address</button>
                                        </div>
                                        <?php
                                        db();
                                        $address_qry = db_query("SELECT * FROM boomerang_user_addresses WHERE status = 1 && user_id = '" . $user_id . "' ORDER BY id DESC");
                                        if (tep_db_num_rows($address_qry) > 0) {
                                            while ($address = array_shift($address_qry)) { ?>
                                                <div class="card mt-3 <?php echo $address['mark_default'] == 1 ? "border-success" : ""; ?>" style="background-color:#F6F8E5;">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <p><b>Name:</b>
                                                                    <?php echo $address['first_name'] . " " . $address['last_name'] ?>
                                                                </p>
                                                                <p><b>Company:</b> <?php echo $address['company'] ?></p>
                                                                <p><b>Address: </b><?php echo $address['addressline1'] ?></p>
                                                                <p><?php echo $address['addressline2'] ?></p>
                                                                <p><?php echo $address['city'] ?>,
                                                                    <?php echo $address['state'] ?>,
                                                                    <?php echo $address['zip'] ?></p>
                                                                <p><b>Country:</b> <?php echo $address['country'] ?></p>
                                                                <p><b>Mobile No:</b> <?php echo $address['mobile_no'] ?></p>
                                                                <p><b>Email:</b> <?php echo $address['email'] ?></p>
                                                                <p><b>Dock Hours:</b> <?php echo $address['dock_hours'] ?></p>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <a href="javascript:void(0);" address_id="<?php echo $address['id']; ?>" class="edit_address_btn text-primary"><span class="fa fa-pencil"></span> Edit</a><br>
                                                                <a href="javascript:void(0);" address_id="<?php echo $address['id']; ?>" class="delete_address_btn text-danger"><span class="fa fa-trash"></span> Delete</a><br>
                                                                <?php if ($address['mark_default'] == 1) {
                                                                    echo "<span class='text-success'><span class='fa fa-check'></span>Default Address</span>";
                                                                } else { ?>
                                                                    <a href="javascript:void(0);" address_id="<?php echo $address['id']; ?>" add_frm_user_id="<?php echo $user_id; ?>" class="text-success mark_default_add"><span class="fa fa-map-pin"></span> Set As Default</a>
                                                                <?php } ?>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                        <?php }
                                        } else {
                                            echo "<div class='card'><div class='card-body'><p class='text-danger'>You dont have any address added!</p></div></div>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                </table>



            </td>
        </tr>
        <tr>
            <td colspan="6" style="background:while;"></td>
        </tr>
        <!--Favorite Inventory section start  -->
        <?php
        if (isset($_REQUEST['favremoveflg']) && $_REQUEST['favremoveflg'] == "yes") {

            db();
            db_query("Delete from b2b_inventory_gaylords_favorite WHERE id =" . $_REQUEST['favitemid']);
        }
        ?>
        <tr align="center">
            <td colspan="6" width="320px" align="center" bgcolor="#C1C1C1"><b>Favorite Inventory</b></td>
        </tr>
        <?php
        db();

        ?>
        <tr>
            <td colspan="6">
                <form name="frmFavItems" method="post" action="boomerange_users_profile.php?user_id=<?php echo $user_id; ?>">
                    <table width="100%" cellspacing="1" cellpadding="1" border="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12; border: 1px solid #cccccc;">
                        <?php
                        db();
                        //$selFavData = db_query("SELECT * FROM boomerange_inventory_gaylords_favorite WHERE user_id = '" . $user_id . "' and fav_status = 1 ORDER BY id DESC");
                        $selFavData = db_query("SELECT * FROM b2b_inventory_gaylords_favorite WHERE user_id = '" . $user_id . "' and fav_status = 1 ORDER BY id DESC");
                        //echo "<pre>";print_r($selFavData);echo "</pre>";
                        $selFavDataCnt = tep_db_num_rows($selFavData);
                        if ($selFavDataCnt > 0) {
                        ?>
                            <tr bgcolor="#E4D5D5">
                                <td class='display_title'>&nbsp;</td>
                                <td class='display_title'>Buy Now</td>
                                <td class='display_title'>Qty Avail</td>
                                <td class='display_title'>Buy Now, Load Can Ship In</td>
                                <td class='display_title'>Expected # of Loads/Mo</td>
                                <td class='display_title'>Per Truckload</td>
                                <td class='display_title'>MIN FOB</td>
                                <td class='display_title'>B2B ID</td>
                                <td align="center" class='display_title'>B2B Status</td>
                                <td align="center" class='display_title'>L</td>
                                <td align="center" class='display_title'>x</td>
                                <td align="center" class='display_title'>W</td>
                                <td align="center" class='display_title'>x</td>
                                <td align="center" class='display_title'>H</td>
                                <td class='display_title'>Description</td>
                                <td class='display_title'>Supplier</td>
                                <td class='display_title'>Ship From</td>
                                <td class='display_title'>Supplier Relationship Owner</td>
                                <td class=''>Box Type</td>
                            </tr>
                            <?php
                            $i = 0;
                            while ($rowsFavData = array_shift($selFavData)) {
                                $after_po_val_tmp = 0;
                                $after_po_val = 0;
                                $pallet_val_afterpo = $preorder_txt2 = "";
                                $rec_found_box = "n";
                                $boxes_per_trailer = 0;
                                $next_load_available_date = "";
                                if ($rowsFavData['b2b_id'] > 0) {
                                    db();
                                    $selBoxDt = db_query("SELECT * FROM loop_boxes WHERE b2b_id = " . $rowsFavData['b2b_id']);
                                    //echo "<pre> selBoxDt ->";print_r($selBoxDt);echo "</pre>";
                                    $rowBoxDt = array_shift($selBoxDt);

                                    if ($rowBoxDt['b2b_id'] > 0) {
                                        db_b2b();
                                        $selInvDt = db_query("SELECT * FROM inventory WHERE ID = " . $rowBoxDt['b2b_id']);
                                        $rowInvDt = array_shift($selInvDt);

                                        $box_type = $rowInvDt['box_type'];
                                        $box_warehouse_id = $rowBoxDt["box_warehouse_id"];
                                        $next_load_available_date = $rowBoxDt["next_load_available_date"];
                                        $boxes_per_trailer = $rowBoxDt['boxes_per_trailer'];
                                        if ($rowInvDt["loops_id"] > 0) {
                                            $dt_view_qry = "SELECT * FROM tmp_inventory_list_set2 WHERE trans_id = " . $rowInvDt["loops_id"] . " ORDER BY warehouse, type_ofbox, Description";
                                            db_b2b();
                                            $dt_view_res_box = db_query($dt_view_qry);
                                            while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
                                                $rec_found_box = "y";
                                                $actual_val = $dt_view_res_box_data["actual"];
                                                $after_po_val_tmp = $dt_view_res_box_data["afterpo"];
                                                $last_month_qty = $dt_view_res_box_data["lastmonthqty"];
                                            }
                                            if ($rec_found_box == "n") {
                                                $actual_val = $rowInvDt["actual_inventory"];
                                                $after_po_val = $rowInvDt["after_actual_inventory"];
                                                $last_month_qty = $rowInvDt["lastmonthqty"];
                                            }
                                            if ($box_warehouse_id == 238) {
                                                $after_po_val = $rowInvDt["after_actual_inventory"];
                                            } else {
                                                $after_po_val = $after_po_val_tmp;
                                            }

                                            $to_show_rec = "y";

                                            $ownername = "";
                                            if ($to_show_rec == "y") {
                                                $vendor_name = "";
                                                //account owner
                                                if ($rowInvDt["vendor_b2b_rescue"] > 0) {

                                                    $vendor_b2b_rescue = $rowInvDt["vendor_b2b_rescue"];
                                                    $q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
                                                    db();
                                                    $query = db_query($q1);
                                                    while ($fetch = array_shift($query)) {
                                                        $vendor_name = get_nickname_val($fetch["company_name"], $fetch["b2bid"]);

                                                        $comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
                                                        db_b2b();
                                                        $comres = db_query($comqry);
                                                        while ($comrow = array_shift($comres)) {
                                                            $ownername = $comrow["initials"];
                                                        }
                                                    }
                                                } else {
                                                    $vendor_b2b_rescue = $rowInvDt["V"];
                                                    if ($vendor_b2b_rescue != "") {
                                                        $q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
                                                        db_b2b();
                                                        $query = db_query($q1);
                                                        while ($fetch = array_shift($query)) {
                                                            $vendor_name = $fetch["Name"];

                                                            $comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
                                                            db_b2b();
                                                            $comres = db_query($comqry);
                                                            while ($comrow = array_shift($comres)) {
                                                                $ownername = $comrow["initials"];
                                                            }
                                                        }
                                                    }
                                                }
                                            }


                                            if ($after_po_val < 0) {
                                                $qty = number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2;
                                            } else if ($after_po_val >= $boxes_per_trailer) {
                                                $qty = number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2;
                                            } else {
                                                $qty = number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2;
                                            }


                                            $estimated_next_load = "";
                                            $b2bstatuscolor = "";
                                            if ($box_warehouse_id == 238 && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00")) {
                                                $now_date = time(); // or your date as well
                                                $next_load_date = strtotime($next_load_available_date);
                                                $datediff = $next_load_date - $now_date;
                                                $no_of_loaddays = round($datediff / (60 * 60 * 24));
                                                //echo $no_of_loaddays;
                                                if ($no_of_loaddays < $lead_time) {
                                                    if ($rowInvDt["lead_time"] > 1) {
                                                        $estimated_next_load = "<font color=green> " . $rowInvDt["lead_time"] . " Days </font>";
                                                    } else {
                                                        $estimated_next_load = "<font color=green> " . $rowInvDt["lead_time"] . " Day </font>";
                                                    }
                                                } else {
                                                    $estimated_next_load = "<font color=green> " . $no_of_loaddays . " Days </font>";
                                                }
                                            } else {
                                                if ($after_po_val >= $boxes_per_trailer) {
                                                    if ($rowInvDt["lead_time"] == 0) {
                                                        $estimated_next_load = "<font color=green> Now </font>";
                                                    }
                                                    if ($rowInvDt["lead_time"] == 1) {
                                                        $estimated_next_load = "<font color=green> " . $rowInvDt["lead_time"] . " Day </font>";
                                                    }
                                                    if ($rowInvDt["lead_time"] > 1) {
                                                        $estimated_next_load = "<font color=green> " . $rowInvDt["lead_time"] . " Days </font>";
                                                    }
                                                } else {
                                                    if (($rowInvDt["expected_loads_per_mo"] <= 0) && ($after_po_val < $boxes_per_trailer)) {
                                                        $estimated_next_load = "<font color=red> Never (sell the " . $after_po_val . ") </font>";
                                                    } else {
                                                        // logic changed by Zac
                                                        //$estimated_next_load=round((((($after_po_val/$boxes_per_trailer)*-1)+1)/$inv["expected_loads_per_mo"])*4)." weeks";;
                                                        //echo "next_load_available_date - " . $inv["I"] . " " . $after_po_val . " " . $boxes_per_trailer . " " . $inv["expected_loads_per_mo"] .  "<br>";
                                                        $estimated_next_load = ceil((((($after_po_val / $boxes_per_trailer) * -1) + 1) / $rowInvDt["expected_loads_per_mo"]) * 4) . " Weeks";
                                                    }
                                                }

                                                if ($after_po_val == 0 && $rowInvDt["expected_loads_per_mo"] == 0) {
                                                    $estimated_next_load = "<font color=red> Ask Purch Rep </font>";
                                                }

                                                if ($rowInvDt["expected_loads_per_mo"] == 0) {
                                                    $expected_loads_per_mo = "<font color=red>0</font>";
                                                } else {
                                                    $expected_loads_per_mo = $rowInvDt["expected_loads_per_mo"];
                                                }
                                            }

                                            $b2b_status = $rowInvDt["b2b_status"];
                                            $b2bstatuscolor = "";
                                            $st_query = "SELECT * FROM b2b_box_status WHERE status_key='" . $b2b_status . "'";
                                            db();
                                            $st_res = db_query($st_query);
                                            $st_row = array_shift($st_res);
                                            $b2bstatus_name = $st_row["box_status"];
                                            if ($st_row["status_key"] == "1.0" || $st_row["status_key"] == "1.1" || $st_row["status_key"] == "1.2") {
                                                $b2bstatuscolor = "green";
                                            } elseif ($st_row["status_key"] == "2.0" || $st_row["status_key"] == "2.1" || $st_row["status_key"] == "2.2") {
                                                $b2bstatuscolor = "orange";
                                                $estimated_next_load = "<font color=red> Ask Purch Rep </font>";
                                            }

                                            $estimated_next_load = $rowInvDt["buy_now_load_can_ship_in"];

                                            $b2b_ulineDollar = round($rowInvDt["ulineDollar"]);
                                            $b2b_ulineCents = $rowInvDt["ulineCents"];
                                            $b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
                                            $b2b_fob = "$" . number_format($b2b_fob, 2);

                                            if ($rowInvDt["location_country"] == "Canada") {
                                                $tmp_zipval = str_replace(" ", "", $rowInvDt["location_zip"]);
                                                $zipStr = "SELECT * FROM zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
                                            } elseif (($rowInvDt["location_country"]) == "Mexico") {
                                                $zipStr = "SELECT * FROM zipcodes_mexico LIMIT 1";
                                            } else {
                                                $zipStr = "SELECT * FROM ZipCodes WHERE zip = '" . intval($rowInvDt["location_zip"]) . "'";
                                            }

                                            /*$dt_view_res3 = db_query($zipStr, db_b2b());
												$locLat = 0;
												$locLong = 0;
												while ($ziploc = array_shift($dt_view_res3)) {
													$locLat = $ziploc["latitude"];
													$locLong = $ziploc["longitude"];
												}
												//	echo $locLong;
												$distLat = ($shipLat - $locLat) * 3.141592653 / 180;
												$distLong = ($shipLong - $locLong) * 3.141592653 / 180;

												$distA = Sin($distLat / 2) * Sin($distLat / 2) + Cos($shipLat * 3.14159 / 180) * Cos($locLat * 3.14159 / 180) * Sin($distLong / 2) * Sin($distLong / 2);
												//echo $inv["I"] . " " . $distA . "p <br/>"; 
												$distC = 2 * atan2(sqrt($distA), sqrt(1 - $distA));

												$miles_from = (int) (6371 * $distC * .621371192);
												if ($miles_from <= 250) {	//echo "chk gr <br/>";
													$miles_away_color = "green";
												}
												if (($miles_from <= 550) && ($miles_from > 250)) {
													$miles_away_color = "#FF9933";
												}
												if (($miles_from > 550)) {
													$miles_away_color = "red";
												}*/

                                            if ($rowInvDt["uniform_mixed_load"] == "Mixed") {
                                                $blength = $rowInvDt["blength_min"] . " - " . $rowInvDt["blength_max"];
                                                $bwidth = $rowInvDt["bwidth_min"] . " - " . $rowInvDt["bwidth_max"];
                                                $bdepth = $rowInvDt["bheight_min"] . " - " . $rowInvDt["bheight_max"];
                                            } else {
                                                $blength = $rowInvDt["lengthInch"];
                                                $bwidth = $rowInvDt["widthInch"];
                                                $bdepth = $rowInvDt["depthInch"];
                                            }
                                            $blength_frac = 0;
                                            $bwidth_frac = 0;
                                            $bdepth_frac = 0;
                                            $length = $blength;
                                            $width = $bwidth;
                                            $depth = $bdepth;
                                            if ($rowInvDt["lengthFraction"] != "") {
                                                $arr_length = explode("/", $rowInvDt["lengthFraction"]);
                                                if (count($arr_length) > 0) {
                                                    $blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
                                                    $length = floatval($blength + $blength_frac);
                                                }
                                            }
                                            if ($rowInvDt["widthFraction"] != "") {
                                                $arr_width = explode("/", $rowInvDt["widthFraction"]);
                                                if (count($arr_width) > 0) {
                                                    $bwidth_frac = intval($arr_width[0]) / intval($arr_width[1]);
                                                    $width = floatval($bwidth + $bwidth_frac);
                                                }
                                            }
                                            if ($rowInvDt["depthFraction"] != "") {
                                                $arr_depth = explode("/", $rowInvDt["depthFraction"]);
                                                if (count($arr_depth) > 0) {
                                                    $bdepth_frac = intval($arr_depth[0]) / intval($arr_depth[1]);
                                                    $depth = floatval($bdepth + $bdepth_frac);
                                                }
                                            }


                                            if ($rowBoxDt["box_warehouse_id"] == "238") {
                                                $vendor_b2b_rescue_id = $rowBoxDt["vendor_b2b_rescue"];
                                                $get_loc_qry = "SELECT * FROM companyInfo WHERE loopid = " . $vendor_b2b_rescue_id;
                                                db_b2b();
                                                $get_loc_res = db_query($get_loc_qry);
                                                $loc_row = array_shift($get_loc_res);
                                                $shipfrom_city = $loc_row["shipCity"];
                                                $shipfrom_state = $loc_row["shipState"];
                                                $shipfrom_zip = $loc_row["shipZip"];
                                            } else {
                                                $vendor_b2b_rescue_id = $rowBoxDt["box_warehouse_id"];
                                                $get_loc_qry = "SELECT * FROM loop_warehouse WHERE id ='" . $vendor_b2b_rescue_id . "'";
                                                db();
                                                $get_loc_res = db_query($get_loc_qry);
                                                $loc_row = array_shift($get_loc_res);
                                                $shipfrom_city = $loc_row["company_city"];
                                                $shipfrom_state = $loc_row["company_state"];
                                                $shipfrom_zip = $loc_row["company_zip"];
                                            }
                                            $ship_from  = $shipfrom_city . ", " . $shipfrom_state . " " . $shipfrom_zip;
                                            $ship_from2 = $shipfrom_state;


                                            if ($i % 2 == 0) {
                                                $rowclr = 'rowalt2';
                                            } else {
                                                $rowclr = 'rowalt1';
                                            }

                                            $enc_str = "";
                                            if ($rowBoxDt['b2b_id'] != "") {
                                                $loop_box_id = get_loop_box_id($rowBoxDt['b2b_id']);
                                                if ($loop_box_id != "") {
                                                    $enc_str = urlencode(encrypt_password($loop_box_id));
                                                }
                                            }

                                            $enc_str_comp = "";
                                            if ($company_id != "") {
                                                $enc_str_comp = urlencode(encrypt_password($company_id));
                                            }


                            ?>
                                            <tr class="<?php echo $rowclr ?>">

                                                <td class=''>
                                                    <?php if ($rowsFavData['fav_status'] == 1) { ?><input type="button" name="favItemIds" id="favItemIds<?php echo $rowsFavData['id']; ?>" value="Remove" onclick="favitem_remove(<?php echo $rowsFavData['id']; ?>, <?php echo $user_id; ?>)">
                                                    <?php } ?>
                                                </td>
                                                <td class='' width="5%"><a href='https://b2b.usedcardboardboxes.com/?id=<?php echo $enc_str; ?>&compnewid=<?php echo $enc_str_comp; ?>' target='_blank'>Buy Now</a></td>
                                                <td class='' width="5%"><?php echo $qty ?></td>
                                                <td class='' width="8%"><?php echo ($estimated_next_load) ?></td>
                                                <td class='' width="5%"><?php echo ($expected_loads_per_mo) ?></td>
                                                <td class='' width="5%"><?php echo $boxes_per_trailer ?></td>
                                                <td class='' width="3%"><?php echo $b2b_fob ?></td>
                                                <td class='' width="5%"><?php echo $rowInvDt['ID'] ?></td>
                                                <td align="center" class='display_title'>
                                                    <font color="<?php echo $b2bstatuscolor; ?>"><?php echo $b2bstatus_name ?></font>
                                                </td>

                                                <td align="center" class=''><?php echo $length ?></td>
                                                <td align="center" class=''>x</td>
                                                <td align="center" class=''><?php echo $width ?></td>
                                                <td align="center" class=''>x</td>
                                                <td align="center" class=''><?php echo $depth ?></td>
                                                <td class='' width="20%">
                                                    <a href='manage_box_b2bloop.php?id=<?php echo $rowBoxDt["id"] ?>&proc=View' target='_blank'>
                                                        <?php echo $rowInvDt["description"] ?></a>
                                                </td>
                                                <td class='' width="5%"><?php echo $vendor_name; ?></td>
                                                <td class='' width="5%"><?php echo $ship_from ?></td>
                                                <td class=''><?php echo $ownername; ?></td>
                                                <td class='' width="7%">
                                                    <?php
                                                    $arrG = array("Gaylord", "GaylordUCB", "Loop", "PresoldGaylord");
                                                    $arrSb = array("Medium", "Large", "Xlarge", "LoopShipping", "Box", "Boxnonucb", "Presold");
                                                    $arrSup = array("SupersackUCB", "SupersacknonUCB");
                                                    $arrPal = array("PalletsUCB", "PalletsnonUCB");
                                                    $arrOther = array("Recycling", "DrumBarrelUCB", "DrumBarrelnonUCB", "Waste-to-Energy", "Other", " ");
                                                    if (in_array($box_type, $arrG)) {
                                                        $boxtype        = "Gaylord";
                                                    } elseif (in_array($box_type, $arrSb)) {
                                                        $boxtype        = "Shipping";
                                                    } elseif (in_array($box_type, $arrSup)) {
                                                        $boxtype        = "SuperSack";
                                                    } elseif (in_array($box_type, $arrPal)) {
                                                        $boxtype        = "Pallet";
                                                    } elseif (in_array($box_type, $arrOther)) {
                                                        $boxtype        = "Other";
                                                    }
                                                    echo $boxtype;
                                                    ?>
                                                </td>
                                            </tr>
                            <?php
                                        }
                                    }
                                }
                                $i++;
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="18">No record found</td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>
                </form>
            </td>
        </tr>

        <tr>
            <td colspan="6">
                <input type="hidden" name="hdnFavItemsAction" value="1">
                <input type="button" name="btnAddFavoriteInv" id="btnAddFavoriteInv" value="Add new favorite inventory" style="cursor:pointer;" onclick="add_inventory_to_favorite(<?php echo $user_id; ?>)">
            </td>
        </tr>

        <!--Favorite Inventory section ends  -->
        <tr>
            <td colspan="6" style="background:while;"></td>
        </tr>
        <!-- Hide Inventory Section start -->
        <?php
        if (isset($_REQUEST['hideremoveflg']) && $_REQUEST['hideremoveflg'] == "yes") {
            db();
            db_query("Delete from boomerang_inventory_hide_items WHERE id =" . $_REQUEST['hideitemid']);
        }
        ?>
        <tr align="center">
            <td colspan="6" width="320px" align="left" style="background-color:white;">&nbsp;</td>
        </tr>
        <tr align="center">
            <td colspan="6" width="320px" align="center" bgcolor="#C1C1C1"><b>Hide Inventory</b></td>
        </tr>

        <td colspan="6">
            <form name="frmhideItems" method="post" action="boomerang_users_profile.php?user_id=<?php echo $user_id; ?>">
                <table width="100%" cellspacing="1" cellpadding="1" border="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12; border: 1px solid #cccccc;">
                    <?php
                    db();

                    $selhideData = db_query("SELECT * FROM boomerang_inventory_hide_items WHERE hideItems = 1 and user_id =" . $user_id . " ORDER BY id DESC");
                    $selhideDataCnt = tep_db_num_rows($selhideData);
                    if ($selhideDataCnt > 0) {

                    ?>
                        <tr bgcolor="#E4D5D5">
                            <td class='display_title'>&nbsp;</td>
                            <td class='display_title'>Qty Avail</td>
                            <td class='display_title'>Buy Now, Load Can Ship In</td>
                            <td class='display_title'>Expected # of Loads/Mo</td>
                            <td class='display_title'>Per Truckload</td>
                            <td class='display_title'>MIN FOB</td>
                            <td class='display_title'>B2B ID</td>
                            <td class='display_title'>Miles Away
                                <div class="tooltip">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                    <span class="tooltiptext">Green Color - miles away <= 250, <br>Orange Color - miles away
                                            <= 550 and> 250,
                                                <br>Red Color - miles away > 550</span>
                                </div>
                            </td>
                            <td align="center" class='display_title'>B2B Status</td>
                            <td align="center" class='display_title'>L</td>
                            <td align="center" class='display_title'>x</td>
                            <td align="center" class='display_title'>W</td>
                            <td align="center" class='display_title'>x</td>
                            <td align="center" class='display_title'>H</td>
                            <td class='display_title'>Description</td>
                            <td class='display_title'>Supplier</td>
                            <td class='display_title'>Ship From</td>
                            <td class='display_title'>Supplier Relationship Owner</td>
                            <td class=''>Box Type</td>
                        </tr>
                        <?php

                        $i = 0;
                        while ($rowsFavData = array_shift($selhideData)) {
                            //echo "<pre> rowsFavData ->";print_r($rowsFavData);echo "</pre>";
                            $after_po_val_tmp = 0;
                            $after_po_val = 0;
                            $pallet_val_afterpo = $preorder_txt2 = "";
                            $rec_found_box = "n";
                            $boxes_per_trailer = 0;
                            $next_load_available_date = "";
                            if ($rowsFavData['hide_b2bid'] > 0) {
                                db();
                                $selBoxDt = db_query("SELECT * FROM loop_boxes WHERE b2b_id = " . $rowsFavData['hide_b2bid']);
                                //echo "<pre> selBoxDt ->";print_r($selBoxDt);echo "</pre>";
                                $rowBoxDt = array_shift($selBoxDt);

                                if ($rowBoxDt['b2b_id'] > 0) {
                                    db_b2b();
                                    $selInvDt = db_query("SELECT * FROM inventory WHERE ID = " . $rowBoxDt['b2b_id']);
                                    $rowInvDt = array_shift($selInvDt);

                                    $box_type = $rowInvDt['box_type'];
                                    $box_warehouse_id = $rowBoxDt["box_warehouse_id"];
                                    $next_load_available_date = $rowBoxDt["next_load_available_date"];
                                    $boxes_per_trailer = $rowBoxDt['boxes_per_trailer'];
                                    if ($rowInvDt["loops_id"] > 0) {
                                        $dt_view_qry = "SELECT * FROM tmp_inventory_list_set2 WHERE trans_id = " . $rowInvDt["loops_id"] . " ORDER BY warehouse, type_ofbox, Description";
                                        db_b2b();
                                        $dt_view_res_box = db_query($dt_view_qry);
                                        while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
                                            $rec_found_box = "y";
                                            $actual_val = $dt_view_res_box_data["actual"];
                                            $after_po_val_tmp = $dt_view_res_box_data["afterpo"];
                                            $last_month_qty = $dt_view_res_box_data["lastmonthqty"];
                                        }
                                        if ($rec_found_box == "n") {
                                            $actual_val = $rowInvDt["actual_inventory"];
                                            $after_po_val = $rowInvDt["after_actual_inventory"];
                                            $last_month_qty = $rowInvDt["lastmonthqty"];
                                        }
                                        if ($box_warehouse_id == 238) {
                                            $after_po_val = $rowInvDt["after_actual_inventory"];
                                        } else {
                                            $after_po_val = $after_po_val_tmp;
                                        }


                                        $vendor_name = "";
                                        //account owner
                                        if ($rowInvDt["vendor_b2b_rescue"] > 0) {

                                            $vendor_b2b_rescue = $rowInvDt["vendor_b2b_rescue"];
                                            $q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
                                            db();
                                            $query = db_query($q1);
                                            while ($fetch = array_shift($query)) {
                                                $vendor_name = get_nickname_val($fetch["company_name"], $fetch["b2bid"]);

                                                $comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
                                                db_b2b();
                                                $comres = db_query($comqry);
                                                while ($comrow = array_shift($comres)) {
                                                    $ownername = $comrow["initials"];
                                                }
                                            }
                                        } else {
                                            $vendor_b2b_rescue = $rowInvDt["V"];
                                            if ($vendor_b2b_rescue != "") {
                                                $q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
                                                db_b2b();
                                                $query = db_query($q1);
                                                while ($fetch = array_shift($query)) {
                                                    $vendor_name = $fetch["Name"];

                                                    $comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
                                                    db_b2b();
                                                    $comres = db_query($comqry);
                                                    while ($comrow = array_shift($comres)) {
                                                        $ownername = $comrow["initials"];
                                                    }
                                                }
                                            }
                                        }



                                        if ($after_po_val < 0) {
                                            $qty = number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2;
                                        } else if ($after_po_val >= $boxes_per_trailer) {
                                            $qty = number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2;
                                        } else {
                                            $qty = number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2;
                                        }


                                        $estimated_next_load = "";
                                        $b2bstatuscolor = "";
                                        if ($rowInvDt["expected_loads_per_mo"] == 0) {
                                            $expected_loads_per_mo = "<font color=red>0</font>";
                                        } else {
                                            $expected_loads_per_mo = $rowInvDt["expected_loads_per_mo"];
                                        }


                                        $b2b_status = $rowInvDt["b2b_status"];
                                        $b2bstatuscolor = "";
                                        $st_query = "SELECT * FROM b2b_box_status WHERE status_key='" . $b2b_status . "'";
                                        db();
                                        $st_res = db_query($st_query);
                                        $st_row = array_shift($st_res);
                                        $b2bstatus_name = $st_row["box_status"];
                                        if ($st_row["status_key"] == "1.0" || $st_row["status_key"] == "1.1" || $st_row["status_key"] == "1.2") {
                                            $b2bstatuscolor = "green";
                                        } elseif ($st_row["status_key"] == "2.0" || $st_row["status_key"] == "2.1" || $st_row["status_key"] == "2.2") {
                                            $b2bstatuscolor = "orange";
                                            //$estimated_next_load= "<font color=red>Ask Rep</font>";
                                        }
                                        if (
                                            $rowInvDt["buy_now_load_can_ship_in"] == '<font color=red>Ask Purch Rep</font>'
                                            || $rowInvDt["buy_now_load_can_ship_in"] == '<font color=red> Ask Purch Rep </font>'
                                        ) {
                                            $estimated_next_load = '<font color=red>Ask Rep</font>';
                                        } else {
                                            $estimated_next_load = $rowInvDt["buy_now_load_can_ship_in"];
                                        }
                                        $b2b_ulineDollar = round($rowInvDt["ulineDollar"]);
                                        $b2b_ulineCents = $rowInvDt["ulineCents"];
                                        $b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
                                        $b2b_fob = "$" . number_format($b2b_fob, 2);

                                        if ($rowInvDt["location_country"] == "Canada") {
                                            $tmp_zipval = str_replace(" ", "", $rowInvDt["location_zip"]);
                                            $zipStr = "SELECT * FROM zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
                                        } elseif (($rowInvDt["location_country"]) == "Mexico") {
                                            $zipStr = "SELECT * FROM zipcodes_mexico LIMIT 1";
                                        } else {
                                            $zipStr = "SELECT * FROM ZipCodes WHERE zip = '" . intval($rowInvDt["location_zip"]) . "'";
                                        }
                                        db_b2b();
                                        $dt_view_res3 = db_query($zipStr);
                                        while ($ziploc = array_shift($dt_view_res3)) {
                                            $locLat = $ziploc["latitude"];
                                            $locLong = $ziploc["longitude"];
                                        }
                                        //	echo $locLong;
                                        $distLat = (($shipLat) - ($locLat)) * 3.141592653 / 180;
                                        $distLong = (($shipLong) - ($locLong)) * 3.141592653 / 180;

                                        $distA = Sin($distLat / 2) * Sin($distLat / 2) + Cos(($shipLat) * 3.14159 / 180) * Cos(($locLat) * 3.14159 / 180) * Sin($distLong / 2) * Sin($distLong / 2);
                                        //echo $inv["I"] . " " . $distA . "p <br/>"; 
                                        $distC = 2 * atan2(sqrt($distA), sqrt(1 - $distA));

                                        $miles_from = (int) (6371 * $distC * .621371192);
                                        if ($miles_from <= 250) {    //echo "chk gr <br/>";
                                            $miles_away_color = "green";
                                        }
                                        if (($miles_from <= 550) && ($miles_from > 250)) {
                                            $miles_away_color = "#FF9933";
                                        }
                                        if (($miles_from > 550)) {
                                            $miles_away_color = "red";
                                        }


                                        if ($rowInvDt["uniform_mixed_load"] == "Mixed") {
                                            $blength = $rowInvDt["blength_min"] . " - " . $rowInvDt["blength_max"];
                                            $bwidth = $rowInvDt["bwidth_min"] . " - " . $rowInvDt["bwidth_max"];
                                            $bdepth = $rowInvDt["bheight_min"] . " - " . $rowInvDt["bheight_max"];
                                        } else {
                                            $blength = $rowInvDt["lengthInch"];
                                            $bwidth = $rowInvDt["widthInch"];
                                            $bdepth = $rowInvDt["depthInch"];
                                        }
                                        $blength_frac = 0;
                                        $bwidth_frac = 0;
                                        $bdepth_frac = 0;
                                        $length = $blength;
                                        $width = $bwidth;
                                        $depth = $bdepth;
                                        if ($rowInvDt["lengthFraction"] != "") {
                                            $arr_length = explode("/", $rowInvDt["lengthFraction"]);
                                            if (count($arr_length) > 0) {
                                                $blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
                                                $length = floatval($blength + $blength_frac);
                                            }
                                        }
                                        if ($rowInvDt["widthFraction"] != "") {
                                            $arr_width = explode("/", $rowInvDt["widthFraction"]);
                                            if (count($arr_width) > 0) {
                                                $bwidth_frac = intval($arr_width[0]) / intval($arr_width[1]);
                                                $width = floatval($bwidth + $bwidth_frac);
                                            }
                                        }
                                        if ($rowInvDt["depthFraction"] != "") {
                                            $arr_depth = explode("/", $rowInvDt["depthFraction"]);
                                            if (count($arr_depth) > 0) {
                                                $bdepth_frac = intval($arr_depth[0]) / intval($arr_depth[1]);
                                                $depth = floatval($bdepth + $bdepth_frac);
                                            }
                                        }


                                        if ($rowBoxDt["box_warehouse_id"] == "238") {
                                            $vendor_b2b_rescue_id = $rowBoxDt["vendor_b2b_rescue"];
                                            $get_loc_qry = "SELECT * FROM companyInfo WHERE loopid = " . $vendor_b2b_rescue_id;
                                            db_b2b();
                                            $get_loc_res = db_query($get_loc_qry);
                                            $loc_row = array_shift($get_loc_res);
                                            $shipfrom_city = $loc_row["shipCity"];
                                            $shipfrom_state = $loc_row["shipState"];
                                            $shipfrom_zip = $loc_row["shipZip"];
                                        } else {
                                            $vendor_b2b_rescue_id = $rowBoxDt["box_warehouse_id"];
                                            $get_loc_qry = "SELECT * FROM loop_warehouse WHERE id ='" . $vendor_b2b_rescue_id . "'";
                                            db();
                                            $get_loc_res = db_query($get_loc_qry);
                                            $loc_row = array_shift($get_loc_res);
                                            $shipfrom_city = $loc_row["company_city"];
                                            $shipfrom_state = $loc_row["company_state"];
                                            $shipfrom_zip = $loc_row["company_zip"];
                                        }
                                        $ship_from  = $shipfrom_city . ", " . $shipfrom_state . " " . $shipfrom_zip;
                                        $ship_from2 = $shipfrom_state;


                                        if ($i % 2 == 0) {
                                            $rowclr = '#dcdcdc';
                                        } else {
                                            $rowclr = '#f7f7f7';
                                        }
                        ?>
                                        <tr bgcolor="<?php echo $rowclr ?>">

                                            <td class=''>
                                                <?php if ($rowsFavData['hideItems'] == 1) { ?><input type="button" name="hideItemIds" id="hideItemIds<?php echo $rowsFavData['id']; ?>" value="Remove" onclick="hideitem_remove(<?php echo $rowsFavData['id']; ?>,<?php echo $user_id ?>)">
                                                <?php } ?>
                                            </td>

                                            <td class='' width="5%"><?php echo $qty ?></td>
                                            <td class='' width="8%"><?php echo $estimated_next_load ?></td>
                                            <td class='' width="5%"><?php echo $expected_loads_per_mo ?></td>
                                            <td class='' width="5%"><?php echo $boxes_per_trailer ?></td>
                                            <td class='' width="3%"><?php echo $b2b_fob ?></td>
                                            <td class='' width="5%"><?php echo $rowInvDt['ID'] ?></td>
                                            <td class='' width="5%">
                                                <font color='<?php echo ($miles_away_color); ?>'><?php echo $miles_from ?></font>
                                            </td>
                                            <td align="center" class='display_title'>
                                                <font color="<?php echo $b2bstatuscolor; ?>"><?php echo $b2bstatus_name ?></font>
                                            </td>

                                            <td align="center" class=''><?php echo $length ?></td>
                                            <td align="center" class=''>x</td>
                                            <td align="center" class=''><?php echo $width ?></td>
                                            <td align="center" class=''>x</td>
                                            <td align="center" class=''><?php echo $depth ?></td>
                                            <td class='' width="20%">
                                                <a href='manage_box_b2bloop.php?id=<?php echo $rowBoxDt["id"] ?>&proc=View' target='_blank'>
                                                    <?php echo $rowInvDt["description"] ?></a>
                                            </td>
                                            <td class='' width="5%"><?php echo $vendor_name; ?></td>
                                            <td class='' width="5%"><?php echo $ship_from ?></td>
                                            <td class=''><?php echo ($ownername); ?></td>
                                            <td class='' width="7%">
                                                <?php
                                                $arrG = array("Gaylord", "GaylordUCB", "Loop", "PresoldGaylord");
                                                $arrSb = array("Medium", "Large", "Xlarge", "LoopShipping", "Box", "Boxnonucb", "Presold");
                                                $arrSup = array("SupersackUCB", "SupersacknonUCB");
                                                $arrPal = array("PalletsUCB", "PalletsnonUCB");
                                                $arrOther = array("Recycling", "DrumBarrelUCB", "DrumBarrelnonUCB", "Waste-to-Energy", "Other", " ");
                                                if (in_array($box_type, $arrG)) {
                                                    $boxtype        = "Gaylord";
                                                } elseif (in_array($box_type, $arrSb)) {
                                                    $boxtype        = "Shipping";
                                                } elseif (in_array($box_type, $arrSup)) {
                                                    $boxtype        = "SuperSack";
                                                } elseif (in_array($box_type, $arrPal)) {
                                                    $boxtype        = "Pallet";
                                                } elseif (in_array($box_type, $arrOther)) {
                                                    $boxtype        = "Other";
                                                }
                                                echo ($boxtype);
                                                ?>
                                            </td>

                                        </tr>


                        <?php
                                    }
                                }
                            }
                            $i++;
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="18">No record found</td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="18">
                            <input type="hidden" name="hdnhideItemsAction" value="1">
                            <input type="hidden" name="hdnCompanyId" value="<?php echo $_REQUEST['ID']; ?>">
                            <input type="button" name="btnAddHideInv" id="btnAddHideInv" value="Add New Inventory to Hide" style="cursor:pointer;" onclick="add_inventory_to_hide(<?php echo $user_id; ?>)">
                        </td>
                    </tr>
                </table>
            </form>
        </td>
        </tr>


        <!-- Hide Inventory Section End -->


        <!--Start Setup for hiding coliumns from user in boomerang section defined as seven 7  -->

        <tr align="center">
            <td colspan="6" width="320px" align="left" style="background-color:white;">&nbsp;</td>
        </tr>
        <tr align="center">
            <td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">
                Setup
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;Hide Pricing and Buy Now page?&emsp;
                <input type="checkbox" name="clientdash_setup_hide" id="clientdash_setup_hide" value="yes" <?php if ($setup_hide_flg == 1) {
                                                                                                                echo " checked ";
                                                                                                            } ?> />
            </td>
            <td colspan="5" align="left">&nbsp;</td>
        </tr>

        <!-- <tr>
			<td>
				&nbsp;Hide Box Profiles and Browse Inventory Pages?&emsp;
				<input type="checkbox" name="clientdash_boxprofile_inv_hide" id="clientdash_boxprofile_inv_hide" value="yes" <?php if ($setup_boxprofile_inv_flg == 1) {
                                                                                                                                    echo " checked ";
                                                                                                                                } ?> />
			</td>
			<td colspan="5" align="left">&nbsp;</td>
		</tr> -->

        <tr align="">
            <td colspan="6">
                <input type="button" name="clientdash_setup_submit" id="clientdash_setup_submit" value="Submit" onclick='handleSetuphide(<?php echo $user_id; ?>);' />
            </td>
        </tr>
    </table>
</div>

<div class="modal fade" id="addAddressModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <form id="add_address_form">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAddressModalLabel">Add Address</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>First Name</label>
                            <input id="add_first_name" type="text" class="form-control form-control-sm" name="first_name" placeholder="Enter First Name">
                            <span class="form_error d-none" id="add_first_name_error">First Name Can't Be Blank</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Last Name</label>
                            <input id="add_last_name" type="text" class="form-control form-control-sm" name="last_name" placeholder="Enter Last Name">
                            <span class="form_error d-none" id="add_last_name_error">Last Name Can't Be Blank</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Company</label>
                            <input id="add_company" type="text" class="form-control form-control-sm" name="company" placeholder="Enter Company">
                            <span class="form_error d-none" id="add_company_error">Company Can't Be Blank</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Country</label>
                            <select id="add_country" class="form-control form-control-sm" name="country">
                                <option value="USA">USA</option>
                                <option value="Canada">Canada</option>
                                <option value="Mexico">Mexico</option>
                            </select>
                            <span class="form_error d-none" id="add_country_error">Country Can't Be Blank</span>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Adress Line 1</label>
                            <input id="add_addressline1" type="text" class="form-control form-control-sm" name="addressline1" placeholder="Enter addressline1">
                            <span class="form_error d-none" id="add_addressline1_error">Address Line 1 Can't Be
                                Blank</span>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Suite number (optional)</label>
                            <input id="add_addressline2" type="text" class="form-control form-control-sm" name="addressline2" placeholder="Enter Suite number (optional)">
                        </div>
                        <div class="form-group col-md-6">
                            <label>City</label>
                            <input id="add_city" type="text" class="form-control form-control-sm" name="city" placeholder="Enter city">
                            <span class="form_error d-none" id="add_city_error">City Can't Be Blank</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label>State/Province</label>
                            <select id="add_state" class="form-control form-control-sm" name="state">
                                <option value=""></option>
                                <?php
                                $tableedit  = "SELECT * FROM zones where zone_country_id in (223,38,37) ORDER BY zone_country_id desc, zone_name";
                                db_b2b();
                                $dt_view_res = db_query($tableedit);
                                while ($row = array_shift($dt_view_res)) {
                                ?>
                                    <option <?php if ((trim($state) == trim($row["zone_code"])) ||
                                                (trim($state) == trim($row["zone_name"]))
                                            ) echo " selected "; ?> value="<?php echo trim($row["zone_code"]) ?>">

                                        <?php echo $row["zone_name"] ?>

                                        (<?php echo $row["zone_code"] ?>)

                                    </option>

                                <?php
                                }
                                ?>
                            </select>
                            <span class="form_error d-none" id="add_state_error">State/Province Can't Be Blank</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Zip</label>
                            <input id="add_zip" type="text" class="form-control form-control-sm" name="zip" placeholder="Enter Zip">
                            <span class="form_error d-none" id="add_zip_error">Zip Can't Be Blank</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Mobile No</label>
                            <input id="add_mobile_no" type="text" class="form-control form-control-sm" name="mobile_no" placeholder="Enter Mobile No">
                            <span class="form_error d-none" id="add_mobile_no_error">Mobile No Can't Be Blank</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Email </label>
                            <input id="add_email" type="text" class="form-control form-control-sm" name="email" placeholder="Enter Email">
                            <span class="form_error d-none" id="add_email_error">Email Can't Be Blank</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Your Dock Hours</label>
                            <input id="dock_hours" type="text" class="form-control form-control-sm" name="dock_hours" placeholder="Your Dock Hours (days open, open time - close time)">
                            <span class="form_error d-none" id="add_dock_hours_error">Dock Hours Can't Be Blank</span>
                        </div>

                        <div class="form-group col-md-12" id="div_def_add">
                            <label>Set as default</label>
                            <input id="add_set_as_def" type="checkbox" name="add_set_as_def" value="1">
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="hidden" id="add_frm_user_id" name="add_frm_user_id" value="<?php echo $_REQUEST["user_id"] ?>">
                    <input type="hidden" id="address_form_action" name="form_action" value="add_address">
                    <button type="submit" id="add_address_btn" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    async function checkDuplicate_email(input_form_action) {
        console.log(input_form_action);
        var action_id = (input_form_action == "add_user" ? "user_email_add" : "user_email_update");
        user_email = $("#" + action_id).val();
        //var user_email = $("#user_email").val();
        var res = 2;
        console.log(("#" + action_id + "_error"));
        $.ajax({
            url: 'boomerang_users_action.php',
            data: {
                user_email,
                form_action: 'check_duplicate_email'
            },
            method: "post",
            async: false,
            success: function(response) {
                if (response > 1 && input_form_action == 'update_user') {
                    $("#" + action_id + "_error").removeClass('hide_error');
                    $("#" + action_id + "_error").text('Email already exists!');
                    res = 0;
                } else if (response == 1 && input_form_action == 'add_user') {
                    $("#" + action_id + "_error").removeClass('hide_error');
                    $("#" + action_id + "_error").text('Email already exists!');
                    res = 0;
                } else {
                    $("#" + action_id + "_error").addClass('hide_error');
                    $("#" + action_id + "_error").text("Email can't be blank!");
                    res = 1;
                }
            }
        });
        return res;
    }

    function mergeIds() {
        let inputs = document.getElementById('comp_b2bid');
        let mergedIds_org = document.getElementById('mergedIds_org').value;
        let mergedIds = '';
        var mergedIds_arr = "";
        if (mergedIds_org != "") {
            mergedIds_arr = mergedIds_org.split(',');
        }

        if (inputs.value.trim() !== '') {
            searchString = inputs.value.trim();

            if (mergedIds_arr.indexOf(searchString) !== -1) {

            } else {
                mergedIds += ',' + inputs.value.trim();
            }
            inputs.value = "";
        }

        if (mergedIds_org != "") {
            document.getElementById('mergedIds').value = mergedIds_org + mergedIds;
            document.getElementById('mergedIds_org').value = mergedIds_org + mergedIds;
        } else {
            document.getElementById('mergedIds').value = mergedIds;
            document.getElementById('mergedIds_org').value = mergedIds;
        }
    }


    $(document).ready(function() {
        $('body').on('submit', ".save_user", async function(event) {
            event.preventDefault();
            var input_form_action = $(this).find('.cls_form_action').val();
            var username = $(this).find("input[name='user_name']").val();
            var useremail = $(this).find("input[name='user_email']").val();
            var user_password = $(this).find("input[name='user_password']").val();
            var flag = true;
            if (username == "") {
                $(this).find('.user_name_error').removeClass('hide_error');
                flag = false;
            } else {
                $(this).find('.user_name_error').addClass('hide_error');
            }
            if (useremail == "") {
                $(this).find('.useremail_error').removeClass('hide_error');
                flag = false;
            } else {
                $(this).find('.useremail_error').addClass('hide_error');
            }
            if (user_password == "") {
                flag = false;
                $(this).find('.user_password_error').removeClass('hide_error');
            } else {
                $(this).find('.user_password_error').addClass('hide_error');
            }
            var duplicate_email = await checkDuplicate_email(input_form_action);
            console.log("Duplicate Email " + duplicate_email);
            if (flag == true && duplicate_email == 1) {
                var all_data = new FormData(this);
                $.ajax({
                    url: 'boomerang_users_action.php',
                    data: all_data,
                    method: "post",
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        console.log(input_form_action);
                        if (response == 1) {
                            if (input_form_action == 'add_user') {
                                alert('User added successfully');
                            } else {
                                alert('User updated successfully');
                            }
                            location.reload();
                        } else {
                            alert('Something went wrong, try again later');
                        }
                    }
                })
            }
            return false;
        });

        $('body').on('click', '.add_comp_b2bid', function() {
            if (document.getElementById("comp_b2bid").value == "") {
                alert("Please enter the Company B2B ID(s)");
                return false;
            }

            var user_id = $(this).attr('user_id');
            $.ajax({
                url: 'boomerang_users_action.php',
                data: {
                    user_id,
                    comp_b2bid: document.getElementById("comp_b2bid").value,
                    form_action: 'get_add_comp_b2bid'
                },
                method: "post",
                type: 'json',
                success: function(response) {
                    //console.log(response);

                    $('#tbl_comp_id').html(response);
                    location.reload();

                }
            })

        });

        $('body').on('click', '.remove_compid', function() {
            var user_id = $(this).attr('compuser_id');
            var comp_id_to_remove = $(this).attr('comp_id_to_remove');
            var comp_b2bid_to_remove = $(this).attr('comp_b2bid_to_remove');
            $.ajax({
                url: 'boomerang_users_action.php',
                data: {
                    user_id,
                    comp_id_to_remove: comp_id_to_remove,
                    comp_b2bid_to_remove: comp_b2bid_to_remove,
                    form_action: 'get_remove_compid'
                },
                method: "post",
                type: 'json',
                success: function(response) {
                    //console.log(response);

                    $('#tbl_comp_id').html(response);
                    location.reload();
                }
            })

        });

        $("#add_address_form").submit(function() {
            var flag = true;
            var add_first_name = $('#add_first_name').val();
            if (add_first_name == '') {
                $('#add_first_name_error').removeClass('d-none');
                flag = false;
            } else {
                $('#add_first_name_error').addClass('d-none');
            }

            var add_last_name = $('#add_last_name').val();
            if (add_last_name == '') {
                $('#add_last_name_error').removeClass('d-none');
                flag = false;
            } else {
                $('#add_last_name_error').addClass('d-none');
            }

            var add_dock_hours = $('#add_dock_hours').val();
            if (add_dock_hours == '') {
                $('#add_dock_hours_error').removeClass('d-none');
                flag = false;
            } else {
                $('#add_dock_hours_error').addClass('d-none');
            }

            var add_mobile_no = $('#add_mobile_no').val();
            if (add_mobile_no == '') {
                $('#add_mobile_no_error').removeClass('d-none');
                flag = false;
            } else {
                $('#add_mobile_no_error').addClass('d-none');
            }

            var add_email = $('#add_email').val();
            if (add_email == '') {
                $('#add_email_error').removeClass('d-none');
                flag = false;
            } else {
                $('#add_email_error').addClass('d-none');
            }

            var add_company = $('#add_company').val();
            if (add_company == '') {
                $('#add_company_error').removeClass('d-none');
                flag = false;
            } else {
                $('#add_company_error').addClass('d-none');
            }
            var add_country = $('#add_country').val();
            if (add_country == '') {
                $('#add_country_error').removeClass('d-none');
                flag = false;
            } else {
                $('#add_country_error').addClass('d-none');
            }

            var add_addressline1 = $('#add_addressline1').val();
            if (add_addressline1 == '') {
                $('#add_addressline1_error').removeClass('d-none');
                flag = false;
            } else {
                $('#add_addressline1_error').addClass('d-none');
            }
            var add_city = $('#add_city').val();
            if (add_city == '') {
                $('#add_city_error').removeClass('d-none');
                flag = false;
            } else {
                $('#add_city_error').addClass('d-none');
            }
            var add_state = $('#add_state').val();
            if (add_state == '') {
                $('#add_state_error').removeClass('d-none');
                flag = false;
            } else {
                $('#add_state_error').addClass('d-none');
            }

            var add_zip = $('#add_zip').val();
            if (add_zip == '') {
                $('#add_zip_error').removeClass('d-none');
                flag = false;
            } else {
                $('#add_zip_error').addClass('d-none');
            }

            if (flag == true) {
                var all_data = new FormData(this);
                $.ajax({
                    url: 'boomerang_user_profile_action.php',
                    data: all_data,
                    method: "post",
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        if (response == 1) {
                            alert('Address Added successfully');
                            location.reload();
                        } else {
                            alert('Something went wrong, try again later');
                        }
                    }
                })
            }
            return false;
        });

        $("body").on('click', '.edit_address_btn', function() {
            var address_id = $(this).attr('address_id');
            $.ajax({
                url: 'boomerang_user_profile_action.php',
                data: {
                    address_id: address_id,
                    form_action: 'get_edit_address'
                },
                method: "post",
                type: 'json',
                async: false,
                success: function(res) {
                    var response = JSON.parse(res);
                    $('#add_first_name').val(response.first_name);
                    $('#add_last_name').val(response.last_name);
                    $('#add_email').val(response.email);
                    $('#dock_hours').val(response.dock_hours);
                    $('#add_mobile_no').val(response.mobile_no);
                    $("#add_company").val(response.company);
                    $("#add_country").val(response.country);
                    $("#add_addressline1").val(response.addressline1);
                    $("#add_addressline2").val(response.addressline2);
                    if (response.mark_default == 1) {
                        $("#div_def_add").html(
                            "<span class='text-success'><span class='fa fa-check'></span>Default Address</span>"
                        );
                    } else {
                        $("#div_def_add").html("");
                    }
                    $("#add_city").val(response.city);
                    $("#add_state").val(response.state);
                    $("#add_zip").val(response.zip);
                    $("#add_address_btn").text('Update Address');
                    $("#add_address_form").append(
                        '<input type="hidden" name="address_id" value="' + address_id + '">'
                    );
                    $("#address_form_action").val("update_address");
                    $("#addAddressModalLabel").html("Edit Address");
                    $("#addAddressModal").modal('show');
                }
            })
        });

        $('.mark_default_add').click(function() {
            var address_id = $(this).attr('address_id');
            var add_frm_user_id = $(this).attr('add_frm_user_id');
            $.ajax({
                url: 'boomerang_user_profile_action.php',
                data: {
                    add_frm_user_id: add_frm_user_id,
                    address_id: address_id,
                    form_action: 'mark_default'
                },
                method: "post",
                async: false,
                success: function(res) {
                    if (res == 1) {
                        //localStorage.setItem('AddchangeClass', 'true');

                        alert('Address marked as default');
                        location.reload();
                    } else {
                        alert('Something went wrong, try again later');
                    }
                }
            })
        });

        $(".delete_address_btn").click(function() {
            var address_id = $(this).attr('address_id');
            $.ajax({
                url: 'boomerang_user_profile_action.php',
                data: {
                    address_id: address_id,
                    form_action: 'delete_address'
                },
                method: "post",
                async: false,
                success: function(res) {
                    if (res == 1) {
                        alert('Address deleted successfully');
                        location.reload();
                    } else {
                        alert('Something went wrong, try again later');
                    }
                }
            })
        })

        $('body').on('click', '.edit_user', function() {
            var user_id = $(this).attr('user_id');
            $.ajax({
                url: 'boomerang_users_action.php',
                data: {
                    user_id,
                    form_action: 'get_edit_user_data'
                },
                method: "post",
                type: 'json',
                success: function(response) {
                    //console.log(response);
                    var data = JSON.parse(response);
                    var all_companies_dp = $('#all_companies').html();
                    var company_list = data.company_list;
                    // Create an array of company IDs from the company_list
                    var company_ids = company_list.map(function(company) {
                        return company;
                    });
                    // Add the 'selected' attribute to the options that match the company IDs

                    all_companies_dp = all_companies_dp.replace(/<option value="(\d+)">/g,
                        function(match, id) {
                            return '<option value="' + id + '"' + (company_ids.includes(
                                Number(id)) ? ' selected' : '') + '>';
                        });
                    

                    var manager_dp = $('#manager_dropdown_hidden').html();
                    var edit_html = `
						<td>
							<input type="text" name="user_name" value="${data.user_name}">
							<br><span class="color_red hide_error user_name_error">Name can't be blank!</span>
						</td>
						<td>
							<input type="text" name="user_last_name" value="${data.user_last_name}">
						</td>
						<td>
							<input type="text" name="user_company" id="user_company_update" value="${data.user_company}" >
							<span id="user_company_update_error" class="color_red hide_error usercompany_error" >Company can't be blank!</span>
						</td>
						<td>
							<input type="email" name="user_email" id="user_email_update" value="${data.user_email}" onblur="checkDuplicate_email('update_user')">
							<span id="user_email_update_error" class="color_red hide_error useremail_error" >Email can't be blank!</span>
							<br>
							Password: <input type="password" name="user_password" value="${data.user_password}">
							<span class="color_red hide_error user_password_error">Password can't be blank!</span>
						</td>
						<td>
							<input type="text" name="user_phone" id="user_phone_update" value="${data.user_phone}" >
							<span id="user_phone_update_error" class="color_red hide_error userphone_error" >Phone can't be blank!</span>
						</td>
						
						<td>
                            ${manager_dp}
                        </td>
						<td>
                            <select name="activate_deactivate"><option></option><option value="1" ${data.activate_deactivate == 1 ? "selected" : ""}>Active</option><option value="0" ${data.activate_deactivate == 0 ? "selected" : ""}>Deactive</option></select>
                        </td>
                        <td>
                            <select name="user_block"><option></option><option value="1" ${data.user_block == 1 ? "selected" : ""}>Block</option><option value="0" ${data.user_block == 0 ? "selected" : ""}>Unblock</option></select>
                        </td>
						<td>
							<input type="hidden" name="form_action" value="update_user" class="cls_form_action"> 
							<input type="hidden" name="user_id" id="user_id" value="${user_id}">
							<input type="submit" value="Update">
							<button type="button" class="cancel_edit">Cancel</button>
						</td>`;
                    $('#userrowid_' + user_id).html(edit_html);
                    $('#userrowid_' + user_id).find('.manager_ids').val(data.manager_id);
                    //$('#userrowid_' + user_id + ' td').wrap("<form class='save_user'></form>");
                }
            })
        });

        $('body').on('click', '.cancel_edit', function() {
            location.reload();
        });

        $('body').on('click', ".delete_user", function() {
            var user_id = $(this).attr('user_id');
            var user_status = $(this).attr('user_status');
            if (user_status == 1) {
                var choice = confirm('Do you want to make this user inactive?');
            } else {
                var choice = confirm('Do you want to make this user as active?');
            }
            if (choice === true) {
                $.ajax({
                    url: 'boomerang_users_action.php',
                    data: {
                        user_id,
                        form_action: 'delete_user'
                    },
                    method: "post",
                    type: 'json',
                    success: function(response) {
                        if (response == 1) {
                            alert('User updation done successfully');
                            window.location.href = "boomerang_users_profile.php?user_id=" +
                                user_id;
                        } else {
                            alert("Something went wrong, try again later")
                        }
                    }
                });
            }
        })
    });

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


    function add_inventory_to_favorite(user_id) {
        var selectobject = document.getElementById("btnAddFavoriteInv");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light').style.display = 'block';
        document.getElementById('light').style.left = n_left + 50 + 'px';
        document.getElementById('light').style.top = n_top + 20 + 'px';

        document.getElementById("light").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        var sstr = "";
        sstr =
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
        sstr = sstr + "<br>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML = sstr + xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "boomerang_getBoxData.php?user_id=" + user_id, true);
        xmlhttp.send(); /**/
    }

    function Remove_boxes_warehouse_data(favB2bId, user_id, closeloop = 0) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (xmlhttp.responseText == 'done') {
                    alert('Remove successfully. Need to reload the page after all boxes are updated.');
                    document.location.reload(true);
                    //document.getElementById('light').style.display='none';
                    //window.location.replace("clientdashboard_setup.php?ID="+compId);
                }
            }
        }
        if (closeloop == 1) {
            xmlhttp.open("GET", "boomerang_update_closeloop_inventory_data.php?favB2bId=" + favB2bId + "&user_id=" +
                user_id + "&upd_action=2", true);
            xmlhttp.send();
        } else {
            xmlhttp.open("GET", "boomerang_update_favorite_inventory_data.php?favB2bId=" + favB2bId + "&user_id=" +
                user_id + "&upd_action=2", true);
            xmlhttp.send();
        }
    }

    function Add_boxes_warehouse_data(favB2bId, user_id, closeloop = 0) {

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (xmlhttp.responseText == 'done') {
                    alert('Added successfully. Need to reload the page after all boxes are updated.');
                    //document.getElementById('light').style.display='none';
                    //window.location.replace("clientdashboard_setup.php?ID="+compId);
                    document.location.reload(true);
                }
            }
        }
        if (closeloop == 1) {
            xmlhttp.open("GET", "boomerang_update_closeloop_inventory_data.php?favB2bId=" + favB2bId + "&user_id=" +
                user_id + "&upd_action=1", true);
            xmlhttp.send();
        } else {
            xmlhttp.open("GET", "boomerang_update_favorite_inventory_data.php?favB2bId=" + favB2bId + "&user_id=" +
                user_id + "&upd_action=1", true);
            xmlhttp.send();
        }

    }

    function add_inventory_to_closeloop(compId) { //alert('compId -> '+compId)
        var selectobject = document.getElementById("btnAddCloseloopInv");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light').style.display = 'block';
        document.getElementById('light').style.left = n_left + 50 + 'px';
        document.getElementById('light').style.top = n_top + 20 + 'px';

        document.getElementById("light").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        var sstr = "";
        sstr =
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
        sstr = sstr + "<br>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML = sstr + xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "boomerang_getBoxDataCloseloop.php?ID=" + compId, true);
        xmlhttp.send();
    }

    function favitem_remove(favitemid, user_id) {
        document.location = "boomerang_users_profile.php?favitemid=" + favitemid + "&user_id=" + user_id +
            "&favremoveflg=yes";
    }

    function add_inventory_to_hide(user_id) {
        var selectobject = document.getElementById("btnAddHideInv");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light').style.display = 'block';
        document.getElementById('light').style.left = n_left + 50 + 'px';
        document.getElementById('light').style.top = n_top + 20 + 'px';

        document.getElementById("light").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        var sstr = "";
        sstr =
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
        sstr = sstr + "<br>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML = sstr + xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "boomerang_getBoxDataHide.php?user_id=" + user_id, true);
        xmlhttp.send();
    }

    function Remove_boxes_hide(favB2bId, user_id) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (xmlhttp.responseText == 'done') {
                    alert('Remove successfully. Need to reload the page after all boxes are updated.');
                    document.location.reload(true);
                }
            }
        }

        xmlhttp.open("GET", "boomerang_update_hide_inventory_data.php?favB2bId=" + favB2bId + "&user_id=" + user_id +
            "&upd_action=2", true);
        xmlhttp.send();
    }

    function Add_boxes_hide(favB2bId, user_id) {

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (xmlhttp.responseText == 'done') {
                    alert('Added successfully. Need to reload the page after all boxes are updated.');
                    document.location.reload(true);
                }
            }
        }

        xmlhttp.open("GET", "boomerang_update_hide_inventory_data.php?favB2bId=" + favB2bId + "&user_id=" + user_id +
            "&upd_action=1", true);
        xmlhttp.send();

    }

    function hideitem_remove(hideitemid, user_id) {
        document.location = "boomerang_users_profile.php?hideitemid=" + hideitemid + "&user_id=" + user_id +
            "&hideremoveflg=yes";
    }

    function handleSetuphide(user_id) {

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                alert("Record Updated.");
            }
        }

        var setuphide_flg = 0;
        if (document.getElementById('clientdash_setup_hide').checked) {
            var setuphide_flg = 1;
        }

        var boxprofileinv_flg = 0;
        //if (document.getElementById('clientdash_boxprofile_inv_hide').checked) {
        //	var boxprofileinv_flg = 1;
        //}


        xmlhttp.open("GET", "boomerang_update_setup_hide_flg.php?user_id=" + user_id + "&setuphide_flg=" + setuphide_flg +
            "&boxprofileinv_flg=" + boxprofileinv_flg, true);
        xmlhttp.send();
    }
</script>