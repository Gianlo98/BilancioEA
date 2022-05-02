<?php 

//Ritorna un array col formato [id_conto] => obj_conto di tutti i conti dentro la riga con id $_row_id
function get_row_accounts($row_id){
	global $database;
	$accounts = array();
	
	//Ottengo tutti gli account e li salvo in $accounts[id_conto] => obj_conto
	$result = $database->query("SELECT * FROM `accounts` WHERE balance_sheet_row_id = $row_id");
	while($row =  $result->fetch_object()){
		$accounts[$row->accounts_id] = $row;
	}
	
	$result->close();
	return $accounts;
}

//Ritorna un array contenente i dati di una sola riga. riga[0] = rigaCercata['id_conto', 'nome_conto'....]
function get_data_row($row_id){
	global $database;
	$rows = $database->query("SELECT * FROM `income_statement_rows` WHERE row_id = " . $row_id);
	$array = $rows->fetch_all(MYSQLI_ASSOC);
	return (empty($array))? false : $array;
}

//[row_id] = { => 1, row_char_identificator => A, row_name => Val. della prod, row_parent => 7, row_childrends = {obj,obj}, accounts => array({15.20, prodotti c/vendite, 459, 1},{14.53, prodotti c/vendite, 443,0})}
function generate_balance_sheet_rows(){
	global $database;
	$rows_array = array();
	$query_res = $database->query("SELECT * FROM balance_sheet_rows ORDER BY row_id");
	foreach ($query_res->fetch_all(MYSQLI_ASSOC) as $key => $value){	
		$rows_array[$value['row_id']] = generate_balance_sheet_row($value['row_id']);
	}
	return $rows_array;
}

function balance_row_intersect($accountsRows, $arrayUserAccounts){
	
	$row_account_array = array();

	foreach($accountsRows as $account_id => $account_values){
				
		foreach($arrayUserAccounts as $user_account_id => $user_account_import){
			
			if ($account_id == $user_account_id){
				$row_account_array[$account_id] = (array)$account_values;
				$row_account_array[$account_id]['import'] = ($account_values->rectified == "0") ? $user_account_import : $user_account_import * -1;
			}
		}
	}
	
	return $row_account_array;
}

function generate_balance_sheet_row($row_id){
	global $database;
	
	$row = array();
	
	$query_res = $database->query("SELECT * FROM balance_sheet_rows WHERE row_id = $row_id");
	
	if($database->affected_rows > 0){
		$row = $query_res->fetch_all(MYSQLI_ASSOC)[0];

		$arrayRow = get_row_accounts($row_id);
		
		$query_res = $database->query("SELECT * FROM balance_sheet_rows WHERE row_parent = $row_id");
		$row['row_childs'] = $query_res->fetch_all(MYSQLI_ASSOC);
		
		$row['row_accounts'] = balance_row_intersect($arrayRow,$_SESSION['accounts']);
		
		$row['row_import'] = array_sum(array_column($row['row_accounts'], 'import'));
	}
	$query_res->close();
	
	return $row;
}



//Mi ritorna un array con tutte le righe "FIGLIE" di quella riga. Ritorna un array simile a questo: $righeFiglie[0] = riga['id_conto', 'nome_conto'...] ....
function get_child_row($rows_array, $row_id){
	$rows = array();
	foreach ($rows_array as $id => $row){
		if($row["row_parent"] == $row_id) $rows[$id] = $rows_array[$id];
	}
	return $rows;
}

//Tipo di riga: SUPER (in grassetto senza tab all'inizio e senza importo), NORMAL (tab x1 inizio), CHILD(tab x2). $row_addition mi dice se la riga va sommata (1 se la riga va sottratta)
function print_row($row_array, $row_id){

	//BUGFIX: Se l'utente inserisce un conto che ha 0 come valore, devo mostrare 0 e non il - !!!!!!!!!!
	list($id,$char_id,$name,$parent,$childs, $accounts,$import) = array_values($row_array[$row_id]);
	
	//Mi servono per indentare o mettere in grassetto alcune righe	
	$row_class = $row_type_str_start = ($parent == null) ? "row_super" : (($row_array[$parent]["row_parent"] != null) ? "row_child" : "row_normal");

	echo "<tr class=\"income_statement_row $row_class \" row_id=\"$id\" ".(($parent != null) ?  "row_id_parents=\"$parent\"": "")."><td class=\"row_name\">";
	
	//Stampo il nome della riga
	echo "$char_id) $name";
	
	//Mi preparo lo script in JS
	$jsArray = "<script>";
	
	//mi salvo in un array tutti i conti e i loro importi, arrayRow + id_riga[id_conto]. ex: arrayRow2['20.01'] = {import:"10",name:"Prodotti c/vendite",rectified:0}
	$jsArray .= "var arrayRow$id = []; ";
	
	echo "</td><td class=\"row_import\">";
	
	//Se non ha sottorighe posso calcolarmi l'importo
	if (!empty($accounts)){
		//Sommo ogni conto appartente alla riga che l'utente ha inserito
		foreach($accounts as $ac_id => $account){
			list($ac_id, $ac_name, $ac_id_type, $ac_nature, $ac_eccedence, $ac_rectified, $ac_cat_id, $ac_row_id, $ac_import) = array_values($account);
		
			//Inserisco il conto nell'array in js inerente alla riga corrente
			$jsArray .= 'arrayRow'.$id.'.push({id:"'.$ac_id.'",name:"'.$ac_name.'",import:"'.$ac_import.'",rectified:'.$ac_rectified.'});';
		}
		
		if (empty($accounts)) $jsArray = "";
		
		//Stampo l'importo
		echo number_format ($import,0,",",".");
}else echo($parent == null) ? "" : "-";
		
	echo "</td>";
	
	//Stampo lo script fuori da td
	echo $jsArray . "</script>";
	
	echo "</tr>";
	
	//Ritorno il valore per fare la somma di tutte le righe, lo ritorno arrotondato ovviamente
	return round($import);
}

//Stampa una riga customizzata con valori specifici
function print_custom_row($row_char_identificator, $row_name, $row_import, $row_class = ""){
	
	echo "<tr class=\"income_statement_row $row_class \"><td class=\"row_name\">";
	
	//Stampo il nome della riga
	echo (($row_char_identificator == "") ? "" : $row_char_identificator . ") " ). $row_name;
	
	echo "</td><td class=\"row_import\">";
	
	echo ($row_import == 0) ? "-" : number_format ($row_import,0,",",".");
	
	echo "</td></tr>";
	
	return $row_import;
}

function calculate_rounding(){
	$rounding = 0;
	foreach($_SESSION['accounts'] as $key => $import){
		$rounding += round($import) - $import;
	}
	return $rounding;
}	




?>































