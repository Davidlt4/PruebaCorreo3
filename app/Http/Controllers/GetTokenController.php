<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseDatosController;
use League\OAuth2\Client\Provider\Google;
use Hayageek\OAuth2\Client\Provider\Yahoo;
use Stevenmaguire\OAuth2\Client\Provider\Microsoft;
 

class GetTokenController extends Controller
{
    
    public function getToken($client_id,$client_secret)
    {
         
        require 'C:\xampp\htdocs\PruebaCorreo\gmail-oauth-app\vendor\autoload.php';
      
        session_start();
         
        $providerName = 'Google';
        $_SESSION['provider'] = $providerName;

        if (array_key_exists('provider', $_SESSION)) {
            $providerName = $_SESSION['provider'];
        }

        $clientId = $client_id;
        $clientSecret =$client_secret;

        $redirectUri = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
         
        $params = [
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri' => $redirectUri,
            'accessType' => 'offline'
        ];
         
        $options = [];
        $provider = null;
        $provider = new Google($params);
        $options = [
            'scope' => [
                'https://mail.google.com/'
            ]
        ];
    
        // switch ($providerName) {
        //     case 'Google':
        //         $provider = new Google($params);
        //         $options = [
        //             'scope' => [
        //                 'https://mail.google.com/'
        //             ]
        //         ];
        //         break;
        //     case 'Yahoo':
        //         $provider = new Yahoo($params);
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

        } else {

            unset($_SESSION['provider']);
            $token = $provider->getAccessToken(
                'authorization_code',
                [
                    'code' => $_GET['code']
                ]
            );
         
            $db = new BaseDatosController();
            if($db->is_token_empty()) {
                $db->update_refresh_token($token->getRefreshToken());
                echo "Refresh token inserted successfully.";
            }
        }

    }

}
