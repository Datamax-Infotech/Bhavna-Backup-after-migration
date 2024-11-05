<?php
require("inc/header_session.php");
require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

?>

<script>
    function btnsendemlclick_eml_p() {
        var tmp_element1, tmp_element2, tmp_element3, tmp_element4, tmp_element5;

        tmp_element1 = document.getElementById("txtemailto").value;

        tmp_element2 = document.getElementById("email_reminder_sch_p");

        tmp_element3 = document.getElementById("txtemailcc").value;

        tmp_element4 = document.getElementById("txtemailsubject").value;

        tmp_element5 = document.getElementById("hidden_reply_eml");

        if (tmp_element1.value == "") {
            alert("Please enter the To Email address.");
            return false;
        }

        if (tmp_element4.value == "") {
            alert("Please enter the Email Subject.");
            return false;
        }

        if (tmp_element3.value == "") {
            alert("Please enter the Cc Email address.");
            return false;
        }


        var inst = FCKeditorAPI.GetInstance("txtemailbody");
        var emailtext = inst.GetHTML();

        tmp_element5.value = emailtext;
        //alert(tmp_element5.value);
        document.getElementById("hidden_sendemail").value = "inemailmode";
        tmp_element2.submit();

    }
</script>

<?php


db();
$po_employee = "";







$eml_confirmation = "<html><head><style>@import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap'); *{ font-family: \"Roboto\", sans-serif;}</style></head><body bgcolor=\"#ffffff\" style=\"margin:0 auto;\">";
$eml_confirmation .= "<table align=\"center\" cellpadding=\"0\" width=\"980px\">";
$eml_confirmation .= "<tr><td colspan=\"2\"><p align=\"left\"><img style=\"height:70px;width:120px;\" src=\"https://www.ucbzerowaste.com/images/UCBZeroWaste_logo_small.png\"></p>";
$eml_confirmation .= "</td></tr><tr><td width=\"35\" valign=\"top\" rowspan=\"4\"><p> </p></td>";
$eml_confirmation .= "<td><p align=\"left\"><font face=\"arial\" size=\"2\">";
$eml_confirmation .= "</font></td></tr><tr><td><p align=\"left\"><font face=\"arial\" size=\"2\">";
// $eml_confirmation .= "Dear Vendor,<br><br>You have received a request for the pick-up from <b>" . $_POST["fullname"] . "</b> of <b>" . $company_nickname . "</b>. Details below";
//$eml_confirmation .= "Dear Vendor,<br><br>You have received a request for the pick-up from <b>Demo Name</b> of <b>Demo company</b>. Details below";
$eml_confirmation .= "Dear Vendor,<br><br>You have been set up to receive email notifications from UCBZeroWaste. This is a test email to ensure that the email address we have on file is valid and does not bounce back";
$eml_confirmation .= "<br></font></td></tr><tr><td><p align=\"left\"><font face=\"arial\" size=\"2\">";

$eml_confirmation .= "</font></td></tr><tr><td><p align=\"left\"><font face=\"arial\" size=\"2\">";



$eml_confirmation .= "There is no action item needed by you at this time, and you can delete this email.<br><br>";
$eml_confirmation .= "If you have any questions, you can email operations@ucbzerowaste.com<br><br>";
$eml_confirmation .= "Thank you!<br>";
$eml_confirmation .= "</font></td></tr></table></body></html>";
$subject = "UCBZeroWaste Test Email";
$email_address_arr = [];
$to_email = $_REQUEST["email_address"];

?>

<form method="post" action="sendemail_add_po_new_save.php">
    <div align="right">
        <a href='javascript:void(0)' style='text-decoration:none;'
            onclick="document.getElementById('light_reminder').style.display='none';">
            <font color="black" size="2"><b>Close</b></font>
        </a>
    </div>

    <table>
        <tr>
            <td width="10%">To:</td>
            <td width="90%"> <input size=60 type="text" id="txtemailto" name="txtemailto"
                    value="<?php echo $to_email; ?>">&nbsp;<font size=1>(Use ; to separate multiple email address)
                </font>
            </td>
        </tr>

        <tr>
            <td width="10%">Cc:</td>
            <td width="90%"> <input size=60 type="text" id="txtemailcc" name="txtemailcc"
                    value="<?php echo $acc_owner_eml; ?><?php if ($acc_owner_eml != $emp_email) {
                                                            echo ";" . $emp_email;
                                                        } ?>">&nbsp;<font size=1>(Use ; to separate multiple
                    email address)</font>
            </td>
        </tr>

        <tr>
            <td width="10%">Bcc:</td>
            <td width="90%"> <input size=60 type="text" id="txtemailbcc" name="txtemailbcc"
                    value="Operations@UsedCardboardBoxes.com">&nbsp;<font size=1>(Use ; to separate multiple email
                    address)</font>
            </td>
        </tr>

        <tr>
            <td width="10%">Subject:</td>
            <!-- <td width="90%"><input size=90 type="text" id="txtemailsubject" name="txtemailsubject"
                    value="UsedCardboardBoxes Order #<?php echo $rec_id; ?> Received"></td> -->
            <td width="90%"><input size=90 type="text" id="txtemailsubject" name="txtemailsubject"
                    value="UCBZeroWaste Test Email"></td>
        </tr>

        <tr>
            <td valign="top" width="10%">Body:</td>
            <td width="1000px" style="height: 60px!important;" id="bodytxt">
                <?php
                require_once('fckeditor_new/fckeditor.php');
                $FCKeditor = new FCKeditor('txtemailbody');
                $FCKeditor->BasePath = 'fckeditor_new/';
                $FCKeditor->Value = $eml_confirmation;
                $FCKeditor->Height = 600;
                $FCKeditor->Width = 1000;
                $FCKeditor->Create();
                ?>
                <div style="height:15px;">&nbsp;</div>

                <input type="submit" name="send_quote_email" value="Submit" />

                <input type="hidden" name="comid" value="<?php echo $_REQUEST['comid']; ?>" />
                <input type="hidden" name="company_id" value="<?php echo $_REQUEST['company_id']; ?>" />
                <input type="hidden" name="eml_confirmation" value="<?php echo htmlspecialchars($eml_confirmation); ?>" />

                <!-- <input type="hidden" name="hidden_reply_eml" id="hidden_reply_eml" value="" />
                <input type="hidden" name="hidden_sendemail" id="hidden_sendemail" value="inemailmode"> -->

            </td>
        </tr>

    </table>
</form>

<?php



// if ($resp == 'emailsend') {
//     $sql = "INSERT INTO water_supplier_email (commodity_id, email_sent, email_sendon) VALUES ( '" . $comm_id . "', '" . $resp . "', '" . date("Y-m-d H:i:s") . "')";
//     db();
//     $result_crm = db_query($sql);
// }



?>