 <?php
include "configure/database_connection.php";

// Dummy user ID
$user_id = 1;

if(!isset($_POST['form_id'])) die("Form ID missing.");
$form_id = intval($_POST['form_id']);

// Insert main submission
$stmt = $conn->prepare("INSERT INTO form_responses (form_id, user_id, submitted_at) VALUES (?, ?, NOW())");
$stmt->bind_param("ii", $form_id, $user_id);
$stmt->execute();
$response_id = $stmt->insert_id;

// Fetch fields
$fields = $conn->query("SELECT * FROM form_fields WHERE form_id=$form_id");

while($field = $fields->fetch_assoc()) {
    $field_name = "field_".$field['id'];

    if($field['required'] && empty($_POST[$field_name])) {
        die("Error: {$field['label']} is required.");
    }

    if(isset($_POST[$field_name])) {
        $value = $_POST[$field_name];

        if(is_array($value)) $value = implode(",", $value);

        // Validate options
        if(in_array($field['type'], ['dropdown','radio','checkbox'])) {
            $valid_options = [];
            $opt_res = $conn->query("SELECT option_text FROM field_options WHERE field_id={$field['id']}");
            while($o = $opt_res->fetch_assoc()) $valid_options[] = $o['option_text'];

            $submitted_values = is_array($_POST[$field_name]) ? $_POST[$field_name] : [$value];
            foreach($submitted_values as $val) {
                if(!in_array($val, $valid_options)) die("Invalid value for {$field['label']}.");
            }
        }

        $stmt_val = $conn->prepare("INSERT INTO form_response_values (response_id, field_id, value) VALUES (?, ?, ?)");
        $stmt_val->bind_param("iis", $response_id, $field['id'], $value);
        $stmt_val->execute();
    }
}

echo "<div class='container'><h3>Form submitted successfully!</h3>";
echo "<a href='index.php'>Back to Forms</a></div>";
