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

if(isset($_GET["id"])){
    $sql = "SELECT ide,idu FROM members WHERE ide = ? AND idu = ?";

    if($stmt = mysqli_prepare($db, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ii", intval($_GET["id"]), $_SESSION["id"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check si l'user est membre de l'evenement
            if(mysqli_stmt_num_rows($stmt) != 1){
            $_GET["id"] = "";
            mysqli_stmt_close($stmt);
            header("location: index.php");
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);
    }
}else {
    header("location: index.php");
}

$sql = "SELECT username,DATE_FORMAT(d.date, '%b', 'fr-FR') as date,SUM(d.prix) as prix FROM users u, depenses d WHERE u.id like d.idu and d.ide like ? GROUP BY username,DATE_FORMAT(`date`, '%M') ORDER BY date asc";
                          
if($stmt = mysqli_prepare($db, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $_GET["id"]);

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