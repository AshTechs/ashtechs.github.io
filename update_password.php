// update_password.php
<?php

include 'config.php';

if (isset($_POST['submit'])) {
    $token = $_POST['token'];
    $pass = $_POST['password'];
    $cpass = $_POST['cpassword'];

    if ($pass != $cpass) {
        $message[] = 'Confirm password not matched!';
    } else {
        $passwordPattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/";

        if (!preg_match($passwordPattern, $pass)) {
            $message[] = 'Password must be at least 6 characters long and contain at least one uppercase letter, one number, and one special character!';
        } else {
            $hashedPass = password_hash($pass, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("SELECT * FROM `users` WHERE reset_token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $stmt = $conn->prepare("UPDATE `users` SET password = ?, reset_token = NULL WHERE reset_token = ?");
                $stmt->bind_param("ss", $hashedPass, $token);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    $message[] = 'Password reset successfully! You can now <a href="login.php">login</a>.';
                } else {
                    $message[] = 'Failed to reset password!';
                }
            } else {
                $message[] = 'Invalid or expired token!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="form-container">
    <form action="" method="post">
        <h3>Reset Password</h3>
        <?php
        if(isset($message)){
            foreach($message as $msg){
                echo '<div class="message">'.$msg.'</div>';
            }
        }
        ?>
        <input type="password" name="password" placeholder="Enter new password" class="box" required>
        <input type="password" name="cpassword" placeholder="Confirm new password" class="box" required>
        <input type="submit" name="submit" value="Reset Password" class="btn">
    </form>
</div>
</body>
</html>
