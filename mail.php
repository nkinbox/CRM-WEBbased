<?php
include 'lib.php';
if(!sessioncheck()) {
header('Location: login.php');
exit;
}
if(!empty($_GET['backtoinbox'])) {
backtoinbox();
header('Location: mail.php');
exit;
}
if(!empty($_GET['switchinbox'])) {
switchinbox($_GET['switchinbox']);
}
if(!empty($_COOKIE['inboxID'])) {
$val = explode(":",$_COOKIE['inboxID']);
$employeeid = $val[0];
$inboxname = $val[1];
$groupname = $val[2];
}
getchatgroup();
$error=true;
if(!empty($_GET['id'])) {
$error=getchat();
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
<style>
.sent {
margin:0 0 0 100px;
}
.received {
margin:0 100px 0 0;
}
</style>
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

<div class="row" style="margin:0">
    <div class="col-sm-4">


<div class="panel-group" id="accordion2">
<?php foreach($chatgroup as $key=>$val) { ?>
  <div class="panel panel-info">
    <div class="panel-heading">
      <span class="panel-title">
        <a class="btn btn-default btn-xs" role="button" data-toggle="collapse" data-parent="#accordion2" href="#collapset<?php echo $key; ?>">
        <span class="glyphicon glyphicon-option-vertical"></span></a>
      </span>&nbsp;
<kbd><?php echo $key; ?></kbd>
    </div>
    <div id="collapset<?php echo $key; ?>" class="panel-collapse collapse in">
      <div class="panel-body">
<?php foreach($val as $employee) {
if($employee['employeeid'] != $employeeid) { ?>
<ul class="list-group">
  <li class="list-group-item"><span class="badge"><?php echo $employee['num']; ?></span>  <a href="mail.php?id=<?php echo $employee['employeeid']; ?>&n=<?php echo $employee['name']; ?>"><span class="badge">#<?php echo $employee['employeeid']; ?></span> <?php echo $employee['name']; ?></a></li>
</ul>
<?php }} ?>
      </div>
    </div>
  </div>
<?php } ?>
</div>
    </div>
    <div class="col-sm-8">
<?php if(!empty($inboxname)) echo "<div style='text-align:right'><code><a href='mail.php?backtoinbox=1'>Inbox of [" .$inboxname. "]</a></code></div>";?>
<div class="page-header">
  <h3><?php if(!empty($_GET['n'])) echo $_GET['n']; ?></h3>
</div>
<?php if(!$error) { ?>
<pre class="pre-scrollable" id="chatcontainer">
<?php
foreach($chat as $message) {
if($employeeid == $message['toid']) {
echo "<div class='alert alert-warning received' style='margin-bottom:5px'>";
echo $message['message'];
if($message['filename'] != "n") {
echo "<a href='attachment.php?id=" .$message['filedata']. "' target='_blank' style='float:right; margin-top:20px'><span class='glyphicon glyphicon-download-alt'></span> Attached File</a><hr style='margin-bottom:0'>";
}
echo "</div>";
} else {
echo "<div class='alert alert-success sent' style='margin-bottom:5px'>";
echo $message['message'];
if($message['filename'] != "n") {
echo "<a href='attachment.php?id=" .$message['filedata']. "' target='_blank' style='float:right; margin-top:20px'><span class='glyphicon glyphicon-download-alt'></span> Attached File</a><hr style='margin-bottom:0'>";
}
echo "</div>";
}
}
?>
</pre>
<script>
$('#chatcontainer').scrollTop(1E50);
</script>
<?php } ?>
<form role="form" method="post" action="message.php" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php if(!empty($_GET['id'])) echo $_GET['id']; ?>">
<input type="hidden" name="n" value="<?php if(!empty($_GET['n'])) echo $_GET['n']; ?>">
  <div class="input-group">
<textarea class="form-control" style="resize:vertical; height: 50px" name="message" placeholder="Type Here ..." wrap="hard" required></textarea>
      <span class="input-group-btn">
        <button type="submit" class="btn btn-default" type="button"><span class="glyphicon glyphicon-send"></span> Send</button>
      </span>
    </div><br>
  <div class="input-group">
      <input name="attachment" type="file" class="form-control">
    </div>
  </form>
</div>
</div>
</body>
</html>