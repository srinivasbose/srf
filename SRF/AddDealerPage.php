<?php
		session_start();
include('conf/configure.php');
$msg = '';
if($_SESSION['login'] != 1)
{
	header("Location:loginpage.php");
}
else
{
	$con1 = mysql_connect("localhost","root","");
	if (!$con1)
	{
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db("srfam",$con1);
	if(isset($_POST['Submit']))
	{
		$newDealer =$_POST["newDealer"];
		$address =$_POST["address"];
		$mobileNo = $_POST["mobileNo"];
		$isSubDealer = $_POST["isSubDealer"];

		$qrychk = "select * from dealer where name = '$newDealer' and mobile = '$mobileNo'";
		$exeqrychk = mysql_query($qrychk);

		if(mysql_num_rows($exeqrychk) == 0){

			$sql="INSERT INTO dealer (name,address,mobile, isSubDealer, lastupdated)
				VALUES ('$newDealer', '$address','$mobileNo','$isSubDealer',NOW())";
				$insertNewDealer=mysql_query($sql);
			if(!$insertNewDealer)
			{
				echo (mysql_error());
			}
			echo "<script type='text/javascript'>alert('Dealer added Successfully.')</script>";
			
		}else{
			echo "<script type='text/javascript'>alert('Dealer already exist.')</script>";
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<SCRIPT src="js/calendar.js" 
	type=text/javascript></SCRIPT>
	<script type="text/javascript" src="ajaxreq.js"></script>
	<!-- language for the calendar -->
	<SCRIPT src="js/calendar-en.js" 
		type=text/javascript></SCRIPT>
		<!-- the following script defines the Calendar.setup helper function, which makes
		adding a calendar a matter of 1 or 2 lines of code. -->
		<SCRIPT src="js/calendar-setup.js" 
			type=text/javascript></SCRIPT>
			<link href="css/style.css" rel="stylesheet" type="text/css" />
			<title>Sriram Furniture</title>
			</head>




			<body>
			<div class="layout">
			<div class="main">
			<div class="header" style="width:100%">
			<div class="logo">
			<img src="images/srf_logo.gif" />
			<h2>Sriram Furniture</h2>
			</div>
			<div>
			<a href="summary.php">Home</a>
			</div>
			<div class="logout">
			<a href="loginpage.php?relogin=1">Logout</a>
			</div>
			</div>
			<?php if($msg != ''){ ?>
			<br/>
			<div style="float:left">
			<h4 style="color:#FF0000"> <?php echo $msg; ?> </h4>
			</div>
			<?php } ?>
			<div class="content" width=100%>
			<div class="instruction">
			New Dealer Information.
			</div>
			<div class="details">
			<p>
			</h2><b>Enter New Dealer Details</b></h2>
			</p>
			<form action="" method="post">
			<table width="500">

			<tr>
			<td>Existing Dealers:</td>
			<td>
			<select id="existingDealer" name="existingDealer"  >
			<option value="">Select</option>
			<?php
					$sql1="SELECT DISTINCT name, id from dealer ORDER BY name";
			$result=mysql_query($sql1);
			while($retrieveditems= mysql_fetch_array($result))
			{
				?>
				<option value="<?php echo $retrieveditems['id'] ?>"><?php echo $retrieveditems['name']?></option>
				<?php
			}
			?>
			</select>            </td>
			</tr>

			<tr>
			<td>New Dealer Name:</td>
			<td>
			<input type="text" name="newDealer" />
			</td>
			</tr>


			<tr>
			<td>Address:</td>
			<td>
			<input type="text" name="address" />
			</td>
			</tr>

			<tr>
			<td>Mobile Number:</td>
			<td>
			<input type="text" name="mobileNo" />
			</td>
			</tr>
			
			<tr>
			<td> </td>
			<td>
			<input type="hidden" name="isSubDealer" value="0" />
			<input type="checkbox" name="isSubDealer" value="1">Sub Dealer</input>
			</td>
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



