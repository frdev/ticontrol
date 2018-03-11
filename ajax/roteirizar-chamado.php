<?php
# PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
# Importar Configurações
require_once '../libs/MysqliDb.php';
require      '../vendor/autoload.php';
$db      = new MysqliDb();
$dados   = $_POST;
$chamado = array(
    'empresa_close_id' => $dados['empresa_close_id'],
    'status_id'        => 2,
    'valor'            => floatval($dados['valor'])
);
$db->where('id', $dados['id']);
if($db->update('chamados', $chamado)){
    $retorno['message'] = "<br><div class='alert alert-success text-center'>Chamado roteirizado com sucesso.<br> <img src='img/loading.gif' /></div><br>";
    $retorno['success'] = true;
    $db->where('empresa_id', $dados['empresa_close_id']);
    $db->where('status', 1);
    $usuarios = $db->get('usuarios', null, array('nome, email'));
    $db->where('id', $dados['id']);
    $chamado  = $db->getOne('chamados');
    // if(!empty($usuarios) && !empty($chamado)){
    //     $mail = new PHPMailer(true);
    //     try {
    //         $data  = date('d/m/Y', strtotime($chamado['data_atendimento']));
    //         $body  = "<h3><strong>Acionamento - Chamado {$chamado['numero']} - {$data}</strong></h3>";
    //         $body .= "<p>Sua empresa recebeu um acionamento para um atendimento técnico. Por gentileza, entre no sistema para verificar maiores informações.</p>";
    //         $body .= "<p>Área de login: <a href='http://os.infolinn.com.br/'>[Link]</a></p>";
    //         $body .= "<p>----------------------------------------------------------------------------------</p>";
    //         $body .= "<p>Este é um e-mail de aviso automático, por gentileza, não responda!</p>";
    //         $body .= "<p>----------------------------------------------------------------------------------</p>";
    //         //Server settings
    //         $mail->isSMTP();                                      // Set mailer to use SMTP
    //         $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    //         $mail->SMTPAuth = true;                               // Enable SMTP authentication
    //         $mail->Username = 'no-reply@infolinn.com.br';                 // SMTP username
    //         $mail->Password = 'info@2017';                           // SMTP password
    //         // $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    //         $mail->Port = 587;                                    // TCP port to connect to
    //         //Recipients
    //         $mail->setFrom('no-reply@infolinn.com', 'No-reply');
    //         foreach($usuarios as $usuario){
    //             $mail->addAddress($usuario['email'], $usuario['nome']);
    //         }     // Add a recipient
    //         $mail->addCC('chamados@infolinn.com.br');
    //         //Content
    //         $mail->isHTML(true);                                  // Set email format to HTML
    //         $mail->Subject = 'TI Control - Infolinn - Acionamento - ' . $chamado['numero'];
    //         $mail->Body    = $body;
    //         $mail->send();
    //     } catch (Exception $e) {
    //         $retorno['message'] = "<br><div class='alert alert-danger text-center'>{$mail->ErrorInfo}</div><br>";
    //         $retorno['success'] = false;
    //     }
    // }
} else {
    $retorno['message'] = "<br><div class='alert alert-danger text-center'>Erro ao roteirizar chamado.</div><br>";
    $retorno['success'] = false;
}

echo json_encode($retorno);
?>