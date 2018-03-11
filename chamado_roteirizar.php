<?php
# Importar Header
include_once 'includes/header.php';
if($_SESSION['tipo_empresa_id'] != 1 && $_SESSION['nivel_acesso_id'] != 1){ session_destroy(); header("Location: index.php?mensagem=Usuário sem permissão, realize login novamente.&tipo=danger"); }

$db          = new MysqliDb();
$chamado     = [];
$empresas    = [];
if(isset($_GET['id'])){
    $editMode = true;
    # Recupera dados do chamado em caso de edição
    $db->where('id', $_GET['id']);
    $chamado = $db->getOne('chamados');
    if(empty($chamado)){
        header("Location: chamados.php");
    }
    $db->join('empresas_servicos ev', 'ev.empresa_id=e.id');
    $db->where('e.tipo_empresa_id', 3);
    $db->where('ev.servico_id', $chamado['servico_id']);
    $db->orderBy('e.nome_fantasia', 'asc');
    $empresas = $db->get("empresas e", null, array('e.*'));
} else {
    header("Location: chamados.php");
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-offset-2 col-md-8 col-md-offset-2">
            <div class="retorno"></div>
            <form id="formRoteirizar" method="POST" action="" enctype="multipart/form-data">
                <div class="panel panel-default" style="border-color: #000;">
                    <div class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                        <h4><i class="fa fa-share"></i> <strong>Roteirizar Chamado - <?= $chamado['numero']?></strong></h4>
                    </div>
                    <div class="panel-body">
                        <input type="hidden" name="id" id="id" value="<?=$chamado['id'];?>">
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="control-label col-md-2 text-right" for="empresa_close_id" style="padding-top: 7px;">Empresa:</label>
                            <div class="col-md-4" style="margin-bottom: 10px;">
                                <select class="selectpicker" name="empresa_close_id" id="empresa_close_id" required>
                                    <option value=''>Selecione empresa</option>
                                    <?php
                                    foreach($empresas as $empresa){
                                        echo "<option value='{$empresa['id']}'>{$empresa['nome_fantasia']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" for="valor" style="padding-top: 7px;">Valor:</label>
                            <div class="input-group col-md-4" style="margin-bottom: 10px;">
                                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-usd"></i></span>
                                <input type="text" class="form-control" placeholder="0,00" data-mask="0000,00" data-mask-reverse="true" name="valor" id="valor" aria-describedby="basic-addon1" required>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row text-right">
                            <a href="chamados.php" class="btn btn-md btn-default">Voltar</a>
                            <button type="submit" class="btn btn-md btn-success" style="margin-right: 15px;"><?= $editMode ? 'Confirmar ' : 'Solicitar ' ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php
# Importar Rodapé
include_once 'includes/footer.php';
?>
<script type="text/javascript">
    $(function(){
        $('#formRoteirizar').submit(function(e){
            $.blockUI();
            e.preventDefault();
            var dados = $(this).serialize();
            $.ajax({
                url: 'ajax/roteirizar-chamado.php',
                type: 'POST',
                data: dados
            }).done(function(data){
                var result = JSON.parse(data);
                $('.retorno').html(result.message);
                if(result.success){
                    setTimeout(function(){
                        $.unblockUI();
                        window.location.href = 'chamados.php';
                    }, 2000);
                } else {
                    setTimeout(function(){
                        $.blockUI();
                        $('.retorno').html('');
                    }, 2000);
                }
            });
        });
        
    });
</script>