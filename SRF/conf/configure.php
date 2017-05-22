<?php 
function sqlDateformat($dates){
return substr($dates,6,4)."-".substr($dates,3,2)."-".substr($dates,0,2);
}


function userDDateformat($dates){
return substr($dates,8,2)."-".substr($dates,5,2)."-".substr($dates,0,4);
}

?>