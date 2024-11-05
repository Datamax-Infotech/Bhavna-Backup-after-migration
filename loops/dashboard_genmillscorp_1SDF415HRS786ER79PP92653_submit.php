<?php 	
// require ("inc/header_session.php");
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
db();
$query = "SELECT * FROM loop_warehouse WHERE id = 504" ;
$res = db_query($query );
$warehouse_info = array_shift($res);
$warehouses = "447, 504, 532, 567, 616, 694, 718, 738, 787, 955, 1073, 1074, 1076, 1077,  1089, 1411, 1828, 2019 ";
function string_to_date(string $a, string $b): string{
	$start = explode("/", $a);
	$start_date = "0000-00-00 00:00:01";
	if ($start[2] != "") {
	if ($start[2] == 11 || $start[2] == 10) $start[2] = "20" . $start["2"];
	$start_date = $start[2] . "-" . $start[0] . "-" . $start[1] . " " . $b;
	}
	return $start_date;
}
?>
<html>
<head>
	<title>General Mills - Processed Trailer Report</title>
<style type="text/css">
.style7 {
	font-size: x-small;
	font-family: Arial, Helvetica, sans-serif;
	color: #333333;
	background-color: #FFCC66;
}
.style5 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	text-align: center;
	background-color: #99FF99;
}
.style6 {
	text-align: center;
	background-color: #99FF99;
}
.style2 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
}
.style3 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	color: #333333;
}
.style8 {
	text-align: left;
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	color: #333333;
}
.style11 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	color: #333333;
	text-align: center;
}
.style10 {
	text-align: left;
}
.style12 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	color: #333333;
	text-align: right;
}
.style12center {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	color: #333333;
	text-align: center;
}
.style12right {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	color: #333333;
	text-align: right;
}
.style12left {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	color: #333333;
	text-align: left;
}
.style13 {
	font-family: Arial, Helvetica, sans-serif;
}
.style14 {
	font-size: x-small;
}
.style15 {
	font-size: x-small;
}
.style16 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	background-color: #99FF99;
}
.style17 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	color: #333333;
	background-color: #99FF99;
}
select, input {
font-family: Arial, Helvetica, sans-serif; 
font-size: 12px; 
color : #000000; 
font-weight: normal; 
}
</style>	
</head>
<body>
<table width="100%">
	<tr>
		<td>
			<img src="images/<?php echo $warehouse_info["logo"]; ?>">
		</td>

		<td colspan="20" align="right">
			<img src="new_interface_help.gif">
		</td>
	</tr>
</table>
<!------------- McCormick Trailer Report ---------------->

<?php


if ($_REQUEST["action"] == 'run') {
$start_date = date('Ymd', strtotime($_REQUEST['start_date']));
$end_date = date('Ymd', strtotime($_REQUEST['end_date']));
$start_date = date('Ymd', $start_date);
$end_date = date('Ymd', $end_date + 86400);

if ($start_date > $end_date) {
echo "<font size=4 color=red>Error: End Date before Start Date</font>";
}

?>

<table width=1400>
<tr>
<td>

<input type=hidden name="surveyview" value="<?php echo $_REQUEST["surveyview"];?>">
<input type=hidden name="start_date" value="<?php echo $_REQUEST["start_date"];?>">
<input type=hidden name="end_date" value="<?php echo $_REQUEST["end_date"];?>">
<input type=hidden name="action" value="run">
<table cellSpacing="1" cellPadding="1" width="1250" border="0">

	<tr align="middle">
		<td colSpan="10" class="style12left">
		 </td>
	</tr>	
	<tr align="middle">
		<td colSpan="10" class="style7">
		<b>GENERAL MILLS SORTED TRAILER REPORT FROM <?php echo $_REQUEST["start_date"];?> TO <?php echo $_REQUEST["end_date"];?></b></td>
	</tr>
	<tr>
		<td style="width: 450" class="style17" align="center">
		<b>BOX DESCRIPTION</b></font></td>

<?php
if ($_REQUEST["consolidate"] != 'T') {
?>

		<td style="width: 75" class="style17" align="center">
		<b>LOCATION</b></font></td>
<?php } ?>

		<td style="width: 75" class="style17" align="center">
		<b># GOOD</b></font></td>
		<td style="width: 75" class="style17" align="center">
		<b># BAD</b></font></td>
		<td style="width: 75" class="style17" align="center">
		<b>GOOD WEIGHT</b></font></td>
		<td style="width: 75" class="style17" align="center">
		<b>BAD WEIGHT</b></font></td>
		<td class="style5" style="width: 75" align="center">
		<b>AVERAGE<br>PRICE</b></td>
		<td align="middle" style="width: 75" class="style16" align="center">
		<b>TOTAL VALUE</b></td>
	
	</tr>
	
<?php
if ($_REQUEST["consolidate"] == 'T') {
$query = "SELECT *, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) AS A, sum(loop_boxes_sort.boxgood) AS B, sum(loop_boxes_sort.boxbad) AS C, sum(loop_boxes_sort.boxgood)*loop_boxes.bweight AS D, sum(loop_boxes_sort.boxbad)*loop_boxes.bweight AS E FROM loop_boxes_sort INNER JOIN loop_transaction ON loop_transaction.id = loop_boxes_sort.trans_rec_id INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_transaction.warehouse_id IN (" . $warehouses . ") AND loop_transaction.transaction_date BETWEEN '" . string_to_date($_REQUEST["start_date"],"00:00:00") . "' AND '" . string_to_date($_REQUEST["end_date"],"23:59:59") . "' AND loop_boxes.isbox LIKE 'Y' GROUP BY loop_boxes_sort.box_id ORDER BY A DESC";
} else {
$query = "SELECT *, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) AS A, sum(loop_boxes_sort.boxgood) AS B, sum(loop_boxes_sort.boxbad) AS C, sum(loop_boxes_sort.boxgood)*loop_boxes.bweight AS D, sum(loop_boxes_sort.boxbad)*loop_boxes.bweight AS E FROM loop_boxes_sort INNER JOIN loop_transaction ON loop_transaction.id = loop_boxes_sort.trans_rec_id INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_transaction.warehouse_id IN (" . $warehouses . ") AND loop_transaction.transaction_date BETWEEN '" . string_to_date($_REQUEST["start_date"],"00:00:00") . "' AND '" . string_to_date($_REQUEST["end_date"],"23:59:59") . "' AND loop_boxes.isbox LIKE 'Y' GROUP BY loop_transaction.warehouse_id, loop_boxes_sort.box_id ORDER BY loop_transaction.warehouse_id ASC, A DESC";
}
$goodtot = 0;
$goodweight = 0;
$badweight = 0;
$badtot = 0;
$subtotal_goodtot = 0;
$subtotal_goodweight = 0;
$subtotal_badweight = 0;
$subtotal_badtot = 0;
$subtotal = 0;
$grandtotal = 0;
$curr_warehouse_id = 0;
$res = db_query($query );
while($row = array_shift($res))
{

	if ($curr_warehouse_id > 0 && $curr_warehouse_id != $row["warehouse_id"] && $_REQUEST["consolidate"] != 'T')
	{
	?>
	<tr>

	 <td bgColor="#e4e4e4" class="style12right" ><b>Subtotal</b>
	</td>
<?php
if ($_REQUEST["consolidate"] != 'T') {
$qn = "SELECT * FROM loop_warehouse where id = " . $curr_warehouse_id;
$resn = db_query($qn );
$rn = array_shift($resn);


?>

		<td bgColor="#e4e4e4" class="style12right">
		<b><?php echo $rn["warehouse_name"];?></font></b></td>
<?php } ?>
	<td bgColor="#e4e4e4" class="style12right" ><b><?php echo number_format($subtotal_goodtot,0);?></b></td>
	<td bgColor="#e4e4e4" class="style12right" ><b><?php echo number_format($subtotal_badtot,0);?></b></td>
	<td bgColor="#e4e4e4" class="style12right" ><b><?php echo number_format($subtotal_goodweight,2);?></b></td>
	<td bgColor="#e4e4e4" class="style12right" ><b><?php echo number_format($subtotal_badweight,2);?></b></td>
	<td bgColor="#e4e4e4" class="style12right" ><b><?php echo number_format($subtotal/($subtotal_goodtot + $subtotal_badtot),3);?></b></td>
	<td bgColor="#e4e4e4" class="style12right" ><b>$<?php echo number_format($subtotal,2);?></b></td>
	</tf>
	<?php
	
$subtotal_goodtot = 0;
$subtotal_goodweight = 0;
$subtotal_badweight = 0;
$subtotal_badtot = 0;
$subtotal = 0;
	}
	$curr_warehouse_id = $row["warehouse_id"];
	

	if ($row["B"] > 0 || $row["C"] > 0) 
	{
	?>
		<tr>

      <td bgColor="#e4e4e4" class="style12left" >
      <?php if ($row["isbox"]=='Y') { ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row["blength"];?> <?php echo $row["blength_frac"];?> x
      <?php echo $row["bwidth"];?> <?php echo $row["bwidth_frac"];?> x 
      <?php echo $row["bdepth"];?> <?php echo $row["bdepth_frac"];?>
      <?php } ?>
      <?php
      	if ($row["bwall"] > 1)
      	{
      		echo " " . $row["bwall"] . "-Wall ";
      	}
      ?>
      <?php echo $row["bdescription"];?></td><?php
if ($_REQUEST["consolidate"] != 'T') {
$qn = "SELECT * FROM loop_warehouse where id = " . $row["warehouse_id"];
$resn = db_query($qn );
$rn = array_shift($resn);


?>

		<td bgColor="#e4e4e4" class="style12right">
		<?php echo $rn["warehouse_name"];?></font></td>
<?php } ?>
      <td bgColor="#e4e4e4" class="style12right" ><?php echo number_format($row["B"],0);?></td>
      <td bgColor="#e4e4e4" class="style12right" ><?php echo number_format($row["C"],0);?></td>
      <td bgColor="#e4e4e4" class="style12right" ><?php echo number_format($row["D"],2);?></td>
      <td bgColor="#e4e4e4" class="style12right" ><?php echo number_format($row["E"],2);?></td>
      <td bgColor="#e4e4e4" class="style12right" >$<?php echo number_format($row["A"]/$row["B"],3) ;?></td>
      <td bgColor="#e4e4e4" class="style12right" >$<?php echo number_format($row["A"],2) ;?></td>

	  </tr>
	
				
<?php
	$goodtot += $row["B"];
	$badtot += $row["C"];
	$goodweight += $row["D"];
	$badweight += $row["E"];
	$subtotal_goodtot += $row["B"];
	$subtotal_badtot += $row["C"];
	$subtotal_goodweight += $row["D"];
	$subtotal_badweight += $row["E"];
	$subtotal += $row["A"];
	$grandtotal += $row["A"];
	}
}

	if ($curr_warehouse_id > 0 && $curr_warehouse_id != $row["warehouse_id"] && $_REQUEST["consolidate"] != 'T')
	{
	?>
	<tr>

	 <td bgColor="#e4e4e4" class="style12right" ><b>Subtotal</b>
	</td>
<?php
if ($_REQUEST["consolidate"] != 'T') {
$qn = "SELECT * FROM loop_warehouse where id = " . $curr_warehouse_id;
$resn = db_query($qn );
$rn = array_shift($resn);


?>

		<td bgColor="#e4e4e4" class="style12right">
		<b><?php echo $rn["warehouse_name"];?></font></b></td>
<?php } ?>
	<td bgColor="#e4e4e4" class="style12right" ><b><?php echo number_format($subtotal_goodtot,0);?></b></td>
	<td bgColor="#e4e4e4" class="style12right" ><b><?php echo number_format($subtotal_badtot,0);?></b></td>
	<td bgColor="#e4e4e4" class="style12right" ><b><?php echo number_format($subtotal_goodweight,2);?></b></td>
	<td bgColor="#e4e4e4" class="style12right" ><b><?php echo number_format($subtotal_badweight,2);?></b></td>
	<td bgColor="#e4e4e4" class="style12right" ><b><?php echo number_format($subtotal/($subtotal_goodtot + $subtotal_badtot),3);?></b></td>
	<td bgColor="#e4e4e4" class="style12right" ><b>$<?php echo number_format($subtotal,2);?></b></td>
	</tf>
	<?php
	
$subtotal_goodtot = 0;
$subtotal_goodweight = 0;
$subtotal_badweight = 0;
$subtotal_badtot = 0;
$subtotal = 0;
	}

?>
		<tr>

      <td bgColor="#e4e4e4" class="style12left" >
      <b>BOX TOTAL</b></td>
<?php
if ($_REQUEST["consolidate"] != 'T') {
?>

		<td bgColor="#e4e4e4" class="style12right" > </td>
<?php } ?>
      <td bgColor="#e4e4e4" class="style12right" ><b><?php echo number_format($goodtot, 0);?></b></td>
      <td bgColor="#e4e4e4" class="style12right" ><b><?php echo number_format($badtot, 0) ;?></b></td>
      <td bgColor="#e4e4e4" class="style12right" ><b><?php echo number_format($goodweight, 2);?></b></td>
      <td bgColor="#e4e4e4" class="style12right" ><b><?php echo number_format($badweight, 2) ;?></b></td>
      <td bgColor="#e4e4e4" class="style12right" ><b>$<?php echo number_format($grandtotal/$goodtot,3) ;?></b></td>
      <td bgColor="#e4e4e4" class="style12right" ><b>$<?php echo number_format($grandtotal,2) ;?></b></td>

	  </tr>
	<tr>
		<td style="width: 450" class="style17" colspan="3" align="center">
		<b>COMMODITY DESCRIPTION</b></font></td>
<?php
if ($_REQUEST["consolidate"] != 'T') {
?>

		<td class="style17" align="center"><b>LOCATION</b></td>
<?php } ?>
		<td style="width: 75" class="style17" colspan=2 align="center">
		<b># GOOD</b></font></td>
		<td class="style5" style="width: 75" align="center">
		<b>AVERAGE<br>PRICE</b></td>
		<td align="middle" style="width: 75" class="style16" align="center">
		<b>TOTAL VALUE</b></td>
	
	</tr>

<?php
if ($_REQUEST["consolidate"] == 'T') {
$queryrec = "SELECT *, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) AS A, sum(loop_boxes_sort.boxgood) AS B, sum(loop_boxes_sort.boxbad) AS C FROM loop_boxes_sort INNER JOIN loop_transaction ON loop_transaction.id = loop_boxes_sort.trans_rec_id INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_transaction.warehouse_id IN ( " . $warehouses . " ) AND loop_transaction.pr_requestdate_php BETWEEN '" . string_to_date($_REQUEST["start_date"],"00:00:00") . "' AND '" . string_to_date($_REQUEST["end_date"],"23:59:59") . "' AND loop_boxes.isbox LIKE 'N' GROUP BY loop_boxes_sort.box_id ORDER BY A DESC";
} else {
$queryrec = "SELECT *, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) AS A, sum(loop_boxes_sort.boxgood) AS B, sum(loop_boxes_sort.boxbad) AS C FROM loop_boxes_sort INNER JOIN loop_transaction ON loop_transaction.id = loop_boxes_sort.trans_rec_id INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_transaction.warehouse_id IN ( " . $warehouses . " ) AND loop_transaction.pr_requestdate_php BETWEEN '" . string_to_date($_REQUEST["start_date"],"00:00:00") . "' AND '" . string_to_date($_REQUEST["end_date"],"23:59:59") . "' AND loop_boxes.isbox LIKE 'N' GROUP BY loop_boxes_sort.warehouse_id, loop_boxes_sort.box_id ORDER BY A DESC";
}
$cgoodtot = 0;
$cbadtot = 0;
$cgrandtotal = 0;
$res = db_query($queryrec );
while($row = array_shift($res))
{
	if ($row["B"] > 0 || $row["C"] > 0) 
	{
	?>
		<tr>

      <td colspan="3" bgColor="#e4e4e4" class="style12left" >

      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row["bdescription"];?></td>
<?php
if ($_REQUEST["consolidate"] != 'T') {
$qn = "SELECT * FROM loop_warehouse where id = " . $row["warehouse_id"];
$resn = db_query($qn );
$rn = array_shift($resn);


?>

		<td bgColor="#e4e4e4" class="style12right">
		<?php echo $rn["warehouse_name"];?></font></td>
<?php } ?>
      <td bgColor="#e4e4e4" class="style12right" colspan=2><?php echo number_format($row["B"],0);?></td>

      <td bgColor="#e4e4e4" class="style12right" >$<?php echo number_format($row["A"]/$row["B"],3) ;?></td>
      <td bgColor="#e4e4e4" class="style12right" >$<?php echo number_format($row["A"],2) ;?></td>

	  </tr>
	
				
<?php
	$cgoodtot += $row["B"];
	$cbadtot += $row["C"];
	$cgrandtotal += $row["A"];
	}
}

?>
		<tr>

      <td colspan="3" bgColor="#e4e4e4" class="style12left" >
      <b>COMMODITY TOTAL</b></td>
<?php
if ($_REQUEST["consolidate"] != 'T') {
?>

		<td bgColor="#e4e4e4" class="style12right" > </td>
<?php } ?>
      <td bgColor="#e4e4e4" class="style12right" colspan=2><b><?php echo number_format($cgoodtot, 0);?></b></td>
      <td bgColor="#e4e4e4" class="style12right" ><b>$<?php echo number_format($cgrandtotal/$cgoodtot,3) ;?></b></td>
      <td bgColor="#e4e4e4" class="style12right" ><b>$<?php echo number_format($cgrandtotal,2) ;?></b></td>

	  </tr>


	<tr>
		<td colspan="3" style="width: 450" class="style17" align="center">
		<b>OTHER ITEMS</b></font></td>
		<td style="width: 75" class="style17" colspan=2 align="center">
		<b> </b></font></td>
		<td class="style5" style="width: 75" align="center">
		<b> </b></td>
<?php
if ($_REQUEST["consolidate"] != 'T') {
?>

		<td class="style17" align="center"><b>LOCATION</b></td>
<?php } ?>
		<td align="middle" style="width: 75" class="style16" align="center">
		<b>TOTAL</b></td>
	
	</tr>

<?php
if ($_REQUEST["consolidate"] == 'T') {
$query = "SELECT *, sum(freightcharge) AS A, sum(othercharge) AS B FROM loop_transaction WHERE warehouse_id IN (" . $warehouses . ")  AND pr_requestdate_php BETWEEN '" . string_to_date($_REQUEST["start_date"],"00:00:00") . "' AND '" . string_to_date($_REQUEST["end_date"],"23:59:59") . "'";
} else {
$query = "SELECT *, sum(freightcharge) AS A, sum(othercharge) AS B FROM loop_transaction WHERE warehouse_id IN (" . $warehouses . ")  AND pr_requestdate_php BETWEEN '" . string_to_date($_REQUEST["start_date"],"00:00:00") . "' AND '" . string_to_date($_REQUEST["end_date"],"23:59:59") . "' GROUP BY warehouse_id";
}
$res = db_query($query );
$row = array_shift($res);

	?>
		<tr>

      <td colspan=6 bgColor="#e4e4e4" class="style12left" >

      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FREIGHT</b></td>
<?php
if ($_REQUEST["consolidate"] != 'T') {
$qn = "SELECT * FROM loop_warehouse where id = " . $row["warehouse_id"];
$resn = db_query($qn );
$rn = array_shift($resn);


?>

		<td bgColor="#e4e4e4" class="style12right">
		<?php if ($row["A"] > 0) echo $rn["warehouse_name"];?></font></td>
<?php } ?>
      <td bgColor="#e4e4e4" class="style12right" >$<?php echo number_format($row["A"],2) ;?></td>

	  </tr>
	  	<tr>

      <td colspan=6 bgColor="#e4e4e4" class="style12left" >

      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OTHER CHARGES</td>
<?php if ($_REQUEST["consolidate"] != 'T') {
$qn = "SELECT * FROM loop_warehouse where id = " . $row["warehouse_id"];
$resn = db_query($qn );
$rn = array_shift($resn);


?>

		<td bgColor="#e4e4e4" class="style12right">
		<?php if ($row["B"] > 0) echo $rn["warehouse_name"];?></font></td>
<?php } ?>
      <td bgColor="#e4e4e4" class="style12right" ><?php echo number_format($row["B"],2);?>

	  </tr>
	
			
		<tr>

      <td bgColor="#e4e4e4" class="style12left" colspan=6>
      <b>OTHER TOTAL</b></td>
<?php
if ($_REQUEST["consolidate"] != 'T') {
?>

		<td bgColor="#e4e4e4" class="style12right" > </td>
<?php } ?>
      <td bgColor="#e4e4e4" class="style12right" ><b>$<?php echo number_format($row["A"]+$row["B"],2) ;?></b></td>

	  </tr>
		<tr>

      <td bgColor="#e4e4e4" class="style12left" colspan=6>
      <b>GRAND TOTAL</b></td><?php
if ($_REQUEST["consolidate"] != 'T') {
?>

		<td bgColor="#e4e4e4" class="style12right" > </td>
<?php } ?>
      <td bgColor="#e4e4e4" class="style12right" ><b>$<?php echo number_format($grandtotal + $cgrandtotal + $row["A"]+$row["B"],2) ;?></b></td>

	  </tr>




















	
</table>
</td>
<td valign="top">
<?php
}

if ($_REQUEST["trailer"]>0)
{
$dt_view_qry = "SELECT * FROM loop_transaction WHERE id = " . $_REQUEST["trailer"];
$dt_view_res = db_query($dt_view_qry  );

$dt_view_trl_row = array_shift($dt_view_res)
?>
<table cellSpacing="1" cellPadding="1" border="0" width="800">
    <tr align="middle">
      <td class="style12" colspan="10" >&nbsp;</td>
    </tr>
       <tr align="middle">
      <td class="style7" colspan="10" style="height: 16px"><strong>SORT REPORT FOR TRAILER #<?php echo $dt_view_trl_row["pr_trailer"];?></strong></td>
    </tr>
       <tr align="middle">
      <td bgColor="88EEEE" colspan="10" class="style17" ><strong>BOXES</strong></td>
    </tr>
    <tr vAlign="center">
      <td bgColor="#e4e4e4" class="style12" >Good Boxes</td>
      <td bgColor="#e4e4e4" class="style12" >Bad Boxes</td>
      <td bgColor="#e4e4e4" width="350" class="style12" >Description</td>
      <td bgColor="#e4e4e4" class="style12" >Box Weight</td>
      <td bgColor="#e4e4e4" class="style12" >Value Per Box</td>
      <td bgColor="#e4e4e4" class="style12" >Value of Boxes</td>
    </tr>
<?php
$gb = 0;
$bb = 0;
$gbw = 0;
$vob = 0;


$dt_view_qry = "SELECT * FROM loop_boxes_sort INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_boxes.isbox LIKE 'Y' AND loop_boxes_sort.trans_rec_id = " . $_REQUEST["trailer"];
$dt_view_res = db_query($dt_view_qry  );

while ($dt_view_row = array_shift($dt_view_res)) {

	if ($dt_view_row["boxgood"] > 0 || $dt_view_row["boxbad"] > 0) 
	{
?>
		<tr>
      <td bgColor="#e4e4e4" class="style12right" ><?php echo $dt_view_row["boxgood"];?></td>
      <td bgColor="#e4e4e4" class="style12right" ><?php echo $dt_view_row["boxbad"];?></td>
      <td bgColor="#e4e4e4" class="style12left" >
      <?php echo $dt_view_row["blength"];?> <?php echo $dt_view_row["blength_frac"];?> x
      <?php echo $dt_view_row["bwidth"];?> <?php echo $dt_view_row["bwidth_frac"];?> x 
      <?php echo $dt_view_row["bdepth"];?> <?php echo $dt_view_row["bdepth_frac"];?>
      <?php echo $dt_view_row["bdescription"];?></td>
      <td bgColor="#e4e4e4" class="style12right" ><?php echo $dt_view_row["bweight"];?></td>
      <td bgColor="#e4e4e4" class="style12right" ><?php echo $dt_view_row["sort_boxgoodvalue"] ;?></td>
      <td bgColor="#e4e4e4" class="style12right" ><?php echo number_format($dt_view_row["sort_boxgoodvalue"] * $dt_view_row["boxgood"], 2);?></td>
	  </tr>
	
	
<?php 
	$gb += $dt_view_row["boxgood"];
	$bb += $dt_view_row["boxbad"];
	$gbw += $dt_view_row["bweight"] * $dt_view_row["boxgood"];
	$vob += $dt_view_row["sort_boxgoodvalue"] * $dt_view_row["boxgood"];
}
} ?>	

		<tr>
      <td bgColor="#e4e4e4" class="style12right" ><strong><?php echo $gb;?></strong></td>
      <td bgColor="#e4e4e4" class="style12right" ><strong><?php echo $bb;?></strong></td>
      <td bgColor="#e4e4e4" class="style12" ><strong>BOX TOTALS</strong></td>
      <td bgColor="#e4e4e4" class="style12right" ><strong><?php echo number_format($gbw,2) ;?></strong></td>
      <td bgColor="#e4e4e4" class="style12" > </td>
      <td bgColor="#e4e4e4" class="style12right" ><strong>$<?php echo number_format($vob,2);?></strong></td>
	  </tr>

    <tr align="middle">
      <td bgColor="88EEEE" colspan="10" class="style17" ><strong>OTHER ITEMS</strong></td>
    </tr>
    <tr vAlign="center">
      <td bgColor="#e4e4e4" colspan="2" class="style12" >Quantity</td>
      <td bgColor="#e4e4e4" class="style12left" >Description</td>
      <td bgColor="#e4e4e4" class="style12right" >Units</td>
      <td bgColor="#e4e4e4" class="style12right" >Value Per Unit</td>
      <td bgColor="#e4e4e4" class="style12right" >Total Value</td>
    </tr>
<?php


$voo = 0;


$dt_view_qry = "SELECT * FROM loop_boxes_sort INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_boxes_sort.trans_rec_id = " . $_REQUEST["trailer"] . " AND loop_boxes.isbox LIKE 'N'";
$dt_view_res = db_query($dt_view_qry  );

while ($dt_view_row = array_shift($dt_view_res)) {

	if ($dt_view_row["boxgood"] > 0 || $dt_view_row["boxbad"] > 0) 
	{
?>
		<tr>
      <td bgColor="#e4e4e4" colspan="2" class="style12right" ><?php echo $dt_view_row["boxgood"];?></td>
      <td bgColor="#e4e4e4" class="style12left" >
      <?php echo $dt_view_row["bdescription"];?></td>
      <td bgColor="#e4e4e4" class="style12right" ><?php echo $dt_view_row["bunit"];?></td>
      <td bgColor="#e4e4e4" class="style12right" ><?php echo number_format($dt_view_row["sort_boxgoodvalue"],3);?></td>
      <td bgColor="#e4e4e4" class="style12right" ><?php echo number_format($dt_view_row["boxgood"] * $dt_view_row["sort_boxgoodvalue"],2);?></td>
	  </tr>
	
	
<?php 
	$voo += $dt_view_row["boxgood"] * $dt_view_row["sort_boxgoodvalue"];
}
} ?>	

		<tr>

      <td bgColor="#e4e4e4" colspan="5" class="style12right" ><strong>OTHER ITEM TOTALS</strong></td>

      <td bgColor="#e4e4e4" class="style12right" ><strong>$<?php echo number_format($voo,2);?></strong></td>
	  </tr>
	      <tr align="middle">
      <td bgColor="88EEEE" colspan="10" class="style17" ><strong>TOTALS</strong></td>
    </tr>
	<tr>
		<td bgColor="#e4e4e4" colspan="5" class="style12right" ><strong>GROSS EARNINGS</strong></td>
		<td bgColor="#e4e4e4" class="style12right" ><strong>$<?php echo number_format($vob + $voo,2);?></strong></td>
	</tr>
<?php if (	$dt_view_trl_row["othercharge"] != 0) { ?>
	<tr>
		<td bgColor="#e4e4e4" colspan="5" class="style12right" ><strong><?php echo $dt_view_trl_row["otherdetails"]; ?></strong></td>
		<td bgColor="#e4e4e4" class="style12right" ><strong>$<?php echo number_format($dt_view_trl_row["othercharge"],2);?></strong></td>
	</tr>
<?php } ?>
		<tr>
	      <td bgColor="#e4e4e4" colspan="5" class="style12right" ><strong>FREIGHT</strong></td>
	      <td bgColor="#e4e4e4" class="style12right" ><strong>$<?php echo number_format($dt_view_trl_row["freightcharge"],2);?></strong></td>
	   </tr>
		<tr>
	      <td bgColor="#e4e4e4" colspan="5" class="style12right" ><strong>TOTAL EARNED</strong></td>
	      <td bgColor="#e4e4e4" class="style12right" ><strong>$<?php echo number_format($vob + $voo + $dt_view_trl_row["othercharge"] + $dt_view_trl_row["freightcharge"],2);?></strong></td>
	   </tr>




<?php } ?>


</td>
</tr>
</table>
<br>
<?php $trees = ($goodweight / 2000) * 17; ?>
<font size=2>
<img src="images/trees.jpg"> Did you know you would have to cut down <b><?php echo number_format($trees,2); ?></b> trees to make as many boxes as you rescued?<br><Br>
Prepared by Used Cardboard Boxes, Inc., 4032 Wilshire Blvd, Ste 402, Los Angeles, CA 90010.<br>
For the private use of the company listed above.
</font>















</body>
</html>
