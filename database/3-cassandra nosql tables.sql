DROP KEYSPACE charithrift;

CREATE KEYSPACE charithrift  WITH REPLICATION = { 'class' : 'SimpleStrategy', 'replication_factor' : 1 };

USE charithrift;

CREATE TABLE users(
   email text PRIMARY KEY,
   name text,
   upassword text,
   state text,
   points int
   );
Create index usersnameindex on users(name);
Create index usersstateindex on users(state);
Create index userspointsindex on users(points);

CREATE TABLE products(
	pid int PRIMARY KEY,
	pname text,
	uemail text,
	uname text,
	category text,
	description text,
	price int,
	imageloc text,
	sold boolean
	);
Create index prodpnameindex on products(pname);
Create index produemailindex on products(uemail);
Create index prodcategoryindex on products(category);
Create index prodpriceindex on products(price);
Create index prodsoldindex on products(sold);
	
CREATE TABLE orders(
	pid int PRIMARY KEY,
	ordertime timestamp,
	pname text,
	category text,
	price int,
	uemail text,
	uname text,
	charity text,
	state text,
	imageloc text
	);
Create index ordordertimeindex on orders(ordertime);
Create index ordpnameindex on orders(pname);
Create index ordcategoryindex on orders(category);
Create index orduemailindex on orders(uemail);	
Create index ordunameindex on orders(uname);
Create index ordcharityindex on orders(charity);
Create index ordstateindex on orders(state);
	
CREATE TABLE charities(
	cname text PRIMARY KEY,
	raised int
);
