<?php 
    session_start();
    if(isset($_SESSION['unique_id'])){
        include_once "config.php";
        
        $outgoing_id = mysqli_real_escape_string($conn, $_POST['outgoing_id']);
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $output = "";

        // Select all messages between the two users, ordered by ID (time)
        $sql = "SELECT * FROM messages 
                LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
                WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id})
                OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id}) ORDER BY msg_id";

        $query = mysqli_query($conn, $sql);

        if(mysqli_num_rows($query) > 0){
            while($row = mysqli_fetch_assoc($query)){
                // Check if the message is from the outgoing user (You)
                if($row['outgoing_msg_id'] === $outgoing_id){
                    // Outgoing message (Right side)
                    $output .= '<div class="message outgoing">
                                    <div class="message-content">
                                        <p>'. $row['msg'] .'</p>
                                    </div>
                                </div>';
                } else {
                    // Incoming message (Left side)
                    $output .= '<div class="message incoming">
                                    <img src="images/'. $row['img'] .'" alt="">
                                    <div class="message-content">
                                        <p>'. $row['msg'] .'</p>
                                    </div>
                                </div>';
                }
            }
        }
        echo $output;
    } else {
        echo "Authentication error.";
    }
?>