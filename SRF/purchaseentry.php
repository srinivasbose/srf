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
$daddress=$_POST["daddress"];
$sql="INSERT INTO purchase (billno, date, name, address, item_id, brand_id,size_id, quantity)
VALUES ('$billno', '$date', '$dname', '$daddress', '$item', '$brand', '$size', '$quantity')";
$insertpurchase=mysql_query($sql);
if(!$insertpurchase)
{
echo (mysql_error());
}
/*$sql1="SELECT * from stock where item = '$item' and brand = '$brand'";
$stock_rows=mysql_query($sql1);
if(!$stock_rows)
{
echo (mysql_error());
}
$stockdetail=mysql_fetch_array($stock_rows);
if(!$stockdetail)
{
$sql2="INSERT INTO stock (item, brand, quantity)
VALUES ('$item', '$brand', '$quantity')";
$insertstock=mysql_query($sql2);
if(!$insertstock)
echo (mysql_error());
}
else
{
$oldquantity=$stockdetail['QUANTITY'];
$newquantity=$oldquantity+$quantity;
echo $newquantity;
$sql3="UPDATE stock set quantity='$newquantity' where item='$item' and brand='$brand'";
$updatestock=mysql_query($sql3);
if(!$updatestock)
{
echo (mysql_error());
}
}*/
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
				</h2>Enter Old Bill Information</h2>
            </p>
            <form action="purchaseentry.php" method="post">
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
            <td>Dealer Name:</td><td> <input type="text" name="dname" /></td>
            </tr>
            <tr>
            <td>Dealer Address:</td><td> <input type="text" name="daddress" /></td>
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



