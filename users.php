<?php 
    session_start();
    
    // 1. Check Authentication: If user is not logged in, redirect.
    if(!isset($_SESSION['unique_id'])){
        header("location: login.php");
        exit(); 
    }
    
    include_once "header.php"; 
    include_once "php/config.php";
    
    // 2. FIX: Initialize $row to an empty array to prevent 'Undefined variable' errors
    // if the database query fails or finds no user for the session ID.
    $row = []; 
    
    // Fetch the current logged-in user's details
    $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
    
    if($sql && mysqli_num_rows($sql) > 0){
        $row = mysqli_fetch_assoc($sql);
    } else {
        // If the user isn't found in the DB (bad session ID), log out the user gracefully.
        header("location: php/logout.php?error=user_not_found"); 
        exit(); 
    }
?>
<body>
    <div class="wrapper">
        <section class="users">
            <header>
                <div class="content">
                    
                    <img src="php/images/<?php echo $row['img'] ?? 'default.jpg'; ?>" alt="Profile Picture">
                    
                    <div class="details">
                        <span><?php echo ($row['fname'] ?? 'Unknown') . " " . ($row['lname'] ?? 'User') ?></span>
                        <p><?php echo $row['status'] ?? 'Offline'; ?></p>
                    </div>
                </div>
                <a href="php/logout.php?logout_id=<?php echo $row['unique_id'] ?? ''; ?>" class="logout">Logout</a>
            </header>
            
            <div class="search">
                <span class="text">Select a user to start chat</span>
                <input type="text" placeholder="Enter name to search...">
                <button><i class="fas fa-search"></i></button>
            </div>
            
            <div class="users-list">
                </div>
        </section>
    </div>
    
    <script src="js/users.js"></script>

</body>
</html>