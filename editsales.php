<?php
include 'lib.php';
if(!sessioncheck()) {
header('Location: login.php');
exit;
}
if($handler != "a") {
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
$error = editsale();
if($error) { ?>
<div class="alert alert-danger">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error!</strong> Process was Interrupted.
</div>
<?php } else { ?>
<script>i=true;</script>
<div class="alert alert-success">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Done!</strong> Process successful.
</div>
<?php }} if(empty($_GET['sid'])) { if(empty($_POST)){ ?>
<div class="alert alert-danger">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error!</strong> No Data Found.
</div>
<?php }} else {
getsalesdata($_GET['sid']);
if(empty($saledata)) { ?>
<div class="alert alert-danger">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error!</strong> No Data Found.
</div>
<?php } else { ?>
<div class="page-header">
  <h3>Sales Data</h3>
</div>

<form role="form" action="editsales.php" method="post">
<input type="hidden" name="saleid" value="<?php echo $saledata['saleid']; ?>">
<input type="hidden" name="customerid" value="<?php echo $saledata['customerid']; ?>">
 <div class="panel panel-primary">
    <div class="panel-heading">
<span class="badge">#<?php echo $saledata['customerid']; ?></span> <?php echo $saledata['salutation']. " " .$saledata['fname']. " " .$saledata['lname']; ?><kbd class="pull-right">#<?php echo $saledata['saleagent']. " " .$saledata['name']; ?></kbd>
</div>
<div class="panel-body">
<div class="row">
  <div class="col-sm-6">
<div class="form-group">
    <input name="amount" type="text" class="form-control" placeholder="Amount 0.00" value="<?php echo $saledata['amount']; ?>" required>
</div>
<div class="form-group">
    <input name="chequedetails" type="text" class="form-control" placeholder="Cheque Details" value="<?php echo $saledata['chequedetails']; ?>" required>
</div>
<div class="form-group">
<select name="chequetype" class="form-control">
<option value="Phy. Cheque"<?php if($saledata['chequetype'] == "Phy. Cheque") echo " selected"; ?>>Phy. Cheque</option>
<option value="Gateway"<?php if($saledata['chequetype'] == "Gateway") echo " selected"; ?>>Gateway</option>
<option value="V. Cheque"<?php if($saledata['chequetype'] == "V. Cheque") echo " selected"; ?>>V. Cheque</option>
</select>
</div>
<div class="form-group">
    <input name="plan" type="text" class="form-control" placeholder="Sale's Plan" value="<?php echo $saledata['plan']; ?>" required>
</div>
<div class="form-group">
    <input name="paymenttakenby" type="text" class="form-control" placeholder="Payment Taken By" value="<?php echo $saledata['paymenttakenby']; ?>" required>
</div>
</div>
  <div class="col-sm-6">
<div class="row">
<div class="col-sm-12">
<div class="col-xs-6">
<select name="status" class="form-control">
<option value="p"<?php if($saledata['status'] == "p") echo " selected"; ?>>Pending</option>
<option value="s"<?php if($saledata['status'] == "s") echo " selected"; ?>>Success</option>
<option value="r"<?php if($saledata['status'] == "r") echo " selected"; ?>>Rejected</option>
</select>
</div>
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