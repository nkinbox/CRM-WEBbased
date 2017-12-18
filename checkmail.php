<?php
echo "0";
exit;
include 'lib.php';
if(!sessioncheck()) {
echo "0";
exit;
}
echo $chatnum;
exit;
?>