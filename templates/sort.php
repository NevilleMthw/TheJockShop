<?php

include('common.php');

//Output header and navigation 
outputHeader('Our Products');
outputBannerNavigation();

echo '<form class="search-box" action="search_results.php" method="get">
<input type="text" placeholder="Search here..." name= "name" required />
<button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
  </form>'; 
session_start();
$mongoClient = new MongoClient();
$db = $mongoClient->thejockshop;
$collection = $db->products;

function addToCart(){
  $mongoClient = new MongoClient();
  $db = $mongoClient->thejockshop;
  $collection=$db->cart;
  $name= filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING);
  $search_string= $name;
  $findCriteria = [
    '$text' => [ '$search' => $search_string ] 
 ];
 $cursor = $db->products->find($findCriteria);
$quantity=1;
if ($_SESSION['loggedInUsername']!=NULL){
 foreach ($cursor as $prod){
  $document = array( 
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

}
function redirect() {
  echo header("location: cart.php");
}

if(isset($_GET['cart'])){
  addToCart();
  redirect();
}

if(isset($_POST["desc"])) {
    $sortCursor = $collection->find ()->sort(array('price'=>-1));
}


else if (isset($_POST["asc"])) {
    $sortCursor = $collection->find ()->sort(array('price'=>1));
}

else if (isset($_POST["atoz"])) {
    $sortCursor = $collection->find ()->sort(array('name'=>1));
}

else if (isset($_POST["ztoa"])) {
    $sortCursor = $collection->find ()->sort(array('name'=>-1));
}


echo '<form action="sort.php" method="POST" />
<input type="submit" value="Price: High to Low" name="desc" class="sort red"/>
  <input type="submit" value="Price: Low to High" name="asc" class="sort green"/>
  <input type="submit" value="Product Name: A-Z" name="atoz" class = "sort red"/>
  <input type="submit" value="Product Name: Z-A" name="ztoa" class = "sort green"/>
</form>';
echo '<div class=wrapper>';
foreach ($sortCursor as $prod){
   
   echo "<div class=shop-card>";
   echo '<a style="text-decoration: none; color: #23211f;" href="product.php?name='.$prod['name'].'">';
   echo "<div class=title>" . $prod['name'];
   echo '</a>';
   echo "</div>";
   echo "<div class=desc>" . $prod['category'];
   echo "</div>";
   echo '<a href="product.php?name='.$prod['name'].'">';
   echo "<img id=imgstyle src=". $prod['img'] . ">";
   echo '</a>';
   echo "<div class=price> $". $prod['price'];
   echo "</div>";
   echo'<form method="POST">';
   echo '<a href="?cart=true&name='.$prod['name'].'" class=btn2>';
   echo 'Add to cart<span class=bg></span>';
   echo'</a>';
   echo'</form>';
   echo "</div>";
   
   
}
echo"</div>";
?>
<?php
    outputFooter();
?>