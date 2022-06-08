//resetTimer breaks the current setInterval and resets the Pomodoro Timer
function resetTimer(){
	//clear setInterval
	clearInterval(loopingTimer);
	//change timer to match the work duration string
	$("#timer-clock").html(function(index,oldhtml){
		return $("#work-time").html();
	});
	//reenable the add, subtract, and start timer events to allow for the user to customize the pomodoro sequence
	$(".timer-filler").css("top", "100%");
	subtractTime.on("click",changeSessionValues);
	addTime.on("click",changeSessionValues);
	engageButton.off();
	engageButton.on("click",startCountDown);
	engageButton.html("Start");
}

