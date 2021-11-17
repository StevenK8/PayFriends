<?php
// Initialize the session
session_start();
 
// Si l'utilsateur est déjà connecté il est redirigé vers la page d'accueil
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = $recaptcha_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    # Verify captcha
    $post_data = http_build_query(
      array(
          'secret' => "",
          'response' => $_POST['g-recaptcha-response'],
          'remoteip' => $_SERVER['REMOTE_ADDR']
      )
    );
    $opts = array('http' =>
      array(
          'method'  => 'POST',
          'header'  => 'Content-type: application/x-www-form-urlencoded',
          'content' => $post_data
      )
    );
    $context  = stream_context_create($opts);
    $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
    $result = json_decode($response);
    if (!$result->success) {
      $recaptcha_err = "Veuillez valider le recaptcha.";
    }
 
    // Validate username
    if(empty(trim($_POST["username"]))){
      $username_err = "Veuillez rentrer un nom d'utilisateur.";
    }else if(preg_match('/[^a-z_]/i', trim($_POST["username"]))){
      $username_err = "Votre nom d'utilisateur ne peut contenir que des lettres ou '_'.";
    }else if(strlen(trim($_POST["username"])) > 20){
      $username_err = "Votre nom d'utilisateur ne doit pas dépasser 20 caractères";
    }else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = preg_replace('/\s+/', '', htmlspecialchars(trim($_POST["username"])));
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Ce nom d'utilisateur est déjà utilisé.";
                } else{
                    $username = preg_replace('/\s+/', '', htmlspecialchars(trim($_POST["username"])));
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Veuillez entrer un mot de passe.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Le mot de passe doit être d'au moins 6 caractères.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Veuillez confirmer le mot de passe.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Les mots de passe ne correspondent pas.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($recaptcha_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                //Sauvegarde un avatar généré à partir de l'username grâce à une API
                if (!copy("https://api.adorable.io/avatars/285/".$username.".png", "assets/images/faces/".$username.".png")) {
                  echo "Erreur de réception d'avatar\n";
                }
                // file_put_contents("assets/images/faces", file_get_contents("https://api.adorable.io/avatars/285/".$username.".png"));
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Une erreur s'est produite. Veuillez rééssayer plus tard.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($db);
}
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PayFriends</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- End layout styles -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/icons/favicon-16x16.png">
    <link rel="manifest" href="assets/images/icons/site.webmanifest">
    <link rel="mask-icon" href="assets/images/icons/safari-pinned-tab.svg" color="#9a55ff">
    <link rel="shortcut icon" href="assets/images/icons/favicon.ico">
    <meta name="msapplication-TileColor" content="#9f00a7">
    <meta name="msapplication-config" content="assets/images/icons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left p-5">
                <a class="navbar-brand brand-logo" id="websiteName" href="index.php">PayFriends</a>
                <h4>Nouveau ici?</h4>
                <h6 class="font-weight-light">Seulement quelques étapes pour créer un compte.</h6>
                <form class="pt-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                  <div class="form-group <?php echo (!empty($username_err)) ? 'is-invalid help-block' : ''; ?>">
                    <input type="text" class="form-control form-control-lg" id="username" name="username" placeholder="Nom d'utilisateur" required>
                    <span class=" text-danger"><?php echo $username_err; ?></span>
                  </div>
                  <!-- <div class="form-group">
                    <input type="email" class="form-control form-control-lg" id="email" placeholder="Email" required>
                  </div> -->
                  <div class="form-group <?php echo (!empty($password_err)) ? 'is-invalid help-block' : ''; ?>">
                    <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Mot de passe" required>
                    <span class=" text-danger"><?php echo $password_err; ?></span>
                  </div>
                  <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'is-invalid help-block' : ''; ?>">
                    <input type="password" class="form-control form-control-lg" id="passwordConfirm" name="confirm_password" placeholder="Confirmer le mot de passe" required>
                    <span class=" text-danger"><?php echo $confirm_password_err; ?></span>
                  </div>
                  <div class="form-group">
                    <div class="g-recaptcha form-group" data-sitekey=""></div>
                    <span class="text-danger"><?php echo $recaptcha_err; ?></span>
                  </div>

                  <div class="mt-3">
                    <button class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" type="submit">CRÉER UN COMPTE</button>
                  </div>
                  <div class="text-center mt-4 font-weight-light"> Vous avez déjà un compte? <a href="login.php" class="text-primary">Se connecter</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <!-- endinject -->
  </body>
</html>