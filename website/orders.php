<?php
session_start();
require_once('config.php');
?>

<HTML>
<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">	
    <link href="css/style.css" type="text/css" rel="stylesheet" />
	<a href='index.php'><button class="btn"><i class="fa fa-home"></i> Home</button></a>
</head>
<body>
   
<div id="order-grid">
	<div class ="txt-heading">Orders</div>
	<?php

	$semail = $_SESSION["email"];
	$orders = $session->execute(new Cassandra\SimpleStatement
	("SELECT * FROM orders WHERE uemail='$semail'"));
		  
		  foreach ($orders as $key=>$value) {
			?>
	<div class="order-item">
		<form>
		<div class="order-image"><img src="<?php echo $orders[$key]["imageloc"]; ?>" width="250" height="130"></div>
		<div class="order-tile-footer">
		<div class="order-title"><?php echo wordwrap(($orders[$key]["pname"]),35,"<br>\n"); ?></div>
		<div class="order-title"><?php echo wordwrap(($orders[$key]["pid"]),35,"<br>\n"); ?></div>
		<div class="order-title"><?php echo wordwrap(($orders[$key]["ordertime"]),35,"<br>\n"); ?></div>
		<div class="order-title"><?php echo wordwrap(($orders[$key]["category"]),35,"<br>\n"); ?></div>
		<div class="order-title"><?php echo wordwrap(($orders[$key]["charity"]),35,"<br>\n"); ?></div>
		<div class="order-price"><?php echo "$".$orders[$key]["price"]; ?></div>
		</div>
		</form>
	</div>
<?php
	}
?>
</body>
</html>