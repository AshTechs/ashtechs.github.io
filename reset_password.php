<!-- reset_password.php -->
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
    <form action="update_password.php" method="post">
        <h3>Reset Password</h3>
        <?php
        if(isset($_GET['token'])){
            $token = $_GET['token'];
            echo '<input type="hidden" name="token" value="'.$token.'" class="box">';
        }
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
