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
<!DOCTYPE html>
<html>
<head>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<style>
* {box-sizing: border-box}
body {font-family: "Lato", sans-serif;}

/* Style the tab */
div.tab {
	float: left;
border: 1px solid #ccc;
background-color: #f1f1f1;
width: 30%;
height: 300px;
}

/* Style the buttons inside the tab */
div.tab button {
	display: block;
background-color: inherit;
color: black;
padding: 22px 16px;
width: 100%;
border: none;
outline: none;
text-align: left;
cursor: pointer;
transition: 0.3s;
font-size: 17px;
}

/* Change background color of buttons on hover */
div.tab button:hover {
	background-color: #ddd;
}

/* Create an active/current "tab button" class */
div.tab button.active {
	background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
	float: left;
padding: 0px 12px;
border: 1px solid #ccc;
width: 70%;
border-left: none;
height: 300px;
}
function submit(){
	
}
</style>
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
		<form name="purchaseSummary" method="post">
		<div class="tab">
			<button class="tablinks" onclick="openTab(event, 'byDate')" id="defaultOpen">By Date</button>
			<button class="tablinks" onclick="openTab(event, 'month')">By Month</button>
			<button class="tablinks" onclick="openTab(event, 'dateRange')">Between Period</button>
		</div>

		<div id="byDate" class="tabcontent">
			<h2><b>Purchase Summary by Date</b></h2>
			<table width="500">
				<tr>
					<td>
						<label for="date">Bill Date:</label>
						<input type="date" id="date" name="date" value =""  /></td>
					</td>
				</tr>
				<tr>
					<td>
						<input type="submit" name="Submit" value ="Get Summary"  onclick="submit()"/>
					</td>
				</tr>
			</table>
		</div>

		<div id="month" class="tabcontent">
			<h2><b>Purchase Summary by Month</b></h2>
		  <table width="500">
			<tr>
				<td>
					<label for="date">Bill Date:</label>
					<input type="date" id="date" name="date" value =""  /></td>
				</td>
			</tr>
			<tr>
				<td>
					<input type="submit" name="Submit" value ="Get Summary" onclick="submit()" />
				</td>
			</tr>
		  </table>
		</div>

<div id="dateRange" class="tabcontent">
<h2><b>Purchase Summary for Period</b></h2>


<table width="500">

<tr><td>
<label for="date">From Date:</label>
<input type="date" id="fromDate" name="fromDate" value =""  /></td>
<td>
<label for="date">End Date:</label>
<input type="date" id="endDate" name="endDate" value =""  /></td>
</tr>



<tr>
<td>
<input type="submit" name="Submit" value ="Get Bills" onclick="submit()" />
</td>
</tr>
</table>
</div>



<?php

		if(isset($_POST['Submit']))
		{		
			if($_POST["date"] != ''){
				$date = date_format(new DateTime($_POST["date"]), "Y-m-d");
				$sql1="SELECT * from purchase_vw where date = '$date'";
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
				$sql1="SELECT * from purchase_vw where date >= '$fromDate' and date <= '$endDate'";
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
			}else {
				?>
				alert("Hi");
				<?php
			}
		}

?>
</form>

</div>


<script>
function openTab(evt, eventType) {
	var i, tabcontent, tablinks;
	tabcontent = document.getElementsByClassName("tabcontent");
	for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	}
	tablinks = document.getElementsByClassName("tablinks");
	for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	}
	document.getElementById(eventType).style.display = "block";
	evt.currentTarget.className += " active";
}

//Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>


</div>
</div>
</body>
</html> 
