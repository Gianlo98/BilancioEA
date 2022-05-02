<?php
    require_once(dirname(__FILE__) . "/../../BilancioEA.php");
    $BilancioEA = new BilancioEA();

    $userAccountManager = $BilancioEA->getUserAccountManager();

    $response = array();

    if(isset($_GET["action"])){
        
        $response["action"] = $_GET["action"];
        
        switch($_GET["action"]){
    
            case "add":
                if(isset($_GET["account_id"])){
                    if(isset($_GET["account_value"])){

                        $account_id = $_GET["account_id"];
                        $account_value = floatval($_GET["account_value"]);

                        $userAccountManager->addUserAccount($account_id,$account_value);

                        $response["status"] = "Done";
                    } else $response["error"] = "Missing account_value parameter";
                } else $response["error"] = "Missing account_id parameter";
                break;
                
            case "remove":
                if(isset($_GET["account_id"])){
                    $account_id = $_GET["account_id"];
                    $userAccountManager->removeUserAccount($account_id);
                    $response["status"] = "Done";
                } else $response["error"] = "Missing account_id parameter";
                break;
            
            case "get":
                $response["data"] = $userAccountManager->getAllUserAccounts();
                break;
                
            case "reset":
                $userAccountManager->resetAllUserAccounts();
                $response["status"] = "Done";
                break;
            
            default:
                $response["error"] = "Missing action requested";
        }
    } else $response["error"] = "Missing action parameter";


    echo (json_encode($response)); 
?>