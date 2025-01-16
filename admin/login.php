<?php
session_start();
include('../include/db.php'); // Include database connection

if (isset($_POST['submit'])) {
    // Initialize error array
    $error = array();

    // Check for empty email
    if (empty($_POST['email'])) {
        $error['email'] = "Enter Email";
    }

    // Check for empty password
    if (empty($_POST['hash'])) {
        $error['hash'] = "Enter password";
    }

    // If no errors, check credentials in the database
    if (empty($error)) {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE email = :em");
        $stmt->bindParam(":em", $_POST['email']);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_BOTH);

        // Check if user exists and password is correct
        if ($stmt->rowCount() > 0 && password_verify($_POST['hash'], $row['hash'])) {
            // Set session variables upon successful login
            $_SESSION['admin_id'] = $row['admin_id'];
            $_SESSION['admin_name'] = $row['name'];

            // Set success message and redirect to dashboard
            $_SESSION['success_message'] = 'Login successful!';
            header("Location: dashboard.php");
            exit();
        } else {
            // If credentials are incorrect, redirect with error
            header("Location: login.php?error=Invalid email or password");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .login-header {
            background-color: #343a40;
            color: #fff;
            text-align: center;
            padding: 15px;
            border-radius: 8px 8px 0 0;
        }
        .btn-primary {
            background-color: #343a40;
            border: none;
        }
        .btn-primary:hover {
            background-color: #495057;
        }
        .form-control:focus {
            border-color: #ffc107;
            box-shadow: 0 0 5px #ffc107;
        }
        .alert {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-header">
        <h2>Admin Login</h2>
    </div>
    
    <!-- Show Success Message if it exists in the session -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success_message']); ?>
        </div>
        <?php unset($_SESSION['success_message']); ?> <!-- Clear the session message -->
    <?php endif; ?>

    <!-- Show Error Message if passed via URL -->
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <form action="" method="post" class="p-3">
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="mb-3">
            <label for="hash" class="form-label">Password</label>
            <input type="password" name="hash" id="hash" class="form-control" placeholder="Enter your password" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>

<script>
    // Focus effect for form fields
    document.getElementById('email').addEventListener('focus', function() {
        this.style.backgroundColor = '#fef9e7';
    });
    document.getElementById('email').addEventListener('blur', function() {
        this.style.backgroundColor = '';
    });

    document.getElementById('hash').addEventListener('focus', function() {
        this.style.backgroundColor = '#fef9e7';
    });
    document.getElementById('hash').addEventListener('blur', function() {
        this.style.backgroundColor = '';
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>
</html>
