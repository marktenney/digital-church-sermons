var Now = new Date(),
  CurrentDay = Now.getDay(),
  OnAirTime = new Date(Now.getFullYear(), Now.getMonth(), Now.getDate(), 10, 45), //Set OnAirTime
  OffAirTime = new Date(Now.getFullYear(), Now.getMonth(), Now.getDate(), 12, 30), //Set OffAirTime
  OnAir = (Now.getTime() > OnAirTime.getTime() && Now.getTime() < OffAirTime.getTime());

if (CurrentDay == 0 && OnAir) { //If it's Sunday and you're OnAir
    $('.on-air').show(); // Show .on-air classes
	$('.off-air').hide(); // Hide .off-air classes
}
