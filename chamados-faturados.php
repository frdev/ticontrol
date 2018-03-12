<?php
# Importar Header
include_once 'includes/header.php';
if($_SESSION['tipo_empresa_id'] != 1 && $_SESSION['nivel_acesso_id'] != 1){ session_destroy(); header("Location: index.php?mensagem=Usuário sem permissão, realize login novamente.&tipo=danger"); }

$db       = new MysqliDb();

if(!isset($_GET['pag'])){
    $pag = 1;
} else {
    $pag = $_GET['pag'];
}
$db->orderBy('p.data', 'desc');
$db->join('empresas e', 'e.id=p.empresa_id');
$db->join('pagamentos_chamados pc', 'pc.pagamento_id=p.id');
$db->join('chamados c', 'pc.chamado_id=c.id');
$db->where('e.id', $_SESSION['empresa_id']);
$pagamentos = $db->arraybuilder()->paginate('pagamentos p', $pag, 'p.*, e.nome_fantasia, SUM(c.valor) as valor');
?>
<div class="container">
    <br>
    <div class="retorno"></div>
    <div class="row">
        <div class="col-md-offset-2 col-md-8 col-md-offset-2">
            <div class="panel panel-default" style="border-color: #000;">
                <div class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                    <h4><i class="fa fa-money"></i> Pagamentos Recebidos</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="text-center">
                            <td><strong>Data</strong></td>
                            <td><strong>Período</strong></td>
                            <td><strong>Valor</strong></td>
                            <td><strong>Ações</strong></td>
                        </thead>
                        <tbody>
                            <?php
                            if(!empty($pagamentos[0])){
                                foreach($pagamentos as $pagamento){
                            ?>
                                <tr>
                                    <td class='text-center'><?=date('d/m/Y', strtotime($pagamento['data']))?></td>
                                    <td class='text-center'><?=date('d/m/Y', strtotime($pagamento['periodo_ini']))?> à <?=date('d/m/Y', strtotime($pagamento['periodo_fim']))?></td>
                                    <td class='text-center'><?=number_format($pagamento['valor'], 2, ',', '.')?></td>
                                    <td class='text-center'><a href="pagamento-visualizar.php?id=<?= $pagamento['id']; ?>" class="btn btn-sm btn-default" title="Visualizar"><i class="fa fa-search-plus"></i></a></td>
                                </tr>
                            <?php
                                }
                            } else {
                            ?>
                            <tr>
                                <td colspan="5" class="text-center"><span class="text-danger"><strong>Não existe chamados para o filtro aplicado.</strong></span></td>
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
                                for ($i=1; $i <= $db->totalPages; $i++) {
                                    if($i == $pag){
                                        echo "<li class='active'><a href='efetuar-pagamentos.php?pag={$i}'>{$i}</a></li>";
                                    } else {
                                        echo "<li><a href='efetuar-pagamentos.php?pag={$i}'>{$i}</a></li>";
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