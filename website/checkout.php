<?php
session_start();
require 'config.php';

if(!empty($_SESSION["cart_item"])) {
			foreach($_SESSION["cart_item"] as $item) {
						$pid = $item["pid"];
						$pname = $item["pname"];
						$category = $item["category"];
						$price = $item["price"];
						$uemail = $_SESSION["email"];
						$uname = $_SESSION["name"];
						$charity = $item["charity"];
						$state = $_SESSION["state"];
						$imageloc = $item["imageloc"];
						$points = $_SESSION["points"];
						
						
						$insertOrder = $session->execute(new Cassandra\SimpleStatement
						("INSERT INTO orders (pid, ordertime, pname, category, price, uemail, uname, charity, state, imageloc) VALUES ($pid, toTimeStamp(now()), '$pname', '$category', $price, '$uemail', '$uname', '$charity', '$state', '$imageloc')")
						);
						
						$updateToSold = $session->execute(new Cassandra\SimpleStatement
						("UPDATE products SET sold=TRUE WHERE pid=$pid"));
						}
						
						/*
						$updateBuyerPoints = $session->execute(new Cassandra\SimpleStatement
						("UPDATE users SET points = $points + $price WHERE pid = $pid"));
						
						$charityq = $session->execute(new Cassandra\SimpleStatement
						("SELECT * FROM charities WHERE cname = $charity"));
						
						$row = $charityq->first(); 
						
						$v = $row['raised'];
						
						$updateRaised = $session->execute(new Cassandra\SimpleStatement
						("UPDATE users SET raised = $v + $price WHERE cname = $charity"));*/
						
}
unset($_SESSION['cart_item']);

echo "Order succeful, redirecting to orders page";
sleep(1);
header("Location: orders.php");