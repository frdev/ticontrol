<?php
# PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
# Importar Configurações
require_once '../libs/MysqliDb.php';
require      '../vendor/autoload.php';
# Recuperando os dados via POST
$dados = $_POST;
# Valida se as senhas estão iguais
if(($dados['senha'] != $dados['conf_senha']) && !empty($dados['senha']) && !empty($dados['conf_senha'])){
    $retorno['message'] = "<br><div class='alert alert-danger text-center'>Os campos de senha estão diferentes, por gentileza, digite a mesma senha.</div><br>";
    $retorno['success'] = false;
    echo json_encode($retorno);
    exit();
}
$db   = new MysqliDb();
$db->where('login', $dados['login']);
if(!empty($dados['id'])){
   $db->where('id', $dados['id'], '!=');
}
$existUser = $db->getOne('usuarios');
if(empty($existUser)){ # Caso não exista, segue para atualização/cadastro
    $user = array(
        'nivel_acesso_id' => $dados['nivel_acesso_id'],
        'cpf'             => $dados['cpf'],
        'rg'              => $dados['rg'],
        'empresa_id'      => $dados['empresa_id'],
        'nome'            => $dados['nome'],
        'email'           => $dados['email'],
        'login'           => $dados['login'],
        'telefone'        => $dados['telefone'],
        'whatsapp'        => $dados['whatsapp'],
        'senha'           => md5($dados['senha'])
    );
    if(!empty($dados['id'])){
        if(empty($dados['senha'])) { # Realiza UPDATE sem a senha
            $user = array(
                'nivel_acesso_id' => $dados['nivel_acesso_id'],
                'empresa_id'      => $dados['empresa_id'],
                'cpf'             => $dados['cpf'],
                'rg'              => $dados['rg'],
                'nome'            => $dados['nome'],
                'email'           => $dados['email'],
                'telefone'        => $dados['telefone'],
                'whatsapp'        => $dados['whatsapp'],
                'login'           => $dados['login']
            );
        }
        $db->where('id', $dados['id']);
        if($db->update('usuarios', $user)){
            $retorno['message'] = "<br><div class='alert alert-success text-center'>Usuário alterado com sucesso. <br><img src='img/loading.gif' /></div><br>";
            $retorno['success'] = true;
        } else {
            $retorno['message'] = "<br><div class='alert alert-danger text-center'>Erro ao atualizar usuário.</div><br>";
            $retorno['success'] = false;
        }
    } else {
        # Realiza o INSERT
        $userId = $db->insert('usuarios', $user);
        if($userId){
            $retorno['message'] = "<br><div class='alert alert-success text-center'>Usuário inserido com sucesso. <br><img src='img/loading.gif' /></div><br>";
            $retorno['success'] = true;
            // $mail = new PHPMailer(true);
            // try {
            //     $body  = "<h3><strong>Dados de acesso</strong></h3>";
            //     $body .= "<p><strong>Login:</strong> {$dados['login']}</p>";
            //     $body .= "<p><strong>Senha:</strong> {$dados['senha']}</p>";
            //     $body .= "<p>Área de login: <a href='http://os.infolinn.com.br/'>[Link]</a></p>";
            //     $body .= "<p>----------------------------------------------------------------------------------</p>";
            //     $body .= "<p>Este é um e-mail de aviso automático, por gentileza, não responda!</p>";
            //     $body .= "<p>----------------------------------------------------------------------------------</p>";
            //     //Server settings
            //     $mail->isSMTP();                                      // Set mailer to use SMTP
            //     $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            //     $mail->SMTPAuth = true;                               // Enable SMTP authentication
            //     $mail->Username = 'no-reply@infolinn.com.br';                 // SMTP username
            //     $mail->Password = 'info@2017';                           // SMTP password
            //     // $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            //     $mail->Port = 587;                                    // TCP port to connect to
            //     //Recipients
            //     $mail->setFrom('no-reply@infolinn.com', 'No-reply');
            //     $mail->addAddress($dados['email'], $dados['nome']);     // Add a recipient
            //     //Content
            //     $mail->isHTML(true);                                  // Set email format to HTML
            //     $mail->Subject = 'TI Control - Infolinn - Cadastro';
            //     $mail->Body    = $body;
            //     $mail->send();
            // } catch (Exception $e) {
            //     $retorno['message'] = "<br><div class='alert alert-danger text-center'>{$mail->ErrorInfo}</div><br>";
            //     $retorno['success'] = false;
            // }
        } else {
            $retorno['message'] = "<br><div class='alert alert-danger text-center'>Erro ao inserir usuário.</div><br>";
            $retorno['success'] = false;
        }
    }
} else { # Caso exista, informa mensagem de error
    # Retorno caso o usuário já exista na base de dados
    $retorno['message'] = "<br><div class='alert alert-danger text-center'>Login de usuário já existente, por gentileza, altere para outro.</div><br>";
    $retorno['success'] = false;
}

echo json_encode($retorno);

?>