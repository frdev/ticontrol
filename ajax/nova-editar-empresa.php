<?php
date_default_timezone_set('America/Sao_Paulo');
# Importar Configurações
require_once '../libs/MysqliDb.php';
# Recuperando os dados via POST
$dados = $_POST;
$db    = new MysqliDb();
$db->where('cnpj', $dados['cnpj']);
if(!empty($dados['id'])){
    $db->where('id', $dados['id'], '!=');
}
$existEmpresa = $db->getOne('empresas');
if(empty($existEmpresa)){ # Caso não exista, segue para atualização/cadastro
    if($dados['tipo_empresa_id'] == 3){
        $empresa = array(
            'tipo_empresa_id' => $dados['tipo_empresa_id'],
            'nome_fantasia'   => $dados['nome_fantasia'],
            'razao_social'    => $dados['razao_social'],
            'cnpj'            => $dados['cnpj'],
            'ie'              => $dados['ie'],
            'banco'           => $dados['banco'],
            'tipo_conta'      => $dados['tipo_conta'],
            'agencia'         => $dados['agencia'],
            'conta'           => $dados['conta'],
            'obs_adicional'   => $dados['obs_adicional']
        );
    } else {
        $empresa = array(
            'tipo_empresa_id' => $dados['tipo_empresa_id'],
            'nome_fantasia'   => $dados['nome_fantasia'],
            'razao_social'    => $dados['razao_social'],
            'cnpj'            => $dados['cnpj'],
            'ie'              => $dados['ie']
        );
    }
    
    if(!empty($dados['id'])){
        $db->where('id', $dados['id']);
        if($db->update('empresas', $empresa)){
            $retorno['message'] = "<div class='alert alert-success text-center'>Empresa atualizada com sucesso.<br><img src='img/loading.gif' /></div><br>";
            $retorno['success'] = true;
        } else {
            $retorno['message'] = "<br><div class='alert alert-danger text-center'>Erro ao atualizar empresa.</div><br>";
            $retorno['success'] = false;
        }
    } else {
        $empresaId          = $db->insert('empresas', $empresa);
        $dados['id']        = $empresaId;
        $retorno['message'] = "<div class='alert alert-success text-center'>Empresa criada com sucesso.<br><img src='img/loading.gif' /></div><br>";
        $retorno['success'] = true;
    }
    if(!empty($dados['id'])){
        $db->where('empresa_id', $dados['id'])->delete('empresas_servicos');
    }
    foreach($dados['servicos'] as $servico){
        $db->insert('empresas_servicos', array('empresa_id' => $dados['id'], 'servico_id' => $servico));
    }
} else { # Caso exista, informa mensagem de error
    # Retorno caso o usuário já exista na base de dados
    $retorno['message'] = "<br><div class='alert alert-danger text-center'>CNPJ já existente, por gentileza, altere para outro.</div><br>";
    $retorno['success'] = false;
}

echo json_encode($retorno);

?>