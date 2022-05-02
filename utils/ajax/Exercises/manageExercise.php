<?php
    require_once(dirname(__FILE__) . "/../../BilancioEA.php");
    $BilancioEA = new BilancioEA();

    $exerciseManager = $BilancioEA->getExerciseManager();

    $response = array();

    if(isset($_GET["action"]) || isset($_POST["action"]) ){
        
		$action = isset($_GET["action"]) ? $_GET["action"] : $_POST["action"];
		
        $response["action"] = $action;
        
        switch($action){
    
            case "set":
                if(isset($_GET["exercise_id"])){
                    $exercise_id = $_GET["exercise_id"];
                    if($exerciseManager->setUserExercise($exercise_id)){
                        $response["status"] = "Done";
                    } else $response["error"] = "Wrong exercise_id";
                } else $response["error"] = "Missing exercise_id parameter";
                break;
                        
            case "get_current":
                $response["data"] = $exerciseManager->getUserExercise();
                break;
			
			case "get":
				if(isset($_GET["exercise_id"])){
					$response["status"] = "Done";
					$response["data"] = $exerciseManager->getExercise($_GET["exercise_id"]);
				} else $response["error"] = "Missing exercise_id parameter";
				break;			
			
			case "get_properties":
				if(isset($_GET["exercise_id"])){
					$response["status"] = "Done";
					$response["data"] = $exerciseManager->getExerciseProperties($_GET["exercise_id"]);
				} else $response["error"] = "Missing exercise_id parameter";
				break;
				
			case "get_all":
				$response["status"] = "Done";
			    $response["data"] = $exerciseManager->getAllUserExercise();
                break;
				
            case "auth_key":
				if(isset($_POST["manager_key"])){
					$response["status"] = "Done";
					$response["data"] = $exerciseManager->authManagerKey($_POST["manager_key"]);
				}else $response["error"] = "Missing manager_key parameter";
				break;
				
			case "deauth_key":
				$exerciseManager->deauthManagerKey();
				$response["status"] = "Done";
				break;
				
            case "reset":
                $exerciseManager->resetUserExercise();
                $response["status"] = "Done";
                break;
				
			case "create":
				if($exerciseManager->isAuth()){
					if(isset($_POST["exercise_id"])){
						if(isset($_POST["exercise_difficulty"])){
							if(isset($_POST["exercise_pages"])){
								if(isset($_POST["exercise_notes"])){
									$rs = $exerciseManager->createUserExercise($_POST["exercise_id"],$_POST["exercise_pages"],$_POST["exercise_difficulty"],$_POST["exercise_notes"]);
									$response["status"] = $rs ? "Done" : "Incompleted";
								}else $response["error"] = "Missing exercise_notes parameter";
							}else $response["error"] = "Missing exercise_pages parameter";
						}else $response["error"] = "Missing exercise_difficulty parameter";
					}else $response["error"] = "Missing exercise_id parameter";
				}else $response["error"] = "Operation not permitted";
				break;			
				
			case "set_propriety":
				if($exerciseManager->isAuth()){
					if(isset($_POST["exercise_id"])){
						if(isset($_POST["exercise_proprieties"])){
							$proprieties = $_POST["exercise_proprieties"];
							foreach($proprieties as $key => $value){
								$rs = $exerciseManager->addExercisePropriety($_POST["exercise_id"],$proprieties[$key]['name'],$proprieties[$key]['value']);
							}
							$response["status"] = $rs ? "Done" : "Incompleted";
						}else $response["error"] = "Missing exercise_proprieties parameter";
					}else $response["error"] = "Missing exercise_id parameter";
				}else $response["error"] = "Operation not permitted";
				break;
				
            
			case "delete":
				if($exerciseManager->isAuth()){
					if(isset($_GET["exercise_id"])){
						$exerciseManager->deleteUserExercise($_GET["exercise_id"]);
						$exerciseManager->resetUserExercise();
						$response["status"] = "Done";
					} else $response["error"] = "Missing exercise_id parameter";
				} else $response["error"] = "Operation not permitted";
				break;
				
			case "edit":
				if($exerciseManager->isAuth()){
					if(isset($_POST["exercise_id"])){
						if(isset($_POST["exercise_difficulty"])){
							if(isset($_POST["exercise_pages"])){
								if(isset($_POST["exercise_notes"])){
									$rs = $exerciseManager->editUserExercise($_POST["exercise_id"],$_POST["exercise_pages"],$_POST["exercise_difficulty"],$_POST["exercise_notes"]);
									$response["status"] = $rs ? "Done" : "Incompleted";
								}else $response["error"] = "Missing exercise_notes parameter";
							}else $response["error"] = "Missing exercise_pages parameter";
						}else $response["error"] = "Missing exercise_difficulty parameter";
					}else $response["error"] = "Missing exercise_id parameter";
				}else $response["error"] = "Operation not permitted";
				break;
				
            default:
                $response["error"] = "Missing action requested";
        }
    } else $response["error"] = "Missing action parameter";


    echo (json_encode($response)); 

?>