<?php
include 'lib.php';
if(!sessioncheck()) {
header('Location: login.php');
exit;
}
if($handler != "a") {
header('Location: index.php');
exit;
}
$month = date('m');
$year = date('Y');
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
administrator($month,$year);
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
        <li><a href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
        <li><a href="customer.php">+ Customer</a></li>
        <li><a href="mail.php">Inbox<?php if(!empty($chatnum) && $chatnum != 0) echo " <span class='badge'>" .$chatnum. "</span>"; ?></a></li>
        <li><a href="account.php">Account</a></li>
        <li class="active"><a href="administrator.php">Admin</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="out.php"><span class="glyphicon glyphicon-pause"></span> Pause</a></li>
        <li><a href="out.php?log=y"><span class="glyphicon glyphicon-log-out"></span> LogOut</a></li>
      </ul>
    </div>
  </div>
</nav>


<div class="row">
<div class="col-sm-6">
<div class="well well-sm">
  <a href="employee.php" class="btn btn-default" role="button"><span class="glyphicon glyphicon-user"></span> New Employee</a>
</div>
<div class="panel-group" id="accordion2">
<?php $i=0; foreach($group as $key=>$val) { ?>
  <div class="panel panel-info">
    <div class="panel-heading">
      <span class="panel-title">
        <a class="btn btn-default btn-xs" role="button" data-toggle="collapse" data-parent="#accordion2" href="#collapset<?php echo $i; ?>">
        <span class="glyphicon glyphicon-option-vertical"></span></a>
      </span>&nbsp;
<kbd><?php echo $key; ?></kbd>
<span class="pull-right badge"><?php echo sizeof($val);?></span>
    </div>
    <div id="collapset<?php echo $i; ?>" class="panel-collapse collapse">
      <div class="panel-body">
<?php foreach($val as $emp) { ?>
<div class="dropdown" style="margin:3px">
  <button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown"><span class="badge">#<?php echo $emp['employeeid']; ?></span> <?php echo $emp['name']; ?></button>
  <ul class="dropdown-menu">
    <li><a href="account.php?id=<?php echo $emp['employeeid']; ?>"><span class="glyphicon glyphicon-eye-open"></span> Show</a></li>
    <li><a href="mail.php?switchinbox=<?php echo $emp['employeeid']; ?>"><span class="glyphicon glyphicon-envelope"></span> Inbox</a></li>
    <li><a href="employee.php?eid=<?php echo $emp['employeeid']; ?>"><span class="glyphicon glyphicon-edit"></span> Edit</a></li>
  </ul>
</div>
<?php } ?>
      </div>
    </div>
  </div>
<?php $i++; } ?>
</div>
</div>
<div class="col-sm-6">
<div class="well well-sm">
<form class="form-inline" role="form" action="administrator.php" method="get">
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
      <input name="y" type="text" class="form-control" placeholder="YYYY" value="<?php echo $year; ?>">
      <span class="input-group-btn">
        <button class="btn btn-default" type="submit">Go</button>
      </span>
    </div>
</form>
</div>


<ul class="list-group">
  <li class="list-group-item"><span class="badge"><?php echo $stats['total']; ?></span> Total <kbd>$<?php echo $stats['totala']; ?></kbd></li>
  <li class="list-group-item"><span class="badge"><?php echo $stats['pending']; ?></span> Pending <kbd>$<?php echo $stats['pendinga']; ?></kbd></li> 
  <li class="list-group-item"><span class="badge"><?php echo $stats['rejected']; ?></span> Rejected <kbd>$<?php echo $stats['rejecteda']; ?></kbd></li> 
  <li class="list-group-item"><span class="badge"><?php echo $stats['success']; ?></span> Success <kbd>$<?php echo $stats['successa']; ?></kbd></li>
</ul>


</div>
</div>


<div class="page-header">
  <h4>&nbsp; Sales Data</h4>
</div>


<div class="panel-group" id="accordion">
<?php foreach($sales as $key=>$sale) { ?>
  <div class="panel panel-warning" style="border-color:#D09A2A">
    <div class="panel-heading">
      <span class="panel-title">
        <a class="btn btn-default btn-xs" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $key; ?>">
        <span class="glyphicon glyphicon-option-vertical"></span></a>
      </span>&nbsp;
<kbd><?php echo $sale['saledate']; ?></kbd>&nbsp;&nbsp;<span class="label label-primary" style="font-size:90%">$<?php echo $sale['amount']; ?></span> <a href="index.php?query=<?php echo $sale['customerid']; ?>"><?php echo $sale['salutation']; ?> <?php echo $sale['fname']; ?> <?php echo $sale['lname']; ?></a>
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
      <div class="panel-footer">
        <a href="editsales.php?sid=<?php echo $sale['saleid']?>" class="btn btn-default" role="button"><span class="glyphicon glyphicon-edit"></span> Edit Data</a>
      </div>
    </div>
  </div>

<?php } ?>
</div>
</body>
</html>