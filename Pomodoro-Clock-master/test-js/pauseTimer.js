//pauseCountDown pauses the timer by clearing the setInterval
function pauseCountDown(event){
	var target = $(event.target);
	//clears setInterval which executes the timer
	engageButton.off();
	clearInterval(loopingTimer);
	//removes the pause class and appends the start class so that the timer can be affected by the user;
	target.removeClass("pause");
	target.addClass("start");
	//enables the engage button to allows users the ability to start the timer from the paused time
	engageButton.on("click",startCountDown);
	engageButton.html("Start");

}