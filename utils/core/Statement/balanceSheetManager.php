<?php
    
    require_once(dirname(__FILE__) . "/../../BilancioEA.php");
    require_once(dirname(__FILE__) . "/../PrintUtils/BalancePrintUtils.php");

  /** Classe che genera e stampa lo stato patrimoniale
    * @author Gianlorenzo Occhipinti <gianlorenzo.occhipinti@gmail.com>
    * @license http://opensource.org/licenses/gpl-license.php GNU Public License
    * @link http://data.baeproject.eu/BilancioEA Home del progetto
    */
    class BalanceSheetManager{
        
        
        private $balanceRows;         
        private $balance_Row_manager;         
        private $balance_Account_manager;
        private $balancePrintUtils;
        private $balance_Exercise_manager;
        
        const   BALANCE_SHEET_A_A_INDEX = 47;
        const   BALANCE_SHEET_A_B_INDEX = 48;
        const   BALANCE_SHEET_A_C_INDEX = 78;
        const   BALANCE_SHEET_A_D_INDEX = 106;
        const   BALANCE_SHEET_P_A_INDEX = 107;
        const   BALANCE_SHEET_P_B_INDEX = 118;
        const   BALANCE_SHEET_P_C_INDEX = 123;
        const   BALANCE_SHEET_P_D_INDEX = 124;
        const   BALANCE_SHEET_P_E_INDEX = 140;
		
		private	$creditOverExercise = null;
		private	$resourcesOverExercise = null;
		private	$debtOverExercise = null;
        
       /**
        * Metodo costruttore
        */
        function __construct(){
            $balanceObject = new BilancioEA();
            $this->balance_Row_manager = $balanceObject->getBalanceRowManager();
            $this->balance_Account_manager = $balanceObject->getBalanceAccountManager();
			$this->balance_Exercise_manager = $balanceObject->getExerciseManager();
            $this->balanceRows = $this->balance_Row_manager->getBalanceSheetRows();
            $this->balancePrintUtils = new BalancePrintUtils($this->balanceRows);
			
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
					case "LTRESOURCES_AFTER_YEAR":
						$this->resourcesOverExercise = $pr_value;
						break;					
					case "DEBTS_AFTER_YEAR":
						$this->debtOverExercise = $pr_value;
						break;
				}
			}
        }
     
       /**
        * Funzione che stampa l'attivo dello stato patrimoniale
		*/
        public function printBalanceSheetActive(){
			
           
			//$textCreditOverExercise = (!is_null($creditOverExercise)) ? "(Di cui $creditOverExercise esigibili oltre l'esercizio successivo)" : "";
			//$textResourcesOverExercise = (!is_null($resourcesOverExercise)) ? "(Di cui $resourcesOverExercise esigibili entro l'esercizio successivo)" : "";
			
			$totalA  = $this->printBalanceSheetRow("A_A");
			$totalB  = $this->printBalanceSheetRow("A_B");
			$totalC  = $this->printBalanceSheetRow("A_C");
			$totalD  = $this->printBalanceSheetRow("A_D");
			   
            //Stampo totale attivo
            $this->balancePrintUtils->print_custom_row("","Totale Attivo",$totalA + $totalB + $totalC + $totalD,"row_final");
        }
		
       /**
        * Funzione che stampa il passivo dello stato patrimoniale
        */
        public function printBalanceSheetPassive(){
           
			$totalA  = $this->printBalanceSheetRow("P_A");
			$totalB  = $this->printBalanceSheetRow("P_B");
			$totalC  = $this->printBalanceSheetRow("P_C");
			$totalD  = $this->printBalanceSheetRow("P_D");
			$totalE  = $this->printBalanceSheetRow("P_E");
			
            //Stampo totale attivo
            $this->balancePrintUtils->print_custom_row("","Totale Passivo",$totalA + $totalB + $totalC + $totalD + $totalE,"row_final");
        }
        
	  /**
        * Questa funzione stampa una riga dello stato patrimoniale
		* @param char $char Carattere identificativo della riga da stampare (A_A/A_B/A_C/A_D/P_A/P_B/P_C/P_D/P_E)
        * @return int importo della riga
        */
        public function printBalanceSheetRow($char){
            switch($char){
                case "A_A":
                    $index = BalanceSheetManager::BALANCE_SHEET_A_A_INDEX;
                    break;              
                case "A_B":
                    $index = BalanceSheetManager::BALANCE_SHEET_A_B_INDEX;
                    break;             
                case "A_C":
                    $index = BalanceSheetManager::BALANCE_SHEET_A_C_INDEX;
                    break;              
                case "A_D":
                    $index = BalanceSheetManager::BALANCE_SHEET_A_D_INDEX;
                    break;              
                case "P_A":
                    $index = BalanceSheetManager::BALANCE_SHEET_P_A_INDEX;
                    break;              
                case "P_B":
                    $index = BalanceSheetManager::BALANCE_SHEET_P_B_INDEX;
                    break;             
                case "P_C":
                    $index = BalanceSheetManager::BALANCE_SHEET_P_C_INDEX;
                    break;              
                case "P_D":
                    $index = BalanceSheetManager::BALANCE_SHEET_P_D_INDEX;
                    break;            
                case "P_E":
                    $index = BalanceSheetManager::BALANCE_SHEET_P_E_INDEX;
                    break;
                default:
                    $index = null;
            }
            
            if(!is_null($index)){
				//Super-righe
				$row_selected = $this->balanceRows[$index++];
				$total_row = $row_selected->getImport();
				$tx_addon = "";
				switch($row_selected->getId()){
					case "124": //Debiti
						$tx_addon = (!is_null($this->debtOverExercise)) ? " (Di cui " . $this->debtOverExercise . " esigibili oltre l'esercizio successivo)" : "";
						break;
				}
				$row_selected->setName($row_selected->getName(). $tx_addon);
				$this->balancePrintUtils->print_row($row_selected);
				$child = false;
				if(isset($this->balanceRows[$index])){
					//Righe figlie
					$current_row = $this->balanceRows[$index++];	
					$row_parent = $current_row->getParent();
					while($row_parent == $row_selected->getId()){
						$child = true;
						//Controllo per vedere se aggiungere la precisazione dell'esigibile oltre ex succ
						$tx_addon = "";
						switch($current_row->getId()){
							case "63": //Immobilizzazioni Finanziarie	
								$tx_addon = (!is_null($this->resourcesOverExercise)) ? " (Di cui " . $this->resourcesOverExercise . " esigibili entro l'esercizio successivo)" : "";
								break;
							case "85": //Crediti
								$tx_addon = (!is_null($this->creditOverExercise)) ? " (Di cui " . $this->creditOverExercise . " esigibili oltre l'esercizio successivo)" : "";
								break;
						}
						$current_row->setName($current_row->getName(). $tx_addon);					
						$total_row += $this->balancePrintUtils->print_row($current_row);
						$current_row = $this->balanceRows[$index++];
						$row_parent = $current_row->getParent();
						if(!is_null($row_parent)){
							while(!is_null($this->balanceRows[$row_parent]->getParent())){
								$row_parent = $this->balanceRows[$row_parent]->getParent();
							}
						}

						if(is_null($current_row->getParent())) break;

					}
					
				}
				if($child) $this->balancePrintUtils->print_custom_row("","Totale:",$total_row,"row_total");
                return $total_row;
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
					while($row_parent == $row_selected->getId()){
						$child = true;
						$total_row += $current_row->getImport();
						$current_row = $this->balanceRows[$row_id++];

						$row_parent = $current_row->getParent();
						if(!is_null($row_parent)){
							while(!is_null($this->balanceRows[$row_parent]->getParent())){
								$row_parent = $this->balanceRows[$row_parent]->getParent();
							}
						}

						if(is_null($current_row->getParent())) break;

					}
					
				}
                return $total_row;
            
        }
        
        
        
    }
?>