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

// Busca IPTV
$buscaCliente = "SELECT clientes.userIptv from vendas inner join clientes on clientes.idClientes = vendas.clientes_id where idVendas = $idVenda";
$result = $conn->query($buscaCliente);
  if ($result->num_rows > 0) {
    // Percorre cada linha do resultado
    while ($row = $result->fetch_assoc()) {
        $userIptv = $row["userIptv"];
    }
  }

    $sql = "INSERT INTO ipn (idVenda, payment_type_id, status, status_detail, description, transaction_amount, installments)
    VALUES ($idVenda, '$payment_type_id', '$status', '$status_detail', '$description', $transaction_amount, $installments);";

if ($conn->multi_query($sql) === TRUE) {
  if($status === 'approved'){
    $registraBaixa = "UPDATE lancamentos SET baixado = 1, data_pagamento = now(), forma_pgto = 'Pix' where vendas_id = $idVenda";
    if ($conn->query($registraBaixa) === TRUE) {
      $conn->close();
      renovaAssinatura($endpoint,$userIptv);
    }else{
      $conn->close();
    }
  }
} else {
  $conn->close();
}

function renovaAssinatura($endpoint,$loginIptv) {
  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $endpoint.$loginIptv,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
));

$response = curl_exec($curl);

curl_close($curl);
}

?>