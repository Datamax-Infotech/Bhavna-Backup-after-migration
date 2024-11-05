<?php
ini_set("display_errors", "1");
error_reporting(E_ERROR);
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

if (isset($_REQUEST['form_action']) && $_REQUEST['form_action'] == "check_duplicate_email") {
    $user_email = $_REQUEST['user_email'];
    db();
    $user_data_qry = db_query("SELECT * FROM boomerang_usermaster where user_status = 1 && user_email = '" . $user_email . "'");
    echo tep_db_num_rows($user_data_qry);
}



if (isset($_REQUEST['form_action']) && $_REQUEST['form_action'] == "check_duplicate_user_id") {
    $user_id = $_REQUEST['selectedUserId'];
    db();

    // Query to check if user_id already exists in boomerang_usermaster
    $user_data_qry = db_query("SELECT * FROM boomerang_usermaster WHERE user_id = '" . $user_id . "' AND user_id != 0 AND user_status = 1");

    // echo tep_db_num_rows($user_data_qry); // This should return the count directly
    // exit; // Ensure no additional output is returned
    echo json_encode([
        'user_id' => $user_id,
        'count' => tep_db_num_rows($user_data_qry)
    ]);
    exit;
}


if (isset($_REQUEST['form_action']) && $_REQUEST['form_action'] == "add_user") {
    $user_name = str_replace("'", "\'", $_REQUEST['user_name']);
    $user_last_name = str_replace("'", "\'", $_REQUEST['user_last_name']);
    $user_email = $_REQUEST['user_email'];
    $user_password = $_REQUEST['user_password'];
    $user_company = str_replace("'", "\'", $_REQUEST['user_company']);
    $user_phone = $_REQUEST['user_phone'];
    $selectedManager = $_REQUEST['manager'];

    db();
    $insert_usr = db_query("INSERT INTO boomerang_usermaster (`manager_id`,`user_name`, `user_last_name`, user_company, user_phone, `password`,`activate_deactivate`,`user_email`,`user_name_bkp`,`user_block`,`user_status`,`created_by`,`created_on`)
		VALUES ('" . $selectedManager . "','" . $user_name . "', '" . $user_last_name . "', '" . $user_company . "', '" . $user_phone . "', '" . base64_encode($user_password) . "','1','" . $user_email . "','" . $user_name . "','0',1,'" . $_COOKIE['b2b_id'] . "','" . date('Y-m-d H:i:s') . "')");
    $user_id = tep_db_insert_id();

    echo 1;
}

if (isset($_REQUEST['form_action']) && $_REQUEST['form_action'] == "add_boom_user") {
    $user_id = $_REQUEST['userid'];
    $company_id = $_REQUEST['hidden_companyid'];
    $manager_id = $_REQUEST['manager'];
    db_b2b();

    // Fetch company name based on company ID
    $company_qry = db_query("SELECT company FROM companyInfo WHERE id = '" . $company_id . "'");
    $company_data = array_shift($company_qry);
    $company_name = str_replace("'", "\'", $company_data['company']);

    $employee_qry = db_query("SELECT name, mobile, password, email FROM employees WHERE employeeID = '" . $user_id . "'");
    $employee_data = array_shift($employee_qry);

    $user_name = str_replace("'", "\'", $employee_data['name']);

    $user_password = base64_encode($employee_data['password']);
    $user_email = $employee_data['email'];

    db();
    // Insert user_id and company_name into boomerang_usermaster
    // $insert_usr = db_query("INSERT INTO boomerang_usermaster (`user_id`,`manager_id`, `user_company`, `activate_deactivate`, `user_status`, `created_by`, `created_on`)
    // 	VALUES ('" . $user_id . "', '". $manager_id ."','" . $company_name . "', '1', '1', '" . $_COOKIE['b2b_id'] . "', '" . date('Y-m-d H:i:s') . "')");
    $insert_usr = db_query("INSERT INTO boomerang_usermaster (`user_id`, `manager_id`, `user_name`, `user_company`,  `password`, `activate_deactivate`, `user_email`, `user_status`, `created_by`, `created_on`)
        VALUES ('" . $user_id . "', '" . $manager_id . "', '" . $user_name . "',  '" . $company_name . "', '" . $user_password . "', '1', '" . $user_email . "', '1', '" . $_COOKIE['b2b_id'] . "', '" . date('Y-m-d H:i:s') . "')");


    echo 1;
}



if (isset($_REQUEST['form_action']) && $_REQUEST['form_action'] == "get_add_comp_b2bid") {
    $user_id = $_REQUEST['user_id'];

    if ($_REQUEST['comp_b2bid'] != "") {
        $tmppos_1 = strpos($_REQUEST['comp_b2bid'], ",");
        if ($tmppos_1 != false) {
            $companies = explode(",", $_REQUEST['comp_b2bid']);
        } else {
            $companies[] = $_REQUEST['comp_b2bid'];
        }

        db();

        if ($user_id != "" && count($companies) > 0) {
            foreach ($companies as $company) {
                db();
                $select_user_companies = db_query("SELECT company_id FROM boomerang_user_companies where user_id = '" . $user_id . "' and company_id = '" . $company . "'");
                $company_list_found = "no";
                while ($row1 = array_shift($select_user_companies)) {
                    $company_list_found = "yes";
                }

                if ($company_list_found == "no") {
                    db_query("INSERT INTO boomerang_user_companies (`user_id`,`company_id`) VALUES ('" . $user_id . "','" . $company . "')");

                    db_b2b();
                    $select_sell_to_address = db_query("SELECT contact,email,address,address2,city,state,zip,country,phone,email,company FROM companyInfo where ID = '" . $company . "'");
                    $sell_to_address = array_shift($select_sell_to_address);
                    db();
                    $comp_name = explode(" ", $sell_to_address['contact']);

                    $insert_qry = "INSERT INTO boomerang_user_addresses (user_id,status,first_name,last_name,company,country,addressline1,addressline2,city,state, zip,mobile_no,email,company_id,dock_hours,mark_default,created_on) 
					VALUES ('" . $user_id . "',1,'" . $comp_name[0] . "','" . $comp_name[1] . "','" . $sell_to_address['company'] . "','" . $sell_to_address['country'] . "','" . $sell_to_address['address'] . "','" . $sell_to_address['address2'] . "'
					,'" . $sell_to_address['city'] . "','" . $sell_to_address['state'] . "','" . $sell_to_address['zip'] . "','" . $sell_to_address['phone'] . "','" . $sell_to_address['email'] . "','" . $company . "','',0,'" . date('Y-m-d H:i:s') . "')";
                    db_query($insert_qry);
                }
            }
            echo "<script type='text/javascript'>window.location.reload();</script>";
            exit;
        }
    }
?>
    <tr>
        <td colspan="11" style="background: #FFF">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="bg_C1C1C1 align_center">Related Company Records</td>
    </tr>

    <tr>
        <td colspan=2>
            <input type="text" id="comp_b2bid" name="comp_b2bid" placeholder="Enter Company B2B ID">
            <button type="button" user_id="<?php echo $user_id; ?>" class='add_comp_b2bid'>Add B2B IDs</button>
        </td>
    </tr>

    <tr>
        <td style="width:50px;">B2B ID</td>
        <td style="width:150px;">Company Nickname</td>
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
					  <td>" . $row1['company_id'] . "</td>
					  <td><a target='_blank' href='viewCompany.php?ID=" . $company_name_row["ID"] . "'>" . get_nickname_val($company_name_row['company'], $company_name_row["ID"]) . "</a></td>
					  <td> <button type='button' compuser_id='" . $user_id . "' comp_id_to_remove='" . $row1['id'] . "' comp_b2bid_to_remove='" . $row1['company_id'] . "' class='remove_compid'>Remove</button> </td>
					  </tr>";
    }
    ?>

<?php
}

if (isset($_REQUEST['form_action']) && $_REQUEST['form_action'] == "get_remove_compid") {
    $user_id = $_REQUEST['user_id'];

    if ($_REQUEST['comp_id_to_remove'] != "") {
        db();

        db_query("delete from boomerang_user_addresses where `user_id` = '" . $user_id . "' and`company_id` = '" . $_REQUEST['comp_b2bid_to_remove'] . "'");

        db_query("delete from boomerang_user_companies where `user_id` = '" . $user_id . "' and `id` = '" . $_REQUEST['comp_id_to_remove'] . "'");
    }
?>
    <tr>
        <td colspan="11" style="background: #FFF">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="bg_C1C1C1 align_center">Related Company Records</td>
    </tr>

    <tr>
        <td colspan=2>
            <input type="text" id="comp_b2bid" name="comp_b2bid" placeholder="Enter Company B2B ID">
            <button type="button" user_id="<?php echo $user_id; ?>" class='add_comp_b2bid'>Add B2B IDs</button>
        </td>
    </tr>

    <tr>
        <td style="width:50px;">B2B ID</td>
        <td style="width:150px;">Company Nickname</td>
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
					  <td>" . $row1['company_id'] . "</td>
					  <td><a target='_blank' href='viewCompany.php?ID=" . $company_name_row["ID"] . "'>" . get_nickname_val($company_name_row['company'], $company_name_row["ID"]) . "</a></td>
					  <td> <button type='button' compuser_id='" . $user_id . "' comp_id_to_remove='" . $row1['id'] . "' comp_b2bid_to_remove='" . $row1['company_id'] . "' class='remove_compid'>Remove</button> </td>
					  </tr>";
    }
    ?>

<?php
}

if (isset($_REQUEST['form_action']) && $_REQUEST['form_action'] == "get_edit_user_data") {
    $user_id = $_REQUEST['user_id'];
    db();
    $user_data_qry = db_query("SELECT * FROM boomerang_usermaster where loginid = '" . $user_id . "'");
    $user_data = array_shift($user_data_qry);
    $password = $user_data['password'];
    $user_data['company_list'] = array();
    $select_user_companies = db_query("SELECT company_id FROM boomerang_user_companies where user_id = '" . $user_id . "'");
    if (tep_db_num_rows($select_user_companies) > 0) {
        while ($row1 = array_shift($select_user_companies)) {
            $user_data['company_list'][] = $row1['company_id'];
        }
    }

    $user_data['user_password'] = base64_decode($password);
    echo json_encode($user_data);
}

if (isset($_REQUEST['form_action']) && $_REQUEST['form_action'] == "update_user") {
    $loginid = $_REQUEST['user_id'];
    $user_name = $_REQUEST['user_name'];
    $user_email = $_REQUEST['user_email'];
    $user_password = $_REQUEST['user_password'];
    $companies = isset($_REQUEST['companies']) ? $_REQUEST['companies'] : array();
    $activate_deactivate = $_REQUEST['activate_deactivate'];
    $user_block = $_REQUEST['user_block'];

    $user_last_name = str_replace("'", "\'", $_REQUEST['user_last_name']);
    $user_company = str_replace("'", "\'", $_REQUEST['user_company']);
    $user_phone = $_REQUEST['user_phone'];
    $selectedManager = $_REQUEST['manager'];
    db();
    $update_user = db_query("UPDATE boomerang_usermaster SET `user_name` = '" . $user_name . "', `user_email` = '" . $user_email . "',
	`user_company` = '" . $user_company . "', `user_phone` = '" . $user_phone . "',`manager_id` = '".$selectedManager."', `user_last_name` = '" . $user_last_name . "',
	`password` = '" . base64_encode($user_password) . "', `activate_deactivate`='" . $activate_deactivate . "',`user_block`='" . $user_block . "' WHERE loginid = '" . $loginid . "'");
    // Fetch the current company data
    $current_companies_query = db_query("SELECT company_id FROM boomerang_user_companies WHERE user_id = '" . $loginid . "'");
    $current_companies = array();
    while ($row = array_shift($current_companies_query)) {
        $current_companies[] = $row['company_id'];
    }
    if (count($companies) == 0) {
        //db();
        //db_query("DELETE FROM boomerang_user_companies WHERE user_id = '" . $loginid . "'");
        //db_query("DELETE FROM boomerang_user_addresses WHERE user_id = '" . $loginid . "' && company_id != 0");
    } else {
        // Compare the current data with the new data
        $companies_to_add = array_diff($companies, $current_companies);
        $companies_to_remove = array_diff($current_companies, $companies);
        // Update the database only if there's a difference
        if (!empty($companies_to_add) || !empty($companies_to_remove)) {
            // Remove the companies that are not in the new data
            foreach ($companies_to_remove as $company_id) {
                db_query("DELETE FROM boomerang_user_companies WHERE user_id = '" . $loginid . "' AND company_id = '" . $company_id . "'");
                db_query("DELETE FROM boomerang_user_addresses WHERE user_id = '" . $loginid . "'  AND company_id = '" . $company_id . "'");
            }
            // Add the companies that are in the new data but not in the current data
            foreach ($companies_to_add as $company_id) {
                db_query("INSERT INTO boomerang_user_companies (user_id, company_id) VALUES ('" . $loginid . "', '" . $company_id . "')");
                db_b2b();
                $select_sell_to_address = db_query("SELECT contact,address,email,address2,city,state,zip,country,phone,email,company FROM companyInfo where id = '" . $company_id . "' ORDER BY id DESC LIMIT 1");
                $sell_to_address = array_shift($select_sell_to_address);
                db();
                $insert_qry = "INSERT INTO boomerang_user_addresses (user_id,status,first_name,last_name,company,country,addressline1,addressline2,city,state, zip,mobile_no,email,company_id,dock_hours,mark_default,created_on) 
				VALUES ('" . $loginid . "',1,'" . $sell_to_address['contact'] . "','','" . $sell_to_address['company'] . "','" . $sell_to_address['country'] . "','" . $sell_to_address['address'] . "','" . $sell_to_address['address2'] . "'
				,'" . $sell_to_address['city'] . "','" . $sell_to_address['state'] . "','" . $sell_to_address['zip'] . "','" . $sell_to_address['phone'] . "','" . $sell_to_address['email'] . "','" . $company_id . "','',0,'" . date('Y-m-d H:i:s') . "')";
                db_query($insert_qry);
            }
        }
    }
    echo 1;
}

if (isset($_REQUEST['form_action']) && $_REQUEST['form_action'] == "delete_user") {
    $user_id = $_REQUEST['user_id'];

    db();
    $user_status_val = 0;
    $select_user_companies = db_query("SELECT user_status FROM boomerang_usermaster where loginid = '" . $user_id . "'");
    while ($row1 = array_shift($select_user_companies)) {
        $user_status_val = $row1['user_status'];
    }

    if ($user_status_val == 1) {
        $update_user = db_query("UPDATE boomerang_usermaster SET `user_status` = '0' WHERE loginid = '" . $user_id . "'");
    } else {
        $update_user = db_query("UPDATE boomerang_usermaster SET `user_status` = '1' WHERE loginid = '" . $user_id . "'");
    }
    echo 1;
}
