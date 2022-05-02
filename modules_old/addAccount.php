<?php
    require(dirname(__FILE__) . "/../utils/balance_utils.php");
    init_utils();
		?>
<section class="content-header">
  <link  rel="stylesheet"  href="dist/css/addAccount.css"/>
	<h1>
	Modifica Conti
	<small><?php echo((getUserExercise() != false) ?("Esercizio " . getUserExercise()) :""); ?></small>
  </h1>
</section>

<!-- Main content -->
<section class="content" id="insert_account_section">
    <?php

    if ((isset($_POST["addUserAccountID"]) & !empty($_POST["addUserAccountID"])) & (isset($_POST["addUserAccountImport"]) & !empty($_POST["addUserAccountImport"]))){
        addUserAccount($_POST["addUserAccountID"],$_POST["addUserAccountImport"]);
        //DA FARE CONTROLLI !!!!
        $orderE = 1;
        $orderP = 2;
        $account = getAccount($_POST["addUserAccountID"])[$_POST["addUserAccountID"]];
        if($account->getNature() == "P"){
            $orderP = 1;
            $orderE = 2;
        } else {
            $orderE = 1;
            $orderP = 2;
        }
    }
    //var_dump(getUserAccounts());
  ?>
    <form method="POST" >
      <!--<legend><h1><center>Inserisci conti</center></h1></legend>-->
      <div class="form-group" >
            <form method="POST">
                <div class="col-sm-5">
                    <select class="select form-control" id="select" style="width:100%;" name="addUserAccountID" size=20></select>
                </div>
                <div class="col-sm-3">
                    <input type="number" step="any" id="input_import" name="addUserAccountImport" placeholder="importo" class="form-control" style="width:100%"></input>
                </div>
                <button class="btn btn-info" type="submit" id="addAccountBtn" style="width: 30%">Inserisci</button>
            </form>
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
                                $userAccount = getUserAccounts();
                                foreach($userAccount as $key => $import){
                                    $account = getAccount($key)[$key];
                                    if($account->getNature() == "E"){
                                        echo "<tr class=\"account_data\">";
                                        echo "<td class=\"account_id\">" . $account->getId() . '</td>';
                                        echo "<td class=\"account_name\"><div class=\"account_name \">" . $account->getName() . '</div></td>';
                                        echo "<td class=\"account_import\">" . number_format ($import,2,",",".") . '</td>';
                                        echo "<td style=\"width:50px\"><a href=\"".$_SERVER['PHP_SELF']."?remove_id=".""."\" style=\"color:grey\"><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></a></br>";
                                        echo "</tr>";
                                    }
                                }
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
                                $userAccount = getUserAccounts();
                                foreach($userAccount as $key => $import){
                                    $account = getAccount($key)[$key];
                                 
                                    if($account->getNature() == "P"){
                                        echo "<tr class=\"account_data\">";
                                        echo "<td class=\"account_id\">" . $account->getId() . '</td>';
                                        echo "<td class=\"account_name\"><div class=\"account_name \">" . $account->getName() . '</div></td>';
                                        echo "<td class=\"account_import\">" . number_format ($import,2,",",".") . '</td>';
                                        echo "<td style=\"width:50px\"><a href=\"". $_SERVER['HTTP_REFERER'] ."?remove_id=".""."\" style=\"color:grey\"><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></a></br>";
                                        echo "</tr>";
                                    }
                                }
                            ?>                    
                        </tbody>
                    </table>
                </div>         
            </div>
        </div>
    </form>
	<!-- Modal Per aggiungere esercizi -->
	<div class="modal fade" id="modalAddExercise" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			 <div class="modal-content">
				<form role="form" method="GET" action="utils/admin/createExercise.php">
					<div class="modal-header">
					   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					   <h4 class="modal-title" id="exampleModalLabel">Aggiungi Esercizio con i conti salvati</h4>
					</div>
					<div class="modal-body">
						 <div class="form-group">
						   <label>Numero</label>
						   <input type="text" class="form-control" name="exercise_id" placeholder="Numero">
						 </div> 
						 <div class="form-group">
						   <label>Pagina</label>
						   <input type="text" class="form-control" name="exercise_page" placeholder="Pagina">
						 </div>
						 <style>
							 .slider-selection{
								 background: #f39c12 !important;
							 }
							 .slider{
								 margin-left: 5% !important;
								 width: 80% !important;
							 }
						 </style>
						 <div class="form-group">
							 <label>Difficoltà</label>
								 <input id="modalAddExSlid" name="exercise_diff" data-slider-id='ex1Slider' type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="40"
								 value="40"/>
						 </div>
						 <div class="form-group">
						   <label>Note</label>
						   <textarea name="exercise_note" class="form-control" rows="3" placeholder="Note dell'esercizio"></textarea>
						 </div>
					</div>
				   <div class="modal-footer">
					   <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
					   <button type="send" class="btn btn-primary" id="modalAddExSend">Salva</button>
				   </div>
				</form>
			</div>
		</div>
	</div>
	<button class="btn btn-primary" style="width:100%" data-toggle="modal" data-target="#modalAddExercise">Salva Esercizio</button>
</section>
<script>
    
	var body = document.body,
	html = document.documentElement;
	
	var height = Math.max( body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight );
	
	initSelect();
	
$('#modalAddExSlid').slider({
	formatter: function(value) {
		return 'Current value: ' + value;
	}
});
			
	$('.select').on('select2:opening', function () {
			console.log("fff");
			if(height/2 > window.innerHeight){
					$.scrollTo(document.getElementById('insert_account_section'), 800);
			}
	});
	
	$('.select').on('select2:select', function () {
		$("#input_import").focus();
	});
	
	function initSelect(){ 
					$('.select').select2({
		placeholder: 'Seleziona un conto',
		allowClear: true,
		ajax: {
				url: function () {
				return "utils/rest/get-accounts.php";
			},
			
			data: function (params){
				var query = {
					accounts : params.term
				};
				return query;
			},	
			cache: true,
			processResults: function (data) {
				obj = $.parseJSON(data);
				var categoriesArray = [];
				var accountsArray = [];
				var idex = 1;
				$.each(obj, function(key,value){
					accountsArray = [];
					
					$.each(value, function(key1,value1){
						accountsArray.push({
							id: value1.accounts_id,
							text: value1.accounts_name
						});
					});
					
					categoriesArray.push({
						id: idex++,
						text: key,
						children: accountsArray.slice(0)
					});
					
				});
		
				return {
					results: categoriesArray
				};
			}
		},
		language: {
			noResults: function () {
				return "Nessun contro trovato";
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
