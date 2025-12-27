<?php
session_start();
session_unset();  
session_destroy(); 

header("Location: ../loginForms/owner/ownerlogin.php");
exit();
?>