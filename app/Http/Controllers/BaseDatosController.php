<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseDatosController extends Controller
{

    private $dbHost     = "localhost";
    private $dbUsername = "root";
    private $dbPassword = "";
    private $dbName     = "GmailApi";
 
    public function __construct(){

        if(!isset($this->db)){
            // Connect to the database
            $conn = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
            if($conn->connect_error){
                die("Failed to connect with MySQL: " . $conn->connect_error);
            }else{
                $this->db = $conn;
            }
        }
        
    }
 
    public function is_token_empty() {
        $result = $this->db->query("SELECT id FROM tokens WHERE provider = 'google'");
        if($result->num_rows) {
            return false;
        }
  
        return true;
    }
 
    public function get_refersh_token() {
        $sql = $this->db->query("SELECT provider_value FROM tokens WHERE provider='google'");
        return $sql->fetch_assoc()['provider_value'];
    }
 
    public function update_refresh_token($token) {
        if($this->is_token_empty()) {
            $this->db->query("INSERT INTO tokens(provider, provider_value) VALUES('google', '$token')");
        } else {
            $this->db->query("UPDATE tokens SET provider_value = '$token' WHERE provider = 'google'");
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
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
