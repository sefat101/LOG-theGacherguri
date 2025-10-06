<?php

    $hostname = "localhost";
    $username = "root";
    $password = ""; // *** Important: Put your MySQL root password here if you have one! ***
    $dbname = "phpRTCapp";
    
    // Attempt to establish the connection and check for errors immediately
    $conn = mysqli_connect($hostname, $username, $password, $dbname);

    if(!$conn){
        // Using die() stops the script and outputs the error, useful for debugging
        die("Database connection failed: " . mysqli_connect_error());
    }

?>