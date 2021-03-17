<?php

namespace core\classes;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class SendEmail
{
    public function send_email_novo_cliente(string $email_cliente, string $nome_cliente, string $token)
    {

        $link = BASE_URL . '?r=confirmar_email&token=' . $token;

        $mail = new PHPMailer(true);

        try {

            $mail->SMTPDebug = SMTP::DEBUG_OFF;                   
            $mail->isSMTP();                                      
            $mail->Host       = EMAIL_HOST;                  
            $mail->SMTPAuth   = true;                                  
            $mail->Username   = EMAIL_FROM;                     
            $mail->Password   = EMAIL_PASS;                               
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
            $mail->Port       = EMAIL_PORT;  
            $mail->CharSet    = 'UTF-8';                           

            $mail->setFrom(EMAIL_FROM, APP_NAME);
            $mail->addAddress($email_cliente, $nome_cliente);     

            $mail->isHTML(true);                                  
            $mail->Subject = APP_NAME . ' - Confirmação de E-mail';
            $html = '<p>Seja bem vindo à nossa Loja ' . APP_NAME . '</p>';
            $html .= '<p>Para poder entrar em nossa loja, é necessário confirmar o seu e-mail</p>';
            $html .= '<p>Para confirmar o seu e-mail, clique no link abaixo:</p>';
            $html .= '<p><a href="'.$link.'">Confirmar E-mail</a></p>';
            $html .= '<p><i><small>'.APP_NAME.'</small></i></p>';

            $mail->Body = $html;

            $mail->send();

            return true;
            
        } catch (Exception $e) {
            return false;
        }

    }

    public function enviar_email_confirmacao_compra(string $email_cliente, string $nome_cliente, string $token)
    {
        $mail = new PHPMailer(true);

        try {

            $mail->SMTPDebug = SMTP::DEBUG_OFF;                   
            $mail->isSMTP();                                      
            $mail->Host       = EMAIL_HOST;                  
            $mail->SMTPAuth   = true;                                  
            $mail->Username   = EMAIL_FROM;                     
            $mail->Password   = EMAIL_PASS;                               
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
            $mail->Port       = EMAIL_PORT;  
            $mail->CharSet    = 'UTF-8';                           

            $mail->setFrom(EMAIL_FROM, APP_NAME);
            $mail->addAddress($email_cliente, $nome_cliente);     

            $mail->isHTML(true);                                  
            $mail->Subject = APP_NAME . ' - Confirmação da Compra - xxxxxxx';
            $html = '<p>Seja bem vindo à nossa Loja ' . APP_NAME . '</p>';
            $html .= '';
            $html .= '';
            $html .= '';
            $html .= '<p><i><small>'.APP_NAME.'</small></i></p>';

            $mail->Body = $html;

            $mail->send();

            return true;
            
        } catch (Exception $e) {
            return false;
        }

    }
}