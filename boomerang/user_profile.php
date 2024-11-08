<?php
ob_start(); // Turn on output buffering
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'logout') {

    $date_of_expiry = time() - 2;
    setcookie("loginid", "", $date_of_expiry);
    header("Location: index.php");
    exit('Redirecting...'); // Ensure the remaining script doesn't execute
}
?>
<title>My Profile - Boomerange Portal</title>
<?php
require("inc/header_session_client.php");
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
require_once('boomerange_common_header.php');

?>
<main>
    <div class="container mt-5">
        <div class="row">
            <div class="col-3">
                <div class="nav flex-column nav-pills my-account-tab" id="my-account-tab" role="tablist"
                    aria-orientation="vertical">
                    <button class="nav-link active" id="my-profile-tab" data-toggle="pill" data-target="#my-profile"
                        role="tab" aria-controls="my-profile" aria-selected="true">My Profile</button>
                    <button class="nav-link" id="change-password-tab" data-toggle="pill" data-target="#change-password"
                        role="tab" aria-controls="change-password" aria-selected="false">Change Password</button>
                    <button class="nav-link" id="manage-address-tab" data-toggle="pill" data-target="#manage-address"
                        role="tab" aria-controls="manage-address" aria-selected="false">Manage Addresses</button>
                    <a class="nav-link" href="client_dashboard_new.php?show=closed_loop_inv<?php echo  $repchk_str ?>"
                        onclick="show_loading()">Closed Loop Inventory</a>
                    <a class="nav-link" href="client_dashboard_new.php?show=sales_quotes<?php echo  $repchk_str ?>"
                        onclick="show_loading()">Sales quotes</a>
                    <a class="nav-link" href="client_dashboard_new.php?show=favorites<?php echo  $repchk_str ?>"
                        onclick="show_loading()">Favorites</a>
                    <a class="nav-link" href="client_dashboard_new.php?show=prev_order<?php echo  $repchk_str ?>"
                        onclick="show_loading()">Previously Ordered</a>
                    <a class="nav-link" href="client_dashboard_new.php?show=current_order<?php echo  $repchk_str ?>"
                        onclick="show_loading()">Open Orders</a>
                    <a class="nav-link"
                        href="client_dashboard_new.php?show=current_order_history<?php echo  $repchk_str ?>"
                        onclick="show_loading()">Order History</a>
                    <a class="nav-link" href="client_dashboard_new.php?show=accounting<?php echo  $repchk_str ?>"
                        onclick="show_loading()">Accounting</a>
                    <a class="nav-link" href="client_dashboard_new.php?show=reports<?php echo  $repchk_str ?>"
                        onclick="show_loading()">Reports</a>
                    <a href="user_profile.php?action=logout" class="nav-link">Logout</a>
                </div>
            </div>
            <div class="col-9">
                <div class="tab-content" id="my-account-tabContent">
                    <div class="tab-pane fade show active" id="my-profile" role="tabpanel"
                        aria-labelledby="my-profile-tab">
                        <?php
                        $loginid = $_COOKIE['loginid'];
                        db();
                        $user_info_qry = db_query("SELECT user_name,user_email,password, user_last_name, user_phone, user_company FROM boomerang_usermaster WHERE loginid = '" . $loginid . "'");
                        $user_info = array_shift($user_info_qry);
                        $user_name = $user_info['user_name'];
                        $user_last_name = $user_info['user_last_name'];
                        $user_phone = $user_info['user_phone'];
                        $user_company = $user_info['user_company'];
                        $user_email = $user_info['user_email'];
                        ?>
                        <div class="container_fluid">
                            <div class="row">
                                <div class="col-md-8">
                                    <form id="edit_profile_form">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input id="user_name" type="text"
                                                        class="form-control form-control-sm" name="user_name"
                                                        placeholder="Enter First Name"
                                                        value="<?php echo $user_name; ?>">
                                                    <span class="form_error d-none" id="user_name_error">Username can't
                                                        be blank</span>
                                                </div>
                                                <div class="col-md-6">
                                                    <input id="user_last_name" type="text"
                                                        class="form-control form-control-sm" name="user_last_name"
                                                        placeholder="Enter Last Name"
                                                        value="<?php echo $user_last_name; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Company</label>
                                            <input id="user_company" type="text" class="form-control form-control-sm"
                                                name="user_company" placeholder="Enter Company"
                                                value="<?php echo $user_info['user_company']; ?>">
                                            <span class="form_error d-none" id="user_email_error">Company can't be
                                                blank</span>
                                        </div>

                                        <div class="form-group">
                                            <label>Email</label>
                                            <input id="user_email" type="email" class="form-control form-control-sm"
                                                name="user_email" placeholder="Enter Email"
                                                value="<?php echo $user_info['user_email']; ?>">
                                            <span class="form_error d-none" id="user_email_error">Email Can't Be
                                                Blank</span>
                                        </div>

                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input id="user_phone" type="text" class="form-control form-control-sm"
                                                name="user_phone" placeholder="Enter Phone"
                                                value="<?php echo $user_info['user_phone']; ?>">
                                            <span class="form_error d-none" id="user_email_error">Phone can't be
                                                blank</span>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <input type="hidden" name="form_action" value="edit_profile">
                                            <input type="hidden" name="user_id" value="<?php echo $loginid; ?>">
                                            <button type="submit" class="btn btn-custom">Apply Changes</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                    <p class="mt-2"><b>Name:</b> <?php echo $user_name . " " . $user_last_name; ?></p>
                                    <p class="mt-2"><b>Company:</b> <?php echo $user_info['user_company']; ?></p>
                                    <p class="mt-2"><b>Email:</b> <?php echo $user_info['user_email']; ?></p>
                                    <p class="mt-2"><b>Phone:</b> <?php echo $user_info['user_phone']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="change-password" role="tabpanel"
                        aria-labelledby="change-password-tab">
                        <div class="col-md-8">
                            <form id="change_password_form">
                                <div class="form-group">
                                    <label>Old Password</label>
                                    <input id="user_password" type="password" class="form-control form-control-sm"
                                        placeholder="Enter Old Password">
                                    <span class="form_error d-none" id="user_password_error"></span>
                                </div>
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input id="new_password" type="password" class="form-control form-control-sm"
                                        name="new_password" placeholder="Enter New Password">
                                    <span class="form_error d-none" id="new_password_error"></span>
                                </div>
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input id="conf_password" type="password" class="form-control form-control-sm"
                                        placeholder="Renter New Password">
                                    <span class="form_error d-none" id="conf_password_error"></span>
                                </div>
                                <div class="mb-3">
                                    <input id="user_old_password" type="hidden" class="form-control form-control-sm"
                                        value="<?php echo base64_decode($user_info['password']); ?>">
                                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $loginid; ?>">
                                    <button type="submit" class="btn btn-custom">Apply Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="manage-address" role="tabpanel" aria-labelledby="manage-address-tab">
                        <div class="text-right mb-3">
                            <button class="btn btn-primary btn-sm" onclick="set_user_profile_info()">Add
                                Address</button>
                        </div>
                        <?php
                        db();
                        $address_qry = db_query("SELECT * FROM boomerang_user_addresses WHERE status = 1 && user_id = '" . $loginid . "' ORDER BY id DESC");
                        if (tep_db_num_rows($address_qry) > 0) {
                            while ($address = array_shift($address_qry)) { ?>
                                <div class="card mt-3 <?php echo $address['mark_default'] == 1 ? "border-success" : ""; ?>">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <p><b>Name:</b>
                                                    <?php echo $address['first_name'] . " " . $address['last_name'] ?></p>
                                                <p><b>Company:</b> <?php echo $address['company'] ?></p>
                                                <p><b>Address: </b><?php echo $address['addressline1'] ?></p>
                                                <p><?php echo $address['addressline2'] ?></p>
                                                <p><?php echo $address['city'] ?>, <?php echo $address['state'] ?>,
                                                    <?php echo $address['zip'] ?></p>
                                                <p><b>Country:</b> <?php echo $address['country'] ?></p>
                                                <p><b>Mobile No:</b> <?php echo $address['mobile_no'] ?></p>
                                                <p><b>Email:</b> <?php echo $address['email'] ?></p>
                                                <p><b>Dock Hours:</b> <?php echo $address['dock_hours'] ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <a href="javascript:void(0);" address_id="<?php echo $address['id']; ?>"
                                                    class="edit_address_btn text-primary"><span class="fa fa-pencil"></span>
                                                    Edit</a><br>
                                                <a href="javascript:void(0);" address_id="<?php echo $address['id']; ?>"
                                                    class="delete_address_btn text-danger"><span class="fa fa-trash"></span>
                                                    Delete</a><br>
                                                <?php if ($address['mark_default'] == 1) {
                                                    echo "<span class='text-success'><span class='fa fa-check'></span>Default Address</span>";
                                                } else { ?>
                                                    <a href="javascript:void(0);" address_id="<?php echo $address['id']; ?>"
                                                        class="text-success mark_default_add"><span class="fa fa-map-pin"></span>
                                                        Set As Default</a>
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
        </div>
    </div>
</main>

<div class="modal fade" id="addAddressModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="addAddressModalLabel" aria-hidden="true">
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
                            <input id="add_first_name" type="text" class="form-control form-control-sm"
                                name="first_name" placeholder="Enter First Name" value="<?php echo $user_name; ?>">
                            <span class="form_error d-none" id="add_first_name_error">First Name Can't Be Blank</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Last Name</label>
                            <input id="add_last_name" type="text" class="form-control form-control-sm" name="last_name"
                                placeholder="Enter Last Name" value="<?php echo $user_last_name; ?>">
                            <span class="form_error d-none" id="add_last_name_error">Last Name Can't Be Blank</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Company</label>
                            <input id="add_company" type="text" class="form-control form-control-sm" name="company"
                                placeholder="Enter Company" value="<?php echo $user_company; ?>">
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
                            <input id="add_addressline1" type="text" class="form-control form-control-sm"
                                name="addressline1" placeholder="Enter addressline1">
                            <span class="form_error d-none" id="add_addressline1_error">Address Line 1 Can't Be
                                Blank</span>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Suite number (optional)</label>
                            <input id="add_addressline2" type="text" class="form-control form-control-sm"
                                name="addressline2" placeholder="Enter Suite number (optional)">
                        </div>
                        <div class="form-group col-md-6">
                            <label>City</label>
                            <input id="add_city" type="text" class="form-control form-control-sm" name="city"
                                placeholder="Enter city">
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
                                    <option value="<?php echo trim($row["zone_code"]) ?>">

                                        <?php echo $row["zone_name"] ?>

                                        (<?php echo $row["zone_code"] ?>)

                                    </option>

                                <?php
                                }
                                db();
                                ?>
                            </select>
                            <span class="form_error d-none" id="add_state_error">State/Province Can't Be Blank</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Zip</label>
                            <input id="add_zip" type="text" class="form-control form-control-sm" name="zip"
                                placeholder="Enter Zip">
                            <span class="form_error d-none" id="add_zip_error">Zip Can't Be Blank</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Mobile No</label>
                            <input id="add_mobile_no" type="text" class="form-control form-control-sm" name="mobile_no"
                                placeholder="Enter Mobile No" value="<?php echo $user_phone; ?>">
                            <span class="form_error d-none" id="add_mobile_no_error">Mobile No Can't Be Blank</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Email </label>
                            <input id="add_email" type="text" class="form-control form-control-sm" name="email"
                                placeholder="Enter Email" value="<?php echo $user_email; ?>">
                            <span class="form_error d-none" id="add_email_error">Email Can't Be Blank</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Your Dock Hours</label>
                            <input id="dock_hours" type="text" class="form-control form-control-sm" name="dock_hours"
                                placeholder="Your Dock Hours (days open, open time - close time)">
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
                    <input type="hidden" id="address_form_action" name="form_action" value="add_address">
                    <button type="submit" id="add_address_btn" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    function set_user_profile_info() {
        console.log("Yesssss!!!");
        $('#add_first_name').val('<?php echo $user_name; ?>');
        $('#add_last_name').val('<?php echo $user_last_name; ?>');
        $('#add_company').val('<?php echo $user_company; ?>');
        $('#add_country').val('');
        $('#add_addressline1').val('');
        $('#add_addressline2').val('');
        $('#add_city').val('');
        $('#add_state').val('');
        $('#add_zip').val('');
        $('#dock_hours').val('');
        $('#add_set_as_def').prop('checked', false);
        $('#add_mobile_no').val('<?php echo $user_phone; ?>');
        $('#add_email').val('<?php echo $user_email; ?>');
        $('#addAddressModal').modal('show');
    }
    $(document).ready(function() {

        // Check if there's a saved tab
        /*if (localStorage.getItem('activeTab')) {
             var activeTab = localStorage.getItem('activeTab');
             $('#' + activeTab).tab('show');
         }

         // Save the clicked tab to localStorage
         $('.nav-link').on('click', function() {
             var tabId = $(this).attr('id');
             localStorage.setItem('activeTab', tabId);
         });
         */

        function getParameterByName(name) {
            var url = new URL(window.location.href);
            return url.searchParams.get(name);
        }

        function reloadWithParameter(paramName, paramValue) {
            var url = new URL(window.location.href);
            url.searchParams.set(paramName, paramValue);
            window.location.href = url.href;
        }

        // Check for the parameter value after the page reloads
        var paramValue = getParameterByName('parammark_default_add');
        //console.log("paramValue "+paramValue);
        var element = "";
        var active_tab_div = "";
        if (paramValue == "change-pass") {
            element = document.getElementById("change-password");
            active_tab_div = document.getElementById("change-password-tab");

        } else if (paramValue) {
            element = document.getElementById("manage-address");
            active_tab_div = document.getElementById("manage-address-tab");
        }

        //console.log("element "+element+" active_tab_div "+active_tab_div);
        if (element != "") {
            var element_def = document.getElementById("my-profile");
            var element_myprf_add = document.getElementById("my-profile-tab");

            element_def.classList.remove('active');
            element.classList.add('show');
            element.classList.add('active');

            element_myprf_add.classList.remove('active');
            active_tab_div.classList.add('active');

            var urlParams = new URLSearchParams(window.location.search);

            // Remove the parameter from the URL without reloading the page
            urlParams.delete('parammark_default_add');
            var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' +
                urlParams.toString();
            window.history.replaceState({}, document.title, newUrl);

            // Change the class of the div if the parameter exists
            if (paramValue === 'true') {
                // $('#myDiv').removeClass('original-class').addClass('new-class');
            }
        }

        /*window.onload = function() {

		//if (localStorage.getItem('AddchangeClass') === 'true') {
			// Add click event listener to the button
			//var element = document.getElementById("manage-address");
			//var element_def = document.getElementById("my-profile");
			//var element_mg_add = document.getElementById("manage-address-tab"); 
			//var element_myprf_add = document.getElementById("my-profile-tab"); 
			
			//element_def.classList.remove('active');
			//element.classList.add('show');
			//element.classList.add('active');
			
			//element_myprf_add.classList.remove('active');
			//element_mg_add.classList.add('active');

			// Remove the flag from local storage
			//localStorage.removeItem('AddchangeClass');
		//}
			
		// Get the URL parameters
		//var urlParams = new URLSearchParams(window.location.search);
		
		const paramValue = getParameterByName('selectedElement');
		alert("tt");
		//if (urlParams.has('selectedElement')) {
		if (paramValue) {	
			//var elementId = urlParams.get('selectedElement');
			
			// Find the element by its ID
			//if (elementId == "mark_default_add"){
				// Add click event listener to the button
				var element = document.getElementById("manage-address");
				var element_def = document.getElementById("my-profile");
				var element_mg_add = document.getElementById("manage-address-tab"); 
				var element_myprf_add = document.getElementById("my-profile-tab"); 
				
				element_def.classList.remove('active');
				element.classList.add('show');
				element.classList.add('active');
				
				element_myprf_add.classList.remove('active');
				element_mg_add.classList.add('active');
				
				// Remove the parameter from the URL without reloading the page
                urlParams.delete('selectedElement');
                var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + urlParams.toString();
                window.history.replaceState({}, document.title, newUrl);				
			//}
		}
    };*/



        $("#edit_profile_form").submit(function() {
            var user_name = $('#user_name').val();
            var user_email = $('#user_email').val();
            var flag = true;
            if (user_name == '') {
                $('#user_name_error').removeClass('d-none');
                flag = false;
            } else {
                $('#user_name_error').addClass('d-none');
            }
            if (user_email == '') {
                $('#user_email_error').removeClass('d-none');
                flag = false;
            } else {
                $('#user_email_error').addClass('d-none');
            }
            if (flag == true) {
                var all_data = new FormData(this);
                $.ajax({
                    url: 'user_profile_action.php',
                    data: all_data,
                    method: "post",
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        if (response == 1) {
                            alert('User updated successfully');
                            location.reload();
                        } else {
                            alert('Something went wrong, try again later');
                        }
                    }
                })
            }
            return false;
        });

        $("#change_password_form").submit(function() {
            var user_password = $("#user_password").val();
            var user_old_password = $("#user_old_password").val();
            var new_password = $("#new_password").val();
            var conf_password = $("#conf_password").val();
            var flag = true;
            if (user_password == '') {
                $("#user_password_error").removeClass('d-none');
                $("#user_password_error").text("Old Password Can't Be Blank");
                flag = false;
            } else {
                $("#user_password_error").addClass('d-none');
                $("#user_password_error").text("");
                if (user_password != user_old_password) {
                    $("#user_password_error").removeClass('d-none');
                    $("#user_password_error").text("Old Password Doesn't Match");
                    flag = false;
                } else {
                    $("#user_password_error").addClass('d-none');
                    $('#user_password_error').text("");
                }

            }
            if (new_password == '') {
                $("#new_password_error").removeClass('d-none');
                $("#new_password_error").text("New Password Can't Be Blank");
                flag = false;
            } else {
                $("#new_password_error").addClass('d-none');
                $("#new_password_error").text("");
            }
            if (conf_password == '') {
                $("#conf_password_error").removeClass('d-none');
                $("#conf_password_error").text("Confirm Password Can't Be Blank");
                flag = false;
            } else {
                $("#conf_password_error").addClass('d-none');
                $("#conf_password_error").text("");

                if (new_password != conf_password) {
                    $("#conf_password_error").removeClass('d-none');
                    $("#conf_password_error").text("Password Doesn't Match");
                    flag = false;
                } else {
                    $("#conf_password_error").addClass('d-none');
                    $('#conf_password_error').text("");
                }
            }

            if (flag == true) {

                $.ajax({
                    url: 'user_profile_action.php',
                    data: {
                        user_id: $("#user_id").val(),
                        new_password: new_password,
                        form_action: 'change_password'
                    },
                    method: "post",
                    async: false,
                    success: function(response) {
                        console.log(response);
                        if (response == 1) {
                            alert('Password updated successfully');
                            reloadWithParameter('parammark_default_add', 'change-pass');
                            //location.reload();
                        } else {
                            alert('Something went wrong, try again later');
                        }
                    }
                })
            }
            return false;
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
            // if (flag == true) {
            //     var all_data = new FormData(this);
            //     $.ajax({
            //         url: 'user_profile_action.php',
            //         data: all_data,
            //         method: "post",
            //         processData: false,
            //         contentType: false,
            //         success: function(response) {
            //             console.log(response);
            //             if (response == 1) {
            //                 alert('Address Added successfully');
            //                 reloadWithParameter('parammark_default_add', 'true');
            //                 //location.reload();
            //             } else {
            //                 alert('Something went wrong, try again later');
            //             }
            //         }
            //     })

            // }
            // if (flag == true) {
            //     var all_data = new FormData(this);

            //     // Extract address details
            //     var addressData = {
            //         "streetLines": [$('#add_addressline1').val()], // Remove empty lines
            //         "city": $('#add_city').val(),
            //         "stateOrProvinceCode": $('#add_state').val(),
            //         "postalCode": $('#add_zip').val(),
            //         "countryCode": $('#add_country').val()
            //     };

            //     // Call the new FedEx Address Validation API method
            //     $.ajax({
            //         url: 'fedex.php', // Update to your new PHP file
            //         method: "post",
            //         data: JSON.stringify(addressData),
            //         contentType: 'application/json',
            //         success: function(response) {
            //             console.log(response); // Log the entire response
            //             console.log('Is Valid:', response.isValid); // Log the isValid status

            //             if (response.isValid) {
            //                 // Proceed to save the address
            //                 $.ajax({
            //                     url: 'user_profile_action.php',
            //                     data: all_data,
            //                     method: "post",
            //                     processData: false,
            //                     contentType: false,
            //                     success: function(response) {
            //                         if (response == 1) {
            //                             alert('Address Added successfully');
            //                             reloadWithParameter('parammark_default_add', 'true');
            //                         } else {
            //                             alert('Something went wrong, try again later');
            //                         }
            //                     }
            //                 });
            //             } else {
            //                 console.log("Validation failed with message: ", response.message); // Log the message
            //                 alert('Address validation failed: ' + response.message);
            //             }
            //         },


            //         error: function() {
            //             alert('Error contacting FedEx for address validation.');
            //         }
            //     });
            // }
            // return false;
            if (flag == true) {
                var all_data = new FormData(this);

                // Extract address details
                var addressData = {
                    "streetLines": [$('#add_addressline1').val().trim()], // Ensure leading/trailing spaces are removed
                    "city": $('#add_city').val().trim(),
                    "stateOrProvinceCode": $('#add_state').val().trim(),
                    "postalCode": $('#add_zip').val().trim(),
                    "countryCode": $('#add_country').val().trim() // Ensure it is trimmed too
                };

                // Call the new FedEx Address Validation API method
                $.ajax({
                    url: 'fedex.php', // Update to your new PHP file
                    method: "post",
                    data: JSON.stringify(addressData),
                    contentType: 'application/json',
                    success: function(response) {
                        console.log(response); // Log the entire response
                        console.log('Is Valid:', response.isValid); // Log the isValid status

                        if (response.isValid) {
                            // Proceed to save the address
                            $.ajax({
                                url: 'user_profile_action.php',
                                data: all_data,
                                method: "post",
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    if (response == 1) {
                                        alert('Address added successfully');
                                        reloadWithParameter('parammark_default_add', 'true');
                                    } else {
                                        alert('Something went wrong, try again later');
                                    }
                                }
                            });
                        } else {
                            console.log("Validation failed with message: ", response.message); // Log the message
                            alert('Address validation failed: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Error contacting FedEx for address validation.');
                    }
                });
            }
            return false;

        });

        $("body").on('click', '.edit_address_btn', function() {
            var address_id = $(this).attr('address_id');
            $.ajax({
                url: 'user_profile_action.php',
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
                    $('#add_dock_hours').val(response.dock_hours);
                    $('#add_mobile_no').val(response.mobile_no);
                    $("#add_company").val(response.company);
                    $("#add_country").val(response.country);
                    $("#add_addressline1").val(response.addressline1);
                    $("#add_addressline2").val(response.addressline2);
                    $("#dock_hours").val(response.dock_hours);
                    // if (response.mark_default == 1) {
                    //     $("#div_def_add").html(
                    //         "<span class='text-success'><span class='fa fa-check'></span>Default Address</span>"
                    //         );
                    // } else {
                    //     $("#div_def_add").html("");
                    // }
                    if (response.mark_default == 1) {
                        $("#div_def_add").html(
                            /*"<label>Set as default</label>" +
                            "<input style='margin-left: 5px;' id='add_set_as_def' type='checkbox' name='add_set_as_def' value='1' checked>"
                            */
                            
                           "<span class='text-success'>This is the default address</span>"
                        );
                    } else {
                        $("#div_def_add").html(
                            "<label>Set as default</label>" +
                            "<input  style='margin-left: 5px;' id='add_set_as_def' type='checkbox' name='add_set_as_def' value='1'>"
                        );
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
            $.ajax({
                url: 'user_profile_action.php',
                data: {
                    address_id: address_id,
                    form_action: 'mark_default'
                },
                method: "post",
                async: false,
                success: function(res) {
                    if (res == 1) {
                        //localStorage.setItem('AddchangeClass', 'true');

                        alert('Address marked as default');
                        reloadWithParameter('parammark_default_add', 'true');
                        //location.reload();
                    } else {
                        alert('Something went wrong, try again later');
                    }
                }
            })
        });

        $(".delete_address_btn").click(function() {
            var address_id = $(this).attr('address_id');
            $.ajax({
                url: 'user_profile_action.php',
                data: {
                    address_id: address_id,
                    form_action: 'delete_address'
                },
                method: "post",
                async: false,
                success: function(res) {
                    if (res == 1) {
                        alert('Address deleted successfully');
                        reloadWithParameter('parammark_default_add', 'true');
                        //location.reload();
                    } else {
                        alert('Something went wrong, try again later');
                    }
                }
            })
        })

    });
</script>
<?php require_once("boomerange_common_footer.php"); ?>