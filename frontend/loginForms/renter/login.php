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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
	<style>
	        body { 
            font-family: 'Inter', sans-serif; 
            background-image: url(../../res/kalabaw2.jpeg);
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat;  
            background-attachment: fixed;
            margin: 0; 
            display: flex; 
            flex-direction: column; 
            min-height: 110vh;
			}

			.social-container a.social-btn {
			text-decoration: none;
			color: inherit;        
			display: flex;        
			justify-content: center;
			align-items: center;
			}

			.forgot-link {
			color: #1009ebff;    
			text-decoration: none; 
			font-size: 14px;      
			float: right;           
			}

			.forgot-link:hover {
    		text-decoration: underline; 
			}

			.modal {
				display: none; 
				position: fixed;
				z-index: 1000;
				left: 0; top: 0;
				width: 100%; height: 100%;
				background-color: rgba(0,0,0,0.5); 
			}

			.modal-content {
				background-color: white;
				margin: 15% auto;
				padding: 20px;
				border-radius: 10px;
				width: 300px;
				text-align: center;
				position: relative;
			}

			.close-btn {
				position: absolute;
				right: 15px; top: 10px;
				font-size: 20px;
				cursor: pointer;
				color: #888;
			}
	</style>

<!-- Top navigation -->
<nav class="navbar">
	<div class="nav-brand"><img src="../../res/logo1.png" alt="UEP logo" class="nav-logo"/><span>UEP DORMDASH</span></div>
	<div class="nav-links">
		<a href="../../admin_ui/admin_login.php">Admin</a>
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
		<a href="javascript:void(0);" class="forgot-link" onclick="openModal()">Forgot Password?</a>

		<button type="submit" class="btn-login">Sign in</button>
	</form>
		<div class="social-container">
			<a href="https://accounts.google.com" target="_blank" class="social-btn google">
				<i class="fab fa-google"></i>
			</a>

			<a href="https://m.me/yourusername" target="_blank" class="social-btn messenger">
				<i class="fab fa-facebook-messenger"></i>
			</a>

			<a href="https://www.facebook.com/yourprofile" target="_blank" class="social-btn facebook">
				<i class="fab fa-facebook-f"></i>
			</a>
		</div>

	<div class="links">
		Don't have an account? <a href="create.php">Register for free</a>
	</div>
</div>

<div id="forgotModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        
        <div id="verifyStep">
            <h3>Reset Password</h3>
            <p>Enter your registered email.</p>
            <input type="email" id="resetEmail" placeholder="Email Address" required class="modal-input">
            <button type="button" onclick="verifyEmail()" class="btn-reset">Verify Email</button>
        </div>

        <div id="resetStep" style="display:none;">
            <h3>Create New Password</h3>
            <p>Enter your new password below.</p>
            <input type="password" id="newPass" placeholder="New Password" class="modal-input">
            <input type="password" id="confirmPass" placeholder="Confirm Password" class="modal-input">
            <button type="button" onclick="updatePassword()" class="btn-reset">Update Password</button>
        </div>
        
        <p id="modalMsg" style="margin-top:10px; font-size:13px;"></p>
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

		//FORGOT PASS SCRIPT

		function openModal() {
			// This finds the div with id="forgotModal" and makes it visible
			var modal = document.getElementById("forgotModal");
			if (modal) {
				modal.style.display = "block";
			} else {
				console.error("Modal element not found!");
			}
		}

		function closeModal() {
			document.getElementById("forgotModal").style.display = "none";
		}

		// Close modal if user clicks outside the box
		window.onclick = function(event) {
			var modal = document.getElementById("forgotModal");
			if (event.target == modal) {
				modal.style.display = "none";
			}
		}


		function verifyEmail() {
			const email = document.getElementById('resetEmail').value;
			const msg = document.getElementById('modalMsg');

			// Use Fetch to check email in a small background script
			fetch('check_email2.php', {
				method: 'POST',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				body: 'email=' + encodeURIComponent(email)
			})
			.then(response => response.text())
			.then(data => {
				if(data === 'exists') {
					document.getElementById('verifyStep').style.display = 'none';
					document.getElementById('resetStep').style.display = 'block';
					msg.innerText = "";
				} else {
					msg.style.color = "red";
					msg.innerText = "Email not found.";
				}
			});
		}

		function updatePassword() {
			const pass = document.getElementById('newPass').value;
			const conf = document.getElementById('confirmPass').value;
			const email = document.getElementById('resetEmail').value;

			if(pass !== conf) { alert("Passwords don't match!"); return; }

			fetch('update_pass_logic2.php', {
				method: 'POST',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				body: `email=${email}&pass=${pass}`
			})
			.then(() => {
				alert("Password Updated Successfully!");
				closeModal();
			});
		}
</script>

</body>
</html>