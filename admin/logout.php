<?php
session_start(); 


session_unset(); 
session_destroy(); 

// Redirect to the index page
header("Location: ../index.php");
exit;
?>
