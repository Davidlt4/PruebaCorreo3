<?php

namespace App\Http\Controllers;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\OAuth;
use League\OAuth2\Client\Provider\Google;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseDatosController;
use App\Http\Controllers\GetTokenController;

class CorreoController extends Controller
{
    public function enviarCorreo(Request $request){

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        
        $mail->SMTPAuth = true;
        $mail->AuthType = 'XOAUTH2';
        
        $email = $request->input('email');
        $clientId = $request->input('client_id');
        $clientSecret = $request->input('client_secret');
        
        $db = new BaseDatosController();
        $refreshToken = $db->get_refersh_token($clientId,$clientSecret);

        if(!$refreshToken){
            $gtoken=new GetTokenController();
            $refreshToken = $gtoken->getToken($clientId,$clientSecret);
        }
        
        $provider = new Google(
            [
                'clientId' => $clientId,
                'clientSecret' => $clientSecret,
            ]
        );
        
        $mail->setOAuth(
            new OAuth(
                [
                    'provider' => $provider,
                    'clientId' => $clientId,
                    'clientSecret' => $clientSecret,
                    'refreshToken' => $refreshToken,
                    'userName' => $email,
                ]
            )
        );
        
        $mail->setFrom($email,'NaturalSoft');
        $mail->addAddress('correonatural1234@gmail.com','David López');
        $mail->isHTML(true);
        $mail->Subject = 'Email Subject';
        $mail->Body = '<b>Email Body</b>';
        
        if (!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message sent!';
        }
    }
}
