<?php
# PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
# Importar Configurações
require_once '../libs/MysqliDb.php';
require      '../vendor/autoload.php';
$dados = $_POST;
$db    = new MysqliDb();
$db->where('id', $dados['id']);
$chamado = $db->getOne('chamados');
# Valida se os arquivos files sao compativeis
if(isset($_FILES) && $_FILES['rat_fechamento']['size'] > 0 && $_FILES['foto1']['size'] > 0 && $_FILES['foto2']['size'] > 0){
    $extensoes_aceitas   = array('pdf' , 'png', 'jpeg', 'jpg');
    $extensao_fechamento = explode('.', $_FILES['rat_fechamento']['name']);
    $extensao_fechamento = strtolower(end($extensao_fechamento));
    $extensao_foto1      = explode('.', $_FILES['foto1']['name']);
    $extensao_foto1      = strtolower(end($extensao_foto1));
    $extensao_foto2      = explode('.', $_FILES['foto2']['name']);
    $extensao_foto2      = strtolower(end($extensao_foto2));
    if(array_search($extensao_fechamento, $extensoes_aceitas) || array_search($extensao_foto1, $extensoes_aceitas) || array_search($extensao_foto2, $extensoes_aceitas)){
        $arq_fechamento = $chamado['numero'].'-fechamento'.'.'.$extensao_fechamento;
        $arq_foto1      = $chamado['numero'].'-foto1'.'.'.$extensao_foto1;
        $arq_foto2      = $chamado['numero'].'-foto2'.'.'.$extensao_foto2;
        if(move_uploaded_file($_FILES['rat_fechamento']['tmp_name'], '../fechamentos/'.$arq_fechamento) && move_uploaded_file($_FILES['foto1']['tmp_name'], '../fechamentos/'.$arq_foto1) && move_uploaded_file($_FILES['foto2']['tmp_name'], '../fechamentos/'.$arq_foto2)){
            $dados_chamado = array(
                'obs_close'      => $dados['obs_close'],
                'inicio_at'      => $dados['inicio_at'],
                'fim_at'         => $dados['fim_at'],
                'status_id'      => 3,
                'rat_fechamento' => $arq_fechamento,
                'foto1'          => $arq_foto1,
                'foto2'          => $arq_foto2
            );
            $db->where('id', $dados['id']);
            if($db->update('chamados', $dados_chamado)){
                $retorno['message'] = "<div class='alert alert-success text-center'>Chamado fechado com sucesso, aguarde o redirecionamento!<br><img src='img/loading.gif' /></div>";
                $retorno['success'] = true;
                # Recupera dados dos e-mails
                $db->where('id', $dados['id']);
                $db->where('empresa_id', $chamado['empresa_open_id']);
                $db->orWhere('empresa_id', $chamado['empresa_close_id']);
                $usuarios  = $db->get('usuarios', null, array('nome, email'));
                // if(!empty($chamado)){
                //     $mail = new PHPMailer(true);
                //     try {
                //         $data  = date('d/m/Y', strtotime($chamado['data_atendimento']));
                //         $body  = "<h3><strong>Fechamento - Chamado {$chamado['numero']} - {$data}</strong></h3>";
                //         $body .= "<p><strong>Horário de atendimento realizado:</strong> {$dados['inicio_at']} às {$dados['fim_at']}</p>";
                //         $body .= "<p><strong>Resumo Fechamento:</strong> {$dados['obs_close']}</p>";
                //         $body .= "<p>O chamado foi atendido e encontra-se finalizado.</p>";
                //         $body .= "<p>Área de login: Clique <a href='http://os.infolinn.com.br/'>aqui</a></p>";
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
                //         if(!empty($usuarios)){
                //             foreach($usuarios as $usuario){
                //                 $mail->addAddress($usuario['email'], $usuario['nome']);
                //             }     // Add a recipient
                //         }
                //         $mail->addCC('chamados@infolinn.com.br');
                //         //Content
                //         $mail->isHTML(true);                                  // Set email format to HTML
                //         $mail->Subject = 'TI Control - Infolinn - Fechamento - ' . $chamado['numero'];
                //         $mail->Body    = $body;
                //         $mail->addAttachment('../fechamentos/'.$arq_fechamento);
                //         $mail->addAttachment('../fechamentos/'.$arq_foto1);
                //         $mail->addAttachment('../fechamentos/'.$arq_foto2);
                //         $mail->send();
                //     } catch (Exception $e) {
                //         $retorno['message'] = "<br><div class='alert alert-danger text-center'>{$mail->ErrorInfo}</div><br>";
                //         $retorno['success'] = false;
                //     }
                // }
            } else {
                $retorno['message'] = "<div class='alert alert-danger text-center'>Erro ao atualizar chamado.</div>";
                $retorno['success'] = false;
            }
        } else {
            $retorno['message'] = "<div class='alert alert-danger text-center'>Erro ao realizar upload das fotos.</div>";
            $retorno['success'] = false;
        }
    } else {
        $retorno['message'] = "<div class='alert alert-danger text-center'>Extensões aceitas, pdf, png, jpeg e jpg. Insira um formato aceito.</div>";
        $retorno['success'] = false;
    }
} else {
    $retorno['message'] = "<div class='alert alert-danger text-center'>Arquivo inválido, insira novamente.</div>";
    $retorno['success'] = false;
}
echo json_encode($retorno);
?>