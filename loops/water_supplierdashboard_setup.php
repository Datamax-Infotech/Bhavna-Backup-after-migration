<?php
require_once("inc/header_session.php");

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

$supplier_loopid = 0;
$sql = "SELECT id, warehouse_name FROM loop_warehouse where b2bid = " . $_REQUEST['ID'] . " ";
//echo $sql;
db();
$result = db_query($sql);
$warehouse_name = "";
while ($myrowsel = array_shift($result)) {
    $supplier_loopid = $myrowsel["id"];
    $warehouse_name = $myrowsel["warehouse_name"];
}
?>
<html>

<head>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        /*Tooltip style*/
        .tooltip {
            position: relative;
            display: inline-block;

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
            /*white-space: nowrap;*/
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

        .fa-info-circle {
            font-size: 9px;
            color: #767676;
        }

        .border_1px,
        .border_1px td {
            border: solid 1px #0000005c;
            border-collapse: collapse;
        }
    </style>

    <style type="text/css">
        /* .black_overlay {
            display: none;
            position: absolute;
            top: 0%;
            left: 0%;
            width: 100%;
            height: 100%;
            background-color: gray;
            z-index: 1001;
            -moz-opacity: 0.8;
            opacity: .80;
            filter: alpha(opacity=80);
        } */

        .white_content {
            display: none;
            position: absolute;
            top: 5%;
            left: 10%;
            width: 60%;
            height: 90%;
            padding: 16px;
            border: 1px solid gray;
            background-color: white;
            z-index: 1002;
            overflow: auto;
        }

        .white_content_details {
            display: none;
            position: absolute;
            top: 0%;
            left: 10%;
            width: 50%;
            height: auto;
            padding: 16px;
            border: 1px solid gray;
            background-color: white;
            z-index: 1002;
            overflow: auto;
            box-shadow: 8px 8px 5px #888888;
        }


        .white_content_reminder {
            display: none;
            position: absolute;
            top: 1300px !important;
            left: 10%;
            width: 70%;
            height: 85%;
            padding: 16px;
            border: 1px solid gray;
            background-color: white;
            z-index: 1002;
            overflow: auto;
            box-shadow: 8px 8px 5px #888888;
        }

        #txtemailbody___Frame {
            height: 284px !important;
        }
    </style>
    <script LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></script>
    <script LANGUAGE="JavaScript">
        document.write(getCalendarStyles());
    </script>
    <script language="javascript">
        var calstdate = new CalendarPopup("listdiv_dt");
        calstdate.showNavigationDropdowns();

        var calenddate = new CalendarPopup("listdiv_dt");
        calenddate.showNavigationDropdowns();
    </script>

    <title>Supplier Dashboard Setup</title>

    <script language="javascript">
        function validateEmail(email) {
            email = email.trim();
            var regexva =
                /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if (email != '') {
                return regexva.test(email);
            } else {
                return true;
            }
        }

        function emailtocheck(txtEmail, inputtxtName) {
            var emailchk = "pass";
            if (txtEmail != "") {
                if (txtEmail.includes(',')) {
                    const txtEmailarr = txtEmail.split(",");
                    var flg = "no";
                    for (let i = 0; i < txtEmailarr.length; i++) {
                        if (validateEmail(txtEmailarr[i]) == false) {
                            flg = "yes";
                            emailchk = "fail";
                            alert('Please enter a valid email address \n' + txtEmailarr[i] + '\n in [' + inputtxtName +
                                '].');
                        }
                    }
                    if (flg == "no") {
                        emailchk = "pass";
                    }
                } else if (txtEmail.includes(';')) {
                    const txtEmailarr = txtEmail.split(";");
                    var flg = "no";
                    for (let i = 0; i < txtEmailarr.length; i++) {
                        if (validateEmail(txtEmailarr[i]) == false) {
                            flg = "yes";
                            emailchk = "fail";
                            alert('Please enter a valid email address \n' + txtEmailarr[i] + '\n in [' + inputtxtName +
                                '].');
                        }
                    }
                    if (flg == "no") {
                        emailchk = "pass";
                    }
                } else {
                    if (validateEmail(txtEmail) == false) {
                        emailchk = "fail";
                        alert('Please enter a valid email address \n' + txtEmail + '\n in [' + inputtxtName + '].');
                    } else {
                        emailchk = "pass";
                    }
                }

            } else {
                emailchk = "pass";
            }

            return emailchk;
        }

        function validate_ctrl() {
            var txtEmailTo = document.getElementById('email_address_txt').value;
            emailchk = emailtocheck(txtEmailTo, 'Emai');

            if (emailchk == "fail") {
                return false;
            } else {
                return true;
            }
        }

        function supplierdash_chkfrm() {
            if (document.getElementById('supplierdash_username').value == '') {
                alert("Please enter the User name.");
                return false;
            } else if (document.getElementById('supplierdash_pwd').value == '') {
                alert("Please enter the Password.");
                return false;
            } else if (document.getElementById('supplierdash_eml').value == '') {
                alert("Please enter the supplier Email.");
                return false;
            } else {
                document.supplierdash_adduser.submit();
                return true;
            }

        }


        function supplierdash_edit(loginid, id) {
            if (document.getElementById('supplierdash_username_edit' + loginid).value == '') {
                alert("Please enter the User name.");
                return false;
            } else if (document.getElementById('supplierdash_pwd_edit' + loginid).value == '') {
                alert("Please enter the Password.");
                return false;
            } else {
                var chknewval = 0;
                if (document.getElementById('supplierdash_flg' + loginid).checked) {
                    var chknewval = 1;
                }
                document.location = "supplierdashboard_edituser.php?chkval=" + chknewval + "&usernm=" + document
                    .getElementById('supplierdash_username_edit' + loginid).value + "&pwd=" + document.getElementById(
                        'supplierdash_pwd_edit' + loginid).value + "&supplierdash_eml=" + document.getElementById(
                        'supplierdash_eml_edit' + loginid).value + "&loginid=" + loginid + "&companyid=" + id;

                //document.getElementById('user_edit_id').value = loginid;
                //document.supplierdash_edituser.submit(); return true; 
            }
        }


        function supplierdash_dele(loginid, id) {
            var alertval = confirm("Are you sure you want to delete the supplier user.");
            if (alertval) {
                document.location = "supplierdashboard_deluser.php?loginid=" + loginid + "&companyid=" + id;
                return true;
            }
        }

        function supplierdash_sec_edit(sectionid, id) {
            var chknewval = 0;
            var supplierdash_sec_element = document.getElementById('supplierdash_sec_flg' + sectionid);
            if (supplierdash_sec_element && supplierdash_sec_element.checked) {
                chknewval = 1;
            }

            var chknewval1 = 0;
            var supplierdash_transcation_element = document.getElementById('supplierdash_transcation_flg' + sectionid);
            if (supplierdash_transcation_element && supplierdash_transcation_element.checked) {
                chknewval1 = 1;
            }

            document.location = "supplierdashboard_edit_sec.php?supplierdash_transcation_flg=" + chknewval1 +
                "&supplierdash_flg_sec=" + chknewval + "&sectionid=" + sectionid + "&companyid=" + id;
        }

        function supplierdash_sec_dele(sectionid, id) {
            var alertval = confirm("Are you sure you want to delete the record.");
            if (alertval) {
                document.location = "supplierdashboard_del_sec.php?sectionid=" + sectionid + "&companyid=" + id +
                    "&flg=suplryes";
                return true;
            }
        }

        function supplierdash_item_sec_dele(sectionid, id) {
            var alertval = confirm("Are you sure you want to delete the record.");
            if (alertval) {
                document.location = "supplierdashboard_del_sec.php?sectionid=" + sectionid + "&companyid=" + id +
                    "&flg=yes";
                return true;
            }
        }

        function supplierdash_commodity_sec_edit(sectionid, id) {
            document.location = "water_supplierdashboard_setup.php?sectionid=" + sectionid + "&ID=" + id +
                "&flg=edit_commodity";
            return true;
        }

        function supplierdash_commodity_sec_dele(sectionid, id) {
            var alertval = confirm("Are you sure you want to delete the record.");
            if (alertval) {
                document.location = "supplierdashboard_commodity_del_sec.php?sectionid=" + sectionid + "&companyid=" + id +
                    "&flg=yes";
                return true;
            }
        }

        function supplierdash_file_dele(sectionid, id, supplier_loopid) {
            var alertval = confirm("Are you sure you want to delete the record.");
            if (alertval) {
                document.location = "supplierdashboard_obm_sec.php?sectionid=" + sectionid + "&companyid=" + id +
                    "&warehouse_id=" + supplier_loopid + "&obm_flg=yes";
                return true;
            }
        }

        function supplierdash_obm_dele(sectionid, id, supplier_loopid) {
            var alertval = confirm("Are you sure you want to delete the record.");
            if (alertval) {
                document.location = "supplierdashboard_obm_sec.php?sectionid=" + sectionid + "&companyid=" + id +
                    "&warehouse_id=" + supplier_loopid + "&obm_flg=yes";
                return true;
            }
        }

        function supplierdash_sec_col_edit(section_col_id, id) {
            var chknewval = 0;
            if (document.getElementById('supplierdash_sec_col_flg' + section_col_id).checked) {
                var chknewval = 1;
            }

            document.location = "supplierdashboard_col_edit_sec.php?supplierdash_flg_sec=" + chknewval +
                "&section_col_id=" + section_col_id + "&companyid=" + id + "&item_info=suplrYes";
        }

        function supplierdash_item_sec_col_edit(col_id, id) {
            //alert(document.getElementById('supplierdash_item_info_flg' + col_id).checked);
            var chknewval = 0;
            if (document.getElementById('supplierdash_item_info_flg' + col_id).checked) {
                var chknewval = 1;
            }
            document.location = "supplierdashboard_col_edit_sec.php?supplierdash_flg=" + chknewval + "&col_id=" + col_id +
                "&item_info=Yes" + "&companyid=" + id;
        }

        function supplierdash_sec_list_edit(section_list_id, id) {
            var chknewval = 0;
            if (document.getElementById('supplierdash_sec_list_flg' + section_list_id).checked) {
                var chknewval = 1;
            }
            document.location = "supplierdashboard_list_edit_sec.php?supplierdash_flg_sec=" + chknewval +
                "&section_list_id=" + section_list_id + "&companyid=" + id;
        }

        function supplierdash_trailer_sec_dele(sectionid, id) {
            var alertval = confirm("Are you sure you want to delete the record.");
            if (alertval) {
                document.location = "supplierdashboard_del_sec.php?sectionid=" + sectionid + "&companyid=" + id +
                    "&flg=traileryes";
                return true;
            }
        }

        function supplierdash_trailer_sec_col_edit(col_id, id) {
            //alert(document.getElementById('supplierdash_item_info_flg' + col_id).checked);
            var chknewval = 0;
            if (document.getElementById('supplierdash_trailer_info_flg' + col_id).checked) {
                var chknewval = 1;
            }
            document.location = "supplierdashboard_col_edit_sec.php?supplierdash_flg=" + chknewval + "&col_id=" + col_id +
                "&item_info=trailerYes" + "&companyid=" + id;
        }


        function GetProfileFileSize() {
            var fi = document.getElementById('Profilefile');
            // VALIDATE OR CHECK IF ANY FILE IS SELECTED.
            if (fi.files.length > 0) {
                // RUN A LOOP TO CHECK EACH SELECTED FILE.
                for (var i = 0; i <= fi.files.length - 1; i++) {
                    var filenm = fi.files.item(i).name;
                    if (filenm.indexOf("#") > 0) {
                        alert("Remove # from " + filenm + " file and then upload file!");
                        document.getElementById("Profilefile").value = "";
                    }
                    if (filenm.indexOf("\'") > 0) {
                        alert("Remove \' from " + filenm + " file  and then upload file!");
                        document.getElementById("Profilefile").value = "";
                    }
                    var fsize = fi.files.item(i).size; // THE SIZE OF THE FILE.
                    if (Math.round(fsize / 1024) > 8000) {
                        alert("Only files with 8mb is allowed.");
                        document.getElementById("Profilefile").value = "";
                    }

                    // Allowing file type 
                    var allowedExtensions = /(\.pdf|\.PDF|\.xls|\.XLS|\.xlsx|\.XLSX|\.doc|\.DOC|\.docx|\.DOCX|)$/i;
                    if (!allowedExtensions.exec(filenm)) {
                        alert('Invalid file type');
                        document.getElementById("Profilefile").value = "";
                    }

                }
            }
        }

        function proFileListDel(recordId) {
            var alertval = confirm("Are you sure you want to delete this record.");
            if (alertval) {
                document.location = "upload_profile_file.php?action=delete&recordId=" + recordId;
                return true;
            }
        }

        function GetCommodityFileSize() {
            var imgAcceptComm = document.getElementById('imgAcceptComm');
            var imgNonAcceptComm = document.getElementById('imgNonAcceptComm');
            // VALIDATE OR CHECK IF ANY FILE IS SELECTED.
            if (imgAcceptComm != '') {
                if (imgAcceptComm.files.length > 0) {
                    for (var i = 0; i <= imgAcceptComm.files.length - 1; i++) {
                        var filenm = imgAcceptComm.files.item(i).name;
                        if (filenm.indexOf("#") > 0) {
                            alert("Remove # from " + filenm + " file and then upload file!");
                            document.getElementById("imgAcceptComm").value = "";
                        }
                        if (filenm.indexOf("\'") > 0) {
                            alert("Remove \' from " + filenm + " file  and then upload file!");
                            document.getElementById("imgAcceptComm").value = "";
                        }
                        var fsize = imgAcceptComm.files.item(i).size; // THE SIZE OF THE FILE.
                        if (Math.round(fsize / 1024) > 8000) {
                            alert("Only files with 8mb is allowed.");
                            document.getElementById("imgAcceptComm").value = "";
                        }
                        // Allowing file type 
                        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pjpeg|\.pjp)$/i;
                        if (!allowedExtensions.exec(filenm)) {
                            alert('Invalid file type');
                            document.getElementById("imgAcceptComm").value = "";
                        }
                    }
                }
            }
            if (imgNonAcceptComm != '') {
                if (imgNonAcceptComm.files.length > 0) {
                    for (var i = 0; i <= imgNonAcceptComm.files.length - 1; i++) {
                        var filenm = imgNonAcceptComm.files.item(i).name;
                        if (filenm.indexOf("#") > 0) {
                            alert("Remove # from " + filenm + " file and then upload file!");
                            document.getElementById("imgNonAcceptComm").value = "";
                        }
                        if (filenm.indexOf("\'") > 0) {
                            alert("Remove \' from " + filenm + " file  and then upload file!");
                            document.getElementById("imgNonAcceptComm").value = "";
                        }
                        var fsize = imgNonAcceptComm.files.item(i).size; // THE SIZE OF THE FILE.
                        if (Math.round(fsize / 1024) > 8000) {
                            alert("Only files with 8mb is allowed.");
                            document.getElementById("imgNonAcceptComm").value = "";
                        }
                        // Allowing file type 
                        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pjpeg|\.pjp)$/i;
                        if (!allowedExtensions.exec(filenm)) {
                            alert('Invalid file type');
                            document.getElementById("imgNonAcceptComm").value = "";
                        }
                    }
                }
            }
        }

        function chkSubmitdtls() {
            var txtPriCommodityNm = document.getElementById('txtPriCommodityNm');
            var txtSecCommodityNm = document.getElementById('txtSecCommodityNm');
            var imgAcceptComm = document.getElementById('imgAcceptComm');
            var imgNonAcceptComm = document.getElementById('imgNonAcceptComm');
            var txtNotes = document.getElementById('txtNotes');
            var txtNotes2 = document.getElementById('txtNotes2');
            if (txtPriCommodityNm != '') {
                if (txtPriCommodityNm.value == '') {
                    alert('Please enter Primary Commodity Name.');
                    txtPriCommodityNm.focus();
                    return false;
                }
            }
            if (txtSecCommodityNm != '') {
                if (txtSecCommodityNm.value == '') {
                    alert('Please enter Secondary Commodity Name.');
                    txtSecCommodityNm.focus();
                    return false;
                }
            }
            if (imgAcceptComm != '') {
                if (imgAcceptComm.value == '') {
                    alert('Please select picture 1(accept commodity).');
                    imgAcceptComm.focus();
                    return false;
                }
            }
            if (imgNonAcceptComm != '') {
                if (imgNonAcceptComm.value == '') {
                    alert('Please select picture 1(non accept commodity).');
                    imgNonAcceptComm.focus();
                    return false;
                }
            }
            if (txtNotes != '') {
                if (txtNotes.value == '') {
                    alert('Please enter Notes 1.');
                    txtNotes.focus();
                    return false;
                }
            }
            if (txtNotes2 != '') {
                if (txtNotes2.value == '') {
                    alert('Please enter Notes 2.');
                    txtNotes2.focus();
                    return false;
                }
            }
            return true;
        }

        function zwCommodityDel(recordId) {
            var alertval = confirm("Are you sure you want to delete this record.");
            if (alertval) {
                document.location = "save_zw_program_bins.php?action=delete&recordId=" + recordId;
                return true;
            }
        }

        function zwCommodityEdit(sectionId, compId, warehouseId) {
            document.location = "water_supplierdashboard_setup.php?sectionid=" + sectionId + "&ID=" + compId +
                "&warehouseId=" + warehouseId + "&flgZw=edit_zw_commodity";
            return true;
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

        function reminder_popup_set_new(comid, email_address, company_id) {
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    selectobject = document.getElementById("viewdiv");
                    n_left = f_getPosition(selectobject, 'Left');
                    n_top = f_getPosition(selectobject, 'Top');

                    document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                    document.getElementById('light_reminder').style.display = 'block';

                    document.getElementById('light_reminder').style.left = (n_left + 50) + 'px';
                    document.getElementById('light_reminder').style.top = n_top + 50 + 'px';
                    document.getElementById('light_reminder').style.width = 1100 + 'px';
                }
            }

            xmlhttp.open("POST", "sendemail_add_po_new.php?comid=" + comid + "&email_address=" + encodeURIComponent(email_address) + "&company_id=" + company_id, true);
            xmlhttp.send();
        }
    </script>



</head>

<?php
$ID = $_REQUEST["ID"];
$account_owner = 0;
$company_log = "";
db();
$res = db_query("Select * from supplierdashboard_details where companyid = $ID");
while ($fetch_data = array_shift($res)) {
    $account_owner = $fetch_data["accountmanager_empid"];
    $company_log = $fetch_data["logo_image"];
    $cntSprtLns        = $fetch_data['contact_support_line'];
}

if ($account_owner == 0) {
    db_b2b();
    $res = db_query("Select assignedto from companyInfo where ID = $ID");
    while ($fetch_data = array_shift($res)) {
        $account_owner = $fetch_data["assignedto"];
    }
    db();
    $res = db_query("Insert into supplierdashboard_details ( companyid, accountmanager_empid, logo_image) values ($ID, $account_owner, '')");
}

db_b2b();
$res = db_query("Select haveNeed, email from companyInfo where ID = $ID");
$buyer_seller_flg = 0;
$supplier_eml = "";
while ($fetch_data = array_shift($res)) {
    if ($fetch_data["haveNeed"] == "Have Boxes") {
        $buyer_seller_flg = 0;
    } else {
        $buyer_seller_flg = 1;
    }
    $supplier_eml = $fetch_data["email"];
}

if ($buyer_seller_flg == 0) {
    db();
    $res = db_query("Select section_id from supplierdashboard_section_details where companyid = $ID and section_id = 1");
    $rec_found = "no";
    while ($fetch_data = array_shift($res)) {
        $rec_found = "yes";
    }
    if ($rec_found == "no") {
        db();
        $res = db_query("Insert into supplierdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 1, 1)");
        $res = db_query("Insert into supplierdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 2, 1)");
        $res = db_query("Insert into supplierdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 3, 0)");
        $res = db_query("Insert into supplierdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 4, 1)");
        $res = db_query("Insert into supplierdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 5, 1)");
        $res = db_query("Insert into supplierdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 6, 1)");
        $res = db_query("Insert into supplierdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 7, 1)");
        $res = db_query("Insert into supplierdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 8, 1)");
        $res = db_query("Insert into supplierdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 9, 0)");

        $res = db_query("Insert into supplierdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 1, 1)");
        $res = db_query("Insert into supplierdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 2, 1)");
        $res = db_query("Insert into supplierdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 3, 1)");
        $res = db_query("Insert into supplierdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 4 ,1)");
        $res = db_query("Insert into supplierdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 5, 1)");
        $res = db_query("Insert into supplierdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 6, 1)");
        $res = db_query("Insert into supplierdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 7, 1)");
        $res = db_query("Insert into supplierdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 8, 1)");
        $res = db_query("Insert into supplierdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 9, 1)");
    }
    db();
    $res = db_query("Select id from supplier_trailer_info where companyid = $ID");
    $rec_found = "no";
    while ($fetch_data = array_shift($res)) {
        $rec_found = "yes";
    }
    if ($rec_found == "no") {
        db();
        $res = db_query("Insert into supplier_trailer_info ( companyid, item, displayflg) values ($ID, 'Trailer Number:', 1)");
        $res = db_query("Insert into supplier_trailer_info ( companyid, item, displayflg) values ($ID, 'Commodity:', 1)");
        $res = db_query("Insert into supplier_trailer_info ( companyid, item, displayflg) values ($ID, 'Name:', 1)");
        $res = db_query("Insert into supplier_trailer_info ( companyid, item, displayflg) values ($ID, 'Pickup Date:', 1)");
        $res = db_query("Insert into supplier_trailer_info ( companyid, item, displayflg) values ($ID, 'Comments:', 1)");
    }

    /*$res = db_query("Select section_id from supplierdashboard_section_details where companyid = $ID and section_id = 2" , db()); 
		$rec_found = "no";
		while($fetch_data=array_shift($res))
		{
			$rec_found = "yes";
		}
		if ($rec_found == "no") {
			$res = db_query("Insert into supplierdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 2, 1)" , db()); 
			$res = db_query("Insert into supplierdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 4, 1)" , db()); 
			$res = db_query("Insert into supplierdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 5, 1)" , db()); 
			$res = db_query("Insert into supplierdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 6, 1)" , db()); 
			$res = db_query("Insert into supplierdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 7, 1)" , db()); 
			$res = db_query("Insert into supplierdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 8, 1)" , db()); 
			$res = db_query("Insert into supplierdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 9, 1)" , db()); 
			$res = db_query("Insert into supplierdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 10, 1)" , db()); 
		}

		$res = db_query("Select section_id from supplierdashboard_section_details where companyid = $ID and section_id = 3" , db()); 
		$rec_found = "no";
		while($fetch_data=array_shift($res))
		{
			$rec_found = "yes";
		}
		if ($rec_found == "no") {
			$res = db_query("Insert into supplierdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 3, 1)" , db()); 
		}
		
		$res = db_query("Select section_id from supplierdashboard_section_details where companyid = $ID and section_id = 4" , db()); 
		$rec_found = "no";
		while($fetch_data=array_shift($res))
		{
			$rec_found = "yes";
		}
		if ($rec_found == "no") {
			$res = db_query("Insert into supplierdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 4, 1)" , db()); 
		}
		
		$res = db_query("Select section_id from supplierdashboard_section_details where companyid = $ID and section_id = 5" , db()); 
		$rec_found = "no";
		while($fetch_data=array_shift($res))
		{
			$rec_found = "yes";
		}
		if ($rec_found == "no") {
			$res = db_query("Insert into supplierdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 5, 1)" , db()); 
		}*/
}
?>

<body>
    <a href="viewCompany.php?ID=<?php echo $ID ?>">View B2B page</a> &nbsp;&nbsp;<a
        href="https://supplier.usedcardboardboxes.com/supplier_dashboard.php?companyid=<?php echo $ID ?>&repchk=yes">View supplier
        Dashboard</a>
    <br />
    <table border="0" bgcolor="#F6F8E5" align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12;">
        <tr align="center">
            <td colspan="6" width="320px" bgcolor="#E8EEA8"><strong>Supplier Dashboard Setup</strong></font>
            </td>
        </tr>
        <form method="post" name="supplierdash_adduser" action="supplierdashboard_adduser.php">
            <input type="hidden" name="hidden_companyid" value="<?php echo $ID; ?>" />

            <?php if (isset($_REQUEST["duprec"])) {
                if ($_REQUEST["duprec"] == "yes") { ?>
                    <tr align="center">
                        <td colspan="6" width="320px" align="left" bgcolor="red">User name already exists, record not added.
                        </td>
                    </tr>
            <?php  }
            } ?>


            <tr align="center">
                <td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">Add new user for Customer</td>
            </tr>
            <tr align="center">
                <td width="80px">User name: </td>
                <td colspan="5" width="320px" align="left"><input type="text" name="supplierdash_username"
                        id="supplierdash_username" value="" /></td>
            </tr>
            <tr align="center">
                <td width="80px">Password: </td>
                <td colspan="5" width="320px" align="left"><input type="password" name="supplierdash_pwd"
                        id="supplierdash_pwd" value="" /></td>
            </tr>
            <tr align="center">
                <td width="80px">Email: </td>
                <td colspan="5" width="320px" align="left"><input type="text" name="supplierdash_eml"
                        id="supplierdash_eml" value="<?php echo $supplier_eml; ?>" /></td>
            </tr>
            <tr align="center">
                <td width="80px">&nbsp;</td>
                <td colspan="5" width="320px" align="left"><input type="button" name="supplierdash_adduser" value="Add"
                        onclick="supplierdash_chkfrm()" /></td>
            </tr>
        </form>

        <form method="get" name="supplierdash_edituser" action="supplierdashboard_edituser.php">
            <input type="hidden" name="user_edit" id="user_edit" value="yes" />

            <tr align="center">
                <td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">Customer user list</td>
            </tr>
            <tr align="center">
                <td width="80px">User name</td>
                <td width="80px" align="left">Password</td>
                <td width="80px" align="left">Email</td>
                <td width="100px" align="left">Activate/Deactivate</td>
                <td width="40px" align="left">Edit</td>
                <td width="100px" align="left">Delete</td>
            </tr>
            <?php
            $qry = "Select * From supplierdashboard_usermaster Where companyid = $ID";
            db();
            $res = db_query($qry);
            while ($fetch_data = array_shift($res)) {
            ?>
                <input type="hidden" name="loginid" id="loginid" value="<?php echo $fetch_data["loginid"]; ?>" />
                <tr align="center">
                    <td width="80px"><input type="text" name="supplierdash_username_edit"
                            id="supplierdash_username_edit<?php echo $fetch_data["loginid"]; ?>"
                            value="<?php echo $fetch_data["user_name"]; ?>" /></td>
                    <td width="80px" align="left"><input type="password" name="supplierdash_pwd_edit"
                            id="supplierdash_pwd_edit<?php echo $fetch_data["loginid"]; ?>"
                            value="<?php echo $fetch_data["password"]; ?>" /></td>
                    <td width="80px" align="left"><input type="text" name="supplierdash_eml_edit"
                            id="supplierdash_eml_edit<?php echo $fetch_data["loginid"]; ?>"
                            value="<?php echo $fetch_data["supplier_email"]; ?>" /></td>
                    <td width="100px" align="left"><input type="checkbox" name="supplierdash_flg"
                            id="supplierdash_flg<?php echo $fetch_data["loginid"]; ?>"
                            <?php if ($fetch_data["activate_deactivate"] == 1) {
                                echo " checked ";
                            } ?> /></td>
                    <td width="40px" align="left"><input type="button" value="Edit"
                            onclick="supplierdash_edit(<?php echo $fetch_data["loginid"]; ?>, <?php echo $ID; ?>)" /></td>
                    <td width="100px" align="left"><input type="button" value="Delete"
                            onclick="supplierdash_dele(<?php echo $fetch_data["loginid"]; ?>, <?php echo $ID; ?>)" /></td>
                </tr>
            <?php
            }
            ?>
        </form>

        <tr align="center">
            <td colspan="6" width="320px" align="left">&nbsp;</td>
        </tr>
        <form method="post" name="supplierdash_edituser_details" action="supplierdashboard_edit_details.php"
            encType="multipart/form-data">
            <input type="hidden" name="supplierdash_edituser_details_id" id="supplierdash_edituser_details_id"
                value="<?php echo $ID; ?>" />
            <tr align="center">
                <td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">Update Details</td>
            </tr>
            <tr align="center">
                <td width="140px" align="left">Account owner</td>
                <td colspan="3" width="180px" align="left"><select name="supplierdash_acc_owner"
                        id="supplierdash_acc_owner">
                        <?php
                        //$res = db_query("Select id, b2b_id, name, phoneext from loop_employees order by name" , db_b2b()); 
                        db_b2b();
                        $res = db_query("Select employeeID, name from employees order by name");

                        $tmpvar = "";
                        while ($fetch_data = array_shift($res)) {
                            if ($fetch_data['employeeID'] == $account_owner) {
                                $tmpvar = " selected ";
                            } else {
                                $tmpvar = " ";
                            }
                            echo "<option value='" . $fetch_data["employeeID"] . "' $tmpvar >" . $fetch_data["name"] . " </option>";
                        }
                        ?></select>
                </td>
            </tr>

            <tr align="center">
                <td width="140px" align="left">Company Logo</td>
                <td colspan="3" width="180px" align="left">
                    <input type="file" name="companylogo" /> <br />
                    <?php if ($company_log != "") {
                        echo "Uploaded file: " . $company_log; ?>
                        <image src="supplierdashboard_logo/<?php echo $company_log; ?>" width="100px" height="100px" />
                    <?php  } ?>
                </td>
            </tr>

            <?php

            db();
            $res = db_query("Select * from  supplierdashboard_globalvar where variable_name = 'tollfree_no'");
            $tollfree_no = "";
            while ($fetch_data = array_shift($res)) {
                $tollfree_no = $fetch_data["variable_value"];
            }
            db();
            $res = db_query("Select * from supplierdashboard_globalvar where variable_name = 'office_no'");
            $office_no = "";
            while ($fetch_data = array_shift($res)) {
                $office_no = $fetch_data["variable_value"];
            }
            db();
            $res = db_query("Select phoneext,email from loop_employees where b2b_id = $account_owner");
            $office_no_ext = "";
            $supplier_email = "";
            while ($fetch_data = array_shift($res)) {
                $office_no_ext = $fetch_data["phoneext"];
                $supplier_email = $fetch_data["email"];
            }
            ?>
            <tr align="center">
                <td width="140px" align="left">UCB Toll Free number:</td>
                <td colspan="3" width="180px" align="left"><input type="text" name="tollfree_no" id="tollfree_no"
                        value="<?php echo $tollfree_no; ?>" />
                </td>
            </tr>
            <tr align="center">
                <td width="140px" align="left">UCB Office number:</td>
                <td colspan="3" width="180px" align="left"><input type="text" name="office_no" id="office_no"
                        value="<?php echo $office_no; ?>" />
                </td>
            </tr>
            <tr align="center">
                <td width="140px" align="left">UCB Office number ext.:</td>
                <td colspan="3" width="180px" align="left"><input type="text" name="office_ext" id="office_ext"
                        value="<?php echo $office_no_ext; ?>" disabled style="background:gray;" />
                </td>
            </tr>
            <tr align="center">
                <td width="140px" align="left">Email:</td>
                <td colspan="3" width="180px" align="left"><input size="40px;" type="text" name="supplier_email"
                        id="supplier_email" value="<?php echo $supplier_email; ?>" disabled style="background:gray;" />
                </td>
            </tr>
            <tr align="center">
                <td colspan="4" width="320px" align="left">
                    <input type="submit" name="btndetailsupd" id="btndetailsupd" value="Update Details"
                        style="cursor:pointer;" />
                </td>
            </tr>

            <?php
            // for the column in the section list
            $qry_2 = "Select * From supplierdashboard_section_col_master order by section_col_id";
            //echo $qry_2;
            db();
            $res_2 = db_query($qry_2);
            $reccnt = tep_db_num_rows($res_2);
            if ($reccnt > 0) { ?>
                <tr align="center">
                    <td colspan="7">
                        <table style="font-family:Arial, Helvetica, sans-serif; font-size:12;">
                            <tr align="center">
                                <td colspan="2" width="180px" align="left" bgcolor="#E4D5D5">Column name</td>
                                <td width="100px" align="left" bgcolor="#E4D5D5">Display (Yes/No)</td>
                                <td width="40px" align="left" bgcolor="#E4D5D5">Edit</td>
                            </tr>
                        <?php }
                    while ($fetch_data_2 = array_shift($res_2)) {
                        $qry_3 = "Select * From supplierdashboard_section_col_details where companyid = $ID and section_col_id = " . $fetch_data_2["section_col_id"];
                        //echo $qry_3;
                        db();
                        $res_3 = db_query($qry_3);
                        $section_col_flg = 0;
                        while ($fetch_data_3 = array_shift($res_3)) {
                            $section_col_flg = $fetch_data_3["displayflg"];
                        }
                        ?>
                            <tr align="center">
                                <td colspan="2" width="180px" align="left"><?php echo $fetch_data_2["column_name"]; ?></td>
                                <td width="100px" align="left"><input type="checkbox" name="supplierdash_sec_col_flg"
                                        id="supplierdash_sec_col_flg<?php echo $fetch_data_2["section_col_id"]; ?>"
                                        <?php if ($section_col_flg == 1) {
                                            echo " checked ";
                                        } ?> />
                                </td>
                                <td width="40px" align="left"><input type="button" value="Edit"
                                        onclick="supplierdash_sec_col_edit(<?php echo $fetch_data_2["section_col_id"]; ?>,<?php echo $ID; ?>)" />
                                </td>
                            </tr>
                        <?php }
                    if ($reccnt > 0) { ?>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <hr />
                    </td>
                </tr>
            <?php }
            ?>

        </form>
        <!-- Contact us - support line section start -->
        <?php
        /***************************/
        /**Add field for contact us support line
		/**Done by Nayan
		/**Date : 22 Feb 2021
		/***************************/
        ?>
        <form method="post" name="supplierdash_contactus_support" action="supplierdashboard_edit_details.php">
            <input type="hidden" name="supplierdash_edituser_details_id" id="supplierdash_edituser_details_id"
                value="<?php echo $ID; ?>" />
            <input type="hidden" name="hdnContctSupportLine" value="1">
            <tr align="center">
                <td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">Contact Us - Support Lines.</td>
            </tr>
            <tr align="center">
                <td width="140px" align="left">Support Line</td>
                <td colspan="3" width="180px" align="left">
                    <textarea name="txtSprtLns"
                        style="margin: 0px; width: 328px; height: 148px;"><?php echo $cntSprtLns; ?></textarea>
                </td>
            </tr>
            <tr align="center">
                <td width="140px" align="left">&nbsp;</td>
                <td colspan="3" width="180px" align="left">
                    <input type="submit" name="btnupdtSupportLine" id="btnupdtSupportLine"
                        value="Update Support Line Details" style="cursor:pointer;" />
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <hr />
                </td>
            </tr>
        </form>
        <!-- Contact us - support line section end -->

        <tr align="center">
            <td colspan="7" width="320px" align="left">&nbsp;</td>
        </tr>
        <!-- Section list -->
        <form method="post" name="supplierdash_edituser_sec" action="supplierdashboard_edit_sec.php">
            <tr align="center">
                <td colspan="7" width="320px" align="left" bgcolor="#C1C1C1">Section list</td>
            </tr>
            <tr align="center">
                <td colspan="2" width="180px" align="left" bgcolor="#E4D5D5">Section name</td>
                <td width="100px" align="left" bgcolor="#E4D5D5">Activate/Deactivate</td>
                <td width="50px" align="left" bgcolor="#E4D5D5">Check Water transaction - and UnCheck - UCB transaction
                </td>
                <td width="40px" align="left" bgcolor="#E4D5D5">Edit</td>
                <td width="80px" align="left" bgcolor="#E4D5D5">Delete</td>
            </tr>
            <?php
            db();
            $res_fld = db_query("Select * from supplierdashboard_section_details_fld where companyid = $ID and field_name = 'UCB_WATER_TRANS'");
            $UCB_WATER_TRANS = "";
            while ($fetch_data_fld = array_shift($res_fld)) {
                $UCB_WATER_TRANS = $fetch_data_fld["field_value"];
            }

            $qry = "Select * From supplierdashboard_section_details inner join supplierdashboard_section_master on supplierdashboard_section_master.section_id = supplierdashboard_section_details.section_id where companyid = $ID and buyer_seller = $buyer_seller_flg ";
            //echo $qry;
            db();
            $res = db_query($qry);
            while ($fetch_data = array_shift($res)) {
            ?>
                <input type="hidden" name="section_id" id="section_id" value="<?php echo $fetch_data["section_id"]; ?>" />
                <input type="hidden" name="companyid_sec" id="companyid_sec" value="<?php echo $ID; ?>" />

                <tr align="center">
                    <td colspan="2" width="180px" align="left"><?php echo $fetch_data["section_name"]; ?></td>

                    <td width="100px" align="left"><input type="checkbox" name="supplierdash_sec_flg"
                            id="supplierdash_sec_flg<?php echo $fetch_data["section_id"]; ?>" <?php if ($fetch_data["activate_deactivate"] == 1) {
                                                                                                    echo " checked ";
                                                                                                } ?> />
                        <?php  //if ($fetch_data["section_id"] == 1) { 
                        ?>
                        <!-- <br>Carrier: <input type="textbox" name="supplierdash_carrier_nm" id="supplierdash_carrier_nm" size="10" value="<?php echo $supplierdash_carrier_nm_value; ?>" /> -->
                        <?php  //} 
                        ?>
                    </td>

                    <td width="50px" align="left">
                        <?php if ($fetch_data["section_name"] == "Request a Trailer WITH a Bill of Lading (BOL)") { ?>

                            <input type="checkbox" name="supplierdash_transcation_flg"
                                id="supplierdash_transcation_flg<?php echo $fetch_data["section_id"]; ?>" <?php if ($UCB_WATER_TRANS == 1) {
                                                                                                                echo " checked ";
                                                                                                            } ?> />
                        <?php  } ?>
                    </td>

                    <td width="40px" align="left"><input type="button" value="Edit"
                            onclick="supplierdash_sec_edit(<?php echo $fetch_data["section_id"]; ?>, <?php echo $ID; ?>)" />
                    </td>
                    <td width="80px" align="left"><input type="button" value="Delete"
                            onclick="supplierdash_sec_dele(<?php echo $fetch_data["section_id"]; ?>, <?php echo $ID; ?>)" />
                    </td>
                </tr>
                <?php
                if ($fetch_data["section_name"] == "Request a Trailer WITH a Bill of Lading (BOL)") {
                    // for the column in the section list
                    $sql_2 = "Select * From supplier_trailer_info where companyid = " . $ID;
                    db();
                    $result_2 = db_query($sql_2);
                    $rec_cnt = tep_db_num_rows($result_2);
                    if ($rec_cnt > 0) { ?>
                        <tr align="center">
                            <td colspan="7">
                                <table style="font-family:Arial, Helvetica, sans-serif; font-size:12;">
                                    <tr align="center">
                                        <td colspan="2" width="180px" align="left" bgcolor="#E4D5D5">Input name</td>
                                        <td width="100px" align="left" bgcolor="#E4D5D5">Display (Yes/No)</td>
                                        <td width="40px" align="left" bgcolor="#E4D5D5">Edit</td>
                                        <td width="80px" align="left" bgcolor="#E4D5D5">Delete</td>
                                    </tr>
                                <?php }
                            while ($myrow = array_shift($result_2)) {
                                $item_info_flg = $myrow["displayflg"];
                                ?>
                                    <tr align="center">
                                        <td colspan="2" width="180px" align="left"><?php echo $myrow["item"]; ?></td>
                                        <td width="100px" align="left"><input type="checkbox" name="supplierdash_trailer_info_flg"
                                                id="supplierdash_trailer_info_flg<?php echo $myrow["id"]; ?>"
                                                <?php if ($item_info_flg == 1) {
                                                    echo " checked ";
                                                } ?> />
                                        </td>
                                        <td width="40px" align="left"><input type="button" value="Edit"
                                                onclick="supplierdash_trailer_sec_col_edit(<?php echo $myrow["id"]; ?>,<?php echo $ID; ?>)" />
                                        </td>
                                    </tr>

                                <?php }
                            if ($rec_cnt > 0) { ?>
                                </table>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="6">
                            <hr />
                        </td>
                    </tr>

                    <?php
                    // for the Commodity master
                    $sql_2 = "Select * From supplier_commodity_details where companyid = " . $ID . " order by id";
                    db();
                    $result_2 = db_query($sql_2);
                    $rec_cnt = tep_db_num_rows($result_2);

                    if ($rec_cnt > 0) { ?>
                        <tr align="center">
                            <td colspan="8">
                                <div style="font-weight: bold">WATER Dashboard Pickup Request Setup Table</div>
                            </td>
                        </tr>
                        <tr align="left">
                            <td colspan="8">
                                <div style="font-weight: bold">Active WATER Dashboard Pickup Request Options</div>
                            </td>
                        </tr>
                        <div id="light_reminder" class="white_content_reminder"></div>
                        <span id="viewdiv" />
                        <tr align="center">
                            <td colspan="8">
                                <table style="font-family:Arial, Helvetica, sans-serif; font-size:12;" width="100%"
                                    class="border_1px">
                                    <tr align="center">
                                        <td width="60px" align="left" bgcolor="#E4D5D5">Commodity, Vendor and Container Name</td>
                                        <td width="60px" align="left" bgcolor="#E4D5D5">Is it a WATER Pick Up? Flag for Yes.</td>
                                        <td width="60px" align="left" bgcolor="#E4D5D5">Need to Send a Reminder?</td>
                                        <td width="60px" align="left" bgcolor="#E4D5D5">Waste Stream</td>
                                        <td width="60px" align="left" bgcolor="#E4D5D5">BOL format</td>
                                        <td width="60px" align="left" bgcolor="#E4D5D5">Dock</td>
                                        <td width="60px" align="left" bgcolor="#E4D5D5">Carrier</td>
                                        <td width="60px" align="left" bgcolor="#E4D5D5">Warehouse</td>
                                        <td width="60px" align="left" bgcolor="#E4D5D5">Add 1</td>
                                        <td width="60px" align="left" bgcolor="#E4D5D5">Add 2</td>
                                        <td width="60px" align="left" bgcolor="#E4D5D5">City</td>
                                        <td width="60px" align="left" bgcolor="#E4D5D5">State</td>
                                        <td width="60px" align="left" bgcolor="#E4D5D5">Zip</td>
                                        <td width="60px" align="left" bgcolor="#E4D5D5">Phone</td>
                                        <td width="60px" align="left" bgcolor="#E4D5D5">Email</td>
                                        <td width="80px" align="left" bgcolor="#E4D5D5">Test Email</td> <!-- New Column Header -->
                                        <td width="80px" align="left" bgcolor="#E4D5D5">Edit</td>
                                        <td width="80px" align="left" bgcolor="#E4D5D5">Delete</td>
                                    </tr>
                                <?php }
                            while ($myrow = array_shift($result_2)) {
                                $item_info_flg = 1;
                                $comm_id = $myrow['id']; // Get the commodity ID
                                db();
                                $sql_check_email = "SELECT email_sent, email_sendon FROM water_supplier_email WHERE commodity_id = " . $comm_id;
                                $result_email_check = db_query($sql_check_email);
                                $email_check_row = array_shift($result_email_check);

                                // Prepare variables for display
                                $test_email_text = "Email Not Checked"; // Default message
                                $email_sent_time = ""; // To store the sent time if email was sent

                                // Check if the email was sent
                                if ($email_check_row) {
                                    if ($email_check_row['email_sent']) {
                                        $test_email_text = "Email Checked: " . date("Y-m-d H:i:s", strtotime($email_check_row['email_sendon']));
                                        $email_sent_time = $email_check_row['email_sendon'];
                                    }
                                }
                                ?>
                                    <tr align="center">
                                        <td width="60px" align="left"><?php echo $myrow["commodity"]; ?></td>

                                        <td width="60px" align="left"><?php if ($myrow["water_pick_up"] == 1) {
                                                                            echo "Yes";
                                                                        } else {
                                                                            echo "No";
                                                                        } ?></td>
                                        <td width="60px" align="left"><?php echo $myrow["send_a_reminder"] == 1 ? "Yes" : "No"; ?>
                                        </td>
                                        <td width="60px" align="left"><?php echo $myrow["waste_stream"]; ?></td>
                                        <td width="60px" align="left">
                                            <?php
                                            if ($myrow["bol_format"] == 'Southbend') {
                                                echo "Mauser Packaging";
                                            } elseif ($myrow["bol_format"] == 'califiamauser') {
                                                echo "Califia-Mauser";
                                            } else {
                                                echo $myrow["bol_format"];
                                            }
                                            ?>
                                        </td>

                                        <td width="60px" align="left"><?php echo $myrow["dock_value"]; ?></td>
                                        <td width="60px" align="left"><?php echo $myrow["carrier_value"]; ?></td>
                                        <td width="60px" align="left"><?php echo $myrow["shipto_warehouse"]; ?></td>
                                        <td width="60px" align="left"><?php echo $myrow["shipto_address_one"]; ?></td>
                                        <td width="60px" align="left"><?php echo $myrow["shipto_address_two"]; ?></td>
                                        <td width="60px" align="left"><?php echo $myrow["shipto_city"]; ?></td>
                                        <td width="60px" align="left"><?php echo $myrow["shipto_state"]; ?></td>
                                        <td width="60px" align="left"><?php echo $myrow["shipto_zip"]; ?></td>
                                        <td width="60px" align="left"><?php echo $myrow["phone"]; ?></td>
                                        <td width="60px" align="left"><?php echo $myrow["email_address"]; ?></td>
                                        <td width="80px" align="left">


                                            <input type="button" value="Test" id="btnposendemail"
                                                onclick="parent.reminder_popup_set_new(
           <?php echo $myrow['id']; ?>, 
           '<?php echo $myrow['email_address']; ?>', 
           '<?php echo $_REQUEST['ID']; ?>'
       );" />
                                            <div style="color:green"><?php echo $test_email_text; ?></div>


                                        </td>
                                        <td width="80px" align="left"><input type="button" id="btn_edit" name="btn_edit"
                                                value="Edit"
                                                onclick="supplierdash_commodity_sec_edit(<?php echo $myrow["id"]; ?>, <?php echo $ID; ?>)" />
                                        </td>
                                        <td width="80px" align="left"><input type="button" id="btn_del" value="Delete"
                                                onclick="supplierdash_commodity_sec_dele(<?php echo $myrow["id"]; ?>, <?php echo $ID; ?>)" />
                                        </td>
                                    </tr>

                                <?php }
                            if ($rec_cnt > 0) { ?>
                                </table>
                            </td>
                        </tr>
                    <?php } ?>
        </form>

        <?php if ($_REQUEST["flg"] == "edit_commodity") {
                        $sql_2 = "Select * From supplier_commodity_details where companyid = " . $_REQUEST["ID"] . " and id = " . $_REQUEST["sectionid"] . " order by id";
                        db();
                        $result_2 = db_query($sql_2);
                        while ($myrow = array_shift($result_2)) {
        ?>
                <tr>
                    <td colspan="5" align="left">
                        <b>Commodity Master - Edit</b>
                        <form method="post" action="supplierdashboard_edit_commodity.php" encType="multipart/form-data"
                            onsubmit="return validate_ctrl()">
                            <table width="100%" style="font-family:Arial, Helvetica, sans-serif; font-size:12;">
                                <tr>
                                    <td width="60px" align="left" bgcolor="#E4D5D5">Commodity, Vendor and Container Name</td>
                                    <td width="60px" align="left" bgcolor="#E4D5D5">Is it a WATER Pick Up? Flag for Yes.</td>
                                    <td width="60px" align="left" bgcolor="#E4D5D5">Need to Send a Reminder?</td>
                                    <td width="60px" align="left" bgcolor="#E4D5D5">Waste Stream</td>
                                    <td width="60px" align="left" bgcolor="#E4D5D5">BOL format</td>
                                    <td width="60px" align="left" bgcolor="#E4D5D5">Dock</td>
                                    <td width="60px" align="left" bgcolor="#E4D5D5">Carrier</td>
                                    <td width="60px" align="left" bgcolor="#E4D5D5">Warehouse</td>
                                    <td width="60px" align="left" bgcolor="#E4D5D5">Add 1</td>
                                    <td width="60px" align="left" bgcolor="#E4D5D5">Add 2</td>
                                    <td width="60px" align="left" bgcolor="#E4D5D5">City</td>
                                    <td width="60px" align="left" bgcolor="#E4D5D5">State</td>
                                    <td width="60px" align="left" bgcolor="#E4D5D5">Zip</td>
                                    <td width="60px" align="left" bgcolor="#E4D5D5">Phone</td>
                                    <td width="60px" align="left" bgcolor="#E4D5D5">Email</td>
                                    <td width="60px" align="left" bgcolor="#E4D5D5"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="hidden" id="companyid" name="companyid" value="<?php echo $ID; ?>">
                                        <input type="hidden" id="edit_commodity" name="edit_commodity"
                                            value="<?php echo $_REQUEST["sectionid"]; ?>">

                                        <input type="text" id="commodity_txt" name="commodity_txt"
                                            value="<?php echo $myrow["commodity"]; ?>" placeholder="Enter Commodity" autofocus>
                                    </td>
                                    <td>
                                        <input type="checkbox" id="chk_water_pick_up" name="chk_water_pick_up" value="1"
                                            <?php if ($myrow["water_pick_up"] == 1) {
                                                echo " checked ";
                                            } ?>>
                                    </td>
                                    <td>
                                        <select id="send_a_reminder" name="send_a_reminder">
                                            <option <?php echo $myrow['send_a_reminder'] != 0 ? " selected " : ""; ?> value='1'>
                                                Yes</option>
                                            <option <?php echo $myrow['send_a_reminder'] == 0 ? " selected " : ""; ?> value='0'>
                                                No</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="waste_stream" id="waste_stream">
                                            <option value=''>Select</option>
                                            <?php
                                            if ($myrow['waste_stream'] == 'Reuse') {
                                                $tmpvar = " selected ";
                                            } else {
                                                $tmpvar = " ";
                                            }
                                            echo "<option value='Reuse' $tmpvar >Reuse</option>";
                                            if ($myrow['waste_stream'] == 'Recycling') {
                                                $tmpvar = " selected ";
                                            } else {
                                                $tmpvar = " ";
                                            }
                                            echo "<option value='Recycling' $tmpvar >Recycling</option>";
                                            if ($myrow['waste_stream'] == 'Waste-to-Energy') {
                                                $tmpvar = " selected ";
                                            } else {
                                                $tmpvar = " ";
                                            }
                                            echo "<option value='Waste-to-Energy' $tmpvar >Waste-to-Energy</option>";
                                            if ($myrow['waste_stream'] == 'Incinaration') {
                                                $tmpvar = " selected ";
                                            } else {
                                                $tmpvar = " ";
                                            }
                                            echo "<option value='Incinaration' $tmpvar >Incinaration</option>";
                                            if ($myrow['waste_stream'] == 'Landfill') {
                                                $tmpvar = " selected ";
                                            } else {
                                                $tmpvar = " ";
                                            }
                                            echo "<option value='Landfill' $tmpvar >Landfill</option>";
                                            ?>
                                        </select>
                                    </td>
                                    <td>

                                        <select name="ddBOLFormat" id="ddBOLFormat">
                                            <?php
                                            if ($myrow['bol_format'] == 'Standard') {
                                                $tmpvar = " selected ";
                                            } else {
                                                $tmpvar = " ";
                                            }
                                            echo "<option value='Standard' $tmpvar >Standard</option>";
                                            if ($myrow['bol_format'] == 'Southbend') {
                                                $tmpvar = " selected ";
                                            } else {
                                                $tmpvar = " ";
                                            }
                                            echo "<option value='Southbend' $tmpvar >Mauser Packaging</option>";
                                            if ($myrow['bol_format'] == 'califiamauser') {
                                                $tmpvar = " selected ";
                                            } else {
                                                $tmpvar = " ";
                                            }
                                            echo "<option value='califiamauser' $tmpvar >Califia-Mauser</option>";
                                            if ($myrow['bol_format'] == 'Republic Services Manifest') {
                                                $tmpvar = " selected ";
                                            } else {
                                                $tmpvar = " ";
                                            }
                                            echo "<option value='Republic Services Manifest' $tmpvar >Republic Services Manifest</option>";

                                            ?></select>
                                    </td>
                                    <td>

                                        <input type="text" id="dock_txt" name="dock_txt"
                                            value="<?php echo $myrow["dock_value"]; ?>" placeholder="Enter Dock">
                                    </td>
                                    <td>
                                        <input type="text" id="carrier_value_txt" name="carrier_value_txt"
                                            value="<?php echo $myrow["carrier_value"]; ?>" placeholder="Enter Carrier">
                                    </td>
                                    <td>
                                        <input type="text" id="shipto_warehouse_txt" name="shipto_warehouse_txt"
                                            value="<?php echo $myrow["shipto_warehouse"]; ?>" placeholder="Enter Warehouse">
                                    </td>
                                    <td>
                                        <input type="text" id="shipto_address_one_txt" name="shipto_address_one_txt"
                                            value="<?php echo $myrow["shipto_address_one"]; ?>" placeholder="Enter Add 1">
                                    </td>
                                    <td>
                                        <input type="text" id="shipto_address_two_txt" name="shipto_address_two_txt"
                                            value="<?php echo $myrow["shipto_address_two"]; ?>" placeholder="Enter Add 2">
                                    </td>
                                    <td>
                                        <input type="text" id="shipto_city_txt" name="shipto_city_txt"
                                            value="<?php echo $myrow["shipto_city"]; ?>" placeholder="Enter City">
                                    </td>
                                    <td>
                                        <input type="text" id="shipto_state_txt" name="shipto_state_txt"
                                            value="<?php echo $myrow["shipto_state"]; ?>" placeholder="Enter State">
                                    </td>
                                    <td>
                                        <input type="text" id="shipto_zip_txt" name="shipto_zip_txt"
                                            value="<?php echo $myrow["shipto_zip"]; ?>" placeholder="Enter Zip">
                                    </td>
                                    <td>
                                        <input type="text" id="phone_txt" name="phone_txt"
                                            value="<?php echo $myrow["phone"]; ?>" placeholder="Enter Phone">
                                    </td>
                                    <td>
                                        <textarea id="email_address_txt"
                                            name="email_address_txt"><?php echo $myrow["email_address"]; ?></textarea>
                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                            <span class="tooltiptext">To add more than one email address, please separate emails
                                                by comma</span>
                                        </div>
                                    </td>
                                    <td>
                                        <input style="cursor:pointer;" type="submit" id="item" value="Save">

                                    </td>
                                </tr>
                            </table>
                        </form>
                    </td>
                </tr>

        <?php     }
                    }
        ?>
        <tr>
            <td colspan="8" align="left" class="border_1px">
                <b><br>Add new WATER Dashboard Pickup Request Options</b>
                <form method="post" action="supplierdashboard_add_commodity.php" encType="multipart/form-data"
                    onsubmit="return validate_ctrl()">
                    <table width="60%" style="font-family:Arial, Helvetica, sans-serif; font-size:12;"
                        class="border_1px">
                        <tr>
                            <td width="60px" align="left" bgcolor="#E4D5D5">Commodity, Vendor and Container Name</td>
                            <td width="20px" align="left" bgcolor="#E4D5D5">Is it a WATER Pick Up? Flag for Yes.</td>
                            <td width="60px" align="left" bgcolor="#E4D5D5">Need to Send a Reminder?</td>
                            <td width="60px" align="left" bgcolor="#E4D5D5">Waste Stream</td>
                            <td width="40px" align="left" bgcolor="#E4D5D5">BOL format</td>
                            <td width="20px" align="left" bgcolor="#E4D5D5">Dock</td>
                            <td width="40px" align="left" bgcolor="#E4D5D5">Carrier</td>
                            <td width="60px" align="left" bgcolor="#E4D5D5">Warehouse</td>
                            <td width="60px" align="left" bgcolor="#E4D5D5">Add 1</td>
                            <td width="30px" align="left" bgcolor="#E4D5D5">Add 2</td>
                            <td width="40px" align="left" bgcolor="#E4D5D5">City</td>
                            <td width="10px" align="left" bgcolor="#E4D5D5">State</td>
                            <td width="10px" align="left" bgcolor="#E4D5D5">Zip</td>
                            <td width="20px" align="left" bgcolor="#E4D5D5">Phone</td>
                            <td width="60px" align="left" bgcolor="#E4D5D5">Email</td>
                            <td width="10px" align="left" bgcolor="#E4D5D5"></td>
                        </tr>
                        <tr>
                            <td><input type="text" id="commodity_txt" name="commodity_txt" size="8" value=""
                                    placeholder="Enter Commodity"></td>
                            <td>
                                <input type="hidden" id="companyid" name="companyid" value="<?php echo $ID; ?>">
                                <input type="checkbox" id="chk_water_pick_up" name="chk_water_pick_up" value="1">
                            </td>
                            <td>
                                <select id="send_a_reminder" name="send_a_reminder">
                                    <option value='1'>Yes</option>
                                    <option value='0'>No</option>
                                </select>
                            </td>
                            <td>
                                <select name="waste_stream" id="waste_stream">
                                    <option value=''>Select</option>
                                    <?php
                                    $tmpvar = "";
                                    echo "<option value='Reuse' $tmpvar >Reuse</option>";
                                    echo "<option value='Recycling' $tmpvar >Recycling</option>";
                                    echo "<option value='Waste-to-Energy' $tmpvar >Waste-to-Energy</option>";
                                    echo "<option value='Incinaration' $tmpvar >Incinaration</option>";
                                    echo "<option value='Landfill' $tmpvar >Landfill</option>";
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select name="ddBOLFormat" id="ddBOLFormat">
                                    <option value="Standard">Standard</option>
                                    <option value="Southbend">Mauser Packaging</option>
                                    <option value="califiamauser">Califia-Mauser</option>
                                    <option value="Republic Services Manifest">Republic Services Manifest</option>

                                </select>
                            </td>
                            <td>
                                <input type="text" id="dock_txt" name="dock_txt" value="" size="2"
                                    placeholder="Enter Dock">
                            </td>
                            <td>
                                <input type="text" id="carrier_value_txt" name="carrier_value_txt" value="" size="8"
                                    placeholder="Enter Carrier">
                            </td>
                            <td>
                                <input type="text" id="shipto_warehouse_txt" name="shipto_warehouse_txt" size="8"
                                    value="" placeholder="Enter Warehouse">
                            </td>
                            <td>
                                <input type="text" id="shipto_address_one_txt" name="shipto_address_one_txt" size="8"
                                    value="" placeholder="Enter Add 1">
                            </td>
                            <td>
                                <input type="text" id="shipto_address_two_txt" name="shipto_address_two_txt" size="8"
                                    value="" placeholder="Enter Add 2">
                            </td>
                            <td>
                                <input type="text" id="shipto_city_txt" name="shipto_city_txt" value="" size="10"
                                    placeholder="Enter City">
                            </td>
                            <td>
                                <input type="text" id="shipto_state_txt" name="shipto_state_txt" value="" size="2"
                                    placeholder="Enter State">
                            </td>
                            <td>
                                <input type="text" id="shipto_zip_txt" name="shipto_zip_txt" value="" size="2"
                                    placeholder="Enter Zip">
                            </td>
                            <td>
                                <input type="text" id="phone_txt" name="phone_txt" value="" size="8"
                                    placeholder="Enter Phone">
                            </td>
                            <td>
                                <textarea id="email_address_txt"
                                    name="email_address_txt"><?php echo $myrow["email_address"]; ?></textarea>

                                <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                    <span class="tooltiptext">To add more than one email address, please separate emails
                                        by comma</span>
                                </div>
                            </td>
                            <td>
                                <input style="cursor:pointer;" type="submit" id="item" value="Add item">
                            </td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>

        <tr>
            <td colspan="6">
                <hr />
            </td>
        </tr>

        <?php }


                if ($fetch_data["section_name"] == "Item Information") {
                    // for the column in the section list
                    $sql_2 = "Select * From supplier_item_info where companyid = " . $ID;
                    db();
                    $result_2 = db_query($sql_2);
                    $rec_cnt = tep_db_num_rows($result_2);
                    if ($rec_cnt > 0) { ?>
            <tr align="center">
                <td colspan="7">
                    <table style="font-family:Arial, Helvetica, sans-serif; font-size:12;" class="border_1px">
                        <tr align="center">
                            <td colspan="2" width="180px" align="left" bgcolor="#E4D5D5">Column name</td>
                            <td width="100px" align="left" bgcolor="#E4D5D5">Display (Yes/No)</td>
                            <td width="40px" align="left" bgcolor="#E4D5D5">Edit</td>
                            <td width="80px" align="left" bgcolor="#E4D5D5">Delete</td>
                        </tr>
                    <?php }
                    while ($myrow = array_shift($result_2)) {
                        $item_info_flg = $myrow["displayflg"];
                    ?>
                        <tr align="center">
                            <td colspan="2" width="180px" align="left"><?php echo $myrow["item"]; ?></td>
                            <td width="100px" align="left"><input type="checkbox" name="supplierdash_item_info_flg"
                                    id="supplierdash_item_info_flg<?php echo $myrow["id"]; ?>"
                                    <?php if ($item_info_flg == 1) {
                                        echo " checked ";
                                    } ?> />
                            </td>
                            <td width="40px" align="left"><input type="button" value="Edit"
                                    onclick="supplierdash_item_sec_col_edit(<?php echo $myrow["id"]; ?>,<?php echo $ID; ?>)" />
                            </td>
                            <td width="80px" align="left"><input type="button" value="Delete"
                                    onclick="supplierdash_item_sec_dele(<?php echo $myrow["id"]; ?>, <?php echo $ID; ?>)" />
                            </td>
                        </tr>

                    <?php }
                    if ($rec_cnt > 0) { ?>
                    </table>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="5" align="center">
                <form method="post" action="supplierdashboard_add_item.php" encType="multipart/form-data">
                    <table>
                        <tr>
                            <td>
                                <input type="hidden" id="companyid" name="companyid" value="<?php echo $ID; ?>">
                                <input type="text" id="item_info_txt" name="item_info_txt" value="">
                                <input style="cursor:pointer;" type="submit" id="item" value="Add item">
                            </td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>

        <tr>
            <td colspan="6">
                <hr />
            </td>
        </tr>
    <?php } ?>

<?php }
?>
<tr>
    <td colspan="6">
        <hr />
    </td>
</tr>

<tr align="center">
    <td colspan="7" width="320px" align="left" bgcolor="#C1C1C1">
        <font size="2" face="Arial">File list</font>
    </td>
</tr>

<tr>
    <td colspan="7">
        <form METHOD="POST" ENCTYPE="multipart/form-data" action="upload_files_supplier.php">
            <TABLE ALIGN='LEFT' border="0" width="500" bgcolor="#F6F8E5">
                <tr align="left">
                    <td colSpan="6">
                        <font size="2" face="Arial">Notes: </font><input type="text" size="40" name="file_memo"
                            id="file_memo" />
                        <div style="height:8px;">&nbsp;</div>
                        <input type="file" size="40" name="file" id="file" />
                        <input type="submit" name="upload_file" value="Upload" style="cursor:pointer;">
                        <input type="hidden" id="warehouse_id" name="warehouse_id"
                            value="<?php echo $supplier_loopid; ?>">
                        <input type="hidden" id="companyid" name="companyid"
                            value="<?php echo $_REQUEST['ID']; ?>">
                    </td>
                </tr>
                <tr>
                    <td colSpan="5"></td>
                </tr>
            </table>
        </form>
    </td>
</tr>

<tr>
    <td colspan="6">
        <hr />
    </td>
</tr>

<tr align="center">
    <td colspan="7">
        <?php
        $qry_2 = "select * from loop_files where warehouse_id=" . $supplier_loopid . " order by date desc";
        //echo $qry_2;
        db();
        $res_2 = db_query($qry_2);
        $reccnt = tep_db_num_rows($res_2);
        if ($reccnt > 0) { ?>
            <!--<div style="height:200px; overflow-y: scroll;">-->
            <table style="font-family:Arial, Helvetica, sans-serif; font-size:12;" class="border_1px">
                <tr align="center">
                    <td colspan="2" width="180px" align="left" bgcolor="#E4D5D5">Notes</td>
                    <td width="100px" align="left" bgcolor="#E4D5D5">File name</td>
                    <td width="80px" align="left" bgcolor="#E4D5D5">Delete</td>
                </tr>
                <?php
                while ($fetch = array_shift($res_2)) {
                ?>
                    <tr align="center">
                        <td colspan="2" width="180px" align="left"><?php echo $fetch["memo"]; ?></td>
                        <td width="40px" align="left"><?php echo $fetch["filename"]; ?></td>
                        <td width="80px" align="left"><input type="button" value="Delete"
                                onclick="supplierdash_file_dele(<?php echo $fetch["id"] ?>,<?php echo $ID; ?>,<?php echo $supplier_loopid; ?>)" />
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <!--</div>-->
        <?php } ?>
    </td>
</tr>

<tr>
    <td colspan="6">
        <hr />
    </td>
</tr>
<tr align="center">
    <td colspan="7" width="320px" align="left" bgcolor="#C1C1C1">
        <font size="2" face="Arial"> Profile File list</font>
    </td>
</tr>

<tr>
    <td colspan="7">
        <form METHOD="POST" ENCTYPE="multipart/form-data" id="profile_frm" name="profile_frm"
            action="upload_profile_file.php">
            <TABLE ALIGN='LEFT' border="0" width="500" bgcolor="#F6F8E5">
                <tr align="left">
                    <td colSpan="6">
                        <font size="2" face="Arial">Name of file: </font>
                        <input type="text" size="40" name="profileFileName" id="profileFileName" />

                        <br>
                        <input type="text" name="profile_start_date" id="profile_start_date" size="8" value="">
                        <a href="#"
                            onclick="calstdate.select(document.profile_frm.profile_start_date,'st_profile','MM-dd-yyyy'); return false;"
                            name="st_profile" id="st_profile">
                            <img border="0" src="images/calendar.jpg"></a>

                        <br>
                        <input type="text" name="profile_end_date" id="profile_end_date" size="8" value="">
                        <a href="#"
                            onclick="calenddate.select(document.profile_frm.profile_end_date,'en_profile','MM-dd-yyyy'); return false;"
                            name="en_profile" id="en_profile">
                            <img border="0" src="images/calendar.jpg"></a>
                        <div ID="listdiv_dt"
                            STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                        </div>

                        <div style="height:8px;">&nbsp;</div>
                        <input type="file" size="40" name="Profilefile" id="Profilefile"
                            onchange="GetProfileFileSize()" />
                        <input type="submit" name="uploadProfile" value="Upload" style="cursor:pointer;">
                        <input type="hidden" id="hdWarehouseId" name="hdWarehouseId"
                            value="<?php echo $supplier_loopid; ?>">
                        <input type="hidden" id="hdnCompanyId" name="hdnCompanyId"
                            value="<?php echo $_REQUEST['ID']; ?>">
                    </td>
                </tr>
                <tr>
                    <td colSpan="5"></td>
                </tr>
            </table>
        </form>
    </td>
</tr>
<?php
$qrySelProfListDt = "SELECT  * FROM  profile_files WHERE warehouse_id=" . $supplier_loopid . " order by profile_file_date desc";
db();
$resSelProfListDt = db_query($qrySelProfListDt);
if (tep_db_num_rows($resSelProfListDt) > 0) {
?>
    <tr>
        <td colspan="7">
            <table align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12;"
                class="border_1px">
                <tr align="center">
                    <td colspan="2" width="180px" align="left" bgcolor="#E4D5D5">Name of file</td>
                    <td width="100px" align="left" bgcolor="#E4D5D5">Uploaded files</td>
                    <td width="100px" align="left" bgcolor="#E4D5D5">Start Date</td>
                    <td width="100px" align="left" bgcolor="#E4D5D5">End Date</td>
                    <td width="100px" align="left" bgcolor="#E4D5D5">Delete</td>
                </tr>
                <?php while ($rowSelProfListDt = array_shift($resSelProfListDt)) {

                    $profile_start_date = "";
                    if ($rowSelProfListDt['profile_start_date'] != "0000-00-00" && $rowSelProfListDt['profile_start_date'] != "") {
                        $profile_start_date = date("m/d/Y", strtotime($rowSelProfListDt['profile_start_date']));
                    }

                    $profile_end_date = "";
                    if ($rowSelProfListDt['profile_end_date'] != "0000-00-00" && $rowSelProfListDt['profile_end_date'] != "") {
                        $profile_end_date = date("m/d/Y", strtotime($rowSelProfListDt['profile_end_date']));
                    }
                ?>
                    <tr align="center">
                        <td colspan="2" width="180px" align="left">
                            <?php echo  $rowSelProfListDt['profile_file_name']; ?></td>
                        <td width="100px" align="left"><?php echo  $rowSelProfListDt['profile_files_image']; ?></td>
                        <td width="50px" align="left"><?php echo  $profile_start_date; ?></td>
                        <td width="50px" align="left"><?php echo  $profile_end_date; ?></td>
                        <td width="80px" align="left"><input type="button" value="Delete"
                                onclick="proFileListDel(<?php echo  $rowSelProfListDt['id']; ?>)" /></td>
                    </tr>
                <?php  } ?>
            </table>
        </td>
    </tr>
<?php  } ?>
<tr>
    <td colspan="6">
        <hr />
    </td>
</tr>
<tr align="center">
    <td colspan="7" width="320px" align="left" bgcolor="#C1C1C1">
        <font size="2" face="Arial"> ZERO-WASTE PROGRAM BINS AND CONTAINERS LABELS</font>
    </td>
</tr>
<tr>
    <td colspan="7">
        <form METHOD="POST" ENCTYPE="multipart/form-data" action="save_zw_program_bins.php">
            <table width="100%" align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:12;">
                <tr align="center">
                    <td align="left" bgcolor="#E4D5D5">Primary Commodity Name</td>
                    <td align="left" bgcolor="#E4D5D5">Secondary Commodity Name</td>
                    <td align="left" bgcolor="#E4D5D5">Label outline and font color</td>
                    <td align="left" bgcolor="#E4D5D5">Picture 1 accepted commodity</td>
                    <td align="left" bgcolor="#E4D5D5">Picture 2 non-accepted commodity</td>
                    <td align="left" bgcolor="#E4D5D5">Notes 1</td>
                    <td align="left" bgcolor="#E4D5D5">Notes 2</td>
                    <td align="left" bgcolor="#E4D5D5">&nbsp;</td>
                </tr>
                <tr align="center">
                    <td align="left" bgcolor="#C1C1C1"><input type="text" name="txtPriCommodityNm"
                            id="txtPriCommodityNm" /></td>
                    <td align="left" bgcolor="#C1C1C1"><input type="text" name="txtSecCommodityNm"
                            id="txtSecCommodityNm" /></td>
                    <td align="left" bgcolor="#C1C1C1"><input type="color" name="clrFontColor" id="clrFontColor"
                            value="#ff0000"></td>
                    <td align="left" bgcolor="#C1C1C1"><input type="file" name="imgAcceptComm"
                            id="imgAcceptComm" onchange="GetCommodityFileSize()" /></td>
                    <td align="left" bgcolor="#C1C1C1"><input type="file" name="imgNonAcceptComm"
                            id="imgNonAcceptComm" onchange="GetCommodityFileSize()" /></td>
                    <td align="left" bgcolor="#C1C1C1"><input type="text" name="txtNotes" id="txtNotes" /></td>
                    <td align="left" bgcolor="#C1C1C1"><input type="text" name="txtNotes2" id="txtNotes2" />
                    </td>
                    <td align="left" bgcolor="#C1C1C1"><input type="submit" name="uploadProfile"
                            value="Add Item" style="cursor:pointer;" onclick="return chkSubmitdtls();">
                        <input type="hidden" id="hdnWarehouseId" name="hdnWarehouseId"
                            value="<?php echo $supplier_loopid; ?>">
                        <input type="hidden" id="hdnCompanyId" name="hdnCompanyId"
                            value="<?php echo $_REQUEST['ID']; ?>">
                    </td>
                </tr>
                <?php if (isset($_REQUEST['flgZw']) && $_REQUEST['flgZw'] == 'edit_zw_commodity') {
                    $qryZwCommodityBinsDt = "SELECT * FROM zw_program_bins_labels WHERE company_id = " . $_REQUEST["ID"] . " AND id = " . $_REQUEST["sectionid"] . " AND warehouse_id ='" . $_REQUEST['warehouseId'] . "'";
                    //echo "<br /> <br /> ". $qryZwCommodityBinsDt;
                    db();
                    $resZwCommodityBinsDt = db_query($qryZwCommodityBinsDt);
                    while ($rowZwCommodityBinsDt = array_shift($resZwCommodityBinsDt)) {
                ?>
                        <tr align="center">
                            <td align="left" bgcolor="#C1C1C1"><input type="text" name="txtPriCommodityNm"
                                    id="txtPriCommodityNm"
                                    value="<?php echo  $rowZwCommodityBinsDt['pri_commodity_name']; ?>" /></td>
                            <td align="left" bgcolor="#C1C1C1"><input type="text" name="txtSecCommodityNm"
                                    id="txtSecCommodityNm"
                                    value="<?php echo  $rowZwCommodityBinsDt['sec_commodity_name']; ?>" /></td>
                            <td align="left" bgcolor="#C1C1C1"><input type="color" name="clrFontColor" id="clrFontColor"
                                    value="<?php echo $rowZwCommodityBinsDt['font_color']; ?>"></td>
                            <td align="left" bgcolor="#C1C1C1"><input type="file" name="imgAcceptComm"
                                    id="imgAcceptComm" onchange="GetCommodityFileSize()" /></td>
                            <td align="left" bgcolor="#C1C1C1"><input type="file" name="imgNonAcceptComm"
                                    id="imgNonAcceptComm" onchange="GetCommodityFileSize()" /></td>
                            <td align="left" bgcolor="#C1C1C1"><input type="text" name="txtNotes" id="txtNotes"
                                    value="<?php echo  $rowZwCommodityBinsDt['notes']; ?>" /></td>
                            <td align="left" bgcolor="#C1C1C1"><input type="text" name="txtNotes2" id="txtNotes2"
                                    value="<?php echo  $rowZwCommodityBinsDt['notes2']; ?>" /></td>
                            <td align="left" bgcolor="#C1C1C1"><input type="submit" name="uploadProfile"
                                    value="Edit Item" style="cursor:pointer;">
                                <input type="hidden" id="hdnWarehouseId" name="hdnWarehouseId"
                                    value="<?php echo $supplier_loopid; ?>">
                                <input type="hidden" id="hdnCompanyId" name="hdnCompanyId"
                                    value="<?php echo $_REQUEST['ID']; ?>">
                                <input type="hidden" id="action" name="action" value="zwCommodityEdit">
                                <input type="hidden" id="editId" name="editId"
                                    value="<?php echo $_REQUEST['sectionid']; ?>">
                            </td>
                        </tr>
                <?php
                    }
                } ?>
            </table>
        </form>
    </td>
</tr>
<tr align="center">
    <td colspan="7" width="320px" align="left" bgcolor="#C1C1C1">
        <font size="2" face="Arial"> ZERO-WASTE PROGRAM BINS AND CONTAINERS LABELS LIST</font>
    </td>
</tr>
<tr>
    <td colspan="7">
        <form METHOD="POST" ENCTYPE="multipart/form-data" action="save_zw_program_bins.php">
            <table width="100%" align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:12;">
                <tr align="center">
                    <td align="left" bgcolor="#E4D5D5">Primary Commodity Name</td>
                    <td align="left" bgcolor="#E4D5D5">Secondary Commodity Name</td>
                    <td align="left" bgcolor="#E4D5D5" width="110px;">Label outline and font color</td>
                    <td align="left" bgcolor="#E4D5D5">Picture 1 (accepted commodities)</td>
                    <td align="left" bgcolor="#E4D5D5">Picture 2 (non-accepted commodities)</td>
                    <td align="left" bgcolor="#E4D5D5">Notes 1</td>
                    <td align="left" bgcolor="#E4D5D5">Notes 2</td>
                    <td align="left" bgcolor="#E4D5D5">Download PDF</td>
                    <td align="left" bgcolor="#E4D5D5">&nbsp;</td>
                </tr>
                <?php
                $qryZwCommodityBinsData = "SELECT  * FROM  zw_program_bins_labels WHERE warehouse_id =" . $supplier_loopid . " ORDER BY id DESC";
                db();
                $resZwCommodityBinsData = db_query($qryZwCommodityBinsData);
                if (tep_db_num_rows($resZwCommodityBinsData) > 0) {
                    while ($rowsZwCommodityBinsData = array_shift($resZwCommodityBinsData)) {
                ?>
                        <tr align="center">
                            <td align="left" bgcolor="#E4D5D5">
                                <?php echo  $rowsZwCommodityBinsData['pri_commodity_name']; ?></td>
                            <td align="left" bgcolor="#E4D5D5">
                                <?php echo  $rowsZwCommodityBinsData['sec_commodity_name']; ?></td>
                            <td align="left" bgcolor="#E4D5D5">

                                <div
                                    style="width: 10px; height: 10px; border-radius: 50%; border: 1px solid; background-color: <?php echo $rowsZwCommodityBinsData['font_color']; ?>;">
                                    <span style="margin-left: 15px; display: inline-block;   width: 150px;"> Selected
                                        color </span>
                                </div>
                            </td>
                            <td align="left" bgcolor="#E4D5D5">
                                <?php if ($rowsZwCommodityBinsData['accepted_commodity_img'] != '') { ?>
                                    <a href="commoditi_pictures/accepted/<?php echo $rowsZwCommodityBinsData['accepted_commodity_img']; ?>"
                                        download> file attached </a>
                                <?php  } ?>
                            </td>
                            <td align="left" bgcolor="#E4D5D5">
                                <?php if ($rowsZwCommodityBinsData['non_accepted_commodity_img'] != '') { ?>
                                    <a href="commoditi_pictures/non_accepted/<?php echo $rowsZwCommodityBinsData['non_accepted_commodity_img']; ?>"
                                        download> file attached </a>
                                <?php  } ?>
                            </td>
                            <td align="left" bgcolor="#E4D5D5"><?php echo  $rowsZwCommodityBinsData['notes']; ?></td>
                            <td align="left" bgcolor="#E4D5D5"><?php echo  $rowsZwCommodityBinsData['notes2']; ?></td>

                            <td align="left" bgcolor="#E4D5D5">
                                <a target="_blank"
                                    href="commoditi_pictures/zw_pdfs/<?php echo $rowsZwCommodityBinsData['zw_label_pdf']; ?>">Download</a>
                            </td>

                            <td align="left" bgcolor="#E4D5D5"><input type="button" value="Edit"
                                    onclick="zwCommodityEdit(<?php echo  $rowsZwCommodityBinsData['id']; ?>, <?php echo $_REQUEST['ID']; ?>, <?php echo $supplier_loopid; ?>  );" />&nbsp;&nbsp;<input
                                    type="button" value="Delete"
                                    onclick="zwCommodityDel(<?php echo  $rowsZwCommodityBinsData['id']; ?>)" /></td>
                        </tr>
                <?php
                    }
                }
                ?>
            </table>
        </form>
    </td>
</tr>
<!--
		<tr align="center">
			<td colspan="6" width="320px" align="left" bgcolor="#C1C1C1"><font size="2" face="Arial">OBM file list</font></td>
		</tr>
		
		<tr>
			<td colspan="6">
				<form METHOD="POST" ENCTYPE="multipart/form-data" action="upload_obm_files_supplier.php" >
					<TABLE ALIGN='LEFT' border="0" width="500" bgcolor="#F6F8E5">	
						<tr align="left">
							<td colSpan="6">
								<font size="2" face="Arial">Notes: </font><input type="text" size="40" name="file_memo" id="file_memo"/>
								<div style="height:8px;">&nbsp;</div>
								<input type="file" size="40" name="obm_file" id="obm_file"/>
								<input type="submit" name="upload_obm_file" value="Upload" style="cursor:pointer;">
								<input type="hidden" id="warehouse_id" name="warehouse_id" value="<?php //echo $supplier_loopid;
                                                                                                    ?>">
								<input type="hidden" id="companyid" name="companyid" value="<?php //echo $_REQUEST['ID'];
                                                                                            ?>">
							</td>
						</tr>
						<tr>
							<td colSpan="5"></td>
						</tr>
					</table>
				</form>
			</td>
		</tr>
		
		<tr align="center">
			<td colspan="6" >
				<?php
                /*$qry_2 ="Select * From supplier_obm";
					$res_2 = db_query($qry_2 , db());
					$reccnt = tep_db_num_rows($res_2);
					if ($reccnt > 0) 
					{*/ ?>
						<table style="font-family:Arial, Helvetica, sans-serif; font-size:12;">
							<tr align="center">
								<td colspan="2" width="180px" align="left" bgcolor="#E4D5D5" >Notes</td>
								<td width="100px" align="left" bgcolor="#E4D5D5">File name</td>
								<td width="80px" align="left" bgcolor="#E4D5D5">Delete</td>
							</tr>
							<?php
                            /*while($fetch = array_shift($res_2))
							{*/
                            ?>
								<tr align="center">
									<td colspan="2" width="180px" align="left" ><?php  //echo $fetch["obm_memo"]; 
                                                                                ?></td>
									<td width="40px" align="left"><?php  //echo $fetch["obm_filename"];
                                                                    ?></td>
									<td width="80px" align="left"><input type="button" value="Delete" onclick="supplierdash_obm_dele(<?php  //echo $fetch["unqid"]
                                                                                                                                        ?>,<?php  //echo $ID;
                                                                                                                                            ?>)" /></td>
								</tr>
						  <?php //}
                            ?>
						</table>
				  <?php //}
                    ?>	
			</td>
		</tr>-->
    </table>
</body>

</html>