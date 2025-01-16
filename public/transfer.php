<?php
session_start();

include('../include/user_auth.php');
include('../include/db.php');
include('../include/user_info.php');

if(isset($_POST['pay'])){
    $issue = [];
    if(empty($_POST['account_number'])){
        $issue['account_number'] = "Please Enter Account Number";
    } elseif(!is_numeric($_POST['account_number'])){
        $issue['account_number'] = "Please enter a numeric value";
    }
    
    if(empty($_POST['amount'])){
        $issue['amount'] = "Please Specify Amount";
    } elseif(!is_numeric($_POST['amount'])){
        $issue['amount'] = "Enter a numeric value";
    }

    if(empty($issue)){
        // Check if current user has enough funds
        if($_POST['amount'] > $current_users_data['account_balance']){
            header("Location:transfer.php?error=Insufficient Funds");
            exit();                    
        }

        $fetch_beneficiary = $conn->prepare("SELECT * FROM customer WHERE account_number = :an");
        $fetch_beneficiary->bindParam(":an", $_POST['account_number']);
        $fetch_beneficiary->execute();

        if($fetch_beneficiary->rowCount() < 1){
            header("Location:transfer.php?error=Account Number Does Not Exist");
            exit();
        }

        $beneficiary_record = $fetch_beneficiary->fetch(PDO::FETCH_BOTH);

        // Check if user is trying to send money to themselves
        if($current_users_data['customer_id'] == $beneficiary_record['customer_id']){
            header("Location:transfer.php?error=You Cannot Send Funds to Yourself");
            exit();
        }

        $senders_open_balance = $current_users_data['account_balance'];
        $senders_closing_balance = $senders_open_balance - $_POST['amount'];
        $debit = $conn->prepare("UPDATE customer SET account_balance = :ab WHERE account_number = :cua");
        $debit->bindParam(":ab", $senders_closing_balance);
        $debit->bindParam(":cua", $current_users_data['account_number']);
        $debit->execute();

        // Log debit transaction
        $debit_transactions = $conn->prepare("INSERT INTO transactions (senders_account, receivers_account, transaction_amount, sender_balance_before, sender_balance_after, transaction_type, customer_id, created_at, updated_at) 
        VALUES (:sa, :ra, :ta, :pb, :fb, :tt, :cst, NOW(), NOW())");
        
        $data = [
            ":sa" => $current_users_data['account_number'],
            ":ra" => $beneficiary_record['account_number'],
            ":ta" => $_POST['amount'],
            ":pb" => $senders_open_balance,
            ":fb" => $senders_closing_balance,
            ":tt" => "debit",
            ":cst" => $current_users_data['customer_id']
        ];

        $debit_transactions->execute($data);

        // Credit the beneficiary's account
        $beneficiary_opening_balance = $beneficiary_record['account_balance'];
        $beneficiary_closing_balance = $beneficiary_opening_balance + $_POST['amount'];
        $credit = $conn->prepare("UPDATE customer SET account_balance = :ab WHERE account_number = :ban");
        $credit->bindParam(":ab", $beneficiary_closing_balance);
        $credit->bindParam(":ban", $beneficiary_record['account_number']);
        $credit->execute();

        // Log credit transaction
        try{
            $credit_transactions = $conn->prepare("INSERT INTO transactions (senders_account, receivers_account, transaction_amount, sender_balance_before, sender_balance_after, transaction_type, customer_id, created_at, updated_at) 
            VALUES (:sa, :ra, :ta, :pb, :fb, :tt, :cst, NOW(), NOW())");
            $credit_data = [
                ":sa" => $current_users_data['account_number'],
                ":ra" => $beneficiary_record['account_number'],
                ":ta" => $_POST['amount'],
                ":pb" => $beneficiary_opening_balance,
                ":fb" => $beneficiary_closing_balance,
                ":tt" => "credit",
                ":cst" => $beneficiary_record['customer_id']
            ];
            $credit_transactions->execute($credit_data);
        } catch(PDOException $e){
            die($e->getMessage());
        }

        header("location:transfer.php?success=Transfer Successful");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .transfer-container {
            max-width: 500px;
            margin: 50px auto;
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

<?php include('../include/user_header.php') ?>

<div class="container transfer-container">
    <h2 class="text-center">Transfer Funds</h2>
    
    <?php
    // Display success or error messages
    if (isset($_GET['success'])) {
        echo "<div class='alert alert-success'>".$_GET['success']."</div>";
    }
    if (isset($_GET['error'])) {
        echo "<div class='alert alert-danger'>".$_GET['error']."</div>";
    }
    ?>
    
    <form action="" method="post">
        <div class="mb-3">
            <label for="account_number" class="form-label">Account Number</label>
            <input type="text" name="account_number" class="form-control" id="account_number" required>
            <?php if (isset($issue['account_number'])): ?>
                <div class="error-message"><?= $issue['account_number']; ?></div>
            <?php endif; ?>
        </div>
        
        <div class="mb-3">
            <label for="amount" class="form-label">Transaction Amount</label>
            <input type="text" name="amount" class="form-control" id="amount" required>
            <?php if (isset($issue['amount'])): ?>
                <div class="error-message"><?= $issue['amount']; ?></div>
            <?php endif; ?>
        </div>

        <button type="submit" name="pay" class="btn btn-custom w-100">Transfer</button>
    </form>
</div>

<!-- Bootstrap JS and Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<script>
    // Example of simple interaction: Highlight the form on focus
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            input.style.borderColor = '#ffc107';
        });
        input.addEventListener('blur', function() {
            input.style.borderColor = '';
        });
    });
</script>

</body>
</html>
