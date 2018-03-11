<?php
# Importar Header
include_once 'includes/header.php';

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
$db->join("pagamentos_chamados pag", "pag.chamado_id=c.id");
$campos  = "c.*, p.descricao as p_descricao, p.rat, eo.nome_fantasia as eo_nome, ec.nome_fantasia as ec_nome, sv.descricao as sv_descricao, pr.descricao as pr_descricao, s.descricao as s_descricao, uf.abbreviation as uf, cidade.name as cidade, pc.descricao as pc_descricao, pag.pagamento_id";
# Recupera dados do chamado em caso de edição
$db->where('c.id', $_GET['id']);
$chamado = $db->getOne('chamados c', $campos);
?>

<div class="container">
    <div class="row">
        <div class="col-md-offset-2 col-md-8 col-md-offset-2">
            <div class="panel panel-default" style="border-color: #000;">
                <div class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                    <h4><i class="fa fa-search-plus"></i> <strong>Informações do Chamado - <?= $chamado['numero']?></strong></h4>
                </div>
                <div class="panel-body">
                    <div class="col-md-4">
                        <p class="text-justify"><strong>Chamado: </strong> <?=$chamado['numero'];?></p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-justify"><strong>Serviço: </strong> <?=$chamado['sv_descricao'];?></p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-justify"><strong>Prioridade: </strong> <?=$chamado['pr_descricao'];?></p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-justify"><strong>Projeto: </strong> <?=$chamado['p_descricao'];?></p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-justify">
                            <?php
                                if($_SESSION['tipo_empresa_id'] != 3){
                            ?>
                                <strong>Parceiro: </strong> <?=$chamado['eo_nome'];?>
                            <?php
                                }
                            ?>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-justify">
                            <?php
                                if($_SESSION['tipo_empresa_id'] != 2){
                            ?>
                                <strong>Técnico: </strong> <?=$chamado['ec_nome'];?>
                            <?php
                                }
                            ?>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-justify"><strong>Data: </strong> <?=date('d/m/Y', strtotime($chamado['data_atendimento']))?></p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-justify"><strong>Período: </strong> <?=$chamado['pc_descricao'];?></p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-justify">
                            <?php
                                if($_SESSION['tipo_empresa_id'] == 1){
                            ?>
                                <strong>Valor: </strong> R$<?=number_format($chamado['valor'], 2, ',', '.');?>
                            <?php
                                }
                            ?>
                        </p>
                    </div>
                    <div class="col-md-8">
                        <p class="text-justify"><strong>Cliente: </strong> <?=$chamado['nome_cliente'];?></p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-justify"><strong>Status: </strong> <?=$chamado['s_descricao'];?></p>
                    </div>
                    <div class="col-md-12">
                        <p class="text-justify"><strong>Logradouro: </strong> <?=$chamado['logradouro'];?></p>
                    </div>
                    <div class="col-md-8">
                        <p class="text-justify"><strong>Estado: </strong> <?=$chamado['uf'];?></p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-justify"><strong>Cidade: </strong> <?=$chamado['cidade'];?></p>
                    </div>
                    <div class="col-md-12">
                        <p class="text-justify"><strong>Info At.: </strong> <?=$chamado['obs_at'];?></p>
                    </div>
                    <div class="col-md-12">
                        <p class="text-justify"><strong>RAT - Modelo: </strong> <a href="rats/<?=$chamado['rat'];?>" download>[Download]</a></p>
                    </div>
                    <?php
                        if($chamado['status_id'] == 3 || $chamado['status_id'] == 7){
                    ?>
                    <div class="col-md-12">
                        <p class="text-justify"><strong>Horário realizado de atendimento: </strong> <?=$chamado['inicio_at'];?> às <?=$chamado['fim_at'];?></p>
                    </div>
                    <div class="col-md-12">
                        <p class="text-justify"><strong>Fechamento: </strong> <?=$chamado['obs_close'];?></p>
                    </div>
                    <div class="col-md-12">
                        <p class="text-justify"><strong>RAT - Fechamento: </strong> <a href="fechamentos/<?=$chamado['rat_fechamento'];?>" download>[Download]</a> <a href="fechamentos/<?=$chamado['foto1'];?>" download>[Foto 1]</a> <a href="fechamentos/<?=$chamado['foto2'];?>" download>[Foto 2]</a></p>
                    </div>
                    <?php
                        }
                    ?>
                    <?php
                        if($chamado['status_id'] == 7){
                    ?>
                    <div class="col-md-12">
                        <p class="text-justify"><strong>Informativo de pagamento Pagamento: </strong> <a href="pagamento-visualizar.php?id=<?=$chamado['pagamento_id'];?>">[Link]</a></p>   
                    </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="panel-footer">
                    <div class="text-right">
                        <a class="btn btn-md btn-default pdf" href="ajax/imprimir-chamado.php?id=<?=$chamado['id'];?>" target="_blank" title="Gerar PDF"><i class="fa fa-file-pdf-o"></i></a>
                        <a href="chamados.php" class="btn btn-md btn-default" title="Voltar"><i class="fa fa-chevron-left"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
# Importar Rodapé
include_once 'includes/footer.php';
?>