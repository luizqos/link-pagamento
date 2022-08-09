<?php
require('../config/config.php');
require('../mercadopago/lib/mercadopago/vendor/autoload.php');

#Variables
$email=filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);
$cardNumber=filter_input(INPUT_POST,'cardNumber',FILTER_DEFAULT);
$securityCode=filter_input(INPUT_POST,'securityCode',FILTER_DEFAULT);
$cardExpirationMonth=filter_input(INPUT_POST,'cardExpirationMonth',FILTER_DEFAULT);
$cardExpirationYear=filter_input(INPUT_POST,'cardExpirationYear',FILTER_DEFAULT);
$cardholderName=filter_input(INPUT_POST,'cardholderName',FILTER_DEFAULT);
$docType=filter_input(INPUT_POST,'docType',FILTER_DEFAULT);
$docNumber=filter_input(INPUT_POST,'docNumber',FILTER_DEFAULT);
$installments=filter_input(INPUT_POST,'installments',FILTER_DEFAULT);
$amount=filter_input(INPUT_POST,'amount',FILTER_DEFAULT);
$description=filter_input(INPUT_POST,'description',FILTER_DEFAULT);
$paymentMethodId=filter_input(INPUT_POST,'paymentMethodId',FILTER_DEFAULT);
$token=filter_input(INPUT_POST,'token',FILTER_DEFAULT);
$idVenda=filter_input(INPUT_POST,'idvenda',FILTER_DEFAULT);

#Method
MercadoPago\SDK::setAccessToken($access_token);
$payment = new MercadoPago\Payment();
$payment->transaction_amount = $amount;
$payment->token = $token;
$payment->description = $description;
$payment->installments = $installments;
$payment->payment_method_id = $paymentMethodId;
$payment->payer = array(
    "email" => $email
);
$payment->save();

function random_str_ossl($size) 
{
    return bin2hex(openssl_random_pseudo_bytes($size));
}

$uuid = random_str_ossl(20);

$status = $payment->status;
$paid = $payment->transaction_amount;
$totalpaid = $payment->transaction_details->total_paid_amount;
$totalinstallments = $payment->installments;
$installmentsvalue = $payment->transaction_details->installment_amount;
$status_detail = $payment->status_detail;
$cardholder = $payment->payment_method_id;
$cardtype = $payment->payment_type_id;
$transaction = $payment->collector_id;
$expiration = $payment->card->expiration_month . '/'. $payment->card->expiration_year;
$cardnumberhide = $payment->card->first_six_digits . 'XXXXXX'. $payment->card->last_four_digits;
$holdername = $payment->card->cardholder->name;


// Create connection
$conn = new mysqli($server, $user, $pass, $db);
mysqli_set_charset($conn,"utf8");

// Check connection
if ($conn->connect_error) {
  die("Connection Falhou: " . $conn->connect_error);
}

$expiration = strlen($expiration) < 7 ? '0' . $expiration : $expiration;

$sql = "INSERT INTO transacoes (uuid, idsale, description, status, amount, totalpaid, installmentsvalue, totalinstallments, statusdetail, cardholder, cardtype, transaction, expiration, cardnumber, holdername)
VALUES ('$uuid', $idVenda, '$description', '$status', $paid, $totalpaid, $installmentsvalue, $totalinstallments, '$status_detail', '$cardholder', '$cardtype', $transaction, '$expiration', '$cardnumberhide', '$holdername');";

if ($conn->multi_query($sql) === TRUE) {
  if($status === 'approved'){
    $registraBaixa = "UPDATE lancamentos SET baixado = 1, data_pagamento = now(), forma_pgto = 'Cartão de Crédito' where vendas_id = $idVenda";
    if ($conn->query($registraBaixa) === TRUE) {
      $conn->close();
    } 
  }
} else {
  $conn->close();
}
header("Location: ./?uuid=" . $uuid);
?>