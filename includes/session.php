<?php

class Session {
    
    private $logged_in = false;
    public $user_id;
    public $message;

    public function __construct() {
        session_start();
        $this->checkMessage();
        $this->checkLogin();
        
        if($this->logged_in) {
            
        } else {
            
        }
    }
    
    public function login($user) {
        if($user){
            $this->user_id = $_SESSION['user_id'] = $user->id;
            $this->logged_in = true;
        }
    }
    
    public function logout() {
        unset($_SESSION['user_id']);
        unset($this->user->id);
        $this->logged_in = false;
    }
    
    public function message($msg='') {
        if(!empty($msg)) {
            $_SESSION['message'] = $msg;
        } else {
            return $this->message;
        }
    }
    
    public function isLoggedIn() {
        return $this->logged_in;
    }
    
    private function checkLogin() {
        if(isset($_SESSION['user_id'])) {
            $this->user_id = $_SESSION['user_id'];
            $this->logged_in = true;
        } else {
            unset($this->user_id);
            $this->logged_in = false;
        }
    }
    
    private function checkMessage() {
        if(isset($_SESSION['message'])) {
            $this->message = $_SESSION['message'];
            unset($_SESSION['message']);
        } else {
            $this->message = '';
        }
    }
}

$session = new Session();
$message = $session->message();
