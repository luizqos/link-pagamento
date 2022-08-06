<?php
include('../config/config.php');
if(isset($_GET['req'])){
    $request = $_GET['req'];
    if($request == $contraSenha){
        echo json_encode(array("key" => "$key"));
       exit;
    }else{
        header("Location: ../"); 
     }
 }
 else{
    header("Location: ../"); 
 }
 ?>