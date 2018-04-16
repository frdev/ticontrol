<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="content-language" content="pt-br" />
        <meta name="author" content="Felipe Ristow" />
        <meta name="copyright" content="© 2018 Felipe Ristow" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <link rel="icon" type="imagem/png" href="img/fvicon.png" />
        <title>IT Control ®</title>
        <meta name="description" content="Tela de login para sistema de controle e gerenciamento de chamados de TI" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body class="background">
        <div class="container tela-login">
            <div class="row">
                <div class="col-md-offset-4 col-md-4 col-md-offset-4 text-center">
                    <img src="img/logo.png" style="width: 100%; height: 37.5%;"/>
                </div>
                <form method="POST" action="#" id="formLogin">
                    <div class="col-md-offset-4 col-md-4 col-md-offset-4 painel-login">
                        <div class="panel panel-default" style="border-color: #000;">
                            <div class="panel-heading" style="color: #fff; background-color: #000; border-color: #000;">
                                <span class="glyphicon glyphicon-log-in"></span> Login
                            </div>
                            <div class="panel-body">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-user-circle-o" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control" placeholder="Usuário" aria-describedby="basic-addon1" name="usuario" id="usuario" required>
                                </div>
                                <br>
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-id-card-o" aria-hidden="true"></i></span>
                                    <input type="password" class="form-control" placeholder="****" aria-describedby="basic-addon1" name="senha" id="senha" required>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <div class="row">
                                    <div>
                                        <a href="cadastre-se.php" class="btn btn-md btn-success pull-left" target="_blank" style="margin-left: 15px;">Seja um técnico</a>
                                        <button type="submit" class="btn btn-md btn-primary login pull-right" style="margin-right: 15px;">Entrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-offset-md-4 col-md-4 col-md-offset-4">
                    <div class="retorno text-center">
                        <?php
                            if(isset($_GET['mensagem']) && isset($_GET['tipo'])){
                        ?>
                                <div class="alert alert-<?= $_GET['tipo']; ?>"><?= $_GET['mensagem']; ?></div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.blockUI.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(function(){
            $('#formLogin').submit(function(e){
                e.preventDefault();
                var dados = $(this).serialize();
                $.blockUI();
                $.ajax({
                    url: 'ajax/validar-login.php',
                    type: 'POST',
                    data: dados
                }).done(function(data){
                    var result = JSON.parse(data);
                    $('.retorno').html(result.message);
                    if(result.success){
                        setTimeout(function(){
                            $.unblockUI();
                            window.location.href = "chamados.php";
                        }, 2000);
                    } else {
                        $.unblockUI();
                        setTimeout(function(){
                            $('.retorno').html('');
                        }, 2500);
                    }
                });
            });
        });
    </script>
</html>