<?php
# Importar Configurações
require_once '../libs/MysqliDb.php';

# Query de acesso
$db = new MysqliDb();
$db->join("empresas e", "e.id=u.empresa_id");
$db->where("u.login", $_POST['usuario']);
$db->where("u.senha", md5($_POST['senha']));
$db->where("u.status", 1);
$user = $db->getOne("usuarios u", "u.*, e.tipo_empresa_id");

# Valia usuário
if(!empty($user)){
    # Inicia e instancia Session
    session_start();
    $_SESSION['id']              = $user['id'];
    $_SESSION['nivel_acesso_id'] = $user['nivel_acesso_id'];
    $_SESSION['empresa_id']      = $user['empresa_id'];
    $_SESSION['nome']            = $user['nome'];
    $_SESSION['login']           = $user['login'];
    $_SESSION['senha']           = $user['senha'];
    $_SESSION['tipo_empresa_id'] = $user['tipo_empresa_id'];
    $retorno['message']          = "<div class='alert alert-success'>Login realizado, aguarde o redirecionamento!<br> <img src='img/loading.gif' /></div>";
    $retorno['success']          = true;
} else {
    $retorno['message'] = "<div class='alert alert-danger'>Usuário ou senha inválidos!</div>";
    $retorno['success'] = false;
}

echo json_encode($retorno);

?>