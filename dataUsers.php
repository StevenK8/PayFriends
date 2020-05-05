<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
  if(isset($_GET["redirect"])){
    header("location: login.php?redirect=".$_GET["redirect"]);
    exit;
  }else{
    header("location: login.php");
    exit;
  }
}

header('Content-Type: application/json');
// Include config file
require_once "config.php";

$sql = "SELECT username from users";
                          
if($stmt = mysqli_prepare($db, $sql)){

    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
    // Store result
        $result = mysqli_stmt_get_result($stmt);
        // mysqli_stmt_bind_result($stmt, $nom, $payeur, $date, $prix);

        $data = array();
        foreach ($result as $row) {
          $data[] = $row;
        }
        $result->close();
    } else{
    echo mysqli_error($db);
    }
// Close statement
mysqli_stmt_close($stmt);
}
//close connection
mysqli_close($db);

//now print the data
print json_encode($data);
?>