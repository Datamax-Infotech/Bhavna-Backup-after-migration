<?php  
require_once("inc/header_session.php");
require_once("../mainfunctions/database.php");
require_once("../mainfunctions/general-functions.php");
?> 
<table cellSpacing="1" cellPadding="1" width="440px" border="0">
	<tr align="middle">
		<td bgColor="#c0cdda" colspan="6">
			<font face="Arial, Helvetica, sans-serif" size="1">LOG</font>
		</td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td class="style1" ><font size="1">Date</font></td>
		<td class="style1" ><font size="1">Employee</font></td>
		<td class="style1" ><font size="1">Notes</font></td>
	</tr>					
<?php 
$sql = '';
if ($_REQUEST['compid'] !="")
{
	$sql = "SELECT * from inventory_notes where companyID = " . $_REQUEST['compid']. " ORDER BY unqid desc ";
}

if ($_REQUEST['box_b2b_id'] !="")
{
	$sql = "SELECT * from inventory_notes where box_b2b_id = " . $_REQUEST['box_b2b_id']. " ORDER BY unqid desc ";
}
//echo $sql;
db_b2b();
$result = db_query($sql);

while ($myrowsel = array_shift($result)) {
	$the_log_date = $myrowsel["entry_datetime"];
?>
	<tr bgColor="#e4e4e4">
		<td class="style1" width="10%"><font size="1"><?php echo date("m/d/Y H:i:s", strtotime($the_log_date)) . " CT"; ?></font></td>
		<td class="style1" width="10%"><font size="1"><?php echo $myrowsel["entry_emp"]; ?></font></td>
		<td class="style1" width="35%"><font size="1"><?php echo stripslashes($myrowsel["notes"]); ?></font></td>
	</tr>
<?php  }  ?>
</table> 