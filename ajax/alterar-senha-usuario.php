<?php
# Importar Configurações
require_once '../libs/MysqliDb.php';

$dados = $_POST;

if($dados['nova'] == $dados['confirmar']){
    $db = new MysqliDb();
    $db->where('id', $dados['id']);
    $db->where('senha', md5($dados['senha']));
    if($db->update('usuarios', array('senha' => md5($dados['nova'])))){
        $retorno['message'] = "<br><div class='alert alert-success text-center'`>Senha alterada com sucesso.<br><img src='img/loading.gif' /></div><br>";
        $retorno['success'] = true;
    } else {
        $retorno['message'] = "<br><div class='alert alert-success text-center'`>Senha atual inválida.</div><br>";
        $retorno['success'] = false;
    }
} else {
    $retorno['message'] = "<br><div class='alert alert-success text-center'`>Os campos da nova senha devem ser idênticos, por gentileza, corrija.</div><br>";
    $retorno['success'] = false;
}
echo json_encode($retorno);
?>