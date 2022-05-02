<link  rel="stylesheet"  href="plugins/BilancioEA/css/BalancePrintUtils.css"/>
<link  rel="stylesheet"  href="plugins/BilancioEA/css/incomeStatmentStyle.css"/>
<?php
	require(dirname(__FILE__) . "/../utils/core/Statement/incomeStatementManager.php");           
	$incomeStatementManager = new IncomeStatementManager();
?>
<section class="content-header">
  <h1 class="income-statment-title">
	Conto Economico
	<small>
		<?php
			if(isset($_SESSION['exercise'])) echo "Esercizio " . $_SESSION['exercise'];
		?>
	</small>
  </h1>
  <ul class="income-statment-buttons">
	<li class="income-statment-button"> <button class="btn btn-info income-stat-btn" id="add_round" data-toggle="modal" data-target="#modalRounding">Aggiungi Arrotondamento</button></li>
    <li class="income-statment-button"> <button class="btn btn-info income-stat-btn" id="btn_hide_row">Mostra solo righe utilizzate</button></li>
    <li class="income-statment-button">  <button class="btn btn-info income-stat-btn" id="print" >Stampa</button></li>
  </ul>
</section>

<section class="content">
    <div style="background-color:white;display:inline-block;width:100%">
        <table class="table table-striped table-hover ">
            <tbody>
                <?php 
                    $incomeStatementManager->printIncomeStatement();
                ?>
            </tbody>
        </table>
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

        $(".row_normal,.row_child").click(function(){
            $nameElement = $(this).find("td")[0];
            name = ($($nameElement).text().replace("&nbsp;",""));
            
            $importElement = $(this).find("td")[1];
            rowImport = ($($importElement).text());

            row_id = ($(this).attr("row_id"))
            
            array = eval("arrayRow"+row_id);
            
			row_account_table = "";
			
			//id, name, import, rectified
            $.each(array, function(key,value){
				
                row_account_table += ("<tr><td>" + value.name + "</td><td class=\"modal_row_import\">" + number_format(value.import, 0, ',', '.') + "</td></tr>");
                
            })
			
			row_account_table += "<tr><td><b>Importo riga:</b></td><td class=\"modal_row_import\"><b>" + rowImport + "</b></td></tr>";
            
            
            
            $('#modal_row').find("#modal_row_title").text(name.substring(name.indexOf(")", 1) + 1,name.length));
            $('#modal_row').find("#table_row_contnent").html(row_account_table);
            
            
            $('#modal_row').modal('show');       
        });
		
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
		
		$(document).ready(function(){
			$("#btn_hide_row").trigger("click");
		})
			
    </script>
</section>
    