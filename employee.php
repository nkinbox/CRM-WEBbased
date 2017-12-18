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
if(!empty($_POST)) {
AEemployee();
header('Location: administrator.php');
exit;
}
$employee=array();
if(!empty($_GET['eid'])) {
getemployeedata($_GET['eid']);
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

<div class="container" style="width: 600px">
<div class="page-header">
  <h4>Employee</h4>
</div>
  <form class="form-horizontal well" role="form" action="employee.php" method="post">
<?php if(!empty($employee)) { ?>
<input type="hidden" name="eid" value="<?php echo $employee['employeeid']; ?>">
<?php } ?>
    <div class="form-group">
      <label class="control-label col-sm-2" for="name">Name:</label>
      <div class="col-sm-10">
        <input name="name" type="text" class="form-control" id="name" value="<?php if(!empty($employee)) echo $employee['name']; ?>" placeholder="Full name" required>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="email">Email:</label>
      <div class="col-sm-10">
        <input name="email" type="text" class="form-control" id="email" value="<?php if(!empty($employee)) echo $employee['email']; ?>" placeholder="Email" required>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="phone">Phone:</label>
      <div class="col-sm-10">
        <input name="phone" type="text" class="form-control" id="phone" value="<?php if(!empty($employee)) echo $employee['phone']; ?>" placeholder="Phone number" required>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="pwd">Password:</label>
      <div class="col-sm-10">          
        <input name="password" type="password" class="form-control" id="pwd" placeholder="Enter password">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="group">Group:</label>
      <div class="col-sm-10">          
        <input name="groupname" type="text" class="form-control" id="group" value="<?php if(!empty($employee)) echo $employee['groupname']; ?>" placeholder="Group Name">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2">Handler:</label>
      <div class="col-sm-10">          
<select name="handler" class="form-control">
<option value="s"<?php if(!empty($employee)) { if($employee['handler'] == "s") echo " selected"; } ?>>Sales Agent</option>
<option value="t"<?php if(!empty($employee)) { if($employee['handler'] == "t") echo " selected"; } ?>>Technician</option>
<option value="a"<?php if(!empty($employee)) { if($employee['handler'] == "a") echo " selected"; } ?>>Administrator</option>
</select>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2">Delete:</label>
      <div class="col-sm-10">          
<select name="deleted" class="form-control">
<option value="n"<?php if(!empty($employee)) { if($employee['deleted'] == "n") echo " selected"; } ?>>No</option>
<option value="y"<?php if(!empty($employee)) { if($employee['deleted'] == "y") echo " selected"; } ?>>Yes</option>
</select>
      </div>
    </div>
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </form>
</div>

</body>
</html>