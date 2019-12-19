<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    exit;
}

// Include config file
require_once "config.php";

function getId($db){
    if(isset($_GET["username"]) && isset($_GET["ide"])){
        $sql = "SELECT id FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($db, $sql)){
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($stmt, "s", $_GET["username"]);
          
          // Attempt to execute the prepared statement
          if(mysqli_stmt_execute($stmt)){
              // Store result
              mysqli_stmt_store_result($stmt);

              if(mysqli_stmt_num_rows($stmt) == 1){                  
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $idu);
                if(mysqli_stmt_fetch($stmt)){
                    adduser($db,$idu);
                }
            }
          }
              // Close statement
            mysqli_stmt_close($stmt);
        }
    
    }
}

function addUser($db, $idu){
    $ide = intval($_GET['ide']);
 
    $sql = "INSERT INTO `members` (`ide`, `idu`) VALUES ( ? ,  ? )";

    if($stmt = mysqli_prepare($db, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ii", $ide, $idu);

        // Attempt to execute the prepared statement
        if(!mysqli_stmt_execute($stmt)){
            echo "Error: " . $sql . "<br>" . mysqli_error($db);
        }
  }
  mysqli_stmt_close($stmt);
}

// Si la page est appelée, on vérifie si l'user appartient à l'événement
if(isset($_GET["ide"])){
    $sql = "SELECT ide,idu FROM members WHERE ide = ? AND idu = ?";
  
    if($stmt = mysqli_prepare($db, $sql)){
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "ii", intval($_GET["ide"]), $_SESSION["id"]);
  
      // Attempt to execute the prepared statement
      if(mysqli_stmt_execute($stmt)){
          // Store result
          mysqli_stmt_store_result($stmt);
  
          // Check si l'user est membre de l'evenement
          if(mysqli_stmt_num_rows($stmt) == 1){
            getId($db);
          }
      }
    }
    // Close statement
    mysqli_stmt_close($stmt);
}

mysqli_close($db);

?>