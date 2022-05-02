<?php
    
    require_once(dirname(__FILE__) . "/../Database/Database.php");
    require_once(dirname(__FILE__) . "/../Database/Class/Balance_Sheet_Rows.php");

  /** Classe che gestisce le righe del bilancio d'esercizio
    * @author Gianlorenzo Occhipinti <gianlorenzo.occhipinti@gmail.com>
    * @license http://opensource.org/licenses/gpl-license.php GNU Public License
    * @link http://data.baeproject.eu/BilancioEA Home del progetto
    */

    class Balance_Row_Manager{
        
        private $row_array;
        private $userAccountManager;
        
       /**
         * Funzione per settare un nuovo esercizio corrente
         *
         * @param BilancioEA $balance_object oggetto del bilancio
         */
        public function __construct($balance){
            
            $database = $balance->getDatabase();
            $this->userAccountManager = $balance->getBalanceAccountManager();
            
            if($database instanceof mysqli){
                
                $result = $database->query("SELECT * FROM balance_sheet_rows");
            
                if($result){
                    
                    while($row = $result->fetch_object()){
                        
                        $rowUserAccounts = $this->getUserRowAccounts($row->row_id);
                        
                        $this->row_array[$row->row_id] = new BalanceRow(  
                                                                    $row->row_id,
                                            				        $row->row_char_identificator,
                                                                    $row->row_name,
                                                                    $row->row_parent,
                                                                    $row->row_destination,
                                                                    $rowUserAccounts,
                                                                    $this->getAccountArrayImport($rowUserAccounts)
                                                                );
                        
                    }
                    
                }
                
                $result->close(); 
                
                $this->adjustBalanceSheetRows();       
            }
            
        }
        
        /*
         * Metodo che ritorna tutti i conti di una determinata riga
         * @param String $row_id id della riga di quale si vogliono sapere tutti i conti
         * @return array con tutti i conti di quella riga
         */
        
        public function getRowAccounts($row_id){
            
            $accountArray = $this->userAccountManager->getAccountArray();        

            $row_accounts = array();
            
            foreach ($accountArray as $id => $account) {
                
                if($account->getRowId() == $row_id){
                                
                    
                    $row_accounts[$account->getId()] = $account;
                }
                
            }
            
            return $row_accounts;           
            
        }
        
        /*
         * Metodo che ritorna tutti i conti usati dall'utente di una determinata riga
         * @param String $row_id id della riga di quale si vogliono sapere tutti i conti
         * @return array con tutti i conti di quella riga
         */
        
        public function getUserRowAccounts($row_id){
            
            $accountArray = $this->userAccountManager->getAccountArray();        
        
            $row_accounts = array();
            
            foreach ($accountArray as $id => $account) {
                
                if($account->getRowId() == $row_id && $account->getImport() !== null){
                    $row_accounts[$account->getId()] = $account;
                }
                
            }
            
            return $row_accounts;           
            
        }
        
        /*
         * Metodo che ritorna l'importo dei conti di un array di conti
         * @param String $row_id id della riga di quale si vogliono sapere tutti i conti
         * @return int l'importo della riga
         */
        
        public function getAccountArrayImport($accountArray){
            
            $numberOfAccount = 0;
            $import = 0;
            
            foreach ($accountArray as $id => $account){
                
                if ($account instanceof BalanceAccount){
                    
                    if($account->getImport() !== null){
                        
                        $numberOfAccount++;
                        $import += $account->getImport() * ($account->getRectified() ? -1 : 1); 
                        
                    }
                }
            }
            
            return (($numberOfAccount != 0) ? $import : null);
        }
        
        /*
         * Metodo che ritorna una riga del bilancio d'esercizio
         * @param String $row_id id della riga
         * @return Balance_Sheet_Rows l'oggetto della riga richiesta
         */
        
        public function getRow($row_id){
            return (isset($this->row_array[$row_id]) ? $this->row_array[$row_id] : null);
        }
        
        /*
         * Metodo che ritorna tutte le righe del conto economico (incomeStatment)
         * @param String $row_id id della riga
         * @return array con tutti i conti del conto economico 
         */
        
        public function getIncomeStatmentRows(){
            
            $income_statment_row_array = array();
            
            foreach ($this->row_array as $id => $row){
                if($row->getDestination() == "income statment"){
                    $income_statment_row_array[$id] =  $row;
                }
            }
            
            return $income_statment_row_array;
        }
        
        /*
         * Metodo che ritorna tutte le righe dello stato patrimoniale (balanceSheet)
         * @param String $row_id id della riga
         * @return array con tutti i conti dello stato patrimoniale
         */
        
        public function getBalanceSheetRows(){
            
            $balance_sheet_row_array =  array();
            
            foreach ($this->row_array as $id => $row){
                if($row->getDestination() == "balance sheet"){
                    $balance_sheet_row_array[$id] =  $row;
                }
            }
            
            return $balance_sheet_row_array;
        }
        
        private function adjustBalanceSheetRows(){
           /* $this->addCustomAccountToRow(83,"20.30");
            $this->addCustomAccountToRow(83,"20.32");
            $this->addCustomAccountToRow(81,"20.31");
            $this->addCustomAccountToRow(82,"20.41");
            $this->addCustomAccountToRow(80,"37.10");
            $this->addCustomAccountToRow(80,"37.11");
            $this->addCustomAccountToRow(80,"37.12");*/
        }
        
        private function addCustomAccountToRow($row_id, $account_id){
            $balanceSheetResources = $this->row_array[$row_id];
            $row_custom_accounts = $balanceSheetResources->getAccounts();
            $row_custom_account = $this->userAccountManager->getAccount($account_id);
            $row_custom_account->setRectified(0);
            $row_custom_accounts[$account_id] = $row_custom_account;
            $balanceSheetResources->setAccounts($row_custom_accounts);
            $balanceSheetResources->setImport($this->getAccountArrayImport($row_custom_accounts));
        }
        
    }
?>