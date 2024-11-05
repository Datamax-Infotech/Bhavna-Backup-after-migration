<?php
ini_set("display_errors", "1");
error_reporting(E_ERROR);
require("inc/header_session.php");
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
// function encrypt_password($txt)
// {
//     $key = "1sw54@Asa$offj";

//     $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
//     $iv = openssl_random_pseudo_bytes($ivlen);
//     $ciphertext_raw = openssl_encrypt($txt, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
//     $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
//     $ciphertext = base64_encode($iv . $hmac . $ciphertext_raw);
//     return $ciphertext;
// }

$comp_b2bid = 0;
if (isset($_REQUEST["ID"])) {
    $comp_b2bid = $_REQUEST["ID"];
}
//echo base64_decode("a2VsbGkxMjM=");
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
    </style>
    <LINK rel='stylesheet' type='text/css' href='one_style.css'>
    <link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<?php
include("inc/header.php");
?>
<div class="main_data_css">
    <div style="height: 13px;">&nbsp;</div>
    <div style="border-bottom: 1px solid #C8C8C8; padding-bottom: 10px;">
        <img src="images/boomerang-logo.jpg" alt="moving boxes"> &nbsp;&nbsp; &nbsp;&nbsp;
        <!--<a href="viewCompany.php?ID=<?php echo  isset($ID) ?>">View B2B page</a> &nbsp;&nbsp;
			<a target="_blank" href="https://clientold.usedcardboardboxes.com/client_dashboard.php?compnewid=<?php echo  isset($ID) ?>&repchk=yes">Old Client dash</a> &nbsp;&nbsp; -->
        <?php if (isset($_REQUEST["ID"])) { ?>
            <a target="_blank" href="https://clientold.usedcardboardboxes.com/client_dashboard.php?compnewid=<?php echo $_REQUEST["ID"] ?>&repchk=yes">Original
                Older Client Dash</a> &nbsp;&nbsp;
            <a target="_blank" href="https://client.usedcardboardboxes.com/client_dashboard.php?compnewid=<?php echo urlencode(encrypt_password($_REQUEST["ID"])); ?>&repchk=yes">Old
                Client dash</a> &nbsp;&nbsp;
        <?php } ?>

        <a target="_blank" href="https://boomerang.usedcardboardboxes.com">Boomerang Login Page</a>
    </div>
    <div id="light" class="white_content"> </div>
    <table class="table_boomerang_portal">
        <tr>
            <td colspan="6" width="320px" style="background:#E8EEA8; text-align:center"><strong>Boomerang Portal User
                    Setup</strong></font>
            </td>
        </tr>

        <?php
        if (isset($_GET['displaydata']) && $_GET['displaydata'] == "Boom_beta") {
            $ID = $_GET['ID'];
        ?>

            <tr align="center">
                <td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">Invite a New User</td>
            </tr>


            <tr align="center" style="margin-bottom:10px">

                <td colspan="5" height="30px" width="320px" align="left">
                    <a target="_blank" href="boomerang_user_invite.php?ID=<?php echo $ID ?>">click here to Sent a Invite Link</a>
                </td>
            </tr>


            <form class="save_boomrang_user">
                <input type="hidden" name="hidden_companyid" value="<?php echo $ID; ?>" />
                <input type="hidden" name="boomrang" value="Boom_beta" />

                <tr align="center">
                    <td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">Add new user for Boomerang</td>
                </tr>
                <tr align="center">
                    <td align="left" width="180px">Username: </td>
                    <td colspan="5" width="320px" align="left">
                        <select name="userid" id="userid" onchange="handleUserChange()">
                            <option value="">Select User</option>
                            <?php
                            db_b2b();
                            $user_res = db_query("SELECT * FROM employees ORDER BY name ASC");
                            while ($user = array_shift($user_res)) {
                                echo '<option value="' . $user['employeeID'] . '">' . $user['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Select Manager: &nbsp; &nbsp;</td>
                    <td colspan="5" width="320px">
                        <select name="manager" id="manager" >
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
                    </td>
                </tr>
                <tr align="center">
                    <td width="80px">&nbsp;</td>
                    <td colspan="5" width="320px" align="left">
                        <input type="hidden" name="form_action" value="add_boom_user" class="cls__boom_form_action" />
                        <input type="submit" value="Add User" />
                    </td>
                </tr>

            </form>

        <?php

        }
        ?>

        <?php if (!isset($_REQUEST["ID"])) { ?>
            <tr>
                <td colspan="6" class="bg_C1C1C1 align_center">Add new user for Customer</td>
            </tr>
            <tr>
                <td colspan="6">
                    <form class="save_user">
                        <table>
                            <tr>
                                <td>Name: </td>
                                <td colspan="5">
                                    <input type="text" name="user_name" id="user_name" value="" placeholder="Enter first name" />
                                    <span class="color_red hide_error user_name_error">Name can't be blank!</span>
                                    <input type="text" name="user_last_name" value="" placeholder="Enter last name" />
                                </td>
                            </tr>
                            <tr>
                            <td>Select Manager: &nbsp; &nbsp;</td>
                            <td colspan="5">
                                <select name="manager" id="manager">
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
                            </td>
                            </tr>
                            <tr>
                                <td>Company Name: </td>
                                <td colspan="5">
                                    <input type="text" name="user_company" size="46" id="user_company" value="" placeholder="Enter company name" />
                                    <span class="color_red hide_error user_company_error">Company Name can't be
                                        blank!</span>
                                </td>
                            </tr>

                            <tr>
                                <td>Email: </td>
                                <td colspan="5">
                                    <input type="email" name="user_email" size="46" id="user_email_add" value="" onblur="checkDuplicate_email('add_user')" placeholder="Enter email address" />
                                    <spanc id="user_email_add_error" class="color_red hide_error useremail_error">Email
                                        can't be blank!</spanc>
                                </td>
                            </tr>

                            <tr>
                                <td>Phone: </td>
                                <td colspan="5">
                                    <input type="text" name="user_phone" size="46" id="user_phone" value="" placeholder="Enter phone number" />
                                    <span id="user_phone_error" class="color_red hide_error user_phone_error">Phone can't be
                                        blank!</span>
                                </td>
                            </tr>

                            <tr>
                                <td>Password: </td>
                                <td colspan="5">
                                    <input type="password" name="user_password" size="46" value="" />
                                    <span class="color_red hide_error user_password_error">Password can't be blank!</span>
                                </td>
                            </tr>
                            <!-- <tr>
							<td>Select Companies: &nbsp; &nbsp;</td>
							<td colspan="5">
								<select style="height:150px;" multiple name="companies[]" id="all_companies">
									<option>Select Companies</option>
									<?php
                                    db_b2b();
                                    $select_sales_comp = db_query("SELECT ID, company FROM companyInfo where haveNeed = 'Need Boxes' and active = 1 and company <>'' and loopid > 0 ORDER BY nickname");
                                    while ($row = array_shift($select_sales_comp)) {
                                        echo "<option value='" . $row['ID'] . "'>" . get_nickname_val($row['company'], $row["ID"]) . "</option>";
                                    }
                                    ?>
								</select>
							</td>
						</tr> -->
                            <tr>
                                <td>&nbsp;</td>
                                <td colspan="5">
                                    <input type="hidden" name="form_action" value="add_user" class="cls_form_action" />
                                    <input type="submit" value="Add User" />
                                </td>
                            </tr>
                        </table>

                    </form>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="6" class="bg_C1C1C1 align_center">User List</td>
        </tr>
        <tr>
            <td colspan="6">
                <?php if (isset($_GET['displaydata']) && $_GET['displaydata'] == "Boom_beta") { ?>
                    <table class="tbl_border" style="width: 100%">
                        <tr>
                            <td width="150px">Name</td>
                            <td width="150px">Company Name</td>
                            <td width="150px">Email</td>
                            <td width="50px">Phone</td>
                            <td width="50px">Status</td>
                            <td width="50px">Blocked</td>
                            <td width="150px">Manager</td> <!-- Added Manager column -->
                        </tr>
                        <?php
                        db_b2b();
                        $mainqry = "";
                        $companyQuery = "SELECT company FROM companyInfo WHERE ID = '" . $ID . "'";
                        $companyResult = db_query($companyQuery);
                        $companyData = array_shift($companyResult);
                        $company_name = $companyData ? $companyData['company'] : '';
                        db();
                        $mainqry = "SELECT * FROM boomerang_usermaster WHERE user_status = 1 AND user_company = '" . $company_name . "' ORDER BY loginid DESC";

                        if ($mainqry != "") {
                            $select_users = db_query($mainqry);
                            if (tep_db_num_rows($select_users) > 0) {
                                while ($row = array_shift($select_users)) {
                                    db();

                                    // Determine user ID to fetch the correct data
                                    $userid = $row['user_id']; // Assuming this is the correct field from boomerang_usermaster

                                    if ($userid == 0) {
                                        // Use the information from boomerang_usermaster
                                        $user_name = $row['user_name'] . " " . $row['user_last_name'];
                                        $user_email = $row['user_email'];
                                        $user_phone = $row['user_phone'];
                                    } else {
                                        // Fetch data from employees table for non-zero user_id
                                        db_b2b();
                                        $employeeQry = "SELECT name, email, mobile FROM employees WHERE employeeID = $userid";
                                        $employeeRes = db_query($employeeQry);
                                        $employeeData = array_shift($employeeRes);

                                        // Default values if employee data is not found
                                        $user_name = "N/A";
                                        $user_email = "N/A";
                                        $user_phone = "N/A";

                                        // Check if employee data was found
                                        if ($employeeData) {
                                            $user_name = $employeeData['name'];
                                            $user_email = $employeeData['email'];
                                            $user_phone = $employeeData['mobile'];
                                        }
                                    }

                                    // Fetch company names
                                    db();
                                    $select_user_companies = db_query("SELECT company_id FROM boomerang_user_companies WHERE user_id = '" . $row['loginid'] . "'");
                                    $company_list = "";
                                    if (tep_db_num_rows($select_user_companies) > 0) {
                                        while ($row1 = array_shift($select_user_companies)) {
                                            db_b2b();
                                            $company_name = db_query("SELECT company, ID FROM companyInfo WHERE ID = '" . $row1['company_id'] . "'");
                                            $company_name = array_shift($company_name);
                                            $company_list .= get_nickname_val($company_name['company'], $company_name["ID"]) . "<br>";
                                        }
                                    }

                                    // Fetch manager name based on manager_id
                                    $manager_display = 'N/A'; // Default value for manager name
                                    if ($row['manager_id'] > 0) { // Only fetch if manager_id is greater than 0
                                        db();
                                        $manager_query = db_query("SELECT username FROM loop_employees WHERE id = '" . $row['manager_id'] . "'");
                                        $manager_result = array_shift($manager_query);
                                        if ($manager_result) {
                                            $manager_display = $manager_result['username'];
                                        }
                                    }
                                    //echo $mainqry;
                                    echo "<tr id='userrowid_" . $row['loginid'] . "'>
                            <td>" . $user_name . "</td>
                            <td>" . $row['user_company'] . "</td>
                            <td><a href='boomerang_users_profile.php?user_id=" . $row['loginid'] . "'>" . $user_email . "</a></td>
                            <td>" . $user_phone . "</td>
                            <td>" . ($row['activate_deactivate'] == 1 ? 'Active' : 'Deactive') . "</td>
                            <td>" . ($row['user_block'] == 0 ? 'Unblocked' : 'Blocked') . "</td>
                            <td>" . $manager_display . "</td> <!-- Displaying manager name -->
                        </tr>";
                                }
                            } else {
                                echo "<tr><td class='color_red align_center' colspan='7'>No records found</td></tr>"; // Updated colspan to match the new column
                            }
                        } else {
                            echo "<tr><td class='color_red align_center' colspan='7'>No records found</td></tr>"; // Updated colspan to match the new column
                        }
                        ?>
                    </table>
                <?php } else { ?>
                    <table class="tbl_border" style="width: 100%">
                        <tr>
                            <td width="150px">Name</td>
                            <td width="150px">Company Name</td>
                            <td width="150px">Email</td>
                            <td width="50px">Phone</td>
                            <td width="50px">Status</td>
                            <td width="50px">Blocked</td>
                            <td width="150px">Manager</td> <!-- Added Manager column -->
                        </tr>
                        <?php
                        db();
                        $mainqry = "";
                        if ($comp_b2bid > 0) {
                            $select_user_companies = db_query("SELECT id, user_id FROM boomerang_user_companies WHERE company_id = '" . $comp_b2bid . "'");
                            $company_user_id_list = "";
                            while ($row1 = array_shift($select_user_companies)) {
                                $company_user_id_list .= $row1["user_id"] . ",";
                            }
                            if ($company_user_id_list != "") {
                                $company_user_id_list = rtrim($company_user_id_list, ",");
                                $mainqry = "SELECT * FROM boomerang_usermaster WHERE user_status = 1 AND loginid IN ($company_user_id_list) ORDER BY loginid DESC";
                            }
                        } else {
                            $mainqry = "SELECT * FROM boomerang_usermaster WHERE user_status = 1 ORDER BY loginid DESC";
                        }

                        if ($mainqry != "") {
                            $select_users = db_query($mainqry);
                            if (tep_db_num_rows($select_users) > 0) {
                                while ($row = array_shift($select_users)) {
                                    db();

                                    // Determine user ID to fetch the correct data

                                    // Use the information from boomerang_usermaster
                                    $user_name = $row['user_name'] . " " . $row['user_last_name'];
                                    $user_email = $row['user_email'];
                                    $user_phone = $row['user_phone'];


                                    // Fetch company names
                                    db();
                                    $select_user_companies = db_query("SELECT company_id FROM boomerang_user_companies WHERE user_id = '" . $row['loginid'] . "'");
                                    $company_list = "";
                                    if (tep_db_num_rows($select_user_companies) > 0) {
                                        while ($row1 = array_shift($select_user_companies)) {
                                            db_b2b();
                                            $company_name = db_query("SELECT company, ID FROM companyInfo WHERE ID = '" . $row1['company_id'] . "'");
                                            $company_name = array_shift($company_name);
                                            $company_list .= get_nickname_val($company_name['company'], $company_name["ID"]) . "<br>";
                                        }
                                    }

                                    // Fetch manager name based on manager_id
                                    $manager_display = 'N/A'; // Default value for manager name
                                    if ($row['manager_id'] > 0) { // Only fetch if manager_id is greater than 0
                                        db();
                                        $manager_query = db_query("SELECT username FROM loop_employees WHERE id = '" . $row['manager_id'] . "'");
                                        $manager_result = array_shift($manager_query);
                                        if ($manager_result) {
                                            $manager_display = $manager_result['username'];
                                        }
                                    }
                                    //echo $mainqry;
                                    echo "<tr id='userrowid_" . $row['loginid'] . "'>
                            <td>" . $user_name . "</td>
                            <td>" . $row['user_company'] . "</td>
                            <td><a href='boomerang_users_profile.php?user_id=" . $row['loginid'] . "'>" . $user_email . "</a></td>
                            <td>" . $user_phone . "</td>
                            <td>" . ($row['activate_deactivate'] == 1 ? 'Active' : 'Deactive') . "</td>
                            <td>" . ($row['user_block'] == 0 ? 'Unblocked' : 'Blocked') . "</td>
                            <td>" . $manager_display . "</td> <!-- Displaying manager name -->
                        </tr>";
                                }
                            } else {
                                echo "<tr><td class='color_red align_center' colspan='7'>No records found</td></tr>"; // Updated colspan to match the new column
                            }
                        } else {
                            echo "<tr><td class='color_red align_center' colspan='7'>No records found</td></tr>"; // Updated colspan to match the new column
                        }
                        ?>
                    </table>
                <?php } ?>
            </td>
        </tr>



        <?php
        db();
        $mainqry = "";
        if ($comp_b2bid > 0) {
            $select_user_companies = db_query("SELECT id, user_id FROM boomerang_user_companies where company_id = '" . $comp_b2bid . "'");
            $company_user_id_list = "";
            while ($row1 = array_shift($select_user_companies)) {
                $company_user_id_list = $row1["user_id"] . ",";
            }
            if ($company_user_id_list != "") {
                $company_user_id_list = substr($company_user_id_list, 0, strlen($company_user_id_list) - 1);
                $mainqry = "SELECT * FROM boomerang_usermaster where user_status = 0 and loginid in ($company_user_id_list) ORDER BY loginid DESC";
            }
        } else {
            $mainqry = "SELECT * FROM boomerang_usermaster where user_status = 0 ORDER BY loginid DESC";
        }
        if ($mainqry != "") {
            $select_users = db_query($mainqry);
            if (tep_db_num_rows($select_users) > 0) {
        ?>
                <tr>
                    <td colspan="6" style="height:20px;"></td>
                </tr>

                <tr>
                    <td colspan="6" class="bg_C1C1C1 align_center">Inactive User List</td>
                </tr>
                <tr>
                    <td colspan="6">
                        <table class="tbl_border" style="width: 100%">
                            <tr>
                                <td width="150px">Name</td>
                                <td width="150px">Company Name</td>
                                <td width="150px">Email</td>
                                <td width="50px">Phone</td>
                                <td width="50px">Status</td>
                                <td width="50px">Blocked</td>
                            </tr>

                            <?php
                            while ($row = array_shift($select_users)) {
                                echo "<tr id='userrowid_" . $row['loginid'] . "'>
								<td>" . $row['user_name'] . " " . $row['user_last_name'] . "</td>
								<td>" . $row['user_company'] . "</td>
								<td><a href='boomerang_users_profile.php?user_id=" . $row['loginid'] . "'>" . $row['user_email'] . "</a></td>
								<td>" . $row['user_phone'] . "</td>
								<td>" . ($row['user_status'] == 1 ? 'Active' : 'Deactive') . "</td>
								<td>" . ($row['user_block'] == 0 ? 'Unblocked' : 'Blocked') . "</td>
								</tr>";

                                /*<td>
									<button type='button' user_id='" . $row['loginid'] . "' class='edit_user'>Edit</button>
									<button type='button' user_id='" . $row['loginid'] . "' class='delete_user'>Delete</button>
									<button type='button'><a href='boomerang_users_profile.php?user_id=".$row['loginid']."'>View</a></button>
								</td>	*/
                            }
                            ?>
                        </table>
                    </td>
                </tr>

        <?php                         }
        }
        ?>
    </table>

    <script>
        let selectedUserId = ""; // Variable to store the selected user ID

        function handleUserChange() {
            selectedUserId = document.getElementById("userid").value; // Get the selected value
            console.log("Selected User ID: " + selectedUserId);
        }
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

        // async function checkDuplicateUserId(input_form_action, user_id) {
        //     let res = 2;
        //     await $.ajax({
        //         url: 'boomerang_users_action.php',
        //         data: {
        //             user_id,
        //             form_action: 'check_duplicate_user_id'
        //         },
        //         method: "post",
        //         async: false,
        //         success: function(response) {
        //             console.log("Server Response:", response); // Debugging: check exact server response in console

        //             // Check if response is greater than 0 (indicating duplicate)
        //             if (parseInt(response, 10) > 0) {
        //                 alert('User already exists!');
        //                 res = 0;
        //             } else {
        //                 res = 1;
        //             }
        //         }
        //     });
        //     return res;
        // }

        async function checkDuplicateUserId(input_form_action, selectedUserId) {
            var res = 2;
            await $.ajax({
                url: 'boomerang_users_action.php',
                data: {
                    selectedUserId,
                    form_action: 'check_duplicate_user_id'
                },
                method: "post",
                dataType: 'json', // Expect JSON response
                success: function(response) {
                    // Log user_id to the console
                    // console.log("Received user_id: " + response.user_id);
                    // console.log("Duplicate count: " + response.count);

                    if (response.count > 0) {
                        alert('User already exists!');
                        res = 0;
                    } else {
                        res = 1;
                    }
                }
            });
            return res;
        }

        $(document).ready(function() {
            $('body').on('submit', ".save_user", async function(event) {
                event.preventDefault();
                var input_form_action = $(this).find('.cls_form_action').val();
                var username = $(this).find("input[name='user_name']").val();
                var selectedManager = $(this).find("select[name='manager']").val();
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







            $('body').on('submit', ".save_boomrang_user", async function(event) {
                event.preventDefault();
                var input_form_action = $(this).find('.cls__boom_form_action').val();
                var flag = true;
                if (selectedUserId == "") {
                    alert('Please select a user.');
                    flag = false;
                }

                // Call the duplicate user ID check function instead of checking for duplicate email
                var duplicate_user = await checkDuplicateUserId(input_form_action, selectedUserId);
                // console.log("Duplicate User Response: " + duplicate_user);

                if (flag === true && duplicate_user === 1) {
                    var all_data = new FormData(this);
                    $.ajax({
                        url: 'boomerang_users_action.php',
                        data: all_data,
                        method: "post",
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            //console.log("Response from server:", response);
                            if (response == 1) {
                                alert('User added successfully');
                                location.reload();
                            } else {
                                alert('Something went wrong, try again later');
                            }
                        }
                    });
                }
                return false;
            });


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
                        //var all_companies_dp = $('#all_companies').html();
                        var company_list = data.company_list;
                        // Create an array of company IDs from the company_list
                        var company_ids = company_list.map(function(company) {
                            return company;
                        });

                        // Add the 'selected' attribute to the options that match the company IDs
                        //all_companies_dp = all_companies_dp.replace(/<option value="(\d+)">/g, function(match, id) {
                        //	return '<option value="' + id + '"' + (company_ids.includes(Number(id)) ? ' selected' : '') + '>';
                        //});

                        //<td><select multiple name="companies[]">${all_companies_dp}</select></td>

                        var edit_html = `<td colspan="6"><form class="save_user"><table>
						<td>
							<input type="text" name="user_name" value="${data.user_name}">
							<br><span class="color_red hide_error user_name_error">Name can't be blank!</span>
							<input type="text" name="user_last_name" value="${data.user_last_name}">
						</td>
						<td>
							<input type="email" name="user_email" id="user_email_update" value="${data.user_email}" onblur="checkDuplicate_email('update_user')">
							<span id="user_email_update_error" class="color_red hide_error useremail_error" >Email can't be blank!</span>
							<br>
							<input type="password" name="user_password" value="${data.user_password}">
							<span class="color_red hide_error user_password_error">Password can't be blank!</span>
						</td>
						<td><select name="activate_deactivate"><option></option><option value="1" ${data.activate_deactivate == 1 ? "selected" : ""}>Active</option><option value="0" ${data.activate_deactivate == 0 ? "selected" : ""}>Deactive</option></select></td>
						<td><select name="user_block"><option></option><option value="1" ${data.user_block == 1 ? "selected" : ""}>Block</option><option value="0" ${data.user_block == 0 ? "selected" : ""}>Unblock</option></select></td>
						<td>
							<input type="hidden" name="form_action" value="update_user" class="cls_form_action"> 
							<input type="hidden" name="user_id" id="user_id" value="${user_id}">
							<input type="submit" value="Update">
							<button type="button" class="cancel_edit">Cancel</button>
						</td></tr></table></form></td>`;
                        $('#userrowid_' + user_id).html(edit_html);
                        //$('#userrowid_' + user_id + ' td').wrap("<form class='save_user'></form>");
                    }
                })
            });

            $('body').on('click', '.cancel_edit', function() {
                location.reload();
            });

            $('body').on('click', ".delete_user", function() {
                var user_id = $(this).attr('user_id');
                alert("Do you sure want to delete this user?");
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
                            alert('User deleted successfully');
                            location.reload();
                        } else {
                            alert("Something went wrong, try again later")
                        }
                    }
                });
            })


        });
    </script>