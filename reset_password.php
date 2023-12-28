<?php
session_start();
@include 'config.php';

$message = [];

if (!isset($_SESSION['reset_email']) || !isset($_SESSION['reset_token'])) {
    header('location: login.php');
    exit();
}

if (isset($_POST['submit'])) {
    $new_password = $_POST['new_password'];
    $email = $_SESSION['reset_email'];
    $reset_token = $_SESSION['reset_token'];

    $select_user = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' AND reset_token = '$reset_token'") or die('query failed');

    if (mysqli_num_rows($select_user) > 0) {
        $passwordValidation = validatePassword($new_password);

        if ($passwordValidation === true) {
            $hashed_password = md5($new_password);
            mysqli_query($conn, "UPDATE users SET password = '$hashed_password', reset_token = NULL WHERE email = '$email' AND reset_token = '$reset_token'") or die('query failed');

            $_SESSION['success_message'] = 'Password reset successfully!';
            header('location: login.php');
            exit();
        } else {
            $message[] = $passwordValidation;
        }
    } else {
        $message[] = 'Invalid email or token!';
    }
}

function validatePassword($password) {
    if (strlen($password) < 8) {
        return "Password must be at least 8 characters long.";
    }


    if (!preg_match('/[a-z]/', $password)) {
        return "Password must include at least one lowercase letter.";
    }

    if (!preg_match('/[0-9]/', $password)) {
        return "Password must include at least one number.";
    }

    if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
        return "Password must include at least one symbol.";
    }

    return true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <link rel="stylesheet" href="css/style.css">

    <script>
        function validatePasswordInput() {
            var newPassword = document.getElementById("new_password").value;

            var messages = [];

            if (newPassword.length < 8) {
                messages.push("Password must be at least 8 characters long.");
            }

            if (!/[a-z]/.test(newPassword)) {
                messages.push("Password must include at least one lowercase letter.");
            }

            if (!/[0-9]/.test(newPassword)) {
                messages.push("Password must include at least one number.");
            }

            if (!/[^a-zA-Z0-9]/.test(newPassword)) {
                messages.push("Password must include at least one symbol.");
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
if (isset($message)) {
    foreach ($message as $message) {
        echo '
        <div class="message">
            <span>' . htmlspecialchars($message) . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>

<section class="form-container">
    <form method="post" onsubmit="return validatePasswordInput()">
        <h3>Password Reset</h3>
        <input type="password" id="new_password" name="new_password" class="box" placeholder="Enter your new password" required>
        <input type="submit" class="btn" name="submit" value="Reset Password">
        <p>Remember your password? <a href="login.php">Login now</a></p>
    </form>
</section>

<?php @include 'footer.php'; ?>

</body>
</html>
