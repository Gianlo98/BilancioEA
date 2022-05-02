<?php
    require(dirname(__FILE__) . "/../utils/BilancioEA.php");
    $balanceObject = new BilancioEA();
    $balance_exercise_manager = $balanceObject->getExerciseManager();
    $balance_userAccount_manager = $balanceObject->getUserAccountManager();
    $balance_account_manager = $balanceObject->getBalanceAccountManager();
	//$balance_userAccount_manager->addUserAccount("01.05",55);
	$userAccounts = $balance_userAccount_manager->getAllUserAccounts();
	$E_accounts = "";
	$P_accounts = "";
?>
	
<section class="content-header">
  <style>
  @media screen and (max-width: 767px) {
      #addAccountBtn{
          width: 100% !important;
          margin-top: 3%;
      }
  }
  </style>
	<h1>
	Modifica Conti
	<small><?php echo(($balance_exercise_manager->getUserExerciseID() != false) ?("Esercizio " . $balance_exercise_manager->getUserExerciseID()) : ""); ?></small>
  </h1>
</section>

<!-- Main content -->
<section class="content" id="insert_account_section">
    <?php
		$last_account_id = (isset($userAccounts['last'])) ? $userAccounts['last'] : null;
		$account = $balance_account_manager->getAccount($last_account_id);
		if($account != null && $account->getNature() == "P"){
			$orderP = 1;
			$orderE = 2;
		}else{
			$orderE = 1;
			$orderP = 2;
		}
		
		
		if($userAccounts != null){
			foreach($userAccounts as $key => $import){
				$account = $balance_account_manager->getAccount($key);
				if($account != null && $account->getNature() == "E"){
					$E_accounts .= "<tr class=\"account_data\">";
					$E_accounts .= "<td class=\"account_id\">" . $account->getId() . '</td>';
					$E_accounts .= "<td class=\"account_name\"><div class=\"account_name \">" . $account->getName() . '</div></td>';
					$E_accounts .= "<td class=\"account_import\">" . number_format ($account->getImport(),2,",",".") . '</td>';
					$E_accounts .= "<td style=\"width:50px\" class=\"account_remover\" account-id=\"". $account->getId() ."\"><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></br>";
					$E_accounts .= "</tr>";
				}else if($account != null && $account->getNature() == "P"){
					$P_accounts .= "<tr class=\"account_data\">";
					$P_accounts .= "<td class=\"account_id\">" . $account->getId() . '</td>';
					$P_accounts .= "<td class=\"account_name\"><div class=\"account_name \">" . $account->getName() . '</div></td>';
					$P_accounts .= "<td class=\"account_import\">" . number_format ($account->getImport(),2,",",".") . '</td>';
					$P_accounts .= "<td style=\"width:50px\" class=\"account_remover\" account-id=\"". $account->getId() ."\"><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></br>";
					$P_accounts .= "</tr>";
				}
			}
		}
		
		//TODO RICHIESTE AJAX AGGIUNTA/RIMOZIONE CONTI
  ?>
    <form method="POST" >
      <!--<legend><h1><center>Inserisci conti</center></h1></legend>-->
      <div class="form-group" >
			<div class="col-sm-5">
				<select class="select form-control" id="select" style="width:100%;" name="addUserAccountID" size=20></select>
			</div>
			<div class="col-sm-3">
				<input type="number" step="any" id="input_import" name="addUserAccountImport" placeholder="importo" class="form-control" style="width:100%"></input>
			</div>
				<button class="btn btn-info" type="button" id="addAccountBtn" style="width: 30%">Inserisci</button>
        </div>

        <div id="panel_type_order">
            <div class="panel" id="<?php echo $orderE; ?>">
                <div class="panel-heading">
                    <h1>Situazione Economica</h1>
                </div>
                <div class="panel-body" data-pg-collapsed="">
                    <table class="table" data-pg-collapsed="">
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
								echo $E_accounts;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel" id="<?php echo $orderP; ?>">
                <div class="panel-heading">
                <h1>Situazione Patrimoniale</h1>
            </div>
                <div class="panel-body" data-pg-collapsed="">
                    <table class="table" data-pg-collapsed="">
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
                              echo $P_accounts;
                          ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
	<button class="btn btn-danger" id="resetAccountBtn" style="width:100%">Cancella Tutti i conti</button>
</section>
<script>
	
	var body = document.body,
		html = document.documentElement,
		height = Math.max( body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight ),
		selected_account_id = "";
	
	initSelect();
	
	$("#addAccountBtn").click(function(){
		$.ajax({
			url : "utils/ajax/Account/manageUserAccounts.php",
			data : {
				action: "add",
				account_id : selected_account_id,
				account_value : $("#input_import").val()
			},
			method : "GET",
		}).done(function(data){
			location.reload();
			console.log(data);
		});
	});
	
	$('#input_import').keypress(function (e) {
		if(e.which == 13){
		  $("#addAccountBtn").trigger("click");
		}
	});
	
	$(".account_remover").click(function(){
			
		$.ajax({
			url : "utils/ajax/Account/manageUserAccounts.php",
			data : {
				action: "remove",
				account_id : $(this).attr("account-id")
			},
			method : "GET",
		}).done(function(data){
			location.reload();
		});
		
	});
	
	$('#resetAccountBtn').click(function(){
		swal({
			type: 'warning', 
			title: "Vuoi davvero resettare tutti i conti impostati ?",
			showCancelButton: true,
			confirmButtonText: "Procedi",
			cancelButtonText: "Annulla"
		}).then(function(){
			$.ajax({
				url: "utils/ajax/Exercises/manageExercise.php",
				data: {
					action: "reset"
				},
				method: "GET"
			}).done(function(){location.reload()});
		});
		
	});

	$('.select').on('select2:opening', function () {
			if(height/2 > window.innerHeight){
					$.scrollTo(document.getElementById('insert_account_section'), 800);
			}
	});

	$('.select').on('select2:select', function () {
		$("#input_import").focus();
		selected_account_id = $(this).val();
	});

	function initSelect(){
		$('.select').select2({
			placeholder: 'Seleziona un conto',
			allowClear: true,
			ajax: {
					url: function () {
					return "utils/ajax/Account/manageBalanceAccounts.php";
				},

				data: function (params){
					var query = {
						action : "search",
						account_name : params.term
					};
					return query;
				},
				cache: true,
				processResults: function (data) {
					obj = $.parseJSON(data);
					var categoriesArray = [];
					var accountsArray = [];
					var idex = 1;
					$.each(obj.data, function(key,value){
						accountsArray = [];

						$.each(value.accounts, function(key1,value1){
							accountsArray.push({
								id: value1.id,
								text: value1.name
							});
						});

						categoriesArray.push({
							id: idex++,
							text: value.name,
							children: accountsArray.slice(0)
						});

					});

					return {
						results: categoriesArray
					};
				}
			},
			language: {
				noResults: function(){
					return "Nessun contro trovato";
				},
				searching:function(){ 
					return"Sto cercando…"
				},
				errorLoading:function(){
					return"I risultati non possono essere caricati."
				}
			},
		});
	}

	var main = document.getElementById( 'panel_type_order' );
	[].map.call( main.children, Object ).sort( function ( a, b ) {
			return +a.id.match( /\d+/ ) - +b.id.match( /\d+/ );
	}).forEach( function ( elem ) {
			main.appendChild( elem );
	});

</script>
<!-- /.content -->
