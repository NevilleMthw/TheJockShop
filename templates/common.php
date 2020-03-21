<?php

function outputHeader($title){
    echo '<!DOCTYPE html>';
    echo '<html>';
    echo '<head>';
    echo '<title>' . $title . '</title>';
    echo '<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">';
    echo '<link rel="stylesheet" href="../Stylesheets/style.css">';
   echo '<link href="https://fonts.googleapis.com/css?family=Oswald&display=swap" rel="stylesheet">';
   echo '<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">';
    echo '</head>';
    echo '<body>';
}
function outputBannerNavigation(){
    //Output navigation

    echo '<div class="main"> 
    <header>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="products.php">Products</a></li>
        <li> <img class ="logo" src="../images/logo.png"> </a></li>
        <li><a href="account.php">Account</a></li>
        <li><a href="cart.php">Cart</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
     
    </header>

    </div> <hr> ';
}

function outputFooter() {
  echo'<footer>
  Copyrighted by The Jock Shop 2020.©
  </footer>'  ;
}
?>