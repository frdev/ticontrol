<?php
# Importar Header
include_once 'includes/header.php';

validaEmpresaAdminOrTecnico($_SESSION);
$db          = new MysqliDb();
$chamado     = [];
if(isset($_GET['id'])){
    $editMode = true;
    # Recupera dados do chamado em caso de edição
    $db->where('id', $_GET['id']);
    $db->where('status_id', 3, '!=');
    $chamado = $db->getOne('chamados');
    if(empty($chamado)){
        header("Location: chamados.php");
    }
} else {
    header("Location: chamados.php");
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-offset-2 col-md-8 col-md-offset-2">
            <div class="retorno"></div>
            <form id="formFechar" method="POST" action="" enctype="multipart/form-data">
                <div class="panel panel-default" style="border-color: #000;">
                    <div class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                        <h4><i class="fa fa-calendar-check-o"></i> <strong>Finalizar Chamado - <?= $chamado['numero']?></strong></h4>
                    </div>
                    <div class="panel-body">
                        <input type="hidden" name="id" id="id" value="<?=$chamado['id'];?>">
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="control-label col-md-2 text-right" for="inicio_at" style="padding-top: 7px;"">Início:</label>
                            <div class="col-md-4" style="margin-bottom: 10px;">
                                <input type="text" class='form-control' name="inicio_at" id="inicio_at" value="<?= $chamado['inicio_at']; ?>" rows="8" required>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="control-label col-md-2 text-right" for="fim_at" style="padding-top: 7px;"">Fim:</label>
                            <div class="col-md-4" style="margin-bottom: 10px;">
                                <input type="text" class='form-control' name="fim_at" id="fim_at" rows="8" required>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="control-label col-md-2 text-right" for="obs_close" style="padding-top: 7px;"">Fechamento:</label>
                            <div class="col-md-10" style="margin-bottom: 10px;">
                                <textarea class='form-control' name="obs_close" id="obs_close" rows="8" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" for="rat_fechamento" style="padding-top: 7px;">RAT:</label>
                            <div class="col-md-10" style="margin-bottom: 10px;">
                                <input type="file" class="form-control" name="rat_fechamento" id="rat_fechamento" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" for="foto1" style="padding-top: 7px;">Anexo 1:</label>
                            <div class="col-md-10" style="margin-bottom: 10px;">
                                <input type="file" class="form-control" name="foto1" id="foto1" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" for="foto2" style="padding-top: 7px;">Anexo 2:</label>
                            <div class="col-md-10" style="margin-bottom: 10px;">
                                <input type="file" class="form-control" name="foto2" id="foto2" required>
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
        $('#formFechar').submit(function(e){
            $.blockUI();
            e.preventDefault();
            var formData  = new FormData(this);
            $.ajax({
                url: 'ajax/fechar-chamado.php',
                type: 'POST',
                dataType: 'json',
                data: formData,
                processData: false,
                contentType: false
            }).done(function(data){
                var result = data;
                $('.retorno').html(result.message);
                if(result.success){
                    setTimeout(function(){
                        $.unblockUI();
                        window.location.href = 'chamados.php';
                    }, 2000);
                } else {
                    $.unblockUI();
                    setTimeout(function(){
                        $('.retorno').html('');
                    }, 2000);
                }
            });
        });
        $('#inicio_at').mask('00:00');
        $('#fim_at').mask('00:00');
    });
</script>