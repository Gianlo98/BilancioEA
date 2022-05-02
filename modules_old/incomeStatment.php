<link  rel="stylesheet"  href="dist/css/incomeStatmentStyle.css"/>
<section class="content-header">
  <h1 class="income-statment-title">
	Conto Economico
	<small>Esercizio 6.5</small>
  </h1>
  <ul class="income-statment-buttons">
	<li class="income-statment-button"> <button class="btn btn-info income-stat-btn" id="add_round" data-toggle="modal" data-target="#modalRounding">Aggiungi Arrotondamento</button></li>
    <li class="income-statment-button"> <button class="btn btn-info income-stat-btn" id="btn_hide_row">Mostra solo righe utilizzate</button></li>
    <li class="income-statment-button">  <button class="btn btn-info income-stat-btn" id="print" >Stampa</button></li>
  </ul>
</section>

<section class="content">
    <div style="background-color:white">
        <table class="table table-striped table-hover ">
            <tbody>
                <?php require(dirname(__FILE__) . "/../utils/income_statment_row_utils.php");
                
                var_dump(json_encode($rows));
                
                //Controllo se sono stati aggiunti arrotondamenti
                if(isset($_POST["round"]) && !empty($_POST["round"])) {
                    $round = $_POST["round"];
                    if($round >= 0){
                        addUserAccount("21.10",$round);
                    }else{
                        addUserAccount("39.10",$round);
                    }
                }     
                //Row Index
                $index = 3;

                //Stampo tutti i conti della lettera A;
                $totalA = 0;
                $row_A = $rows[1];
				
                print_row($row_A);
                $current_row = $rows[2];
                do{
                    $totalA += print_row($current_row);
                    $current_row = $rows[$index++];
                    if(is_null($current_row->getParent())) break;
                } while($current_row->getParent() == $row_A->getId() || $rows[$current_row->getParent()]->getParent() == $row_A->getId());
				
                print_custom_row("","Totale:",$totalA,"row_final");

                 //Stampo tutti i conti della lettera B;
                $totalB = 0;
                $row_B = $current_row;

                print_row($row_B);
                $current_row = $rows[$index++];
                do{
                    $totalB += print_row($current_row);
                    $current_row = $rows[$index++];
                    if(is_null($current_row->getParent())) break;
                } while($current_row->getParent() == $row_B->getId() || $rows[$current_row->getParent()]->getParent() == $row_B->getId());

                print_custom_row("","Totale:",$totalB,"row_final");

                //Stampo la differenza A-B
                print_custom_row("","Differenza tra valori e costi della produzione",$totalA - $totalB,"row_final");

                //Stampo tutti i conti della lettera C;
                $totalC = 0;
                $row_C = $current_row;

                print_row($row_C);
                $current_row = $rows[$index++];
                do{
						
					if ($current_row->getId() == 33) {
						
						//Se la riga è un interesse/onere finanziario, va sottratto anzichè sommarlo
						$totalC -= print_row($current_row);
					}else{
						
						$totalC += print_row($current_row);
					}
					
                    $current_row = $rows[$index++];
                    if(is_null($current_row->getParent())) break;
                } while($current_row->getParent() == $row_C->getId() || $rows[$current_row->getParent()]->getParent() == $row_C->getId());

                print_custom_row("","Totale:",$totalC,"row_final");       

                //Stampo tutti i conti della lettera D;
                $totalD = 0;
                $row_D = $current_row;

                print_row($row_D);
                $current_row = $rows[$index++];
                do{
                    $totalD += print_row($current_row);
                    $current_row = $rows[$index++];
                    if(is_null($current_row->getParent())) break;
                } while($current_row->getParent() == $row_D->getId() || $rows[$current_row->getParent()]->getParent() == $row_D->getId());
                print_custom_row("","Totale:",$totalD,"row_final");

                //Stampo le righe finali
                print_custom_row("","Totale delle rettifiche",$totalD,"row_final");
                $total = $totalA - $totalB + $totalC + $totalD;
                print_custom_row("","Risultato prima delle imposte",$total,"row_final");
                $total -= print_custom_row("20","imposte sul reddito dell'esercizio, correnti, differite e anticipate",$accounts["60.01"]->getValue(),"row_normal");
                print_custom_row("","Utile (perdita) d'esercizio",$total,"row_final"); 
					
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
                         <form method="POST">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Importo dell'arrotondamento:</label>
                                    <input type="number" name="round" class="form-control" id="recipient-name">
                                    <small for="recipient">Se l'arrotondamento è passivo inserirlo col segno -</small>
                                </div>
                                <button type="submit" class="btn btn-primary">Aggiungi</button>
                         </form>
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
			
    </script>
</section>