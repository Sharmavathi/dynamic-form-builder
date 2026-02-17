  <?php 
include "../configure/database_connection.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Form Responses</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">

<?php
// STEP 1: If no form selected → show all forms
if (!isset($_GET['form_id'])) {
    echo "<h2>Select a Form to View Responses</h2>";
    $forms = $conn->query("SELECT * FROM forms ORDER BY created_at DESC");

    if ($forms->num_rows > 0) {
        echo "<ul>";
        while ($form = $forms->fetch_assoc()) {
            echo "<li>
                <a href='view_responses.php?form_id={$form['id']}'>
                    {$form['form_name']}
                </a>
            </li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No forms available.</p>";
    }

    echo "<br><a href='admin_dashboard.php'>Back to Dashboard</a>";
    echo "</div></body></html>";
    exit();
}

// STEP 2: Show submissions of selected form
$form_id = intval($_GET['form_id']);
$form = $conn->query("SELECT * FROM forms WHERE id=$form_id")->fetch_assoc();

if (!$form) {
    die("Form not found.");
}

echo "<h2>Responses for: " . htmlspecialchars($form['form_name']) . "</h2>";

$responses = $conn->query("
    SELECT * FROM form_responses 
    WHERE form_id=$form_id 
    ORDER BY submitted_at DESC
");

if ($responses->num_rows == 0) {
    echo "<p>No submissions yet.</p>";
    echo "<br><a href='view_responses.php'>Back to Forms</a>";
    echo "<br><a href='admin_dashboard.php'>Back to Dashboard</a>";
    echo "</div></body></html>";
    exit();
}

echo "<ul>";
while ($resp = $responses->fetch_assoc()) {
    echo "<li>
        <strong>Submission ID:</strong> {$resp['id']} |
        <strong>User ID:</strong> {$resp['user_id']} |
        <strong>Submitted At:</strong> {$resp['submitted_at']}
        <br>
        <a href='view_response_detail.php?response_id={$resp['id']}'>
            View Details
        </a>
    </li>";
}
echo "</ul>";

echo "<br>";
echo "<a href='view_responses.php'>Back to Forms</a>";
echo "<br><br>";
echo "<a href='admin_dashboard.php'>Back to Dashboard</a>";
?>

</div>
</body>
</html>
