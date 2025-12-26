<?php
// Start session at the very top
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($email === '' || $password === '') {
        header('Location: login.php?error=empty_fields');
        exit;
    }

    require_once 'C:/xampp/htdocs/WEB101-PROJECT_BSIT3-D_DORMDASHUEP/backend/dbconnection.php';

    try {
        $stmt = $conn->prepare("SELECT user_id, password, role FROM users WHERE email = :email AND role = 'renter' LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id();
            
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            
            header('Location: ../homepage.php');
            exit;
        } else {
            header('Location: login.php?error=invalid_credentials');
            exit;
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        header('Location: login.php?error=server_error');
        exit;
    }
}
// If not POST, render the login HTML page below
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>UEP DORMDASH - Login</title>
	<link rel="stylesheet" href="../css/login2.css" />
</head>
<body>

<!-- Top navigation -->
<nav class="navbar">
	<div class="nav-brand"><a href="../admin_login.php"><img src="../../res/logo1.png" alt="UEP logo" class="nav-logo"/><span>UEP DORMDASH</span></a></div>
	<div class="nav-links">
		<a href="../homepage.php">Home</a>
		<a href="login.php">Renter Login</a>
		<a href="../owner/ownerlogin.php">Owner Login</a>
	</div>
</nav>

<div class="container">
	<img src="../../res/logo1.png" alt="ueplogo" class="logo">
	<h1>UEP DORMDASH</h1>

	<h2>Renter Login</h2>

	<form action="login.php" method="POST">

		<label>Email</label>
		<input type="email" name="email" placeholder="username@gmail.com" required />

		<label>Password</label>
		<input type="password" name="password" placeholder="Password" required />
		<a class="forgot" href="#">Forgot Password?</a>

		<button type="submit" class="btn-login">Sign in</button>
	</form>

	<div class="social-container">
		<div class="social-btn google"><i class="fab fa-google"></i></div>
		<div class="social-btn messenger"><i class="fab fa-facebook-messenger"></i></div>
		<div class="social-btn facebook"><i class="fab fa-facebook-f"></i></div>
	</div>

	<div class="links">
		Don't have an account? <a href="create.php">Register for free</a>
	</div>
</div>

<!-- Toast notification container -->
<div id="toast">Account created successfully</div>

<script>
// Show toast if URL contains created=1 or error=1
document.addEventListener('DOMContentLoaded', function () {
	const params = new URLSearchParams(window.location.search);
	const toast = document.getElementById('toast');
	if (!toast) return;

	function showToast(text, isError = false) {
		toast.textContent = text;
		if (isError) toast.classList.add('error');
		toast.classList.add('show');
		setTimeout(() => {
			toast.classList.remove('show');
			if (isError) toast.classList.remove('error');
			// remove the query param cleanly
			const url = new URL(window.location);
			url.searchParams.delete('created');
			url.searchParams.delete('error');
			window.history.replaceState({}, '', url);
		}, 3000);
	}

	if (params.get('created') === '1') {
		showToast('Account created successfully', false);
	} else if (params.get('error') === '1') {
		showToast('Invalid email or password', true);
	}
});
</script>

</body>
</html>