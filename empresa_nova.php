<?php
# Importar Header
include_once 'includes/header.php';
if($_SESSION['tipo_empresa_id'] != 1 && $_SESSION['nivel_acesso_id'] != 1){ session_destroy(); header("Location: index.php?mensagem=Usuário sem permissão, realize login novamente.&tipo=danger"); }

$db             = new MysqliDb();
$tipos_empresas = $db->get("tipos_empresas");
$servicos       = $db->get("servicos");
$editMode       = false;
$empresa        = [];

if(isset($_GET['id'])){
    $editMode = true;
    # RECUPERA OS DADOS DA EMPRESA PARA PREENCHIMENTO EM CASO DE EDIÇÃO
    $db->where("e.id", $_GET['id']);
    $db->join("tipos_empresas te", "te.id=e.tipo_empresa_id", "LEFT");
    $empresa = $db->getOne("empresas e", "e.*, te.id as tipo_empresa_id");
    $db->where("empresa_id", $_GET['id']);
    $empresa['servicos'] = $db->get("empresas_servicos");
    if(empty($empresa)){
        header("Location: empresas.php");
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-offset-2 col-md-8 col-md-offset-2">
            <form id="formNovaEmpresa" method="POST" action="">
                <div class="panel panel-default" style="border-color: #000;">
                    <div class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                        <h4><i class="fa fa-building-o"></i> <strong><?= $editMode ? 'Edição de ' : 'Nova ' ?> Empresa</strong></h4>
                    </div>
                    <div class="panel-body">
                        <input type="hidden" name="id" id="id" value="<?= $editMode ? $empresa['id'] : ''; ?>">
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" for="nome_fantasia" style="padding-top: 7px;">Nome F.:</label>
                            <div class="col-md-10">
                                <input type="text" name="nome_fantasia" id="nome_fantasia" class="form-control" style="margin-bottom: 10px;" value="<?= $editMode ? $empresa['nome_fantasia'] : ''; ?>" placeholder="Nome Fantasia" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" for="razao_social" style="padding-top: 7px;">R. Social:</label>
                            <div class="col-md-10">
                                <input type="text" name="razao_social" id="razao_social" class="form-control" style="margin-bottom: 10px;" value="<?= $editMode ? $empresa['razao_social'] : ''; ?>" placeholder="Razão Social" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 pull text-right" for="cnpj" style="padding-top: 7px;">CNPJ:</label>
                            <div class="col-md-4">
                                <input type="text" name="cnpj" id="cnpj" class="form-control" style="margin-bottom: 10px;" value="<?= $editMode ? $empresa['cnpj'] : ''; ?>" placeholder="00.000.000/0000-00" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 pull text-right" for="ie" style="padding-top: 7px;">IE:</label>
                            <div class="col-md-4">
                                <input type="text" name="ie" id="ie" class="form-control" style="margin-bottom: 10px;" value="<?= $editMode ? $empresa['ie'] : ''; ?>" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" for="tipo_empresa_id" style="padding-top: 7px;">Tipo:</label>
                            <div class="col-md-4">
                                <select class="selectpicker" name="tipo_empresa_id" id="tipo_empresa_id" required>
                                    <option value=''>Nada selecionado</option>
                                    <?php
                                        foreach($tipos_empresas as $tipo){
                                            if($editMode && $tipo['id'] == $empresa['tipo_empresa_id']){
                                                echo "<option value='{$tipo['id']}' selected>{$tipo['descricao']}</option>;";
                                            } else {
                                                echo "<option value='{$tipo['id']}'>{$tipo['descricao']}</option>;";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" for="servicos" style="padding-top: 7px;" <?= $editMode ? '' : 'required'; ?>>Serviços:</label>
                            <div class="col-md-4">
                                <select class="selectpicker" name="servicos[]" id="servicos" data-selected-text-format="count" multiple required>
                                    <?php
                                        foreach($servicos as $servico){
                                            $selected = '';
                                            if($editMode){
                                                foreach($empresa['servicos'] as $eservico){
                                                    if($servico['id'] == $eservico['servico_id']){
                                                        $selected = 'selected';
                                                        break;
                                                    }
                                                }
                                            }
                                            echo "<option value='{$servico['id']}' {$selected}>{$servico['descricao']}</option>;";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="dados-bancarios" style="display: <?= !empty($empresa) && $empresa['tipo_empresa_id'] == 3 ? 'block' : 'none'; ?>;">
                        <div id="dados-bancarios" class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                            <h4><i class="fa fa-university"></i> <strong>Dados Bancários</strong></h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-group" style="margin-top: 15px;">
                                <label class="control-label col-md-2 text-right" id="banco" style="padding-top: 7px;">Banco:</label>
                                <div class="col-md-4">
                                    <input type="text" name="banco" id="banco" class="form-control" style="margin-bottom: 10px;" value="<?= !empty($empresa) ? $empresa['banco'] : ''; ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 text-right" id="tipo_conta" style="padding-top: 7px;">Tipo:</label>
                                <div class="col-md-4">
                                    <input type="text" name="tipo_conta" id="tipo_conta" class="form-control" style="margin-bottom: 10px;" value="<?= !empty($empresa) ? $empresa['tipo_conta'] : ''; ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 text-right" id="agencia" style="padding-top: 7px;">Agência:</label>
                                <div class="col-md-4">
                                    <input type="text" name="agencia" id="agencia" class="form-control" style="margin-bottom: 10px;" value="<?= !empty($empresa) ? $empresa['agencia'] : ''; ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 text-right" id="conta" style="padding-top: 7px;">Conta:</label>
                                <div class="col-md-4">
                                    <input type="text" name="conta" id="conta" class="form-control" style="margin-bottom: 10px;" value="<?= !empty($empresa) ? $empresa['conta'] : ''; ?>"/>
                                </div>
                            </div>
                        </div>
                        <div id="dados-bancarios" class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                            <h4><i class="fa fa-file-text-o"></i> <strong>Observações adicionais</strong></h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-group" style="margin-top: 15px;">
                                <div class="col-md-12">
                                    <textarea type="text" rows="6" name="obs_adicional" id="obs_adicional" class="form-control" style="margin-bottom: 10px;"><?= !empty($empresa) ? $empresa['obs_adicional'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row text-right">
                            <a href="empresas.php" class="btn btn-md btn-default">Voltar</a>
                            <button type="submit" class="btn btn-md btn-success" style="margin-right: 15px;"><?= $editMode ? 'Confirmar ' : 'Cadastrar ' ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-offset-2 col-md-8 col-md-offset-2">
        <div class="retorno"></div>
    </div>
</div>
<?php
# Importar Rodapé
include_once 'includes/footer.php';
?>
<script type="text/javascript">
    $(function(){
        $('#formNovaEmpresa').submit(function(e){
            e.preventDefault();
            var dados = $(this).serialize();
            $.blockUI();
            $.ajax({
                url: 'ajax/nova-editar-empresa.php',
                type: 'POST',
                data: dados
            }).done(function(data){
                var result = JSON.parse(data);
                $('.retorno').html(result.message);
                if(result.success){
                    setTimeout(function(){
                        $.unblockUI();
                        window.location.href = 'empresas.php';
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