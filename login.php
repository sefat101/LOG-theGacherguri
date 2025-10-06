<?php include_once "header.php" ; ?>

<body>
    <div class="wrapper">
        <section class="form login">
            <header>LOG</header>
            <form action="#" method="post" enctype="multipart/form-data"
            autocomplete="off">
                   <div class="error-text"></div>
                   <div class="field-input">
                       <label>Email Address</label>
                       <input type="text" name="email" 
                        placeholder="Enter Your Email" required>
                   </div>

                   <div class="field-input">
                       <label>Password</label>
                       <input type="password" name="password"
                       placeholder="Enter Your Password" required>
                       <i class="fas fa-eye show-hide-icon"></i>
                   </div>

                    <div class="field-button">
                        <input type="submit" name="submit"
                        value="Continue to Chat">
                    </div>

            </form>

            <div class="link">Not Yet Signed Up?
                <a href="index.php">Signup now</a>
            </div>

        </section>
    </div>

    <script src="js/pass-show-hide.js"></script>
    <script src="js/login.js"></script>

</body>
</html>