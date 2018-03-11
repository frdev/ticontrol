<?php
# Importar Configurações
require_once '../libs/MysqliDb.php';

$status          = $_POST['tipo'] == 'ativar' ? 1 : 0;
$id              = $_POST['id'];
$retorno['tipo'] = $status;
# Query de acesso
$db    = new MysqliDb();
$data  = array('status' => $status);
$db->where('id', $id);
$projeto  = $db->getOne('projetos');
$texto = $status == 1? 'ativado' : 'desativado';
if($db->update('projetos', $data)){
    $retorno['message'] = "<div class='alert alert-success text-center'>Projeto {$projeto['descricao']} <strong>{$texto}</strong> com sucesso.<br><img src='img/loading.gif' /></div>";
    $retorno['success'] = true;
} else {
    $retorno['message'] = "<div class='alert alert-danger text-center'>Erro ao atualizar projeto {$projeto['descricao']}.</div>";
    $retorno['success'] = false;
}
echo json_encode($retorno);
?>