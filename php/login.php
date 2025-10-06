<?php
    // Include the database configuration file
    session_start();
    include_once "config.php";

    // Debugging (Keep commented unless you encounter a 500 error again)
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error()); 
    }

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // 1. Basic Validation Check
    if(!empty($email) && !empty($password)){
        
        // 2. Check if the email exists in the database
        $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
        
        if(mysqli_num_rows($sql) > 0){
            $result = mysqli_fetch_assoc($sql);
            $hashed_password = $result['password'];
            
            // 3. Verify the hashed password
            if(password_verify($password, $hashed_password)){
                
                // 4. Login Successful: Update Status and Create Session
                $status = "Online now";
                $update_query = mysqli_query($conn, "UPDATE users SET status = '{$status}' WHERE unique_id = {$result['unique_id']}");
                
                if($update_query){
                    $_SESSION['unique_id'] = $result['unique_id'];
                    echo "success"; // Signal to JavaScript to redirect to users.php
                } else {
                    echo "Failed to update user status.";
                }

            }else{
                echo "Email or Password incorrect!";
            }
        }else{
            echo "$email - This email does not exist!";
        }
    }else{
        echo "All input fields are required!";
    }
?>