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
$user  = $db->getOne('usuarios');
$texto = $status == 1? 'ativado' : 'desativado';
if($db->update('usuarios', $data)){
    $retorno['message'] = "<div class='alert alert-success text-center'>Usuário {$user['nome']} <strong>{$texto}</strong> com sucesso.<br><img src='img/loading.gif' /></div>";
    $retorno['success'] = true;
} else {
    $retorno['message'] = "<div class='alert alert-danger text-center'>Erro ao atualziado usuário {$user['nome']}.</div>";
    $retorno['success'] = false;
}
echo json_encode($retorno);
?>