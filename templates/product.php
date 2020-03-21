<?php
 include('common.php');
	
 //Output header and navigation 
 outputHeader('Welcome Page');
 outputBannerNavigation();
 ?>
 <?php
 session_start();
 $mongoClient = new MongoClient();
 $db = $mongoClient->thejockshop;
 $products = $db->products->find();
 $search_string = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING);

 //Create a PHP array with our search criteria
 $findCriteria = [
     '$text' => [ '$search' => $search_string ] 
  ];
 
$products = $db->products->find($findCriteria);
function addToCart(){
  $mongoClient = new MongoClient();
  $db = $mongoClient->thejockshop;
  $products = $db->products;
  $collection=$db->cart;
  $name= filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING);
  $search_string= $name;
  $findCriteria = [
    '$text' => ['$search' => $search_string]
 ];
 $cursor = $db->products->find($findCriteria);
$quantity=1;

if( array_key_exists("loggedInUsername", $_SESSION) ){
 foreach ($cursor as $prod){
  $document = array( 
    "cust_name" => $_SESSION['loggedInUsername'],
    "name" => $prod['name'], 
    "category" => $prod['category'], 
    "img" => $prod['img'], 
    "price" => $prod['price'],
    "quantity" =>$quantity,
 );

 $cursor2 = $db->cart->find($findCriteria);
 if ($cursor2->count()==0) {
  $collection->insert($document);
  }
  else if($cursor2->count() > 0){

 
 foreach ($cursor2 as $prod1){
  $quantity=$prod1['quantity'];
   $quantity++;
   $newdata1 = array('$set' => array("name" =>$prod['name'], "quantity" => $quantity));
   $condition = array("name" =>$name);
   $collection->update($condition, $newdata1);
 }
 
  
}
}
}
else {
  echo '<script> alert("You need to be logged in!")</script>';
}
}
if(isset($_GET['cart'])){
  addToCart();
}
 
foreach ($products as $prod){
echo'<html>
<body>
<div class = "containerprod">
 <div class="individual-prod">
  <div class="left-side">';
  echo "<img id=imgstyle2 src=". $prod['img'] . ">";
 echo' </div>
  <div class="right-side">
    <div class="name">'.$prod['name'].'</div>
    <div class="subname">'.$prod['category'].'</div>
    <div class=price> $'.$prod['price'].'</div>
    <div class="desc">
    <p>'.$prod['desc'].'</p>
    </div>';
    echo'<form method="POST">';
   echo '<a href="?cart=true&name='.$prod['name'].'" id="submit">';
   echo 'Add to cart<span class=bg></span>';
   echo'</a>';
   echo'</form>
</div>
</div>
</div>';


echo '<div>';
$findCriteria2 = [
  'category' => $prod['category'],
];
$products2 = $db->products->find($findCriteria2)->limit(4);
$i=1;
echo'<h1 class="projTitle">Related Items</h1>';
echo'<div id=recWrapper>';
foreach ($products2 as $prod2){
  if ($prod['name']!=$prod2['name']){
  echo "<div class=shop-card>";
  echo '<a style="text-decoration: none; color: #23211f;" href="product.php?name='.$prod2['name'].'">';
  echo "<div class=title>" . $prod2['name'];
  echo'</a>';
  echo "</div>";
  echo "<div class=desc>" . $prod2['category'];
  echo "</div>";
  echo '<a href="product.php?name='.$prod2['name'].'">';
  echo "<img id=imgstyle src=". $prod2['img'] . ">";
  echo'</a>';
  echo '<div class=price> $'.$prod2['price'].'';
  echo "</div>";
  echo'<form method="POST">';
  echo '<a href="?cart=true&name='.$prod2['name'].'" class=btn2>';
  echo 'Add to cart<span class=bg></span>';
  echo'</a>';
  echo'</form>';
  echo "</div>";
  if ($i++ == 3) break;
  }
  
}
}
echo'</div>';
echo'</body></html>';
?>
<?php
    outputFooter();
?>

