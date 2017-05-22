<?php
		session_start();
include('conf/configure.php');

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
	<SCRIPT src="js/calendar-en.js" type=text/javascript></SCRIPT>
	<!-- the following script defines the Calendar.setup helper function, which makes
	adding a calendar a matter of 1 or 2 lines of code. -->
	<SCRIPT src="js/calendar-setup.js" type=text/javascript></SCRIPT>
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<link href="css/calendar-win2k-cold-1.css" rel="stylesheet" type="text/css" />
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

	<div>
	
	<h2><b>Sales Summary by Date</b></h2>
	
	<form action="" method="post">
	<table width="500">

	<tr>
	<td>Bill Date:</td>
	<td><input type='text' id='date' name='date' readonly="readonly" /> 
	<img id=C_trigger_c1 onMouseOver="this.style.background='red';" 
		title="Date selector" 
			style="BORDER-RIGHT: red 1px solid; BORDER-TOP: red 1px solid; BORDER-LEFT: red 1px solid; CURSOR: pointer; BORDER-BOTTOM: red 1px solid" 
				onmouseout="this.style.background='red'" 
					src="js/img.gif" />
	<script type=text/javascript>
	Calendar.setup({
		inputField     :    "date",     // id of the input field
		ifFormat       :    "%d-%m-%Y",      // format of the input field
		button         :    "C_trigger_c1",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
	</script></td>
	</tr>


	<tr> <br></tr>
	 <br>
	<tr><td colspan="2" align="center">
	<input type="submit" name="Submit" value ="Get Summary"  /></td></tr>


	</table>
	
	<?php

			if(isset($_POST['Submit']))
			{		
				$date = sqlDateformat($_POST["date"]);
				$sql1="SELECT * from sales_vw where date = '$date'";
				$result=mysql_query($sql1);
				if(mysql_num_rows($result) != 0){
					?>
					<br>
					<h4>Sales Bill on <?php echo date_format(new DateTime($date),"d-m-Y") ?> </h4>
					<br>
					<table border='1' style='border-collapse: collapse'>
					<th>Bill No.</th><th>Name</th><th>Item</th><th>Brand</th><th>Category</th><th>Quantity</th>

					<?php
							while($row= mysql_fetch_array($result))
							{
								?>
								<tr>
								<td><?php echo $row['BILLNO'] ?></td>
								<td><?php echo $row['NAME'] ?></td>
								<td><?php echo $row['ITEMS'] ?></td>
								<td><?php echo $row['BRANDS'] ?></td>
								<td><?php echo $row['size_name'] ?></td>
								<td><?php echo $row['QUANTITY'] ?></td>
								</tr>
								<?php
							}
				}else{
	?>
					<p><b>No Sales on <?php echo date_format(new DateTime($date),"d-m-Y") ?>.</b></p>
	<?php
				}
			}

	?>

	</form>
	</div>
	</div>
	</div>
	</div>


	</body>
	</html>

