document.addEventListener("DOMContentLoaded", function () {
    // Select elements
    const elements = document.querySelectorAll("h1, p, .left-box");
    const loginBox = document.querySelector(".login-box");

    // Hide elements initially
    elements.forEach((el) => {
        el.style.opacity = "0";
        el.style.visibility = "hidden";
        el.style.transform = "translateY(30px)";
    });

    // Image slides from left
    const leftImage = document.querySelector(".left-box");
    if (leftImage) {
        leftImage.style.transform = "translateX(-50px)";
    }

    // Login box starts off-screen (Right side)
    if (loginBox) {
        loginBox.style.opacity = "0";
        loginBox.style.visibility = "hidden";
        loginBox.style.transform = "translateX(100px)";  // Swiping effect only
    }

    // Start animations
    requestAnimationFrame(() => {
        setTimeout(() => {
            // Fade-in & movement for text and image
            elements.forEach((el) => {
                el.style.transition = "opacity 1.2s ease-out, transform 1.2s ease-out";
                el.style.opacity = "1";
                el.style.visibility = "visible";
                el.style.transform = "translateY(0)";
            });

            // Ensure image slides in at the same time
            if (leftImage) {
                leftImage.style.transform = "translateX(0)";
            }

            // Swipe-in animation for login box (No popup)
            if (loginBox) {
                loginBox.style.transition = "opacity 1.2s ease-out, transform 1.2s ease-out";
                loginBox.style.opacity = "1";
                loginBox.style.visibility = "visible";
                loginBox.style.transform = "translateX(0)";  // No scale, just swipe
            }
        }, 300); // Delay to sync animations
    });

    // Prevent animation retriggering when revisiting the tab
    document.addEventListener("visibilitychange", function () {
        if (document.visibilityState === "visible") {
            elements.forEach((el) => el.style.transition = "none");
            if (loginBox) loginBox.style.transition = "none";
        }
    });
});