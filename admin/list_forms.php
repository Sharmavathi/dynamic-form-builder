<?php
include "../configure/database_connection.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Forms - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 20px;
            color: #2c3e50;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #34495e;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #3498db;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
        }

        tr:hover {
            background: #f1f8ff;
        }

        a {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
            margin-right: 10px;
        }

        a:hover {
            color: #2980b9;
            text-decoration: underline;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
            color: #2980b9;
        }

        .no-forms {
            text-align: center;
            padding: 20px;
            font-size: 16px;
        }

        .create-btn {
            display: inline-block;
            margin-bottom: 15px;
            background: #3498db;
            color: #fff;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        .create-btn:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>All Forms</h2>

    <?php
    $forms = $conn->query("SELECT * FROM forms ORDER BY created_at DESC");

    if ($forms->num_rows > 0) {
        echo "<ul>";
         while ($form = $forms->fetch_assoc()) {
    echo "<li>
        <strong>{$form['form_name']}</strong> 
        (Version: {$form['version']})
        <br>
        <a href='add_fields.php?form_id={$form['id']}'>Manage Fields</a> |
        <a href=' create_version.php?form_id={$form['id']}'>Create New Version</a>
        <hr>
    </li>";
}

        echo "</ul>";
    } else {
        echo "<p>No forms found. <a href='create_form.php'>Create a Form</a></p>";
    }
    ?>
    <br>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>
