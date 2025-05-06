<?php
include('connection.php');
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$current_page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }

        .form-control:focus {
            outline: none !important;
            box-shadow: none !important;
        }

        .side-bar {
            background: #343a40;
            color: #ffffff;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .side-bar .nav-link {
            color: #ced4da;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
        }

        .side-bar .nav-link.active,
        .side-bar .nav-link:hover {
            background-color: #495057;
            color: #ffffff;
            border-radius: 6px;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .top-bar {
            height: 60px;
            background-color: #ffffff;
            color: #212529;
            padding: 0 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            transition: 0.2s;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .content-area {
            margin-top: 30px;
            background-color: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        h4 {
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="side-bar">
        <ul class="nav flex-column p-3">
            <?php if ($_SESSION['role'] == 'donor'): ?>
                <!-- Donor Link -->
                <li class="nav-item">
                    <a href="dashboard.php?page=assessment" class="nav-link <?php echo ($current_page == 'assessment') ? 'active' : ''; ?>">Assessment</a>
                </li>
                <li class="nav-item">
                    <a href="dashboard.php?page=assessment_history" class="nav-link <?php echo ($current_page == 'assessment_history') ? 'active' : ''; ?>">History</a>
                </li>
            <?php elseif ($_SESSION['role'] == 'staff'): ?>
                <!-- Staff Link -->
                <li class="nav-item">
                    <a href="dashboard.php?page=dashboard" class="nav-link <?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="dashboard.php?page=donor_list" class="nav-link <?php echo ($current_page == 'donor_list') ? 'active' : ''; ?>">Donor List</a>
                </li>
            <?php elseif ($_SESSION['role'] == 'admin'): ?>
                <!-- Admin Links -->
                <li class="nav-item">
                    <a href="dashboard.php?page=dashboard" class="nav-link <?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="dashboard.php?page=user_management" class="nav-link <?php echo ($current_page == 'user_management') ? 'active' : ''; ?>">User Management</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <div class="d-flex justify-content-between">
                <h4>Dashboard</h4>
                <div>
                    <a href="dashboard.php?page=user_profile" class="btn btn-primary">
                        <i class="bi bi-person"></i>
                    </a>
                    <a href="auth/logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>

        <div class="content-area mt-4">
            <?php
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
                switch ($page) {
                    case 'assessment':
                        include 'assessment/assessment.php';
                        break;
                    case 'assessment_history':
                        include 'assessment/history.php';
                        break;
                    case 'donor_list':
                        include 'assessment/donor_list.php';
                        break;
                    case 'view_user':
                        include 'user/view.php';
                        break;
                    case 'add_user':
                        include 'user/add.php';
                        break;
                    case 'edit_user':
                        include 'user/edit.php';
                        break;
                    case 'delete_user':
                        include 'user/delete.php';
                        break;
                    case 'user_management':
                        include 'user/list.php';
                        break;
                    case 'user_profile':
                        include 'user/profile.php';
                        break;
                    default:
                        include('dashboard_page.php');
                }
            } else {
                echo "<p>Welcome to the dashboard</p>";
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>