<?php
session_start();

// Check if the user is already logged in, and if the variable is set. if yes then redirect to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: thrift.php");
  exit;
}

require_once('config.php');

$email = $password = "";
$email_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Checking if email empty
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter email.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    // Check password emptyi
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
	
	  // Check email and password empty
    if(empty($email_err) && empty($password_err)){
        // Retrieve given email password
        $query = $session->execute(new Cassandra\SimpleStatement
          ("SELECT * FROM users WHERE email='$email'"));

        $row = $query->first();
		
			// Check password is same as query password
			if($row['upassword'] == $password){
				session_start();
                            
                            // Store session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["email"] = $row['email'];    
							$_SESSION["name"] = $row['name'];
							$_SESSION["state"] = $row['state'];
							$_SESSION["points"] = $row['points'];
                            
                            // Redirect to welcome page
                            header("location: thrift.php");
							exit;
                        } else{
                            // Display error
                            $password_err = "The password entered was not correct.";
                        }
			}
	
}
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
	<a href='index.php'><button class="btn"><i class="fa fa-home"></i> Home</button></a>
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="registration.php">Sign up now</a>.</p>
        </form>
    </div>    
</body>
</html>