// Typewriter effect for the welcome message
const welcomeMessage = "Welcome to TrackSmart";
let index = 0;

function typeWriter() {
    if (index < welcomeMessage.length) {
        document.getElementById("welcomeMessage").innerHTML += welcomeMessage.charAt(index);
        index++;
        setTimeout(typeWriter, 100);
    }
}

typeWriter();

// Adding functionality for the "Get Started" button
document.getElementById("startButton").addEventListener("click", function() {
    // Redirect to the login page
    window.location.href = "login.php";
});
