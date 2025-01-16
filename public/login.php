<?php
session_start();
var_dump($_SESSION); // For debugging, to check session values
include '../include/db.php';

if (isset($_POST['submit'])) {
    $error = [];
    if (empty($_POST['account_number'])) {
        $error['account_number'] = "Please Enter Account Number";
    }
    if (!is_numeric($_POST['account_number'])) {
        $error['account_number'] = "Please Enter a Numeric Value";
    }

    if (empty($error)) {
        $statement = $conn->prepare("SELECT customer_id, account_name, account_number FROM customer WHERE account_number = :an");
        $statement->bindParam(":an", $_POST['account_number'], PDO::PARAM_STR);
        $statement->execute();
    }
       $statement->execute();

        if ($statement->rowCount() > 0) {
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            $_SESSION['customer_id'] = $row['customer_id'];  // Store customer_id in session
            $_SESSION['account_name'] = $row['account_name']; // Store account_name in session
            $_SESSION['account_number'] = $row['account_name']; // Store account_name in session
            var_dump($row); // For debugging, to check the data being fetched
            header("Location: dashboard.php");
            exit();
        } else {
            header("Location: login.php?error=Incorrect Account Number");
            exit();
        }
    }

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Login</title>
    <!-- Add Bootstrap for styling -->
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
        .btn-custom {
            background-color: #343a40;
            border: none;
            color: #fff;
        }
        .btn-custom:hover {
            background-color: #495057;
        }
        .form-control:focus {
            border-color: #ffc107;
            box-shadow: 0 0 5px #ffc107;
        }
        .error-message {
            color: red;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2 class="text-center">Swap Bank Login</h2>
    <hr/>
    
    <?php
    // Display error message if any
    if (isset($_GET['error'])) {
        echo "<div class='alert alert-danger'>" . htmlspecialchars($_GET['error']) . "</div>";
    }
    ?>

    <form action="" method="post">
        <!-- Display account number error if any -->
        <?php if (isset($error['account_number'])): ?>
            <div class="alert alert-danger"><?= $error['account_number']; ?></div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="account_number" class="form-label">Account Number</label>
            <input type="text" name="account_number" class="form-control" id="account_number" value="<?= isset($_POST['account_number']) ? htmlspecialchars($_POST['account_number']) : ''; ?>" required>
        </div>
        
        <button type="submit" name="submit" class="btn btn-custom w-100">Login</button>
    </form>
</div>

<!-- Bootstrap JS and Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>
</html>


