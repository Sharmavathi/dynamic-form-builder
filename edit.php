<?php
include "../configure/database_connection.php";

if(!isset($_GET['form_id'])) die("Form ID missing.");

$form_id = intval($_GET['form_id']);

// Get old form
$old_form = $conn->query("SELECT * FROM forms WHERE id=$form_id")->fetch_assoc();
if(!$old_form) die("Form not found.");

// 🔥 CREATE NEW VERSION
$new_version = $old_form['version'] + 1;

$stmt = $conn->prepare("INSERT INTO forms (form_name, version) VALUES (?, ?)");
$stmt->bind_param("si", $old_form['form_name'], $new_version);
$stmt->execute();

$new_form_id = $stmt->insert_id;

// 🔥 COPY FIELDS
$fields = $conn->query("SELECT * FROM form_fields WHERE form_id=$form_id");

while($field = $fields->fetch_assoc()) {

    $stmt_field = $conn->prepare("
        INSERT INTO form_fields 
        (form_id, label, type, placeholder, required, sort_order) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt_field->bind_param(
        "isssii",
        $new_form_id,
        $field['label'],
        $field['type'],
        $field['placeholder'],
        $field['required'],
        $field['sort_order']
    );

    $stmt_field->execute();

    $new_field_id = $stmt_field->insert_id;

    // Copy options
    $options = $conn->query("SELECT * FROM field_options WHERE field_id=".$field['id']);
    while($opt = $options->fetch_assoc()) {

        $stmt_opt = $conn->prepare("
            INSERT INTO field_options (field_id, option_text)
            VALUES (?, ?)
        ");

        $stmt_opt->bind_param("is", $new_field_id, $opt['option_text']);
        $stmt_opt->execute();
    }
}

// Redirect admin to edit new version fields
header("Location: add_fields.php?form_id=".$new_form_id);
exit();
?>
