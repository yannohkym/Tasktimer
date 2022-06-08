
//global variables which are needed accross various functions
var engageButton= $("#engage-button");
var subtractTime = $(".subtract-time");
var addTime = $(".add-time");
var resetButton = $("#reset-button");
var loopingTimer;

//event listeners which allow for the user to start and reset the pomodoro clock
engageButton.on("click",startCountDown);
resetButton.on("click",resetTimer);

//event listeners which allow for the user to change the break and work session times
subtractTime.on("click",changeSessionValues);
addTime.on("click",changeSessionValues);
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


//startCountDown and timerChange manage the executions of the timer

function startCountDown(event){
	var target = $(event.target);
	//unbinds the events of the decrement,increment, and start events while the timer is in use
	engageButton.off();
	subtractTime.off();
	addTime.off();
	//removes the start class and add the pause class which will change the inner html of the button
	target.removeClass("start");
	target.addClass("pause");
	//binds event which allows for the timer to be paused
	engageButton.on("click",pauseCountDown);
	engageButton.html("Pause");
	//variables which target the elements which contain the work and break times
	var workDuration = $("#work-time");
	var breakDuration = $("#break-time");
	//changes the time timer title to the initial working session title
	$("#timer-title").hide().html("Time to Focus!").fadeIn();
	//begins the timer loop which will load the animation and 
	timerChange(workDuration,breakDuration);

}


function timerChange(workTime,breakTime){
	// variables which mark the beginning states of the Work and Break time
	var initialWorkTime = workTime.html() + ":00";
	var initialBreakTime = breakTime.html() + ":00";
	//variable which targes the timer clock element
	var timerClock = $("#timer-clock");
	//variable which targets the timer bell which contains the audio tag
	var timerBell = document.getElementById("timer-bell");
	//sets the audio tag volume
	timerBell.volume = 0.5;
	//variables which contain pixels/second metric which is the number the timer filler needs to progress per each second
	var workLoadInterval = 410 / (parseInt(initialWorkTime) * 60);
    var breakLoadInterval = 410 / (parseInt(initialBreakTime) * 60);

    //condition changes the timer-clock html to the initial worktime
	if(workTime.html() === timerClock.html() ){
			timerClock.html(initialWorkTime);
		}
	//setInterval sets the intial timer function
    loopingTimer  = window.setInterval(function(){
    	//min and secs assign the min and seconds strings to be used later in the function
    	var min = timerClock.html().split(":")[0];
    	var secs = timerClock.html().split(":")[1];
    	//condition for when the timer reaches 0:00
    	if(timerClock.html() === "0:00"){
    		//reset the loader element out of view
    		$(".timer-filler").css("top", "100%");
    		//condition to detect if a working session has ended
    		if($("#timer-title").html() === "Time to Focus!"){
    			//change html to Break Time and change the audio tag  to alert the user that the session has ended
    			$("#timer-title").html("Break Time");
    			$(".timer-filler").toggleClass("timer-filler-work");
    			$(".timer-filler").toggleClass("timer-filler-break");
    			timerClock.html(initialBreakTime);
    			timerBell.src = "./Pomodoro-Clock-master/breakbell.mp3";
    			timerBell.play();
    			return;
    		}
    		//change the html to a working session and change/play the audio tag as appropriate
    		$("#timer-title").html("Time to Focus!");
    		$(".timer-filler").toggleClass("timer-filler-work");
    	    $(".timer-filler").toggleClass("timer-filler-break");
    		timerClock.html(initialWorkTime);
    		timerBell.src = "./Pomodoro-Clock-master/workbell.mp3";
    		timerBell.play();

    		return;
    	}

    	//if the seconds counter has reached 00 decrease the minute by one and restart the minute counter
		if(secs === "00"){
			timerClock.html(function(index,oldhtml){
				return (parseInt(min) -1) + ":" + (60 - 1);
			});
		}

		//if the seconds counter has not reached 00 decrement the seconds counter by oene and move the timer loader the appropriate amount to indicate to the user the progress of the session
		if(secs !== "00"){
			timerClock.html(function(index,oldhtml){
				var seconds = parseInt(secs) - 1;
				if (seconds < 10){
					seconds = "0" + seconds;
				}
				return min + ":" + seconds ;
			});

			if($("#timer-title").html() === "Time to Focus!" ){
				$(".timer-filler").css("top", "-=" + workLoadInterval);
			}

			if($("#timer-title").html() === "Break Time"){
				$(".timer-filler").css("top", "-=" + breakLoadInterval);
			}

			
		}
		

	},1000);

}

