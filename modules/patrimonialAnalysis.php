<link  rel="stylesheet"  href="plugins/BilancioEA/css/BalancePrintUtils.css"/>
<link  rel="stylesheet"  href="plugins/BilancioEA/css/balanceSheet.css"/>

<?php
    require(dirname(__FILE__) . "/../utils/core/Statement/indexManager.php");
    $indexManager = new indexManager();
	$i = $indexManager->getDataArray();
	//var_dump($i);
?>

<style>
	.info-box-number{
		display: block;
		font-weight: bold;
		font-size: 50px;
		text-align: center;
	}
	.box-body{
		font-size: 16px;
	}
	.fraction, .top, .bottom {
		padding: 0 5px;    
	}

	.fraction {
		display: inline-block;
		text-align: center;    
	}

	.bottom{
		border-top: 1px solid #000;
		display: block;
	}

	.operator {
	  overflow: auto;
	  display: block;
	  margin-bottom: 5px;
	}
	.info-box-icon {
		width: 200px;
		font-size: 0.9vw;
	}
	.box-title{
		display: block !important;
		text-align: center;
		font-size: 8px;
	}
</style>

<section class="content-header">
  <h1 class="balance-sheet-title">
	Analisi della Reddività
	<small>
		<?php
			if(isset($_SESSION['exercise'])) echo "Esercizio " . $_SESSION['exercise'];
		?>
	</small>
  </h1>
  <ul class="balance-sheet-buttons">
    <li class="balance-sheet-button">  <button class="btn btn-info balance-she-btn" id="print" >Stampa</button></li>
  </ul>
</section>

<section class="content">



	<?php 
	
	function printIndex($colorClass = 'red',$name,$index){
		echo '	<div class="col-lg-3">
					<div class="info-box">
						<span class="info-box-icon bg-'. $colorClass . '"> ' . $name . ' </span>
						<div class="info-box-content">
							<span class="info-box-text"></span>
							<span class="info-box-number">' . $index . '</span>
						</div>
					</div>
				</div>';
	}
	
	function printFraction($indexName, $numeratorName, $denominatorName, $symbols = '', $numeratorImport = null, $denominatorImport = null){
		echo '			<div class="fraction">
				<span class="operator">'.$indexName.' = </span>
			</div>
			<div class="fraction">
			  <span class="top"> ' . $numeratorName . (!is_null($numeratorImport) && $numeratorImport !== 0 ? " ($numeratorImport)" : '') . ' </span>
			  <span class="bottom">'. $denominatorName . (!is_null($denominatorImport) && $denominatorImport !== 0 ? " ($denominatorImport)" : '') .'</span>
			</div>
			<div class="fraction">
				<span class="operator">'.$symbols.'</span>
			</div>';
	}
	
	


	?>
	<!------------------------------------------------------------------- Rigidità degli Impieghi ----------------------------------------------------------------->
	<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Rigidità degli Impieghi</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
			<?php 
				if(isset($i['AI']) && isset($i['TI']) && $i['TI'] !== 0){
					$res = number_format(($i['AI']/$i['TI']),1) . "%";
					$color = "green";
				}else{
					$res = "-";
					$color = "red";
				}
				printIndex($color,"Rigidità impieghi",$res); 
				printFraction("Rigidità degli Impieghi", "Attivo Immobilizzato", "Totale Impieghi", "%", $i['AI'], $i['TI']);
			?>
				Esprime la percentuale di impieghi a lungo ciclo di utilizzo rispetto al totale impieghi. Tale
				indice è complementare all'indice di elasticità degli impieghi.
				A parità di altre condizioni è preferibile un valore basso.
				Esso dipende dal tipo di attività svolta(un’azienda mercantile avrà meno immobilizzazioni e più
				attivo circolante di una impresa industriale) e dalla struttura tecnico produttiva della azienda
				stessa.
        </div>
      </div>		
	<!------------------------------------------------------------------- Elasticità degli Impieghi ----------------------------------------------------------------->
	<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Elasticità degli Impieghi</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
			<?php 
				if(isset($i['AC']) && isset($i['TI']) && $i['TI'] !== 0){
					$res = number_format(($i['AC']/$i['TI']),1) . "%";
					$color = "green";
				}else{
					$res = "-";
					$color = "red";
				}
				printIndex($color,"Elasticità impieghi",$res); 
				printFraction("Elasticità degli Impieghi", "Attivo Corrente", "Totale Impieghi", "%", $i['AC'], $i['TI']);
			?>
				Esprime la percentuale di impieghi a breve ciclo di utilizzo rispetto al totale impieghi. Tale
				indice è complementare all'indice di rigidità degli impieghi.
				A parità di altre condizione è preferibile un valore alto. 
        </div>
      </div>	
	<!------------------------------------------------------------------- Elasticità globale ----------------------------------------------------------------->
	<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Elasticità globale</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
			<?php 
				if(isset($i['AC']) && isset($i['AI']) && $i['AI'] !== 0){
					$res = number_format(($i['AC']/$i['AI']),1) . "%";
					$color = "green";
				}else{
					$res = "-";
					$color = "red";
				}
				printIndex($color,"Elasticità globale",$res); 
				printFraction("Elasticità globale", "Attivo Corrente", "Attivo Immobilizzato", "%", $i['AC'], $i['AI']);
			?>
				Esprime il rapporto tra attivo circolante e attivo immobilizzato. Quanto più è alto l’indice tanto
				più è elastica la gestione dell’azienda. Una bassa elasticità esprime un certo grado di
				immobilizzo degli impieghi. Un indice pari a 1 esprime l’uguaglianza tra impieghi a breve e
				impieghi a lungo termine. Una bassa elasticità può segnalare problemi di struttura e di
				immobilizzo. E’ necessario tenere presente che alcune imprese hanno per loro natura una
				bassa elasticità in quanto per attuare l’attività economica necessitano di notevoli immobilizzi in
				beni strumentali (imprese industriali, imprese di trasporto.
        </div>
      </div>	
	<!------------------------------------------------------------------- Autonomia finanziaria ----------------------------------------------------------------->
	<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Autonomia finanziaria</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
			<?php 
				if(isset($i['CP']) && isset($i['TI']) && $i['TI'] !== 0){
					$res = number_format(($i['CP']/$i['TI']),1) . "%";
					$color = "green";
				}else{
					$res = "-";
					$color = "red";
				}
				printIndex($color,"Autonomia finanziaria",$res); 
				printFraction("Autonomia finanziaria", "Capitale Proprio", "Totale Finanziamenti", "%", $i['CP'], $i['TI']);
			?>
				Il totale dei finanziamenti sono dati dal totale delle passività più il patrimonio netto.
				L’indice di autonomia finanziaria esprime il rapporto tra capitale netto e totale
				finanziamenti. L’autonomia finanziaria aumenta con l’aumentare del capitale netto. Un
				indice pari a 100 indica che tutti i finanziamenti sono rappresentati da capitale
				proprio.
				Un indice inferiore a 33 segnala una bassa autonomia finanziaria e una struttura
				finanziaria pesante; valori compresi tra 33 e 55 segnalano una struttura finanziaria da
				tenere sotto controllo; valori tra 55 e 66 evidenziano una struttura soddisfacente;
				valori superiori a 66 indicano notevoli possibilità di sviluppo. 
        </div>
      </div>
	<!------------------------------------------------------------------- Grado di capitalizzazione	----------------------------------------------------------------->
	<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Autonomia finanziaria</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
			<?php 
				if(isset($i['CP']) && isset($i['CD']) && $i['CD'] !== 0){
					$res = number_format(($i['CP']/$i['CD']),1);
					$color = "green";
				}else{
					$res = "-";
					$color = "red";
				}
				printIndex($color,"Grado di capitalizzazione",$res); 
				printFraction("Grado di capitalizzazione", "Capitale Proprio", "Capitale di debito", "", $i['CP'], $i['CD']);
			?>
        </div>
      </div>
	<!------------------------------------------------------------------- Incidenza debiti a m/l termine ----------------------------------------------------------------->
	<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Incidenza debiti a m/l termine</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
			<?php 
				if(isset($i['DM']) && isset($i['TI']) && $i['TI'] !== 0){
					$res = number_format(($i['DM']/$i['TI']),1) . "%";
					$color = "green";
				}else{
					$res = "-";
					$color = "red";
				}
				printIndex($color,"Incidenza debiti a breve",$res); 
				printFraction("Incidenza debiti a m/l termine", "Passività consolidate", "Totale Finanziamenti", "%", $i['DM'], $i['TI']);
			?>
        </div>
      </div>
	<!------------------------------------------------------------------- Incidenza debiti a breve ----------------------------------------------------------------->
	<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Incidenza debiti a breve</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
			<?php 
				if(isset($i['DB']) && isset($i['TI']) && $i['TI'] !== 0){
					$res = number_format(($i['DB']/$i['TI']),1) . "%";
					$color = "green";
				}else{
					$res = "-";
					$color = "red";
				}
				printIndex($color,"Incidenza debiti a breve",$res); 
				printFraction("Incidenza debiti a breve", "Passività a breve", "Totale Finanziamenti", "%", $i['DB'], $i['TI']);
			?>
        </div>
      </div>	
</section>        
<script>
	$("#print").click(function(){
		window.print();
	});			
</script>