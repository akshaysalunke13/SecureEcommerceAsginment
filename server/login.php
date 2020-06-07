<?php
    session_start();
    include("files/rsa.php");

    $receivedUsername = $_POST['username'];
    $receivedPassword = $_POST['password'];
    
    foreach(file('../database/users.txt') as $line) {

        list($username, $password) = explode(",", $line);

        if($receivedUsername == $username) {
            $found = 1;

            if ($receivedPassword."\n" == $password) {
                $_SESSION['user'] = $username;
                header('Location: ../client/cart.php');
            } else {
                echo "Wrong Password. Click <a href='../client/login.html'>HERE</a> to try again.";
            }
        } else {
            echo "User not found. Click <a href='../client/login.html'>HERE</a> to try again.";
        }
    }
?>
