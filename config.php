<?php
   define('DB_SERVER', 'localhost:3306');
   define('DB_USERNAME', 'payfriends');
   define('DB_PASSWORD', 'password');
   define('DB_DATABASE', 'payfriends');
   $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);


   if($db === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>