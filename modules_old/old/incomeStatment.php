<html>
	<?php
		require("config/db_config.php");
		$database = new mysqli($db_host, $db_username, $dv_password, $db_name);
		mysqli_set_charset($database, 'utf8');
		
		session_start();
		if(!isset($_SESSION['accounts'])){
			header('Location: index.php');
			exit;
		}
		
		require("core/income_statment_utils.php");
	?>
	<style>
        .income_statement_row:hover{
            color:red !important;
        }
		.modal_row_import{
			text-align:right;
			padding-right:10% !important;
		}
		.hidden{
			color:red;
		}
		balance_sheet_utils::after{
			content: " - Remember this" !important;
			background-color: yellow;
			color: red;
			font-weight: bold;
		}
		.row_final, .row_super{
			 font-weight: bold;
		}
		.row_normal > td:nth-child(1){
			padding-left: 3%;
		}
		.row_child > td:nth-child(1){
			padding-left: 6%;
		}
		.row_final .row_import{
			border-bottom: 1px solid black;
			position: relative;
		}
		.row_final .row_import::AFTER{
			border-bottom: 1px solid black;
			position: absolute;
			width: 100%;
			left: 0;
			content: "";
			bottom: 2%;
		}
		.row_import{
			text-align: center;
		}
        .balance_sheet_utils{
            color:blue;
			position: fixed;
        }
		.mobile-top-button{
			width: 100%;
			margin-bottom: 0.3em !important;
		}
		@media screen and (min-width: 0px){
			.md-device-utils{
				visibility: hidden;
			}
			.small-device-utils{
				display:block;
			}
		}
		@media screen and (min-width: 1200px){
			.md-device-utils{
				visibility: visible;
			}
			.small-device-utils{
				display:none;
			}
		}
		#balance_header{
			color: white;
			margin-bottom: 0.5em;
		}
		
		.wrapper{
		position: absolute;
		margin: auto;
		width: 60%;
		left: 20%;
		}

	</style>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<link rel="stylesheet" href="css/animate.css">
		<link rel="stylesheet" href="css/print.css" type="text/css" media="print" />
		<script src="js/jquery-2.2.4.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<link  rel="stylesheet"  href="css/select2.min.css"/>
		<script src="js/select2.min.js"></script>
		<script src="js/number_format.js"></script>
		<script src="js/jspdf.min.js"></script>
	</head>
	
	<body>
        <?php require("core/navbar.php");
		
			//Verifico se ci sono arrotondamenti
			if(!empty($_POST["rounds"])){
				$round = $_POST["rounds"];
				if($round >= 0){
					addUserAccount("21.10",$round);
				}else{
					addUserAccount("39.10",$round);
				}
			}			
		
		?>
			<div class="balance_sheet_utils noPrint">
				<ul class="nav nav-pills nav-stacked md-device-utils animated bounceInLeft">
				 <li role="presentation"> <button class="btn btn-info mobile-top-button" id="add_round" data-toggle="modal" data-target="#modalRounding" >Aggiungi Arrotondamento</button> </li>
				 <li role="presentation"> <button class="btn btn-info mobile-top-button" id="btn_hide_row">Mostra solo righe utilizzate</button> </li>
				 <li role="presentation"> <button class="btn btn-info mobile-top-button" id="print" >Stampa</button> </li>
				 </ul>
			</div>
		<div class="wrapper bodyPrint">
			<div class="btn-group" data-toggle="buttons" style="    
                padding: 2%;
                width: 100%;
                text-align: center;
                background-color: #333333;
                position: relative;
			">
				<h1 id="balance_header">Conto Economico</h1>
				<div class="small-device-utils noPrint">
					<button class="btn btn-info mobile-top-button" id="add_round" data-toggle="modal" data-target="#modalRounding">Aggiungi Arrotondamento</button>
					<button class="btn btn-info mobile-top-button" id="btn_hide_row">Mostra solo righe utilizzate</button>
					<button class="btn btn-info mobile-top-button" id="print" >Stampa</button>
				</div>
			</div>
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<table class="table table-striped table-hover ">
					<tbody>
			<?php
				$rows = generate_balance_sheet_rows();
				
				//Stampo tutti i conti della lettera A;
				print_row($rows, 1);
				$totalA = 0;
				foreach (get_child_row($rows,1) as $key => $row){
					list($id,$char_id,$name,$parent,$childs,$accounts,$import) = array_values($row);
					$totalA += print_row($rows, $id);
				}
				print_custom_row("","Totale:",$totalA,"row_final");
				
				//Stampo tutti i conti della lettera B;
				print_row($rows, 7);
				$totalB = 0;
				foreach (get_child_row($rows,7) as $key => $row){
					list($id,$char_id,$name,$parent,$childs,$accounts,$import) = array_values($row);
					$totalB += print_row($rows, $id);
					if (!empty($childs)){
						foreach($childs as $key => $value){
							$totalB += print_row($rows, $value["row_id"]);
						}
					}
				}
				print_custom_row("","Totale:",$totalB,"row_final");
                        
                //Stampo la differenza A-B
                print_custom_row("","Differenza tra valori e costi della produzione",$totalA - $totalB,"row_final");
                        
                //Stampo tutti i conti della lettera C;
				print_row($rows, 26);
				$totalC = 0;
				foreach (get_child_row($rows,26) as $key => $row){
					list($id,$char_id,$name,$parent,$childs,$accounts,$import) = array_values($row);
				    if ($char_id == 17 || $char_id == 18){
                        $totalC -= print_row($rows, $id);
                    }else{
                        $totalC += print_row($rows, $id);
                    }
					if (!empty($childs)){
						foreach($childs as $key => $value){
							$totalC += print_row($rows, $value["row_id"]);
						}
					}
				}
				print_custom_row("","Totale",$totalC,"row_final");
                
                //Stampo tutti i conti della lettera D;
				print_row($rows, 35);
				$totalD = 0;
				foreach (get_child_row($rows,35) as $key => $row){
					list($id,$char_id,$name,$parent,$childs,$accounts,$import) = array_values($row);
                    $totalD += print_row($rows, $id);
					if (!empty($childs)){
						foreach($childs as $key => $value){
							$totalD += print_row($rows, $value["row_id"]);
						}
					}
				}
				print_custom_row("","Totale delle rettifiche",$totalD,"row_final");
                $total = $totalA - $totalB + $totalC + $totalD;
				print_custom_row("","Risultato prima delle imposte",$total,"row_final");
				$total -= print_custom_row("20","imposte sul reddito dell'esercizio, correnti, differite e anticipate",$rows[46]["row_import"],"row_normal");
                print_custom_row("","Utile (perdita) d'esercizio",$total,"row_final");
			?>
					</tbody>
				</table>
			</form>
		</div>
        
        <!-- Modal per le righe -->
        <div class="modal fade" id="modal_row" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modal_row_title"></h4>
          </div>
          <div class="modal-body">
              <table id="table_row_contnent" class="table table-striped table-hover ">
				
              </table>
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
					<input type="number" name="rounds" class="form-control" id="recipient-name">
					<small for="recipient">Se l'arrotondamento è passivo inserirlo col segno -</small>
				  </div>
				  <button type="submit" class="btn btn-primary">Aggiungi</button>
			</div>
		  </div>
		</div>
	

	</body>
    <script>

        var array;
        $(".income_statement_row").click(function(){
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
</html>
