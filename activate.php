<?php

include 'config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT * FROM `users` WHERE activation_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if ($row['is_active'] == 0) {
            $stmt = $conn->prepare("UPDATE `users` SET is_active = 1, activation_token = '' WHERE id = ?");
            $stmt->bind_param("i", $row['id']);
            $stmt->execute();

            echo '<h3>Your email has been verified successfully!</h3>';
            echo '<p>You will be redirected to the login page in 5 seconds...</p>';
            header("refresh:5;url=login.php");
        } else {
            echo '<h3>Your account is already activated.</h3>';
            echo '<p>You will be redirected to the login page in 5 seconds...</p>';
            header("refresh:5;url=login.php");
        }
    } else {
        echo '<h3>Invalid activation token.</h3>';
        echo '<p>You will be redirected to the login page in 5 seconds...</p>';
        header("refresh:5;url=login.php");
    }

    $stmt->close();
} else {
    echo '<h3>No activation token provided.</h3>';
    echo '<p>You will be redirected to the login page in 5 seconds...</p>';
    header("refresh:5;url=login.php");
}

?>