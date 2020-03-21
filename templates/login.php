<?php
    //Include the PHP functions to be used on the page 
    include('common.php');
	
    //Output header and navigation 
    outputHeader('Products Page');
    outputBannerNavigation();
?>
<?php    //Start session management
    session_start();

    //Get name and address strings - need to filter input to reduce chances of SQL injection etc.
    $usrName= filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);



    //Connect to MongoDB and select database
    $mongoClient = new MongoClient();
    $db = $mongoClient->thejockshop;
    $_SESSION['loggedInUsername'] = $usrName;
    $_SESSION['loggedInPass'] = $password;
    $search_string= $_SESSION['loggedInUsername'];
    //Create a PHP array with our search criteria
    $findCriteria = [
        'username' => $search_string,
     ];

    //Find all of the customers that match  this criteria
    $cursor = $db->customers->find($findCriteria);

    //Check that there is exactly one customer
    if($cursor->count() == 0){
        echo '<script> alert("This username is not recognized.")</script>';
    echo'<script>window.location.replace("account.php")</script>';
        session_unset(); 
        session_destroy(); 
        return;
    }
    
   
    //Get customer
    $customer = $cursor->getNext();
    
    //Check password
    if($customer['password'] != $password){
        echo '<script> alert("Password incorrect. Try again!")</script>';
    echo'<script>window.location.replace("account.php")</script>';
        session_unset(); 
    session_destroy(); 
        return;
    }

    if($customer['password'] == $password && $customer['username'] == $usrName){
        $_SESSION['loggedInUsername'] = (string)$usrName;
        $_SESSION['loggedInPass'] = $password;
    }
    
    //Start session for this user
    echo header("location: account.php");
    
    if( array_key_exists("loggedInUsername", $_SESSION) ){
        foreach ($cursor as $cust){
        echo '<div class="wrap cf">
			<h1 class="projTitle">User Profile</h1>
			<div class="cart">
	<form action="update.php" method="POST">
	<div id=account>
	
      <p>Username</p>
    <input type="text" name="username" value="'.$_SESSION["loggedInUsername"].'"readonly>

    <p>Password</p>

     <input type="password" name="password" value="'.$cust['password'].'"READONLY>
       <p>Name</p>
        <input type="text" name="name" value="'.$cust['name'].'"READONLY>
        <p>Email</p>
		 <input type="text" name="email" value="'.$cust['email'].'"READONLY>
		 <p style="text-align:center;"> Note: Your username cannot be changed. </p>
		<button class="subbtn"> Update </button>
		</form>
	<form action=logout.php>
		<button class="subbtn">Logout </button>
		</form>
 </div>
 </div>
 </div>
  </div>';
        }

        
	$orderCursor = $db->orders->find($findCriteria);
	
    if ($orderCursor->count()>0){
		echo' <div class="wrap cf">
		<h1 class="projTitle">Purchase History</h1>';
		
		echo '<div class="cart">';

		foreach ($orderCursor as $item){
	
	$subTotal = $item['quantity']*$item['price'];
	echo '<ul class="cartWrap">';
	echo '<li class="items odd">';
	echo '<div class="infoWrap">';
	echo '<div class="cartSection">';
	echo '<img src="'.$item['img'].'" class="itemImg" />';
	echo '<p class="itemCategory">'.$item['category'].'</p>';
	echo '<h3>'.$item['name'].'</h3>';
	echo '<p> <input type="text"  class="qty" placeholder="'.$item['quantity'].'"/> x $'.$item['price'].'</p>';
	echo '</div>';
	echo '<div class="prodTotal cartSection">';
	echo '<p>$'.$subTotal.'</p>';
	echo '</div>';
	echo '</div>';
	echo'</li>';
	echo'</ul>';
	echo'<hr>';
	  

		
	}
	
	echo '<div class="subtotal cf">';

	echo '</div>';
	echo '</div>';


}
	else if($orderCursor->count()==0){
		echo'<div class="wrap cf">
		<h1 class="projTitle">Purchase History</h1>
    <div class="heading cf">';
		echo '<p style="text-align:center;"> You have never purchased anything from The Jock Shop. Go buy something! </p>';
		echo'<a style="margin-right:41%;" href="products.php" class="continue">Continue Shopping</a>';
		echo '</div>';
		echo '</div>';
	}

    }
    //Close the connection
    $mongoClient->close();
    ?>
    <?php
    outputFooter();
?>