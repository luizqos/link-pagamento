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
MercadoPago\SDK::setAccessToken(PROD_TOKEN);
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
 //echo '<pre>',print_r($payment),'</pre>';
 
// $response = array(
//     'status' => $payment->status,
//     'amount' => $payment->transaction_amount,
//     'status_detail' => $payment->status_detail,
//     'cardholder' => $payment->payment_method_id,
//     'cardtype' => $payment->payment_type_id,
//     'transaction' => $payment->collector_id,
//     'expiration' => $payment->card->expiration_month . '/'. $payment->card->expiration_year,
//     'cardnumber' => $payment->card->first_six_digits . 'XX XXXX '. $payment->card->last_four_digits,
//     'holdername' => $payment->card->cardholder->name,
//     'id' => $payment->id
// );
// $dados =  json_encode($response);


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


include('../conexao.php');
// Create connection
$conn = new mysqli($servidor, $usuario, $senha, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection Falhou: " . $conn->connect_error);
}

$sql = "INSERT INTO transacoes (uuid, idsale, description, status, amount, totalpaid, installmentsvalue, totalinstallments, statusdetail, cardholder, cardtype, transaction, expiration, cardnumber, holdername)
VALUES ('$uuid', $idVenda, '$description', '$status', $paid, $totalpaid, $installmentsvalue, $totalinstallments, '$status_detail', '$cardholder', '$cardtype', $transaction, '$expiration', '$cardnumberhide', '$holdername');";

if ($conn->multi_query($sql) === TRUE) {
  //echo "Registro inserido com sucesso";
} else {
  //echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

if($status == 'approved'){
  $status = 'Aprovado';
  $icon = 'success';
}else{
  $status = 'Recusado';
  $icon = 'error';
}

?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pagamento</title>
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon.ico">
    <link href="../assets/css/index.css" rel="stylesheet">
    <link href="../assets/css/custom.css" rel="stylesheet">
 
  <!-- Custom styles for this template -->
  <link href="https://getbootstrap.com/docs/5.2/examples/sign-in/signin.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>

<body class="text-center">


  <main class="form-signin w-100 m-auto">
    <img class="logo" src="../assets/img/logo.jpg" alt="Facil IPTV" width="100px">

    <form>
      <h2 class="h3 mb-3 fw-normal">Comprovante</h2>
     <div class="form-floating">
        <img src="../assets/img/<?php echo $icon;?>.png" width="75px">
        <br />
        <div class="content-return">
          <p><b><span><?php echo $description;?></span></b></p>
          <p><b>Transação: </b><span><?php echo $uuid;?></span></p>
          <p><b>Status: </b><span><?php echo $status;?></span></p>
          <p><b>Valor: </b><span><?php echo 'R$ ' . number_format($paid, 2, ',', '.');?></span></p>

            <?php  
                if ($totalinstallments > 1)
                {
            ?>
                  <p><b>Valor Parcelado: </b><span><?php echo 'R$ ' . number_format($totalpaid, 2, ',', '.');?></span></p>
                  <p><b>Parcelamento: </b><span><?php echo $totalinstallments . ' parcela(s) de R$ ' . number_format($installmentsvalue, 2, ',', '.');?></span></p>
            <?php  
            }
            ?>
            
          <p><b>Cartão: </b><span><?php echo strtoupper($cardholder);?></span></p>
          <p><b>Titular do Cartão: </b><span><?php echo strtoupper($holdername);?></span></p>
          <p><b>Nº do Cartão: </b><span><?php echo $cardnumberhide;?></span></p>
          <p><b>Validade: </b><span><?php echo $expiration;?></span></p>
        </div>
        <br />
      </div>
    </form>
  </main>

</body>

</html>