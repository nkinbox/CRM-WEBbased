<?php
include 'lib.php';
if(!sessioncheck()) {
header('Location: login.php');
exit;
}
$customers=array();
if($handler == "t") {
header('Location: index.php');
exit;
}
if($handler == "a" && !empty($_GET['id'])) {
customerdata($_GET['id']);
}
if(!empty($_POST)) {
$error=AEcustomer();
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
setTimeout(function() { if(i) window.location.assign("index.php<?php if(!empty($cid)) echo "?query=" .$cid; ?>"); }, 1000);
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
        <li class="active"><a href="customer.php">+ Customer</a></li>
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
if($error) {?>
<div class="alert alert-danger">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error!</strong> Process was Interrupted.
</div>
<?php } else {?>
<script>i=true;</script>
<div class="alert alert-success">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Done!</strong> Process was successful.
</div>
<?php
}}
?>
<div class="page-header">
  <h3>Customer Details</h3>
</div>

<form role="form" action="customer.php" method="post">
<?php
if(!empty($customers['customerid']))
echo "<input type='hidden' name='customerid' value='" .$customers['customerid']. "'>";
?>
 <div class="panel panel-primary">
    <div class="panel-heading">
<span class="badge">#<?php
if(!empty($customers['customerid']))
echo $customers['customerid'];
else
echo "CustomerId";
?></span><kbd class="pull-right">#<?php
if(!empty($customers['name']))
echo $customers['name'];
else
echo "CustomerOf";
?></kbd>
</div>
    <div class="panel-body">
<div class="row well">
<div class="col-xs-2">
<select name="salutation" class="form-control">
<option value="Mr"<?php if(!empty($customers['salutation']) && $customers['salutation'] == "Mr") echo " selected"?>>Mr</option>
<option value="Mrs"<?php if(!empty($customers['salutation']) && $customers['salutation'] == "Mrs") echo " selected"?>>Mrs</option>
<option value="Ms"<?php if(!empty($customers['salutation']) && $customers['salutation'] == "Ms") echo " selected"?>>Ms</option>
</select>
</div>
<div class="col-xs-3">
<input name="fname" type="text" class="form-control" placeholder="First Name" <?php
if(!empty($customers['fname']))
echo "value='" .$customers['fname']. "' ";
?>required>
</div>
<div class="col-xs-3">
<input name="lname" type="text" class="form-control" placeholder="Last Name" <?php
if(!empty($customers['lname']))
echo "value='" .$customers['lname']. "' ";
?>required>
</div>
</div>

<div class="row well">
<div class="col-xs-4">
<input name="email" type="text" class="form-control" placeholder="EmailID" <?php
if(!empty($customers['email']))
echo "value='" .$customers['email']. "' ";
?>required>
</div>
<div class="col-xs-2">
<input name="phone" type="text" class="form-control" placeholder="Phone Number" <?php
if(!empty($customers['phone']))
echo "value='" .$customers['phone']. "' ";
?>required>
</div>
<div class="col-xs-2">
<input name="altphone" type="text" class="form-control" placeholder="Alt. Phone" <?php
if(!empty($customers['altphone']))
echo "value='" .$customers['altphone']. "'";
?>>
</div>
</div>

<div class="row well">
<div class="col-xs-6">
<textarea class="form-control" style="resize:none; height: 100px" name="address" placeholder="Customer Address" wrap="hard" required><?php
if(!empty($customers['address']))
echo $customers['address'];
?></textarea>
</div>
<div class="col-xs-2">
<select name="country" class="form-control">
<option value="US"<?php if(!empty($customers['country']) && $customers['country'] == "US") echo " selected"?>>US</option>
<option value="CA"<?php if(!empty($customers['country']) && $customers['country'] == "CA") echo " selected"?>>CA</option>
<option value="UK"<?php if(!empty($customers['country']) && $customers['country'] == "UK") echo " selected"?>>UK</option>
<option value="AU"<?php if(!empty($customers['country']) && $customers['country'] == "AU") echo " selected"?>>AU</option>
</select>
</div>
</div>
</div>
<div class="panel-footer" style="background-color:#337AB7">
  <button type="submit" class="btn btn-default">Submit</button>
</div>
  </div>

</form>



</div>

</body>
</html>