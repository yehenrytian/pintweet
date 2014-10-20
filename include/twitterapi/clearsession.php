<?php
/**
 * @file
 * Clears PHP sessions and redirects to the connect page.
 */
 
/* Load and clear sessions */
session_start();

if (isset($_GET['twitter']) && $_GET['twitter'] == 'clear') {
  unset($_SESSION['access_token']);
  unset($_SESSION['twitter']);
 
  $_SESSION = array();
  // remove the session cookie
  if (isset($_COOKIE[session_name()]))
     setcookie(session_name(), '', time() - 45000); 
  session_destroy();
   
  header('Location: ../../');
}
else{
$_SESSION = array();

// remove the session cookie
if (isset($_COOKIE[session_name()]))
   setcookie(session_name(), '', time() - 45000); 

session_destroy();
/* Redirect to page with the connect to Twitter option. */
header('Location: ../../');
}

?>