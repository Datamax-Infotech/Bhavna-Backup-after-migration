<?php
require("inc/header_session.php");
require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";


$locLat = "";
$shipLat = "";
$locLong = "";
$shipLong = "";
$expected_loads_per_mo = "";
$miles_away_color = "";
$ownername = "";
$boxtype = "";

?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Boomerang Portal Setup</title>

    <script language="javascript">
        function clientdash_chkfrm() {
            var mailformat = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            var clientdash_username = document.getElementById('clientdash_username');
            var clientdash_pwd = document.getElementById('clientdash_pwd');
            if (clientdash_username.value == '') {
                alert("Please enter the Email as User name.");
                clientdash_username.focus();
                return false;
            } else if (mailformat.test(clientdash_username.value) == false) {
                alert("Enter valid email as User name!");
                clientdash_username.focus();
                return false;
            } else if (clientdash_pwd.value == '') {
                alert("Please enter the Password.");
                clientdash_pwd.focus();
                return false;
            }
            /*else if (document.getElementById('clientdash_eml').value == '') {
            			alert("Please enter the Client Email.");
            			document.getElementById('clientdash_eml').focus();
            			return false;
            		}*/
            else {
                document.clientdash_adduser.submit();
                return true;
            }

        }

        function clientdash_chkfrm_new() {
            var mailformat = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            var clientdash_id = document.getElementById('clientdash_userid');

            if (clientdash_id.value == '') {
                alert("Please Select the User name.");
                clientdash_id.focus();
                return false;
            }
            /*else if (document.getElementById('clientdash_eml').value == '') {
            			alert("Please enter the Client Email.");
            			document.getElementById('clientdash_eml').focus();
            			return false;
            		}*/
            else {
                document.clientdash_adduser.submit();
                return true;
            }

        }

        function favitem_remove(favitemid, companyid) {
            document.location = "clientdashboard_setup.php?ID=" + companyid + "&favitemid=" + favitemid +
                "&favremoveflg=yes";
        }

        function handleActive(loginid, id, boomrang) {
            var mailformat = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            // var clientdash_username_edit = document.getElementById('clientdash_username_edit' + loginid);
            // if (clientdash_username_edit.value == '') {
            //     alert("Please enter the Email as User name.");
            //     clientdash_username_edit.focus();
            //     return false;
            // } else if (mailformat.test(clientdash_username_edit.value) == false) {
            //     alert("Enter valid email as User name!");
            //     clientdash_username_edit.focus();
            //     return false;
            // }
            var clientdash_username_edit = document.getElementById('clientdash_username_edit' + loginid);

            // Get the email from the hidden input field
            var clientdash_username_edit_value = document.getElementById('clientdash_username_edit_value' + loginid).value;

            // Check if the hidden email value is empty
            if (clientdash_username_edit_value == '') {
                alert("Please enter the Email as User name.");
                clientdash_username_edit.focus();
                return false;
            } else if (mailformat.test(clientdash_username_edit_value) == false) {
                alert("Enter valid email as User name!");
                clientdash_username_edit.focus();
                return false;
            }

            var checkbox = document.getElementById('clientdash_flg' + loginid).checked;
            if (checkbox) {
                var alertval = confirm("Are you sure you want to 'activate' the customer user.");
            } else {
                var alertval = confirm("Are you sure you want to 'deactivate' the customer user.");
            }
            if (alertval) {
                var clientdash_pwd_edit = document.getElementById('clientdash_pwd_edit' + loginid).value;
                var clientdash_user_edit = document.getElementById('clientdash_username_edit' + loginid).value;

                var useraction_flg = 1;
                if (checkbox) {
                    var alertval = confirm(
                        "Username will be 'activated' from this record. Do you wish to also 'activate' this same username from all connected records as well?"
                    );
                } else {
                    var alertval = confirm(
                        "Username will be 'deactivated' from this record. Do you wish to also 'deactivate' this same username from all connected records as well?"
                    );
                }
                if (alertval) {
                    var useraction_flg = 2;
                }

                document.location = "clientdashboard_user_status.php?loginid=" + loginid + "&useraction_flg=" +
                    useraction_flg + "&companyid=" + id + "&checkbox=" + checkbox +
                    "&status=handleActive&clientdash_pwd_edit=" + clientdash_pwd_edit + "&usnm=" + clientdash_user_edit + "&boomrang=" + encodeURIComponent(boomrang);
                return true;
            } else {
                return false;
            }
        }

        function closed_loop_item_remove(favitemid, companyid) {
            document.location = "clientdashboard_setup.php?ID=" + companyid + "&favitemid=" + favitemid +
                "&closed_loop_item_removeflg=yes";
        }


        function show_closed_loop_inv(companyid) {
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    alert("'Show this section in Front End' - Updated.");
                }
            }

            var chkclosed_inv = 0;
            if (document.getElementById('div_closed_inv').checked) {
                var chkclosed_inv = 1;
            }

            xmlhttp.open("GET", "update_closed_loop_inv_flg.php?companyid=" + companyid + "&chkclosed_inv=" + chkclosed_inv,
                true);
            xmlhttp.send();
        }

        function clientdash_edit(loginid, id) {
            if (document.getElementById('clientdash_username_edit' + loginid).value == '') {
                alert("Please enter the User name.");
                return false;
            } else if (document.getElementById('clientdash_pwd_edit' + loginid).value == '') {
                alert("Please enter the Password.");
                return false;
            } else {
                var chknewval = 0;
                if (document.getElementById('clientdash_flg' + loginid).checked) {
                    var chknewval = 1;
                }
                document.location = "clientdashboard_edituser.php?chkval=" + chknewval + "&usernm=" + document
                    .getElementById('clientdash_username_edit' + loginid).value + "&pwd=" + document.getElementById(
                        'clientdash_pwd_edit' + loginid).value + "&clientdash_eml=" + document.getElementById(
                        'clientdash_eml_edit' + loginid).value + "&loginid=" + loginid + "&companyid=" + id;

                //document.getElementById('user_edit_id').value = loginid;
                //document.clientdash_edituser.submit(); return true; 
            }
        }


        function clientdash_dele(loginid, id, boomrang) {
            var alertval = confirm("Are you sure you want to delete the client user.");
            if (alertval) {
                var useraction_flg = 1;
                var alertval2 = confirm(
                    "Username will be deleted from this record. Do you wish to also delete this same username from all connected records as well?"
                );
                if (alertval2) {
                    var useraction_flg = 2;
                }

                document.location = "clientdashboard_deluser.php?loginid=" + loginid + "&companyid=" + id +
                    "&useraction_flg=" + useraction_flg + "&boomrang=" + encodeURIComponent(boomrang);
                return true;

            }
        }

        function clientdash_sec_edit(sectionid, id) {
            var chknewval = 0;
            if (document.getElementById('clientdash_sec_flg' + sectionid).checked) {
                var chknewval = 1;
            }
            document.location = "clientdashboard_edit_sec.php?clientdash_flg_sec=" + chknewval + "&sectionid=" + sectionid +
                "&companyid=" + id;
        }

        function clientdash_sec_dele(sectionid, id, boomrang) {
            var alertval = confirm("Are you sure you want to delete the record?");
            if (alertval) {
                document.location = "clientdashboard_del_sec.php?sectionid=" + sectionid + "&companyid=" + id + "&boomrang=" + encodeURIComponent(boomrang);
                return true;
            }
        }


        function clientdash_sec_col_edit(section_col_id, id) {
            var chknewval = 0;
            if (document.getElementById('clientdash_sec_col_flg' + section_col_id).checked) {
                var chknewval = 1;
            }
            document.location = "clientdashboard_col_edit_sec.php?clientdash_flg_sec=" + chknewval + "&section_col_id=" +
                section_col_id + "&companyid=" + id;
        }

        function clientdash_sec_list_edit(section_list_id, id) {
            var chknewval = 0;
            if (document.getElementById('clientdash_sec_list_flg' + section_list_id).checked) {
                var chknewval = 1;
            }
            document.location = "clientdashboard_list_edit_sec.php?clientdash_flg_sec=" + chknewval + "&section_list_id=" +
                section_list_id + "&companyid=" + id;
        }



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

        function add_inventory_to_favorite(compId) {
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
            xmlhttp.open("GET", "getBoxData.php?ID=" + compId, true);
            xmlhttp.send(); /**/
        }


        function add_inventory_to_hide(compId) {
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
            xmlhttp.open("GET", "getBoxDataHide.php?ID=" + compId, true);
            xmlhttp.send();
        }

        function Remove_boxes_hide(compId, favB2bId) {
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

            xmlhttp.open("GET", "update_hide_inventory_data.php?compId=" + compId + "&favB2bId=" + favB2bId +
                "&upd_action=2", true);
            xmlhttp.send();
        }

        function Add_boxes_hide(compId, favB2bId) {

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

            xmlhttp.open("GET", "update_hide_inventory_data.php?compId=" + compId + "&favB2bId=" + favB2bId +
                "&upd_action=1", true);
            xmlhttp.send();

        }

        function hideitem_remove(hideitemid, companyid) {
            document.location = "clientdashboard_setup.php?ID=" + companyid + "&hideitemid=" + hideitemid +
                "&hideremoveflg=yes";
        }





        function Remove_boxes_warehouse_data(compId, favB2bId, closeloop = 0) {
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    if (xmlhttp.responseText == 'done') {
                        alert('Remove successfully. Need to reload the page after all boxes are updated.');

                    }
                }
            }
            if (closeloop == 1) {
                xmlhttp.open("GET", "update_closeloop_inventory_data.php?compId=" + compId + "&favB2bId=" + favB2bId +
                    "&upd_action=2", true);
                xmlhttp.send();
            } else {
                xmlhttp.open("GET", "update_favorite_inventory_data.php?compId=" + compId + "&favB2bId=" + favB2bId +
                    "&upd_action=2", true);
                xmlhttp.send();
            }
        }

        function Add_boxes_warehouse_data(compId, favB2bId, closeloop = 0) {

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
                    }
                }
            }
            if (closeloop == 1) {
                xmlhttp.open("GET", "update_closeloop_inventory_data.php?compId=" + compId + "&favB2bId=" + favB2bId +
                    "&upd_action=1", true);
                xmlhttp.send();
            } else {
                xmlhttp.open("GET", "update_favorite_inventory_data.php?compId=" + compId + "&favB2bId=" + favB2bId +
                    "&upd_action=1", true);
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
            xmlhttp.open("GET", "getBoxDataCloseloop.php?ID=" + compId, true);
            xmlhttp.send();
        }

        function handleSetuphide(id) {

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
            if (document.getElementById('clientdash_boxprofile_inv_hide').checked) {
                var boxprofileinv_flg = 1;
            }

            var setupfamily_tree = 0;
            if (document.getElementById('clientdash_family_tree')) {
                if (document.getElementById('clientdash_family_tree').checked) {
                    var setupfamily_tree = 1;
                }
            }

            xmlhttp.open("GET", "update_setup_hide_flg.php?companyid=" + id + "&setuphide_flg=" + setuphide_flg +
                "&boxprofileinv_flg=" + boxprofileinv_flg + "&setupfamily_tree=" + setupfamily_tree, true);
            xmlhttp.send();
        }

        function handleBoxprofileInvhide(id) {
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



            xmlhttp.open("GET", "update_boxPinv_hide_flg.php?companyid=" + id + "&boxprofileinv_flg=" + boxprofileinv_flg,
                true);
            xmlhttp.send();
        }
    </script>
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
    </style>
    <LINK rel='stylesheet' type='text/css' href='one_style.css'>
    <link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap"
        rel="stylesheet">
    <script>
        function sendInvite() {
            var email = document.getElementById("email").value;
            var company_id = document.getElementById("companyid").value;
            // alert(company_id);
            if (!email) {
                alert("Please enter an email address.");
                return false;
            }

            if (window.XMLHttpRequest) {
                var xmlhttp = new XMLHttpRequest();
            } else {
                var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }



            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                    alert("Invite sent successfully to " + email);
                }
            }

            var url = "boomerang_new_invite_submit.php?email=" + encodeURIComponent(email) + "&company_id=" + encodeURIComponent(company_id);
            xmlhttp.open("GET", url, true);
            xmlhttp.send();

            return false; // Prevent form submission
        }
    </script>
</head>

<?php


$ID = $_REQUEST["ID"];
$account_owner = 0;
$company_log = "";

db();
$res = db_query("Select * from clientdashboard_details where companyid = $ID");
while ($fetch_data = array_shift($res)) {
    $account_owner = $fetch_data["accountmanager_empid"];
    $company_log = $fetch_data["logo_image"];
}

if ($account_owner == 0) {

    db_b2b();
    $res = db_query("Select assignedto from companyInfo where ID = $ID");
    while ($fetch_data = array_shift($res)) {
        $account_owner = $fetch_data["assignedto"];
    }
}

db_b2b();
$res = db_query("Select haveNeed, email, parent_child from companyInfo where ID = $ID");
$buyer_seller_flg = 0;
$client_eml = "";
$parent_flg = "";
while ($fetch_data = array_shift($res)) {
    if ($fetch_data["haveNeed"] == "Need Boxes") {
        $buyer_seller_flg = 0;
    } else {
        $buyer_seller_flg = 1;
    }
    $client_eml = $fetch_data["email"];
    $parent_flg = $fetch_data["parent_child"];
}

//echo "<br /> buyer_seller_flg - ".$buyer_seller_flg;
db();
$res = db_query("Select activate_deactivate from clientdashboard_section_details where companyid = $ID and section_id = 6 and activate_deactivate = 1");
$close_inv_flg = 0;
while ($fetch_data = array_shift($res)) {
    $close_inv_flg = 1;
}

db();
$res = db_query("Select activate_deactivate from clientdashboard_section_details where companyid = $ID and section_id = 7 and activate_deactivate = 1");
$setup_hide_flg = 0;
while ($fetch_data = array_shift($res)) {
    $setup_hide_flg = 1;
}

db();
$res = db_query("Select activate_deactivate from clientdashboard_section_details where companyid = $ID and section_id = 8 and activate_deactivate = 1");
$setup_boxprofile_inv_flg = 0;
while ($fetch_data = array_shift($res)) {
    $setup_boxprofile_inv_flg = 1;
}

$setup_family_tree = 0;
db();
$res = db_query("Select activate_deactivate from clientdashboard_section_details where companyid = $ID and section_id = 9 and activate_deactivate = 1");
while ($fetch_data = array_shift($res)) {
    $setup_family_tree = 1;
}

if ($buyer_seller_flg == 0) {

    db();
    $res = db_query("Select section_id from clientdashboard_section_details where companyid = $ID and section_id = 1");
    $rec_found = "no";
    while ($fetch_data = array_shift($res)) {
        $rec_found = "yes";
    }
    if ($rec_found == "no") {

        db();
        $res = db_query("Insert into clientdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 1, 1)");
        $res = db_query("Insert into clientdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 1, 1)");
        $res = db_query("Insert into clientdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 2, 1)");
        $res = db_query("Insert into clientdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 3, 1)");
    }

    db();
    $res = db_query("Select section_id from clientdashboard_section_details where companyid = $ID and section_id = 2");
    $rec_found = "no";
    while ($fetch_data = array_shift($res)) {
        $rec_found = "yes";
    }
    if ($rec_found == "no") {

        db();
        $res = db_query("Insert into clientdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 2, 1)");
        $res = db_query("Insert into clientdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 4, 1)");
        $res = db_query("Insert into clientdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 5, 1)");
        $res = db_query("Insert into clientdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 6, 1)");
        $res = db_query("Insert into clientdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 7, 1)");
        $res = db_query("Insert into clientdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 8, 1)");
        $res = db_query("Insert into clientdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 9, 1)");
        $res = db_query("Insert into clientdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 10, 1)");
        $res = db_query("Insert into clientdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 11, 1)");
    }

    db();
    $res = db_query("Select section_id from clientdashboard_section_details where companyid = $ID and section_id = 3");
    $rec_found = "no";
    while ($fetch_data = array_shift($res)) {
        $rec_found = "yes";
    }
    if ($rec_found == "no") {

        db();
        $res = db_query("Insert into clientdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 3, 1)");
    }

    db();
    $res = db_query("Select section_id from clientdashboard_section_details where companyid = $ID and section_id = 4");
    $rec_found = "no";
    while ($fetch_data = array_shift($res)) {
        $rec_found = "yes";
    }
    if ($rec_found == "no") {

        db();
        $res = db_query("Insert into clientdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 4, 1)");
    }

    db();
    $res = db_query("Select section_id from clientdashboard_section_details where companyid = $ID and section_id = 5");
    $rec_found = "no";
    while ($fetch_data = array_shift($res)) {
        $rec_found = "yes";
    }
    if ($rec_found == "no") {

        db();
        $res = db_query("Insert into clientdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 5, 1)");
    }
}

?>

<body>
    <?php

    include("inc/header.php");

    ?>

    <?php
    include "clientdashboard_setup_inc.php";
    ?>
</body>

</html>