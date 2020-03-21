<?php
    //Include the PHP functions to be used on the page 
    include('common.php');
	
    //Output header and navigation 
    outputHeader('Products Page');
    outputBannerNavigation();
?>
<?php    
//Start session management
    session_start();

    //Get name and address strings - need to filter input to reduce chances of SQL injection etc.
    $usrName= filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);    
    $name= filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);  
    //Connect to MongoDB and select database
    $mongoClient = new MongoClient();
    $db = $mongoClient->thejockshop;

    //Create a PHP array with our search criteria
    $findCriteria = [
        "username" => $usrName, 
     ];

    //Find all of the customers that match  this criteria
    $cursor = $db->customers->find($findCriteria);

    
    
    //Start session for this user
    $_SESSION['loggedInUsername'] = (string)$usrName;
    $_SESSION['loggedInPass'] = $password;
    $_SESSION['loggedInEmail'] = $email;
    $_SESSION['loggedInName'] = $name;
 
        echo ' <div class="wrap cf">
        <h1 class="projTitle">User Profile</h1>
        <div class="cart">
  
        <div id="account">
        <form>
       <p>Username</p>
     <input id = "username" value="'.$_SESSION["loggedInUsername"].'"readonly>
 
     <p>Password</p>
 
      <input type="password" id="password" value="'.$_SESSION["loggedInPass"].'">
        <p>Name</p>
         <input id="name" value="'.$_SESSION["loggedInName"].'">
         <p>Email</p>
          <input id="email" value="'.$_SESSION["loggedInEmail"].'">
          <p style="text-align:center;"> Note: Your username cannot be changed. </p>
         <button onclick="update()" class= "subbtn">Save</button>
</form>
          <form action=logout.php>
          <button class="subbtn">Logout </button>
          </form>
          </div>
          </div>
          </div>';


    
   echo '<script type=text/javascript>
            function update(){
                //Create request object 
                var request = new XMLHttpRequest();

                //Create event handler that specifies what should happen when server responds
                request.onload = function(){
                    //Check HTTP status code
                    if(request.status === 200){
                        //Get data from server
                        var responseData = request.responseText;

                        //Add data to page
                        document.getElementById("ServerResponse").innerHTML = responseData;
                    }
                    else
                        alert("Error communicating with server: " + request.status);
                };

                //Set up request with HTTP method and URL 
                request.open("POST", "update-details.php");
                request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                
                //Extract registration data
                var usrName = document.getElementById("username").value;
                var usrPassword = document.getElementById("password").value;
                var email = document.getElementById("email").value;
                var name = document.getElementById("name").value;
                //Send request
                request.send("username=" + usrName + "&password=" + usrPassword + "&email=" + email + "&name=" + name);
            }
        </script>';
        $mongoClient->close();
        ?>