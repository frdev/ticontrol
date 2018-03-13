<?php
# Importar Header
include_once 'includes/header.php';

if(!isset($_GET['pag'])){
    $pag = 1;
} else {
    $pag = $_GET['pag'];
}
# Instancia DB
$db = new MysqliDb();
$db->where('tipo_empresa_id', 3);
$db->orderBy('nome_fantasia', 'asc');
$empresas = $db->get('empresas');
# Monta os join's
$db->join("projetos p", "p.id=c.projeto_id");
$db->join("empresas eo", "eo.id=c.empresa_open_id", "LEFT");
$db->join("empresas ec", "ec.id=c.empresa_close_id", "LEFT");
$db->join("servicos sv", "sv.id=c.servico_id");
$db->join("prioridades_chamados pr", "pr.id=c.prioridade_id");
$db->join("periodos_chamados pe", "pe.id=c.periodo_id");
$db->join("status_chamados s", "s.id=c.status_id");
$db->join("state uf", "uf.id=c.state_id");
$db->join("city cidade", "cidade.id=c.city_id");
$db->join("periodos_chamados pc", "pc.id=c.periodo_id");

if(!empty($_GET['tipo_filtro']) && (!empty($_GET['filtro']) || !empty($_GET['data_ini']) || !empty($_GET['data_fim']))){
    $filtro      = $_GET['filtro'];
    $data_ini    = $_GET['data_ini'];
    $data_fim    = $_GET['data_fim'];
    $tipo_filtro = $_GET['tipo_filtro'];
    switch($_GET['tipo_filtro']){
        case 'chamado':
            $db->where('c.numero', "%{$filtro}%", 'like');
            break;
        case 'parceiro':
            $db->where('eo.nome_fantasia', "%{$filtro}%", 'like');
            break;
        case 'tecnico':
            $db->where('ec.nome_fantasia', "%{$filtro}%", 'like');
            break;
        case 'data':
            $db->where('c.data_atendimento', "{$data_ini}", '>=');
            $db->where('c.data_atendimento', "{$data_fim}", '<=');
    }
}

$db->orderBy('c.data_atendimento', 'desc');

if($_SESSION['tipo_empresa_id'] == 2){
    $db->where('eo.id', $_SESSION['empresa_id']);
} else if($_SESSION['tipo_empresa_id'] == 3) {
    $db->where('ec.id', $_SESSION['empresa_id']);
}
# Prepara os campos para recuperar
$campos = "c.*, p.descricao as p_descricao, p.logo as p_logo, eo.nome_fantasia as eo_nome, ec.nome_fantasia as ec_nome, sv.descricao as sv_descricao, pr.descricao as pr_descricao, s.descricao as s_descricao, uf.abbreviation as uf, cidade.name as cidade, pc.descricao as pc_descricao";
# Recupera os dados
$chamados = $db->arraybuilder()->paginate("chamados c", $pag, $campos);

?>

<div class="container">
    <div class="col-md-12">
        <?php
        if($_SESSION['tipo_empresa_id'] != 3){
        ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <a href="chamado_novo.php" class="btn btn-lg btn-primary" title="Novo Chamado"><strong><i class="fa fa-plus"></i></strong></a>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
        <br>
        <div class="row">
            <div class="retorno" id="retorno">
                <?php
                    if(isset($_GET['message'])){
                        echo $_GET['message'];
                    }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-default" style="border-color: #000;">
                <div class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                    <h4><i class="fa fa-clipboard"></i> Gerencimento de Chamados</h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <form method="GET" action="">
                            <div class="form-group" style="margin-top: 15px;">
                                <label class="control-label col-sm-1 text-left" for="tipo_filtro" style="padding-top: 7px;"><i class='fa fa-filter'></i> Filtro:</label>
                                <div class="col-sm-3" style="margin-bottom: 10px;">
                                    <select class="selectpicker" name="tipo_filtro" id="tipo_filtro">
                                        <option value="">Selecione filtro</option>
                                        <option value="chamado" <?= isset($tipo_filtro) && $tipo_filtro == 'chamado' ? 'selected' : ''; ?>>Chamado</option>
                                        <option value="data" <?= isset($tipo_filtro) && $tipo_filtro == 'data' ? 'selected' : ''; ?>>Data Inicial e Final</option>
                                        <?php
                                            if($_SESSION['tipo_empresa_id'] == 1){
                                        ?>
                                            <option value="parceiro" <?= isset($tipo_filtro) && $tipo_filtro == 'parceiro' ? 'selected' : ''; ?>>Parceiro</option>
                                            <option value="tecnico" <?= isset($tipo_filtro) && $tipo_filtro == 'tecnico' ? 'selected' : ''; ?>>Tecnico</option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-3" style="margin-bottom: 10px;">
                                    <input type="text" name="filtro" id="filtro" class="form-control" value='<?= isset($filtro) ? $filtro : ''; ?>' style="display: <?= isset($tipo_filtro) && $tipo_filtro != 'data' ? 'block' : 'none'; ?>;"/>
                                    <input type="date" name="data_ini" id="data_ini" class="form-control" value='<?= isset($data_ini) ? $data_ini : ''; ?>' style="display: <?= isset($tipo_filtro) && $tipo_filtro == 'data' ? 'block' : 'none'; ?>;"/>
                                </div>
                                <div class="col-sm-3" style="margin-bottom: 10px;">
                                    <input type="date" name="data_fim" id="data_fim" class="form-control" value='<?= isset($data_fim) ? $data_fim : ''; ?>' style="display: <?= isset($tipo_filtro) && $tipo_filtro == 'data' ? 'block' : 'none'; ?>;"/>
                                </div>
                                <div class="col-sm-2" style="margin-bottom: 10px;">
                                    <button type="submit" class="btn btn-md btn-primary">Pesquisar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <hr style="color: #000;">
                    <div class="row">
                        <form id="formExcel" method="POST" action="ajax/gerar-excel.php">
                            <div class="form-group" style="margin-top: 15px;">
                                <label class="control-label col-md-2 text-left" style="padding-top: 7px;"><i class='fa fa-file-excel-o'></i> Relatório:</label>
                                <?php
                                if($_SESSION['tipo_empresa_id'] == 1){
                                ?>
                                    <div class="col-md-3" style="margin-bottom: 10px;">
                                        <select class="selectpicker" name="rel_empresa" id="rel_empresa" required>
                                            <option value="">Selecione técnico</option>
                                            <?php
                                            foreach($empresas as $empresa){
                                            ?>
                                                <option value='<?=$empresa['id'];?>'><?=$empresa['nome_fantasia'];?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                <?php
                                }
                                ?>
                                <div class="col-md-2" style="margin-bottom: 10px;">
                                    <input type="date" name="rel_data_ini" id="data_ini" class="form-control" required/>
                                </div>
                                <div class="col-md-2" style="margin-bottom: 10px;">
                                    <input type="date" name="rel_data_fim" id="data_fim" class="form-control" required/>
                                </div>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px;">
                                <button type="submit" class="btn btn-md btn-primary">Gerar Excel</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="text-center">
                            <tr>
                                <td><strong>Chamado</strong></td>
                                <td colspan="2"><strong>Projeto</strong></td>
                                <?php if($_SESSION['tipo_empresa_id'] == 1){ ?>
                                <td><strong>Técnico</strong></td>
                                <?php } ?>
                                <td><strong>Local</strong></td>
                                <td><strong>Data</strong></td>
                                <td><strong>Período</strong></td>
                                <?php if($_SESSION['tipo_empresa_id'] != 2){ ?>
                                <td><strong>R$</strong></td>
                                <?php } ?>
                                <td><strong>Status</strong></td>
                                <td><strong>Ações</strong></td>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php
                            if(!empty($chamados)){
                                foreach($chamados as $chamado){
                                ?>
                                <tr>
                                    <?php
                                        $cor_status = '';
                                        if($chamado['prioridade_id'] == 2){
                                            $cor_status = "#0000CD";
                                        } else if($chamado['prioridade_id'] == 3){
                                            $cor_status = "#FF0000";
                                        }
                                    ?>
                                    <td style="vertical-align: middle !important; color: <?=$cor_status?>"><?= $chamado['numero'] ?></td>
                                    <td style="vertical-align: middle !important;"><?= $chamado['p_descricao'] ?></td>
                                    <td style="vertical-align: middle !important;"><?= !empty($chamado['p_logo'])? "<img src='logos-projetos/{$chamado['p_logo']}' style='width: 65px; height: 20px;'/>" : '-'?></td>
                                    <?php if($_SESSION['tipo_empresa_id'] == 1){ ?>
                                    <td style="vertical-align: middle !important;"><?= !empty($chamado['empresa_close_id'])? $chamado['ec_nome'] : '-'?></td>
                                    <?php } ?>
                                    <td style="vertical-align: middle !important;"><?= $chamado['cidade'].'/'.$chamado['uf'] ?></td>
                                    <td style="vertical-align: middle !important;"><?= date('d/m/Y', strtotime($chamado['data_atendimento'])); ?></td>
                                    <td style="vertical-align: middle !important;"><?= $chamado['pc_descricao'] ?></td>
                                    <?php if($_SESSION['tipo_empresa_id'] != 2){ ?>
                                    <td style="vertical-align: middle !important;"><?= number_format($chamado['valor'], 2, ',', '.'); ?></td>
                                    <?php } ?>
                                    <td style="vertical-align: middle !important;"><?= $_SESSION['tipo_empresa_id'] == 2 && $chamado['status_id'] == 7? 'Fechado' : $chamado['s_descricao']; ?></td>
                                    <td style="vertical-align: middle !important;">
                                        <a href="chamado_visualizar.php?id=<?= $chamado['id']; ?>" class="btn btn-sm btn-default" title="Visualizar"><i class="fa fa-search-plus"></i></a>
                                        <?php
                                        if($_SESSION['tipo_empresa_id'] != 3 && $chamado['status_id'] < 3){
                                        ?>
                                            <a href="chamado_novo.php?id=<?= $chamado['id']; ?>" class="btn btn-sm btn-default" title="Editar"><span class="glyphicon glyphicon-edit" title="Editar"></span></a>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                            if($_SESSION['tipo_empresa_id'] == 1 && $chamado['status_id'] == 1){
                                        ?>
                                                <a href="chamado_roteirizar.php?id=<?= $chamado['id']; ?>" class="btn btn-sm btn-default" title="Roteirizar"><i class="fa fa-share"></i></a>
                                        <?php
                                            }
                                        ?>
                                        <?php
                                        $data_atual = date('Y-m-d');
                                        if($_SESSION['tipo_empresa_id'] != 2 && $chamado['status_id'] == 2 && strtotime($data_atual) == strtotime($chamado['data_atendimento'])){
                                        ?>
                                            <button data-id="<?= $chamado['id']; ?>" data-numero="<?= $chamado['numero']; ?>" class="btn btn-sm btn-default ematendimento" title="Colocar em Atendimento"><i class="fa fa-suitcase"></i></button>
                                        <?php
                                            }
                                        ?>
                                        <?php
                                            $data_atual = date('Y-m-d');
                                            if($_SESSION['tipo_empresa_id'] != 2 && strtotime($data_atual) == strtotime($chamado['data_atendimento']) && $chamado['status_id'] == 6){
                                        ?>
                                            <a href="chamado_fechar.php?id=<?= $chamado['id']; ?>" class="btn btn-sm btn-default" title="Fechar"><span class="fa fa-check-square-o" title="Finalizar"></span></a>
                                        <?php
                                            }
                                        ?>
                                        <?php
                                            if($_SESSION['tipo_empresa_id'] != 3 && ($chamado['status_id'] != 5 && $chamado['status_id'] != 3 && $chamado['status_id'] != 6 && $chamado['status_id'] != 7)){
                                        ?>
                                                <button value='cancelar' data-id="<?= $chamado['id']; ?>" data-numero="<?= $chamado['numero']; ?>" class="btn btn-sm btn-default cancelar" title="Cancelar"><i class="fa fa-times"></i></button>
                                        <?php
                                            }
                                        ?>
                                        <?php
                                        if($_SESSION['tipo_empresa_id'] != 3 && $chamado['status_id'] == 1){
                                        ?>
                                            <button value="<?= $chamado['id']; ?>" data-numero="<?= $chamado['numero']; ?>" class="btn btn-sm btn-default excluir" title="Deletar"><i class="fa fa-trash-o"></i></button>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <td colspan="9" class="text-center"><span class="text-danger"><strong>Não existe chamados para o filtro aplicado.</strong></span></td>
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
                                        echo "<li class='active'><a href='chamados.php?pag={$i}{$filtro_get}'>{$i}</a></li>";
                                    } else {
                                        echo "<li><a href='chamados.php?pag={$i}{$filtro_get}'>{$i}</a></li>";
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