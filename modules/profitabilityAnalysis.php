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
	.box-title{
		display: block !important;
		text-align: center;
		font-size: 18px;
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
	<!------------------------------------------------------------------- ROE ----------------------------------------------------------------->
	<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">ROE = Tasso di redditività del capitale proprio</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
			<?php 
				if(isset($i['U']) && isset($i['CP']) && $i['CP'] !== 0){
					$res = number_format(($i['U']/$i['CP'])*100,1) . "%";
					$color = "green";
				}else{
					$res = "-";
					$color = "red";
				}
				
				printIndex($color,"ROE",$res); 
				printFraction("ROE", "Utile", "Capitale Proprio", "%", $i['U'], $i['CP']);
			?>
			Per poter valutare la convenienza a investire in una impresa non è sufficiente considerare il
			solo risultato economico in valore assoluto; occorre considerare sempre il risultato economico
			in rapporto al capitale impiegato.
			Quando depositiamo una somma in banca, chiediamo sempre quale sia il tasso netto di
			interesse applicato.
			Chiediamo, cioè, quanto mi verrà remunerato il capitale depositato (investito).
			Ad esempio se la banca applica un tasso del 3% significa dire che per ogni € 100 di capitale
			depositato saranno corrisposte € 3 di rendimento in un anno.
			Il ROE esprime lo stesso concetto: ci dice quanto è il rendimento di € 100 di capitale investito
			dai soci nell’impresa.
			Per poter dire se un dato valore di ROE è buono o cattivo bisogna metterlo a confronto con il
			rendimento di investimenti alternativi a basso rischio (BOT, CCT, depositi bancari, ecc.)
			attualmente circa 4%. Il ROE può essere considerato soddisfacente se è maggiore, almeno di
			3 o 4 punti %, del tasso di rendimento degli investimenti a basso rischio.
			La differenza fra gli investimenti alternativi “sicuri” (BOT, CCT, ecc.) e il valore del ROE viene
			definita “premio al rischio” in quanto “premia” un investimento rischioso. Se il premio al rischio
			fosse 0 non avrebbe senso investire nell’attività rischiosa (un’impresa) in quanto è possibile
			ottenere la stessa remunerazione senza rischiare nulla. 
        </div>
      </div>	
	  <!------------------------------------------------------------------- ROI ----------------------------------------------------------------->
	<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">ROI = Tasso di redditività del capitale investito </h3>
        </div><!-- /.box-header -->
        <div class="box-body">
			<?php 
				if(isset($i['RO']) && isset($i['TI']) && $i['TI'] !== 0){
					$res = number_format(($i['RO']/$i['TI'])*100,1) . "%";
					$color = "green";
				}else{
					$res = "-";
					$color = "red";
				}
				
				printIndex($color,"ROI",$res); 
				printFraction("ROI", "Reddito Operativo", "Totale Impieghi", "%", $i['RO'], $i['TI']);
			?>
			Il ROI rappresenta un ulteriore passo avanti rispetto al ROE; esso rappresenta il rendimento
			dell’attività tipica confrontato con tutti gli investimenti effettuati nella attività tipica.
			Il ROI sintetizza il rendimento della gestione tipica dell'azienda in base a tutto il capitale in
			essa investito (capitale proprio + capitale di terzi), al lordo degli oneri finanziari, degli oneri
			fiscali ed è indipendente dai risultati della gestione non caratteristica e straordinaria.
			Tale indice evidenzia la nostra bravura nel far fruttare sia il capitale dei soci (capitale proprio)
			ma anche quello dei terzi finanziatori (debiti).
			In definitiva il ROI prescinde completamente da ogni considerazione di natura finanziaria e
			fiscale. Esprime pertanto il rendimento dell'investimento effettuato nell'attività tipica
			dell'azienda ed esso dovrà successivamente essere suddiviso in tre componenti:<br>
			a) la remunerazione dei finanziamenti dei terzi;<br>
			b) l'incidenza fiscale;<br>
			c) l'utile degli azionisti o soci.<br>
			L'impresa potrà confrontare il proprio indice ROI con quello dei concorrenti allo scopo di
			comprendere meglio le risultanze del proprio rendimento dell'investimento nella gestione
			caratteristica rispetto a quello degli altri operatori. Nell'ipotesi in cui esso risulti notevolmente
			inferiore, anche alla media del settore, l'impresa stessa dovrà approfondire e cercare i motivi
			per cui essa risulti in stato di crisi. 
        </div>
      </div>	
	<!------------------------------------------------------------------- ROS ----------------------------------------------------------------->
	<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">ROS = Tasso di rendimento sulle vendite</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
			<?php 
				if(isset($i['RO']) && isset($i['RV']) && $i['RV'] !== 0){
					$res = number_format(($i['RO']/$i['RV'])*100,1) . "%";
					$color = "green";
				}else{
					$res = "-";
					$color = "red";
				}
				
				printIndex($color,"ROS",$res); 
				printFraction("ROS", "Reddito Operativo", "Ricavi di Vendita", "%", $i['RO'], $i['RV']);
			?>
			Il ROS esprime la percentuale di guadagno lordo in termini di risultato operativo su 100 di
			vendite nette. L’indice è tanto più soddisfacente quanto più risulta elevato. Il ROS aumenta con
			l’aumentare dei ricavi e con il diminuire dei costi. I ricavi possono aumentare sia
			incrementando il volume delle vendite, sia incrementando i prezzi di vendita. In regime di
			libera concorrenza non è possibile aumentare contemporaneamente il volume di vendita ed i
			prezzi di vendita; infatti, di norma, un aumento dei prezzi di vendita causa una contrazione del
			volume delle vendite. Per questo motivo il valore del ROS deve sempre essere analizzato
			assieme ad un altro indice: il tasso di rotazione degli impieghi.
			Analizzando un bilancio, se ad un miglioramento del ROS corrisponde un tasso di rotazione
			degli impieghi sostanzialmente stabile o addirittura maggiore sicuramente, il miglioramento è
			dovuto ad una riduzione dei costi conseguenti ad un miglioramento dell’efficienza aziendale.
			Naturalmente questo indice influenza notevolmente il ROI (rendimento degli investimenti) e di
			conseguenza anche il ROE (redditività del Capitale proprio): migliore è il ROS tanto migliore
			sarà il ROI
			Un aumento del ROS causa spesso una diminuzione del ROT e viceversa. L’azienda dovrà
			tenere sotto controllo i due indici cercando di trovare il punto di equilibrio in cui il ROI esprime
			il valore più alto possibile
        </div>
      </div>	
	<!------------------------------------------------------------------- ROT ----------------------------------------------------------------->
	<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">ROT = Indice di rotazione degli impieghi </h3>
        </div><!-- /.box-header -->
        <div class="box-body">
			<?php 
				if(isset($i['TI']) && isset($i['RV']) && $i['RV'] !== 0){
					$res = number_format(($i['RV']/$i['TI'])*100,1);
					$color = "green";
				}else{
					$res = "-";
					$color = "red";
				}
				
				printIndex($color,"ROT",$res); 
				printFraction("ROT", "Ricavi di Vendita", "Totale Impieghi", "", $i['RV'], $i['TI']);
			?>
			 ROT esprime il grado di sfruttamento degli impianti e la dinamicità
			dell’impresa sul mercato.
			Il ROT esprime il numero di volte in cui il capitale investito ritorna sotto forma di vendite in un
			anno amministrativo. Se l’indice è pari a 12 significa che il capitale investito ritorna sotto forma
			di vendite una volta al mese. L’indice aumenta, a parità di capitale impiegato, con l’aumentare
			del volume delle vendite.
        </div>
      </div>	
	<!------------------------------------------------------------------- LEVERAGE ----------------------------------------------------------------->
	<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Indice di indebitamento. LEVERAGE  </h3>
        </div><!-- /.box-header -->
        <div class="box-body">
			<?php 
				if(isset($i['TI']) && isset($i['CP']) && $i['CP'] !== 0){
					$res = number_format(($i['TI']/$i['CP']),1);
					$color = "green";
				}else{
					$res = "-";
					$color = "red";
				}
				
				printIndex($color,"LEV",$res); 
				printFraction("LEV", "Totale Impieghi", "Capitale Proprio", "", $i['TI'], $i['CP']);

			?>
			 Nella formula di tale indice, stranamente, non vengono espressamente indicati i debiti.
			Tale espressione dell’indice di indebitamento è una delle tante che possiamo produrre ( quali
			CAPITALE PROPRIO/CAPITALE DI TERZI oppure TOTALE DEBITI/TOTALE FONTI, ecc.)
			utilizziamo questa configurazione per mettere meglio in evidenza l’effetto LEVA.
			Più il capitale proprio (denominatore della formula) è basso rispetto al totale degli impieghi, più
			l’indebitamento aumenta e aumenta anche l’indice. In generale l’indice è tanto più
			soddisfacente quanto più è basso.
			Il Leverage, anche se non rappresenta un indice di redditività, influenza direttamente il ROE,
			come sopra evidenziato.
			Il Leverage dimostra in che modo l’azienda riesce a finanziare i propri investimenti ed in
			particolare se con prevalenza di capitale proprio o di capitale di terzi.
			<br>- LEVERAGE = 1 significa che tutti gli investimenti sono finanziati con capitale proprio,
			situazione più teorica che non effettiva ( assenza di capitale di terzi ); 
			<br>- LEVERAGE è compreso tra 1 e 2 si verifica una situazione di positività, in quanto l’azienda
			possiede un buon rapporto tra capitale proprio e di terzi ( quest’ultimo si mantiene al di sotto
			del 50%);
			<br>- LEVERAGE è > 2 segnala una situazione di indebitamento aziendale, che diventa più onerosa
			per l’azienda al crescere di tale indice.<br>
			<b>Tanto più elevato è l’indice tanto maggiore è l’indebitamento.</b>
        </div>
      </div>
	<!------------------------------------------------------------------- Incidenza gestione non caratt. ----------------------------------------------------------------
	<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Incidenza gestione non caratteristica</h3>
        </div><!-- /.box-header
        <div class="box-body">
			<?php 
				if(isset($i['U']) && isset($i['RO']) && $i['RO'] !== 0){
					$res = number_format(($i['U']/$i['RO'])*100,1) . "%";
					$color = "green";
				}else{
					$res = "-";
					$color = "red";
				}
				
				printIndex($color,"GES",$res); 
			?>
        </div>
      </div>-->
</section>        
<script>
	$("#print").click(function(){
		window.print();
	});			
</script>