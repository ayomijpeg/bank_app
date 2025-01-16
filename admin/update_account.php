<?php
session_start();
include('../include/admin_auth.php'); // Ensure admin authentication
include('../include/db.php'); // Include the database connection

if (!isset($_GET['id'])) {
    header("Location: view_accounts.php?message=Invalid account ID");
    exit();
}

// Fetch account details
try {
    $stmt = $conn->prepare("SELECT * FROM customer WHERE customer_id = :cid");
    $stmt->bindParam(':cid', $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    $account = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$account) {
        header("Location: view_accounts.php?message=Account not found");
        exit();
    }
} catch (PDOException $e) {
    die("Error fetching account: " . $e->getMessage());
}

// Handle account update
if (isset($_POST['update'])) {
    try {
        $updateStmt = $conn->prepare("UPDATE customer SET 
            account_name = :account_name,
            account_balance = :account_balance,
            account_type = :account_type,
            updated_at = NOW()
            WHERE id = :id");

        $updateStmt->execute([
            ':account_name' => $_POST['account_name'],
            ':account_balance' => $_POST['account_balance'],
            ':account_type' => $_POST['account_type'],
            ':id' => $_GET['id']
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
    <title>Update Account</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include('../include/admin_header.php'); ?>

    <div class="container mt-5">
        <h1 class="text-center">Update Account</h1>

        <form method="POST" class="mt-4">
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
            <button type="submit" name="update" class="btn btn-primary">Update Account</button>
        </form>
    </div>

    <!-- Bootstrap JS and Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
