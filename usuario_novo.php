<?php
# Importar Header
include_once 'includes/header.php';
if($_SESSION['tipo_empresa_id'] != 1 && $_SESSION['nivel_acesso_id'] != 1){ session_destroy(); header("Location: index.php?mensagem=Usuário sem permissão, realize login novamente.&tipo=danger"); }

$db       = new MysqliDb();
$niveis   = $db->get("niveis_acessos");
$empresas = $db->get("empresas");
$editMode = false;
$user     = [];
if(isset($_GET['id'])){
    $editMode = true;
    $user = $db->where("id", $_GET['id']);
    $user = $db->getOne("usuarios");
    if(empty($user)){
        header("Location: usuarios.php");
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-offset-2 col-md-8 col-md-offset-2">
            <div class="retorno"></div>
            <form id="formNovoUsuario" method="POST" action="">
                <div class="panel panel-default" style="border-color: #000;">
                    <div class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                        <h4><i class="fa fa-user<?= empty($user) ? '-plus' : ''; ?>"></i> <strong><?= $editMode ? 'Edição de ' : 'Novo ' ?> Usuário</strong></h4>
                    </div>
                    <div class="panel-body">
                        <input type="hidden" name="id" id="id" value="<?= !empty($user) ? $user['id'] : ''; ?>">
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" id="nome" style="padding-top: 7px;">Nome:</label>
                            <div class="col-md-10">
                                <input type="text" name="nome" id="nome" class="form-control" style="margin-bottom: 10px;" value="<?= !empty($user) ? $user['nome'] : ''; ?>" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" id="email" style="padding-top: 7px;">Email:</label>
                            <div class="col-md-10">
                                <input type="email" name="email" id="email" class="form-control" style="margin-bottom: 10px;" value="<?= !empty($user) ? $user['email'] : ''; ?>" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" id="telefone" style="padding-top: 7px;">Telefone:</label>
                            <div class="col-md-4">
                                <input type="text" name="telefone" id="telefone" class="form-control" style="margin-bottom: 10px;" value="<?= !empty($user) ? $user['telefone'] : ''; ?>" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" id="whatsapp" style="padding-top: 7px;">WhatsApp:</label>
                            <div class="col-md-4">
                                <input type="text" name="whatsapp" id="whatsapp" class="form-control" style="margin-bottom: 10px;" value="<?= !empty($user) ? $user['whatsapp'] : ''; ?>" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" id="cpf" style="padding-top: 7px;">CPF:</label>
                            <div class="col-md-4">
                                <input type="text" name="cpf" id="cpf" class="form-control" style="margin-bottom: 10px;" value="<?= !empty($user) ? $user['cpf'] : ''; ?>" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" id="rg" style="padding-top: 7px;">RG/UF:</label>
                            <div class="col-md-4">
                                <input type="text" name="rg" id="rg" class="form-control" style="margin-bottom: 10px;" value="<?= !empty($user) ? $user['rg'] : ''; ?>" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 pull text-right" id="nivel_acesso_id" style="padding-top: 7px;">Acesso:</label>
                            <div class="col-md-4">
                                <select class="form-control" style="margin-bottom: 10px;" name="nivel_acesso_id" id="nivel_acesso_id" required>
                                    <option value="">Selecione acesso</option>
                                    <?php
                                        foreach($niveis as $nivel){
                                            if(!empty($user) && $user['nivel_acesso_id'] == $nivel['id']){
                                                echo "<option value='{$nivel['id']}' selected>{$nivel['descricao']}</option>'";
                                            } else {
                                                echo "<option value='{$nivel['id']}'>{$nivel['descricao']}</option>'";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 pull text-right" id="empresa_id" style="padding-top: 7px;">Empresa:</label>
                            <div class="col-md-4">
                                <select class="form-control" style="margin-bottom: 10px;" name="empresa_id" id="empresa_id" required>
                                    <option value="">Selecione empresa</option>
                                    <?php
                                        foreach($empresas as $empresa){
                                            if(!empty($user) && $user['empresa_id'] == $empresa['id']){
                                                echo "<option value='{$empresa['id']}' selected>{$empresa['nome_fantasia']}</option>'";
                                            } else {
                                                echo "<option value='{$empresa['id']}'>{$empresa['nome_fantasia']}</option>'";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" id="login" style="padding-top: 7px;">Usuário:</label>
                            <div class="col-md-10">
                                <input type="text" name="login" id="login" class="form-control" style="margin-bottom: 10px;" value="<?= !empty($user) ? $user['login'] : ''; ?>" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" id="senha" style="padding-top: 7px;">Senha:</label>
                            <div class="col-md-4">
                                <input type="password" name="senha" id="senha" class="form-control" style="margin-bottom: 10px;"  <?= $editMode ? '' : 'required'; ?>/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 text-right" id="conf_senha" style="padding-top: 7px;" <?= $editMode ? '' : 'required'; ?>>Confirmar:</label>
                            <div class="col-md-4">
                                <input type="password" name="conf_senha" id="conf_senha" class="form-control" style="margin-bottom: 10px;" />
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row text-right">
                            <a href="usuarios.php" class="btn btn-md btn-default">Voltar</a>
                            <button type="submit" class="btn btn-md btn-success" style="margin-right: 15px;"><?= $editMode ? 'Confirmar ' : 'Cadastrar ' ?></button>
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
        $('#formNovoUsuario').submit(function(e){
            e.preventDefault();
            var dados = $(this).serialize();
            $.blockUI();
            $.ajax({
                url: 'ajax/novo-editar-usuario.php',
                type: 'POST',
                data: dados
            }).done(function(data){
                var result = JSON.parse(data);
                if(result.success){
                    setTimeout(function(){
                        window.location.href = 'usuarios.php';
                    }, 2000);
                    $.unblockUI();
                } else {
                    setTimeout(function(){
                        $('.retorno').html('');
                    }, 2000);
                    $.unblockUI();
                }
            });
        });
    });
</script>