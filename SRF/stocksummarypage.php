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
mysql_select_db("srfam", $con);

if($_GET['itemid'] != ""){
 $selitemid = $_GET['itemid'];
$cond = " where s.item_id = '$selitemid'";
// getting brand and size for this item
$brandqry = mysql_query("select * from brand where item_id = '$selitemid'") or die('brand error');
$cateqry = mysql_query("select size_id, size_name from size where item_id = '$selitemid'") or die('cate error');

$brandname = '';
$catname = '';

	if($_GET['brandid'] != ""){
	 $selbrandid = $_GET['brandid'];
	 $cond .= " and s.brand_id = '$selbrandid' "; 
	 
	 $brnameqry = mysql_query("select BRANDS from brand where brand_id = '$selbrandid'") or die('neme erro');
	 $brnameget = mysql_fetch_array($brnameqry);
	 $brandname = $brnameget[0];
	}

	if($_GET['catid'] != ""){
	 $selcatid = $_GET['catid'];
	 $cond .= " and s.size_id = '$selcatid' "; 
	 
	 $sznameqry = mysql_query("select size_name from size where size_id = '$selcatid'") or die('neme erro2');
	 $sznameget = mysql_fetch_array($sznameqry);
	 $catname = $sznameget[0];
	}
}else{
$cond = "";
}
$sql1="SELECT s.*, i.ITEMS, b.BRANDS, c.size_name from stock s LEFT JOIN item i ON s.item_id = i.item_id LEFT JOIN brand b ON s.brand_id = b.brand_id LEFT JOIN size c ON s.size_id= c.size_id $cond";
$stock_rows=mysql_query($sql1);
$currentdate=date("d/m/Y");
$date1=sqlDateformat($currentdate);

// Total product count
$cntqry = mysql_query("select SUM(quantity), i.ITEMS from stock s LEFT JOIN item i ON s.item_id = i.item_id $cond ");
$fetchnct = mysql_fetch_array($cntqry);
$totcount = $fetchnct[0];
$itemdet = $fetchnct[1];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Stock Summary Page</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
   function printTable(){
 
  var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
      disp_setting+="scrollbars=yes,width=650, height=600, left=100, top=25"; 
  var content_vlue = document.getElementById("table").innerHTML; 
  
  var docprint=window.open("","",disp_setting); 
   docprint.document.open(); 
   docprint.document.write('<html><head><title>Stock Details</title>'); 
   docprint.document.write('</head><body onLoad="self.print()"><center>');     
   docprint.document.write('<h4>Sales Details held on shop as on <?php echo $currentdate?></h4><center>'); 
   docprint.document.write(content_vlue);          
   docprint.document.write('</center></body></html>'); 
   docprint.document.close(); 
   docprint.focus(); 
}

function loadstock(sel){
 var itemid = sel.value;
 window.location.href='stocksummarypage.php?itemid='+itemid;
}
function loadstockbybrand(sel){
 var itemid = document.getElementById('item').value;
 var brandid = sel.value;
 window.location.href='stocksummarypage.php?itemid='+itemid+'&brandid='+brandid;
}
function loadstockbycat(sel){
 var itemid = document.getElementById('item').value;
 var catid = sel.value;
 window.location.href='stocksummarypage.php?itemid='+itemid+'&catid='+catid;
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
	  <div class="content">
        	<div class="instruction">
            The current stock information can be viewed in this page. Print this page to verify the stock information held in the store.
			</br></br>
			<?php if($cond != ""){ ?>
			<select name="brandfilter" id="brandfilter" onchange="loadstockbybrand(this)">
			<option value="">Select Brand </option>
			<?php while($brandget = mysql_fetch_array($brandqry)){ ?>
			<option value="<?php echo $brandget['brand_id']; ?>" <?php if($brandget['brand_id'] == $selbrandid){ ?> selected="selected" <?php } ?>> <?php echo $brandget['BRANDS']; ?></option>
			<?php } ?>
			</select>
			&nbsp; &nbsp;
			<select name="catfilter" id="catfilter" onchange="loadstockbycat(this)">
			<option value="">Select Category </option>
			<?php while($catget = mysql_fetch_array($cateqry)){ ?>
			<option value="<?php echo $catget['size_id']; ?>" <?php if($catget['size_id'] == $selcatid){ ?> selected="selected" <?php } ?>> <?php echo $catget['size_name']; ?></option>
			<?php } ?>
			</select>
			<?php } ?>
            </div>
          <div class="details" id="details">
       	<p>
				<h4>Stock Information</h4>
            </p>
			<p>
			Sales Details held on shop as on <?php echo $currentdate?>
			</p>
			<p>
			<select id="item" name="item" onchange="loadstock(this)" >
            <option value="">Select</option>
            <?php
            $sql1="SELECT * from item";
            $result=mysql_query($sql1);
            while($retrieveditems= mysql_fetch_array($result))
            {
			?>
            <option value="<?php echo $retrieveditems['item_id'] ?>" <?php if($retrieveditems['item_id'] == $selitemid){ ?> selected="selected" <?php } ?>><?php echo $retrieveditems['ITEMS']?></option>
            <?php
            }
            ?>
            </select>   
			</p>
<form>
<div id="table">
<?php if($_GET['itemid'] != ""){ ?>
<p>
<h2>Total <?php echo $itemdet.' '.$brandname.$catname.' : '.$totcount; ?></h2>
</p>
<?php } ?>
<table border="1">
<tr>
<th>Brand</th>
<th>Category</th>
<th>Quantity</th>
</tr>
<?php
while($stockdetail=mysql_fetch_array($stock_rows))
{
?>
<tr>
<td>
<?php
echo $stockdetail['BRANDS'];
?>
</td>
<td>
<?php
echo $stockdetail['size_name'];
?>
</td>
<td>
<?php
echo $stockdetail['QUANTITY'];
?>
</td>
</tr>
<?php
}
?>
</table>
<input type="button" id="print" name="print" value="Print" onclick="printTable()" />
</div>
</form>
<?php
}
?>


</div>
      </div>
            </div>
</div>

</body>
</html>