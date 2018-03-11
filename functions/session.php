<?php

function validaUser($session){
    $db = new MysqliDb();
    $db->where("login", $_SESSION['login']);
    $db->where("senha", $_SESSION['senha']);
    $user = $db->getOne("usuarios");
    if(empty($user) || empty($_SESSION['login']) || empty($_SESSION['senha']) || !isset($_SESSION)){
        logout();
    }
}

function validaAdmin($session){
    if($_SESSION['nivel_acesso_id'] != 1){ # VALIDA SE TEM PERMISSÃO DE ADM
        logoutNoPerm();
    }
}

function validaEmpresaAdminOrTecnico($session){
    if($_SESSION['tipo_empresa_id'] == 2){
        logoutNoPerm();   
    }
}

function logout(){
    session_destroy();
    header("Location: index.php?mensagem=Usuário deslogado com sucesso&tipo=success");
}

function logoutNoPerm(){
    session_destroy();
    header("Location: index.php?mensagem=Usuário sem permissão, logue novamente&tipo=danger");
}

?>