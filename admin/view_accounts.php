<?php
session_start();
include('../include/admin_auth.php'); // Ensure admin authentication
include('../include/db.php'); // Include the database connection

// Handle deletion of an account
if (isset($_GET['delete_id'])) {
    try {
        $deleteStmt = $conn->prepare("DELETE FROM customer WHERE customer_id = :cid");
        $deleteStmt->bindParam(':cid', $_GET['delete_id'], PDO::PARAM_INT);
        $deleteStmt->execute();
        header("Location: view_accounts.php?message=Account deleted successfully");
        exit();
    } catch (PDOException $e) {
        die("Error deleting account: " . $e->getMessage());
    }
}

// Fetch all accounts
try {
    $stmt = $conn->prepare("SELECT * FROM customer");
    $stmt->execute();
    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching accounts: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Accounts</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include('../include/admin_header.php'); ?>

    <div class="container mt-5">
        <h1 class="text-center">Customer Accounts</h1>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <table class="table table-bordered table-striped mt-4">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Account Name</th>
                    <th>Account Number</th>
                    <th>Account Type</th>
                    <th>Account Balance</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($accounts)): ?>
                    <?php foreach ($accounts as $index => $account): ?>
                        <tr>
                            <td><?= $index + 1; ?></td>
                            <td><?= htmlspecialchars($account['account_name']); ?></td>
                            <td><?= htmlspecialchars($account['account_number']); ?></td>
                            <td><?= htmlspecialchars($account['account_type']); ?></td>
                            <td><?= htmlspecialchars(number_format($account['account_balance'], 2)); ?></td>
                            <td><?= htmlspecialchars($account['created_at']); ?></td>
                            <td><?= htmlspecialchars($account['updated_at']); ?></td>
                            <td>
                                <a href="update_account.php?id=<?= $account['customer_id']; ?>" class="btn btn-primary btn-sm">Update</a>
                                <a href="view_accounts.php?delete_id=<?= $account['customer_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this account?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No accounts found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS and Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
