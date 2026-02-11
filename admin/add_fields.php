 <?php
include "../configure/database_connection.php";

if (!isset($_GET['form_id'])) die("Form ID missing.");
$form_id = intval($_GET['form_id']);

$form = $conn->query("SELECT * FROM forms WHERE id = $form_id")->fetch_assoc();
if (!$form) die("Form not found.");

if (isset($_POST['add_field'])) {
    $label = trim($_POST['label']);
    $type = $_POST['type'];
    $required = isset($_POST['required']) ? 1 : 0;
    $placeholder = trim($_POST['placeholder']);
    $sort_order = intval($_POST['sort_order']);

    if(empty($label)) {
        echo "<p style='color:red;'>Field label is required.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO form_fields (form_id, label, type, required, placeholder, sort_order) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issisi", $form_id, $label, $type, $required, $placeholder, $sort_order);
        $stmt->execute();
        $field_id = $stmt->insert_id;

        if(in_array($type, ['dropdown','radio','checkbox'])) {
            $options = explode(",", $_POST['options']);
            foreach($options as $opt) {
                $opt = trim($opt);
                if(!empty($opt)) {
                    $stmt_opt = $conn->prepare("INSERT INTO field_options (field_id, option_text) VALUES (?, ?)");
                    $stmt_opt->bind_param("is", $field_id, $opt);
                    $stmt_opt->execute();
                }
            }
        }

        header("Location: add_fields.php?form_id=".$form_id);
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Fields</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">
<h2>Add Fields to: <?php echo $form['form_name']; ?></h2>

<form method="POST">
    <label>Field Label:</label><br>
    <input type="text" name="label"><br><br>

    <label>Field Type:</label><br>
    <select name="type">
        <option value="text">Text</option>
        <option value="textarea">Textarea</option>
        <option value="number">Number</option>
        <option value="date">Date</option>
        <option value="dropdown">Dropdown</option>
        <option value="radio">Radio</option>
        <option value="checkbox">Checkbox</option>
    </select><br><br>

    <label>Placeholder:</label><br>
    <input type="text" name="placeholder"><br><br>

    <label>Sort Order:</label><br>
    <input type="number" name="sort_order" value="1"><br><br>

    <label><input type="checkbox" name="required"> Required</label><br><br>

    <label>Options (comma separated for dropdown/radio/checkbox):</label><br>
    <textarea name="options"></textarea><br><br>

    <button type="submit" name="add_field">Add Field</button>
</form>

<hr>
<h3>Added Fields:</h3>
<?php
$fields = $conn->query("SELECT * FROM form_fields WHERE form_id = $form_id ORDER BY sort_order");
while($field = $fields->fetch_assoc()) {
    echo "<p><strong>{$field['label']}</strong> ({$field['type']})</p>";
}
?>
<br>
<a href="../index.php">Go to Form List</a>
</div>
</body>
</html>
