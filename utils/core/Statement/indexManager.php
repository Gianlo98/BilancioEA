<?php
    require_once(dirname(__FILE__) . "/../../BilancioEA.php");
    require_once(dirname(__FILE__) . "/../PrintUtils/BalancePrintUtils.php");
    require_once(dirname(__FILE__) . "/reworkedBalanceSheetManager.php");
    require_once(dirname(__FILE__) . "/reworkedIncomeStatementManager.php");

   /** Classe gestisce gli indici di Bilancio
    * @author Gianlorenzo Occhipinti <gianlorenzo.occhipinti@gmail.com>
    * @license http://opensource.org/licenses/gpl-license.php GNU Public License
    * @link http://data.baeproject.eu/BilancioEA Home del progetto
    */
	
	class indexManager{
		
        private $balanceRows;         
        private $balance_Row_manager;         
        private $balance_Account_manager;
        private $reworkedBalanceSheetManager;
        private $reworkedIncomeStatementManager;
		
		private $indexDataArray = array();
      /**
        * Metodo costruttore
        */
        function __construct(){
            $balanceObject = new BilancioEA();
            $this->balance_Row_manager = $balanceObject->getBalanceRowManager();
            $this->balance_Account_manager = $balanceObject->getBalanceAccountManager();
            $this->balanceRows = $this->balance_Row_manager->getIncomeStatmentRows();
            $this->reworkedBalanceSheetManager = new ReworkedBalanceSheetManager(true);
            $this->reworkedIncomeStatementManager = new ReworkedIncomeStatementManager();
			$this->setDataArray($this->reworkedBalanceSheetManager->getIndexData());
			$this->setDataArray($this->reworkedIncomeStatementManager->getIndexData());
        }
		
		private function setDataArray($dataArray){
			$this->indexDataArray = array_merge($this->indexDataArray,$dataArray);
		}		
		
		public function getDataArray(){
			return $this->indexDataArray;
		}
	}

?>