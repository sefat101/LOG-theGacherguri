// Select the password field input
const pswrdField = document.querySelector(".form input[type='password']");

// Select the eye icon using a specific class added to the HTML
const toggleIcon = document.querySelector(".form .show-hide-icon"); 

// Check if both elements exist before trying to attach the click listener
if (toggleIcon) {
    toggleIcon.onclick = () => {
        if (pswrdField.type === "password") {
            pswrdField.type = "text";
            toggleIcon.classList.add("active");
        } else {
    
            pswrdField.type = "password"; 
            toggleIcon.classList.remove("active");
        }
    }
}