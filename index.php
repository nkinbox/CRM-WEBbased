<?php include 'lib.php';
if(!sessioncheck()) {
header('Location: login.php');
exit;
}
if(!empty($_POST['sid'])) {
addnotes();
header('Location: index.php?query=' .$_POST['query']);
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
</head>
<body>

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
        <li class="active"><a href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
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



<div class="panel panel-default">
  <div class="panel-body">
<div class="row">
  <div class="col-sm-4">
<form role="form" method="get" action="index.php">
  <div class="input-group">
      <input name="query" type="text" class="form-control" placeholder="Search Customer" autofocus>
      <span class="input-group-btn">
        <button type="submit" class="btn btn-default" type="button">Go</button>
      </span>
    </div>
  </form>
</div>
  <div class="col-sm-8"><code>CustomerID, Phone, AltPhone, Email, #keyword</code></div>
</div>
</div>
</div>
<?php if(!empty($_GET['query'])) {
search($_GET['query']);
if(empty($customers)) {
?>

<div class="alert alert-danger">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error!</strong> No Customer Found.
</div>

<?php } else {
if($hash) {
foreach($customers as $customer) {
echo "<div class='well'><a href='index.php?query=" .$customer["customerid"]. "'>" .$customer["salutation"]. " " .$customer["fname"]. " " .$customer["lname"]. "</a> &nbsp;&nbsp; <kbd><span class='glyphicon glyphicon-envelope'></span> " .$customer['email']. "</kbd> &nbsp;&nbsp; <kbd><span class='glyphicon glyphicon-earphone'></span> " .$customer['phone']. " " .$customer['altphone']. "</kbd></div>";
}
} else {
?>

<div class="panel panel-primary">
<div class="panel-heading"><span class="badge">#<?php echo $customers['customerid'];?></span> <?php echo $customers['salutation'] . " " .$customers['fname']. " " .$customers['lname'];?><kbd class="pull-right">#<?php echo $customers['addedby']. " " .$customers['name'];?></kbd></div>
<div class="panel-body">
<div class="row">
<div class="col-sm-4">
<div style="padding:4px"><div class="badge"><span class="glyphicon glyphicon-envelope"></span> <?php echo $customers['email'];?></div></div>
<div style="padding:4px"><div class="badge"><span class="glyphicon glyphicon-bookmark"></span> <?php echo $customers['password'];?></div></div>
</div><div class="col-sm-4">
<div><kbd><span class="glyphicon glyphicon-earphone"></span> <?php echo $customers['phone'];?></kbd></div>
<div>
<?php if($customers['altphone'] != null) {?>
<kbd><span class="glyphicon glyphicon-earphone"></span> <?php echo $customers['altphone'];?></kbd>
<?php }?>
</div>
</div><div class="col-sm-4">
<pre><?php echo $customers['address'];?> <kbd><?php echo $customers['country'];?></kbd></pre>
</div>
</div>
</div>
<div class="panel-footer">
<?php if($handler == "a") {?>
<a href="customer.php?id=<?php echo $customers['customerid'];?>" class="btn btn-default" role="button"><span class="glyphicon glyphicon-edit"></span> Edit Details</a>
<?php }?>
<a href="sales.php?id=<?php echo $customers['customerid'];?>" class="btn btn-default" role="button"><span class="glyphicon glyphicon-tag"></span> Add Sale</a>
<a href="index.php?query=<?php echo $customers['customerid'];?>&key=yes" class="btn btn-default" role="button"><span class="glyphicon glyphicon-tag"></span> +Key</a>
</div>
</div>


<div class="page-header">
  <h4>&nbsp; Sales History</h4>
</div>


<div class="panel-group" id="accordion">
<?php $i=1; foreach($sales as $sale) {?>
  <div class="panel panel-warning" style="border-color:#D09A2A">
    <div class="panel-heading">
      <span class="panel-title">
        <a class="btn btn-default btn-xs" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i;?>">
        <span class="glyphicon glyphicon-option-vertical"></span></a>
      </span>&nbsp;
<kbd><?php echo $sale['saledate'];?></kbd>&nbsp;&nbsp;<span class="label label-primary" style="font-size:90%">$<?php echo $sale['amount'];?></span>
<span class='pull-right label label-<?php
switch($sale['status']) {
case "p":
echo "default'>Pending";
break;
case "r":
echo "danger'>Rejected";
break;
case "s":
echo "success'>Success";
}?></span>
    </div>
    <div id="collapse<?php echo $i;?>" class="panel-collapse collapse">
      <div class="panel-body">
<div class="row">
<div class="col-sm-4">
<pre><?php echo $sale['plan'];?>

-<?php echo $sale['name'];?></pre>
<div class="well"><span class="glyphicon glyphicon-hand-right"></span>&nbsp;<kbd><?php echo $sale['chequedetails'];?></kbd><span class="label label-warning pull-right"><?php echo $sale['chequetype'];?></span>
<div class='text-muted' style="padding-top:10px">- <?php echo $sale['paymenttakenby'];?></div>
</div>
</div>
  <div class="col-sm-8">
<pre class="pre-scrollable">
<?php
usort($notes[$sale['saleid']], function($a, $b) {
    return $a['commentid'] - $b['commentid'];
});
foreach($notes[$sale['saleid']] as $n) {
echo $n['comment'] . "<br><h6>-By " .$n['name']. "</h6>___________________________________________________________________________________<br>";
}
?>
</pre>
</div>
</div>
      </div>
      <div class="panel-footer">
<?php if($handler == "a") { ?>
        <a href="editsales.php?sid=<?php echo $sale['saleid']?>" class="btn btn-default" role="button"><span class="glyphicon glyphicon-edit"></span> Edit Data</a>
<?php } ?>
        <a href="#" class="btn btn-default" role="button" data-toggle="modal" data-target="#myModal<?php echo $i;?>"><span class="glyphicon glyphicon-list-alt"></span> Add Notes</a>
<div class="modal fade" id="myModal<?php echo $i;?>" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h5 class="modal-title">Add Notes</h5>
        </div>
        <div class="modal-body">
<form role="form" action="index.php" method="post">
<textarea class="form-control" style="resize:none; margin:5px; height: 500px" name="comment" placeholder="Sales Notes" wrap="hard" required></textarea>
<input type="hidden" name="sid" value="<?php echo $sale['saleid']?>">
<input type="hidden" name="query" value="<?php echo $_GET['query']; ?>">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-default">Add</button>
        </div>
</form>
      </div>
    </div>
</div>
      </div>
    </div>
  </div>
<?php $i++;}?>
</div>
<?php }}}?>

<?php
if(!empty($_GET['key']) && $_GET['key'] == "yes") {
?>
<div id="keyholder" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">UzitNow Optimizer Key</h4>
      </div>
      <div class="modal-body">
        <p>
<?php
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,  "http://www.kismetsoftwaresolutions.com/key.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "_password=marijuanaa&customer_name=" .$customers['fname'] .
$customers['lname']. "&customer_phone=" .$customers['customerid']);
$buffer = curl_exec($ch);
if(empty ($buffer))
{ echo "Error Generating Key.."; }
else
{ echo $buffer; }
curl_close($ch);
?>
</p>
      </div>
    </div>
  </div>
</div>
<script>
$("#keyholder").modal();
</script>
<?php } ?>

</body>
</html>
