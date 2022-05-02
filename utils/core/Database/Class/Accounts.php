<?php 

  /** Classe che rappresenta una conto del bilancio nel database
    * @author Gianlorenzo Occhipinti <gianlorenzo.occhipinti@gmail.com>
    * @license http://opensource.org/licenses/gpl-license.php GNU Public License
    * @link http://data.baeproject.eu/BilancioEA Home del progetto
    */
	class BalanceAccount implements JsonSerializable {
		
        /** ID del conto nel DB */
		private $id;
     
        /** Nome del conto nel DB */
		private $name;
     
        /** Tipo del conto nel DB */
		private $type;
     
        /** Natura del conto nel DB */
		private $nature;
     
        /** Eccedenza del conto nel DB */
		private $eccedence;
     
        /** Booleano che mi dice se il conto è in rettifica di altri o meno */
		private $rectified;
     
        /** Categoria del conto nel DB */
		private $categories;
     
        /**		Riga del conto nel Bilancio D'esercizio */
		private $row_id;
     
        /** Valore del conto se definito dall'utente */
		private $import;
		
       /**
        * Questa funzione ritorna l'importo elaborato delle righe (comprensivo di quello delle righe figlie !)
		* @param string $id ID del conto
		* @param string $name Nome del conto
		* @param string $type Tipo del conto
		* @param char $nature Natura del conto 
		* @param string $eccedence Eccedenza del conto 
		* @param boolean $rectified Booleano che mi dice se il conto è in rettifica di altri o meno
		* @param Account_Categories $categories Categoria del conto
		* @param string $row_id Riga del conto nel Bilancio D'esercizio
		* @param float $import Valore del conto se definito dall'utente
        */
		public function __construct($id, $name, $type, $nature, $eccedence, $rectified, $categories, $row_id, $import = null) {
			$this->setId($id);
			$this->setName($name);
			$this->setTypes($type);
			$this->setnature($nature);
			$this->setEccedence($eccedence);
			$this->setRectified($rectified);
			$this->setCategories($categories);
			$this->setRowId($row_id);
			$this->setImport($import);
		}
		
        
		public function setId($id) {
			$this->id = $id;
		}	
     
		public function setName($name) {
			$this->name = $name;
		}	
     
		public function setTypes($type) {
			$this->type = $type;
		}	
     
		public function setNature($nature) {
			$this->nature = $nature;
		}
     
		public function setEccedence($eccedence) {
			$this->eccedence = $eccedence;
		}	
     
		public function setRectified($rectified) {
			$this->rectified = $rectified;
		}	
     
		public function setCategories($categories) {
			$this->categories = $categories;
		}	
     
		public function setRowId($row_id) {
			$this->row_id = $row_id;
		}
     
		public function setImport($import) {
			$this->import = $import;
		}
		
		//GETTER
		public function getId() {
			return $this->id;
		}	

		public function getName() {
			return $this->name;
		}		
		public function getTypes() {
			return $this->type;
		}	
		public function getNature() {
			return $this->nature;
		}		
		public function getEccedence() {
			return $this->eccedence;
		}		
		public function getRectified() {
			return $this->rectified;
		}		
		public function getCategories() {
			return $this->categories;
		}				
		public function getRowId() {
			return $this->row_id;
		}	
		public function getImport() {
			return $this->import;
		}
		
	   /**
        * Questa funzione gestisce la serializzazzione di questo oggetto in json
		* @return array oggetto in json serializzato
		*/
		public function jsonSerialize() {
        return [
                'id' => $this->id,
                'name' => $this->name,
                'type' => $this->type,
                'nature' => $this->nature,
                'eccedence' => $this->eccedence,
                'rectified' => $this->rectified,
                'categories' => $this->categories,
                'row_id' => $this->row_id,
                'import' => $this->import
            ];
		}
	}
 ?>