<?php
session_start();
$relogin=$_REQUEST['relogin'];
if($relogin==1)
{
unset($_SESSION['login']);
session_destroy();
}
if(isset($_POST['Submit']))
{
$con = mysql_connect("localhost","root","");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
  $db = 'srfam';
mysql_select_db($db);
$name=$_POST["name"];
$pword=$_POST["pword"];
$sql = "select * from users where USER_NAME = '$name' and PASSWORD = '$pword'";
$getuser=mysql_query($sql) or die('error in sel'.mysql_error());
$ispresent=mysql_num_rows($getuser);
if($ispresent==0)
{
?>
<script language="javascript">
alert("Please enter a valid username and password");	
</script>
<?php
}
else
{
$_SESSION['login']=1;
header("Location:summary.php");
}
}
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<title>Login</title>
</head>

<body>
<div class="layout">
	<div class="main">
		<div class="header">
        	<div class="logo">
        	<img src="images/srf_logo.gif" />
			<h2>Sriram Furniture</h2>
            </div>
		</div>
	   <div class="content" width=100%>
        	<div class="instruction">
            Enter the login details in this page. Only a SRF registered users can access this page. If you dont have access contact you administrator for access.
            </div>
          <div class="details">
		  	<?php
			
			if($relogin==1)
			{
			?>
			<h2>You have sucessfully logged out.., Please re-login.</h2>
			<?php
			}
			else
			{
			?>
        	<h2>Enter Login Credentials</h2>
			<?php
			}
			?>
			<form action="loginpage.php" method="post">
            <table width="500">
            <tr>
            <td>User Name:</td><td> <input type="text" name="name" /></td>
            </tr>
			 <tr>
            <td>Password:</td><td> <input type="password" name="pword" /></td>
            </tr>
			<tr><td colspan="2" align="center">
            <input type="submit" name="Submit" /></td></tr>
            </table>
            </form>
          </div>
		</div>
	</div>
	</div>
	
</body>
</html>
