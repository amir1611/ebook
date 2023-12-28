<?php

@include 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

if (isset($_POST['submit'])) {
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'amirjr1611@gmail.com';
    $mail->Password   = 'uqlvhpbwvjglxhnf';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    $cpass = password_hash($_POST['cpass'], PASSWORD_DEFAULT);

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO `users` (name, email, password, status) VALUES (?, ?, ?, 'verified')");
    $stmt->bind_param("sss", $name, $email, $pass);
    $stmt->execute();
    $stmt->close();

    // Send email verification link
    $mail->setFrom('amirjr1611@gmail.com', 'E-Book-Store-main');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Email Verification';
    $mail->Body = 'Please <a href="http://localhost:8000/login.php">click here</a> to verify your account.';
    
    // Check if the email is sent successfully
    if ($mail->send()) {
        echo "<script>
                alert('Verification link has been sent to your email.');
                window.location.href='login.php';
              </script>";
        exit;
    } else {
        echo "<script>alert('Email could not be sent.'); window.location.href='register.php';</script>";
        exit;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script>
        function validatePassword() {
            var password = document.getElementById("pass").value;
            var confirmPassword = document.getElementById("cpass").value;

            var messages = [];

            if (password.length < 8) {
                messages.push("Password must be at least 8 characters long.");
            }

            if (!/[A-Z]/.test(password)) {
                messages.push("Include at least one uppercase letter.");
            }

            if (!/[a-z]/.test(password)) {
                messages.push("Include at least one lowercase letter.");
            }

            if (!/\d/.test(password)) {
                messages.push("Include at least one number.");
            }

            if (!/[^A-Za-z0-9]/.test(password)) {
                messages.push("Include at least one symbol.");
            }

            if (password !== confirmPassword) {
                messages.push("Passwords do not match.");
            }

            if (messages.length > 0) {
                alert(messages.join("\n"));
                return false;
            }

            return true;
        }
    </script>
</head>

<body>
    <?php
    if (isset($_SESSION['success_message'])) {
        echo '
            <div class="message">
                <span>' . $_SESSION['success_message'] . '</span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>
        ';
        unset($_SESSION['success_message']);
    }
    ?>

    <section class="form-container">
        <form method="post" onsubmit="return validatePassword()">
            <h3>Register Now</h3>
            <input type="text" name="name" class="box" placeholder="Enter your username" required>
            <input type="email" name="email" class="box" placeholder="Enter your email" required>
            <input type="password" id="pass" name="pass" class="box" placeholder="Enter your password" required>
            <input type="password" id="cpass" name="cpass" class="box" placeholder="Confirm your password" required>
            <input type="submit" class="btn" name="submit" value="Register Now">
            <p>Already have an account? <a href="login.php">Login now</a></p>
        </form>
    </section>

    <?php @include 'footer.php'; ?>
</body>

</html>
