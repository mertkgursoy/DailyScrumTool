
<?php 
 // Header
 include 'templates/header.php';
?>

<?php  
 // (1) Start Session     
 session_start();  
 // (2)
 // Add PDO Config
 require_once "pdo.php";



    if(isset($_SESSION["email"]))  
    {  

        // (3)
        // Check if session expired redirect it to login page
        $now = time(); // Checking the time now when home page starts.
        if ($now > $_SESSION['expire']) {
            session_destroy();
            echo "Your session has expired! <a href='http://localhost/somefolder/login.php'>Login here</a>";
            header("location:login.php");  
        }
        else {

            // (4) 
            // Retrieve User Name By Session Email      
            $theUserSessionEmail = $_SESSION['email'];
            $SqlQueryToRetrieveUserData = $pdo->prepare('SELECT * FROM users WHERE email = ?');
            $SqlQueryToRetrieveUserData->execute(array($theUserSessionEmail));

            $userData = $SqlQueryToRetrieveUserData->fetch(PDO::FETCH_ASSOC);
            if(!empty($userData)) {
                 
            
                // (5)
                // Add DailyNotes Into daily_scrum_table //     
                if ( !empty($_POST['theWhatDidYouDoYesterday']) &&  isset($_POST['theWhatDidYouDoYesterday']) && !empty($_POST['theWhatWillYouDoToday'])  &&  isset($_POST['theWhatWillYouDoToday']) && !empty($_POST['theIsThereAnyImpediment']) && isset($_POST['theIsThereAnyImpediment']) && !empty($_SESSION['email']) && isset($_SESSION['email'])  )  {
                        $selectDailyNoteSqlQuery = "INSERT INTO daily_scrum_table (theName, theEmail, theDate, theWhatDidYouDoYesterday, theWhatWillYouDoToday, theIsThereAnyImpediment)      
                                VALUES (:theName, :theEmail, :theDate, :theWhatDidYouDoYesterday, :theWhatWillYouDoToday, :theIsThereAnyImpediment)";          
                        //echo("<pre>\n".$selectDailyNoteSqlQuery."\n</pre>\n");
                        $stmtDailyNotes = $pdo->prepare($selectDailyNoteSqlQuery);     
                        $stmtDailyNotes->execute(array(
                            ':theName' => $userData['name'],
                            ':theEmail' => $_SESSION['email'],
                            ':theDate' => date("d-m-Y"), /* $_POST['theDate'], */
                            ':theWhatDidYouDoYesterday' => $_POST['theWhatDidYouDoYesterday'],
                            ':theWhatWillYouDoToday' => $_POST['theWhatWillYouDoToday'],
                            ':theIsThereAnyImpediment' => $_POST['theIsThereAnyImpediment']
                        ));
                        header("Location: daily-scrum.php");     
                    }
    
            }    
        }
    }  
    else  {  
    header("location:login.php");  
    } 
  
?>


<div class="container-the100">
		        <div class="wrap-the100">





                    <form method="post" class="the100-form validate-form">
                        


                    
                        <span class="the100-form-title">
                            
                            Create A Daily Note

                            <?php
                                echo '<h3 style="color: #403866;">Hello '.$userData["name"].'</h3>'; 
                            ?>
                        </span>
                        
                        
                        <!--<p>Name:<input type="text" name="theName" size="40"></p>-->
                        <!--<p>Date:<input type="text" name="theDate"></p>-->
                        <div class="wrap-input100 validate-input">
                            
                            <input class="input100" placeholder="Yesterday: What did you do yesterday?" required data-validate = "This field is required" type="text" name="theWhatDidYouDoYesterday">
                            <span class="focus-input100"></span>
                        </div>
                        <div class="wrap-input100 validate-input">
                            
                            <input class="input100" placeholder="Today: What will you do today?" required data-validate = "This field is required" type="text" name="theWhatWillYouDoToday">
                            <span class="focus-input100"></span>
                        </div>
                        <div class="wrap-input100 validate-input">
                            
                            <input class="input100" placeholder="Impediment: Is there any impediment?" required data-validate = "This field is required" type="text" name="theIsThereAnyImpediment">
                            <span class="focus-input100"></span>
                        </div>
                        <div style="padding-top: 25px; padding-bottom:15px" class="container-the100-form-btn">
                            <button type="submit" value="Add New" class="the100-form-btn">Create</button>
                            <span>
						    </span>
                        </div>                
                    
                    
                    </form>
                

                    <p style="padding:0px; padding-bottom: 5px;" class="container-the100-form-btn">
            or
        </p>
        <div style="padding:0px;" class="container-the100-form-btn">

             <a href="daily-scrum.php">Daily Notes</a>
       
             <p style="color: transparent;">--<span style="color: #d4d4e2;font-weight: bold;">|</span>--</p>

             <a href="users.php">Scrum Team</a>
       
        </div>
                
                
                </div></div>


        
  


   


<?php
include "templates/footer.php";
?>