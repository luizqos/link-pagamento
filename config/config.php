<?php
$ambiente = ''; //VALOR PROD PARA PRODUÇÃO

$server = "";
$contraSenha = '';
if( $ambiente == 'PROD'){
    $access_token = '';
    $key = '';
    $user = "";
    $pass = "";
    $db = "";
}else{
    $access_token = '';
    $key = '';
    $user = "";
    $pass = "";
    $db = "";
}
$conexao = mysqli_connect($server, $user, $pass, $db);
mysqli_set_charset($conexao,"utf8");
?>