<?php
require('../config/config.php');
require_once '../mercadopago/lib/mercadopago/vendor/autoload.php';

MercadoPago\SDK::setAccessToken($access_token);
$payment = MercadoPago\Payment::find_by_id($_GET["data_id"]);

$idVenda = $payment->{'external_reference'};
$payment_type_id = $payment->{'payment_type_id'};
$status = $payment->{'status'};
$status_detail = $payment->{'status_detail'};
$description = $payment->{'description'};
$transaction_amount = $payment->{'transaction_amount'};
$installments = $payment->{'installments'};

// Create connection
$conn = new mysqli($server, $user, $pass, $db);
mysqli_set_charset($conn,"utf8");

// Check connection
if ($conn->connect_error) {
  die("Connection Falhou: " . $conn->connect_error);
}

    $sql = "INSERT INTO ipn (idVenda, payment_type_id, status, status_detail, description, transaction_amount, installments)
    VALUES ($idVenda, '$payment_type_id', '$status', '$status_detail', '$description', $transaction_amount, $installments);";

if ($conn->multi_query($sql) === TRUE) {
  if($status === 'approved'){
    $registraBaixa = "UPDATE lancamentos SET baixado = 1, data_pagamento = now(), forma_pgto = 'Pix' where vendas_id = $idVenda";
    if ($conn->query($registraBaixa) === TRUE) {
      $conn->close();
    } 
  }
} else {
  $conn->close();
}



?>