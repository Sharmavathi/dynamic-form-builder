 <?php
include "../configure/database_connection.php";

if (!isset($_GET['form_id'])) die("Form ID missing.");
$form_id = intval($_GET['form_id']);
$form = $conn->query("SELECT * FROM forms WHERE id=$form_id")->fetch_assoc();
if (!$form) die("Form not found.");

// Handle Add/Edit Field
$edit_data = null;
if (isset($_GET['edit_field'])) {
    $edit_id = intval($_GET['edit_field']);
    $edit_data = $conn->query("SELECT * FROM form_fields WHERE id=$edit_id")->fetch_assoc();
}

if (isset($_POST['add_field'])) {
    $label = trim($_POST['label']);
    $type = $_POST['type'];
    $required = isset($_POST['required']) ? 1 : 0;
    $placeholder = trim($_POST['placeholder']);
    $sort_order = intval($_POST['sort_order']);

    if (!empty($_POST['field_id'])) {
        $field_id = intval($_POST['field_id']);
        $stmt = $conn->prepare("UPDATE form_fields SET label=?, type=?, required=?, placeholder=?, sort_order=? WHERE id=?");
        $stmt->bind_param("ssissi", $label, $type, $required, $placeholder, $sort_order, $field_id);
        $stmt->execute();
        $conn->query("DELETE FROM field_options WHERE field_id=$field_id");
    } else {
        $stmt = $conn->prepare("INSERT INTO form_fields (form_id, label, type, required, placeholder, sort_order) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issisi", $form_id, $label, $type, $required, $placeholder, $sort_order);
        $stmt->execute();
        $field_id = $stmt->insert_id;
    }

    if (!empty($_POST['options'])) {
        $options = explode(",", $_POST['options']);
        foreach ($options as $opt) {
            $opt = trim($opt);
            if ($opt !== '') {
                $stmt2 = $conn->prepare("INSERT INTO field_options (field_id, option_text) VALUES (?, ?)");
                $stmt2->bind_param("is", $field_id, $opt);
                $stmt2->execute();
            }
        }
    }

    header("Location: add_fields.php?form_id=$form_id");
    exit();
}

// Handle Conditional Rules
if (isset($_POST['add_rule'])) {
    $trigger_field_id = intval($_POST['trigger_field_id']);
    $operator = $_POST['operator'];
    $trigger_value = $_POST['trigger_value'];
    $target_field_id = intval($_POST['target_field_id']);
    $action = $_POST['action'];

    $stmt = $conn->prepare("INSERT INTO conditional_rules (form_id, trigger_field_id, operator, trigger_value, target_field_id, action) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissis", $form_id, $trigger_field_id, $operator, $trigger_value, $target_field_id, $action);
    $stmt->execute();
    header("Location: add_fields.php?form_id=$form_id");
    exit();
}

// Handle delete
if (isset($_GET['delete_field'])) {
    $field_id = intval($_GET['delete_field']);
    $conn->query("DELETE FROM field_options WHERE field_id=$field_id");
    $conn->query("DELETE FROM form_fields WHERE id=$field_id");
    header("Location: add_fields.php?form_id=$form_id");
    exit();
}

if (isset($_GET['delete_rule'])) {
    $rule_id = intval($_GET['delete_rule']);
    $conn->query("DELETE FROM conditional_rules WHERE id=$rule_id");
    header("Location: add_fields.php?form_id=$form_id");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Fields to: <?php echo htmlspecialchars($form['form_name']); ?></title>
     
     <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 20px;
            color: #2c3e50;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #34495e;
        }

        .container {
            max-width: 700px;
            margin: auto;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        .section-header {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 5px;
            color: #2c3e50;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input[type=text], input[type=number], select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
            transition: 0.2s;
        }

        input[type=text]:focus, input[type=number]:focus, select:focus, textarea:focus {
            border-color: #3498db;
            outline: none;
        }

        textarea { min-height: 60px; }

        .flex {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .flex > div { flex: 1; min-width: 120px; }

        button {
            background: #3498db;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
            font-size: 15px;
            font-weight: 500;
        }
        button:hover { background: #2980b9; }

        .fields-list p {
            background: #f9f9f9;
            padding: 12px 15px;
            border-radius: 6px;
            margin: 6px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            transition: 0.2s;
        }
        .fields-list p:hover { background: #eef6fb; }
        .fields-list a {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
            margin-left: 10px;
        }
        .fields-list a:hover { text-decoration: underline; }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
 <div class="container">

    <h2>Add Fields to: <?php echo htmlspecialchars($form['form_name']); ?></h2>

    <!-- Add/Edit Field Form -->
    <div class="card">
        <div class="section-header">Add / Edit Field</div>
        <form method="POST">
            <?php if ($edit_data) echo '<input type="hidden" name="field_id" value="'.$edit_data['id'].'">'; ?>
            
            <div class="form-group">
                <label>Field Label:</label>
                <input type="text" name="label" value="<?php echo $edit_data ? $edit_data['label'] : ''; ?>" required>
            </div>

            <div class="form-group">
                <label>Field Type:</label>
                <select name="type">
                    <?php $types = ['text','textarea','number','date','dropdown','radio','checkbox'];
                    foreach($types as $t){ $sel=($edit_data && $edit_data['type']==$t)?'selected':''; echo "<option value='$t' $sel>$t</option>";} ?>
                </select>
            </div>

            <div class="form-group">
                <label>Placeholder:</label>
                <input type="text" name="placeholder" value="<?php echo $edit_data ? $edit_data['placeholder'] : ''; ?>">
            </div>

            <div class="flex">
                <div>
                    <label>Sort order:</label>
                    <input type="number" name="sort_order" value="<?php echo $edit_data ? $edit_data['sort_order'] : '1'; ?>">
                </div>
                <div>
                    <label>&nbsp;</label>
                    <input type="checkbox" name="required" <?php echo ($edit_data && $edit_data['required']==1)?'checked':''; ?>> Required
                </div>
            </div>

            <div class="form-group">
                <label>Options (comma separated):</label>
                <textarea name="options"><?php echo $edit_data && !empty($edit_data['type']) ? implode(",", $conn->query("SELECT option_text FROM field_options WHERE field_id=".$edit_data['id'])->fetch_all(MYSQLI_COLUMN)) : ''; ?></textarea>
            </div>

            <button type="submit" name="add_field"><?php echo $edit_data?'Update Field':'Add Field'; ?></button>
        </form>
    </div>

    <!-- Fields List -->
    <div class="card">
        <div class="section-header">Added Fields</div>
        <div class="fields-list">
            <?php
            $fields_list = $conn->query("SELECT * FROM form_fields WHERE form_id=$form_id ORDER BY sort_order");
            while($f=$fields_list->fetch_assoc()){
                echo "<p>{$f['label']} ({$f['type']}) - 
                    <span>
                        <a href='add_fields.php?form_id=$form_id&edit_field={$f['id']}'>Edit</a> | 
                        <a href='add_fields.php?form_id=$form_id&delete_field={$f['id']}' onclick='return confirm(\"Delete this field?\")'>Delete</a>
                    </span>
                </p>";
            }
            ?>
        </div>
    </div>

    <a class="back-link" href="admin_dashboard.php">← Go to Form List</a>
</div>
</div>
</body>
</html>
