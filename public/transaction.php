<?php
session_start();

include('../include/user_auth.php');
include('../include/db.php');
include('../include/user_info.php');

// Assuming the user's customer ID is stored in the session
// $customerId = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : '';

// if ($customerId) {
    $stmt = $conn->prepare("SELECT receivers_account, transaction_amount, transaction_type, date_created, time_created FROM transactions WHERE customer_id = :customer_id ORDER BY created_at DESC LIMIT 10");
    $stmt->execute([':customer_id' => $customerId]);
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
// } else {
//     $transactions = [];
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Recent Transactions</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
        }
        .dashboard-header {
            background-color: #343a40;
            color: #fff;
            text-align: center;
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }
        .btn-custom {
            background-color: #343a40;
            border: none;
            color: #fff;
        }
        .btn-custom:hover {
            background-color: #495057;
        }
        .container {
            margin-top: 50px;
        }
        .table th {
            background-color: #343a40;
            color: #f8f9fa;
        }
        .table td {
            background-color: #f8f9fa;
            color: #343a40;
        }
        .alert-custom {
            background-color: #ffc107;
            color: #000;
        }
    </style>
</head>
<body>

<?php include('../include/user_header.php'); ?>

<div class="container">
    <div class="dashboard-header">
        <h2>Recent Transactions</h2>
    </div>

    <div class="alert alert-custom" role="alert">
        View your most recent transactions below.
    </div>

    <?php if ($customerId && empty($transactions)): ?>
        <p class="text-danger">No transactions found for your account.</p>
    <?php elseif (!empty($transactions)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Receiver's Account</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($transaction['receivers_account']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['transaction_amount']); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($transaction['transaction_type'])); ?></td>
                        <td><?php echo htmlspecialchars($transaction['date_created']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['time_created']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Bootstrap JS and Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<script>
    // Example of simple interaction: Change background color when table rows are hovered
    const rows = document.querySelectorAll('table tbody tr');
    rows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            row.style.backgroundColor = '#e9ecef';
        });
        row.addEventListener('mouseleave', function() {
            row.style.backgroundColor = '';
        });
    });
</script>

</body>
</html>
