<?php
require("inc/header_session.php");
require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";
?>
<?php
$new_task_id = '';
$count = 0;
if (!empty($_REQUEST["init_step"])) {
	if ($_REQUEST["init_step"] != "") {
		if ($_REQUEST["editstep"] == "0") {
			$init_step_no = $_REQUEST["init_step"];
			$company_id = $_REQUEST["init_companyID"];
			//
			$sql = "SELECT * FROM water_initiatives where companyid = '" . $company_id . "' and initiative_step='" . $init_step_no . "' order by task_sub_id desc limit 1";
			db();
			$result = db_query($sql);
			if (tep_db_num_rows($result) > 0) {
				while ($myrowsel = array_shift($result)) {
					$taskid = $myrowsel["task_sub_id"];
					$max_char = substr($taskid, -1);
					$new_char = ++$max_char;
					$new_task_id = $init_step_no . $new_char;
					echo $new_task_id . ".";
					echo "<input type='hidden' name='task_sub_id' id='task_sub_id' value='$new_task_id'>";
				}
			} else {
				$new_task_id = $init_step_no . "a";
				echo $new_task_id . ". ";
				echo "<input type='hidden' name='task_sub_id' id='task_sub_id' value='$new_task_id'>";
			}
		}
		//
		if ($_REQUEST["editstep"] == "1") {
			$old_step_id = $_REQUEST["old_step_id"];
			$new_step_id = $_REQUEST["init_step"];
			$company_id = $_REQUEST["init_companyID"];
			$stepid = $_REQUEST["init_step"];
			//
			if ($old_step_id != 0) {

?>
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
							<td class="display_title" valign="top">Initiative<br>Task Owner </td>
						</tr>
						<?php

						$sql = "SELECT * FROM water_initiatives where companyid = " . $company_id . " and initiative_step = '" . $old_step_id . "' group by initiative_step";
						db();
						$result = db_query($sql);
						if (tep_db_num_rows($result) > 0) {
							while ($step_rows = array_shift($result)) {

								$init_sql = "SELECT * FROM water_initiatives where companyid = " . $company_id . "  and initiative_step= '" . $old_step_id . "' order by task_sub_id";
								db();
								$init_result = db_query($init_sql);
								$total_steps = tep_db_num_rows($init_result);
						?>
								<tr id="init_step_row_edit<?php echo $step_rows["initiative_step"]; ?>">
									<td rowspan="<?php echo $total_steps ?>" class="display_table" align="left" valign="top">
										<select name="init_step_edit" id="init_step_edit" class="form_component" style="width: 150px;" onChange="show_step_number_edit(this.value, <?php echo $company_id; ?>, <?php echo $old_step_id; ?>, <?php echo $_REQUEST["taskid"] ?>, 1)">
											<?php
											$int_step_qry = "select * from water_initiative_steps";
											db();
											$int_step_res = db_query($int_step_qry);
											while ($int_step_rows = array_shift($int_step_res)) {
											?>
												<option value="<?php echo $int_step_rows["unique_key"]; ?>" <?php if ($int_step_rows["unique_key"] != "" && $int_step_rows["unique_key"] == $new_step_id) {
																												echo "selected";
																											} else {
																											} ?>>
													<?php echo $int_step_rows["unique_key"] . "-" . $int_step_rows["initiative_step"]; ?></option>
											<?php
											}
											?>
										</select>
									</td>

									<?php
									$count = 0;
									$tskid = "";
									while ($myrowsel = array_shift($init_result)) {
										$date1 = new DateTime($myrowsel["due_date"]);
										$date2 = new DateTime();

										$days = (strtotime($myrowsel["due_date"]) - strtotime(date("Y-m-d"))) / (60 * 60 * 24);
										//
										$count = $count + 1;
										//
										if ($old_step_id != $new_step_id) {
											$sqli = "SELECT * FROM water_initiatives where companyid = '" . $company_id . "' and initiative_step='" . $new_step_id . "' order by task_sub_id desc limit 1";
											db();
											$result_i = db_query($sqli);
											if (tep_db_num_rows($result_i) > 0) {
												while ($myrowseli = array_shift($result_i)) {

													if ($tskid == "") {
														$taskid = $myrowseli["task_sub_id"];
														$max_char = substr($taskid, -1);
														$new_char = ++$max_char;
														$new_task_id = $new_step_id . $new_char;
													}
													if ($tskid != "") {
														if ($tskid == $new_task_id) {
															$max_char = substr($tskid, -1);
															$new_char = ++$max_char;
															$new_task_id = $new_step_id . $new_char;
														}
													}
												}
											} else {
												if ($tskid == "") {
													$new_task_id = $new_step_id . "a";
												}
												if ($tskid != "") {
													if ($tskid == $new_task_id) {
														$max_char = substr($tskid, -1);
														$new_char = ++$max_char;
														$new_task_id = $new_step_id . $new_char;
													}
												}
											}
										} else {
											$new_task_id = $myrowsel["task_sub_id"];
										}
										//
									?>
										<td align="left" class="display_table" valign="top" style="padding: 0px;">
											<table>
												<tr>
													<td class="display_table" valign="top" align="left">
														<div id="init_titleid">
															<?php
															echo $new_task_id . ". ";
															echo "<input type='hidden' name='task_sub_id$count' id='task_sub_id$count' value='$new_task_id'>";
															$tskid = $new_task_id;
															?>

															<?php
															if ($myrowsel["task_sub_id"] == "") {
															} else {
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
														<?php echo "UCB-" . $emp_row["initials"]; ?></option>
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
														<?php echo $compw_row["company"]; ?></option>
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
							<input type="hidden" name="todo_companyID" id="todo_companyID" value="<?php echo $company_id; ?>" />
							<input type="hidden" name="todo_count" id="todo_count" value="<?php echo $count; ?>" />
							<input style="cursor:pointer;" type="button" value="Update Task" name="todo_update_edit" onclick="update_edit_item_water(<?php echo $new_step_id; ?>)">
						</td>
					</tr>
					</table>
				</form>

			<?php
			} //end if(old_step_id!=0)
			else {
			?>
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
							<td class="display_title" valign="top">Initiative<br>Task Owner </td>
						</tr>
						<?php


						$init_sql = "SELECT * FROM water_initiatives where companyid = " . $company_id . "  and taskid = '" . $_REQUEST["taskid"] . "' order by task_sub_id";
						db();
						$init_result = db_query($init_sql);
						$total_steps = tep_db_num_rows($init_result);
						?>
						<tr id="init_step_row_edit<?php echo $_REQUEST["taskid"]; ?>">
							<td rowspan="<?php echo $total_steps ?>" class="display_table" align="left" valign="top">
								<select name="init_step" id="init_step" class="form_component" style="width: 150px;" onChange="show_step_number_edit(this.value, <?php echo $company_id; ?>, <?php echo $old_step_id; ?>, <?php echo $_REQUEST["taskid"] ?>, 1)">
									<?php
									$int_step_qry = "select * from water_initiative_steps";
									db();
									$int_step_res = db_query($int_step_qry);
									while ($int_step_rows = array_shift($int_step_res)) {
									?>
										<option value="<?php echo $int_step_rows["unique_key"]; ?>" <?php if ($int_step_rows["unique_key"] != "" && $int_step_rows["unique_key"] == $new_step_id) {
																										echo "selected";
																									} else {
																									} ?>>
											<?php echo $int_step_rows["unique_key"] . "-" . $int_step_rows["initiative_step"]; ?></option>
									<?php
									}
									?>
								</select>
							</td>

							<?php
							$count = 0;
							$tskid = "";
							while ($myrowsel = array_shift($init_result)) {
								$date1 = new DateTime($myrowsel["due_date"]);
								$date2 = new DateTime();

								$days = (strtotime($myrowsel["due_date"]) - strtotime(date("Y-m-d"))) / (60 * 60 * 24);
								//
								$count = $count + 1;
								//
								if ($old_step_id != $new_step_id) {
									$sqli = "SELECT * FROM water_initiatives where companyid = '" . $company_id . "' and initiative_step='" . $new_step_id . "' order by task_sub_id desc limit 1";
									db();
									$result_i = db_query($sqli);
									if (tep_db_num_rows($result_i) > 0) {
										while ($myrowseli = array_shift($result_i)) {

											if ($tskid == "") {
												$taskid = $myrowseli["task_sub_id"];
												$max_char = substr($taskid, -1);
												$new_char = ++$max_char;
												$new_task_id = $new_step_id . $new_char;
											}
											if ($tskid != "") {
												if ($tskid == $new_task_id) {
													$max_char = substr($tskid, -1);
													$new_char = ++$max_char;
													$new_task_id = $new_step_id . $new_char;
												}
											}
										}
									} else {
										if ($tskid == "") {
											$new_task_id = $new_step_id . "a";
										}
										if ($tskid != "") {
											if ($tskid == $new_task_id) {
												$max_char = substr($tskid, -1);
												$new_char = ++$max_char;
												$new_task_id = $new_step_id . $new_char;
											}
										}
									}
								} else {
									$new_task_id = $myrowsel["task_sub_id"];
								}
								//
							?>
								<td align="left" class="display_table" valign="top" style="padding: 0px;">
									<table>
										<tr>
											<td class="display_table" valign="top" align="left">
												<div id="init_titleid">
													<?php
													echo $new_task_id . ". ";
													echo "<input type='hidden' name='task_sub_id$count' id='task_sub_id$count' value='$new_task_id'>";
													$tskid = $new_task_id;
													?>

													<?php
													if ($myrowsel["task_sub_id"] == "") {
													} else {
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
												<?php echo "UCB-" . $emp_row["initials"]; ?></option>
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
												<?php echo $compw_row["company"]; ?></option>
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

					?>
					<tr>
						<td>
							<input type="hidden" name="todo_companyID" id="todo_companyID" value="<?php echo $company_id; ?>" />
							<input type="hidden" name="todo_count" id="todo_count" value="<?php echo $count; ?>" />
							<input style="cursor:pointer;" type="button" value="Update Task" name="todo_update_edit" onclick="update_edit_item_water(<?php echo $new_step_id; ?>)">
						</td>
					</tr>
					</table>
				</form>
<?php
			}
		}
	}
}
?>