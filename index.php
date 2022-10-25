<?php
session_start();
include_once 'include/voting-class.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Nanda Voting System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="asset/css/bootstrap.min.css" rel="stylesheet">
  <link href="asset/css/bootstrap-responsive.min.css" rel="stylesheet">
  <link href="asset/css/docs.css" rel="stylesheet">

  <script src="asset/js/jquery-latest.js"></script>
  <script src="asset/js/bootstrap.min.js"></script>
</head>

<body>
<div class="container">
  <?php 
  $user = new User();
  $db = new Database();

  $db->connectMySQL();


  if($_SERVER["REQUEST_METHOD"]=="POST"){
    $login = $user->cek_login(mysql_real_escape_string($_POST['username']), mysql_real_escape_string($_POST['password']));
    if($login){
      if($user->sesi()==1){
        header("location:home.php");
      }elseif($user->sesi()==2){
        header("location:home.php");
      }
    }else{
      echo "
      <div class=\"alert alert-block\">
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>
        <h4>Login Failed!</h4>
        ID or Password Wrong!
      </div>
      ";
    }
  }
  ?>
  <form style="text-align:center;" method="post" name="login">
    <img class="img-responsive" src="asset/img/logo.png" align="center" width="600" />
    <div class="form-group">
      <input type="text" class="form-control" name="username" id="username" placeholder="Student ID">
    </div>
    <div class="form-group">
      <input type="password" class="form-control" name="password" id="password" placeholder="Password">
    </div>

    <button type="submit" class="btn btn-primary">Login</button>
    <button type="reset" class="btn">Clear</button>
  </form>

</div> <!-- /container -->

</body>
</html>
