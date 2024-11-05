<?php
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

if (isset($_REQUEST['email']) && isset($_REQUEST['company_id'])) {
    $email = $_REQUEST['email'];
    $company_id = $_REQUEST['company_id'];
    $invite_link = "https://www.ucbloops.com/boomerang_portal/register_user.php?email=" . urlencode($email) . "&company_id=" . base64_encode($company_id);
    // Check if the email exists in the Boomerang_usermaster table
    db(); // Ensure the database connection is established
    $check_user_query = db_query("SELECT loginid  FROM boomerang_usermaster WHERE user_email = '" . $email . "'");
    $rec_found = "no";
    while ($fetch_data = array_shift($check_user_query)) {
        $rec_found = "yes";
    }

    if ($rec_found == "yes") {
        $done_register = 1; // User is registered
    } else {
        $done_register = 0; // User is not registered
    }

    // Send the email
    $files = []; // Array of files to be attached
    $path = ""; // Path to the attachments
    $mailto = $email; // Recipient's email address
    $scc = ""; // CC email address (optional)
    $sbcc = ""; // BCC email address (optional)
    $from_mail = "dipti.kakade@extractinfo.com"; // Sender's email address
    $from_name = "Sender Name"; // Sender's name
    $replyto = "dipti.kakade@extractinfo.com"; // Reply-to email address
    $subject = "You are invited to setup your Boomerang profile";
    $message = "<html><body>";
    $message .= "<p>You have been invited to join Boomerang. Please click the link below to set up your profile:</p>";
    $message .= "<p><a href='" . $invite_link . "'>Set up your profile</a></p>";
    $message .= "</body></html>";

    $result = sendemail_attachment($files, $path, $mailto, $scc, $sbcc, $from_mail, $from_name, $replyto, $subject, $message);

    // Check if the email was sent successfully
    $email_sent = $result ? 1 : 0;
    db();
    // Insert data into invoice_sent table
    db_query("INSERT INTO boomerang_user_invite (comp_id, email, email_sent, done_register,register_date) 
              VALUES ('" . $company_id . "', '" . $email . "', $email_sent, $done_register, NOW())");

    // Return response to the frontend
    if ($email_sent == 1) {
        echo 'Email sent successfully!';
    } else {
        echo 'Failed to send email!';
    }
}
