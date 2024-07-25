<?php

include 'config.php';
require 'vendor/autoload.php';
require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $cpass = $_POST['cpassword'];

    // Password validation
    $passwordPattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/";

    if (!preg_match($passwordPattern, $pass)) {
        $message[] = 'Password must be at least 6 characters long, contain at least one uppercase letter, one number, and one special character!';
    } else {
        $hashedPass = password_hash($pass, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message[] = 'User already exists';
        } else {
            if ($pass != $cpass) {
                $message[] = 'Confirm password not matched!';
            } else {
                // Generate activation token
                $token = bin2hex(random_bytes(50));
                $stmt = $conn->prepare("INSERT INTO `users` (name, email, password, activation_token) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $name, $email, $hashedPass, $token);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    // Send activation email using PHPMailer
                    $mail = new PHPMailer(true);

                    try {
                        // Server settings
                        $mail->SMTPDebug = 0; // Enable verbose debug output
                        $mail->isSMTP(); // Set mailer to use SMTP
                        $mail->Host = $_ENV['SMTP_HOST']; // Specify main and backup SMTP servers
                        $mail->SMTPAuth = true; // Enable SMTP authentication
                        $mail->Username = $_ENV['SMTP_USERNAME']; // SMTP username
                        $mail->Password = $_ENV['SMTP_PASSWORD']; // SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption, `PHPMailer::ENCRYPTION_SMTPS` also accepted
                        $mail->Port = 587; // TCP port to connect to

                        // Recipients
                        $mail->setFrom($_ENV['SMTP_USERNAME'], 'HealthEase');
                        $mail->addAddress($email, $name); // Add a recipient

                        // Content
                        $mail->isHTML(true); // Set email format to HTML
                        $mail->Subject = 'Activate your account';
                        $mail->Body = "Hi $name, click on the following link to activate your account: ";
                        $mail->Body .= "<a href='http://localhost/login/activate.php?token=$token'>Activate Account</a>";

                        $mail->send();
                        $message[] = 'Registered successfully! Please check your email to activate your account.';
                    } catch (Exception $e) {
                        $message[] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                } else {
                    $message[] = 'Registration failed!';
                }
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
   <div class="logo">
      <img src="img/Logo.png" alt="logo"/>
   </div>
   
<div class="form-container">

   <form action="" method="post">
      <h3>Sign up</h3>
      <?php
      if(isset($message)){
         foreach($message as $msg){
            echo '<div class="message">'.$msg.'</div>';
         }
      }
      ?>
      <input type="text" name="name" placeholder="Enter Username" class="box" required>
      <input type="email" name="email" placeholder="Enter email" class="box" required>
      <input type="password" name="password" id="password" placeholder="Enter password" class="box" required>
      <input type="password" name="cpassword" id="cpassword" placeholder="Confirm password" class="box" required>
      <input type="checkbox" onclick="togglePassword()"> Show Password
      <input type="submit" name="submit" value="Sign up now" class="btn">
      <p>Already have an account? <a href="login.php">Login now</a></p>
   </form>

</div>
<script src="js/scriptz.js.php"></script>
</body>
</html>
