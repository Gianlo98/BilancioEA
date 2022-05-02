<?php
	require("../config/db_config.php");
	$database = new mysqli($db_host, $db_username, $dv_password, $db_name);
	
	if ($database->connect_error){
		die("Connection error (" . $database->connect_errn . ")" . $database->connect_error);
	}
	
	mysqli_set_charset($database, 'utf8');
	
		$row_data = array();

		$account_id = isset($_GET['account_id']) ? $_GET['account_id'] : 0;
		
		$result = $database->query("SELECT * FROM accounts WHERE accounts_id = \"$account_id\"");
		
		if ($database->affected_rows > 0){
			
			$row_data = $result->fetch_assoc();
			
			$category_id = $row_data['accounts_categories_id'];
			$result = $database->query("SELECT * FROM accounts_categories WHERE category_id = \"$category_id\"");
			$row_categories = $result->fetch_assoc();
			
			$balance_row_id = $row_data['balance_sheet_row_id'];
			$result = $database->query("SELECT * FROM balance_sheet_rows WHERE row_id = \"$balance_row_id\"");
			$balance_row = $result->fetch_assoc();
			
			$balance_row_id = $balance_row['row_id'];
			$result = $database->query("SELECT * FROM accounts WHERE balance_sheet_row_id = \"$balance_row_id\"");
			$balance_row['accounts'] = $result->fetch_all(MYSQLI_ASSOC);
			
			
			$row_data['categories'] = $row_categories;
			$row_data['balance_row'] = $balance_row;
		}
		
		echo json_encode($row_data);
		
		$result->close();
	$database->close();
?>