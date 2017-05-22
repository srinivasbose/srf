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
<!DOCTYPE HTML>
<html lang = "en">
<head>
<title>formDemo.html</title>
<meta charset = "UTF-8" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/jquery-3.2.1.js"></script>
<script>
$(document).ready(function(){ 
	$("#datediv").hide();
	$("#monthdiv").hide();
	$("#perioddiv").hide();
	<?php
		if(isset($_POST['Submit'])){
			  if(isset($_POST['billDate']) && $_POST['billDate'] != ''){ ?>
				$("#datediv").show();
				<?php		}else if(isset($_POST['billMonth']) && $_POST['billMonth'] != ''){ ?>
				$("#monthdiv").show();
				<?php			}else if( isset($_POST['fromDate']) && $_POST['fromDate'] != '' && $_POST['endDate'] != ''){ ?>
				$("#perioddiv").show();
				<?php			}
			

		} ?>

		


		$('input:radio[name="filterType"]').change(
				function(){
					if (this.checked && this.value == 'date') {
						// note that, as per comments, the 'changed'
						// <input> will *always* be checked, as the change
						// event only fires on checking an <input>, not
						// on un-checking it.
						// append goes here
						$("#datediv").show();
						$("#monthdiv").hide();
						$("#perioddiv").hide();
						$("#billMonth").val("");
						$("#fromDate").val("");
						$("#endDate").val("");
					}else if(this.checked && this.value == 'month'){
						$("#monthdiv").show();
						$("#datediv").hide();
						$("#perioddiv").hide();
						$("#billDate").val("");
						$("#fromDate").val("");
						$("#endDate").val("");
					}else if(this.checked && this.value == 'period'){
						$("#perioddiv").show();
						$("#datediv").hide();
						$("#monthdiv").hide();
						$("#billMonth").val("");
						$("#billDate").val("");

					}
				});


});



</script>
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
<div class="content" width=100%>
<form action="" method="post">
<fieldset>
<legend>Purchase Summary</legend>
<div id="show1">
<br>
<br>
<label>Filter by</label>            
<input type = "radio"
	name = "filterType"
		value = "date" <?php if(isset($_POST['Submit']) && $_POST['filterType'] =='date') echo "checked"; ?>
		/>
		<label for = "byDate">Date</label>
		<input type = "radio"
			name = "filterType"
				value = "month" <?php if(isset($_POST['Submit']) && $_POST['filterType'] =='month') echo "checked"; ?>
				/>
				<label for = "byMonth">Month</label>
				<input type = "radio"
					name = "filterType"
						value = "period"  <?php if(isset($_POST['Submit']) && $_POST['filterType'] =='period') echo "checked"; ?>
						/>
						<label for = "forPeriod">Period</label>
						<br>
						<br>
						</div>
						<div id="datediv">
						<label for="billDate">Bill Date:</label>
						<input type="date" id="billDate" name="billDate" <?php if(isset($_POST['Submit']) && $_POST['billDate'] != '') { ?> value="<?php echo $_POST['billDate'];?>" <?php }else{  ?>
						value =""  <?php } ?>
						/>
						</div>
						<div id="monthdiv">
						<label for="billMonth">Billing Month:</label>
						<input type="month" id="billMonth" name="billMonth" <?php if(isset($_POST['Submit']) && $_POST['billMonth'] != '') { ?> value="<?php echo $_POST['billMonth'];?>" <?php }else{  ?>
						value =""  <?php } ?> 
							/>
						</div>
						<div id="perioddiv">
						<label for="fromDate">From Date:</label>
						<input type="date" id="fromDate" name="fromDate"  <?php if(isset($_POST['Submit']) && $_POST['fromDate'] != '') { ?> value="<?php echo $_POST['fromDate'];?>" <?php }else{  ?>
						value ="" <?php } ?>  
						/> 

						<label for="endDate">End Date:</label>
						<input type="date" id="endDate" name="endDate"  <?php if(isset($_POST['Submit']) && $_POST['endDate'] != '') { ?>  value="<?php echo $_POST['endDate'];?>" <?php }else{  ?>
						value =""  <?php } ?> 
						/>
						</div>
						<br>
						<label for="item">Item:</label>
						<select id="item" name="item" >
						<option value="">Select</option>
						<?php
								$sql1="SELECT * from item ORDER BY ITEMS";
						$result=mysql_query($sql1);
						if(isset($_POST['Submit']) && $_POST['item'] != ''){
							$selitemid = $_POST['item'];
						}
						
						while($retrieveditems= mysql_fetch_array($result))
						{
							?>
							<option value="<?php echo $retrieveditems['item_id'] ?>" <?php if($retrieveditems['item_id'] == $selitemid){ ?> selected="selected" <?php } ?>><?php echo $retrieveditems['ITEMS']?></option>
							<?php
						}
						?>
			            </select> 
						
						<br>
						<br>
						<input type="submit" name="Submit" value ="Get Summary"  onclick="submit()"/>

						</fieldset> 

						
						<?php

								if(isset($_POST['Submit']))
								{		
									$cond = "";
									if($_POST['item'] != ""){
										$selitemid = $_POST['item'];
										$sql = "select ITEMS from item where item_id = '$selitemid'";
										$result = mysql_query($sql);
										
										$row = mysql_fetch_row($result);

										
										$cond = " AND ITEMS = '$row[0]'";
									}
									?> <fieldset> <?php 
									if($_POST["billDate"] != ''){
										$date = date_format(new DateTime($_POST["billDate"]), "Y-m-d");
										$sql1="SELECT * from purchase_vw where date = '$date' $cond";
										$result=mysql_query($sql1);
										if(mysql_num_rows($result) != 0){
											?>
											<br>
											<h4>Purchase Bills on <?php echo date_format(new DateTime($date),"d-m-Y") ?> </h4>
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
											<p><b>No Purchase bills on <?php echo date_format(new DateTime($date),"d-m-Y") ?>.</b></p>
											<?php
										}
									}else if($_POST["fromDate"] != '' && $_POST["endDate"] != ''){
										$fromDate = date_format(new DateTime($_POST["fromDate"]), "Y-m-d");
										$endDate = date_format(new DateTime($_POST["endDate"]), "Y-m-d");
										$sql1="SELECT * from purchase_vw where date >= '$fromDate' and date <= '$endDate' $cond";
										$result=mysql_query($sql1);
										if(mysql_num_rows($result) != 0){
											?>
											<br>
											<h4>Purchase Bills from <?php echo date_format(new DateTime($fromDate),"d-m-Y") ?> to <?php echo date_format(new DateTime($endDate),"d-m-Y") ?></h4>
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
											<p><b>No Purchase bills from <?php echo date_format(new DateTime($fromDate),"d-m-Y") ?> to <?php echo date_format(new DateTime($endDate),"d-m-Y") ?>.</b></p>
											<?php
										}
									}else if($_POST["billMonth"] != '' ){
										$first_day = $_POST['billMonth'] . "-01";
										$period = date_format(new DateTime($first_day), "Y-m-d");
										$sql1="SELECT * from purchase_vw WHERE date >= '$period' AND date  < '$period' + INTERVAL 1 MONTH $cond";
										$result=mysql_query($sql1);
										if(mysql_num_rows($result) != 0){
											?>
											<br>
											<h4>Purchase Bills from <?php echo date_format(new DateTime($_POST['billMonth']),"m-Y") ?> </h4>
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
											<p><b>No Purchase bills from <?php echo date_format(new DateTime($_POST['billMonth']),"m-Y") ?> .</b></p>
											<?php
										}


									}
									?></fieldset> <?php
								}

						?>      
						
						</form>
						</div>
						</div>
						</body>
						</html>