<?php 
	
  /** Classe che rappresenta una categoria dei conti del bilancio nel database
    * @author Gianlorenzo Occhipinti <gianlorenzo.occhipinti@gmail.com>
    * @license http://opensource.org/licenses/gpl-license.php GNU Public License
    * @link http://data.baeproject.eu/BilancioEA Home del progetto
    */
	
	class BalanceCategory implements jsonSerializable{
		
		/** ID categoria */
		private $id;
     
        /** Nome categoria */
		private $name;
     
        /** Note categoria */
		private $notes;
     
        /** Array conti della categoria */
        private $accounts;
		
	   /**
        * Questa funzione ritorna l'importo elaborato delle righe (comprensivo di quello delle righe figlie !)
		* @param string $id ID categoria
		* @param string $name Nome categoria
		* @param string $notes Note categoria
		* @param char $accounts Array conti della categoria
        */
		public function __construct($id, $name, $notes, $accounts) {
			$this->setId($id);
			$this->setName($name);
			$this->setTypes($notes);
            $this->setAccounts($accounts);
            
		}
		
		//SETTER
		public function setId($id) {
			$this->id = $id;
		}		
		public function setName($name) {
			$this->name = $name;
		}		
		public function setTypes($notes) {
			$this->notes = $notes;
		}		
        
        public function setAccounts($accounts) {
			$this->accounts = $accounts;
		}
		
		//GETTER
		public function getId() {
			return $this->id;
		}		
		public function getName() {
			return $this->name;
		}		
		public function getNotes() {
			return $this->notes;
		}		
        public function getAccounts() {
			return $this->accounts;
		}
		
       /**
        * Questa funzione gestisce la serializzazzione di questo oggetto in json
		* @return array oggetto in json serializzato
		*/
		public function jsonSerialize() {
        return [
                'id' => $this->id,
				'name' => $this->name,
				'notes' => $this->notes,
                'accounts' => $this->accounts
            ];
		}
 }
 ?>