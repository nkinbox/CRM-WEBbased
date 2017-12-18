<?php 
include 'lib.php';
if(!sessioncheck()) {
header('Location: login.php');
exit;
}
if(!empty($_GET['id']) && strlen($_GET['id']) == 40) {
$error=downloadfile();
if(!$error){
header('Content-type: application/octet-stream');
header('Content-Disposition: attachment; filename="' .$filename. '"');
readfile("jvjjvyykjbcvomooybcdjki/" .$_GET['id']);
} else {
header('Location: mail.php');
exit;
}
}
?>
