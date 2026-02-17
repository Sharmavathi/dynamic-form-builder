 <?php
// admin/admin_dashboard.php
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Reset & basic styles */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            display: flex;
            min-height: 100vh;
        }

         /* Sidebar */
.sidebar {
    width: 220px;
    background: #e7d0d0; /* changed from dark to white */
    color: #2c3e50;      /* dark text for contrast */
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    padding-top: 20px;
    border-right: 1px solid #ddd; /* subtle divider */
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 22px;
    letter-spacing: 1px;
    color: #2c3e50; /* dark text */
}

.sidebar a {
    color: #2c3e50; /* dark text */
    text-decoration: none;
    padding: 12px 20px;
    margin: 5px 10px;
    border-radius: 6px;
    transition: 0.3s;
    display: flex;
    align-items: center;
}

.sidebar a:hover {
    background: #3498db;
    color: #fff; /* text turns white on hover */
}


        /* Main content */
        .main {
            flex: 1;
            padding: 30px;
        }

        .header {
            background: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow: 0 4px 10px rgba(236, 231, 231, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header h1 {
            font-size: 24px;
            color: #5d89b6;
        }

        /* Cards */
        .card {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: 0.3s;
        }

        .card:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        .card h3 {
            margin-bottom: 15px;
            color: #2c3e50;
            font-size: 20px;
        }

        .menu a {
            display: block;
            padding: 10px 15px;
            margin-bottom: 10px;
            background: #f8f9fa;
            border-radius: 6px;
            color: #506c89;
            text-decoration: none;
            transition: 0.3s;
        }

        .menu a:hover {
            background: #3498db;
            color: #fff;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #777;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                flex-direction: row;
                overflow-x: auto;
            }
            .sidebar a {
                flex: 1;
                margin: 5px;
                text-align: center;
            }
            .main { padding: 15px; }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="create_form.php">➕ Create Form</a>
        <a href="list_forms.php">📋 View Forms</a>
        <a href="view_responses.php">👀 Form Responses</a>
        <a href="admin_submissions.php?form_id=1">🔎 Submissions</a>
        <a href="set_rules.php">⚡ Set Rules</a>
        <a href="export_list.php">📤 Export CSV</a>
    </div>

    <!-- Main content -->
    <div class="main">
        <div class="header">
            <h1>Welcome, Admin!</h1>
            <div><?php echo date("l, d M Y"); ?></div>
        </div>

        <!-- Cards -->
        <div class="card">
            <h3>📁 Forms Management</h3>
            <div class="menu">
                <a href="create_form.php">➕ Create New Form</a>
                <a href="list_forms.php">📋 View All Forms & Add Fields</a>
            </div>
        </div>

        <div class="card">
            <h3>📊 Submissions Management</h3>
            <div class="menu">
                <a href="view_responses.php">👀 View Form Responses</a>
                <a href="admin_submissions.php?form_id=1">🔎 Submissions (Pagination)</a>
                <a href="set_rules.php">⚡ Set Rules</a>
                <a href="export_list.php">📤 Export CSV</a>
            </div>
        </div>

        <div class="footer">
            &copy; <?php echo date("Y"); ?> Dynamic Form Builder | Admin Panel
        </div>
    </div>

</body>
</html>
