<?php
# PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
# Importar Configurações
require_once '../libs/MysqliDb.php';
require      '../vendor/autoload.php';
$db = new MysqliDb();
# Recupera dados do chamado antes de atualizar
$db->where('id', $_POST['id']);
$chamado  = $db->getOne('chamados', 'numero, data_atendimento, status_id, empresa_close_id');
$db->where('id', $_POST['id']);
if($db->update('chamados', array('status_id' => 5))){
    $retorno['message'] = "<div class='alert alert-success text-center'>Chamado cancelado com sucesso.<br><img src='img/loading.gif' /></div>";
    $retorno['success'] = true;
    if(!empty($chamado)){
        if(!empty($chamado['empresa_close_id'])){
            # Recupera dados dos e-mails
            $db->where('empresa_id', $chamado['empresa_close_id']);
            $db->where('status', 1);
            $usuarios = $db->get('usuarios', null, array('nome, email'));
        }
        $mail = new PHPMailer(true);
        try {
            $data  = date('d/m/Y', strtotime($chamado['data_atendimento']));
            $body  = "<h3><strong>Cancelamento - Chamado {$chamado['numero']} - {$data}</strong></h3>";
            $body .= "<p>O chamado mencionado foi cancelado. Para maiores informações acesse o sistema.</p>";
            $body .= "<p>Área de login: <a href='http://os.infolinn.com.br/'>[Link]</a></p>";
            $body .= "<p>----------------------------------------------------------------------------------</p>";
            $body .= "<p>Este é um e-mail de aviso automático, por gentileza, não responda!</p>";
            $body .= "<p>----------------------------------------------------------------------------------</p>";
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'a2plcpnl0260.prod.iad2.secureserver.net';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'wvdc7erhikrf';                 // SMTP username
            $mail->Password = 'Dime@001508';                           // SMTP password
            $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;                                    // TCP port to connect to
            //Recipients
            $mail->setFrom('no-reply@infolinn.com', 'No-reply');
            if($chamado['status_id'] != 1 && !empty($usuarios)){
                foreach($usuarios as $usuario){
                    $mail->addAddress($usuario['email'], $usuario['nome']);
                }     // Add a recipient
                $mail->addCC('felipertw2@gmail.com');
            } else {
                $mail->addAddress('felipertw2@gmail.com');
            }
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'IT Control - Cancelamento ' . $chamado['numero'];
            $mail->Body    = $body;
            $mail->send();
        } catch (Exception $e) {
            $retorno['message'] = "<br><div class='alert alert-danger text-center'>{$mail->ErrorInfo}</div><br>";
            $retorno['success'] = false;
        }
    }
} else {
    $retorno['message'] = "<div class='alert alert-danger text-center'>Erro ao cancelar chamado.</div>";
    $retorno['success'] = false;
}
echo json_encode($retorno);
?>