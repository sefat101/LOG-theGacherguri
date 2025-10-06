<?php
    session_start();
    include_once "config.php";
    include_once "data.php"; // <-- Include the shared function file

    if(!isset($_SESSION['unique_id'])){
        header("location: ../login.php");
        exit(); 
    }

    $outgoing_id = $_SESSION['unique_id'];
    $output = "";

    // Query all users except the current one, ordered by status (online first)
    $sql = "SELECT * FROM users WHERE NOT unique_id = {$outgoing_id} ORDER BY status DESC, unique_id DESC";
    $query = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($query) == 0){
        $output .= "No users are available to chat";
    } elseif(mysqli_num_rows($query) > 0){
        
        while($row = mysqli_fetch_assoc($query)){
            // Call the shared function to generate the HTML for each user
            $output .= formatUserList($row, $outgoing_id, $conn);
        }
    }
    
    echo $output;
?>