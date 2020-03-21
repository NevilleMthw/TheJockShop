<?php
    //Connection to DB
    $mongoClient = new MongoClient();
    $db = $mongoClient->thejockshop;
    $collection = $db->customers;
    session_start();
    //Get name and address strings - need to filter input to reduce chances of SQL injection etc.
    $username= filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    
    $_SESSION['loggedInUsername']=(string)$username;
    $newdata1 = array('$set' => array("username" => $username, "password" => $password));
    $newdata2 = array('$set' => array("username" => $username, "email" => $email));
    $newdata3 = array('$set' => array("username" => $username, "name" => $name));
    $newdata4 = array('$set' => array("username" => $username, "username" => $username));
   
    // specify the column name whose value is to be updated. If no such column than a new column is created with the same name.
    
    $condition = array("username" =>$username);
    // specify the condition with column name. If no such column exist than no record will update
  

function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}
    if($collection->update($condition, $newdata1))
{
    alert("Hello World");
}

    if($collection->update($condition, $newdata2))
{
    echo '<p style="color:green;">Record updated successfully</p>';
}

    if($collection->update($condition, $newdata3))
{
    echo '<p style="color:green;">Record updated successfully</p>';
}

    if($collection->update($condition, $newdata4))
{
    echo '<p style="color:green;">Record updated successfully</p>';
}


?>