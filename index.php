<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <title>Eppla Inc.</title>
    <style>
      .dropdown {
        position: relative;
        display: inline-block;
      }

      .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
      }

      .dropdown-content a {
        color: #093545;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
      }

      .dropdown:hover .dropdown-content {display: block;}
    </style>
  </head>
  <body>
    <header>
      <nav>
      <ul class="nav-links">
          <li><a href="index.php?page=home" class="nav-link">Home</a></li>
          <li>
            <div class="dropdown">
              <button class="nav-link" onclick="location.href='index.php?page=product'">Products</button>
              <div class="dropdown-content">
                <a href="index.php?page=laptop">Laptop</a>
                <!-- <a href="#">Mobile phone</a>
                <a href="#">Tablet</a> -->
              </div>
            </div>
          </li>
          <li><a href="index.php?page=contact" class="nav-link">Contacts</a></li>
          <?php
            session_start();

            if (!isset($_SESSION["cookie_name"])){
              echo "";
            }
            elseif($_SESSION["cookie_name"] == "admin"){
              echo "<li><a href='index.php?page=manage' class='nav-link'>Manage</a></li>";
            }

            if (!isset($_SESSION["cookie_name"])){
              echo "<li><a href='index.php?page=login' class='nav-link' id='ch'>Login</a></li>";
            }
            else{
              echo "<li><a href='index.php?page=login' class='nav-link' id='ch'> Welcome " . $_SESSION['cookie_name'] . "!</a></li>";
              echo "<li> 
              <form method='post' action='logout.php'>
              <input type='submit' name='logout' value='Log out'/>
              </form>
              </li>";
            }
          ?>
      </ul>
      </nav>
    </header>
    
    <?php
    if (!isset($_GET["page"])){
      include "home.php";
    }
    else{
      if ($_GET["page"] == "home"){
        include "home.php";
      }
      elseif ($_GET["page"] == "product"){
        include "product.php";
      }
      elseif ($_GET["page"] == "contact"){
        include "contact.php";
      }
      elseif ($_GET["page"] == "login"){
        include "login.php";
      }
      elseif ($_GET["page"] == "register"){
        include "register.php";
      }
      elseif ($_GET["page"] == "manage"){
        include "manage.php";
      }
      elseif ($_GET["page"] == "manage_processing"){
        include "manage_processing.php";
      }
      elseif ($_GET["page"] == "laptop"){
        include "laptop.php";
      }
      elseif ($_GET["page"] == "item"){
        include "item.php";
      }
    }
    ?>
  </body>
</html>
