<?php
/*
 * Filename:     loginScripts.php
 * Author:       Andrew Laing
 * Email:        parisianconnections@gmail.com
 * Last updated: 31/05/2019.
 * Description:  The Login Scripts page is a non-displayed page of PHP scripts 
 *               used by all other pages to allow site login functionality.
 */


/* The function checkPasswordIsValid checks whether a username/password
 * combination exists in the database. If yes it returns the users customerID,
 * otherwise it returns -1 */
function checkPasswordIsValid($usr, $pwd) {
  /* Connect to the DB */
  $mysqli = new mysqli("localhost","webuser","webuser","fooddb");
  
  $res = -1;
  
  /* check connection */
  if (mysqli_connect_errno()) {
    header('Location: errorPage.php');
    exit();
  }  
  else {
    $SQL = $mysqli->prepare('CALL checkPasswordIsValid(?,?)');
    $SQL->bind_param('ss', $usr, $pwd);
    $SQL->execute();
    $result = $SQL->get_result();

    if($result->num_rows == 1) {
      $db_field = $result->fetch_assoc();

       $res = $db_field['customerID'];
       echo '  <script>alert("Welcome back ' . $usr . '.");</script>'; 
    }
    else { // destroy the session 
      echo '  <script>alert("Error: Unable to login with the credentials supplied.");</script>';          
    }
  }

  $mysqli->close(); 
  
  return $res;
}  


/* If there is not an active session start one */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/* If the user is not logged in clear session variables */
if( !(isset($_SESSION['login']) && $_SESSION['login'] != '') ) {
    /* Clear session variables if set */
    session_unset(); 
}

/* If the user presses a submit button deal with it */
if( $_SERVER["REQUEST_METHOD"] == "POST" ) {
  /* If the login button was pressed, check for a valid userrname/password combination */
  if (isset($_POST['LoginBtn']) && isset($_POST['username']) && isset($_POST['pwd'])) {
    $usr1= $_POST['username'];
    $pwd = $_POST['pwd'];
    
    $customerID = checkPasswordIsValid($_POST['username'],$pwd);
    
    if($customerID != -1) {
        // remove all session variables
        session_unset(); 
        $_SESSION['login'] = "1";
        $_SESSION['uname'] = $usr1;  	
        $_SESSION['customerID'] = $customerID;
        header($_SERVER["PHP_SELF"]);
    }
    else {
        // remove all session variables
        session_unset(); 
        session_destroy(); 
        header($_SERVER["PHP_SELF"]);
    } 
  }
  else {
    echo '  <script>alert("Another button was pressed!!!!");</script>';
  }
}  
?>
