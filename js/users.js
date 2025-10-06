const searchBar = document.querySelector(".users .search input"),
searchBtn = document.querySelector(".users .search button"),
usersList = document.querySelector(".users .users-list");

// 1. Search Bar Toggle Logic
searchBtn.onclick = () => {
  searchBar.classList.toggle("active");
  searchBtn.classList.toggle("active");
  searchBar.focus();
  searchBar.value = "";
}

// 2. Real-time Users Fetching (Modified to stop during search)
setInterval(() => {
    // Only fetch the full user list if the search bar is NOT active
    if(!searchBar.classList.contains("active")){
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "php/get-users.php", true);
        
        xhr.onload = () => {
            if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200){
                usersList.innerHTML = xhr.response;
            }
        }
        xhr.send();
    }
}, 500);

// 3. New: Event Listener for Real-Time Searching (onkeyup)
searchBar.onkeyup = () => {
    let searchTerm = searchBar.value;
    
    if(searchTerm != "") {
        // A. Add 'active' class to prevent the setInterval loop from refreshing
        searchBar.classList.add("active");
        
        // B. Perform AJAX Search
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "php/search.php", true); // POST request to search.php
        
        xhr.onload = () => {
            if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200){
                // Update the user list with the search results
                usersList.innerHTML = xhr.response;
            }
        }
        
        // C. Send the search term data
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("searchTerm=" + searchTerm);
        
    } else {
        // If the search bar is cleared, remove 'active' from both elements
        searchBar.classList.remove("active");
        searchBtn.classList.remove("active");
    }
}