<?php
session_start();
@include 'config.php';

$message = [];

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $select_user = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'") or die('query failed');

    if (mysqli_num_rows($select_user) > 0) {
      
        $reset_token = bin2hex(random_bytes(32));

        mysqli_query($conn, "UPDATE users SET reset_token = '$reset_token' WHERE email = '$email'") or die('query failed');

        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_token'] = $reset_token;
        header('location: reset_password.php');
        exit();
    } else {
        $message[] = 'Invalid email!';
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

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.htmlspecialchars($message).'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<section class="form-container">
   <form method="post">
      <h3>Forgot Password</h3>
      <input type="email" name="email" class="box" placeholder="Enter your email" required>
      <input type="submit" class="btn" name="submit" value="Reset Password">
      <p>Remember your password? <a href="login.php">Login now</a></p>
   </form>
</section>
<?php @include 'footer.php'; ?>

</body>
</html>
