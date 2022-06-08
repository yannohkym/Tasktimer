
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