<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BaseDatosController extends Controller
{
 
    public function __construct(){
        
    }
 
    public function is_token_empty() {

        $sql="SELECT id FROM tokens WHERE provider = 'google'";
        $result=DB::select($sql);

        if(empty($result)){
            return false;
        }
  
        return true;
    }
 
    public function get_refersh_token($client_id,$client_secret) {
        $sql = DB::select("SELECT provider_value FROM tokens WHERE provider='google' AND client_id='$client_id' AND client_secret='$client_secret'");
        if(!empty($sql)){
            return $sql[0]->provider_value;
        }else{
            return false;
        }
    }
 
    public function update_refresh_token($token) {
        if($this->is_token_empty()) {
            DB::insert("INSERT INTO tokens(provider, provider_value) VALUES('google', '$token')");
        } else {
            DB::update("UPDATE tokens SET provider_value = '$token' WHERE provider = 'google'");
        }
    }
    
}
