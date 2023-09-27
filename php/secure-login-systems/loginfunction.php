<?php

    require "../php/config.php";

    function connect2db(){
        $mysqli = new mysqli(SERVER, USERNAME,PASSWORD,DATABASE);
        if ($mysqli -> connect_errno != 0){
            $error = $mysqli->connect_error;
            $error_date = date("F j, Y, g:i a");
            $message = "{$error} | {$error_date} \r\n";
            file_put_contents("db-log.txt", $message, FILE_APPEND);
            return false;
        }
        else {
            return $mysqli;
        }
    }

    function registerUser($email, $username, $password, $confirm_password){
        $mysqli = connect2db();
        $args = func_get_args();

        $args = array_map(function($value){
            return trim($value);
        }, $args);

        // dont want any javascript code in my DB
        foreach ($args as $value) {
            if(preg_match("/([<|>])/", $value)){
                return "<> characters are not allowed";
            }
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return "Email is not valid";
        }

        // prepared statement if the given email already exists
        $stmt = $mysqli->prepare("SELECT email FROM users WHERE email = ?");
        $stmt -> bind_param("s", $email);
        $stmt -> execute();
        $result = $stmt -> get_result();
        $data = $result-> fetch_assoc();
        if ($data != NULL){
            return "Email already exists, please use a different email";
        }

        // if a given username is too long return an error
        if(strlen($username) > 30){
            return "Username is too long";
        }

        // prepared statement if the given username already exists
        $stmt = $mysqli->prepare("SELECT username FROM users WHERE username = ?");
        $stmt -> bind_param("s", $username);
        $stmt -> execute();
        $result = $stmt -> get_result();
        $data = $result-> fetch_assoc();
        if ($data != NULL){
            return "Username already exists, please use a different username";
        }


        // If a given password is too long return an error 
        if(strlen($password) > 60){
            return "Password is too long";
        }

        // if the password and confirmed password do not match
        if($password != $confirm_password){
            return "Passwords do not match";
        }

        $hased_password = password_hash($password, PASSWORD_DEFAULT);

        // prepared statement insert the user into the DB
        $stmt = $mysqli->prepare("INSERT INTO users(username, password, email) VALUES(?,?,?)");
        $stmt -> bind_param("sss", $username, $hased_password, $email);
        $stmt -> execute();
        if ($stmt -> affected_rows != 1){
            return "An error occured. Please Try Again";
        }
        else{
            return "Success";
        }
    }

?>