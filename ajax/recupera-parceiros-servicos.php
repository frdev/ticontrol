<?php
# Importar Configurações
require_once '../libs/MysqliDb.php';
$db      = new MysqliDb();
$db->join('empresas_servicos es', 'es.empresa_id=e.id');
$db->where('es.servico_id', $_POST['servico_id']);
$db->where('e.tipo_empresa_id', 3);
$empresas = $db->get('empresas e', null, 'e.id, e.nome_fantasia');
echo json_encode($empresas);
?>