<?php
require_once __DIR__ . '/../../backend/dbconnection.php';

session_start();

// Redirect if already logged in
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header('Location: admin_index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['adminName']) ? trim($_POST['adminName']) : '';
    $password = isset($_POST['adminpassword']) ? $_POST['adminpassword'] : '';

    if ($email === '' || $password === '') {
        $error = 'Please enter both email and password.';
 } else {
        try {
            $stmt = $conn->prepare('SELECT user_id, email, password, role FROM users WHERE email = :email LIMIT 1');
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            if ($user) {
                // Get the passwords
                $inputPassword = $password;
                $storedPassword = $user['password'];

                // VALIDATION: Check for Hash OR Plain Text (for your current DB state)
                $isValid = false;
                if (password_verify($inputPassword, $storedPassword)) {
                    $isValid = true;
                } elseif ($inputPassword === $storedPassword) {
                    $isValid = true;
                }

                if ($isValid) {
                    if ($user['role'] === 'admin') {
                        session_regenerate_id();
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['role'] = $user['role'];
                        header('Location: admin_index.php');
                        exit;
                    } else {
                        $error = 'Access Denied: You are logged in as ' . $user['role'] . '.';
                    }
                } else {
                    $error = 'Invalid password.';
                }
            } else {
                $error = 'No account found with that email.';
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Login - DormDash</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #114fac;
            --error-bg: #fff1f0;
            --error-text: #cf1322;
            --border-color: #d9d9d9;
        }

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
            min-height: 100vh;
        }

        .navbar {
            background: #2B469A;
            padding: 1rem 5%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .nav-brand a {
            text-decoration: none;
            font-weight: bold;
            display: flex;
            align-items: center;
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
            gap: 10px;
        }

        .nav-links a {
            color: white;
        }

        .nav-links a:hover {
            color: white;
            background-color: rgba(17, 79, 172, 0.05);
            border-color: var(--primary-color);
        }

        .nav-logo { height: 40px; width: auto; }

        .login-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .admin-box { 
            width: 100%;
            max-width: 400px; 
            padding: 40px; 
            background: #fff; 
            border-radius: 12px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }

        h1 { 
            margin: 0 0 24px; 
            font-size: 24px; 
            font-weight: 600; 
            color: #1a1a1a;
            text-align: center;
        }

        .form-row { margin-bottom: 20px; }

        label { 
            display: block; 
            margin-bottom: 8px; 
            font-size: 14px; 
            font-weight: 500;
            color: #4a4a4a;
        }

        input[type=text], input[type=password] { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid var(--border-color); 
            border-radius: 8px; 
            font-size: 14px;
            box-sizing: border-box;
            transition: border 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(17, 79, 172, 0.1);
        }

        .btn-login { 
            background: var(--primary-color); 
            color: #fff; 
            padding: 12px; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            width: 100%; 
            font-size: 16px;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        .btn-login:hover { background: #0d3d85; }

        .error-msg { 
            background: var(--error-bg); 
            color: var(--error-text); 
            padding: 12px; 
            border: 1px solid #ffa39e; 
            border-radius: 8px; 
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">
            <a href="homepage.php">
                <img src="../../res/logo1.png" alt="logo" class="nav-logo">
                <span>UEP DORMDASH</span>
            </a>
        </div>
        <div class="nav-links">
            <a href="../loginForms/renter/login.php">Return Back <i class="fas fa-chevron-right" style="font-size: 10px; margin-left: 5px;"></i></a>
        </div>
    </nav>

    <div class="login-container">
        <div class="admin-box">
            <h1>ADMIN LOGIN</h1>
            
            <?php if (!empty($error)): ?>
                <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" action="admin_login.php">
                <div class="form-row">
                    <label for="adminName">Email</label>
                    <input type="text" id="adminName" name="adminName" placeholder="Enter email" required>
                </div>
                <div class="form-row">
                    <label for="adminpassword">Password</label>
                    <input type="password" id="adminpassword" name="adminpassword" placeholder="Enter password" required>
                </div>
                <button class="btn-login" type="submit">Sign In</button>
            </form>
        </div>
    </div>
</body>
</html>