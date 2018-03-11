<?php
session_start();
# Importar Configurações
require_once '../libs/MysqliDb.php';
require      '../vendor/autoload.php';
$db          = new MysqliDb();
# Monta os join's
$db->join("projetos p", "p.id=c.projeto_id");
$db->join("empresas eo", "eo.id=c.empresa_open_id", "LEFT");
$db->join("empresas ec", "ec.id=c.empresa_close_id", "LEFT");
$db->join("servicos sv", "sv.id=c.servico_id");
$db->join("prioridades_chamados pr", "pr.id=c.prioridade_id");
$db->join("periodos_chamados pe", "pe.id=c.periodo_id");
$db->join("status_chamados s", "s.id=c.status_id");
$db->join("state uf", "uf.id=c.state_id");
$db->join("city cidade", "cidade.id=c.city_id");
$db->join("periodos_chamados pc", "pc.id=c.periodo_id");
$campos  = "c.*, p.descricao as p_descricao, p.rat, eo.nome_fantasia as eo_nome, ec.nome_fantasia as ec_nome, sv.descricao as sv_descricao, pr.descricao as pr_descricao, s.descricao as s_descricao, uf.abbreviation as uf, cidade.name as cidade, pc.descricao as pc_descricao";
# Recupera dados do chamado em caso de edição
$db->where('c.id', $_GET['id']);
$chamado = $db->getOne('chamados c', $campos);
$data_at = date('d/m/Y', strtotime($chamado['data_atendimento']));
$valor = number_format($chamado['valor'], 2, ',', '.');

$body = "<span><img style='float:right; height: 70px;' src='../img/infolinn-logo.png' /></span>
         <br>
         <h4><strong>Informações do Chamado - {$chamado['numero']}</strong></h4>
         <hr>
            <p style='text-align: justify;'><strong>Chamado: </strong> {$chamado['numero']}   -   <strong>Serviço: </strong> {$chamado['sv_descricao']}   -   <strong>Prioridade: </strong> {$chamado['pr_descricao']}   -   <strong>Projeto: </strong> {$chamado['p_descricao']}</p>
            ";     
            $body .= "
            <p style='text-align: justify;'><strong>Status: </strong> {$chamado['s_descricao']}</p>
            <p style='text-align: justify;'><strong>Cliente: </strong> {$chamado['nome_cliente']}</p>    
            <p style='text-align: justify;'><strong>Logradouro: </strong> {$chamado['logradouro']}, {$chamado['cidade']}, {$chamado['uf']}</p>
            <p style='text-align: justify;'><strong>Informações Atendimento: </strong> {$chamado['obs_at']}</p>";
            if($chamado['status_id'] == 3 || $chamado['status_id'] == 7){
                $body .= "
                <p style='text-align: justify;'><strong>Horário realizado de atendimento: </strong> {$chamado['inicio_at']} às {$chamado['fim_at']}</p>
                <p style='text-align: justify;'><strong>Fechamento: </strong> {$chamado['obs_close']}</p>
                ";
            }
$mpdf = new \Mpdf\Mpdf();
// Write some HTML code:
$mpdf->WriteHTML($body, 2);

// Output a PDF file directly to the browser
$mpdf->Output();
exit();
?>