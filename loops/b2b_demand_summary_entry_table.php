<?php 
require ("inc/header_session.php");
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>B2B Demand Summary</title>
    <style>
    .in_table_style {
        border-collapse: collapse;
    }

    .in_table_style tr td,
    .in_table_style tr th {
        border: 1px solid #FFF !important;
        padding: 8px !important;
    }

    .table1 tr td {
        /*background:#e4e4e4;*/
        font-size: 11px;
        font-family: Arial, Helvetica, sans-serif;
        color: "#333333";
    }

    .table2 tr td {
        border: 1px solid #FFF;
        padding-left: 8px;
    }

    .table2 tr:nth-child(3) td {
        border: none;
        padding-left: 0px;
    }

    .table3 tr td {
        border: 1px solid #FFF;
        padding-left: 8px;
    }

    h4.maintitle {
        font-size: 17px;
        padding: 3px;
        margin: 0px;
        font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
        color: #333333;
        border-bottom: 1px solid #ccc;
    }
    </style>
</head>

<body>
    <div style="width: 750px; text-align: center;">
        <h4 class="maintitle">Demand Entry Details</h4>
    </div>
    <br>
    <table width="100%" class="table1" cellpadding="3" cellspacing="1">
        <?php 
			$subheading="#ccd9e7";
			$rowcolor1="#e4e4e4";
			$rowcolor2="#ececec";
			//$buttonrow="#d5d5d5";
			$buttonrow="#ccd9e7";
			$subheading2="#d5d5d5";
		//---------------------------------------------
			$quote_id=$_REQUEST["quote_id"];
			$companyID=$_REQUEST["companyID"];
			$box_array=$_REQUEST["box_type"];
			if($_REQUEST["showquotedata"]==1)
			{
				//
				if ($box_array == "Gaylord Totes")
				{ 
					$box_table = "quote_gaylord";
					$quantity_request="g_quantity_request";
					$frequency_order="g_frequency_order";
					$notes="g_item_note";
					$prefix="g";
				}
				if ($box_array == "Shipping Boxes")
				{ 
					$box_table = "quote_shipping_boxes"; 
					$quantity_request="sb_quantity_requested";
					$frequency_order="sb_frequency_order";
					$notes="sb_notes";
					$prefix="sb";
				}
				if ($box_array == "Pallets")
				{ 
					$box_table = "quote_pallets"; 
					$quantity_request="pal_quantity_requested";
					$frequency_order="pal_frequency_order";
					$notes="pal_note";
					$prefix="pal";
				}
				if ($box_array == "Supersacks")
				{ 
					$box_table = "quote_supersacks"; 
					$quantity_request="sup_quantity_requested";
					$frequency_order="sup_frequency_order";
					$notes="sup_notes";
					$prefix="sup";
				}
				if ($box_array == "Other")
				{ 
					$box_table = "quote_other"; 
					$quantity_request="other_quantity_requested";
					$frequency_order="other_frequency_order";
					$notes="other_note";
					$prefix="other";
				}
				//
				
				if($box_array == "Gaylord Totes")
				{
					$getrecquery = "Select * from quote_request INNER JOIN quote_gaylord ON quote_request.quote_id = quote_gaylord.quote_id where quote_request.quote_id='".$quote_id."'";
				//
				//--------------------------------------------------------------------------------------------
                db();
				$g_res = db_query($getrecquery);
				//echo tep_db_num_rows($g_res);
				$chkinitials =  $_COOKIE['userinitials'];
				//
				$g_data = array_shift($g_res);
				$quote_item=$g_data["quote_item"];
				
				//Get Item Name
				$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=".$quote_item);
				//echo "Select * from quote_request_item where quote_rq_id=".$quote_item;
                db();
				$quote_item_rs = array_shift($getquotequery);
				$quote_item_name=$quote_item_rs['item'];
				//
				$quote_date = $g_data["quote_date"];
				//
				$g_id=$g_data["id"];
				//
                
                db();
				
				$g_quotereq_sales_flag = "";
				$chk_deny_query = "Select * from quote_gaylord where quote_id=".$g_data["quote_id"];
				$chk_deny_res = db_query($chk_deny_query);
   			    while($deny_row=array_shift($chk_deny_res)){
				  $g_quotereq_sales_flag = $deny_row["g_quotereq_sales_flag"];
				}
				
				 if ($g_quotereq_sales_flag == "Yes") {
					$quotereq_sales_flag_color ="#D3FFB9";
				 }else {
					$quotereq_sales_flag_color ="#C0CDDA";
				 }
			?>
        <tr bgcolor="#e4e4e4">
            <td style="background:<?php  echo $quotereq_sales_flag_color;?>; padding:5px;">
                <table cellpadding="3">
                    <tr>
                        <td style="background:<?php  echo $quotereq_sales_flag_color;?>;"><strong>Demand Entry ID:
                                <?php  echo $g_data["quote_id"]; ?>
                            </strong></td>
                        <td width="200px" style="background:<?php  echo $quotereq_sales_flag_color;?>;"><strong>Quote
                                Item:
                                <?php  echo $box_array; ?>
                            </strong></td>
                        <td style="background:<?php  echo $quotereq_sales_flag_color;?>;"></td>

                        <td style="background:<?php  echo $quotereq_sales_flag_color;?>;"></td>
                        <td style="background:<?php  echo $quotereq_sales_flag_color;?>;"></td>
                        <!-- <td style="background:<?php  echo $quotereq_sales_flag_color;?>;">
                                   <?php  //echo $g_quote_sent_status; ?>
                                </td> -->
                        <td>
                            <font face="Arial, Helvetica, sans-serif" size="1">
                                <?php  
								if($clientdash_flg==0 && $g_data["client_dash_flg"]==1)
								{
									echo "Viewable on Boomerang Portal";
								}else{
									echo "NOT Viewable on Boomerang Portal";
								}
							?>
                            </font>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <div id="g_sub_table<?php  echo $g_id?>">
                    <table width="100%" class="in_table_style">
                        <tr bgcolor="<?php  echo $subheading; ?>">
                            <td colspan="6"><strong>What Do They Buy?</strong></td>
                        </tr>
                        <tr bgcolor="<?php  echo $rowcolor1; ?>">
                            <td> Item </td>
                            <td colspan="5"> Gaylord Totes </td>
                        </tr>
                        <tr bgcolor="<?php  echo $rowcolor2; ?>">
                            <td>Ideal Size (in)</td>
                            <td width="130px">
                                <div class="size_align"> <span class="label_txt">L</span><br>
                                    <?php  echo $g_data["g_item_length"]; ?>
                                </div>
                            </td>
                            <td width="20px" align="center">x</td>
                            <td width="130px">
                                <div class="size_align"> <span class="label_txt">W</span><br>
                                    <?php  echo $g_data["g_item_width"]; ?>
                                </div>
                            </td>
                            <td width="20px" align="center">x</td>
                            <td width="130px">
                                <div class="size_align"> <span class="label_txt">H</span><br>
                                    <?php  echo $g_data["g_item_height"]; ?>
                                </div>
                            </td>
                        </tr>
                        <tr bgcolor="<?php  echo $rowcolor1; ?>">
                            <td> Quantity Requested </td>
                            <td colspan=5>
                                <?php  echo $g_data["g_quantity_request"]; ?>
                                <?php 
									if($g_data["g_quantity_request"]=="Other")
									{
										echo "<br>".$g_data["g_other_quantity"];
									}
									?>
                            </td>
                        </tr>
                        <tr bgcolor="<?php  echo $rowcolor2; ?>">
                            <td> Frequency of Order </td>
                            <td colspan=5>
                                <?php  echo $g_data["g_frequency_order"]; ?>
                            </td>
                        </tr>
                        <tr bgcolor="<?php  echo $rowcolor1; ?>">
                            <td> What Used For? </td>
                            <td colspan=5>
                                <?php  echo $g_data["g_what_used_for"]; ?>
                            </td>
                        </tr>
                        <!-- <tr bgcolor="<?php  echo $rowcolor2; ?>">
								<td>
									Date Needed By?
								</td>
								<td colspan=5>
									<?php  echo date("m/d/Y" , strtotime($g_data["date_needed_by"])); ?>
								</td>
							</tr> -->
                        <tr bgcolor="<?php  echo $rowcolor1; ?>">
                            <td> Also Need Pallets? </td>
                            <td colspan=5>
                                <?php  echo $g_data["need_pallets"]; ?>
                            </td>
                        </tr>
                        <tr bgcolor="<?php  echo $rowcolor1; ?>">
                            <td>
                                Desired Price
                            </td>
                            <td colspan=5>
                                $
                                <?php  echo $g_data["sales_desired_price_g"]; ?>
                            </td>
                        </tr>

                        <tr bgcolor="<?php  echo $rowcolor2; ?>">
                            <td> Notes </td>
                            <td colspan=5>
                                <?php  echo $g_data["g_item_note"]; ?>
                            </td>
                        </tr>
                        <tr bgcolor="<?php  echo $subheading2; ?>">
                            <td colspan="6"><strong>Criteria of what they SHOULD be able to use:</strong></td>
                        </tr>
                        <tr bgcolor="<?php  echo $rowcolor1; ?>">
                            <td>
                                <!-- align="right"-->
                                Height Flexibility
                            </td>
                            <td><span class="label_txt">Min</span> <br>
                                <?php  echo $g_data["g_item_min_height"]; ?>
                            </td>
                            <td align="center">-</td>
                            <td colspan="3"><span class="label_txt">Max</span>
                                <?php  echo $g_data["g_item_max_height"]; ?>
                            </td>
                        </tr>
                        <tr bgcolor="<?php  echo $rowcolor2; ?>">
                            <td> Shape </td>
                            <td> Rectangular </td>
                            <td>
                                <?php  
										echo $g_data["g_shape_rectangular"];
									
									?>
                            </td>
                            <td> Octagonal </td>
                            <td colspan="2">
                                <?php 
										echo $g_data["g_shape_octagonal"];
									?>
                            </td>
                        </tr>
                        <tr bgcolor="<?php  echo $rowcolor1; ?>">
                            <td rowspan="5"> # of Walls </td>
                            <td> 1ply </td>
                            <td>
                                <?php  
									echo $g_data["g_wall_1"];
									?>
                            </td>
                            <td> 6ply </td>
                            <td colspan="2">
                                <?php  
									echo $g_data["g_wall_6"];
									?>
                            </td>
                        </tr>
                        <tr bgcolor="<?php  echo $rowcolor1; ?>">
                            <td> 2ply </td>
                            <td>
                                <?php  
									echo $g_data["g_wall_2"];
									?>
                            </td>
                            <td> 7ply </td>
                            <td colspan="2">
                                <?php  
									echo $g_data["g_wall_7"];
									?>
                            </td>
                        </tr>
                        <tr bgcolor="<?php  echo $rowcolor1; ?>">
                            <td> 3ply </td>
                            <td>
                                <?php  
									echo $g_data["g_wall_3"];
									?>
                            </td>
                            <td> 8ply </td>
                            <td colspan="2">
                                <?php  
									echo $g_data["g_wall_8"];
									?>
                            </td>
                        </tr>
                        <tr bgcolor="<?php  echo $rowcolor1; ?>">
                            <td> 4ply </td>
                            <td>
                                <?php  
									echo $g_data["g_wall_4"];
									?>
                            </td>
                            <td> 9ply </td>
                            <td colspan="2">
                                <?php  
									echo $g_data["g_wall_9"];
									?>
                            </td>
                        </tr>
                        <tr bgcolor="<?php  echo $rowcolor1; ?>">
                            <td> 5ply </td>
                            <td>
                                <?php  
									echo $g_data["g_wall_5"];
									?>
                            </td>
                            <td> 10ply </td>
                            <td colspan="2">
                                <?php  
									echo $g_data["g_wall_10"];
									?>
                            </td>
                        </tr>
                        <tr bgcolor="<?php  echo $rowcolor2; ?>">
                            <td rowspan="2"> Top Config </td>
                            <td> No Top </td>
                            <td>
                                <?php  
									echo $g_data["g_no_top"];
									?>
                            </td>
                            <td> Lid Top </td>
                            <td colspan="2">
                                <?php  echo $g_data["g_lid_top"];
									?>
                            </td>
                        </tr>
                        <tr bgcolor="<?php  echo $rowcolor2; ?>">
                            <td> Partial Flap Top </td>
                            <td>
                                <?php  echo $g_data["g_partial_flap_top"];
									?>
                            </td>
                            <td> Full Flap Top </td>
                            <td colspan="2">
                                <?php  echo $g_data["g_full_flap_top"];
									?>
                            </td>
                        </tr>
                        <tr bgcolor="<?php  echo $rowcolor1; ?>">
                            <td rowspan="3"> Bottom Config </td>
                            <td> No Bottom </td>
                            <td>
                                <?php  echo $g_data["g_no_bottom_config"];
									?>
                            </td>
                            <td> Partial Flap w/ Slipsheet </td>
                            <td colspan="2">
                                <?php  echo $g_data["g_partial_flap_w"];
									?>
                            </td>
                        </tr>
                        <tr bgcolor="<?php  echo $rowcolor1; ?>">
                            <td> Tray Bottom </td>
                            <td>
                                <?php  echo $g_data["g_tray_bottom"];
									?>
                            </td>
                            <td> Full Flap Bottom </td>
                            <td colspan="2">
                                <?php  echo $g_data["g_full_flap_bottom"];
									?>
                            </td>
                        </tr>
                        <tr bgcolor="<?php  echo $rowcolor1; ?>">
                            <td> Partial Flap w/o SlipSheet </td>
                            <td colspan="4">
                                <?php  echo $g_data["g_partial_flap_wo"]; ?>
                            </td>
                        </tr>
                        <tr bgcolor="<?php  echo $rowcolor2; ?>">
                            <td> Vents Okay? </td>
                            <td colspan=5>
                                <?php  echo $g_data["g_vents_okay"];
									?>
                            </td>
                        </tr>
                        <tr bgcolor="<?php  echo $rowcolor2; ?>">
                            <td> High Value Opportunity </td>
                            <td colspan=5>
                                <?php  if ($g_data["high_value_target"] == 1) { echo "Yes"; } else {echo "No";}
									?>
                            </td>
                        </tr>
                        <tr bgcolor="<?php  echo $rowcolor2; ?>">
                            <td colspan="6" align="right" style="padding: 4px;"> Created
                                By:<?php  echo $g_data['user_initials']; ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                Date: <?php  echo date("m/d/Y H:i:s" , strtotime($g_data['quote_date'])); ?>
                                &nbsp;&nbsp;&nbsp; </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>

        <tr>
            <td style="background:#FFFFFF; height:4px;"></td>
        </tr>
        <?php 
				}//End gaylord totes
				//--------------------------------------------------------------------------------
				//
				//--------------------------------------------------------------------------------------------
				if($box_array == "Shipping Boxes")
				{
					$getrecquery = "Select * from quote_request INNER JOIN quote_shipping_boxes ON quote_request.quote_id = quote_shipping_boxes.quote_id where quote_request.quote_id='".$quote_id."'";
                    db();
					$sb_res= db_query($getrecquery);

					$chkinitials =  $_COOKIE['userinitials'];
					//
					$sb_data = array_shift($sb_res);
					$quote_item=$sb_data["quote_item"];
				?>
        <table width="100%" class="table1" cellpadding="3" cellspacing="1">
            <?php 
						//
						$quote_item=$sb_data["quote_item"];
						//Get Item Name
                        db();
						$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=".$quote_item);
						$quote_item_rs = array_shift($getquotequery);
						$quote_item_name=$quote_item_rs['item'];
						//
						$quote_date = $sb_data["quote_date"];
						//
						db();

						$sb_quotereq_sales_flag = "";
						$chk_deny_query = "Select * from quote_shipping_boxes where quote_id=".$quote_id;
                        db();
						$chk_deny_res = db_query($chk_deny_query);
						while($deny_row=array_shift($chk_deny_res)){
						  $sb_quotereq_sales_flag = $deny_row["sb_quotereq_sales_flag"];
						}
				
						 if ($sb_quotereq_sales_flag == "Yes") {
							$quotereq_sales_flag_color ="#D3FFB9";
						 }else {
							$quotereq_sales_flag_color ="#C0CDDA";
						 }
				
					?>
            <tr>
                <td colspan="4" style="background:<?php  echo $quotereq_sales_flag_color;?>; padding:5px;">
                    <table cellpadding="3">
                        <tr>
                            <td style="background:<?php  echo $quotereq_sales_flag_color;?>;"><strong>Demand Entry ID:
                                    <?php  echo $sb_data["quote_id"]; ?>
                                </strong></td>
                            <td width="200px" style="background:<?php  echo $quotereq_sales_flag_color;?>;">
                                <strong>Sales
                                    Item:
                                    <?php  echo $quote_item_name; ?>
                                </strong>
                            </td>
                            <td style="background:<?php  echo $quotereq_sales_flag_color;?>;"></td>
                            <td style="background:<?php  echo $quotereq_sales_flag_color;?>;"></td>
                            <td style="background:<?php  echo $quotereq_sales_flag_color;?>;"></td>
                            <td>
                                <font face="Arial, Helvetica, sans-serif" size="1">
                                    <?php  
									if($clientdash_flg==0 && $sb_data["client_dash_flg"]==1)
									{
										echo "Viewable on Boomerang Portal";
									}else{
										echo "NOT Viewable on Boomerang Portal";
									}
								?>
                                </font>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <div id="sb_sub_table<?php  echo $sb_id?>">
                        <table width="100%" class="in_table_style">
                            <tr bgcolor="<?php  echo $subheading; ?>">
                                <td colspan="6"><strong>What Do They Buy?</strong></td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                <td> Item </td>
                                <td colspan="5"> Shipping Boxes </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor2; ?>">
                                <td>Ideal Size (in)</td>
                                <td width="130px"><span class="label_txt">L</span><br>
                                    <?php  echo $sb_data["sb_item_length"]; ?>
                                </td>
                                <td width="20px" align="center">x</td>
                                <td width="130px">
                                    <div class="size_align"> <span class="label_txt">W</span><br>
                                        <?php  echo $sb_data["sb_item_width"]; ?>
                                    </div>
                                </td>
                                <td width="20px" align="center">x</td>
                                <td width="130px">
                                    <div class="size_align"> <span class="label_txt">H</span><br>
                                        <?php  echo $sb_data["sb_item_height"]; ?>
                                    </div>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                <td> Quantity Requested </td>
                                <td colspan=5>
                                    <?php  echo $sb_data["sb_quantity_requested"]; ?>
                                    <?php 
									if($sb_data["sb_quantity_requested"]=="Other")
									{
										echo "<br>".$sb_data["sb_other_quantity"];
									}
									?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor2; ?>">
                                <td> Frequency of Order </td>
                                <td colspan=5>
                                    <?php  echo $sb_data["sb_frequency_order"]; ?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                <td> What Used For? </td>
                                <td colspan=5>
                                    <?php  echo $sb_data["sb_what_used_for"]; ?>
                                </td>
                            </tr>
                            <!-- <tr bgcolor="<?php  echo $rowcolor2; ?>">
								<td>
									Date Needed By?
								</td>
								<td colspan=5>
									<?php  echo date("m/d/Y" , strtotime($sb_data["sb_date_needed_by"])); ?>
								</td>
							</tr> -->
                            <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                <td> Also Need Pallets? </td>
                                <td colspan=5>
                                    <?php  echo $sb_data["sb_need_pallets"]; ?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                <td>
                                    Desired Price
                                </td>
                                <td colspan=5>
                                    $
                                    <?php  echo $sb_data["sb_sales_desired_price"]; ?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor2; ?>">
                                <td> Notes </td>
                                <td colspan=5>
                                    <?php  echo $sb_data["sb_notes"]; ?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $subheading2; ?>">
                                <td colspan="6"><strong>Criteria of what they SHOULD be able to use:</strong></td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                <td colspan="6"><strong>Size Flexibility</strong></td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor2; ?>">
                                <td>
                                    <!-- align="right"-->
                                    Length
                                </td>
                                <td><span class="label_txt">Min</span> <br>
                                    <?php  echo $sb_data["sb_item_min_length"]; ?>
                                </td>
                                <td align="center">-</td>
                                <td colspan="3"><span class="label_txt">Max</span>
                                    <?php  echo $sb_data["sb_item_max_length"]; ?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                <td>
                                    <!-- align="right"-->
                                    Width
                                </td>
                                <td><span class="label_txt">Min</span> <br>
                                    <?php  echo $sb_data["sb_item_min_width"]; ?>
                                </td>
                                <td align="center">-</td>
                                <td colspan="3"><span class="label_txt">Max</span>
                                    <?php  echo $sb_data["sb_item_max_width"]; ?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor2; ?>">
                                <td>
                                    <!-- align="right"-->
                                    Height
                                </td>
                                <td><span class="label_txt">Min</span> <br>
                                    <?php  echo $sb_data["sb_item_min_height"]; ?>
                                </td>
                                <td align="center">-</td>
                                <td colspan="3"><span class="label_txt">Max</span>
                                    <?php  echo $sb_data["sb_item_max_height"]; ?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                <td>
                                    <!-- align="right"-->
                                    Cubic Footage
                                </td>
                                <td><span class="label_txt">Min</span> <br>
                                    <?php  echo $sb_data["sb_cubic_footage_min"]; ?>
                                </td>
                                <td align="center">-</td>
                                <td colspan="3"><span class="label_txt">Max</span>
                                    <?php  echo $sb_data["sb_cubic_footage_max"]; ?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor2; ?>">
                                <td> # of Walls </td>
                                <td> 1ply </td>
                                <td>
                                    <?php  echo $sb_data["sb_wall_1"]; ?>
                                </td>
                                <td> 2ply </td>
                                <td colspan="2">
                                    <?php  echo $sb_data["sb_wall_2"]; ?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                <td> Top Config </td>
                                <td> No Top </td>
                                <td>
                                    <?php  echo $sb_data["sb_no_top"]; ?>
                                </td>
                                <td> Full Flap Top </td>
                                <td colspan="2">
                                    <?php  echo $sb_data["sb_full_flap_top"]; ?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor2; ?>">
                                <td> Bottom Config </td>
                                <td> No Bottom </td>
                                <td>
                                    <?php  echo $sb_data["sb_no_bottom"]; ?>
                                </td>
                                <td> Full Flap Bottom </td>
                                <td colspan="2">
                                    <?php  echo $sb_data["sb_full_flap_bottom"]; ?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                <td> Vents Okay? </td>
                                <td colspan=5>
                                    <?php  echo $sb_data["sb_vents_okay"]; ?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                <td> High Value Opportunity </td>
                                <td colspan=5>
                                    <?php  if ($sb_data["high_value_target"] == 1) { echo "Yes"; } else {echo "No";}?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor2; ?>">
                                <td colspan="6" align="right" style="padding: 4px;"> Created
                                    By:<?php  echo $sb_data['user_initials']; ?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    Date: <?php  echo date("m/d/Y H:i:s" , strtotime($sb_data['quote_date'])); ?>
                                    &nbsp;&nbsp;&nbsp; </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="background:#FFFFFF; height:4px;"></td>
            </tr>
            <?php 
			?>
        </table>
        <?php 
				}//End shipping boxes
				//--------------------------------------------------------------------------------------------
				if($box_array == "Pallets")
				{
				?>
        <table width="100%" class="table1" cellpadding="3" cellspacing="1">
            <?php 
					//
						$getrecquery = "Select * from quote_request INNER JOIN quote_pallets ON quote_request.quote_id = quote_pallets.quote_id where quote_request.quote_id='".$quote_id."'";
						//
                        db();
						$pal_res= db_query($getrecquery);
						$pal_data = array_shift($pal_res);
						//
						$quote_item=$pal_data["quote_item"];
						//Get Item Name
						$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=".$quote_item);
						$quote_item_rs = array_shift($getquotequery);
						$quote_item_name=$quote_item_rs['item'];
						//
						$quote_date = $pal_data["quote_date"];
						//
						db();
						//
						$pal_quotereq_sales_flag = "";
						$chk_deny_query = "Select * from quote_pallets where quote_id=".$pal_data["quote_id"];
						$chk_deny_res = db_query($chk_deny_query);
						while($deny_row=array_shift($chk_deny_res)){
						  $pal_quotereq_sales_flag = $deny_row["pal_quotereq_sales_flag"];
						}
				
						 if ($pal_quotereq_sales_flag == "Yes") {
							$quotereq_sales_flag_color ="#D3FFB9";
						 }else {
							$quotereq_sales_flag_color ="#C0CDDA";
						 }

			?>
            <tr>
                <td colspan="4" style="background:<?php  echo $quotereq_sales_flag_color;?>; padding:5px;">
                    <table cellpadding="3">
                        <tr>
                            <td style="background:<?php  echo $quotereq_sales_flag_color;?>;"><strong>Demand Entry ID:
                                    <?php  echo $pal_data["quote_id"]; ?>
                                </strong></td>
                            <td width="200px" style="background:<?php  echo $quotereq_sales_flag_color;?>;">
                                <strong>Quote
                                    Item:
                                    <?php  echo $quote_item_name; ?>
                                </strong>
                            </td>
                            <td style="background:<?php  echo $quotereq_sales_flag_color;?>;"></td>
                            <td style="background:<?php  echo $quotereq_sales_flag_color;?>;"></td>
                            <td style="background:<?php  echo $quotereq_sales_flag_color;?>;"></td>
                            <td>
                                <font face="Arial, Helvetica, sans-serif" size="1">
                                    <?php  
										if($clientdash_flg==0 && $pal_data["client_dash_flg"]==1)
										{
											echo "Viewable on Boomerang Portal";
										}else{
											echo "NOT Viewable on Boomerang Portal";
										}
									?>
                                </font>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <div id="pal_sub_table<?php  echo $pal_id?>">
                        <table width="100%" class="in_table_style">
                            <tr bgcolor="<?php  echo $subheading; ?>">
                                <td colspan="6"><strong>What Do They Buy?</strong></td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                <td> Item </td>
                                <td colspan="5"> Pallets </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor2; ?>">
                                <td>Ideal Size (in)</td>
                                <td width="130px">
                                    <div class="size_align"> <span class="label_txt">L</span><br>
                                        <?php  echo $pal_data["pal_item_length"]; ?>
                                    </div>
                                </td>
                                <td width="20px" align="center">x</td>
                                <td width="130px">
                                    <div class="size_align"> <span class="label_txt">W</span><br>
                                        <?php  echo $pal_data["pal_item_width"]; ?>
                                    </div>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                <td> Quantity Requested </td>
                                <td colspan=5>
                                    <?php  echo $pal_data["pal_quantity_requested"]; ?>
                                    <?php 
										if($pal_data["pal_quantity_requested"]=="Other")
										{
											echo "<br>".$pal_data["pal_other_quantity"];
										}
										?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor2; ?>">
                                <td> Frequency of Order </td>
                                <td colspan=5>
                                    <?php  echo $pal_data["pal_frequency_order"]; ?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                <td> What Used For? </td>
                                <td colspan=5>
                                    <?php  echo $pal_data["pal_what_used_for"]; ?>
                                </td>
                            </tr>
                            <!-- <tr bgcolor="<?php  echo $rowcolor2; ?>">
									<td>
										Date Needed By?
									</td>
									<td colspan=5>
										<?php  echo date("m/d/Y" , strtotime($pal_data["pal_date_needed_by"])); ?>
									</td>
								</tr> -->
                            <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                <td>
                                    Desired Price
                                </td>
                                <td colspan=5>
                                    $
                                    <?php  echo $pal_data["pal_sales_desired_price"]; ?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor2; ?>">
                                <td> Notes </td>
                                <td colspan=5>
                                    <?php  echo $pal_data["pal_note"]; ?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor2; ?>">
                                <td> High Value Opportunity </td>
                                <td colspan=5>
                                    <?php  if ($pal_data["high_value_target"] == 1) { echo "Yes"; } else {echo "No";}?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor2; ?>">
                                <td colspan="6" align="right" style="padding: 4px;"> Created
                                    By:<?php  echo $pal_data['user_initials']; ?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    Date: <?php  echo date("m/d/Y H:i:s" , strtotime($pal_data['quote_date'])); ?>
                                    &nbsp;&nbsp;&nbsp; </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="background:#FFFFFF; height:4px;"></td>
            </tr>
        </table>
        <?php 	
				}//End Pallets
				//--------------------------------------------------------------------------------------------
				if($box_array == "Supersacks")
				{
				?>
        <table width="100%" class="table1" cellpadding="3" cellspacing="1">
            <?php 
						$getrecquery = "Select * from quote_request INNER JOIN quote_supersacks ON quote_request.quote_id = quote_supersacks.quote_id where quote_request.quote_id='".$quote_id."'";
						//
                        db();
						$sup_res= db_query($getrecquery);
						$sup_data = array_shift($sup_res);
					
						$quote_item=$sup_data["quote_item"];
						//Get Item Name
						$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=".$quote_item);
						$quote_item_rs = array_shift($getquotequery);
						$quote_item_name=$quote_item_rs['item'];
						//
						$quote_date = $sup_data["quote_date"];
						//
						//
						db();
				
						$sup_quotereq_sales_flag = "";
						$chk_deny_query = "Select * from quote_supersacks where quote_id=".$sup_data["quote_id"];
						$chk_deny_res = db_query($chk_deny_query);
						while($deny_row=array_shift($chk_deny_res)){
						  $sup_quotereq_sales_flag = $deny_row["sup_quotereq_sales_flag"];
						}

						 if ($sup_quotereq_sales_flag == "Yes") {
							$quotereq_sales_flag_color ="#D3FFB9";
						 }else {
							$quotereq_sales_flag_color ="#C0CDDA";
						 }
				
					?>
            <tr>
                <td colspan="4" style="background:<?php  echo $quotereq_sales_flag_color;?>; padding:5px;">
                    <table cellpadding="3">
                        <tr>
                            <td style="background:<?php  echo $quotereq_sales_flag_color;?>;"><strong>Demand Entry ID:
                                    <?php  echo $sup_data["quote_id"]; ?>
                                </strong></td>
                            <td width="200px" style="background:<?php  echo $quotereq_sales_flag_color;?>;">
                                <strong>Quote
                                    Item:
                                    <?php  echo $quote_item_name; ?>
                                </strong>
                            </td>
                            <td style="background:<?php  echo $quotereq_sales_flag_color;?>;"></td>
                            <td style="background:<?php  echo $quotereq_sales_flag_color;?>;"></td>
                            <td style="background:<?php  echo $quotereq_sales_flag_color;?>;"></td>

                            <td>
                                <font face="Arial, Helvetica, sans-serif" size="1">
                                    <?php  
									if($clientdash_flg==0 && $sup_data["client_dash_flg"]==1)
									{
										echo "Viewable on Boomerang Portal";
									}else{
										echo "NOT Viewable on Boomerang Portal";
									}
								?>
                                </font>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <div id="sup_sub_table<?php  echo $sup_id?>">
                        <table class="table_sup" width="100%">
                            <tr>
                                <td>
                                    <table width="100%" class="in_table_style">
                                        <tr bgcolor="<?php  echo $subheading; ?>">
                                            <td colspan="6"><strong>What Do They Buy?</strong></td>
                                        </tr>
                                        <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                            <td> Item </td>
                                            <td colspan="5"> Supersacks </td>
                                        </tr>
                                        <tr bgcolor="<?php  echo $rowcolor2; ?>">
                                            <td>Ideal Size (in)</td>
                                            <td width="130px">
                                                <div class="size_align"> <span class="label_txt">L</span><br>
                                                    <?php  echo $sup_data["sup_item_length"]; ?>
                                                </div>
                                            </td>
                                            <td width="20px" align="center">x</td>
                                            <td width="130px">
                                                <div class="size_align"> <span class="label_txt">W</span><br>
                                                    <?php  echo $sup_data["sup_item_width"]; ?>
                                                </div>
                                            </td>
                                            <td width="20px" align="center">x</td>
                                            <td width="130px">
                                                <div class="size_align"> <span class="label_txt">H</span><br>
                                                    <?php  echo $sup_data["sup_item_height"]; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                            <td> Quantity Requested </td>
                                            <td colspan=5>
                                                <?php  echo $sup_data["sup_quantity_requested"]; ?>
                                                <?php 
												if($sup_data["sup_quantity_requested"]=="Other")
												{
													echo "<br>".$sup_data["sup_other_quantity"];
												}
												?>
                                            </td>
                                        </tr>
                                        <tr bgcolor="<?php  echo $rowcolor2; ?>">
                                            <td> Frequency of Order </td>
                                            <td colspan=5>
                                                <?php  echo $sup_data["sup_frequency_order"]; ?>
                                            </td>
                                        </tr>
                                        <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                            <td> What Used For? </td>
                                            <td colspan=5>
                                                <?php  echo $sup_data["sup_what_used_for"]; ?>
                                            </td>
                                        </tr>
                                        <!-- <tr bgcolor="<?php  echo $rowcolor2; ?>">
											<td>
												Date Needed By?
											</td>
											<td colspan=5>
												<?php  echo date("m/d/Y" , strtotime($sup_data["sup_date_needed_by"])); ?>
											</td>
										</tr> -->
                                        <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                            <td> Also Need Pallets? </td>
                                            <td colspan=5>
                                                <?php  echo $sup_data["sup_need_pallets"]; ?>
                                            </td>
                                        </tr>
                                        <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                            <td>
                                                Desired Price
                                            </td>
                                            <td colspan=5>
                                                $
                                                <?php  echo $sup_data["sup_sales_desired_price"]; ?>
                                            </td>
                                        </tr>
                                        <tr bgcolor="<?php  echo $rowcolor2; ?>">
                                            <td> Notes </td>
                                            <td colspan=5>
                                                <?php  echo $sup_data["sup_notes"]; ?>
                                            </td>
                                        </tr>

                                        <tr bgcolor="<?php  echo $rowcolor2; ?>">
                                            <td> High Value Opportunity </td>
                                            <td colspan=5>
                                                <?php  if ($sup_data["high_value_target"] == 1) { echo "Yes"; } else {echo "No";}?>
                                            </td>
                                        </tr>

                                        <tr bgcolor="<?php  echo $rowcolor2; ?>">
                                            <td colspan="6" align="right" style="padding: 4px;"> Created
                                                By:<?php  echo $sup_data['user_initials']; ?>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                Date:
                                                <?php  echo date("m/d/Y H:i:s" , strtotime($sup_data['quote_date'])); ?>
                                                &nbsp;&nbsp;&nbsp; </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="background:#FFFFFF; height:4px;"></td>
            </tr>

        </table>
        <?php 
				}//End Supersacks
				//--------------------------------------------------------------------------------------------
				if($box_array == "Other")
				{
				?>
        <table width="100%" class="table1" cellpadding="3" cellspacing="1">
            <?php 
					//
						$getrecquery = "Select * from quote_request INNER JOIN quote_other ON quote_request.quote_id = quote_other.quote_id where quote_request.quote_id='".$quote_id."'";
						//
                        db();
						$other_res= db_query($getrecquery);
						$other_data = array_shift($other_res);
					
						$quote_item=$other_data["quote_item"];
						//Get Item Name
						$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=".$quote_item);
						$quote_item_rs = array_shift($getquotequery);
						$quote_item_name=$quote_item_rs['item'];
						//
						$quote_date = $other_data["quote_date"];
						//
						
						db();
						//
						$other_quotereq_sales_flag = "";
						$chk_deny_query = "Select * from quote_other where quote_id=".$other_data["quote_id"];
						$chk_deny_res = db_query($chk_deny_query);
						while($deny_row=array_shift($chk_deny_res)){
						  $other_quotereq_sales_flag = $deny_row["other_quotereq_sales_flag"];
						}
				
						 if ($other_quotereq_sales_flag == "Yes") {
							$quotereq_sales_flag_color ="#D3FFB9";
						 }else {
							$quotereq_sales_flag_color ="#C0CDDA";
						 }
					?>
            <tr>
                <td colspan="4" style="background:<?php  echo $quotereq_sales_flag_color;?>; padding:5px;">
                    <table cellpadding="3">
                        <tr>
                            <td style="background:<?php  echo $quotereq_sales_flag_color;?>;"><strong>Demand Entry ID:
                                    <?php  echo $other_data["quote_id"]; ?>
                                </strong></td>
                            <td width="200px" style="background:<?php  echo $quotereq_sales_flag_color;?>;">
                                <strong>Quote
                                    Item:
                                    <?php  echo $quote_item_name; ?>
                                </strong>
                            </td>
                            <td style="background:<?php  echo $quotereq_sales_flag_color;?>;"></td>
                            <td style="background:<?php  echo $quotereq_sales_flag_color;?>;"></td>
                            <td style="background:<?php  echo $quotereq_sales_flag_color;?>;"></td>
                            <td>
                                <font face="Arial, Helvetica, sans-serif" size="1">
                                    <?php  
										if($clientdash_flg==0 && $other_data["client_dash_flg"]==1)
										{
											echo "Viewable on Boomerang Portal";
										}else{
											echo "NOT Viewable on Boomerang Portal";
										}
									?>
                                </font>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <div id="other_sub_table<?php  echo $other_id?>">
                        <table width="100%" class="in_table_style">
                            <tr bgcolor="<?php  echo $subheading; ?>">
                                <td colspan="6"><strong>What Do They Buy?</strong></td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                <td> Quantity Requested </td>
                                <td colspan=5>
                                    <?php  echo $other_data["other_quantity_requested"]; ?>
                                    <?php 
										if($other_data["other_quantity_requested"]=="Other")
										{
											echo "<br>".$other_data["other_other_quantity"];
										}
										?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor2; ?>">
                                <td> Frequency of Order </td>
                                <td colspan=5>
                                    <?php  echo $other_data["other_frequency_order"]; ?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                <td> What Used For? </td>
                                <td colspan=5>
                                    <?php  echo $other_data["other_what_used_for"]; ?>
                                </td>
                            </tr>
                            <!-- <tr bgcolor="<?php  echo $rowcolor2; ?>">
											<td>
												Date Needed By?
											</td>
											<td colspan=5>
												<?php  echo date("m/d/Y" , strtotime($other_data["other_date_needed_by"])); ?>
											</td>
										</tr> -->
                            <tr bgcolor="<?php  echo $rowcolor1; ?>">
                                <td> Also Need Pallets? </td>
                                <td colspan=5>
                                    <?php  echo $other_data["other_need_pallets"]; ?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php  echo $rowcolor2; ?>">
                                <td> Notes </td>
                                <td colspan=5>
                                    <?php  echo $other_data["other_note"]; ?>
                                </td>
                            </tr>

                            <tr bgcolor="<?php  echo $rowcolor2; ?>">
                                <td> High Value Opportunity </td>
                                <td colspan=5>
                                    <?php  if ($other_data["high_value_target"] == 1) { echo "Yes"; } else {echo "No";}?>
                                </td>
                            </tr>

                            <tr bgcolor="<?php  echo $rowcolor2; ?>">
                                <td colspan="6" align="right" style="padding: 4px;"> Created
                                    By:<?php  echo $other_data['user_initials']; ?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    Date: <?php  echo date("m/d/Y H:i:s" , strtotime($other_data['quote_date'])); ?>
                                    &nbsp;&nbsp;&nbsp; </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="background:#FFFFFF; height:4px;"></td>
            </tr>
        </table>
        <?php 
				}//End others
				//
			}//End if 
			?>
    </table>
</body>

</html>