<?php
date_default_timezone_set('America/Sao_Paulo');
# Importar Configurações
require_once '../libs/MysqliDb.php';
$dados             = $_POST;
$extensoes_aceitas = 'pdf';
# Valida se arquivo existe
if(isset($_FILES) && $_FILES['comprovante']['size'] > 0){
    $extensao_comprovante = explode('.', $_FILES['comprovante']['name']);
    $extensao_comprovante = strtolower(end($extensao_comprovante));
    # Valida extensão do arquivo
    if($extensao_comprovante == $extensoes_aceitas){
        $db              = new MysqliDb();
        $db->where('id', $dados['empresa_id']);
        $empresa         = $db->getOne('empresas');
        $arq_comprovante = $empresa['nome_fantasia'].'-comprovante'.'.'.$extensao_comprovante;
        # Valida se foi feito upload corretamente
        if(move_uploaded_file($_FILES['comprovante']['tmp_name'], '../comprovantes/'.$arq_comprovante)){
            $db->where('status_id', 3);
            $db->where('empresa_close_id', $dados['empresa_id']);
            $db->where('data_atendimento', $dados['data_ini'], '>=');
            $db->where('data_atendimento', $dados['data_fim'], '<=');
            $chamados = $db->get("chamados", null, "id");
            # Valida se os dados do chamado e do update foram realizados
            if(!empty($chamados) && $db->update('chamados', array('status_id' => 7))){
                $pagamento = array(
                    'empresa_id'  => $dados['empresa_id'],
                    'comprovante' => $arq_comprovante,
                    'periodo_ini' => $dados['data_ini'],
                    'periodo_fim' => $dados['data_fim'],
                    'data'        => date('Y-m-d')
                );
                $pagamentoId = $db->insert('pagamentos', $pagamento);
                # Valida se foi inserido a informação no BD para inserir a relação com o chamado
                if($pagamentoId){
                    foreach($chamados as $chamado){
                        $chamado_pagamento = array(
                            'pagamento_id' => $pagamentoId,
                            'chamado_id' => $chamado['id']
                        );
                        $db->insert('pagamentos_chamados', $chamado_pagamento);
                    }
                    $retorno['message'] = "<div class='alert alert-success text-center'>Chamados atribuídos como pagos, atualizando a página!<br><img src='img/loading.gif' /></div>";
                    $retorno['success'] = true;
                } else {
                    $retorno['message'] = "<div class='alert alert-danger text-center'>Erro ao inserir relação de pagamento x chamado!<br><img src='img/loading.gif' /></div>";
                    $retorno['success'] = false;
                }
            } else {
                $retorno['message'] = "<div class='alert alert-danger text-center'>Erro ao inserir pagamento!<br><img src='img/loading.gif' /></div>";
                $retorno['success'] = false;
            }
        } else {
            $retorno['message'] = "<div class='alert alert-danger text-center'>Erro ao realizar upload do comprovante!<br><img src='img/loading.gif' /></div>";
            $retorno['success'] = false;
        }
    } else {
        $retorno['message'] = "<div class='alert alert-danger text-center'>Arquivo inválido, extensões aceitas: somente pdf!<br><img src='img/loading.gif' /></div>";
        $retorno['success'] = false;
    }
} else {
    $retorno['message'] = "<div class='alert alert-danger text-center'>Arquivo inválido!<br><img src='img/loading.gif' /></div>";
    $retorno['success'] = false;
}

echo json_encode($retorno);