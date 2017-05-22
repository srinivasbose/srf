<?php
session_start();
include('conf/configure.php');
if($_SESSION['login'] != 1)
{
header("Location:loginpage.php");
}
else
{
$con = mysql_connect("localhost","root","");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("SRFAM", $con);
$sql1="SELECT * from sales";
$sales_rows=mysql_query($sql1);
$currentdate=date("d/m/Y");
$date1=sqlDateformat($currentdate);

if(isset($_POST['submit']))
{
$currentmonth1=($_POST["month"]);
$months=array_flip(getmonths());
$currentmonth=$months[$currentmonth1];
$currentmonthname=getmonth($currentmonth);
$currentyear=$_POST["year"];

}
else
{
$currentmonth=monthofdate($date1);
$currentmonthname=monthnameofdate($date1);
$currentyear=yearofdate($date1);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>View Sales Details Page</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
   function printTable(){
 
  var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
      disp_setting+="scrollbars=yes,width=650, height=600, left=100, top=25"; 
  var content_vlue = document.getElementById("table").innerHTML; 
  
  var docprint=window.open("","",disp_setting); 
   docprint.document.open(); 
   docprint.document.write('<html><head><title>Sales Details</title>'); 
   docprint.document.write('</head><body onLoad="self.print()"><center>');   
   docprint.document.write('<h4>Sales Information for <?php echo ($currentmonthname." ".$currentyear);?> as on <?php echo $currentdate?></h4><center>'); 
   docprint.document.write(content_vlue);          
   docprint.document.write('</center></body></html>'); 
   docprint.document.close(); 
   docprint.focus(); 
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
            The Sales details for every month can be viewed in this page.
            </div>
          <div class="details">
		  <form action="viewsalespage.php" method="post">
		  <tr>
        	<p>
				<h4>Sales Information</h4>
            </p>
			</tr>
				
			<tr>
			<td>Month:</td>
            <td>
            <select id="month" name="month">
            <option value="">Select</option>
            <?php
			$retrieveditems=getmonths();
            for( $i=1;$i<=12;$i++)
            {
			?>
            <option value="<?php echo $retrieveditems[$i] ?>"><?php echo $retrieveditems[$i]?></option>
            <?php
            }
            ?>
            </select> 
			</td>

			<td>Year:</td>
            <td>
            <select id="year" name="year">
            <option value="">Select</option>
            <?php
			
			$retrieveditems=getyears();
            for($i=1;$i<sizeof($retrieveditems);$i++)
            {
			?>
            <option value="<?php echo $retrieveditems[$i] ?>"><?php echo $retrieveditems[$i]?></option>
            <?php
            }
            ?>
            </select> 
			</td>
			</tr>
			<tr>
			<input type="submit" id="submit" name="submit" />
		<tr>
				
				<p>
			Sales Information for <?php echo ($currentmonthname." ".$currentyear);?> as on <?php echo $currentdate?>.
			</p>
			</tr>
            
		
<div id="table">
<table border="1">
<tr>
<th>Bill No</th>
<th>Date</th>
<th>Name</th>
<th>Address</th>
<th>Item</th>
<th>Brand</th>
<th>Quantity</th>
</tr>
<?php
while($salesdetail=mysql_fetch_array($sales_rows))
{
$retrieveddate=$salesdetail['DATE'];
$retrievedmonth=monthofdate($retrieveddate);
$retrievedyear=yearofdate($retrieveddate);
if(($retrievedmonth==$currentmonth)&&($retrievedyear==$currentyear))
{
?>
<tr>
<td>
<?php
echo $salesdetail['BILLNO'];
?>
</td>
<td>
<?php
echo userDDateformat($retrieveddate);
?>
</td>
<td>
<?php
echo $salesdetail['NAME'];
?>
</td>
<td>
<?php
echo $salesdetail['ADDRESS'];
?>
</td>

<td>
<?php
echo $salesdetail['ITEM'];
?>
</td>
<td>
<?php
echo $salesdetail['BRAND'];
?>
</td>
<td>
<?php
echo $salesdetail['QUANTITY'];
?>
</td>

</tr>
<?php
}
}
?>
</table>
<?php
}
?>
<input type="button" id="print" name="print" value="Print" onclick="printTable()" />
</div>
</form>
</div>
      </div>
            </div>
</div>

</body>
</html>