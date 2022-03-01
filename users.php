

<?php 
 // Header
 include 'templates/header.php';
?>




<?php  

    // (1) Start Session 
    session_start(); 
    
    // (2) PDO
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
                    echo '<br> <br><h1 style="color: white;">Scrum Team</h1>';
                    echo '<br><h3 style="color: white;">Hello '.$userData["name"]. ' </h3>';  
                }


?>
<?php

                // (5)        
                // Delete rows only User Can Delete its own row from daily_scrum_table //
                if ( isset($_POST['delete']) && isset($_POST['user_id']) && isset($_POST['theEmail']) ) {   
                    
                        if ( $_POST['theEmail'] == $_SESSION['email'] ) {

                            $selectUserSqlQuery = "DELETE FROM users WHERE (user_id = :zip AND email = :email)";
                            echo "<pre>\n$selectUserSqlQuery\n</pre>\n";
                            $stmtUser = $pdo->prepare($selectUserSqlQuery);
                            $stmtUser->execute(array(
                                ':zip' => $_POST['user_id'],
                                ':email' => $_SESSION['email']
                            
                            ));


                            header("location:logout.php");


                        }

                }

                // (6) 
                // Diplay Users In HTML Table//
                // Diplay Users In HTML Table//
                $stmtUser = $pdo->query("SELECT name, email, password, user_id FROM users");
                $rowsUser = $stmtUser->fetchAll(PDO::FETCH_ASSOC);
?>



<div class="container-the100">
		        <div  class="wrap-the100">




<div class="table-wrap">

        <table border="1" class="table table-striped">
        <thead>
                        <tr>
                            <th> User Name</th>
                            <th> Email </th>
                            <th> Password </th>
                            <th> </th>
                        </tr>
                    </thead> <tbody>
                    
        <?php 
        foreach ( $rowsUser as $row ) {
            echo "<tr><td>";
            echo($row['name']);
            echo("</td><td>");
            echo($row['email']);
            echo("</td><td class='hidetext'>");
            echo('****');
            echo("</td><td>");
            echo('<form method="post"><input type="hidden" ');
            echo('name="user_id" value="'.$row['user_id'].'">'."\n");
            echo('<input type="hidden"');
            echo('name="theEmail" value="'.$row['email'].'">'."\n");

            if ($_SESSION["email"] === $row["email"]) {
                echo('<input style="background: none; color: #a10000;     font-weight: bold;"type="submit" value="Remove" name="delete">');
            } else {
                echo('<input style="background: none; color: transparent;" type="submit" value="Remove" name="delete">');
            }
            


            echo("\n</form>\n");
            echo("</td></tr>\n");
        }
        ?>
</tbody>




</table>

</div>



    <p style="padding:0px; padding-bottom: 5px;" class="container-the100-form-btn">
            <span style="color: transparent;font-weight: bold;">-----</span>or
    </p>
    <div style="padding:0px;" class="container-the100-form-btn">
        
        <a href="daily-scrum.php">Daily Notes</a>
        <p style="color: transparent;">----<span style="color: #d4d4e2;font-weight: bold;">|</span>----</p>
        <a href="logout.php">Log Out</a>


    </div>




</div>


</div>








<?php
        }
    }  
    else  {  
        header("location:login.php");  
    }  
 ?>  




<?php
include "templates/footer.php"; // Footer
?>