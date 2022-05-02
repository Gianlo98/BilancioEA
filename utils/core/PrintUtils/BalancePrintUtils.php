<?php

  /** Classe che gestisce le operazioni di stampa delle righe dei prospetti di bilancio
    * @author Gianlorenzo Occhipinti <gianlorenzo.occhipinti@gmail.com>
    * @license http://opensource.org/licenses/gpl-license.php GNU Public License
    * @link http://data.baeproject.eu/BilancioEA Home del progetto
    */
    class BalancePrintUtils{
        
        private $balanceRows;
		
      /**
        * Metodo costruttore della classe
        * @return Balance_Account_Manager Che si riferisce al Manager per la gestione di tutti i conti inscritti in bilancio
        */
        function __construct($balanceRows){
            $this->balanceRows = $balanceRows;
        }
    
	  /**
        * Funzione che stampa una riga di un prospetto sottto formato <td>nome</td><td>importo</td>
		* @param Balance_Row $row riga da stampare
        * @return int importo della riga
        */
        function print_row($row){
            
            $rows = $this->balanceRows;
            $id = $row->getId();
            $char_id = $row->getChar();
            $name = $row->getName();
            $parent = $row->getParent();
            $accounts = $row->getAccounts();
            $import = $row->getImport();
            
            //row_super - row_normal - row_child - row_super_child in base al tipo di riga
            $row_parent = $row->getParent();
            if(!is_null($row_parent)){
                $step = 0;
                while(!is_null($rows[$row_parent]->getParent())){
                    $row_parent = $rows[$row_parent]->getParent();
                    $step++;
                }
                switch($step){
                    case 0: 
                        $row_class = "row_normal";
                        break;							
                    case 1: 
                        $row_class = "row_child";
                        break;						
                    case 2: 
                        $row_class = "row_super_child";
                        break;
                }
            } else $row_class = "row_super";
            
            /*//row_super - row_child - row_normal in base al tipo di riga
            $row_class = ($row->getParent() == null) ? "row_super" : (($rows[$row->getParent()]->getParent() != null) ? "row_child" : "row_normal");
*/
							$has_child = false;
					if(isset($rows[$id+1])){
						$has_child = ($rows[$id+1]->getParent() == $id);
					}

            //Stampo il primo tr con le classi e con alcuni attributi
            echo "<tr class=\"financial_statement_row $row_class ". (((is_null($import) && !is_null($parent)) && !$has_child) ? "row_void" : "") ."\" row_id=\"" . $row->getId() . "\" >";

                //Stampo Carattere e Nome della Riga
                echo "<td class=\"row_name\">" . $row->getChar() . ") ". $row->getName() . "</td>";

                //Stampo l'eventuale importo
                echo "</td><td class=\"row_import\">";
					echo (!is_null($import)) ? number_format ($import,0,",",".") : (($has_child) ? "" : "-");
                echo "</td>";

                //Mi preparo lo script in JS
                //mi salvo in un array tutti i conti e i loro importi, arrayRow + id_riga[id_conto]. ex: arrayRow2['20.01'] = {import:"10",name:"Prodotti c/vendite",rectified:0}
                $jsArray = "var arrayRow$id = []; ";

                //Se non ha sottorighe posso calcolarmi l'importo
                if (!empty($accounts)){
                    //Sommo ogni conto appartente alla riga che l'utente ha inserito
                    foreach($accounts as $account_id=> $account){		
                        //Inserisco il conto nell'array in js inerente alla riga corrente
                        if(!is_null($account->getImport())) {
                            $jsArray .= 'arrayRow' . $id . '.push({
                                id:"'.$account->getId().'",
                                name:"'.$account->getName().'",
                                import:"'.$account->getImport().'",
                                rectified:'.$account->getRectified().'
                            });';
                        }
                    }	
                }

                //Stampo lo script fuori dal td
                echo "<script>" . $jsArray . "</script>";

            echo "</tr>";

            //Ritorno il valore arrotondato per fare la somma di tutte le righe, lo ritorno arrotondato
            return $import;
        }
		
	  /**
        * Funzione che stampa una riga di un prospetto sottto formato <td>nome</td><td>importo</td>
		* @param char $row_char_identificator carattere identificativo della riga
		* @param char $row_name nome identificativo della riga
		* @param char $row_import importo riga
		* @param char $row_class classe della riga
		* la classe può essere:
		*	row_super = riga genitore di tutte (ES. A) Valore della produzione oppure A) Patrimonio netto)
		*   row_normal = riga figlia di una riga super
		*   row_child = riga figlia di una riga normale
		*   row_super_child = riga figlia di una riga figlia
		*   row_total = riga del totale
        * @return int importo della riga
        */
        function print_custom_row($row_char_identificator, $row_name, $row_import = null, $row_class = ""){


            echo "<tr class=\"financial_statement_row $row_class \">";

            //Stampo Carattere e Nome della Riga
            echo "<td class=\"row_name\">" . (($row_char_identificator == "" ) ? "" : $row_char_identificator. ") " ). $row_name . "</td>";
            echo "<td class=\"row_import\">";

            echo ($row_import == -1) ? "" :(($row_import == null) ? "-" : number_format ($row_import,0,",","."));

            echo "</td></tr>";

            return $row_import;
        }
        
        
    }
?>