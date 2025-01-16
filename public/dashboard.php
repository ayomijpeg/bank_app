<?php
session_start();
var_dump( $_SESSION);
include('../include/user_auth.php');
include('../include/db.php');
include('../include/user_info.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
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
        .card {
            margin-bottom: 20px;
        }
        .alert-custom {
            background-color: #ffc107;
            color: #000;
        }
    </style>
</head>
<body>

<?php include('../include/user_header.php') ?>

<div class="container">
    <div class="dashboard-header">
        <h2>User Dashboard</h2>
    </div>
    
    <div class="alert alert-custom" role="alert">
        Welcome to your dashboard! Here, you can manage your account and view important information.
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Account Overview</h5>
                </div>
                <div class="card-body">
                    <p>Manage your account details, change your password, and more.</p>
                    <a href="edit_account.php" class="btn btn-custom w-100">Edit Account</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Transactions</h5>
                </div>
                <div class="card-body">
                    <p>View your recent transactions.</p>
                    <a href="statement.php" class="btn btn-custom w-100">View Transactions</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<script>
    // Example of simple interaction: Change background color when card is hovered
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            card.style.backgroundColor = '#f1f1f1';
        });
        card.addEventListener('mouseleave', function() {
            card.style.backgroundColor = '';
        });
    });
</script>

</body>
</html>
