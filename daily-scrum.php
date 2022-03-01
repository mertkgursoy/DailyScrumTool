
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
  



    if(isset($_SESSION["email"]))  {  

        // (3)
        // Check if session expired redirect it to login page
        $now = time(); 
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
                    echo '<br> <br> <h1 style="color: white;">Daily Scrum Standup Notes</h1>';
                    echo '<br><h3 style="margin-top: 0px !important; color: white;">Hello '.$userData["name"]. '</h3>';  
            }
    
            // (5)     
            // Delete USER can only delete its own rows AND if it's added today from daily_scrum_table //
            if ( isset($_POST['delete']) && isset($_POST['auto_id']) && isset($_POST["theEmail"]) ) {
                $selectDailyNoteSqlQuery = "DELETE FROM daily_scrum_table WHERE (auto_id = :zip AND theEmail = :theEmail AND theDate = :theDate)" ;
                // echo "<pre>\n$selectDailyNoteSqlQuery\n</pre>\n";
                $stmtDailyNotes = $pdo->prepare($selectDailyNoteSqlQuery);
                $stmtDailyNotes->execute(array(
                    ':zip' => $_POST['auto_id'],
                    ":theEmail" => $_SESSION["email"],
                    ":theDate" => date("d-m-Y")
                ));
            }
            // (6) 
            // Diplay Daily Notes In HTML Table               
            $stmtDailyNotes = $pdo->query("SELECT theName, theDate, theEmail, theWhatDidYouDoYesterday, theWhatWillYouDoToday, theIsThereAnyImpediment, auto_id FROM daily_scrum_table");
            $rows = $stmtDailyNotes->fetchAll(PDO::FETCH_ASSOC);

    
?>

    





<div class="container-the100">
		        <div  class="wrap-the100">


<div class="table-wrap">


                    <table border="1" class="table table-striped">

                    <thead>
                        <tr>
                            <th> User Name</th>
                            <th> Date </th>
                            <th> Yesterday </th>
                            <th> Today </th>
                            <th> Impediment </th>
                            <th> </th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        foreach ( $rows as $row ) {
                            echo "<tr><td>";
                            echo($row['theName']);
                            echo("</td><td>");     
                            echo($row['theDate']);
                            echo("</td><td>");
                            echo($row['theWhatDidYouDoYesterday']);
                            echo("</td><td>");
                            echo($row['theWhatWillYouDoToday']);
                            echo("</td><td>");
                            echo($row['theIsThereAnyImpediment']);
                            echo("</td><td>");
                            echo('<form method="post"><input type="hidden" ');
                            echo('name="theEmail" value="'.$row['theEmail'].'">'."\n");
                            echo('<input type="hidden" ');
                            echo('name="auto_id" value="'.$row['auto_id'].'">'."\n");


                            if ($_SESSION["email"] === $row["theEmail"] && $row["theDate"] ===  date("d-m-Y")) {
                                echo('<input style="background: none; color: #a10000;     font-weight: bold;" type="submit" value="Remove" name="delete">');
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
                        <span style="color: transparent;font-weight: bold;"></span>or
                    </p>
                    <div style="padding:0px;" class="container-the100-form-btn">
                        
                        <a href="daily-scrum-form.php">Create Note</a>
                        <p style="color: transparent;">----<span style="color: #d4d4e2;font-weight: bold;">|</span>----</p>
                        <a href="users.php">Scrum Team</a>


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