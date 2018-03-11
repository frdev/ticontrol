<?php
# Importar Header
include_once 'includes/header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-offset-3 col-md-6 col-md-offset-3">
            <div class="retorno"></div>
            <form id="formNovoChamado" method="POST" action="" enctype="multipart/form-data">
                <div class="panel panel-default" style="border-color: #000;">
                    <div class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                        <h4><i class="fa fa-id-card" aria-hidden="true"></i> <strong>Alterar Senha</strong></h4>
                    </div>
                    <div class="panel-body">
                        <input type="hidden" name="id" id="id" value="<?= $_SESSION['id']; ?>">
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="control-label col-md-3 text-right" for="senha" style="padding-top: 7px;">Senha:</label>
                            <div class="col-md-9" style="margin-bottom: 10px;">
                                <input type="password" name="senha" id="senha" class="form-control" placeholder="Senha atual" required/>
                            </div>
                        </div>
                        <div class="form-group" >
                            <label class="control-label col-md-3 text-right" for="nova" style="padding-top: 7px;">Nova:</label>
                            <div class="col-md-9" style="margin-bottom: 10px;">
                                <input type="password" name="nova" id="nova" class="form-control" placeholder="Nova senha" required />
                            </div>
                        </div>
                        <div class="form-group" >
                            <label class="control-label col-md-3 text-right" for="confirmar" style="padding-top: 7px;">Confirmar:</label>
                            <div class="col-md-9" style="margin-bottom: 10px;">
                                <input type="password" name="confirmar" id="confirmar" class="form-control" placeholder="Confirmar nova senha" required />
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row text-right">
                            <a href="projetos.php" class="btn btn-md btn-default">Voltar</a>
                            <button type="submit" class="btn btn-md btn-success" style="margin-right: 15px;">Confirmar</button>
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
        $('#formNovoChamado').submit(function(e){
            $.blockUI();
            e.preventDefault();
            var dados = $(this).serialize();
            $.ajax({
                url: 'ajax/alterar-senha-usuario.php',
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
                    $.unblockUI();
                    setTimeout(function(){
                        $('.retorno').html('');
                    }, 2000);
                }
            });
        });
    });
</script>