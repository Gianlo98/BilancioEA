<?php
	
  /** Classe che gestisce la connessione col database
    * @author Gianlorenzo Occhipinti <gianlorenzo.occhipinti@gmail.com>
    * @license http://opensource.org/licenses/gpl-license.php GNU Public License
    * @link http://data.baeproject.eu/BilancioEA Home del progetto
    */
    class Database{
        
    /**
     *  @method dbConnect
     *  Metodo per connettersi al DB
     */
        static function dbConnect(){	
            require_once(dirname(__FILE__) . "../../../configuration/config.php");
            $database = new mysqli(Config::DB_HOST, Config::DB_USERNAME, Config::DB_PASSWORD, Config::DB_NAME);
            if($database->connect_errno !== 0){
              die("[BilancioEA] Errore nella connessione al database: " . $database->connect_error);
            }
            mysqli_set_charset($database, 'utf8');
            return $database;
        }
        
    }
?>
