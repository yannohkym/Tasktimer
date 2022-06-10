$(document).ready(()=>{
    $.ajax({
        type: 'POST',
        url: './ajax/taskDetails.php',
        data: {pomodoro : 1, config : 1},
        success: function (response) {
            let pomodoroTime = response[0]
            timer(pomodoroTime);
        },
        dataType: 'json',
        error: () => {
            console.error();
        }
    })

   function timer(pomodoro){
       let timerInstance = new easytimer.Timer();
       sessionStorage.setItem('duration', pomodoro["duration"]);
       let TIME_LIMIT = parseInt(sessionStorage.getItem('duration'))*60;
       let timeLeft = parseInt(sessionStorage.getItem('duration'))*60;
       let interval = parseInt(pomodoro['intervals']);
       let compinterval = 1;
       let work = 1;
       let pomodororem = sessionStorage.getItem('pomodoro');

       const FULL_DASH_ARRAY = 283;
       let WARNING_THRESHOLD = timeLeft/4;
       let ALERT_THRESHOLD = timeLeft/8;

       let COLOR_CODES = {
           info: {
               color: "green"
           },
           warning: {
               color: "orange",
               threshold: WARNING_THRESHOLD
           },
           alert: {
               color: "red",
               threshold: ALERT_THRESHOLD
           }
       };
       let timePassed = 0;
       let timerInterval = null;
       let remainingPathColor = COLOR_CODES.info.color;
       let audiowork = new Audio('./javascript/workbell.mp3');
       let audiobreak = new Audio('./javascript/breakbell.mp3');

       document.getElementById("app").innerHTML = `
            <div class="base-timer">
              <svg class="base-timer__svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <g class="base-timer__circle">
                  <circle class="base-timer__path-elapsed" cx="50" cy="50" r="45"></circle>
                  <path
                    id="base-timer-path-remaining"
                    stroke-dasharray="283"
                    class="base-timer__path-remaining ${remainingPathColor}"
                    d="
                      M 50, 50
                      m -45, 0
                      a 45,45 0 1,0 90,0
                      a 45,45 0 1,0 -90,0
                    "
                  ></path>
                </g>
              </svg>
              <span id="base-timer-label" class="base-timer__label">${formatTime(
                       timeLeft
                   )}</span>
            </div>
            `;
       $('#buttonstart').click(function (){
           timer(pomodoro['duration']);
           $('#buttonpause').css('display', 'block');
           $('#buttonstart').css('display', 'none');

       })
       $('#buttonpause').click(function (){
           timerInstance.pause();
           $('#buttonresume').css('display', 'block');
           $('#buttonpause').css('display', 'none');
       })
       $('#buttonstop').click(function (){
           timerInstance.stop();
           $('#buttonstart').css('display', 'block');
           $('#buttonpause').css('display', 'none');
       })
       $('#buttonresume').click(function (){
           timerMain((timerInstance.getTotalTimeValues().seconds)/60);
           $('#buttonpause').css('display', 'block');
           $('#buttonresume').css('display', 'none');
       })

       function timer(time){
           TIME_LIMIT = time*60;
           timerMain(time);
       }

       function timerMain(time){
           timeLeft = time*60;
           WARNING_THRESHOLD = timeLeft/4;
           ALERT_THRESHOLD = timeLeft/8;
           COLOR_CODES = {
               info: {
                   color: "green"
               },
               warning: {
                   color: "orange",
                   threshold: WARNING_THRESHOLD
               },
               alert: {
                   color: "red",
                   threshold: ALERT_THRESHOLD
               }
           };
           timerInstance.start({countdown: true, startValues: {minutes: parseInt(time)}});
           $('#countdownExample .values').html(timerInstance.getTimeValues().toString());
           timerInstance.addEventListener('secondsUpdated', function (e) {
               timeLeft = timerInstance.getTotalTimeValues().seconds;
               sessionStorage.setItem('current', timerInstance.getTotalTimeValues().seconds)
               $('#base-timer-label').html(formatTime(
                   sessionStorage.getItem('current')
               ));
               setCircleDasharray();
               setRemainingPathColor(timerInstance.getTotalTimeValues().seconds);
               if (timerInstance.getTotalTimeValues().seconds === 0) {
                   onTimesUp();
               }
           });
       }

       timerInstance.addEventListener('targetAchieved', function (e) {

           if(parseInt(pomodororem) !== 0){
               if (work == 1){
                   work = 0;
                   console.log(compinterval);
                   console.log(interval);
                   if(compinterval != interval){
                       compinterval++
                       console.log(compinterval);
                       pomodororem--
                       sessionStorage.setItem('pomodoro', pomodororem);
                       $('.apple').remove();
                       if(pomodororem<=4){
                           for(let i=0; i<pomodororem; i++){
                               document.getElementById('pomNo').innerHTML += '<span class="apple" ><i style="color: darkred" class="fas fa-apple-alt apple"></i></span>';
                           }
                       }else {
                           document.getElementById('pomNo').innerHTML += '<span class="apple">'+pomodororem+'<i style="color: darkred" class="fas fa-apple-alt"></i></span>';
                       }
                       timer(pomodoro['short_break'])
                       audiobreak.play();
                       $('#message').text('Enjoy! your short break').css('color','green');
                   }else {
                       compinterval = 1;
                       pomodororem--
                       sessionStorage.setItem('pomodoro', pomodororem);
                       $('.apple').remove();
                       if(pomodororem<=4){
                           for(let i=0; i<pomodororem; i++){
                               document.getElementById('pomNo').innerHTML += '<span class="apple" ><i style="color: darkred" class="fas fa-apple-alt apple"></i></span>';
                           }
                       }else {
                           document.getElementById('pomNo').innerHTML += '<span class="apple">'+pomodororem+'<i style="color: darkred" class="fas fa-apple-alt"></i></span>';
                       }
                       timer(pomodoro['long_break'])
                       $('#message').text('Hooray! Enjoy your long break').css('color','green');
                       audiobreak.play();
                   }
               }else{
                   work = 1;
                   timer(pomodoro['duration'])
                   audiowork.play();
                   $('#message').text('Work time! stay focus').css('color','darkred');

               }
           }else{
               $('#buttonstart').css('display', 'block');
               $('#buttonpause').css('display', 'none');
               $('#message').text('Pomodoro completed').css('color','darkred');
               audiobreak.play();
           }

       });






// $('#base-timer-label').html(time);

       function onTimesUp() {
           clearInterval(timerInterval);
       }

       function formatTime(time) {
           const minutes = Math.floor(time / 60);
           let seconds = time % 60;

           if (seconds < 10) {
               seconds = `0${seconds}`;
           }

           return `${minutes}:${seconds}`;
       }

       function setRemainingPathColor(timeLeft) {
           const { alert, warning, info } = COLOR_CODES;
           if (timeLeft <= alert.threshold) {
               document
                   .getElementById("base-timer-path-remaining")
                   .classList.remove(warning.color);
               document
                   .getElementById("base-timer-path-remaining")
                   .classList.add(alert.color);
           } else if (timeLeft <= warning.threshold) {
               document
                   .getElementById("base-timer-path-remaining")
                   .classList.remove(info.color);
               document
                   .getElementById("base-timer-path-remaining")
                   .classList.add(warning.color);
           }else{
               document
                   .getElementById("base-timer-path-remaining")
                   .classList.remove(alert.color);
               document
                   .getElementById("base-timer-path-remaining")
                   .classList.add(info.color);
           }
       }

       function calculateTimeFraction() {
           const rawTimeFraction = timeLeft / TIME_LIMIT;
           return rawTimeFraction - (1 / TIME_LIMIT) * (1 - rawTimeFraction);
       }

       function setCircleDasharray() {
           const circleDasharray = `${(
               calculateTimeFraction() * FULL_DASH_ARRAY
           ).toFixed(0)} 283`;
           document
               .getElementById("base-timer-path-remaining")
               .setAttribute("stroke-dasharray", circleDasharray);
       }

   }


})

