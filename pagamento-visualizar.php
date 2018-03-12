<?php
# Importar Header
include_once 'includes/header.php';
$db       = new MysqliDb();
if(isset($_GET['id'])){
    $pagamento_id = $_GET['id'];
}
if(!isset($_GET['pag'])){
    $pag = 1;
} else {
    $pag = $_GET['pag'];
}

# Header do pagamento
$db->join('empresas e', 'e.id=p.empresa_id');
$db->join('pagamentos_chamados pc', 'pc.pagamento_id=p.id');
$db->join('chamados c', 'pc.chamado_id=c.id');
$db->where('p.id', $pagamento_id);
$pagamento = $db->get('pagamentos p', null, 'p.data, SUM(c.valor) as valor, p.periodo_ini, p.periodo_fim');

# Contador dos pagtos
$db->where('p.id', $pagamento_id);
$db->orderBy('c.data_atendimento', 'asc');
$db->join('pagamentos_chamados pc', 'pc.chamado_id=c.id', 'LEFT');
$db->join('pagamentos p', 'p.id=pc.pagamento_id');
$count_chamados = $db->get('chamados c');

# Listagem dos chamados
$db->where('p.id', $pagamento_id);
$db->orderBy('c.data_atendimento', 'asc');
$db->join('pagamentos_chamados pc', 'pc.chamado_id=c.id', 'LEFT');
$db->join('pagamentos p', 'p.id=pc.pagamento_id');
if(isset($_GET['filtro'])){
    $filtro = $_GET['filtro'];
    $db->where('c.numero', '%'.$filtro.'%', 'like');
}
$chamados       = $db->arraybuilder()->paginate('chamados c', $pag, 'c.*');
?>
<div class="container">
    <br>
    <div class="row">
        <div class="col-md-offset-2 col-md-8 col-md-offset-2">
            <div class="panel panel-default" style="border-color: #000;">
                <div class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                    <h4><i class="fa fa-shopping-bag"></i> Chamados pagos - <?=date('d/m/Y', strtotime($pagamento[0]['data']))?> - Valor: R$<?=number_format($pagamento[0]['valor'], 2, ',', '.')?> - Total chamados - <?=count($count_chamados)?>
                        <br><br>
                        <i class="fa fa-calendar"></i> Período: <?=date('d/m/Y', strtotime($pagamento[0]['periodo_ini']))?> à <?=date('d/m/Y', strtotime($pagamento[0]['periodo_fim']))?> - Comprovante: <a href="comprovantes/<?=$pagamento[0]['comprovante']?>">[Download]</a>
                    </h4>
                </div>
                <div class="panel-body">
                    <form action="" method="GET">
                        <input type="hidden" name="id" value="<?=$pagamento_id?>" />
                        <label class="control-label col-sm-2 text-left" for="filtro" style="padding-top: 7px;"><i class='fa fa-filter'></i> Chamado:</label>
                        <div class="col-sm-4" style="margin-bottom: 10px;">
                            <input type="text" name="filtro" id="filtro" class="form-control" value='<?= isset($filtro) ? $filtro : ''; ?>'/>    
                        </div>
                        <div class="col-sm-2" style="margin-bottom: 10px;">
                            <button type="submit" class="btn btn-md btn-primary">Pesquisar</button>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr class="text-center">
                                <td><strong>Chamado</strong></td>
                                <td><strong>Atendimento</strong></td>
                                <td><strong>Valor</strong></td>
                                <td><strong>Ação</strong></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($chamados as $chamado){
                            ?>
                            <tr class="text-center">
                                <td><?=$chamado['numero']?></td>
                                <td><?=date('d/m/Y', strtotime($chamado['data_atendimento']))?></td>
                                <td><?=number_format($chamado['valor'], 2, ',', '.')?></td>
                                <td><a href="chamado_visualizar.php?id=<?= $chamado['id']; ?>" class="btn btn-sm btn-default" title="Visualizar"><i class="fa fa-search-plus"></i></a></td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    <div class="row text-center">
                        <ul class="pagination">
                            <?php
                                if(!empty($_GET['tipo_filtro']) && $_GET['filtro']){
                                   $filtro_get = '&tipo_filtro='.$_GET['tipo_filtro'].'&filtro='.$_GET['filtro']; 
                                } else {
                                    $filtro_get = '';
                                }
                                for ($i=1; $i <= $db->totalPages; $i++) {
                                    if($i == $pag){
                                        echo "<li class='active'><a href='pagamento-visualizar.php?id={$pagamento_id}&pag={$i}{$filtro_get}'>{$i}</a></li>";
                                    } else {
                                        echo "<li><a href='pagamento-visualizar.php?id={$pagamento_id}&pag={$i}{$filtro_get}'>{$i}</a></li>";
                                    }
                                }
                            ?>
                        </ul>
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