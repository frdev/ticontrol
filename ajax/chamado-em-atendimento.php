<?php
date_default_timezone_set('America/Sao_Paulo');
# PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
# Importar Configurações
require_once '../libs/MysqliDb.php';
require      '../vendor/autoload.php';
$db = new MysqliDb();
# Recupera dados do chamado antes de atualizar
$db->where('id', $_POST['id']);
$chamado    = $db->getOne('chamados', 'numero, data_atendimento, status_id, empresa_open_id, empresa_close_id');
$db->where('id', $_POST['id']);
if($db->update('chamados', array('status_id' => 6, 'inicio_at' => date('H:m')))){
    $retorno['message'] = "<div class='alert alert-success text-center'>Iniciado atendimento do chamado com sucesso.<br><img src='img/loading.gif' /></div>";
    $retorno['success'] = true;
    if(!empty($chamado)){
        if(!empty($chamado['empresa_close_id'])){
            # Recupera dados dos e-mails
            $db->where('empresa_id', $chamado['empresa_close_id']);
            $db->orWhere('empresa_id', $chamado['empresa_open_id']);
            $db->where('status', 1);
            $usuarios = $db->get('usuarios', null, array('nome, email'));
        }
        // $mail = new PHPMailer(true);
        // try {
        //     $data  = date('d/m/Y H:m', strtotime($chamado['data_atendimento']));
        //     $body  = "<h3><strong>Em atendimento - Chamado {$chamado['numero']} - {$data}</strong></h3>";
        //     $body .= "<p>O chamado mencionado foi dado início do atendimento neste momento. Para maiores informações acesse o sistema.</p>";
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
        //     foreach($usuarios as $usuario){
        //         $mail->addAddress($usuario['email'], $usuario['nome']);
        //     }     // Add a recipient
        //     $mail->addCC('chamados@infolinn.com.br');
        //     //Content
        //     $mail->isHTML(true);                                  // Set email format to HTML
        //     $mail->Subject = 'TI Control - Infolinn - Chamado em atendimento - ' . $chamado['numero'];
        //     $mail->Body    = $body;
        //     $mail->send();
        // } catch (Exception $e) {
        //     $retorno['message'] = "<br><div class='alert alert-danger text-center'>{$mail->ErrorInfo}</div><br>";
        //     $retorno['success'] = false;
        // }
    }
} else {
    $retorno['message'] = "<div class='alert alert-danger text-center'>Erro ao realizar chamado.</div>";
    $retorno['success'] = false;
}
echo json_encode($retorno);
?>