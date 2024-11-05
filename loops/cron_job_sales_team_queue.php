<?php  
	set_time_limit(0);	
	ini_set('memory_limit', '-1');

	require ("../mainfunctions/database.php");
	require ("../mainfunctions/general-functions.php");

	db_b2b();

	db_query("Delete from sales_team_queue_2");
    
	echo "Processing - Condition 1: Any sales accounts not contacted in > 6 months, and next step is > 15 days old.' Be sure to exclude sales records which have status of inactive, unqualified, or TRASH. Also exclude any records which have 'Hide from call lists' flag active. <br><br>";
	
	$sql = "Select ID, last_contact_date, loopid ";
	$sql .= " from companyInfo WHERE on_hold = 0 and sales_purc_team_ignore_com = 0 ";
	$sql .= " and status not in (97, 98, 99, 24, 43, 106, 33, 52, 31) ";
	$sql .= " and haveNeed = 'Need Boxes' and active = 1 and last_contact_date <= '" . date('Y-m-d', strtotime ( '-181 days' )) . "' and (next_date <= '" . date('Y-m-d', strtotime ( '-15 days' )) . "' or next_date is null) order by ID";
	echo $sql . "<br>";
	
	$result = db_query($sql);
	while ($myrowsel = array_shift($result)) {
		$tmp_msg_dt = date('Y-m-d', strtotime($myrowsel['last_contact_date']));
		//assign_to_emp, assign_to_emp_on, action_taken, action_taken_on
		$rec_found = "no";
		$sql_chk = "Select company_id from sales_team_queue_2 where company_id = " . $myrowsel["ID"];
		$result_chk = db_query($sql_chk);
		while ($myrowsel_chk = array_shift($result_chk)) {
			$rec_found = "yes";
		}
		
		if ($rec_found == "no") {
		  
			$qry_ins = "Insert into sales_team_queue_2 (run_date, company_id, report_criteria ) ";
			$qry_ins .= " select '" . date("Y-m-d H:i:s") . "', '" . $myrowsel["ID"] . "', 1";
			
			db_query($qry_ins);
		}
	}	

	echo "Processing - Assigning the records to Employee <br><br>";

	db();
	$MGArray = null; $empcnt = 0;
	$sql = "Select id from loop_employees ";
	$sql .= " where status = 'Active' and call_list_member = 1 order by id";
	$result = db_query($sql);
	while ($myrowsel = array_shift($result)) {
		$MGArray[] = array('empid' => $myrowsel["id"]);
		$empcnt = $empcnt + 1;
	}

	db_b2b();

	
	$sql_main = "Select * from sales_team_queue_2 ";
	$sql_main .= " where assign_to_emp = '' order by unqid";
	$result_main = db_query($sql_main);
	while ($myrowsel_main = array_shift($result_main)) {
		foreach ($MGArray as $MGArraytmp) {
			$sql = "Select * from sales_team_queue_2 ";
			$sql .= " where assign_to_emp = '' order by unqid limit " . $empcnt;
			$result = db_query($sql);
			while ($myrowsel = array_shift($result)) {
				$qry_ins = "Update sales_team_queue_2 set assign_to_emp = " . $MGArraytmp['empid'] . ", assign_to_emp_on = '" . date("Y-m-d H:i:s") . "' ";
				$qry_ins .= " where unqid = '" . $myrowsel["unqid"] . "'";
					
				db_query($qry_ins);
				break;
			}	
		}
	}	
?>