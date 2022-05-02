<?php
    
    require_once(dirname(__FILE__) . "/../../BilancioEA.php");
    require_once(dirname(__FILE__) . "/../PrintUtils/BalancePrintUtils.php");
    require_once(dirname(__FILE__) . "/balanceSheetManager.php");

  /** Classe che genera e stampa lo stato patrimoniale
    * @author Gianlorenzo Occhipinti <gianlorenzo.occhipinti@gmail.com>
    * @license http://opensource.org/licenses/gpl-license.php GNU Public License
    * @link http://data.baeproject.eu/BilancioEA Home del progetto
    */
    class ReworkedBalanceSheetManager{
        
        
        private $balanceRows;         
        private $balance_Row_manager;         
        private $balance_Account_manager;
		private $balance_Exercise_manager;
        private $balancePrintUtils;
        
		//Proprietà esercizio
		private $resourcesInExercise = null;
		private $creditOverExercise = null;
		private $debitsOverExercise = null;
		private $shareholders = null;
		private $severanceLiquitated = null;
		private $subscribedCapital = null;
		private $statutory_reserve = null;
		private $straordinary_reserve = null;
        
		//Riparto Utile
        private $totalToShare;
        private $retained_earnings;
        private $share;
        private $totalToSplit;
		
        private $withProfitSharing;
		
		//Importi Impieghi
		private $financial_resources;
		private $liquid_resources;
		private $inventories;
		private $material_assets;
		private $unmaterial_assets;
		private $financtial_assets;
		private $total_investments;
		
		//Importi Fonti
		private $debts_capital;
		private $debts_ml_term;
		private $equity;
		private $equity_capital;
		private $reserve;
		private $profit;
		
		
       /**
        * Metodo costruttore
		* @param boolean $withProfitSharing true = con riparto utile, false = senza riparto utile
        */
        function __construct($withProfitSharing = false){
            $balanceObject = new BilancioEA();
            $this->balance_Row_manager = $balanceObject->getBalanceRowManager();
            $this->balance_Account_manager = $balanceObject->getBalanceAccountManager();
			$this->balance_Exercise_manager = $balanceObject->getExerciseManager();
            $this->balanceRows = $this->balance_Row_manager->getBalanceSheetRows();
            $this->balancePrintUtils = new BalancePrintUtils($this->balanceRows);
            $this->withProfitSharing = $withProfitSharing;
			
			$ex_proprieties = array();
			$ex_id = $this->balance_Exercise_manager->getUserExerciseID();
			if($ex_id){
				$ex_proprieties = $this->balance_Exercise_manager->getExerciseProperties($ex_id);
			}
			
			foreach($ex_proprieties as $key => $proprieties){
				$pr_value = ($proprieties['exercise_propriety_value'] !== "") ? $proprieties['exercise_propriety_value'] : null;
				switch($proprieties['exercise_propriety']){
					case "CREDIT_AFTER_YEAR":
						$this->creditOverExercise = $pr_value;
						break;					
					case "LTRESOURCES_IN_YEAR":
						$this->resourcesInExercise = $pr_value;
						break;					
					case "DEBTS_AFTER_YEAR":
						$this->debitsOverExercise = $pr_value;
						break;					
                    case "SHAREHOLDERS":
						$this->shareholders = $pr_value;
						break;					
                    case "SEVERANCE_PAY_LIQUIDATED":
						$this->severanceLiquitated = $pr_value;
						break;                    
                    case "SUBSCRIBED_CAPITAL":
						$this->subscribedCapital = $pr_value;
						break;
                    case "STATUTORY_RESERVE":
                        $this->statutory_reserve = $pr_value;
                        break;                       
                    case "STRAORDINARY_RESERVE":
                        $this->straordinary_reserve = $pr_value;
                        break;
				}
			}
            
            $this->calculateProfitSharing();
           
        }
       /**
        * Funzione che stampa l'attivo dello stato patrimoniale rielaborato
		*/
        public function printReworkedBalanceSheetInvestments(){
			$this->generateReworkedBalanceSheetInvestments();
            
            $this->balancePrintUtils->print_custom_row("","Attivo Corrente",-1,"row_super");
            $this->balancePrintUtils->print_custom_row("","Disponibilità liquide",$this->liquid_resources,"row_child");
            $this->balancePrintUtils->print_custom_row("","Disponibilità finanziarie ",$this->financial_resources,"row_child");
            $this->balancePrintUtils->print_custom_row("","Rimanenze",$this->inventories,"row_child");            
            $this->balancePrintUtils->print_custom_row("","Totale",$this->financial_resources + $this->liquid_resources + $this->inventories,"row_total");
            
            $this->balancePrintUtils->print_custom_row("","Immobilizzazioni",-1,"row_super");
            $this->balancePrintUtils->print_custom_row("","Immobilizzazioni Immateriali",$this->unmaterial_assets,"row_child"); 
            $this->balancePrintUtils->print_custom_row("","Immobilizzazioni Materiali",$this->material_assets,"row_child");
            $this->balancePrintUtils->print_custom_row("","Immobilizzazioni Finanziarie ",$this->financtial_assets,"row_child");
            $this->balancePrintUtils->print_custom_row("","Totale",$this->unmaterial_assets + $this->material_assets + $this->financtial_assets,"row_total");
				   
            //Stampo totale impieghi
            $this->balancePrintUtils->print_custom_row("","Totale Impieghi",$this->total_investments,"row_final");
        }
		
	   /**
        * Funzione che genera l'attivo dello stato patrimoniale rielaborato
        */	
		
		public function generateReworkedBalanceSheetInvestments(){
			//A) CREDITI V SOCI, parte già richiamata dagli amministratori tra disponibilità liquide, resto disponibilità finanziarie
			//B III) Immob Finanz, rimborso entro esercizio disp finanziare
			//D) Ratei e risconti, disp finanziarie
			
			//Disponibilità Finanziare
            $this->financial_resources = $this->getTotalRow(BalanceSheetManager::BALANCE_SHEET_A_D_INDEX); //Ratei e risconti
            $this->financial_resources += $this->getTotalRow(85) - $this->creditOverExercise; //Totale crediti - crediti oltre esercizio
            $this->financial_resources += $this->getTotalRow(94); //Totale crediti - crediti oltre esercizio
            $this->financial_resources += $this->getTotalRow(BalanceSheetManager::BALANCE_SHEET_A_A_INDEX) - $this->subscribedCapital; //Parte sottoscritta richiamata dagli amministratori
			$this->financial_resources += $this->resourcesInExercise; //Immobilizzazioni entro l'esercizio
            
            //Disponibilità Liquide
            $this->liquid_resources = $this->getTotalRow(102);
            $this->liquid_resources += $this->subscribedCapital;
            
            //Rimanenze
            $this->inventories = $this->getTotalRow(79);
			
			//Immobilizzazioni
			$this->material_assets =  $this->getTotalRow(49);
            $this->unmaterial_assets =  $this->getTotalRow(57);
            $this->financtial_assets =  $this->getTotalRow(63) - $this->resourcesInExercise + $this->creditOverExercise;
			
			//Totale Investimenti
			$this->total_investments = $this->financial_resources + $this->liquid_resources + $this->inventories + $this->unmaterial_assets + $this->material_assets + $this->financtial_assets;
		}
		
       /**
        * Funzione che stampa il passivo dello stato patrimoniale rielaborato
        */
        public function printReworkedBalanceSheetSOF(){
			$this->generateReworkedBalanceSheetSOF();
            
            $this->balancePrintUtils->print_custom_row("","Debiti a breve scadenza",$this->debts_capital - $this->debts_ml_term,"row_super");
            $this->balancePrintUtils->print_custom_row("","Debiti a media e lunga scadenza", $this->debts_ml_term ,"row_super");
 
            $this->balancePrintUtils->print_custom_row("","Patrimonio Netto",-1,"row_super");
			
			if(!$this->withProfitSharing){	
				$this->balancePrintUtils->print_custom_row("","Capitale Proprio",$this->equity_capital,"row_child");
				$this->balancePrintUtils->print_custom_row("","Utile d'esercizio",$this->profit,"row_child");           
			}else{
				$this->balancePrintUtils->print_custom_row("","Capitale Proprio",$this->equity_capital,"row_child");
				$this->balancePrintUtils->print_custom_row("","Riserve",$this->reserve,"row_child");
			}
			
			$this->balancePrintUtils->print_custom_row("","Totale",$this->equity,"row_total");
            	   
            //Stampo totale fonti
            $this->balancePrintUtils->print_custom_row("","Totale Fonti",$this->equity + $this->debts_capital,"row_final");
        }

				
       /**
        * Funzione che genera il passivo dello stato patrimoniale rielaborato
        */	
		
		public function generateReworkedBalanceSheetSOF(){
		
			//B) FONDI RISCHI E ONERI, debiti a brebe
			//C) Quota dipendenti che lasciano esercizio a breve scandeza, resto a lunga
			//E) RATEI E RISCONTI, debiti a breve
			
            $this->debts_capital = $this->getTotalRow(BalanceSheetManager::BALANCE_SHEET_P_B_INDEX); //TOTALE B
            $this->debts_capital += $this->getTotalRow(BalanceSheetManager::BALANCE_SHEET_P_C_INDEX); //TOTALE C
            $this->debts_capital += $this->getTotalRow(BalanceSheetManager::BALANCE_SHEET_P_D_INDEX); //TOTALE D 
            $this->debts_capital += $this->getTotalRow(BalanceSheetManager::BALANCE_SHEET_P_E_INDEX); //TOTALE E
			
			if($this->withProfitSharing){
				$this->debts_capital += $this->totalToShare; //TOTALE DIVIDENDI RIPARTITI
			}
			
            $this->debts_ml_term = $this->balance_Account_manager->getAccount("13.01")->getImport(); //OBBLIGAZIONI
            $this->debts_ml_term += $this->balance_Account_manager->getAccount("13.20")->getImport(); //MUTUI
            $this->debts_ml_term += $this->getTotalRow(BalanceSheetManager::BALANCE_SHEET_P_C_INDEX); //TFR
            $this->debts_ml_term -= intval($this->severanceLiquitated); //- TFR LIQUIDATO
            $this->debts_ml_term += intval($this->debitsOverExercise); //ALTRI DEBITI OLTRE L'ESERCIZIO
			
			$this->equity = null; //Patrimonio Netto
            $this->profit = $this->balance_Account_manager->getAccount("10.30")->getImport(); //UTILE
			
			if(!$this->withProfitSharing){
				$this->equity_capital = $this->getTotalRow(BalanceSheetManager::BALANCE_SHEET_P_A_INDEX) - $this->profit;         
				$this->equity = $this->equity_capital + $this->profit;
			}else{       
				$this->equity = $this->getTotalRow(BalanceSheetManager::BALANCE_SHEET_P_A_INDEX) - $this->totalToShare;
				$this->equity_capital = $this->balance_Account_manager->getAccount("10.01")->getImport() + $this->balance_Account_manager->getAccount("10.11")->getImport();   
				$this->reserve = $this->equity - $this->equity_capital;   
			}
		
		}
        
        	  
	   /**
        * Questa funzione ritorna l'importo elaborato delle righe (comprensivo di quello delle righe figlie !)
		* @param string $row_id id della riga
        * @return int importo della riga
        */
        public function getTotalRow($row_id){
				$row_selected = $this->balanceRows[$row_id++];
				$total_row = $row_selected->getImport();
				$child = false;
				if(isset($this->balanceRows[$row_id])){
					$current_row = $this->balanceRows[$row_id++];
					$row_parent = $current_row->getParent();
                    //finchè l'id parente della riga corrente non è uguale all'id della riga richiesta 
					while($row_parent == $row_selected->getId() || $current_row->getParent() == $row_selected->getId()){
						
						$child = true;
						$total_row += $current_row->getImport();
						$current_row = $this->balanceRows[$row_id++];
						$row_parent = $current_row->getParent();
						if(!is_null($row_parent)){
							while(!is_null($this->balanceRows[$row_parent]->getParent())){
								$row_parent = $this->balanceRows[$row_parent]->getParent(); //Ottengo il parente più grande così da prendere tutte le righe figlie
								if($row_parent == $row_selected->getId()) break;
							}
						}
						if(is_null($current_row->getParent())) break;
					}
					
				}
                return $total_row;
            
        }
        
        /**
        * Funzione per stampare il prospetto di riparto utili
        */
        
        public function printProfitSharing(){            
            $profit = $this->balance_Account_manager->getAccount("10.30")->getImport(); //Utile
            
            $legally_reserve = (($profit * 5 ) / 100) * -1;
            $statutory_reserve = (($profit * $this->statutory_reserve) / 100) * -1;
            $straordinary_reserve = $this->straordinary_reserve * -1;
            
            $this->balancePrintUtils->print_custom_row("","Utile",$profit,"row_normal");
            $this->balancePrintUtils->print_custom_row("","Riserva Legale",$legally_reserve,"row_normal");
            $this->balancePrintUtils->print_custom_row("","Riserva Statutaria",$statutory_reserve,"row_normal");
            $this->balancePrintUtils->print_custom_row("","Riserva Straordinaria",$straordinary_reserve,"row_normal");
            
            $this->balancePrintUtils->print_custom_row("","Totale da dividere", $this->totalToSplit,"row_total");
            
            echo '<tr><td></td><td></td></tr>';
            
            $this->balancePrintUtils->print_custom_row("","Numero di Azionisti",$this->shareholders,"row_normal");
        
            echo '  <tr class="income_statement_row row_total ">
                        <td class="row_name">Dividendo</td>
                        <td class="row_import">' . $this->share . '</td>
                    </tr>';
            
            $this->balancePrintUtils->print_custom_row("","Totale da dividere",$this->totalToShare,"row_super");
            $this->balancePrintUtils->print_custom_row("","Utile e Nuovo",$this->retained_earnings,"row_total");
            
            
        }
		
	   /**
        * Funzione che genera gli importi per il prospetto di riparto utili
        */
        
        public function calculateProfitSharing(){
            
            $profit = $this->balance_Account_manager->getAccount("10.30")->getImport(); //Utile
            
            $legally_reserve = (($profit * 5 ) / 100) * -1;
            $statutory_reserve = (($profit * $this->statutory_reserve) / 100) * -1;
            $straordinary_reserve = $this->straordinary_reserve * -1;
            
            $this->totalToSplit = $profit + $legally_reserve + $statutory_reserve + $straordinary_reserve;
            $this->share = ($this->shareholders != "0" && $this->shareholders != null) ? intval(($this->totalToSplit / $this->shareholders) * 100) /100 : null;
            
            $this->totalToShare = $this->shareholders * $this->share;
            $this->retained_earnings = $this->totalToSplit - $this->totalToShare;
        } 

		/**
		*	Funzione che ritorna i dati per il calcolo degli indici di bilancio
		*
		*  	IMI (Immobilizzazioni Immateriali)
		*  	IMM (Immobilizzazioni Materiali)
		*  	IMF (Immobilizzazioni Finanziare)
		* 	AI  (Attivo Immobilizzato)
		*  	DL  (Disponibilità Liquide)
		*  	DF  (Disponibilità Finanziare)
		*  	R   (Rimanenze)
		*  	AC  (Attivo Corrente)
		*  	TI  (Totale Impieghi)
		*  	CD  (Capitale di Debito)
		*  	DM  (Debiti a M/L)
		*  	DB  (Debiti a B)
		*  	PN  (Patrimonio Netto)
		*  	CP  (Capitale Proprio)
		*  	RI  (Riserve)
		*  	U   (Utile)
		*   @return array array associativo contenente gli indici di bilancio
		*/
		public function getIndexData(){
			
			$this->generateReworkedBalanceSheetInvestments();
			$this->generateReworkedBalanceSheetSOF();
			
			return array(
				"IMI" => $this->unmaterial_assets,
				"IMM" => $this->material_assets,
				"IMF" => $this->financtial_assets,
				"AI" => $this->unmaterial_assets + $this->material_assets + $this->financtial_assets,
				"DL" => $this->liquid_resources,
				"DF" => $this->financial_resources,
				"R" => $this->inventories,
				"AC" => $this->liquid_resources + $this->financial_resources + $this->inventories,
				"TI" => $this->total_investments,
				"CD" => $this->debts_capital,
				"DM" => $this->debts_ml_term,
				"DB" => $this->debts_capital - $this->debts_ml_term,
				"PN" => $this->equity,
				"CP" => $this->equity_capital,
				"RI" => $this->reserve,
				"U" => $this->profit
			);
			
		}
    }
?>