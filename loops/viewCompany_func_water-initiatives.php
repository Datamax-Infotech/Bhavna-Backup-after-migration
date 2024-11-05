<style>
    .display_maintitle {
        font-size: 13px;
        padding: 3px;
        font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
        background: #98bcdf;
        white-space: nowrap;
    }

    .display_title {
        font-size: 12px;
        padding: 3px;
        font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
        background: #ABC5DF;
        white-space: nowrap;
    }

    .display_table {
        font-size: 11px;
        padding: 3px;
        font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
        background: #EBEBEB;
    }

    .form_component {
        font-size: 11px;
        margin-top: 2px;
        margin-bottom: 2px;
        font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
    }

    .info_s {
        font-size: 11px;
        font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
        padding: 3px;
    }

    .sstatus_up {
        cursor: pointer;
        font-size: 11px;
        padding: 3px;
        font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
        background: #EBEBEB;
        text-decoration: underline;
    }

    .update_link_div {
        padding-top: 4px;
    }
</style>
<?php
ini_set("display_errors", "1");
error_reporting(E_ERROR);
require("inc/header_session.php");
require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";
echo "<LINK rel='stylesheet' type='text/css' href='one_style_mrg.css' >";
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#init_step').on('change', function() {

            var init_step = $(this).val();
            var init_companyID = document.getElementById("init_companyID").value;
            if (init_step) {
                $.ajax({
                    type: 'POST',
                    url: 'show_init_step_id.php',
                    data: 'init_step=' + init_step + '&init_companyID=' + init_companyID +
                        '&editstep=0',
                    success: function(html) {
                        $('#init_title_id').html(html);
                    }
                });
            }
            /*else{
            					//$('#sop_division_dd').html('<option value="">Select Function First</option>');
            				}*/
        });

    });
</script>
<script language="javascript">
    function deletetrans(trans_rec_id) {
        if (confirm("Do you want to delete the Transcation?") == true) {
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("rowid" + trans_rec_id).style.display = "none";
                }
            }

            xmlhttp.open("GET", "water_trans_delete.php?trans_rec_id=" + trans_rec_id, true);
            xmlhttp.send();
        }
    }

    function todoitem_delete_water(stepid, taskid, compid) {
        if (confirm("Do you want to delete the Initiative?") == true) {
            var pwdval = prompt("Please enter password to delete the Initiative", "");

            if (pwdval == "4652") {
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        document.getElementById('light_todo').style.display = 'none';
                        document.getElementById("initiative_div").innerHTML = xmlhttp.responseText;
                    }
                }

                xmlhttp.open("GET", "todolist_water_update_row.php?indelete_mode=1&stepid=" + stepid + "&taskid=" + taskid +
                    "&compid=" + compid, true);
                xmlhttp.send();
            } else {
                alert("Password Incorrect!");
            }
        }
    }

    function todoitem_edit_water(stepid, init_task, compid) {
        var view_table = "active";
        //var step_status = document.getElementById('step_status'+ init_task).value;
        var task_status = document.getElementById('task_status' + init_task).value;
        var water_init_notes = document.getElementById('water_init_notes' + init_task).value;
        var water_init_notes1 = document.getElementById('water_init_notes1' + init_task).value;
        //var step_due_date = document.getElementById('step_due_date'+ init_task).value;
        //
        document.getElementById("initiative_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("initiative_div").innerHTML = xmlhttp.responseText;
                //$( "#inv_inact_row" ).load(window.location.href + " #inv_inact_row" );
            }
        }

        xmlhttp.open("POST", "todolist_water_update_row.php?stepid=" + stepid + "&init_task=" + init_task + "&compid=" +
            compid + "&task_status=" + task_status + "&water_init_notes=" + water_init_notes + "&water_init_notes1=" +
            water_init_notes1 + "&view_table=" + view_table, true);
        xmlhttp.send();
    }
    //
    function todoitem_edit_water_comp(stepid, init_task, compid) {
        var view_table = "completed";
        /*var step_status = document.getElementById('step_status'+ stepid).value;
        var task_status = document.getElementById('task_status'+ init_task).value;*/
        var water_init_notes = document.getElementById('water_init_notes' + init_task).value;
        var water_init_notes1 = document.getElementById('water_init_notes1' + init_task).value;
        //
        document.getElementById("initiative_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("initiative_div").innerHTML = xmlhttp.responseText;
                //$( "#inv_inact_row" ).load(window.location.href + " #inv_inact_row" );
            }
        }

        xmlhttp.open("POST", "todolist_water_update_row.php?stepid=" + stepid + "&init_task=" + init_task + "&compid=" +
            compid + "&water_init_notes=" + water_init_notes + "&water_init_notes1=" + water_init_notes1 +
            "&view_table=" + view_table, true);
        xmlhttp.send();
    }
    //Function to update step status
    function update_step_status(stepid, init_task, compid) {

        var view_table = "update_status";
        if (stepid > 0) {
            var step_status = document.getElementById('step_status' + stepid).value;
        } else {
            var step_status = document.getElementById('step_status' + init_task).value;
        }
        // if (stepid > 0) {
        //     step_status_element = document.getElementById('step_status' + stepid);
        // } else {
        //     step_status_element = document.getElementById('step_status' + init_task);
        // }

        // Check if the element exists before accessing its value
        // if (step_status_element !== null) {
        //     var step_status = step_status_element.value;
        // }
        //
        document.getElementById("initiative_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("initiative_div").innerHTML = xmlhttp.responseText;
                //$( "#inv_inact_row" ).load(window.location.href + " #inv_inact_row" );
            }
        }

        xmlhttp.open("POST", "todolist_water_update_row.php?stepid=" + stepid + "&init_task=" + init_task + "&compid=" +
            compid + "&step_status=" + step_status + "&view_table=" + view_table, true);
        xmlhttp.send();
    }
    //Function to update Step due date
    function update_step_duedate(stepid, init_task, compid) {
        var view_table = "update_step_duedate";
        if (stepid > 0) {
            var step_due_date = document.getElementById('step_due_date' + stepid).value;
        } else {
            var step_due_date = document.getElementById('step_due_date' + init_task).value;
        }

        //
        document.getElementById("initiative_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("initiative_div").innerHTML = xmlhttp.responseText;
                //$( "#inv_inact_row" ).load(window.location.href + " #inv_inact_row" );
            }
        }

        xmlhttp.open("POST", "todolist_water_update_row.php?stepid=" + stepid + "&init_task=" + init_task + "&compid=" +
            compid + "&step_due_date=" + step_due_date + "&view_table=" + view_table, true);
        xmlhttp.send();
    }
    //
    function initiative_undo_complete(stepid, init_task, compid) {
        var view_table = "undo completed";
        /*var step_status = document.getElementById('step_status'+ stepid).value;
        var task_status = document.getElementById('task_status'+ init_task).value;*/
        //var water_init_notes = document.getElementById('water_init_notes'+ init_task).value;
        //
        document.getElementById("initiative_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("initiative_div").innerHTML = xmlhttp.responseText;
                //$( "#inv_inact_row" ).load(window.location.href + " #inv_inact_row" );
            }
        }

        xmlhttp.open("POST", "todolist_water_update_row.php?stepid=" + stepid + "&init_task=" + init_task + "&compid=" +
            compid + "&view_table=" + view_table, true);
        xmlhttp.send();
    }

    function todoitem_edit_water_steps(unqid, compid, taskid = 0) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                selectobject = document.getElementById("todo_edit_water_steps" + unqid);
                n_left = f_getPosition(selectobject, 'Left');
                n_top = f_getPosition(selectobject, 'Top');

                document.getElementById("light_todo").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';document.getElementById('fade_todo').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
                    xmlhttp.responseText;
                document.getElementById('light_todo').style.display = 'block';

                document.getElementById('light_todo').style.left = (10) + 'px';
                document.getElementById('light_todo').style.top = (n_top - 50) + 'px';
                document.getElementById('light_todo').style.width = 900 + 'px';
                document.getElementById('light_todo').style.height = 430 + 'px';
            }
        }

        xmlhttp.open("GET", "todolist_water_edit_data.php?compid=" + compid + "&unqid=" + unqid + "&taskid=" + taskid,
            true);
        xmlhttp.send();
    }

    function get_water_financial_details(unqid) {
        selectobject = document.getElementById("idwater_financial_details" + unqid);
        n_left = f_getPosition(selectobject, 'Left');
        n_top = f_getPosition(selectobject, 'Top');

        document.getElementById("light_todo").innerHTML =
            "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';document.getElementById('fade_todo').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
            document.getElementById("div_financial_details" + unqid).innerHTML;
        document.getElementById('light_todo').style.display = 'block';

        document.getElementById('light_todo').style.left = (n_left + 10) + 'px';
        document.getElementById('light_todo').style.top = (n_top + 20) + 'px';
        document.getElementById('light_todo').style.width = 350 + 'px';
        document.getElementById('light_todo').style.height = 200 + 'px';
    }

    function todoitem_edit_water_steps_complete(unqid, compid, taskid = 0) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (unqid > 0) {
                    selectobject = document.getElementById("todo_edit_water_steps_complete" + unqid);
                } else {
                    selectobject = document.getElementById("todo_edit_water_steps_complete" + taskid);
                }
                n_left = f_getPosition(selectobject, 'Left');
                n_top = f_getPosition(selectobject, 'Top');

                document.getElementById("light_todo").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';document.getElementById('fade_todo').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
                    xmlhttp.responseText;
                document.getElementById('light_todo').style.display = 'block';

                document.getElementById('light_todo').style.left = (10) + 'px';
                document.getElementById('light_todo').style.top = (n_top - 50) + 'px';
                document.getElementById('light_todo').style.width = 900 + 'px';
                document.getElementById('light_todo').style.height = 430 + 'px';
            }
        }

        xmlhttp.open("GET", "todolist_water_edit_data.php?compid=" + compid + "&unqid=" + unqid + "&taskid=" + taskid,
            true);
        xmlhttp.send();
    }
    //
    function todoitem_addnew_water_steps(unqid, compid, taskid = 0) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                selectobject = document.getElementById("todo_add_new_steps" + unqid);
                n_left = f_getPosition(selectobject, 'Left');
                n_top = f_getPosition(selectobject, 'Top');

                document.getElementById("light_todo").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';document.getElementById('fade_todo').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
                    xmlhttp.responseText;
                document.getElementById('light_todo').style.display = 'block';

                document.getElementById('light_todo').style.left = (10) + 'px';
                document.getElementById('light_todo').style.top = (n_top - 50) + 'px';
                document.getElementById('light_todo').style.width = 900 + 'px';
                document.getElementById('light_todo').style.height = 430 + 'px';
            }
        }

        xmlhttp.open("GET", "todolist_addnew_init_title.php?compid=" + compid + "&unqid=" + unqid + "&taskid=" + taskid,
            true);
        xmlhttp.send();
    }

    function todoitem_addnew_water_steps_complete(unqid, compid, taskid = 0) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (unqid > 0) {
                    selectobject = document.getElementById("todo_add_new_steps_complete" + unqid);
                } else {
                    selectobject = document.getElementById("todo_add_new_steps_complete" + taskid);
                }

                n_left = f_getPosition(selectobject, 'Left');
                n_top = f_getPosition(selectobject, 'Top');

                document.getElementById("light_todo").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';document.getElementById('fade_todo').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
                    xmlhttp.responseText;
                document.getElementById('light_todo').style.display = 'block';

                document.getElementById('light_todo').style.left = (10) + 'px';
                document.getElementById('light_todo').style.top = (n_top - 50) + 'px';
                document.getElementById('light_todo').style.width = 900 + 'px';
                document.getElementById('light_todo').style.height = 430 + 'px';
            }
        }

        xmlhttp.open("GET", "todolist_addnew_init_title.php?compid=" + compid + "&unqid=" + unqid + "&taskid=" + taskid,
            true);
        xmlhttp.send();
    }
    //
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

    function update_edit_item_water(unqid) {
        //alert("Yes Called");
        document.getElementById("initiative_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById('light_todo').style.display = 'none';
                document.getElementById("initiative_div").innerHTML = xmlhttp.responseText;
            }
        }

        var task_title = "",
            task_details = "",
            due_date = "",
            task_owner = "",
            change_status = "",
            task_sub_id = "",
            todo_taskID = "";
        var compid = document.getElementById('todo_companyID').value;
        var todo_count = document.getElementById('todo_count').value;
        for (var i = 1; i <= todo_count; i++) {
            task_title += document.getElementById('task_title' + i).value + "|";
           // console.log("task_title " + task_title);
            if (document.getElementById('task_sub_id' + i)) {
                task_sub_id += document.getElementById('task_sub_id' + i).value + "|";
            }
            task_details += document.getElementById('task_details' + i).value + "|";
            due_date += document.getElementById('due_date' + i).value + "|";
            todo_taskID += document.getElementById('todo_taskID' + i).value + "|";
            task_owner += document.getElementById('task_owner' + i).value + "|";
            //if(document.getElementById('change_status'+i).checked) { 
            //	change_status+= document.getElementById('change_status'+i).value+",";
            //}

        }
        //var init_step = document.getElementById('init_step_edit').value;
        //
        var init_step = document.getElementById('init_step_edit').value;
        task_title = task_title.slice(0, -1);
        task_sub_id = task_sub_id.slice(0, -1);
        task_details = task_details.slice(0, -1);
        due_date = due_date.slice(0, -1);
        task_owner = task_owner.slice(0, -1);
        //change_status=change_status.slice(0, -1);
        //

        xmlhttp.open("GET", "todolist_water_update_row.php?inedit_mode=1&unqid=" + unqid + "&compid=" + compid +
            "&task_title=" + encodeURIComponent(task_title) + "&task_sub_id=" + task_sub_id + "&task_details=" +
            encodeURIComponent(task_details) + "&due_date=" + due_date + "&task_owner=" + task_owner +
            "&change_status=" + change_status + "&todo_count=" + todo_count + "&todo_taskID=" + todo_taskID +
            "&init_step=" + init_step, true);
        xmlhttp.send();
    }

    function update_mark_comp_water(unqid) {
        document.getElementById("initiative_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById('light_todo').style.display = 'none';
                document.getElementById("initiative_div").innerHTML = xmlhttp.responseText;
            }
        }

        var compid = document.getElementById('todo_companyID').value;
        var todo_date = document.getElementById('todo_date_edit').value;

        xmlhttp.open("GET", "todolist_water_update.php?mark_comp_edit=1&unqid=" + unqid + "&compid=" + compid +
            "&todo_date=" + encodeURIComponent(todo_date), true);
        xmlhttp.send();
    }


    function searchinvno(compid, rec_id) {
        var txtinvno = document.getElementById("txtsrchinvno").value;

        if (txtinvno != "") {

            document.getElementById("water_maintbl").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("water_maintbl").innerHTML = xmlhttp.responseText;
                }
            }

            xmlhttp.open("GET", "water_get_inv_no.php?invno=" + txtinvno + "&comp_id=" + compid + "&rec_id=" + rec_id,
                true);
            xmlhttp.send();
        }
    }

    function fun_show_new_initiative() {
        var new_initiative_frm = document.getElementById('new_initiative_frm');
        var displaySetting = new_initiative_frm.style.display;
        // also get the  button, so we can change what it says
        var add_new_initiative = document.getElementById('add_new_initiative');

        // now toggle and the button text, depending on current state
        if (displaySetting == 'block') {
            // div is visible. hide it
            new_initiative_frm.style.display = 'none';
            // change button text
            // add_new_initiative.innerHTML = 'Show';
        } else {
            // div is hidden. show it
            new_initiative_frm.style.display = 'block';
            // change button text
            //add_new_initiative.innerHTML = 'Hide';
        }
    }

    function newinitiavite_cancel() {
        //cancel_newinitiavite_btn
        var new_initiative_frm = document.getElementById('new_initiative_frm');
        new_initiative_frm.style.display = 'none';
        //
        document.getElementById('task_title').value = "";
        document.getElementById('task_detail').value = "";
        document.getElementById('due_date').value = "<?php echo date('m/d/Y'); ?>";
        document.getElementById('task_owner').value = "";
    }

    function add_newinitiavite() {

        var compid = document.getElementById('init_companyID').value;
        var init_step = document.getElementById('init_step').value;
        var task_sub_id = 0;
        if (document.getElementById('task_sub_id')) {
            task_sub_id = document.getElementById('task_sub_id').value;
        }
        if (document.getElementById('task_title')) {
            var task_title = document.getElementById('task_title').value;
        }
        if (document.getElementById('task_detail')) {
            var task_detail = document.getElementById('task_detail').value;
        }
        var due_date = document.getElementById('due_date').value;
        var task_owner = document.getElementById('task_owner').value;
        var init_created_by = document.getElementById('init_created_by').value;
        var wid = document.getElementById('warehouseid').value;

        var chk_msg = "";

        if (document.getElementById('annual_saving_opportunity').value !== "") {

            var annual_saving_opportunity = document.getElementById('annual_saving_opportunity').value;
        } else {

            chk_msg += "Please Enter atleast 0 in Annual Saving opportunity.\n";
        }

        if (document.getElementById('annual_saving_implemented').value !== "") {

            var annual_saving_implemented = document.getElementById('annual_saving_implemented').value;
        } else {

            chk_msg += "Please Enter atleast 0 in Annual Saving Implemented.\n";
        }

        if (document.getElementById('annual_saving_implement').value !== "") {

            var annual_saving_implement = document.getElementById('annual_saving_implement').value;
        } else {

            chk_msg += "Please Enter atleast 0 in Annual Saving Implement.\n";
        }

        if (document.getElementById('annual_saving_rejected').value !== "") {

            var annual_saving_rejected = document.getElementById('annual_saving_rejected').value;
        } else {

            chk_msg += "Please Enter atleast 0 in Annual Saving Rejected.\n";
        }

        if (document.getElementById('per_in_landfill_total').value !== "") {

            var per_in_landfill_total = document.getElementById('per_in_landfill_total').value;
        } else {

            chk_msg += "Please Enter atleast 0 in Potential % Increase in Total Landfill Diversion.\n";
        }

        if (document.getElementById('landfill_diversion_implemented').value !== "") {

            var landfill_diversion_implemented = document.getElementById('landfill_diversion_implemented').value;
        } else {

            chk_msg += "Please Enter atleast 0 in Landfill Diversion Implemented.\n";
        }

        if (document.getElementById('landfill_diversion_implement').value !== "") {

            var landfill_diversion_implement = document.getElementById('landfill_diversion_implement').value;
        } else {

            chk_msg += "Please Enter atleast 0 in Landfill Diversion Implement.\n";
        }

        if (document.getElementById('landfill_diversion_rejected').value !== "") {

            var landfill_diversion_rejected = document.getElementById('landfill_diversion_rejected').value;
        } else {

            chk_msg += "Please Enter atleast 0 in Landfill Diversion Rejected.\n";
        }

        var Upfile = document.getElementById("task_title_file");
        var selfile = Upfile.files[0];

        if (selfile) {
            const allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'gif'];
            const fileExtension = selfile.name.split('.').pop().toLowerCase();

            if (!allowedExtensions.includes(fileExtension)) {
                chk_msg += "File type is not allowed.\n";
            }
        }
        //alert("water_initiavite_save.php?compid=" +compid+"&init_step="+init_step+"&task_sub_id="+task_sub_id+"&task_title="+task_title+"&task_detail="+encodeURIComponent(task_detail)+"&due_date="+due_date+"&task_owner="+task_owner+"&init_created_by="+init_created_by);
        if (chk_msg == "") {
            document.getElementById("initiative_div").innerHTML =
                "<br><br>Loading .....<img src='images/wait_animated.gif' />";
            var formData = new FormData();
            formData.append("compid", compid);
            formData.append("init_step", init_step);
            formData.append("task_sub_id", task_sub_id);
            formData.append("task_title", task_title);
            formData.append("task_title_file", selfile);
            formData.append("task_detail", task_detail);
            formData.append("annual_saving_opportunity", annual_saving_opportunity);
            formData.append("annual_saving_implemented", annual_saving_implemented);
            formData.append("annual_saving_implement", annual_saving_implement);
            formData.append("annual_saving_rejected", annual_saving_rejected);
            formData.append("per_in_landfill_total", per_in_landfill_total);
            formData.append("landfill_diversion_implemented", landfill_diversion_implemented);
            formData.append("landfill_diversion_implement", landfill_diversion_implement);
            formData.append("landfill_diversion_rejected", landfill_diversion_rejected);
            formData.append("wid", wid);
            formData.append("due_date", due_date);
            formData.append("task_owner", task_owner);
            formData.append("init_created_by", init_created_by);




            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }


            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("initiative_div").innerHTML = xmlhttp.responseText;
                    //$( "#initiative_div" ).load(window.location.href + " #initiative_div" );

                    document.getElementById('init_step').selectedIndex = 0;
                    document.getElementById('task_title').value = "";
                    document.getElementById('task_detail').value = "";
                    document.getElementById('task_owner').value = "";
                    document.getElementById("init_title_id").innerHTML = "";

                    var new_initiative_frm = document.getElementById('new_initiative_frm');
                    new_initiative_frm.style.display = 'block';
                    new_initiative_frm.reset();
                }
            }
            //		
            xmlhttp.open("POST", "water_initiavite_save.php", true);
            xmlhttp.send(formData);
        } else {
            alert(chk_msg);
            return false;
        }
    }
    //
    function addnew_existing_step() {
        //alert(selectedText);
        //document.getElementById("initiative_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />"; 						

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById('light_todo').style.display = 'none';
                document.getElementById("initiative_div").innerHTML = xmlhttp.responseText;
                //$( "#initiative_div" ).load(window.location.href + " #initiative_div" );
                /*document.getElementById('task_title').value="";
                document.getElementById('task_detail').value="";
                document.getElementById('task_owner').value="";*/
                var new_initiative_frm = document.getElementById('new_initiative_frm');
                new_initiative_frm.style.display = 'block';
            }
        }
        //
        var compid = document.getElementById('init_companyID').value;
        var init_step = document.getElementById('init_step_2').value;
        var task_sub_id = document.getElementById('task_sub_id_2').value;
        var task_title = document.getElementById('task_title_2').value;
        var task_detail = document.getElementById('task_detail_2').value;
        var due_date = document.getElementById('new_duedate_2').value;
        var task_owner = document.getElementById('task_owner_2').value;
        var init_created_by = document.getElementById('init_created_by_2').value;
        //
        xmlhttp.open("GET", "water_initiavite_save.php?compid=" + compid + "&init_step=" + init_step + "&task_sub_id=" +
            task_sub_id + "&task_title=" + task_title + "&task_detail=" + encodeURIComponent(task_detail) +
            "&due_date=" + due_date + "&task_owner=" + task_owner + "&init_created_by=" + init_created_by, true);
        xmlhttp.send();
    }
    //

    function init_markcomp(unqid, compid) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                selectobject = document.getElementById("todo_edit_water" + unqid);
                n_left = f_getPosition(selectobject, 'Left');
                n_top = f_getPosition(selectobject, 'Top');

                document.getElementById("light_todo").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';document.getElementById('fade_todo').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
                    xmlhttp.responseText;
                document.getElementById('light_todo').style.display = 'block';

                document.getElementById('light_todo').style.left = (200) + 'px';
                document.getElementById('light_todo').style.top = (n_top - 50) + 'px';
                document.getElementById('light_todo').style.width = 440 + 'px';
                document.getElementById('light_todo').style.height = 200 + 'px';
            }
        }

        xmlhttp.open("GET", "todolist_water_edit_data.php?mark_comp_edit=yes&compid=" + compid + "&unqid=" + unqid, true);
        xmlhttp.send();
    }

    //

    function show_step_number(step_id, compid, flg) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("init_titleid").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("POST", "show_init_step_id.php?editstep=" + flg + "&init_step=" + step_id + "&init_companyID=" +
            compid, true);
        xmlhttp.send();
    }
    //
    function show_step_number_edit(step_id, compid, old_step_id, taskid, flg) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("step_edit_div").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("POST", "show_init_step_id.php?editstep=" + flg + "&init_step=" + step_id + "&init_companyID=" +
            compid + "&old_step_id=" + old_step_id + "&taskid=" + taskid, true);
        xmlhttp.send();
    }

    function getQueryParameters(value) {
        const queryString = window.location.search;
        const params = new URLSearchParams(queryString);
        params.set('filterid', value);
        return params.toString();
    }

    function filter_active_initiative_records(id) {
        const anchorLink = document.getElementById('filterhiddenlink');
        const existingQueryParams = getQueryParameters(id);
        const url = '?' + existingQueryParams;
        anchorLink.setAttribute('href', url);
        anchorLink.click();
        /*
        if (window.XMLHttpRequest){
        	xhr = new XMLHttpRequest();
        }else{
        	xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xhr.onreadystatechange=function(){
        	if (xmlhttp.readyState==4 && xmlhttp.status==200){
        	document.getElementById("step_edit_div").innerHTML = xmlhttp.responseText;
        	}
        }
        */
    }
    //
    function edit_task_annual_saving(unqid, compid, role) {

        var annual_saving_tableID = "annual_tbl" + unqid;

        if (role == 1) {
            var annual_saving_tableID = "initiatives_completed_annual_saving" + unqid;
        }

        if (window.XMLHttpRequest) {
            xhr = new XMLHttpRequest();
        } else {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                selectobject = document.getElementById(annual_saving_tableID);
                n_left = f_getPosition(selectobject, 'Left');
                n_top = f_getPosition(selectobject, 'Top');

                document.getElementById("light_todo").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';>Close</a> &nbsp;<center></center><br/>" +
                    xhr.responseText;
                document.getElementById('light_todo').style.display = 'block';

                document.getElementById('light_todo').style.left = (n_left - 10) + 'px';
                document.getElementById('light_todo').style.top = (n_top + 10) + 'px';
                document.getElementById('light_todo').style.width = 700 + 'px';
                document.getElementById('light_todo').style.height = 230 + 'px';
            }
        }

        xhr.open("GET", "water_edit_annual_landfill_data.php?annual_landfill=annual&compid=" + compid + "&unqid=" + unqid,
            true);
        xhr.send();
    }

    // Update Annual Saving
    function update_water_annual(unqid, compid) {

        document.getElementById("initiative_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xhr = new XMLHttpRequest();
        } else {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById('light_todo').style.display = 'none';
                document.getElementById("initiative_div").innerHTML = xhr.responseText;
            }
        }

        var as_opportunity = document.getElementById('annual_saving_opportunity').value;
        var as_implemented = document.getElementById('annual_saving_implemented').value;
        var as_implement = document.getElementById('annual_saving_implement').value;
        var as_rejected = document.getElementById('annual_saving_rejected').value;

        xhr.open("GET", "todolist_water_update_row.php?edit_mode=annualS&unqid=" + unqid + "&compid=" + compid +
            "&as_opportunity=" + as_opportunity + "&as_implemented=" + as_implemented + "&as_implement=" +
            as_implement + "&as_rejected=" + as_rejected, true);
        xhr.send();
    }
    //
    function edit_task_landfill_diversion(unqid, compid, role) {

        var landfill_tableID = "landfill_tbl" + unqid;

        if (role == 1) {
            var landfill_tableID = "initiatives_completed_landfill_diversion" + unqid;
        }

        if (window.XMLHttpRequest) {
            xhr = new XMLHttpRequest();
        } else {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                selectobject = document.getElementById(landfill_tableID);
                n_left = f_getPosition(selectobject, 'Left');
                n_top = f_getPosition(selectobject, 'Top');

                document.getElementById("light_todo").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';>Close</a> &nbsp;<center></center><br/>" +
                    xhr.responseText;
                document.getElementById('light_todo').style.display = 'block';

                document.getElementById('light_todo').style.left = (n_left - 50) + 'px';
                document.getElementById('light_todo').style.top = (n_top + 10) + 'px';
                document.getElementById('light_todo').style.width = 700 + 'px';
                document.getElementById('light_todo').style.height = 230 + 'px';
            }
        }

        xhr.open("GET", "water_edit_annual_landfill_data.php?annual_landfill=landfill&compid=" + compid + "&unqid=" + unqid,
            true);
        xhr.send();
    }
    // Update landfill
    function update_water_landfill(unqid, compid) {

        document.getElementById("initiative_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xhr = new XMLHttpRequest();
        } else {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById('light_todo').style.display = 'none';
                document.getElementById("initiative_div").innerHTML = xhr.responseText;
            }
        }

        var ld_perlandfill = document.getElementById('per_in_landfill_total').value;
        var ld_implemented = document.getElementById('landfill_diversion_implemented').value;
        var ld_implement = document.getElementById('landfill_diversion_implement').value;
        var ld_rejected = document.getElementById('landfill_diversion_rejected').value;

        xhr.open("GET", "todolist_water_update_row.php?edit_mode=Landfill&unqid=" + unqid + "&compid=" + compid +
            "&ld_perlandfill=" + ld_perlandfill + "&ld_implemented=" + ld_implemented + "&ld_implement=" +
            ld_implement + "&ld_rejected=" + ld_rejected, true);
        xhr.send();
    }
</script>
<?php
$MGArray = array();
$orderby = '';
$b2bid = "";
$MGArrayComplete = array();
if ($_REQUEST["show"] == "water-initiatives") {
?>

    <style type="text/css">
        .black_overlay {
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
        }

        .white_content {
            display: none;
            position: absolute;
            top: 5%;
            left: 5%;
            width: 60%;
            height: 90%;
            padding: 16px;
            border: 1px solid gray;
            background-color: white;
            z-index: 1002;
            overflow: auto;
        }
    </style>

    <div id="light_todo" class="white_content"></div>
    <div id="fade_todo" class="black_overlay"></div>

    <script LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></script>
    <script LANGUAGE="JavaScript" SRC="inc/general.js"></script>
    <script LANGUAGE="JavaScript">
        document.write(getCalendarStyles());
    </script>
    <script LANGUAGE="JavaScript">
        var cal2xxwater = new CalendarPopup("listdivwater");
        cal2xxwater.showNavigationDropdowns();

        var stepduedt = new CalendarPopup("listdiv_blastdt");
        stepduedt.showNavigationDropdowns();

        var cal2xxwateredit = new CalendarPopup("listdiv1");
        cal2xxwateredit.showNavigationDropdowns();

        var duedt = new CalendarPopup("listdiv_duedt");
        duedt.showNavigationDropdowns();

        var new_duedt = new CalendarPopup("int_duedt");
        new_duedt.showNavigationDropdowns();

        function isNumberKey(evt) {

            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode != 46 && charCode > 31 &&
                (charCode < 48 || charCode > 57))
                return false;

            return true;
        }
    </script>

    <?php
    $ID = $_REQUEST["ID"];
    if (isset($_REQUEST["id"])) {
        $id = $_REQUEST["id"];
    }
    if (isset($_REQUEST["warehouse_id"])) {
        $warehouse_id = $_REQUEST["warehouse_id"];
        $id = $_REQUEST["warehouse_id"];
    }
    if (isset($_REQUEST["ID"])) {
        $ID = $_REQUEST["ID"];
        $b2bid = $_REQUEST["ID"];
    }
    if (isset($_REQUEST["b2bid"])) {
        $b2bid = $_REQUEST["b2bid"];
        $ID = $_REQUEST["b2bid"];
    }

    /*to get company details*/
    $note2Title = '';
    db_b2b();
    $selCompDt = db_query("SELECT * FROM companyInfo WHERE ID = " . $_REQUEST["ID"]);
    $rowCompDt = array_shift($selCompDt);
    $note2Title = $rowCompDt['company'] . "-" . $rowCompDt['city'] . ", " . $rowCompDt['state'];

    ?>
    <br><br>

    <!--Add / Display WATER INITIATIVES list-->
    <div style="margin-bottom: 8px;">
        <font size="4" face="arial" color="#333333"><b>WATER INITIATIVES</b></font>
    </div>
    <!-- <input type="button" value="ADD NEW INITIATIVE" name="add_new_initiative" id="add_new_initiative" onclick="fun_show_new_initiative()" style="margin-bottom: 4px;"> -->
    <form method="post" name="new_initiative_frm" id="new_initiative_frm" action="" enctype="multipart/form-data">

        <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
                <td colspan="15" align="center" class="display_maintitle">
                    <strong>New Initiative</strong>
                </td>
            </tr>
            <tr>
                <td class="display_table" width="90px">
                    Initiative Step
                </td>
                <td class="display_table">
                    Initiative Title
                </td>
                <td class="display_table">
                    Initiative Details
                </td>
                <td class="display_table">
                    Annual Savings Opportunity (Gross Value)
                </td>
                <td class="display_table">
                    Annual Savings Implemented
                </td>
                <td class="display_table">
                    Annual Savings to Implement
                </td>
                <td class="display_table">
                    Annual Savings Rejected
                </td>
                <td class="display_table">
                    Potential % Increase in Total Landfill Diversion
                </td>
                <td class="display_table">
                    Landfill Diversion Implemented
                </td>
                <td class="display_table">
                    Landfill Diversion to Implement
                </td>
                <td class="display_table">
                    Landfill Diversion Rejected
                </td>
                <td class="display_table">
                    Initiative Details Due date
                </td>
                <td class="display_table">
                    Task Owner
                </td>
            </tr>
            <tr>
                <td class="display_table">
                    <select name="init_step" id="init_step" class="form_component" style="width: 150px;">
                        <?php
                        $int_step_qry = "select * from water_initiative_steps";
                        db();
                        $int_step_res = db_query($int_step_qry);
                        while ($int_step_rows = array_shift($int_step_res)) {
                        ?>
                            <option value="<?php echo $int_step_rows["unique_key"]; ?>">
                                <?php echo $int_step_rows["unique_key"] . "-" . $int_step_rows["initiative_step"]; ?></option>
                        <?php
                        }

                        ?>
                    </select>
                </td>
                <td class="display_table" valign="top">
                    <table>
                        <tr>
                            <?php

                            ?>
                            <td class="display_table">
                                <div id="init_title_id"></div>
                            </td>
                            <td class="display_table">
                                <textarea name="task_title" id="task_title" class="form_component"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="display_table" colspan="2">
                                <input type="file" id="task_title_file" name="task_title_file">
                            </td>
                        </tr>
                    </table>
                </td>

                <td class="display_table">
                    <textarea name="task_detail" id="task_detail" class="form_component"></textarea>
                </td>
                <td class="display_table">
                    $<input type="text" class="form_component" id="annual_saving_opportunity" onkeypress="return isNumberKey(event)" size="10">
                    <font color="red">*</font>
                </td>
                <td class="display_table">
                    $<input type="text" class="form_component" id="annual_saving_implemented" onkeypress="return isNumberKey(event)" size="8">
                    <font color="red">*</font>
                </td>
                <td class="display_table">
                    $<input type="text" class="form_component" id="annual_saving_implement" onkeypress="return isNumberKey(event)" size="8">
                    <font color="red">*</font>
                </td>
                <td class="display_table">
                    $<input type="text" class="form_component" id="annual_saving_rejected" onkeypress="return isNumberKey(event)" size="7">
                    <font color="red">*</font>
                </td>
                <td class="display_table">
                    <input type="text" class="form_component" id="per_in_landfill_total" onkeypress="return isNumberKey(event)" size="8">%
                    <font color="red">*</font>
                </td>
                <td class="display_table">
                    <input type="text" class="form_component" id="landfill_diversion_implemented" onkeypress="return isNumberKey(event)" size="8">%
                    <font color="red">*</font>
                </td>
                <td class="display_table">
                    <input type="text" class="form_component" id="landfill_diversion_implement" onkeypress="return isNumberKey(event)" size="8">%
                    <font color="red">*</font>
                </td>
                <td class="display_table">
                    <input type="text" class="form_component" id="landfill_diversion_rejected" onkeypress="return isNumberKey(event)" size="8">%<font color="red">*</font>
                </td>
                <td class="display_table">
                    <input type="text" name="due_date" id="due_date" size="10" value="<?php echo date('m/d/Y') ?>" class="form_component">
                    <a href="#" onclick="cal2xxwater.select(document.new_initiative_frm.due_date,'dtanchor2xxwater','MM/dd/yyyy'); return false;" name="dtanchor2xxwater" id="dtanchor2xxwater"><img border="0" src="images/calendar.jpg"></a>
                    <div ID="listdivwater" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                    </div>
                </td>
                <td class="display_table">
                    <select name="task_owner" id="task_owner" class="form_component" style="width: 80px;">
                        <option value="">Select</option>
                        <?php
                        $emp_res = "SELECT * FROM employees WHERE status='Active' order by name asc";
                        db_b2b();
                        $emp_res = db_query($emp_res);
                        while ($emp_row = array_shift($emp_res)) {
                        ?>
                            <option id="<?php echo $emp_row["initials"]; ?>"><?php echo "UCB-" . $emp_row["initials"]; ?></option>
                        <?php
                        }
                        $compw_res = "SELECT * FROM companyInfo WHERE ucbzw_flg=1";
                        db_b2b();
                        $compw_res = db_query($compw_res);
                        while ($compw_row = array_shift($compw_res)) {
                        ?>
                            <option id="<?php echo $compw_row["ID"]; ?>"><?php echo $compw_row["company"]; ?></option>
                        <?php
                        }
                        ?>
                    </select>

                    <input type="hidden" name="init_companyID" id="init_companyID" value="<?php echo $b2bid; ?>">
                    <input type="hidden" name="warehouseid" id="warehouseid" value="<?php echo $id; ?>">
                    <input type="hidden" name="init_created_by" id="init_created_by" value="<?php echo $_COOKIE["userinitials"]; ?>">

                </td>
            </tr>
            <tr align="center">
                <td colspan="15" class="display_table">
                    <input type="button" id="add_newinitiavite_btn" onclick="add_newinitiavite()" value="Submit" />
                    <input type="button" id="cancel_newinitiavite_btn" onclick="newinitiavite_cancel()" value="Cancel" />
                </td>
            </tr>

        </table>
        <br><br>
    </form>
    <form name="active_frm">
        <div id="initiative_div">

            <div ID="listdiv_blastdt" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>

            <?php
            //and task_status <> 4
            $imgasc  = '<img src="images/sort_asc.png" width="6px;" height="12px;">';
            $imgdesc = '<img src="images/sort_desc.png" width="6px;" height="12px;">';
            //$sorturl="?id=". $_REQUEST['id'] ."&b2bid=". $b2bid ."&ID=". $_REQUEST['ID'] ."&warehouse_id=". $id ."&searchcrit=&show=water-initiatives&rec_type=". $rec_type ."&status=". $status ."&purchasing=yes&tblname=water_active&filterid=". $_REQUEST['filterid']."&sort_order=";
            // $sorturl = "?";
            // $sorturl .= isset($_REQUEST['id']) ? "id=" . $_REQUEST['id'] . "&" : "";
            // $sorturl .= isset($b2bid) ? "b2bid=" . $b2bid . "&" : "";
            // $sorturl .= isset($_REQUEST['ID']) ? "ID=" . $_REQUEST['ID'] . "&" : "";
            // $sorturl .= isset($id) ? "warehouse_id=" . $id . "&" : "";
            // $sorturl .= "searchcrit=&show=water-initiatives&";
            // $sorturl .= isset($rec_type) ? "rec_type=" . $rec_type . "&" : "";
            // $sorturl .= isset($status) ? "status=" . $status . "&" : "";
            // $sorturl .= "purchasing=yes&tblname=water_active&";
            // $sorturl .= isset($_REQUEST['filterid']) ? "filterid=" . $_REQUEST['filterid'] . "&" : "";
            // $sorturl .= "sort_order=";


            $sorturl = "?id=" . $_REQUEST['id'] . "&b2bid=" . $b2bid . "&ID=" . $_REQUEST['ID'] . "&warehouse_id=" . $id . "&searchcrit=&show=water-initiatives&rec_type=" . $rec_type . "&status=" . $status . "&purchasing=yes&tblname=water_active&filterid=" . $_REQUEST['filterid'] . "&sort_order=";

            $annual_saving_total = 0;
            $landfill_diversion_total = 0;
            if (isset($_REQUEST['tblname']) && $_REQUEST['tblname'] == "water_active") {
                if (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "pild") {
                    if ($_REQUEST['sort_order'] == "ASC") {
                        $orderby = "ORDER BY pilt ASC";
                    } else {
                        $orderby = "ORDER BY pilt DESC";
                    }
                } elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "aso") {
                    if ($_REQUEST['sort_order'] == "ASC") {
                        $orderby = "ORDER BY aso ASC";
                    } else {
                        $orderby = "ORDER BY aso DESC";
                    }
                }
            } else {
                $orderby = "ORDER BY initiative_step ASC, taskid ASC";
            }
            //$sql = "SELECT * FROM water_initiatives where companyid = " . $b2bid . " and status = 1 group by initiative_step order by initiative_step ASC, taskid ASC" ;
            if (isset($_REQUEST["filterid"]) && $_REQUEST["filterid"] != "") {

                $sql = "SELECT *, sum(annual_saving_opportunity) AS aso, sum(per_in_landfill_total) as pilt FROM water_initiatives WHERE companyid = " . $b2bid . " 
				AND status = 1 AND task_status = '" . $_REQUEST["filterid"] . "' GROUP BY initiative_step " . $orderby;
            } else {
                $sql = "SELECT *, sum(annual_saving_opportunity) AS aso, sum(per_in_landfill_total) as pilt FROM water_initiatives where companyid = " . $b2bid . " and status = 1 group by initiative_step " . $orderby;
            }



            db();
            $result = db_query($sql);
            if (tep_db_num_rows($result) > 0) {
                $cnt = 0;
                while ($step_rows = array_shift($result)) {

                    $tmp_records = [];
                    if (isset($_REQUEST["filterid"]) && $_REQUEST["filterid"] != "") {
                        $init_sql = "SELECT * FROM water_initiatives where companyid = " . $b2bid . " and status = 1  and initiative_step= '" . $step_rows["initiative_step"] . "' and task_status ='" . $_REQUEST["filterid"] . "' order by task_sub_id";
                    } else {
                        $init_sql = "SELECT * FROM water_initiatives where companyid = " . $b2bid . " and status = 1  and initiative_step= '" . $step_rows["initiative_step"] . "' order by task_sub_id";
                    }


                    db();
                    $init_result = db_query($init_sql);
                    $total_steps = tep_db_num_rows($init_result);

                    $int_step_qry = "select * from water_initiative_steps where unique_key='" . $step_rows["initiative_step"] . "'";
                    db();
                    $int_step_res = db_query($int_step_qry);
                    $int_step_rows = array_shift($int_step_res);

                    while ($myrowsel = array_shift($init_result)) {
                        $date1 = new DateTime($myrowsel["due_date"]);
                        $date2 = new DateTime();

                        $days = (strtotime($myrowsel["due_date"]) - strtotime(date("Y-m-d"))) / (60 * 60 * 24);
                        $tmp_records[] = array(
                            'days' => $days,
                            'taskid' => $myrowsel["taskid"],
                            'task_sub_id' => $myrowsel["task_sub_id"],
                            'task_title' => $myrowsel["task_title"],
                            'task_details' => $myrowsel["task_details"],
                            'initiative_step_status' => $myrowsel["initiative_step_status"],
                            'step_due_date' => $myrowsel["step_due_date"],
                            'task_title_file' => $myrowsel["task_title_file"],
                            'annual_saving_opportunity' => $myrowsel["annual_saving_opportunity"],
                            'annual_saving_implemented' => $myrowsel["annual_saving_implemented"],
                            'annual_saving_implement' => $myrowsel["annual_saving_implement"],
                            'annual_saving_rejected' => $myrowsel["annual_saving_rejected"],
                            'per_in_landfill_total' => $myrowsel["per_in_landfill_total"],
                            'landfill_diversion_implemented' => $myrowsel["landfill_diversion_implemented"],
                            'landfill_diversion_implement' => $myrowsel["landfill_diversion_implement"],
                            'landfill_diversion_rejected' => $myrowsel["landfill_diversion_rejected"],
                            'task_status' => $myrowsel["task_status"],
                            'due_date' => $myrowsel["due_date"],
                            'task_owner' => $myrowsel["task_owner"],
                            'task_added_on' => $myrowsel["task_added_on"],
                            'task_notes' => $myrowsel["task_notes"],
                            'task_notes1' => $myrowsel["task_notes1"]
                        );
                    }

                    $MGArray[] = array(
                        'initiative_step' => $step_rows["initiative_step"],
                        'total_row' => $total_steps,
                        'initiative_step_title' => $int_step_rows["initiative_step"],
                        'initiative_step_status' => $step_rows["initiative_step_status"],
                        'taskid' => $step_rows["taskid"],
                        'step_due_date' => $step_rows["step_due_date"],
                        'taskdata' => $tmp_records
                    );
                }
            }




            $_SESSION['active_initiative'] = $MGArray;

            ?>
            <div id="active_initiative_filter_div">
                <table border="0" cellspacing="1" cellpadding="1" width="100%">
                    <tr align="center">
                        <td colspan="17" class="display_maintitle">
                            <strong>Active Initiatives</strong>
                        </td>
                    </tr>
                    <tr align="center">
                        <td class="display_title" valign="top"></td>
                        <td class="display_title" valign="top">Initiative Step
                        </td>
                        <td class="display_title" valign="top">
                            <div style="border-bottom: 1px solid #F7F7F7; margin-bottom: 2px;">Initiative Step Status</div>
                            <div style="line-height: 13px;">Once all sub-steps are completed<br>please mark the entire step
                                complete.</div>
                        </td>
                        <td class="display_title" valign="top">Initiative Step Due Date
                        </td>
                        <td class="display_title" valign="top">Initiative Title
                        </td>
                        <td class="display_title">Initiative Details
                        </td>
                        <td class="display_title">Annual Savings Opportunity (Gross Value)
                            <a href="<?php echo $sorturl; ?>ASC&sort=aso"><?php echo $imgasc; ?></a>
                            <a href="<?php echo $sorturl; ?>DESC&sort=aso"><?php echo $imgdesc; ?></a>
                        </td>
                        <td class="display_title">Potential % Increase in Total Landfill Diversion
                            <a href="<?php echo $sorturl; ?>ASC&sort=pild"><?php echo $imgasc; ?></a>
                            <a href="<?php echo $sorturl; ?>DESC&sort=pild"><?php echo $imgdesc; ?></a>
                        </td>
                        <td class="display_title" valign="top">Initiative Status<br>
                            <a href="#" id="filterhiddenlink" style="display:none;">hidden</a>
                            <select onchange="filter_active_initiative_records(this.value)" class="form_component" style="width: 90px;">
                                <option value="">Select</option>
                                <?php
                                $int_task_qry = "select * from water_task_status";
                                db();
                                $int_task_res = db_query($int_task_qry);
                                while ($int_task_rows = array_shift($int_task_res)) {
                                ?>
                                    <option value="<?php echo $int_task_rows["id"]; ?>" <?php if (isset($_REQUEST["filterid"]) && $_REQUEST["filterid"] == $int_task_rows["id"]) {
                                                                                            echo "selected";
                                                                                        } else {
                                                                                        } ?>>
                                        <?php echo $int_task_rows["task_status"]; ?></option>
                                <?php
                                } ?>
                            </select>
                        </td>
                        <td class="display_title" valign="top">Initiative Due Date
                        </td>
                        <td class="display_title" valign="top">Initiative<br>Task Owner
                        </td>
                        <td class="display_title" valign="top">Date Created
                        </td>
                        <!-- <td class="display_title" valign="top">Notes
				</td> -->
                        <td class="display_title" valign="top">UCBZW Notes
                        </td>
                        <td class="display_title" valign="top">Client Notes
                        </td>

                        <td class="display_title">&nbsp;</td>
                        <td class="display_title">&nbsp;</td>
                        <td class="display_title" valign="top">Task ID</td>

                    </tr>

                    <?php
                    $annual_saving_opportunity_total = 0;
                    $annual_saving_implemented_total = 0;
                    $annual_saving_implement_total = 0;
                    $annual_saving_rejected_total = 0;
                    $per_in_landfill_total_all = 0;
                    $landfill_diversion_implemented_total = 0;
                    $landfill_diversion_implement_total = 0;
                    $landfill_diversion_rejected_total = 0;
                    $all_row = 0;
                    $all_row_cnt = 0;

                    foreach ($MGArray as $rw1) {
                        $all_row = $all_row + $rw1["total_row"];
                        if ($rw1["initiative_step"] != "") {
                    ?>
                            <tr id="init_step_row<?php echo $rw1["initiative_step"]; ?>">
                                <td rowspan="<?php echo $rw1["total_row"]; ?>" class="display_table" align="left" valign="top">
                                    <input type="button" value="Edit Steps" name="todo_edit_water_steps" id="todo_edit_water_steps<?php echo $rw1["initiative_step"]; ?>" onclick="todoitem_edit_water_steps(<?php echo $rw1["initiative_step"]; ?>, <?php echo $b2bid; ?>)" class="form_component">
                                    <br>
                                    <input type="button" value="Add new &#x00A;Initiative Title" name="todo_add_new_steps" id="todo_add_new_steps<?php echo $rw1["initiative_step"]; ?>" onclick="todoitem_addnew_water_steps(<?php echo $rw1["initiative_step"]; ?>, <?php echo $b2bid; ?>)" class="form_component">
                                </td>
                                <td rowspan="<?php echo $rw1["total_row"]; ?>" class="display_table" align="left" valign="top">
                                    <?php echo $rw1["initiative_step_title"]; ?>
                                </td>
                                <td class="display_table" align="left" rowspan="<?php echo $rw1["total_row"]; ?>" valign="top">
                                    <select name="step_status" id="step_status<?php echo $rw1["initiative_step"] ?>" class="form_component">
                                        <option value="">Select</option>
                                        <option <?php if (($rw1["initiative_step_status"] != "") && ($rw1["initiative_step_status"] == "Complete")) {
                                                    echo "selected";
                                                } ?>>
                                            Complete</option>
                                        <option <?php if (($rw1["initiative_step_status"] != "") && ($rw1["initiative_step_status"] == "In Process")) {
                                                    echo "selected";
                                                } ?>>
                                            In Process</option>
                                        <option <?php if (($rw1["initiative_step_status"] != "") && ($rw1["initiative_step_status"] == "Pending")) {
                                                    echo "selected";
                                                } ?>>
                                            Pending</option>
                                    </select>
                                    <div class="update_link_div">
                                        <a class="sstatus_up" id="up_step_status<?php echo $rw1["taskid"]; ?>" name="up_step_status" onclick="update_step_status(<?php echo $rw1["initiative_step"]; ?>, <?php echo $rw1["taskid"]; ?>, <?php echo $b2bid; ?>)">Update</a>
                                    </div>
                                </td>
                                <td class="display_table" align="left" rowspan="<?php echo $rw1["total_row"]; ?>" valign="top">
                                    <input name="step_due_date<?php echo $rw1["initiative_step"]; ?>" type="text" class="form_component" id="step_due_date<?php echo $rw1["initiative_step"]; ?>" value="<?php if (isset($rw1["step_due_date"]) && ($rw1["step_due_date"] != '0000-00-00')) {
                                                                                                                                                                                                                echo $rw1["step_due_date"];
                                                                                                                                                                                                            } else {
                                                                                                                                                                                                                echo "";
                                                                                                                                                                                                            } ?>" style="width:70px; ">&nbsp;
                                    <a href="#" onclick="stepduedt.select(document.active_frm.step_due_date<?php echo $rw1["initiative_step"]; ?>,'anchorblastdt<?php echo $rw1["initiative_step"]; ?>','yyyy-MM-dd'); return false;" name="anchorblastdt<?php echo $rw1["initiative_step"]; ?>" id="anchorblastdt<?php echo $rw1["initiative_step"]; ?>"><img border="0" src="images/calendar.jpg"></a>

                                    <div class="update_link_div">
                                        <a class="sstatus_up" id="up_step_duedt<?php echo $rw1["taskid"]; ?>" onclick="update_step_duedate(<?php echo $rw1["initiative_step"] ?>, <?php echo $rw1["taskid"]; ?>, <?php echo $b2bid; ?>)" name="up_step_duedt">Update</a>
                                    </div>
                                </td>

                                <?php
                            }


                            $arr2sort = $rw1["taskdata"];
                            if ($_REQUEST['sort'] == "aso") {
                                $MGArraysort_I = array();

                                foreach ($arr2sort as $MGArraytmp) {
                                    $MGArraysort_I[] = $MGArraytmp['annual_saving_opportunity'];
                                }

                                if ($_REQUEST['sort_order'] == "ASC") {
                                    array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $arr2sort);
                                } else {
                                    array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $arr2sort);
                                }
                            }

                            if ($_REQUEST['sort'] == "pild") {
                                $MGArraysort_I = array();

                                foreach ($arr2sort as $MGArraytmp) {
                                    $MGArraysort_I[] = $MGArraytmp['per_in_landfill_total'];
                                }

                                if ($_REQUEST['sort_order'] == "ASC") {
                                    array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $arr2sort);
                                } else {
                                    array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $arr2sort);
                                }
                            }
                            foreach ($arr2sort as $myrowsel) {


                                if ($rw1["initiative_step"] == "") {
                                    $initiative_step = 0;
                                ?>
                            <tr id="init_step_row<?php echo $myrowsel["taskid"] ?>">
                                <td class="display_table" align="left" valign="top">
                                    <input type="button" value="Edit Steps" name="todo_edit_water_steps" id="todo_edit_water_steps<?php echo $myrowsel["taskid"] ?>" onclick="todoitem_edit_water_steps(<?php echo $myrowsel["taskid"] ?>, <?php echo $b2bid; ?>, <?php echo $myrowsel["taskid"] ?>)" class="form_component">
                                    <br>
                                    <input type="button" value="Add new &#x00A;Initiative Title" name="todo_add_new_steps" id="todo_add_new_steps<?php echo $myrowsel["taskid"] ?>" onclick="todoitem_addnew_water_steps(<?php echo $myrowsel["taskid"] ?>, <?php echo $b2bid; ?>, <?php echo $myrowsel["taskid"] ?>)" class="form_component">
                                </td>
                                <td class="display_table" align="left" valign="top">
                                    <?php echo $rw1["initiative_step_title"]; ?>
                                </td>
                                <td class="display_table" align="left" valign="top">
                                    <select name="step_status" id="step_status<?php echo $myrowsel["taskid"] ?>" class="form_component">
                                        <option value="">Select</option>
                                        <option <?php if (($myrowsel["initiative_step_status"] != "") && ($myrowsel["initiative_step_status"] == "Complete")) {
                                                    echo "selected";
                                                } ?>>
                                            Complete</option>
                                        <option <?php if (($myrowsel["initiative_step_status"] != "") && ($myrowsel["initiative_step_status"] == "In Process")) {
                                                    echo "selected";
                                                } ?>>
                                            In Process</option>
                                        <option <?php if (($myrowsel["initiative_step_status"] != "") && ($myrowsel["initiative_step_status"] == "Pending")) {
                                                    echo "selected";
                                                } ?>>
                                            Pending</option>
                                    </select>
                                    <br>
                                    <div class="update_link_div">
                                        <a class="sstatus_up" name="up_step_status" id="up_step_status<?php echo $myrowsel["taskid"]; ?>" onclick="update_step_status(<?php echo $initiative_step ?>, <?php echo $myrowsel["taskid"]; ?>, <?php echo $b2bid; ?>)">Update</a>
                                    </div>
                                </td>
                                <td class="display_table" align="left" valign="top">
                                    <input name="step_due_date<?php echo $myrowsel["taskid"] ?>" type="text" class="form_component" id="step_due_date<?php echo $myrowsel["taskid"] ?>" value="<?php if (isset($myrowsel["step_due_date"]) && ($myrowsel["step_due_date"] != '0000-00-00')) {
                                                                                                                                                                                                    echo $myrowsel["step_due_date"];
                                                                                                                                                                                                } else {
                                                                                                                                                                                                    echo "";
                                                                                                                                                                                                } ?>" style="width:70px; ">&nbsp;
                                    <a href="#" onclick="stepduedt.select(document.active_frm.step_due_date<?php echo $myrowsel["taskid"] ?>,'anchorblastdt<?php echo $myrowsel["taskid"] ?>','yyyy-MM-dd'); return false;" name="anchorblastdt<?php echo $myrowsel["taskid"] ?>" id="anchorblastdt<?php echo $myrowsel["taskid"] ?>">
                                        <img border="0" src="images/calendar.jpg"></a>
                                    <div class="update_link_div">
                                        <a class="sstatus_up" name="up_step_duedt" id="up_step_duedt<?php echo $myrowsel["taskid"]; ?>;?>" onclick="update_step_duedate(<?php echo $initiative_step ?>, <?php echo $myrowsel["taskid"]; ?>, <?php echo $b2bid; ?>)">Update</a>
                                    </div>
                                </td>
                            <?php } ?>
                            <td align="left" class="display_table" valign="top" style="padding: 0px;">
                                <table>
                                    <tr>
                                        <td class="display_table" valign="top" align="left">
                                            <?php echo $myrowsel["task_sub_id"] . ". " ?>
                                        </td>
                                        <td class="display_table">
                                            <?php echo $myrowsel["task_title"] ?>
                                            <?php if ($myrowsel["task_title_file"] != "") { ?>
                                                <a href="water_initiative_files/<?php echo $myrowsel["task_title_file"]; ?>" target="_blank">View File</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td class="display_table" align="left" valign="top">
                                <?php echo $myrowsel["task_details"]; ?>
                            </td>
                            <td class="display_table" align="left" valign="top">
                                <?php
                                $all_row_cnt++;
                                echo "<table id='annual_tbl" . $myrowsel["taskid"] . "'>";
                                echo "<tr><td class='display_table'>Annual Savings Opportunity (Gross Value):</td><td class='display_table' align='right'>$" . number_format($myrowsel["annual_saving_opportunity"], 0) . "</td></tr>";
                                echo "<tr><td class='display_table'>Annual Savings Implemented:</td><td class='display_table'  align='right'>$" . number_format($myrowsel["annual_saving_implemented"], 0) . "</td></tr>";
                                echo "<tr><td class='display_table'>Annual Savings to Implement:</td><td class='display_table'  align='right'>$" . number_format($myrowsel["annual_saving_implement"], 0) . "</td></tr>";
                                echo "<tr><td class='display_table'>Annual Savings Rejected:</td><td class='display_table' align='right'>$" . number_format($myrowsel["annual_saving_rejected"], 0) . "</td></tr>";

                                echo "<tr><td class='display_table'>&nbsp;</td><td>";

                                echo "<input type='button' class='form_component' value= 'Edit' onclick='edit_task_annual_saving(" . $myrowsel["taskid"] . ", " . $b2bid . ")'>";
                                echo "</td></tr>";
                                echo "</table>";

                                $annual_saving_opportunity_total = $annual_saving_opportunity_total + str_replace(",", "", number_format($myrowsel["annual_saving_opportunity"], 0));
                                $annual_saving_implemented_total = $annual_saving_implemented_total + str_replace(",", "", number_format($myrowsel["annual_saving_implemented"], 0));
                                $annual_saving_implement_total = $annual_saving_implement_total + str_replace(",", "", number_format($myrowsel["annual_saving_implement"], 0));
                                $annual_saving_rejected_total = $annual_saving_rejected_total + str_replace(",", "", number_format($myrowsel["annual_saving_rejected"], 0));

                                ?>
                            </td>
                            <td class="display_table" align="left" valign="top">
                                <?php
                                echo "<table id='landfill_tbl" . $myrowsel["taskid"] . "'>";
                                echo "<tr><td class='display_table'>Potential % Increase in Total Landfill Diversion:</td><td class='display_table'  align='right'>" . number_format($myrowsel["per_in_landfill_total"], 0) . "%</td></tr>";
                                echo "<tr><td class='display_table'>Landfill Diversion Implemented:</td><td class='display_table'  align='right'>" . number_format($myrowsel["landfill_diversion_implemented"], 0) . "%</td></tr>";
                                echo "<tr><td class='display_table'>Landfill Diversion to Implement:</td><td class='display_table'  align='right'>" . number_format($myrowsel["landfill_diversion_implement"], 0) . "%</td></tr>";
                                echo "<tr><td class='display_table'>Landfill Diversion Rejected:</td><td class='display_table'  align='right'>" . number_format($myrowsel["landfill_diversion_rejected"], 0) . "%</td></tr>";
                                echo "<tr><td class='display_table'>&nbsp;</td><td>";
                                echo "<input type='button' class='form_component' value= 'Edit' onclick='edit_task_landfill_diversion(" . $myrowsel["taskid"] . ", " . $b2bid . ")'>";
                                echo "</td></tr>";
                                echo "</table>";

                                $per_in_landfill_total_all = $per_in_landfill_total_all + str_replace(",", "", number_format($myrowsel["per_in_landfill_total"], 0));
                                $landfill_diversion_implemented_total = $landfill_diversion_implemented_total + str_replace(",", "", number_format($myrowsel["landfill_diversion_implemented"], 0));
                                $landfill_diversion_implement_total = $landfill_diversion_implement_total + str_replace(",", "", number_format($myrowsel["landfill_diversion_implement"], 0));
                                $landfill_diversion_rejected_total = $landfill_diversion_rejected_total + str_replace(",", "", number_format($myrowsel["landfill_diversion_rejected"], 0));

                                ?>
                            </td>
                            <?php
                                $task_status_bgcolor = "";
                                if ($myrowsel["task_status"] == 4) {
                                    $task_status_bgcolor = "bgcolor='#AAFFAA'";
                                } else if ($myrowsel["task_status"] == 1) {
                                    $task_status_bgcolor = "bgcolor='#FFFFAA'";
                                } else if ($myrowsel["task_status"] == "") {
                                    $task_status_bgcolor = "bgcolor='#EBEBEB'";
                                } else {
                                    $task_status_bgcolor = "bgcolor='#FFAAAA'";
                                }
                            ?>
                            <td class="info_s" valign="top" <?php echo $task_status_bgcolor; ?>>
                                <select name="task_status" id="task_status<?php echo $myrowsel["taskid"] ?>" class="form_component" style="width: 90px;" align="left">
                                    <option>Select</option>
                                    <?php
                                    $int_task_qry = "select * from water_task_status";
                                    db();
                                    $int_task_res = db_query($int_task_qry);
                                    while ($int_task_rows = array_shift($int_task_res)) {
                                    ?>
                                        <option value="<?php echo $int_task_rows["id"]; ?>" <? if (
                                                                                                $myrowsel["task_status"] != "" &&
                                                                                                $myrowsel["task_status"] == $int_task_rows["id"]
                                                                                            ) {
                                                                                                echo "selected";
                                                                                            } else {
                                                                                            } ?>><?php echo $int_task_rows["task_status"]; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                            <?php
                                $task_due_color = "#E4E4E4";
                                if ($myrowsel["days"] == 0) {
                                    $task_due_color = "green";
                                }
                                if ($myrowsel["days"] > 0) {
                                    $task_due_color = "#E4E4E4";
                                }
                                if ($myrowsel["days"] < 0 && $myrowsel["task_status"] <> 4) {
                                    $task_due_color = "red";
                                }
                                if ($myrowsel["task_status"] == 4) {
                                    $task_due_color = "#E4E4E4";
                                }
                            ?>
                            <td bgcolor="<?php echo $task_due_color; ?>" class="info_s" align="left" valign="top">
                                <?php echo date("m/d/Y", strtotime($myrowsel["due_date"])) . " CT"; ?>
                            </td>
                            <td class="display_table" align="left" valign="top">
                                <?php echo $myrowsel["task_owner"] ?>
                            </td>
                            <td class="display_table" align="left">
                                <?php echo date("m/d/Y H:i:s", strtotime($myrowsel["task_added_on"]))  . " CT"; ?>
                            </td>
                            <td align="left" class="display_table">
                                <textarea cols="8" name="water_init_notes" id="water_init_notes<?php echo $myrowsel["taskid"] ?>" class="form_component"><?php echo $myrowsel["task_notes"] ?></textarea>
                            </td>
                            <td align="left" class="display_table">
                                <textarea cols="8" name="water_init_notes1" id="water_init_notes1<?php echo $myrowsel["taskid"] ?>" class="form_component"><?php echo $myrowsel["task_notes1"] ?></textarea>
                            </td>
                            <td align="left" class="display_table">
                                <?php
                                if ($myrowsel["initiative_step"] != "") {
                                ?>
                                    <input type="button" value="Save" name="todo_edit_water" id="todo_edit_water<?php echo $myrowsel["initiative_step"]; ?>" onclick="todoitem_edit_water(<?php echo $myrowsel["initiative_step"] ?>, <?php echo $myrowsel["taskid"]; ?>, <?php echo $b2bid; ?>)" class="form_component">
                                <?php
                                } else {
                                    $initiative_step = 0;
                                ?>
                                    <input type="button" value="Save" name="todo_edit_water" id="todo_edit_water<?php echo $myrowsel["taskid"]; ?>" onclick="todoitem_edit_water(<?php echo $initiative_step ?>, <?php echo $myrowsel["taskid"]; ?>, <?php echo $b2bid; ?>)" class="form_component">
                                <?php
                                }
                                ?>
                            </td>
                            <td align="left" class="display_table">
                                <?php
                                if ($myrowsel["initiative_step"] != "") {
                                ?>
                                    <input type="button" value="Delete" name="todo_del_water" id="todo_del_water<?php echo $myrowsel["initiative_step"]; ?>" onclick="todoitem_delete_water(<?php echo $myrowsel["initiative_step"] ?>, <?php echo $myrowsel["taskid"]; ?>, <?php echo $b2bid; ?>)" class="form_component">
                                <?php
                                } else {
                                ?>
                                    <input type="button" value="Delete" name="todo_del_water" id="todo_del_water<?php echo $myrowsel["taskid"]; ?>" onclick="todoitem_delete_water(<?php echo $initiative_step ?>, <?php echo $myrowsel["taskid"]; ?>, <?php echo $b2bid; ?>)" class="form_component">
                                <?php
                                }
                                ?>
                            </td>

                            <td align="center" class="display_table"><?php echo $myrowsel["taskid"]; ?></td>
                            </tr>
                        <?php
                            }


                        ?>

                    <?php

                    }


                    ?>
                    <tr>
                        <td class="display_table" colspan="5">&nbsp;</td>
                        <td class="display_table" align="right"><b>Total:</b></td>
                        <td class="display_table" align="right">
                            <table>
                                <tr>
                                    <td class='display_table'>TOTAL SUM for Annual Savings Opportunity:</td>
                                    <td class='display_table' align='right'>
                                        <b>$<?php echo number_format($annual_saving_opportunity_total, 2); ?></b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='display_table'>TOTAL SUM for Annual Savings Implemented:</td>
                                    <td class='display_table' align='right'>
                                        <b>$<?php echo number_format($annual_saving_implemented_total, 2); ?></b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='display_table'>TOTAL SUM for Annual Savings to Implement:</td>
                                    <td class='display_table' align='right'>
                                        <b>$<?php echo number_format($annual_saving_implement_total, 2); ?></b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='display_table'>TOTAL SUM for Annual Savings Rejected:</td>
                                    <td class='display_table' align='right'>
                                        <b>$<?php echo number_format($annual_saving_rejected_total, 2); ?></b>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <td class="display_table" align="right">
                            <table>
                                <tr>
                                    <td class='display_table'>TOTAL SUM for Potential % Increase in Total Landfill
                                        Diversion:</td>
                                    <td class='display_table' align='right'><b>
                                            <?php
                                            if ($all_row_cnt > 0) {

                                                echo number_format($per_in_landfill_total_all, 2);
                                            } else {
                                                echo "0";
                                            }
                                            ?>
                                            %</b></td>
                                </tr>
                                <tr>
                                    <td class='display_table'>TOTAL SUM for Landfill Diversion Implemented:</td>
                                    <td class='display_table' align='right'>
                                        <b><?php echo number_format($landfill_diversion_implemented_total, 2); ?>%</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='display_table'>TOTAL SUM for Landfill Diversion to Implement:</td>
                                    <td class='display_table' align='right'>
                                        <b><?php echo number_format($landfill_diversion_implement_total, 2); ?>%</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='display_table'>TOTAL SUM for Landfill Diversion Rejected:</td>
                                    <td class='display_table' align='right'>
                                        <b><?php echo number_format($landfill_diversion_rejected_total, 2); ?>%</b>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="display_table" colspan="7">&nbsp;</td>
                    </tr>
                </table>
            </div>
            <br>
            <div id="complete_initiative_filter_div">
                <?php

                $imgasc  = '<img src="images/sort_asc.png" width="6px;" height="12px;">';
                $imgdesc = '<img src="images/sort_desc.png" width="6px;" height="12px;">';
                // $sorturl2 = "?id=" . $_REQUEST['id'] . "&b2bid=" . (isset($b2bid) ? $b2bid : "") . "&ID=" . $_REQUEST['ID'] . "&warehouse_id=" . (isset($id) ? $id : "") . "&searchcrit=&show=water-initiatives&rec_type=" . (isset($rec_type) ? $rec_type : "") . "&status=" . (isset($status) ? $status : "") . "&purchasing=yes&tblname=water_complete&filterid=" . $_REQUEST['filterid'] . "&sort_order=";
                $sorturl2 = "?id=" . $_REQUEST['id'] . "&b2bid=" . $b2bid . "&ID=" . $_REQUEST['ID'] . "&warehouse_id=" . $id . "&searchcrit=&show=water-initiatives&rec_type=" . $rec_type . "&status=" . $status . "&purchasing=yes&tblname=water_complete&filterid=" . $_REQUEST['filterid'] . "&sort_order=";


                $annual_saving_total = 0;
                $landfill_diversion_total = 0;
                if (isset($_REQUEST['tblname']) && $_REQUEST['tblname'] == "water_complete") {
                    if (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "cpild") {
                        if ($_REQUEST['sort_order'] == "ASC") {
                            $orderby = "ORDER BY pilt ASC";
                        } else {
                            $orderby = "ORDER BY pilt DESC";
                        }
                    } elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "caso") {
                        if ($_REQUEST['sort_order'] == "ASC") {
                            $orderby = "ORDER BY aso ASC";
                        } else {
                            $orderby = "ORDER BY aso DESC";
                        }
                    }
                } else {
                    $orderby = "ORDER BY aso ASC";
                }

                $sql = "SELECT *, sum(annual_saving_opportunity) AS aso, sum(per_in_landfill_total) as pilt FROM water_initiatives where companyid = " . $b2bid . " and status = 2 group by initiative_step $orderby";
                // and task_status='Completed'
                db();
                $result = db_query($sql);
                if (tep_db_num_rows($result) > 0) {
                    while ($step_rows = array_shift($result)) {
                        $tmp_records = [];
                        $init_sql = "SELECT * FROM water_initiatives where companyid = " . $b2bid . " and status = 2  and initiative_step= '" . $step_rows["initiative_step"] . "' order by task_sub_id";
                        db();
                        $init_result = db_query($init_sql);
                        $total_steps = tep_db_num_rows($init_result);


                        $int_step_qry = "select * from water_initiative_steps where unique_key='" . $step_rows["initiative_step"] . "'";
                        db();
                        $int_step_res = db_query($int_step_qry);
                        $int_step_rows = array_shift($int_step_res);

                        while ($myrowsel = array_shift($init_result)) {
                            $date1 = new DateTime($myrowsel["due_date"]);
                            $date2 = new DateTime();

                            $days = (strtotime($myrowsel["due_date"]) - strtotime(date("Y-m-d"))) / (60 * 60 * 24);
                            $tmp_records[] = array(
                                'days' => $days,
                                'taskid' => $myrowsel["taskid"],
                                'task_sub_id' => $myrowsel["task_sub_id"],
                                'task_title' => $myrowsel["task_title"],
                                'task_details' => $myrowsel["task_details"],
                                'initiative_step_status' => $myrowsel["initiative_step_status"],
                                'step_due_date' => $myrowsel["step_due_date"],
                                'annual_saving_opportunity' => $myrowsel["annual_saving_opportunity"],
                                'annual_saving_implemented' => $myrowsel["annual_saving_implemented"],
                                'annual_saving_implement' => $myrowsel["annual_saving_implement"],
                                'annual_saving_rejected' => $myrowsel["annual_saving_rejected"],
                                'per_in_landfill_total' => $myrowsel["per_in_landfill_total"],
                                'landfill_diversion_implemented' => $myrowsel["landfill_diversion_implemented"],
                                'landfill_diversion_implement' => $myrowsel["landfill_diversion_implement"],
                                'landfill_diversion_rejected' => $myrowsel["landfill_diversion_rejected"],
                                'task_status' => $myrowsel["task_status"],
                                'due_date' => $myrowsel["due_date"],
                                'task_owner' => $myrowsel["task_owner"],
                                'task_added_on' => $myrowsel["task_added_on"],
                                'task_notes' => $myrowsel["task_notes"],
                                'task_notes1' => $myrowsel["task_notes1"]
                            );
                        }

                        $MGArrayComplete[] = array(
                            'initiative_step' => $step_rows["initiative_step"],
                            'total_row' => $total_steps,
                            'initiative_step_title' => $int_step_rows["initiative_step"],
                            'initiative_step_status' => $step_rows["initiative_step_status"],
                            'taskid' => $step_rows["taskid"],
                            'step_due_date' => $step_rows["step_due_date"],
                            'taskdata' => $tmp_records
                        );
                    }
                }

                $_SESSION['complete_initiative'] = $MGArrayComplete;

                ?>
                <table border="0" cellspacing="1" cellpadding="1" width="100%">
                    <tr align="center">
                        <td colspan="15" class="display_maintitle">
                            <strong>Initiatives Completed</strong>
                        </td>
                    </tr>
                    <tr align="center">
                        <td class="display_title" valign="top"></td>
                        <td class="display_title">&nbsp;</td>
                        <td class="display_title" valign="top">Initiative Step
                        </td>

                        <td class="display_title" valign="top">Initiative Title
                        </td>
                        <td class="display_title">Initiative Details</td>
                        <td class="display_title">Annual Savings Opportunity (Gross Value)
                            <a href="<?php echo $sorturl2; ?>ASC&sort=caso"><?php echo $imgasc; ?></a>
                            <a href="<?php echo $sorturl2; ?>DESC&sort=caso"><?php echo $imgdesc; ?></a>
                        </td>
                        <td class="display_title">Potential % Increase in Total Landfill Diversion
                            <a href="<?php echo $sorturl2; ?>ASC&sort=cpild"><?php echo $imgasc; ?></a>
                            <a href="<?php echo $sorturl2; ?>DESC&sort=cpild"><?php echo $imgdesc; ?></a>
                        </td>
                        <td class="display_title" valign="top">Initiative<br>Due Date
                        </td>
                        <td class="display_title" valign="top">Initiative<br>Task Owner
                        </td>
                        <td class="display_title" valign="top">Date Created
                        </td>
                        <!-- <td class="display_title" valign="top">Notes
				</td> -->
                        <td class="display_title" valign="top">UCBZW Notes
                        </td>
                        <td class="display_title" valign="top">Client Notes
                        </td>

                        <td class="display_title">&nbsp;</td>

                        <td class="display_title">&nbsp;</td>
                        <td class="display_title" valign="top">Task ID</td>

                    </tr>

                    <?php
                    $annual_saving_total = 0;
                    $landfill_diversion_total = 0;

                    $annual_saving_opportunity_total = 0;
                    $annual_saving_implemented_total = 0;
                    $annual_saving_implement_total = 0;
                    $annual_saving_rejected_total = 0;
                    $per_in_landfill_total_all = 0;
                    $landfill_diversion_implemented_total = 0;
                    $landfill_diversion_implement_total = 0;
                    $landfill_diversion_rejected_total = 0;
                    $all_row = 0;
                    $all_row_cnt = 0;

                    foreach ($MGArrayComplete as $rw2) {
                        $all_row = $all_row + $rw2["total_row"];
                        if ($rw2["initiative_step"] != "") {
                    ?>
                            <tr id="init_step_row<?php echo $rw2["initiative_step"]; ?>">
                                <td rowspan="<?php echo $rw2["total_row"]; ?>" class="display_table" align="left" valign="top">
                                    <input type="button" value="Edit Steps" name="todo_edit_water_steps_complete" id="todo_edit_water_steps_complete<?php echo $rw2["initiative_step"]; ?>" onclick="todoitem_edit_water_steps_complete(<?php echo $rw2["initiative_step"]; ?>, <?php echo $b2bid; ?>)" class="form_component">
                                    <br>
                                    <input type="button" value="Add new &#x00A;Initiative Title" name="todo_add_new_steps_complete" id="todo_add_new_steps_complete<?php echo $rw2["initiative_step"]; ?>" onclick="todoitem_addnew_water_steps_complete(<?php echo $rw2["initiative_step"]; ?>, <?php echo $b2bid; ?>)" class="step_rows form_component">
                                </td>
                                <td rowspan="<?php echo $rw2["total_row"]; ?>" align="left" class="display_table" valign="top">
                                    <input type="button" value="Undo - Complete" name="undo_complete" id="undo_complete<?php echo $rw2["taskid"]; ?>" onclick="initiative_undo_complete(<?php echo $rw2["initiative_step"] ?>, <?php echo $rw2["taskid"]; ?>, <?php echo $b2bid; ?>)" class="form_component">

                                </td>
                                <td rowspan="<?php echo $rw2["total_row"]; ?>" class="display_table" align="left" valign="top">
                                    <?php echo $rw2["initiative_step_title"]; ?>
                                </td>
                                <?php
                            }

                            $arr2sort = $rw2["taskdata"];
                            if ($_REQUEST['sort'] == "caso") {
                                $MGArraysort_I = array();

                                foreach ($arr2sort as $MGArraytmp) {
                                    $MGArraysort_I[] = $MGArraytmp['annual_saving_opportunity'];
                                }

                                if ($_REQUEST['sort_order'] == "ASC") {
                                    array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $arr2sort);
                                } else {
                                    array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $arr2sort);
                                }
                            }

                            if ($_REQUEST['sort'] == "cpild") {
                                $MGArraysort_I = array();

                                foreach ($arr2sort as $MGArraytmp) {
                                    $MGArraysort_I[] = $MGArraytmp['per_in_landfill_total'];
                                }

                                if ($_REQUEST['sort_order'] == "ASC") {
                                    array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $arr2sort);
                                } else {
                                    array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $arr2sort);
                                }
                            }
                            foreach ($arr2sort as $myrowsel) {
                                //foreach($rw2["taskdata"] as $myrowsel) {
                                if ($rw2["initiative_step"] == "") {
                                    $initiative_step = 0;
                                ?>
                            <tr id="init_step_row<?php echo $myrowsel["taskid"] ?>">
                                <td class="display_table" align="left" valign="top">
                                    <input type="button" value="Edit Steps" name="todo_edit_water_steps_complete" id="todo_edit_water_steps_complete<?php echo $myrowsel["taskid"]; ?>" onclick="todoitem_edit_water_steps_complete(<?php echo $initiative_step; ?>, <?php echo $b2bid; ?>, <?php echo $myrowsel["taskid"]; ?>)" class="form_component">
                                    <br>
                                    <input type="button" value="Add new &#x00A;Initiative Title" name="todo_add_new_steps_complete" id="todo_add_new_steps_complete<?php echo $myrowsel["taskid"]; ?>" onclick="todoitem_addnew_water_steps_complete(<?php echo $initiative_step; ?>, <?php echo $b2bid; ?>, <?php echo $myrowsel["taskid"]; ?>)" class="form_component">

                                </td>
                                <td align="left" class="display_table" valign="top">

                                    <input type="button" value="Undo - Complete" name="undo_complete" id="undo_complete<?php echo $myrowsel["taskid"]; ?>" onclick="initiative_undo_complete(<?php echo $initiative_step ?>, <?php echo $myrowsel["taskid"]; ?>, <?php echo $b2bid; ?>)" class="form_component">
                                </td>
                                <td class="display_table" align="left" valign="top">
                                    <?php
                                    $int_step_qry = "select * from water_initiative_steps where unique_key='" . $myrowsel["initiative_step"] . "'";
                                    db();
                                    $int_step_res = db_query($int_step_qry);
                                    $int_step_rows = array_shift($int_step_res);
                                    echo $int_step_rows["initiative_step"];
                                    ?>
                                </td>
                            <?php }    ?>

                            <td align="left" class="display_table" valign="top" style="padding: 0px;">
                                <table>
                                    <tr>
                                        <td class="display_table" valign="top" align="left">
                                            <?php echo $myrowsel["task_sub_id"] . ". " ?>
                                        </td>
                                        <td class="display_table">
                                            <?php echo $myrowsel["task_title"] ?>
                                        </td>
                                    </tr>
                                </table>

                            </td>
                            <td class="display_table" align="left" valign="top">
                                <?php echo $myrowsel["task_details"] ?>
                            </td>

                            <td class="display_table" align="left" valign="top">
                                <?php
                                $all_row_cnt++;
                                echo "<table  id='initiatives_completed_annual_saving" . $myrowsel["taskid"] . "'><tr><td class='display_table'>Annual Savings Opportunity (Gross Value):</td><td class='display_table' align='right'>$" . number_format($myrowsel["annual_saving_opportunity"], 0) . "</td></tr>";
                                echo "<tr><td class='display_table'>Annual Savings Implemented:</td><td class='display_table'  align='right'>$" . number_format($myrowsel["annual_saving_implemented"], 0) . "</td></tr>";
                                echo "<tr><td class='display_table'>Annual Savings to Implement:</td><td class='display_table'  align='right'>$" . number_format($myrowsel["annual_saving_implement"], 0) . "</td></tr>";
                                echo "<tr><td class='display_table'>Annual Savings Rejected:</td><td class='display_table' align='right'>$" . number_format($myrowsel["annual_saving_rejected"], 0) . "</td></tr>";

                                echo "<tr><td class='display_table'>&nbsp;</td><td>";
                                echo "<input type='button' class='form_component' value= 'Edit' onclick='edit_task_annual_saving(" . $myrowsel["taskid"] . ", " . $b2bid . ",1)'>";
                                echo "</td></tr>";
                                echo "</table>";

                                $annual_saving_opportunity_total = $annual_saving_opportunity_total + str_replace(",", "", number_format($myrowsel["annual_saving_opportunity"], 0));
                                $annual_saving_implemented_total = $annual_saving_implemented_total + str_replace(",", "", number_format($myrowsel["annual_saving_implemented"], 0));
                                $annual_saving_implement_total = $annual_saving_implement_total + str_replace(",", "", number_format($myrowsel["annual_saving_implement"], 0));
                                $annual_saving_rejected_total = $annual_saving_rejected_total + str_replace(",", "", number_format($myrowsel["annual_saving_rejected"], 0));

                                ?>

                            </td>
                            <td class="display_table" align="left" valign="top">
                                <?php
                                echo "<table id='initiatives_completed_landfill_diversion" . $myrowsel["taskid"] . "'>";
                                echo "<tr><td class='display_table'>Potential % Increase in Total Landfill Diversion:</td><td class='display_table'  align='right'>" . number_format($myrowsel["per_in_landfill_total"], 0) . "%</td></tr>";
                                echo "<tr><td class='display_table'>Landfill Diversion Implemented:</td><td class='display_table'  align='right'>" . number_format($myrowsel["landfill_diversion_implemented"], 0) . "%</td></tr>";
                                echo "<tr><td class='display_table'>Landfill Diversion to Implement:</td><td class='display_table'  align='right'>" . number_format($myrowsel["landfill_diversion_implement"], 0) . "%</td></tr>";
                                echo "<tr><td class='display_table'>Landfill Diversion Rejected:</td><td class='display_table'  align='right'>" . number_format($myrowsel["landfill_diversion_rejected"], 0) . "%</td></tr>";
                                echo "<tr><td class='display_table'>&nbsp;</td><td>";
                                echo "<input type='button' class='form_component' value= 'Edit' onclick='edit_task_landfill_diversion(" . $myrowsel["taskid"] . ", " . $b2bid . ",1)'>";
                                echo "</td></tr>";
                                echo "</table>";

                                $per_in_landfill_total_all = $per_in_landfill_total_all + str_replace(",", "", number_format($myrowsel["per_in_landfill_total"], 0));
                                $landfill_diversion_implemented_total = $landfill_diversion_implemented_total + str_replace(",", "", number_format($myrowsel["landfill_diversion_implemented"], 0));
                                $landfill_diversion_implement_total = $landfill_diversion_implement_total + str_replace(",", "", number_format($myrowsel["landfill_diversion_implement"], 0));
                                $landfill_diversion_rejected_total = $landfill_diversion_rejected_total + str_replace(",", "", number_format($myrowsel["landfill_diversion_rejected"], 0));
                                ?>
                            </td>

                            <td bgcolor="#E4E4E4" class="info_s" align="left" valign="top">
                                <?php echo date("m/d/Y", strtotime($myrowsel["due_date"])) . " CT"; ?>
                            </td>

                            <td class="display_table" align="left" valign="top">
                                <?php echo $myrowsel["task_owner"] ?>
                            </td>

                            <td class="display_table" align="left">
                                <?php echo date("m/d/Y H:i:s", strtotime($myrowsel["task_added_on"]))  . " CT"; ?>
                            </td>

                            <td align="left" class="display_table">
                                <textarea cols="8" name="water_init_notes" id="water_init_notes<?php echo $myrowsel["taskid"] ?>" class="form_component"><?php echo $myrowsel["task_notes"] ?></textarea>
                            </td>

                            <td align="left" class="display_table">
                                <textarea cols="8" name="water_init_notes1" id="water_init_notes1<?php echo $myrowsel["taskid"] ?>" class="form_component"><?php echo $myrowsel["task_notes1"] ?></textarea>
                            </td>

                            <td align="left" class="display_table">
                                <?php
                                if ($myrowsel["initiative_step"] != "") {
                                ?>
                                    <input type="button" value="Save" name="todo_edit_water_comp" id="todo_edit_water_comp<?php echo $myrowsel["initiative_step"]; ?>" onclick="todoitem_edit_water_comp(<?php echo $myrowsel["initiative_step"]; ?>, <?php echo $myrowsel["taskid"]; ?>, <?php echo $b2bid; ?>)" class="form_component">
                                <?php
                                } else {
                                    $initiative_step = 0;
                                ?>
                                    <input type="button" value="Save" name="todo_edit_water_comp" id="todo_edit_water_comp<?php echo $myrowsel["taskid"]; ?>" onclick="todoitem_edit_water_comp(<?php echo $initiative_step ?>, <?php echo $myrowsel["taskid"]; ?>, <?php echo $b2bid; ?>)" class="form_component">
                                <?php
                                }
                                ?>
                            </td>
                            <td align="left" class="display_table">
                                <?php
                                if ($myrowsel["initiative_step"] != "") {
                                ?>
                                    <input type="button" value="Delete" name="todo_del_water" id="todo_del_water<?php echo $myrowsel["initiative_step"]; ?>" onclick="todoitem_delete_water(<?php echo $initiative_step ?>, <?php echo $myrowsel["taskid"]; ?>, <?php echo $b2bid; ?>)" class="form_component">
                                <?php
                                } else {
                                ?>
                                    <input type="button" value="Delete" name="todo_edit_water_comp" id="todo_edit_water_comp<?php echo $myrowsel["taskid"]; ?>" onclick="todoitem_delete_water(<?php echo $initiative_step ?>, <?php echo $myrowsel["taskid"]; ?>, <?php echo $b2bid; ?>)" class="form_component">
                                <?php
                                }
                                ?>
                            </td>
                            <td align="center" class="display_table">
                                <?php echo $myrowsel["taskid"] ?>
                            </td>

                            </tr>

                    <?php

                            }
                        }

                    ?>
                    <tr>
                        <td class="display_table" colspan="4">&nbsp;</td>
                        <td class="display_table" align="right"><b>Total:</b></td>
                        <td class="display_table" align="right">
                            <table>
                                <tr>
                                    <td class='display_table'>TOTAL SUM for Annual Savings Opportunity:</td>
                                    <td class='display_table' align='right'>
                                        <b>$<?php echo number_format($annual_saving_opportunity_total, 2); ?></b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='display_table'>TOTAL SUM for Annual Savings Implemented:</td>
                                    <td class='display_table' align='right'>
                                        <b>$<?php echo number_format($annual_saving_implemented_total, 2); ?></b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='display_table'>TOTAL SUM for Annual Savings to Implement:</td>
                                    <td class='display_table' align='right'>
                                        <b>$<?php echo number_format($annual_saving_implement_total, 2); ?></b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='display_table'>TOTAL SUM for Annual Savings Rejected:</td>
                                    <td class='display_table' align='right'>
                                        <b>$<?php echo number_format($annual_saving_rejected_total, 2); ?></b>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <td class="display_table" align="right">
                            <table>
                                <tr>
                                    <td class='display_table'>TOTAL SUM for Potential % Increase in Total Landfill
                                        Diversion:</td>
                                    <td class='display_table' align='right'><b>
                                            <?php
                                            if ($all_row_cnt > 0) {

                                                echo number_format($per_in_landfill_total_all, 2);
                                            } else {
                                                echo "0";
                                            }

                                            ?>
                                            %</b></td>
                                </tr>
                                <tr>
                                    <td class='display_table'>TOTAL SUM for Landfill Diversion Implemented:</td>
                                    <td class='display_table' align='right'>
                                        <b><?php echo number_format($landfill_diversion_implemented_total, 2); ?>%</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='display_table'>TOTAL SUM for Landfill Diversion to Implement:</td>
                                    <td class='display_table' align='right'>
                                        <b><?php echo number_format($landfill_diversion_implement_total, 2); ?>%</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='display_table'>TOTAL SUM for Landfill Diversion Rejected:</td>
                                    <td class='display_table' align='right'>
                                        <b><?php echo number_format($landfill_diversion_rejected_total, 2); ?>%</b>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <td class="display_table" colspan="7">&nbsp;</td>
                    </tr>

                </table>
            </div>

        </div>

    </form>

<?php

} ?>