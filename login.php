
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

    // p' OR '1' = '1

    // (3) 
    // Check Submit Button Clicked & Inputs Filled Correctly // 
    if ( !empty($_POST['email']) &&  isset($_POST['email']) && isset($_POST['password']) && !empty($_POST['password']) ) {

        // (4) 
        // Check User Exist or Not In Users Table - Try To Select User From Users Table // 
        $selectUserSqlQuery = "SELECT name FROM users 
            WHERE email = :em AND password = :pw";

        // echo "<p>$selectUserSqlQuery</p>\n";

        
        $stmt = $pdo->prepare($selectUserSqlQuery);

        $password = $_POST["password"];
        $hash = md5($password);
        $stmt->execute(array(
            ':em' => $_POST['email'], 
            ':pw' => $hash));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($row);

        // (5) 
        // Check If User Exists Log In and Redirect To The Success Page or Display Error Message //
        if ( $row === FALSE ) {
            echo "<h1 style='color: #e4ff00;'>Login incorrect.</h1>\n";
            $password = $_POST["password"];
            $hash = md5($password);
            // echo $hash;
        } else { 
            echo "<p>Login success.</p>\n";
            
            // (6)
            // Set The User Email In Session And Go To DailyScrum Page // 
            $_SESSION["email"] = $_POST["email"]; 
            
            // (7) 
            // Retrieve User Name By Session Email     
            $theUserSessionEmail = $_POST["email"];
            $SqlQueryToRetrieveUserData = $pdo->prepare('SELECT * FROM users WHERE email = ?');
            $SqlQueryToRetrieveUserData->execute(array($theUserSessionEmail));
            $userData = $SqlQueryToRetrieveUserData->fetch(PDO::FETCH_ASSOC);

            // (8) 
            // Add this user if we do not have any issue above. "empty error" means we passed the error conditions above. //
            if(!empty($userData)) {

                // (9)
                // Set user name in Session
                $_SESSION["name"] = $userData["name"];  

                // (10)
                // Taking now logged in time.
                $_SESSION['start'] = time(); 

                // (11) 
                // Ending a session in 30 minutes from the starting time.
                $_SESSION['expire'] = $_SESSION['start'] + (30 * 60);
            }
            header("Location: daily-scrum-form.php");
        }
    }



    // Register Form //
    if ( isset($_POST['register']) ) {
        header("Location: register.php");
    }


?>

<div class="container-the100">
		        <div class="wrap-the100">

                    <form method="post" class="the100-form validate-form">


                    <span class="the100-form-title">
                            
                            Daily Scrum Tool

                        </span>

            

                        <div class="wrap-input100 validate-input">
                            <input style="height: 60px;" class="input100" placeholder="Email"  required data-validate = "This field is required" type="email" name="email">
                            <span class="focus-input100"></span>
                        </div>


                        <div class="wrap-input100 validate-input">
                            <input style="height: 60px;" class="input100" placeholder="Password"  required  data-validate = "This field is required" type="password" name="password">
                            <span class="focus-input100"></span>
                        </div>

                        <div style="padding-top: 25px; padding-bottom:15px" class="container-the100-form-btn">
                            <button style="margin-right: 15px; margin-left:15px;"    type="submit" value="Login" class="the100-form-btn">Log In</button>
                            <span>
						    </span>
                            <!--
                            button style="margin-right: 15px; margin-left:15px;" class="the100-form-btn">
                                <a href="<?php echo($_SERVER['PHP_SELF']);?>">Refresh</a>
                            </button>
                            -->
                        </div> 

                            

</form>


<p style="padding:0px; padding-bottom: 5px;" class="container-the100-form-btn">
            or
        </p>
        <div style="padding:0px;" class="container-the100-form-btn">
            <a href="register.php">Create A New User</a>
        </div>



</div></div>



<?php
    include "templates/footer.php"; // Footer
?>