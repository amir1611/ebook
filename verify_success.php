<?php
session_start();

if (isset($_SESSION['verify_email']) && isset($_SESSION['verify_token'])) :
?>
	<!DOCTYPE html>
	<html lang="en" class="h-100">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Email Verification Successful</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	</head>

	<body class="h-100">
		<div class="container h-100">

			<div class="row h-100">
				<div class="col-12 d-flex justify-content-center align-items-center">
					<div class="row">
						<div class="col-12 text-center">
							<h1>
								<?php echo $_SESSION['verify_email'];
								?> has been verified.
							</h1>
						</div>
						<div class="col-12 text-center">
							<a class="btn btn-primary" href="login.php">Login Now</a>
						</div>
					</div>

				</div>
			</div>
		</div>
	</body>

	</html>
<?php
	unset($_SESSION['verify_email']);
	unset($_SESSION['verify_token']);
else :
	header('location: login.php');
endif;
?>