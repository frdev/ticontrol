<?php
# Importar Header
include_once 'includes/header.php';

?>

<div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8 col-md-offset-2">
                <div class="panel panel-default" style="border-color: #000;">
                    <div class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                        <h4><i class="fa fa-info-circle"></i> Informações sobre Faturamento</h4>
                    </div>
                    <div class="panel-body">
                    
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
    $(function(){
        $('.cancelar').on('click', function(){
            var $this   = $(this);
            var chamado = $this.data('numero');
            var id      = $this.data('id');
            bootbox.confirm({
                message: "Deseja realmente realizar o cancelamento do chamado <strong>" + chamado + "</strong>?",
                buttons: {
                    confirm: {
                        label: 'Sim',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'Não',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if(result){
                        window.location.href = "#retorno";
                        $('.retorno').html("<div class='alert alert-success text-center'>Cancelando chamado, aguarde um momento.<br><img src='img/loading.gif' /></div>");
                        $.ajax({
                            url: 'ajax/cancelar-chamado.php',
                            type: 'POST',
                            data: {id: id}
                        }).done(function(data){
                            var result = JSON.parse(data);
                            $('.retorno').html(result.message);
                            if(result.success){
                                setTimeout(function(){
                                    window.location.href = "chamados.php";
                                }, 2000);
                            } else {
                                setTimeout(function(){
                                    $('.retorno').html('');
                                }, 2000);
                            }
                        });
                    }
                }
            });
        });
        $('.excluir').on('click', function(){
            var $this   = $(this);
            var chamado = $this.data('numero');
            var id      = $this.val();
            bootbox.confirm({
                message: "Deseja realmente realizar a exclusão do chamado <strong>" + chamado + "</strong>?",
                buttons: {
                    confirm: {
                        label: 'Sim',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'Não',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if(result){
                        window.location.href = "#retorno";
                        $('.retorno').html("<div class='alert alert-success text-center'>Excluindo chamado, aguarde um momento.<br><img src='img/loading.gif' /></div>");
                        $.ajax({
                            url: 'ajax/excluir-chamado.php',
                            type: 'POST',
                            data: {id: id}
                        }).done(function(data){
                            var result = JSON.parse(data);
                            $('.retorno').html(result.message);
                            if(result.success){
                                setTimeout(function(){
                                    window.location.href = "chamados.php";
                                }, 2000);
                            } else {
                                setTimeout(function(){
                                    $('.retorno').html('');
                                }, 2000);
                            }
                        });
                    }
                }
            });
        });
        $('.ematendimento').on('click', function(){
            var $this   = $(this);
            var chamado = $this.data('numero');
            var id      = $this.data('id');
            bootbox.confirm({
                message: "Deseja começar o atendimento do chamado <strong>" + chamado + "</strong>?<br>Será enviado um e-mail notificando o início do atendimento.",
                buttons: {
                    confirm: {
                        label: 'Sim',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'Não',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    window.location.href = "#retorno";
                    $('.retorno').html("<div class='alert alert-success text-center'>Iniciando atendimento, aguarde um momento.<br><img src='img/loading.gif' /></div>");
                    if(result){
                        $.ajax({
                            url: 'ajax/chamado-em-atendimento.php',
                            type: 'POST',
                            data: {id: id}
                        }).done(function(data){
                            var result = JSON.parse(data);
                            $('.retorno').html(result.message);
                            if(result.success){
                                setTimeout(function(){
                                    window.location.href = "chamados.php";
                                }, 2000);
                            } else {
                                setTimeout(function(){
                                    $('.retorno').html('');
                                }, 2000);
                            }
                        });
                    }
                }
            });
        });
        $('#tipo_filtro').on('change', function(){
            if($(this).val() == 'data'){
                $('#data_ini').show();
                $('#data_fim').show();
                $('#filtro').hide();
            } else {
                $('#data_ini').hide();
                $('#data_fim').hide();
                $('#filtro').show();
            }
        });
    });
</script>

<?php
# Importar Rodapé
include_once 'includes/footer.php';
?>