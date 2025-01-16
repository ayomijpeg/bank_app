<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Admin</title>
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
        .nav-link {
            color: #fff;
            margin-right: 15px;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #ffc107;
        }
        .logout:hover {
            color: #dc3545 !important;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header d-flex align-items-center justify-content-between">
        <div>
            <h1>Swap Bank</h1>
             <h5>Account ID: <?= isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : 'N/A' ?></h5>
              <h5>User Name: <?= isset($_SESSION['account_name']) ? $_SESSION['account_name'] : 'N/A' ?></h5>
              <h5>Account Number: <?= isset($_SESSION['account_number']) ? $_SESSION['account_number'] : 'N/A' ?></h5>
              var_dump($_SESSION);
        </div>
        <nav>
            <a href="dashboard.php" class="nav-link">Dashboard</a>
            <a href="transfer.php" class="nav-link">Transfer</a>
            <a href="logout.php" class="nav-link logout">Logout</a>
        </nav>
    </div>
    <hr />

    <!-- Optional JS for Alerts -->
    <script>
        document.querySelector('.logout').addEventListener('click', function (event) {
            if (!confirm('Are you sure you want to logout?')) {
                event.preventDefault();
            }
        });
    </script>

    <!-- Bootstrap JS and Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
