<?php
	require("../config/db_config.php");
	$database = new mysqli($db_host, $db_username, $dv_password, $db_name);
	
	if ($database->connect_error){
		die("Connection error (" . $database->connect_errn . ")" . $database->connect_error);
	}
	
	mysqli_set_charset($database, 'utf8');
	
		$jsonArray = array();

	
		$query = 'SELECT * FROM accounts ' . ((isset($_GET['accounts'])) ? ('WHERE accounts_name LIKE "%'. $_GET['accounts'].'%"; ') : '');
		$result = $database->query("SELECT * FROM accounts_categories");
		$accounts_categories = array();
		while($row = $result->fetch_array()){
			$accounts_categories[$row['category_id']] = $row['category_name'];
		}

		$result = $database->query($query);
		
		
		while($row = $result->fetch_array()){
			 
			if(
				!array_key_exists ($accounts_categories[$row['accounts_categories_id']],$jsonArray)
				){
				$jsonArray[$accounts_categories[$row['accounts_categories_id']]] = array();
			}
				array_push($jsonArray[$accounts_categories[$row['accounts_categories_id']]],$row);
			
		}
		
		echo json_encode($jsonArray);
		$result->close();

	$database->close();
?>