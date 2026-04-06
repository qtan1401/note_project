<?php
session_start();
require_once 'config.php';
if(isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $hash_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $pass = $_POST['password'];
    $conf_pass = $_POST['conf-password'];
    $checkEmail = $connect->query("SELECT email FROM user WHERE email = '$email'");
    if($checkEmail -> num_rows>0){
        $_SESSION['register_error'] = 'Email is already registered';
        $_SESSION['active_form'] = 'register';
        header("Location: index.php");
        exit;
    }if($conf_pass!==$pass){
        $_SESSION['password_error'] = 'Confirm password does not match';
        $_SESSION['active_form'] = 'register';
        header("Location: index.php");
        exit();
    }else{
        $connect->query("INSERT INTO user (display_name, email, password) VALUES('$name', '$email', '$hash_pass')");
    }
    header("Location: index.php");
    exit();
}

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $result = $connect->query("SELECT * FROM user WHERE email = '$email'");
    if($result -> num_rows >0){
        $user = $result->fetch_assoc(); 
        if(password_verify($pass, $user['password'])){
            $_SESSION['name'] = $user['display_name'];
            $_SESSION['email'] = $user['email'];
            header("Location: home.php");
            exit();
        }
    }
    $_SESSION['login_error'] = 'Incorrect email or password';
    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}
?>

