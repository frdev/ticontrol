<?php
# PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
# Importar Configurações
require_once '../libs/MysqliDb.php';
require      '../vendor/autoload.php';
# Recuperando os dados via POST
$dados = $_POST;
$db    = new MysqliDb();
$db->where('cnpj', $dados['cnpj']);
$existEmpresa = $db->getOne('empresas');
if(empty($existEmpresa)){ # Caso não exista, segue para atualização/cadastra
    $cpf = trim($dados['cpf']);
    $cpf = str_replace(".", "", $cpf);
    $cpf = str_replace(",", "", $cpf);
    $cpf = str_replace("-", "", $cpf);
    $cpf = str_replace("/", "", $cpf);
    $db->where('login', $cpf);
    $existUser = $db->getOne('usuarios');
    if(empty($existUser)){
        $empresa = array(
            'tipo_empresa_id' => $dados['tipo_empresa_id'],
            'nome_fantasia'   => $dados['nome_fantasia'],
            'razao_social'    => $dados['razao_social'],
            'cnpj'            => $dados['cnpj'],
            'ie'              => $dados['ie'],
            'banco'           => $dados['banco'],
            'tipo_conta'      => $dados['tipo_conta'],
            'agencia'         => $dados['agencia'],
            'conta'           => $dados['conta'],
            'obs_adicional'   => $dados['obs_adicional']
        );
        $empresaId          = $db->insert('empresas', $empresa);

        $usuario = array(
            'nivel_acesso_id' => 2,
            'empresa_id'      => $empresaId,
            'nome'            => $dados['nome'],
            'login'           => $cpf,
            'senha'           => md5('mudar123'),
            'email'           => $dados['email'],
            'telefone'        => $dados['telefone'],
            'whatsapp'        => $dados['whatsapp'],
            'cpf'             => $dados['cpf'],
            'rg'              => $dados['rg']
        );
        $usuarioId          = $db->insert('usuarios', $usuario);
        $retorno['message'] = "<div class='alert alert-success text-center'>Empresa habilitada com sucesso.<br><img src='img/loading.gif' /></div><br>";
        $retorno['success'] = true;
        foreach($dados['servicos'] as $servico){
            $db->insert('empresas_servicos', array('empresa_id' => $empresaId, 'servico_id' => $servico));
        }
        $mail = new PHPMailer(true);
        try {
            $body  = "<h3><strong>TI Control - Infolinn - Cadastro Efetivado</h3>";
            $body .= "<p>Seu cadastro no sistema TI Control - Infolinn foi efetivado com sucesso.</p>
            ";
            $body .= "<p><strong>Usuário:</strong> {$cpf}</p>";
            $body .= "<p><strong>Senha:</strong> mudar123</p>";
            $body .= "<p>Área de login: <a href='http://frdevpro.com/'>[Link]</a></p>";
            $body .= "<p>----------------------------------------------------------------------------------</p>";
            $body .= "<p>Este é um e-mail de aviso automático, por gentileza, não responda!</p>";
            $body .= "<p>----------------------------------------------------------------------------------</p>";
            //Server settings
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'a2plcpnl0260.prod.iad2.secureserver.net';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'wvdc7erhikrf';                 // SMTP username
            $mail->Password = 'Dime@001508';                           // SMTP password
            $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;                                    // TCP port to connect to
            //Recipients
            $mail->setFrom('no-reply@frdevpro.com', 'No-reply');
            $mail->addAddress($dados['email'], $usuario['nome']);
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'IT Control - Cadastro Efetivado';
            $mail->Body    = $body;
            $mail->send();
        } catch (Exception $e) {
            $retorno['message'] = "<br><div class='alert alert-danger text-center'>{$mail->ErrorInfo}</div><br>";
            $retorno['success'] = false;
        }
    } else {
        # Retorno caso o usuário já exista na base de dados
        $retorno['message'] = "<br><div class='alert alert-danger text-center'>CPF já existente, por gentileza, altere para outro.</div><br>";
        $retorno['success'] = false;
    }
} else { # Caso exista, informa mensagem de error
    # Retorno caso a empresa já exista na base de dados
    $retorno['message'] = "<br><div class='alert alert-danger text-center'>CNPJ já existente, por gentileza, altere para outro.</div><br>";
    $retorno['success'] = false;
}

echo json_encode($retorno);

?>