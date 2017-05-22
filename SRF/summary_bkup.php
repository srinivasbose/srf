<?php
session_start();
if($_SESSION['login'] != 1)
{
header("Location:loginpage.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<title>Summary</title>
</head>

<body>
<div class="layout">
	<div class="main">
		<div class="header">
        	<div class="logo">
        	<img src="" />
			<h2>Sriram Furniture</h2>
            </div>
            <div class="logout">
            <a href="loginpage.php?relogin=1">Logout</a>
            </div>
        </div>
	   <div class="content" width=100%>
        	<div class="instruction">
	        <p>Welcome to Sriram Furniture Account Maintenance Portal!!!!!!</p>
			<p>This portal is used to add purchase and sales information and to view the stock information.</p>
            </div>
          <div class="details">
		  <table>
		  <tr>
		  <td>Purchase</td><td><a href="inputpurchasepage.php">To add purchase details</a></td>
		  </tr>
        	  <tr>
		  <td>Stock</td><td><a href="viewstockpage.php">To view current stock information</a></td>
		  </tr>
		  <tr>
		  <td>Sales</td><td><a href="inputsalespage.php">To add sales details</a></td>
		  </tr>
	
          </div>
		</div>
	</div>
	</div>
	
</body>
</html>
