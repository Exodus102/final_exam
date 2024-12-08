<?php
session_start();

// Destroy the session
session_unset();
session_destroy();

// Redirect to the login page
header("Location: ../../pages/login/loginv2.php");
exit;
?>