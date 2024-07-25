<!-- forgot_password.php -->
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
    <form action="send_reset_link.php" method="post">
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
