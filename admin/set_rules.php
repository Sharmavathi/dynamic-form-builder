 <?php 
include "../configure/database_connection.php";

// Fetch all forms
$forms_res = $conn->query("SELECT * FROM forms ORDER BY id");
$forms = [];
while($f = $forms_res->fetch_assoc()) $forms[$f['id']] = $f; // keyed by form_id

// Fetch all fields grouped by form
$fields_res = $conn->query("SELECT * FROM form_fields ORDER BY form_id, sort_order");
$fields_by_form = [];
while($f = $fields_res->fetch_assoc()) $fields_by_form[$f['form_id']][] = $f;

// Fetch field options grouped by field_id
$field_options = [];
$opt_res = $conn->query("SELECT * FROM field_options");
while($o = $opt_res->fetch_assoc()) {
    $field_options[$o['field_id']][] = $o['option_text'];
}

// Handle saving rule
if(isset($_POST['save_rule'])){
    $trigger_field = intval($_POST['trigger_field']);
    $operator = $_POST['operator'];
    $trigger_value = $_POST['trigger_value'];
    $target_field = intval($_POST['target_field']);
    $action = $_POST['action'];
    $apply_all = isset($_POST['apply_all']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO conditional_rules (form_id, trigger_field_id, operator, trigger_value, target_field_id, action) VALUES (?, ?, ?, ?, ?, ?)");

    if($apply_all){
        foreach($forms as $form){
            $stmt->bind_param("iissis", $form['id'], $trigger_field, $operator, $trigger_value, $target_field, $action);
            $stmt->execute();
        }
    } else {
        if(!isset($_POST['form_id']) || $_POST['form_id'] == '' || !isset($forms[intval($_POST['form_id'])])){
            die("Form ID missing or invalid for single form rule.");
        }
        $form_id = intval($_POST['form_id']);
        $stmt->bind_param("iissis", $form_id, $trigger_field, $operator, $trigger_value, $target_field, $action);
        $stmt->execute();
    }

    header("Location: set_rules.php?success=1");
    exit();
}

// Fetch existing rules
$rules_res = $conn->query("
    SELECT cr.*, ff1.label AS trigger_label, ff2.label AS target_label, f.form_name
    FROM conditional_rules cr
    LEFT JOIN form_fields ff1 ON cr.trigger_field_id = ff1.id
    LEFT JOIN form_fields ff2 ON cr.target_field_id = ff2.id
    LEFT JOIN forms f ON cr.form_id = f.id
    ORDER BY cr.id DESC
");
$existing_rules = [];
while($r = $rules_res->fetch_assoc()) $existing_rules[] = $r;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Set Conditional Rules</title>
    <style>
        body{ font-family: Arial; padding: 20px; background:#f4f6f9; }
        .container{ max-width:700px; margin:auto; background:#fff; padding:25px; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.1); }
        label{ display:block; margin:10px 0 5px; font-weight:600; }
        select, input[type=text]{ width:100%; padding:8px; border-radius:6px; border:1px solid #ccc; }
        button{ margin-top:15px; padding:10px 18px; border:none; border-radius:6px; background:#3498db; color:#fff; cursor:pointer; font-size:15px; }
        button:hover{ background:#2980b9; }
        table { width: 100%; border-collapse: collapse; margin-top:20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background:#f1f1f1; }
    </style>
</head>
<body>
<div class="container">
    <h2>Set  Rules</h2>

    <form method="POST">
        <label>Select Form:</label>
        <select name="form_id" id="form_id">
            <option value="">Select Form</option>
            <?php foreach($forms as $f): ?>
                <option value="<?php echo $f['id']; ?>"><?php echo htmlspecialchars($f['form_name']); ?></option>
            <?php endforeach; ?>
        </select>

        <label>Trigger Field:</label>
        <select name="trigger_field" id="trigger_field" required>
            <option value="">Select Trigger Field</option>
        </select>

        <label>Operator:</label>
        <select name="operator" required>
            <option value="=">=</option>
            <option value="!=">!=</option>
            <option value=">">&gt;</option>
            <option value="<">&lt;</option>
        </select>

        <label>Trigger Value:</label>
        <select name="trigger_value" id="trigger_value" required>
            <option value="">Select value</option>
        </select>

        <label>Target Field:</label>
        <select name="target_field" id="target_field" required>
            <option value="">Select Target Field</option>
        </select>

        <label>Action:</label>
        <select name="action">
            <option value="show">Show</option>
            <option value="hide">Hide</option>
        </select>

        <label><input type="checkbox" name="apply_all" id="apply_all"> Apply to all forms</label>

        <button type="submit" name="save_rule">Save Rule</button>
    </form>

    <h3>Existing Rules</h3>
    <table>
        <tr>
            <th>Trigger Field</th>
            <th>Operator</th>
            <th>Trigger Value</th>
            <th>Target Field</th>
            <th>Action</th>
            <th>Form</th>
        </tr>
        <?php if($existing_rules): foreach($existing_rules as $r): ?>
        <tr>
            <td><?php echo $r['trigger_label']; ?></td>
            <td><?php echo $r['operator']; ?></td>
            <td><?php echo $r['trigger_value']; ?></td>
            <td><?php echo $r['target_label']; ?></td>
            <td><?php echo $r['action']; ?></td>
            <td><?php echo $r['form_name']; ?></td>
        </tr>
        <?php endforeach; else: ?>
        <tr><td colspan="6">No rules defined.</td></tr>
        <?php endif; ?>
    </table>
</div>

<script>
const fieldsByForm = <?php echo json_encode($fields_by_form); ?>;
const fieldOptions = <?php echo json_encode($field_options); ?>;
const formSelect = document.getElementById('form_id');
const triggerFieldSelect = document.getElementById('trigger_field');
const targetFieldSelect = document.getElementById('target_field');
const triggerValueSelect = document.getElementById('trigger_value');
const applyAllCheckbox = document.getElementById('apply_all');

function populateFieldsForForm(formId){
    triggerFieldSelect.innerHTML = '<option value="">Select Trigger Field</option>';
    targetFieldSelect.innerHTML = '<option value="">Select Target Field</option>';
    triggerValueSelect.innerHTML = '<option value="">Select value</option>';

    if(!fieldsByForm[formId]) return;

    fieldsByForm[formId].forEach(f => {
        const option1 = document.createElement('option');
        option1.value = f.id;
        option1.textContent = f.label;
        triggerFieldSelect.appendChild(option1);

        const option2 = document.createElement('option');
        option2.value = f.id;
        option2.textContent = f.label;
        targetFieldSelect.appendChild(option2);
    });
}

formSelect.addEventListener('change', function(){
    populateFieldsForForm(this.value);
});

// Populate trigger values when trigger field changes
triggerFieldSelect.addEventListener('change', function(){
    const fieldId = this.value;
    triggerValueSelect.innerHTML = '<option value="">Select value</option>';
    if(fieldOptions[fieldId]){
        fieldOptions[fieldId].forEach(val=>{
            const option = document.createElement('option');
            option.value = val;
            option.textContent = val;
            triggerValueSelect.appendChild(option);
        });
    } else {
        const option = document.createElement('option');
        option.value = '';
        option.textContent = 'Enter value manually in user form';
        triggerValueSelect.appendChild(option);
    }
});

// Disable form select if apply_all checked
applyAllCheckbox.addEventListener('change', function(){
    formSelect.disabled = this.checked;
});
</script>
</body>
</html>
