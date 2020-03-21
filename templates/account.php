<?php
    //Include the PHP functions to be used on the page 

	include('common.php');
	
    //Output header and navigation 
    outputHeader('Account');
	outputBannerNavigation();
	
	session_start();
	
	
	if( array_key_exists("loggedInUsername", $_SESSION) ){
		$mongoClient = new MongoClient();
    $db = $mongoClient->thejockshop;
   
    $search_string=  $_SESSION['loggedInUsername'];
    //Create a PHP array with our search criteria
    $findCriteria = [
        'username' => $search_string,
	 ];
	 $findCriteria2 = [
        'cust_name'=> $search_string,
     ];

    //Find all of the customers that match  this criteria
    $cursor = $db->customers->find($findCriteria);
		echo'<body>';
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
			

	$orderCursor = $db->orders->find($findCriteria2);
	
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
	echo ''.$item['quantity'].' x $'.$item['price'];
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
	
	
	else {
		echo '<body>
		  
		<div class = "container">
			<div class="register">
				<div class="register-box">
					<div class="box-header">
						<h2 class ="login-title">Register</h2>
					</div>
					<label for="name">Name</label>
					<br>
					<input type="text" id="name">
					<br>
					<label for="username">Username</label>
					<br/>
					<input type="text" id="username">
					<br/>
					<label for="email">Email</label>
					<br>
					<input type="text" id="email">
					<br>
					<label for="password">Password</label>
					<br/>
					<input type="password" id="password" required>
					<br/>
					<button type="register" onclick = "register()">Submit</button>
					<br/>
					<p>
					 <span id="ServerResponse"></span>
				</p>
				</div>
			</div>
		
			<div class="login">
				<div class="login-box">
					<div class="box-header2">
						<h2 class="login-title">Log In</h2>
					</div>
					<form action="login.php" method="POST">
					
					<label for="username">Username</label>
					<br/>
					<input type="text" id="username" name="username">
					<br/>
					<label for="password">Password</label>
					<br/>
					<input type="password" id="password" name="password">
					<br/>
					
					<button type="submit">Login</button>
					<br/>
					</form>
				</div>
			</div>
		</div>
		<script>
					function register(){
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
						request.open("POST", "registration.php");
						request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						
						//Extract registration data
						var name = document.getElementById("name").value;
						var usrName = document.getElementById("username").value;
						var usrEmail = document.getElementById("email").value;
						var usrPassword = document.getElementById("password").value;
						
						//Send request
						request.send("name=" + name +"&username=" + usrName + "&email=" + usrEmail + "&password=" + usrPassword);
					}
				</script>
		</body>';
	}
    //Close the connection

?>

<?php
outputFooter();
?>