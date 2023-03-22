<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseDatosController;
use League\OAuth2\Client\Provider\Google;
use Hayageek\OAuth2\Client\Provider\Yahoo;
use Stevenmaguire\OAuth2\Client\Provider\Microsoft;
use Vendor\Autoloader\Autoloader;
 

class GetTokenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // $clientId = $request->input('client_id');
        // $clientSecret = $request->input('client_secret');

        // require 'vendor/autoload.php';
         
        session_start();
         
        $providerName = '';
         
        if (array_key_exists('provider', $_GET)) {
            $providerName = "Google";
            $_SESSION['provider'] = $providerName;
        } elseif (array_key_exists('provider', $_SESSION)) {
            $providerName = $_SESSION['provider'];
        }
        if (!in_array($providerName, ['Google', 'Microsoft', 'Yahoo'])) {
            exit('Only Google, Microsoft and Yahoo OAuth2 providers are currently supported in this script.');
        }
         
        $clientId = $request->input('client_id');
        $clientSecret = $request->input('client_secret');
        //  $clientId = $_GET['client_id'];
        //  $clientSecret =$_GET['client_secret'];
         
        $redirectUri = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
         
        $params = [
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri' => $redirectUri,
            'accessType' => 'offline'
        ];
         
        $options = [];
        $provider = null;
         
        switch ($providerName) {
            case 'Google':
                $provider = new Google($params);
                $options = [
                    'scope' => [
                        'https://mail.google.com/'
                    ]
                ];
                break;
            case 'Yahoo':
                $provider = new Yahoo($params);
                break;
            case 'Microsoft':
                $provider = new Microsoft($params);
                $options = [
                    'scope' => [
                        'wl.imap',
                        'wl.offline_access'
                    ]
                ];
                break;
        }
         
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
