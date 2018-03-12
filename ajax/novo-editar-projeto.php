<?php
date_default_timezone_set('America/Sao_Paulo');
# Importar Configurações
require_once '../libs/MysqliDb.php';
# Recuperando os dados via POST
$dados     = $_POST;
$db        = new MysqliDb();
$db->where('descricao', $dados['descricao']);
if(!empty($dados['id'])){
    $db->where('id', $dados['id'], '!=');
}
$existProjeto = $db->getOne('projetos');
if(empty($existProjeto)){ # Caso não exista, segue para atualização/cadastro
    if(isset($_FILES) && $_FILES['rat']['size'] > 0){
        $extensoes_aceitas = 'pdf';
        $extensao_rat      = explode('.', $_FILES['rat']['name']);
        $extensao_rat      = strtolower(end($extensao_rat));
        $arq_rat           = $dados['descricao'].'-rat.'.$extensao_rat;
        if($extensoes_aceitas == $extensao_rat){
            if(move_uploaded_file($_FILES['rat']['tmp_name'], '../rats/'.$arq_rat)){
                $extensoes_aceitas_logo = array('png', 'jpeg', 'jpg');
                $extensao_logo          = explode('.', $_FILES['logo']['name']);
                $extensao_logo          = strtolower(end($extensao_logo));
                $arq_logo               = $dados['descricao'].'-logo'.$extensao_logo;
                move_uploaded_file($_FILES['logo']['tmp_name'], '../logos-projetos/'.$arq_logo);
                $projeto = array(
                    'descricao' => strtoupper($dados['descricao']),
                    'rat'       => $arq_rat,
                    'logo'      => $arq_logo
                );
                if(!empty($dados['id'])){
                    $db->where('id', $dados['id']);
                    if($db->update('projetos', $projeto)){
                        $retorno['message'] = "<br><div class='alert alert-success text-center'`>Projeto alterado com sucesso.<br><img src='img/loading.gif' /></div><br>";
                        $retorno['success'] = true;
                    } else {
                        $retorno['message'] = "<br><div class='alert alert-danger text-center'>Erro ao atualizar projeto.</div><br>";
                        $retorno['success'] = false;
                    }
                } else {
                    # Realiza o INSERT
                    $projetoId = $db->insert('projetos', $projeto);
                    if($projetoId){
                        $retorno['message'] = "<br><div class='alert alert-success text-center'>Projeto inserido com sucesso.<br><img src='img/loading.gif' /></div><br>";
                        $retorno['success'] = true;
                    } else {
                        $retorno['message'] = "<br><div class='alert alert-danger text-center'>Erro ao inserir projeto.</div><br>";
                        $retorno['success'] = false;
                    }
                }
            } else {
                $retorno['message'] = "<br><div class='alert alert-danger text-center'>Erro ao fazer upload do arquivo.</div><br>";
                $retorno['success'] = false; 
            }
        } else {
            $retorno['message'] = "<br><div class='alert alert-danger text-center'>Somente arquivos PDF são aceitos.</div><br>";
            $retorno['success'] = false; 
        }
    } else {
        $retorno['message'] = "<br><div class='alert alert-danger text-center'>Arquivo inválido.</div><br>";
        $retorno['success'] = false;
    }
} else { # Caso exista, informa mensagem de error
    # Retorno caso o usuário já exista na base de dados
    $retorno['message'] = "<br><div class='alert alert-danger text-center'>Projeto já existente, por gentileza, altere para outro.</div><br>";
    $retorno['success'] = false;
}

echo json_encode($retorno);

?>