<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
# Importar Classe BD
require_once 'libs/MysqliDb.php';
# Importa Funções Session
require_once 'functions/session.php';
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="content-language" content="pt-br" />
        <meta name="author" content="Felipe Ristow" />
        <meta name="copyright" content="© 2018 Felipe Ristow" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta name="description" content="Tela de login para sistema de controle e gerenciamento de chamados de TI" />
        <title>IT Control ®</title>
        <link rel="icon" type="imagem/png" href="img/fvicon.png" />
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap-select.min.css">
        <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    </head>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="chamados.php"><img src="img/itcontrol.png" style="margin-top: -12px;" /></a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <?php
                            if($_SESSION['nivel_acesso_id'] == 1){
                        ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog" aria-hidden="true"></i> Administração <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="usuarios.php"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Usuários</a></li>
                                <li><a href="empresas.php"> <i class="fa fa-building-o" aria-hidden="true"></i> Empresas</a></li>
                                <li><a href="projetos.php"><i class="fa fa-laptop" aria-hidden="true"></i> Projetos</a></li>
                                <li><a href="efetuar-pagamentos.php"><i class="fa fa-money" aria-hidden="true"></i> Efetuar Pagamentos - Técnicos</a></li>
                            </ul>
                        </li>
                        <?php
                            }
                        ?>
                        <li><a href="chamados.php"><i class="fa fa-clipboard"></i> Chamados</a></li>
                        <?php
                        if($_SESSION['tipo_empresa_id'] != 2){
                        ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-money" aria-hidden="true"></i> Faturamento <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="faturamento.php"><i class="fa fa-info-circle" aria-hidden="true"></i> Informativo</a></li>
                                <li><a href="chamados-faturados.php"><i class="fa fa-usd" aria-hidden="true"></i> Faturados</a></li>
                            </ul>
                        </li>
                        <?php
                        }
                        ?>
                        <li><a href="chamados.php" target="_blank"><i class="fa fa-file-archive-o" aria-hidden="true"></i> RAT's e Manuais</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#"><i class="fa fa-user-circle-o" aria-hidden="true"></i> <?= $_SESSION['login']; ?></a></li>
                        <li><a href="alterar_senha.php"><i class="fa fa-id-card" aria-hidden="true"></i> Alterar Senha</a></li>
                        <li><a href="logout.php"><i class="fa fa-times" aria-hidden="true"></i> Sair</a></li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <br><br><br>