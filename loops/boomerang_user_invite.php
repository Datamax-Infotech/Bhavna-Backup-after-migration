<?php
error_reporting(E_ERROR);
ini_set('display_errors', 1);
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css">
        .tbl_border,
        .tbl_border td,
        .tbl_border tr {
            border: solid 1px #C8C8C8;
            border-collapse: collapse;
        }

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
            var emails = document.getElementById("email").value;
            var company_id = document.getElementById("companyid").value;

            if (!emails) {
                alert("Please enter at least one email address.");
                return false;
            }

            // Split the emails by comma and remove any extra spaces
            var emailArray = emails.split(',').map(function(email) {
                return email.trim();
            });

            // Validate each email format
            for (var i = 0; i < emailArray.length; i++) {
                if (!validateEmail(emailArray[i])) {
                    alert("Invalid email format: " + emailArray[i]);
                    return false;
                }
            }

            // Loop through each email and send an invite
            emailArray.forEach(function(email) {
                var xmlhttp = new XMLHttpRequest();

                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        alert(xmlhttp.responseText);
                        location.reload();
                    }
                }

                var url = "boomerang_invite_submit.php?email=" + encodeURIComponent(email) + "&company_id=" +
                    encodeURIComponent(company_id);
                xmlhttp.open("GET", url, true);
                xmlhttp.send();
            });

            return false; // Prevent form submission
        }

        function validateEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
    </script>

</head>

<?php
$ID = $_GET['ID'];
?>

<body>
    <?php
    include("inc/header.php");
    ?>

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
        <table border="0" bgcolor="#F6F8E5" align="center" width="85%"
            style="font-family:Arial, Helvetica, sans-serif; font-size:12;">
            <tr align="center">
                <td colspan="6" width="320px" bgcolor="#E8EEA8"><strong>Boomerang Portal Setup</strong></font>
                </td>
            </tr>
            <tr align="center">
                <td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">Sent a Invite Link</td>
            </tr>
            <form id="inviteForm" onsubmit="return sendInvite();" method="post">
                <input type="hidden" id="companyid" name="companyid" value="<?php echo $ID; ?>" />

                <!-- <tr align="center">
                    <td align="left" width="180px">Email Address: </td>
                    <td colspan="7" width="200px" align="left">
                        <input width="200px" type="email" name="email" id="email" required />
                    </td>
                </tr> -->
                <tr align="center">
                    <td align="left" width="250px">Email Addresses:</td>
                    <td colspan="9" width="200px" align="left">
                        <textarea rows="4" width="240px" type="email" name="email" id="email" multiple required ></textarea>
                        <small>(add multiple separated by commas)
                    </td>
                </tr>

                <tr align="center">
                    <td width="80px">&nbsp;</td>
                    <td colspan="5" width="320px" align="left">
                        <input type="submit" value="Send Invite" />
                    </td>
                </tr>
            </form>

            <tr>
                <td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">Invite List</td>
            </tr>
            <tr>
                <td colspan="6">
                    <table class="tbl_border" style="width: 100%">
                        <tr>
                            <td width="150px">ID</td>
                            <td width="150px">Company ID</td>
                            <td width="150px">Email</td>
                            <td width="50px">Email Sent</td>
                            <td width="50px">Registered</td>
                            <td width="150px">Register Date</td>
                        </tr>

                        <?php
                        db();
                        $fetch_invites_query = db_query("SELECT * FROM boomerang_user_invite WHERE comp_id = '" . $ID . "' ORDER BY id DESC");

                        if (tep_db_num_rows($fetch_invites_query) > 0) {
                            while ($invite_data = array_shift($fetch_invites_query)) {
                                echo "<tr>
                            <td width='150px'>" . $invite_data['id'] . "</td>
                            <td width='150px'>" . $invite_data['comp_id'] . "</td>
                            <td width='150px'>" . $invite_data['email'] . "</td>
                            <td width='50px'>" . ($invite_data['email_sent'] == 1 ? 'Yes' : 'No') . "</td>
                            <td width='50px'>" . ($invite_data['done_register'] == 1 ? 'Registered' : 'Not Registered') . "</td>
                            <td width='150px'>" . $invite_data['register_date'] . "</td>
                        </tr>";
                            }
                        } else {
                            echo "<tr><td class='color_red align_center' colspan='6'>No records found</td></tr>";
                        }
                        ?>
                    </table>
                </td>
            </tr>
        </table>
        <br />
    </div>
</body>

</html>