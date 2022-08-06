<?php
date_default_timezone_set('America/Sao_Paulo');
require('../config/config.php');

    
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

function limpaCPF($valor){
  $valor = trim($valor);
  $valor = str_replace(".", "", $valor);
  $valor = str_replace(",", "", $valor);
  $valor = str_replace("-", "", $valor);
  $valor = str_replace("/", "", $valor);
  return $valor;
 }

$cpf  = $_GET['doc'];

  if(isset($_POST)){

    if(isset($_POST['card'])){
      if($_POST['card']){

        $queryCard = "select 
        l.vendas_id as idVenda
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
        from lancamentos as l
        inner join clientes as c
        on l.clientes_id = c.idClientes
        where c.documento = '$cpf'
        and l.baixado = 0
        order by l.data_vencimento asc
        LIMIT 1";

        $result = mysqli_query($conexao,$queryCard);

        foreach($result as $r) {
          date_default_timezone_set('America/Sao_Paulo');
          $dataAtual = date('Y-m-d H:i:s');

          $idVenda = $r['idVenda'];
          $valor = $r['valor'];
          $nomeCliente = $r['nomeCliente'];
          $email = $r['email'];
          $cep = str_replace('-', '', $r['cep']);
          $rua = $r['rua'];
          $numero = $r['numero'];
          $bairro = $r['bairro'];
          $cidade = $r['cidade'];
          $uf = $r['estado'];
          $ref = string_between_two_string($r['descricao'], 'Ref: ', ' N');
          $descricao = $r['descricao'];
          //$descricao = 'Lista Iptv | Ref:'.$ref.' | Venda: '. $idVenda;
        }
        mysqli_close($conexao);

      }
    }
    if($idVenda){
      echo json_encode(
        array(
          'dados'=>  array(
          'idVenda'  => $idVenda,
          'cpf' => limpaCPF($cpf),
          'email' => $email,
          'valor' => $valor,
          'descricao' => $descricao,
          'ref' => $ref
          )
          ));
    } else{
      echo json_encode(
        array(
          'dados'=>  array(
          'erro'  => 'Não existe débito aberto para este CPF.'
          )
          ));
    }


  } 
?>