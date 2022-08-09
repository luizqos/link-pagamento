<?php
$ambiente = ''; //VALOR PROD PARA PRODUÇÃO

$server = "";
$contraSenha = '';
if( $ambiente == 'PROD'){
    $linkNotification = '';
    $access_token = '';
    $key = '';
    $user = "";
    $pass = "";
    $db = "";
}else{
    $linkNotification = '';
    $access_token = '';
    $key = '';
    $user = "";
    $pass = "";
    $db = "";
}
$conexao = mysqli_connect($server, $user, $pass, $db);
mysqli_set_charset($conexao,"utf8");
?>