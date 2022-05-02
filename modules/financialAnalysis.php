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
		echo '	<div class="col-md-4">
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
	<!------------------------------------------------------------------- Patrimonio Circolante Netto ----------------------------------------------------------------->
	<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Patrimonio Circolante Netto</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
			<?php 
				if(isset($i['AC']) && isset($i['DB'])){
					$res = $i['AC'] - $i['DB'];
					$color = "green";
				}else{
					$res = "-";
					$color = "red";
				}
				printIndex($color,"PCN",$res); 
			?>
        </div>
      </div>		
	<!------------------------------------------------------------------- Indice di disponibilità ----------------------------------------------------------------->
	<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Indice di disponibilità</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
			<?php 
				if(isset($i['AC']) && isset($i['DB']) && $i['DB'] !== 0){
					$res = number_format(($i['AC']/$i['DB']),1);
					$color = "green";
				}else{
					$res = "-";
					$color = "red";
				}
				printIndex($color,"Indice di disponibilità",$res); 
				printFraction("Indice di disponibilità", "Attivo Corrente", "Debiti a breve termine", "", $i['AC'], $i['DB']);
			?>
			Esprime la capacità di far fronte ai debiti a breve utilizzando le disponibilità a breve
			(magazzino, disponibilità, liquidità). E’ considerato soddisfacente un indice vicino a 2. Un
			valore inferiore a 1 segnala gravi problemi di solvibilità nel breve periodo. 
        </div>
      </div>
	<!------------------------------------------------------------------- Indice di Autocopertura ----------------------------------------------------------------->
	<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Indice di Autocopertura delle immobilizzazioni</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
			<?php 
				if(isset($i['CP']) && isset($i['AI']) && $i['AI'] !== 0){
					$res = number_format(($i['CP']/$i['AI']),1);
					$color = "green";
				}else{
					$res = "-";
					$color = "red";
				}
				printIndex($color,"Indice di Autocopertura",$res); 
				printFraction("Indice di Autocopertura", "Capitale Proprio", "Attivo Immobilizzato", "", $i['CP'], $i['AI']);
			?>
			Segnala se il capitale proprio copre le immobilizzazioni, riuscendole a finanziare interamente.
			Un valore maggiore di 1 indica una situazione ottima. Un indice pari a 1 indica che tutte le
			immobilizzazioni sono finanziate con capitale proprio. Un indice inferiore deve essere
			ulteriormente approfondito in quanto se la parte di immobilizzazioni non coperta dal capitale
			proprio è finanziata da debiti a lungo esiste ugualmente equilibrio fra fonti e impieghi. Se
			invece se la parte di immobilizzazioni, non coperta dal capitale proprio, è finanziata da debiti a
			breve, sicuramente ci si trova di fronte ad uno scorretto utilizzo delle fonti di finanziamento
			con conseguenti problemi di squilibrio finanziario; 
        </div>
      </div>	
	<!------------------------------------------------------------------- Indice di Autocopertura Globale ----------------------------------------------------------------->
	<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Indice di Autocopertura delle immobilizzazioni</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
			<?php 
				if(isset($i['CP']) && isset($i['AI']) && isset($i['DM']) && $i['AI'] !== 0){
					$res = number_format((($i['CP'] + $i['DM'])/$i['AI']),1);
					$color = "green";
				}else{
					$res = "-";
					$color = "red";
				}
				printIndex($color,"Indice di Autocop Glob",$res); 
				printFraction("Indice di Autocopertura Globale", "Capitale Permanente", "Attivo Immobilizzato", "", $i['CP'] + $i['DM'], $i['AI']);
			?>
			Dove il capitale permanente è la somma fra capitale proprio e debiti a medio/lungo termine.
			Questo indice segnala se il capitale permanente copre (finanzia) le immobilizzazioni.
			<br>- Un indice > 1 segnala una situazione ottimale ed un corretto utilizzo delle fonti di
			finanziamento (tutti gli investimenti a medio/lungo termine: immobilizzazioni sono finanziate
			con capitale destinato a rimanere vincolato in azienda per periodi medio lunghi: capitale
			proprio più debiti a lungo. 
			<br>- Un indice pari a 1 indica che tutte le immobilizzazioni sono finanziate con capitale
			permanente.
			<br>- Un indice inferiore a 1 segnala uno squilibrio nella relazione tra investimenti e finanziamenti;
			valori compresi tra 1 e 1,50 indicano una situazione di squilibrio finanziario da tenere sotto
			controllo; un valore superiore a 1,50 indica una situazione equilibrata;
			<br>- Un valore > 2 indica una situazione ottima. 
        </div>
      </div>
	<!------------------------------------------------------------------- Margine Struttura ----------------------------------------------------------------->
	<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Margine Struttura</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
			<?php 
				if(isset($i['CP']) && isset($i['AI']) && isset($i['DM']) && $i['AI'] !== 0){
					$res = $i['CP'] - $i['AI'];
					$color = "green";
				}else{
					$res = "-";
					$color = "red";
				}
				printIndex($color,"Margine Struttura",$res); 
			?>
        </div>
      </div>	<!------------------------------------------------------------------- Margine copertura Globale ----------------------------------------------------------------->
	<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Margine Copertura Globale</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
			<?php 
				if(isset($i['CP']) && isset($i['AI']) && isset($i['DM']) && $i['AI'] !== 0){
					$res =($i['CP'] + $i['DM']) - $i['AI'];
					$color = "green";
				}else{
					$res = "-";
					$color = "red";
				}
				printIndex($color,"Margine Copertura Globale",$res); 
			?>
        </div>
      </div>
	<!------------------------------------------------------------------- Indice di Liquidità primaria  ----------------------------------------------------------------->
	<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Indice di Liquidità Primaria</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
			<?php 
				if(isset($i['DL']) && isset($i['DB']) && $i['DB'] !== 0){
					$res = number_format(($i['DL']/$i['DB']),1);
					$color = "green";
				}else{
					$res = "-";
					$color = "red";
				}
				printIndex($color,"Liquidità Primaria",$res); 
				printFraction("Liquidità Primaria", "Disponibilià Liquide", "Debiti a breve scadenza", "", $i['DL'], $i['DB']);
			?>

        </div>
      </div>	
	<!------------------------------------------------------------------- Indice di Liquidità secondaria  ----------------------------------------------------------------->
	<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Indice di Liquidità Secondaria</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
			<?php 
				if(isset($i['DL']) && isset($i['DB']) && isset($i['DF']) && $i['DB'] !== 0){
					$res = number_format((($i['DL'] + $i['DF']) /$i['DB']),1);
					$color = "green";
				}else{
					$res = "-";
					$color = "red";
				}
				printIndex($color,"Liquidità Secondaria",$res); 
				printFraction("Liquidità Secondaria", "Disponibilià Liquide + Disponibilià Finanziarie", "Debiti a breve scadenza", "", $i['DL'] + $i['DF'], $i['DB']);
			?>
				Al numeratore l’attivo circolante senza le scorte; al denominatore, come nel current ratio, i
				debiti a breve.
				Esprime la capacità di far fronte ai debiti a breve utilizzando le disponibilità a breve, senza
				considerare le scorte di magazzino che, per quanto riguarda la scorta di sicurezza, è più
				immobilizzazione che attivo circolante . E’ considerato soddisfacente un indice vicino a 1. Un
				valore inferiore segnala problemi di solvibilità nel breve periodo.
				Se sono disponibili i dati riguardanti le scadenze dei debiti a breve, impossibile per un analista
				esterno, è possibile calcolare un indice ancora più preciso che confronta le liquidità immediate
				ed i debiti di prossima scadenza (entro un mese). Indice di liquidità secondaria o indice secco
				di liquidità. 
        </div>
      </div>		
</section>        
<script>
	$("#print").click(function(){
		window.print();
	});			
</script>