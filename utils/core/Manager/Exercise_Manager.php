<?php

	include(dirname(__FILE__) . "/../../../plugins/phpqrcode/qrlib.php");

  /** Classe che gestisce gli esercizi
    * @author Gianlorenzo Occhipinti <gianlorenzo.occhipinti@gmail.com>
    * @license http://opensource.org/licenses/gpl-license.php GNU Public License
    * @link http://data.baeproject.eu/BilancioEA Home del progetto
    */
    class Exercise_Manager{

        private $database;
        private $userAccountManager;
        /**
         * Funzione per settare un nuovo esercizio corrente
         *
         * @param BilancioEA $balance_object oggetto del bilancio
         */
        public function __construct($balance_object){

            $this->database = $balance_object->getDatabase();
            $this->userAccountManager = $balance_object->getUserAccountManager();

        }

        /**
         * Funzione per settare un nuovo esercizio corrente
         *
         * @param string $exercise_id ID dell'esercizio
         * @return boolean se il settaggio ha avuto successo o meno
         */

        function setUserExercise($exercise_id){

            $result = $this->database->query("SELECT * FROM exercises WHERE exercises_id = '$exercise_id'");

            if($result->num_rows > 0){

                $result = $this->database->query("SELECT * FROM exercises_account WHERE exercise_id = '$exercise_id'");

                $this->resetUserExercise();
				$_SESSION['exercise'] = $exercise_id;

                while($ex_account = $result->fetch_object()){
                    $account_id = $ex_account->account_id;
                    $account_value = floatval($ex_account->value);
                    $this->userAccountManager->addUserAccount($account_id,$account_value);
                }
				
				$result = $this->database->query("SELECT * FROM exercises_propriety WHERE exercise_id = '$exercise_id'");

				while($ex_propriety = $result->fetch_object()){
                    $exercise_propriety = $ex_propriety->exercise_propriety;
                    $exercise_propriety_value = $ex_propriety->exercise_propriety_value;
					$this->setExercisePropriety($exercise_propriety,$exercise_propriety_value);
                }
					
                return true;

            } else return false;

        }

        /**
         * Funzione per conoscere l'ID dell'esercizio corrente
         *
         * @return string ID dell'esercizio
         */

        function getUserExercise(){
            if(isset($_SESSION['exercise'])){
                $exercise_id = $_SESSION['exercise'];
                $result = $this->database->query("SELECT * FROM exercises WHERE exercises_id = '$exercise_id'");
                if($result->num_rows > 0) return $result->fetch_object();
            } else return false;
        }        
		
		/**
         * Funzione per settare la proprietà di un esercizio
         *
         * @param string $proprietyName nome proprietà
         * @param string $proprietyValue valore proprietà
         */

        function setExercisePropriety($proprietyName, $proprietyValue){
			
            $_SESSION['exercise_propriety'][$proprietyName] = $proprietyValue; 
        }  		
		
		/**
         * Funzione per che ritorna le proprietà di un esercizio
         *
         * @param string $proprietyName nome proprietà
         * @return array proprietà dell'esercizio
         */

        function getExerciseProperties($exercise_id){
			
			$result = $this->database->query("SELECT * FROM exercises_propriety WHERE exercise_id = '$exercise_id'");
			return $result->fetch_all(MYSQLI_ASSOC);
			
        } 		
		
		/**
         * Funzione per salvare la proprietà di un esercizio
         *
         * @param string $exercise_id id esercizio
         * @param string $proprietyName nome proprietà
         * @param string $proprietyValue valore proprietà
         * @return boolead successo dell'inserimento
         */

        function addExercisePropriety($exercise_id, $proprietyName, $proprietyValue){
			$result = $this->database->query("SELECT * FROM `exercises_propriety` WHERE exercise_id = '$exercise_id' AND exercise_propriety = '$proprietyName'");
			if($result->num_rows == 0){
				$result = $this->database->query("INSERT INTO `exercises_propriety` (`relation_id`, `exercise_id`, `exercise_propriety`, `exercise_propriety_value`) VALUES (NULL, '$exercise_id', '$proprietyName', '$proprietyValue')");
			} else $result = $this->database->query("UPDATE `exercises_propriety` SET exercise_propriety_value = '$proprietyValue' WHERE exercise_id = '$exercise_id' AND exercise_propriety = '$proprietyName'");
		
			return $result;
		}        
		
		/**
         * Funzione per sapere tutti gli esercizi previsti
         *
         * @return array contenente tutti gli esercizi
         */

        function getAllUserExercise(){
			$result = $this->database->query("SELECT * FROM exercises");
			return $result->fetch_all(MYSQLI_ASSOC);
        }		
		
		/**
         * Funzione per generare il QRCODE nella cartella /utils/QRcode/exerciseQR_00.png
		 *
		 * @param $exercise_id id esercizio
         */

        function generateQRCode($exercise_id){
			
			$key = $_SERVER['HTTP_REFERER'] . "?exercise_id=" . $exercise_id;
			
			//$filename = "../utils/QRcode/".'exerciseQR_'. str_replace('.','',$exercise_id).'.png';
			$filename = $_SERVER['DOCUMENT_ROOT'] . "/BilancioEA/utils/QRcode/".'exerciseQR_'. str_replace('.','',$exercise_id).'.png';

			QRcode::png($key, $filename, "Q",10, 2);
        }	
		
		/**
         * Funzione per sapere un determinato esercizio
         * @param string $exercise_id ID dell'esercizio
         * @return array contenente tutte le informazioni sull'esercizio
         */

        function getExercise($exercise_id){
			$result = $this->database->query("SELECT * FROM exercises WHERE exercises_id = '$exercise_id'");
			return $result->fetch_all(MYSQLI_ASSOC);
        }

        /**
         * Funzione per sapere l' ID dell'esercizio attualmente selezionato
         *
         * @return string,false ritorna l'id se c'è un esercizio selezionato altrimenti ritorna false
         */

        function getUserExerciseID(){
            if(isset($_SESSION['exercise'])){
                return $_SESSION['exercise'];
            } else return false;
        }

        /**
         * Funzione per deselezionare l'esercizio corrente
         *  
         */

        function resetUserExercise(){
            unset($_SESSION['exercise']);
            unset($_SESSION['exercise_propriety']);
            $this->userAccountManager->resetAllUserAccounts();
        }        
		
		/**
         * Funzione per autenticare la chiave per la modifica dei dati
         *  @param string $manager_key chiave d'autenticazione
         * @return boolean successo dell'autenticazione
         */

        function authManagerKey($managerKey){
             $result = $this->database->query("SELECT * FROM options WHERE option_name = 'EXERCISE_SECRET_KEY'");
			 $validKey = $result->fetch_object();
			 if ($validKey->option_value == $managerKey) $_SESSION['managere_exrcise_key'] = sha1("AGianloLaTeoriaDiEconomiaNonPiace" .  $managerKey);
	 		 return ($validKey->option_value == $managerKey);
        }	

		/**
         * Funzione per deautenticare la chiave
         * @return true sucesso dell'operazione
         */

        function deauthManagerKey(){
			unset($_SESSION['managere_exrcise_key']);
	 		return (true);
        }
		
		/**
         * Funzione sapere se l'utente è autenticato o no
         *  @return boolean se l'utente è autenticato per modificare gli esercizi o meno
         */

        function isAuth(){
             $result = $this->database->query("SELECT * FROM options WHERE option_name = 'EXERCISE_SECRET_KEY'");
			 $validKey = $result->fetch_object();
			 if(isset($_SESSION['managere_exrcise_key'])){
				return ($_SESSION['managere_exrcise_key'] == sha1("AGianloLaTeoriaDiEconomiaNonPiace" .  $validKey->option_value));
			 } else return false;
        }
		
		/**
         * Funzione per eliminare un'esercizio dal db
         * @param string $exercise_id id dell'esercizio da eliminare
         * @return boolean successo dell'operazione
         */

       
        function deleteUserExercise($exercise_id){
				$result = $this->database->query("DELETE FROM exercises_propriety WHERE exercise_id = '$exercise_id'");
				$result = $this->database->query("DELETE FROM exercises_account WHERE exercise_id = '$exercise_id'");
				$result = $this->database->query("DELETE FROM exercises WHERE exercises_id = '$exercise_id'");
                return true;
        }
		
		/**
         * Funzione per creare un'esercizio nel db
         * @param string $exercise_id id esercizio
         * @param int $exercise_page pagina dell'esercizio
         * @param int $exercise_difficulty difficoltà dell'esercizio
         * @param string $exercise_note note dell'esercizio
         * @return boolean successo dell'operazione
         */

       
        function createUserExercise($exercise_id,$exercise_page,$exercise_difficulty,$exercise_note){
			
            $result = $this->database->query("INSERT INTO `exercises` (`exercises_id`, `exercises_pages`, `exercises_difficulty`, `exercises_notes`) VALUES ('$exercise_id', '$exercise_page', '$exercise_difficulty', '$exercise_note')");
			
            foreach($this->userAccountManager->getAllUserAccounts() as $id => $value){
				$acresult = $this->database->query("INSERT INTO `exercises_account` (`relation_id`, `exercise_id`, `account_id`, `value`) VALUES (NULL, '$exercise_id', '$id', '$value')");
			}
			
			$this->generateQRCode($exercise_id);
			
			return $result;
        }
		
		/**
         * Funzione per modificare un'esercizio nel db
         * @param string $exercise_id id esercizio
         * @param int $exercise_page pagina dell'esercizio
         * @param int $exercise_difficulty difficoltà dell'esercizio
         * @param string $exercise_note note dell'esercizio
         * @return boolean successo dell'operazione
         */

       
        function editUserExercise($exercise_id,$exercise_page,$exercise_difficulty,$exercise_note){
			
            $result = $this->database->query("UPDATE SET exercises_pages = '$exercise_page', exercises_difficulty  = '$exercise_difficulty', exercises_notes = '$exercise_note' WHERE exercises_id = '$exercise_id')");
			
			return $result;
        }
		
		

    }

?>
