<?php
    //Include the PHP functions to be used on the page 
    include('common.php');
	
    //Output header and navigation 
    outputHeader('Welcome Page');
    
?>
<?php
    outputBannerNavigation();
    
    $mongoClient = new MongoClient();
    $db = $mongoClient->thejockshop;
    $cart = $db->cart->find();

    function delete(){
        $mongoClient = new MongoClient();
        $db = $mongoClient->thejockshop;
        $cart = $db->cart;
        
        $deleteResult = $cart->remove();
        echo'<script>window.location.replace("cart.php")</script>';


        
    }
if ($cart->count()==0){
    echo'<div class="wrap cf">
    <h1 class="projTitle">Shopping Cart</h1>
    <div class="heading cf">';
    echo'<a href="products.php" class="continue">Continue Shopping</a>';
echo '<div>';
    echo '<div class="cart">';
    echo'<p> The cart is empty. Add something now! </p>';
}
else{
    if(isset($_GET['del'])){
        delete();
    }
   echo' <div class="wrap cf">
    <h1 class="projTitle">Shopping Cart</h1>
    <div class="heading cf">';
    echo'<a href="cart.php?del=true" class="continue">Empty Cart</a>';
    echo'<a href="products.php" class="continue">Continue Shopping</a>';
echo '<div>';
    echo '<div class="cart">';
    $total = 0;
    function deleteItem($pname){
        $mongoClient = new MongoClient();
        $db = $mongoClient->thejockshop;
        $cart = $db->cart;
        $pname = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING);
        $deleteResult = $cart->remove( array( 'name' => $pname ) );
        echo'<script>window.location.replace("cart.php")</script>';
    }
    function add($pname){
        $mongoClient = new MongoClient();
        $db = $mongoClient->thejockshop;
        $cart = $db->cart;
        $pname = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING);
        $quantity = filter_input(INPUT_GET, 'quantity', FILTER_SANITIZE_STRING);
        $quantity++;
        
        $newdata1 = array('$set' => array("name" => $pname, "quantity" => $quantity));
        
        $condition = array("name" =>$pname);
        $update = $cart->update($condition, $newdata1);
    }

    function minus($pname){
        $mongoClient = new MongoClient();
        $db = $mongoClient->thejockshop;
        $cart = $db->cart;
        $quantity = filter_input(INPUT_GET, 'quantity', FILTER_SANITIZE_STRING);
        $pname = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING);
        if ($quantity<=1){
            $deleteResult = $cart->remove( array( 'name' => $pname ) );
            echo'<script>window.location.replace("cart.php")</script>';
        }
       
        else {
            $quantity--;
        }
        $newdata1 = array('$set' => array("name" => $pname, "quantity" => $quantity));
   
        
        
        $condition = array("name" =>$pname);
        $update = $cart->update($condition, $newdata1);
    }

foreach ($cart as $item){
    $name = $item['name'];
                   
                
                    if(isset($_GET['delete'])){
                        deleteItem($name);
  }            
  if(isset($_GET['minusq'])){
    minus($name);
}  
if(isset($_GET['addq'])){
    add($name);
}      
}

    foreach ($cart as $item){

$subTotal = $item['quantity']*$item['price'];
echo '<ul class="cartWrap">';
echo '<li class="items odd">';
echo '<div class="infoWrap">';
echo '<div class="cartSection">';
echo '<img src="'.$item['img'].'" class="itemImg" />';
echo '<p class="itemCategory">'.$item['category'].'</p>';
echo '<h3>'.$item['name'].'</h3>';
echo '<a href="cart.php?minusq=true&quantity='.$item['quantity'].'&name='.$item['name'].'"'.$item['name'].'" class="remove">-</a>';
echo ''.$item['quantity'].' <a href="cart.php?addq=true&quantity='.$item['quantity'].'&name='.$item['name'].'" class="remove">+</a>x $'.$item['price'].'';
echo '</div>';
echo '<div class="prodTotal cartSection">';
echo '<p>$'.$subTotal.'</p>';
echo '</div>';
echo '<div class="cartSection removeWrap">';
echo '<a href="cart.php?delete=true&name='.$item['name'].'" class="remove">x</a>';
echo '</div>';
echo '</div>';
echo'</li>';
echo'</ul>';
echo'<hr>';
  
$total += $item['price']*$item['quantity'];
    
}



echo '<div class="subtotal cf">';
echo '<ul> <li class="totalRow final"><span class="label">Total</span><span class="value">$'.$total.'</span></li>';
echo '<li class="totalRow"><a href="checkout.php" class="continue-btn continue">Checkout</a></li>';
echo '</ul>';
echo '</div>';
echo '</div>';
echo '</div';


}
?>
