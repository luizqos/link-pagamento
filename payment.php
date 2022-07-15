<?php
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
          'ref' => $ref
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
