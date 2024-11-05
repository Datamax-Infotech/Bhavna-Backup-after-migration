<div id="table_loop_paidbubble_inv_status">
    Loading .....<img src='images/wait_animated.gif' />
</div>

<LINK rel='stylesheet' type='text/css' href='one_style_mrg.css'>

<!-- To set the Invoice Status-->
<?php
ini_set("display_errors", "1");
error_reporting(E_ERROR);
require_once("inc/header_session.php");
require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";


db();

if (isset($_REQUEST["btnUpdateinvstat"])) {
    $qry = "UPdate loop_transaction_buyer SET trans_status = '" . $_REQUEST["transaction_status"] . "' , `trans_status_update_dt` = '".date('Y-m-d H:i:s')."' where id = '" . $_REQUEST["rec_id"] . "'";
    $res_newtrans = db_query($qry);
}
//For the Invoice Status
$sql = "SELECT loop_trans_status.trans_status FROM loop_transaction_buyer inner join loop_trans_status on loop_trans_status.id = loop_transaction_buyer.trans_status WHERE loop_transaction_buyer.id = " . $_REQUEST['rec_id'];
$sql_res = db_query($sql);
while ($row = array_shift($sql_res)) {
?>
    <br>
    <table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
        <tr align="middle">
            <td bgColor="#99FF99" colSpan="2">
                <font size="1">Invoice Status</font>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 100px" class="style1">
                <font size="1">Status</font>
                </font>
            </td>
            <td align="left" height="13" style="width: 235px" class="style1">
                <font size="1"><?php echo $row["trans_status"]; ?></font>
            </td>
        </tr>
    </table>
<?php
} //while loop

$trans_status = 0;
$sql = "SELECT trans_status FROM loop_transaction_buyer WHERE id = " . $_REQUEST['rec_id'];
$sql_res = db_query($sql);
while ($row = array_shift($sql_res)) {
    $trans_status = $row["trans_status"];
}
?>
<br>
<form action="loop_paidbubble_inv_status.php" method="post">
    <input type="hidden" name="rec_id" value="<?php echo $_REQUEST['rec_id']; ?>" />
    <input type="hidden" name="warehouse_id" value="<?php echo $warehouse_id; ?>" />
    <input type="hidden" name="ID" value="<?php echo $_REQUEST['ID']; ?>" />

    <table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
        <tr align="middle">
            <td bgColor="#99FF99" colSpan="2">
                <font size="1">Update Invoice Status&nbsp;</font>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 100px" class="style1">
                <font size="1">Status</font>
                </font>
            </td>
            <td align="left" height="13" style="width: 235px" class="style1">
                <font size="1">
                    <select name="transaction_status">
                        <option value="0">Please select</option>
                        <?php
                        $qry = "SELECT * FROM loop_trans_status order by trans_status";
                        $dt_view_res = db_query($qry);
                        while ($data_row = array_shift($dt_view_res)) {
                        ?>
                            <option value="<?php echo $data_row["id"] ?>"
                                <?php if ($trans_status == $data_row["id"]) echo " selected "; ?>>
                                <?php echo $data_row["trans_status"] ?></option>
                        <?php }  ?>
                    </select>
                </font>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td colspan=2 align=center>
                <font size="1"><input style="cursor:pointer;" type="submit" id="btnUpdateinvstat"
                        name="btnUpdateinvstat" value="Update Status"></font>
            </td>
        </tr>
    </table>
</form>
<!-- To set the Invoice Status-->

<script>
    document.getElementById("table_loop_paidbubble_inv_status").style.display = "none";
</script>