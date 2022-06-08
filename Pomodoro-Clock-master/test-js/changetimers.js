//change Session Values allows for the user to change the values of the break and work session times
function changeSessionValues(event){
	var target = $(event.target);
	var timerNumber;
	//decreases the break or work time by one 
	if (target.is(subtractTime)){
		target.next().html(function(index,oldhtml){
			//does not allow the number to be lower than one
			if(parseInt(oldhtml) === 1){
				return oldhtml;
			}
			timerNumber = parseInt(oldhtml) - 1 ;
			return timerNumber;
		});
	}
	//increase the the break or work time by one
	if (target.is(addTime)){
		target.prev().html(function(index,oldhtml){
			timerNumber = parseInt(oldhtml) + 1 ;
			return timerNumber ;
		});
	}
	//every time the work duration is changed the timer-clock html will be changed simultaneously
	if (target.parents().is("#work-container")){
		$("#timer-clock").html(timerNumber);
	}	
}