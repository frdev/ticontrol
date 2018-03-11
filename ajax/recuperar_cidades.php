<?php
# Importar Configurações
require_once '../libs/MysqliDb.php';
$db      = new MysqliDb();
$db->where('state_id', $_POST['state_id']);
$cidades = $db->get('city');
echo json_encode($cidades);
?>