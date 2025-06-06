const eye = document.querySelector(".feather-eye");
const eyeoff = document.querySelector(".feather-eye-off");
const passwordField = document.querySelector("input[type=password]");

eye.addEventListener("click", () => {
    eye.style.display = "none";
    eyeoff.style.display = "block";
    passwordField.type = "text";
});
  
eyeoff.addEventListener("click", () => {
    eyeoff.style.display = "none";
    eye.style.display = "block";
    passwordField.type = "password";
});

var options = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
var date = new Date();
var datedisplay = date.toLocaleDateString("fr-FR", options);
var hour = date.getUTCHours();
var minute = date.getUTCMinutes();
var second = date.getUTCSeconds();
var timeDisplay = function() {
    if(second<59)
        second++;
    else {
        minute++;
        second = 0;
    }
    if(minute>59) {
        hour++;
        minute = 0;
    }
    document.getElementById("horloge").textContent = datedisplay + " - " +  formatTime(hour) + ":" + formatTime(minute) + ":" + formatTime(second) + " UTC(Z)";
    setTimeout(timeDisplay, 1000);
}

function formatTime(timeconvert) {
    if(timeconvert.toString().length==2) {
        return timeconvert.toString();
    } else {
        return "0" + timeconvert.toString();
    }
}

setTimeout(timeDisplay, 1000);