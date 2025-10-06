const form = document.querySelector(".form.login form"),
continueBtn = form.querySelector(".field-button input"),
errorText = form.querySelector(".error-text");

// Prevent the default form submission to handle it via AJAX
form.onsubmit = (e) => {
    e.preventDefault(); 
}

continueBtn.onclick = () => {
    // Start AJAX
    let xhr = new XMLHttpRequest();
    // Assuming your PHP login script is at php/login.php
    xhr.open("POST", "php/login.php", true); 
    
    xhr.onload = () => {
        // Check if the request is complete (readyState 4) and successful (status 200)
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            let data = xhr.response;
            
            // Debugging: Check the server response in the console
            console.log("Login Server Response:", data);
            
            // Check the response from the PHP script
            if (data.trim() === "success") {
                // If login is successful, redirect user to the users page
                location.href = "users.php";
            } else {
                // If there's an error, display it
                errorText.style.display = "block";
                errorText.textContent = data;
            }
        } else {
            // Display a general error if the server response status is not 200 (e.g., 500)
            errorText.style.display = "block";
            errorText.textContent = `Server Error: Status ${xhr.status}. Please check PHP logs.`;
        }
    }
    
    // Send form data to PHP
    // Since login only sends text data (email, password), FormData is still the easiest method
    let formData = new FormData(form); 
    xhr.send(formData); 
}