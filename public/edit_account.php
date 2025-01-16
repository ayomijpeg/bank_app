<?php
session_start();
include('../include/user_auth.php'); // Ensure admin authentication
include('../include/db.php'); // Include the database connection
include('../include/user_info.php');

// Fetch account details based on customer ID from session or GET request
if (isset($_GET['id'])) {
    $customerId = $_GET['id'];
} elseif (isset($_SESSION['customer_id'])) {
    $customerId = $_SESSION['customer_id'];
} else {
    echo "<div class='alert alert-danger'>Invalid account ID. Please provide a valid ID.</div>";
    exit();
}

try {
    $stmt = $conn->prepare("SELECT * FROM customer WHERE customer_id = :id");
    $stmt->execute([':id' => $customerId]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$account) {
        echo "<div class='alert alert-danger'>Account not found.</div>";
        exit();
    }
} catch (PDOException $e) {
    die("Error fetching account: " . $e->getMessage());
}

// Handle account update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    try {
        $updateStmt = $conn->prepare("UPDATE customer SET 
            account_name = :account_name,
            account_balance = :account_balance,
            account_type = :account_type,
            updated_at = NOW()
            WHERE customer_id = :id");

        $updateStmt->execute([
            ':account_name' => $_POST['account_name'],
            ':account_balance' => $_POST['account_balance'],
            ':account_type' => $_POST['account_type'],
            ':id' => $customerId
        ]);

        header("Location: view_accounts.php?message=Account updated successfully");
        exit();
    } catch (PDOException $e) {
        die("Error updating account: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header {
            background-color: #343a40;
            color: #fff;
            padding: 15px;
        }
        .header h1 {
            margin: 0;
        }
        .form-container {
            max-width: 600px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #343a40;
            border: none;
        }
        .btn-primary:hover {
            background-color: #ffc107;
            color: #343a40;
        }
    </style>
</head>
<body>
    <?php include('../include/user_header.php') ?>
    <!-- Header -->
    <div class="header text-center">
        <h1>Edit Account Details</h1>
    </div>

    <div class="container form-container">
        <form method="POST">
            <div class="mb-3">
                <label for="account_name" class="form-label">Account Name</label>
                <input type="text" class="form-control" id="account_name" name="account_name" value="<?= htmlspecialchars($account['account_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="account_balance" class="form-label">Account Balance</label>
                <input type="number" step="0.01" class="form-control" id="account_balance" name="account_balance" value="<?= htmlspecialchars($account['account_balance']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="account_type" class="form-label">Account Type</label>
                <select id="account_type" name="account_type" class="form-select" required>
                    <option value="Savings" <?= $account['account_type'] === 'Savings' ? 'selected' : ''; ?>>Savings</option>
                    <option value="Current" <?= $account['account_type'] === 'Current' ? 'selected' : ''; ?>>Current</option>
                </select>
            </div>
            <button type="submit" name="update" class="btn btn-primary w-100">Update Account</button>
        </form>
    </div>

    <!-- Bootstrap JS and Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
