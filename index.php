 <?php 
include "configure/database_connection.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dynamic Form Builder</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Welcome to Dynamic Form Builder</h2>

    <!-- Admin Access -->
    <p><strong>Admin Access:</strong> <a href="admin/admin_dashboard.php">Go to Admin Panel</a></p>
    <hr>

    <!-- User Access -->
    <h3>Available Forms</h3>
    <?php
    $forms = $conn->query("SELECT * FROM forms ORDER BY created_at DESC");

    if ($forms->num_rows > 0) {
        echo "<ul>";
        while ($form = $forms->fetch_assoc()) {
            echo "<li><a href='form.php?form_id={$form['id']}'>{$form['form_name']}</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No forms available.</p>";
    }
    ?>
</div>
</body>
</html>
