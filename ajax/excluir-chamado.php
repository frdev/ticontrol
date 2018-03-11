<?php
# Importar Configurações
require_once '../libs/MysqliDb.php';
$db    = new MysqliDb();
$dados = $_POST;
$db->where('id', $dados['id']);
if($db->delete('chamados')){
    $retorno['message'] = "<div class='alert alert-success text-center'>Chamado excluído com sucesso.<br><img src='img/loading.gif' /></div>";
    $retorno['success'] = true;
} else {
    $retorno['message'] = "<div class='alert alert-success text-center'>Erro ao excluir chamado<br><img src='img/loading.gif' /></div>";
    $retorno['success'] = false;
}
echo json_encode($retorno);
?>