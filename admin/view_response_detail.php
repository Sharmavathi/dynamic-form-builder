 <?php 
include "../configure/database_connection.php";

if (!isset($_GET['response_id'])) {
    die("Response ID missing.");
}

$response_id = intval($_GET['response_id']);

// Fetch response info
$response_query = $conn->query("SELECT * FROM form_responses WHERE id=$response_id");
$response = $response_query->fetch_assoc();

if (!$response) {
    die("Response not found.");
}

// Fetch form info
$form_query = $conn->query("SELECT * FROM forms WHERE id={$response['form_id']}");
$form = $form_query->fetch_assoc();

// Fetch field-wise values
$values = $conn->query("
    SELECT f.label, f.type, v.value
    FROM form_response_values v
    JOIN form_fields f ON v.field_id = f.id
    WHERE v.response_id=$response_id
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Response Details</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">

<h2>Response Details</h2>

<h3>Form: <?php echo htmlspecialchars($form['form_name']); ?></h3>

<p>
    <strong>Submission ID:</strong> <?php echo $response['id']; ?> <br>
    <strong>User ID:</strong> <?php echo $response['user_id']; ?> <br>
    <strong>Submitted At:</strong> <?php echo $response['submitted_at']; ?>
</p>

<?php if ($values->num_rows > 0): ?>
    <table>
        <tr>
            <th>Field Name</th>
            <th>Value</th>
        </tr>
        <?php while($row = $values->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['label']); ?></td>
                <td><?php echo htmlspecialchars($row['value']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No values found for this submission.</p>
<?php endif; ?>

<br>

<a href="view_responses.php?form_id=<?php echo $form['id']; ?>">Back to Submissions</a>
<br><br>
<a href="admin_dashboard.php">Back to Dashboard</a>

</div>
</body>
</html>
