<?php
    session_start();
    
    // Check if a session is active (user is logged in)
    if(isset($_SESSION['unique_id'])){ 
        include_once "config.php"; // Include database connection
        
        // 1. Get the unique_id from the URL parameter
        $logout_id = mysqli_real_escape_string($conn, $_GET['logout_id']);
        
        if(isset($logout_id)){
            $status = "Offline now";
            
            // 2. Update the user's status in the database
            $sql = mysqli_query($conn, "UPDATE users SET status = '{$status}' WHERE unique_id = {$logout_id}");
            
            if($sql){
                // 3. Destroy the session variables and session
                session_unset();
                session_destroy();
                
                // 4. Redirect the user to the login page (path is relative to the current file)
                header("location: ../login.php");
                exit();
            }
        }else{
            // If the ID wasn't passed, just redirect them back to the users page
            header("location: ../users.php");
            exit();
        }
    }else{  
        // If they weren't logged in, redirect them to the login page anyway
        header("location: ../login.php");
        exit();
    }
?>