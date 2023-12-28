<?php
@include 'config.php';
session_start();

try {
    $email = $_GET['email'];
    $verify_token = $_GET['verify_token'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' AND status = 'unverified'") or die('query failed');

    if (mysqli_num_rows($result) > 0) {
        // User is not yet verified
        $verify = mysqli_query($conn, "UPDATE users SET status = 'verified' WHERE email = '$email'") or die('query failed');
        $_SESSION['verify_email'] = $email;
        $_SESSION['verify_token'] = $verify_token;
        header('location: verify_success.php');
    } else {
        // User is already verified or not found
        echo "User is already verified or not found.";
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
