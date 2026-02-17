 <?php
include "configure/database_connection.php";

/* -----------------------------
   0️⃣ Validate Input
------------------------------*/
if (!isset($_GET['form_id']) || !is_numeric($_GET['form_id'])) {
    die("Invalid Form ID.");
}

$form_id = intval($_GET['form_id']);

/* -----------------------------
   1️⃣ Validate Form
------------------------------*/
$form_stmt = $conn->prepare("SELECT id, form_name FROM forms WHERE id = ?");
$form_stmt->bind_param("i", $form_id);
$form_stmt->execute();
$form = $form_stmt->get_result()->fetch_assoc();

if (!$form) {
    die("Form not found.");
}

/* -----------------------------
   2️⃣ Fetch Dynamic Fields
------------------------------*/
$field_stmt = $conn->prepare("
    SELECT id, label 
    FROM form_fields 
    WHERE form_id = ? 
    ORDER BY sort_order ASC
");
$field_stmt->bind_param("i", $form_id);
$field_stmt->execute();
$field_result = $field_stmt->get_result();

$fields = [];
$field_ids = [];

while ($row = $field_result->fetch_assoc()) {
    $fields[] = $row;
    $field_ids[] = $row['id'];
}

/* -----------------------------
   3️⃣ Prepare Headers
------------------------------*/
$headers = ['Submission ID', 'User ID', 'Submitted At'];

foreach ($fields as $field) {
    $headers[] = $field['label'];
}

/* -----------------------------
   4️⃣ Send CSV Headers (NO BOM)
------------------------------*/
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="form_' . $form_id . '_submissions.csv"');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');

/* Write Header Row */
fputcsv($output, $headers);

/* -----------------------------
   5️⃣ Fetch All Submissions
------------------------------*/
$submission_stmt = $conn->prepare("
    SELECT id, user_id, submitted_at 
    FROM form_responses 
    WHERE form_id = ? 
    ORDER BY submitted_at ASC
");
$submission_stmt->bind_param("i", $form_id);
$submission_stmt->execute();
$submission_result = $submission_stmt->get_result();

/* -----------------------------
   6️⃣ Loop Submissions
------------------------------*/
while ($submission = $submission_result->fetch_assoc()) {

    $response_id = $submission['id'];

    /* Fetch all field values in ONE query */
    $value_stmt = $conn->prepare("
        SELECT field_id, value 
        FROM form_response_values 
        WHERE response_id = ?
    ");
    $value_stmt->bind_param("i", $response_id);
    $value_stmt->execute();
    $values_result = $value_stmt->get_result();

    $values_map = [];
    while ($val = $values_result->fetch_assoc()) {
        $values_map[$val['field_id']] = $val['value'];
    }

    /* Base Row */
    $row = [];

    // Prevent Excel auto-format (numbers, large IDs, dates)
    $row[] = "'" . $submission['id'];
    $row[] = "'" . $submission['user_id'];
    $row[] = "'" . date("Y-m-d H:i:s", strtotime($submission['submitted_at']));

    /* Add Dynamic Fields */
    foreach ($field_ids as $fid) {

        $value = isset($values_map[$fid]) ? $values_map[$fid] : '';

        // Prevent Excel converting DOB or numbers
        if (!empty($value)) {
            $value = "'" . $value;
        }

        $row[] = $value;
    }

    fputcsv($output, $row);
}

fclose($output);
exit();
?>
