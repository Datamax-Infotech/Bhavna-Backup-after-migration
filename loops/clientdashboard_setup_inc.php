<div class="main_data_css">
    <div style="height: 13px;">&nbsp;</div>
    <div style="border-bottom: 1px solid #C8C8C8; padding-bottom: 10px;">
        <img src="images/boomerang-logo.jpg" alt="moving boxes"> &nbsp;&nbsp; &nbsp;&nbsp;

        <a href="viewCompany.php?ID=<?php echo $ID ?>">View B2B page</a> &nbsp;&nbsp;
        <a target="_blank"
            href="https://clientold.usedcardboardboxes.com/client_dashboard.php?compnewid=<?php echo $ID ?>&repchk=yes">Old
            Client dash</a> &nbsp;&nbsp;
        <a target="_blank" href="https://boomerang.usedcardboardboxes.com/client_dashboard.php?compnewid=<?php echo urlencode(encrypt_password($ID)); ?>&repchk=yes&repchk_from_setup=yes&userid=<?php echo $_COOKIE["employeeid"] ?>">
            View Boomerang Portal</a>
        <font color=red size=1>*Do NOT give this link out to customers! It is a "back door" to the portal ONLY FOR
            YOU!</font>
    </div>

    <div class="dashboard_heading" style="float: left;">
        <div style="float: left;">
            <?php $comp_name = get_nickname_val('', $ID);
            echo $comp_name;
            ?>
        </div>

        &nbsp;
        <!--<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
			<span class="tooltiptext">&nbsp;</span></div>-->
        <div style="height: 13px;">&nbsp;</div>
    </div>


    <div id="light" class="white_content"> </div>




    <table border="0" bgcolor="#F6F8E5" align="center" width="85%"
        style="font-family:Arial, Helvetica, sans-serif; font-size:12;">
        <tr align="center">
            <td colspan="6" width="320px" bgcolor="#E8EEA8"><strong>Boomerang Portal Setup</strong></font>
            </td>
        </tr>
        <?php
        $boomrang = "Boom_beta";
        if (isset($_GET['displaydata']) && $_GET['displaydata'] == "Boom_beta") {


        ?>


            <tr align="center">
                <td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">Invite a New User</td>
            </tr>


            <tr align="center" style="margin-bottom:10px">

                <td colspan="5" height="30px" width="320px" align="left">
                    <a target="_blank" href="boomerang_user_invite.php?ID=<?php echo $ID ?>">click here to Sent a Invite Link</a>
                </td>
            </tr>




            <!-- <tr align="center">
                <td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">Sent a Invite Link</td>
            </tr>
            <form id="inviteForm" onsubmit="return sendInvite();" method="post">
                <input type="hidden" id="companyid" name="companyid" value="<?php echo $ID; ?>" />

                <tr align="center">
                    <td align="left" width="180px">Email Address: </td>
                    <td colspan="5" width="320px" align="left">
                        <input type="email" name="email" id="email" required />
                    </td>
                </tr>

                <tr align="center">
                    <td width="80px">&nbsp;</td>
                    <td colspan="5" width="320px" align="left">
                        <input type="submit" value="Send Invite" />
                    </td>
                </tr>
            </form> -->

            <form method="post" name="clientdash_adduser" action="clientdashboard_adduser.php">
                <input type="hidden" name="hidden_companyid" value="<?php echo $ID; ?>" />
                <input type="hidden" name="boomrang" value="<?php echo $boomrang; ?>" />
                <?php if (isset($_REQUEST["duprec"])) {
                    if ($_REQUEST["duprec"] == "yes") {

                        db();
                        // Fetch the username from the usermaster table using the user ID selected in the dropdown
                        $user_id = $_REQUEST["clientdash_userid"];  // Use user_id from the dropdown
                        $res = db_query("SELECT user_name, companyid FROM clientdashboard_usermaster WHERE user_id = '" . $user_id . "'");

                        $fetch_data = array_shift($res);
                        $user_name = $fetch_data["user_name"];
                        $cid = $fetch_data["companyid"];

                        if ($user_name && $cid) {
                            db_b2b();
                            $ures = db_query("SELECT company, ID FROM companyInfo WHERE ID = '" . $cid . "'");
                            $ufetch_data = array_shift($ures);
                            // Get the company name
                            $usr_company_name = get_nickname_val($ufetch_data["company"], $ufetch_data["ID"]);

                ?>
                            <tr align="center">
                                <td colspan="6" width="960px" align="left" bgcolor="red" style="padding-left: 10px; color:#FFFFFF;">
                                    This username "<?php echo $user_name; ?>" already exists for <strong style="font-size: 13px;">
                                        <a href='viewCompany.php?ID=<?php echo $ufetch_data["ID"]; ?>' target="_blank">
                                            <?php echo $usr_company_name; ?>
                                        </a></strong>, add this user to this location as well?<br>
                                    <a href="clientdashboard_adduser.php?hidden_companyid=<?php echo $ID; ?>&clientdash_userid=<?php echo $user_id; ?>&existing=new" style="color:#FFFFFF;">Yes</a> &nbsp;&nbsp;&nbsp;
                                    <a href="clientdashboard_setup.php?ID=<?php echo $ID; ?>&dupl_recheck=yes" style="color:#FFFFFF;">No</a>

                                    <!-- Username already exists, record not added. -->
                                </td>
                            </tr>
                <?php
                        }
                    }
                } ?>

                <?php if (isset($_REQUEST["dupl_recheck"])) {
                    if ($_REQUEST["dupl_recheck"] == "yes") {
                ?>
                        <tr align="center">
                            <td colspan="6" width="960px" align="left" bgcolor="red" style="padding-left: 10px; color:#FFFFFF;">
                                Username already exists, record not added.
                            </td>
                        </tr>
                <?php
                    }
                } ?>


                <tr align="center">
                    <td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">Add new user for Boomerang</td>
                </tr>
                <tr align="center">
                    <td align="left" width="180px">Username: </td>
                    <td colspan="5" width="320px" align="left">
                        <select name="clientdash_userid" id="clientdash_userid">
                            <option value="">Select User</option> <!-- Default option -->
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

                <tr align="center">
                    <td width="80px">&nbsp;</td>
                    <td colspan="5" width="320px" align="left"><input type="button" name="clientdash_adduser" value="Apply"
                            onclick="clientdash_chkfrm_new()" /></td>
                </tr>
            </form>



            <form name="frmClientDashboard" method="post" action="frmClientDashboardSave.php" encType="multipart/form-data">
                <input type="hidden" name="user_edit" id="user_edit" value="yes" />

                <tr align="center">
                    <td colspan="6" width="320px" align="left">Customer user list</td>
                </tr>
                <tr align="center">
                    <td width="80px">User name (Email address)</td>
                    <td width="80px" align="left">Password</td>
                    <td width="100px" align="left">Activate/Deactivate<br>
                        <font size=1><i>Click on checkbox to update Activate/Deactivate user</i></font>
                    </td>
                    <td width="100px" align="left">Delete</td>
                    <td width="100px" align="left">&nbsp;</td>
                </tr>

                <?php
                $qry = "SELECT * FROM clientdashboard_usermaster WHERE companyid = $ID";
                db();
                $res = db_query($qry);
                if (!$res) {
                    die("Database query failed: "); // Add your database connection variable here
                }
                while ($fetch_data = array_shift($res)) {
                    $userid = $fetch_data["user_id"];

                    // Prepare variables for user details
                    $username = '';
                    $email = '';
                    $password = '';

                    // Check if user_id is 0, fetch from clientdashboard_usermaster
                    if ($userid == 0) {
                        $username = $fetch_data['user_name']; // Assuming user_name holds the username
                        $email = $fetch_data['client_email']; // Assuming client_email holds the email
                        $password = $fetch_data['password']; // Password directly from clientdashboard_usermaster
                    } else {
                        // Fetch data from employees table for non-zero user_id
                        db_b2b();
                        $employeeQry = "SELECT username, email, password FROM employees WHERE employeeID = $userid";
                        $employeeRes = db_query($employeeQry);
                        $employeeData = array_shift($employeeRes);

                        // Check if employee data was found
                        if ($employeeData) {
                            $username = $employeeData['username'];
                            $email = $employeeData['email'];
                            $password = $employeeData['password'];
                        }
                    }
                ?>

                    <input type="hidden" name="loginid" id="loginid" value="<?php echo $fetch_data["loginid"]; ?>" />
                    <input type="hidden" name="ID" id="ID" value="<?php echo $ID; ?>" />

                    <tr align="center">

                        <td width="80px">
                            <a href="boomerang_users_profile.php?user_id=<?php echo $fetch_data['user_id']; ?>"
                                name="clientdash_username_edit"
                                id="clientdash_username_edit<?php echo $fetch_data['loginid']; ?>"
                                class="textbox-label">
                                <?php echo htmlspecialchars($email); ?>
                            </a>
                            <input type="hidden" name="clientdash_username_edit_value<?php echo $fetch_data["loginid"]; ?>"
                                id="clientdash_username_edit_value<?php echo $fetch_data["loginid"]; ?>"
                                value="<?php echo htmlspecialchars($email); ?>" />
                        </td>




                        <td width="80px" align="left"><input type="password" name="clientdash_pwd_edit"
                                id="clientdash_pwd_edit<?php echo $fetch_data["loginid"]; ?>" value="<?php echo htmlspecialchars($password); ?>" />
                        </td>

                        <td width="100px" align="left"><input type="checkbox" name="clientdash_flg"
                                id="clientdash_flg<?php echo $fetch_data["loginid"]; ?>"
                                <?php if ($fetch_data["activate_deactivate"] == 1) {
                                    echo "checked";
                                } ?> />
                        </td>
                        <td width="100px" align="left">
                            <input type="button" value="Delete"
                                onclick="clientdash_dele(<?php echo $fetch_data['loginid']; ?>, <?php echo $ID; ?>, 'Boom_beta')" />
                        </td>


                        <td width="80px" align="left">
                            <input type="button" name="clientdash_eml_edit" id="clientdash_eml_edit"
                                value="Submit" onclick='handleActive(<?php echo $fetch_data["loginid"]; ?>, <?php echo $ID; ?>, "Boom_beta");' />
                        </td>


                        <td width="80px" align="left"><input type="hidden" name="clientdash_eml_edit"
                                id="clientdash_eml_edit<?php echo $fetch_data["loginid"]; ?>" value="" /></td>
                    </tr>
                <?php
                }
                ?>
            </form>



        <?php
        } else {
        ?>

            <tr align="center">
                <td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">Invite a New User</td>
            </tr>


            <tr align="center" style="margin-bottom:10px">

                <td colspan="5" height="30px" width="320px" align="left">
                    <a target="_blank" href="boomerang_user_invite.php?ID=<?php echo $ID ?>">click here to Sent a Invite Link</a>
                </td>
            </tr>

            <form method="post" name="clientdash_adduser" action="clientdashboard_adduser.php">
                <input type="hidden" name="hidden_companyid" value="<?php echo $ID; ?>" />

                <?php if (isset($_REQUEST["duprec"])) {
                    if ($_REQUEST["duprec"] == "yes") {

                        db();
                        $res = db_query("SELECT companyid FROM clientdashboard_usermaster WHERE user_name = '" . $_REQUEST["usrnm"] . "'");

                        $fetch_data = array_shift($res);
                        $cid = $fetch_data["companyid"];

                        db_b2b();
                        $ures = db_query("SELECT company, ID FROM companyInfo WHERE ID = '" . $cid . "'");
                        $ufetch_data = array_shift($ures);
                        //echo "old  name--".$cid."<br>";
                        $usr_company_name = get_nickname_val($ufetch_data["company"], $ufetch_data["ID"]);

                ?>
                        <tr align="center">
                            <td colspan="6" width="960px" align="left" bgcolor="red" style="padding-left: 10px; color:#FFFFFF;">
                                This username already exists for <strong style="font-size: 13px;"><a
                                        href='viewCompany.php?ID=<?php echo $ufetch_data["ID"]; ?>'
                                        target="_blank">
                                        <?php echo $usr_company_name; ?>
                                    </a></strong>, add their username to this location as well?<br> <a href="clientdashboard_adduser.php?hidden_companyid=<?php echo $ID; ?>&clientdash_username=<?php echo $_REQUEST["usrnm"] ?>&existing=new" style="color:#FFFFFF;">Yes</a> &nbsp;&nbsp;&nbsp; <a
                                    href="clientdashboard_setup.php?ID=<?php echo $ID; ?>&dupl_recheck=yes"
                                    style="color:#FFFFFF;">No</a>

                                <!--User name already exists, record not added.-->
                            </td>
                        </tr>
                <?php }
                } ?>
                <?php if (isset($_REQUEST["dupl_recheck"])) {
                    if ($_REQUEST["dupl_recheck"] == "yes") {
                ?>
                        <tr align="center">
                            <td colspan="6" width="960px" align="left" bgcolor="red" style="padding-left: 10px; color:#FFFFFF;">
                                User name already exists, record not added.
                            </td>
                        </tr>
                <?php
                    }
                }
                ?>

                <tr align="center">
                    <td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">Add new user for Customer</td>
                </tr>
                <tr align="center">
                    <td align="left" width="180px">Email for login: </td>
                    <td colspan="5" width="320px" align="left"><input type="text" name="clientdash_username"
                            id="clientdash_username" value="" /></td>
                </tr>
                <tr align="center">
                    <td align="left" width="180px">Password: </td>
                    <td colspan="5" width="320px" align="left"><input type="password" name="clientdash_pwd"
                            id="clientdash_pwd" value="" /></td>
                </tr>

                <tr align="center">
                    <td width="80px">&nbsp;</td>
                    <td colspan="5" width="320px" align="left"><input type="button" name="clientdash_adduser" value="Add"
                            onclick="clientdash_chkfrm()" /></td>
                </tr>
            </form>
            <form name="frmClientDashboard" method="post" action="frmClientDashboardSave.php" encType="multipart/form-data">
                <input type="hidden" name="user_edit" id="user_edit" value="yes" />

                <tr align="center">
                    <td colspan="6" width="320px" align="left" bgcolor="">Customer user list</td>
                </tr>
                <tr align="center">
                    <td width="80px">User name (Email address)</td>
                    <td width="80px" align="left">Password</td>
                    <td width="100px" align="left">Activate/Deactivate<br>
                        <font size=1><i>Click on checkbox to update Activate/Deactivate user</i></font>
                    </td>
                    <td width="100px" align="left">Delete</td>
                    <td width="100px" align="left">&nbsp;</td>
                </tr>
                <?php

                $qry = "Select * From clientdashboard_usermaster Where companyid = $ID";

                db();
                $res = db_query($qry);
                while ($fetch_data = array_shift($res)) {
                    $userid = $fetch_data["user_id"];

                    // Prepare variables for user details
                    $username = '';
                    $email = '';
                    $password = '';

                    // Check if user_id is 0, fetch from clientdashboard_usermaster
                    if ($userid == 0) {
                        $username = $fetch_data['user_name']; // Assuming user_name holds the username
                        $email = $fetch_data['client_email']; // Assuming client_email holds the email
                        $password = $fetch_data['password']; // Password directly from clientdashboard_usermaster
                    } else {
                        // Fetch data from employees table for non-zero user_id
                        db_b2b();
                        $employeeQry = "SELECT username, email, password FROM employees WHERE employeeID = $userid";
                        $employeeRes = db_query($employeeQry);
                        $employeeData = array_shift($employeeRes);

                        // Check if employee data was found
                        if ($employeeData) {
                            $username = $employeeData['username'];
                            $email = $employeeData['email'];
                            $password = $employeeData['password'];
                        }
                    }
                ?>
                    <input type="hidden" name="loginid" id="loginid" value="<?php echo $fetch_data["loginid"]; ?>" />
                    <input type="hidden" name="ID" id="ID" value="<?php echo $ID; ?>" />

                    <tr align="center">

                        <td width="80px">
                            <a href="boomerang_users_profile.php?user_id=<?php echo $fetch_data['user_id']; ?>"
                                name="clientdash_username_edit"
                                id="clientdash_username_edit<?php echo $fetch_data['loginid']; ?>"
                                class="textbox-label">
                                <?php echo htmlspecialchars($email); ?>
                            </a>
                            <input type="hidden" name="clientdash_username_edit_value<?php echo $fetch_data["loginid"]; ?>"
                                id="clientdash_username_edit_value<?php echo $fetch_data["loginid"]; ?>"
                                value="<?php echo htmlspecialchars($email); ?>" />
                        </td>



                        <td width="80px" align="left"><input type="password" name="clientdash_pwd_edit"
                                id="clientdash_pwd_edit<?php echo $fetch_data["loginid"]; ?>" value="
                        <?php echo $password; ?>" />
                        </td>

                        <td width="100px" align="left"><input type="checkbox" name="clientdash_flg"
                                id="clientdash_flg<?php echo $fetch_data["loginid"]; ?>"
                                <?php if ($fetch_data["activate_deactivate"] == 1) {
                                    echo " checked ";
                                } ?> />
                        </td>
                        <td width="100px" align="left"><input type="button" value="Delete" onclick="clientdash_dele(<?php echo $fetch_data["loginid"]; ?>,
                        <?php echo $ID; ?>)" />
                        </td>
                        <td width="80px" align="left"><input type="button" name="clientdash_eml_edit" id="clientdash_eml_edit"
                                value="Submit"
                                onclick='handleActive(<?php echo $fetch_data["loginid"]; ?>, <?php echo $ID; ?>);' /></td>

                        <td width="80px" align="left"><input type="hidden" name="clientdash_eml_edit"
                                id="clientdash_eml_edit<?php echo $fetch_data["loginid"]; ?>" value="" /></td>
                    </tr>
                <?php

                }

                ?>


            </form>
        <?php
        }
        ?>


        <form name="frmClientDashboard_logo" method="post" action="frmClientDashboardSave.php"
            encType="multipart/form-data">
            <tr align="center">
                <td colspan="6" width="320px" align="left" style="background-color:white;">&nbsp;
                    <input type="hidden" name="hidden_companyid" value="<?php echo $ID; ?>" />
                    <input type="hidden" name="ID" id="ID" value="<?php echo $ID; ?>" />
                </td>
            </tr>
            <input type="hidden" name="clientdash_edituser_details_id" id="clientdash_edituser_details_id"
                value="<?php echo $ID; ?>" />
            <tr align="center">
                <td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">Update Details</td>
            </tr>


            <tr align="center">
                <td width="140px" align="left">Company Logo</td>
                <td colspan="3" width="180px" align="left"><input type="file" name="companylogo" /> <br />
                    <?php if ($company_log != "") {
                        echo "Uploaded file: " . $company_log; ?>
                        <image src="clientdashboard_images/<?php echo $company_log; ?>" width="100px" height="100px"
                            style="object-fit: cover;" />
                    <?php } ?>
                </td>
            </tr>





            <tr align="center">
                <td colspan="6" width="320px" align="left">&nbsp;</td>
            </tr>

            <!-- Section list -->

            <!-- Section list -->
            <tr align="center">
                <td colspan="6" width="320px"><input type="submit" name="btn_clientdash_upd_logo"
                        id="btn_clientdash_upd_logo" value="Submit" /></td>
            </tr>
        </form>
        <tr align="center">
            <td colspan="6" width="320px" align="left" style="background-color:white;">&nbsp;</td>
        </tr>

        <!--Favorite Inventory section start  -->
        <?php
        //if(isset($_REQUEST['hdnFavItemsAction']) && $_REQUEST['hdnFavItemsAction'] == 1 ){
        if (isset($_REQUEST['favremoveflg']) && $_REQUEST['favremoveflg'] == "yes") {

            db();
            db_query("Delete from clientdash_favorite_items WHERE id =" . $_REQUEST['favitemid']);
        }
        ?>
        <tr align="center">
            <td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">Favorite Inventory</td>
        </tr>
        <?php

        $x = "Select * from companyInfo Where ID = " . $_REQUEST["ID"];
        db_b2b();
        $dt_view_res = db_query($x);

        while ($row = array_shift($dt_view_res)) {
            //if((remove_non_numeric($row["shipZip"])) !="")
            if (($row["shipZip"]) != "") {
                //$zipShipStr= "Select * from ZipCodes WHERE zip = " . remove_non_numeric($row["shipZip"]);
                $tmp_zipval = "";
                $tmp_zipval = str_replace(" ", "", $row["shipZip"]);
                if ($row["shipcountry"] == "Canada") {
                    $zipShipStr = "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
                } elseif (($row["shipcountry"]) == "Mexico") {
                    $zipShipStr = "Select * from zipcodes_mexico limit 1";
                } else {
                    $zipShipStr = "Select * from ZipCodes WHERE zip = '" . intval($row["shipZip"]) . "'";
                }

                db_b2b();
                $dt_view_res = db_query($zipShipStr);
                while ($zip = array_shift($dt_view_res)) {
                    $shipLat = $zip["latitude"];
                    $shipLong = $zip["longitude"];
                }
            }
        }
        ?>



        <tr>
            <td colspan="6">
                <form name="frmFavItems" method="post"
                    action="clientdashboard_setup.php?ID=<?php echo $_REQUEST['ID']; ?>">
                    <table width="100%" cellspacing="1" cellpadding="1" border="0"
                        style="font-family:Arial, Helvetica, sans-serif; font-size:12; border: 1px solid #cccccc;">
                        <?php

                        db();
                        $selFavData = db_query("SELECT * FROM clientdash_favorite_items WHERE compid = " . $_REQUEST['ID'] . " and favItems = 1 ORDER BY id DESC");
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
                                <td class='display_title'>Miles Away
                                    <div class="tooltip">
                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                        <span class="tooltiptext">Green Color - miles away <= 250, <br>Orange Color -
                                                miles away <= 550 and> 250,
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
                            while ($rowsFavData = array_shift($selFavData)) {
                                //echo "<pre> rowsFavData ->";print_r($rowsFavData);echo "</pre>";
                                $after_po_val_tmp = 0;
                                $after_po_val = 0;
                                $pallet_val_afterpo = $preorder_txt2 = "";
                                $rec_found_box = "n";
                                $boxes_per_trailer = 0;
                                $next_load_available_date = "";
                                if ($rowsFavData['fav_b2bid'] > 0) {

                                    db();
                                    $selBoxDt = db_query("SELECT * FROM loop_boxes WHERE b2b_id = " . $rowsFavData['fav_b2bid']);
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

                                            db_b2b();
                                            $dt_view_res3 = db_query($zipStr);
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
                                                if (tep_db_num_rows($arr_length) > 0) {
                                                    $blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
                                                    $length = floatval($blength + $blength_frac);
                                                }
                                            }
                                            if ($rowInvDt["widthFraction"] != "") {
                                                $arr_width = explode("/", $rowInvDt["widthFraction"]);
                                                if (tep_db_num_rows($arr_width) > 0) {
                                                    $bwidth_frac = intval($arr_width[0]) / intval($arr_width[1]);
                                                    $width = floatval($bwidth + $bwidth_frac);
                                                }
                                            }
                                            if ($rowInvDt["depthFraction"] != "") {
                                                $arr_depth = explode("/", $rowInvDt["depthFraction"]);
                                                if (tep_db_num_rows($arr_depth) > 0) {
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

                            ?>
                                            <tr class="<?php echo $rowclr ?>">

                                                <td class=''>
                                                    <?php if ($rowsFavData['favItems'] == 1) { ?><input type="button" name="favItemIds"
                                                            id="favItemIds<?php echo $rowsFavData['id']; ?>" value="Remove" onclick="favitem_remove(<?php echo $rowsFavData['id']; ?>, 
                                        <?php echo $_REQUEST['ID']; ?>)">
                                                    <?php } ?>
                                                </td>
                                                <td class='' width="5%"><a href='https://b2b.usedcardboardboxes.com/?id=<?php echo urlencode(encrypt_password(get_loop_box_id($rowBoxDt['
                                        b2b_id']))); ?>&compnewid=
                                        <?php echo urlencode(encrypt_password($_REQUEST['ID'])); ?>'
                                                        target='_blank'>Buy
                                                        Now
                                                    </a></td>
                                                <td class='' width="5%"><?php echo $qty ?></td>
                                                <td class='' width="8%"><?php echo $estimated_next_load ?></td>
                                                <td class='' width="5%"><?php echo $expected_loads_per_mo ?></td>
                                                <td class='' width="5%"><?php echo $boxes_per_trailer ?></td>
                                                <td class='' width="3%"><?php echo $b2b_fob ?></td>
                                                <td class='' width="5%"><?php echo $rowInvDt['ID'] ?></td>
                                                <td class='' width="5%">
                                                    <font color='<?php echo $miles_away_color; ?>'><?php echo $miles_from ?></font>
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
                                                    <a href='manage_box_b2bloop.php?id=<?php echo $rowBoxDt["id"] ?>&proc=View'
                                                        target='_blank'>
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
                        <tr>
                            <td colspan="18">
                                <input type="hidden" name="hdnFavItemsAction" value="1">
                                <input type="hidden" name="hdnCompanyId" value="<?php echo $_REQUEST['ID']; ?>">
                                <input type="button" name="btnAddFavoriteInv" id="btnAddFavoriteInv"
                                    value="Add new favorite inventory" style="cursor:pointer;"
                                    onclick="add_inventory_to_favorite(<?php echo $ID; ?>)">
                            </td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>

        <!--Favorite Inventory section ends  -->
        <tr align="center">
            <td colspan="6" width="320px" align="left">&nbsp;</td>
        </tr>
        <tr align="center">
            <td colspan="6" width="320px" align="left" style="background-color:white;">&nbsp;</td>
        </tr>
        <!--Closed Loop Inventory section start  -->

        <?php
        //if(isset($_REQUEST['hdnClosedLoopInvAction']) && $_REQUEST['hdnClosedLoopInvAction'] == 1 ){
        if (isset($_REQUEST['closed_loop_item_removeflg']) && $_REQUEST['closed_loop_item_removeflg'] == "yes") {
            //db_query("UPDATE clientdash_closed_loop_items SET favItems = 0 WHERE id =".$_REQUEST['favitemid'], db());

            db();
            db_query("Delete from clientdash_closed_loop_items WHERE id =" . $_REQUEST['favitemid']);
        }
        ?>
        <tr align="center">
            <td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">
                Closed Loop Inventory&nbsp;<input type="checkbox" name="div_closed_inv" id="div_closed_inv" value="yes"
                    <?php if ($close_inv_flg == 1) {
                        echo " checked ";
                    } ?> onclick="show_closed_loop_inv(
                    <?php echo $_REQUEST["ID"]; ?>)">
                Show this section in Front End
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <form name="frmClosedLoopInv" method="post"
                    action="clientdashboard_setup.php?ID=<?php echo $_REQUEST['ID']; ?>">
                    <table width="100%" cellspacing="1" cellpadding="1" border="0"
                        style="font-family:Arial, Helvetica, sans-serif; font-size:12; border: 1px solid #cccccc;">
                        <?php

                        db();
                        $selFavDt1 = db_query("SELECT * FROM clientdash_closed_loop_items WHERE compid = " . $_REQUEST['ID'] . " and favItems = 1 ORDER BY id DESC");
                        //echo "SELECT * FROM clientdash_closed_loop_items WHERE compid = ".$_REQUEST['ID']." and favItems = 1 ORDER BY id DESC </br>";
                        $selFavDtCnt1 = tep_db_num_rows($selFavDt1);
                        if ($selFavDtCnt1 > 0) {
                        ?>
                            <tr bgcolor="#E4D5D5">
                                <td class='display_title'>Select</td>
                                <td class='display_title'>Buy Now</td>
                                <td class='display_title'>Qty Avail</td>
                                <td class='display_title'>Buy Now, Load Can Ship In</td>
                                <td class='display_title'>Expected # of Loads/Mo</td>
                                <td class='display_title'>Per Truckload</td>
                                <td class='display_title'>FOB Origin Price/Unit</td>
                                <td class='display_title'>B2B ID</td>
                                <td class='display_title'>Miles Away
                                    <div class="tooltip">
                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                        <span class="tooltiptext">Green Color - miles away <= 250, <br>Orange Color -
                                                miles away <= 550 and> 250,
                                                    <br>Red Color - miles away > 550</span>
                                    </div>
                                </td>
                                <td align="center" class='display_title'>L</td>
                                <td align="center" class='display_title'>x</td>
                                <td align="center" class='display_title'>W</td>
                                <td align="center" class='display_title'>x</td>
                                <td align="center" class='display_title'>H</td>
                                <td class='display_title'>Walls</td>
                                <td class='display_title'>Description</td>
                                <td class='display_title'>Ship From</td>
                                <td class=''>Box Type</td>
                            </tr>

                            <?php

                            $x = "SELECT * FROM companyInfo WHERE ID = " . $_REQUEST["ID"];
                            db_b2b();
                            $dt_view_res = db_query($x);
                            while ($row = array_shift($dt_view_res)) {
                                //if((remove_non_numeric($row["shipZip"])) !="")
                                if (($row["shipZip"]) != "") {
                                    $tmp_zipval = "";
                                    $tmp_zipval = str_replace(" ", "", $row["shipZip"]);
                                    if ($row["shipcountry"] == "Canada") {
                                        $zipShipStr = "SELECT * FROM zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
                                    } elseif (($row["shipcountry"]) == "Mexico") {
                                        $zipShipStr = "SELECT * FROM zipcodes_mexico LIMIT 1";
                                    } else {
                                        $zipShipStr = "SELECT * FROM ZipCodes WHERE zip = '" . intval($row["shipZip"]) . "'";
                                    }

                                    db_b2b();
                                    $dt_view_res = db_query($zipShipStr);
                                    while ($zip = array_shift($dt_view_res)) {
                                        $shipLat = $zip["latitude"];
                                        $shipLong = $zip["longitude"];
                                    }
                                }
                            }

                            $i = 0;
                            while ($rowsFavDt = array_shift($selFavDt1)) {

                                db();
                                $selBoxDt = db_query("SELECT * FROM loop_boxes WHERE b2b_id = " . $rowsFavDt['fav_b2bid']);
                                $rowBoxDt = array_shift($selBoxDt);
                                if ($rowBoxDt['b2b_id'] > 0) {

                                    db_b2b();
                                    $selInvDt = db_query("SELECT * FROM inventory WHERE ID = " . $rowBoxDt['b2b_id']);
                                    $rowInvDt = array_shift($selInvDt);
                                }

                                $loop_id = $rowBoxDt['id'];
                                $bpallet_qty = $rowBoxDt['bpallet_qty'];
                                $boxes_per_trailer = $rowBoxDt['boxes_per_trailer'];
                                $box_warehouse_id = $rowBoxDt["box_warehouse_id"];
                                $next_load_available_date = $rowBoxDt["next_load_available_date"];

                                //Get ship from
                                if ($loc_res["box_warehouse_id"] == "238") {

                                    $vendor_b2b_rescue_id = $rowBoxDt["vendor_b2b_rescue"];
                                    $get_loc_qry = "SELECT * FROM companyInfo WHERE loopid = '" . $vendor_b2b_rescue_id . "'";
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

                                $ship_from  = $shipfrom_city . ", " . $shipfrom_state;
                                $ship_from2 = $shipfrom_state;

                                $after_po_val_tmp = 0;
                                $actual_val = 0;
                                $dt_view_qry = "SELECT * from tmp_inventory_list_set2 where trans_id = " . $loop_id . " order by warehouse, type_ofbox, Description";

                                db_b2b();
                                $dt_view_res_box = db_query($dt_view_qry);
                                while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
                                    $rec_found_box = "y";
                                    $actual_val = $dt_view_res_box_data["actual"];
                                    $after_po_val_tmp = $dt_view_res_box_data["afterpo"];
                                    $last_month_qty = $dt_view_res_box_data["lastmonthqty"];
                                    $sales_order_qty = $dt_view_res_box_data["sales_order_qty"];
                                    //
                                }

                                if ($rec_found_box == "n") {
                                    $after_po_val = $rowInvDt["after_actual_inventory"];
                                    $last_month_qty = $rowInvDt["lastmonthqty"];

                                    //$actual_val = $rowInvDt["actual_inventory"];

                                    $dt_view_qry = "SELECT loop_boxes.bpallet_qty, loop_boxes.work_as_kit_box, loop_boxes.flyer, loop_boxes.boxes_per_trailer, loop_boxes.id AS I, loop_boxes.b2b_id AS B2BID, SUM(loop_inventory.boxgood) AS A, loop_warehouse.company_name AS B, loop_boxes.bdescription AS C, loop_boxes.blength AS L, loop_boxes.blength_frac AS LF, loop_boxes.bwidth AS W, loop_boxes.bwidth_frac AS WF, loop_boxes.bdepth AS D, loop_boxes.bdepth_frac as DF, loop_boxes.work_as_kit_box as kb, loop_boxes.bwall AS WALL, loop_boxes.bstrength AS ST, loop_boxes.isbox as ISBOX, loop_boxes.type as TYPE, loop_warehouse.id AS wid, loop_warehouse.pallet_space, loop_boxes.sku as SKU FROM loop_inventory INNER JOIN loop_warehouse 
									ON loop_inventory.warehouse_id = loop_warehouse.id INNER JOIN loop_boxes ON loop_inventory.box_id = loop_boxes.id where loop_boxes.id = '" . $loop_id . "'
									GROUP BY loop_warehouse.warehouse_name, loop_inventory.box_id ORDER BY loop_warehouse.warehouse_name, loop_boxes.type, loop_boxes.blength, loop_boxes.bwidth, loop_boxes.bdepth,loop_boxes.bdescription";
                                    db();
                                    $dt_view_res = db_query($dt_view_qry);
                                    while ($dt_view_row = array_shift($dt_view_res)) {
                                        $actual_val = $dt_view_row["A"];
                                    }
                                }

                                if ($box_warehouse_id == 238) {
                                    $after_po_val = $rowInvDt["after_actual_inventory"];
                                } else {
                                    $after_po_val = $after_po_val_tmp;
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
                                    if (tep_db_num_rows($arr_length) > 0) {
                                        $blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
                                        $length = floatval($blength + $blength_frac);
                                    }
                                }
                                if ($rowInvDt["widthFraction"] != "") {
                                    $arr_width = explode("/", $rowInvDt["widthFraction"]);
                                    if (tep_db_num_rows($arr_width) > 0) {
                                        $bwidth_frac = intval($arr_width[0]) / intval($arr_width[1]);
                                        $width = floatval($bwidth + $bwidth_frac);
                                    }
                                }

                                if ($rowInvDt["depthFraction"] != "") {
                                    $arr_depth = explode("/", $rowInvDt["depthFraction"]);
                                    if (tep_db_num_rows($arr_depth) > 0) {
                                        $bdepth_frac = intval($arr_depth[0]) / intval($arr_depth[1]);
                                        $depth = floatval($bdepth + $bdepth_frac);
                                    }
                                }

                                $fav_bl = $length;
                                $fav_bw = $width;
                                $fav_bh = $depth;

                                if ($box_warehouse_id == 238 && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00")) {
                                } else {
                                    if ($rowInvDt["expected_loads_per_mo"] == 0) {
                                        $expected_loads_per_mo = "<font color=red> 0 </font>";
                                    } else {
                                        $expected_loads_per_mo = $rowInvDt["expected_loads_per_mo"];
                                    }
                                }

                                $b2b_ulineDollar = isset($rowInvDt["ulineDollar"]) ? round($rowInvDt["ulineDollar"]) : 0;
                                $b2b_ulineCents = $rowInvDt["ulineCents"];
                                $b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
                                $b2b_fob = "$" . number_format($b2b_fob, 2);

                                $miles_from = "";
                                if (isset($rowInvDt["location_country"]) == "Canada") {
                                    $tmp_zipval = str_replace(" ", "", $rowInvDt["location_zip"]);
                                    $zipStr = "SELECT * FROM zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
                                } elseif (isset($rowInvDt["location_country"]) == "Mexico") {
                                    $zipStr = "SELECT * FROM zipcodes_mexico LIMIT 1";
                                } else {
                                    $zipStr = "SELECT * FROM ZipCodes WHERE zip = '" . intval($rowInvDt["location_zip"]) . "'";
                                }

                                if ($rowInvDt["location_zip"] != "") {
                                    if ($rowInvDt["availability"] != "-3.5") {
                                        $inv_id_list .= $rowInvDt["I"] . ",";
                                    }

                                    db_b2b();
                                    $dt_view_res3 = db_query($zipStr);
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

                                    if ($miles_from <= 250) {    //echo "chk gr <br/>";
                                        $miles_away_color = "green";
                                    }
                                    if (($miles_from <= 550) && ($miles_from > 250)) {
                                        $miles_away_color = "#FF9933";
                                    }
                                    if (($miles_from > 550)) {
                                        $miles_away_color = "red";
                                    }
                                }

                                $type = "";
                                if (in_array(strtolower(trim($rowBoxDt['type'])), array_map('strtolower', array("Gaylord", "GaylordUCB", "Loop", "PresoldGaylord")))) {
                                    $type = 'g';
                                } elseif (in_array(strtolower(trim($rowBoxDt['type'])), array_map('strtolower', array("Medium", "Large", "Xlarge", "LoopShipping", "Box", "Boxnonucb", "Presold")))) {
                                    $type = 'sb';
                                } elseif (in_array(strtolower(trim($rowBoxDt['type'])), array_map('strtolower', array("SupersackUCB", "SupersacknonUCB")))) {
                                    $type = 'sup';
                                } elseif (in_array(strtolower(trim($rowBoxDt['type'])), array_map('strtolower', array("PalletsUCB", "PalletsnonUCB")))) {
                                    $type = 'pal';
                                }

                                if ($i % 2 == 0) {
                                    $rowclr = '#dcdcdc';
                                } else {
                                    $rowclr = '#f7f7f7';
                                }
                            ?>
                                <tr bgcolor="<?php echo $rowclr ?>">
                                    <td class=''>
                                        <?php if ($rowsFavDt['favItems'] == 1) { ?><input type="button"
                                                name="favClosedLoopInvItemIds"
                                                id="favClosedLoopInvItemIds<?php echo $rowsFavDt['id']; ?>" value="Remove"
                                                onclick="closed_loop_item_remove(<?php echo $rowsFavDt['id']; ?>, <?php echo $_REQUEST['ID']; ?>)">
                                        <?php } ?>
                                    </td>
                                    <td class=''><a href='https://b2b.usedcardboardboxes.com/?id=<?php echo urlencode(encrypt_password(get_loop_box_id($rowsFavDt["fav_b2bid"]))); ?>&compnewid=<?php echo urlencode(encrypt_password($_REQUEST['
                                        ID'])); ?>' target='_blank'>Buy Now</a></td>
                                    <td class=''><?php echo $after_po_val ?></td>
                                    <td class=''><?php echo $rowBoxDt['buy_now_load_can_ship_in'] ?></td>
                                    <td class=''><?php echo $expected_loads_per_mo ?></td>
                                    <td class=''><?php echo $rowBoxDt['boxes_per_trailer'] ?></td>
                                    <td class=''><?php echo $b2b_fob ?></td>
                                    <td class=''><?php echo $rowBoxDt['b2b_id'] ?></td>
                                    <td class=''>
                                        <font color='<?php echo $miles_away_color; ?>'><?php echo $miles_from ?></font>
                                    </td>
                                    <td align="center" class=''><?php echo $fav_bl ?></td>
                                    <td align="center" class=''>x</td>
                                    <td align="center" class=''><?php echo $fav_bw ?></td>
                                    <td align="center" class=''>x</td>
                                    <td align="center" class=''><?php echo $fav_bh ?></td>
                                    <td class=''><?php echo $rowInvDt["bwall"] ?></td>
                                    <td class=''>
                                        <a href='manage_box_b2bloop.php?id=<?php echo $rowBoxDt["id"] ?>&proc=View'
                                            target='_blank'>
                                            <?php echo $rowInvDt["description"] ?></a>
                                    </td>
                                    <td class=''><?php echo $ship_from2 ?></td>
                                    <td class=''>
                                        <?php

                                        if ($type == 'g') {
                                            $boxtype = 'Gaylord';
                                        } elseif ($type == 'sb') {
                                            $boxtype = 'Shipping';
                                        } elseif ($type == 'sup') {
                                            $boxtype = 'Supersack';
                                        } elseif ($type == 'pal') {
                                            $boxtype = 'Pallet';
                                        } elseif ($type == 'other') {
                                            $boxtype = 'Other';
                                        }
                                        echo $boxtype;
                                        ?>
                                    </td>
                                </tr>
                            <?php
                                $i++;
                            }
                            ?>
                            <!-- <tr>
								<td colspan="18"><input type="submit" value="Update" style="cursor:pointer;"></td>
							</tr> -->
                        <?php

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
                                <input type="button" name="btnAddCloseloopInv" id="btnAddCloseloopInv"
                                    value="Add new close loop inventory" style="cursor:pointer;"
                                    onclick="add_inventory_to_closeloop(<?php echo $ID; ?>)">
                            </td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>

        <!--Closed Loop Inventory section ends  -->

        <!-- Hide Inventory Section start -->
        <?php

        if (isset($_REQUEST['hideremoveflg']) && $_REQUEST['hideremoveflg'] == "yes") {

            db();
            db_query("Delete from clientdash_hide_items WHERE id =" . $_REQUEST['hideitemid']);
        }
        ?>
        <tr align="center">
            <td colspan="6" width="320px" align="left" style="background-color:white;">&nbsp;</td>
        </tr>
        <tr align="center">
            <td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">Hide Inventory</td>
        </tr>

        <td colspan="6">
            <form name="frmhideItems" method="post"
                action="clientdashboard_setup.php?ID=<?php echo $_REQUEST['ID']; ?>">
                <table width="100%" cellspacing="1" cellpadding="1" border="0"
                    style="font-family:Arial, Helvetica, sans-serif; font-size:12; border: 1px solid #cccccc;">
                    <?php

                    db();
                    $selhideData = db_query("SELECT * FROM clientdash_hide_items WHERE compid = " . $_REQUEST['ID'] . " and hideItems = 1 ORDER BY id DESC");

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
                                    <span class="tooltiptext">Green Color - miles away <= 250, <br>Orange Color - miles
                                            away <= 550 and> 250,
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
                                        $distLat = ($shipLat - $locLat) * 3.141592653 / 180;
                                        $distLong = ($shipLong - $locLong) * 3.141592653 / 180;

                                        $distA = Sin($distLat / 2) * Sin($distLat / 2) + Cos($shipLat * 3.14159 / 180) * Cos($locLat * 3.14159 / 180) * Sin($distLong / 2) * Sin($distLong / 2);
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
                                            if (tep_db_num_rows($arr_length) > 0) {
                                                $blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
                                                $length = floatval($blength + $blength_frac);
                                            }
                                        }
                                        if ($rowInvDt["widthFraction"] != "") {
                                            $arr_width = explode("/", $rowInvDt["widthFraction"]);
                                            if (tep_db_num_rows($arr_width) > 0) {
                                                $bwidth_frac = intval($arr_width[0]) / intval($arr_width[1]);
                                                $width = floatval($bwidth + $bwidth_frac);
                                            }
                                        }
                                        if ($rowInvDt["depthFraction"] != "") {
                                            $arr_depth = explode("/", $rowInvDt["depthFraction"]);
                                            if (tep_db_num_rows($arr_depth) > 0) {
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
                                                <?php if ($rowsFavData['hideItems'] == 1) { ?><input type="button" name="hideItemIds"
                                                        id="hideItemIds<?php echo $rowsFavData['id']; ?>" value="Remove"
                                                        onclick="hideitem_remove(<?php echo $rowsFavData['id']; ?>, <?php echo $_REQUEST['ID']; ?>)">
                                                <?php } ?>
                                            </td>

                                            <td class='' width="5%"><?php echo $qty ?></td>
                                            <td class='' width="8%"><?php echo $estimated_next_load ?></td>
                                            <td class='' width="5%"><?php echo $expected_loads_per_mo ?></td>
                                            <td class='' width="5%"><?php echo $boxes_per_trailer ?></td>
                                            <td class='' width="3%"><?php echo $b2b_fob ?></td>
                                            <td class='' width="5%"><?php echo $rowInvDt['ID'] ?></td>
                                            <td class='' width="5%">
                                                <font color='<?php echo $miles_away_color; ?>'><?php echo $miles_from ?></font>
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
                                                <a href='manage_box_b2bloop.php?id=<?php echo $rowBoxDt["id"] ?>&proc=View'
                                                    target='_blank'>
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
                    <tr>
                        <td colspan="18">
                            <input type="hidden" name="hdnhideItemsAction" value="1">
                            <input type="hidden" name="hdnCompanyId" value="<?php echo $_REQUEST['ID']; ?>">
                            <input type="button" name="btnAddHideInv" id="btnAddHideInv"
                                value="Add New Inventory to Hide" style="cursor:pointer;"
                                onclick="add_inventory_to_hide(<?php echo $ID; ?>)">
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

        <tr>
            <td>
                &nbsp;Hide Box Profiles and Browse Inventory Pages?&emsp;
                <input type="checkbox" name="clientdash_boxprofile_inv_hide" id="clientdash_boxprofile_inv_hide"
                    value="yes"
                    <?php if ($setup_boxprofile_inv_flg == 1) {
                        echo " checked ";
                    } ?> />
            </td>
            <td colspan="5" align="left">&nbsp;</td>
        </tr>

        <?php if ($parent_flg == "Parent") { ?>
            <tr>
                <td>
                    &nbsp;Hide Corporate Views from Reports?&emsp;
                    <input type="checkbox" name="clientdash_family_tree" id="clientdash_family_tree" value="yes"
                        <?php if ($setup_family_tree == 1) {
                            echo " checked ";
                        } ?> />
                </td>
                <td colspan="5" align="left">&nbsp;</td>
            </tr>
        <?php } ?>

        <tr align="">
            <td colspan="6">
                <input type="button" name="clientdash_setup_submit" id="clientdash_setup_submit" value="Submit"
                    onclick='handleSetuphide(<?php echo $ID; ?>);' />
            </td>
        </tr>
        <!--Closed Setup for hiding coliumns from user in boomerang section 7 -->

        <!--Start Setup for hiding coliumns from user in boomerang links defined as eight 8  -->
        <!--Closed Setup for hiding coliumns from user in boomerang section 8 -->


    </table>


    <br />
</div>