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

	$cond = "";
	if($_GET['itemid'] != ""){
		$selitemid = $_GET['itemid'];
		$cond = " where s.item_id = '$selitemid'";
	}
	if($_GET['brandid'] != ""){
		$selbrandid = $_GET['brandid'];
		if($cond != ""){
			$cond .= "  and s.brand_id = '$selbrandid'";
		}else{
			$cond .= "  where s.brand_id = '$selbrandid'";
		}

	}
	if($_GET['sizeid'] != ""){
		$selsizeid = $_GET['sizeid'];
		if($cond != ""){
			$cond .= "  and s.size_id = '$selsizeid'";
		}else{
			$cond .= "  where s.size_id = '$selsizeid'";
		}

	}



	$sql1="SELECT s.*, i.ITEMS, b.BRANDS, c.size_name from stock s LEFT JOIN item i ON s.item_id = i.item_id LEFT JOIN brand b ON s.brand_id = b.brand_id LEFT JOIN size c ON s.size_id= c.size_id $cond";
	$stock_rows=mysql_query($sql1);
	$currentdate=date("d/m/Y");
	$date1=sqlDateformat($currentdate);

//	Total product count
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
		alert(sel.value);
		var itemid = sel.value;
		window.location.href='stocksummarypage.php?itemid='+itemid;
	}
	function loadstockbybrand(sel, itemid){
		alert(sel.value);
		alert(itemid);
		var brandid = sel.value;
		window.location.href="stocksummarypage.php?itemid="+itemid+"&brandid="+brandid;
	}
	function loadstockbycategory(sel, itemid, brandid){
		alert(sel.value);
		alert(itemid);
		alert(brandid);
		var sizeid = sel.value;
		window.location.href='stocksummarypage.php?itemid='+itemid+'&brandid='+brandid+'&sizeid='+sizeid;
	}
	
	var s1= document.getElementById("item");
	var s2 = document.getElementById("brand");
	onchange(); //Change options after page load
	s1.onchange = onchange; // change options when s1 is changed

	function onchange() {
		alert($selitemid);
	    <?php 
	    		$sql2="SELECT * from brand where item_id= '$selitemid'";
		$result2=mysql_query($sql2);
		$retrieveditems2= mysql_fetch_array($result2);
	    foreach ($retrieveditems2 as $sa) {    ?>
	        
	            option_html = "";
	            option_html += "<option><?php echo $a['BRANDS']; ?></option>";
	                
	    <?php } ?>
	            s2.innerHTML = option_html;
	            
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
	</div>
	<div class="details" id="details">
	<p>
	<h4>Stock Information</h4>
	</p>
	<p>
	Sales Details held on shop as on <?php echo $currentdate?>
	</p>
	<p>
	<select id="item" name="item" onchange="loadstockbybrand(this, '<?php echo $selitemid ?>' );" >
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


	<br>
	<select id="brand" name="brand" onchange="loadstockbybrand(this, '<?php echo $selitemid ?>' );" >
	<option value="">Select</option>
	<?php
			$sql2="SELECT * from brand where item_id='<?php echo $selitemid ?>'";
	$result2=mysql_query($sql2);
	while($retrieveditems2= mysql_fetch_array($result2))
	{
		?>
		<option value="<?php echo $retrieveditems2['brand_id'] ?>" <?php if($retrieveditems2['brand_id'] == $selbrandid){ ?> selected="selected" <?php } ?>><?php echo $retrieveditems2['BRANDS']?></option>
		<?php
	}
	?>
	</select>
	<br>
	<select id="size" name="size" onchange="loadstockbycategory(this, '<?php echo $selitemid ?>' , '<?php echo $selbrandid ?>' );" >
	<option value="">Select</option>
	<?php
			$sql3="SELECT * from size";
	$result3=mysql_query($sql3);
	while($retrieveditems3= mysql_fetch_array($result3))
	{
		?>
		<option value="<?php echo $retrieveditems3['size_id'] ?>" <?php if($retrieveditems3['size_id'] == $selcategoryid){ ?> selected="selected" <?php } ?>><?php echo $retrieveditems3['size_name']?></option>
		<?php
	}
	?>
	</select> 
	</p>

	<form>
	<div id="table">
	<?php if($_GET['itemid'] != ""){ ?>
	<p>
	<h2>Total <?php echo $itemdet .' : '.$totcount; ?></h2>
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