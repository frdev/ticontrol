<?php
# Importar Header
include_once 'includes/header.php';
if($_SESSION['tipo_empresa_id'] != 1 && $_SESSION['nivel_acesso_id'] != 1){ session_destroy(); header("Location: index.php?mensagem=Usuário sem permissão, realize login novamente.&tipo=danger"); }

$db       = new MysqliDb();
$db->where('tipo_empresa_id', 3);
$db->orderBy('nome_fantasia', 'asc');
$empresas = $db->get("empresas");
if(!isset($_GET['pag'])){
    $pag = 1;
} else {
    $pag = $_GET['pag'];
}
$db->orderBy('p.data', 'desc');
$db->join('empresas e', 'e.id=p.empresa_id');
$db->join('pagamentos_chamados pc', 'pc.pagamento_id=p.id');
$db->join('chamados c', 'pc.chamado_id=c.id');
if(isset($_GET) && !empty($_GET['tipo_filtro']) && !empty($_GET['filtro'])){
    $filtro      = $_GET['filtro'];
    $tipo_filtro = $_GET['tipo_filtro'];
    switch($tipo_filtro){
        case 'tecnico':
            $db->where('e.nome_fantasia', '%'.$filtro.'%', 'like');
            break;
    }
}
$db->groupBy('p.id');
$pagamentos = $db->arraybuilder()->paginate('pagamentos p', $pag, 'p.*, e.nome_fantasia');
?>
<div class="container">
    <br>
    <div class="retorno"></div>
    <div class="row">
        <div class="col-md-offset-3 col-md-6 col-md-offset-3">
            <form id="formPagamento" method="POST" action="">
                <div class="panel panel-default" style="border-color: #000;">
                    <div class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                        <h4><i class="fa fa-money"></i> Pagamento</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-2" style="padding-top: 7px;">
                                    <strong>Empresa:</strong>
                                </div>
                                <div class="col-md-10">
                                    <select class="selectpicker" name="empresa_id" id="empresa_id" data-live-search="true" required>
                                        <option value="">Selecione Empresa</option>
                                        <?php

                                        foreach($empresas as $empresa){
                                            echo "<option value='{$empresa['id']}'>{$empresa['nome_fantasia']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:10px;">
                            <div class="form-group">
                                <div class="col-md-2" style="padding-top: 7px;">
                                    <strong>Período:</strong>
                                </div>
                                <div class="col-md-5">
                                    <input class="form-control" type="date" id="data_ini" name="data_ini" required/>
                                </div>
                                <div class="col-md-5">
                                    <input class="form-control" type="date" id="data_fim" name="data_fim" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:10px;">
                            <div class="form-group">
                                <div class="col-md-2" style="padding-top: 7px;">
                                    <strong>Anexo:</strong>
                                </div>
                                <div class="col-md-10">
                                    <input class="form-control" type="file" id="comprovante" name="comprovante" required/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row text-right">
                            <a href="chamados.php" class="btn btn-md btn-default">Voltar</a>
                                <button type="submit" class="btn btn-md btn-success .submit" style="margin-right: 15px;">Classificar como Pago</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-2 col-md-8 col-md-offset-2">
            <div class="panel panel-default" style="border-color: #000;">
                <div class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                    <h4><i class="fa fa-money"></i> Pagamentos Efetuados</h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <form method="GET" action="">
                            <div class="form-group" style="margin-top: 15px;">
                                <label class="control-label col-sm-2 text-left" for="tipo_filtro" style="padding-top: 7px;"><i class='fa fa-filter'></i> Filtro:</label>
                                <div class="col-sm-4" style="margin-bottom: 10px;">
                                    <select class="selectpicker" name="tipo_filtro" id="tipo_filtro">
                                        <option value="">Selecione filtro</option>
                                        <option value="tecnico" <?=!empty($tipo_filtro) && $tipo_filtro == 'tecnico' ? 'selected' : ''?>>Parceiro</option>
                                    </select>
                                </div>
                                <div class="col-sm-4" style="margin-bottom: 10px;">
                                    <input type="text" name="filtro" class="form-control" value="<?=!empty($filtro)? $filtro : ''?>"/>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-sm btn-primary" type="submit">Pesquisar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="text-center">
                            <td><strong>Técnico</strong></td>
                            <td><strong>Data</strong></td>
                            <td><strong>Período</strong></td>
                            <td><strong>Ações</strong></td>
                        </thead>
                        <tbody>
                            <?php
                            if(!empty($pagamentos[0])){
                                foreach($pagamentos as $pagamento){
                            ?>
                                <tr>
                                    <td class='text-center'><?=$pagamento['nome_fantasia']?></td>
                                    <td class='text-center'><?=date('d/m/Y', strtotime($pagamento['data']))?></td>
                                    <td class='text-center'><?=date('d/m/Y', strtotime($pagamento['periodo_ini']))?> à <?=date('d/m/Y', strtotime($pagamento['periodo_fim']))?></td>
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
                                if(!empty($_GET['tipo_filtro']) && $_GET['filtro']){
                                   $filtro_get = '&tipo_filtro='.$_GET['tipo_filtro'].'&filtro='.$_GET['filtro']; 
                                } else {
                                    $filtro_get = '';
                                }
                                for ($i=1; $i <= $db->totalPages; $i++) {
                                    if($i == $pag){
                                        echo "<li class='active'><a href='efetuar-pagamentos.php?pag={$i}{$filtro_get}'>{$i}</a></li>";
                                    } else {
                                        echo "<li><a href='efetuar-pagamentos.php?pag={$i}{$filtro_get}'>{$i}</a></li>";
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
    $(function(){
        $('#formPagamento').submit(function(e){
            e.preventDefault();
            var formData  = new FormData(this);
            bootbox.confirm({
                message: "Confirma o pagamento para o parceiro mencionado?</strong>?",
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
                    $.blockUI();
                    if(result){
                        $.ajax({
                            url: 'ajax/efetivar-pagamento.php',
                            type: 'POST',
                            dataType: 'json',
                            data: formData,
                            processData: false,
                            contentType: false
                        }).done(function(data){
                            $.unblockUI();
                            var result = data;
                            $('.retorno').html(result.message);
                            if(result.success){
                                setTimeout(function(){
                                    $.unblockUI();
                                    window.location.href = "efetuar-pagamentos.php";
                                }, 2000);
                            } else {
                                $.unblockUI();
                                setTimeout(function(){
                                    $('.retorno').html('');
                                }, 2000);
                            }
                        });
                    }
                }
            });
        });
    });
</script>