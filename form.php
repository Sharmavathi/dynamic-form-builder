 <?php
include "configure/database_connection.php";

if(!isset($_GET['form_id'])) die("Form ID missing.");
$form_id = intval($_GET['form_id']);

$form = $conn->query("SELECT * FROM forms WHERE id=$form_id")->fetch_assoc();
if(!$form) die("Form not found.");

$fields_result = $conn->query("SELECT * FROM form_fields WHERE form_id=$form_id ORDER BY sort_order");
$fields = [];
while($row = $fields_result->fetch_assoc()) $fields[] = $row;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($form['form_name']); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2><?php echo htmlspecialchars($form['form_name']); ?></h2>

    <form method="POST" action="submit.php">
        <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">

        <?php foreach($fields as $field): 
            $field_name = "field_".$field['id']; 
            $required_attr = $field['required'] ? "required" : "";
            $options = [];
            if(in_array($field['type'], ['dropdown','radio','checkbox'])) {
                $opt_res = $conn->query("SELECT option_text FROM field_options WHERE field_id={$field['id']}");
                while($o = $opt_res->fetch_assoc()) $options[] = $o['option_text'];
            }
        ?>
            <label><?php echo htmlspecialchars($field['label']); ?> <?php if($field['required']) echo "*"; ?></label><br>

            <?php if(in_array($field['type'], ['text','number','date'])): ?>
                <input type="<?php echo $field['type']; ?>" name="<?php echo $field_name; ?>" placeholder="<?php echo htmlspecialchars($field['placeholder']); ?>" <?php echo $required_attr; ?>>
            <?php elseif($field['type'] == 'textarea'): ?>
                <textarea name="<?php echo $field_name; ?>" placeholder="<?php echo htmlspecialchars($field['placeholder']); ?>" <?php echo $required_attr; ?>></textarea>
            <?php elseif($field['type'] == 'dropdown'): ?>
                <select name="<?php echo $field_name; ?>" <?php echo $required_attr; ?>>
                    <option value="">Select</option>
                    <?php foreach($options as $opt): ?>
                        <option value="<?php echo htmlspecialchars($opt); ?>"><?php echo htmlspecialchars($opt); ?></option>
                    <?php endforeach; ?>
                </select>
            <?php elseif($field['type'] == 'radio'): ?>
                <?php foreach($options as $opt): ?>
                    <input type="radio" name="<?php echo $field_name; ?>" value="<?php echo htmlspecialchars($opt); ?>" <?php echo $required_attr; ?>> <?php echo htmlspecialchars($opt); ?>
                <?php endforeach; ?>
            <?php elseif($field['type'] == 'checkbox'): ?>
                <?php foreach($options as $opt): ?>
                    <input type="checkbox" name="<?php echo $field_name; ?>[]" value="<?php echo htmlspecialchars($opt); ?>" <?php echo $required_attr; ?>> <?php echo htmlspecialchars($opt); ?>
                <?php endforeach; ?>
            <?php endif; ?>

            <br><br>
        <?php endforeach; ?>

        <button type="submit">Submit</button>
    </form>

    <br>
    <a href="index.php">Back to Forms</a>
</div>
</body>
</html>
