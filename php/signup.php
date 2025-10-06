<?php
    // Optional: Use this only temporarily for debugging if you get a 500 status.
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    session_start();
    include_once "config.php";

    if (!$conn) {
        // Output database connection error if it fails
        die("Database connection failed: " . mysqli_connect_error()); 
    }

    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // 1. Basic Validation
    if(!empty($fname) && !empty($lname) && !empty($email) && !empty($password)){
        
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            
            // 2. Check if email already exists
            $sql = mysqli_query($conn, "SELECT email FROM users WHERE email = '{$email}'");
            if(mysqli_num_rows($sql) > 0){
                echo "$email - This email already exists!";
            }else{
                
                // 3. Handle image upload
                if(isset($_FILES['image'])){
                    $img_name = $_FILES['image']['name'];
                    $img_type = $_FILES['image']['type'];
                    $tmp_name = $_FILES['image']['tmp_name'];
                    
                    $img_explode = explode('.',$img_name);
                    $img_ext = end($img_explode); 
                    
                    $extensions = ["jpeg", "png", "jpg"];
                    
                    if(in_array($img_ext, $extensions) === true){
                        $time = time();
                        $new_img_name = $time.$img_name;
                        
                        // Check if file was moved successfully (permissions are key here)
                        if(move_uploaded_file($tmp_name,"images/".$new_img_name)){
                            
                            // 4. Insert user data
                            $status = "Offline now"; // User is offline until they log in
                            $unique_id = rand(time(), 100000000); 
                            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                            $insert_query = mysqli_query($conn, "INSERT INTO users (unique_id, fname, lname, email, password, img, status)
                                                        VALUES ({$unique_id}, '{$fname}','{$lname}', '{$email}', '{$hashed_password}', '{$new_img_name}', '{$status}')");

                            if($insert_query){
                                // SIGNAL SUCCESS TO JAVASCRIPT
                                echo "success_redirect_to_login"; 
                            }else{
                                echo "Something went wrong with the database insert: ".mysqli_error($conn); 
                            }
                        } else {
                            echo "Error uploading image. Check 'images' folder permissions.";
                        }
                    }else{
                        echo "Please upload an image file - jpeg, png, jpg!";
                    }
                }else{
                    echo "Please upload a profile image!";
                }
            }
        }else{
            echo "$email is not a valid email!";
        }
    }else{
        echo "All input fields are required!";
    }
// END PHP TAG: Ensure NO whitespace or characters follow this tag.
?>