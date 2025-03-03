document.addEventListener("DOMContentLoaded", function () {
    // Select elements
    const header = document.querySelector("h1");
    const welcomeText = document.querySelector("p");
    const loginBox = document.querySelector(".login-box");
    const leftImage = document.querySelector(".left-box");

    // Apply initial styles for animation
    header.style.opacity = "0";
    header.style.transform = "translateY(-20px) scale(0.8)";
    welcomeText.style.opacity = "0";
    welcomeText.style.transform = "translateY(-20px) scale(0.8)";
    loginBox.style.opacity = "0";
    loginBox.style.transform = "scale(0.8)";
    leftImage.style.opacity = "0";
    leftImage.style.transform = "translateX(-100%)";

    // Animate elements with a delay
    setTimeout(() => {
        header.style.transition = "opacity 1.5s ease-in-out, transform 1.5s ease-in-out";
        header.style.opacity = "1";
        header.style.transform = "translateY(0) scale(1)";
    }, 500);

    setTimeout(() => {
        welcomeText.style.transition = "opacity 1.5s ease-in-out, transform 1.5s ease-in-out";
        welcomeText.style.opacity = "1";
        welcomeText.style.transform = "translateY(0) scale(1)";
    }, 700);

    setTimeout(() => {
        loginBox.style.transition = "opacity 1s ease-in-out, transform 0.8s ease-in-out";
        loginBox.style.opacity = "1";
        loginBox.style.transform = "scale(1)";
    }, 1000);

    setTimeout(() => {
        leftImage.style.transition = "opacity 1.5s ease-out, transform 1.5s ease-out";
        leftImage.style.opacity = "1";
        leftImage.style.transform = "translateX(0)";
    }, 1200);
});