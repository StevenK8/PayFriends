<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

// Include config file
require_once "../../config.php";

// Define variables and initialize with empty values
$title = $description = "";
$title_err = $description_err = $event_success = $event_error = "";


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

  // Validate title
  if(empty(trim($_POST["title"]))){
    $title_err = "Veuillez renseigner un titre.";
  }elseif(strlen(trim($_POST["title"])) > 30){
    $title_err = "Le nom de l'événement ne doit pas dépasser 30 caractères.";
  } else{
    $title = htmlspecialchars(trim($_POST["title"]));
  }

  // Validate description
  if(empty(trim($_POST["description"]))){
      $description_err = "Veuillez renseigner une description.";
  } elseif(strlen(trim($_POST["description"])) > 100){
    $description_err = "La description ne doit pas dépasser 100 caractères.";
  }else{
      $description = htmlspecialchars(trim($_POST["description"]));
  }

  function randomURL($URLlength = 12) {
    $charray = array_merge(range('a','z'), range('0','9'));
    $max = count($charray) - 1;
    $url = "";
    for ($i = 0; $i < $URLlength; $i++) {
        $randomChar = mt_rand(0, $max);
        $url .= $charray[$randomChar];
    }
    return $url;
  }


  function checkToken($tokenInput, $db){
    // Prepare a select statement
    $sql = "SELECT id FROM events WHERE token = ?";

    if($stmt = mysqli_prepare($db, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $token);

        // Set parameters
        $token = $tokenInput;

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            /* store result */
            mysqli_stmt_store_result($stmt);

            if(mysqli_stmt_num_rows($stmt) == 1){
              return false;
            }
            else{
              return true;
            }
        } else{
            echo "Erreur token";
            return false;
        }
    }
    return false;
        // Close statement
    mysqli_stmt_close($stmt);

  }

  $token = randomURL();
  while(!checkToken($token, $db)){
    $nbcheck++;
    if($nbcheck > 10){
      break;
    }
  }

  function addMember($db, $ide, $idu){
    $sql = "INSERT INTO members (ide, idu) VALUES (?, ?)";
    if($stmt = mysqli_prepare($db, $sql)){
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "ii", $ide, $idu);

      // Attempt to execute the prepared statement
      if(!mysqli_stmt_execute($stmt)){
          echo "Erreur d'ajout member";
      }
    }
    // Close statement
    mysqli_stmt_close($stmt);
  }


  // Check input errors before inserting in database
  if(empty($title_err) && empty($description_err)){

      // Prepare an insert statement
      $sql = "INSERT INTO events (title, description, token) VALUES (?, ?, ?)";

      if($stmt = mysqli_prepare($db, $sql)){
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($stmt, "sss", $title, $description, $token);

          // Attempt to execute the prepared statement
          if(!mysqli_stmt_execute($stmt)){
            $event_error = "L'événement ".$title." n'a pas pu être ajouté";
          }else{
            $event_success = "L'événement ".$title." a été créé avec succès!";
            $ide = getId($db, $token);
             //add event creator to event
            addMember($db, $ide, $_SESSION["id"]);
            mysqli_stmt_close($stmt);

            header("location: ../../index.php?id=$ide");
            exit;
          }

      }
      // Close statement
      mysqli_stmt_close($stmt);
     
  }

}

function getId($db, $token){
  //Get newly added event ID
  $sql2 = "SELECT id FROM events WHERE token = ? limit 1";

  if($stmt = mysqli_prepare($db, $sql2)){
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "s", $token);

      // Attempt to execute the prepared statement
      if(mysqli_stmt_execute($stmt)){
          mysqli_stmt_bind_result($stmt, $ide);
          mysqli_stmt_fetch($stmt);
      } else{
          echo "Erreur récupération id from token";
      }
  }
  // Close statement
  mysqli_stmt_close($stmt);
  return $ide;
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Payfriends</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="../../assets/css/style.css">
    <!-- End layout styles -->
    <link rel="apple-touch-icon" sizes="180x180" href="../../assets/images/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../assets/images/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/images/icons/favicon-16x16.png">
    <link rel="manifest" href="../../assets/images/icons/site.webmanifest">
    <link rel="mask-icon" href="../../assets/images/icons/safari-pinned-tab.svg" color="#9a55ff">
    <link rel="shortcut icon" href="../../assets/images/icons/favicon.ico">
    <meta name="msapplication-TileColor" content="#9f00a7">
    <meta name="msapplication-config" content="../../assets/images/icons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:../../partials/_navbar.html -->
      <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
          <a class="navbar-brand brand-logo" id="websiteName" href="../../index.php">PayFriends</a>
          <a class="navbar-brand brand-logo-mini" href="../../index.php"><img src="../../assets/images/logo-mini.svg" alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-stretch">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <div class="nav-profile-img">
                  <img src="<?php echo "../../assets/images/faces/".$_SESSION["username"].".png"?>" alt="image">
                  <span class="availability-status online"></span>
                </div>
                <div class="nav-profile-text">
                  <p class="mb-1 text-black"><?php echo $_SESSION["username"]?></p>
                </div>
              </a>
              <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                <a class="dropdown-item" href="#">
                  <i class="mdi mdi-cached mr-2 text-success"></i> Activity Log </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="../../logout.php">
                  <i class="mdi mdi-logout mr-2 text-primary"></i> Signout </a>
              </div>
            </li>
            <li class="nav-item d-none d-lg-block full-screen-link">
              <a class="nav-link">
                <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                <i class="mdi mdi-bell-outline"></i>
                <span class="count-symbol bg-danger"></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                <h6 class="p-3 mb-0">Notifications</h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-success">
                      <i class="mdi mdi-calendar"></i>
                    </div>
                  </div>
                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject font-weight-normal mb-1">Event today</h6>
                    <p class="text-gray ellipsis mb-0"> Just a reminder that you have an event today </p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-warning">
                      <i class="mdi mdi-settings"></i>
                    </div>
                  </div>
                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject font-weight-normal mb-1">Settings</h6>
                    <p class="text-gray ellipsis mb-0"> Update dashboard </p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-info">
                      <i class="mdi mdi-link-variant"></i>
                    </div>
                  </div>
                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject font-weight-normal mb-1">Launch Admin</h6>
                    <p class="text-gray ellipsis mb-0"> New admin wow! </p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <h6 class="p-3 mb-0 text-center">See all notifications</h6>
              </div>
            </li>
            <li class="nav-item nav-settings d-none d-lg-block">
              <a class="nav-link" href="#">
                <i class="mdi mdi-format-line-spacing"></i>
              </a>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:../../partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item nav-profile">
              <a href="#" class="nav-link">
                <div class="nav-profile-image">
                  <img src="<?php echo "../../assets/images/faces/".$_SESSION["username"].".png"?>" alt="profile">
                  <span class="login-status online"></span>
                  <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex flex-column">
                  <span class="font-weight-bold mb-2"><?php echo $_SESSION["username"]?></span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../../index.php">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Evenements</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi mdi-calendar-multiple-check menu-icon"></i>
              </a>
              <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                <?php
                  //Get user events
                  $sql = "SELECT e.id,title FROM events e, members m WHERE e.id like m.ide and m.idu = ?";

                  if($stmt = mysqli_prepare($db, $sql)){
                      // Bind variables to the prepared statement as parameters
                      mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);

                      // Attempt to execute the prepared statement
                      if(mysqli_stmt_execute($stmt)){
                          mysqli_stmt_bind_result($stmt, $id, $title);

                          /* fetch values */
                          while (mysqli_stmt_fetch($stmt)) {
                            echo '<li class="nav-item" id="'.$id.'"> <a class="nav-link" href="../../index.php?id='.$id.'">'.$title.'</a></li>';
                          }
                          mysqli_stmt_fetch($stmt);
                      } else{
                          echo "Oops! Something went wrong. Please try again later.";
                      }
                  }
                  // Close statement
                  mysqli_stmt_close($stmt);
                    // Close connection
                    mysqli_close($db);
                  ?>
                </ul>
              </div>
            </li>
          </ul>
        </nav>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="menu-title"> Nouvel évènement  </h3>
            </div>
            <div class="row">
              <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Formulaire</h4>
                    <p class="card-description"> Veuillez remplir ces champs </p>
                    <form class="forms-sample" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                      <div class="form-group">
                        <label for="title" >Titre</label>
                        <input type="text" class="form-control form-control-lg <?php echo (!empty($title_err)) ? 'is-invalid help-block' : ''; ?>" id="title" name="title" placeholder="Titre" required>
                        <span class="invalid-feedback text-danger"><?php echo $title_err; ?></span>
                      </div>
                      <div class="form-group">
                        <label for="description" >Description</label>
                        <textarea rows="4" cols="10" class="form-control form-control-lg <?php echo (!empty($description_err)) ? 'is-invalid help-block' : ''; ?>" id="description" placeholder="Description" maxlength="100" name="description" required></textarea>
                        <span class="invalid-feedback text-danger"><?php echo $description_err; ?></span>
                      </div>
                      <button type="submit" class="btn btn-gradient-primary mr-2">Valider</button>
                      <button class="btn btn-light">Annuler</button>
                      <span class="valid-feedback text-success block"><?php echo $event_success; ?></span>
                      <span class="invalid-feedback text-danger block"><?php echo $event_error; ?></span>
                    </form>
                  </div>
                </div>
              </div>
              <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Tableau participants</h4>
                    <p class="card-description"> Liste des participants </p>
                    <form class="forms-sample">
                      <div class="form-group row">
                        <label for="title" class="col-sm-3 col-form-label">Nom</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" name="name" placeholder="Nom">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="description" class="col-sm-3 col-form-label">Prénom</label>
                        <div class="col-sm-9">
                          <input type="textarea" class="form-control" name="firstname" placeholder="Prénom">
                        </div>
                      </div>
                      <button type="submit" class="btn btn-gradient-primary mr-2">Ajouter</button>
                    </form>
                    <form class="forms-sample">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>Nom</th>
                            <th>Prenom</th>
                            <th>Situation</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>Jacob</td>
                            <td>53275531</td>
                            <td><label class="badge badge-danger">Nope</label></td>
                          </tr>
                          <tr>
                            <td>Messsy</td>
                            <td>53275532</td>
                            <td><label class="badge badge-warning">En cours</label></td>
                          </tr>
                          <tr>
                            <td>John</td>
                            <td>53275533</td>
                            <td><label class="badge badge-success">Ajouté</label></td>
                          </tr>
                          <tr>
                            <td>Peter</td>
                            <td>53275534</td>
                            <td><label class="badge badge-success">Ajouté</label></td>
                          </tr>
                          <tr>
                            <td>Dave</td>
                            <td>53275535</td>
                            <td><label class="badge badge-warning">En cours</label></td>
                          </tr>
                        </tbody>
                      </table>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:../../partials/_footer.html -->
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">

                <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Projet Developpement Web 2019 -  <a href="http://www.polytech.u-psud.fr/fr/index.html" target="_blank">Polytech Paris-Sud</a></span>
              <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="mdi mdi-heart text-danger"></i></span>
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../../assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../../assets/js/off-canvas.js"></script>
    <script src="../../assets/js/hoverable-collapse.js"></script>
    <script src="../../assets/js/misc.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="../../assets/js/file-upload.js"></script>
    <!-- End custom js for this page -->
  </body>
</html>
