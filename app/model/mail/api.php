<?php

error_reporting(0);
require_once('class.phpmailer.php');

class Mail_Api
{
    static function send($subject,$body,$user_mail,$user_nick = '客户')
    {

        $cfg = Q::ini('appini/email_config');

        $mail             = new PHPMailer();
        $mail->IsSMTP(); 
        $mail->SMTPDebug  = false;
        $mail->SMTPAuth   = $cfg['smtpauth'];
        $mail->SMTPSecure = $cfg['smtpsecure'];
        $mail->Host       = $cfg['host'];
        $mail->Port       = $cfg['port'];
        $mail->Username   = $cfg['username'];
        $mail->Password   = $cfg['password'];

        $mail->SetFrom($cfg['from_email'], $cfg['from_nick']);

        $mail->Subject    = $subject;

        $mail->MsgHTML($body);

        $mail->AddAddress($user_mail, $user_nick);

        if(!$mail->Send())
        {
          return $mail->ErrorInfo;
        } else {
          return true;
        }
    }
}