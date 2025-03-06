let index = 0;
const bannerImage = document.getElementById("bannerImage");
const shopNowBtn = document.getElementById("shopNowBtn");

function changeBanner() {
    if (images.length > 1) {
        // Smooth transition for banner
        bannerImage.style.transition = "transform 1.2s cubic-bezier(0.25, 1, 0.5, 1), opacity 1.2s ease-in-out";
        shopNowBtn.style.transition = "opacity 1.2s ease-in-out";
        
        // Swipe out the current image
        bannerImage.style.transform = "translateX(-100%)";
        bannerImage.style.opacity = "0";
        shopNowBtn.style.opacity = "0";

        setTimeout(() => {
            index = (index + 1) % images.length;
            bannerImage.src = "http://localhost/" + images[index];
            
            // Smooth fade-in effect
            bannerImage.style.transform = "translateX(0)";
            bannerImage.style.opacity = "1";
            shopNowBtn.style.opacity = "1";
        }, 600); 
    }
}

setInterval(changeBanner, 5000);
