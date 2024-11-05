<?php
ini_set("display_errors", "1");
error_reporting(E_ERROR);
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
if (isset($_REQUEST['form_action']) && $_REQUEST['form_action'] == "add_profile") {
    // echo "hi: ";
    $user_name = $_REQUEST['user_name'];
    $user_email = $_REQUEST['user_email'];
    $user_last_name = $_REQUEST['user_last_name'];
    $user_phone = $_REQUEST['user_phone'];
    $user_password = $_REQUEST['user_password'];
    $company_id = $_REQUEST['company_id'];

    db_b2b();

    // Fetch company name using the company_id
    $company_result = db_query("SELECT company FROM companyInfo WHERE id = '" . intval($company_id) . "' LIMIT 1");
    $company_name = '';

    if ($company_row = array_shift($company_result)) {
        $company_name = $company_row['company'];
    }
	 db();
    $insert_user = db_query("INSERT INTO boomerang_usermaster (`user_name`, `password`, `activate_deactivate`, `user_email`, `user_status`, `user_last_name`, `user_phone`, `user_company`) 
    VALUES ('" . $user_name . "', '" . base64_encode($user_password) . "', 1, '" . $user_email . "', 1, '" . $user_last_name . "', '" . $user_phone . "', '" . $company_name . "')");
    $user_id = tep_db_insert_id();
    $user_id = $user_id ? $user_id : 0;
    
    $sent_email_address = $_REQUEST['sent_email_address'];
    //echo 'UPDATE boomerang_user_invite set done_register = 1 where email = "' . $sent_email_address . '"';
    db_query('UPDATE boomerang_user_invite set done_register = 1 where email = "' . $sent_email_address . '"');
    echo $user_id;
}

if (isset($_REQUEST['form_action']) && $_REQUEST['form_action'] == "add_address") {
    db();
    $user_email = $_REQUEST['email'];
    $result = db_query("SELECT loginid FROM boomerang_usermaster WHERE user_email = '" . $user_email . "' LIMIT 1");

    // Ensure the result is not empty
    if ($row = array_shift($result)) {
        $user_id = $row['loginid'];
        // echo "User inserted successfully with ID: " . $user_id;
    }
    // } else {
    //     echo "Error inserting user";
    // }
    if (!empty($user_id)) {
        $add_set_as_def = isset($_REQUEST['add_set_as_def']) ? 1 : 0;
        db();
        $insert_qry = db_query("INSERT INTO boomerang_user_addresses (user_id,status,first_name,last_name,company,country,addressline1,addressline2,city,state, zip,mobile_no,email,company_id,dock_hours,mark_default,created_on) 
        VALUES ('" . $user_id . "',1,'" . $_REQUEST['first_name'] . "','" . $_REQUEST['last_name'] . "','" . $_REQUEST['company'] . "','" . $_REQUEST['country'] . "','" . $_REQUEST['addressline1'] . "','" . $_REQUEST['addressline2'] . "'
        ,'" . $_REQUEST['city'] . "','" . $_REQUEST['state'] . "','" . $_REQUEST['zip'] . "','" . $_REQUEST['mobile_no'] . "','" . $_REQUEST['email'] . "',0,'" . $_REQUEST['dock_hours'] . "','" . $add_set_as_def . "','" . date('Y-m-d H:i:s') . "')");
        echo 1;

    }
}
