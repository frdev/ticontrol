<?php
# Importar Header
include_once 'includes/header.php';

if($_SESSION['tipo_empresa_id'] == 3){
    header("Location: chamados.php");
}
$db             = new MysqliDb();
$db->where('status', 1);
$db->orderBy('descricao', 'asc');
$projetos       = $db->get('projetos');
$db->orderBy('id', 'asc');
$prioridades    = $db->get('prioridades_chamados');
$db->orderBy('id', 'asc');
$servicos       = $db->get('servicos');
$db->orderBy('abbreviation', 'asc');
$estados        = $db->get('state');
$db->orderBy('id', 'asc');
$periodos       = $db->get('periodos_chamados');
$db->orderBy('id', 'asc');
$status         = $db->get('status_chamados');
$db->where('tipo_empresa_id', 2);
$db->orderBy('nome_fantasia', 'asc');
$empresas_open = $db->get('empresas');
$db->where('tipo_empresa_id', 3);
$db->orderBy('nome_fantasia', 'asc');
$empresas_close = $db->get('empresas');
$editMode       = false;
$chamado        = [];
$cidades        = [];
if(isset($_GET['id'])){
    $editMode = true;
    # Recupera dados do chamado em caso de edição
    $db->where('id', $_GET['id']);
    $chamado = $db->getOne('chamados');
    if(empty($chamado)){
        header("Location: chamados.php");
    }
    $db->where('state_id', $chamado['state_id']);
    $cidades = $db->get('city');
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-offset-2 col-md-8 col-md-offset-2">
            <form id="formNovoChamado" method="POST" action="" enctype="multipart/form-data">
                <div class="panel panel-default" style="border-color: #000;">
                    <div class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                        <h4><i class="fa fa-calendar<?= $editMode ? '-o' : '-plus-o ' ?>"></i> <strong><?= $editMode ? 'Edição de ' : 'Novo ' ?> Chamado</strong></h4>
                    </div>
                    <div class="panel-body">
                        <input type="hidden" name="id" id="id" value="<?= $editMode ? $chamado['id'] : ''; ?>">
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="control-label col-md-2 text-right" for="numero" style="padding-top: 7px;">OS:</label>
                            <div class="col-md-4" style="margin-bottom: 10px;">
                                <input type="text" name="numero" id="numero" class="form-control" value="<?= $editMode ? $chamado['numero'] : ''; ?>" placeholder="Número da OS" <?= $editMode && $_SESSION['tipo_empresa_id'] == 3 ? 'readonly' : ''?> required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" for="projeto_id" style="padding-top: 7px;">Projeto:</label>
                            <div class="col-md-4" style="margin-bottom: 10px;">
                                <select class="selectpicker" name="projeto_id" id="projeto_id" data-live-search="true" <?= $editMode && $_SESSION['tipo_empresa_id'] == 3 ? 'readonly' : ''?> required>
                                    <option value=''>Selecione projeto</option>
                                    <?php
                                    if($editMode){
                                        foreach($projetos as $projeto){
                                            if($projeto['id'] == $chamado['projeto_id']){
                                                echo "<option value='{$projeto['id']}' selected>{$projeto['descricao']}</option>";
                                            } else {
                                                echo "<option value='{$projeto['id']}'>{$projeto['descricao']}</option>";
                                            }   
                                        }
                                    } else {
                                        foreach($projetos as $projeto){
                                            echo "<option value='{$projeto['id']}'>{$projeto['descricao']}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" for="prioridade_id" style="padding-top: 7px;">Prioridade:</label>
                            <div class="col-md-4">
                                <select class="selectpicker" name="prioridade_id" id="prioridade_id" <?= $editMode && $_SESSION['tipo_empresa_id'] == 3 ? 'readonly' : ''?> required style="margin-bottom: 10px;">
                                    <option value=''>Selecione prioridade</option>
                                    <?php
                                    if($editMode){
                                        foreach($prioridades as $prioridade){
                                            if($prioridade['id'] == $chamado['prioridade_id']){
                                                echo "<option value='{$prioridade['id']}' selected>{$prioridade['descricao']}</option>";
                                            } else {
                                                echo "<option value='{$prioridade['id']}'>{$prioridade['descricao']}</option>";
                                            }   
                                        }
                                    } else {
                                        foreach($prioridades as $prioridade){
                                            echo "<option value='{$prioridade['id']}'>{$prioridade['descricao']}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" for="servico_id" style="padding-top: 7px;">Serviço:</label>
                            <div class="col-md-4">
                                <select class="selectpicker" name="servico_id" id="servico_id" data-live-search="true" <?= $editMode && $_SESSION['tipo_empresa_id'] == 3 ? 'readonly' : ''?> required style="margin-bottom: 10px;">
                                    <option value=''>Selecione serviço</option>
                                    <?php
                                    if($editMode){
                                        foreach($servicos as $servico){
                                            if($servico['id'] == $chamado['servico_id']){
                                                echo "<option value='{$servico['id']}' selected>{$servico['descricao']}</option>";
                                            } else {
                                                echo "<option value='{$servico['id']}'>{$servico['descricao']}</option>";
                                            }   
                                        }
                                    } else {
                                        foreach($servicos as $servico){
                                            echo "<option value='{$servico['id']}'>{$servico['descricao']}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php
                        if($_SESSION['tipo_empresa_id'] == 1){
                        ?>
                            <div class="form-group">
                                <label class="control-label col-md-2 text-right" for="empresa_open_id" style="padding-top: 14px;">Parceria:</label>
                                <div class="col-md-4" style="margin-top: 10px;">
                                    <select class="selectpicker" name="empresa_open_id" id="empresa_open_id" data-live-search="true" required style="margin-bottom: 14px;">
                                        <option value=''>Selecione parceiro</option>
                                        <?php
                                        if($editMode){
                                            foreach($empresas_open as $empresa){
                                                if($empresa['id'] == $chamado['empresa_open_id']){
                                                    echo "<option value='{$empresa['id']}' selected>{$empresa['nome_fantasia']}</option>";
                                                } else {
                                                    echo "<option value='{$empresa['id']}'>{$empresa['nome_fantasia']}</option>";
                                                }   
                                            }
                                        } else {
                                            foreach($empresas_open as $empresa){
                                                echo "<option value='{$empresa['id']}'>{$empresa['nome_fantasia']}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 text-right" for="empresa_close_id" style="padding-top: 14px;">Técnico:</label>
                                <div class="col-md-4" style="margin-top: 10px;">
                                    <select class="selectpicker" name="empresa_close_id" id="empresa_close_id" data-live-search="true" required style="margin-bottom: 10px;">
                                        <?php
                                        if($editMode){
                                            foreach($empresas_close as $empresa){
                                                if($empresa['id'] == $chamado['empresa_close_id']){
                                                    echo "<option value='{$empresa['id']}' selected>{$empresa['nome_fantasia']}</option>";
                                                } else {
                                                    echo "<option value='{$empresa['id']}'>{$empresa['nome_fantasia']}</option>";
                                                }   
                                            }
                                        } else {
                                            echo "<option value=''>Selecione serviço</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" style="margin-top: 14px;">
                                    <label class="control-label col-md-2 text-right" for="valor" style="padding-top: 7px;">Valor:</label>
                                    <div class="col-md-4 text-left" style="margin-bottom: 10px;">
                                    <?php 
                                    if($_SESSION['tipo_empresa_id'] == 1){
                                    ?>
                                        <input type="text" name="valor" id="valor" class="form-control" value="<?= $editMode && !empty($chamado['valor'])? $chamado['valor'].',00' : ''; ?>" placeholder="R$ 0,00" data-mask="0000,00" data-mask-reverse="true" required <?= $editMode && $_SESSION['tipo_empresa_id'] == 3 ? 'readonly' : ''?>/>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="form-group" style="margin-top: -7px;">
                                    <label class="control-label col-md-2 text-right" for="cep" style="padding-top: 7px;">Recebido:</label>
                                    <div class="col-md-4 text-left" style="margin-bottom: 10px;">
                                        <input type="text" name="valor" id="valor" class="form-control" value="<?= $editMode && !empty($chamado['valor'])? $chamado['valor'].',00' : ''; ?>" placeholder="R$ 0,00" data-mask="0000,00" data-mask-reverse="true" required <?= $editMode && $_SESSION['tipo_empresa_id'] == 3 ? 'readonly' : ''?>/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" >
                            <label class="control-label col-md-2 text-right" for="nome_cliente" style="padding-top: 14px;">Cliente:</label>
                            <div class="col-md-10" style="margin-bottom: 10px;">
                                <input type="text" name="nome_cliente" id="nome_cliente" class="form-control"value="<?= $editMode ? $chamado['nome_cliente'] : ''; ?>" placeholder="Nome do Cliente" style="margin-top: 10px;" <?= $editMode && $_SESSION['tipo_empresa_id'] == 3 ? 'readonly' : ''?> />
                            </div>
                        </div>
                        <div class="form-group" >
                            <label class="control-label col-md-2 text-right" for="data_atendimento" style="padding-top: 7px;">Data:</label>
                            <div class="col-md-4" style="margin-bottom: 10px;">
                                <input type="date" name="data_atendimento" id="data_atendimento" class="form-control"value="<?= $editMode ? $chamado['data_atendimento'] : ''; ?>" placeholder="Data atendimento" <?= $editMode && $_SESSION['tipo_empresa_id'] == 3 ? 'readonly' : ''?> required/>
                            </div>
                        </div>
                        <div class="form-group" >
                            <label class="control-label col-md-2 text-right" for="periodo_id" style="padding-top: 7px;">Período:</label>
                            <div class="col-md-4" style="margin-bottom: 10px;">
                                <select class="selectpicker" name="periodo_id" id="periodo_id" required <?= $editMode && $_SESSION['tipo_empresa_id'] == 3 ? 'readonly' : ''?> style="margin-bottom: 10px;">
                                    <option value=''>Selecione período</option>
                                    <?php
                                    if($editMode){
                                        foreach($periodos as $periodo){
                                            if($periodo['id'] == $chamado['periodo_id']){
                                                echo "<option value='{$periodo['id']}' selected>{$periodo['descricao']}</option>";
                                            } else {
                                                echo "<option value='{$periodo['id']}'>{$periodo['descricao']}</option>";
                                            }   
                                        }
                                    } else {
                                        foreach($periodos as $periodo){
                                            echo "<option value='{$periodo['id']}'>{$periodo['descricao']}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                    if($editMode &&  $_SESSION['tipo_empresa_id'] == 1){
                                ?>
                                <div class="form-group" >
                                    <label class="control-label col-md-2 text-right" for="status_id" style="padding-top: 7px;">Status:</label>
                                    <div class="col-md-4 text-left" style="margin-bottom: 10px;" >
                                        <select class="selectpicker" name="status_id" id="status_id" data-live-search="true" required <?= $editMode && $_SESSION['tipo_empresa_id'] == 3 ? 'readonly' : ''?> style="margin-bottom: 10px;">
                                            <?php
                                            if($editMode){
                                                foreach($status as $stat){
                                                    if($stat['id'] == $chamado['status_id']){
                                                        echo "<option value='{$stat['id']}' selected>{$stat['descricao']}</option>";
                                                    } else {
                                                        echo "<option value='{$stat['id']}'>{$stat['descricao']}</option>";
                                                    }   
                                                }
                                            } else {
                                                foreach($status as $stat){
                                                    echo "<option value='{$stat['id']}'>{$stat['descricao']}</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group" >
                            <label class="control-label col-md-2 text-right" for="logradouro" style="padding-top: 7px;">Logradouro:</label>
                            <div class="col-md-10" style="margin-bottom: 10px;">
                                <input type="text" name="logradouro" id="logradouro" class="form-control"value="<?= $editMode ? $chamado['logradouro'] : ''; ?>" placeholder="Endereço" required <?= $editMode && $_SESSION['tipo_empresa_id'] == 3 ? 'readonly' : ''?>/>
                            </div>
                        </div>
                        <div class="form-group" >
                            <label class="control-label col-md-2 text-right" for="state_id" style="padding-top: 7px;">Estado:</label>
                            <div class="col-md-4" style="margin-bottom: 10px;">
                                <select class="selectpicker" name="state_id" id="state_id" required data-live-search="true" <?= $editMode && $_SESSION['tipo_empresa_id'] == 3 ? 'readonly' : ''?> style="margin-bottom: 10px;">
                                    <option value=''>Selecione estado</option>
                                    <?php
                                    if($editMode){
                                        foreach($estados as $estado){
                                            if($estado['id'] == $chamado['state_id']){
                                                echo "<option value='{$estado['id']}' selected>{$estado['abbreviation']}</option>";
                                            } else {
                                                echo "<option value='{$estado['id']}'>{$estado['abbreviation']}</option>";
                                            }   
                                        }
                                    } else {
                                        foreach($estados as $estado){
                                            echo "<option value='{$estado['id']}'>{$estado['abbreviation']}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" >
                            <label class="control-label col-md-2 text-right" for="city_id" style="padding-top: 7px;">Cidade:</label>
                            <div class="col-md-4" style="margin-bottom: 10px;">
                                <select class="selectpicker" name="city_id" id="city_id" required data-live-search="true" <?= $editMode && $_SESSION['tipo_empresa_id'] == 3 ? 'readonly' : ''?> style="margin-bottom: 10px;">
                                    <?php
                                    if(!empty($cidades)){
                                        foreach($cidades as $cidade){
                                            if($cidade['id'] == $chamado['city_id']){
                                                echo "<option value='{$cidade['id']}' selected>{$cidade['name']}</option>";
                                            } else {
                                                echo "<option value='{$cidade['id']}'>{$cidade['name']}</option>";
                                            }
                                        }
                                    } else {
                                        echo "<option value=''>Selecione estado</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" >
                            <label class="control-label col-md-2 text-right" for="obs_at" style="padding-top: 7px;">Info At.:</label>
                            <div class="col-md-10" style="margin-bottom: 10px;">
                                <textarea class="form-control" id="obs_at" name="obs_at" rows="8" <?= $editMode && $_SESSION['tipo_empresa_id'] == 3 ? 'readonly' : ''?>><?=$editMode ? $chamado['obs_at'] : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row text-right">
                            <a href="chamados.php" class="btn btn-md btn-default">Voltar</a>
                            <button type="submit" class="btn btn-md btn-success .submit" style="margin-right: 15px;"><?= $editMode ? 'Confirmar ' : 'Solicitar ' ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-offset-2 col-md-8 col-md-offset-2">
        <div class="retorno text-center" style="display: none;"></div>
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
            $('.retorno').show();
            e.preventDefault();
            var dados = $(this).serialize();
            $.ajax({
                url: 'ajax/novo-editar-chamado.php',
                type: 'POST',
                data: dados
            }).done(function(data){
                var result = JSON.parse(data);
                $('.retorno').html(result.message);
                if(result.success){
                    $('#formNovoChamado')[0].reset();
                    $('#prioridade_id').val("");
                    $('#projeto_id').val("");
                    $('#servico_id').val("");
                    $('#empresa_close_id').val("");
                    $('#empresa_open_id').val("");
                    $('#periodo_id').val("");
                    $('#state_id').val("");
                    $('#city_id').val("");
                    setTimeout(function(){
                        $.unblockUI();
                        $('.retorno').hide();
                        $('.retorno').html('');
                    }, 4000);
                } else {
                    $.unblockUI();
                    setTimeout(function(){
                        $('.retorno').hide();
                    }, 2000);
                }
            });
        });
        $('#state_id').on('change', function(){
            $.ajax({
                url: 'ajax/recuperar_cidades.php',
                type: 'POST',
                data: {state_id: $(this).val()}
            }).done(function(data){
                var result          = JSON.parse(data);
                var $select_cidades = $('#city_id');
                var options         = '';
                $select_cidades.html('');
                $.each(result, function(index, value){
                    options += '<option value="' + value.id + '">' + value.name + '</option>';
                });
                $select_cidades.html(options);
                $('.selectpicker').selectpicker('refresh');
            });
        });
        $('#servico_id').on('change', function(){
            $.ajax({
                url: 'ajax/recupera-parceiros-servicos.php',
                type: 'POST',
                data: {servico_id: $(this).val()}
            }).done(function(data){
                var result                = JSON.parse(data);
                var $select_empresa_close = $('#empresa_close_id');
                var options               = '';
                $select_empresa_close.html('');
                $.each(result, function(index, value){
                    options += '<option value="' + value.id + '">' + value.nome_fantasia + '</option>';
                });
                $select_empresa_close.html(options);
                $('.selectpicker').selectpicker('refresh');
            });
        });
    });
</script>