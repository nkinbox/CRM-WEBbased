<?php
include 'lib.php';
if(!sessioncheck()) {
header('Location: login.php');
exit;
}
$month = date('m');
$year = date('Y');
if(!empty($_GET['id']) && $handler == "a") {
$employeeid = $_GET['id'];
}
if(!empty($_GET['m']) && !empty($_GET['y'])) {
switch(intval($_GET['m'])) {
case 1:
$month = "01";
break;
case 2:
$month = "02";
break;
case 3:
$month = "03";
break;
case 4:
$month = "04";
break;
case 5:
$month = "05";
break;
case 6:
$month = "06";
break;
case 7:
$month = "07";
break;
case 8:
$month = "08";
break;
case 9:
$month = "09";
break;
case 10:
$month = "10";
break;
case 11:
$month = "11";
break;
case 12:
$month = "12";
}
$year = intval($_GET['y']);
}
employeedata($employeeid,$month,$year);
if(empty($employees)) {
header('Location: account.php');
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
function umhtoggle(e) {
var id = $(e).attr('id');
$('.fff').hide();
$('.cii' + id).show();
}
</script>
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
        <li><a href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
        <li><a href="customer.php">+ Customer</a></li>
        <li><a href="mail.php">Inbox<?php if(!empty($chatnum) && $chatnum != 0) echo " <span class='badge'>" .$chatnum. "</span>"; ?></a></li>
        <li class="active"><a href="account.php">Account</a></li>
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


<div class="panel panel-primary">
<div class="panel-heading"><span class="badge">#<?php echo $employees['employeeid']; ?></span> <?php echo $employees['name']; ?><kbd class="pull-right"><?php
switch($employees['handler']) {
case "a":
echo "Administrator";
break;
case "t":
echo "Technician";
break;
case "s":
echo "SalesAgent";
}
?></kbd></div>
<div class="panel-body">
<div class="row">
<div class="col-sm-3">
<div class="badge"><span class="glyphicon glyphicon-envelope"></span> <?php echo $employees['email']; ?></div>
<div style="margin:10px 0 10px 0;"><kbd><span class="glyphicon glyphicon-earphone"></span> <?php echo $employees['phone']; ?></kbd></div>
</div><div class="col-sm-6">

<div class="well well-sm">
<form class="form-inline" role="form" action="account.php" method="get">
  <div class="form-group">
    <select class="form-control" name="m">
<option value="1"<?php if($month == "01") echo " selected"; ?>>Jan</option>
<option value="2"<?php if($month == "02") echo " selected"; ?>>Feb</option>
<option value="3"<?php if($month == "03") echo " selected"; ?>>Mar</option>
<option value="4"<?php if($month == "04") echo " selected"; ?>>Apr</option>
<option value="5"<?php if($month == "05") echo " selected"; ?>>May</option>
<option value="6"<?php if($month == "06") echo " selected"; ?>>Jun</option>
<option value="7"<?php if($month == "07") echo " selected"; ?>>Jul</option>
<option value="8"<?php if($month == "08") echo " selected"; ?>>Aug</option>
<option value="9"<?php if($month == "09") echo " selected"; ?>>Sep</option>
<option value="10"<?php if($month == "10") echo " selected"; ?>>Oct</option>
<option value="11"<?php if($month == "11") echo " selected"; ?>>Nov</option>
<option value="12"<?php if($month == "12") echo " selected"; ?>>Dec</option>
</select>
  </div>
<div class="input-group col-xs-3">
<input type="hidden" name="id" value="<?php echo $employeeid; ?>">
      <input name="y" type="text" class="form-control" placeholder="YYYY" value="<?php echo $year; ?>">
      <span class="input-group-btn">
        <button class="btn btn-default" type="submit">Go</button>
      </span>
    </div>
</form>
</div>

<div class="panel-group pre-scrollable" id="accordion2" style="height: 120px; resize: vertical">
<?php foreach($logins as $key=>$login) { ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <span class="panel-title">
        <a class="btn btn-default btn-xs" role="button" data-toggle="collapse" data-parent="#accordion2" href="#collapset<?php echo $key; ?>">
        <span class="glyphicon glyphicon-option-vertical"></span></a>
      </span>&nbsp;
<kbd><?php echo date_format(date_create_from_format('Y-m-d H:i:s', $login['login']),"d | H:i:s"); ?></kbd>&nbsp;&nbsp;<a href="#" id="ii<?php echo $key; ?>" onclick="umhtoggle(this)" class="btn btn-info" role="button"><span class="glyphicon glyphicon-time"></span> <?php echo $login['workingtime']; ?></a>
&nbsp;&nbsp;<span class="label label-warning" style="font-size:90%"><span class="glyphicon glyphicon-time"></span> <?php echo $login['breaktime']; ?></span> <span class="badge"><?php echo $login['totalbreaks']; ?></span>
<kbd class="pull-right"><?php echo date_format(date_create_from_format('Y-m-d H:i:s', $login['logout']),"d | H:i:s"); ?></kbd>
    </div>
    <div id="collapset<?php echo $key; ?>" class="panel-collapse collapse">
      <div class="panel-body">

<ul class="list-group">
  <li class="list-group-item"><span class="badge"><?php echo $login['total']; ?></span> Total <kbd>$<?php echo $login['totala']; ?></kbd></li>
  <li class="list-group-item"><span class="badge"><?php echo $login['pending']; ?></span> Pending <kbd>$<?php echo $login['pendinga']; ?></kbd></li> 
  <li class="list-group-item"><span class="badge"><?php echo $login['rejected']; ?></span> Rejected <kbd>$<?php echo $login['rejecteda']; ?></kbd></li> 
  <li class="list-group-item"><span class="badge"><?php echo $login['success']; ?></span> Success <kbd>$<?php echo $login['successa']; ?></kbd></li>
</ul>

      </div>

    </div>
  </div>

<?php } ?>
</div>
</div><div class="col-sm-3">

<ul class="list-group">
  <li class="list-group-item"><span class="badge"><?php echo $stats['total']; ?></span> Total <kbd>$<?php echo $stats['totala']; ?></kbd></li>
  <li class="list-group-item"><span class="badge"><?php echo $stats['pending']; ?></span> Pending <kbd>$<?php echo $stats['pendinga']; ?></kbd></li> 
  <li class="list-group-item"><span class="badge"><?php echo $stats['rejected']; ?></span> Rejected <kbd>$<?php echo $stats['rejecteda']; ?></kbd></li> 
  <li class="list-group-item"><span class="badge"><?php echo $stats['success']; ?></span> Success <kbd>$<?php echo $stats['successa']; ?></kbd></li>
</ul>

</div>
</div>
</div>
</div>


<div class="page-header">
  <h4>&nbsp; Sales Record</h4>
</div>


<div class="panel-group" id="accordion">
<?php foreach($sales as $key=>$sale) { ?>
  <div class="panel panel-warning fff ciiii<?php echo $sale['id']; ?>" style="border-color:#D09A2A">
    <div class="panel-heading">
      <span class="panel-title">
        <a class="btn btn-default btn-xs" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $key; ?>">
        <span class="glyphicon glyphicon-option-vertical"></span></a>
      </span>&nbsp;
<kbd><?php echo $sale['saledate']; ?></kbd>&nbsp;&nbsp;<span class="label label-primary" style="font-size:90%">$<?php echo $sale['amount']; ?></span> <a href="index.php?query=<?php echo $sale['customerid']; ?>"><?php echo $sale['salutation']. " " .$sale['fname']. " " .$sale['lname']; ?></a>
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
    <div id="collapse<?php echo $key; ?>" class="panel-collapse collapse">
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

    </div>
  </div>



<?php } ?>
</div>


</body>
</html>