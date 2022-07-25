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

#Method
MercadoPago\SDK::setAccessToken(SAND_TOKEN);
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
// echo '<pre>',print_r($payment),'</pre>';
$response = array(
    'status' => $payment->status,
    'amount' => $payment->transaction_amount,
    'status_detail' => $payment->status_detail,
    'cardholder' => $payment->payment_method_id,
    'cardtype' => $payment->payment_type_id,
    'transaction' => $payment->collector_id,
    'id' => $payment->id
);


echo json_encode($response);

?>