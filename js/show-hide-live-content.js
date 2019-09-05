function test(){
  var date=new Date();
  if(date.getDay()===0&& date.getHours()+1 >=9 && date.getMinutes()+1 >=45)
  { //9:45-11:30AM
    $(".on-air").dialog();
    clearInterval(iId);
  }
elseif(date.getDay()===0&& date.getHours()+1 == 11 && date.getMinutes()+1 <= 30)
{
$(".on-air").dialog();
clearInterval(iId);
}
}
