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
mysql_select_db("SRFAM",$con1);
if(isset($_POST['Submit']))
{
$quantity=$_POST["quantity"];
$item=$_POST["item"];
$brand=$_POST["brand"];
$size = $_POST["size"];


/*$sql="INSERT INTO purchase (billno, date, name, item_id, brand_id, size_id, quantity)
VALUES ('000', '2017-04-01', 'opening balance',  '$item', '$brand', '$size', '$quantity')";
$insertpurchase=mysql_query($sql);
if(!$insertpurchase)
{
echo (mysql_error());
}*/
$sql1="SELECT * from stock where item_id = '$item' and brand_id = '$brand' and size_id = '$size' ";
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
//echo $newquantity;
$sql3="UPDATE stock set quantity='$newquantity' where item_id='$item' and brand_id='$brand' and size_id = '$size' ";
$updatestock=mysql_query($sql3);
if(!$updatestock)
{
echo (mysql_error());
}
}
$msg = 'Stock Updated Successfully';
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
            Enter the opening stock for the items using his page. Item name and quantity are mandatory and brand is optional.
            </div>
          <div class="details">
        	<p>
				</h2>Enter Stock Details</h2>
            </p>
            <form action="" method="post">
            <table width="500">
            
            <tr>
            <td>Item:</td>
            <td>
            <select id="item" name="item" onchange="getItemBranddetails()" >
            <option value="">Select</option>
            <?php
            $sql1="SELECT * from item";
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



