<?php
# Importar Configurações
require_once '../libs/MysqliDb.php';

$status          = $_POST['tipo'] == 'ativar' ? 1 : 0;
$id              = $_POST['id'];
$retorno['tipo'] = $status;
$texto           = $status == 1? 'ativado' : 'desativado';
# Query de acesso
$db      = new MysqliDb();
$db->where('id', $id);
$empresa = $db->getOne('empresas');
$db->where('id', $id);
if($db->update('empresas', array('status' => $status))){
    if($status == 0){
        $db->where('empresa_id', $id);
        if($db->update('usuarios', array('status' => 0))){
            $retorno['message'] = "<div class='alert alert-success text-center'>Empresa {$empresa['nome_fantasia']} <strong>{$texto}</strong> com sucesso.<br><img src='img/loading.gif' /></div>";
            $retorno['success'] = true;
        } else {
            $retorno['message'] = "<div class='alert alert-danger text-center'>Erro ao atualizar Empresa {$empresa['nome_fantasia']}.</div>";
            $retorno['success'] = false;
        }
    }
    $retorno['message'] = "<div class='alert alert-success text-center'>Empresa {$empresa['nome_fantasia']} <strong>{$texto}</strong> com sucesso.<br><img src='img/loading.gif' /></div>";
    $retorno['success'] = true;
} else {
    $retorno['message'] = "<div class='alert alert-danger text-center'>Erro ao atualizar Empresa {$empresa['nome_fantasia']}.</div>";
    $retorno['success'] = false;
}
echo json_encode($retorno);
?>