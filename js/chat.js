const form = document.querySelector(".typing-area"),
incoming_id = form.querySelector(".incoming_id").value,
inputField = form.querySelector(".input-field"),
sendBtn = form.querySelector("button"),
chatBox = document.querySelector(".chat-box");


// 1. Stop form from submitting normally
form.onsubmit = (e)=>{
    e.preventDefault();
}

// Ensure the chat box scrolls to the bottom initially
window.onload = function() {
    scrollToBottom();
};

// Add event listeners to the chatBox for scroll-lock feature
chatBox.onmouseenter = ()=>{
    chatBox.classList.add("active");
}

chatBox.onmouseleave = ()=>{
    chatBox.classList.remove("active");
}


// 2. AJAX to send the message
sendBtn.onclick = ()=>{
    let xhr = new XMLHttpRequest();
    // FIX: Corrected path for insert-chat.php
    xhr.open("POST", "../php/insert-chat.php", true); 
    xhr.onload = ()=>{
      if(xhr.readyState === 4 && xhr.status === 200){
          // On successful insert, clear the input field and scroll
          inputField.value = "";
          scrollToBottom();
      }
    }
    // Collect form data to send
    let formData = new FormData(form);
    xhr.send(formData);
}


// 3. Repeating AJAX call to get new messages every 500ms
setInterval(() =>{
    let xhr = new XMLHttpRequest();
    // FIX: Corrected path for get-chat.php
    xhr.open("POST", "../php/get-chat.php", true);
    xhr.onload = ()=>{
        if(xhr.readyState === 4 && xhr.status === 200){
            let data = xhr.response;
            chatBox.innerHTML = data;
            
            // Scroll to bottom only if the user is NOT actively scrolling/hovering
            if(!chatBox.classList.contains("active")){
                scrollToBottom();
            }
        }
    }
    // Send only the IDs needed to fetch the correct chat history
    let formData = new FormData(form);
    xhr.send(formData);
}, 500); // This will run every half second

// 4. Function to automatically scroll to the latest message
function scrollToBottom(){
    chatBox.scrollTop = chatBox.scrollHeight;
}