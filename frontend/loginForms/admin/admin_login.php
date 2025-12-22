<?php
// admin_login.php - Admin login page and handler
// Renders login form on GET; authenticates on POST using admin_acc table.
require_once __DIR__ . '/dbcon.php'; // provides $pdo

// Decide whether to show the create-admin form
$showCreate = isset($_GET['action']) && $_GET['action'] === 'create';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // If create_admin is present, handle admin creation/update
    if (isset($_POST['create_admin'])) {
        $cname = isset($_POST['adminName']) ? trim($_POST['adminName']) : '';
        $cpass = isset($_POST['adminpassword']) ? $_POST['adminpassword'] : '';
        if ($cname === '' || $cpass === '') {
            $create_error = 'Name and password are required to create admin.';
        } else {
            try {
                // ensure table exists
                $pdo->exec("CREATE TABLE IF NOT EXISTS admin_acc (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    adminName VARCHAR(45) NOT NULL,
                    adminpassword VARCHAR(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

                $hash = password_hash($cpass, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare('SELECT id FROM admin_acc WHERE adminName = :name LIMIT 1');
                $stmt->execute([':name' => $cname]);
                $row = $stmt->fetch();
                if ($row) {
                    $update = $pdo->prepare('UPDATE admin_acc SET adminpassword = :pass WHERE id = :id');
                    $update->execute([':pass' => $hash, ':id' => $row['id']]);
                    $create_success = 'Admin updated successfully.';
                } else {
                    $ins = $pdo->prepare('INSERT INTO admin_acc (adminName, adminpassword) VALUES (:name, :pass)');
                    $ins->execute([':name' => $cname, ':pass' => $hash]);
                    $create_success = 'Admin created successfully.';
                }
            } catch (PDOException $e) {
                $create_error = 'DB error: ' . $e->getMessage();
            }
        }
    } else {
        // Normal login flow
        $name = isset($_POST['adminName']) ? trim($_POST['adminName']) : '';
        $password = isset($_POST['adminpassword']) ? $_POST['adminpassword'] : '';

        if ($name === '' || $password === '') {
            $error = 'Missing admin name or password.';
        } else {
            try {
                $stmt = $pdo->prepare('SELECT id, adminName, adminpassword FROM admin_acc WHERE adminName = :name LIMIT 1');
                $stmt->execute([':name' => $name]);
                $row = $stmt->fetch();
                if (!$row) {
                    $error = 'Invalid admin name or password.';
                } else {
                    $hash = $row['adminpassword'];
                    if (password_verify($password, $hash)) {
                        session_start();
                        $_SESSION['admin_id'] = $row['id'];
                        $_SESSION['admin_name'] = $row['adminName'];
                        header('Location: admin_dashboard.php');
                        exit;
                    } else {
                        $error = 'Invalid admin name or password.';
                    }
                }
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
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
    <link rel="stylesheet" href="css/login.css">
    <style>
        .admin-box {
            max-width: 420px;
            margin: 120px auto;
            padding: 28px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 8px 28px rgba(0, 0, 0, .08)
        }

        .admin-box h1 {
            margin: 0 0 12px
        }

        .form-row {
            margin-bottom: 12px
        }

        label {
            display: block;
            margin-bottom: 6px
        }

        input[type=text],
        input[type=password] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px
        }

        .btn-login {
            background: #114fac;
            color: #fff;
            padding: 10px 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer
        }

        .error-msg {
            background: #ffe6e6;
            color: #c0392b;
            padding: 10px;
            border: 1px solid #f1c0c0;
            border-radius: 6px;
            margin-bottom: 12px
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="nav-brand"><a href="homepage.php"><img src="res/logo1.png" alt="logo" class="nav-logo" /><span>UEP DORMDASH</span></a></div>
        <div class="nav-links"><a href="homepage.php">Home</a></div>
    </nav>

    <div class="admin-box">
        <h1>Admin Login</h1>
        <?php if (!empty($error)): ?>
            <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (!empty($create_error)): ?>
            <div class="error-msg"><?php echo htmlspecialchars($create_error); ?></div>
        <?php endif; ?>
        <?php if (!empty($create_success)): ?>
            <div style="background:#e6ffed;color:#1b7a2a;padding:10px;border:1px solid #cfead1;border-radius:6px;margin-bottom:12px"><?php echo htmlspecialchars($create_success); ?></div>
        <?php endif; ?>

        <?php if ($showCreate): ?>
            <form method="post" action="admin_login.php">
                <input type="hidden" name="create_admin" value="1">
                <div class="form-row">
                    <label for="adminName">Admin Name</label>
                    <input type="text" id="adminName" name="adminName" required>
                </div>
                <div class="form-row">
                    <label for="adminpassword">Password</label>
                    <input type="password" id="adminpassword" name="adminpassword" required>
                </div>
                <button class="btn-login" type="submit">Create / Update Admin</button>
            </form>
            <p style="margin-top:12px"><a href="admin_login.php">Back to login</a></p>
            
        <?php else: ?>
            <form method="post" action="admin_login.php">
                <div class="form-row">
                    <label for="adminName">Admin Name</label>
                    <input type="text" id="adminName" name="adminName" required>
                </div>
                <div class="form-row">
                    <label for="adminpassword">Password</label>
                    <input type="password" id="adminpassword" name="adminpassword" required>
                </div>
                <button class="btn-login" type="submit">Sign In</button>
            </form>
        <?php endif; ?>
    </div>

</body>

</html>