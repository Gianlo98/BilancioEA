<?php
    require_once(dirname(__FILE__) . "/../../BilancioEA.php");
    $BilancioEA = new BilancioEA();

    $balanceAccountManager = $BilancioEA->getBalanceAccountManager();
    $categoryManager = $BilancioEA->getCategoryManager();

    $response = array();

    if(isset($_GET["action"])){
        
        $response["action"] = $_GET["action"];
        
        switch($_GET["action"]){
    
            case "get":
                if(isset($_GET["account_id"])){
                    $account_id = $_GET["account_id"];
                    if(is_null($balanceAccountManager->getAccount($account_id))){
                        $response["error"] = "Wrong account_id parameter";
                    } else $response["data"] = $balanceAccountManager->getAccount($account_id);
    
                } else $response["error"] = "Missing account_id parameter";
                break;
                
            case "all":
                $response["status"] = "Done";
                $response["data"] = $categoryManager->getAllCategories();
                break;
            
            case "search":
                    $searched_account_name = isset($_GET["account_name"]) ? $_GET["account_name"] : "";
                    
                    $category_array = $categoryManager->getAllCategories();
                
                    foreach($category_array as $categoryID => $balanceCategoryOBJ){
                        
                        $account_array = $balanceCategoryOBJ->getAccounts();
                    
                        $newAccountArray = array();
                        
                        if (!is_null($account_array)){
                            foreach($account_array as $accountID => $balanceAccountOBJ){
                                $accountName = $balanceAccountOBJ->getName();
                                if($searched_account_name === "" || strpos(strtolower($accountName),strtolower($searched_account_name)) !== false){
                                    $newAccountArray[$accountID] = $balanceAccountOBJ;
                                }
                            }
                            
                        }
                        
                        
                        if(!empty($newAccountArray)){
                            $balanceCategoryOBJ->setAccounts($newAccountArray);
                            $response["data"][intval($categoryID)] = $balanceCategoryOBJ;
                        }
                    }    
                break;
            
            default:
                $response["error"] = "Missing action requested";
        }
    } else $response["error"] = "Missing action parameter";


    echo (json_encode($response)); 
?>