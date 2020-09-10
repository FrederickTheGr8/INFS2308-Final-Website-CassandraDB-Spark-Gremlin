<?php
require_once('config.php');

$email = $password = $name = $state = "";
$email_err = $password_err = $name_err = $state_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Checking if email empty
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter email.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    // Check if password emptyi
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
	
	//Check if name empty
	if(empty(trim($_POST["name"]))){
        $name_err = "Please enter your name.";
    } else{
        $name = trim($_POST["name"]);
    }
	
	// CHeck if state empty
	if(empty(trim($_POST["state"]))){
        $state_err = "Please enter your state.";
    } else{
        $state = trim($_POST["state"]);
    }
	
	  // Check that there has been no errors
    if(empty($email_err) && empty($password_err) && empty($name_err) && empty($state_err)){
        
		
        $email = $_POST['email'];
		$name = $_POST['name'];
		$password = $_POST['password'];
		$state = $_POST['state'];
		
		$query = $session->execute(new Cassandra\SimpleStatement
        ("SELECT * FROM users WHERE email='$email'"));
		
		$row = $query->first();
		
		// Check that no user with given email exists
		if(empty($row)){
			
			// Insert user info into database
			$statement = $session->execute(new Cassandra\SimpleStatement(
			"INSERT INTO users (email, name, upassword, state) VALUES ('$email', '$name', '$password' , '$state')"));
			
			// Redirect to login page
			header("location: login.php");
			exit;
		}
		else {
				$email_err = "Email already exists.";
		}
		
	}
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
    <title>Registration</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
	<a href='index.php'><button class="btn"><i class="fa fa-home"></i> Home</button></a>
</head>
<body>
  <form action="registration.php" method="post">
			<div class="wrapper">
				<h2>Register</h2>
				<p>Please fill in your credentials to Register.</p>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="text" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
			<div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                <span class="help-block"><?php echo $name_err; ?></span>
            </div> 
			<div class="form-group <?php echo (!empty($state_err)) ? 'has-error' : ''; ?>">
                <label>State</label>
                <input type="text" name="state" class="form-control" value="<?php echo $state; ?>">
                <span class="help-block"><?php echo $state_err; ?></span>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Register">
            </div>
            <p>Already have an account? <a href="login.php">Login</a>.</p>
        </form>
    </div>    
	</form>
</body>
</html>