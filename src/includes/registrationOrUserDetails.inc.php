<!-- Generated with PHP to take login status into account -->
<?php
  if( !(isset($_SESSION['login']) && $_SESSION['login'] != '') ) {
      echo '<li><a href="registration.php">Sign Up</a></li>';   
      echo '<li><a href="#" id="loginBtn">Login</a></li>'; 
      session_unset(); 
  }
  else {
      echo '<li><a href="userDetails.php">Your Account</a></li>  ';
      echo '<li><a href="javascript:confirm_logout();">Log out</a></li>  ';
  }
?> 