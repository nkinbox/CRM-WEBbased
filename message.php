<?php
include 'lib.php';
if(!sessioncheck()) {
header('Location: login.php');
exit;
}
if(empty($_POST['id']) || empty($_POST['message'])) {
header('Location: mail.php');
exit;
}
$error=sendmessage();
if($error) {
header('Location: mail.php');
exit;
}
header('Location: mail.php?id=' .$_POST['id']. '&n=' .$_POST['n']);
exit;
?>