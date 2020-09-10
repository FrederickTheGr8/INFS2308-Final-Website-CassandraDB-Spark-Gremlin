<?php
session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  $email = $_SESSION["email"];
  $name = $_SESSION["name"];
  $state = $_SESSION["state"];
  $points = $_SESSION["points"];
}

else {
  header("location: login.php");
  exit;
}?>

<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
<a href='index.php'><button class="btn"><i class="fa fa-home"></i> Home</button></a>
</head>
<body>
  <div class="profile">
  <h1><?php echo "Name: "."$name";?></h1>
  <p><?php echo "State: "."$state";?></p>
  <p><?php echo "Points: "."$points";?></p>
  </div>
  </body>
</html>