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
	$currentdate=date("d/m/Y");
	
	?>

	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Stock Summary Page</title>
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="ajaxreq.js"></script>
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
	
	function SetData(){
		var select = document.getElementById('item');
		var itemid = select.options[select.selectedIndex].value;
		var selectbrand = document.getElementById('brand');
		var brandid = selectbrand.options[selectbrand.selectedIndex].value;
		var selectsize = document.getElementById('size');
		var sizeid = selectsize.options[selectsize.selectedIndex].value;
		document.myform.action = "stocksummarypagenew.php?Item="+itemid+"&Brand="+brandid+"&Size="+sizeid+"&Submitted=true";
		document.myform.submit();
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





	<form name="myform" action=" " method="post" onsubmit="SetData()">
	<table width="500">
	<tr>
	<td>Item:</td>
	<td>
	<select id="item" name="item" onchange="getItemBranddetails()" >
	<option value="">Select</option>
	<?php
			$sql1="SELECT * from ITEM ORDER BY ITEMS";
	$result=mysql_query($sql1);
	while($retrieveditems= mysql_fetch_array($result))
	{
		if($_GET['Item'] != ""){
			$selitemid = $_GET['Item']; ?>
			<option value="<?php echo $retrieveditems['item_id'] ?>" <?php if($retrieveditems['item_id'] == $selitemid){ ?> selected="selected" <?php } ?>><?php echo $retrieveditems['ITEMS']?></option>
			<?php 
		}else{
			?>
			<option value="<?php echo $retrieveditems['item_id'] ?>"><?php echo $retrieveditems['ITEMS']?></option>
			<?php
		}
	}
	?>
	</select>            </td>
	</tr>

	<tr>
	<td>Brand:</td>
	<td>
	<select id="brand" name="brand" >
	<option value="">Select</option>
	<?php 
			
				$selitemid = $_GET['Item'];
				$selbrandid = $_GET['Brand'];
				$sql2="SELECT * from brand where item_id='$selitemid' ORDER BY BRANDS";
				$result2=mysql_query($sql2);
				while($retrieveditems2= mysql_fetch_array($result2))
				{
					if($_GET["Brand"] != ""){
					
				?>
						<option value="<?php echo $retrieveditems2['brand_id'] ?>" <?php if($retrieveditems2['brand_id'] == $selbrandid){ ?> selected="selected" <?php } ?>><?php echo $retrieveditems2['BRANDS']?></option>
			    <?php 
					}
					else{ ?>
					
					<option value="<?php echo $retrieveditems2['brand_id'] ?>"><?php echo $retrieveditems2['BRANDS']?></option>
			  <?php 
					} 
				}
			     ?>
			
			
			</select>            </td>
			</tr>
			<tr>
			<td>Category:</td>
			<td>
			<select id="size" name="size">
			 <option value="">Select</option>
			<?php

					
						$selitemid2 = $_GET['Item'];
						$selbrandid2 = $_GET['Brand'];
						$selsizeid2 = $_GET['Size'];

						$sql3="SELECT * from size where item_id='$selitemid' ORDER BY size_name ";
						echo "<script>alert('$sql3');</script>";
						$result3=mysql_query($sql3);
						while($retrieveditems3= mysql_fetch_array($result3))
						{
							$sizename=$retrieveditems3['size_name'];
							if($selsizeid2 != ""){
								
							?>
							    
									<option value="<?php echo $retrieveditems3['size_id'] ?>" <?php if($retrieveditems3['size_id'] == $selsizeid2){ ?> selected="selected" <?php } ?>><?php echo $retrieveditems3['size_name']?></option>
							   <?php 
							}
							else{   ?>
						   
						    <option value="<?php echo $retrieveditems3['size_id'] ?>" ><?php echo $retrieveditems3['size_name']?></option>	
					 <?php   
							 }
						}
					    
			?>
			</select>            </td>
			</tr>
			<tr><td colspan="2" align="center">
			<input type="submit" name="Submit" /></td></tr>
			</table>

			<div id="table">
			<?php 


					if(!isset($_POST['Submit'])){
						$cond="";
					}else{
						$cond = "";
						if($_GET['Item'] != ""){
							$selitemid = $_GET['Item'];
							$cond = " where s.item_id = '$selitemid'";
						}
						if($_GET['Brand'] != ""){
							$selbrandid = $_GET['Brand'];
							if($cond != ""){
								$cond .= "  and s.brand_id = '$selbrandid'";
							}else{
								$cond .= "  where s.brand_id = '$selbrandid'";
							}

						}
						if($_GET['Size'] != ""){
							$selsizeid = $_GET['Size'];
							if($cond != ""){
								$cond .= "  and s.size_id = '$selsizeid'";
							}else{
								$cond .= "  where s.size_id = '$selsizeid'";
							}

						}

                    }

						$sql1="SELECT s.*, i.ITEMS, b.BRANDS, c.size_name from stock s LEFT JOIN item i ON s.item_id = i.item_id LEFT JOIN brand b ON s.brand_id = b.brand_id LEFT JOIN size c ON s.size_id= c.size_id $cond ORDER BY i.ITEMS,b.BRANDS";  
						$stock_rows=mysql_query($sql1);
						$currentdate=date("d/m/Y");
						$date1=sqlDateformat($currentdate);

//						Total product count
						$cntqry = mysql_query("select SUM(quantity), i.ITEMS from stock s LEFT JOIN item i ON s.item_id = i.item_id $cond ");
						$fetchnct = mysql_fetch_array($cntqry);
						$totcount = 0;
						
						$totcount = $fetchnct[0];
						if(!isset($_POST['Submit'])){
							
							$itemdet = "Items";
						}
						else{
							$itemdet = $fetchnct[1];
						}

						if( mysql_num_rows($stock_rows) != 0){

							?>
							<p>
							<h2>Total <?php echo $itemdet .' : '.$totcount; ?></h2>
							</p>
							<table border="1">
							<tr>
							<th>Item</th>
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
												echo $stockdetail['ITEMS'];
										?>
										</td>
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
							<?php 
						}
						else
						{ 	?>
							<p>No Stock Found.</p>
							<?php 
						}
						 ?> 
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