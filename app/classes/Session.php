<?php

namespace App;

class Session
{
    function __construct()
    {
        session_start();
    }

    public function add(string $key, $data)  // Setter pour la variable de session
    {
        $_SESSION[$key] = $data;
    }

    public function get(string $key)  // Getter pour la variable de session
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : false;
    }
    
    public function destroy()
    {
        unset($_SESSION);
        session_destroy();
    }

    public function isConnected()
    {
        return isset($_SESSION['user']);        // 1ère manière
        // return isset($this->get('user'));    // 2ème manière
    }

    public function hasRole(string $role)
    {
        return $_SESSION['user']['role'] == $role ? true : false;  // test ternaire, comme un if/else
    }
}