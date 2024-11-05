<?php
/*require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";
*/
$box_type = "";
$box_subtype = "";
//print_r($_REQUEST);
//echo "<br>".$_REQUEST['category']." ".(isset($_REQUEST['category']) && $_REQUEST['category'] == "sb");
$box_title = "";
if (isset($_REQUEST['category']) && $_REQUEST['category'] == "pl") {
    $box_type = "Pallets";
    $box_title = "Pallets";
} else if (isset($_REQUEST['category']) && $_REQUEST['category'] == "sb") {
    $box_type = "Shipping";
    $box_title = "Shipping Boxes";
} else if (isset($_REQUEST['category']) && $_REQUEST['category'] == "ss") {
    $box_type = "Supersacks";
    $box_title = "Supersacks";
} else {
    $box_type = "Gaylord";
    $box_title = "Gaylord Totes";
}
//echo $box_type;
//$box_type = isset($_REQUEST['box_type']) && $_REQUEST['box_type'] != "" ? $_REQUEST['box_type'] : "Gaylord";
$box_subtype = isset($_REQUEST['box_subtype']) && $_REQUEST['box_subtype'] != "" ? $_REQUEST['box_subtype'] : "all";
$shown_in_client_flg = 0;
$client_companyid = 0;
$user_id = isset($_REQUEST['loginid']) && $_REQUEST['loginid'] != "" ? $_REQUEST['loginid'] : "";

if ($_REQUEST["shown_in_client_flg"] == 1) {
    $shown_in_client_flg = 1;
    $shown_in_client_flg_width = "";
    $client_companyid = $_REQUEST["client_companyid"];

    db_b2b();
    $sql = "SELECT shipAddress, shipAddress2, shipCity, shipState, shipZip FROM companyInfo WHERE ID = '" . $client_companyid . "'";
    $shipAddress = $shipAddress2 = $shipCity = $shipState = $shipZip = "";
    //$result = db_query($sql, array("i"), array($client_companyid));
    $result = db_query($sql);
    while ($res_data = array_shift($result)) {
        $shipAddress  = $res_data["shipAddress"];
        $shipAddress2 = $res_data["shipAddress2"];
        $shipCity     = $res_data["shipCity"];
        $shipState    = $res_data["shipState"];
        $shipZip      = $res_data["shipZip"];
    }
    db();
} else {
    require("inc/header_session.php");
    $shown_in_client_flg_width = "width:75%;";
}

$user_id_hide_price = "no";
if (isset($_REQUEST['loginid']) && $_REQUEST['loginid'] != "") {
    db();
    $get_boxes_query = db_query("SELECT * FROM boomerang_user_section_details where user_id = '" . $_REQUEST['loginid'] . "' and section_id = 7 and activate_deactivate = 1");
    while ($boxes = array_shift($get_boxes_query)) {
        $user_id_hide_price = "yes";
    }
}
?>
<html>

<head>
    <title>B2B Inventory: Used <?php echo $box_title; ?> Totes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        function display_preoder_sel(tmpcnt, reccnt, box_id, wid) {
            if (reccnt > 0) {

                //if (document.getElementById('inventory_preord_top_' + tmpcnt).style.display == 'table-row') 
                //{ document.getElementById('inventory_preord_top_' + tmpcnt).style.display='none'; } else {
                //document.getElementById('inventory_preord_top_' + tmpcnt).style.display='table-row'; }

                if (document.getElementById('inventory_preord_middle_div_' + tmpcnt).style.display == 'inline') {
                    document.getElementById('inventory_preord_middle_div_' + tmpcnt).style.display = 'none';
                } else {
                    document.getElementById('inventory_preord_middle_div_' + tmpcnt).style.display = 'inline';
                }

                document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML =
                    "<br><br>Loading .....<img src='images/wait_animated.gif' />";

                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML = xmlhttp
                            .responseText;
                    }
                }

                xmlhttp.open("GET", "inventory_preorder_childtable.php?box_id=" + box_id + "&wid=" + wid, true);
                xmlhttp.send();
            }
        }

        function change_addressbook() {
            view_type_chg = "no";
            //echo "<option value='".$user_address['id'] . "|" . str_replace("'", "\'" ,$user_address['addressline1']) . "|" . str_replace("'", "\'" ,$user_address['addressline2']) . "|" . $user_address['city'] . "|" . $user_address['state'] . "|" . $user_address['zip'] ."'>".$address."</option>";
            if (document.getElementById("address_book").value != "") {
                document.getElementById("div_enter_add").style.display = "none";

                let address_book_str = document.getElementById("address_book").value;
                let address_book_arr = address_book_str.split("|");

                document.getElementById("txtaddress").value = address_book_arr[1];
                document.getElementById("txtaddress2").value = address_book_arr[2];
                document.getElementById("txtcity").value = address_book_arr[3];
                document.getElementById("txtstate").value = address_book_arr[4];
                document.getElementById("txtzipcode").value = address_book_arr[5];

                show_inventories('', 1, 1, 1, 'address_changed');
                functionLoaded = false;
                show_inventories('2', 1, 2, 1, 'address_changed');
            } else {
                document.getElementById("div_enter_add").style.display = "inline";
            }
        }
    </script>
</head>

<?php
if ($shown_in_client_flg == 0) {
    include("inc/header.php");
?>
    <br>
    <br>
    <script type="text/javascript" src="wz_tooltip.js"></script>
<?php }

db();
?>
<div class="clearfix"></div>
<div id="overlay1" style="border: 1px solid black; text-align: center; margin: 0 auto; width: 100%; display: none;">
    <font color="black">
        <div style="background-color:rgba(255, 255, 255, 1); padding: 10px; text-align: center; box-shadow: 0px 10px  5px rgba(0,0,0,0.6);
        -moz-box-shadow: 0px 10px  5px  rgba(0,0,0,0.6);
        -webkit-box-shadow: 0px 10px 5px  rgba(0,0,0,0.6);
        -o-box-shadow: 0px 10px 5px  rgba(0,0,0,0.6); border-radius:10px;">
            <span id="loader_text" style="line-height: 1px">Loading...</span>
            <img src="images/wait_animated.gif" alt="Loading" />
        </div>
    </font>
</div>
<div id="loader" class="overlay d-none">
    <div class="d-flex justify-content-center">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</div>

<div class="main-section mb-5 pb-5">
    <div class="container-fluid">
        <div class="dashboard_heading" style="font-size: 24px;font-family: 'Titillium Web', sans-serif;font-weight: 600;padding: 1% 0;">B2B
            Inventory: Used <?php echo $box_title; ?></div>

        <div class="products_div_main_urgent">
            <div class="result_products_urgent" id="result_products_urgent">
                <?php if ($shown_in_client_flg == 1) { ?>
                    <div id="products-grid-view-urgent"></div>
                    <div id="products-table-view-urgent" class="d-none"></div>
                <?php } ?>

                <?php if ($shown_in_client_flg == 0) { ?>
                    <div id="products-table-view-urgent"></div>
                    <div id="products-grid-view-urgent" class="d-none"></div>
                <?php } ?>
            </div>
            <br>
        </div>

        <div class="row">
            <div class="col-md-2 inventory-filter">
                <div class="col-md-12 filter-box1">
                    <div class="row">
                        <div class="col-md-12 p-0" id="your-selections">
                            <p><b>YOUR SELECTIONS</b></p>
                            <div id="selections"></div>
                            <a class="map_link float-right" href="#" id="clear_all">Clear All</a>
                        </div>
                        <form id="filter_form" class="w-100">
                            <?php
                            if (isset($_REQUEST['loginid']) && $_REQUEST['loginid'] != "") 
                            {
                                db();
                                $rec_found = "no";
                                $user_address_qry = db_query("SELECT * FROM boomerang_user_addresses WHERE user_id = '" . $user_id . "' ORDER BY addressline1 asc");
                                $no_of_add = tep_db_num_rows($user_address_qry);
                                //if($no_of_add > 0){
                                 ?>
                                <input type="hidden" name="loginid" value="<?php echo $_REQUEST['loginid']; ?>" />
                                <div class="form-group">
                                    <label class="font-weight-bold">Address Book
                                        <span class="tooltip_cust"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                            <span class="tooltiptext">Select a pre-stored address in your address book to calculate distance of all available inventory. For entering a new address, you have to type at least the zip code to calculate distance, or a full street address to calculate delivery (FTL) cost. Please note, this does take a few extra seconds to load the page.</span>
                                        </span>
                                    </label>
                                    <!--<label data-toggle="tooltip" title="Select to filter results for Address Book." class="font-weight-bold">Address Book</label>-->
                                    <select name="address_book" id="address_book" onchange="change_addressbook()">
                                        <option value=''>Enter New Address</option>
                                        <?php
                                        while ($user_address = array_shift($user_address_qry)) {
                                            $sel_add_txt = "";
                                            $rec_found = "yes";
                                            if ($no_of_add == 1 || $user_address['mark_default'] == 1) {
                                                $sel_add_txt = " selected ";
                                                $shipAddress = $user_address['addressline1'];
                                                $shipAddress2 = $user_address['addressline2'];
                                                $shipCity = $user_address['city'];
                                                $shipState = $user_address['state'];
                                                $shipZip = $user_address['zip'];
                                            }
                                            $address = $user_address['addressline1'] . ", " . $user_address['city'] . ", " . $user_address['state'] . ", " . $user_address['zip'];
                                            //$address = substr($address, 0, 60);
                                            //$address = $address."...";

                                            echo "<option " . $sel_add_txt . " value='" . $user_address['id'] . "|" . str_replace("'", "\'", $user_address['addressline1']) . "|" . str_replace("'", "\'", $user_address['addressline2']) . "|" . $user_address['city'] . "|" . $user_address['state'] . "|" . $user_address['zip'] . "'>" . $address . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <input type="hidden" class="w-100" name="txtaddress" id="txtaddress" value="<?php echo $shipAddress; ?>" />
                                <input type="hidden" class="w-100" name="txtaddress2" id="txtaddress2" value="<?php echo $shipAddress2; ?>" />
                                <input type="hidden" class="w-100" name="txtcity" id="txtcity" value="<?php echo $shipCity; ?>" />
                                <input type="hidden" class="w-100" name="txtstate" id="txtstate" value="<?php echo $shipState; ?>" />
                                <input type="hidden" class="w-100" name="txtzipcode" id="txtzipcode" value="<?php echo $shipZip; ?>" />

                                <?php if ($rec_found == "yes") { ?>
                                    <div class="form-group" id="div_enter_add" style="display:none;">
                                    <?php } else { ?>
                                        <div class="form-group" id="div_enter_add">
                                        <?php } ?>

                                        <div id="full_address_div">
                                            <label class="font-weight-bold">Address</label>
                                            <input type="text" class="w-100" name="cust_txtaddress" id="cust_txtaddress" value="" />
                                            <label class="font-weight-bold">Address 2</label>
                                            <input type="text" class="w-100" name="cust_txtaddress2" id="cust_txtaddress2" value="" />
                                            <label class="font-weight-bold">City</label>
                                            <input type="text" class="w-100" name="cust_txtcity" id="cust_txtcity" value="" />
                                            <label class="font-weight-bold">State</label>
                                            <select class="w-100" name="cust_txtstate" id="cust_txtstate">
                                                <option value="">Select State</option>
                                                <?php
                                                $tableedit  = "SELECT * FROM zones where zone_country_id in (223,38,37) ORDER BY zone_country_id desc, zone_name";
                                                db_b2b();
                                                $dt_view_res = db_query($tableedit);
                                                while ($row = array_shift($dt_view_res)) {
                                                ?>
                                                    <option <?php ?> value="<?php echo trim($row["zone_code"]) ?>" display_val="<?php echo trim($row["zone_code"]) ?>">
                                                        <?php echo $row["zone_name"] ?>
                                                        (<?php echo $row["zone_code"] ?>)
                                                    </option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <label class="d-flex font-weight-bold">Zip Code</label>

                                        <input type="text" class="w-100" name="cust_txtzipcode" id="cust_txtzipcode" value="" />
                                        <div class="text-center">
                                            <button type="button" class="btn apply-filter btn-sm" id="clear-deliverydata" onclick="clear_filter_cust('deliverydata')">Clear Address</button>
                                            <!-- <button type="button" class="btn apply-filter btn-sm" id="apply-deliverydata" onclick="copy_add_from_cust_to_main(); show_inventories('', 1, 1, 1, 'address_change'); functionLoaded = false; show_inventories('2',1,2,1,'address_change');">Apply
                                                Address</button> -->
                                            <button type="button" class="btn apply-filter btn-sm" id="apply-deliverydata" onclick="validateAddressAndApply()">Apply Address</button>
                                            <!-- <button type="button" class="btn apply-filter btn-sm" id="add-deliverydata" >Save Address</button> -->
                                        </div>
                                        </div>
                                    <?php //}
                            } else 
                            { ?>
                                        <div class="form-group">
                                            <p id="enter_address_text" class="text-primary text-center m-0 flex-grow-1 ml-3" style="cursor:pointer"><u>Enter Full Adddress</u></p>

                                            <div id="full_address_div" class="d-none">
                                                <label class="font-weight-bold">Address</label>
                                                <input type="text" class="w-100" name="txtaddress" id="txtaddress" value="<?php echo $shipAddress; ?>" />
                                                <label class="font-weight-bold">Address 2</label>
                                                <input type="text" class="w-100" name="txtaddress2" id="txtaddress2" value="<?php echo $shipAddress2; ?>" />
                                                <label class="font-weight-bold">City</label>
                                                <input type="text" class="w-100" name="txtcity" id="txtcity" value="<?php echo $shipCity; ?>" />
                                                <label class="font-weight-bold">State</label>
                                                <select class="w-100" name="txtstate" id="txtstate">
                                                    <option value="">Select State</option>
                                                    <?php
                                                    $tableedit  = "SELECT * FROM zones where zone_country_id in (223,38,37) ORDER BY zone_country_id desc, zone_name";
                                                    db_b2b();
                                                    $dt_view_res = db_query($tableedit);
                                                    while ($row = array_shift($dt_view_res)) {
                                                    ?>
                                                        <option <?php if ((trim($_REQUEST["txtstate"]) == trim($row["zone_code"])) ||
                                                                    (trim($shipState) == trim($row["zone_code"])) ||
                                                                    (trim($_REQUEST["txtstate"]) == trim($row["zone_name"])) ||
                                                                    (trim($shipState) == trim($row["zone_name"]))
                                                                ) echo " selected "; ?> value="<?php echo trim($row["zone_code"]) ?>" display_val="<?php echo trim($row["zone_code"]) ?>">
                                                            <?php echo $row["zone_name"] ?>
                                                            (<?php echo $row["zone_code"] ?>)
                                                        </option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <label class="d-flex font-weight-bold">Zip Code</label>

                                            <input type="text" class="w-100" name="txtzipcode" id="txtzipcode" value="<?php echo $shipZip; ?>" />
                                            <div class="text-center">
                                                <button type="button" class="btn apply-filter btn-sm" id="clear-deliverydata" onclick="clear_filter('deliverydata')">Clear Zipcode</button>
                                                <button type="button" class="btn apply-filter btn-sm" id="apply-deliverydata" onclick="show_inventories('', 1, 1); functionLoaded = false; show_inventories('2');">
                                                    Apply Zipcode</button>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            Warehouse
                                            <span class="tooltip_cust"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                                <span class="tooltiptext">
                                                Select a UCB warehouse just the inventory currently available there. Please note, inventory at the same warehouse can be loaded on the same delivery trailer, regardless of number of SKUs selected. "Direct Ship" refers to locations where the boxes are being shipped directly from the facility where they were unpacked, and cannot be combined with other SKUs.
                                                </span>
                                            </span>
                                        </label><br>
                                        <select name="warehouse" id="warehouse" onchange="change_warehouse()">
                                            <!-- <select name="warehouse" id="warehouse" onchange="show_inventories('', 1, 2, 2, 'filter')"> -->
                                            <option value="all" display_val="All" selected>All</option>
                                            <?php
                                            /*if ($shown_in_client_flg == 1) {
                                                echo '<option value="238" display_val="Direct Ship">Direct Ship</option>';
                                                db();
                                                $warehouse_get_query = db_query("SELECT id, warehouse_name, warehouse_city, warehouse_state FROM loop_warehouse WHERE rec_type = 'Sorting' AND Active = 1 and id <> 238 ORDER BY warehouse_city");
                                                while ($warehouse = array_shift($warehouse_get_query)) {
                                                    $name = $warehouse['warehouse_city'] . ", " . $warehouse['warehouse_state'];
                                                    $id = $warehouse['id'];
                                            ?>
                                                    <option value="<?php echo $id ?>" display_val="<?php echo $name ?>">
                                                        <?php echo $name ?></option>
                                                <?php     }
                                            } else {

                                                db();
                                                $warehouse_get_query = db_query("SELECT id, warehouse_name, warehouse_city, warehouse_state FROM loop_warehouse WHERE rec_type = 'Sorting' AND Active = 1 ORDER BY warehouse_name");
                                                while ($warehouse = array_shift($warehouse_get_query)) {
                                                    $name = $warehouse['warehouse_name'];
                                                    $id = $warehouse['id'];
                                                ?>
                                                    <option value="<?php echo $id ?>" display_val="<?php echo $name ?>">
                                                        <?php echo $name ?></option>
                                            <?php     }
                                            }
                                            */
                                            ?>
                                        </select>
                                    </div>

                                    <?php if ($shown_in_client_flg == 1) { ?>
                                    <?php } else { ?>

                                        <div class="form-group">
                                            <label class="font-weight-bold">Timing</label><br>
                                            <select name="timing" id="timing" onchange="change_timing()">
                                                <option value="4" display_val="Ready Now">Ready Now</option>
                                                <option value="5" display_val="Can ship in 2 weeks">Can ship in 2 weeks</option>
                                                <option value="10" display_val="Can ship in 4 weeks">Can ship in 4 weeks
                                                </option>
                                                <option value="7" display_val="Can ship this month">Can ship this month</option>
                                                <option value="6" display_val="Ready to ship whenever" selected>Ready to ship
                                                    whenever</option>
                                                <option value="9" display_val="Enter ship by date">Enter ship by date</option>
                                            </select>
                                            <div class="mt-2 d-none" id="timing_date_div">
                                                <input type="date" class="form-control  form-control-sm" name="timing_date" id="timing_date" />
                                                <p id="timing_date_error" class="text-danger d-none"><small>Please Select
                                                        Date</small></p>
                                                <div class="text-center">
                                                    <button type="button" class="btn apply-filter btn-sm" id="apply-timimg" onclick="show_inventories('',1)"> Load</button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if ($shown_in_client_flg == 0) { ?>
                                        <div class="form-group ">
                                            <label class="font-weight-bold">Gaylord Type</label><br>
                                            <input class="gaylord_box_nonucb" id="gaylord_box_nonucb" type="checkbox" display_val="Gaylord (UCB)" value="GaylordUCB" name="include_presold_and_loops[]" onchange="show_inventories('',1)" checked />
                                            UCB </br>

                                            <input class="gaylord_box_ucb" id="gaylord_box_ucb" type="checkbox" display_val="Gaylord (Non-UCB)" value="Gaylord" name="include_presold_and_loops[]" onchange="show_inventories('',1)" checked />
                                            Non-UCB </br>

                                            <input class="gaylord_box_presold" id="gaylord_box_presold" type="checkbox" display_val="Gaylord (Presold)" value="PresoldGaylord" name="include_presold_and_loops[]" onchange="show_inventories('',1)" /> Presold
                                            </br>

                                            <input class="gaylord_box_loop" id="gaylord_box_loop" type="checkbox" display_val="Gaylord (Loop)" value="Loop" name="include_presold_and_loops[]" onchange="show_inventories('',1)" /> Loops </br>
                                        </div>
                                    <?php } ?>

                                    <?php if ($shown_in_client_flg == 0) { ?>
                                        <div class="form-group">
                                            <label class="font-weight-bold">
                                                Status
                                                <span class="tooltip_cust">
                                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                    <span class="tooltiptext">Select to filter results for Status.</span>
                                                </span>
                                            </label><br>
                                            <input class="active_available" type="checkbox" display_val="Active (Available)" value="1" name="status[]" id="active_available" onchange="show_inventories('',1)" checked /> Active (Available) </br>
                                            <input class="potential_to_get" type="checkbox" display_val="Potential to Get" value="2" name="status[]" id="potential_to_get" onchange="show_inventories('',1)" /> Potential to Get </br>
                                            <input class="inactive_unavailable" type="checkbox" display_val="Inactive (Unavailable, Can't sell)" value="3" name="status[]" id="inactive_unavailable" onchange="show_inventories('',1)" /> Inactive
                                            (Unavailable, Can't sell)
                                        </div>
                                    <?php } ?>

                                    <?php if ($shown_in_client_flg == 1) { ?>
                                        <?php if ($box_type == "Shipping" || $box_type == "Gaylord") { ?>
                                            <div class="form-group">
                                                <div class="d-flex label_mb">
                                                    <input class="include_FTL_Rdy_Now_Only" type="checkbox" display_val="FTL Rdy Now Only" value="1" name="include_FTL_Rdy_Now_Only" id="include_FTL_Rdy_Now_Only" onchange="show_inventories('',1,2,2,'filter')" />
                                                    <span>
                                                        Full Truckloads Only
                                                        <span class="tooltip_cust">
                                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                            <span class="tooltiptext">Check this box if you only want to view inventory where we have full 53' truckload quantities available of it to ship.</span>
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="form-group">
                                            <div class="d-flex label_mb">
                                                <input class="ltl_allowed" type="checkbox" display_val="LTL Allowed" value="1" name="ltl_allowed" id="ltl_allowed" onchange="show_inventories('',1,2,2,'filter')" />
                                                <span>
                                                    LTL Allowed
                                                    <span class="tooltip_cust">
                                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        <span class="tooltiptext">Check this box if you only want to view inventory where the facility is able to accommodate Less-Than-Full Truckload (LTL) shipments (such as buying only a few pallets or a half a load).</span>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="d-flex label_mb">
                                                <input class="customer_pickup_allowed" type="checkbox" display_val="Customer Pickup Allowed" value="1" name="customer_pickup_allowed" id="customer_pickup_allowed" onchange="show_inventories('',1,2,2,'filter')" /> Customer
                                                <span>
                                                    Pickup Allowed
                                                    <span class="tooltip_cust">
                                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        <span class="tooltiptext">Select to filter results for Pickup Allowed.</span>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="form-group">
                                            <input class="include_FTL_Rdy_Now_Only" type="checkbox" display_val="FTL Rdy Now Only" value="1" name="include_FTL_Rdy_Now_Only" id="include_FTL_Rdy_Now_Only" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>
                                                FTL Rdy Now Only
                                                <span class="tooltip_cust">
                                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                    <span class="tooltiptext">Select to filter results for FTL Rdy Now.</span>
                                                </span>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <input class="include_sold_out_items" type="checkbox" display_val="Include Sold Out Items" value="1" name="include_sold_out_items" id="include_sold_out_items" onchange="show_inventories('',1)" />
                                            <span>Include Sold Out Items
                                                <span class="tooltip_cust">
                                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                    <span class="tooltiptext">Select to filter results for Including Sold Out Items.</span>
                                                </span>
                                            </span>
                                        </div>

                                        <div class="form-group">
                                            <input class="ltl_allowed" type="checkbox" display_val="LTL Allowed" value="1" name="ltl_allowed" id="ltl_allowed" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>
                                                LTL Allowed?
                                                <span class="tooltip_cust">
                                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                    <span class="tooltiptext">Select to filter results for LTL Allowed.</span>
                                                </span>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <input class="customer_pickup_allowed" type="checkbox" display_val="Customer Pickup Allowed" value="1" name="customer_pickup_allowed" id="customer_pickup_allowed" onchange="show_inventories('',1,2,2,'filter')" /> Customer
                                            <span>
                                                Pickup Allowed?
                                                <span class="tooltip_cust">
                                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                    <span class="tooltiptext">Select to filter results for Pickup Allowed.</span>
                                                </span>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <input class="urgent_clearance" type="checkbox" display_val="Urgent/Clearance" value="1" name="urgent_clearance" id="urgent_clearance" onchange="show_inventories('',1)" />
                                            <span>
                                                Urgent/Clearance
                                                <span class="tooltip_cust">
                                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                    <span class="tooltiptext">Select to filter results for Urgent/Clearance Boxes.</span>
                                                </span>
                                            </span>
                                        </div>
                                    <?php }  ?>
                                    </div>
                    </div>
                        <div class="col-md-12 filter-box2">
                            
                           <div class="flex-column" style="margin:0px -15px">
                           <?php if ($box_type == "Shipping" || $box_type == "Gaylord") { ?>
                    
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        Price (ea)
                                        <span class="tooltip_cust">
                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            <span class="tooltiptext">Filter results by minimum and maximum price points.</span>
                                        </span>
                                    </label>
                                    <table class="form-table">
                                        <tr>
                                            <td>
                                                <div class="input-group-addon">
                                                    <span>$</span>
                                                    <input type="text" name="min_price_each" class="w-100" value="0.00" id="price_each-from" onchange="(function(el){el.value=parseFloat(el.value).toFixed(2);})(this); show_inventories('',1,2,2,'filter')" />
                                                </div>
                                            </td>
                                            <td>&nbsp;-&nbsp;</td>
                                            <td>
                                                <div class="input-group-addon">
                                                    <span>$</span>
                                                    <input type="text" name="max_price_each" class="w-100" value="99.99" id="price_each-to" onchange="(function(el){el.value=parseFloat(el.value).toFixed(2);})(this); show_inventories('',1,2,2,'filter')" />
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <?php if ($box_type == "Shipping") { ?>
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            Length (in)
                                            <span class="tooltip_cust">
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                <span class="tooltiptext">Filter results by minimum and maximum Length.</span>
                                            </span>
                                        </label>
                                        <table class="form-table">
                                            <tr>
                                                <td><input name="min_length" type="number" class="w-100" value="0" id="length-from" onchange="show_inventories('',1,2,2,'filter')" /></td>
                                                <td>&nbsp;-&nbsp;</td>
                                                <td><input name="max_length" type="number" class="w-100" value="99.99" id="length-to" onchange="show_inventories('',1,2,2,'filter')" /></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            Width (in)
                                            <span class="tooltip_cust">
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                <span class="tooltiptext">Filter results by minimum and maximum Width.</span>
                                            </span>
                                        </label>
                                        <table class="form-table">
                                            <tr>
                                                <td><input name="min_width" type="number" class="w-100" value="0" id="width-from" onchange="show_inventories('',1,2,2,'filter')" /></td>
                                                <td>&nbsp;-&nbsp;</td>
                                                <td><input name="max_width" type="number" class="w-100" value="99.99" id="width-to" onchange="show_inventories('',1,2,2,'filter')" /></td>
                                            </tr>
                                        </table>
                                    </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        Height (in)
                                        <span class="tooltip_cust">
                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            <span class="tooltiptext">
                                                <b>Min - Max:</b> This is how tall a gaylord is when assembled, from the bottom flap crease to the top flap crease. Note that gaylords taller than 47" cannot double stack in a trailer on a pallet, while gaylords shorter than 30" can be triple stacked in a trailer on pallets. As such, gaylords outside the standard range of 30"-47" are cheaper than those within the range.
                                            </span>
                                        </span>
                                    </label>
                                    <table class="form-table">
                                        <tr>
                                            <td><input name="min_height" type="number" class="w-100" value="0" id="height-from" onchange="show_inventories('',1,2,2,'filter')" /></td>
                                            <td>&nbsp;-&nbsp;</td>
                                            <td><input name="max_height" type="number" class="w-100" value="99.99" id="height-to" onchange="show_inventories('',1,2,2,'filter')" /></td>
                                        </tr>
                                    </table>
                                </div>
                                <?php if ($box_type == "Shipping") { ?>
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            Cubic Footage (in)
                                            <span class="tooltip_cust">
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                <span class="tooltiptext">Filter results by minimum and maximum Cubic Footage (in).</span>
                                            </span>
                                        </label>
                                        <table class="form-table">
                                            <tr>
                                                <td><input name="min_cubic_footage" type="number" class="w-100" value="0" id="cubic_footage-from" onchange="show_inventories('',1,2,2,'filter')" /></td>
                                                <td>&nbsp;-&nbsp;</td>
                                                <td><input name="max_cubic_footage" type="number" class="w-100" value="99.99" id="cubic_footage-to" onchange="show_inventories('',1,2,2,'filter')" /></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold">ECT/Burst
                                            <span class="tooltip_cust"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                                <span class="tooltiptext">Select to filter results for ECT/Burst.</span>
                                            </span>
                                        </label>
                                        <!--<label data-toggle="tooltip" title="Select to filter results for ECT/Burst." class="font-weight-bold">ECT/Burst</label>-->
                                        <select name="ect_burst" id="ect_burst" onchange="change_ect_burst()">
                                            <option value="all" display_val="">All</option>
                                            <option value="1" display_val="Light Duty">Light Duty (&lt 32ECT or 200# Burst) </option>
                                            <option value="2" display_val="Standard">Standard (&gt= 32ECT or 200# Burst) </option>
                                            <option value="3" display_val="Heavy Duty">Heavy Duty (&gt= 44ECT or 275# Burst) </option>
                                            <option value="4" display_val="Super Heavy Duty">Super Heavy Duty (&gt= 48ECT or 275# Burst) </option>
                                        </select>
                                    </div>
                                    <div class="form-group p-0">
                                        <label class="font-weight-bold">
                                            Walls Thick
                                            <span class="tooltip_cust">
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                <span class="tooltiptext">Select to filter results for Strength.</span>
                                            </span>
                                        </label><br>
                                        <div class="d-flex label_mb">
                                            <input class="standard_duty_1ply" type="checkbox" class="" display_val="Wall Thick: 1ply" value="1" name="wall_thickness[]" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>Standard Duty: 1ply</span>
                                        </div>
                                        <div class="d-flex label_mb">
                                            <input class="heady_duty_2ply_plus" type="checkbox" display_val="Wall Thick: 2ply+" value="2" name="wall_thickness[]" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>Heavy Duty: 2ply+</span>
                                        </div>
                                    </div>
                                <?php }
                                ?>
                                <?php if ($shown_in_client_flg == 0) { ?>
                                    <div class="form-group p-0">
                                        <label class="font-weight-bold">
                                            Sub Type
                                            <span class="tooltip_cust">
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                <span class="tooltiptext">Select to filter results for Subtype.</span>
                                            </span>
                                        </label><br>
                                        <div class="d-block">
                                            <input class="hpt-41" type="checkbox" display_val="HPT-41" value="1" name="type[]" id="hpt-41" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>HPT-41</span>
                                        </div>
                                        <div class="d-block">
                                            <input class="resin" type="checkbox" display_val="Resin" value="2" name="type[]" id="resin" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>Resin</span>
                                        </div>
                                        <div class="d-block">
                                            <input class="produce" type="checkbox" display_val="Produce" value="3" name="type[]" id="produce" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>Produce</span>
                                        </div>
                                        <div class="d-block">
                                            <input class="melon_short" type="checkbox" display_val="Melon/Short" value="4" name="type[]" id="melon_short" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>Melon/Short</span>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="form-group p-0">
                                    <label class="font-weight-bold">
                                        Uniformity
                                        <span class="tooltip_cust">
                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            <span class="tooltiptext">
                                            <b>Uniform ($$$):</b> All gaylords will be the same.<br>

                                            <b>Mixed ($):</b> There will be a mixture of different types of gaylords. The variances will be disclosed.
                                            </span>
                                        </span>
                                    </label><br>
                                    <div class="d-flex label_mb">
                                        <input class="uniform" type="checkbox" display_val="Uniform" value="1" name="uniformity[]" id="uniform" onchange="show_inventories('',1,2,2,'filter')" />
                                        <span>Uniform</span>
                                    </div>
                                    <div class="d-flex label_mb">
                                        <input class="mixed" type="checkbox" display_val="Mixed" value="2" name="uniformity[]" id="mixed" onchange="show_inventories('',1,2,2,'filter')" />
                                        <span>Mixed</span>
                                    </div>
                                </div>
                                <?php if ($box_type == "Shipping") { ?>
                                    <div class="form-group p-0">
                                        <label class="font-weight-bold">
                                            Type
                                            <span class="tooltip_cust">
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                <span class="tooltiptext">Select to filter results for Type RSC/HSC .</span>
                                            </span>
                                        </label><br>
                                        <div class="d-flex label_mb">
                                            <input class="type_rsc" type="checkbox" display_val="Type RSC" value="1" name="type_sh[]" id="type_rsc" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>RSC</span>
                                        </div>
                                        <div class="d-flex label_mb">
                                            <input class="type_hsc" type="checkbox" display_val="Type HSC" value="2" name="type_sh[]" id="type_hsc" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>HSC</span>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($box_type == "Gaylord") { ?>
                                    <div class="form-group p-0">
                                        <label class="font-weight-bold">
                                            Shape
                                            <span class="tooltip_cust">
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                <span class="tooltiptext">
                                                    <b>Rectangular:</b> Shaped like a rectangle with (4) 90deg corners. Maximizes storage volume.
                                                    <br>
                                                    <b>Octagonal: </b>Shaped like an octagon, where the corners are cut in. Better for stacking.
                                                </span>
                                            </span>
                                        </label><br>
                                        <div class="d-flex label_mb">
                                            <input class="rectangular" type="checkbox" display_val="Shape: Rectangular" value="1" name="shape[]" id="rectangular" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>Rectangular</span>
                                        </div>
                                        <div class="d-flex label_mb">
                                            <input class="octagonal" type="checkbox" display_val="Shape: Octagonal" value="2" name="shape[]" id="octagonal" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>Octagonal</span>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($box_type == "Gaylord") { ?>
                                    <div class="form-group p-0">
                                        <label class="font-weight-bold">
                                            Strength
                                            <span class="tooltip_cust">
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                <span class="tooltiptext">
                                                <b>Light Duty:</b> 1-2ply ($): Has 1-2 layers of corrugation for strength.
                                                <br>
                                                <b>Standard Duty:</b> 3ply ($$): Has 3 layers of corrugation for strength.
                                                <br>
                                                <b>Heavy Duty: 4ply ($$$):</b> Has 4 or more layers of corrugation for strength.
                                                <br>
                                                <b>Super Heavy Duty: 5ply+ ($$$$):</b> Has 5 or more layers of corrugation for strength.
                                                </span>
                                            </span>
                                        </label><br>
                                        <div class="d-flex label_mb">
                                            <input class="one_two_ply" type="checkbox" display_val="Strength: 1-2ply" value="1" name="wall_thickness[]" id="one_two_ply" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>Light Duty: 1-2ply</span>
                                        </div>
                                      
                                        <div class="d-flex label_mb">
                                            <input class="three_ply" type="checkbox" display_val="Strength: 3ply" value="2" name="wall_thickness[]" id="three_ply" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>Standard Duty: 3ply</span>
                                        </div>
                                        <div class="d-flex label_mb">
                                            <input class="four_ply" type="checkbox" display_val="Strength: 4ply" value="3" name="wall_thickness[]" id="four_ply" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>Heavy Duty: 4ply</span>
                                        </div>
                                        <div class="d-flex label_mb">
                                            <input class="five_plus_ply" type="checkbox" display_val="Strength: 5ply+ +" value="4" name="wall_thickness[]" id="five_plus_ply" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>Super Heavy Duty: 5ply+</span>
                                        </div>
                                    </div>

                                    <div class="form-group p-0">
                                        <label class="font-weight-bold">
                                            Top
                                            <span class="tooltip_cust">
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                <span class="tooltiptext">
                                                    	
                                                    <b>Full Flaps ($$$):</b> Has attached flaps which fully enclose the top of the gaylord.
                                                    <br>
                                                    <b>Partial Flaps ($$):</b> Has attached flaps which are generally only 6" long, and do not enclose the top, but make a better "shelf" for another gaylord to be stacked on top of it.
                                                    <br>
                                                    <b>Removable Lid ($$):</b> Has a removable lid which can be slid over the top of the gaylord to enclose it.
                                                    <br>
                                                    <b>No Top ($):</b> Has no flaps or lid of any kind. The top is "open."
                                                </span>
                                            </span>
                                        </label><br>
                                        <div class="d-flex label_mb">
                                            <input class="top_full_flaps" type="checkbox" display_val="Top: Full Flaps" value="4" name="top[]" id="top_full_flaps" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>Full Flaps</span>
                                        </div>
                                        <div class="d-flex label_mb">
                                            <input class="partial_flaps" type="checkbox" display_val="Top: Partial Flaps" value="3" name="top[]" id="partial_flaps" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>Partial Flaps</span>
                                        </div>
                                        <div class="d-flex label_mb">
                                            <input class="removable_lid" type="checkbox" display_val="Top: Removable Lid " value="2" name="top[]" id="removable_lid" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>Removable Lid</span>
                                        </div>
                                        <div class="d-flex label_mb">
                                            <input class="no_top" type="checkbox" display_val="Top: No Top" value="1" name="top[]" id="no_top" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>No Top</span>
                                        </div>
                                    </div>

                                    <div class="form-group p-0">
                                        <label class="font-weight-bold">
                                            Bottom
                                            <span class="tooltip_cust">
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                <span class="tooltiptext">
                                                <b>Full Flaps ($$$):</b> Has attached flaps which fully enclose the bottom of the gaylord.
                                                <br>
                                                <b>Partial Flaps w/ Slipshee ($$):</b> Has attached flaps which do not fully enclose the bottom, leaving a substantial gap between flaps. A slipsheet is included to lay over top of this gap to enclose the bottom of the gaylord. Generally much cheaper than full flap bottom gaylords.
                                                <br>
                                                <b>Partial Flaps w/o Slipsheet ($):</b> Has attached flaps which do not fully enclose the bottom, leaving a substantial gap between flaps. No slipsheet is included to lay over top of this gap to enclose the bottom of the gaylord. Generally much cheaper than gaylords which do include the slipsheet.
                                                <br>
                                                <b>Removable Tray ($):</b> Similar to a removable Lid, there is a tray which the gaylord slides into to create the bottom of the gaylord (instead of flaps).
                                                </span>
                                            </span>
                                        </label><br>
                                        <div class="d-flex label_mb">
                                            <input class="bottom_full_flaps" type="checkbox" display_val="Bottom: Full Flaps" value="4" name="bottom[]" id="bottom_full_flaps" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span> Full Flaps</span>
                                        </div>
                                        <div class="d-flex label_mb">
                                            <input class="partial_flaps_slipsheet" type="checkbox" display_val="Bottom: Partial Flaps w/ Slipsheet" value="5" name="bottom[]" id="partial_flaps_slipsheet" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>Partial Flaps w/ Slipsheet</span>
                                        </div>
                                        <div class="d-flex label_mb">
                                            <input class="no_top" type="checkbox" display_val="Bottom: Partial Flaps w/o Slipsheet" value="2" name="bottom[]" id="no_top" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>Partial Flaps w/o Slipsheet </span>
                                        </div>
                                        <div class="d-flex label_mb">
                                            <input class="removable_tray" type="checkbox" display_val="Bottom: Removable Tray " value="3" name="bottom[]" id="removable_tray" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>Removable Tray</span>
                                        </div>
                                    </div>

                                    <div class="form-group p-0">
                                        <label class="font-weight-bold">
                                            Vents
                                            <span class="tooltip_cust">
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                <span class="tooltiptext">
                                                <b>Yes ($):</b> Includes vent holes on the side of the gaylord, ranging from only a few vents to many (depending on the previous use). These vented gaylords are most prevalent in the produce industry.
                                                <br>
                                                <b>No ($$):</b> Does not have any vent holes at all in the sides of the gaylords.
                                                </span>
                                            </span>
                                        </label><br>
                                        <div class="d-flex label_mb">
                                            <input class="vents_yes" type="checkbox" display_val="Vents: Yes" value="1" name="vents[]" id="vents_yes" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>Yes</span>
                                        </div>
                                        <div class="d-flex label_mb">
                                            <input class="vents_no" type="checkbox" display_val="Vents: No" value="2" name="vents[]" id="vents_no" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>No</span>
                                        </div>
                                    </div>

                                <?php } ?>
                                
                            <?php } ?>
                                <?php if($box_type == "Gaylord" || $box_type == "Pallets" )
                                {?>
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        Grade
                                        <span class="tooltip_cust">
                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            <span class="tooltiptext">
                                            <b>A ($$$$):</b> Once used, like new, minimal cosmetic imperfections, structural integrity intact.
                                            <br>
                                            <b>B ($$):</b> Once used or more, considerable cosmetic imperfections, structural integrity intact.
                                            <br>
                                            <b>C ($):</b> Used multiple times, substantial cosmetic imperfections, structural integrity compromised but usable, may be missing a flap or two.
                                            </span>
                                        </span>
                                    </label><br>
                                    <div class="d-flex label_mb">
                                        <input class="grade_A" type="checkbox" display_val="Grade: A" value="1" name="grade[]" id="grade_A" onchange="show_inventories('',1,2,2,'filter')" />
                                        <span>A </span>
                                    </div>
                                    <div class="d-flex label_mb">
                                        <input class="grade_B" type="checkbox" display_val="Grade: B" value="2" name="grade[]" id="grade_B" onchange="show_inventories('',1,2,2,'filter')" />
                                        <span>B </span>
                                    </div>
                                    <div class="d-flex label_mb">
                                        <input class="grade_C" type="checkbox" display_val="Grade: C" value="3" name="grade[]" id="grade_C" onchange="show_inventories('',1,2,2,'filter')" />
                                        <span>C </span>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php if($box_type == "Pallets" )
                                {?>
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        Material
                                        <span class="tooltip_cust">
                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            <span class="tooltiptext">Select to filter results for Material Whitewood,Plastic,Corrugate .</span>
                                        </span>
                                    </label><br>
                                    <div class="d-flex label_mb">
                                        <input class="material_whitewood" type="checkbox" display_val="Material: Whitewood" value="1" name="material[]" id="material_whitewood" onchange="show_inventories('',1,2,2,'filter')" />
                                        <span>Whitewood</span>
                                    </div>
                                    <div class="d-flex label_mb">
                                        <input class="material_plastic" type="checkbox" display_val="Material: Plastic" value="2" name="material[]" id="material_plastic" onchange="show_inventories('',1,2,2,'filter')" />
                                        <span>Plastic</span>
                                    </div>
                                    <div class="d-flex label_mb">
                                        <input class="material_corrugate" type="checkbox" display_val="Material: Corrugate" value="3" name="material[]" id="material_corrugate" onchange="show_inventories('',1,2,2,'filter')" />
                                        <span>Corrugate</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        Entry
                                        <span class="tooltip_cust">
                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            <span class="tooltiptext">Select to filter results for Entry 4-way,2-way .</span>
                                        </span>
                                    </label><br>
                                    <div class="d-flex label_mb">
                                        <input class="entry_4_way" type="checkbox" display_val="Entry: 4-way" value="1" name="entry[]" id="entry_4_way" onchange="show_inventories('',1,2,2,'filter')" />
                                        <span>4-way</span>
                                    </div>
                                    <div class="d-flex label_mb">
                                        <input class="entry_2_way" type="checkbox" display_val="Entry: 2-way" value="2" name="entry[]" id="entry_2_way" onchange="show_inventories('',1,2,2,'filter')" />
                                        <span>2-way</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        Structure
                                        <span class="tooltip_cust">
                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            <span class="tooltiptext">Select to filter results for Structure Stringer,Block .</span>
                                        </span>
                                    </label><br>
                                    <div class="d-flex label_mb">
                                        <input class="structure_stringer" type="checkbox" display_val="Structure: Stringer" value="1" name="structure[]" id="structure_stringer" onchange="show_inventories('',1,2,2,'filter')" />
                                        <span>Stringer</span>
                                    </div>
                                    <div class="d-flex label_mb">
                                        <input class="structure_block" type="checkbox" display_val="Structure: Block" value="2" name="structure[]" id="structure_block" onchange="show_inventories('',1,2,2,'filter')" />
                                        <span>Block</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        Treatment
                                        <span class="tooltip_cust">
                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            <span class="tooltiptext">Select to filter results for Treatment Heat Treated,Not Heat Treated .</span>
                                        </span>
                                    </label><br>
                                    <div class="d-flex label_mb">
                                        <input class="treated_heat_treated" type="checkbox" display_val="Treatment: Heat Treated" value="1" name="treatment[]" id="treated_heat_treated" onchange="show_inventories('',1,2,2,'filter')" />
                                        <span>Heat Treated</span>
                                    </div>
                                    <div class="d-flex label_mb">
                                        <input class="treated_not_heat_treated" type="checkbox" display_val="Treatment: Not Heat Treated" value="2" name="treatment[]" id="treated_not_heat_treated" onchange="show_inventories('',1,2,2,'filter')" />
                                        <span>Not Heat Treated</span>
                                    </div>
                                </div>
                                <?php } ?>
                                
                                <?php if ($box_type == "Shipping" || $box_type == "Gaylord") { ?>
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            Printing
                                            <span class="tooltip_cust">
                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            <span class="tooltiptext">
                                                <b>Printing ($):</b> Box is printed with branding from previous use.
                                                    <br>
                                                <b>Plain ($$$):</b> Box is not branded, any printing if any is generic.
                                            </span>
                                            </span>
                                        </label><br>
                                        <div class="d-flex label_mb align-items-center">
                                            <input class="printing" type="checkbox" display_val="Printing" value="1" name="printing[]" id="printing" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>Printing ($) </span>
                                        </div>
                                        <div class="d-flex label_mb align-items-center">
                                            <input class="plain" type="checkbox" display_val="Plain" value="2" name="printing[]" id="plain" onchange="show_inventories('',1,2,2,'filter')" />
                                            <span>Plain ($$$) </span>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($shown_in_client_flg == 0) { ?>
                                    <div class="form-group">
                                        <label class="font-weight-bold">Tags</label><br>

                                        <select name="box_tags[]" multiple="multiple" id="box_tags">
                                            <?php

                                            db();
                                            $warehouse_get_query = db_query("select * from loop_inv_tags order by id asc");
                                            while ($warehouse = array_shift($warehouse_get_query)) {
                                                $name = $warehouse['tags'];
                                                $id = $warehouse['id'];
                                            ?>
                                                <option value="<?php echo $id ?>" display_val="<?php echo $name ?>">
                                                    <?php echo $name ?></option>
                                            <?php     } ?>
                                        </select>
                                        <button type="button" class="btn apply-filter btn-sm" id="box_tags_apply_filter">Apply
                                            tag filter</button>
                                    </div>
                                <?php } ?>

                                </form>
                            </div>
                        </div>
                </div>
                <div class="col-md-10">
                    <div class="row m-0 total_value_table">
                        <?php
                        $style_str = "width:25%";
                        if ($shown_in_client_flg == 1) {
                            $style_str = "width:18%";
                        }
                        ?>
                        <table class="table table-sm table-bordered text-center" style="<?php echo $style_str; ?>; display: revert">
                            <thead>
                                <tr>
                                    <?php if ($shown_in_client_flg == 1) { ?>
                                        <th id="heading_total" class="text-center">Qty Avail Total</th>
                                        <th id="heading_total_SKU" class="text-center">Total SKUs</th>
                                        <th id="heading_truckload" class="text-center">Qty Avail Truckloads</th>
                                    <?php } else { ?>
                                        <th id="heading_total" class="text-center">Avail Total</th>
                                        <th id="heading_truckload" class="text-center">Avail Truckloads</th>
                                        <th id="heading_frequency" class="text-center">Frequency (mo)</th>
                                        <th id="heading_frequency_ftl" class="text-center">Frequency FTL(mo)</th>
                                        <!-- <th class="text-center">Loads Available After PO</th>
									<th class="text-center">Frequency (Loads/Mo)</th> -->
                                    <?php }  ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php if ($shown_in_client_flg == 1) { ?>
                                        <td id="sub_table_total_value">0</td>
                                        <td id="total_SKU">0</td>
                                        <td id="sub_table_truckload">0</td>
                                    <?php } else { ?>
                                        <td id="total_SKU">0</td>
                                        <td id="sub_table_total_value">0</td>
                                        <td id="sub_table_truckload">0</td>
                                        <td id="sub_table_frequency">0</td>
                                        <td id="sub_table_frequency_ftl">0</td>
                                        <!-- <td id="load_av_after_po">0</td>
									<td id="frequency">0</td> -->
                                    <?php } ?>

                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row justify-content-end align-items-end m-0" id="view-control-div">
                        <div class="col-md-1 p-0">
                            <!-- <select class="bg-light w-100" id="available" name="available" onchange="show_inventories('',1)">
                                <option value="quantities"> Quantities </option>
                                <option value="available" selected>Available</option>
                                <option value="actual">Actual</option>
                                <option value="frequency">Frequency</option>
                            </select> -->
                            <input type="hidden" id="active_page_id" value="1" />
                            <input type="hidden" id="shown_in_client_flg" value="<?php echo $shown_in_client_flg; ?>" />
                        </div>
                        <!-- <div class="col-md-1 p-0 ml-3">
                            <select class="bg-light w-100" id="list_by_item" onchange="change_list_by_item()">
                                <option value="groupby"> Group by </option>
                                <option value="list-by-item" selected>List by item</option>
                                <option value="group-by-location">Group by Location</option>
                            </select>
                            <input type="hidden" id="active_page_id" value="1"/>
                        </div> -->
                        <?php if ($box_type == "Shipping" || $box_type == "Gaylord") { ?>
                            <div class="col-md-3 p-0 ml-3">
                                <select class="bg-light w-100" id="sort_by" onchange="change_sort_by()">
                                    <option value=""> Sort By </option>
                                    <option value="low-high" selected>Price: Low-High</option>
                                    <option value="high-low">Price: High-Low</option>
                                    <option value="nearest">Distance: Nearest</option>
                                    <option value="furthest">Distance: Furthest</option>
                                    <option value="freq-most-least">Frequnecy: Most-Least</option>
                                    <option value="freq-least-most">Frequency: Least-Most</option>
                                    <option value="qty-most-least">Quantity: Most-Least</option>
                                    <option value="qty-least-most">Quantity: Least-Most</option>
                                    <option value="leadtime-soonest-latest">Lead Time: Soonest-Latest</option>
                                    <option value="leadtime-latest-soonest">Lead Time: Latest-Soonest</option>
                                    <?php if ($box_type == "Shipping") { ?>
                                        <option value="length-short-long">Length: Short-Long</option>
                                        <option value="length-long-short">Length: Long-Short</option>
                                        <option value="width-thin-long">Width: Thin-Long</option>
                                        <option value="width-long-thin">Width: Long-Thin</option>
                                    <?php } ?>
                                    <option value="height-short-tall">Height: Short-Tall</option>
                                    <option value="height-tall-short">Height: Tall-Short</option>
                                    <option value="cu-small-big">Cu.Ft: Small-Big</option>
                                    <option value="cu-big-small">Cu.Ft: Big-Small</option>
                                </select>
                                <input type="hidden" id="active_page_id" value="1" />
                            </div>
                        <?php } ?>
                        <div class="col-md-1 p-0 ml-1 text-right">
                            <?php if ($shown_in_client_flg == 0) { ?>
                                <input type="hidden" id="view_type" value="table_view" />
                            <?php } ?>

                            <?php if ($shown_in_client_flg == 1) { ?>
                                <input type="hidden" id="view_type" value="grid_view" />
                            <?php } ?>

                            <button class="btn control_view_btn btn-active-view btn-sm" id="grid-view-button" onclick="change_view_type('grid_view')"><i class="fa fa-th-large"></i></button>
                            <button class="btn control_view_btn btn-sm" id="table-view-button" onclick="change_view_type('table_view')"><i class="fa fa-align-justify"></i></button>
                        </div>
                    </div>
                    <div class="products_div_main">
                        <div class="result_products" id="result_products">
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6 class="text-danger">* Prices are displayed in Full Truckload (FTL) order quantities.</h6>
                    </div>
                    <!-- <div class="col-md-12 mt-4 pagination d-none justify-content-center">
            
                </div> -->
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="available_loads_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Available Load Ship Dates</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="ship_data">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- <script src="scripts/jquery-3.7.1.min.js"></script> -->
        <!--<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
        </script>
        <!-- Include jQuery (make sure this is before Bootstrap's JS) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Include Bootstrap JS -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

        <script>
            $(document).ready(function() {
                $('[data-toggle="tooltip"]').tooltip({
                    delay: {
                        "show": 200,
                        "hide": 100
                    },
                    animation: true,
                    placement: 'right'
                });
            });
        </script>
        <!--<script src="scripts/boomerang.js"></script> -->
        <!--<script type="text/javascript" src="tagfiles/solnew.js"></script> -->
        <script>
            var selection_obj = {};
            var default_sel_gaylord = true;
            var default_sel_timing = true;
            var default_sel_warehouse = true;
            var default_sel_ect_burst = true;
            var default_sel_tags = true;

            function products_loading(load_condition) {
                /*
                if (load_condition == 1) {
                    $('#loader_text').html('Loading...<br>We are calculating the distance of every single inventory item to you, this may take a few extra second.');
                }else{
                    $('#loader_text').html('Loading...');
                }
                $('#loader').removeClass('d-none');
                //$('html, body').animate({scrollTop:0},500);
                $('#result_products').addClass('d-none');
                $('.pagination').addClass('d-none');
                */
                if (load_condition == 1) {
                    $("#loader_text").html('<br>We are calculating the distance of every single inventory item to you, this may take a few extra seconds....');
                } else {
                    $("#loader_text").html('Loading....');
                }
                $("#overlay1").css('display', 'flex');
            }

            function products_loaded() {
                /*$('#loader').addClass('d-none');
                $('#result_products').removeClass('d-none')
                $('.pagination').removeClass('d-none');
                */
                $("#overlay1").css('display', 'none');
            }

            function change_type() {
                default_sel_gaylord = false;
                show_inventories('', 1);
            }

            function change_sort_by() {
                var sort_by = $('#sort_by').val();
                if (sort_by == "nearest" || sort_by == "furthest") {
                    if ($('#txtzipcode').val() == "") {
                        var alert_msg = sort_by == "nearest" ? 'Nearest' : 'furthest';
                        alert("Enter Zipcode For " + alert_msg + " Products");
                        $('#txtzipcode').focus();
                        //$('#txtzipcode').attr("zip_for_sorting",1)
                    } else {
                        show_inventories('', 1, 2, 2, "sorting");
                    }
                } else {
                    show_inventories('', 1, 2, 2, "sorting");
                }
            }
            $('#txtzipcode').change(function() {
                var sort_by = $('#sort_by').val();
                if ($('#txtzipcode').val() != "" && (sort_by == "nearest" || sort_by == "furthest")) {
                    show_inventories('', 1);
                }
            })

            function clear_filter(clear_filter_of) {
                if (clear_filter_of == 'deliverydata') {
                    $("input[name='txtaddress']").val("");
                    $("input[name='txtaddress2']").val("");
                    $("input[name='txtcity']").val("");
                    $("input[name='txtstate']").val("");
                    $("input[name='txtcountry']").val("");
                    $("input[name='txtzipcode']").val("");
                    $("#apply-deliverydata").css('width', '100%');
                    $("#clear-deliverydata").css('display', 'none');
                    selection_obj.deliverydata = {};
                }
                show_inventories("", 1);
            }

            function clear_filter_cust(clear_filter_of) {
                if (clear_filter_of == 'deliverydata') {
                    $("input[name='cust_txtaddress']").val("");
                    $("input[name='cust_txtaddress2']").val("");
                    $("input[name='cust_txtcity']").val("");
                    $("input[name='cust_txtstate']").val("");
                    $("input[name='cust_txtzipcode']").val("");
                    $("#cust_apply-deliverydata").css('width', '100%');
                    $("#cust_clear-deliverydata").css('display', 'none');
                    selection_obj.deliverydata = {};
                }
                show_inventories("", 1);
            }

            function copy_add_from_cust_to_main() {
                document.getElementById("txtaddress").value = document.getElementById("cust_txtaddress").value;
                document.getElementById("txtaddress2").value = document.getElementById("cust_txtaddress2").value;
                document.getElementById("txtcity").value = document.getElementById("cust_txtcity").value;
                document.getElementById("txtstate").value = document.getElementById("cust_txtstate").value;
                document.getElementById("txtzipcode").value = document.getElementById("cust_txtzipcode").value;
            }

            var view_type_chg = "no";

            function change_view_type(view_type) {
                view_type_chg = "yes";
                if (view_type == "table_view") {
                    $("#view-control-div").css('width', '80%');
                    $("#products-grid-view").css("display", "none");
                    $("#products-table-view").css("display", "block");
                    $("#products-grid-view-urgent").addClass("d-none");
                    $("#products-table-view-urgent").removeClass("d-none");
                    $('#grid-view-button').removeClass('btn-active-view');
                    $('#table-view-button').addClass('btn-active-view');
                } else {
                    $("#view-control-div").css('width', '100%');
                    $("#products-table-view").css("display", "none");
                    $("#products-grid-view").css("display", "block");
                    $("#products-grid-view-urgent").removeClass("d-none");
                    $("#products-table-view-urgent").addClass("d-none");
                    $('#grid-view-button').addClass('btn-active-view');
                    $('#table-view-button').removeClass('btn-active-view');
                }
                $('#view_type').val(view_type);

                show_inventories('', 1, 1, 2, "view_type");
                functionLoaded = false;
                show_inventories('2', "", 2, 2, "view_type");

                //show_inventories("" ,1);
            }

            function change_timing() {
                default_sel_timing = false;
                var timing = $("select[name='timing']").val();
                if (timing == 9) {
                    $('#timing_date_div').removeClass('d-none');
                } else {
                    $('#timing_date_div').addClass('d-none');
                    show_inventories("", 1);
                }
            }

            function change_warehouse() {
                default_sel_warehouse = false;
                var warehouse = $("select[name='warehouse']").val();
                //if(warehouse!='all'){
                show_inventories("", 1, 2, 2, 'filter');
                //}
            }

            function change_ect_burst() {
                default_sel_ect_burst = false;
                var ect_burst = $("select[name='ect_burst']").val();
                //if(warehouse!='all'){
                show_inventories("", 1, 2, 2, 'filter');
                //}
            }

            $('#apply-deliverydata').on('click', function() {
                if ($('#txtzipcode').val() == '') {
                    alert('Please Enter zipcode');
                }
            })

            var box_tag = [];
            $("#box_tags_apply_filter").click(function() {
                $.each($("#box_tags option:selected"), function() {
                    box_tag.push($(this).val());
                });
                show_inventories("", 1);
            });


            let functionLoaded = false;

            var totalPages = 0;
            var numberOfItems = 0;
            var currentPage;

            // function validateAddressAndApply() {
            //     // Collect address data
            //     var addressData = {
            //         streetLines: [document.getElementById("shipping_add1").value],
            //         city: document.getElementById("shipping_city").value,
            //         stateOrProvinceCode: document.getElementById("shipping_state").value,
            //         postalCode: document.getElementById("cust_txtzipcode").value, // ZIP code input
            //         countryCode: 'US' // Set your country code here
            //     };

            //     // AJAX call to validate the address
            //     $.ajax({
            //         url: 'fedex_address_validation_for_plp.php', // Point to your validation PHP file
            //         method: "POST",
            //         data: JSON.stringify(addressData),
            //         contentType: 'application/json',
            //         success: function(response) {
            //             console.log("Response from FedEx:", response); // Log response

            //             if (response.isValid) {
            //                 // Address is valid; proceed with the next operations
            //                 alert("Address is valid. Proceeding to apply.");
            //                 // Your code for applying the address
            //                 copy_add_from_cust_to_main();
            //                 show_inventories('', 1, 1, 1, 'address_change');
            //                 functionLoaded = false;
            //                 show_inventories('2', 1, 2, 1, 'address_change');
            //             } else {
            //                 // Show validation error
            //                 alert('Address validation failed: ' + response.message);
            //             }
            //         },
            //         error: function(jqXHR, textStatus, errorThrown) {
            //             if (jqXHR.status === 404) {
            //                 alert('Error: The address validation service is not found (404). Please check the URL: fedex_address_validation.php');
            //             } else {
            //                 alert('Error contacting FedEx for address validation: ' + textStatus + ' - ' + errorThrown);
            //             }
            //         }
            //     });
            // }

            function validateAddressAndApply() {
                // Collect address data
                var addressData = {
                    streetLines: [document.getElementById("cust_txtaddress").value],
                    city: document.getElementById("cust_txtcity").value,
                    stateOrProvinceCode: document.getElementById("cust_txtstate").value,
                    postalCode: document.getElementById("cust_txtzipcode").value
                    // countryCode: 'US' // Set your country code here
                };

                // AJAX call to validate the address
                $.ajax({
                    url: 'fedex_address_validation_for_plp.php', // Point to your validation PHP file
                    method: "POST",
                    data: JSON.stringify(addressData),
                    contentType: 'application/json',
                    success: function(response) {
                        console.log("Response from FedEx:", response); // Log response

                        if (response.isValid) {
                            // Address is valid; proceed with the next operations
                            // alert("Address is valid. Proceeding to apply.");
                            copy_add_from_cust_to_main();
                            show_inventories('', 1, 1, 1, 'address_change');
                            functionLoaded = false;
                            show_inventories('2', 1, 2, 1, 'address_change');
                        } else {
                            // Show validation error
                            alert('Address validation failed: ' + response.message);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status === 404) {
                            alert('Error: The address validation service is not found (404). Please check the URL: fedex_address_validation.php');
                        } else {
                            alert('Error contacting FedEx for address validation: ' + textStatus + ' - ' + errorThrown);
                        }
                    }
                });
            }

            function show_inventories(reset_form = "", filter = "", show_urgent_box = 2, load_condition = 2, applied_changes = "") {

                /*if ($('#loader').hasClass('d-none')) {} else {
                    if (functionLoaded != false) {
                        alert("Please wait as page is loading.");
                        return false;
                    }
                }
                    */

                products_loading(load_condition);

                var no_data = false;
                if (filter == 1) {
                    $("#active_page_id").val(1);
                }
                if (reset_form == 1) {
                    selection_str = "";
                }
                var active_page_id = $("#active_page_id").val();
                var view_type = $("#view_type").val();
                var shown_in_client_flg = $("#shown_in_client_flg").val();

                selection_str = "";
                var box_type = "<?php echo $box_type; ?>";
                //alert(box_type);
                var box_subtype = "all";

                var sort_by = $("#sort_by").val();
                var available = ""; //$("#available").val();
                var list_by_item = $("#list_by_item").val();

                var txtaddress = $("input[name='txtaddress']").val();
                var txtaddress2 = $("input[name='txtaddress2']").val();
                var txtcity = $("input[name='txtcity']").val();
                var txtstate = $("select[name='txtstate']").find(':selected').attr('display_val');
                if (!txtstate) {
                    var txtstate = $("input[name='txtstate']").val();
                }
                var txtcountry = $("select[name='txtcountry']").find(':selected').attr('display_val');
                var txtzipcode = $("input[name='txtzipcode']").val();

                if (view_type_chg == "no") {
                    if ($("input[name='txtzipcode']").val() != "" && (reset_form == 2 || show_urgent_box == 1)) {
                        $("#sort_by").val('nearest');
                        sort_by = 'nearest';
                    }
                }

                if ($("input[name='txtzipcode']").val() == "" && show_urgent_box == 1) {
                    $("#sort_by").val('qty-most-least');
                    sort_by = 'qty-most-least';
                }

                var warehouse = $("select[name='warehouse']").val();
                var warehouse_display_val = "Warehouse: " + $("select[name='warehouse']").find(':selected').attr('display_val');
                if (default_sel_warehouse || warehouse == "") {
                    selection_obj.warehouse = {};
                } else if (warehouse != "all") {
                    selection_obj.warehouse = {
                        'input_type': 'select',
                        'input_name': 'warehouse',
                        data: warehouse,
                        'display_val': warehouse_display_val
                    };
                }

                var ect_burst = $("select[name='ect_burst']").val();
                var ect_burst_display_val = "Ect Burst: " + $("select[name='ect_burst']").find(':selected').attr('display_val');
                // alert(ect_burst_display_val);
                if (default_sel_ect_burst || ect_burst == "") {
                    selection_obj.ect_burst = {};
                } else if (ect_burst != "all") {
                    selection_obj.ect_burst = {
                        'input_type': 'select',
                        'input_name': 'ect_burst',
                        data: ect_burst,
                        'display_val': ect_burst_display_val
                    };
                }

                var timing = "";
                var display_val = "";
                if ($('#timing').length) {
                    timing = $("select[name='timing']").val();
                    display_val = $("select[name='timing']").find(':selected').attr('display_val');
                } else {
                    timing = "6";
                    display_val = "6";
                    selection_obj.timing = {
                        'input_type': 'select',
                        'input_name': 'timing',
                        data: timing,
                        'display_val': display_val
                    };
                }
                var selected_date = "";
                if (default_sel_timing || timing == "") {
                    selection_obj.timing = {};
                } else if (timing != "") {
                    alert("test 3");
                    if (timing == 9) {
                        var selected_date = $('#timing_date').val();
                        if (selected_date == "") {
                            $('#timing_date_error').removeClass('d-none');
                            alert('Please enter ship by date timimg');
                            // return false;
                        } else {
                            $('#timing_date_error').addClass('d-none');
                            selection_obj.timing = {
                                'input_type': 'select',
                                'input_name': 'timing',
                                data: timing,
                                'display_val': display_val,
                                selected_date
                            };
                        }
                    } else {
                        selection_obj.timing = {
                            'input_type': 'select',
                            'input_name': 'timing',
                            data: timing,
                            'display_val': display_val
                        };
                    }
                }

                var include_FTL_Rdy_Now_Only = "";
                if ($('#include_FTL_Rdy_Now_Only').is(':checked')) {
                    include_FTL_Rdy_Now_Only = $("#include_FTL_Rdy_Now_Only").val();
                    var classname = $("#include_FTL_Rdy_Now_Only").attr('class');
                    var display_val = $("#include_FTL_Rdy_Now_Only").attr('display_val');
                    selection_obj.include_FTL_Rdy_Now_Only = {
                        'input_type': 'checkbox',
                        'input_name': 'include_FTL_Rdy_Now_Only',
                        'classname': classname,
                        data: include_FTL_Rdy_Now_Only,
                        'display_val': display_val
                    };
                } else {
                    selection_obj.include_FTL_Rdy_Now_Only = {};
                }

                var include_sold_out_items = "";
                if ($('#include_sold_out_items').is(':checked')) {
                    include_sold_out_items = $("#include_sold_out_items").val();
                    var classname = $("#include_sold_out_items").attr('class');
                    var display_val = $("#include_sold_out_items").attr('display_val');
                    selection_obj.include_sold_out_items = {
                        'input_type': 'checkbox',
                        'input_name': 'include_sold_out_items',
                        'classname': classname,
                        data: include_sold_out_items,
                        'display_val': display_val
                    };
                } else {
                    selection_obj.include_sold_out_items = {};
                }

                var ltl_allowed = "";
                if ($('#ltl_allowed').is(':checked')) {
                    ltl_allowed = $("#ltl_allowed").val();
                    var classname = $("#ltl_allowed").attr('class');
                    var display_val = $("#ltl_allowed").attr('display_val');
                    selection_obj.ltl_allowed = {
                        'input_type': 'checkbox',
                        'input_name': 'ltl_allowed',
                        'classname': classname,
                        data: ltl_allowed,
                        'display_val': display_val
                    };
                } else {
                    selection_obj.ltl_allowed = {};
                }
                var customer_pickup_allowed = "";
                if ($('#customer_pickup_allowed').is(':checked')) {
                    customer_pickup_allowed = $("#customer_pickup_allowed").val();
                    var classname = $("#customer_pickup_allowed").attr('class');
                    var display_val = $("#customer_pickup_allowed").attr('display_val');
                    selection_obj.customer_pickup_allowed = {
                        'input_type': 'checkbox',
                        'input_name': 'customer_pickup_allowed',
                        'classname': classname,
                        data: customer_pickup_allowed,
                        display_val: display_val
                    };
                } else {
                    selection_obj.customer_pickup_allowed = {};
                }
                var urgent_clearance = "";
                if ($('#urgent_clearance').is(':checked')) {
                    urgent_clearance = $("#urgent_clearance").val();
                    var classname = $("#urgent_clearance").attr('class');
                    var display_val = $("#urgent_clearance").attr('display_val');
                    selection_obj.urgent_clearance = {
                        'input_type': 'checkbox',
                        'input_name': 'urgent_clearance',
                        'classname': classname,
                        data: urgent_clearance,
                        display_val: display_val
                    };
                } else {
                    selection_obj.urgent_clearance = {};
                }
                if ($("input[name='min_price_each']").length > 0) {
                    var min_price_each = $("input[name='min_price_each']").val().replace("$", "");
                    var max_price_each = $("input[name='max_price_each']").val().replace("$", "");
                    if (min_price_each != 0.00 || max_price_each != 99.99) {
                        selection_obj.price_each = {
                            'input_type': 'text',
                            'input_name': 'price_each',
                            data: [min_price_each, max_price_each]
                        };
                    } else {
                        selection_obj.price_each = {};
                    }
                }
                if ($("input[name='min_height']").length > 0) {
                    var min_height = $("input[name='min_height']").val();
                    var max_height = $("input[name='max_height']").val();
                    if (min_height != 0 || max_height != 99.99) {
                        selection_obj.height = {
                            'input_type': 'text',
                            'input_name': 'height',
                            data: [min_height, max_height]
                        };
                    } else {
                        selection_obj.height = {};
                    }
                }
                if ($("input[name='min_width']").length > 0) {
                    var min_width = $("input[name='min_width']").val();
                    var max_width = $("input[name='max_width']").val();
                    if (min_width != 0 || max_width != 99.99) {
                        selection_obj.width = {
                            'input_type': 'text',
                            'input_name': 'width',
                            data: [min_width, max_width]
                        };
                    } else {
                        selection_obj.width = {};
                    }
                }
                if ($("input[name='min_length']").length > 0) {
                    var min_length = $("input[name='min_length']").val();
                    var max_length = $("input[name='max_length']").val();
                    if (min_length != 0 || max_length != 99.99) {
                        selection_obj.length = {
                            'input_type': 'text',
                            'input_name': 'length',
                            data: [min_length, max_length]
                        };
                    } else {
                        selection_obj.length = {};
                    }
                }

                if ($("input[name='min_cubic_footage']").length > 0) {
                    var min_cubic_footage = $("input[name='min_cubic_footage']").val();
                    var max_cubic_footage = $("input[name='max_cubic_footage']").val();
                    if (min_cubic_footage != 0 || max_cubic_footage != 99.99) {
                        selection_obj.cubic_footage = {
                            'input_type': 'text',
                            'input_name': 'cubic_footage',
                            data: [min_cubic_footage, max_cubic_footage]
                        };
                    } else {
                        selection_obj.cubic_footage = {};
                    }
                }

                var wall_thickness = [];
                var all_thickness_data = [];
                if ($("input:checkbox[name='wall_thickness[]']").filter(':checked').length > 0) {
                    $("input[name='wall_thickness[]']:checked").each(function() {
                        var classname = $(this).attr('class');
                        var display_val = $(this).attr('display_val');
                        wall_thickness.push({
                            'values': $(this).val(),
                            'classname': classname,
                            display_val
                        });
                        all_thickness_data.push($(this).val());
                    });
                    selection_obj.wall_thickness = {
                        'input_type': 'checkbox',
                        'input_name': 'wall_thickness',
                        data: wall_thickness
                    };
                } else {
                    selection_obj.wall_thickness = {};
                }

                var type = [];
                var all_type_data = [];
                if ($("input:checkbox[name='type[]']").filter(':checked').length > 0) {
                    $("input[name='type[]']:checked").each(function() {
                        var classname = $(this).attr('class');
                        var display_val = $(this).attr('display_val');
                        type.push({
                            'values': $(this).val(),
                            'classname': classname,
                            display_val
                        });
                        all_type_data.push($(this).val());
                    });
                    selection_obj.type = {
                        'input_type': 'checkbox',
                        'input_name': 'type',
                        data: type
                    };
                } else {
                    selection_obj.type = {};
                }

                var uniformity = [];
                var all_uniformity_data = [];
                if ($("input:checkbox[name='uniformity[]']").filter(':checked').length > 0) {
                    $("input[name='uniformity[]']:checked").each(function() {
                        var classname = $(this).attr('class');
                        var display_val = $(this).attr('display_val');
                        uniformity.push({
                            'values': $(this).val(),
                            'classname': classname,
                            display_val
                        });
                        all_uniformity_data.push($(this).val());
                    });
                    selection_obj.uniformity = {
                        'input_uniformity': 'checkbox',
                        'input_name': 'uniformity',
                        data: uniformity
                    };
                } else {
                    selection_obj.uniformity = {};
                }

                var shape = [];
                var all_shape_data = [];
                if ($("input:checkbox[name='shape[]']").filter(':checked').length > 0) {
                    $("input[name='shape[]']:checked").each(function() {
                        var classname = $(this).attr('class');
                        var display_val = $(this).attr('display_val');
                        shape.push({
                            'values': $(this).val(),
                            'classname': classname,
                            display_val
                        });
                        all_shape_data.push($(this).val());
                    });
                    selection_obj.shape = {
                        'input_type': 'checkbox',
                        'input_name': 'shape',
                        data: shape
                    };
                } else {
                    selection_obj.shape = {};
                }

                var type_sh = [];
                var all_type_sh_data = [];
                if ($("input:checkbox[name='type_sh[]']").filter(':checked').length > 0) {
                    $("input[name='type_sh[]']:checked").each(function() {
                        var classname = $(this).attr('class');
                        var display_val = $(this).attr('display_val');
                        type_sh.push({
                            'values': $(this).val(),
                            'classname': classname,
                            display_val
                        });
                        all_type_sh_data.push($(this).val());
                    });
                    selection_obj.type_sh = {
                        'input_type': 'checkbox',
                        'input_name': 'type_sh',
                        data: type_sh
                    };
                } else {
                    selection_obj.type_sh = {};
                }

                var all_top_data = [];
                var top = [];
                if ($("input:checkbox[name='top[]']").filter(':checked').length > 0) {
                    $("input[name='top[]']:checked").each(function() {
                        var classname = $(this).attr('class');
                        var display_val = $(this).attr('display_val');
                        top.push({
                            'values': $(this).val(),
                            'classname': classname,
                            display_val
                        });
                        all_top_data.push($(this).val());

                    });
                    selection_obj.top = {
                        'input_type': 'checkbox',
                        'input_name': 'top',
                        data: top
                    };
                } else {
                    selection_obj.top = {};
                }

                var bottom = [];
                var all_bottom_data = [];
                if ($("input:checkbox[name='bottom[]']").filter(':checked').length > 0) {
                    $("input[name='bottom[]']:checked").each(function() {
                        var classname = $(this).attr('class');
                        var display_val = $(this).attr('display_val');
                        bottom.push({
                            'values': $(this).val(),
                            'classname': classname,
                            display_val
                        });
                        all_bottom_data.push($(this).val());
                    });
                    selection_obj.bottom = {
                        'input_type': 'checkbox',
                        'input_name': 'bottom',
                        data: bottom
                    };
                } else {
                    selection_obj.bottom = {};
                }

                var vents = [];
                var all_vents_data = [];
                if ($("input:checkbox[name='vents[]']").filter(':checked').length > 0) {
                    $("input[name='vents[]']:checked").each(function() {
                        var classname = $(this).attr('class');
                        var display_val = $(this).attr('display_val');
                        vents.push({
                            'values': $(this).val(),
                            'classname': classname,
                            display_val
                        });
                        all_vents_data.push($(this).val());
                    });
                    selection_obj.vents = {
                        'input_type': 'checkbox',
                        'input_name': 'vents',
                        data: vents
                    };
                } else {
                    selection_obj.vents = {};
                }

                var grade = [];
                var all_grade_data = [];
                if ($("input:checkbox[name='grade[]']").filter(':checked').length > 0) {
                    $("input[name='grade[]']:checked").each(function() {
                        var classname = $(this).attr('class');
                        var display_val = $(this).attr('display_val');
                        grade.push({
                            'values': $(this).val(),
                            'classname': classname,
                            display_val
                        });
                        all_grade_data.push($(this).val());
                    });
                    selection_obj.grade = {
                        'input_type': 'checkbox',
                        'input_name': 'grade',
                        data: grade
                    };
                } else {
                    selection_obj.grade = {};
                }

                var material = [];
                var all_material_data = [];
                if ($("input:checkbox[name='material[]']").filter(':checked').length > 0) {
                    $("input[name='material[]']:checked").each(function() {
                        var classname = $(this).attr('class');
                        var display_val = $(this).attr('display_val');
                        material.push({
                            'values': $(this).val(),
                            'classname': classname,
                            display_val
                        });
                        all_material_data.push($(this).val());
                    });
                    selection_obj.material = {
                        'input_type': 'checkbox',
                        'input_name': 'material',
                        data: material
                    };
                } else {
                    selection_obj.material = {};
                }

                var entry = [];
                var all_entry_data = [];
                if ($("input:checkbox[name='entry[]']").filter(':checked').length > 0) {
                    $("input[name='entry[]']:checked").each(function() {
                        var classname = $(this).attr('class');
                        var display_val = $(this).attr('display_val');
                        entry.push({
                            'values': $(this).val(),
                            'classname': classname,
                            display_val
                        });
                        all_entry_data.push($(this).val());
                    });
                    selection_obj.entry = {
                        'input_type': 'checkbox',
                        'input_name': 'entry',
                        data: entry
                    };
                } else {
                    selection_obj.entry = {};
                }

                var structure = [];
                var all_structure_data = [];
                if ($("input:checkbox[name='structure[]']").filter(':checked').length > 0) {
                    $("input[name='structure[]']:checked").each(function() {
                        var classname = $(this).attr('class');
                        var display_val = $(this).attr('display_val');
                        structure.push({
                            'values': $(this).val(),
                            'classname': classname,
                            display_val
                        });
                        all_structure_data.push($(this).val());
                    });
                    selection_obj.structure = {
                        'input_type': 'checkbox',
                        'input_name': 'structure',
                        data: structure
                    };
                } else {
                    selection_obj.structure = {};
                }

                var treatment = [];
                var all_treatment_data = [];
                if ($("input:checkbox[name='treatment[]']").filter(':checked').length > 0) {
                    $("input[name='treatment[]']:checked").each(function() {
                        var classname = $(this).attr('class');
                        var display_val = $(this).attr('display_val');
                        treatment.push({
                            'values': $(this).val(),
                            'classname': classname,
                            display_val
                        });
                        all_treatment_data.push($(this).val());
                    });
                    selection_obj.treatment = {
                        'input_type': 'checkbox',
                        'input_name': 'treatment',
                        data: treatment
                    };
                } else {
                    selection_obj.treatment = {};
                }
                var include_presold_and_loops = [];
                var all_include_presold_and_loops_data = [];
                if ($("input:checkbox[name='include_presold_and_loops[]']").filter(':checked').length > 0) {
                    $("input[name='include_presold_and_loops[]']:checked").each(function() {
                        var classname = $(this).attr('class');
                        var display_val = $(this).attr('display_val');
                        include_presold_and_loops.push({
                            'values': $(this).val(),
                            'classname': classname,
                            display_val
                        });
                        all_include_presold_and_loops_data.push($(this).val());
                    });
                    selection_obj.include_presold_and_loops = {
                        'input_type': 'checkbox',
                        'input_name': 'include_presold_and_loops',
                        data: include_presold_and_loops
                    };
                } else {
                    selection_obj.include_presold_and_loops = {};
                }

                var status = [];
                var all_status_data = [];
                if ($("input:checkbox[name='status[]']").filter(':checked').length > 0) {
                    $("input[name='status[]']:checked").each(function() {
                        var classname = $(this).attr('class');
                        var display_val = $(this).attr('display_val');
                        status.push({
                            'values': $(this).val(),
                            'classname': classname,
                            display_val
                        });
                        all_status_data.push($(this).val());
                    });
                    selection_obj.status = {
                        'input_type': 'checkbox',
                        'input_name': 'status',
                        data: status
                    };
                } else {
                    selection_obj.status = {};
                }

                var printing = [];
                var all_printing_data = [];
                if ($("input:checkbox[name='printing[]']").filter(':checked').length > 0) {
                    $("input[name='printing[]']:checked").each(function() {
                        var classname = $(this).attr('class');
                        var display_val = $(this).attr('display_val');
                        printing.push({
                            'values': $(this).val(),
                            'classname': classname,
                            display_val
                        });
                        all_printing_data.push($(this).val());
                    });
                    selection_obj.printing = {
                        'input_type': 'checkbox',
                        'input_name': 'printing',
                        data: printing
                    };
                } else {
                    selection_obj.printing = {};
                }

                var box_tags_display_val = $("select[id='box_tags']").find(':selected').attr('display_val');
                //alert(box_tags_display_val);
                //if (box_tag.length > 0){
                if (box_tags_display_val) {
                    selection_obj.box_tags = {
                        'input_type': 'select',
                        'input_name': 'box_tags',
                        data: box_tags,
                        'display_val': box_tags_display_val
                    };
                } else {
                    box_tag = [];
                    selection_obj.box_tags = {};
                }

                $.each(selection_obj, function(k, v) {
                    if (Object.keys(v).length != 0) {
                        if (v.input_name == "include_sold_out_items" || v.input_name ==
                            "include_FTL_Rdy_Now_Only" || v.input_name == "ltl_allowed" || v.input_name ==
                            "customer_pickup_allowed" || v.input_name == "urgent_clearance") {
                            selection_str += "<p class='added_selection' classname='" + v.classname +
                                "' respective_input='" + v.input_name + "' respective_type='" + v.input_type +
                                "'><span> " + v.display_val +
                                " </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
                        } else if (v.input_name == "warehouse") {
                            selection_str += "<p class='added_selection' id='sel_warehouse_id' respective_input='" +
                                v.input_name + "' respective_type='" + v.input_type + "'><span>" + v.display_val +
                                " </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
                        } else if (v.input_name == "ect_burst") {
                            selection_str += "<p class='added_selection' id='sel_ect_burst_id' respective_input='" +
                                v.input_name + "' respective_type='" + v.input_type + "'><span>" + v.display_val +
                                " </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
                        } else if (v.input_name == "box_tags") {
                            selection_str += "<p class='added_selection' respective_input='" + v.input_name +
                                "' respective_type='" + v.input_type +
                                "'><span>Box Tags</span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
                        } else if (v.input_name == "timing") {
                            if (v.data == 9) {
                                selection_str += "<p class='added_selection' respective_input='" + v.input_name +
                                    "' respective_type='" + v.input_type + "'><span>" + v.display_val + "( " + v
                                    .selected_date +
                                    " ) </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
                            } else {
                                selection_str += "<p class='added_selection' respective_input='" + v.input_name +
                                    "' respective_type='" + v.input_type + "'><span>" + v.display_val +
                                    " </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
                            }
                        } else if (v.input_name == "wall_thickness" || v.input_name == "type" || v.input_name ==
                            "uniformity" || v.input_name == "shape" || v.input_name == "type_sh" || v.input_name == "top" || v.input_name ==
                            "bottom" || v.input_name == "vents" || v.input_name == "grade" || v.input_name == "include_presold_and_loops" 
                            || v.input_name == "treatment" || v.input_name == "status" || v.input_name == "material" || v.input_name == "entry" || v.input_name == "structure") {
                            for (var x = 0; x < v.data.length; x++) {
                                selection_str += "<p class='added_selection' classname='" + v.data[x].classname +
                                    "' respective_input='" + v.input_name + "' respective_type='checkbox'><span> " +
                                    v.data[x].display_val +
                                    " </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
                            }
                        } else if (v.input_name == "printing") {
                            for (var x = 0; x < v.data.length; x++) {
                                selection_str += "<p class='added_selection' classname='" + v.data[x].classname +
                                    "' respective_input='" + v.input_name + "' respective_type='" + v.input_type +
                                    "'><span> " + v.data[x].display_val +
                                    " </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
                            }
                        } else if (v.input_name == "price_each") {
                            var min_price_each = $("input[name='min_price_each']").val();
                            var max_price_each = $("input[name='max_price_each']").val();
                            var price_val_str = `Price: $${min_price_each} - $${max_price_each}`;
                            selection_str += "<p class='added_selection' respective_input='" + v.input_name +
                                "' respective_type='" + v.input_type + "'><span class='text-capitalize'>" + price_val_str +
                                "</span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";

                        } else if (v.input_name == "height") {
                            var min_height = $("input[name='min_height']").val();
                            var max_height = $("input[name='max_height']").val();
                            var height_val_str = `Height: ${min_height}in - ${max_height}in`;
                            selection_str += "<p class='added_selection' respective_input='" + v.input_name +
                                "' respective_type='" + v.input_type + "'><span class='text-capitalize'>" + height_val_str +
                                "</span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";

                        } else if (v.input_name == "length") {
                            var min_length = $("input[name='min_length']").val();
                            var max_length = $("input[name='max_length']").val();
                            var length_val_str = `Length: ${min_length}in - ${max_length}in`;
                            selection_str += "<p class='added_selection' respective_input='" + v.input_name +
                                "' respective_type='" + v.input_type + "'><span class='text-capitalize'>" + length_val_str +
                                "</span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";

                        } else if (v.input_name == "width") {
                            var min_width = $("input[name='min_width']").val();
                            var max_width = $("input[name='max_width']").val();
                            var width_val_str = `Width: ${min_width}in - ${max_width}in`;
                            selection_str += "<p class='added_selection' respective_input='" + v.input_name +
                                "' respective_type='" + v.input_type + "'><span class='text-capitalize'>" + width_val_str +
                                "</span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";

                        } else if (v.input_name == "cubic_footage") {
                            var min_cubic_footage = $("input[name='min_cubic_footage']").val();
                            var max_cubic_footage = $("input[name='max_cubic_footage']").val();
                            var cubic_footage_val_str = `cubic_footage: ${min_cubic_footage}in - ${max_cubic_footage}in`;
                            selection_str += "<p class='added_selection' respective_input='" + v.input_name +
                                "' respective_type='" + v.input_type + "'><span class='text-capitalize'>" + cubic_footage_val_str +
                                "</span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";

                        } else {
                            selection_str += "<p class='added_selection' respective_input='" + v.input_name +
                                "' respective_type='" + v.input_type + "'><span class='text-capitalize'>" + v
                                .input_name +
                                "</span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
                        }
                    }
                });

                $('#selections').html(selection_str);
                if (selection_str != "") {
                    $('#your-selections').css('display', "block");
                } else {
                    $('#your-selections').css('display', "none");
                }
                var user_id = `<?php echo $user_id; ?>`;

                var data = {
                    applied_changes,
                    show_urgent_box,
                    box_type,
                    view_type,
                    active_page_id,
                    box_subtype,
                    sort_by,
                    available,
                    list_by_item,
                    txtaddress,
                    txtaddress2,
                    txtcity,
                    txtstate,
                    txtcountry,
                    txtzipcode,
                    warehouse,
                    ect_burst,
                    timing,
                    selected_date,
                    include_sold_out_items,
                    include_FTL_Rdy_Now_Only,
                    all_include_presold_and_loops_data,
                    all_status_data,
                    ltl_allowed,
                    customer_pickup_allowed,
                    urgent_clearance,
                    min_height,
                    max_height,
                    min_length,
                    max_length,
                    min_width,
                    max_width,
                    min_cubic_footage,
                    max_cubic_footage,
                    min_price_each,
                    max_price_each,
                    all_thickness_data,
                    all_type_data,
                    all_uniformity_data,
                    all_shape_data,
                    all_type_sh_data,
                    all_top_data,
                    all_bottom_data,
                    all_vents_data,
                    all_grade_data,
                    all_structure_data,
                    all_treatment_data,
                    all_entry_data,
                    all_material_data,
                    all_printing_data,
                    box_tag,
                    shown_in_client_flg,
                    user_id
                };

                /*
                 var xhr = new XMLHttpRequest();
                 xhr.open('POST', 'product_result_new1.php', true); // True makes it an async request
                 xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                 xhr.onload = function() {
                     if(show_urgent_box == 2){
                         products_loaded();
                     }
                     if (xhr.status >= 200 && xhr.status < 300) {
                         // Success
                         var response = JSON.parse(xhr.responseText);
                         //console.log('Success:', response);
                         display_inventory_data(response, show_urgent_box, view_type);
                     } else {
                         console.error('Error:', xhr.statusText);
                     }
                 };

                 // Handle network errors
                 xhr.onerror = function() {
                     console.error('Request failed');
                 };

                 // Send the JSON data
                 xhr.send(toUrlEncoded(data));
                 */
                $.ajax({
                    url: 'product_result_new1.php',
                    type: 'get',
                    data: data,
                    datatype: 'json',
                    success: function(result) {
                        console.log(result);
                        var response = JSON.parse(result);
                        //console.log('Success:', response);
                        display_inventory_data(response, show_urgent_box, view_type, applied_changes);
                    },
                    complete: function() {
                        if (no_data == false) {
                            $('#txtzipcode').removeAttr("zip_for_sorting");
                            if (show_urgent_box != 1) {
                                products_loaded();
                            }
                        } else {
                            if (show_urgent_box != 1) {
                                products_loaded();
                            }
                        }

                    },
                })
                functionLoaded = true;
            }

            function toUrlEncoded(data) {
                return Object.keys(data)
                    .map(key => encodeURIComponent(key) + '=' + encodeURIComponent(data[key]))
                    .join('&');
            }

            function display_inventory_data(all_data, show_urgent_box, view_type, applied_changes) {
                result_str = "";
                //var all_data = JSON.parse(response);
                var result = all_data.data;
                var no_of_pages = all_data.no_of_pages;
                numberOfItems = all_data.total_items;
                limitPerPage = 15;
                totalPages = Math.ceil(numberOfItems / limitPerPage);
                if (show_urgent_box != 1 && applied_changes == "refresh") {
                    $('#warehouse').html(all_data.warehouse_filter_dp);
                }
                if (!$.isArray(result) || result.length == 0) {
                    if (show_urgent_box != 1) {
                        result_str += '<div class="col-md-12 mt-5 alert alert-danger">';
                        result_str += '<h6 class="mb-0">No inventory available with these specific filters. Update filters to check other available inventory near you.</h6>';
                        result_str += '</div>';
                    }
                    no_data = true;
                } else {
                    var sub_table_total_value = 0;
                    var sub_table_truckload = 0;
                    var sub_table_frequency = 0;
                    var sub_table_frequency_ftl = 0;
                    var default_img = "images/boomerang/default_product.jpg";

                    if (view_type == "grid_view") {
                        var shown_in_client_flg = <?php echo json_encode($shown_in_client_flg); ?>;
                        result_str += '<div id="products-grid-view">';
                        result_str += '<div class="row">';
                        sub_table_total_value = 0;
                        $.each(result, function(index, res) {
                            var address_details = "";

                            if (shown_in_client_flg == 1) {
                                if (document.getElementById("address_book")) {
                                    var enter_add_id = document.getElementById("address_book").value;

                                    var enter_add1 = document.getElementById("txtaddress").value;
                                    var enter_add2 = document.getElementById("txtaddress2").value
                                    var enter_txtcity = document.getElementById("txtcity").value
                                    var enter_txtstate = document.getElementById("txtstate").value
                                    var enter_txtzipcode = document.getElementById("txtzipcode").value

                                    var address_details = "enter_add1=" + enter_add1 +
                                        "&enter_add2=" + enter_add2 + "&enter_txtcity=" +
                                        enter_txtcity + "&enter_txtstate=" + enter_txtstate +
                                        "&enter_txtzipcode=" + enter_txtzipcode +
                                        "&enter_add_id=" + enter_add_id;
                                }
                            }

                            var minfobtxt = "";
                            if ((res["minfob"] == 0)) {
                                minfobtxt = "Inquire";
                            } else {
                                minfobtxt = res["price"];
                            }

                            var td_bg_color = "";
                            if (res["td_bg"] == "green") {
                                td_bg_color = " color: green;";
                            }
                            perofftl = "";
                            if (res['percent_per_load'] > 0) {
                                perofftl = res['percent_per_load'];
                            }
                            sub_table_total_value = sub_table_total_value + parseFloat(res[
                                'qtynumbervalue']);
                            sub_table_truckload += parseFloat(res['percent_per_load']);
                            sub_table_frequency += parseFloat(res['frequency_sort']);
                            sub_table_frequency_ftl += parseFloat(res['frequency_ftl']);

                            result_str +=
                                '<div class="col-lg-3 col-md-4 col-sm-6 mt-4 product-box-grid">';
                            result_str += '<div class="product-bg-light">';
                            var prod_src = res['img'] == "" ? default_img :
                                "https://loops.usedcardboardboxes.com/boxpics_thumbnail/" + res['img'];
                            result_str +=
                                '<a target="_blank" href="https://b2b.usedcardboardboxes.com/index_new.php?id=' +
                                res['loop_id_encrypt_str'] +
                                '&uid=<?php echo urlencode(encrypt_password($user_id)); ?>&' +
                                address_details + '"><img src="' + prod_src +
                                '" class="img-fluid product-img"  loading="lazy" /></a>';

                            var system_description = "";
                            if ("<?php echo $box_type ?>" == "Shipping") {
                                system_description = res['boxlength'] + '"x' + res['boxwidth'] + '"x' + res['boxheight'] + " " + res['boxwall'] + " " + res['product_ect_detail'];
                            } else {
                                system_description = res['boxheight'] + " " + res['boxwall'] + " " + res['box_desc_bottom'];
                            }
                            result_str += '<div class="product-description-grid">';
                            result_str += '<h6 class="sysdescription"> ' + system_description +
                                ' </h6>';
                            result_str += '<h4 class="mb-0"><b>' + minfobtxt + '</b><span class="color_black">*</span></h4>';
                            if ($('#txtzipcode').val() == "") {
                                result_str +=
                                    '<p class="highlight-detail-grid my-1" onclick="get_miles_away(); return false;">Add zip for miles away</p>';
                            } else {
                                result_str += '<p class="highlight-detail-grid my-1">' + res[
                                    "distance"] + '</p>';
                            }

                            result_str += '<div class="my-1">';
                            var delivery_cal_para = res['b2b_id'] + " , '" + res['txtaddress'] +
                                "' , '" + res['txtaddress2'] + "' , '" + res['txtcity'] +
                                "' , '" + res['txtstate'] + "' , '" + res['txtcountry'] +
                                "' , '" + res['txtzipcode'] + "' , " + res['minfob'];
                            result_str += '<div id="cal_delh4' + res['b2b_id'] +
                                show_urgent_box +
                                '"><a class="text-primary" href="#" onclick="calculate_delivery(' +
                                delivery_cal_para + ', ' + show_urgent_box +
                                '); return false"><u>Calculate Delivery (FTL)</u></a></div>';
                            result_str += '</div>';

                            result_str += '</div>';

                            result_str += '<div class="product-additional-info px-3">';
                            result_str +=
                                '<p class="quantity_available_now">Quantity Available Now</p>';
                            result_str += '<p class="load_ship">' + res["colorvalueQty"] +
                                '</p>';

                            result_str += '<div class="">';
                            result_str +=
                                `<p><span>ID = ${res["b2b_id"]}</span><span class="float-right"> Lead Time = <span style="${res["td_leadtime_bg_color"]}"> ${res["box_lead_time"]} </span></span></p>`;
                            result_str +=
                                `<p><span>% of FTL = <span style="${td_bg_color}">${perofftl}</span></span><span class="float-right"> FTL = ${res["ftl_qty"].toLocaleString('en-US')} </span></p>`;
                            result_str += '</div>';

                            result_str += '</div>';
                            var favorite = res['favorite'];
                            result_str +=
                                `<div class="view_item_grid"><span class="fav_icon" onclick="change_fav_status(this, ${res['companyID']},${res['b2b_id']},${favorite},<?php echo $user_id; ?>)">`;
                            result_str += favorite == 1 ?
                                '<i class="fa fa-heart fav-added"></i>' :
                                '<i class="fa fa-heart fav-removed"></i>';
                            result_str += '</span>';
                            result_str +=
                                '<a target="_blank" href="https://b2b.usedcardboardboxes.com/index_new.php?id=' +
                                res['loop_id_encrypt_str'] +
                                '&uid=<?php echo urlencode(encrypt_password($user_id)); ?>&' +
                                address_details + '" class="btn btn-cart-grid">VIEW ITEM</a>';
                            result_str += '</div>';
                            result_str += '</div>';
                            result_str += '</div>';
                        });
                        result_str += '</div>';
                        result_str += '</div>';

                        if (show_urgent_box == 1) {
                            var address_details = "";

                            if (shown_in_client_flg == 1) {
                                if (document.getElementById("address_book")) {
                                    var enter_add_id = document.getElementById("address_book").value;

                                    var enter_add1 = document.getElementById("txtaddress").value;
                                    var enter_add2 = document.getElementById("txtaddress2").value
                                    var enter_txtcity = document.getElementById("txtcity").value
                                    var enter_txtstate = document.getElementById("txtstate").value
                                    var enter_txtzipcode = document.getElementById("txtzipcode").value

                                    var address_details = "enter_add1=" + enter_add1 + "&enter_add2=" +
                                        enter_add2 + "&enter_txtcity=" + enter_txtcity +
                                        "&enter_txtstate=" + enter_txtstate + "&enter_txtzipcode=" +
                                        enter_txtzipcode + "&enter_add_id=" + enter_add_id;
                                }
                            }

                            var result_str_urgent = "";
                            result_str_urgent +=
                                '<div class="dashboard_heading" style="font-size: 16px;font-family: Titillium Web, sans-serif;font-weight:400;">For a limited time, these <?php echo $box_title; ?> are on sale!</div>';
                            result_str_urgent += '<div class="container-fluid mb-4">';
                            result_str_urgent += '<div class="px-2">';
                            result_str_urgent += '<div class="row mx-auto">';
                            result_str_urgent +=
                                '<div id="gallery" class="carousel slide w-100 align-self-center data-ride="carousel">';
                            result_str_urgent += '<ol class="carousel-indicators">';
                            for (var ind = 0; ind < result.length; ind++) {
                                var active_cls = ind == 0 ? 'class="active"' : '';
                                result_str_urgent += '<li data-target="#gallery" data-slide-to="' +
                                    ind + '" ' + active_cls + '></li>';
                            }
                            result_str_urgent += ' </ol>';
                            result_str_urgent +=
                                '<div class="carousel-inner mx-auto" role="listbox" data-toggle="modal" data-target="#lightbox">';
                            $.each(result, function(index, res) {
                                var td_bg_color = "";
                                if (res["td_bg"] == "green") {
                                    td_bg_color = " color: green;";
                                }
                                perofftl = "";
                                if (res['percent_per_load'] > 0) {
                                    perofftl = res['percent_per_load'];
                                }
                                sub_table_total_value = sub_table_total_value + parseFloat(res[
                                    'qtynumbervalue']);
                                sub_table_truckload += parseFloat(res['percent_per_load']);
                                sub_table_frequency += parseFloat(res['frequency_sort']);
                                sub_table_frequency_ftl += parseFloat(res['frequency_ftl']);
                                result_str_urgent += '<div class="carousel-item">';
                                result_str_urgent +=
                                    '<div class="col-lg-2 col-md-4 col-sm-6 mt-4 product-box-grid">';
                                result_str_urgent += '<div class="product-bg-light">';
                                var prod_src = res['img'] == "" ? default_img :
                                    "https://loops.usedcardboardboxes.com/boxpics_thumbnail/" + res['img'];
                                result_str_urgent +=
                                    '<a target="_blank" href="https://b2b.usedcardboardboxes.com/index_new.php?id=' +
                                    res['loop_id_encrypt_str'] +
                                    '&uid=<?php echo urlencode(encrypt_password($user_id)); ?>&' +
                                    address_details + '"><img src="' + prod_src +
                                    '" class="img-fluid product-img"  loading="lazy" /></a>';

                                var system_description = "";
                                if ("<?php echo $box_type ?>" == "Shipping") {
                                    system_description = res['boxlength'] + '"x' + res['boxwidth'] + '"x' + res['boxheight'] + " " + res['boxwall'] + " " + res['product_ect_detail'];
                                } else {
                                    system_description = res['boxheight'] + " " + res['boxwall'] + " " + res['box_desc_bottom'];
                                }

                                result_str_urgent += '<div class="product-description-grid">';
                                result_str_urgent += '<h6 class="sysdescription"> ' +
                                    system_description + ' </h6>';
                                result_str_urgent += '<h4 class="mb-0"><b>' + res["price"] +
                                    '</b></h4>';
                                if ($('#txtzipcode').val() == "") {
                                    result_str_urgent +=
                                        '<p class="highlight-detail-grid my-1" onclick="get_miles_away(); return false;">Add zip for miles away</p>';
                                } else {
                                    result_str_urgent +=
                                        '<p class="highlight-detail-grid my-1">' + res[
                                            "distance"] + '</p>';
                                }

                                //result_str_urgent += '<div class="my-1">';
                                //var delivery_cal_para = res['b2b_id'] + " , '" + res['txtaddress'] + "' , '" + res['txtaddress2'] + "' , '" + res['txtcity'] + "' , '" + res['txtstate'] + "' , '" + res['txtcountry'] + "' , '" + res['txtzipcode'] + "' , " + res['minfob'];
                                //result_str_urgent += '<div id="cal_delh4' + res['b2b_id'] + show_urgent_box + '"><a class="text-primary" href="#" onclick="calculate_delivery(' + delivery_cal_para + ', ' + show_urgent_box + '); return false"><u>Calculate Delivery (FTL)</u></a></div>';
                                //result_str_urgent += '</div>';

                                result_str_urgent += '</div>';

                                //result_str_urgent += '<div class="product-additional-info px-3">';
                                //result_str_urgent += '<p class="quantity_available_now">Quantity Available Now</p>';
                                //result_str_urgent += '<p class="load_ship">' + res["colorvalueQty"] + '</p>';

                                //result_str_urgent += '<div class="">';
                                //result_str_urgent += `<p><span>ID = ${res["b2b_id"]}</span><span class="text-muted float-right"> Lead Time = <span style="${res["td_leadtime_bg_color"]}"> ${res["box_lead_time"]} </span></span></p>`;
                                //result_str_urgent += `<p><span>% of FTL = <span style="${td_bg_color}">${perofftl}</span></span><span class="float-right"> FTL = ${res["ftl_qty"].toLocaleString('en-US')} </span></p>`;
                                //result_str_urgent += '</div>';

                                //result_str_urgent += '</div>';
                                var favorite = res['favorite'];
                                result_str_urgent +=
                                    `<div class="view_item_grid"><span class="fav_icon" onclick="change_fav_status(this, ${res['companyID']},${res['b2b_id']},${favorite},<?php echo $user_id; ?>)">`;
                                result_str_urgent += favorite == 1 ?
                                    '<i class="fa fa-heart fav-added"></i>' :
                                    '<i class="fa fa-heart fav-removed"></i>';
                                result_str_urgent += '</span>';
                                result_str_urgent +=
                                    '<a target="_blank" href="https://b2b.usedcardboardboxes.com/index_new.php?id=' +
                                    res['loop_id_encrypt_str'] +
                                    '&uid=<?php echo urlencode(encrypt_password($user_id)); ?>&' +
                                    address_details +
                                    '" class="btn btn-cart-grid">VIEW ITEM</a>';
                                result_str_urgent += '</div>';
                                result_str_urgent += '</div>';
                                result_str_urgent += '</div>';
                                result_str_urgent += '</div>';
                            });

                            result_str_urgent += '</div>';
                            result_str_urgent += '<div class="w-100">';
                            result_str_urgent +=
                                ' <a class="carousel-control-prev w-auto" href="#gallery" role="button" data-slide="prev">';
                            result_str_urgent +=
                                ' <span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="sr-only">Previous</span>';
                            result_str_urgent += ' </a>';
                            result_str_urgent +=
                                '<a class="carousel-control-next w-auto" href="#gallery" role="button" data-slide="next">';
                            result_str_urgent +=
                                ' <span class="carousel-control-next-icon" aria-hidden="true"></span><span class="sr-only">Next</span>';
                            result_str_urgent += ' </a>';
                            result_str_urgent += '</div>';
                            result_str_urgent += '</div>';
                            result_str_urgent += '</div>';
                            result_str_urgent += '</div>';
                            result_str_urgent += '</div>';
                        }

                    } else if (view_type == "list_view") {
                        result_str += '<div id="products-list-view" class="mt-3 m-auto">';
                        $.each(result, function(index, res) {
                            //sub_table_total_value += parseInt(res['qtynumbervalue'].replace(/[^0-9]/g, ""));
                            //sub_table_truckload += parseInt(res['percent_per_load'].replace(/[^0-9]/g, ""));
                            //sub_table_frequency += parseFloat(res['frequency_sort']);
                            //sub_table_frequency_ftl += parseFloat(res['frequency_ftl']);

                            result_str += '<div class="col-md-12 product-box-list thikness-5">';
                            result_str += '<div class="row align-items-center">';
                            result_str += '<div class="col-md-2">';
                            var prod_src = res['img'] == "" ? default_img :
                                "https://loops.usedcardboardboxes.com/boxpics_thumbnail/" + res['img'];
                            result_str +=
                                '<a target="_blank" href="https://b2b.usedcardboardboxes.com/?id=' +
                                res['loop_id_encrypt_str'] + '"><img src="' + prod_src +
                                '" class="img-fluid product-img"  loading="lazy" /></a>';
                            result_str += '</div>';
                            result_str +=
                                `<div class="col-md-8 product-description-list" style="background-color : ${res['td_bg'] == 'yellow' ? '#ffff0070' : res['td_bg']};">`;
                            result_str += '<div class="col-md-12 product-additional-info">';
                            result_str += '<div class="row">';
                            result_str += '<div class="col-md-8 p-0">';
                            result_str +=
                                '<a target="_blank" href="https://b2b.usedcardboardboxes.com/?id=' +
                                res['loop_id_encrypt_str'] + '"><h5>' + res["description"] +
                                '</h5></a>';
                            result_str += '<div class="media">';
                            result_str +=
                                '<img class="mr-2 img-fluid" src="images/boomerang/icon_status.png" alt="Generic placeholder image">';
                            result_str +=
                                '<div class="media-body"><p><span class="product_desc">Status: ' +
                                res["status"] + '</p></div>';
                            result_str += '</div>';
                            result_str += '<div class="media">';
                            result_str +=
                                '<img class="mr-2 img-fluid" src="images/boomerang/icon_Location.png" alt="Generic placeholder image">';
                            result_str +=
                                '<div class="media-body"><span class="product_desc">Ship From: </span> ' +
                                res["ship_from"] + '</div>';
                            result_str += '</div>';
                            result_str += '<div class="media">';
                            result_str +=
                                '<img class="mr-2 img-fluid" src="images/boomerang/Icon_Dimensions.png" alt="Generic placeholder image">';
                            var system_description = res['system_description'];
                            result_str +=
                                '<div class="media-body"><p><span class="product_desc">Description: </span>' +
                                system_description + '</p></div>';
                            result_str += '</div>';
                            result_str += '<div class="media">';
                            result_str +=
                                '<img class="mr-2 img-fluid" src="images/boomerang/Icon_notes.png" alt="Generic placeholder image">';
                            var flyer_notes = res['flyer_notes'];
                            result_str +=
                                '<div class="media-body"><p><span class="product_desc">Flyer Notes: </span> ' +
                                flyer_notes + '</p></div>';
                            result_str += '</div>';
                            // result_str +='<div class="media">';
                            // result_str +='<img class="mr-2 img-fluid" src="images/boomerang/Icon_notes.png" alt="Generic placeholder image">';
                            // result_str +='<div class="media-body"><p><span class="product_desc">Lead time of FTL: </span> '+res['lead_time_of_FTL']+'</p></div>';
                            // result_str +='</div>';
                            result_str += '</div>';
                            result_str += '<div class="col-md-4 col-sm-2">';
                            // if($('#txtzipcode').val()==""){
                            // 	result_str +='<p class="my-1"><span class="highlight-detail-list" onclick="get_miles_away(); return false;">Add zip for mi away</span></p>';
                            // }else{
                            // 	result_str +='<p class="my-1"><span class="highlight-detail-list">'+res["distance"]+' mi away</span></p>';
                            // }
                            result_str += '<p class="my-1 text-center">' + res["distance"] +
                                '</p>';
                            result_str += '<div class="media">';
                            result_str +=
                                '<img class="mr-2 img-fluid" src="images/boomerang/Icon_truck.png" alt="Generic placeholder image">';
                            result_str +=
                                '<div class="media-body"><p><span class="product_desc">Can Ship LTL? </span> ' +
                                res["ltl"] + '</p></div>';
                            result_str += '</div>';
                            result_str += '<div class="media">';
                            result_str +=
                                '<img class="mr-2 img-fluid" src="images/boomerang/can_customer_pickup.png" alt="Generic placeholder image">';
                            result_str +=
                                '<div class="media-body"><p><span class="product_desc">Can Customer Pickup? </span> ' +
                                res["customer_pickup"] + '</p></div>';
                            result_str += '</div>';
                            result_str += '<div class="media flex-column">';
                            result_str +=
                                '<p class="text-center w-100"><a class="product_link available_loads" data-toggle="modal" data-target="#available_loads_modal" loop_id="' +
                                res.loop_id + '">' + res["loads"] + '</a></p>';
                            result_str +=
                                '<p class="text-center w-100">First Load Can Ship In <br> ' +
                                res["first_load_can_ship_in"] + '</p>';
                            result_str += '</div>';
                            result_str += '</div>';
                            result_str += '</div></div></div>';
                            result_str += '<div class="col-md-2 align-self-end p-0">';
                            result_str += '<div class="load_ship">';
                            var delivery_cal_para = res['b2b_id'] + " , '" + res['txtaddress'] +
                                "' , '" + res['txtaddress2'] + "' , '" + res['txtcity'] +
                                "' , '" + res['txtstate'] + "' , '" + res['txtcountry'] +
                                "' , '" + res['txtzipcode'] + "' , " + res['minfob'];
                            result_str += '<h5 class="font-weight-bold m-0" id="cal_delh4' +
                                res['b2b_id'] + '">' + res["price"] + ' </h5><div id="cal_del' +
                                res['b2b_id'] +
                                '"><a class="product_link" href="#" onclick="calculate_delivery(' +
                                delivery_cal_para +
                                '); return false;">Calculate Delivery</a></div>';
                            result_str += '</div>';
                            result_str += '<div class="load_ship mt-2">';
                            result_str +=
                                '<ul class="pl-1 text-left m-0" style="list-style-type: none;">';
                            result_str += `<li><b>Quantity </b> : ${res['colorvalueQty']}</li>`;
                            result_str += '<li><b>Lead Time </b> : ' + res['lead_time_of_FTL'] +
                                '</li>';
                            result_str += '<li><b>% of Load</b> : ' + res['percent_per_load'] +
                                '%</li>';
                            result_str += '</ul>';
                            result_str += '</div>';
                            result_str +=
                                '<a target="_blank" href="https://b2b.usedcardboardboxes.com/?id=' +
                                res['loop_id_encrypt_str'] +
                                '&checkout=1" class="btn btn-cart">Buy Now</a>';

                            result_str += '</div></div></div>';

                        });
                        result_str += '</div>';
                    } else {
                        var shown_in_client_flg_width =
                            <?php echo json_encode($shown_in_client_flg_width); ?>;
                        var shown_in_client_flg = <?php echo json_encode($shown_in_client_flg); ?>;
                        var shown_in_client_user_id = <?php echo json_encode($user_id); ?>;
                        var shown_in_client_user_id_hide =
                            <?php echo json_encode($user_id_hide_price); ?>;

                        var address_details = "";
                        if (shown_in_client_flg == 1) {
                            if (document.getElementById("address_book")) {
                                var enter_add_id = document.getElementById("address_book").value;

                                var enter_add1 = document.getElementById("txtaddress").value;
                                var enter_add2 = document.getElementById("txtaddress2").value
                                var enter_txtcity = document.getElementById("txtcity").value
                                var enter_txtstate = document.getElementById("txtstate").value
                                var enter_txtzipcode = document.getElementById("txtzipcode").value

                                var address_details = "enter_add1=" + enter_add1 + "&enter_add2=" +
                                    enter_add2 + "&enter_txtcity=" + enter_txtcity +
                                    "&enter_txtstate=" + enter_txtstate + "&enter_txtzipcode=" +
                                    enter_txtzipcode + "&enter_add_id=" + enter_add_id;
                            }
                        }

                        if (show_urgent_box == 1) {
                            var result_str_urgent = "";
                            result_str_urgent +=
                                '<div class="dashboard_heading" style="font-size: 16px;font-family: Titillium Web, sans-serif;font-weight:400;">For a limited time, these <?php echo $box_title; ?> are on sale!</div>';
                            result_str_urgent += '<div class="container-fluid mb-4">';
                            result_str_urgent += '<div class="px-2">';
                            result_str_urgent += '<div class="row mx-auto">';
                            result_str_urgent +=
                                '<div id="gallery" class="carousel slide w-100 align-self-center data-ride="carousel">';
                            result_str_urgent += '<ol class="carousel-indicators">';
                            for (var ind = 0; ind < result.length; ind++) {
                                var active_cls = ind == 0 ? 'class="active"' : '';
                                result_str_urgent += '<li data-target="#gallery" data-slide-to="' +
                                    ind + '" ' + active_cls + '></li>';
                            }
                            result_str_urgent += ' </ol>';
                            result_str_urgent +=
                                '<div class="carousel-inner mx-auto" role="listbox" data-toggle="modal" data-target="#lightbox">';
                            $.each(result, function(index, res) {
                                var td_bg_color = "";
                                if (res["td_bg"] == "green") {
                                    td_bg_color = " color: green;";
                                }
                                perofftl = "";
                                if (res['percent_per_load'] > 0) {
                                    perofftl = res['percent_per_load'];
                                }
                                sub_table_total_value = sub_table_total_value + parseFloat(res[
                                    'qtynumbervalue']);
                                sub_table_truckload += parseFloat(res['percent_per_load']);
                                sub_table_frequency += parseFloat(res['frequency_sort']);
                                sub_table_frequency_ftl += parseFloat(res['frequency_ftl']);
                                result_str_urgent += '<div class="carousel-item">';
                                result_str_urgent +=
                                    '<div class="col-lg-2 col-md-4 col-sm-6 mt-4 product-box-grid">';
                                result_str_urgent += '<div class="product-bg-light">';
                                var prod_src = res['img'] == "" ? default_img :
                                    "https://loops.usedcardboardboxes.com/boxpics_thumbnail/" + res['img'];
                                result_str_urgent +=
                                    '<a target="_blank" href="https://b2b.usedcardboardboxes.com/index_new.php?id=' +
                                    res['loop_id_encrypt_str'] +
                                    '&uid=<?php echo urlencode(encrypt_password($user_id)); ?>&' +
                                    address_details + '"><img src="' + prod_src +
                                    '" class="img-fluid product-img"  loading="lazy" /></a>';

                                var system_description = res['boxheight'] + " " + res[
                                    'boxwall'] + " " + res['box_desc_bottom'];

                                result_str_urgent += '<div class="product-description-grid">';
                                result_str_urgent += '<h6 class="sysdescription"> ' +
                                    system_description + ' </h6>';
                                result_str_urgent += '<h4 class="mb-0"><b>' + res["price"] +
                                    '</b></h4>';
                                if ($('#txtzipcode').val() == "") {
                                    result_str_urgent +=
                                        '<p class="highlight-detail-grid my-1" onclick="get_miles_away(); return false;">Add zip for miles away</p>';
                                } else {
                                    result_str_urgent +=
                                        '<p class="highlight-detail-grid my-1">' + res[
                                            "distance"] + '</p>';
                                }

                                //result_str_urgent += '<div class="my-1">';
                                //var delivery_cal_para = res['b2b_id'] + " , '" + res['txtaddress'] + "' , '" + res['txtaddress2'] + "' , '" + res['txtcity'] + "' , '" + res['txtstate'] + "' , '" + res['txtcountry'] + "' , '" + res['txtzipcode'] + "' , " + res['minfob'];
                                //result_str_urgent += '<div id="cal_delh4' + res['b2b_id'] + show_urgent_box + '"><a class="text-primary" href="#" onclick="calculate_delivery(' + delivery_cal_para + ', ' + show_urgent_box + '); return false"><u>Calculate Delivery (FTL)</u></a></div>';
                                //result_str_urgent += '</div>';

                                result_str_urgent += '</div>';

                                //result_str_urgent += '<div class="product-additional-info px-3">';
                                //result_str_urgent += '<p class="quantity_available_now">Quantity Available Now</p>';
                                //result_str_urgent += '<p class="load_ship">' + res["colorvalueQty"] + '</p>';

                                //result_str_urgent += '<div class="">';
                                //result_str_urgent += `<p><span>ID = ${res["b2b_id"]}</span><span class="float-right"> Lead Time = <span style="${res["td_leadtime_bg_color"]}"> ${res["box_lead_time"]} </span></span></p>`;
                                //result_str_urgent += `<p><span>% of FTL = <span style="${td_bg_color}">${perofftl}</span></span><span class="float-right"> FTL = ${res["ftl_qty"].toLocaleString('en-US')} </span></p>`;
                                //result_str_urgent += '</div>';

                                //result_str_urgent += '</div>';
                                var favorite = res['favorite'];
                                result_str_urgent +=
                                    `<div class="view_item_grid"><span class="fav_icon" onclick="change_fav_status(this, ${res['companyID']},${res['b2b_id']},${favorite},<?php echo $user_id; ?>)">`;
                                result_str_urgent += favorite == 1 ?
                                    '<i class="fa fa-heart fav-added"></i>' :
                                    '<i class="fa fa-heart fav-removed"></i>';
                                result_str_urgent += '</span>';
                                result_str_urgent +=
                                    '<a target="_blank" href="https://b2b.usedcardboardboxes.com/index_new.php?id=' +
                                    res['loop_id_encrypt_str'] +
                                    '&uid=<?php echo urlencode(encrypt_password($user_id)); ?>&' +
                                    address_details +
                                    '" class="btn btn-cart-grid">VIEW ITEM</a>';
                                result_str_urgent += '</div>';
                                result_str_urgent += '</div>';
                                result_str_urgent += '</div>';
                                result_str_urgent += '</div>';
                            });

                            result_str_urgent += '</div>';
                            result_str_urgent += '<div class="w-100">';
                            result_str_urgent +=
                                ' <a class="carousel-control-prev w-auto" href="#gallery" role="button" data-slide="prev">';
                            result_str_urgent +=
                                ' <span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="sr-only">Previous</span>';
                            result_str_urgent += ' </a>';
                            result_str_urgent +=
                                '<a class="carousel-control-next w-auto" href="#gallery" role="button" data-slide="next">';
                            result_str_urgent +=
                                ' <span class="carousel-control-next-icon" aria-hidden="true"></span><span class="sr-only">Next</span>';
                            result_str_urgent += ' </a>';
                            result_str_urgent += '</div>';
                            result_str_urgent += '</div>';
                            result_str_urgent += '</div>';
                            result_str_urgent += '</div>';
                            result_str_urgent += '</div>';

                            result_str +=
                                '<div class="dashboard_heading" style="text-align: left; font-size: 16px;font-family: Titillium Web, sans-serif;font-weight:400;">For a limited time, these <?php echo $box_title; ?> are on sale!</div>';
                            result_str += '<div id="products-table-view">';
                        } else {
                            result_str += '<div id="products-table-view" class="mt-3">';
                        }

                        result_str += '<table style="' + shown_in_client_flg_width +
                            '" cellSpacing="1" cellPadding="1" border="0">';
                        result_str += '<thead><tr>';

                        if (shown_in_client_flg == 1) {
                            result_str += `<th style="width:5%;">Favorite</th>`;
                        }
                        result_str += `<th>B2B ID</th>`;
                        if ("<?php echo $box_type ?>" == "Shipping") {
                            result_str += `<th>Length</th>
								<th>Width</th>
                                <th>Height</th>
                                <th>Walls</th>
								<th>Strength</th>`;
                        }else if ("<?php echo $box_type ?>" == "Pallets") {
                            result_str += `<th>Grade</th>
								<th>Heat Treated</th>`;
                        } else {
                            result_str += `<th>Height</th>
								<th>Walls</th>
								<th>Bottom</th>`;
                        }

                        result_str += `<th style='background-color: white;'>&nbsp;</th>
								<th>Qty Avail <br>NOW</th>
								<th>% of FTL</th>
								<th>Lead Time</th>
								<th>FTL Qty</th>`;
                        if (shown_in_client_flg == 0) {
                            result_str += `<th>Lead Time<br>for FTL</th>`;
                        }
                        result_str += `<th>Miles Away</th>`;

                        if (shown_in_client_flg == 1) {
                            if (shown_in_client_user_id_hide == "no") {
                                result_str += `<th>Price</th>`;
                            }
                        } else {
                            result_str += `<th>Price</th>`;
                        }

                        if (shown_in_client_flg == 0) {
                            result_str += `<th>Delivery</th>`;
                        } else {
                            result_str += `<th>FTL Delivery</th>`;
                        }
                        if (shown_in_client_flg == 1) {
                            if (shown_in_client_user_id_hide == "no") {
                                result_str += `<th>View Item</th>`;
                            }
                        } else {
                            result_str += `<th>View Item</th>`;
                        }
                        result_str += `</tr>
								</thead>
								<tbody>`;

                        sub_table_total_value = 0;
                        row_alternate_cnt = 0;
                        $.each(result, function(index, res) {
                            perofftl = "";
                            if (res['percent_per_load'] > 0) {
                                perofftl = res['percent_per_load'];
                            }

                            sub_table_total_value = sub_table_total_value + parseFloat(res[
                                'qtynumbervalue']);

                            sub_table_truckload += parseFloat(res['percent_per_load']);
                            sub_table_frequency += parseFloat(res['frequency_sort']);
                            sub_table_frequency_ftl += parseFloat(res['frequency_ftl']);
                            var delivery_cal_para = res['b2b_id'] + " , '" + res['txtaddress'] +
                                "' , '" + res['txtaddress2'] + "' , '" + res['txtcity'] +
                                "' , '" + res['txtstate'] + "' , '" + res['txtcountry'] +
                                "' , '" + res['txtzipcode'] + "' , " + res['minfob'];

                            if (row_alternate_cnt == 0) {
                                row_alternate_color = "#e4e4e4";
                                row_alternate_cnt = 1;
                            } else {
                                row_alternate_color = "#f4f4f4";
                                row_alternate_cnt = 0;
                            }

                            td_bg_color = "";
                            if (res["td_bg"] == "green") {
                                td_bg_color = " color: green;";
                            }
                            if (shown_in_client_flg == 0) {
                                if (res["td_bg"] != "") {
                                    row_alternate_color = "yellow";
                                }
                            }

                            var minfobtxt = "";
                            if ((res["minfob"] == 0)) {
                                minfobtxt = "Inquire";
                            } else {
                                minfobtxt = "$" + res["minfob"];
                            }


                            var favorite = res['favorite'];
                            result_str += `<tr>`;
                            if (shown_in_client_flg == 1) {
                                result_str +=
                                    `		<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">
										<span onclick="change_fav_status(this, ${res['companyID']},${res['b2b_id']},${favorite},<?php echo $user_id; ?>)">`;
                                result_str += favorite == 1 ?
                                    '<i class="fa fa-heart fav-added"></i>' :
                                    '<i class="fa fa-heart fav-removed"></i>';
                                result_str += `</span></td>`;
                            }

                            result_str += `
										<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${res['b2b_id']}</td>`;
                            if ("<?php echo $box_type ?>" == "Shipping") {
                                result_str += `<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${res['boxlength']}"</td>
										<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${res['boxwidth']}"</td>
										<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${res['boxheight']}</td>
										<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${res['boxwall']}</td>
										<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${res['product_ect_detail']}</td>
										<td style="background-color:white;" >&nbsp;</td>`;
                            } else if ("<?php echo $box_type ?>" == "Pallets") {
                                    var dis_grade = "";
                                    if(res.product_grade == 1){
                                        dis_grade = "Grade A";
                                    }else if(res.product_grade == 2){
                                        dis_grade = "Grade B";
                                    }
                                    var dis_heat_treated = "";
                                    if(res.product_heat_treated == 1){
                                        dis_heat_treated = "Heat Treated";
                                    }else if(res.product_heat_treated == 2){
                                        dis_heat_treated = "No";
                                    }
                                     result_str += `<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${dis_grade}</td>
									<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${dis_heat_treated}</td>
                                    <td style="background-color:white;" >&nbsp;</td>`;
										
                            } else {
                                result_str += `<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${res['boxheight']}</td>
										<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${res['boxwall']}</td>
										<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${res['box_desc_bottom']}</td>
										
										<td style="background-color:white;" >&nbsp;</td>`;
                            }
                            if (shown_in_client_flg == 1) {
                                result_str +=
                                    `		<td style="background-color:${row_alternate_color}; ${td_bg_color}" bgColor="${res['td_bg']}" id="after_po${index}">${res['colorvalueQty']}</td>`;
                            } else {
                                result_str +=
                                    `		<td style="background-color:${row_alternate_color}; ${td_bg_color}" bgColor="${res['td_bg']}" id="after_po${index}"><a href="javascript:void(0)" onclick="display_preoder_sel(${index}, ${res['qtynumbervalue']}, ${res['loop_id']}, ${res['box_warehouse_id']})"><u>${res['colorvalueQty']}</u></a></td>`;
                            }

                            result_str +=
                                `	<td style="background-color:${row_alternate_color}; ${td_bg_color}" bgColor="${res['td_bg']}">${perofftl}</td>
										<td style="background-color:${row_alternate_color}; ${res['td_leadtime_bg_color']}" bgColor="${res['td_bg']}">${res['box_lead_time']}</td>
										<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${res["ftl_qty"].toLocaleString('en-US')}</td>`;

                            if (shown_in_client_flg == 0) {
                                result_str +=
                                    `		<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${res['lead_time_of_FTL']}</td>`;
                            }

                            result_str +=
                                `<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${res["distance"]}`;
                            result_str += show_urgent_box == 2 ? `` : `</td>`;

                            if (shown_in_client_flg == 1) {
                                if (shown_in_client_user_id_hide == "no") {
                                    result_str +=
                                        ` <td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${minfobtxt}`;
                                    result_str += show_urgent_box == 2 ? `<span class="color_black">*</span></td>` : `</td>`;
                                }
                            } else {
                                result_str +=
                                    `  <td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${minfobtxt}`;
                                result_str += show_urgent_box == 2 ? `<span class="color_black">*</span></td>` : `</td>`;
                            }

                            result_str += `	  <td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">
											<div class="font-weight-bold m-0" id="cal_delh4${res['b2b_id']}${show_urgent_box}"></div>										
											<a class="text-primary" href="#" id="cal_del${res['b2b_id']}${show_urgent_box}" onclick="calculate_delivery(${delivery_cal_para}, ${show_urgent_box}); return false;"><u>Calculate</u></a>
										</td>`;

                            if (shown_in_client_flg == 1) {
                                if (shown_in_client_user_id_hide == "no") {
                                    result_str +=
                                        `		<td style="background-color:${row_alternate_color}" class="text-center" bgColor="${res['td_bg']}"><a class="text-primary" href="https://b2b.usedcardboardboxes.com/index_new.php?id=${res['loop_id_encrypt_str']}&uid=<?php echo urlencode(encrypt_password($user_id)); ?>&${address_details}" target="_blank"><u>View Item</u></a></td>`;
                                }
                            } else {
                                result_str +=
                                    `		<td style="background-color:${row_alternate_color}" class="text-center" bgColor="${res['td_bg']}"><a class="text-primary" onmouseover="Tip('${res['description_hover_notes']}')"  onmouseout="UnTip()" target="_blank" href="manage_box_b2bloop_new.php?id=${res['loop_id']}&proc=View&"><u>View Item</u></a></td>`;
                            }
                            result_str += `</tr>`;

                            if (res['qtynumbervalue'] > 0) {
                                result_str += `<tr >
												<td colspan="15">
												<div id="inventory_preord_middle_div_${index}"></div>		
											</td>
										</tr>`;
                            }

                        });
                        result_str += '</tbody>';
                        result_str += '</div>';
                    };
                }

                //console.log("show_urgent_box " + show_urgent_box + "view_type " + view_type);
                if (show_urgent_box == 1) {
                    if (view_type == "table-view") {
                        $('#products-table-view').html(result_str);
                    }

                    $('#products-table-view-urgent').html(result_str);
                    $("#products-grid-view-urgent").html(result_str_urgent);
                    //$("#products-grid-view-urgent").addClass('d-none');

                    jQuery('#gallery').carousel({
                        interval: false
                    })

                    // Modify each slide to contain five columns of images
                    jQuery('#gallery.carousel .carousel-item').each(function() {
                        if (result.length >= 6) {
                            var minPerSlide = 3;
                        } else if (result.length <= 5 && result.length > 4) {
                            var minPerSlide = 3;
                        } else if (result.length <= 4 && result.length > 3) {
                            var minPerSlide = 2;
                        } else if (result.length <= 3 && result.length > 2) {
                            var minPerSlide = 1;
                        } else if (result.length <= 2) {
                            var minPerSlide = 0;
                        } else {
                            var minPerSlide = 6;
                        }
                        var next = jQuery(this).next();
                        if (!next.length) {
                            next = jQuery(this).siblings(':first');
                        }
                        next.children(':first-child').clone().appendTo(jQuery(this));

                        for (var i = 0; i < minPerSlide; i++) {
                            next = next.next();
                            if (!next.length) {
                                next = jQuery(this).siblings(':first');
                            }

                            next.children(':first-child').clone().appendTo(jQuery(this));
                        }
                    });

                    // Initialize carousel
                    jQuery(".carousel-item:first-of-type").addClass("active");
                    jQuery(".carousel-indicators:first-child").addClass("active");
                } else {

                    $('#result_products').html(result_str);
                }

                if (shown_in_client_flg == 1) {
                    if (show_urgent_box != 1) {
                        $('#total_SKU').html(result.length);
                        if (sub_table_total_value) {
                            $('#sub_table_total_value').html(sub_table_total_value.toLocaleString('en-US'));
                        } else {
                            $('#sub_table_total_value').html("0");
                        }

                        if (sub_table_truckload) {
                            var truckloadValue = sub_table_truckload;
                            $('#sub_table_truckload').html(truckloadValue.toFixed(2));
                        } else {
                            $('#sub_table_truckload').html(0);
                        }
                    }

                } else {
                    $('#total_SKU').html(result.length);

                    if (sub_table_truckload) {
                        var truckloadValue = sub_table_truckload;
                        $('#sub_table_truckload').html(truckloadValue.toFixed(2));
                    } else {
                        $('#sub_table_truckload').html(0);
                    }

                    if (sub_table_total_value) {
                        $('#sub_table_total_value').html(sub_table_total_value.toLocaleString('en-US'));
                    } else {
                        $('#sub_table_total_value').html("0");
                    }

                    if (sub_table_frequency && sub_table_frequency > 0) {
                        $('#sub_table_frequency').html(sub_table_frequency.toLocaleString('en-US'));
                    } else {
                        $('#sub_table_frequency').html("0");
                    }

                    if (sub_table_frequency_ftl > 0) {
                        $('#sub_table_frequency_ftl').html(sub_table_frequency_ftl.toFixed(2));
                    } else {
                        $('#sub_table_frequency_ftl').html("0");
                    }
                }
            }
            var shown_in_client_flg = <?php echo json_encode($shown_in_client_flg); ?>;
            //default page Loading
            if (shown_in_client_flg == 1 && $('#txtzipcode').val() != "") {
                show_inventories("", 1, 1, 1, 'refresh');

                functionLoaded = false;

                show_inventories('2', "", 2, 1, 'refresh');
            } else {

                show_inventories("", 1, 1, 1, 'refresh');

                functionLoaded = false;

                show_inventories("", 1, 2, 1, 'refresh');
            }

            $(document).on('mouseenter', '.product-box-grid', function() {
                $(this).find('.fixed_height_description').addClass('d-none');
                $(this).find('.complete_description').removeClass('d-none');
                $(this).find('.fixed_height_flyer_note').addClass('d-none');
                $(this).find('.complete_flyernote').removeClass('d-none');
            })
            $(document).on('mouseleave', '.product-box-grid', function() {
                $(this).find('.fixed_height_description').removeClass('d-none');
                $(this).find('.complete_description').addClass('d-none');
                $(this).find('.fixed_height_flyer_note').removeClass('d-none');
                $(this).find('.complete_flyernote').addClass('d-none');
            })

            $(document).on('click', '.remove_selection', function() {
                var remove_val_of = $(this).parents('.added_selection').attr('respective_input');
                var input_type = $(this).parents('.added_selection').attr('respective_type');
                if (input_type == "text") {
                    if (remove_val_of == "height") {
                        $("input[name='min_height']").val(0);
                        $("input[name='max_height']").val(99.99);
                    } else if (remove_val_of == "width") {
                        $("input[name='min_width']").val(0);
                        $("input[name='max_width']").val(99.99);
                    } else if (remove_val_of == "length") {
                        $("input[name='min_length']").val(0);
                        $("input[name='max_length']").val(99.99);
                    } else if (remove_val_of == "price_each") {
                        $("input[name='min_price_each']").val("0.00");
                        $("input[name='max_price_each']").val("99.99");
                    } else if (remove_val_of == "cubic_footage") {
                        $("input[name='min_cubic_footage']").val("0.00");
                        $("input[name='max_cubic_footage']").val("99.99");
                    } else {
                        $("#" + remove_val_of).val("");
                    }
                } else if (input_type == "select") {
                    if (remove_val_of == "box_type") {
                        $("#" + remove_val_of).val("Gaylord");
                        default_sel_gaylord = true;
                    } else if (remove_val_of == "timing") {
                        $("#" + remove_val_of).val(4);
                        default_sel_timing = true;
                    } else if (remove_val_of == "warehouse") {
                        $("#" + remove_val_of).val('all');
                        default_sel_warehouse = true;
                    } else if (remove_val_of == "ect_burst") {
                        $("#" + remove_val_of).val('all');
                        default_sel_ect_burst = true;
                    } else if (remove_val_of == "box_tags") {
                        $("#" + remove_val_of).val('remove');
                        default_sel_tags = true;
                    } else {
                        $("#" + remove_val_of).val("");
                    }
                } else if (input_type == "checkbox") {
                    var classname = $(this).parents('.added_selection').attr('classname');
                    $("." + classname).prop("checked", false);
                }
                $(this).parents('.added_selection').css('display', 'none');
                show_inventories("", 1, 2, 2, 'filter');
            })

            $("#clear_all").click(function() {
                //$("#sel_warehouse_id").css('display', 'none');

                $("#filter_form")[0].reset();

                //document.getElementById("sel_warehouse_id").style.visibility = 'hidden';

                default_sel_warehouse = true;
                default_sel_ect_burst = true;

                show_inventories(1, 1, 2, 2, 'clear_filter');
                return false;
            });

            function get_miles_away() {
                $('#txtzipcode').focus();
            }


            function calculate_delivery(inv_b2b_id, txtaddress, txtaddress2, txtcity, txtstate, txtcountry, txtzipcode,
                minfob, show_urgent_box) {
                if ($('#txtaddress').val() == "" || $('#txtcity').val() == "" || $('#txtstate').val() == "" || $(
                        '#txtzipcode').val() == "") {
                    alert("Enter the Delivery address to calculate the delivery.")
                    $('#txtaddress').focus();
                } else {
					txtaddress = $('#txtaddress').val();
					txtaddress2 = $('#txtaddress2').val();
					txtcity = $('#txtcity').val();
					txtstate = $('#txtstate').val();
					txtzipcode = $('#txtzipcode').val();

                    $.ajax({
                        url: 'uber_freight_matching_tool_v3.php',
                        type: 'get',
                        data: "inv_b2b_id=" + inv_b2b_id + "&inclient=1&b2binvflg=yes&txtaddress=" + txtaddress +
                            "&txtaddress2=" + txtaddress2 + "&txtcity=" + txtcity + "&txtstate=" + txtstate +
                            "&txtcountry=" + txtcountry + "&txtzipcode=" + txtzipcode + "&minfob=" + minfob,
                        datatype: 'text',
                        beforeSend: function() {
                            $('#cal_delh4' + inv_b2b_id + show_urgent_box).html(
                                '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
                            );
                        },
                        success: function(res) {
                            $('#cal_del' + inv_b2b_id + show_urgent_box).css('display', 'none');
                            $('#cal_delh4' + inv_b2b_id + show_urgent_box).html(res);
                        },
                    })
                }
            }

            $(document).on('click', '.available_loads', function() {
                var loop_id = $(this).attr('loop_id');
                $.ajax({
                    url: 'show_available_load_data.php',
                    type: 'get',
                    data: {
                        loop_id
                    },
                    datatype: 'json',
                    beforeSend: function() {
                        $("#ship_data").html(
                            '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
                        );
                    },
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.count > 0) {
                            var table_data =
                                "<table class='table table-bordered'><thead><tr><th>Sr. no.</th><th>Available Load Ship Date</th></tr></thead>";
                            $.each(res.data, function(i, d) {
                                table_data += "<tr>";
                                table_data += "<td>" + (i + 1) + "</td>";
                                table_data += "<td>" + d.load_available_date + "</td>";
                                table_data += "</tr>";
                            })
                            table_data += "</table>";
                            $("#ship_data").html(table_data);
                        } else {
                            $("#ship_data").html('No data for this inventory item at this time.');
                        }
                    },
                })
            });

            $(document).on('click', '#enter_address_text', function() {
                if ($('#full_address_div').hasClass('d-none')) {
                    $('#full_address_div').removeClass('d-none');
                    $(this).text('Hide Address');
                    $('#clear-deliverydata').text('Clear Address');
                    $('#apply-deliverydata').text('Apply Address');

                } else {
                    $('#full_address_div').addClass('d-none');
                    $(this).text('Enter Full Adddress');
                    $('#clear-deliverydata').text('Clear Zipcode');
                    $('#apply-deliverydata').text('Apply Zipcode');
                }
            })

            /* $(function() {
                 $('#box_tags').searchableOptionList();
             });

             */
            function change_fav_status(currentEle, companyID, b2b_id, fav_status, user_id) {
                $.ajax({
                    url: 'product_result_new1.php',
                    type: 'get',
                    data: {
                        companyID,
                        b2b_id,
                        fav_status,
                        user_id: `<?php echo $user_id ?>`,
                        change_fav_status: 1
                    },
                    datatype: 'json',
                    success: function(response) {

                        if (response == 1) {
                            $(currentEle).html('<i class="fa fa-heart fav-added"></i>');
                        } else if (response == 0) {
                            $(currentEle).html('<i class="fa fa-heart fav-removed"></i>');
                        } else {
                            alert('Something went wrong, try again');
                        }
                    }
                })
            }


            /*  $(document).ready(function() {
                  function sendHeight() {
                      var height = $(document).height();
                      window.parent.postMessage({
                          height: height
                      }, 'https://boomerang.usedcardboardboxes.com');
                      
                  }

                  // Send the height on page load
                  sendHeight();

                  // Send the height when the content changes (if needed)
                  $(window).resize(sendHeight);
              });

              window.addEventListener('message', function(event) {

                  if (event.origin === 'https://boomerang.usedcardboardboxes.com') { 
                      if (event.data === 'resize') {
                          var height = $(document).height();
                          window.parent.postMessage({
                              height: height
                          }, 'https://boomerang.usedcardboardboxes.com');
                      }
                  }
              });
              */
        </script>


</html>