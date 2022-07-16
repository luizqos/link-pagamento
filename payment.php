<?php
// header("Content-type: application/json; charset=iso-8859-1");
// header('Content-type: text/html; charset=utf-8');

include_once 'conexao.php';

    
function string_between_two_string($str, $starting_word, $ending_word)
{
    $subtring_start = strpos($str, $starting_word);
    //Adding the strating index of the strating word to 
    //its length would give its ending index
    $subtring_start += strlen($starting_word);  
    //Length of our required sub string
    $size = strpos($str, $ending_word, $subtring_start) - $subtring_start;  
    // Return the substring from the index substring_start of length size 
    return substr($str, $subtring_start, $size);  
}

$cpf  = $_GET['doc'];

  if(isset($_POST)){

    if(isset($_POST['pix'])){

      if($_POST['pix']){

        $query = "select  pp.id 
        , if(pp.idVenda is null, l.vendas_id, pp.idVenda) as idVenda
        , pp.codePix
        , pp.qrCode
        , pp.dataCreated
        , l.valor
        , l.clientes_id
        , l.descricao
        , l.usuarios_id
        , c.nomeCliente
        , c.email
        , c.cep
        , c.rua
        , c.numero
        , c.bairro
        , c.cidade
        , c.estado
        , DATE_ADD(pp.dataCreated, INTERVAL 1 DAY) as validadePix
        , IF(DATE_ADD(pp.dataCreated, INTERVAL 1 DAY) > NOW(), 'S', 'N') AS pixValido
		from lancamentos as l
        left join pagamentos_pix as pp
        on pp.idVenda = l.vendas_id
        inner join clientes as c
        on l.clientes_id = c.idClientes
        where c.documento = '$cpf'
        and l.baixado = 0
        order by l.data_vencimento asc, pp.dataCreated desc
        LIMIT 1";

        $result = mysqli_query($conexao,$query);

        foreach($result as $r) {
            date_default_timezone_set('America/Fortaleza');
            $dataAtual = date('Y-m-d H:i:s');

            $id = $r['id'];
            $idVenda = $r['idVenda'];
            $codePix = $r['codePix'];
            $qrCode = $r['qrCode'];
            $dataCreated = $r['dataCreated'];
            $valor = $r['valor'];
            $nomeCliente = $r['nomeCliente'];
            $email = $r['email'];
            $cep = str_replace('-', '', $r['cep']);
            $rua = $r['rua'];
            $numero = $r['numero'];
            $bairro = $r['bairro'];
            $cidade = $r['cidade'];
            $uf = $r['estado'];
            $validadePix = date('Y-m-d H:i:s', strtotime($r['validadePix'])); 
            $ref = string_between_two_string($r['descricao'], 'Ref: ', ' N');
            $descricao = $r['descricao'];
            //$descricao = 'Lista Iptv | Ref:'.$ref.' | Venda: '. $idVenda;
            if($r['pixValido'] == 'N'){
              $gravaPix = 'S';
            }else{
              $gravaPix = 'N';
            }
          }
        mysqli_close($conexao);
        
        if($idVenda){
            if($gravaPix == 'S'){

              require_once 'mercadopago/lib/mercadopago/vendor/autoload.php';

              MercadoPago\SDK::setAccessToken($access_token);
      
      
              $payment = new MercadoPago\Payment();
              $payment->description = $descricao;
              $payment->transaction_amount = (double)$valor;
              $payment->payment_method_id = "pix";
      
              $payment->notification_url  = 'https://seusite.com/notification.php';
              $payment->external_reference = $idVenda;
      
                $payment->payer = array(
                  "email" => $email,
                  "first_name" => $nomeCliente,
                  "address"=>  array(
                    "zip_code" => $cep,
                    "street_name" => $rua,
                    "street_number" => $numero,
                    "neighborhood" => $bairro,
                    "city" => $cidade,
                    "federal_unit" => $uf
                  )
                );
      

              $payment->save();

              $qr_code = $payment->point_of_interaction->transaction_data->qr_code;
              $qr_code_base64 = $payment->point_of_interaction->transaction_data->qr_code_base64;

              $conexao = mysqli_connect($servidor, $usuario, $senha, $dbname);
              
              $sql = "INSERT INTO `pagamentos_pix` (`idVenda`, `codePix`, `qrCode`) VALUES ($idVenda, '$qr_code', '$qr_code_base64')";

              mysqli_query($conexao,$sql);

              mysqli_close($conexao);

              echo json_encode(
                array(
                  'pix'  => $payment->point_of_interaction,
                  'dados'=>  array(
                  'idVenda'  => $idVenda,
                  'valor' => $valor,
                  'ref' => $ref,
                  'validoAt' => $validadePix,
                  'gerapix' => $gravaPix
                  )
                ));
        
              }else {            
              echo json_encode(
                array(
                  'dados'=>  array(
                  'qr_code_base64' => $qrCode,
                  'qr_code' => $codePix,
                  'idVenda'  => $idVenda,
                  'valor' => $valor,
                  'validoAt' => $validadePix,
                  'ref' => $ref,
                  'gerapix' => $gravaPix
                  )
                  ));
              }
          }else{
            echo json_encode(array(
              'status'  => 'info',
              'message' => 'Não encontrei débitos para seu CPF'
            ));
          }
      
            }
      
          }
      } 
?>