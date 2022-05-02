<?php

  /** Classe che gestisce i conti utilizzati dall'utente
    * @author Gianlorenzo Occhipinti <gianlorenzo.occhipinti@gmail.com>
    * @license http://opensource.org/licenses/gpl-license.php GNU Public License
    * @link http://data.baeproject.eu/BilancioEA Home del progetto
    */
    class User_Account_Manager{
        
      /**
        * Funzione per aggiungere un importo ad un conto usato dall'utente
		*
        * @param string $account_id ID del conto di bilancio
        * @param double $account_value Valore del conto di bilancio
        */
        
        public function addUserAccount($account_id, $account_value){
            //Mi serve per ricordare l'ultimo conto aggiunto che id ha
            $_SESSION['accounts']['last'] = $account_id; 
            $_SESSION['accounts'][$account_id] = $account_value;
        }
        
      /**
        * Funzione per rimuovere un conto usato dall'utente
        *
        * @param string $account_id ID del conto di bilancio
        */
        
        public function removeUserAccount($account_id){
            unset($_SESSION['accounts'][$account_id]);
        }       
        
      /**
        * Funzione che ritorna un unico conto settato dall'utente
        *
        * @param string $account_id ID del conto di bilancio
        * @return float,null Se il conto è settato ritorna l'importo, altrimenti ritorna null
        */
        
        public function getUserAccount($account_id){
            return(isset($_SESSION['accounts'][$account_id]) ? $_SESSION['accounts'][$account_id] : null);
        }
    
      /**
        * Funzione che restituisce tutti i conti scelti dall'utente
        *  
        *  esempio ritorno: array(
        *                ["last"]=> string(5) "35.05" 
        *                ["20.22"]=> float(70000) 
        *                ["37.01"]=> float(70000) 
        *                ["30.01"]=> float(780000) 
        *           )
        * @return array,null se ci sono conti scelti dall'utente ritorna l'array, altrimenti ritorna null
        */

        public function getAllUserAccounts(){
            return isset($_SESSION['accounts']) ? $_SESSION['accounts'] : null;
        }
        
      /**
        * Funzione che cancella tutti i conti scelti dall'utente
        *
        */

        public function resetAllUserAccounts(){
             unset($_SESSION['accounts']);
        }


         
    }

?>