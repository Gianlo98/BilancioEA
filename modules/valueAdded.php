<link  rel="stylesheet"  href="plugins/BilancioEA/css/BalancePrintUtils.css"/>
<link  rel="stylesheet"  href="plugins/BilancioEA/css/balanceSheet.css"/>

<?php
    require(dirname(__FILE__) . "/../utils/core/Statement/reworkedIncomeStatementManager.php");
    $reworkedIncomeStatementManager = new reworkedIncomeStatementManager();
?>

<section class="content-header">
  <h1 class="balance-sheet-title">
	Prospetto a Valore Aggiunto
	<small>
		<?php
			if(isset($_SESSION['exercise'])) echo "Esercizio " . $_SESSION['exercise'];
		?>
	</small>
  </h1>
  <ul class="balance-sheet-buttons">
	<li class="balance-sheet-button"> <button class="btn btn-info balance-she-btn" id="add_round" data-toggle="modal" data-target="#modalRounding">Aggiungi Arrotondamento</button></li>
    <li class="balance-sheet-button"> <button class="btn btn-info balance-she-btn" id="btn_hide_row">Mostra solo righe utilizzate</button></li>
    <li class="balance-sheet-button">  <button class="btn btn-info balance-she-btn" id="print" >Stampa</button></li>
  </ul>
</section>

<section class="content">
    <div id="banceSheetContainer">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <table class="table table-striped table-hover ">
                <thead>
                    <h1 class="balanceSheetSectionTitle">Calcolo</h1>
                </thead>
                <tbody>
                    <?php
                        $reworkedIncomeStatementManager->printValueAdded();
                    ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-3"></div>
    </div>
    <div style="background-color:white">

                <!-- Modal per le righe -->
        <div class="modal fade" id="modal_row" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modal_row_title"></h4>
                    </div>
                    <div class="modal-body">
                        <table id="table_row_contnent" class="table table-striped table-hover "></table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                    </div>
                </div>
            </div>
        </div>		
		<!-- Modal Per gli arrotondamenti -->
		<div class="modal fade" id="modalRounding" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" role="document">
			     <div class="modal-content">
                     <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title" id="exampleModalLabel">Aggiungi arrotondamento</h4>
			         </div>
                     <div class="modal-body">
						<div class="form-group">
							<label for="rounded-input" class="control-label">Importo dell'arrotondamento:</label>
							<input type="number" name="round" class="form-control" id="rounded-input">
							<small for="rounded-input">Se l'arrotondamento è passivo inserirlo col segno -</small>
						</div>
						<button type="button" id="add-round-btn" class="btn btn-primary">Aggiungi</button>
			         </div>
                </div>
            </div>
        </div>
        <script>

		
		$("#print").click(function(){
			window.print();
		});
		
		$("#btn_hide_row").click(function(){
			if ($(".row_void").is(":visible")){
				$(".row_void").hide()
				$("#btn_hide_row").text("Mostra tutte le righe")
			}else{
				$(".row_void").show()
				$("#btn_hide_row").text("Mostra solo righe utilizzate")
			}
		});
		
		$("#add-round-btn").click(function(){
			round = $("#rounded-input").val()
			account_id = (round >= 0) ? "21.10" : "39.10";
			$.ajax({
				url : "utils/ajax/Account/manageUserAccounts.php",
				data : {
					action: "add",
					account_id : account_id,
					account_value : Math.abs(round) 
				},
				method : "GET",
			}).done(function(data){
				location.reload();
			});
		});
			
    </script>
</section>