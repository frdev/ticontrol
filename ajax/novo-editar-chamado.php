<?php
# PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
# Importar Configurações
require_once '../libs/MysqliDb.php';
require      '../vendor/autoload.php';
session_start();
$db    = new MysqliDb();
$dados = $_POST;

# Valida se o chamado já existe
$db->where('numero', $dados['numero'], 'like');
if($_SESSION['tipo_empresa_id'] == 1){
    $db->where('empresa_open_id', $dados['empresa_close_id']);
} else {
    $db->where('empresa_open_id', $_SESSION['empresa_id']);
}
if(!empty($dados['id'])){
    $db->where('id', $dados['id'], '!=');
}
$existChamado = $db->getOne('chamados');
if($existChamado){
    $retorno['message'] = "<br><div class='alert alert-success text-center'>Chamado já existente.</div><br>";
    $retorno['success'] = false;
    echo json_encode($retorno);
}

$dados['status_id'] = !empty($dados['id']) ? $dados['status_id'] : 1; 

# Atualiza
if(!empty($dados['id'])){
    if($_SESSION['tipo_empresa_id'] == 1){
        $chamado = array(
            'numero'           => $dados['numero'],
            'empresa_open_id'  => $dados['empresa_open_id'],
            'empresa_close_id' => $dados['empresa_close_id'],
            'projeto_id'       => $dados['projeto_id'],
            'prioridade_id'    => $dados['prioridade_id'],
            'servico_id'       => $dados['servico_id'],
            'status_id'        => $dados['status_id'],
            'valor'            => intval($dados['valor']),
            'nome_cliente'     => $dados['nome_cliente'],
            'data_atendimento' => $dados['data_atendimento'],
            'periodo_id'       => $dados['periodo_id'],
            'logradouro'       => $dados['logradouro'],
            'state_id'         => $dados['state_id'],
            'city_id'          => $dados['city_id'],
            'obs_at'           => $dados['obs_at']
        );
    } else {
        $chamado = array(
            'numero'           => $dados['numero'],
            'projeto_id'       => $dados['projeto_id'],
            'prioridade_id'    => $dados['prioridade_id'],
            'servico_id'       => $dados['servico_id'],
            'status_id'        => $dados['status_id'],
            'nome_cliente'     => $dados['nome_cliente'],
            'data_atendimento' => $dados['data_atendimento'],
            'periodo_id'       => $dados['periodo_id'],
            'logradouro'       => $dados['logradouro'],
            'state_id'         => $dados['state_id'],
            'city_id'          => $dados['city_id'],
            'obs_at'           => $dados['obs_at']
        );
    }
    $db->where('id', $dados['id']);
    if($db->update('chamados', $chamado)){
        $retorno['message'] = "<br><div class='alert alert-success text-center'>Chamado atualizado com sucesso.<br><img src='img/loading.gif' /></div><br>";
        $retorno['success'] = true;
        // $mail = new PHPMailer(true);
        // try {
        //     $data  = date('d/m/Y', strtotime($chamado['data_atendimento']));
        //     $body  = "<h3><strong>Edição - Chamado {$chamado['numero']} - {$data}</strong></h3>";
        //     $body .= "<p>O chamado mencionado foi editado. Para maiores informações acesse o sistema.</p>";
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
        //     if($chamado['status_id'] != 1 && $chamado['status_id'] != 4){
        //         $db->where('id', $dados['id']);
        //         $c = $db->getOne('chamados');
        //         $db->where('empresa_id', $c['empresa_open_id']);
        //         $db->orWhere('empresa_id', $c['empresa_close_id']);
        //         $db->where('status', 1);
        //         $usuarios = $db->get('usuarios');
        //         foreach($usuarios as $usuario){
        //             $mail->addAddress($usuario['email'], $usuario['nome']);
        //         }     // Add a recipient
        //         $mail->addCC('chamados@infolinn.com.br');
        //     } else {
        //         $mail->addAddress('chamados@infolinn.com.br');
        //     }
        //     //Content
        //     $mail->isHTML(true);                                  // Set email format to HTML
        //     $mail->Subject = 'TI Control - Infolinn - Edicao de Chamado - ' . $dados['numero'];
        //     $mail->Body    = $body;
        //     $mail->send();
        // } catch (Exception $e) {
        //     $retorno['message'] = "<br><div class='alert alert-danger text-center'>{$mail->ErrorInfo}</div><br>";
        //     $retorno['success'] = false;
        // }
    } else {
        $retorno['message'] = "<br><div class='alert alert-danger text-center'>Erro ao atualizar chamado.</div><br>";
        $retorno['success'] = false;
    }
# Cadastra
} else {
    if($_SESSION['tipo_empresa_id'] == 1){
        $chamado = array(
            'numero'           => $dados['numero'],
            'projeto_id'       => $dados['projeto_id'],
            'prioridade_id'    => $dados['prioridade_id'],
            'servico_id'       => $dados['servico_id'],
            'status_id'        => 2,
            'empresa_open_id'  => $dados['empresa_open_id'],
            'empresa_close_id' => $dados['empresa_close_id'],
            'valor'            => intval($dados['valor']),
            'nome_cliente'     => $dados['nome_cliente'],
            'data_atendimento' => $dados['data_atendimento'],
            'periodo_id'       => $dados['periodo_id'],
            'logradouro'       => $dados['logradouro'],
            'state_id'         => $dados['state_id'],
            'city_id'          => $dados['city_id'],
            'obs_at'           => $dados['obs_at']
        );
    } else {
        $chamado = array(
            'numero'           => $dados['numero'],
            'projeto_id'       => $dados['projeto_id'],
            'prioridade_id'    => $dados['prioridade_id'],
            'servico_id'       => $dados['servico_id'],
            'status_id'        => $dados['status_id'],
            'empresa_open_id'  => $_SESSION['empresa_id'],
            'nome_cliente'     => $dados['nome_cliente'],
            'data_atendimento' => $dados['data_atendimento'],
            'periodo_id'       => $dados['periodo_id'],
            'logradouro'       => $dados['logradouro'],
            'state_id'         => $dados['state_id'],
            'city_id'          => $dados['city_id'],
            'obs_at'           => $dados['obs_at']
        );
    }
    $chamadoId = $db->insert('chamados', $chamado);
    if($chamadoId){
        $retorno['message'] = "<div class='alert alert-success'><span>Chamado criado com sucesso.</span><br><img src='img/loading.gif' /></div><br>";
        $retorno['success'] = true;
        // $mail = new PHPMailer(true);
        // try {
        //     $data  = date('d/m/Y', strtotime($chamado['data_atendimento']));
        //     $body  = "<h3><strong>Edição - Chamado {$chamado['numero']} - {$data}</strong></h3>";
        //     $body .= "<p>Você recebeu um chamado. Para maiores informações acesse o sistema.</p>";
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
        //     $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        //     $mail->Port = 465;                                    // TCP port to connect to
        //     //Recipients
        //     $mail->setFrom('no-reply@infolinn.com', 'No-reply');
        //     $db->where('id', $dados['id']);
        //     $c = $db->getOne('chamados');
        //     $db->where('empresa_id', $c['empresa_open_id']);
        //     if($_SESSION['tipo_empresa_id'] == 1){
        //         $db->orWhere('empresa_id', $c['empresa_close_id']);
        //     }
        //     $db->where('status', 1);
        //     $usuarios = $db->get('usuarios');
        //     foreach($usuarios as $usuario){
        //         $mail->addAddress($usuario['email'], $usuario['nome']);
        //     }     // Add a recipient
        //     $mail->addCC('chamados@infolinn.com.br');
        //     //Content
        //     $mail->isHTML(true);                                  // Set email format to HTML
        //     $mail->Subject = 'TI Control - Infolinn - Abertura de Chamado - ' . $dados['numero'];
        //     $mail->Body    = $body;
        //     $mail->send();
        // } catch (Exception $e) {
        //     $retorno['message'] = "<div class='alert alert-danger text-center'>{$mail->ErrorInfo}</div>";
        //     $retorno['success'] = false;
        // }
    } else {
        $retorno['message'] = "<div class='alert alert-danger text-center'>Erro ao criar chamado.</div>";
        $retorno['success'] = false;
    }
}
echo json_encode($retorno);
?>