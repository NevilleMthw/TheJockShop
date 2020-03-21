<?php
$mongoClient = new MongoClient();
$db = $mongoClient->thejockshop;
    //Start session management
    session_start();

    //Remove all session variables
    session_unset(); 

    //Destroy the session 
    session_destroy(); 

    //Echo result to user

    echo '<script> alert("You have successfully logged out!")</script>';
    echo'<script>window.location.replace("account.php")</script>';
    $cart = $db->cart;
    $deleteResult = $cart->remove();
?>

    