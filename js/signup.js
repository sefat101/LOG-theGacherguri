const form = document.querySelector(".form.signup form"),
continueBtn = form.querySelector(".field-button input"),
errorText = form.querySelector(".error-text");

// Prevent default form submission
form.onsubmit = (e) => {
    e.preventDefault(); 
}

// Attach the AJAX logic to the button click
continueBtn.onclick = () => {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/signup.php", true); 
    
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) { 
            // Trim the response to remove any leading/trailing whitespace
            let data = xhr.response.trim();
            
            console.log("Server Response Status:", xhr.status);
            console.log("Server Response Text (Trimmed):", data); 

            if (xhr.status === 200) {
                
                // CHECK FOR THE SPECIFIC SUCCESS STRING
                if (data === "success_redirect_to_login") { 
                    // Redirect immediately to the login page
                    location.href = "login.php"; 
                } else {
                    // If the string does NOT match, it's an error message
                    errorText.style.display = "block";
                    errorText.textContent = data;
                }
            } else {
                // Handle non-200 statuses (like 404 or 500 error)
                errorText.style.display = "block";
                errorText.textContent = `Error: Server returned status ${xhr.status}. Check PHP logs.`;
            }
        }
    }
    
    // Send form data to PHP (including the image file)
    let formData = new FormData(form); 
    xhr.send(formData); 
}