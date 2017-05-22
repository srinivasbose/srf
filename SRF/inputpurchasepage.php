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
	if(isset($_POST['Submit']))
	{
		$billno=$_POST["billno"];
		$date=sqlDateformat($_POST["date"]);
		$quantity=$_POST["quantity"];
		$item=$_POST["item"];
		$dname=$_POST["dname"];
		$brand=$_POST["brand"];
		$size=$_POST["size"];
		
		if($dname == '25'){ // storing customer name from whom sales returned
		$custname = $_POST['custname'];
		  $sql="INSERT INTO purchase (BILLNO, DATE, NAME, ADDRESS, item_id, brand_id,size_id, QUANTITY)
			VALUES ('$billno', '$date', '$dname', '$custname' ,'$item', '$brand', '$size', '$quantity')";
		}else{

		$sql="INSERT INTO purchase (BILLNO, DATE, NAME,  item_id, brand_id,size_id, QUANTITY)
			VALUES ('$billno', '$date', '$dname', '$item', '$brand', '$size', '$quantity')";
		}
		$insertpurchase=mysql_query($sql);
		if(!$insertpurchase)
		{
			echo (mysql_error());
		}
		$sql1="SELECT * from stock where item_id = '$item' and brand_id = '$brand' and size_id = '$size'";
		$stock_rows=mysql_query($sql1);
		if(!$stock_rows)
		{
			echo (mysql_error());
		}
		$stockdetail=mysql_fetch_array($stock_rows);
		if(!$stockdetail)
		{
			$sql2="INSERT INTO stock (item_id, brand_id, size_id, quantity)
				VALUES ('$item', '$brand', '$size', '$quantity')";
				$insertstock=mysql_query($sql2);
			if(!$insertstock)
				echo (mysql_error());
		}
		else
		{
			$oldquantity=$stockdetail['QUANTITY'];
			$newquantity=$oldquantity+$quantity;
			echo $newquantity;
			$sql3="UPDATE stock set quantity='$newquantity' where item_id='$item' and brand_id='$brand' and size_id = '$size'";
			$updatestock=mysql_query($sql3);
			if(!$updatestock)
			{
				echo (mysql_error());
			}
		}
		if($insertpurchase){
			if($insertstock || $updatestock){
				echo "<script>alert('Purchase added Successfully.')</script>";
			}else{
				echo "<script>alert('Purchase Done. But Not added in Stock.')</script>";
			}
		}else{
			echo "<script>alert('Error occured while adding Purchase.')</script>";
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
	<SCRIPT src="js/calendar-en.js" type=text/javascript></SCRIPT>
	<!-- the following script defines the Calendar.setup helper function, which makes
	adding a calendar a matter of 1 or 2 lines of code. -->
	<SCRIPT src="js/calendar-setup.js" type=text/javascript></SCRIPT>
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<link href="css/calendar-win2k-cold-1.css" rel="stylesheet" type="text/css" />
	<title>Sriram Furniture</title>
	<script type="text/javascript">
	function getreturn(selbox){
	var selval = selbox.value;
	if(selval == 25){
	  document.getElementById('itemreturn').style.display = 'table-row';
	}else{
		document.getElementById('itemreturn').style.display = 'none';
	}
	
	}
	
	</script>
	</head>




	<body>
	<div class="layout">
	<div class="main">
	<div class="header">
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
	<div class="content" width=100%>
	<div class="instruction">
	Enter the purchase details in this page. The dealer bill number and the other details are mandatory. Please have the orignal billcopy while entering details in this page.
	</div>
	<div class="details">
	<p>
	</h2>Enter Purchase Information</h2>
	</p>
	<form action="inputpurchasepage.php" method="post">
	<table width="500">
	<tr>
	<td>Invoice Number:</td><td> <input type="text" name="billno" /></td>
	</tr>
	<tr>
	<td>Date:</td>
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
		align          :    "Tl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
	</script></td>
	</tr>
	<tr>
	<td>Item:</td>
	<td>
	<select id="item" name="item" onchange="getItemBranddetails()" >
	<option value="">Select</option>
	<?php
			$sql1="SELECT * from ITEM";
	$result=mysql_query($sql1);
	while($retrieveditems= mysql_fetch_array($result))
	{
		?>
		<option value="<?php echo $retrieveditems['item_id'] ?>"><?php echo $retrieveditems['ITEMS']?></option>
		<?php
	}
	?>
	</select>            </td>
	</tr>

	<tr>
	<td>Brand:</td>
	<td>
	<select id="brand" name="brand">  <!--onchange="getItemSizedetails()"-->
	<option value=""> Select </option>
	</select>            </td>
	</tr>
	<tr>
	<td>Category:</td>
	<td>
	<select id="size" name="size">
	<option value=""> Select </option>
	</select>            </td>
	</tr>
	<tr>
	<td colspan="">Quantity:</td><td> <input type="text" name="quantity" /></td>
	</tr>
	<tr>
	<td>Dealer Name:</td>
	<td>
	<select id="dname" name="dname" onchange="getreturn(this);" >
	<option value="">Select</option>
	<?php
			$sql1="SELECT DISTINCT name,id from dealer where isSubDealer =0 ORDER BY name";
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
	<tr id="itemreturn" style="display:none">
	<td>Customer Name </td>
	<td> <input type="text" name="custname" id="custname" value="" /> </td>
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



