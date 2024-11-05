<style>
    .todo_style {
        font-size: 12px;
        padding: 3px;
        font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
    }

    .dtclass {
        top: 150px !important;
    }
</style>

<?php
require("inc/header_session.php");
require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

db();

$b2bid = $_REQUEST["compid"];

if (isset($_REQUEST["mark_comp_edit"])) {
    if ($_REQUEST["mark_comp_edit"] == "yes") {
?>
        <form name="todoform_edit" id="todoform_edit">
            <table>
                <tr align="center">

                    <td bgcolor="#E4E4E4" align="middle">
                        Completion Date: <input type="text" name="todo_date_edit" id="todo_date_edit" size="8" value="<?php echo date(" m/d/Y"); ?>">
                        <a style='color:#0000FF;' href="#" onclick="cal2xxwateredit.select(document.todoform_edit.todo_date_edit,'anchor2nxx','MM/dd/yyyy'); return false;" name="anchor2nxx" id="anchor2nxx"><img border="0" src="images/calendar.jpg"></a>
                        <div ID="listdiv1" STYLE="top: 100%; float: right; visibility:hidden;background-color:white;layer-background-color:white;">
                        </div>

                    </td>
                    <td bgcolor="#E4E4E4" align="middle">
                        <input type="hidden" name="todo_companyID" id="todo_companyID" value="<?php echo $b2bid; ?>" />
                        <input type="hidden" name="mark_comp_edit" id="mark_comp_edit" value="yes" />
                        <input style="cursor:pointer;" type="button" value="Submit" name="todo_update_edit" onclick="update_mark_comp_water(<?php echo $_REQUEST["unqid"]; ?>)">
                    </td>
                </tr>
            </table>
        <?php  }
} else {
        ?>
        <div id="step_edit_div">
            <form name="todoform_edit" id="todoform_edit">
                <div ID="listdiv_duedt" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;" class="dtclass"></div>
                <table border="0" cellspacing="1" cellpadding="1" width="100%">
                    <tr align="center">
                        <td colspan="14" class="display_maintitle">
                            <strong>Active Initiatives</strong>
                        </td>
                    </tr>
                    <tr align="center">

                        <td class="display_title" valign="top">Initiative Step
                        </td>
                        <td class="display_title" valign="top">Initiative Title
                        </td>
                        <td class="display_title">Initiative Details
                        </td>
                        <td class="display_title" valign="top">Initiative<br>Due Date
                        </td>
                        <td class="display_title" valign="top">Initiative<br>Task Owner
                        </td>
                        <!-- <td class="display_title" valign="top">Initiative Update
				</td> -->
                    </tr>

                    <?php
                    if ($_REQUEST["taskid"] == 0) {
                        $stepid = $_REQUEST["unqid"];
                        $sql = "SELECT * FROM water_initiatives where companyid = " . $b2bid . " and initiative_step = '" . $stepid . "' group by initiative_step";
                    } else {
                        $stepid = 0;
                        $sql = "SELECT * FROM water_initiatives where companyid = " . $b2bid . " and taskid = '" . $_REQUEST["taskid"] . "' group by initiative_step";
                    }
                    db();
                    $result = db_query($sql);
                    if (tep_db_num_rows($result) > 0) {
                        while ($step_rows = array_shift($result)) {
                            //
                            if ($_REQUEST["taskid"] == 0) {
                                $init_sql = "SELECT * FROM water_initiatives where companyid = " . $b2bid . "  and initiative_step= '" . $step_rows["initiative_step"] . "' order by taskid";
                            } else {
                                $init_sql = "SELECT * FROM water_initiatives where companyid = " . $b2bid . " and taskid = '" . $_REQUEST["taskid"] . "' order by taskid";
                            }
                            db();
                            $init_result = db_query($init_sql);
                            $total_steps = tep_db_num_rows($init_result);
                    ?>

                            <tr id="init_step_row_edit<?php echo $step_rows["initiative_step"]; ?>">
                                <td rowspan="<?php echo $total_steps ?>" class="display_table" align="left" valign="top">
                                    <input type="hidden" name="old_step" id="old_step" value="<?php echo $stepid; ?>">
                                    <select name="init_step_edit" id="init_step_edit" class="form_component" style="width: 150px;" onChange="show_step_number_edit(this.value, <?php echo $b2bid; ?>, <?php echo $stepid ?>, <?php echo $_REQUEST["taskid"] ?>, 1)">
                                        <?php
                                        $int_step_qry = "select * from water_initiative_steps";
                                        db();
                                        $int_step_res = db_query($int_step_qry);
                                        while ($int_step_rows = array_shift($int_step_res)) {
                                        ?>
                                            <option value="<?php echo $int_step_rows["unique_key"]; ?>" <?php if ($int_step_rows["unique_key"] != "" && $int_step_rows["unique_key"] == $stepid) {
                                                                                                            echo "selected";
                                                                                                        } else {
                                                                                                        } ?>>
                                                <?php echo $int_step_rows["unique_key"] . "-" . $int_step_rows["initiative_step"]; ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <?php
                                $count = 0;
                                while ($myrowsel = array_shift($init_result)) {
                                    $date1 = new DateTime($myrowsel["due_date"]);
                                    $date2 = new DateTime();

                                    $days = (strtotime($myrowsel["due_date"]) - strtotime(date("Y-m-d"))) / (60 * 60 * 24);
                                    //
                                    $count = $count + 1;
                                    //
                                ?>
                                    <td align="left" class="display_table" valign="top" style="padding: 0px;">
                                        <table>
                                            <tr>
                                                <td class="display_table" valign="top" align="left">
                                                    <div id="init_titleid">
                                                        <?php
                                                        if ($myrowsel["task_sub_id"] == "") {
                                                        } else {
                                                            $tsi = $myrowsel["task_sub_id"];
                                                            echo $myrowsel["task_sub_id"] . ". ";
                                                            echo "<input type='hidden' name='task_sub_id$count' id='task_sub_id$count' value='$tsi'>";
                                                        }
                                                        ?>
                                                    </div>
                                                </td>
                                                <td class="display_table">
                                                    <textarea name="task_title<?php echo $count ?>" id="task_title<?php echo $count ?>" class="form_component"><?php echo $myrowsel["task_title"] ?></textarea>
                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                    <td class="display_table" align="left" valign="top">
                                        <textarea name="task_details<?php echo $count ?>" id="task_details<?php echo $count ?>" class="form_component"><?php echo $myrowsel["task_details"] ?></textarea>

                                    </td>

                                    <td class="display_table" align="left" valign="top">
                                        <input name="due_date<?php echo $count ?>" type="text" class="form_component" id="due_date<?php echo $count ?>" value="<?php if (isset($myrowsel["due_date"])) {
                                                                                                                                                                    echo $myrowsel["due_date"];
                                                                                                                                                                } else {
                                                                                                                                                                    echo "";
                                                                                                                                                                } ?>" style="width:70px; ">&nbsp;
                                        <a href="#" onclick="duedt.select(document.todoform_edit.due_date<?php echo $count ?>,'anchorblastdt<?php echo $count ?>','yyyy-MM-dd'); return false;" name="anchorblastdt<?php echo $count ?>" id="anchorblastdt<?php echo $count ?>"><img border="0" src="images/calendar.jpg"></a>
        </div>
        </td>
        <td class="display_table" align="left" valign="top">
            <select name="task_owner<?php echo $count ?>" id="task_owner<?php echo $count ?>" class="form_component" style="width: 80px;">
                <option value="">Select</option>
                <?php
                                    $emp_res = "SELECT * FROM employees WHERE status='Active' order by name asc";
                                    db_b2b();
                                    $emp_res = db_query($emp_res);
                                    while ($emp_row = array_shift($emp_res)) {
                ?>
                    <option id="<?php echo $emp_row["initials"]; ?>" <?php if ($emp_row["initials"] != "" && "UCB-" . $emp_row["initials"] == $myrowsel["task_owner"]) {
                                                                            echo "selected";
                                                                        } else {
                                                                        } ?>>
                        <?php echo "UCB-" . $emp_row["initials"]; ?>
                    </option>
                <?php
                                    }
                                    $compw_res = "SELECT * FROM companyInfo WHERE ucbzw_flg=1";
                                    db_b2b();
                                    $compw_res = db_query($compw_res);
                                    while ($compw_row = array_shift($compw_res)) {
                ?>
                    <option id="<?php echo $compw_row["ID"]; ?>" <?php if ($compw_row["ID"] != "" && $compw_row["ID"] == $myrowsel["task_owner"]) {
                                                                        echo "selected";
                                                                    } else {
                                                                    } ?>>
                        <?php echo $compw_row["company"]; ?>
                    </option>
                <?php
                                    }
                ?>
            </select>
            <input type="hidden" name="todo_taskID<?php echo $count ?>" id="todo_taskID<?php echo $count ?>" value="<?php echo $myrowsel["taskid"] ?>" />
        </td>


        </tr>

    <?php
                                }

    ?>

    </tr>
<?php
                        }
                    }
?>
<tr>
    <td>
        <input type="hidden" name="todo_companyID" id="todo_companyID" value="<?php echo $b2bid; ?>" />
        <input type="hidden" name="todo_count" id="todo_count" value="<?php echo $count; ?>" />
        <input style="cursor:pointer;" type="button" value="Update Task" name="todo_update_edit" onclick="update_edit_item_water(<?php echo $stepid; ?>)">
    </td>
</tr>
</table>
        </form>
        </div>
    <?php
}
    ?>