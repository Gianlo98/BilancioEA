<?php


    require_once("BilancioEA.php");

    $balanceObject = new BilancioEA();
    
    
    var_dump($balanceObject->getUserAccountManager()->getUserAccount('20.22'));
     

?>