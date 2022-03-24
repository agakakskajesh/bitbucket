<?php
    class User {
        private $name;
        private $login;
        private $email;
        private $password;


        function __construct($name, $login, $email, $password) 
        {
            $this->name = $name;
            $this->login = $login;
            $this->email = $email;
            $this->password = $password;
        }

        function getName() {
            return $this->name;
        }

        function getLogin() {
            return $this->login;
        }

        function getEmail() {
            return $this->email;
        }

        function getPassword() {
            return $this->password;
        }

        function getData() {
            return ['name' => $this->name,'login' => $this->login, 'email' => $this->email, 'password' => $this->password];
        }
       
    }