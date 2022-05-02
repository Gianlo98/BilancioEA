<?php


    require_once(dirname(__FILE__) . "/../Database/Database.php");
    require_once(dirname(__FILE__) . "/../Database/Class/Accounts.php");


  /** Classe che gestisce i conti del bilancio d'esercizio
    * @author Gianlorenzo Occhipinti <gianlorenzo.occhipinti@gmail.com>
    * @license http://opensource.org/licenses/gpl-license.php GNU Public License
    * @link http://data.baeproject.eu/BilancioEA Home del progetto
    */
    class Balance_Account_Manager{
        
        private $account_array;
        
       /**
         * Funzione per settare un nuovo esercizio corrente
         *
         * @param BilancioEA $balance_object oggetto del bilancio
         */
        public function __construct($balance){
            
            $database = $balance->getDatabase();
            $userAccountManager = $balance->getUserAccountManager();
            
            if($database instanceof mysqli){
                
                $result = $database->query("SELECT * FROM accounts");
            
                if($result){
                    while($row =  $result->fetch_object()){
                        $this->account_array[$row->accounts_id] = new BalanceAccount(
                                                                                        $row->accounts_id, 
                                                                                        $row->accounts_name,
                                                                                        $row->accounts_id_type,
                                                                                        $row->accounts_nature,
                                                                                        $row->accounts_eccedence,
                                                                                        $row->rectified,
                                                                                        $row->accounts_categories_id,
                                                                                        $row->balance_sheet_row_id,
                                                                                        ($userAccountManager->getUserAccount($row->accounts_id) ? $userAccountManager->getUserAccount($row->accounts_id) : null)
                                                                                    );
                    }
                }
                
                $result->close(); 
                
                    
            }
            
        }
        
        public function getAccount($search_id){
            return (isset($this->account_array[$search_id]) ? $this->account_array[$search_id] : null);
        }
        
        public function getAccountArray(){
            return $this->account_array;
        }
        
        
    }
?>