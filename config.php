<?php
   define('DB_SERVER', 'xx:3306');
   define('DB_USERNAME', 'user');
   define('DB_PASSWORD', 'password;');
   define('DB_DATABASE', 'payfriends');
   $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);


   if($db === false){
    die("ERROR: Connexion à la DB impossible. " . mysqli_connect_error());
}
?>
