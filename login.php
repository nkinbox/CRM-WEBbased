<?php
include 'lib.php';
if(sessioncheck()) {
header('Location: index.php');
exit;
}
$error = false;
if(!empty($_POST['username']) && !empty($_POST['password'])) {
$error = login($_POST['username'],$_POST['password']);
if(!$error) {
header('Location: index.php');
exit;
}
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
<body style="background-image: url('bg.jpg');background-size:100%;">

<div class="container" style="width: 600px">
<div class="page-header">
  <h1>Login to UMH</h1>
</div>
<?php if($error) {?>
<div class="alert alert-danger">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error!</strong> Username or Password seems to be incorrect.
</div>
<?php }?>
  <form class="form-horizontal well" role="form" method="post" action="login.php" style="background-color:rgba(245,245,245,0.5)">
    <div class="form-group">
      <label class="control-label col-sm-2" for="email">Username:</label>
      <div class="col-sm-10">
        <input name="username" type="text" class="form-control" id="email" placeholder="Enter Username">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="pwd">Password:</label>
      <div class="col-sm-10">          
        <input name="password" type="password" class="form-control" id="pwd" placeholder="Enter password">
      </div>
    </div>
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </form>
</div>



<div class="navbar-fixed-bottom" style="background-color:rgba(245,245,245,0.5)">
  <h3 style="text-align:center" class="text-muted">
Kismet Technical Services
  </h3>
</div>

</body>
</html>