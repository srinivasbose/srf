var xmlHttp
var scripturl;
var displayobj;
var samp = 0;




function getItemBranddetails()
{ 
xmlHttp=GetXmlHttpObject()
if (xmlHttp==null)
 {
 alert ("Browser does not support HTTP Request")
 return
 }
 

var url="getdatas.php";
url=url+"?Item="+document.getElementById('item').value;
url=url+"&regfor="+"branddetails";
xmlHttp.onreadystatechange=stateChanged3 
xmlHttp.open("GET",url,true)
xmlHttp.send(null)	
}

function getItemSizedetails()
{ 
xmlHttp=GetXmlHttpObject()
if (xmlHttp==null)
 {
 alert ("Browser does not support HTTP Request")
 return
 }
 

var url="getdatas.php";
url=url+"?Item="+document.getElementById('item').value; <!--  +"&Brand="+document.getElementById('brand').value  -->
url=url+"&regfor="+"sizedetails";
xmlHttp.onreadystatechange=stateChanged4 
xmlHttp.open("GET",url,true)
xmlHttp.send(null)	
}


function stateChanged3() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
  //document.getElementById('cusResult').innerHTML=xmlHttp.responseText
  var Res = xmlHttp.responseText;
 // alert(Res);
   document.getElementById('brand').innerHTML=Res;
  // document.getElementById('g1age').value=spl[1];
  getItemSizedetails();
    } 
	
}

function stateChanged4() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
  //document.getElementById('cusResult').innerHTML=xmlHttp.responseText
  var Res = xmlHttp.responseText;
   document.getElementById('size').innerHTML=Res;
  // document.getElementById('g1age').value=spl[1];
    } 
}

function GetXmlHttpObject()
{
var xmlHttp=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e)
 {
 //Internet Explorer
 try
  {
  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e)
  {
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }
return xmlHttp;
}
