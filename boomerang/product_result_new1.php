<?php
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

/*ini_set("display_errors", "1");
error_reporting(E_ALL);
*/
session_start();
$box_vents = $box_vents ?? "";
$type = $type ?? "";
$box_grade = $box_grade ?? "";
$box_shape = $box_shape ?? "";
$box_uniformity = $box_uniformity ?? "";
function calculate_miles_away_from_session_data(string $enter_zipcode, int $b2b_id)
{
	db_b2b();
	$dt_view_qry = db_query("SELECT location_zip, location_zip_latitude,location_zip_longitude from inventory WHERE id = " . $b2b_id . " LIMIT 1");
	$dt_view_res = array_shift($dt_view_qry);
	$locLat = $dt_view_res["location_zip_latitude"];
	$locLong = $dt_view_res["location_zip_longitude"];
	$shipLat = "";
	$shipLong = "";
	$mile = "";
	$miles_from = "";
	if (!empty($dt_view_res["location_zip"]) && !empty($enter_zipcode)) {
		$zipShipStr = "Select latitude, longitude from ZipCodes WHERE zip = '" . intval($enter_zipcode) . "'";
		db_b2b();
		$zip_view_res = db_query($zipShipStr);
		while ($ziprec = array_shift($zip_view_res)) {
			$shipLat = $ziprec["latitude"];
			$shipLong = $ziprec["longitude"];
		}

		$distLat = deg2rad($shipLat - $locLat);
		$distLong = deg2rad($shipLong - $locLong);

		$distA = sin($distLat / 2) * sin($distLat / 2) + cos(deg2rad($shipLat)) * cos(deg2rad($locLat)) * sin($distLong / 2) * sin($distLong / 2);
		$distC = 2 * atan2(sqrt($distA), sqrt(1 - $distA));
		$miles_from = (int) (6371 * $distC * 0.621371192);
		//$miles_from = (int) (6371 * $distC * .621371192);
		//echo "miles_from = " . $miles_from . "<br>";

		if ($miles_from <= 250) {
			$miles_away_color = "green";
		}
		if (($miles_from <= 550) && ($miles_from > 250)) {
			$miles_away_color = "#FF9933";
		}
		if (($miles_from > 550)) {
			$miles_away_color = "red";
		}
	}

	if ($enter_zipcode == "") {
		$miles = "<font color='red'>Enter Zipcode</font>";
	} else {
		$miles = "<font color='$miles_away_color'>" . $miles_from . " mi away</font>";
	}

	return array('miles' => $miles, 'miles_from' => $miles_from);
}


if ($_REQUEST["user_id"] == "") {
	$user_id = 0;
} else {
	$user_id = $_REQUEST["user_id"];
}
if (isset($_REQUEST['change_fav_status']) && $_REQUEST['change_fav_status'] == 1) {
	$company_id = $_REQUEST['companyID'];
	$b2b_id = $_REQUEST['b2b_id'];
	$fav_status = $_REQUEST['fav_status'] == 0 ? 1 : 0;
	$res = 2;
	db();
	$check_if_data_exist_for_id = db_query("SELECT id, fav_status FROM b2b_inventory_gaylords_favorite WHERE company_id = $company_id AND b2b_id = $b2b_id and user_id= $user_id");
	if (tep_db_num_rows($check_if_data_exist_for_id) > 0) {
		$fav_id = 0;
		$fav_status_db = "";
		while ($row_data = array_shift($check_if_data_exist_for_id)) {
			$fav_id = $row_data['id'];
			$fav_status_db = $row_data['fav_status'];
		}
		$fav_status = $fav_status_db == 1 ? 0 : 1;

		//echo "fav_status_db = " . $fav_status_db . "|". $fav_status . "<br>";

		$result = db_query("UPDATE b2b_inventory_gaylords_favorite SET fav_status = $fav_status WHERE id = $fav_id");
		//echo "UPDATE b2b_inventory_gaylords_favorite SET fav_status = $fav_status WHERE id = $fav_id" . "<br>";

		//$fav_status = $fav_status_db == 1 ? 0 : 1;

		$res = $fav_status;
	} else {
		$result = db_query("INSERT INTO b2b_inventory_gaylords_favorite (company_id, b2b_id, fav_status, user_id,created_on) VALUES ($company_id, $b2b_id, $fav_status, $user_id,'" . date("Y-m-d h:i:s") . "')");
		$res = $fav_status;
	}
	echo $res;
} else if (isset($_REQUEST['applied_changes']) && $_REQUEST['applied_changes'] != 'refresh') {
	$product_array = $_SESSION[$_REQUEST['box_type'] . '_product_data']['data'];
	//echo "Recalculate Miles Away based on address!!";
	if ($_REQUEST['applied_changes'] == 'address_changed') {
		if (isset($_REQUEST['show_urgent_box']) && $_REQUEST['show_urgent_box'] == 1) {
			$products_array = $_SESSION[$_REQUEST['box_type'] . '_urgent_boxes']['data'];
		} else {
			$products_array = $_SESSION[$_REQUEST['box_type'] . '_product_data']['data'];
		}
		$new_product_array = array();
			foreach ($products_array as $key => $product_data) {
				$result = calculate_miles_away_from_session_data($_REQUEST['txtzipcode'], $product_data['b2b_id']);
				$product_data['miles'] =  $result['miles'];
				$product_data['miles_away'] =  $result['miles_from'];
				$product_data['distance'] =  $result['miles'];
				$product_data['distance_sort'] =  $result['miles_from'];
				$new_product_array[] = $product_data;
			}
			$final_res = array(
				'data' => $new_product_array,
				'total_items' => count($new_product_array),
			);
			if (isset($_REQUEST['show_urgent_box']) && $_REQUEST['show_urgent_box'] == 1) {
				$_SESSION[$_REQUEST['box_type'] . '_urgent_boxes'] = $final_res;
			}else{
				$_SESSION[$_REQUEST['box_type'] . '_product_data'] = $final_res;
			}
			//print_r($products_array);
	}
	if (isset($_REQUEST['applied_changes']) && $_REQUEST['applied_changes'] == "clear_filter") {
		$products_array = $_SESSION[$_REQUEST['box_type'] . '_product_data']['data'];
	} else {
		if (isset($_REQUEST['show_urgent_box']) && $_REQUEST['show_urgent_box'] == 1) {
			$products_array = $_SESSION[$_REQUEST['box_type'] . '_urgent_boxes']['data'];
			//echo count($products_array);
			$final_res = array(
				// 'no_of_pages'=>$no_of_pages,
				'data' => $products_array,
				'total_items' => count($products_array),
			);

			echo json_encode($final_res);
			//echo "Yes at 134";
			exit;
		} else {
			$products_array = $_SESSION[$_REQUEST['box_type'] . '_product_data']['data'];
		}
		$customer_pickup_allowed_filter = isset($_REQUEST['customer_pickup_allowed']) && $_REQUEST['customer_pickup_allowed'] == 1 ? 1 : 0;
		if ($customer_pickup_allowed_filter == 1) {
			$products_array = array_filter($products_array, function ($product) {
				return isset($product['customer_pickup']) && $product['customer_pickup'] == "Yes";
			});
			$products_array = array_values($products_array);
		}
		$ltl_filter = isset($_REQUEST['ltl_allowed']) && $_REQUEST['ltl_allowed'] == 1 ? 1 : 0;
		if ($ltl_filter == 1) {
			$products_array = array_filter($products_array, function ($product) {
				return isset($product['ltl']) && $product['ltl'] == "Yes";
			});
			$products_array = array_values($products_array);
		}

		/*$full_truck_load = isset($_REQUEST['include_FTL_Rdy_Now_Only']) && $_REQUEST['include_FTL_Rdy_Now_Only'] == 1 ? 1 : 0;
		if ($full_truck_load == 1) {
			$products_array = array_filter($products_array, function ($product) {
				return isset($product['include_FTL_Rdy_Now_Only']) && $product['include_FTL_Rdy_Now_Only'] == 1;
			});
			$products_array = array_values($products_array);
		}
		*/

		if (isset($_REQUEST['include_FTL_Rdy_Now_Only']) && $_REQUEST['include_FTL_Rdy_Now_Only'] == 1) {
			$products_array = array_filter($products_array, function ($product) {
				return ((floatval($product['qtynumbervalue']) >= floatval($product['ftl_qty'])) && (floatval($product['ftl_qty']) > 0));
			});
			$products_array = array_values($products_array);
		}
		if (isset($_REQUEST["min_price_each"]) && isset($_REQUEST["max_price_each"]) && ($_REQUEST["min_price_each"] != 0.00 || $_REQUEST["max_price_each"] != 99.99)) {
			$products_array = array_filter($products_array, function ($product) {
				$price = floatval(str_replace("$", "", $product['price']));
				//echo $price . " "; // Uncomment for debugging if needed
				return ($price >= floatval($_REQUEST["min_price_each"]) && $price <= floatval($_REQUEST["max_price_each"]));
			});
			$products_array = array_values($products_array);
		}

		if (isset($_REQUEST["min_height"]) || isset($_REQUEST['max_height'])) {
			if ($_REQUEST["min_height"] != 0 || $_REQUEST["max_height"] != 99.99) {
				//echo "min_height = " . $_REQUEST["min_height"] . " max_height = " . $_REQUEST["max_height"];
				$products_array = array_filter($products_array, function ($product) {
					return (isset($product['depth']) && floatval($product['depth']) >= floatval($_REQUEST["min_height"]) && floatval($product['depth']) <= floatval($_REQUEST["max_height"]));
				});
			}
		}
		if (isset($_REQUEST["min_length"]) || isset($_REQUEST['max_length'])) {
			if ($_REQUEST["min_length"] != 0 || $_REQUEST["max_length"] != 99.99) {
				//echo "min_length = " . $_REQUEST["min_length"] . " max_length = " . $_REQUEST["max_length"];
				$products_array = array_filter($products_array, function ($product) {
					return (isset($product['length']) && floatval($product['length']) >= floatval($_REQUEST["min_length"]) && floatval($product['length']) <= floatval($_REQUEST["max_length"]));
				});
			}
		}

		if (isset($_REQUEST["min_width"]) || isset($_REQUEST['max_width'])) {
			if ($_REQUEST["min_width"] != 0 || $_REQUEST["max_width"] != 99.99) {
				//echo "min_width = " . $_REQUEST["min_width"] . " max_width = " . $_REQUEST["max_width"];
				$products_array = array_filter($products_array, function ($product) {
					return (isset($product['width']) && floatval($product['width']) >= floatval($_REQUEST["min_width"]) && floatval($product['width']) <= floatval($_REQUEST["max_width"]));
				});
			}
		}

		if (isset($_REQUEST["min_cubic_footage"]) || isset($_REQUEST['max_cubic_footage'])) {
			if ($_REQUEST["min_cubic_footage"] != 0 || $_REQUEST["max_cubic_footage"] != 99.99) {
				//echo "min_cubic_footage = " . $_REQUEST["min_cubic_footage"] . " max_cubic_footage = " . $_REQUEST["max_cubic_footage"];
				$products_array = array_filter($products_array, function ($product) {
					return (isset($product['cubicfootage']) && floatval($product['cubicfootage']) >= floatval($_REQUEST["min_cubic_footage"]) && floatval($product['cubicfootage']) <= floatval($_REQUEST["max_cubic_footage"]));
				});
			}
		}

		if (isset($_REQUEST["all_uniformity_data"]) && $_REQUEST["all_uniformity_data"] != "") {
			$box_uniformity_val = $_REQUEST["all_uniformity_data"];
			if (count($box_uniformity_val) != 0) {
				$products_array = array_filter($products_array, function ($product) use ($box_uniformity_val) {
					return in_array($product['uniform_mixed_load_txt'], $box_uniformity_val);
				});
			}
			$products_array = array_values($products_array);
		}

		if (isset($_REQUEST["all_shape_data"]) && $_REQUEST["all_shape_data"] != "") {
			$shapes_val = $_REQUEST["all_shape_data"];
			if (count($shapes_val) != 0) {
				$products_array = array_filter($products_array, function ($product) use ($shapes_val) {
					return in_array($product['product_shape'], $shapes_val);
				});
			}
			$products_array = array_values($products_array);
		}
		if (isset($_REQUEST["all_thickness_data"]) && $_REQUEST["all_thickness_data"] != "") {

			$thickness_val = $_REQUEST["all_thickness_data"];
			if (count($thickness_val) > 0) {
				$products_array = array_filter($products_array, function ($product) use ($thickness_val) {
					return in_array($product['product_bwall'], $thickness_val);
				});
			}
			$products_array = array_values($products_array);
		}
		if (isset($_REQUEST["all_top_data"]) && $_REQUEST["all_top_data"] != "") {
			$top_val = $_REQUEST["all_top_data"];
			if (count($top_val) > 0) {
				$products_array = array_filter($products_array, function ($product) use ($top_val) {
					foreach ($top_val as $val) {
						$key = 'product_top' . $val;
						if (isset($product[$key]) && $product[$key] == 1) {
							return true;
						}
					}
					return false;
				});

				//print_r($filtered_products);
			}
			$products_array = array_values($products_array);
		}
		if (isset($_REQUEST["all_bottom_data"]) && $_REQUEST["all_bottom_data"] != "") {
			$bottom_val = $_REQUEST["all_bottom_data"];
			if (count($bottom_val) > 0) {
				$products_array = array_filter($products_array, function ($product) use ($bottom_val) {
					foreach ($bottom_val as $val) {
						$key = 'product_bottom' . $val;
						if (isset($product[$key]) && $product[$key] == 1) {
							return true;
						}
					}
					return false;
				});

				//print_r($filtered_products);
			}
			$products_array = array_values($products_array);
		}

		if (isset($_REQUEST["all_vents_data"]) && $_REQUEST["all_vents_data"] != "") {

			$vents_val = $_REQUEST["all_vents_data"];
			if (count($vents_val) > 0) {
				$products_array = array_filter($products_array, function ($product) use ($vents_val) {
					return in_array($product['product_vents'], $vents_val);
				});
			}
			$products_array = array_values($products_array);
		}

		if (isset($_REQUEST["all_grade_data"]) && $_REQUEST["all_grade_data"] != "") {
			$grade_val = $_REQUEST["all_grade_data"];
			if (count($grade_val) > 0) {
				$products_array = array_filter($products_array, function ($product) use ($grade_val) {
					return in_array($product['product_grade'], $grade_val);
				});
			}
			$products_array = array_values($products_array);
		}
		if (isset($_REQUEST["all_treatment_data"]) && $_REQUEST["all_treatment_data"] != "") {
			$treatment_val = $_REQUEST["all_treatment_data"];
			if (count($treatment_val) > 0) {
				$products_array = array_filter($products_array, function ($product) use ($treatment_val) {
					return in_array($product['product_heat_treated'], $treatment_val);
				});
			}
			$products_array = array_values($products_array);
		}
		if (isset($_REQUEST["all_entry_data"]) && $_REQUEST["all_entry_data"] != "") {
			$entry_val = $_REQUEST["all_entry_data"];
			if (count($entry_val) > 0) {
				$products_array = array_filter($products_array, function ($product) use ($entry_val) {
					return in_array($product['product_entry'], $entry_val);
				});
			}
			$products_array = array_values($products_array);
		}
		if (isset($_REQUEST["all_material_data"]) && $_REQUEST["all_material_data"] != "") {
			$material_val = $_REQUEST["all_material_data"];
			if (count($material_val) > 0) {
				$products_array = array_filter($products_array, function ($product) use ($material_val) {
					return in_array($product['product_material'], $material_val);
				});
			}
			$products_array = array_values($products_array);
		}
		if (isset($_REQUEST["all_structure_data"]) && $_REQUEST["all_structure_data"] != "") {
			$structure_val = $_REQUEST["all_structure_data"];
			if (count($structure_val) > 0) {
				$products_array = array_filter($products_array, function ($product) use ($structure_val) {
					return in_array($product['product_structure'], $structure_val);
				});
			}
			$products_array = array_values($products_array);
		}
		if (isset($_REQUEST["all_type_sh_data"]) && $_REQUEST["all_type_sh_data"] != "") {
			$type_sh_val = $_REQUEST["all_type_sh_data"];
			if (count($type_sh_val) > 0) {
				$products_array = array_filter($products_array, function ($product) use ($type_sh_val) {
					return in_array($product['product_type_sh'], $type_sh_val);
				});
			}
			$products_array = array_values($products_array);
		}

		if (isset($_REQUEST["all_printing_data"]) && $_REQUEST["all_printing_data"] != "") {

			$printing_val = $_REQUEST["all_printing_data"];
			if (count($printing_val) > 0) {

				$products_array = array_filter($products_array, function ($product) use ($printing_val) {
					return in_array($product['product_printing'], $printing_val);
				});
			}
			$products_array = array_values($products_array);
		}
		if (isset($_REQUEST["warehouse"]) && $_REQUEST["warehouse"] != "all") {

			$warehouse_id_val = $_REQUEST["warehouse"];
			$products_array = array_filter($products_array, function ($product) use ($warehouse_id_val) {
				return $product['box_warehouse_id'] == $warehouse_id_val;
			});
			$products_array = array_values($products_array);
		}

		if (isset($_REQUEST["ect_burst"]) && $_REQUEST["ect_burst"] != "all") {

			$ect_burst_val = $_REQUEST["ect_burst"];
			$products_array = array_filter($products_array, function ($product) use ($ect_burst_val) {
				return $product['product_ect_burst'] == $ect_burst_val;
			});
			$products_array = array_values($products_array);
		}
	}

	// Define the sorting options and their corresponding parameters
	$sortOptions = [
		'low-high' => ['column' => 'minfob', 'direction' => SORT_ASC],
		'high-low' => ['column' => 'minfob', 'direction' => SORT_DESC],
		'nearest' => ['column' => 'distance_sort', 'direction' => SORT_ASC],
		'furthest' => ['column' => 'distance_sort', 'direction' => SORT_DESC],
		'freq-most-least' => ['column' => 'frequency_sort', 'direction' => SORT_DESC, 'type' => SORT_NUMERIC],
		'freq-least-most' => ['column' => 'frequency_sort', 'direction' => SORT_ASC, 'type' => SORT_NUMERIC],
		'qty-most-least' => ['column' => 'qtynumbervalueorg', 'direction' => SORT_DESC],
		'qty-least-most' => ['column' => 'qtynumbervalueorg', 'direction' => SORT_ASC],
		'leadtime-soonest-latest' => ['column' => 'lead_time_days', 'direction' => SORT_ASC, 'type' => SORT_NUMERIC],
		'leadtime-latest-soonest' => ['column' => 'lead_time_days', 'direction' => SORT_DESC, 'type' => SORT_NUMERIC],
		'length-short-long' => ['column' => 'length', 'direction' => SORT_ASC, 'type' => SORT_NUMERIC],
		'length-long-short' => ['column' => 'length', 'direction' => SORT_DESC, 'type' => SORT_NUMERIC],
		'width-thin-long' => ['column' => 'width', 'direction' => SORT_ASC, 'type' => SORT_NUMERIC],
		'width-long-thin' => ['column' => 'width', 'direction' => SORT_DESC, 'type' => SORT_NUMERIC],
		'height-short-tall' => ['column' => 'depth', 'direction' => SORT_ASC, 'type' => SORT_NUMERIC],
		'height-tall-short' => ['column' => 'depth', 'direction' => SORT_DESC, 'type' => SORT_NUMERIC],
		'cu-small-big' => ['column' => 'cubicfootage', 'direction' => SORT_ASC, 'type' => SORT_NUMERIC],
		'cu-big-small' => ['column' => 'cubicfootage', 'direction' => SORT_DESC, 'type' => SORT_NUMERIC],
	];

	// Check if a valid sort option is provided
	//print_r($products_array);
	if (isset($_REQUEST['sort_by']) && $_REQUEST['sort_by'] != "" && isset($sortOptions[$_REQUEST['sort_by']])) {
		$sortOption = $sortOptions[$_REQUEST['sort_by']];
		$column = $sortOption['column'];
		$direction = $sortOption['direction'];
		$type = isset($sortOption['type']) ? $sortOption['type'] : SORT_REGULAR;

		// Extract the column values and sort the array
		$key_values = array_column($products_array, $column);
		array_multisort($key_values, $direction, $type, $products_array);
	}
	$final_res = array(
		// 'no_of_pages'=>$no_of_pages,
		'data' => $products_array,
		'total_items' => count($products_array),
	);
	echo json_encode($final_res);
} else {
	if (isset($_REQUEST['show_urgent_box']) && $_REQUEST['show_urgent_box'] == 1) {
		if (isset($_SESSION[$_REQUEST['box_type'] . '_urgent_boxes'])) {
			unset($_SESSION[$_REQUEST['box_type'] . '_urgent_boxes']);
		}
		if (isset($_SESSION[$_REQUEST['box_type'] . '_product_data'])) {
			unset($_SESSION[$_REQUEST['box_type'] . '_product_data']);
		}
	}
	$enter_zipcode = "";
	$final_filter_str = "";
	$loop_boxes_final_filter_str = "";
	if (isset($_REQUEST["all_type_data"]) && $_REQUEST["all_type_data"] != "") {
		$type_val = $_REQUEST["all_type_data"];
		if (in_array('1', $type_val)) {
			$type = " AND (inventory.box_sub_type = 1";
		}
		if (in_array('2', $type_val)) {
			if ($type == "") {
				$type = " AND (inventory.box_sub_type = 4";
			} else {
				$type .= " OR inventory.box_sub_type = 4";
			}
		}
		if (in_array('3', $type_val)) {
			if ($type == "") {
				$type = " AND (inventory.box_sub_type = 5";
			} else {
				$type .= " OR inventory.box_sub_type = 5";
			}
		}
		if (in_array('4', $type_val)) {
			if ($type == "") {
				$type = " AND (inventory.box_sub_type = 6";
			} else {
				$type .= " OR inventory.box_sub_type = 6";
			}
		}
		$final_filter_str .= $type . ")";
	}

	if (isset($_REQUEST["all_uniformity_data"]) && $_REQUEST["all_uniformity_data"] != "") {
		$filter_cnt = 0;
		$box_uniformity_val = $_REQUEST["all_uniformity_data"];
		if (in_array('1', $box_uniformity_val)) {
			$box_uniformity = " AND (inventory.uniform_mixed_load = 'Uniform'";
			$filter_cnt = 1;
		}
		if (in_array('2', $box_uniformity_val)) {
			if ($filter_cnt == 1) {
				$box_uniformity .= " OR inventory.uniform_mixed_load = 'Mixed'";
			} else {
				$box_uniformity .= " AND ( inventory.uniform_mixed_load = 'Mixed'";
			}
		}
		$final_filter_str .= $box_uniformity . ")";
	}
	if (isset($_REQUEST["all_shape_data"]) && $_REQUEST["all_shape_data"] != "") {
		$box_shape_val = $_REQUEST["all_shape_data"];
		if (in_array('1', $box_shape_val)) {
			$box_shape = " AND (inventory.shape_rect = 1";
		}
		if (in_array('2', $box_shape_val)) {
			if ($box_shape == "") {
				$box_shape = " AND (inventory.shape_oct = 1";
			} else {
				$box_shape .= " OR inventory.shape_oct = 1";
			}
		}
		$final_filter_str .= $box_shape . ")";
	}

	$sql_wall = "";
	if (isset($_REQUEST["all_thickness_data"]) && $_REQUEST["all_thickness_data"] != "") {
		$wall_val = $_REQUEST["all_thickness_data"];
		for ($i = 0; $i < count($wall_val); $i++) {
			$wall = $wall_val[$i];

			if ($sql_wall == "") {
				if ($wall == 1) {
					if ($_REQUEST['box_type'] == "Shipping") {
						$sql_wall = " AND ((inventory.bwall < 2) ";
					} else {
						$sql_wall = " AND ((inventory.bwall >= 1 and inventory.bwall <= 2) ";
					}
				} else if ($wall == 2) {
					if ($_REQUEST['box_type'] == "Shipping") {
						$sql_wall = " AND ((inventory.bwall > 2) ";
					} else {
						$sql_wall = " AND ((inventory.bwall = 3) ";
					}
				} else if ($wall == 3) {
					$sql_wall = " AND ((inventory.bwall = 4) ";
				} else if ($wall == 4) {
					$sql_wall = " AND ((inventory.bwall >= 5) ";
				}
				if (count($wall_val) == 1) {
					$sql_wall = $sql_wall . ") ";
				}
			} else {
				/*if ($wall == 1) {
					$sql_wall .= " OR (inventory.bwall >= 1 and inventory.bwall <= 2) ";
				} else if ($wall == 2) {
					$sql_wall .= " OR (inventory.bwall >= 3 and inventory.bwall <= 4) ";
				} else if ($wall == 3) {
					$sql_wall .= " OR (inventory.bwall >= 5) ";
				}
					*/
				if ($wall == 1) {
					if ($_REQUEST['box_type'] == "Shipping") {
						$sql_wall .= " OR (inventory.bwall <2) ";
					} else {
						$sql_wall .= " OR (inventory.bwall >= 1 and inventory.bwall <= 2) ";
					}
				} else if ($wall == 2) {
					if ($_REQUEST['box_type'] == "Shipping") {
						$sql_wall .= " OR (inventory.bwall > 2) ";
					} else {
						$sql_wall .= " OR (inventory.bwall = 3) ";
					}
				} else if ($wall == 3) {
					$sql_wall .= " OR (inventory.bwall = 4)) ";
				} else if ($wall == 4) {
					$sql_wall .= " OR (inventory.bwall >= 5) ";
				}

				//$sql_wall = $sql_wall . " OR inventory.bwall = ". $wall;
			}
		}
		if ($sql_wall != "" && count($wall_val) > 1) {
			$sql_wall = $sql_wall . ") ";
		}
		$final_filter_str .= $sql_wall;
	}

	if (isset($_REQUEST["all_top_data"]) && $_REQUEST["all_top_data"] != "") {
		$top_config = "";
		$top_config_val = $_REQUEST["all_top_data"];
		$top_config_val_3 = "";
		$top_config_val_4 = "";
		if (in_array('3', $top_config_val)) {
			$top_config_val_3 = "yes";
		}
		if (in_array('4', $top_config_val)) {
			$top_config_val_4 = "yes";
		}

		if (in_array('1', $top_config_val)) {
			if ($top_config_val_3 == "yes" || $top_config_val_4 == "yes") {
				$top_config = " AND (inventory.top_nolid = 1";
			} else {
				$top_config = " AND inventory.top_nolid = 1";
			}
		}

		if (in_array('2', $top_config_val)) {
			if ($top_config == "") {
				if ($top_config_val_3 == "yes" || $top_config_val_4 == "yes") {
					$top_config = " AND (inventory.top_remove = 1";
				} else {
					$top_config = " AND inventory.top_remove = 1";
				}
			}
		}

		if (in_array('3', $top_config_val)) {
			if ($top_config == "") {
				if (in_array('4', $top_config_val)) {
					$top_config = " AND ( inventory.top_partial = 1";
				} else {
					$top_config = " AND inventory.top_partial = 1";
				}
			} else {
				if (in_array('4', $top_config_val)) {
					$top_config = $top_config . " OR inventory.top_partial = 1";
				} else {
					$top_config = $top_config . " OR inventory.top_partial = 1) ";
				}
			}
		}

		if (in_array('4', $top_config_val)) {
			if ($top_config == "") {
				$top_config = " AND inventory.top_full = 1";
			} else {
				$top_config = $top_config . " OR inventory.top_full = 1) ";
			}
		}
		$final_filter_str .= $top_config;
	}

	if (isset($_REQUEST["all_bottom_data"]) && $_REQUEST["all_bottom_data"] != "") {
		$bottom_config = "";
		$bottom_config_val = $_REQUEST["all_bottom_data"];

		if (in_array('1', $bottom_config_val)) {
			if (in_array('2', $bottom_config_val) || in_array('3', $bottom_config_val) || in_array('4', $bottom_config_val) || in_array('5', $bottom_config_val) || in_array('6', $bottom_config_val)) {
				$bottom_config = " AND ( inventory.bottom_no = 1";
			} else {
				$bottom_config = " AND inventory.bottom_no = 1";
			}
		}

		if (in_array('2', $bottom_config_val)) {
			if ($bottom_config == "") {
				if (in_array('3', $bottom_config_val) || in_array('4', $bottom_config_val) || in_array('5', $bottom_config_val) || in_array('6', $bottom_config_val)) {
					$bottom_config = " AND (inventory.bottom_partial = 1";
				} else {
					$bottom_config = " AND inventory.bottom_partial = 1";
				}
			} else {
				if (in_array('3', $bottom_config_val) || in_array('4', $bottom_config_val) || in_array('5', $bottom_config_val) || in_array('6', $bottom_config_val)) {
					$bottom_config = $bottom_config . " OR inventory.bottom_partial = 1 ";
				} else {
					$bottom_config = $bottom_config . " OR inventory.bottom_partial = 1) ";
				}
			}
		}

		if (in_array('3', $bottom_config_val)) {
			if ($bottom_config == "") {
				if (in_array('4', $bottom_config_val) || in_array('5', $bottom_config_val) || in_array('6', $bottom_config_val)) {
					$bottom_config = " AND (inventory.bottom_tray = 1";
				} else {
					$bottom_config = " AND inventory.bottom_tray = 1";
				}
			} else {
				if (in_array('4', $bottom_config_val) || in_array('5', $bottom_config_val) || in_array('6', $bottom_config_val)) {
					$bottom_config = $bottom_config . " OR inventory.bottom_tray = 1";
				} else {
					$bottom_config = $bottom_config . " OR inventory.bottom_tray = 1) ";
				}
			}
		}

		if (in_array('4', $bottom_config_val)) {
			if ($bottom_config == "") {
				if (in_array('5', $bottom_config_val) || in_array('6', $bottom_config_val)) {
					$bottom_config = " AND (inventory.bottom_fullflap = 1";
				} else {
					$bottom_config = " AND inventory.bottom_fullflap = 1";
				}
			} else {
				if (in_array('5', $bottom_config_val) || in_array('6', $bottom_config_val)) {
					$bottom_config = $bottom_config . " OR inventory.bottom_fullflap = 1";
				} else {
					$bottom_config = $bottom_config . " OR inventory.bottom_fullflap = 1) ";
				}
			}
		}
		if (in_array('5', $bottom_config_val)) {
			if ($bottom_config == "") {
				if (in_array('6', $bottom_config_val)) {
					$bottom_config = " AND (inventory.bottom_partialsheet = 1";
				} else {
					$bottom_config = " AND inventory.bottom_partialsheet = 1";
				}
			} else {
				if (in_array('6', $bottom_config_val)) {
					$bottom_config = $bottom_config . " OR inventory.bottom_partialsheet = 1 ";
				} else {
					$bottom_config = $bottom_config . " OR inventory.bottom_partialsheet = 1) ";
				}
			}
		}

		if (in_array('6', $bottom_config_val)) {
			if ($bottom_config == "") {
				$bottom_config = " AND inventory.bottom_flat = 1";
			} else {
				$bottom_config = $bottom_config . " OR inventory.bottom_flat = 1)";
			}
		}
		$final_filter_str .= $bottom_config;
	}
	$vents_filter = [""];
	if (isset($_REQUEST["all_vents_data"]) && $_REQUEST["all_vents_data"] != "") {
		$box_vents_val = $_REQUEST["all_vents_data"];
		if (in_array('1', $box_vents_val)) {

			$box_vents = " AND (inventory.vents_yes = 1";
		}
		if (in_array('2', $box_vents_val)) {
			if ($box_vents == "") {
				$box_vents = " AND (inventory.vents_no = 1";
			} else {
				$box_vents .= " OR inventory.vents_no = 1";
			}
		}
		$final_filter_str .= $box_vents . ")";
	}

	if (isset($_REQUEST["all_grade_data"]) && $_REQUEST["all_grade_data"] != "") {
		$box_grade_val = $_REQUEST["all_grade_data"];
		if (in_array('1', $box_grade_val)) {
			$box_grade = " AND (inventory.grade = 'A'";
		}
		if (in_array('2', $box_grade_val)) {
			if ($box_grade == "") {
				$box_grade = " AND (inventory.grade = 'B'";
			} else {
				$box_grade .= " OR inventory.grade = 'B'";
			}
		}
		if (in_array('3', $box_grade_val)) {
			if ($box_grade == "") {
				$box_grade = " AND (inventory.grade = 'C'";
			} else {
				$box_grade .= " OR inventory.grade = 'C'";
			}
		}
		$final_filter_str .= $box_grade . ")";
	}

	if (isset($_REQUEST["all_material_data"]) && $_REQUEST["all_material_data"] != "") {
		$box_material_val = $_REQUEST["all_material_data"];
		if (in_array('1', $box_material_val)) {
			$box_material = " AND (inventory.material = 'Whitewood'";
		}
		if (in_array('2', $box_material_val)) {
			if ($box_material == "") {
				$box_material = " AND (inventory.material = 'Plastic'";
			} else {
				$box_material .= " OR inventory.material = 'Plastic'";
			}
		}
		if (in_array('3', $box_material_val)) {
			if ($box_material == "") {
				$box_material = " AND (inventory.material = 'Corrugate'";
			} else {
				$box_material .= " OR inventory.material = 'Corrugate'";
			}
		}
		$final_filter_str .= $box_material . ")";
	}
	if (isset($_REQUEST["all_entry_data"]) && $_REQUEST["all_entry_data"] != "") {
		$box_entry_val = $_REQUEST["all_entry_data"];
		if (in_array('1', $box_entry_val)) {
			$box_entry = " AND (inventory.entry = '4-way'";
		}
		if (in_array('2', $box_entry_val)) {
			if ($box_entry == "") {
				$box_entry = " AND (inventory.entry = '2-way'";
			} else {
				$box_entry .= " OR inventory.entry = '2-way'";
			}
		}
		$final_filter_str .= $box_entry . ")";
	}

	if (isset($_REQUEST["all_structure_data"]) && $_REQUEST["all_structure_data"] != "") {
		$box_structure_val = $_REQUEST["all_structure_data"];
		if (in_array('1', $box_structure_val)) {
			$box_structure = " AND (inventory.structure = 'Stringer'";
		}
		if (in_array('2', $box_structure_val)) {
			if ($box_structure == "") {
				$box_structure = " AND (inventory.structure = 'Block'";
			} else {
				$box_structure .= " OR inventory.structure = 'Block'";
			}
		}
		$final_filter_str .= $box_structure . ")";
	}
	if (isset($_REQUEST["all_heat_treated_data"]) && $_REQUEST["all_heat_treated_data"] != "") {
		$box_heat_treated_val = $_REQUEST["all_heat_treated_data"];
		if (in_array('1', $box_heat_treated_val)) {
			$box_heat_treated = " AND (inventory.heat_treated = 'Required'";
		}
		if (in_array('2', $box_heat_treated_val)) {
			if ($box_heat_treated == "") {
				$box_heat_treated = " AND (inventory.heat_treated = 'Not Required'";
			} else {
				$box_heat_treated .= " OR inventory.heat_treated = 'Not Required'";
			}
		}
		$final_filter_str .= $box_heat_treated . ")";
	}

	if (isset($_REQUEST["all_type_sh_data"]) && $_REQUEST["all_type_sh_data"] != "") {
		$type_sh_filter_cnt = 0;
		$type_sh_filter_or_val = "";
		$final_filter_str .= " and ( ";
		if (in_array('1', $_REQUEST["all_type_sh_data"])) {
			$final_filter_str .= " (inventory.top_full = 1 AND inventory.bottom_fullflap = 1)";
			$type_sh_filter_cnt = 1;
		}
		if (in_array('2', $_REQUEST["all_type_sh_data"])) {
			if ($type_sh_filter_cnt == 1) {
				$type_sh_filter_or_val = " OR ";
			}
			$final_filter_str .= " $type_sh_filter_or_val (inventory.top_nolid = 1 AND inventory.bottom_fullflap = 1)";
		}
		$final_filter_str .= " ) ";
	}

	if (isset($_REQUEST["min_price_each"]) || isset($_REQUEST['max_price_each'])) {
		if ($_REQUEST["min_price_each"] != 0.00 || $_REQUEST["max_price_each"] != 99.99) {
			$final_filter_str .= " AND ((ulineDollar + if(ulineCents > 0, ulineCents, 0)) >= " . $_REQUEST["min_price_each"] . " 
			and (ulineDollar + if(ulineCents > 0, ulineCents, 0)) <= " . $_REQUEST["max_price_each"] . ")";
		}
	}

	if (isset($_REQUEST["min_length"]) || isset($_REQUEST['max_length'])) {
		if ($_REQUEST["min_length"] != 0 || $_REQUEST["max_length"] != 99) {
			$final_filter_str .= " AND ((lengthInch + if(lengthFraction > 0, CAST(SUBSTRING_INDEX(lengthFraction, '/', 1) AS DECIMAL(10,2)) / CAST(SUBSTRING_INDEX(lengthFraction, '/', -1) AS DECIMAL(10,2)), 0)) >= " . $_REQUEST["min_length"] . " 
			and (lengthInch + if(lengthFraction > 0, CAST(SUBSTRING_INDEX(lengthFraction, '/', 1) AS DECIMAL(10,2)) / CAST(SUBSTRING_INDEX(lengthFraction, '/', -1) AS DECIMAL(10,2)), 0)) <= " . $_REQUEST["max_length"] . ")";
		}
	}

	if (isset($_REQUEST["min_width"]) || isset($_REQUEST['max_width'])) {
		if ($_REQUEST["min_width"] != 0 || $_REQUEST["max_width"] != 99) {
			$final_filter_str .= " AND ((widthInch + if(widthFraction > 0, CAST(SUBSTRING_INDEX(widthFraction, '/', 1) AS DECIMAL(10,2)) / CAST(SUBSTRING_INDEX(widthFraction, '/', -1) AS DECIMAL(10,2)), 0)) >= " . $_REQUEST["min_width"] . " 
			and (widthInch + if(widthFraction > 0, CAST(SUBSTRING_INDEX(widthFraction, '/', 1) AS DECIMAL(10,2)) / CAST(SUBSTRING_INDEX(widthFraction, '/', -1) AS DECIMAL(10,2)), 0)) <= " . $_REQUEST["max_width"] . ")";
		}
	}

	if (isset($_REQUEST["min_height"]) || isset($_REQUEST['max_height'])) {
		if ($_REQUEST["min_height"] != 0 || $_REQUEST["max_height"] != 99) {
			//$final_filter_str.= " AND (CONVERT(SUBSTRING_INDEX(LWH, 'x', -1), UNSIGNED INTEGER) >= ". $_REQUEST["min_height"] ." AND CONVERT(SUBSTRING_INDEX(LWH, 'x', -1), UNSIGNED INTEGER) <= ".$_REQUEST["max_height"]." )" ;

			$final_filter_str .= " AND ((depthInch + if(depthFraction > 0, CAST(SUBSTRING_INDEX(depthFraction, '/', 1) AS DECIMAL(10,2)) / CAST(SUBSTRING_INDEX(depthFraction, '/', -1) AS DECIMAL(10,2)), 0)) >= " . $_REQUEST["min_height"] . " 
			and (depthInch + if(depthFraction > 0, CAST(SUBSTRING_INDEX(depthFraction, '/', 1) AS DECIMAL(10,2)) / CAST(SUBSTRING_INDEX(depthFraction, '/', -1) AS DECIMAL(10,2)), 0)) <= " . $_REQUEST["max_height"] . ")";
		}
	}


	if (isset($_REQUEST["min_cubic_footage"]) || isset($_REQUEST['max_cubic_footage'])) {
		if ($_REQUEST["min_cubic_footage"] != 0.00 || $_REQUEST["max_cubic_footage"] != 99.99) {
			$final_filter_str .= " AND cubicFeet >= " . $_REQUEST["min_cubic_footage"] . " AND cubicFeet <= " . $_REQUEST["max_cubic_footage"] . "";
		}
	}

	$dropoff_add1 = strval($_REQUEST["txtaddress"]);
	$dropoff_add2 = strval($_REQUEST["txtaddress2"]);
	$dropoff_city = strval($_REQUEST["txtcity"]);
	$dropoff_state = strval($_REQUEST["txtstate"]);
	if ($_REQUEST["txtcountry"] == "") {
		$dropoff_country = "USA";
	} else {
		$dropoff_country = $_REQUEST["txtcountry"];
	}
	//if (strtolower($_REQUEST["txtcountry"] ?? '') == "usa") {
	if (strtolower($_REQUEST["txtcountry"]) == "usa") {
		$dropoff_zip = substr(strval($_REQUEST["txtzipcode"]), 0, 5);
	} else {
		$dropoff_zip = $_REQUEST["txtzipcode"];
	}

	// if(isset($_REQUEST['vents']) && $_REQUEST['vents'] == 1){
	// 	$final_filter_str.= " AND inventory.vents_yes=1";
	// }
	if (isset($_REQUEST['include_sold_out_items']) && $_REQUEST['include_sold_out_items'] == 1) {
		//$final_filter_str.= " AND inventory.quantity_available > 0";
	}
	if (isset($_REQUEST['include_presold_and_loops']) && $_REQUEST['include_presold_and_loops'] == 1) {
		// $final_filter_str.= " AND inventory.box_type IN('Gaylord','GaylordUCB', 'Loop','PresoldGaylord')";	
	}


	if (isset($_REQUEST['ltl_allowed']) && $_REQUEST['ltl_allowed'] == 1) {
		$final_filter_str .= " AND inventory.ship_ltl=1";
	}
	if (isset($_REQUEST['customer_pickup_allowed']) && $_REQUEST['customer_pickup_allowed'] == 1) {
		$final_filter_str .= " AND inventory.customer_pickup_allowed=1";
	}
	if (isset($_REQUEST['urgent_clearance']) && $_REQUEST['urgent_clearance'] == 1) {
		$final_filter_str .= " AND inventory.box_urgent=1";
	}
	if (isset($_REQUEST['ect_burst']) && $_REQUEST['ect_burst'] != "") {
		switch ($_REQUEST['ect_burst']) {
			case '1':
				// Light Duty (< 32 ECT or 200# Burst)
				$final_filter_str .= " AND ((inventory.ect_val < 32 AND inventory.burst = 'ECT') OR (inventory.burst_val < 200 AND inventory.burst = 'Burst'))";
				break;
			case '2':
				// Standard (>= 32 ECT or 200# Burst)
				$final_filter_str .= " AND ((inventory.ect_val >= 32 AND inventory.burst = 'ECT') OR (inventory.burst_val >= 200 AND inventory.burst = 'Burst'))";
				break;
			case '3':
				// Heavy Duty (>= 44 ECT or 275# Burst)
				$final_filter_str .= " AND ((inventory.ect_val >= 44 AND inventory.burst = 'ECT') OR (inventory.burst_val >= 275 AND inventory.burst = 'Burst'))";
				break;
			case '4':
				// Super Heavy Duty (>= 48 ECT or 275# Burst)
				$final_filter_str .= " AND ((inventory.ect_val >= 48 AND inventory.burst = 'ECT') OR (inventory.burst_val >= 275 AND inventory.burst = 'Burst'))";
				break;
		}
	}


	if (isset($_REQUEST["all_printing_data"]) && $_REQUEST["all_printing_data"] != "") {
		$printing_filter_cnt = 0;
		$printing_filter_or_val = "";
		$final_filter_str .= " and ( ";
		if (in_array('1', $_REQUEST["all_printing_data"])) {
			$final_filter_str .= " inventory.printing = 'Printing'";
			$printing_filter_cnt = 1;
		}
		if (in_array('2', $_REQUEST["all_printing_data"])) {
			if ($printing_filter_cnt == 1) {
				$printing_filter_or_val = " OR ";
			}
			$final_filter_str .= " $printing_filter_or_val inventory.printing = 'Plain'";
		}
		$final_filter_str .= " ) ";
	}


	if (isset($_REQUEST["all_work_as_a_kit_box_data"]) && $_REQUEST["all_work_as_a_kit_box_data"] != "") {
		$kit_filter_cnt = 0;
		$loop_boxes_final_filter_str .= " AND (";
		if (in_array('1', $_REQUEST["all_work_as_a_kit_box_data"])) {
			$loop_boxes_final_filter_str .= " work_as_kit_box = 'Medium'";
			$kit_filter_cnt = 1;
		}
		if (in_array('2', $_REQUEST["all_work_as_a_kit_box_data"])) {
			$kit_filter_or_val = " ";
			if ($kit_filter_cnt == 1) {
				$kit_filter_or_val = " OR ";
			}

			$loop_boxes_final_filter_str .= " $kit_filter_or_val work_as_kit_box = 'Large'";
			$kit_filter_cnt = 1;
		}
		if (in_array('3', $_REQUEST["all_work_as_a_kit_box_data"])) {
			$kit_filter_or_val = " ";
			if ($kit_filter_cnt == 1) {
				$kit_filter_or_val = " OR ";
			}
			$loop_boxes_final_filter_str .= " $kit_filter_or_val work_as_kit_box = 'Large (Fold at Seam)'";
			$kit_filter_cnt = 1;
		}
		if (in_array('4', $_REQUEST["all_work_as_a_kit_box_data"])) {
			$kit_filter_or_val = " ";
			if ($kit_filter_cnt == 1) {
				$kit_filter_or_val = " OR ";
			}
			$loop_boxes_final_filter_str .= " $kit_filter_or_val work_as_kit_box = 'X-Large'";
			$kit_filter_cnt = 1;
		}
		if (in_array('5', $_REQUEST["all_work_as_a_kit_box_data"])) {
			$kit_filter_or_val = " ";
			if ($kit_filter_cnt == 1) {
				$kit_filter_or_val = " OR ";
			}
			$loop_boxes_final_filter_str .= " $kit_filter_or_val work_as_kit_box = 'X-Large (Fold at Seam)'";
			$kit_filter_cnt = 1;
		}
		$loop_boxes_final_filter_str .= " ) ";
	}

	$box_status_filter = "";
	$availalesort = "";
	$availale_selectval = "";
	if (isset($_REQUEST['available'])) {
		$availale_selectval = $_REQUEST['available'];
		switch ($_REQUEST['available']) {
			case 'quantities':
				$availalesort = " ,`inventory`.`quantity_available`";
				break;
			case "actual";
				$availalesort = " ,`inventory`.`actual_inventory`";
				break;
			case "frequency";
				$availalesort = " ,`inventory`.`expected_loads_per_mo`";
				break;
		}
	}

	$sql_fil = "";

	if (isset($_REQUEST["box_type"]) && strcasecmp($_REQUEST["box_type"], "Gaylord") == 0) {
		if (isset($_REQUEST['box_subtype']) &&  (strcasecmp($_REQUEST["box_subtype"], "all") != 0	 && $_REQUEST['box_subtype'] != "")) {
			//$box_subtype = $box_subtype ?? "";
			$sql_fil .= " AND inventory.box_type IN ('$box_subtype')";
		} else {
			if (isset($_REQUEST['all_include_presold_and_loops_data']) && $_REQUEST['all_include_presold_and_loops_data'] != "") {
				$all_include_presold_and_loops_data = implode("','", $_REQUEST['all_include_presold_and_loops_data']);
				$sql_fil .= " AND inventory.box_type IN ('$all_include_presold_and_loops_data')";
			} else {
				$sql_fil .= " AND inventory.box_type IN('Gaylord','GaylordUCB', 'Loop','PresoldGaylord')";
			}
		}
	} else if (isset($_REQUEST["box_type"]) && strcasecmp($_REQUEST["box_type"], "Pallets") == 0) {
		if (isset($_REQUEST['box_subtype']) && (strcasecmp($_REQUEST["box_subtype"], "all") == -1 && $_REQUEST['box_subtype'] != "")) {
			//$box_subtype = $box_subtype ?? "";
			$sql_fil .= " AND inventory.box_type IN ('$box_subtype')";
		} else {
			$sql_fil .= " AND inventory.box_type IN ('PalletsUCB','PalletsnonUCB')";
		}
	} else if (isset($_REQUEST["box_type"]) && strcasecmp($_REQUEST["box_type"], "Shipping") == 0) {
		//echo "box_subtype " . $_REQUEST['box_type'] . "<br>";
		if (isset($_REQUEST['box_subtype']) && (strcasecmp($_REQUEST["box_subtype"], "all") == -1 && $_REQUEST['box_subtype'] != "")) {
			//$box_subtype = $box_subtype ?? "";
			$sql_fil .= " AND inventory.box_type IN ('$box_subtype')";
		} else {
			if (isset($_REQUEST['all_include_presold_and_loops_data'])) {
				$all_include_presold_and_loops_data = $_REQUEST['all_include_presold_and_loops_data'];

				$sql_fil .= " AND inventory.box_type IN(";
				$Shippingboxtype = "";
				foreach ($all_include_presold_and_loops_data as $boxtype) {
					if ($boxtype == "ShippingKit") {
						$Shippingboxtype .= "'Medium','Large','Xlarge',";
					} else {
						$Shippingboxtype .= "'$boxtype',";
					}
				}
				if ($Shippingboxtype != "") {
					$Shippingboxtype = substr($Shippingboxtype, 0, strlen($Shippingboxtype) - 1);
				}
				$sql_fil .= $Shippingboxtype . " ) ";
			} else {
				$sql_fil .= " AND inventory.box_type IN ('Box','Boxnonucb','Medium','Large','Xlarge','Boxnonucb')"; //'LoopShipping','Box','Boxnonucb','Presold','Medium','Large','Xlarge','Boxnonucb'	
			}
		}
		//echo "sql_fil ".$sql_fil;
	} else if (isset($_REQUEST["box_type"]) && strcasecmp($_REQUEST["box_type"], "Supersacks") == 0) {
		if (isset($_REQUEST['box_subtype']) && (strcasecmp($_REQUEST["box_subtype"], "all") == -1 && $_REQUEST['box_subtype'] != "")) {
			//$box_subtype = $box_subtype ?? "";
			$sql_fil .= " AND inventory.box_type IN ('$box_subtype')";
		} else {
			$sql_fil .= " AND inventory.box_type IN ('SupersackUCB','SupersacknonUCB','Supersacks')";
		}
	}


	$shipLat = "";
	$shipLong = "";
	$enter_zipcode = $_REQUEST['txtzipcode'];

	if ($enter_zipcode != "") {
		$tmp_zipval = "";
		$tmp_zipval = str_replace(" ", "", $enter_zipcode);
		//if($country == "Canada" )
		//{ 	
		//	$zipShipStr= "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
		//}elseif(($country) == "Mexico" ){
		//	$zipShipStr= "Select * from zipcodes_mexico limit 1";
		//}else {
		$zipShipStr = "Select latitude, longitude from ZipCodes WHERE zip = '" . intval($enter_zipcode) . "'";
		//}
		//echo $zipShipStr . "<br>";
		db_b2b();
		$zip_view_res = db_query($zipShipStr);
		while ($ziprec = array_shift($zip_view_res)) {
			$shipLat = $ziprec["latitude"];
			$shipLong = $ziprec["longitude"];
		}
	}
	//echo "shipLat " . $enter_zipcode . $shipLat . " = " . $shipLong . "<br>";
	//exit;

	$warehouse_innerjoin_sql = "";
	if (isset($_REQUEST['warehouse']) && $_REQUEST['warehouse'] != "all") {
		$final_filter_str .= " AND inventory.box_warehouse_id = '" . $_REQUEST['warehouse'] . "' ";
	}

	$box_tag_str = "";
	if (isset($_REQUEST['box_tag']) && $_REQUEST['box_tag'] != "") {
		$box_tag_str = implode(',', $_REQUEST['box_tag']);
		$final_filter_str .= " AND inventory.tag IN ('$box_tag_str')";
	}

	$b2b_status_str = '';
	if (isset($_REQUEST["all_status_data"]) && $_REQUEST["all_status_data"] != "") {
		if (count($_REQUEST["all_status_data"]) != 3) {
			$status_filter_cnt = 0;
			$status_filter_or_val = "";
			$b2b_status_str .= " AND ( ";
			if (in_array('1', $_REQUEST["all_status_data"])) {
				$b2b_status_str .= " inventory.b2b_status='1.0' or inventory.b2b_status='1.1' or inventory.b2b_status='1.2' ";
				$status_filter_cnt = 1;
			}
			if (in_array('2', $_REQUEST["all_status_data"])) {
				if ($status_filter_cnt == 1) {
					$status_filter_or_val = " OR ";
				}
				$b2b_status_str .= " $status_filter_or_val inventory.b2b_status='2.0' or inventory.b2b_status='2.1' or inventory.b2b_status='2.2' or inventory.b2b_status='2.3' or inventory.b2b_status='2.4' ";
				//Added inventory.b2b_status=2.3 or inventory.b2b_status=2.4 to match the warehouse inv
				$status_filter_cnt = 1;
			}
			if (in_array('3', $_REQUEST["all_status_data"])) {
				if ($status_filter_cnt == 1) {
					$status_filter_or_val = " OR ";
				}
				$b2b_status_str .= " $status_filter_or_val inventory.b2b_status='2.5' or inventory.b2b_status='2.6' or inventory.b2b_status='2.7' or inventory.b2b_status='2.8' or inventory.b2b_status='2.9' ";
			}
			$b2b_status_str .= " ) ";
		}
	}

	if (isset($_REQUEST["shown_in_client_flg"]) && $_REQUEST["shown_in_client_flg"] == 1) {

		$b2b_status_str = " AND (inventory.b2b_status='1.0' or inventory.b2b_status='1.1' or inventory.b2b_status='1.2') ";
		if (isset($_REQUEST["box_type"]) && strcasecmp($_REQUEST["box_type"], "Pallets") == 0) {
			$sql_fil = " AND inventory.box_type IN('PalletsUCB','PalletsnonUCB')";
		} else if (isset($_REQUEST["box_type"]) && strcasecmp($_REQUEST["box_type"], "Shipping") == 0) {
			$sql_fil = " AND inventory.box_type IN ('Box','Boxnonucb','Medium','Large','Xlarge','Boxnonucb')"; //'LoopShipping','Box','Boxnonucb','Presold','Medium','Large','Xlarge','Boxnonucb'	
		} else if (isset($_REQUEST["box_type"]) && strcasecmp($_REQUEST["box_type"], "Supersacks") == 0) {
			$sql_fil = " AND inventory.box_type IN ('SupersackUCB','SupersacknonUCB','Supersacks')";
		} else {
			$sql_fil = " AND inventory.box_type IN('Gaylord','GaylordUCB')";
		}
	}

	$lastmonth_qry_array = array();
	$lastmonth_qry = "SELECT box_id, sum(boxgood) as sumboxgood from loop_inventory where boxgood >0 and ";
	$lastmonth_qry .= " UNIX_TIMESTAMP(add_date) >= " .  strtotime('today - 30 days') . " AND UNIX_TIMESTAMP(add_date) <= " . strtotime(date("m/d/Y")) . " group by box_id";
	db();
	$dt_res_so = db_query($lastmonth_qry);
	while ($so_row = array_shift($dt_res_so)) {
		$lastmonth_qry_array[$so_row["box_id"]] = $so_row["sumboxgood"];
	}

	$dt_view_qry = "";
	$warehouse_filter_arr = [];
	if (isset($_REQUEST["show_urgent_box"]) && $_REQUEST["show_urgent_box"] == 1) {
		$dt_view_qry = "SELECT inventory.b2b_status as invb2b_status , inventory.id as b2b_id, inventory.loops_id, inventory.location_city, inventory.location_state, inventory.location_zip, inventory.bwall,
		inventory.expected_loads_per_mo, inventory.vendor, inventory.lengthInch, inventory.widthInch, inventory.depthInch, inventory.vendor_b2b_rescue,inventory.ship_ltl,
		inventory.material, inventory.customer_pickup_allowed, inventory.uniform_mixed_load, inventory.lead_time, inventory.box_sub_type, inventory.bwall_max,
		inventory.bwall_min,inventory.box_type,inventory.date, inventory.system_description, inventory.quantity_per_pallet, inventory.quantity, inventory.additional_description_text, inventory.location_zip_latitude, inventory.buy_now_load_can_ship_in,
		inventory.location_zip_longitude, inventory.nickname, inventory.ID, inventory.bottom_no, inventory.bottom_partial, inventory.bottom_partialsheet, inventory.bottom_fullflap, inventory.bottom_tray, inventory.top_spout, inventory.bottom_spout, inventory.bottom_spiked, inventory.bottom_flat,
		inventory.location, inventory.actual_qty_calculated, inventory.box_urgent, inventory.contracted, inventory.prepay, inventory.ulineDollar, inventory.ulineCents, inventory.costDollar, inventory.costCents,
		inventory.notes, inventory.description, inventory.after_actual_inventory, inventory.active, inventory.shape_rect, inventory.shape_oct, inventory.top_nolid, inventory.top_remove, inventory.top_partial, inventory.top_full, inventory.vents_yes, inventory.vents_no, inventory.grade, inventory.printing
		,inventory.ect_val,inventory.burst,inventory.burst_val, inventory.entry, inventory.heat_treated, inventory.structure from inventory WHERE inventory.Active LIKE 'A' and inventory.box_urgent = 1 $sql_fil order by inventory.b2b_status $availalesort asc";
		//FORCE INDEX (box_urgent)
	} elseif (isset($_REQUEST["fav_inv"]) && $_REQUEST["fav_inv"] == 1) {
		$fav_inv_ids = "";
		db();

		$user_id = $_REQUEST['fav_user_id'];

		$selFavData = db_query("SELECT b2b_id FROM b2b_inventory_gaylords_favorite WHERE fav_status = 1 and user_id= $user_id");
		while ($rowsFavData = array_shift($selFavData)) {
			$fav_inv_ids .= $rowsFavData["b2b_id"] . ",";
		}
		if ($fav_inv_ids != "") {
			$fav_inv_ids = substr($fav_inv_ids, 0, strlen($fav_inv_ids) - 1);

			$dt_view_qry = "SELECT inventory.b2b_status as invb2b_status , inventory.id as b2b_id, inventory.loops_id, inventory.location_city, inventory.location_state, inventory.location_zip, inventory.bwall,
			inventory.expected_loads_per_mo, inventory.vendor, inventory.lengthInch, inventory.widthInch, inventory.depthInch, inventory.vendor_b2b_rescue,inventory.ship_ltl,
			inventory.material, inventory.customer_pickup_allowed, inventory.uniform_mixed_load, inventory.lead_time, inventory.box_sub_type, inventory.bwall_max,
			inventory.bwall_min,inventory.box_type,inventory.date, inventory.system_description, inventory.quantity_per_pallet, inventory.quantity, inventory.additional_description_text, inventory.location_zip_latitude, inventory.buy_now_load_can_ship_in,
			inventory.location_zip_longitude, inventory.nickname, inventory.ID, inventory.bottom_no, inventory.bottom_partial, inventory.bottom_partialsheet, inventory.bottom_fullflap, inventory.bottom_tray, inventory.top_spout, inventory.bottom_spout, inventory.bottom_spiked, inventory.bottom_flat,
			inventory.location, inventory.actual_qty_calculated, inventory.box_urgent, inventory.contracted, inventory.prepay, inventory.ulineDollar, inventory.ulineCents, inventory.costDollar, inventory.costCents,
			inventory.notes, inventory.description, inventory.after_actual_inventory, inventory.active, inventory.shape_rect, inventory.shape_oct , inventory.top_nolid, inventory.top_remove, inventory.top_partial, inventory.top_full, inventory.vents_yes, inventory.vents_no, inventory.grade, inventory.printing 
			,inventory.ect_val,inventory.burst,inventory.burst_val,inventory.entry, inventory.heat_treated, inventory.structure from inventory WHERE inventory.Active LIKE 'A' and inventory.id in ($fav_inv_ids) order by inventory.b2b_status $availalesort asc";
			//FORCE INDEX (PRIMARY)
		}
	} elseif (isset($_REQUEST["fav_prev_order"]) && $_REQUEST["fav_prev_order"] == 1) {
		$dt_view_qry = "SELECT inventory.b2b_status as invb2b_status , inventory.id as b2b_id, inventory.loops_id, inventory.location_city, inventory.location_state, inventory.location_zip, inventory.bwall,
		inventory.expected_loads_per_mo, inventory.vendor, inventory.lengthInch, inventory.widthInch, inventory.depthInch, inventory.vendor_b2b_rescue,inventory.ship_ltl,
		inventory.material, inventory.customer_pickup_allowed, inventory.uniform_mixed_load, inventory.lead_time, inventory.box_sub_type, inventory.bwall_max,
		inventory.bwall_min,inventory.box_type,inventory.date, inventory.system_description, inventory.quantity_per_pallet, inventory.quantity, inventory.additional_description_text, inventory.location_zip_latitude, inventory.buy_now_load_can_ship_in,
		inventory.location_zip_longitude, inventory.shape_rect, inventory.shape_oct, inventory.top_nolid, inventory.top_remove, inventory.top_partial, inventory.top_full, inventory.vents_yes, inventory.vents_no, inventory.grade, inventory.printing
		,inventory.ect_val,inventory.burst,inventory.burst_val,inventory.entry, inventory.heat_treated, inventory.structure from inventory WHERE inventory.Active LIKE 'A' and inventory.id in (" . $_REQUEST["prev_order_str"] . ") order by inventory.b2b_status, inventory.id $availalesort asc";
		//FORCE INDEX (PRIMARY)
	} else {
		$dt_view_qry = "SELECT inventory.b2b_status as invb2b_status , inventory.id as b2b_id, inventory.loops_id, inventory.location_city, inventory.location_state, inventory.location_zip, inventory.bwall,
		inventory.expected_loads_per_mo, inventory.vendor, inventory.lengthInch, inventory.widthInch, inventory.depthInch, inventory.vendor_b2b_rescue,inventory.ship_ltl,
		inventory.material, inventory.customer_pickup_allowed, inventory.uniform_mixed_load, inventory.lead_time, inventory.box_sub_type, inventory.bwall_max,
		inventory.bwall_min,inventory.box_type,inventory.date, inventory.system_description, inventory.quantity_per_pallet, inventory.quantity, inventory.additional_description_text, inventory.location_zip_latitude, inventory.buy_now_load_can_ship_in,
		inventory.location_zip_longitude , inventory.nickname, inventory.ID, inventory.bottom_no, inventory.bottom_partial, inventory.bottom_partialsheet, inventory.bottom_fullflap, inventory.bottom_tray, inventory.top_spout, inventory.bottom_spout, inventory.bottom_spiked, inventory.bottom_flat,
		inventory.location, inventory.actual_qty_calculated, inventory.box_urgent, inventory.contracted, inventory.prepay, inventory.ulineDollar, inventory.ulineCents, inventory.costDollar, inventory.costCents,
		inventory.notes, inventory.description, inventory.after_actual_inventory, inventory.active, inventory.shape_rect, inventory.shape_oct, inventory.top_nolid, inventory.top_remove, inventory.top_partial, inventory.top_full, inventory.vents_yes, inventory.vents_no, inventory.grade, inventory.printing 
		,inventory.ect_val,inventory.burst,inventory.burst_val, inventory.entry, inventory.heat_treated, inventory.structure from inventory $warehouse_innerjoin_sql WHERE inventory.Active LIKE 'A' $b2b_status_str $sql_fil $final_filter_str order by inventory.b2b_status $availalesort asc";
		//FORCE INDEX (box_type, b2b_status, Active)
	}
	// and actual_qty_calculated > 0 
	//and ID = 5267 
	//echo $dt_view_qry;

	$products_array = array();
	$final_product_array = array();
	if ($dt_view_qry != "") {
		db_b2b();
		$dt_view_res = db_query($dt_view_qry);
		//$dt_view_res = db_query_cache_data($dt_view_qry, [], [], "boomerang_new_get_data");

		$supplier_name = "";
		$load_av_after_po =	0;
		$query_count = 0;
		while ($dt_view_row = array_shift($dt_view_res)) {
			$product_shape = "";
			if ($dt_view_row['shape_rect'] == 1) {
				$product_shape = 1;
			} else if ($dt_view_row['shape_oct'] == 1) {
				$product_shape = 2;
			}

			$product_top1 = "";
			$product_top2 = "";
			$product_top3 = "";
			$product_top4 = "";
			if ($dt_view_row['top_nolid'] == 1) {
				$product_top1 = 1;
			}
			if ($dt_view_row['top_remove'] == 1) {
				$product_top2 = 1;
			}
			if ($dt_view_row['top_partial'] == 1) {
				$product_top3 = 1;
			}
			if ($dt_view_row['top_full'] == 1) {
				$product_top4 = 1;
			}
			$product_bottom2 = "";
			$product_bottom3 = "";
			$product_bottom4 = "";
			$product_bottom5 = "";
			if ($dt_view_row['bottom_partial'] == 1) {
				$product_bottom2 = 1;
			} else if ($dt_view_row['bottom_partialsheet'] == 1) {
				$product_bottom5 = 1;
			} else if ($dt_view_row['bottom_tray'] == 1) {
				$product_bottom3 = 1;
			} else if ($dt_view_row['bottom_fullflap'] == 1) {
				$product_bottom4 = 1;
			}

			$product_vents = "";
			if ($dt_view_row['vents_yes'] == 1) {
				$product_vents = 1;
			} else if ($dt_view_row['vents_no'] == 1) {
				$product_vents = 2;
			}
			$product_grade = "";
			if ($dt_view_row['grade'] == "A") {
				$product_grade = 1;
			} else if ($dt_view_row['grade'] == "B") {
				$product_grade = 2;
			} else if ($dt_view_row['grade'] == "C") {
				$product_grade = 3;
			}
			$product_printing = "";
			if ($dt_view_row['printing'] == "Printing") {
				$product_printing = 1;
			} else if ($dt_view_row['printing'] == "Plain") {
				$product_printing = 2;
			}

			$product_material = "";
			if ($dt_view_row['material'] == "Whitewood") {
				$product_material = 1;
			} else if ($dt_view_row['material'] == "Plastic") {
				$product_material = 2;
			} else if ($dt_view_row['material'] == "Corrugate") {
				$product_material = 3;
			}
			$product_entry = "";
			if ($dt_view_row['entry'] == "4-way") {
				$product_entry = 1;
			} else if ($dt_view_row['entry'] == "2-way") {
				$product_entry = 2;
			}

			$product_structure = "";
			if ($dt_view_row['structure'] == "Stringer") {
				$product_structure = 1;
			} else if ($dt_view_row['structure'] == "Block") {
				$product_structure = 2;
			}

			$product_type_sh = "";
			if ($_REQUEST['box_type'] == "Shipping") {
				if ($dt_view_row['top_full'] == 1 && $dt_view_row['bottom_fullflap'] == 1) {
					$product_type_sh = 1;
				} else if ($dt_view_row['top_nolid'] == 1 && $dt_view_row['bottom_fullflap'] == 1) {
					$product_type_sh = 2;
				}
			}


			$product_heat_treated = "";
			if ($dt_view_row['heat_treated'] == "Required") {
				$product_heat_treated = 1;
			} else if ($dt_view_row['heat_treated'] == "Not Required") {
				$product_heat_treated = 2;
			}

			$product_bwall = "";
			if ($_REQUEST['box_type'] == "Shipping") {
				if ($dt_view_row['bwall'] < 2) {
					$product_bwall = 1;
				} else if ($dt_view_row['bwall'] > 2) {
					$product_bwall = 2;
				}
			} else {
				if ($dt_view_row['bwall'] >= 1 && $dt_view_row['bwall'] <= 2) {
					$product_bwall = 1;
				} else if ($dt_view_row['bwall'] == 3 ) {
					$product_bwall = 2;
				} else if ($dt_view_row['bwall'] == 4 ) {
					$product_bwall = 3;
				} else if ($dt_view_row['bwall'] >= 5) {
					$product_bwall = 4;
				}
			}
			$product_ect_detail = "";
			$product_ect_burst = "";
			if (($dt_view_row['ect_val'] < 32  && $dt_view_row['burst'] == 'ECT') || ($dt_view_row['burst_val'] < 200 && $dt_view_row['burst'] == 'Burst')) {
				$product_ect_burst = 1;
				if ($dt_view_row['burst'] == "ECT") {
					$product_ect_detail = $dt_view_row['ect_val'] . "ECT";
				} else {
					$product_ect_detail = $dt_view_row['burst_val'] . "#";
				}
			} else if (($dt_view_row['ect_val'] >= 32 && $dt_view_row['ect_val'] < 44 && $dt_view_row['burst'] == 'ECT') || ($dt_view_row['burst_val'] >= 200 && $dt_view_row['burst_val'] < 275 && $dt_view_row['burst'] == 'Burst')) {
				$product_ect_burst = 2;
				if ($dt_view_row['burst'] == "ECT") {
					$product_ect_detail = $dt_view_row['ect_val'] . "ECT";
				} else {
					$product_ect_detail = $dt_view_row['burst_val'] . "#";
				}
			} else if (($dt_view_row['ect_val'] >= 44 && $dt_view_row['ect_val'] < 48 && $dt_view_row['burst'] == 'ECT') || ($dt_view_row['burst_val'] >= 275 && $dt_view_row['burst'] == 'Burst')) {
				$product_ect_burst = 3;
				if ($dt_view_row['burst'] == "ECT") {
					$product_ect_detail = $dt_view_row['ect_val'] . "ECT";
				} else {
					$product_ect_detail = $dt_view_row['burst_val'] . "#";
				}
			} else if ($dt_view_row['ect_val'] >= 48 && $dt_view_row['burst'] == 'ECT' || $dt_view_row['burst_val'] >= 275 && $dt_view_row['burst'] == 'Burst') {
				$product_ect_burst = 4;
				if ($dt_view_row['burst'] == "ECT") {
					$product_ect_detail = $dt_view_row['ect_val'] . "ECT";
				} else {
					$product_ect_detail = $dt_view_row['burst_val'] . "#";
				}
			}


			$ftl_qty = $dt_view_row["quantity"];
			$nickname = "";
			if ($dt_view_row["nickname"] != "") {
				$nickname = $dt_view_row["nickname"];
			}

			$description = "";
			$box_sub_type = "";
			$q1 = "SELECT sub_type_name FROM loop_boxes_sub_type_master where unqid = " . $dt_view_row["box_sub_type"] . " LIMIT 1";
			db();
			$query = db_query($q1);
			while ($fetch = array_shift($query)) {
				$box_sub_type = $fetch['sub_type_name'];
			}

			$box_type_txt = "";
			$box_type_from_qry = $dt_view_row["box_type"];
			if ($box_type_from_qry == 'Gaylord' || $box_type_from_qry == 'GaylordUCB' || $box_type_from_qry == 'Loop' || $box_type_from_qry == 'PresoldGaylord') {
				$box_type_txt = "Gaylord";
			}
			if (
				$box_type_from_qry == 'Box' || $box_type_from_qry == 'Boxnonucb' || $box_type_from_qry == 'Presold' || $box_type_from_qry == 'Medium' || $box_type_from_qry == 'Large'
				|| $box_type_from_qry == 'Xlarge' || $box_type_from_qry == 'Boxnonucb'
			) {
				$box_type_txt = "Shipping Boxes";
			}
			if ($box_type_from_qry == 'PalletsUCB' || $box_type_from_qry == 'PalletsnonUCB') {
				$box_type_txt = "Pallets";
			}
			if ($box_type_from_qry == 'SupersackUCB' || $box_type_from_qry == 'SupersacknonUCB' || $box_type_from_qry == 'Supersacks') {
				$box_type_txt = "Supersacks";
			}
			if ($box_type_from_qry == 'DrumBarrelUCB' || $box_type_from_qry == 'DrumBarrelnonUCB') {
				$box_type_txt = "Drums/Barrels/IBCs";
			}
			if ($box_type_from_qry == 'Recycling' || $box_type_from_qry == 'Other' || $box_type_from_qry == 'Waste-to-Energy') {
				$box_type_txt = "Recycling+Other";
			}
			$uniform_mixed_load_txt = "";
			if ($dt_view_row["uniform_mixed_load"] == "Uniform") {
				$uniform_mixed_load_txt = 1;
			} else if ($dt_view_row["uniform_mixed_load"] == "Mixed") {
				$uniform_mixed_load_txt = 2;
			}
			$box_sub_type_str = $box_sub_type == "" ? "" : " - $box_sub_type";
			if ($dt_view_row["uniform_mixed_load"] == "Uniform") {
				/*if (isset($_REQUEST['view_type']) && $_REQUEST['view_type'] == "grid_view") {
						$description = ucfirst($box_type_txt) . ": " . $dt_view_row["bwall"] . "ply" . $box_sub_type_str . "  <br>(ID " . $dt_view_row["ID"] . ")";
					} else {
						$description = ucfirst($box_type_txt) . ": " . $dt_view_row["bwall"] . "ply" . $box_sub_type_str . " (ID " . $dt_view_row["ID"] . ")";
					}
						*/
				$description_grid = ucfirst($box_type_txt) . ": " . $dt_view_row["bwall"] . "ply" . $box_sub_type_str . "  <br>(ID " . $dt_view_row["ID"] . ")";
				$description = ucfirst($box_type_txt) . ": " . $dt_view_row["bwall"] . "ply" . $box_sub_type_str . " (ID " . $dt_view_row["ID"] . ")";


				$boxwall = $dt_view_row["bwall"] . "ply";
			} else {
				$wall_str = "";
				if ($dt_view_row["bwall_min"] == $dt_view_row["bwall_max"]) {
					$wall_str = $dt_view_row["bwall_min"];
				} else {
					$wall_str = $dt_view_row["bwall_min"] . "-" . $dt_view_row["bwall_max"];
				}
				$boxwall = $wall_str . "ply";

				/*if (isset($_REQUEST['view_type']) && $_REQUEST['view_type'] == "grid_view") {
						$description = ucfirst($box_type_txt) . ": " . $wall_str . "ply" . $box_sub_type_str . " <br>(ID " . $dt_view_row["ID"] . ")";
					} else {
						$description = ucfirst($box_type_txt) . ": " . $wall_str . "ply" . $box_sub_type_str . " (ID " . $dt_view_row["ID"] . ")";
					}
						*/
				$description_grid = ucfirst($box_type_txt) . ": " . $wall_str . "ply" . $box_sub_type_str . " <br>(ID " . $dt_view_row["ID"] . ")";
				$description = ucfirst($box_type_txt) . ": " . $wall_str . "ply" . $box_sub_type_str . " (ID " . $dt_view_row["ID"] . ")";
			}

			$system_description = $dt_view_row["system_description"] . " " . number_format(floatval($dt_view_row["quantity_per_pallet"])) . "/pallet, " . number_format(floatval($ftl_qty)) . "/load " . $dt_view_row["additional_description_text"];

			//bottom column data
			$bottom_no = $dt_view_row["bottom_no"];
			$bottom_partial = $dt_view_row["bottom_partial"];
			$bottom_partialsheet = $dt_view_row["bottom_partialsheet"];
			$bottom_fullflap = $dt_view_row["bottom_fullflap"];
			$bottom_tray = $dt_view_row["bottom_tray"];
			$top_spout = $dt_view_row["top_spout"];
			$bottom_spout = $dt_view_row["bottom_spout"];
			$bottom_spiked = $dt_view_row["bottom_spiked"];
			$bottom_flat = $dt_view_row["bottom_flat"];

			$lead_time = $dt_view_row["lead_time"];

			$box_desc_bottom = "";
			//if (($bottom_no + $bottom_partial + $bottom_partialsheet + $bottom_fullflap + $bottom_tray + $bottom_spout + $bottom_spiked) > 1) {
			if ((intval($bottom_no) + intval($bottom_partial) + intval($bottom_partialsheet) + intval($bottom_fullflap) + intval($bottom_tray) + intval($bottom_spout) + intval($bottom_spiked)) > 1) {
				$box_desc_bottom = "Mixed Bottom";
			} else {
				if ($bottom_no == 1) {
					$box_desc_bottom = "No Bottom";
				}
				if ($bottom_partial == 1) {
					$box_desc_bottom = "Partial Flap Bottom";
				}
				if ($bottom_partialsheet == 1) {
					$box_desc_bottom = "Slip Sheet Bottom";
				}
				if ($bottom_fullflap == 1) {
					$box_desc_bottom = "Full Flap Bottom";
				}
				if ($bottom_tray == 1) {
					$box_desc_bottom = "Tray Bottom";
				}
				if ($bottom_spout == 1) {
					$box_desc_bottom = "No Bottom";
				}
				if ($bottom_spiked == 1) {
					$box_desc_bottom = "No Bottom";
				}
				if ($bottom_flat == 1) {
					$box_desc_bottom = "No Bottom";
				}
			}

			$ship_from = "";
			if ($dt_view_row["location_state"] != "") {
				$get_loc_qry = "Select state from state_master where state_code ='" . $dt_view_row["location_state"] . "'";
				db();
				$get_loc_res = db_query($get_loc_qry);
				$loc_row = array_shift($get_loc_res);
				$ship_from = $loc_row["state"];
			}

			$added_on = $dt_view_row["date"];
			$ltl = $dt_view_row["ship_ltl"] == 1 ? "Yes" : "No";
			$customer_pickup_allowed = $dt_view_row["customer_pickup_allowed"] == 1 ? "Yes" : "No";

			$updated_on = "";
			$img = "";
			$loads = " - ";
			$vendor_b2b_rescue = $dt_view_row["vendor_b2b_rescue"];
			$supplier_owner = "";
			$first_load_can_ship_in = "";
			$vendor_id = $dt_view_row["vendor"];
			$b2b_status = $dt_view_row["invb2b_status"];
			$b2b_id = $dt_view_row["b2b_id"];

			$shipFromLocation = $dt_view_row["location"];
			$actual_qty = $dt_view_row["actual_qty_calculated"];



			$pickup_cdata_allowed = 'No';
			if ($dt_view_row["customer_pickup_allowed"] == 1) {
				$pickup_cdata_allowed = 'Yes';
			}

			$st_query = "Select * from b2b_box_status where status_key='" . $b2b_status . "' $box_status_filter";
			db();
			$st_res = db_query($st_query);
			$st_row = array_shift($st_res);
			$status_org = $st_row["box_status"];
			$status_key = $st_row["status_key"];
			$status = $st_row["box_status"];
			$status_key_qry = $st_row["status_key"];
			if ($status_key_qry == "1.0" || $status_key_qry == "1.1" || $status_key_qry == "1.2") {
				$status = "<font color='green'>" . $st_row["box_status"] . "</font></td>";
			} elseif ($status_key_qry == "2.0" || $status_key_qry == "2.1" || $status_key_qry == "2.2") {
				$status = "<font color='orange'>" . $st_row["box_status"] . "</font></td>";
			}
			if ($st_row["box_urgent"] == 1) {
				$status = "<font color='red'>URGENT</font></td>";
			}

			$loop_id = $dt_view_row["loops_id"];
			$qry_sku = "select id, box_warehouse_id, sku, bpallet_qty, boxes_per_trailer, next_load_available_date,
			bpic_1, flyer_notes,last_modified_date, after_po, expected_loads_per_mo, blength, bwidth, bdepth, blength_frac, bwidth_frac, bdepth_frac, blength_min, blength_max,  
			bwidth_min, bwidth_max, bheight_min, bheight_max from loop_boxes where b2b_id=" . $dt_view_row["b2b_id"] . " $loop_boxes_final_filter_str LIMIT 1";
			//FORCE INDEX (b2b_id)
			//echo $qry_sku . "<br>";
			//exit;
			$sku = "";
			$flyer_notes = "";
			$loop_data_found = "no";
			$boxheight = "";
			$boxwidth = "";
			$boxlength = "";
			db();
			$dt_view_sku = db_query($qry_sku);
			$total_no_of_loads = 0;
			$box_warehouse_id = 0;
			$frequency = 0;
			$blength = $bwidth = $bdepth = $bcubicfootage = 0;
			while ($sku_val = array_shift($dt_view_sku)) {
				if (!in_array($sku_val["box_warehouse_id"], $warehouse_filter_arr)) {
					$warehouse_filter_arr[] = $sku_val["box_warehouse_id"];
				}

				$loop_data_found = "yes";

				// Dimension sorting 
				$uniform_mixed_load = $dt_view_row["uniform_mixed_load"];

				if ($uniform_mixed_load != "Mixed") {

					$blength = $sku_val["blength"];
					$blength = preg_replace("(\n)", "<BR>", $blength);
					$bwidth = $sku_val["bwidth"];
					$bwidth = preg_replace("(\n)", "<BR>", $bwidth);
					$bheight = $sku_val["bdepth"];
					$bheight = preg_replace("(\n)", "<BR>", $bheight);

					$boxheight = $bheight . chr(34);
					$boxwidth = $bwidth;
					$boxlength = $blength;

					$blength_frac = $sku_val["blength_frac"];
					$blength_frac = preg_replace("(\n)", "<BR>", $blength_frac);
					$bwidth_frac = $sku_val["bwidth_frac"];
					$bwidth_frac = preg_replace("(\n)", "<BR>", $bwidth_frac);
					$bdepth_frac = $sku_val["bdepth_frac"];
					$bdepth_frac = preg_replace("(\n)", "<BR>", $bdepth_frac);

					if ($blength_frac != "") {
						$frac = explode("/", $blength_frac);
						$numerator = $frac[0];
						$denominator = $frac[1];
						$box_length = $blength + (float)$numerator / (float)$denominator;
					} else {
						$frac = "";
						$box_length = $blength;
					}
					//box width fraction
					if ($bwidth_frac != "") {
						$frac = explode("/", $bwidth_frac);
						$numerator = $frac[0];
						$denominator = $frac[1];
						$box_width = $bwidth + (float)$numerator / (float)$denominator;
					} else {
						$frac = "";
						$box_width = $bwidth;
					}

					//box height fraction
					if ($bdepth_frac != "") {
						$frac = explode("/", $bdepth_frac);
						$numerator = $frac[0];
						$denominator = $frac[1];
						$box_height = (float)$bheight + (float)$numerator / (float)$denominator;
					} else {
						$frac = "";
						$box_height = $bheight;
					}

					$blength = $box_length;
					$bwidth = $box_width;
					$bdepth = $box_height;

					$bcubicfootage = number_format(round(((floatval($blength) + floatval($blength_frac_conv)) * (floatval($bwidth) + floatval($bwidth_frac_conv)) * (floatval($bheight) + floatval($bdepth_frac_conv))) / 1728, 2), 2);
				} else {

					$blength = $sku_val["blength_max"];
					$bwidth = $sku_val["bwidth_max"];
					$bdepth = $sku_val["bheight_max"];

					$blength_min = $sku_val["blength_min"];
					$blength_min = preg_replace("(\n)", "<BR>", $blength_min);
					$blength_max = $sku_val["blength_max"];
					$blength_max = preg_replace("(\n)", "<BR>", $blength_max);
					$bwidth_min = $sku_val["bwidth_min"];
					$bwidth_min = preg_replace("(\n)", "<BR>", $bwidth_min);
					$bwidth_max = $sku_val["bwidth_max"];
					$bwidth_max = preg_replace("(\n)", "<BR>", $bwidth_max);
					$bheight_min = $sku_val["bheight_min"];
					$bheight_min = preg_replace("(\n)", "<BR>", $bheight_min);
					$bheight_max = $sku_val["bheight_max"];
					$bheight_max = preg_replace("(\n)", "<BR>", $bheight_max);

					$bcubicfootage_min = (($blength_min) * ($bwidth_min) * ($bheight_min)) / 1728;
					$item_bcubicfootage_min = number_format($bcubicfootage_min, 2);
					$bcubicfootage_max = (($blength_max) * ($bwidth_max) * ($bheight_max)) / 1728;
					$item_bcubicfootage_max = number_format($bcubicfootage_max, 2);
					$bcubicfootage = $item_bcubicfootage_min . " - " . $item_bcubicfootage_max;

					if ($bheight_min == $bheight_max) {
						//$boxheight = rtrim(number_format($bheight_max, 2), '0'). chr(34);
						$boxheight = floatval($bheight_max) . chr(34);
					} else {
						$boxheight = floatval($bheight_min) . "-" . floatval($bheight_max) . chr(34);
						//$boxheight = rtrim(number_format($bheight_min, 2), '0') . "-". rtrim(number_format($bheight_max, 2), '0'). chr(34);
					}

					if ($bwidth_min == $bwidth_max) {
						$boxwidth = $bwidth_max;
					} else {
						$boxwidth = $bwidth_min . "-" . $bwidth_max;
					}

					if ($blength_min == $blength_max) {
						$boxlength = $blength_max;
					} else {
						$boxlength = $blength_min . "-" . $blength_max;
					}
				}


				//$img = "../boxpics_thumbnail/".$sku_val['bpic_1'];
				$img = $sku_val['bpic_1'];

				$boxes_per_trailer = $sku_val['boxes_per_trailer'];
				$flyer_notes = $sku_val['flyer_notes'];
				$last_modified_date = $sku_val['last_modified_date'];

				$loop_boxes_txtafterPo = $sku_val['after_po'];
				$box_warehouse_id = $sku_val['box_warehouse_id'];

				if ($box_warehouse_id == 238) {
					$frequency = number_format($sku_val['expected_loads_per_mo'], 0);
				} else {
					$frequency = number_format($lastmonth_qry_array[$loop_id], 0);
				}

				$date_dt = new DateTime(); //Today
				$dateMinus12 = $date_dt->modify("-12 months"); // Last day 12 months ago

				$sales_order_qty = 0;
				// Org qry
				$dt_so_item = "SELECT sum(qty) as sumqty FROM loop_salesorders ";
				$dt_so_item .= " inner join loop_transaction_buyer on loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
				//(STR_TO_DATE(loop_salesorders.so_date, '%m/%d/%Y') between '" . $dateMinus12->format('Y-m-d') . "' and '" . date("Y-m-d") . "') 
				$dt_so_item .= " where loop_transaction_buyer.mark_unavailable = 1 ";
				if (isset($_REQUEST['warehouse']) && $_REQUEST['warehouse'] != "all") {
					$dt_so_item .= " and location_warehouse_id = '" . $_REQUEST['warehouse'] . "' ";
				}
				$dt_so_item .= " and box_id = " . $loop_id . " and loop_transaction_buyer.bol_create = 0 and loop_transaction_buyer.ignore = 0 ";
				//and loop_transaction_buyer.Preorder=0 and 
				db();
				//echo $dt_so_item;
				//exit;
				$dt_res_so_item = db_query($dt_so_item);
				$so_item_row = array_shift($dt_res_so_item);
				if ($so_item_row["sumqty"] > 0) {
					$sales_order_qty = $so_item_row["sumqty"];
				}

				$actual_po = $dt_view_row["actual_qty_calculated"] - $sales_order_qty;
				//echo $loop_id . " | " . $actual_po  . " | " . $dt_view_row["actual_qty_calculated"]  . " | " .  $sales_order_qty  . "<br>";

				if ($per_trailer > 0) {
					$load_av_after_po = round(($actual_po / $per_trailer) * 100, 2);
				}

				$reccnt = 0;
				if ($sales_order_qty > 0) {
					$reccnt = $sales_order_qty;
				}

				if ($availale_selectval == "actual") {
					$txt_after_po = $dt_view_row["actual_qty_calculated"] - $sales_order_qty;
				} else {
					$txt_after_po = $dt_view_row["actual_qty_calculated"];
				}

				//echo $dt_view_row["b2b_id"] . "|" . $dt_view_row["actual_qty_calculated"] . " | " . $sales_order_qty . " | " . $txt_after_po . " | " . $ftl_qty . "|" . $percent_per_load . "<br>";
				$expected_loads_per_mo_to_display = 0;
				if (floatval($boxes_per_trailer) != 0) {
					$expected_loads_per_mo_to_display = round($txt_after_po / floatval($boxes_per_trailer), 2);
				}
				//$expected_loads_per_mo_to_display = round($txt_after_po / $boxes_per_trailer, 2);

				$qtynumbervalue = 0;
				$qtynumbervalueorg = 0;
				/*if ($txt_after_po == 0 && $expected_loads_per_mo_to_display == 0) {
					$colorvalueQty = "<font color='black'>" . number_format($txt_after_po,0) . "</font></td>";
					$qtynumbervalue = str_replace(",", "" ,number_format($txt_after_po,0));
				}else if ($txt_after_po >= $boxes_per_trailer) {
					$colorvalueQty = "<font color='green'>".number_format($txt_after_po,0)."</font></td>";
					$qtynumbervalue = str_replace(",", "" ,number_format($txt_after_po,0));
				} else { 
					$colorvalueQty = "<font color='black'>".number_format($txt_after_po,0)."</font></td>";
					$qtynumbervalue = str_replace(",", "" ,number_format($txt_after_po,0));
				}
				$qtynumbervalueorg = $txt_after_po;*/


				$to_show_rec_main2 = "y";
				$to_show_rec_main3 = "y";
				$include_FTL_Rdy_Now_Only = 1;
				$to_show_rec_main4 = "y";
				$to_show_rec_main5 = "y";


				/*if ($last_modified_date != "") {
						$days = number_format((strtotime(date("Y-m-d")) - strtotime($last_modified_date)) / (60 * 60 * 24));
						$updated_on_grid = date("d-m-Y", strtotime($last_modified_date)) . " ( " . $days . " days ago)";
						//if (isset($_REQUEST['view_type']) && $_REQUEST['view_type'] == "list_view") {
							//$updated_on = date("d-m-Y", strtotime($last_modified_date)) . " <br> ( " . $days . " days ago)";
						//}
						$updated_on = date("d-m-Y", strtotime($last_modified_date)) . " <br> ( " . $days . " days ago)";
					}*/

				// Desciption Hover Start

				$b_urgent = "No";
				$contracted = "No";
				$prepay = "No";
				$ship_ltl = "No";
				$ship_ltl_int = 0;
				if ($dt_view_row["box_urgent"] == 1) {
					$b_urgent = "Yes";
				}
				if ($dt_view_row["contracted"] == 1) {
					$contracted = "Yes";
				}
				if ($dt_view_row["prepay"] == 1) {
					$prepay = "Yes";
				}
				if ($dt_view_row["ship_ltl"] == 1) {
					$ship_ltl = "Yes";
					$ship_ltl_int = 1;
				}

				$ownername = "";
				$b2bid = 0;
				$companyID = 0;
				if (isset($_REQUEST["shown_in_client_flg"]) && $_REQUEST["shown_in_client_flg"] == 1) {
				} else {
					if ($vendor_b2b_rescue > 0) {
						db();
						$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
						$query = db_query($q1);
						while ($fetch = array_shift($query)) {
							$supplier_name = get_nickname_val($fetch['company_name'], $fetch["b2bid"]);
							$b2bid = $fetch["b2bid"];
							$comqry = "select employees.initials, companyInfo.ID from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
							db_b2b();
							$comres = db_query($comqry);
							while ($comrow = array_shift($comres)) {
								$ownername = $comrow["initials"];
								$companyID = $comrow['ID'];
							}
						}
					}
				}

				$next_load_available_date = $sku_val["next_load_available_date"];

				$lead_time = $dt_view_row["lead_time"];

				$rec_found_box = "n";
				$after_po_val_tmp = 0;
				/*db_b2b();
				$dt_view_qry = "SELECT afterpo from tmp_inventory_list_set2 where trans_id = " . $dt_view_row["loops_id"] . " order by warehouse, type_ofbox, Description";
				$dt_view_res_box = db_query($dt_view_qry);
				while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
					$rec_found_box = "y";
					$after_po_val_tmp = $dt_view_res_box_data["afterpo"];
				}
				db();*/

				$txt_after_po_new = $sku_val["after_po"];
				if ($box_warehouse_id == 238) {
					$txt_after_po_new = $sku_val["after_po"];
				} else {
					if ($rec_found_box == "n") {
						$txt_after_po_new = $sku_val["after_po"];
					} else {
						$txt_after_po_new = $after_po_val_tmp;
					}
				}

				$getEstimatedNextLoad = getEstimatedNextLoad_New($dt_view_row["loops_id"], $box_warehouse_id, $next_load_available_date, $lead_time, $lead_time, $txt_after_po_new, $boxes_per_trailer, $sku_val['expected_loads_per_mo'], $status_key, 'no');
				$lead_time_for_FTL = $getEstimatedNextLoad;


				$b2b_ulineDollar = round($dt_view_row["ulineDollar"]);
				$b2b_ulineCents = $dt_view_row["ulineCents"];
				$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
				$b2b_fob = "$" . number_format($b2b_fob, 2);

				$b2b_costDollar = round($dt_view_row["costDollar"]);
				$b2b_costCents = $dt_view_row["costCents"];
				$b2b_cost = $b2b_costDollar + $b2b_costCents;
				$b2b_cost = "$" . number_format($b2b_cost, 2);

				$ship_cdata_ltl = $sku_val['ship_ltl'] == 1 ? 'Y' : '';
				//$customer_pickup_allowed = $sku_val['customer_pickup_allowed'] == 1 ? 'Y' : '';

				$tipStr = "<b>Notes:</b> " . $dt_view_row['notes'] . "<br>";
				if ($dt_view_row['date'] != "0000-00-00") {
					$tipStr .= "<b>Notes Date:</b> " . date("m/d/Y", strtotime($dt_view_row['date'])) . "<br>";
				} else {
					$tipStr .= "<b>Notes Date:</b> <br>";
				}
				$tipStr .= "<b>Urgent:</b> " . $b_urgent . "<br>";
				$tipStr .= "<b>Contracted:</b> " . $contracted . "<br>";
				$tipStr .= "<b>Prepay:</b> " . $prepay . "<br>";
				$tipStr .= "<b>Can Ship LTL?</b> " . $ship_ltl . "<br>";

				$tipStr .= "<b>Qty Avail:</b> " . number_format($txt_after_po, 0) . "<br>";
				$tipStr .= "<b>Lead Time for FTL:</b> " . $lead_time_for_FTL . "<br>";
				$tipStr .= "<b>Qty Available, Next 3 Months:</b> " . $dt_view_row["expected_loads_per_mo"] . "<br>";
				$tipStr .= "<b>B2B Status:</b> " . $status_org . "<br>";
				$tipStr .= "<b>Supplier Relationship Owner:</b> " . $ownername . "<br>";
				$tipStr .= "<b>B2B ID#:</b> " . $dt_view_row["b2b_id"] . "<br>";
				$tipStr .= "<b>Description:</b> " . $dt_view_row["description"] . "<br>";
				$tipStr .= "<b>Supplier:</b> " .  $supplier_name . "<br>";
				$tipStr .= "<b>Ship From:</b> " . $ship_from . "<br>";
				$tipStr .= "<b>Miles From:</b> " . isset($miles_from) . "<br>";
				$tipStr .= "<b>Per Pallet:</b> " . $sku_val['bpallet_qty'] . "<br>";
				$tipStr .= "<b>Per Truckload:</b> " . $sku_val['boxes_per_trailer'] . "<br>";
				$tipStr .= "<b>Min FOB:</b> " . $b2b_fob . "<br>";
				$tipStr .= "<b>B2B Cost:</b> " . $b2b_cost . "<br>";
				$tipStr .= "<b>Ship Ltl:</b> " . $ship_cdata_ltl . "<br>";
				$tipStr .= "<b>Custome Pickup:</b> " . $pickup_cdata_allowed . "<br>";

				$description_hover_notes = str_replace("'", "\'", $tipStr);
				$description_hover_notes = str_replace('"', " inch ", $tipStr);
				// $description_hover_notes = str_replace("'", "'" , $tipStr);

				// Desciption Hover End

				// To get the Shipsinweek
				$no_of_loads = 0;
				$shipsinweek = "";
				$to_show_rec = "";
				$total_no_of_loads = 0;
				//echo "shown_in_client_flg =" . $_REQUEST["shown_in_client_flg"] . "<br>";
				if (isset($_REQUEST["timing"]) && $_REQUEST["timing"] == 5) {
					$to_show_rec = "";
					$next_2_week_date = date("Y-m-d", strtotime("+2 week"));
					$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id =" . $loop_id . " and inactive_delete_flg = 0 
					and (load_available_date <= '" . $next_2_week_date . "') order by load_available_date";
					//echo $dt_view_qry . "<br>";
					db();
					$dt_view_res_box = db_query($dt_view_qry);
					while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
						if ($dt_view_res_box_data["trans_rec_id"] == 0) {
							$no_of_loads = $no_of_loads + 1;
							$to_show_rec = "y";
						}
						$total_no_of_loads = $total_no_of_loads + 1;

						if ($no_of_loads == 1) {
							$now_date = time();
							$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
							$datediff = $next_load_date - $now_date;
							$shipsinweek_org = round($datediff / (60 * 60 * 24));
							//echo $inv["lead_time"] . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
							//if (isset($inv["lead_time"]) > $shipsinweek_org) {
							if ($inv["lead_time"] > $shipsinweek_org) {
								$shipsinweekval = $inv["lead_time"];
							} else {
								$shipsinweekval = $shipsinweek_org;
							}
							if ($shipsinweekval == 0) {
								$shipsinweekval = 1;
							}
							if ($shipsinweekval >= 10) {
								$shipsinweek = round($shipsinweekval / 7) . " weeks";
							}
							if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
								$shipsinweek = $shipsinweekval . " days";
							}
							if ($shipsinweekval == 1) {
								$shipsinweek = $shipsinweekval . " day";
							}
						}
					}
				}

				// Can ship in 4 weeks
				else if (isset($_REQUEST["timing"]) && $_REQUEST["timing"] == 10) {
					$to_show_rec = "";
					$next_4_week_date = date("Y-m-d", strtotime("+4 week"));
					$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id =" . $loop_id . " and inactive_delete_flg = 0 
					and (load_available_date <= '" . $next_4_week_date . "') order by load_available_date";
					//echo $dt_view_qry . "<br>";
					db();
					$dt_view_res_box = db_query($dt_view_qry);
					while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
						if ($dt_view_res_box_data["trans_rec_id"] == 0) {
							$no_of_loads = $no_of_loads + 1;
							$to_show_rec = "y";
						}
						$total_no_of_loads = $total_no_of_loads + 1;

						if ($no_of_loads == 1) {
							$now_date = time();
							$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
							$datediff = $next_load_date - $now_date;
							$shipsinweek_org = round($datediff / (60 * 60 * 24));
							//echo $inv["lead_time"] . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
							if ($inv["lead_time"] > $shipsinweek_org) {
								$shipsinweekval = $inv["lead_time"];
							} else {
								$shipsinweekval = $shipsinweek_org;
							}
							if ($shipsinweekval == 0) {
								$shipsinweekval = 1;
							}
							if ($shipsinweekval >= 10) {
								$shipsinweek = round($shipsinweekval / 7) . " weeks";
							}
							if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
								$shipsinweek = $shipsinweekval . " days";
							}
							if ($shipsinweekval == 1) {
								$shipsinweek = $shipsinweekval . " day";
							}
						}
					}
				}
				//Ready to ship whenever
				else if (isset($_REQUEST["timing"]) && $_REQUEST["timing"] == 6) {
					$to_show_rec = "y";
				}
				//Can ship next month a date range of the 1st day of next month to last day of next month 
				else if (isset($_REQUEST["timing"]) && $_REQUEST["timing"] == 7) {
					$to_show_rec = "";
					$next_month_date = date("Y-m-t");
					$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id =" . $loop_id . " and inactive_delete_flg = 0 
					and (load_available_date <= '" . $next_month_date . "')
					order by load_available_date";
					//echo $dt_view_qry . "<br>";
					db();
					$dt_view_res_box = db_query($dt_view_qry);
					while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
						if ($dt_view_res_box_data["trans_rec_id"] == 0) {
							$no_of_loads = $no_of_loads + 1;
							$to_show_rec = "y";
						}
						$total_no_of_loads = $total_no_of_loads + 1;

						if ($no_of_loads == 1) {
							$now_date = time();
							$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
							$datediff = $next_load_date - $now_date;
							$shipsinweek_org = round($datediff / (60 * 60 * 24));
							//echo $inv["lead_time"] . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
							if ($inv["lead_time"] > $shipsinweek_org) {
								$shipsinweekval = $inv["lead_time"];
							} else {
								$shipsinweekval = $shipsinweek_org;
							}
							if ($shipsinweekval == 0) {
								$shipsinweekval = 1;
							}
							if ($shipsinweekval >= 10) {
								$shipsinweek = round($shipsinweekval / 7) . " weeks";
							}
							if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
								$shipsinweek = $shipsinweekval . " days";
							}
							if ($shipsinweekval == 1) {
								$shipsinweek = $shipsinweekval . " day";
							}
						}
					}
					//echo "in step 7 " . $to_show_rec . "<br>";	
				}



				//Enter ship by date = Take user input of 1 date
				else if (isset($_REQUEST["timing"]) && $_REQUEST["timing"] == 9 && $_REQUEST["selected_date"] != '') {
					$to_show_rec = "";
					$next_month_date = date("Y-m-d", strtotime($_REQUEST["selected_date"]));
					$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id =" . $loop_id . " and inactive_delete_flg = 0 
					and load_available_date <= '" . $next_month_date . "' order by load_available_date";
					db();
					$dt_view_res_box = db_query($dt_view_qry);
					while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
						if ($dt_view_res_box_data["trans_rec_id"] == 0) {
							$no_of_loads = $no_of_loads + 1;
							$to_show_rec = "y";
						}
						$total_no_of_loads = $total_no_of_loads + 1;

						if ($no_of_loads == 1) {
							$now_date = time();
							$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
							$datediff = $next_load_date - $now_date;
							$shipsinweek_org = round($datediff / (60 * 60 * 24));
							if ($inv["lead_time"] > $shipsinweek_org) {
								$shipsinweekval = $inv["lead_time"];
							} else {
								$shipsinweekval = $shipsinweek_org;
							}
							if ($shipsinweekval == 0) {
								$shipsinweekval = 1;
							}
							if ($shipsinweekval >= 10) {
								$shipsinweek = round($shipsinweekval / 7) . " weeks";
							}
							if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
								$shipsinweek = $shipsinweekval . " days";
							}
							if ($shipsinweekval == 1) {
								$shipsinweek = $shipsinweekval . " day";
							}
						}
					}
				}

				// Ready Now 
				else if (isset($_REQUEST["timing"]) && $_REQUEST["timing"] == 4) {
					$next_2_week_date = date("Y-m-d", strtotime("+3 day"));
					$to_show_rec = "";
					$dt_view_qry = "SELECT load_available_date, trans_rec_id  from loop_next_load_available_history where inv_loop_id =" . $loop_id . " and inactive_delete_flg = 0 
					and (load_available_date <= '" . $next_2_week_date . "') order by load_available_date";
					db();
					$dt_view_res_box = db_query($dt_view_qry);
					while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
						if ($dt_view_res_box_data["trans_rec_id"] == 0) {
							$no_of_loads = $no_of_loads + 1;
							$to_show_rec = "y";
						}
						$total_no_of_loads = $total_no_of_loads + 1;

						if ($no_of_loads == 1) {
							$now_date = time();
							$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
							$datediff = $next_load_date - $now_date;
							$shipsinweek_org = round($datediff / (60 * 60 * 24));
							if (($inv["lead_time"] > $shipsinweek_org) || ($shipsinweek_org < 0)) {
								$shipsinweekval = $inv["lead_time"];
							} else {
								$shipsinweekval = $shipsinweek_org;
							}
							if ($shipsinweekval == 0) {
								$shipsinweekval = 1;
							}
							if ($shipsinweekval >= 10) {
								$shipsinweek = round($shipsinweekval / 7) . " weeks";
							}
							if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
								$shipsinweek = $shipsinweekval . " days";
							}
							if ($shipsinweekval == 1) {
								$shipsinweek = $shipsinweekval . " day";
							}
						}
					}
				} else {
					$to_show_rec = "";
					$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id =" . $loop_id . " and inactive_delete_flg = 0 
					order by load_available_date";
					db();
					$dt_view_res_box = db_query($dt_view_qry);
					while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
						if ($dt_view_res_box_data["trans_rec_id"] == 0) {
							$no_of_loads = $no_of_loads + 1;
							$to_show_rec = "y";
						}
						$total_no_of_loads = $total_no_of_loads + 1;

						if ($no_of_loads == 1) {
							$now_date = time();
							$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
							$datediff = $next_load_date - $now_date;
							$shipsinweek_org = round($datediff / (60 * 60 * 24));
							if ((isset($inv["lead_time"]) > $shipsinweek_org) || ($shipsinweek_org < 0)) {
								$shipsinweekval = isset($inv["lead_time"]);
							} else {
								$shipsinweekval = $shipsinweek_org;
							}

							if ($shipsinweekval == 0) {
								$shipsinweekval = 1;
							}

							if ($shipsinweekval >= 10) {
								$shipsinweek = round($shipsinweekval / 7) . " weeks";
							}
							if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
								$shipsinweek = $shipsinweekval . " days";
							}
							if ($shipsinweekval == 1) {
								$shipsinweek = $shipsinweekval . " day";
							}
						}
					}
				}
				$no_of_loads_str = "";
				$first_load_can_ship_in = $shipsinweek;
				if ($total_no_of_loads == 1) {
					$no_of_loads_str = " Load";
				}
				if ($total_no_of_loads > 1) {
					$no_of_loads_str = " Loads";
				}
				$loads = $no_of_loads . " of " . $total_no_of_loads . "" . $no_of_loads_str;


				//New logic 1156 Quantity Available Now 
				$after_actual_inventory = 0;
				$quantity_availableValue_new = "";

				$quantity_availableValue_new1 = getQtyAvNow($loop_id, $box_warehouse_id, $lead_time, $ship_ltl_int, $dt_view_row["after_actual_inventory"], $dt_view_row["quantity"], $dt_view_row["actual_qty_calculated"], $sales_order_qty);
				if (($actual_po > $quantity_availableValue_new1) && ($actual_po > 0)) {
					$quantity_availableValue_new = $actual_po;
				} else {
					$quantity_availableValue_new = $quantity_availableValue_new1;
				}



				$percent_per_load = 0;
				if ($ftl_qty > 0) {
					$percent_per_load = number_format(floatval((str_replace(",", "", $quantity_availableValue_new) / str_replace(",", "", $ftl_qty))), 2);
				}

				$td_bg = "";
				if (isset($_REQUEST["shown_in_client_flg"]) && $_REQUEST["shown_in_client_flg"] == 1) {
					if ((str_replace(",", "", $quantity_availableValue_new) >= str_replace(",", "", $ftl_qty)) && (str_replace(",", "", $ftl_qty) > 0)) {
						$td_bg = "green";
					}
				} else {
					if (($actual_qty >= $boxes_per_trailer) && ($boxes_per_trailer > 0)) {
						$td_bg = "yellow";
					}
				}

				$to_show_rec_main1 = "";
				if ($quantity_availableValue_new > 0) {
					$to_show_rec_main1 = "y";
				}

				if (isset($_REQUEST['include_FTL_Rdy_Now_Only']) && $_REQUEST['include_FTL_Rdy_Now_Only'] == 1) {

					$include_FTL_Rdy_Now_Only = 0;
					$to_show_rec_main3 = "";
					//if (($txt_after_po >= $boxes_per_trailer) && ($txt_after_po >0 && $boxes_per_trailer > 0)){
					if ((str_replace(",", "", $quantity_availableValue_new) >= str_replace(",", "", $ftl_qty)) && (str_replace(",", "", $ftl_qty) > 0)) {
						$to_show_rec_main3 = "y";
						$include_FTL_Rdy_Now_Only = 1;
					}
				}

				//Filter by 'Qty Avail', default is unchecked, if user checks box, then adjust the default from 'Qty Avail' > 0 to no filter instead.
				if (isset($_REQUEST['include_sold_out_items']) && $_REQUEST['include_sold_out_items'] == 1) {
					$to_show_rec_main1 = "y";
				}

				//New logic changes
				$box_lead_time = getLeadTime($loop_id, $quantity_availableValue_new, $lead_time);

				$td_leadtime_bg_color = "";

				if (isset($_REQUEST["shown_in_client_flg"]) && $_REQUEST["shown_in_client_flg"] == 1) {
					$position = strpos($box_lead_time, "Day");
					if ($position != false) {
						$box_lead_time_number = (int)$box_lead_time;

						if ($box_lead_time_number <= 7) {
							$td_leadtime_bg_color = " color: green;";
						}
					}
				}

				$lead_time_days = 0;
				if ($box_lead_time != "") {
					$lead_time_for_FTL_org = $box_lead_time;
					$tmppos_1 = strpos($lead_time_for_FTL_org, 'Now');
					if ($tmppos_1 != false) {
						$lead_time_days = 0;
					}

					if ($lead_time_for_FTL_org == 'Now') {
						$lead_time_days = -1;
					}

					$tmppos_1 = strpos($lead_time_for_FTL_org, 'Never (sell the');
					if ($tmppos_1 != false) {
						$lead_time_days = 8000; //$lead_time;
					}

					$lead_time_for_FTL_org = str_replace("<font color=green>", "", $lead_time_for_FTL_org);
					$lead_time_for_FTL_org = str_replace("</font>", "", $lead_time_for_FTL_org);

					$lead_time_for_FTL_org = str_replace("<span color=green>", "", $lead_time_for_FTL_org);
					$lead_time_for_FTL_org = str_replace("</span>", "", $lead_time_for_FTL_org);

					$lead_time_arr = explode(" ", $lead_time_for_FTL_org, 2);

					if ($lead_time_arr[1] == 'Week') {
						$lead_time_days = (int)$lead_time_arr[0] * 7;
					}
					if ($lead_time_arr[1] == 'Weeks') {
						$lead_time_days = (int)$lead_time_arr[0] * 7;
					}
					if ($lead_time_arr[1] == 'Day') {
						$lead_time_days = $lead_time_arr[0];
					}
					if ($lead_time_arr[1] == 'Days') {
						$lead_time_days = $lead_time_arr[0];
					}
				}
			}


			if (isset($_REQUEST["all_work_as_a_kit_box_data"]) && $_REQUEST["all_work_as_a_kit_box_data"] != "") {
				$to_show_rec_main4 = "n";
				if ($loop_data_found == "yes") {
					$to_show_rec_main4 = "y";
				}
			}

			if (isset($_REQUEST["box_type"]) && strcasecmp($_REQUEST["box_type"], "Shipping") == 0 || strcasecmp($_REQUEST["box_type"], "Pallets") == 0) {
				$to_show_rec = "y";
			}

			if (isset($_REQUEST["fav_inv"]) && $_REQUEST["fav_inv"] == 1 || isset($_REQUEST["fav_prev_order"]) && $_REQUEST["fav_prev_order"] == 1) {
				$to_show_rec = "y";
				$to_show_rec_main1 = "y";
				$to_show_rec_main2 = "y";
				$to_show_rec_main3 = "y";
				$to_show_rec_main4 = "y";
				$include_FTL_Rdy_Now_Only = 1;
			}

			if ($to_show_rec == "y" && $to_show_rec_main1 == "y" && $to_show_rec_main2 == "y" && $to_show_rec_main3 == "y" && $to_show_rec_main4 == "y") {
				//if ($dt_view_row["location_zip"] != "" && $enter_zipcode != "") {
				//echo "Yes At Line 2126";
				if (!empty($dt_view_row["location_zip"]) && !empty($enter_zipcode)) {
					/*$locLat = $dt_view_row["location_zip_latitude"];
					$locLong = $dt_view_row["location_zip_longitude"];

					$distLat = ($shipLat - $locLat) * 3.141592653 / 180;
					$distLong = ($shipLong - $locLong) * 3.141592653 / 180;

					$distA = Sin($distLat / 2) * Sin($distLat / 2) + Cos($shipLat * 3.14159 / 180) * Cos($locLat * 3.14159 / 180) * Sin($distLong / 2) * Sin($distLong / 2);
					$distC = 2 * atan2(sqrt($distA), sqrt(1 - $distA));
					*/

					$locLat = $dt_view_row["location_zip_latitude"];
					$locLong = $dt_view_row["location_zip_longitude"];

					$distLat = deg2rad($shipLat - $locLat);
					$distLong = deg2rad($shipLong - $locLong);

					$distA = sin($distLat / 2) * sin($distLat / 2) + cos(deg2rad($shipLat)) * cos(deg2rad($locLat)) * sin($distLong / 2) * sin($distLong / 2);
					$distC = 2 * atan2(sqrt($distA), sqrt(1 - $distA));
				}
				$miles_from = (int) (6371 * $distC * 0.621371192);
				//$miles_from = (int) (6371 * $distC * .621371192);
				//echo "miles_from = " . $miles_from . "<br>";

				if ($miles_from <= 250) {
					$miles_away_color = "green";
				}
				if (($miles_from <= 550) && ($miles_from > 250)) {
					$miles_away_color = "#FF9933";
				}
				if (($miles_from > 550)) {
					$miles_away_color = "red";
				}

				if ($enter_zipcode == "") {
					$miles = "<font color='red'>Enter Zipcode</font>";
				} else {
					$miles = "<font color='$miles_away_color'>" . $miles_from . " mi away</font>";
				}

				$b2b_ulineDollar = round($dt_view_row["ulineDollar"]);
				$b2b_ulineCents = $dt_view_row["ulineCents"];
				if ($b2b_ulineDollar != "" || $b2b_ulineCents != "") {
					$price = "$" . number_format($b2b_ulineDollar + $b2b_ulineCents, 2);
				} else {
					$price = "$0.00";
				}
				$minfob = str_replace(",", "", $price);
				$minfob = str_replace("$", "", $minfob);

				$frequency_ftl = 0;
				if ($ftl_qty > 0) {
					$frequency_ftl = (float)str_replace(",", "", $frequency) / (float)str_replace(",", "", $ftl_qty);
				}
				$favorite = 0;
				if (isset($_REQUEST["shown_in_client_flg"]) && $_REQUEST["shown_in_client_flg"] == 1) {
					//echo "SELECT fav_status from b2b_inventory_gaylords_favorite where company_id = $companyID && b2b_id = $b2b_id && user_id = '".$_REQUEST['user_id']."'";
					//die;
					db();
					//echo "SELECT fav_status from b2b_inventory_gaylords_favorite where company_id = $companyID and b2b_id = $b2b_id and user_id = $user_id";
					$select_fav_status = db_query("SELECT fav_status from b2b_inventory_gaylords_favorite where company_id = $companyID and b2b_id = $b2b_id and user_id = $user_id LIMIT 1");
					if (tep_db_num_rows($select_fav_status) > 0) {
						$favorite = array_shift($select_fav_status)["fav_status"];
					}
					//echo "<bR> Here ";
				}

				if ($_REQUEST["min_price"] == 0 && $_REQUEST["max_price"] == 500 && $_REQUEST["timing"] == "") {
					$products_array[] = array(
						'ftl_qty' => $ftl_qty,
						'colorvalueQty' => number_format($quantity_availableValue_new),
						'qtynumbervalue' => $quantity_availableValue_new,
						'qtynumbervalueorg' => $quantity_availableValue_new,
						'img' => $img,
						'inv_Active' => $dt_view_row["active"],
						'inv_b2b_status' => $dt_view_row["invb2b_status"],
						'inv_box_type' => $box_type_from_qry,
						'boxheight' => $boxheight,
						'boxwidth' => $bwidth,
						'boxlength' => $blength,
						'boxwall' => $boxwall,
						'box_lead_time' => $box_lead_time,
						'td_leadtime_bg_color' => $td_leadtime_bg_color,
						'box_desc_bottom' => $box_desc_bottom,
						'lead_time_days' => $lead_time_days,
						'description' => $description,
						'description_grid' => $description_grid,
						'status' => $status,
						'ship_from' => $ship_from,
						'system_description' => $system_description,
						'flyer_notes' => $flyer_notes,
						'lead_time_of_FTL' => $lead_time_for_FTL,
						'distance' => $miles,
						'distance_sort' => $miles_from,
						'ltl' => $ltl,
						'td_bg' => $td_bg,
						'customer_pickup' => $customer_pickup_allowed,
						'supplier_owner' => $supplier_owner,
						/*'updated_on' => $updated_on,
							'updated_on_grid' =>  $updated_on_grid,*/
						'price' => $price,
						'loads' => $loads,
						'shipsinweekval' => $shipsinweekval,
						'first_load_can_ship_in' => $first_load_can_ship_in,
						'b2b_id' => $b2b_id,
						'loop_id' => $loop_id,
						'box_warehouse_id' => $box_warehouse_id,
						'companyID' => $companyID,
						'minfob' => $minfob,
						'txtaddress' => $dropoff_add1,
						'txtaddress2' => $dropoff_add2,
						'txtcity' => $dropoff_city,
						'txtstate' => $dropoff_state,
						'txtcountry' => $dropoff_country,
						'txtzipcode' => $dropoff_zip,
						'added_on' => $added_on,
						'supplier_name' => $supplier_name,
						'shipFromLocation' => $shipFromLocation,
						//'percent_per_load' => $percent_per_load,
						'percent_per_load' => $percent_per_load,
						'actual_qty' => $actual_qty,
						'actual_po' => $actual_po,
						'load_av_after_po' => $load_av_after_po,
						'frequency' => $frequency,
						'frequency_sort' => str_replace(",", "", $frequency),
						'frequency_ftl' => $frequency_ftl,
						'vendor_b2b_rescue' => $vendor_b2b_rescue,
						'reccnt' => $reccnt,
						'description_hover_notes' => $description_hover_notes,
						'nickname' => $nickname,
						'length' => $blength,
						'width' => $bwidth,
						'depth' => $bdepth,
						'cubicfootage' => $bcubicfootage,
						'loop_id_encrypt_str' => urlencode(encrypt_password($loop_id)),
						'favorite' => $favorite,
						'include_FTL_Rdy_Now_Only' => $include_FTL_Rdy_Now_Only,
						'uniform_mixed_load_txt' => $uniform_mixed_load_txt,
						'product_shape' => $product_shape,
						'product_bwall' => $product_bwall,
						'product_bottom2' => $product_bottom2,
						'product_bottom3' => $product_bottom3,
						'product_bottom4' => $product_bottom4,
						'product_bottom5' => $product_bottom5,
						'product_top1' => $product_top1,
						'product_top2' => $product_top2,
						'product_top3' => $product_top3,
						'product_top4' => $product_top4,
						'product_vents' => $product_vents,
						'product_grade' => $product_grade,
						'product_printing' => $product_printing,
						'product_ect_burst' => $product_ect_burst,
						'product_ect_detail' => $product_ect_detail,
						'product_material' => $product_material,
						'product_entry' => $product_entry,
						'product_structure' => $product_structure,
						'product_heat_treated' => $product_heat_treated,
						'product_type_sh' => $product_type_sh,
					);
				} else {
					if (isset($_REQUEST["min_price"]) && $_REQUEST["min_price"] != 0 || isset($_REQUEST['max_price']) && $_REQUEST["max_price"] != 500) {
						if ($minfob >= $_REQUEST["min_price"] && $minfob <= $_REQUEST["max_price"]) {
							$products_array[] = array(
								'ftl_qty' => $ftl_qty,
								'colorvalueQty' => number_format($quantity_availableValue_new),
								'qtynumbervalue' => $quantity_availableValue_new,
								'qtynumbervalueorg' => $quantity_availableValue_new,
								'img' => $img,
								'inv_Active' => $dt_view_row["active"],
								'inv_b2b_status' => $dt_view_row["invb2b_status"],
								'inv_box_type' => $box_type_from_qry,
								'boxheight' => $boxheight,
								'boxwidth' => $bwidth,
								'boxlength' => $blength,
								'boxwall' => $boxwall,
								'box_lead_time' => $box_lead_time,
								'td_leadtime_bg_color' => $td_leadtime_bg_color,
								'box_desc_bottom' => $box_desc_bottom,
								'lead_time_days' => $lead_time_days,
								'description' => $description,
								'description_grid' => $description_grid,
								'status' => $status,
								'ship_from' => $ship_from,
								'system_description' => $system_description,
								'flyer_notes' => $flyer_notes,
								'lead_time_of_FTL' => $lead_time_for_FTL,
								'distance' => $miles,
								'distance_sort' => $miles_from,
								'ltl' => $ltl,
								'td_bg' => $td_bg,
								'customer_pickup' => $customer_pickup_allowed,
								'supplier_owner' => $supplier_owner,
								/*'updated_on' => $updated_on,
									'updated_on_grid' =>  $updated_on_grid,*/
								'price' => $price,
								'loads' => $loads,
								'shipsinweekval' => $shipsinweekval,
								'first_load_can_ship_in' => $first_load_can_ship_in,
								'b2b_id' => $b2b_id,
								'loop_id' => $loop_id,
								'box_warehouse_id' => $box_warehouse_id,
								'companyID' => $companyID,
								'minfob' => $minfob,
								'txtaddress' => $dropoff_add1,
								'txtaddress2' => $dropoff_add2,
								'txtcity' => $dropoff_city,
								'txtstate' => $dropoff_state,
								'txtcountry' => $dropoff_country,
								'txtzipcode' => $dropoff_zip,
								'added_on' => $added_on,
								'supplier_name' => $supplier_name,
								'shipFromLocation' => $shipFromLocation,
								'percent_per_load' => $percent_per_load,
								'actual_qty' => $actual_qty,
								'actual_po' => $actual_po,
								'load_av_after_po' => $load_av_after_po,
								'frequency' => $frequency,
								'frequency_sort' => str_replace(",", "", $frequency),
								'frequency_ftl' => $frequency_ftl,
								'vendor_b2b_rescue' => $vendor_b2b_rescue,
								'reccnt' => $reccnt,
								'description_hover_notes' => $description_hover_notes,
								'nickname' => $nickname,
								'length' => $blength,
								'width' => $bwidth,
								'depth' => $bdepth,
								'cubicfootage' => $bcubicfootage,
								//'QTYfunction' => display_preoder_sel($query_count, $reccnt, $loop_id, $box_warehouse_id),
								'loop_id_encrypt_str' => urlencode(encrypt_password($loop_id)),
								'favorite' => $favorite,
								'include_FTL_Rdy_Now_Only' => $include_FTL_Rdy_Now_Only,
								'uniform_mixed_load_txt' => $uniform_mixed_load_txt,
								'product_shape' => $product_shape,
								'product_bwall' => $product_bwall,
								'product_bottom2' => $product_bottom2,
								'product_bottom3' => $product_bottom3,
								'product_bottom4' => $product_bottom4,
								'product_bottom5' => $product_bottom5,
								'product_top1' => $product_top1,
								'product_top2' => $product_top2,
								'product_top3' => $product_top3,
								'product_top4' => $product_top4,
								'product_vents' => $product_vents,
								'product_grade' => $product_grade,
								'product_printing' => $product_printing,
								'product_ect_burst' => $product_ect_burst,
								'product_ect_detail' => $product_ect_detail,
								'product_material' => $product_material,
								'product_entry' => $product_entry,
								'product_structure' => $product_structure,
								'product_heat_treated' => $product_heat_treated,
								'product_type_sh' => $product_type_sh,
							);
						}
					} else if (isset($_REQUEST["timing"]) && $_REQUEST["timing"] != "") {
						/*if($to_show_rec=='y'){ */
						$products_array[] = array(
							'ftl_qty' => $ftl_qty,
							'colorvalueQty' => number_format($quantity_availableValue_new),
							'qtynumbervalue' => $quantity_availableValue_new,
							'qtynumbervalueorg' => $quantity_availableValue_new,
							'img' => $img,
							'inv_Active' => $dt_view_row["active"],
							'inv_b2b_status' => $dt_view_row["invb2b_status"],
							'inv_box_type' => $box_type_from_qry,
							'boxheight' => $boxheight,
							'boxwidth' => $bwidth,
							'boxlength' => $blength,
							'boxwall' => $boxwall,
							'box_lead_time' => $box_lead_time,
							'td_leadtime_bg_color' => $td_leadtime_bg_color,
							'box_desc_bottom' => $box_desc_bottom,
							'lead_time_days' => $lead_time_days,
							'description' => $description,
							'description_grid' => $description_grid,
							'status' => $status,
							'ship_from' => $ship_from,
							'system_description' => $system_description,
							'flyer_notes' => $flyer_notes,
							'lead_time_of_FTL' => $lead_time_for_FTL,
							'distance' => $miles,
							'distance_sort' => $miles_from,
							'ltl' => $ltl,
							'td_bg' => $td_bg,
							'customer_pickup' => $customer_pickup_allowed,
							'supplier_owner' => $supplier_owner,
							/*'updated_on' => $updated_on,
								'updated_on_grid' =>  $updated_on_grid,*/
							'price' => $price,
							'loads' => $loads,
							'shipsinweekval' => $shipsinweekval,
							'first_load_can_ship_in' => $first_load_can_ship_in,
							'b2b_id' => $b2b_id,
							'loop_id' => $loop_id,
							'box_warehouse_id' => $box_warehouse_id,
							'companyID' => $companyID,
							'minfob' => $minfob,
							'txtaddress' => $dropoff_add1,
							'txtaddress2' => $dropoff_add2,
							'txtcity' => $dropoff_city,
							'txtstate' => $dropoff_state,
							'txtcountry' => $dropoff_country,
							'txtzipcode' => $dropoff_zip,
							'added_on' => $added_on,
							'supplier_name' => $supplier_name,
							'shipFromLocation' => $shipFromLocation,
							'percent_per_load' => $percent_per_load,
							'actual_qty' => $actual_qty,
							'actual_po' => $actual_po,
							'load_av_after_po' => $load_av_after_po,
							'frequency' => $frequency,
							'frequency_sort' => str_replace(",", "", $frequency),
							'frequency_ftl' => $frequency_ftl,
							'vendor_b2b_rescue' => $vendor_b2b_rescue,
							'reccnt' => $reccnt,
							'description_hover_notes' => $description_hover_notes,
							'nickname' => $nickname,
							'length' => $blength,
							'width' => $bwidth,
							'depth' => $bdepth,
							'cubicfootage' => $bcubicfootage,
							'loop_id_encrypt_str' => urlencode(encrypt_password($loop_id)),
							'favorite' => $favorite,
							'include_FTL_Rdy_Now_Only' => $include_FTL_Rdy_Now_Only,
							'uniform_mixed_load_txt' => $uniform_mixed_load_txt,
							'product_shape' => $product_shape,
							'product_bwall' => $product_bwall,
							'product_bottom2' => $product_bottom2,
							'product_bottom3' => $product_bottom3,
							'product_bottom4' => $product_bottom4,
							'product_bottom5' => $product_bottom5,
							'product_top1' => $product_top1,
							'product_top2' => $product_top2,
							'product_top3' => $product_top3,
							'product_top4' => $product_top4,
							'product_vents' => $product_vents,
							'product_grade' => $product_grade,
							'product_printing' => $product_printing,
							'product_ect_burst' => $product_ect_burst,
							'product_ect_detail' => $product_ect_detail,
							'product_material' => $product_material,
							'product_entry' => $product_entry,
							'product_structure' => $product_structure,
							'product_heat_treated' => $product_heat_treated,
							'product_type_sh' => $product_type_sh,
						);
						/*}*/
					}
				}
			}
			$query_count++;
		}
	}

	//echo $query_count;
	// Define the sorting options and their corresponding parameters
	$sortOptions = [
		'low-high' => ['column' => 'minfob', 'direction' => SORT_ASC],
		'high-low' => ['column' => 'minfob', 'direction' => SORT_DESC],
		'nearest' => ['column' => 'distance_sort', 'direction' => SORT_ASC],
		'furthest' => ['column' => 'distance_sort', 'direction' => SORT_DESC],
		'freq-most-least' => ['column' => 'frequency_sort', 'direction' => SORT_DESC, 'type' => SORT_NUMERIC],
		'freq-least-most' => ['column' => 'frequency_sort', 'direction' => SORT_ASC, 'type' => SORT_NUMERIC],
		'qty-most-least' => ['column' => 'qtynumbervalueorg', 'direction' => SORT_DESC],
		'qty-least-most' => ['column' => 'qtynumbervalueorg', 'direction' => SORT_ASC],
		'leadtime-soonest-latest' => ['column' => 'lead_time_days', 'direction' => SORT_ASC, 'type' => SORT_NUMERIC],
		'leadtime-latest-soonest' => ['column' => 'lead_time_days', 'direction' => SORT_DESC, 'type' => SORT_NUMERIC],
		'length-short-long' => ['column' => 'length', 'direction' => SORT_ASC, 'type' => SORT_NUMERIC],
		'length-long-short' => ['column' => 'length', 'direction' => SORT_DESC, 'type' => SORT_NUMERIC],
		'width-thin-long' => ['column' => 'width', 'direction' => SORT_ASC, 'type' => SORT_NUMERIC],
		'width-long-thin' => ['column' => 'width', 'direction' => SORT_DESC, 'type' => SORT_NUMERIC],
		'height-short-tall' => ['column' => 'depth', 'direction' => SORT_ASC, 'type' => SORT_NUMERIC],
		'height-tall-short' => ['column' => 'depth', 'direction' => SORT_DESC, 'type' => SORT_NUMERIC],
		'cu-small-big' => ['column' => 'cubicfootage', 'direction' => SORT_ASC, 'type' => SORT_NUMERIC],
		'cu-big-small' => ['column' => 'cubicfootage', 'direction' => SORT_DESC, 'type' => SORT_NUMERIC],
	];

	// Check if a valid sort option is provided
	if (isset($_REQUEST['sort_by']) && $_REQUEST['sort_by'] != "" && isset($sortOptions[$_REQUEST['sort_by']])) {
		$sortOption = $sortOptions[$_REQUEST['sort_by']];
		$column = $sortOption['column'];
		$direction = $sortOption['direction'];
		$type = isset($sortOption['type']) ? $sortOption['type'] : SORT_REGULAR;

		// Extract the column values and sort the array
		$key_values = array_column($products_array, $column);
		array_multisort($key_values, $direction, $type, $products_array);
	}

	// $no_of_pages=ceil(count($products_array)/$no_of_product_per_page);
	/*if (!empty($products_array)){
		$final_product_array = array_slice($products_array, $start_index, $no_of_product_per_page);
	}*/
	//Final Response Of Products/ Inventory
	$warehouse_filter_dp = '';
	if (isset($_REQUEST["show_urgent_box"]) && $_REQUEST["show_urgent_box"] != 1) {
		$warehouse_filter_dp =  ' <option value="all" display_val="All" selected>All</option>';
		if (isset($_REQUEST["shown_in_client_flg"]) && $_REQUEST["shown_in_client_flg"] == 1) {
			$warehouse_filter_dp .= '<option value="238" display_val="Direct Ship">Direct Ship</option>';
		}
		if (count($warehouse_filter_arr) > 0) {
			$warehouse_ids = implode(",", $warehouse_filter_arr);
			$warehouse_get_query = db_query("SELECT id, warehouse_name, warehouse_city, warehouse_state FROM loop_warehouse WHERE id IN ($warehouse_ids) ORDER BY warehouse_city");
			while ($warehouse = array_shift($warehouse_get_query)) {
				$name = $warehouse['warehouse_city'] . ", " . $warehouse['warehouse_state'];
				$id = $warehouse['id'];
				$warehouse_filter_dp .= '<option value="' . $id . '" display_val="' . $name . '">' . $name . '</option>';
			}
		}
	}

	$final_res = array(
		// 'no_of_pages'=>$no_of_pages,
		'data' => $products_array,
		'total_items' => count($products_array),
		'warehouse_filter_dp' => $warehouse_filter_dp,
	);

	if (isset($_REQUEST['show_urgent_box']) && $_REQUEST['show_urgent_box'] == 1) {
		$_SESSION[$_REQUEST['box_type'] . '_urgent_boxes'] = $final_res;
	} else {
		$_SESSION[$_REQUEST['box_type'] . '_product_data'] = $final_res;
	}
	echo json_encode($final_res);
}
