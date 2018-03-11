<?php
# Importar Header
include_once 'includes/header.php';
if($_SESSION['tipo_empresa_id'] != 1 && $_SESSION['nivel_acesso_id'] != 1){ session_destroy(); header("Location: index.php?mensagem=Usuário sem permissão, realize login novamente.&tipo=danger"); }

$db       = new MysqliDb();
$niveis   = $db->get("niveis_acessos");
$empresas = $db->get("empresas");
$editMode = false;
$projeto     = [];
if(isset($_GET['id'])){
    $editMode = true;
    $projeto = $db->where("id", $_GET['id']);
    $projeto = $db->getOne("projetos");
    if(empty($projeto)){
        header("Location: projetos.php");
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-offset-2 col-md-8 col-md-offset-2">
            <div class="retorno"></div>
            <form id="formNovoProjeto" method="POST" action="" enctype="multipart/form-data">
                <div class="panel panel-default" style="border-color: #000;">
                    <div class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                        <h4><i class="fa fa-projeto-plus"></i> <strong><i class="fa fa-laptop"></i> <?= $editMode ? 'Edição de ' : 'Novo ' ?> Projeto</strong></h4>
                    </div>
                    <div class="panel-body">
                        <input type="hidden" name="id" id="id" value="<?= !empty($projeto) ? $projeto['id'] : ''; ?>">
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" id="descricao" style="padding-top: 7px;">Descrição:</label>
                            <div class="col-md-10">
                                <input type="text" name="descricao" id="descricao" class="form-control" style="margin-bottom: 10px;" value="<?= !empty($projeto) ? $projeto['descricao'] : ''; ?>" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" for="rat" style="padding-top: 7px;">RAT:</label>
                            <div class="col-md-10" style="margin-bottom: 10px;">
                                <input type="file" class="form-control" name="rat" id="rat" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" for="logo" style="padding-top: 7px;">Logo:</label>
                            <div class="col-md-10" style="margin-bottom: 10px;">
                                <input type="file" class="form-control" name="logo" id="logo" required>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row text-right">
                            <a href="projetos.php" class="btn btn-md btn-default">Voltar</a>
                            <button type="submit" class="btn btn-md btn-success .submit" style="margin-right: 15px;"><?= $editMode ? 'Confirmar ' : 'Cadastrar ' ?></button>
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
        $('#formNovoProjeto').submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $.blockUI();
            $.ajax({
                url: 'ajax/novo-editar-projeto.php',
                type: 'POST',
                dataType: 'json',
                data: formData,
                processData: false,
                contentType: false
            }).done(function(data){
                result = data;
                $('.retorno').html(result.message);
                if(result.success){
                    setTimeout(function(){
                        window.location.href = 'projetos.php';
                        $.unblockUI();
                    }, 2000);
                } else {
                    $.blockUI();
                    setTimeout(function(){
                        $('.retorno').html('');
                    }, 2000);
                }
            });
        });
    });
</script>