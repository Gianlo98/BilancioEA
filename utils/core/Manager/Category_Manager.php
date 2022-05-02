<?php
    require_once(dirname(__FILE__) . "/../Database/Class/Accounts_Categories.php");
	
  /** Classe che gestisce le categorie dei conti di bilancio
    * @author Gianlorenzo Occhipinti <gianlorenzo.occhipinti@gmail.com>
    * @license http://opensource.org/licenses/gpl-license.php GNU Public License
    * @link http://data.baeproject.eu/BilancioEA Home del progetto
    */
    class Category_Manager{
        
        private $database;
        private $userAccountManager;
        private $category_array;
        
    
       /**
         * Funzione per settare un nuovo esercizio corrente
         *
         * @param BilancioEA $balance_object oggetto del bilancio
         */
        public function __construct($balance_object){
            
            $this->database = $balance_object->getDatabase();
            $this->balanceAccountManager = $balance_object->getBalanceAccountManager();
            
            $result = $this->database->query("SELECT * FROM accounts_categories");
            
            while($category = $result->fetch_object()){
                
                $this->category_array[$category->category_id] = new BalanceCategory(
                                                                                        $category->category_id,
                                                                                        $category->category_name,
                                                                                        $category->category_note,
                                                                                        $this->getAllCategoryAccounts($category->category_id)
                                                                                    );
                
            }
            
        }
        
        /**
         * Funzione che ritorna tutte le categorie
         *
         * @return array con tutte le categorie
         */
    
        function getAllCategories(){
            return $this->category_array;
        }
        
        /**
         * Funzione che ritorna una categoria
         *
         * @param string $category_id ID categoria
         * @return array dati della categoria
         */

        function getCategory($category_id){
            return (isset($this->category_array[$category_id]) ? $this->category_array[$category_id] : null);
        }       
        
        /**
         * Funzione che ritorna tutti i conti di una categoria
         *
         * @param string $category_id ID categoria
         * @return array conti della categoria
         */

        function getAllCategoryAccounts($category_id){
            
            $category_account_array = array();
            
            $account_array = $this->balanceAccountManager->getAccountArray();
            
            $find = false;
            
            foreach ($account_array as $id => $account){
                
                $set = false;
                
                if($account->getCategories() == $category_id){
                    $find = true;
                    $set =  true;
                    $category_account_array[$id] = $account;
                }
                
            }
            
            return (!empty($category_account_array) ? $category_account_array : null);
        }

        
    }

?>