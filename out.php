<?php
include 'lib.php';
if(!empty($_GET['log']))
logout();
else
pause();
header('Location: login.php');
exit;
?>