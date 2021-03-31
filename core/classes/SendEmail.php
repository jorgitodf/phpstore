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
            $html .= '<p><a href="' . $link . '">Confirmar E-mail</a></p>';
            $html .= '<p><i><small>' . APP_NAME . '</small></i></p>';

            $mail->Body = $html;

            $mail->send();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function enviar_email_confirmacao_compra(string $email_cliente, array $dados_compra)
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
            $mail->addAddress($email_cliente);

            $mail->isHTML(true);
            $mail->Subject = APP_NAME . " - Confirmação da Compra - {$dados_compra['dados_pagamento']['codigo_compra']}";
            $html = '<p>E-mail de confirmação da sua compra.</p>';
            $html .= '<p>Dados da sua Compra:</p>';
            $html .= '<ul>';
            foreach ($dados_compra['lista_compra'] as $value) {
                $html .= "<li>{$value}</li>";
            }
            $html .= '</ul>';
            $html .= "<p>Total: <strong>{$dados_compra['total']}</strong></p>";
            $html .= "<hr>";
            $html .= "<p>DADOS DO PAGAMENTO:</p>";
            $html .= "<p>Número da Conta: <strong>{$dados_compra['dados_pagamento']['numero_conta']}</strong></p>";
            $html .= "<p>Código da Compra: <strong>{$dados_compra['dados_pagamento']['codigo_compra']}</strong></p>";
            $html .= "<p>Valor a Pagar: <strong>{$dados_compra['total']}</strong></p>";
            $html .= "<hr>";
            $html .= "<p>NOTA: A sua compra só será processada após confirmação do pagamento!</p>";

            $mail->Body = $html;

            $mail->send();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function sendEmailStatusPurchasing(string $email_cliente, array $dados_compra, array $statusPur)
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
            $mail->addAddress($email_cliente);

            $mail->isHTML(true);
            $mail->Subject = APP_NAME . " - Status da Compra - {$dados_compra['dados_pagamento']['codigo_compra']}";
            $html = '<p>E-mail de informação sobre o Status da sua compra.</p>';
            $html .= '<p>Dados da sua Compra:</p>';
            $html .= '<ul>';
            foreach ($dados_compra['lista_compra'] as $value) {
                $html .= "<li>{$value}</li>";
            }
            $html .= '</ul>';
            $html .= "<p>Total: <strong>{$dados_compra['total']}</strong></p>";
            $html .= "<hr>";
            $html .= "<p>STATUS DA COMPRA:</p>";
            $html .= "<p>Status: <strong>{$statusPur[0]->nome_status}</strong></p>";
            $html .= "<p>Descrição: <strong>{$statusPur[0]->mensagem_status}</strong></p>";
            $html .= "<p>Data: <strong>{$statusPur[0]->data_status}</strong></p>";
            $html .= "<hr>";
            if ($statusPur[0]->status_id == 1) {
                $html .= "<p>NOTA: A sua compra só será processada após confirmação do pagamento!</p>";
            }

            $mail->Body = $html;

            $mail->send();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function sendEmailStatusPayment(string $email_cliente, array $dados_compra, array $statusPay)
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
            $mail->addAddress($email_cliente);

            $mail->isHTML(true);
            $mail->Subject = APP_NAME . " - Status da Pagamento - {$dados_compra['dados_pagamento']['codigo_compra']}";
            $html = '<p>E-mail de informação sobre o Status do Pagamento.</p>';
            $html .= '<p>Dados da sua Compra:</p>';
            $html .= '<ul>';
            foreach ($dados_compra['lista_compra'] as $value) {
                $html .= "<li>{$value}</li>";
            }
            $html .= '</ul>';
            $html .= "<p>Total: <strong>{$dados_compra['total']}</strong></p>";
            $html .= "<hr>";
            $html .= "<p>STATUS DO PAGAMENTO:</p>";
            $html .= "<p>Status: <strong>{$statusPay[0]->nome_status}</strong></p>";
            $html .= "<p>Descrição: <strong>{$statusPay[0]->mensagem_status}</strong></p>";
            $html .= "<p>Data: <strong>{$statusPay[0]->data_status}</strong></p>";
            $html .= "<hr>";
            if ($statusPay[0]->status_id == 7) {
                $html .= "<p>A sua compra será cancelada automaticamente!</strong></p>";
            }

            $mail->Body = $html;

            $mail->send();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}