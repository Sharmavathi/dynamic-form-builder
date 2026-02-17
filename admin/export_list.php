<?php
include "../configure/database_connection.php";

$forms = $conn->query("SELECT * FROM forms ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Export Submissions</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">
    <h2>Select Form to Export</h2>

    <ul>
    <?php while($form = $forms->fetch_assoc()): ?>
        <li>
            <?php echo htmlspecialchars($form['form_name']); ?>
            (Version <?php echo $form['version']; ?>)
            -
            <a href="../export_submissions.php?form_id=<?php echo $form['id']; ?>">
                Export CSV
            </a>
        </li>
    <?php endwhile; ?>
    </ul>

    <br>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>
