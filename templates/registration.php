<?php
    //Connection to DB
    $mongoClient = new MongoClient();
    $db = $mongoClient->thejockshop;
    $collection = $db->customers;
    //Get name and address strings - need to filter input to reduce chances of SQL injection etc.
    $name= filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $username= filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);


    
    if($name != "" && $username != "" && $email != "" && $password != ""){//Check query parameters 
        //STORE REGISTRATION DATA IN MONGODB
   $document = array( 
      "name" => $name, 
      "username" => $username, 
      "email" => $email, 
      "password" => $password
   );

   $findCriteria = [
    'username' => $username,
 ];

   $cursor = $db->customers->find($findCriteria);
   if ($cursor->count() == 0) {
   $collection->insert($document);
   echo '<p class="register-message"> You have registered successfully. </p>';
   }

   else if($cursor->count() > 0){
    echo '<p class="register-message"> Sorry, this username is not available.';
    return;
}
    }
    else{//A query string parameter cannot be found
        echo '<p class="register-message"> Please fill all the fields <b> required. </p>';
    }
?>
