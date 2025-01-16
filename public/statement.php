<?php

session_start();
// var_dump(value: $session_start);
include('../include/user_auth.php');
include('../include/db.php');
include('../include/user_info.php');

// Check customer_id value
if (isset($customer_id)) {
    echo "Customer ID: " . $customer_id;
} else {
    echo "No customer ID found.";
}

// Fetch transactions
try {
    $stmt = $conn->prepare("SELECT * FROM transactions WHERE customer_id = :cid");
    $stmt->bindParam(":cid", $customer_id, PDO::PARAM_INT);
    $stmt->execute();
    $statement_record = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($statement_record)) {
        echo "No transactions found for this customer.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Statement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<?php include('../include/user_header.php') ?>

<div class="container mt-5">
    <h2 class="text-center">Account Statement</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Senders Account</th>
                <th>Receivers Account</th>
                <th>Transaction Amount</th>
                <th>Previous Balance</th>
                <th>Final Balance</th>
                <th>Transaction Type</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($statement_record)): ?>
                <tr><td colspan="8">No transactions found.</td></tr>
            <?php else: ?>
                <?php foreach($statement_record as $value): ?>
                    <tr>
                        <td><?= htmlspecialchars($value['senders_account']) ?></td>
                        <td><?= htmlspecialchars($value['receivers_account']) ?></td>
                        <td><?= htmlspecialchars($value['transaction_amount']) ?></td>
                        <td><?= htmlspecialchars($value['previous_balance']) ?></td>
                        <td><?= htmlspecialchars($value['final_balance']) ?></td>
                        <td><?= strtoupper(htmlspecialchars($value['transaction_type'])) ?></td>
                        <td><?= htmlspecialchars($value['date_created']) ?></td>
                        <td><?= htmlspecialchars($value['time_created']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>
</html>
