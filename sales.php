<?php
include 'lib.php';
if(!sessioncheck()) {
header('Location: login.php');
exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>UMH | Kismet Technical Services</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/bootstrap.min.css">
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jsscript.js"></script>
<script>
var i=false;
function newDoc() {
setTimeout(function() { if(i) window.location.assign("index.php"); }, 1000);
}
</script>
</head>
<body onload="newDoc()">

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span> 
      </button>
      <a class="navbar-brand" href="#">Kismet Technical Services</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
        <li><a href="customer.php">+ Customer</a></li>
        <li><a href="mail.php">Inbox<?php if(!empty($chatnum) && $chatnum != 0) echo " <span class='badge'>" .$chatnum. "</span>"; ?></a></li>
        <li><a href="account.php">Account</a></li>
<?php if($handler == "a") { ?>
        <li><a href="administrator.php">Admin</a></li>
<?php } ?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="out.php"><span class="glyphicon glyphicon-pause"></span> Pause</a></li>
        <li><a href="out.php?log=y"><span class="glyphicon glyphicon-log-out"></span> LogOut</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
<?php 
if(!empty($_POST)) {
$error = addsale();
if($error) { ?>
<div class="alert alert-danger">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error!</strong> Process was Interrupted.
</div>
<?php } else { ?>
<script>i=true;</script>
<div class="alert alert-success">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Done!</strong> Process Successful.
</div>
<?php }} if(empty($_GET['id'])){ if(empty($_POST)) { ?>
<div class="alert alert-danger">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error!</strong> No Customer Found.
</div>
<?php }} else {
customerdata($_GET['id']);
if(empty($customers)) { ?>
<div class="alert alert-danger">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error!</strong> No Customer Found.
</div>
<?php
} else {
?>
<div class="page-header">
  <h3>Sales Data</h3>
</div>

<form role="form" action="sales.php" method="post">
<input type="hidden" name="customerid" value="<?php echo $customers['customerid']; ?>">
 <div class="panel panel-primary">
    <div class="panel-heading">
<span class="badge">#<?php echo $customers['customerid']; ?></span> <?php echo $customers['salutation']. " " .$customers['fname']. " " .$customers['lname']; ?><kbd class="pull-right">#<?php echo $employeeid. " " .$name; ?></kbd>
</div>
<div class="panel-body">
<div class="row">
  <div class="col-sm-6">
<div class="form-group">
    <input name="amount" type="text" class="form-control" placeholder="Amount 0.00" required>
</div>
<div class="form-group">
    <input name="chequedetails" type="text" class="form-control" placeholder="Cheque Details" required>
</div>
<div class="form-group">
<select name="chequetype" class="form-control">
<option value="Phy. Cheque">Phy. Cheque</option>
<option value="Gateway">Gateway</option>
<option value="V. Cheque">V. Cheque</option>
</select>
</div>
<div class="form-group">
    <input name="plan" type="text" class="form-control" placeholder="Sale's Plan" required>
</div>
<div class="form-group">
    <input name="paymenttakenby" type="text" class="form-control" placeholder="Payment Taken By" required>
</div>



</div>
  <div class="col-sm-6">
<div class="row">
<div class="col-sm-12">
<textarea class="form-control" style="resize:none; margin:5px; height: 600px" name="comment" placeholder="Sales Notes" wrap="hard" required></textarea>
</div>
</div>
</div>
</div>
</div>
<div class="panel-footer" style="background-color:#337AB7">
  <button type="submit" class="btn btn-default">Submit</button>
</div>
  </div>

</form>


<?php }} ?>
</div>
</body>
</html>