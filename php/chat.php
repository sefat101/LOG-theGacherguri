<?php 
  session_start();
  include_once "config.php";
  
  if(!isset($_SESSION['unique_id'])){
    // Path correction: Go up one level to the root login.php
    header("location: ../login.php");
    exit(); 
  }

  $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
  
  $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$user_id}");
  if(mysqli_num_rows($sql) > 0){
    $row = mysqli_fetch_assoc($sql);
  } else {
    // Path correction: Go up one level to the root users.php
    header("location: ../users.php");
    exit();
  }

  $pageTitle = "Chatting with " . $row['fname']; 
  // REMOVED: include_once "../header.php"; since all styling is here.
?>
    
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $pageTitle; ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
   
    :root {
      --primary-color: #0088cc;
      --primary-light: #40a8e4;
      --bg-color: #f0f2f5;
      --chat-bg: #ffffff;
      --header-bg: #f0f2f5;
      --border-color: #e6e6e6;
      --text-primary: #333333;
      --text-secondary: #707579;
      --outgoing-bg: #e3f2fd;
      --incoming-bg: #ffffff;
      --shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background-color: var(--bg-color);
      height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .chat-area {
      flex: 1;
      display: flex;
      flex-direction: column;
      max-width: 900px;
      margin: 0 auto;
      width: 100%;
      height: 100vh;
      background-color: var(--chat-bg);
      box-shadow: var(--shadow);
    }

    /* Header styling */
    .chat-area header {
      display: flex;
      align-items: center;
      padding: 12px 16px;
      background-color: var(--header-bg);
      border-bottom: 1px solid var(--border-color);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .back-icon {
      color: var(--primary-color);
      font-size: 20px;
      margin-right: 15px;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      transition: background-color 0.2s;
    }

    .back-icon:hover {
      background-color: rgba(0, 136, 204, 0.1);
    }

    .chat-area header img {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 15px;
      border: 2px solid var(--primary-light);
    }

    .details {
      flex: 1;
    }

    .details span {
      font-size: 17px;
      font-weight: 600;
      color: var(--text-primary);
    }

    .details p {
      font-size: 13px;
      color: var(--text-secondary);
      margin-top: 2px;
    }

    /* Chat box styling */
    .chat-box {
      flex: 1;
      overflow-y: auto;
      padding: 20px;
      background-color: #f2fafaff;
      background-size: 100px;
      background-repeat: repeat;
    }

    .message {
      display: flex;
      margin-bottom: 20px;
    }
    
    /* FIX: Ensures the image in the incoming message is small and round */
    .message.incoming img {
        height: 35px; 
        width: 35px;  
        border-radius: 50%;
        object-fit: cover;
        margin-right: 10px; 
    }

    .outgoing {
      justify-content: flex-end;
    }

    .incoming {
      justify-content: flex-start;
      /* Ensure image and bubble align correctly at the top */
      align-items: flex-start; 
    }

    .message-content {
      max-width: 70%;
      padding: 12px 16px;
      border-radius: 18px;
      position: relative;
      box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    }

    .outgoing .message-content {
      background-color: var(--outgoing-bg);
      border-bottom-right-radius: 5px;
      color: var(--text-primary);
    }

    .incoming .message-content {
      background-color: var(--incoming-bg);
      border-bottom-left-radius: 5px;
      color: var(--text-primary);
      border: 1px solid var(--border-color);
    }

    .message-time {
      font-size: 11px;
      color: var(--text-secondary);
      text-align: right;
      margin-top: 5px;
    }

    /* Typing area styling */
    .typing-area {
      display: flex;
      padding: 12px 16px;
      background-color: var(--header-bg);
      border-top: 1px solid var(--border-color);
      align-items: center;
    }
    /* ... (rest of typing-area styles) ... */

    .input-field {
      flex: 1;
      height: 45px;
      border: none;
      outline: none;
      border-radius: 24px;
      padding: 0 18px;
      font-size: 15px;
      background-color: white;
      border: 1px solid var(--border-color);
      transition: border-color 0.2s;
    }

    .input-field:focus {
      border-color: var(--primary-light);
    }

    .typing-area button {
      width: 45px;
      height: 45px;
      border: none;
      outline: none;
      border-radius: 50%;
      background-color: var(--primary-color);
      color: white;
      font-size: 18px;
      margin-left: 10px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background-color 0.2s;
    }

    .typing-area button:hover {
      background-color: var(--primary-light);
    }

    /* Scrollbar styling */
    .chat-box::-webkit-scrollbar {
      width: 6px;
    }

    .chat-box::-webkit-scrollbar-track {
      background: transparent;
    }

    .chat-box::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 3px;
    }

    .chat-box::-webkit-scrollbar-thumb:hover {
      background: #a8a8a8;
    }

    /* Responsive design */
    @media (max-width: 768px) {
      .chat-area {
        height: 100vh;
      }
      
      .message-content {
        max-width: 85%;
      }
    }
  </style>
</head>
<body>
  <div class="chat-area">
    <header>
      <a href="../users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
      
      <img src="images/<?php echo $row['img']; ?>" alt="Profile Picture"> 
      
      <div class="details">
        <span><?php echo $row['fname'] . " " . $row['lname'] ?></span>
        <p><?php echo $row['status']; ?></p>
      </div>
    </header>
    
    <div class="chat-box">
      </div>
    
    <form action="#" class="typing-area" autocomplete="off">
      <input type="text" class="outgoing_id" name="outgoing_id" value="<?php echo $_SESSION['unique_id']; ?>" hidden>
      <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $user_id; ?>" hidden>
      
      <input type="text" name="message" class="input-field" placeholder="Type a message here..." required>
      <button><i class="fas fa-paper-plane"></i></button> </form>
  </div>

  <script src="../js/chat.js"></script>
</body>
</html>