<?php
include '../include/db.php';

if (isset($_POST['submit'])) {
    $error = array();
    if (empty($_POST['name'])) {
        $error['name'] = "Enter Name";
    }
    if (empty($_POST['email'])) {
        $error['email'] = "Enter Email";
    } else {
        $statement = $conn->prepare("SELECT * FROM admin WHERE email=:em");
        $statement->bindParam(":em", $_POST['email']);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            $error['email'] = "Email already exists";
        }
    }

    if (empty($_POST['hash'])) {
        $error['hash'] = "Enter Password";
    }
    if (empty($_POST['confirm_hash'])) {
        $error['confirm_hash'] = "Confirm Password";
    } elseif ($_POST['hash'] !== $_POST['confirm_hash']) {
        $error['confirm_hash'] = "Password Mismatch";
    }

    if (empty($error)) {
        $encrypted = password_hash($_POST['hash'], PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO admin VALUES (NULL, :nm, :em, :hsh, NOW(), NOW())");
        $data = array(
            ":nm" => $_POST['name'],
            ":em" => $_POST['email'],
            ":hsh" => $encrypted
        );
        $stmt->execute($data);
        header("Location:login.php?success=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .signup-container {
            max-width: 500px;
            margin: 50px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        .signup-header {
            background-color: #343a40;
            color: #fff;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
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
        .error-message {
            display: none;
            margin-bottom: 15px;
        }
        .alert-success {
            background-color: #28a745;
            color: #fff;
            display: none;
        }
        .alert-danger {
            background-color: #dc3545;
            color: #fff;
            display: none;
        }
    </style>
</head>
<body>

<div class="signup-container">
    <div class="signup-header">
        <h2>Admin Signup</h2>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Registration successful! Redirecting to login...</div>
        <script>
            setTimeout(function() {
                window.location.href = 'login.php';
            }, 3000);
        </script>
    <?php endif; ?>

    <form action="" method="post">
        <?php if (isset($error['name'])): ?>
            <div class="alert alert-danger">
                <?php echo $error['name']; ?>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Enter your name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>" required>
        </div>

        <?php if (isset($error['email'])): ?>
            <div class="alert alert-danger">
                <?php echo $error['email']; ?>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
        </div>

        <?php if (isset($error['hash'])): ?>
            <div class="alert alert-danger">
                <?php echo $error['hash']; ?>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="hash" class="form-label">Password</label>
            <input type="password" name="hash" id="hash" class="form-control" placeholder="Enter your password" required>
        </div>

        <?php if (isset($error['confirm_hash'])): ?>
            <div class="alert alert-danger">
                <?php echo $error['confirm_hash']; ?>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="confirm_hash" class="form-label">Confirm Password</label>
            <input type="password" name="confirm_hash" id="confirm_hash" class="form-control" placeholder="Confirm your password" required>
        </div>

        <button type="submit" name="submit" class="btn btn-primary w-100">Sign Up</button>
    </form>
</div>

<!-- Bootstrap JS and Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<script>
    // Display error messages in a dropdown format after form submission
    const errorMessages = document.querySelectorAll('.alert-danger');
    errorMessages.forEach((message) => {
        message.style.display = 'block';
        setTimeout(() => {
            message.style.display = 'none';
        }, 5000);
    });
</script>

</body>
</html>
