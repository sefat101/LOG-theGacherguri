<?php 
    session_start();
    // Use the config file to connect to the database
    if(isset($_SESSION['unique_id'])){
        include_once "config.php";

        // Collect incoming form data
        $outgoing_id = mysqli_real_escape_string($conn, $_POST['outgoing_id']);
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $message = mysqli_real_escape_string($conn, $_POST['message']);

        // Check if the message is not empty before inserting
        if(!empty($message)){
            // Insert the message into the messages table
            $sql = mysqli_query($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg)
                                        VALUES ({$incoming_id}, {$outgoing_id}, '{$message}')") or die();
        }
    } else {
        header("location: ../login.php");
    }
?>