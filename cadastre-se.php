<?php
date_default_timezone_set('America/Sao_Paulo');
# Importar Classe BD
require_once 'libs/MysqliDb.php';
# Importar Header
$db             = new MysqliDb();
$tipos_empresas = $db->get("tipos_empresas");
$servicos       = $db->get("servicos");
$editMode       = false;
$empresa        = [];

?>
<html>
    <head>
        <link rel="icon" type="imagem/png" href="img/infolinn-s-fav.png" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="content-language" content="pt-br" />
        <meta name="author" content="Felipe Ristow" />
        <meta name="copyright" content="© 2018 Felipe Ristow" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <title>TI Control ®</title>
        <meta name="description" content="Tela de login para sistema de controle e gerenciamento de chamados de TI" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap-select.min.css">
        <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body class="background">
        <br>
        <div class="container">
            <div class="row">
                <div class="col-md-offset-2 col-md-8 col-md-offset-2" id="retorno">
                    <div class="retorno text-center" style="display: none;"><strong>Aguarde, estamos efetuando o cadastro.</strong><br><img src='img/loading.gif' /><br><br></div>
                </div>
                <div class="col-md-offset-2 col-md-8 col-md-offset-2">
                    <form id="formTecnico" method="POST" action="">
                        <div class="panel panel-default" style="border-color: #000;">
                            <div class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                                <h4><i class="fa fa-building-o"></i> <strong>Dados da Empresa</strong></h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="control-label col-md-2 text-right" for="nome_fantasia" style="padding-top: 7px;">Nome F.:</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nome_fantasia" id="nome_fantasia" class="form-control" style="margin-bottom: 10px;"  placeholder="Nome Fantasia" required/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 text-right" for="razao_social" style="padding-top: 7px;">R. Social:</label>
                                    <div class="col-md-10">
                                        <input type="text" name="razao_social" id="razao_social" class="form-control" style="margin-bottom: 10px;"  placeholder="Razão Social" required/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 pull text-right" for="cnpj" style="padding-top: 7px;">CNPJ:</label>
                                    <div class="col-md-4">
                                        <input type="text" name="cnpj" id="cnpj" class="form-control" style="margin-bottom: 10px;"  placeholder="00.000.000/0000-00" required/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 pull text-right" for="ie" style="padding-top: 7px;">IE:</label>
                                    <div class="col-md-4">
                                        <input type="text" name="ie" id="ie" class="form-control" style="margin-bottom: 10px;" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 text-right" for="tipo_empresa_id" style="padding-top: 7px;">Tipo:</label>
                                    <div class="col-md-4">
                                        <input type="hidden" name="tipo_empresa_id" id="tipo_empresa_id" value="3" required>
                                        <input type="text" class="form-control" value="TÉCNICA" readonly />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 text-right" for="servicos" style="padding-top: 7px;">Serviços:</label>
                                    <div class="col-md-4">
                                        <select class="selectpicker" name="servicos[]" id="servicos" data-selected-text-format="count" multiple required>
                                            <?php
                                                foreach($servicos as $servico){
                                                    echo "<option value='{$servico['id']}'>{$servico['descricao']}</option>;";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="dados-bancarios">
                                <div id="dados-bancarios" class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                                    <h4><i class="fa fa-university"></i> <strong>Dados Bancários</strong></h4>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group" style="margin-top: 15px;">
                                        <label class="control-label col-md-2 text-right" id="banco" style="padding-top: 7px;">Banco:</label>
                                        <div class="col-md-4">
                                            <input type="text" name="banco" id="banco" class="form-control" style="margin-bottom: 10px;" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2 text-right" id="tipo_conta" style="padding-top: 7px;">Tipo:</label>
                                        <div class="col-md-4">
                                            <input type="text" name="tipo_conta" id="tipo_conta" class="form-control" style="margin-bottom: 10px;" required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2 text-right" id="agencia" style="padding-top: 7px;">Agência:</label>
                                        <div class="col-md-4">
                                            <input type="text" name="agencia" id="agencia" class="form-control" style="margin-bottom: 10px;" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2 text-right" id="conta" style="padding-top: 7px;">Conta:</label>
                                        <div class="col-md-4">
                                            <input type="text" name="conta" id="conta" class="form-control" style="margin-bottom: 10px;" required/>
                                        </div>
                                    </div>
                                </div>
                                <div id="dados-bancarios" class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                                    <h4><i class="fa fa-file-text-o"></i> <strong>Observações adicionais</strong></h4>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group" style="margin-top: 15px;">
                                        <div class="col-md-12">
                                            <textarea type="text" rows="6" name="obs_adicional" id="obs_adicional" class="form-control" placeholder="Insira informações como cidades/estados atendidos e observações adicionais"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                                <h4><i class="fa fa-user-o"></i> <strong>Dados do Técnico</strong></h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="control-label col-md-2 text-right" for="nome" style="padding-top: 7px;">Nome:</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nome" id="nome" class="form-control" style="margin-bottom: 10px;" placeholder="Nome do Técnico" required/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 text-right" for="email" style="padding-top: 7px;">E-mail:</label>
                                    <div class="col-md-10">
                                        <input type="email" name="email" id="email" class="form-control" style="margin-bottom: 10px;" placeholder="exemplo@infolinn.com" required/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 pull text-right" for="telefone" style="padding-top: 7px;">Telefone:</label>
                                    <div class="col-md-4">
                                        <input type="text" name="telefone" id="telefone" class="form-control" style="margin-bottom: 10px;" placeholder="Residencial ou celular" data-mask="(00) 00000-0000" required/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 pull text-right" for="whatsapp" style="padding-top: 7px;">WhatsApp:</label>
                                    <div class="col-md-4">
                                        <input type="text" name="whatsapp" id="whatsapp" class="form-control" placeholder="Número do WhatsApp" style="margin-bottom: 10px;" data-mask="(00) 00000-0000" required/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 text-right" for="cpf" style="padding-top: 7px;">CPF:</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="cpf" id="cpf" data-mask="000.000.000-00" placeholder="000.000.000-00" required/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 text-right" for="rg" style="padding-top: 7px;">RG/UF:</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="rg" id="rg" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <div class="row text-right">
                                    <a href="index.php" class="btn btn-md btn-default">Voltar</a>
                                    <button type="submit" class="btn btn-md btn-success" style="margin-right: 15px;">Confirmar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.mask.js"></script>
    <script type="text/javascript" src="js/jquery.blockUI.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-select.min.js"></script>
</html>
<script type="text/javascript">
    $(function(){
        $('#formTecnico').submit(function(e){
            $.blockUI();
            e.preventDefault();
            window.location.href = "#retorno";
            $('.retorno').show();
            var dados = $(this).serialize();
            $.ajax({
                url: 'ajax/cadastro-novo-tecnico.php',
                type: 'POST',
                data: dados
            }).done(function(data){
                var result = JSON.parse(data);
                $('.retorno').html(result.message);
                if(result.success){
                    setTimeout(function(){
                        $.unblockUI();
                        window.location.href = 'index.php';
                    }, 2000);
                } else {
                    $.unblockUI();
                    setTimeout(function(){
                        $('.retorno').html('');
                        $('.retorno').hide();
                    }, 2000);
                }
            });
        });
        $('#cpf').blur(function(){
            if(!valida_cpf($(this).val())){
                if($(this).val() != ''){
                    alert("CPF inválido.")
                    $('#cpf').val('');
                    $('#cpf').focus();
                }   
            }
        });
        function valida_cpf(valor) {
            valor = valor.toString();
            valor = valor.replace(/[^0-9]/g, '');
            var digitos = valor.substr(0, 9);
            var novo_cpf = calc_digitos_posicoes( digitos );
            var novo_cpf = calc_digitos_posicoes( novo_cpf, 11 );
            if ( novo_cpf === valor ) {
                return true;
            } else {
                return false;
            }
        }
        function calc_digitos_posicoes( digitos, posicoes = 10, soma_digitos = 0 ) {
            digitos = digitos.toString();
            for ( var i = 0; i < digitos.length; i++  ) {
                soma_digitos = soma_digitos + ( digitos[i] * posicoes );
                posicoes--;
                if ( posicoes < 2 ) {
                    posicoes = 9;
                }
            }
            soma_digitos = soma_digitos % 11;
            if ( soma_digitos < 2 ) {
                soma_digitos = 0;
            } else {
                soma_digitos = 11 - soma_digitos;
            }
            var cpf = digitos + soma_digitos;
            return cpf;
        }
    });
</script>