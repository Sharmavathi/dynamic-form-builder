 <?php
include "configure/database_connection.php";
$user_name = "user"; // replace with actual login username
$forms = $conn->query("SELECT * FROM forms ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <style>
        /* Reset */
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            color: #2c3e50;
        }

        .header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .dashboard-container {
            display: flex;
            max-width: 1200px;
            margin: 30px auto;
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            padding: 20px;
            height: fit-content;
        }

        .sidebar h2 {
            font-size: 20px;
            margin-bottom: 20px;
            color: #3498db;
        }

        .sidebar a {
            display: block;
            margin: 12px 0;
            padding: 10px;
            color: #333;
            text-decoration: none;
            border-radius: 6px;
            transition: 0.3s;
            font-weight: 500;
        }

        .sidebar a:hover {
            background: #3498db;
            color: white;
        }

        /* Main content */
        .main-content {
            flex: 1;
            margin-left: 25px;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        .card h3 {
            margin-bottom: 15px;
            color: #34495e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        table th {
            background: #3498db;
            color: white;
            font-weight: 600;
        }

        table tr:hover {
            background: #f1f8ff;
        }

        a.form-link {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }

        a.form-link:hover {
            color: #2980b9;
            text-decoration: underline;
        }

        @media(max-width: 900px) {
            .dashboard-container {
                flex-direction: column;
            }
            .main-content {
                margin-left: 0;
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h1>
    </div>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>User Panel</h2>
            <a href="user_dashboard.php">Dashboard Home</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </div>

        <!-- Main content -->
        <div class="main-content">
            <div class="card">
                <h3>Available Forms</h3>
                <?php if($forms->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Form Name</th>
                                <th>Version</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($form = $forms->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($form['form_name']); ?></td>
                                    <td><?php echo $form['version']; ?></td>
                                    <td><a class="form-link" href="form.php?form_id=<?php echo $form['id']; ?>">Open Form</a></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No forms available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
