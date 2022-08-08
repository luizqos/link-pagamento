<?php
ini_set('display_errors',0);
ini_set('display_startup_erros',0);
error_reporting(E_ALL);

$uuid=(isset($_GET['uuid'])) ? $_GET['uuid'] : 1;

require('../config/config.php');

$sql = "SELECT * FROM transacoes where uuid  = '$uuid'";

$result = mysqli_query($conexao,$sql);

foreach($result as $r) {
  $uuid = $r['uuid'];
  $idsale = $r['idsale'];
  $description = $r['description'];
  $status = $r['status'];
  $amount = $r['amount'];
  $totalpaid = $r['totalpaid'];
  $installmentsvalue = $r['installmentsvalue'];
  $totalinstallments = $r['totalinstallments'];
  $status_detail = $r['statusdetail'];
  $cardholder = $r['cardholder'];
  $expiration = $r['expiration'];
  $cardnumber = $r['cardnumber'];
  $holdername = $r['holdername'];
}

$conexao->close();

if($status == 'approved'){
  $status = 'Aprovado';
  $icon = 'success';
}else{
  $status = 'Recusado';
  $icon = 'error';
}

switch ($status_detail) {
  case 'cc_rejected_bad_filled_security_code':
    $status_detail = ', verifique o cvv digitado!';
    break;
  case 'cc_rejected_bad_filled_card_number':
    $status_detail = ', verifique o número do cartão digitado!';
    break;
  case 'cc_rejected_bad_filled_date':
    $status_detail = ', verifique a data de vencimento digitada!';
    break;
  case 'cc_rejected_bad_filled_other':
    $status_detail = ', verifique os dados digitados!';
    break;
  case 'cc_rejected_blacklist':
    $status_detail = ', Não pudemos processar seu pagamento.';
    break;
  case 'cc_rejected_card_disabled':
    $status_detail = ', Seu cartão não está ativo, verifique com sua operadora.';
    break;
  case 'cc_rejected_card_error':
    $status_detail = ', Não conseguimos processar seu pagamento.';
    break;
  case 'cc_rejected_duplicated_payment':
    $status_detail = ', Você já efetuou um pagamento com esse valor. Caso precise pagar novamente, utilize outro cartão ou outra forma de pagamento.';
    break;
  case 'cc_rejected_insufficient_amount':
    $status_detail = ', Saldo insuficiente';
    break;
  case 'cc_rejected_max_attempts':
    $status_detail = ', Você atingiu o limite de tentativas permitidas';
    break;
  case 'cc_rejected_call_for_authorize':
    $status_detail = ', Verifique os dados do cartão.';
    break;
  default:
  $status_detail = '';
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
    <link href="../assets/css/card.css" rel="stylesheet">
 
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
          <p><b>Status: </b><span><?php echo $status . $status_detail;?></span></p>
          <p><b>Valor: </b><span><?php echo 'R$ ' . number_format($amount, 2, ',', '.');?></span></p>

            <?php  
                if ($totalinstallments > 1)
                {
            ?>
                  <p><b>Parcelado: </b><span><?php echo 'R$ ' . number_format($totalpaid, 2, ',', '.') . ' em ' . $totalinstallments . ' parcela(s) de R$ ' . number_format($installmentsvalue, 2, ',', '.') ;?></span></p>
            <?php  
            }
            ?>
            
          <p><b>Cartão: </b><span><?php echo strtoupper($cardholder);?></span></p>
          <p><b>Titular do Cartão: </b><span><?php echo strtoupper($holdername);?></span></p>
          <p><b>Nº do Cartão: </b><span><?php echo $cardnumber;?></span></p>
          <p><b>Validade: </b><span><?php echo $expiration;?></span></p>
        </div>
        <br />
      </div>
    </form>
  </main>

</body>

</html>