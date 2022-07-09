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
$tiraacento = array(
  'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj',''=>'Z', ''=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
  'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
  'Ï'=>'I', 'Ñ'=>'N', 'Ń'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
  'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
  'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
  'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ń'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
  'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
  'ă'=>'a', 'î'=>'i', 'â'=>'a', 'ș'=>'s', 'ț'=>'t', 'Ă'=>'A', 'Î'=>'I', 'Â'=>'A', 'Ș'=>'S', 'Ț'=>'T',
);

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
		from lancamentos as l
        left join pagamentos_pix as pp
        on pp.idVenda = l.vendas_id
        inner join clientes as c
        on l.clientes_id = c.idClientes
        where c.documento = '$cpf'
        and l.baixado = 0
        order by l.data_vencimento asc
        LIMIT 1";

        $result = mysqli_query($conexao,$query);

        foreach($result as $r) {
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
            $validadePix = $r['validadePix'];
            $ref = string_between_two_string($r['descricao'], 'Ref: ', ' N');
            $descricao = 'Lista Iptv | Ref:'.$ref.' | Venda: '. $idVenda;

          }
        mysqli_close($conexao);

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

        //echo json_encode($payment->point_of_interaction);
        

        echo json_encode(
        array(
          'pix'  => $payment->point_of_interaction,
          'dados'=>  array(
          'idVenda'  => $idVenda,
          'valor' => $valor,
          'ref' => $ref,
          'bairro' => $bairro
          )
        ));

      }else{
        echo json_encode(array(
          'status'  => 'error',
          'message' => 'pix required'
        ));
        exit;
      }

    }else{
      echo json_encode(array(
        'status'  => 'error',
        'message' => 'pix required'
      ));
      exit;
    }

  }else{
    echo json_encode(array(
      'status'  => 'error',
      'message' => 'post required'
    ));
    exit;
  }
