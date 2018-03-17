<?php
# Importar Header
include_once 'includes/header.php';
if($_SESSION['tipo_empresa_id'] === 2){ session_destroy(); header("Location: index.php?mensagem=Usuário sem permissão, realize login novamente.&tipo=danger"); }
if(!isset($_GET['pag'])){
    $pag = 1;
} else {
    $pag = $_GET['pag'];
}

$db = new MysqliDb();
# Monta os join's
$db->join("projetos p", "p.id=c.projeto_id");
$db->join("empresas ec", "ec.id=c.empresa_close_id", "LEFT");
$db->orderBy('c.data_atendimento', 'desc');
$db->where('ec.id', $_SESSION['empresa_id']);
$db->where('c.status_id', 3);

# Prepara os campos para recuperar
$campos = "c.*, p.descricao as p_descricao, p.logo as p_logo";
# Recupera os dados
$chamados = $db->arraybuilder()->paginate("chamados c", $pag, $campos);
?>
<div class="container">
    <br>
    <div class="retorno"></div>
    <div class="row">
        <div class="col-md-offset-2 col-md-8 col-md-offset-2">
            <div class="panel panel-default" style="border-color: #000;">
                <div class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                    <h4><i class="fa fa-money"></i> Pagamentos a Faturar</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="text-center">
                            <td><strong>Número</strong></td>
                            <td><strong>Data</strong></td>
                            <td colspan="2"><strong>Projeto</strong></td>
                            <td><strong>Valor</strong></td>
                            <td><strong>Ações</strong></td>
                        </thead>
                        <tbody>
                            <?php
                            if(!empty($chamados[0])){
                                foreach($chamados as $chamado){
                            ?>
                                <tr>
                                    <td class="text-center" style="vertical-align: middle !important;"><?=$chamado['numero']?></td>
                                    <td class="text-center" style="vertical-align: middle !important;"><?=date('d/m/Y', strtotime($chamado['data_atendimento']))?></td>
                                    <td class="text-center" style="vertical-align: middle !important;"><?=$chamado['p_descricao']?></td>
                                    <td class="text-center" style="vertical-align: middle !important;"><?= !empty($chamado['p_logo'])? "<img src='logos-projetos/{$chamado['p_logo']}' style='width: 65px; height: 20px;'/>" : '-'?></td>
                                    <td class="text-center" style="vertical-align: middle !important;">R$ <?=number_format($chamado['valor'], 2, ',', '.')?></td>
                                    <td class="text-center" style="vertical-align: middle !important;"><a href="chamado_visualizar.php?id=<?= $chamado['id']; ?>" class="btn btn-sm btn-default" title="Visualizar"><i class="fa fa-search-plus"></i></a></td>
                                </tr>
                            <?php
                                }
                            } else {
                            ?>
                            <tr>
                                <td colspan="6" class="text-center"><span class="text-danger"><strong>Não existe chamados pendentes para faturamento.</strong></span></td>
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
                                        echo "<li class='active'><a href='chamados-a-faturar.php?pag={$i}'>{$i}</a></li>";
                                    } else {
                                        echo "<li><a href='chamados-a-faturar.php?pag={$i}'>{$i}</a></li>";
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