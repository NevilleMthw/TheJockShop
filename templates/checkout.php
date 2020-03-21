<?php
    //Include the PHP functions to be used on the page 
    include('common.php');
	
    //Output header and navigation 
    outputHeader('Products Page');
    outputBannerNavigation();
?>
<?php
session_start();
echo'<div class="wrap cf">';
echo'<p id=checkMessage>Your order has been processed.</p>';
echo'<div class="heading1">';
echo'<a href="products.php" class="continue">Continue Shopping</a>';
echo'</div>';
echo '</div>';

$mongoClient = new MongoClient();
$db = $mongoClient->thejockshop;
$collection = $db->orders;
$cart = $db->cart;
$cursor = $db->cart->find();

if( array_key_exists("loggedInUsername", $_SESSION) ){
foreach ($cursor as $prod){
$document = array( 
  "cust_name" => $_SESSION['loggedInUsername'],
  "name" => $prod['name'], 
  "category" => $prod['category'], 
  "img" => $prod['img'], 
  "price" => $prod['price'],
  "quantity" =>$prod['quantity'],
);
$collection->insert($document);
$deleteResult = $cart->remove();
  
}
echo'<h1 class="projTitle">Recommendations</h1>';

$quantity=1;
$category="";
$search_string = $_SESSION['loggedInUsername'];
$findCriteria = [
  'cust_name' => $search_string,
];
$rec = $db->orders->find($findCriteria);
foreach ($rec as $item){
  if($item['quantity']>=$quantity)
  {
    $quantity = $item['quantity'];
    $category = $item['category'];
  }
 
}

$findCriteria2 = [
  'category' => $category,
];

$products = $db->products->find($findCriteria2)->limit(3);
echo'<div id=recWrapper>';
foreach ($products as $prod){
  echo "<div class=shop-card>";
  echo "<div class=title>" . $prod['name'];
  echo "</div>";
  echo "<div class=desc>" . $prod['category'];
  echo "</div>";
  echo "<img id=imgstyle src=". $prod['img'] . ">";
  echo "<div class=price> $". $prod['price'];
  echo "</div>";
  echo'<form method="POST">';
  echo '<a href="?cart=true&name='.$prod['name'].'" class=btn2>';
  echo 'Add to cart<span class=bg></span>';
  echo'</a>';
  echo'</form>';
  echo "</div>";
}
echo'</div>';
}

else {
  echo '<script> alert("You need to be logged in!")</script>';
  echo "<script type='text/javascript'> window.location.replace('cart.php') </script>";
}


?>
<?php
    outputFooter();
?>