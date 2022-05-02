<?php 

  /** Classe che rappresenta una riga del bilancio nel database
    * @author Gianlorenzo Occhipinti <gianlorenzo.occhipinti@gmail.com>
    * @license http://opensource.org/licenses/gpl-license.php GNU Public License
    * @link http://data.baeproject.eu/BilancioEA Home del progetto
    */
	class BalanceRow implements jsonSerializable{
		
        /** ID della riga del bilancio d'esercizio */
		private $id;
     
        /** Carattere Identificativo della riga del bilancio d'esercizio */
		private $char;
     
        /** Nome della riga del bilancio d'esercizio */
		private $name;
     
        /** ID della riga che contiene questa riga */
		private $parents;
     
        /** Prospetto di destinazione */
        private $destination;
     
        /** Array di tutti i conti della riga */
		private $accounts;
     
        /** Importo della riga del bilancio d'esercizioi*/
		private $import;
		
		        	  
	   /**
        * Questa funzione ritorna l'importo elaborato delle righe (comprensivo di quello delle righe figlie !)
		* @param string $id id della riga
		* @param char $char carattere identificativo della riga
		* @param string $name nome identificativo della riga
		* @param string $parent id identificativo della riga genitore
		* @param string $destination prospetto di destinazione
		* @param array $accounts array di conti che vanno in questa riga
		* @param float $import importo di questa riga
        */
		public function __construct($id, $char, $name, $parent, $destination, $accounts = array(), $import = null) {
			$this->setId($id);
			$this->setChar($char);
			$this->setName($name);
			$this->setParent($parent);
			$this->setDestination($destination);
			$this->setAccounts($accounts);
			$this->setImport($import);
		}
		
	   /**
        * Questa funzione che imposta l'id della riga
		* @param string $id id della riga
		*/
		public function setId($id) {
			$this->id = $id;
		}

	   /**
        * Questa funzione che imposta il carattere identificativo della riga
		* @param char $char carattere identificativo della riga
		*/
		public function setChar($char) {
			$this->char = $char;
		}
		
	   /**
        * Questa funzione che imposta il nome identificativo della riga
		* @param string $name nome identificativo della riga
		*/
		public function setName($name) {
			$this->name = $name;
		}
		
	   /**
        * Questa funzione che imposta id identificativo della riga genitore
		* @param string $parent id identificativo della riga genitore
		*/		
		public function setParent($parent) {
			$this->parents = $parent;
		}
		
	   /**
        * Questa funzione che imposta il prospetto di destinazione
		* @param string $destination prospetto di destinazione
		*/
        public function setDestination($destination) {
			$this->destination = $destination;
		}	
		
	   /**
        * Questa funzione che imposta l'array di conti che vanno in questa riga
		* @param array $accounts array di conti che vanno in questa riga
		*/
		public function setAccounts($accounts) {
			$this->accounts = $accounts;
		}
		
	   /**
        * Questa funzione che imposta l'importo di questa riga
		* @param float $import importo di questa riga
		*/
		public function setImport($import) {
			$this->import = $import;
		}		
		
	   /**
        * Questa funzione restituisce l'id della riga
		* @return string id della riga
		*/
		public function getId() {
			return $this->id;
		}	
		
	   /**
        * Questa funzione restituisce il carattere identificativo della riga
		* @return char carattere identificativo della riga
		*/
		public function getChar() {
			return $this->char;
		}	
		
	   /**
        * Questa funzione restituisce il nome identificativo della riga
		* @return string nome identificativo della riga
		*/
		public function getName() {
			return $this->name;
		}	

	   /**
        * Questa funzione restituisce id identificativo della riga genitore
		* @return string id identificativo della riga genitore
		*/	
		public function getParent() {
			return $this->parents;
		}
		
	   /**
        * Questa funzione restituisce il prospetto di destinazione
		* @return string  prospetto di destinazione
		*/
        public function getDestination() {
			return $this->destination;
		}

	   /**
        * Questa funzione restituisce l'array di conti che vanno in questa riga
		* @return array $accounts array di conti che vanno in questa riga
		*/		
		public function getAccounts() {
			return $this->accounts;
		}	
		
	   /**
        * Questa funzione restituisce l'importo di questa riga
		* @return float importo di questa riga
		*/
		public function getImport() {
			return $this->import;
		}
		
	   /**
        * Questa funzione gestisce la serializzazzione di questo oggetto in json
		* @return array oggetto in json serializzato
		*/
		public function jsonSerialize(){
			return [
						'id' => $this->id,
						'name' => $this->name,
						'char' => $this->char,
						'parents' => $this->parents,
						'destination' => $this->destination,
						'accounts' => $this->accounts,
						'import' => $this->import
			];
		}
	}
 ?>