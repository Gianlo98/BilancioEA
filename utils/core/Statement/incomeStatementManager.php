<?php
    
    require_once(dirname(__FILE__) . "/../../BilancioEA.php");
    require_once(dirname(__FILE__) . "/../PrintUtils/BalancePrintUtils.php");

  /** Classe che genera e stampa il conto economico
    * @author Gianlorenzo Occhipinti <gianlorenzo.occhipinti@gmail.com>
    * @license http://opensource.org/licenses/gpl-license.php GNU Public License
    * @link http://data.baeproject.eu/BilancioEA Home del progetto
    */
    class IncomeStatementManager{
        
        
        private $balanceRows;         
        private $balance_Row_manager;         
        private $balance_Account_manager;
        private $balancePrintUtils;
        
        const   INCOME_STATMENT_A_INDEX = 1;
        const   INCOME_STATMENT_B_INDEX = 7;
        const   INCOME_STATMENT_C_INDEX = 26;
        const   INCOME_STATMENT_D_INDEX = 35;
        
      /**
        * Metodo costruttore
        */
        function __construct(){
            $balanceObject = new BilancioEA();
            $this->balance_Row_manager = $balanceObject->getBalanceRowManager();
            $this->balance_Account_manager = $balanceObject->getBalanceAccountManager();
            $this->balanceRows = $this->balance_Row_manager->getIncomeStatmentRows();
            $this->balancePrintUtils = new BalancePrintUtils($this->balanceRows);
        }
     
      /**
        * Funzione che stampa tutto il conto economico nella riga dove è richiamata
        */
        public function printIncomeStatement(){
            $totalA = $this->printStatmentRow('A');
            $totalB = $this->printStatmentRow('B');
            
            //Stampo la differenza A-B
            $this->balancePrintUtils->print_custom_row("","Differenza tra valori e costi della produzione",$totalA - $totalB,"row_final");
            
            $totalC = $this->printStatmentRow('C');
            $totalD = $this->printStatmentRow('D');
            
            
            $total = $totalA - $totalB + $totalC + $totalD;
            $this->balancePrintUtils->print_custom_row("","Risultato prima delle imposte",$total,"row_final");
            
            $total -=  $this->balancePrintUtils->print_custom_row("20","imposte sul reddito dell'esercizio, correnti, differite e anticipate",$this->balance_Account_manager->getAccount("60.01")->getImport(),"row_normal");
            
             $this->balancePrintUtils->print_custom_row("","Utile (perdita) d'esercizio",$total,"row_final"); 
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
        public function getTotalRow($row_id){
            $total_row = 0;
            $row_selected = $this->balanceRows[$row_id++];
            $current_row = $this->balanceRows[$row_id++];
            do{
                $total_row += $current_row->getImport();
                $current_row = $this->balanceRows[$row_id++];
                if(is_null($current_row->getParent())) break;
            }while($current_row->getParent() == $row_selected->getId() || $this->balanceRows[$current_row->getParent()]->getParent() == $row_selected->getId());
            return $total_row;
        }
        
        
        
    }
?>