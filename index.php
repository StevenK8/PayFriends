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

// Include config file
require_once "config.php";

$username_err = $username_success = "";

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
  if(isset($_GET["username"])){
    if($_SERVER["REQUEST_METHOD"] == "GET"){
      $sql = "SELECT id FROM users WHERE username = ?";
    
      if($stmt = mysqli_prepare($db, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $_GET["username"]);
    
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Store result
            mysqli_stmt_store_result($stmt);
    
            // Check si l'user est membre de l'evenement
            if(mysqli_stmt_num_rows($stmt) != 1){
              mysqli_stmt_close($stmt);
              $username_err = "Cet utilisateur n'existe pas";
            }else{
              mysqli_stmt_bind_result($stmt, $idu);
    
              /* fetch values */
              mysqli_stmt_fetch($stmt);
    
              addInvite($db,$_GET["id"],$idu);
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);
      }
    }
  }
}

if(isset($_GET["redirect"])){
  $sql = "SELECT id,title,description FROM events, members WHERE token = ? AND ide not in (select ide from members WHERE idu like ?)";

  if($stmt = mysqli_prepare($db, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "si", $_GET["redirect"], $_SESSION["id"]);

    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){

        // Store result
        mysqli_stmt_store_result($stmt);

        // Check si l'user est membre de l'evenement
        if(mysqli_stmt_num_rows($stmt) != 0){
          mysqli_stmt_bind_result($stmt, $id, $title, $description);

          /* fetch values */
          mysqli_stmt_fetch($stmt);

          #echo '<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#invitation">Open invitation</button>';

          echo '<div id="invitation" tabindex="-1" class="modal fade" role="dialog" aria-labelledby="titleInvitation" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 id="titleInvitation" class="modal-title">Invitation à '.$title.'</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">
                <p>'.$description.'</p>
              </div>

              <div class="modal-footer">
                <form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">
                  <button type="submit" name="accept" class="btn btn-outline-success btn-fw">Accepter</button>
                  <input type="hidden" name="token" value="'.$_GET["redirect"].'" />
                  <button type="submit" name="deny" class="btn btn-outline-danger btn-fw">Refuser</button>
                </form>
              </div>
            </div>
          </div>
        </div>';
        }else{
          $_GET["redirect"] = "";
        }
    }
    // Close statement
    mysqli_stmt_close($stmt);
  }

}

function addUser($db,$ide,$idu){
  if(!isAlreadyIn($db,$ide,$idu)){
    $sql = "INSERT INTO `members` (`ide`, `idu`) VALUES ( ? ,  ? )";

    if($stmt = mysqli_prepare($db, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ii", intval($ide), intval($idu));
  
        // Attempt to execute the prepared statement
        if(!mysqli_stmt_execute($stmt)){
            echo "Error: " . $sql . "<br>" . mysqli_error($db);
        }else{
          deleteInvite($db,$ide,$idu);
        }
    }
    mysqli_stmt_close($stmt);
    header("location: index.php?id=".$ide);
  }
}

function deleteInvite($db,$ide,$idu){
  $sql = "DELETE FROM invites WHERE ide = ? AND idu = ?";

  if($stmt = mysqli_prepare($db, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "ii", intval($ide), intval($idu));

    // Attempt to execute the prepared statement
    if(!mysqli_stmt_execute($stmt)){
        echo "Error: " . $sql . "<br>" . mysqli_error($db);
    }
  }
  mysqli_stmt_close($stmt);
  header("location: index.php");
}

function addInvite($db,$ide,$idu){
  if(!isAlreadyIn($db,$ide,$idu)){
    $sql = "INSERT INTO `invites` (`ide`, `idu`) VALUES ( ? ,  ? )";

    if($stmt = mysqli_prepare($db, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ii", intval($ide), intval($idu));
  
        // Attempt to execute the prepared statement
        if(!mysqli_stmt_execute($stmt)){
            echo "Error: " . $sql . "<br>" . mysqli_error($db);
        }else{
          $username_success = "Invitation envoyée!";
        }
    }
    mysqli_stmt_close($stmt);
    header("location: index.php?id=".$ide);
  }
}

function isAlreadyIn($db,$ide,$idu){
  $sql = "SELECT ide,idu FROM members WHERE ide = ? AND idu = ?";
  
  if($stmt = mysqli_prepare($db, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "ii", intval($ide), intval($idu));

    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
      // Store result
      mysqli_stmt_store_result($stmt);

      // Check si l'user est membre de l'evenement
      if(mysqli_stmt_num_rows($stmt) == 1){
        // Close statement
        mysqli_stmt_close($stmt);
        return true;
      }else{
        // Close statement
        mysqli_stmt_close($stmt);
        return false;
      }
    }
  }
}

function addDepense($db,$ide,$idu,$nom,$prix,$date){
  if(isAlreadyIn($db,$ide,$idu)){
    $sql = "INSERT INTO `depenses` (`ide`, `idu`, `nom`, `prix` , `date`) VALUES ( ? ,  ? ,  ? ,  ? ,  ? )";

    if($stmt = mysqli_prepare($db, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "iisds", intval($ide), intval($idu), $nom, floatval($prix), $date);

        // Attempt to execute the prepared statement
        if(!mysqli_stmt_execute($stmt)){
            echo "Error: " . $sql . "<br>" . mysqli_error($db);
        }else{
          $depense_success = "Dépense créée!";
        }
    }
    mysqli_stmt_close($stmt);
    addMemberDepense($db, mysqli_insert_id($db), $ide, $idu);
  }
}

function addMemberDepense($db, $idd, $ide, $idu){
  $sql = "SELECT idu FROM `members` WHERE ide=? AND idu!=?";
  if($stmt = mysqli_prepare($db, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "ii", intval($ide), intval($idu));

    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
      mysqli_stmt_store_result($stmt);

      mysqli_stmt_bind_result($stmt, $idu);
      /* fetch values */
      while(mysqli_stmt_fetch($stmt)){
        $sql2 = "INSERT INTO `members_depense` (`idd`, `idu`, `haspaid`) VALUES ( ? ,  ? ,  0)";
        if($stmt2 = mysqli_prepare($db, $sql2)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt2, "ii", intval($idd), intval($idu));
            // Attempt to execute the prepared statement
            if(!mysqli_stmt_execute($stmt2)){
                echo "Error: " . $sql2 . "<br>" . mysqli_error($db);
            }
            mysqli_stmt_close($stmt2);
        }else{
          echo "Error: " . $sql2 . "<br>" . mysqli_error($db);
        }
      }
      mysqli_stmt_fetch($stmt);
    }
  }
  mysqli_stmt_close($stmt);
  header("location: index.php?id=".$ide);
}

if(isset($_POST["nomDepense"]) && isset($_POST["prixDepense"]) && intval($_POST["prixDepense"])>0 && isset($_POST["id"])){
  addDepense($db,$_POST["id"],$_SESSION["id"],htmlspecialchars($_POST["nomDepense"]),$_POST["prixDepense"], date("Y-m-d"));
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST["token"])){
    $sql = "SELECT id FROM events WHERE token = ?";

    if($stmt = mysqli_prepare($db, $sql)){
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "s", $_POST["token"]);

      // Attempt to execute the prepared statement
      if(mysqli_stmt_execute($stmt)){
          // Store result
          mysqli_stmt_store_result($stmt);

          // Check si l'user est membre de l'evenement
          if(mysqli_stmt_num_rows($stmt) != 1){
            mysqli_stmt_close($stmt);
            header("location: index.php");
          }else{
            mysqli_stmt_bind_result($stmt, $ide);

            /* fetch values */
            mysqli_stmt_fetch($stmt);

            if(isset($_POST["accept"])){
              addUser($db,$ide,$_SESSION["id"]); // On rejoint l'événement
            }else if(isset($_POST["deny"])){
              deleteInvite($db,$ide,$_SESSION["id"]); // On supprime l'invitation
            }
            
          }
      }
      // Close statement
      mysqli_stmt_close($stmt);
    }
  }
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
  </head>
  <body>
    <div id="depense" tabindex="-1" class="modal fade" role="dialog" aria-labelledby="titleDepense" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 id="titleDepense" class="modal-title">Nouvelle dépense</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <p>Veuillez renseigner le nom et le prix de la dépense à ajouter.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
              <div class="form-group">
                <input type="text" class="form-control" id="nomDepense" placeholder="Nom" name="nomDepense">
                <div class="input-group">
                    <input type="number" class="form-control" min="1" aria-label="Prix" placeholder="Prix" name="prixDepense">
                    <input type="hidden" name="id" value="<?php echo $_GET["id"] ?>" />
                    <div class="input-group-append">
                      <span class="input-group-text bg-gradient-primary text-white">€</span>
                    </div>
                  </div>
                  <div class="form-check form-check-info">
                    <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" checked=""> Tous les membres <i class="input-helper"></i></label>
                  </div>
                  <div class="form-check form-check-info">
                    <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios2" value=""> Sélectionner certains membres <i class="input-helper"></i></label>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-gradient-primary btn-fw">Ajouter</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
          <a class="navbar-brand brand-logo" id="websiteName" href="index.php">PayFriends</a>
        <!--  <a class="navbar-brand brand-logo" href="index.php"><img src="assets/images/logo.svg" alt="logo" /></a> -->
          <a class="navbar-brand brand-logo-mini" href="index.php"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-stretch">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <div class="nav-profile-img">
                  <img src="<?php echo "assets/images/faces/".$_SESSION["username"].".png"?>" alt="image">
                  <span class="availability-status online"></span>
                </div>
                <div class="nav-profile-text">
                  <p class="mb-1 text-black"><?php echo $_SESSION["username"]?></p>
                </div>
              </a>
              <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                <a class="dropdown-item" href="logout.php">
                  <i class="mdi mdi-logout mr-2 text-primary"></i> Déconnexion </a>
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
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                <h6 class="p-3 mb-0">Notifications</h6>
                <div class="dropdown-divider"></div>
                <?php
                $sql = "SELECT e.title,e.token FROM events e,invites i WHERE i.idu like ? AND i.ide like e.id";
                $i = 0;
                if($stmt = mysqli_prepare($db, $sql)){
                  // Bind variables to the prepared statement as parameters
                  mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);

                  // Attempt to execute the prepared statement
                  if(mysqli_stmt_execute($stmt)){
                    
                    mysqli_stmt_bind_result($stmt, $titleNotification, $tokenNotification);

                    /* fetch values */
                    while (mysqli_stmt_fetch($stmt)) {
                      $i++;
                      echo '                
                      <a name="notification'.$i.'" class="dropdown-item preview-item" href="https://stevenkerautret.com/PayFriends/index.php?redirect='.$tokenNotification.'">
                        <div class="preview-thumbnail">
                          <div class="preview-icon bg-success">
                            <i class="mdi mdi-calendar"></i>
                          </div>
                        </div>
                        <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                          <h6 class="preview-subject font-weight-normal mb-1">'.$titleNotification.'</h6>
                          <p class="text-gray ellipsis mb-0"> Vous avez été invité à cet événement </p>
                        </div>
                      </a>
                      <div class="dropdown-divider"></div>';
                    }
                    mysqli_stmt_fetch($stmt);
                  }
                  // Close statement
                  mysqli_stmt_close($stmt);
                }
                if($i==0){
                  echo '<a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-info">
                      <i class="mdi mdi-calendar-blank"></i>
                    </div>
                  </div>
                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject font-weight-normal mb-1">Vous n\'avez pas de notifications</h6>
                  </div>
                </a>
                <div class="dropdown-divider"></div>';
                }
                ?>
                
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
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item nav-profile">
              <a href="#" class="nav-link">
                <div class="nav-profile-image">
                  <img src="<?php echo "assets/images/faces/".$_SESSION["username"].".png"?>" alt="profile">
                  <span class="login-status online"></span>
                  <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex flex-column">
                  <span class="font-weight-bold mb-2"><?php echo $_SESSION["username"]?></span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
              </a>
            </li>
            <li class="nav-item <?php echo isset($_GET["id"])?"":"active" ?>">
              <a class="nav-link" href="index.php">
                <span class="menu-title">Menu principal</span>
                <i class="mdi mdi-home menu-icon"></i>
              </a>
            </li>
            <li class="nav-item <?php echo isset($_GET["id"])?"active":"" ?>">
              <a class="<?php echo isset($_GET["id"])?"nav-link":"nav-link collapsed" ?>" data-toggle="collapse" href="#ui-basic" aria-expanded="<?php echo isset($_GET["id"])?"true":"false" ?>" aria-controls="ui-basic">
                <span class="menu-title">Événements</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi mdi-calendar-multiple-check menu-icon"></i>
              </a>
              <div class="collapse <?php echo isset($_GET["id"])?"show":"" ?>" id="ui-basic">
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
                            echo '<li class="nav-item" id="'.$id.'"> <a class="nav-link ';
                            if(isset($_GET["id"])){
                              echo (($_GET["id"]==$id)?"active":"");
                            } 
                            echo '" href="index.php?id='.$id.'">'.$title.'</a></li>';
                          }
                          mysqli_stmt_fetch($stmt);
                      } else{
                          echo "Erreur events barre latérale";
                      }
                  }
                  // Close statement
                  mysqli_stmt_close($stmt);
                  ?>
                  <!-- <li class="nav-item"> <a class="nav-link" href="pages/ui-features/buttons.html">Buttons</a></li>
                  <li class="nav-item"> <a class="nav-link" href="pages/ui-features/typography.html">Typography</a></li> -->
                </ul>
              </div>
            </li>

            <li class="nav-item sidebar-actions">
              <span class="nav-link">
                <div class="border-bottom">
                </div>
                <a class="nav-link" href="pages/forms/newEvent.php">
                  <button class="btn btn-block btn-lg btn-gradient-primary mt-4" id="newEvent"><i class="mdi mdi-plus-circle-multiple-outline mdi-24px float-center"></i><br>Nouvel Evénement</button>
                </a>
              </span>
            </li>
          </ul>
        </nav>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <?php
              if(isset($_GET["id"])){
                $sql = "SELECT title,description FROM events e WHERE e.id like ?";

                if($stmt = mysqli_prepare($db, $sql)){
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "i", $_GET["id"]);

                    // Attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt)){
                        mysqli_stmt_bind_result($stmt, $title, $description);

                        mysqli_stmt_fetch($stmt);
                        echo '<h3 class="page-title">
                        <span class="page-title-icon bg-gradient-primary text-white mr-2">
                          <i class="mdi mdi-calendar"></i>
                        </span>';
                        echo $title;
                        echo '</h3>';
                        echo "<i>".$description."</i>";
                    } else{
                        echo "Error";
                    }
                }
                // Close statement
                mysqli_stmt_close($stmt);
              }else{
                echo '<h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                  <i class="mdi mdi-home"></i>
                </span>Menu</h3>';
              }
              ?>
            </div>
            <div class="row">
              <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-danger card-img-holder text-white">
                  <div class="card-body">
                    <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Coût total <i class="mdi mdi-chart-line mdi-24px float-right"></i>
                    </h4>
                    <h2 class="mb-5">
                      <?php
                      if(isset($_GET["id"])){
                        $sql = "SELECT COUNT(idu) FROM members WHERE ide like ?";

                        if($stmt = mysqli_prepare($db, $sql)){
                            // Bind variables to the prepared statement as parameters
                            mysqli_stmt_bind_param($stmt, "i", $_GET["id"]);
      
                            // Attempt to execute the prepared statement
                            if(mysqli_stmt_execute($stmt)){
                                mysqli_stmt_bind_result($stmt, $nbParticipants);
      
                                mysqli_stmt_fetch($stmt);
                            }
                          // Close statement
                          mysqli_stmt_close($stmt);
                        }

                        $sql = "SELECT SUM(prix) FROM depenses d WHERE d.ide like ?";

                        if($stmt = mysqli_prepare($db, $sql)){
                            // Bind variables to the prepared statement as parameters
                            mysqli_stmt_bind_param($stmt, "i", $_GET["id"]);
      
                            // Attempt to execute the prepared statement
                            if(mysqli_stmt_execute($stmt)){
                                mysqli_stmt_bind_result($stmt, $total);
      
                                mysqli_stmt_fetch($stmt);
                                if($total!=""){
                                  echo $total."€";
                                }else{
                                  echo "0€";
                                }
                            } else{
                                echo "?";
                            }
                          // Close statement
                          mysqli_stmt_close($stmt);
                        }
                        
                      }else{
                        $sql = "SELECT COUNT(DISTINCT idu) FROM members";

                        if($stmt = mysqli_prepare($db, $sql)){

                          if(mysqli_stmt_execute($stmt)){
                            mysqli_stmt_bind_result($stmt, $nbParticipants);
                            mysqli_stmt_fetch($stmt); 
                          }else{
                            echo "?";
                          }
                          // Close statement
                          mysqli_stmt_close($stmt);
                        } 
                        
                        $sql = "SELECT SUM(prix) FROM depenses d";

                        if($stmt = mysqli_prepare($db, $sql)){
      
                            // Attempt to execute the prepared statement
                            if(mysqli_stmt_execute($stmt)){
                                mysqli_stmt_bind_result($stmt, $total);
      
                                mysqli_stmt_fetch($stmt);
                                if($total!=""){
                                  echo $total."€";
                                }else{
                                  echo "0€";
                                }
                                
                            } else{
                                echo "?";
                            }
                          // Close statement
                          mysqli_stmt_close($stmt);
                        }                     
                        
                      }
                      ?>
                    </h2>
                    <h6 class="card-text">
                    <?php
                      if($total!=0){
                        echo "Soit ".intval($total / $nbParticipants)."€ par personne";
                      }else{
                        echo "Zé-ro!";
                      }
                    ?>

                    </h6>
                  </div>
                </div>
              </div>
              <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-info card-img-holder text-white">
                  <div class="card-body">
                    <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Vos dépenses <i class="mdi mdi-currency-eur mdi-24px float-right"></i>
                    </h4>
                    <h2 class="mb-5">
                    <?php
                      if(isset($_GET["id"])){
                        $sql = "SELECT SUM(prix) FROM depenses d WHERE d.ide like ? and d.idu like ?";

                        if($stmt = mysqli_prepare($db, $sql)){
                            // Bind variables to the prepared statement as parameters
                            mysqli_stmt_bind_param($stmt, "ii", $_GET["id"], $_SESSION["id"]);
      
                            // Attempt to execute the prepared statement
                            if(mysqli_stmt_execute($stmt)){
                                mysqli_stmt_bind_result($stmt, $total_user);
      
                                mysqli_stmt_fetch($stmt);
                                if($total_user!=""){
                                  echo $total_user."€";
                                }else{
                                  echo "0€";
                                }
                            } else{
                                echo "?";
                            }
                          // Close statement
                          mysqli_stmt_close($stmt);
                        }
                        
                      }else{
                        $sql = "SELECT SUM(prix) FROM depenses d WHERE d.idu like ?";

                        if($stmt = mysqli_prepare($db, $sql)){
      
                          mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
                          // Attempt to execute the prepared statement
                          if(mysqli_stmt_execute($stmt)){
                              mysqli_stmt_bind_result($stmt, $total_user);
    
                              mysqli_stmt_fetch($stmt);
                              if($total_user!=""){
                                echo $total_user."€";
                              }else{
                                echo "0€";
                              }
                              
                          } else{
                              echo "?";
                          }
                          // Close statement
                          mysqli_stmt_close($stmt);
                        }                     
                        
                      }
                      ?>
                    </h2>
                    <h6 class="card-text">
                    <?php
                      if($total!=0){
                        $pourcentage = ($total_user/$total)*100;
                        echo "Soit ".intval($pourcentage)."% du total";
                      }else{
                        echo "Zé-ro!";
                      }
                    ?>
                    </h6>
                  </div>
                </div>
              </div>
              <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-success card-img-holder text-white">
                  <div class="card-body">
                    <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Participants <i class="mdi mdi-account-multiple mdi-24px float-right"></i>
                    </h4>
                    <h2 class="mb-5">
                    <?php
                      if(isset($_GET["id"])){
                        if($nbParticipants!=""){
                          echo $nbParticipants;
                        }else{
                          echo "0";
                        }
                      }else{
                        if($total!=""){
                          echo $nbParticipants;
                        }else{
                          echo "0";
                        }
                      }
                    ?>
                    </h2>
                    <h6 class="card-text" name="nbParticipants" value="<?php echo $nbParticipants ?>">Increased by 5%</h6>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-7 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="clearfix">
                      <h4 class="card-title float-left">Statistiques des dépenses</h4>
                      <div id="visit-sale-chart-legend" class="rounded-legend legend-horizontal legend-top-right float-right"></div>
                    </div>
                    <canvas id="visit-sale-chart" class="mt-4"></canvas>
                    <canvas id="mycanvas"></canvas>
                    <div id="mycanvas-legend" class="rounded-legend legend-vertical legend-bottom-left pt-4"></div>
                  </div>
                </div>
              </div>
              <div class="col-md-5 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Participations au budget</h4>
                    <canvas id="traffic-chart"></canvas>
                    <div id="traffic-chart-legend" class="rounded-legend legend-vertical legend-bottom-left pt-4"></div>
                  </div>
                </div>
              </div>
            </div>

                        <?php
                        if(isset($_GET["id"])){

                          echo '<div class="row">
                          <div class="col-12 grid-margin">
                            <div class="card">
                              <div class="card-body">
                                <h4 class="card-title">Participants</h4>
                                <div class="table-responsive">
                                  <table class="table">
                                    <thead>
                                      <tr>
                                        <th> Nom d\'utilisateur </th>
                                        <th> Somme totale </th>
                                        <th> Statut </th>
                                        <th> Date </th>
                                      </tr>
                                    </thead>
                                    <tbody>';
                          $sql = "SELECT u.username, SUM(d.prix)/(COUNT(DISTINCT md.idu)+1) AS remboursement FROM depenses d, members_depense md, users u WHERE d.id like md.idd AND md.haspaid=0 AND md.idu = u.id AND d.ide like ? GROUP BY md.idu";

                          if($stmt2 = mysqli_prepare($db, $sql)){
                            // Bind variables to the prepared statement as parameters
                            mysqli_stmt_bind_param($stmt2, "i", $_GET["id"]);

                            // Attempt to execute the prepared statement
                            if(mysqli_stmt_execute($stmt2)){
                              // Store result
                              mysqli_stmt_store_result($stmt2);
                              mysqli_stmt_bind_result($stmt2, $username2, $remboursement);
                              mysqli_stmt_fetch($stmt2);
                              $sql = "SELECT username,SUM(d.prix) as prix,date FROM members m, users u, depenses d WHERE u.id like m.idu and d.ide like m.ide and d.idu like m.idu and m.ide = ? GROUP BY username ORDER BY date ";

                              if($stmt = mysqli_prepare($db, $sql)){
                                  // Bind variables to the prepared statement as parameters
                                  mysqli_stmt_bind_param($stmt, "i", $_GET["id"]);

                                  // Attempt to execute the prepared statement
                                  if(mysqli_stmt_execute($stmt)){
                                    // Store result
                                    mysqli_stmt_store_result($stmt);
                                    mysqli_stmt_bind_result($stmt, $username, $prix,$date);

                                    /* fetch values */
                                    while (mysqli_stmt_fetch($stmt)) {
                                      if($username!=""){
                                        if($username == $username2){
                                          echo '<tr>
                                          <td>
                                            <img src="assets/images/faces/'.$username.'.png" class="mr-2" alt="image"> '.$username.'</td>
                                          <td> '.($prix-$remboursement).'€ </td>
                                          <td>
                                            <label class="badge '.(($prix-$remboursement)>0?"badge-gradient-success\">Positif":"badge-gradient-danger\">Négatif").'</label>
                                          </td>
                                          <td> '.$date.' </td>
                                        </tr>';
                                        mysqli_stmt_fetch($stmt2);
                                        }else{
                                          echo '<tr>
                                          <td>
                                            <img src="assets/images/faces/'.$username.'.png" class="mr-2" alt="image"> '.$username.'</td>
                                          <td> '.$prix.'€ </td>
                                          <td>
                                            <label class="badge badge-gradient-success">Positif</label>
                                          </td>
                                          <td> '.$date.' </td>
                                        </tr>';
                                        }
                                      }
                                    }
                                    mysqli_stmt_fetch($stmt);
                                    
                                  } else{
                                      echo mysqli_error($db);
                                  }
                                // Close statement
                                mysqli_stmt_close($stmt);

                                      
                                $sql = "SELECT username FROM members m, users u WHERE id like idu and ide = ? AND username not in (SELECT username as prix FROM members m, users u, depenses d WHERE u.id like m.idu and d.ide like m.ide and d.idu like m.idu and m.ide = ?)";

                                if($stmt = mysqli_prepare($db, $sql)){
                                  // Bind variables to the prepared statement as parameters
                                  mysqli_stmt_bind_param($stmt, "ii", $_GET["id"],$_GET["id"]);

                                  // Attempt to execute the prepared statement
                                  if(mysqli_stmt_execute($stmt)){

                                      mysqli_stmt_bind_result($stmt, $username);

                                      /* fetch values */
                                      while (mysqli_stmt_fetch($stmt)) {
                                        if($username!=""){
                                          if($username == $username2){
                                            echo '<tr>
                                            <td>
                                              <img src="assets/images/faces/'.$username.'.png" class="mr-2" alt="image"> '.$username.'</td>
                                            <td> '.(-$remboursement).'€ </td>
                                            <td>
                                              <label class="badge badge-gradient-danger">Négatif</label>
                                            </td>
                                            <td style="color: grey;"> Jamais </td>
                                          </tr>';
                                          mysqli_stmt_fetch($stmt2);
                                          }else{
                                            echo '<tr>
                                            <td>
                                              <img src="assets/images/faces/'.$username.'.png" class="mr-2" alt="image"> '.$username.'</td>
                                            <td> 0€ </td>
                                            <td>
                                              <label class="badge badge-gradient-info">Zé-ro</label>
                                            </td>
                                            <td style="color: grey;"> Jamais </td>
                                          </tr>';
                                          }
                                        }
                                      }
                                      mysqli_stmt_fetch($stmt);
                                  } else{
                                      echo mysqli_error($db);
                                  }
                                // Close statement
                                mysqli_stmt_close($stmt);
                              }
                              }
                            } else{
                                echo mysqli_error($db);
                            }
                            // Close statement
                            mysqli_stmt_close($stmt2);
                          }




                          echo '                        </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>';
                          
                        }

                        ?>
                          <?php
            if(isset($_GET["id"])){
              echo '            <div class="row">
              <div class="col-md-7 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title" style="float: left;">Bilan des dépenses</h4>
                    <button class="add btn btn-icon btn-gradient-primary font-weight-bold add-btn-font-size depense-add-btn" id="add-depense" style="float: right;
                    "><i class="mdi mdi-cart-plus mdi-22px"></i></button>';

                        $sql = "SELECT d.nom,username,d.date,d.prix FROM users u, depenses d WHERE u.id like d.idu and d.ide like ? ORDER BY date desc";
                          
                          if($stmt = mysqli_prepare($db, $sql)){
                              // Bind variables to the prepared statement as parameters
                              mysqli_stmt_bind_param($stmt, "i", $_GET["id"]);

                              // Attempt to execute the prepared statement
                              if(mysqli_stmt_execute($stmt)){
                                // Store result
                        
                                mysqli_stmt_bind_result($stmt, $nom, $payeur, $date, $prix);

                                echo '                                    
                                <div class="table-responsive" id="table-depenses">
                                <table class="table">
                                  <thead>
                                    <tr>
                                      <th> Nom </th>
                                      <th> Payé par </th>
                                      <th> Date </th>
                                      <th> Prix </th>
                                    </tr>
                                  </thead>
                                  <tbody>';
                                /* fetch values */
                                while (mysqli_stmt_fetch($stmt)) {
                                  if($nom!=""){
                                    echo '
                                        <tr>
                                        <td class="text-info"> '.$nom.' </td>
                                        <td class="font-weight-bold"> '.$payeur.' </td>
                                        <td> '.$date.' </td>
                                        <td class="text-info"> '.$prix.'€ </td>
                                    ';
                                  }
                                }
                                mysqli_stmt_fetch($stmt);
                                echo '
                                </tr>
                                </tbody>
                                </table>
                              </div>';
                              } else{
                                echo mysqli_error($db);
                              }
                            // Close statement
                            mysqli_stmt_close($stmt);
                          }

                        echo '

                  </div>
                </div>
              </div>
              <div class="col-md-5 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Ajout participants</h4><br>
                    ';
                    
                    $sql = "SELECT token FROM events WHERE id = ?";

                    if($stmt = mysqli_prepare($db, $sql)){
                      // Bind variables to the prepared statement as parameters
                      mysqli_stmt_bind_param($stmt, "i", intval($_GET["id"]));
                  
                      // Attempt to execute the prepared statement
                      if(mysqli_stmt_execute($stmt)){
                          // Store result
                          mysqli_stmt_bind_result($stmt, $tokenEvent);

                          /* fetch values */
                          mysqli_stmt_fetch($stmt);
                      }
                      // Close statement
                      mysqli_stmt_close($stmt);
                    }
                    
                    echo '
                    <form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="get">
                      <div class="add-items d-flex">
                        <input name="username" type="text" class="form-control todo-list-input" placeholder="Nom du compte">
                        <input type="hidden" name="id" value="'.$_GET["id"].'" />
                        <button type="submit" class="add btn btn-gradient-primary font-weight-bold todo-list-add-btn add-btn-font-size" id="add-user"><i class="mdi mdi-account-plus mdi-22px float-right"></i></button>
                      </div>
                      <span class="text-danger"> '.$username_err.' </span>
                      <span class="text-success"> '.$username_success.' </span>
                    </form>

                   <div class="input-group">
                   <input type="text" id="tokenURL" class="form-control" value="https://stevenkerautret.com/PayFriends/index.php?redirect='.$tokenEvent.'">
                   <div class="input-group-append">
                     <button class="btn btn-sm btn-gradient-info" onclick="copy()" type="button">Copier</button>
                   </div>
                 </div>

                   <span id="confirm-invite" class="text-success block"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>';
            }
              ?>

              <script>
                function copy() {
                  /* Get the text field */
                  var copyText = document.getElementById("tokenURL");

                  /* Select the text field */
                  copyText.select();
                  copyText.setSelectionRange(0, 99999); /*For mobile devices*/

                  /* Copy the text inside the text field */
                  document.execCommand("copy");
                }
              </script>
        

          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Projet Developpement Web 2020 -  <a href="http://www.polytech.u-psud.fr/fr/index.html" target="_blank">Polytech Paris-Sud</a></span>
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
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="assets/vendors/chart.js/Chart.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="assets/js/dashboard.js"></script>
    <script src="assets/js/add-depense.js"></script>
    <!-- End custom js for this page -->
  </body>
</html>
<?php
mysqli_close($db);
?>