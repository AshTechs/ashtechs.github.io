<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
   exit;
}

if(isset($_GET['logout'])){
   unset($user_id);
   session_destroy();
   header('location:login.php');
   exit;
}

$query = $conn->prepare("SELECT id, role FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
   $row = $result->fetch_assoc();
   $user_id = $row['id'];
   $role = $row['role'];

   switch ($role) {
      case 'ADMIN':
         $custom_id = 'ADM' . str_pad($user_id, 3, '0', STR_PAD_LEFT);
         $access_level = 'admin';
         break;
      case 'LAB':
         $custom_id = 'LAB' . str_pad($user_id, 3, '0', STR_PAD_LEFT);
         $access_level = 'lab';
         break;
      case 'PHA':
         $custom_id = 'PHA' . str_pad($user_id, 3, '0', STR_PAD_LEFT);
         $access_level = 'pha';
         break;
      case 'MDR':
         $custom_id = 'MDR' . str_pad($user_id, 3, '0', STR_PAD_LEFT);
         $access_level = 'mdr';
         break;
      case 'NUR':
         $custom_id = 'NUR' . str_pad($user_id, 3, '0', STR_PAD_LEFT);
         $access_level = 'nur';
         break;
      case 'PAT':
         $custom_id = 'PAT' . str_pad($user_id, 3, '0', STR_PAD_LEFT);
         $access_level = 'pat';
         break;
      default:
         $custom_id = 'USR' . str_pad($user_id, 3, '0', STR_PAD_LEFT);
         $access_level = 'user';
         break;
   }
} else {
   echo "User not found!";
   exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <!-- Custom CSS file link -->
   <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
   <link rel="stylesheet" href="css/stylehome.css">
</head>
<body>
   <div class="header">
      <div class="menu-icon" id="menu-icon">
         <i class="fa-solid fa-bars" onclick="toggleMenu()"></i>
      </div>
      <img src="img/Logo.png" alt="logo">
      <p><?php echo htmlspecialchars($custom_id); ?></p>
      <span id="current-time"></span>
   </div>

   <div class="side-bar" id="side-bar">
      <a href="home.php?logout=<?php echo $user_id; ?>">
         <img src="img/logout.png" alt="Logout">
         <p class="logout-text">Logout</p>
      </a>
   </div>

   <div class="content">
      <a href="#"><div class="patient">Patient</div></a>
      <a href="#"><div class="clinician">Clinician</div></a>
      <a href="#"><div class="laboratory">Laboratory Scientist</div></a>
      <a href="#"><div class="pharmacy">Pharmacist</div></a>
      <a href="#"><div class="nursing">Nurse</div></a>
      <a href="#"><div class="admin">Adminstrator</div></a>
   </div>

   <div class="margin">
      <?php
      switch ($access_level) {
         case 'admin':
            echo "<h4>Welcome, Ashbel (Admin)!</h4>";
            // Display admin content
            break;
         case 'lab':
            echo "<h4>Welcome, Lab Technician!</h4>";
            // Display lab content
            break;
         case 'pha':
            echo "<h1>Welcome, Pharmacist!</h1>";
            // Display pharmacy content
            break;
         case 'mdr':
            echo "<h1>Welcome, Medical Doctor!</h1>";
            // Display doctor content
            break;
         case 'nur':
            echo "<h1>Welcome, Nurse!</h1>";
            // Display nurse content
            break;
         case 'pat':
            echo "<h1>Welcome, Patient!</h1>";
            // Display patient content
            break;
         default:
            echo "<h1>Welcome, User!</h1>";
            // Display generic content
            break;
      }
      ?>
   </div>
   <footer>
   <ul class="socials">
      <li><a href="https://web.facebook.com/ashbel.hinneh" target="_blank"><i class="ri-facebook-circle-fill"></i></a></li>
      <li><a href="https://x.com/AshbelHinn" target="_blank"><i class="ri-twitter-fill"></i></a></li>
      <li><a href="https://www.linkedin.com/in/ashbel-hinneh-83264a162/" target="_blank"><i class="ri-linkedin-fill"></i></a></li>
      <li><a href="https://github.com/AshTechs/ashtechs.github.io" target="_blank"><i class="ri-github-fill"></i></a></li>
   </ul>
   <div class="footer__bar">Copyright © 2024 AshTechs | All rights reserved.</div>
</footer>
   <script src="js/menu.js.php"></script>
   <script src="js/script.js"></script>
   <script src="https://kit.fontawesome.com/f8e1a90484.js" crossorigin="anonymous"></script>
</body>
</html>
