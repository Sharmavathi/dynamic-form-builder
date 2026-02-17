 <?php
include "../configure/database_connection.php";

if (!isset($_GET['form_id'])) die("Form ID missing.");
$form_id = intval($_GET['form_id']);

// Get the old form
$old_form = $conn->query("SELECT * FROM forms WHERE id=$form_id")->fetch_assoc();
if (!$old_form) die("Form not found.");

// New version number
$new_version = $old_form['version'] + 1;

// Insert new form version
$stmt = $conn->prepare("INSERT INTO forms (form_name, version) VALUES (?, ?)");
$stmt->bind_param("si", $old_form['form_name'], $new_version);
$stmt->execute();
$new_form_id = $stmt->insert_id;

// Copy fields
$fields = $conn->query("SELECT * FROM form_fields WHERE form_id=$form_id");
while ($field = $fields->fetch_assoc()) {
    $stmt2 = $conn->prepare("INSERT INTO form_fields (form_id, label, type, required, placeholder, sort_order) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt2->bind_param("issisi", $new_form_id, $field['label'], $field['type'], $field['required'], $field['placeholder'], $field['sort_order']);
    $stmt2->execute();
    
    $new_field_id = $stmt2->insert_id;

    // Copy options
    $options = $conn->query("SELECT * FROM field_options WHERE field_id={$field['id']}");
    while ($opt = $options->fetch_assoc()) {
        $stmt3 = $conn->prepare("INSERT INTO field_options (field_id, option_text) VALUES (?, ?)");
        $stmt3->bind_param("is", $new_field_id, $opt['option_text']);
        $stmt3->execute();
    }
}

// Redirect to manage fields of the new version
header("Location: ../admin/add_fields.php?form_id=$new_form_id");
exit();
