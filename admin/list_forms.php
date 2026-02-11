<?php
include "../configure/database_connection.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Forms - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
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
                {$form['form_name']} 
                - <a href='add_fields.php?form_id={$form['id']}'>Add / Edit Fields</a>
                 
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
