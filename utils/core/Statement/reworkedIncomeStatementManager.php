<?php
    
    require_once(dirname(__FILE__) . "/../../BilancioEA.php");
    require_once(dirname(__FILE__) . "/../PrintUtils/BalancePrintUtils.php");
    require_once(dirname(__FILE__) . "/../Statement/incomeStatementManager.php");

  /** Classe che genera e stampa il conto economico
    * @author Gianlorenzo Occhipinti <gianlorenzo.occhipinti@gmail.com>
    * @license http://opensource.org/licenses/gpl-license.php GNU Public License
    * @link http://data.baeproject.eu/BilancioEA Home del progetto
    */
    class reworkedIncomeStatementManager{
        
        
        private $balanceRows;         
        private $balance_Row_manager;         
        private $balance_Account_manager;
        private $balancePrintUtils;
        private $balance_Exercise_manager;
		
		//Prospetto costo del venduto
		private $initial_inventories;
		private $resources_buyed;
		private $costs_internal_work;
		private $final_inventories;
		
		private $industrials_costs;
		private $administratives_costs;
		private $commercials_costs;
		private $accessory_managment;
	
		private $costs_of_sale;
		private $value_added;
        
      /**
        * Metodo costruttore
        */
        function __construct(){
            $balanceObject = new BilancioEA();
            $this->balance_Row_manager = $balanceObject->getBalanceRowManager();
            $this->balance_Account_manager = $balanceObject->getBalanceAccountManager();
            $this->balanceRows = $this->balance_Row_manager->getIncomeStatmentRows();
			$this->balance_Exercise_manager = $balanceObject->getExerciseManager();
            $this->balancePrintUtils = new BalancePrintUtils($this->balanceRows);
			
			

			$ex_proprieties = array();
			$ex_id = $this->balance_Exercise_manager->getUserExerciseID();
			if($ex_id){
				$ex_proprieties = $this->balance_Exercise_manager->getExerciseProperties($ex_id);
			}
			
			foreach($ex_proprieties as $key => $proprieties){
				$pr_value = ($proprieties['exercise_propriety_value'] !== "") ? $proprieties['exercise_propriety_value'] : null;
				switch($proprieties['exercise_propriety']){
					case "INDUSTRIALS_COSTS":
						$this->industrials_costs = $pr_value;
						break;					
					case "ADMINISTRATIVES_COSTS":
						$this->administratives_costs = $pr_value;
						break;					
					case "COMMERCIALS_COSTS":
						$this->commercials_costs = $pr_value;
						break;						
					case "ACCESSORY_MANAGMENT":
						$this->accessory_managment = $pr_value;
						break;					
				}
			}
        }
		
      /**
        * Funzione che stampa tutto il conto economico nella riga dove è richiamata
        */
        public function printIncomeStatement($type){

            $total = null;
            $operating_income = null;
			
			$straordinary_managment_revenue = null;
			$straordinary_managment_costs = null;
			
			$straordinary_managment_revenue += $this->balance_Account_manager->getAccount('21.21')->getImport();
			$straordinary_managment_revenue += $this->balance_Account_manager->getAccount('21.31')->getImport();
			$straordinary_managment_costs += $this->balance_Account_manager->getAccount('39.21')->getImport();
			$straordinary_managment_costs += $this->balance_Account_manager->getAccount('39.31')->getImport();
			$straordinary_managment_costs += $this->balance_Account_manager->getAccount('39.41')->getImport();
            
            if($type == 'VA'){
				
				$this->generateValueAdded();
                
                $this->balancePrintUtils->print_custom_row("","Valore Aggiunto", $this->value_added,"row_final");
				
				$costs_for_employers = $this->getTotalRow(11);
                $this->balancePrintUtils->print_custom_row("","Costi per il personale",$costs_for_employers * -1,"row_normal");
				
				$mol = $this->value_added - $costs_for_employers;
                $this->balancePrintUtils->print_custom_row("","Margine Operativo Lordo",$mol,"row_final");
				
				$amortisations = $this->balance_Row_manager->getRow(18)->getImport() + $this->balance_Row_manager->getRow(19)->getImport();
                $this->balancePrintUtils->print_custom_row("","Ammortamenti",$amortisations * -1, "row_normal");
				
				$svalutations = $this->balance_Row_manager->getRow(20)->getImport() + $this->balance_Row_manager->getRow(21)->getImport();
                $this->balancePrintUtils->print_custom_row("","Svalutazioni",$svalutations * -1,"row_normal");
				
				$provisions = $this->balance_Row_manager->getRow(23)->getImport() + $this->balance_Row_manager->getRow(24)->getImport();
                $this->balancePrintUtils->print_custom_row("","Accantonamenti",$provisions * -1,"row_normal");
				
				$operating_income = $mol - $amortisations - $svalutations - $provisions;
                
            }else if($type == 'CV'){
                
				$this->generateCostsOfSale();
				
				$sales_revenue = $this->balance_Row_manager->getRow(2)->getImport();
				
                $this->balancePrintUtils->print_custom_row("","Ricavi di Vendita",$sales_revenue,"row_final");
                $this->balancePrintUtils->print_custom_row("","Costi del venduto",$this->costs_of_sale,"row_normal");
				
				$industrial_operated_margin = $sales_revenue - $this->costs_of_sale;
					
                $this->balancePrintUtils->print_custom_row("","Margine Operativo Industriale",$industrial_operated_margin,"row_final");
                $this->balancePrintUtils->print_custom_row("","Costi di distribuzione",$this->commercials_costs * -1,"row_normal");
                $this->balancePrintUtils->print_custom_row("","Costi di amministrazione",$this->administratives_costs * -1,"row_normal");
				
				$other_revenues = $this->balance_Row_manager->getRow(6)->getImport();
                $this->balancePrintUtils->print_custom_row("","Altri ricavi",$other_revenues - $straordinary_managment_revenue,"row_normal");
                
				$operating_income = $industrial_operated_margin - $this->commercials_costs - $this->administratives_costs + $other_revenues  - $straordinary_managment_revenue;
            }
            

            $this->balancePrintUtils->print_custom_row("","Reddito Operativo",$operating_income,"row_final");
			
            $this->balancePrintUtils->print_custom_row("","Risultato Gestione Finanziaria",$this->getTotalRow(26),"row_normal");
            $this->balancePrintUtils->print_custom_row("","Risultato Gestione Accessoria",$this->accessory_managment,"row_normal");
			
			$results_ordinary_managment = $operating_income + $this->getTotalRow(26) + $this->accessory_managment;
            $this->balancePrintUtils->print_custom_row("","Risultato Della Gestione Ordinaria",$results_ordinary_managment,"row_final");
			
            $this->balancePrintUtils->print_custom_row("","Risultato Gestione Straordinaria",$straordinary_managment_revenue - $straordinary_managment_costs,"row_normal");
			
			$results_wihout_fee = $results_ordinary_managment + ($straordinary_managment_revenue - $straordinary_managment_costs);
            $this->balancePrintUtils->print_custom_row("","Risultato al lordo delle Imposte",$results_wihout_fee,"row_final");
			
			$results_with_fee = $results_wihout_fee - $this->balance_Account_manager->getAccount("60.01")->getImport();
            $this->balancePrintUtils->print_custom_row("","Imposte",$this->balance_Account_manager->getAccount("60.01")->getImport() * -1,"row_normal");
            $this->balancePrintUtils->print_custom_row("","Utile",$results_with_fee,"row_final");

            

        }
        
        public function printValueAdded(){
            $this->generateValueAdded();
    
			$straordinary_managment_revenue = null;
			$straordinary_managment_costs = null;
			
			$straordinary_managment_revenue += $this->balance_Account_manager->getAccount('21.21')->getImport();
			$straordinary_managment_revenue += $this->balance_Account_manager->getAccount('21.31')->getImport();
			$straordinary_managment_costs += $this->balance_Account_manager->getAccount('39.21')->getImport();
			$straordinary_managment_costs += $this->balance_Account_manager->getAccount('39.31')->getImport();
			$straordinary_managment_costs += $this->balance_Account_manager->getAccount('39.41')->getImport();
			
            $this->balancePrintUtils->print_custom_row("","Ricavi delle vendite e delle prestazioni",$this->balance_Row_manager->getRow(2)->getImport(),"row_normal");
            $this->balancePrintUtils->print_custom_row("","Variazioni delle rimanenze di prodotti in corso di lavorazione, semilavorati e finiti",$this->balance_Row_manager->getRow(3)->getImport(),"row_normal");
            $this->balancePrintUtils->print_custom_row("","Variazioni di lavori in corso su ordinazione",$this->balance_Row_manager->getRow(4)->getImport(),"row_normal");
            $this->balancePrintUtils->print_custom_row("","Incrementi di immobilizzazioni per lavori interni",$this->balance_Row_manager->getRow(5)->getImport(),"row_normal");
            $this->balancePrintUtils->print_custom_row("","Altri ricavi e proventi, con separata indicazione dei contributi in conto esercizio",$this->balance_Row_manager->getRow(6)->getImport() - $straordinary_managment_revenue,"row_normal");
            $this->balancePrintUtils->print_custom_row("","Valore della Produzione",$this->getTotalRow(1) - $straordinary_managment_revenue,"row_final");
            
            $this->balancePrintUtils->print_custom_row("","Costi acquisto merci",$this->balance_Row_manager->getRow(8)->getImport() * -1,"row_normal");
            $this->balancePrintUtils->print_custom_row("","Variaz rimanente merci",$this->balance_Row_manager->getRow(22)->getImport() * -1,"row_normal");
            $this->balancePrintUtils->print_custom_row("","Costi per Servizi",($this->balance_Row_manager->getRow(9)->getImport() + $this->balance_Row_manager->getRow(10)->getImport()) * -1,"row_normal");
            $this->balancePrintUtils->print_custom_row("","Oneri diversi di gestione",($this->balance_Row_manager->getRow(25)->getImport() * -1) + $straordinary_managment_costs,"row_normal");
            $this->balancePrintUtils->print_custom_row("","Valore Aggiunto",$this->value_added,"row_final");
            
            
        }      
        
        public function generateValueAdded(){
            
			$straordinary_managment_revenue = null;
			$straordinary_managment_costs = null;
			
			$straordinary_managment_revenue += $this->balance_Account_manager->getAccount('21.21')->getImport();
			$straordinary_managment_revenue += $this->balance_Account_manager->getAccount('21.31')->getImport();
			$straordinary_managment_costs += $this->balance_Account_manager->getAccount('39.21')->getImport();
			$straordinary_managment_costs += $this->balance_Account_manager->getAccount('39.31')->getImport();
			$straordinary_managment_costs += $this->balance_Account_manager->getAccount('39.41')->getImport();
			
			
            $value_of_productions = $this->getTotalRow(1) - $straordinary_managment_revenue;
            $resources_buyed = $this->balance_Row_manager->getRow(8)->getImport();
            $resources_variations = $this->balance_Row_manager->getRow(22)->getImport();
            $costs_for_services = $this->balance_Row_manager->getRow(9)->getImport() + $this->balance_Row_manager->getRow(10)->getImport();
            $different_of_managing = $this->balance_Row_manager->getRow(25)->getImport() - $straordinary_managment_costs;
		
            $this->value_added = $value_of_productions - $resources_buyed - $resources_variations - $costs_for_services - $different_of_managing;
            
        }
        
        public function printCostsOfSale(){
			$this->generateCostsOfSale();
			$initial_inventories = $this->initial_inventories;
			$final_inventories = $this->final_inventories;
			$resources_buyed = $this->resources_buyed;
			$costs_internal_work = $this->costs_internal_work;
			$industrials_costs = $this->industrials_costs;
			$costs_of_sale = $this->costs_of_sale;
			
            $this->balancePrintUtils->print_custom_row("","Esistenze Inziali",$initial_inventories,"row_final");
            $this->balancePrintUtils->print_custom_row("","Acquisti merci",$resources_buyed,"row_normal");
            $this->balancePrintUtils->print_custom_row("","costi industriali",$industrials_costs,"row_normal");
            $this->balancePrintUtils->print_custom_row("","costri patr lavori interni",$costs_internal_work * -1,"row_normal");
            $this->balancePrintUtils->print_custom_row("","rimanenze finali",$final_inventories * -1,"row_normal");
            $this->balancePrintUtils->print_custom_row("","Costo del venduto",$costs_of_sale,"row_final");
            
        }
		
		public function generateCostsOfSale(){
			
			/* Calcolo Esistenze Iniziali */
			$this->initial_inventories = null;
			$this->initial_inventories += $this->balance_Account_manager->getAccount('20.20')->getImport();
			$this->initial_inventories += $this->balance_Account_manager->getAccount('20.21')->getImport();
			$this->initial_inventories += $this->balance_Account_manager->getAccount('20.22')->getImport();
			$this->initial_inventories += $this->balance_Account_manager->getAccount('20.40')->getImport();
			$this->initial_inventories += $this->balance_Account_manager->getAccount('37.01')->getImport();
			$this->initial_inventories += $this->balance_Account_manager->getAccount('37.02')->getImport();
			$this->initial_inventories += $this->balance_Account_manager->getAccount('37.03')->getImport();			
			
			/* Calcolo Rimanenze Finali */
			$this->final_inventories = null;
			$this->final_inventories += $this->balance_Account_manager->getAccount('20.30')->getImport();
			$this->final_inventories += $this->balance_Account_manager->getAccount('20.31')->getImport();
			$this->final_inventories += $this->balance_Account_manager->getAccount('20.32')->getImport();
			$this->final_inventories += $this->balance_Account_manager->getAccount('20.41')->getImport();
			$this->final_inventories += $this->balance_Account_manager->getAccount('37.10')->getImport();
			$this->final_inventories += $this->balance_Account_manager->getAccount('37.11')->getImport();
			$this->final_inventories += $this->balance_Account_manager->getAccount('37.12')->getImport();
			
			$this->resources_buyed = $this->balance_Row_manager->getRow(8)->getImport();
			$this->costs_internal_work = $this->balance_Row_manager->getRow(5)->getImport();
			$industrials_costs = $this->industrials_costs;
			
			$this->costs_of_sale = $this->initial_inventories + $this->resources_buyed + $this->industrials_costs - $this->final_inventories - $this->costs_internal_work;
		}
        
	  /**
        * Questa funzione stampa una riga del conto economico
		* @param char $char Carattere identificativo della riga da stampare (A-B-C-D)
        * @return int importo della riga
        */
        public function printStatmentRow($char){
            switch($char){
                case "A":
                    $index = IncomeStatementManager::INCOME_STATMENT_A_INDEX;
                    break;              
                case "B":
                    $index = IncomeStatementManager::INCOME_STATMENT_B_INDEX;
                    break;             
                case "C":
                    $index = IncomeStatementManager::INCOME_STATMENT_C_INDEX;
                    break;              
                case "D":
                    $index = IncomeStatementManager::INCOME_STATMENT_D_INDEX;
                    break;
                default:
                    $index = null;
            }
            
            if(!is_null($index)){
                $total_row = 0;
                $row_selected = $this->balanceRows[$index++];;

                 $this->balancePrintUtils->print_row($row_selected);
                $current_row = $this->balanceRows[$index++];
                do{
                    
                    
                    if ($current_row->getId() == 33) {
						//Se la riga è un interesse/onere finanziario, va sottratto anzichè sommarlo
                        $total_row -=  $this->balancePrintUtils->print_row($current_row);
					}else{
                        $total_row +=  $this->balancePrintUtils->print_row($current_row);
					}
                    $current_row = $this->balanceRows[$index++];
                    
                    if(is_null($current_row->getParent())) break;
                } while($current_row->getParent() == $row_selected->getId() || $this->balanceRows[$current_row->getParent()]->getParent() == $row_selected->getId());

                $this->balancePrintUtils->print_custom_row("","Totale:",$total_row,"row_total");
                return $total_row;
            }
        }
        	  
	  /**
        * Questa funzione ritorna l'importo elaborato delle righe (comprensivo di quello delle righe figlie !)
		* @param string $row_id id della riga
        * @return int importo della riga
        */
        //RIFARE BENE !!!!
        public function getTotalRow($row_id){
            $total_row = 0;
            $row_selected = $this->balanceRows[$row_id++];
            $current_row = $this->balanceRows[$row_id++];
            do{
				if($current_row->getId() == 33){
					$total_row -= $current_row->getImport();
				}else $total_row += $current_row->getImport();
                $current_row = $this->balanceRows[$row_id++];
                if(is_null($current_row->getParent())) break;
            }while($current_row->getParent() == $row_selected->getId() || $this->balanceRows[$current_row->getParent()]->getParent() == $row_selected->getId());
            return $total_row;
        }
		
		/**
		*	Funzione che ritorna i dati per il calcolo degli indici di bilancio
		*
		*  	RO (Reddito Operativo)
		*   RV (Ricavi di Vendita)
		*   
		*   @return array array associativo contenente gli indici di bilancio
		*/
		public function getIndexData(){
			
				$this->generateValueAdded();
                
				/** Calcoli per il RO */
				$costs_for_employers = $this->getTotalRow(11);
				$mol = $this->value_added - $costs_for_employers;
				$amortisations = $this->balance_Row_manager->getRow(18)->getImport() + $this->balance_Row_manager->getRow(19)->getImport();
				$svalutations = $this->balance_Row_manager->getRow(20)->getImport() + $this->balance_Row_manager->getRow(21)->getImport();
				$provisions = $this->balance_Row_manager->getRow(23)->getImport() + $this->balance_Row_manager->getRow(24)->getImport();
				
				//RO
				$operating_income = $mol - $amortisations - $svalutations - $provisions;
			
			return array(
				"RO" => $operating_income,
				"RV" => $this->balance_Row_manager->getRow(2)->getImport()
			);
			
		}
        
        
        
    }
?>