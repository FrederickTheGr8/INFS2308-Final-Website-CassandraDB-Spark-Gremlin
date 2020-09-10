<?php
session_start();
require_once('config.php');

// Check that an action has been done
if(!empty($_GET["action"])) {
	
	// Switch statement which has 4 modes
switch($_GET["action"]) {
	// Switch statement that gets called when the add to cart button is pressed
	case "add":
			$productsByPid = $session->execute(new Cassandra\SimpleStatement
			("SELECT * FROM products WHERE pid=".$_GET["pid"]));
			
			$itemArray = array($productsByPid[0]["pid"]=>array('pname'=>$productsByPid[0]["pname"], 'pid'=>$productsByPid[0]["pid"], 'price'=>$productsByPid[0]["price"], 'imageloc'=>$productsByPid[0]["imageloc"], 'category'=>$productsByPid[0]['category'], 'charity'=>$_POST['formCharity']));
			
			$cartPidArray = array();
			
			// Check there is already a cart item
			if(!empty($_SESSION["cart_item"])) {
				
				// Iterate over all cart items
					foreach($_SESSION["cart_item"] as $cartkey => $cartvalue){
						
						// Attempt to check if that item is already in the cart
						// This is bugged, not sure how to stop duplicates.
						if(!in_array($_GET["pid"], $cartPidArray)){
							$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
							$getpid = $_GET["pid"];
							array_push($cartPidArray, $getpid);
						}
						
						}

			}
			
			// If there is no other cart item, add to cart
			else {
				$_SESSION["cart_item"] = $itemArray;
				array_push($cartPidArray, $_GET["pid"]);
			}
	break;
	
	// Remove case
	case "remove":
		// Check if there is a cart item
		if(!empty($_SESSION["cart_item"])) {
			// Iterate over cart item
			foreach($_SESSION["cart_item"] as $key => $value) {
					// Check if the pid = the cart item key
					if($_GET["pid"] == $key)
						unset($_SESSION["cart_item"][$key]);				
					if(empty($_SESSION["cart_item"]))
						unset($_SESSION["cart_item"]);
			}
		}
	break;
	
	// Empty cart case
	case "empty":
		unset($_SESSION["cart_item"]);
		$cartPidArray = array();
	break;	

	// Checkout case
	case "checkout":
		if(!empty($_SESSION["cart_item"])) {
			$cartPidArray = array();
			header('Location: checkout.php');
			exit;
		}

		else {
			echo "Add some items to cart";
		}
		break;

}
}?>


<HTML>
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">	
    <link href="css/style.css" type="text/css" rel="stylesheet" />
	<a href='index.php'><button class="btn"><i class="fa fa-home"></i> Home</button></a>
</head>
<body>
<div id="shopping-cart">
<div class="txt-heading">Shopping Cart</div>
<a id="btnCheckout" href="thrift.php?action=checkout">Checkout</a>
<a id="btnEmpty" href="thrift.php?action=empty">Empty Cart</a>
<?php
if(isset($_SESSION["cart_item"])){
	$selected_charity = '';
    $item_price = 0;
	$total_price = 0;
?>	
<table class="tbl-cart" cellpadding="10" cellspacing="1">
<tbody>
<tr>
<th style="text-align:left;">Name</th>
<th style="text-align:left;">Charity</th>
<th style="text-align:left;">PID</th>
<th style="text-align:right;" width="5%">Category</th>
<th style="text-align:right;" width="10%">Unit Price</th>
<th style="text-align:right;" width="10%">Price</th>
<th style="text-align:center;" width="5%">Remove</th>
</tr>	
<?php		
    foreach ($_SESSION["cart_item"] as $item){
        $item_price = $item["price"];
		?>
				<tr>
				<td><img src="<?php echo $item["imageloc"]; ?>" width="300" height="160"class="cart-item-image" /><?php echo $item["pname"]; ?></td>
				<td><?php echo $item["charity"]; ?></td>
				<td><?php echo $item["pid"]; ?></td>
				<td style="text-align:right;"><?php echo $item["category"]; ?></td>
				<td  style="text-align:right;"><?php echo "$ ".$item["price"]; ?></td>
				<td  style="text-align:right;"><?php echo "$ ". number_format($item_price,2); ?></td>
				<td style="text-align:center;"><a href="thrift.php?action=remove&pid=<?php echo $item["pid"]; ?>" class="btnRemoveAction"><img src="css/icon-delete.png" alt="Remove Item" /></a></td>
				</tr>
				<?php
				$total_price += ($item["price"]);
		}
		?>

<tr>
<td colspan="2" align="right">Total:</td>
<td align="right" colspan="2"><strong><?php echo "$ ".number_format($total_price, 2); ?></strong></td>
<td></td>
</tr>
</tbody>
</table>		
  <?php
} else {
?>
<div class="no-records">Your Cart is Empty</div>
<?php 
}
?>
</div>
   
   
<div id="product-grid">
	<div class ="txt-heading">Products</div>
	<?php

	$products = $session->execute(new Cassandra\SimpleStatement
        ("SELECT * FROM products WHERE sold=FALSE"));
	
	$charities = $session->execute(new Cassandra\SimpleStatement
        ("SELECT cname FROM charities"));
		  
		  foreach ($products as $key=>$value) {
			?>
	<div class="product-item">
		<form method="post" action="thrift.php?action=add&pid=<?php echo $products[$key]["pid"]; ?>">
		<div class="product-image"><img src="<?php echo $products[$key]["imageloc"]; ?>" width="250" height="130"></div>
		<div class="product-tile-footer">
		<div class="product-title"><?php echo wordwrap(($products[$key]["pname"]),35,"<br>\n"); ?></div
		<div class="product-title"><?php echo wordwrap(($products[$key]["category"]),35,"<br>\n"); ?></div>
		<div class="product-title"><?php echo wordwrap(($products[$key]["description"]),35,"<br>\n"); ?></div>
		<div class="product-price"><?php echo "$".$products[$key]["price"]; ?></div>
		<div class="cart-action">
		<p> Select a Charity
		<select name="formCharity">
		<?php foreach ($charities as $charity){
			 $charityname = $charity["cname"]; 
			echo "<option value=\"$charityname\">" . $charityname . "</option>";
			//<option value=<?php "$charityname" ><?php $charityname </option>
		 } ?>
		</select>
		</p>
		<input type="submit" value="Add to Cart" class="btnAddAction" /></div>
		</div>
		</form>
	</div>
<?php
	}
?>
</body>
</html>