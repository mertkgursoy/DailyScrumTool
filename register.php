

<?php 
 // Header
 include 'templates/header.php';
?>


<?php  
    // (1) 
    // Start Session 
    session_start();  

    // (2)
    // Add PDO Config 
    require_once "pdo.php";

    // (3)
    // Add User Into Users Table 
    if ( isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
       
        // (4)
        // Set Email From Post Email
        $email = $_POST['email'];

        // (5) 
        // Check Email is really Email 
        if (!filter_var ($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format";
            echo "<span>  Invalid Email Address! </span>";
        }

        // (6) 
        // Check If Users Exists and Registered Before //
        $query = $pdo->prepare("  SELECT * FROM users WHERE email = ? ");
        $query->execute([$email]);
        $result = $query->rowCount();
        if ($result > 0 ) {
            $error = "<span> Email Already Exists! Please try again. </span>";
            echo "<span>  Email Already Exists! Please try again.  </span>";
        }
        
        // (7) 
        // Add this user if we do not have any issue above. "empty error" means we passed the error conditions above. //
        if (empty($error)) {

            // (8) 
            // Add User in Database Users Table
            $password = $_POST["password"];
            $hash = md5($password);
            $insertUserSqlQuery = "INSERT INTO users (name, email, password) 
            VALUES (:name, :email, :password)";
            echo("<pre>\n".$insertUserSqlQuery."\n</pre>\n");
            $stmtTheNewUser = $pdo->prepare($insertUserSqlQuery);
            $stmtTheNewUser->execute(array(
                ':name' => $_POST['name'],
                ':email' => $_POST['email'],
                ':password' => $hash)
            );

            // (9)
            // Set the user email in session and go to tool page // 
            $_SESSION["email"] = $_POST["email"]; 
            
            // (10)
            // Taking now logged in time.
            $_SESSION['start'] = time(); 

            // (11) 
            // Ending a session in 30 minutes from the starting time.
            $_SESSION['expire'] = $_SESSION['start'] + (30 * 60);
            
            // (12)
            // Redirect url from register page to tool page.
            header("Location: daily-scrum-form.php");
        }
    }

    // Register Form //
    if ( isset($_POST['loginForm']) ) {
        header("Location: login.php");
    }
?>

    
<div class="container-the100">
		        <div class="wrap-the100">

                    <form method="post" class="the100-form validate-form">






                                <span class="the100-form-title">
                                    
                                    Create User

                                </span>

                    

                                <div class="wrap-input100 validate-input">
                                    <input style="height: 60px;" class="input100" placeholder="Name" required data-validate = "This field is required" type="text" name="name">
                                    <span class="focus-input100"></span>
                                </div>

                                <div class="wrap-input100 validate-input">
                                    <input  style="height: 60px;" class="input100" placeholder="Email" required data-validate = "This field is required" type="email" name="email">
                                    <span class="focus-input100"></span>
                                </div>


                                <div class="wrap-input100 validate-input">
                                    <input style="height: 60px;" class="input100" placeholder="Password"  required data-validate = "This field is required" type="password" name="password">
                                    <span class="focus-input100"></span>
                                </div>

                                <div style="padding-top: 25px; padding-bottom:0px" class="container-the100-form-btn">
                                <button style="margin-right: 15px; margin-left:15px;" type="submit" value="Add New" class="the100-form-btn">Create</button>

                                    <span>
                                    </span>
                                </div> 





    </form>    <br/>
        <p style="padding:0px; padding-bottom: 5px;" class="container-the100-form-btn">
            or
        </p>
        <div style="padding:0px;" class="container-the100-form-btn">
            <a href="login.php">Log In</a>
        </div>

    </div></div>






    <?php
include "templates/footer.php"; // Footer
?>