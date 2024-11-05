<?php
set_time_limit(0);	
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '90');
ini_set('net_read_timeout', '90');
ini_set('net_write_timeout', '90');
ini_set('interactive_timeout', '90');
ini_set('connect_timeout', '90');

use Office365\Runtime\Auth\UserCredentials;
use Office365\SharePoint\ClientContext;

$rundt_start = date('l, F j, Y H:i:s');
spl_autoload_register('ews_autoloader');

ini_set("display_errors", "1");
error_reporting(E_ALL);

require("../../mainfunctions/database.php");
require("../../mainfunctions/general-functions.php");

function ews_autoloader($className) {
  if($className != 'EWS_Exception') {
    $classPath = str_replace('_','/',$className);
	if(file_exists("php-ews/{$classPath}.php")) {
		include("php-ews/{$classPath}.php");
	}
  }
}
	
	function getvariabledata_all() {
		global $mailserver, $mailuser, $mailpass, $emailattbasepath, $move_foldername, $importfolder_name, $crm_userid, $email_scrape_ignore_emails;
		
		$sql = "SELECT * FROM tblvariable " ;
		$result = db_query($sql);
		
		while ($myrowsel = array_shift($result)) {
			if (strtoupper($myrowsel["variablename"]) == strtoupper("mailserver_exchange"))
			{
				$mailserver = $myrowsel["variablevalue"];
			}
			if (strtoupper($myrowsel["variablename"]) == strtoupper("mailuser"))
			{
				$mailuser = $myrowsel["variablevalue"];
			}
			if (strtoupper($myrowsel["variablename"]) == strtoupper("mailpass"))
			{
				$mailpass = $myrowsel["variablevalue"];
			}
			
			if (strtoupper($myrowsel["variablename"]) == strtoupper("importfolder_name"))
			{
				$importfolder_name = $myrowsel["variablevalue"];
			}
			if (strtoupper($myrowsel["variablename"]) == strtoupper("email_scrape_ignore_emails"))
			{
				$email_scrape_ignore_emails = $myrowsel["variablevalue"];
			}
		}
	}	
	
$mailuser = "";
$mailpass = "";
$emailattbasepath = "";
$move_foldername = "";
$move_folder_id = "";
$move_folder_chgkey = "";
$importfolder_name = "";
$crm_userid = "";
$email_scrape_ignore_emails = "";
$importemail_cnt = 0; 
$importemail_match_cnt = 0;
db();
//get the variable details
getvariabledata_all();
$email_scrape_ignore_emails_arr = explode(",", $email_scrape_ignore_emails);
//echo $mailserver . " " . $mailuser . " ". $mailpass . "<br>";
//$mailserver = "exg6.exghost.com";
//$mailuser = "emailscraper92958";
//$mailpass = "boomerang123";
require_once '../graph_mailer.php';
$graphMailer = new graphMailer('e340f7a1-00a6-480d-8879-2a1c9adb0348','fbd04436-f3dd-4f85-821f-5161af030672','FwK8Q~I8Cp61_yh~kJC79nslQPTGStEDytbM-aV-');
$token_val = $graphMailer->getToken();
$newarr_eml = $graphMailer->getMessages($mailuser);
//echo '<pre>';
//echo $token_val;
//print_r($newarr_eml);
//echo '</pre>';
	
	
	
	foreach ($newarr_eml as $arrind_eml) {
		//var_dump($arrind_eml);
		$eml_id = $arrind_eml["id"];
		
		//echo $eml_id . "<br>";
		$chg_key = ""; //$arrind_eml->ItemId->ChangeKey;
		//echo "pr: " . $eml_id . " " . $chg_key . "<br/>";
		
		//echo "Processing : Subject: " . $arrind_eml["subject"] . " " . $arrind_eml["sentDateTime"] . " " . $eml_id . "<br/>";
		
		// && (strpos($arrind_eml["subject"], "TEST:") > 0)
		if (chk_ifemailinward($eml_id, $chg_key) == "not found" )
		{
			//echo "Processing : ". $arrind_eml->ItemId->Id . " key: " . $arrind_eml->ItemId->ChangeKey . "<br/>";
			//$importemail_internal = getemailparts($ews, $arrind_eml->Id, "",  "", "", "");
			
			$message = $arrind_eml["body"]->content;
			$eml_size = 0;
			
			$toemailadd = "";
			if ($arrind_eml["toRecipients"]) {
				$arr_toeml = $arrind_eml["toRecipients"];
				foreach ($arr_toeml as $arr_toeml_tmp){
					$toemailadd = $arr_toeml_tmp->emailAddress->address . ", " . $toemailadd ; 
				}
				$toemailadd = substr($toemailadd, 0, strlen(trim($toemailadd))-1);
			}
			$fromemailadd = "";
			if ($arrind_eml["sender"]) {
				$arr_toeml = $arrind_eml["sender"];
				if (!empty($arr_toeml)){
					foreach ($arr_toeml as $arr_toeml_tmp){
						$fromemailadd = $arr_toeml_tmp->emailAddress->address . ", " . $fromemailadd ; 
					}
					$fromemailadd = substr($fromemailadd, 0, strlen(trim($fromemailadd))-1);
				}else{
					$fromemailadd = $arr_toeml->emailAddress->address;
				}
			}
			$ccemailadd = "";
			if ($arrind_eml["ccRecipients"]) {
				$arr_cceml = $arrind_eml["ccRecipients"];
				foreach ($arr_cceml as $arr_cceml_tmp){
					$ccemailadd = $arr_cceml_tmp->emailAddress->address . ", " . $ccemailadd ; 
				}
				$ccemailadd = substr($ccemailadd, 0, strlen(trim($ccemailadd))-1);
			}
			if (strpos($message, "'") > 0) {
				$message = stripslashes($message); 
				$message = addslashes($message); 
			}
			if (strpos($message, "<base") > 0) {
				$message_org = substr($message, 0 , strpos($message, "<base")-1);
				$message_t = substr($message, strpos($message, "<base"));
				if (strpos($message_t, ">") > 0) {
					$message_new = substr($message_t, strpos($message_t, ">")+1);
					$message = $message_org . " " . $message_new;
				}
			}
			$strsubject = preg_replace ( "/'/", "\'", $arrind_eml["subject"]);
			$strto = preg_replace ( "/'/", "\'", $toemailadd);
			$strfrom = preg_replace ( "/'/", "\'", $fromemailadd);
			$strcc = preg_replace ( "/'/", "\'", $ccemailadd);
			$reply_to = "";
		
			$tz = new DateTimeZone('America/Los_Angeles');
			$strdate = $arrind_eml["sentDateTime"];
			$strdate = new DateTime($strdate);
			$strdate->setTimeZone($tz);
			$strdate = $strdate->format('Y-m-d H:i:s');
			$item_chgkey = "";
			
			echo $strsubject . " - " . $strto . " - " . $strfrom . " - " . $strcc . " - " . $strdate. "<br>";	
			
			$emai_tosearch_toeml = ""; $emai_tosearch_fromeml = ""; $emai_tosearch_cceml = "";
			if ($strto != "" && strlen($strto) > 5){
				$pos = strpos(strtoupper($strto), ",");
				if ($pos !== false) {
					$strto_array = explode(",", $strto) ;
					
					$new_arr_cnt = 0;
					foreach ($strto_array as $strto_array_ind) {
						if (trim($strto_array_ind) != ""){
							$pos = strpos(strtoupper($strto_array_ind), strtoupper("UsedCardboardBoxes.com"));
							if ($pos === false) {
								$emai_tosearch_toeml .= "'" . trim($strto_array_ind) . "',";
								$new_arr_cnt = $new_arr_cnt + 1;
							}
						}	
					}
					if ($new_arr_cnt > 0){
						$emai_tosearch_toeml = trim(substr($emai_tosearch_toeml, 0, strlen($emai_tosearch_toeml)-1));
					}	
					//echo "Chk emai_tosearch_toeml - " . $emai_tosearch_toeml . "<br>";	
				}else {
					$pos = strpos(strtoupper($strto), strtoupper("UsedCardboardBoxes.com"));
	 
					if ($pos === false) {
						$emai_tosearch_toeml = "'" . $strto . "'";
					}
					if ($strto == "david.krasnow@gmail.com") {
						$emai_tosearch_toeml = "";
					}
				}
			}
			
			if ($strfrom != "" && strlen($strfrom) > 5){
				$pos = strpos(strtoupper($strfrom), strtoupper("UsedCardboardBoxes.com"));
				if ($pos === false) {
					$emai_tosearch_fromeml .= $strfrom;
				}
				if ($strfrom == "david.krasnow@gmail.com") {
					$emai_tosearch_fromeml = "";
				}
			}
		
			if ($strcc != "" && strlen($strcc) > 5){
				$pos = strpos(strtoupper($strcc), ",");
				if ($pos !== false) {
					$strcc_array = explode(",", $strcc) ;
					
					$new_arr_cnt = 0;
					foreach ($strcc_array as $strcc_array_ind) {
						if (trim($strcc_array_ind) != ""){
							$pos = strpos(strtoupper($strcc_array_ind), strtoupper("UsedCardboardBoxes.com"));
							if ($pos === false) {
								$emai_tosearch_cceml .= "'" . trim($strcc_array_ind) . "',";
								$new_arr_cnt = $new_arr_cnt + 1;
							}
						}	
					}
					if ($new_arr_cnt > 0){
						$emai_tosearch_cceml = trim(substr($emai_tosearch_cceml, 0, strlen($emai_tosearch_cceml)-1));
					}	
				}else {
					$pos = strpos(strtoupper($strcc), strtoupper("UsedCardboardBoxes.com"));
	 
					if ($pos === false) {
						$emai_tosearch_cceml = "'" . $strcc . "'";
					}
					if ($strcc == "david.krasnow@gmail.com") {
						$emai_tosearch_cceml = "";
					}
				}
			}		
		
			//As per email_scrape_ignore_emails program will ignore the emails
			foreach ($email_scrape_ignore_emails_arr as $email_scrape_ignore_email_tmp) {
				$pos = strpos(strtoupper($emai_tosearch_toeml), strtoupper($email_scrape_ignore_email_tmp));
				if ($pos === false) {
					
				}else{
					$emai_tosearch_toeml = "";
				}
				
				$pos = strpos(strtoupper($emai_tosearch_fromeml), strtoupper($email_scrape_ignore_email_tmp));
				if ($pos === false) {
					
				}else{
					$emai_tosearch_fromeml = "";
				}
				$pos = strpos(strtoupper($emai_tosearch_cceml), strtoupper($email_scrape_ignore_email_tmp));
				if ($pos === false) {
					
				}else{
					$emai_tosearch_cceml = "";
				}
			}
			
			$found_combination = "";
			$sql = " " ; $sql_b2bsellto = " " ;
			$found_combination = "";
		
			//to insert record in the CRM log
		
			$num_rows = 0; $found_combination = "yes";
			$sql_chk = $sql;
			$rec_inserted_main = "no";
			//echo "emai_tosearch_toeml - " . $emai_tosearch_toeml . " " . $emai_tosearch_fromeml . " " . $emai_tosearch_cceml . "<br>";
			if ($found_combination == "yes"){
				db_water_inbox_email();
				$sql = "INSERT INTO tblemail (emaildate, toadd, fromadd, ccadd, subject,  messageid, eml_changekey, inward_date )";
				$sql .= " VALUES('" . $strdate . "', '" . $strto . "', '" . $strfrom . "', '" . $strcc . "', " ;
				$sql .= "'" . $strsubject . "', '" . $eml_id . "', '" . $item_chgkey . "', '" . date("Y-m-d H:i:s") . "')";
				//echo $sql . "<br/>";
				$result2 = db_query($sql) ;
				$unqeml_id = tep_db_insert_id();
				$sql = "INSERT INTO tblemail_body_txt (email_id, body_txt)";
				$sql .= " VALUES('" . $unqeml_id . "', '" . $message . "')";
				$result2 = db_query($sql) ;
				echo "Inserted <br>From: " . $strfrom . "<br>To: " . $toemailadd . "<br>Date: " . $strdate . "<br>". "Subject: " . $strsubject . "<br/>Inserted id: " . $unqeml_id . " <br/><br/>";
				
				$attachment_str = "";
				if ($unqeml_id > 0)
				{
					$rec_inserted_main = "yes";
					
					//if there are attachment size greater then 6.4 mb then it stuck
					if ($eml_size < 6000000) { 
						if(!empty($arrind_eml["attachments"])) {
							// FileAttachment attribute can either be an array or instance of stdClass...
							$attachments = $arrind_eml["attachments"]->value;
							
							$first = true;
							
							foreach($attachments as $attachment) {
								$content_main = base64_decode($attachment->contentBytes);
								$content = $attachment->id;
								
								if ($first)
								{
									$first = false;
									$cont_id =$content; 
									preg_match('/(?<=@)\S+/i', $cont_id."", $match);
									$signature= $match[0];
									//echo 'Sign: ' . $signature . "</br>";
									$mainattacment = substr($content, 0, strpos($content, '@'));
								}
				
								$filename = $attachment->name;
								$filename_db = preg_replace( "/'/", "\'", $filename); 
								//* : < > ? / \ | ~ " # % & * : < > ? / \ { | }.
								$filename = str_replace("'", "" ,$filename);
								$filename = str_replace("*", "" ,$filename);
								$filename = str_replace(":", " " ,$filename);
								$filename = str_replace("<", " " ,$filename);
								$filename = str_replace(">", " " ,$filename);
								$filename = str_replace("/", " " ,$filename);
								$filename = str_replace("/", " " ,$filename);
								$filename = str_replace("\\", " " ,$filename);
								$filename = str_replace("|", " " ,$filename);
								$filename = str_replace("~", " " ,$filename);
								$filename = str_replace("&", " " ,$filename);
								$filename = str_replace("#", " " ,$filename);
								$filename = str_replace("%", " " ,$filename);
								$filename = str_replace("{", " " ,$filename);
								$filename = str_replace("}", " " ,$filename);
								$filename = str_replace(" ", "-" ,$filename);
								$filename = str_replace("(", "-" ,$filename);
								$filename = str_replace(")", "-" ,$filename);
								$main_folder_nm = date("Y") . "_" . date("m");	
								if (!file_exists($main_folder_nm)) {
									mkdir($main_folder_nm);
									chmod($main_folder_nm, 0777);
								}
								if (!file_exists($main_folder_nm . "/" . $unqeml_id)) {
									mkdir($main_folder_nm . "/" . $unqeml_id);
									chmod($main_folder_nm . "/" . $unqeml_id, 0777);
								}
								
								file_put_contents($main_folder_nm . "/" . $unqeml_id."/".$filename, $content_main);
								
								db_water_inbox_email();
								$sql = "INSERT INTO tblemail_attachment (emailid , attachmentname)";
								$sql .= " VALUES(" . $unqeml_id . ", '" . $filename . "');";
								$result4 = db_query($sql);
								
								$attachment_str = $attachment_str . "<a href=emailatt_uploads/" . $unqeml_id."/".$filename . ">$filename</a><br/>";
								
							}
						}
					}
					
					move_email_to_folder($eml_id);
					$importemail_cnt = $importemail_cnt +1;
					
				}else {
					$importemail = "From: " . $fromemailadd . "<br/>To: " . $toemailadd . "<br/>". "Subject: " . $strsubject . "<br/>Date:".$strdate . "<br/><br/>";
				}
			
			}
		
			if ($num_rows == 0)
			{
				//echo "<br>Deleted From: " . $fromemailadd . "<br>To: " . $toemailadd . "<br>Date: " . $strdate . "<br>". "Subject: " . $strsubject . "<br/><br/>";
				//moveitem($eml_id);
			}			
				
		}
		
	}
	function chk_ifemailinward($eml_id, $chg_key)
	{
		db_water_inbox_email();
		
		global $move_folder_id, $move_folder_chgkey;
		//$sql_found = "select messageid, match_company_id from tblemail where messageid = '" . $eml_id . "' and eml_changekey = '" . $chg_key . "'";
		$sql_found = "select messageid, match_company_id from tblemail where messageid = '" . $eml_id . "'";
		//echo $sql_found;
		$result_found = db_query($sql_found);
		
		$sStr = "not found";
		while ($myrowsel_found = array_shift($result_found)) {
			//echo $sql_found . "<br>";
			$sStr = "found";
			if ($myrowsel_found["match_company_id"] > 0){
				//moveitem($eml_id);
			}
		}
		
		return $sStr;
	}
	
	function moveitem($id)
	{
		global $mailuser, $graphMailer;
	
		//$graphMailer->deleteEmail($mailuser, $id, false);
	}	
	
	function move_email_to_folder($id)
	{
		global $mailuser, $graphMailer;
		
		$graphMailer->moveEmail($mailuser, $id);
	}	
	
	db_water_inbox_email();
	$sql_found = "Select tblemail.unqid, inward_date, attachmentname, subject from tblemail inner join tblemail_attachment on tblemail_attachment.emailid = tblemail.unqid 
	where tblemail_attachment.email_ocr_done = 0 and attachmentname like '%.pdf%' order by tblemail.unqid";
	$result_found = db_query($sql_found);
	while ($myrowsel_found = array_shift($result_found)) 
	{
		$inward_date = $myrowsel_found["inward_date"];
		$org_filename = $myrowsel_found["attachmentname"];;
		//$ocr_database = "water_invoice_email_ocr";
		//if (strpos($myrowsel_found["subject"], "TEST:") > 0)
		//{
			$ocr_database = "water_invoice_email_ocr";
		//}
		
		$filename = Date("Y", strtotime($inward_date)) . "_" . Date("m", strtotime($inward_date)) . "/" . $myrowsel_found["unqid"] . "/" . $org_filename;
		
		$inv_file_for_ocr = "https://www.ucbloops.com/loops/water_email_inbox_inv_files/" . $filename;
		//echo $inv_file_for_ocr . "<br>";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://www.ucbloops.com/loops/AZFormR/formrecognizer_v2024/ai-form-recognizer/extract_invoice_data_ocr_v2024.php");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "inv_filename=" . $inv_file_for_ocr);			
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$mainstr = curl_exec($ch);
		curl_close($ch);
		
		$main_array = explode("|Delimiter|", $mainstr);		
		
		//var_dump($main_array);
		
		$ocr_inv_number = ""; $ocr_inv_date = ""; $vendor_name = ""; $VendorAddressRecipient = ""; $ServiceStartDate = ""; $account_no = ""; $invoice_amount = ""; $invoice_due_date = "";
		$inv_number_accuracy = 0; $vendor_name_accuracy = 0; $invoice_amount_accuracy = 0; $account_no_accuracy = 0; $invoice_date_accuracy = 0;
		$template_id_tmp = 0; $template_id_tmp_field_name = "";
		$ShippingAddressRecipient = "";
		if ($main_array){
			//Array 0 is Key pair value
			$main_keypair_array = explode("|", $main_array[0]);
			//var_dump($main_keypair_array);
			foreach ($main_keypair_array as $keypair_array_detail)
			{
				$keypair_detail = explode("^", $keypair_array_detail);
				if ($keypair_detail){
					$tmppos_1 = strpos($keypair_detail[0],"VendorName");
					if ($tmppos_1 != false)
					{ 		
						if ($keypair_detail[1] != ""){
							$vendor_name = $keypair_detail[1];
							$vendor_name_accuracy = $keypair_detail[2];
						}	
					}
					
					$tmppos_1 = strpos($keypair_detail[0],"VendorAddressRecipient");
					if ($tmppos_1 != false)
					{ 		
						if ($keypair_detail[1] != ""){
							$VendorAddressRecipient = $keypair_detail[1];
							$VendorAddressRecipient_accuracy = $keypair_detail[2];
						}	
					}
					
				}
			}
			
			foreach ($main_keypair_array as $keypair_array_detail)
			{
				/*string(5699) "Type: invoice Fields: Field AmountDue has value '[object Object]' with a confidence score of 0.85
				Field CustomerAddress has value '[object Object]' with a confidence score of 0.658
				Field CustomerAddressRecipient has value 'MCCORMICK & COMPANY INC. C/O UCBZEROWASTE LLC' with a confidence score of 0.804
				Field CustomerId has value 'ROL01360-001' with a confidence score of 0.977
				Field CustomerName has value 'MCCORMICK & COMPANY INC. C/O UCBZEROWASTE LLC' with a confidence score of 0.804
				Field InvoiceDate has value 'Mon Feb 27 2023 18:00:00 GMT-0600 (Central Standard Time)' with a confidence score of 0.978
				Field InvoiceId has value '228754' with a confidence score of 0.978
				Field InvoiceTotal has value '[object Object]' with a confidence score of 0.803
				InvTotal^3,657.94^0.907|<br>
				Field Items has value 'undefined' with a confidence score of undefined
				Field PaymentTerm has value 'DUE ON RECEIPT' with a confidence score of 0.931
				Field PreviousUnpaidBalance has value '[object Object]' with a confidence score of 0.568
				Field PurchaseOrder has value '4700227054' with a confidence score of 0.973
				Field ServiceAddress has value '[object Object]' with a confidence score of 0.302
				Field ServiceAddressRecipient has value 'MCCORMICK & CO. INC. INGRED. COMPACTOR' with a confidence score of 0.423
				Field ServiceStartDate has value 'Tue Jan 31 2023 18:00:00 GMT-0600 (Central Standard Time)' with a confidence score of 0.541
				Field VendorAddress has value '[object Object]' with a confidence score of 0.712
				Field VendorName has value 'Gerber's Ine.' with a confidence score of 0.912
				*/		
				
				$keypair_detail = explode("^", $keypair_array_detail);
				if ($keypair_detail){
					//var_dump($keypair_detail);
					//echo "<br>";
					$tmppos_1 = strpos($keypair_detail[0],"InvoiceId");
					if ($tmppos_1 != false)
					{ 		
						$ocr_inv_number = $keypair_detail[1];
						$inv_number_accuracy = $keypair_detail[2];
						//echo $ocr_inv_number . "<br>";
					}
					/*
					Not used as it retrive sub total
					$tmppos_1 = strpos($keypair_detail[0],"InvoiceTotal");
					if ($tmppos_1 != false)
					{ 		
						if ($keypair_detail[1] != ""){
							$invoice_amount = str_replace(",", "", $keypair_detail[1]);
							$invoice_amount_accuracy = $keypair_detail[2];
						}	
					}*/
					$tmppos_1 = strpos($keypair_detail[0],"CustomerId");
					if ($tmppos_1 != false)
					{ 		
						if ($keypair_detail[1] != ""){
							$account_no = $keypair_detail[1];
							$account_no_accuracy = $keypair_detail[2];
						}	
					}
					
					
					//This is for the Garick template, we are checking phone number
					if ($account_no == ""){
						$tmppos_1 = strpos($keypair_detail[0],"CustomerName");
						if ($tmppos_1 != false)
						{ 		
							if ($keypair_detail[1] != ""){
								$account_no = $keypair_detail[1];
								$account_no_accuracy = $keypair_detail[2];
							}	
						}
					}
					//This is for the Post Environmental Services template, we are checking address
					$tmppos_1 = strpos($keypair_detail[0],"ShippAddval");
					if ($tmppos_1 != false)
					{ 		
						if ($vendor_name == "Post Environmental Services"){
							$keypair_detail_data = $keypair_detail[1];
							$keypair_detail_data = str_replace("\n", ' ', $keypair_detail_data);
							
							//for ($i = 0; $i < strlen($keypair_detail_data); $i++) {
							//	$ascii = ord($keypair_detail_data[$i]);
							//	echo "Character: {$keypair_detail_data[$i]}, ASCII: {$ascii}<br>";
							//}							
							
							if ($keypair_detail_data == "4343 E Mustard Way Springfield, MO 65803")
							{ 		
								$account_no = "4343 E Mustard";
								$account_no_accuracy = $keypair_detail[2];
							}	
							if ($keypair_detail_data == "4455 E Mustard Way Springfield, MO 65803")
							{
								$account_no = "4455 E Mustard";
								$account_no_accuracy = $keypair_detail[2];
							}	
						}
					}
					$tmppos_1 = strpos($keypair_detail[0],"ServiceStartDate");
					if ($tmppos_1 != false)
					{ 		
						if ($keypair_detail[1] != ""){
							$ServiceStartDate = $keypair_detail[1];
						}	
					}
					
					$tmppos_1 = strpos($keypair_detail[0],"ShippingAddressRecipient");
					if ($tmppos_1 != false)
					{ 		
						if ($keypair_detail[1] != ""){
							$ShippingAddressRecipient = $keypair_detail[1];
						}	
					}
					
					
					$tmppos_3 = strpos($vendor_name,"earth farms");
					$tmppos_2 = strpos($vendor_name,"REPUBLIC");
					$tmppos_1 = strpos($vendor_name,"Gerber");
					if ($tmppos_1 != false)
					{					
						$tmppos_1 = strpos($keypair_detail[0],"InvDate");
						if ($tmppos_1 != false)
						{ 		
							if ($keypair_detail[1] != ""){
								$ocr_inv_date = date("Y-m-d" , strtotime($keypair_detail[1]));
								$invoice_date_accuracy = $keypair_detail[2];
							}	
						}
						
						$tmppos_1 = strpos($keypair_detail[0],"InvTotal");
						if ($tmppos_1 != false)
						{ 		
							if ($keypair_detail[1] != ""){
								$invoice_amount = str_replace(",", "", $keypair_detail[1]);
								$invoice_amount = str_replace("$", "", $invoice_amount);
								$invoice_amount_accuracy = $keypair_detail[2];
							}	
						}
					}else if ($tmppos_2 != false) {
						//Becasue of date format taken key pair value
						$tmppos_1 = strpos($keypair_detail[0],"InvoiceDate");
						if ($tmppos_1 != false)
						{ 		
							if ($keypair_detail[1] != ""){
								$ocr_inv_date = date("Y-m-d", strtotime($keypair_detail[1]));
								
								$invoice_date_accuracy = $keypair_detail[2];
							}	
						}
						
						$tmppos_1 = strpos($keypair_detail[0],"InvoiceTotal");
						if ($tmppos_1 != false)
						{ 		
							if ($keypair_detail[1] != ""){
								$invoice_amount = str_replace(",", "", $keypair_detail[1]);
								$invoice_amount = str_replace("$", "", $invoice_amount);
								$invoice_amount_accuracy = $keypair_detail[2];
							}	
						}
					}else if ($tmppos_3 != false) {
						$tmppos_1 = strpos($keypair_detail[0],"InvoiceDate");
						if ($tmppos_1 != false)
						{ 		
							if ($keypair_detail[1] != ""){
								$ocr_inv_date = date("Y-m-d", strtotime($keypair_detail[1]));
								
								$invoice_date_accuracy = $keypair_detail[2];
							}	
						}
						
						$tmppos_1 = strpos($keypair_detail[0],"InvoiceTotal");
						if ($tmppos_1 != false)
						{ 		
							if ($keypair_detail[1] != ""){
								$invoice_amount = str_replace(",", "", $keypair_detail[1]);
								$invoice_amount = str_replace("$", "", $invoice_amount);
								$invoice_amount_accuracy = $keypair_detail[2];
							}	
						}
					}else{
						//Becasue of date format taken key pair value
						$tmppos_1 = strpos($keypair_detail[0],"InvoiceDate");
						if ($tmppos_1 != false)
						{ 		
							if ($keypair_detail[1] != ""){
								/*$ocr_inv_date1 = str_replace(" (Central Standard Time)", "", $keypair_detail[1]);
								$ocr_inv_date1 = str_replace(" (Central Daylight Time)", "", $keypair_detail[1]);
								$ocr_inv_date2 = new DateTime($ocr_inv_date1);
								$ocr_inv_date2->modify('+1 day');
								$ocr_inv_date = $ocr_inv_date2->format("Y-m-d");*/
								$ocr_inv_date = date("Y-m-d", strtotime($keypair_detail[1]));
								
								$invoice_date_accuracy = $keypair_detail[2];
							}	
						}
						
						
						$tmppos_1 = strpos($keypair_detail[0],"InvoiceTotal");
						if ($tmppos_1 != false)
						{ 		
							if ($keypair_detail[1] != ""){
								$invoice_amount = str_replace(",", "", $keypair_detail[1]);
								$invoice_amount = str_replace("$", "", $invoice_amount);
								$invoice_amount_accuracy = $keypair_detail[2];
							}	
						}
						
					}
					
					$tmppos_1 = strpos($keypair_detail[0],"Total Due by");
					if ($tmppos_1 != false)
					{ 		
						if ($keypair_detail[1] != ""){
							$invoice_due_date_str = str_replace("^undefined", "", $keypair_detail[1]);
							$invoice_due_date_str = str_replace("Total Due by ", "", $invoice_due_date_str);
							$invoice_due_date = date("Y-m-d", strtotime($invoice_due_date_str));
						}	
					}
					
			
					db();

					$sql_srch = "Select * from water_ocr_account_mapping where account_no = '" . $account_no . "'";
					echo $sql_srch . "<br>";
					$newaccount_no_found_flg = 0;
					$result_srch = db_query($sql_srch);
					while ($myrowsel_srch = array_shift($result_srch)) 
					{
						$newaccount_no_found_flg = 1;
					}

					if ($newaccount_no_found_flg == 0){
						
						if ($newaccount_no_found_flg == 0){
							$keypair_label = str_replace(["\r", "\n"], ' ', $keypair_detail[0]);
							$keypair_label = str_replace("<br>", " ", $keypair_label);
							$keypair_label = str_replace("'", "\'" , $keypair_label);
							
							$keypair_data = str_replace(["\r", "\n"], ' ', $keypair_detail[1]);
							$keypair_data = str_replace("<br>", ' ', $keypair_data);
							$keypair_data = str_replace("'", "\'" , $keypair_data);
							
							$sql_srch = "Select template_id, field_value, field_name from water_ocr_account_mapping_account_not_found where field_name = '" . trim($keypair_label) ."'";
							echo $sql_srch . "<br>";
							$result_srch = db_query($sql_srch);
							while ($myrowsel_srch = array_shift($result_srch)) 
							{
								//echo "srch = " . $keypair_data . "|" . $myrowsel_srch["field_value"] . "<br>";
								//$tmppos_1 = strpos(trim($keypair_data),trim($myrowsel_srch["field_value"]));
								if (trim($keypair_data) == trim($myrowsel_srch["field_value"]))
								{ 		
									$template_id_tmp = $myrowsel_srch["template_id"];
									$template_id_tmp_field_name = $myrowsel_srch["field_name"];
									//echo "template_id_tmp = " . $template_id_tmp . "<br>";
								}					
							}
						}
					}
				}
			}
			
			//if $template_id_tmp matched then check for second or thrid pair
			if ($template_id_tmp > 0){
				$sql_srch = "Select template_id, field_value, field_name from water_ocr_account_mapping_account_not_found where template_id = '" . $template_id_tmp . "' and field_name <> '" . $template_id_tmp_field_name ."'";
				echo "Sec match = " . $sql_srch . "<br>";
				$result_srch = db_query($sql_srch);
				while ($myrowsel_srch = array_shift($result_srch)) 
				{
					//echo "srch = " . $keypair_data . "|" . $myrowsel_srch["field_value"] . "<br>";
					//$tmppos_1 = strpos(trim($keypair_data),trim($myrowsel_srch["field_value"]));
					$template_chk_second_match = "no";
					
					foreach ($main_keypair_array as $keypair_array_detail)
					{
						$keypair_detail = explode("^", $keypair_array_detail);
						$keypair_label = str_replace(["\r", "\n"], ' ', $keypair_detail[0]);
						$keypair_label = str_replace("<br>", " ", $keypair_label);
						$keypair_data = str_replace(["\r", "\n"], ' ', $keypair_detail[1]);
						$keypair_data = str_replace("<br>", ' ', $keypair_data);
						
						if ($keypair_label){
							if (trim($keypair_data) == trim($myrowsel_srch["field_value"]))
							{ 		
								$template_chk_second_match = "yes";
								//echo "template_id_tmp = " . $template_id_tmp . "<br>";
							}					
							
						}
					}
					
					if ($template_chk_second_match == "yes"){
						echo "Sec match 1 = " . $myrowsel_srch["template_id"] . "<br>";
						$template_id_tmp = $myrowsel_srch["template_id"];
					}else{
						echo "Sec match 2 = <br>";
						//not found case
						$template_id_tmp = 0;
					}
				}
			}
			db();
			$company_id = ""; $vendor_id = ""; $account_no_found_flg = 0; $template_id = 0;
			$sql_srch = "Select * from water_ocr_account_mapping where generic_flg = 1 and account_no = '" . $account_no . "'";
			echo $sql_srch . "<br>";
			$result_srch = db_query($sql_srch);
			while ($myrowsel_srch = array_shift($result_srch)) 
			{
				$account_no_found_flg = 1;
				$company_id = $myrowsel_srch["company_id"];
				$vendor_id = $myrowsel_srch["vendor_id"];
				$template_id = $myrowsel_srch["unqid"];
			}
			//echo "account_no= " . $account_no . "|" . $template_id . "<br>";
			if ($account_no_found_flg == 0){
				$template_id = $template_id_tmp;			
			}
			
			$company_id = ""; $vendor_id = ""; $account_no_found_flg = 0;
			if ($template_id > 0 ){
				$sql_srch = "Select * from water_ocr_account_mapping where generic_flg = 1 and unqid = '" . $template_id . "'";
			}else{
				$sql_srch = "Select * from water_ocr_account_mapping where account_no = '" . $account_no . "'";
			}
			echo "Final = " . $sql_srch . "<br>";
			$result_srch = db_query($sql_srch);
			while ($myrowsel_srch = array_shift($result_srch)) 
			{
				$account_no_found_flg = 1;
				$company_id = $myrowsel_srch["company_id"];
				$vendor_id = $myrowsel_srch["vendor_id"];
			}
			if ($template_id > 0 ){
				$inv_due_date_fldnm = "";
				$mapping_field_sql = db_query("SELECT field_name FROM water_ocr_mapping_field_and_values where mapped_with = 4  and field_value <> '' and template_id = $template_id");
				while($mapping_field = array_shift($mapping_field_sql)){
					$inv_due_date_fldnm = $mapping_field['field_name'];
				}
				
				if ($inv_due_date_fldnm != ""){
					$main_keypair_array = explode("|", $main_array[0]);
					//var_dump($main_keypair_array);
					foreach ($main_keypair_array as $keypair_array_detail)
					{
						$keypair_data = str_replace(["\r", "\n"], ' ', $keypair_array_detail);
						$keypair_data = str_replace("<br>", ' ', $keypair_data);
						
						//echo "Feidl Inv due srch1 = " . $keypair_data . "<br>";
						$keypair_detail = explode("^", $keypair_data);
						if ($keypair_detail){
							//echo "Feidl Inv due srch = " . $keypair_detail[0] . "|" . $inv_due_date_fldnm . "|" . $keypair_detail[1] . "<br>";
							//$tmppos_1 = strpos($keypair_detail[0],$inv_due_date_fldnm);
							if (trim($keypair_detail[0]) == trim($inv_due_date_fldnm))
							{ 		
								//if ($keypair_detail[1] != ""){
									$invoice_due_date1 = $keypair_detail[1];
									$invoice_due_date = date('Y-m-d', strtotime($invoice_due_date1));
									break;
								//}	
							}
						}
					}
				}
				
				//$ocr_inv_number		
				$field_fldnm = "";				
				$mapping_field_sql = db_query("SELECT field_name FROM water_ocr_mapping_field_and_values where mapped_with = 2 and field_value <> '' and template_id = $template_id");
				while($mapping_field = array_shift($mapping_field_sql)){
					$field_fldnm = $mapping_field['field_name'];
				}
				
				if ($field_fldnm != ""){
					$main_keypair_array = explode("|", $main_array[0]);
					foreach ($main_keypair_array as $keypair_array_detail)
					{
						$keypair_data = str_replace(["\r", "\n"], ' ', $keypair_array_detail);
						$keypair_data = str_replace("<br>", ' ', $keypair_data);
						
						//echo "Feidl Inv no srch1 = " . $keypair_data . "<br>";
						$keypair_detail = explode("^", $keypair_data);
						if ($keypair_detail){
							//echo "Feidl Inv srch = " . $keypair_detail[0] . "|" . $field_fldnm . "|" . $keypair_detail[1] . "<br>";
							
							//$tmppos_1 = strpos($keypair_detail[0],$field_fldnm);
							if (trim($keypair_detail[0]) == trim($field_fldnm))
							{ 		
								//if ($keypair_detail[1] != ""){
									$ocr_inv_number = $keypair_detail[1];
									$inv_number_accuracy = $keypair_detail[2];
									break;
								//}	
							}
						}
					}
				}
				
				//Invoice date		
				$field_fldnm = "";				
				$mapping_field_sql = db_query("SELECT field_name FROM water_ocr_mapping_field_and_values where mapped_with = 3 and field_value <> '' and template_id = $template_id");
				while($mapping_field = array_shift($mapping_field_sql)){
					$field_fldnm = $mapping_field['field_name'];
				}
				
				if ($field_fldnm != ""){
					$main_keypair_array = explode("|", $main_array[0]);
					foreach ($main_keypair_array as $keypair_array_detail)
					{
						$keypair_data = str_replace(["\r", "\n"], ' ', $keypair_array_detail);
						$keypair_data = str_replace("<br>", ' ', $keypair_data);
						
						//echo "Feidl Inv dt srch1 = " . $keypair_data . "<br>";
						$keypair_detail = explode("^", $keypair_data);
						if ($keypair_detail){
							//$tmppos_1 = strpos($keypair_detail[0],$field_fldnm);
							if (trim($keypair_detail[0]) == trim($field_fldnm))
							{ 		
								if ($keypair_detail[1] != ""){
									$ocr_inv_date1 = $keypair_detail[1];
									$ocr_inv_date = date('Y-m-d', strtotime($ocr_inv_date1));
									$invoice_date_accuracy = $keypair_detail[2];
									break;
								}	
							}
						}
					}
				}
				//Invoice Amt		
				$field_fldnm = "";				
				$mapping_field_sql = db_query("SELECT field_name FROM water_ocr_mapping_field_and_values where mapped_with = 6 and field_value <> '' and template_id = $template_id");
				while($mapping_field = array_shift($mapping_field_sql)){
					$field_fldnm = $mapping_field['field_name'];
				}
				
				if ($field_fldnm != ""){
					$main_keypair_array = explode("|", $main_array[0]);
					foreach ($main_keypair_array as $keypair_array_detail)
					{
						$keypair_data = str_replace(["\r", "\n"], ' ', $keypair_array_detail);
						$keypair_data = str_replace("<br>", ' ', $keypair_data);
						
						//echo "Feidl Inv amt srch1 = " . $keypair_data . "<br>";
						$keypair_detail = explode("^", $keypair_data);
						if ($keypair_detail){
							//$tmppos_1 = strpos($keypair_detail[0],$field_fldnm);
							if (trim($keypair_detail[0]) == trim($field_fldnm))
							{ 		
								if ($keypair_detail[1] != ""){
									$invoice_amount = $keypair_detail[1];
									$invoice_amount = str_replace("$", "", $invoice_amount);
									$invoice_amount = str_replace(",", "", $invoice_amount);
									$invoice_amount_accuracy = $keypair_detail[2];
									break;
								}	
							}
						}
					}
				}
			}
			
			db_water_inbox_email();	
			$sql_upd = "Insert into $ocr_database (emailid, email_attachment, ocr_extract_text, ocr_done_on, 
			inv_number, inv_number_accuracy, account_no, account_no_found_flg, 	account_no_accuracy, invoice_date, invoice_date_accuracy, 
			company_id, vendor_id, invoice_amount, invoice_amount_accuracy, vendor_name, vendor_name_accuracy, invoice_due_date, template_id) 
			Select '" . $myrowsel_found["unqid"] . "', '" . str_replace("'", "\'" ,$myrowsel_found["attachmentname"]) . "', '" . str_replace("'", "\'" , $mainstr) . "', 
			'" . date("Y-m-d H:i:s") . "', '" . str_replace("'", "\'" ,$ocr_inv_number) . "', $inv_number_accuracy, '" . $account_no . "', $account_no_found_flg , $account_no_accuracy, 
			'" . $ocr_inv_date . "', $invoice_date_accuracy, '" . $company_id . "', '" . $vendor_id . "', '" . $invoice_amount . "', $invoice_amount_accuracy, 
			'" . str_replace("'", "\'" ,$vendor_name) . "', $vendor_name_accuracy , '" . $invoice_due_date . "', '" . $template_id . "' ";
			//echo $sql_upd . "<br>";
			$result_upd = db_query($sql_upd);
	
			$result_upd = db_query("Update tblemail_attachment set email_ocr_done = 1, email_ocr_done_on = '" . date("Y-m-d H:i:s") . "' where emailid = '" . $myrowsel_found["unqid"] . "'");
				
		}			
	}
echo "<br><b>Emails are inwarded by the program.</b>"
?>