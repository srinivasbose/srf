<?php
$con = mysql_connect("localhost","root","");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("srfam", $con);
$htm='';
if($_GET['regfor'] == "branddetails"){
$item = $_REQUEST['Item'];
$checkicqry = mysql_query("select * from brand where item_id = '$item'");
$htm.= '<option value="">Select</option>';
while($retrievedbrands= mysql_fetch_array($checkicqry))
{
$brand=$retrievedbrands['BRANDS'];
$brand_id = $retrievedbrands['brand_id'];
$htm.= '<option value="'.$brand_id.'">'.$brand.'</option>';
}
echo $htm;
} 

if($_GET['regfor'] == "sizedetails"){
$item = $_REQUEST['Item'];
$brand = $_REQUEST['Brand'];
$checkicqry = mysql_query("select * from size where item_id = '$item' "); // and brand_id = '$brand'
$htm.= '<option value="">Select</option>';
while($retrievedbrands= mysql_fetch_array($checkicqry))
{
$size_name=$retrievedbrands['size_name'];
$size_id = $retrievedbrands['size_id'];
$htm.= '<option value="'.$size_id.'">'.$size_name.'</option>';
}
echo $htm;
} 
?>