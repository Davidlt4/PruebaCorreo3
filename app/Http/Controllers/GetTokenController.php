<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseDatosController;
use League\OAuth2\Client\Provider\Google;
 

class GetTokenController extends Controller
{
    
    public function getToken($client_id=null,$client_secret=null)
    {
        
        require 'C:\apache\htdocs\gmail-oauth-app\vendor\autoload.php';

        session_start();

        $providerName='';

        // if(array_key_exists('provider',$_GET)){
        //     $providerName=$_GET['provider'];
        //     $_SESSION['provider']=$providerName;
        // }elseif(array_key_exists('provider',$_SESSION)){
        //     $providerName=$_SESSION['provider'];
        // }

        // if(!in_array($providerName,['Google','Microsoft'])){
        //     exit ('Only Google, Microsoft and Yahoo OAuth2 providers are currently supported in this script.');
        // }
         
        $providerName = 'Google';
        $_SESSION['provider'] = $providerName;

        if (array_key_exists('provider', $_SESSION)) {
            $providerName = $_SESSION['provider'];
        }

        if($client_id!=null && $client_secret!=null){

            $clientId = $client_id;
            $clientSecret =$client_secret;

            $_SESSION['id'] = $clientId;
            $_SESSION['secret'] = $clientSecret;

        }else{

            $clientId = $_SESSION['id'];
            $clientSecret =$_SESSION['secret'];

            unset($_SESSION['id']);
            unset($_SESSION['secret']);

        }

        $redirectUri = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'].'/gettoken';
         
        $params = [
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri' => $redirectUri,
            'accessType' => 'offline'
        ];
         
        $provider = new Google($params);
        $options = [
            'scope' => [
                'https://mail.google.com/'
            ]
        ];

        // $options = [];
        // $provider = null;
        
        // switch ($providerName) {
        //     case 'Google':
        //         $provider = new Google($params);
        //         $options = [
        //             'scope' => [
        //                 'https://mail.google.com/'
        //             ]
        //         ];
        //         break;
        //     case 'Microsoft':
        //         $provider = new Microsoft($params);
        //         $options = [
        //             'scope' => [
        //                 'wl.imap',
        //                 'wl.offline_access'
        //             ]
        //         ];
        //         break;
        // }


        if (null === $provider) {
            exit('Provider missing');
        }
         
        if (!isset($_GET['code'])) {

            $authUrl = $provider->getAuthorizationUrl($options);
            $_SESSION['oauth2state'] = $provider->getState();
            header('Location: ' . $authUrl);
            exit;
        
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            
            unset($_SESSION['oauth2state']);
            unset($_SESSION['provider']);
            exit('Invalid state');

         }else {

            unset($_SESSION['provider']);
            unset($_SESSION['id']);
            unset($_SESSION['secret']);

            $code=str_replace("%2F",'/\/',$_GET['code']);
           
            $token = $provider->getAccessToken(
                'authorization_code',
                [
                    'code' => $code
                ],
            );
            
            // dd($token);
         
            $db = new BaseDatosController();
            if($db->is_token_empty()) {
                // dd($token->getRefreshToken());
                $db->update_refresh_token($token,$clientId,$clientSecret);
                //$token->getRefreshToken();
            }

            // header('Location:https://'.$_SERVER['HTTP_HOST'].'/home');
            echo "token creado";
        }

    }

}
