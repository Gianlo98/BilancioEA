<?php
 /**
  * Classe principale del progetto.
  *
  * Ciao Bianca :D
  *
  * TODO
  * - cambiare nel DB balance_sheet_rows con financial_statement_rows
  * - nome categoria a volte category a volte categories
  * - ottimizzare funzioni sui conti ES: getAllCategoryAccounts si ferma dato che l'array è ordinato
  * - gestione di tutte le risorse in locale
  * - apigen
  * - creare il manager per gli esercizi (ajax)
  * - addattare index al nuovo sitema
  * -  - Card per la visualizzazione delle informazioni sul conto
  * -  - richieste Ajax
  * -  - moduli tutti in html
  * - Stato patrimoniale Rielaborato
  * - Conto Economico Rielaborato
  * - Pannello admin
  * -  - Aggiungere/Rimuovere Esercizi
  * -  - Aggiungere/Rimuovere/Modificare Conti
  * -  - Aggiungere/Rimuovere/Modificare Righe conto economico
  * - Inidici
  * - Flussi
  *
  * 2 mesi =  60 giorni
  * 60 giorni = 2 ore libere
  * 2 * 60 = 120 ore disponibili
  * 120 - 40 imprevisti = 80 ore nette
  */

    /* Risorse */
    require_once(dirname(__FILE__) . "/core/Database/Database.php");
    require_once(dirname(__FILE__) . "/configuration/config.php");

    /* Manager */
    require_once(dirname(__FILE__) . "/core/Manager/User_Account_Manager.php");
    require_once(dirname(__FILE__) . "/core/Manager/Balance_Account_Manager.php");
    require_once(dirname(__FILE__) . "/core/Manager/Balance_Row_Manager.php");
    require_once(dirname(__FILE__) . "/core/Manager/Exercise_Manager.php");
    require_once(dirname(__FILE__) . "/core/Manager/Category_Manager.php");
    
  /** Classe principale del progetto
    * @author Gianlorenzo Occhipinti <gianlorenzo.occhipinti@gmail.com>
    * @license http://opensource.org/licenses/gpl-license.php GNU Public License
    * @link http://data.baeproject.eu/BilancioEA Home del progetto
    */
    class BilancioEA {

        private $database;
        private $userAccountManager;
        private $exerciseManager;
        private $balanceAccountManager;
        private $balanceRowManager;
        private $categoryManager;
        
        /** Metodo costruttore che istanzia i vari manager */
        public function __construct() {

           if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}
            $this->database = Database::dbConnect();
            
            if(Config::DEBUG){
                error_reporting(E_ALL);
                ini_set('display_errors', 1);
            }

            $this->userAccountManager = new User_Account_Manager($this);
            $this->exerciseManager =  new Exercise_Manager($this);
            $this->balanceAccountManager = new Balance_Account_Manager($this);
            $this->balanceRowManager = new Balance_Row_Manager($this);
            $this->categoryManager = new Category_Manager($this);
        }
        
      /**
        * Questo metodo ritorna l'oggetto riferito alla connessione col Database per eseguire le query
        * @return Database Che si riferisce alla connessione al db.
        */
        public function getDatabase() {
            return $this->database;
        }
        
      /**
        * Questo metodo ritorna l'oggetto riferito al Manager per la gestione dei conti utilizzati dall'utente
        * @return User_Account_Manager Che si riferisce al Manager per la gestione dei conti utente
        */
        public function getUserAccountManager() {
            return $this->userAccountManager;
        }
        
      /**
        * Questo metodo ritorna l'oggetto riferito al Manager per la gestione degli esercizi
        * @return Exercise_Manager Che si riferisce al Manager per la gestione degli esercizi
        */
        public function getExerciseManager() {
            return $this->exerciseManager;
        }
        
      /**
        * Questo metodo ritorna l'oggetto riferito al Manager per la gestione di tutti i conti inscritti in bilancio
        * @return Balance_Account_Manager Che si riferisce al Manager per la gestione di tutti i conti inscritti in bilancio
        */
        public function getBalanceAccountManager() {
            return $this->balanceAccountManager;
        }
        
      /**
        * Questo metodo ritorna l'oggetto riferito al Manager per la gestione di tutte le righe inscritte in bilancio
        * @return Balance_Row_Manager Che si riferisce al Manager per la gestione di tutte le righe inscritte in bilancio
        */
        public function getBalanceRowManager() {
            return $this->balanceRowManager;
        }

      /**
        * Questo metodo ritorna l'oggetto riferito al Manager per la gestione di tutte le categorie dei conti
        * @return Category_Manager Che si riferisce al Manager per la gestione di tutte le categorie dei conti
        */
        public function getCategoryManager() {
            return $this->categoryManager;
        }




    }

?>
