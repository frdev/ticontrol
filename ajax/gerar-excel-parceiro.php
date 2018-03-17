<?php
session_start();
# Importar Configurações
require_once '../libs/MysqliDb.php';
$dados = $_POST;
$db = new MysqliDb();
# Recupera dados da Empresa pra montar nome do arquivo
if(!empty($dados['rel_empresa'])){
  $db->where('id', $dados['rel_empresa']);
} else {
  $db->where('id', $_SESSION['empresa_id']);
}
$empresa = $db->getOne('empresas');

# Converte datas para modelo br
$data_ini = date('d/m/Y', strtotime($dados['rel_data_ini']));
$data_fim = date('d/m/Y', strtotime($dados['rel_data_fim']));
# Monta nome do arquivo
$arquivo = 'Chamados -'.$empresa['nome_fantasia'].' - '.$data_ini.' a '.$data_fim.'.xls';

# Recupera dados do chamado de acordo com o periodo e empresa
# Monta os join's
if(!empty($dados['rel_empresa'])){
  $db->where('c.empresa_open_id', $dados['rel_empresa']);
} else {
  $db->where('c.empresa_open_id', $_SESSION['empresa_id']);
}
$db->where('c.data_atendimento', $dados['rel_data_ini'], '>=');
$db->where('c.data_atendimento', $dados['rel_data_fim'], '<=');
$db->where('c.status_id', 1, '!=');
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
$db->orderBy("c.data_atendimento", "asc");
# Prepara os campos para recuperar
$campos = "c.*, p.descricao as p_descricao, eo.nome_fantasia as eo_nome, ec.nome_fantasia as ec_nome, sv.descricao as sv_descricao, pr.descricao as pr_descricao, s.descricao as s_descricao, uf.abbreviation as uf, cidade.name as cidade, pc.descricao as pc_descricao";
# Recupera os dados
$chamados = $db->get("chamados c", null, $campos);

# Monta o Relatório
$html         = "";
$html        .= "<table border='1'>";
$html        .= "<tr>";
$html        .= "<td><strong>Projeto</strong></td>";
$html        .= "<td><strong>Chamado</strong></td>";
$html        .= "<td><strong>Status</strong></td>";
$html        .= "<td><strong>Cidade</strong></td>";
$html        .= "<td><strong>Data</strong></td>";
$html        .= "<td><strong>Hora ".utf8_decode("Início")."</strong></td>";
$html        .= "<td><strong>Hora ".utf8_decode("Término")."</strong></td>";
$html        .= "<td><strong>RAT</strong></td>";
$html        .= "<td><strong>".utf8_decode("Descrição")."</strong></td>";
$html        .= "<td><strong>Valor Chamado</strong></td>";
if($_SESSION['tipo_empresa_id'] == 1){
  $html        .= "<td><strong>Valor Pago</strong></td>";
  $html        .= "<td><strong>Lucro</strong></td>";
}
$html        .= "</tr>";
foreach($chamados as $chamado){
   $html .= "<tr>";
   $html .= "<td>" . utf8_decode($chamado['p_descricao']) . "</td>";
   $html .= "<td>" . $chamado['numero'] . "</td>";
   $html .= "<td>" . $chamado['s_descricao'] . "</td>"; 
   $html .= "<td>" . $chamado['cidade'] . "</td>";
   $html .= "<td>" . date('d/m/Y', strtotime($chamado['data_atendimento'])) . "</td>";
   $html .= "<td>" . $chamado['inicio_at'] . "</td>"; 
   $html .= "<td>" . $chamado['fim_at'] . "</td>";
   if(!empty($chamado['rat_fechamento'])){
    $html .= "<td>SIM</td>";
   } else{
    $html .= "<td>NAO</td>";
   }
   $html        .= "<td>" . utf8_decode($chamado['obs_close']) . "</td>";
   $html        .= "<td>" . $chamado['valor_recebido'] . "</td>";
   if($_SESSION['tipo_empresa_id'] == 1){
    $html        .= "<td>" . $chamado['valor'] . "</td>";
    $html        .= "<td>" . ($chamado['valor_recebido']-$chamado['valor']) . "</td>";
   }
   $html        .= "</tr>";
}
$html .= "</table>";
// Configurações header para forçar o download
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel; charset=utf-8");
header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
header ("Content-Description: PHP Generated Data" );
// Envia o conteúdo do arquivo
echo $html;
exit();

?>