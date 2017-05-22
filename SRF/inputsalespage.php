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

		$sql1="SELECT * from stock where item_id = '$item' and brand_id = '$brand' and size_id = '$size'";
		$stock_rows=mysql_query($sql1);
		if(!$stock_rows)
		{
			echo (mysql_error());
		}
		$stockdetail=mysql_fetch_array($stock_rows);
		if($stockdetail)
		{
			$oldquantity=$stockdetail['QUANTITY'];
			$newquantity=$oldquantity-$quantity;
			if($newquantity>=0)
			{

				$sql3="UPDATE stock set quantity='$newquantity' where item_id ='$item' and brand_id ='$brand' and size_id = '$size'";
				$updatestock=mysql_query($sql3);
				if(!$updatestock)
				{
					echo (mysql_error());
				}
				if($dname == '26'){ // storing dealer name to whom purchase returned
				$custname = $_POST['custname'];
				
					$sql="INSERT INTO sales (BILLNO, DATE, NAME, ADDRESS, item_id, brand_id,size_id, QUANTITY)
					VALUES ('$billno', '$date', '$dname', '$custname', '$item', '$brand', '$size', '$quantity')";
					
				}else{
				$sql="INSERT INTO sales (BILLNO, DATE, NAME, item_id, brand_id,size_id, QUANTITY)
					VALUES ('$billno', '$date', '$dname', '$item', '$brand', '$size', '$quantity')";
				}
				
					$insertsales=mysql_query($sql);
				if(!$insertsales)
				{
					echo (mysql_error());
				}
			}
			else
			{
				$sql4="delete from stock where item_id ='$item' and brand_id ='$brand' and size_id = '$size'";
				$deletestock=mysql_query($sql4);
				if(!$deletestock)
				{
					echo (mysql_error());
				}

			}
		}
        unset($_POST['Submit']);
		if(!$stockdetail){
			echo "<script>alert('No Stock in this Item.')</script>";
		}
		else if($updatestock){
			if($insertsales){
				echo "<script>alert('Sales added Successfully.')</script>";
			}else{
				echo "<script>alert('No Sufficient Stock.')</script>";
			}
		}else{
			echo "<script>alert('Error occured while adding Sales.Please try later.')</script>";
		}
		
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<SCRIPT src="js/calendar.js" type=text/javascript></SCRIPT>
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
	if(selval == 26){
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
<div class="logout">
<a href="loginpage.php?relogin=1">Logout</a>
</div>
<div class="summary">
<a href="summary.php">Summary</a>
</div>
</div>
<div class="content" width=100%>
<div class="instruction">
Enter the sales details in this page. The bill number and the other details are mandatory. 
</div>
<div class="details">
<tr>
<p>
<h2>Enter Sales Information</h2>
</p>
</tr>
<div class="table">
<form action="inputsalespage.php" method="post">
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
	align          :    "R8",           // alignment (defaults to "Bl")
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
		$sql1="SELECT DISTINCT name,id from dealer where isSubDealer =1 ORDER BY name";
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
	<td>Dealer Name </td>
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
</div>


</body>
</html>



