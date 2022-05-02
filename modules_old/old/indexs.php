<html>
	<?php
    
		require("config/db_config.php");
		$database = new mysqli($db_host, $db_username, $dv_password, $db_name);
		
		mysqli_set_charset($database, 'utf8');
		
		session_start();
		if(!isset($_SESSION['accounts'])) $_SESSION['accounts'] = array();
			
		if(!isset($_SESSION['db_accounts'])) {
			$result = $database->query("SELECT * FROM `accounts`");
			$_SESSION['db_accounts'] = array();
			while($row =  $result->fetch_object()){
				$_SESSION['db_accounts'][$row->accounts_id] = $row;
			}
			$result->close();
		}
		
		$accounts = $_SESSION['db_accounts'];
		
		
		

		
		class account{
			
			public $import;
			public $name;
			public $id_type;
			public $nature;
			public $eccedence;
			public $categories_id;
			public $balance_sheet_row_id;
			public $income_statement_row_id;
			
			function __construct($import,$name,$id_type, $nature, $eccedence, $categories_id, $balance_sheet_row_id, $income_statement_row_id){
				$this->import = $import;
				$this->name = $name;
				$this->id_type = $id_type;
				$this->nature = $nature;
				$this->eccedence = $eccedence;
				$this->categories_id = $categories_id;
				$this->balance_sheet_row_id = $balance_sheet_row_id;
				$this->income_statement_row_id = $income_statement_row_id;
			}
			
		}
        
    //Se sono settate, significa che l'utente ha aggiunto un nuovo conto
		if(isset($_POST['account']) && isset($_POST['import'])){
			if (intval($_POST['import'])){
				$_SESSION['accounts'][$_POST['account']] = $_POST['import'];
				$obj = $accounts[$_POST['account']];
			}else{
				echo'<div class="alert alert-danger">
				  <strong>Warning!</strong> Bel tentativo :-)
				</div>';
			}
		}
	
		
		if (isset($_GET['remove_id'])){
			if (array_key_exists($_GET['remove_id'], $_SESSION['accounts'])) {
				unset($_SESSION['accounts'][$_GET['remove_id']]);
				header('Location: '.$_SERVER['PHP_SELF']);
				exit;
			}
		}
    
    
    
    
    
	?>
	<style>
	.select2-selection__rendered {
		line-height: 35px !important;
	}

	.select2-selection {
		height: 37px !important;
	}
	.select2-container--default .select2-selection--single{
		border: 1px solid #cccccc !important;
	}
	.fa-trash:hover{
		color:#af1212;
		
	}
	.account_id{
		width: 10%;
	}
	.account_name{
		width: auto;
	}
	.account_import{
		text-align: right;
		padding-right: 10% !important;
	}
	</style>
	<head>

		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<script src="js/jquery-2.2.4.min.js"></script>
		<script src="js/bootstrap.min.js"></script>

	</head>
	
	<body>
        <?php require("core/navbar.php");?>
		<div class="container">
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <legend><h1><center>Inserisci conti</center></h1></legend>
				<div class="form-group">
					<div class="col-sm-5">
						<select class="select" style="width:100%;" name="account" size=20></select>
					</div>
					<div class="col-sm-3">
						<input type="number" step="any" id="input_import" name="import" placeholder="importo" class="form-control" style="width:100%"></input>
					</div>
					<button class="btn btn-info" type="submit" style="width: 30%">Inserisci</button>
				</div>
				<div class="form-group">
					<div class="panel panel-primary" style="">
						<div class="panel-body">
							<table class="table table-striped table-hover ">
								<thead>
									<tr>
									<th>ID</th>
									<th>Nome Conto</th>
									<th class="account_import">Importo Conto</th>
									<th></th>
									</tr>
								</thead>
								<tbody>
									<?php 
										foreach($_SESSION['accounts'] as $key => $import){
											echo "<tr class=\"account_data\">";
											echo "<td class=\"account_id\">" . $accounts[$key]->accounts_id . '</td>';
											echo "<td class=\"account_name\"><div class=\"account_name \">" . $accounts[$key]->accounts_name . '</div></td>';
											echo "<td class=\"account_import\">" . number_format ($import,2,",",".") . '</td>';
											echo "<td style=\"width:50px\"><a href=\"".$_SERVER['PHP_SELF']."?remove_id=".$accounts[$key]->accounts_id."\" style=\"color:grey\"><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></a></br>";
											echo "</tr>";
										}
										if(empty($_SESSION['accounts'])){
											echo "<tr><td>Nessun conto inserito</td></tr>";
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!--<div class="col-sm-2"></div>
					<a href="hub.php"><button class="btn btn-success" type="button"  href="hub.php" style="width: 60%">Elabora</button></hub>-->
			</form>
		</div>
		
		<div class="modal fade" id="accountModal" role="dialog">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Informazioni sul conto</h4>
			  </div>
			  <div class="modal-body" style="height:200px">
					   <div class="modal_account_id"></div>
					   
                       <div class="modal_account_name"></div>

                       <div class="modal_account_type"></div>
					   
                       <div class="modal_account_nature"></div>
					   
                       <div class="modal_account_eccedence"></div>
					   
                       <div class="modal_account_rectified"></div>
					   
                       <div class="modal_account_categories"></div>
					   
                       <div class="modal_account_balance"></div>

			  </div>
			  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
			  </div>
			</div>
		  </div>
		</div>
	</body>
	<script>
	var json;
	$(".account_data").unbind('click');
	$(".account_data").click(function(){		
		var account_id = $($(this).children()[0]).text();
		var modal = $("#accountModal");
		
		json = $.ajax({
			url: "core/accounts_modal_info.php?account_id=" + account_id,
			async: true,
			success: function(response){
				json = JSON.parse(response);
				$.each(json, function(key,value){
					account_id = json.accounts_id;
					account_name = json.accounts_name;
					account_type = json.accounts_id_type;
					account_nature = json.accounts_nature;
					account_eccedence = json.accounts_eccedence;
					account_rectified = json.rectified;
					category_name = json.categories.category_name;
					category_desc = json.categories.category_note;
					balance_char_id = json.balance_row.row_char_identificator;
					balance_char_name = json.balance_row.row_name;
					balance_char_accounts = json.balance_row.accounts;
					
					//CAPIRE PERCHE DA 10 VOLTE E SISTEMARE IL MODAL
					console.log(balance_char_accounts);
					
					modal.modal({keyboard: false})
					modal.find('.modal_account_id').text(account_id);
					modal.find('.modal_account_name').text(account_name);
					modal.find('.modal_account_type').text( account_type);
					modal.find('.modal_account_nature').text((account_nature == "E")? "Economico":"Finanziario");
					modal.find('.modal_account_eccedence').text((account_eccedence == "A")? "Avere":"Dare");
					modal.find('.modal_account_rectified').text((account_rectified == "0")? "":"(In rettifica)");
					//modal.find('.modal_account_categories').text(account_categories);
				});
			}
		});
		modal.modal('show');
	});
	
	$('.select').on('select2:select', function (evt) {
		$("#input_import").focus();
	});
	</script>
	<script src="js/select-custom.js"></script>
</html>