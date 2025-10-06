<?php
// NOTE: This file ONLY contains the function. 
// It relies on the calling script (get-users.php or search.php) to call session_start() and include config.php.

function formatUserList($row, $outgoing_id, $conn){
    // The $outgoing_id and $conn variables are passed from the calling script.
    
    // 1. Get the latest message for the conversation
    $sql2 = "SELECT * FROM messages 
             WHERE (incoming_msg_id = {$row['unique_id']} OR outgoing_msg_id = {$row['unique_id']})
             AND (outgoing_msg_id = {$outgoing_id} OR incoming_msg_id = {$outgoing_id})
             ORDER BY msg_id DESC LIMIT 1";
    
    $query2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($query2);
    
    // Check if a message exists
    $msg = (mysqli_num_rows($query2) > 0) ? $row2['msg'] : "No message yet";
    
    // 2. Format the message for display
    (strlen($msg) > 28) ? $msg_trim = substr($msg, 0, 28) . '...' : $msg_trim = $msg;
    
    // Determine if the message was sent by the current user ("You: ")
    // Use null coalescing operator (??) to prevent error if $row2 is null
    ($outgoing_id == ($row2['outgoing_msg_id'] ?? null)) ? $you = "You: " : $you = "";
    
    // 3. Determine status class
    ($row['status'] == "Offline now") ? $offline = "offline" : $offline = "";
    
    // 4. Build and return the HTML output
    
    $output = '<a href="php/chat.php?user_id='.$row['unique_id'].'">
                <div class="content">
                    <img src="php/images/'. ($row['img'] ?? 'default.png') .'" alt="User Profile Image">
                    <div class="details">
                        <span>'. ($row['fname'] ?? 'Unknown') . " " . ($row['lname'] ?? 'User') .'</span>
                        <p>'. $you . $msg_trim .'</p>
                    </div>
                </div>
                <div class="status-dot '.$offline.'">
                    <i class="fas fa-circle"></i>
                </div>
            </a>';
            
    return $output;
}


