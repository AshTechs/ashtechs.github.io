<?php

include 'config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $stmt = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(50));
        $stmt = $conn->prepare("UPDATE `users` SET reset_token = ? WHERE email = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $mail = new PHPMailer(true);
            try {
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->Host = $_ENV['SMTP_HOST'];
                $mail->SMTPAuth = true;
                $mail->Username = $_ENV['SMTP_USERNAME'];
                $mail->Password = $_ENV['SMTP_PASSWORD'];
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom($_ENV['SMTP_USERNAME'], 'HealthEase');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                $mail->Body = "Hi $name, click on the following link to reset your password: ";
                $mail->Body .= "<a href='http://localhost/login/reset_password.php?token=$token'>Reset Password</a>";

                $mail->send();
                $message[] = 'Reset link has been sent to your email.';
            } catch (Exception $e) {
                $message[] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $message[] = 'Failed to generate reset link!';
        }
    } else {
        $message[] = 'Email not found!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="form-container">
    <form action="" method="post">
        <h3>Forgot Password</h3>
        <?php
        if(isset($message)){
            foreach($message as $msg){
                echo '<div class="message">'.$msg.'</div>';
            }
        }
        ?>
        <input type="email" name="email" placeholder="Enter your email" class="box" required>
        <input type="submit" name="submit" value="Send Reset Link" class="btn">
    </form>
</div>
</body>
</html>
