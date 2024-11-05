
<title>Register User - Boomerange Portal</title>
<?php
// require("inc/header_session_client.php");
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
require_once('boomerange_common_header.php');
$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
db();
//echo "SELECT loginid  FROM boomerang_usermaster WHERE user_email = '" . $email . "'";
$check_user_query = db_query("SELECT loginid  FROM boomerang_usermaster WHERE user_email = '" . $email . "'");
    $rec_found = "no";
    while ($fetch_data = array_shift($check_user_query)) {
        $rec_found = "yes";
    }
?>
<?php if($rec_found == 'no'){ ?>
<main>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12" id="show_register">
                <div class="tab-content" id="my-account-tabContent">
                    <div class="tab-pane fade show active" id="my-profile" role="tabpanel" aria-labelledby="my-profile-tab">
                        <div class="container_fluid">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5 class="modal-title" id="addAddressModalLabel">Register User</h5>
                                    <br>
                                    <form id="add_profile_form">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input id="user_name" type="text"
                                                        class="form-control form-control-sm" name="user_name"
                                                        placeholder="Enter First Name">
                                                    <span class="form_error d-none" id="user_name_error">Username can't
                                                        be blank</span>
                                                </div>
                                                <div class="col-md-6">
                                                    <input id="user_last_name" type="text"
                                                        class="form-control form-control-sm" name="user_last_name"
                                                        placeholder="Enter Last Name">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label>Email</label>
                                            <input id="user_email" type="email" class="form-control form-control-sm"
                                                name="user_email" placeholder="Enter Email" value="<?php echo $email; ?>">
                                            <span class="form_error d-none" id="user_email_error">Email Can't Be
                                                Blank</span>
                                        </div>

                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input id="user_phone" type="text" class="form-control form-control-sm"
                                                name="user_phone" placeholder="Enter Phone">
                                            <span class="form_error d-none" id="user_email_error">Phone can't be
                                                blank</span>
                                        </div>
                                        <!-- <div class="form-group">
                                            <label>Password</label>
                                            <input id="user_password" type="password" class="form-control form-control-sm" name="user_password" placeholder="Enter Password">
                                            <span class="form_error d-none" id="user_password_error">Password can't be blank</span>
                                        </div> -->
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input id="user_password" type="password" class="form-control form-control-sm" name="user_password" placeholder="Enter Password">
                                            <span class="form_error d-none" id="user_password_error">Password can't be blank</span>
                                        </div>

                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <input id="user_confirm_password" type="password" class="form-control form-control-sm" name="user_confirm_password" placeholder="Confirm Password">
                                            <span class="form_error d-none" id="user_confirm_password_error">Passwords do not match</span>
                                        </div>

                                        <div class="mb-3">
                                            
                                            <input type="hidden" name="sent_email_address" value="<?php echo $email; ?>">
                                            <input type="hidden" name="form_action" value="add_profile">
                                            <input type="hidden" name="company_id" value="<?php echo base64_decode($_GET['company_id']); ?>">
                                            <button type="submit" class="btn btn-custom">Register</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-8" style="display: none;" id="show_address">
                <form id="add_address_form">
                    <h5 class="modal-title" id="addAddressModalLabel">Add Address</h5>


                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>First Name</label>
                            <input id="add_first_name" type="text" class="form-control form-control-sm"
                                name="first_name" placeholder="Enter First Name"
                                value="<?php echo $user_name; ?>">
                            <span class="form_error d-none" id="add_first_name_error">First Name
                                Can't Be
                                Blank</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Last Name</label>
                            <input id="add_last_name" type="text" class="form-control form-control-sm"
                                name="last_name" placeholder="Enter Last Name"
                                value="<?php echo $user_last_name; ?>">
                            <span class="form_error d-none" id="add_last_name_error">Last Name Can't
                                Be Blank</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Company</label>
                            <input id="add_company" type="text" class="form-control form-control-sm"
                                name="company" placeholder="Enter Company"
                                value="<?php echo $user_company; ?>">
                            <span class="form_error d-none" id="add_company_error">Company Can't Be
                                Blank</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Country</label>
                            <select id="add_country" class="form-control form-control-sm"
                                name="country">
                                <option value="USA">USA</option>
                                <option value="Canada">Canada</option>
                                <option value="Mexico">Mexico</option>
                            </select>
                            <span class="form_error d-none" id="add_country_error">Country Can't Be
                                Blank</span>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Adress Line 1</label>
                            <input id="add_addressline1" type="text"
                                class="form-control form-control-sm" name="addressline1"
                                placeholder="Enter addressline1">
                            <span class="form_error d-none" id="add_addressline1_error">Address Line
                                1 Can't Be
                                Blank</span>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Suite number (optional)</label>
                            <input id="add_addressline2" type="text"
                                class="form-control form-control-sm" name="addressline2"
                                placeholder="Enter Suite number (optional)">
                        </div>
                        <div class="form-group col-md-6">
                            <label>City</label>
                            <input id="add_city" type="text" class="form-control form-control-sm"
                                name="city" placeholder="Enter city">
                            <span class="form_error d-none" id="add_city_error">City
                                Can't Be Blank</span>
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
                            <span class="form_error d-none" id="add_state_error">State/Province
                                Can't Be
                                Blank</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Zip</label>
                            <input id="add_zip" type="text" class="form-control form-control-sm"
                                name="zip" placeholder="Enter Zip">
                            <span class="form_error d-none" id="add_zip_error">Zip Can't
                                Be Blank</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Mobile No</label>
                            <input id="add_mobile_no" type="text" class="form-control form-control-sm"
                                name="mobile_no" placeholder="Enter Mobile No"
                                value="<?php echo $user_phone; ?>">
                            <span class="form_error d-none" id="add_mobile_no_error">Mobile No Can't
                                Be Blank</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Email </label>
                            <input id="add_email" type="text" class="form-control form-control-sm"
                                name="email" placeholder="Enter Email"
                                value="<?php echo $user_email; ?>">
                            <span class="form_error d-none" id="add_email_error">Email
                                Can't Be Blank</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Your Dock Hours</label>
                            <input id="dock_hours" type="text" class="form-control form-control-sm"
                                name="dock_hours"
                                placeholder="Your Dock Hours (days open, open time - close time)">
                            <span class="form_error d-none" id="add_dock_hours_error">Dock Hours
                                Can't Be
                                Blank</span>
                        </div>

                        <div class="form-group col-md-12" id="div_def_add">
                            <label>Set as default</label>
                            <input id="add_set_as_def" type="checkbox" name="add_set_as_def" checked value="1">
                        </div>

                    </div>



                    <input type="hidden" id="address_form_action" name="form_action"
                        value="add_address">
                    <input type="hidden" name="user_id" id="user_id"?>
                                            
                    <button type="submit" id="add_address_btn" class="btn btn-primary">Save
                        Address</button>
                </form>
            </div>
        </div>
    </div>
</main>


<script>
    $(document).ready(function() {
        $("#add_profile_form").submit(function(e) {
            e.preventDefault(); // Prevent the default form submission

            var password = $("#user_password").val();
            var confirmPassword = $("#user_confirm_password").val();

            // Check if passwords match
            if (password !== confirmPassword) {
                $("#user_confirm_password_error").removeClass('d-none').show(); // Show error message
                return; // Stop form submission
            } else {
                $("#user_confirm_password_error").addClass('d-none').hide(); // Hide error message
            }

            var all_data = new FormData(this);
            $.ajax({
                url: 'register_user_profile.php',
                data: all_data,
                method: "post",
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response); // Log the response for debugging
                    if (response == 0) {
                        alert('Something went wrong, try again later');
                    } else {
                            alert('User added successfully');
                            $('#user_id').val(response);
                            $('#add_first_name').val($('#user_name').val());
                            $('#add_last_name').val($('#user_last_name').val());
                            $('#add_mobile_no').val($('#user_phone').val());
                            $('#add_email').val($('#user_email').val());
                            $("#show_register").hide();
                            // Show the address block
                            $("#show_address").show();
                            // window.location.href = "https://www.ucbloops.com/boomerang_portal/index.php";
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error: ' + textStatus + ' - ' + errorThrown); // Log any errors
                    alert('An error occurred: ' + textStatus);
                }
            });
        });


        $("#add_address_form").submit(function(e) {
            e.preventDefault();

            var all_data = new FormData(this);
            $.ajax({
                url: 'register_user_profile.php',
                data: all_data,
                method: "post",
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response == 1) {
                        alert('Address added successfully');

                        window.location.href = "https://www.ucbloops.com/boomerang_portal/index.php";
                    } else {
                        alert(
                            'Something went wrong, try again later'
                        );
                    }
                }
            });
        });
    });
</script>
<?php } else{?>
    <main style="min-height: 50vh">
    <h6 class="text-center"><?php echo $email?> already registered! <a href="index.php"> Click Here to Login Boomerang Portal</a></h6>
    </main>
<?php } ?>
<?php require_once("boomerange_common_footer.php"); ?>