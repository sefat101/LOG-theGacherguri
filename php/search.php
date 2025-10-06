<?php
    session_start();
    include_once "config.php";
    include_once "data.php"; // <-- Include the shared function file

    if(!isset($_SESSION['unique_id'])){
        echo "Error: Not authenticated.";
        exit();
    }
    
    $outgoing_id = $_SESSION['unique_id'];
    $output = "";
    
    // Retrieve and sanitize the search term from the AJAX POST request
    $searchTerm = mysqli_real_escape_string($conn, $_POST['searchTerm']);

    // SQL query to search first name OR last name, excluding the current user
    $sql = "SELECT * FROM users 
            WHERE NOT unique_id = {$outgoing_id} 
            AND (fname LIKE '%{$searchTerm}%' OR lname LIKE '%{$searchTerm}%')
            ORDER BY status DESC, fname ASC";
            
    $query = mysqli_query($conn, $sql);

    // Process the results
    if(mysqli_num_rows($query) == 0){
        $output .= "No user found related to your search term: \"" . htmlspecialchars($searchTerm) . "\"";
    } else {
        while($row = mysqli_fetch_assoc($query)){
            // Call the shared function to generate the HTML for each user
            $output .= formatUserList($row, $outgoing_id, $conn);
        }
    }
    
    echo $output;
?>