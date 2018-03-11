<?php
# Importar Header
include_once 'includes/header.php';
if($_SESSION['tipo_empresa_id'] != 1 && $_SESSION['nivel_acesso_id'] != 1){ session_destroy(); header("Location: index.php?mensagem=Usuário sem permissão, realize login novamente.&tipo=danger"); }

if(!isset($_GET['pag'])){
    $pag = 1;
} else {
    $pag = $_GET['pag'];
}
$db = new MysqliDb();
$db->orderBy('e.id', 'desc');
$db->join("tipos_empresas te", "te.id=e.tipo_empresa_id");
if(!empty($_GET['tipo_filtro']) && (!empty($_GET['filtro']))){
    $filtro      = $_GET['filtro'];
    $tipo_filtro = $_GET['tipo_filtro'];
    switch($_GET['tipo_filtro']){
        case 'nome_fantasia':
            $db->where('e.nome_fantasia', "%{$filtro}%", 'like');
            break;
        case 'tipo_empresa':
            $db->where('te.descricao', "%{$filtro}%", 'like');
            break;
    }
}
$empresas = $db->arraybuilder()->paginate("empresas e", $pag, "e.*, te.descricao");
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <a href="empresa_nova.php" class="btn btn-lg btn-primary" title="Nova Empresa"><strong><i class="fa fa-plus"></i></strong></a>
            </div>
        </div>
    </div>
    <br>
    <div class="retorno">
        <?php
            if(isset($_GET['message'])){
                echo $_GET['message'];
            }
        ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default" style="border-color: #000;">
                <div class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                    <h4><i class="fa fa-building-o"></i> Gerencimento de Empresas</h4>
                </div>
                <div class="panel-body">
                    <form method="GET" action="">
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="control-label col-md-1 text-left" for="tipo_filtro" style="padding-top: 7px;"><i class='fa fa-filter'></i> Filtro:</label>
                            <div class="col-md-3" style="margin-bottom: 10px;">
                                <select class="selectpicker" name="tipo_filtro" id="tipo_filtro">
                                    <option value="">Selecione filtro</option>
                                    <option value="nome_fantasia" <?= isset($tipo_filtro) && $tipo_filtro == 'nome_fantasia' ? 'selected' : ''; ?>>Nome Fantasia</option>
                                    <option value="tipo_empresa" <?= isset($tipo_filtro) && $tipo_filtro == 'tipo_empresa' ? 'selected' : ''; ?>>Tipo Empresa</option>
                                </select>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px;">
                                <input type="text" name="filtro" id="filtro" class="form-control" value='<?= isset($filtro) ? $filtro : ''; ?>' />
                            </div>
                            <div class="col-md-5 text-right" style="margin-bottom: 10px;">
                                <button type="submit" class="btn btn-md btn-primary">Pesquisar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="text-center">
                            <tr>
                                <td><strong>Nome</strong></td>
                                <td><strong>CNPJ</strong></td>
                                <td><strong>Tipo</strong></td>
                                <td><strong>Status</strong></td>
                                <td><strong>Ações</strong></td>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php
                            foreach($empresas as $empresa){
                            ?>
                                <tr>
                                    <td style="vertical-align: middle !important;"><?= $empresa['nome_fantasia'] ?></td>
                                    <td style="vertical-align: middle !important;"><?= $empresa['cnpj'] ?>
                                    <td style="vertical-align: middle !important;"><?= $empresa['descricao'] ?></td>
                                    <td style="vertical-align: middle !important;"><?= $empresa['status'] == 1 ? 'Ativo' : 'Inativo'?></td>
                                    <td style="vertical-align: middle !important;">
                                        <a href="empresa_nova.php?id=<?= $empresa['id']; ?>" class="btn btn-md btn-default" title="Editar"><span class="glyphicon glyphicon-edit" title="Editar"></span></a>
                                        <button type="button" class="btn btn-md btn-default alterar_status" data-id="<?= $empresa['id']; ?>" value="<?= $empresa['status'] == 1 ? 'desativar' : 'ativar'; ?>">
                                            <?= $empresa['status'] == 1 ? "<i class='fa fa-times' title='Desativar'></i>" : "<i class='fa fa-check' title='Ativar'></i>"?>
                                        </button>
                                    </td>
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
                                        echo "<li class='active'><a href='empresas.php?pag={$i}{$filtro_get}'>{$i}</a></li>";
                                    } else {
                                        echo "<li><a href='empresas.php?pag={$i}{$filtro_get}'>{$i}</a></li>";
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
<script type="text/javascript">
    $('.alterar_status').on('click', function(){
        var $this  = $(this);
        var tr  = $this.parents('tr');
        var dados  = {};
        dados.tipo = $this.val();
        dados.id   = $this.data('id');
        $.ajax({
            url: 'ajax/ativar_desativar_empresa.php',
            type: 'POST',
            data: dados
        }).done(function(data){
            var result = JSON.parse(data);
            if(result.success){
                $this.val(result.tipo);
                if(result.tipo == 1){
                    $this.val(1);
                    tr.find('td:eq(3)').html('Ativo');
                    $this.find('i').removeClass('fa-check');
                    $this.find('i').addClass('fa-times');
                } else {
                    $this.val(0);
                    tr.find('td:eq(3)').html('Inativo');
                    $this.find('i').removeClass('fa-times');
                    $this.find('i').addClass('fa-check');
                }
                $('.retorno').html(result.message);
                setTimeout(function(){
                    $('.retorno').html('');
                }, 2000);
            }
        });
    });
</script>