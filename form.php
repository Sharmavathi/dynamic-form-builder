 <?php  
include "configure/database_connection.php";

if(!isset($_GET['form_id'])) die("Form ID missing.");
$form_id = intval($_GET['form_id']);

// Fetch form
$form = $conn->query("SELECT * FROM forms WHERE id=$form_id")->fetch_assoc();
if(!$form) die("Form not found.");

// Fetch fields
$fields_result = $conn->query("SELECT * FROM form_fields WHERE form_id=$form_id ORDER BY sort_order");
$fields = [];
while($row = $fields_result->fetch_assoc()) $fields[] = $row;

// Fetch options for fields
foreach($fields as &$f) {
    $f['options'] = [];
    if(in_array($f['type'], ['dropdown','radio','checkbox'])) {
        $opt_res = $conn->query("SELECT option_text FROM field_options WHERE field_id={$f['id']}");
        while($o = $opt_res->fetch_assoc()) $f['options'][] = $o['option_text'];
    }
}

// Fetch conditional rules for this form and all-global rules
$rules_result = $conn->query("SELECT * FROM conditional_rules WHERE form_id=$form_id OR is_global=1");
$rules = [];
while($row = $rules_result->fetch_assoc()) {
    $rules[] = $row;
}

// Collect fields targeted by rules to hide them initially
$targets = [];
foreach($rules as $r) $targets[] = $r['target_field_id'];
$targets = array_unique($targets);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($form['form_name']); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 0; }
        .container { max-width: 700px; margin: 30px auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #2c3e50; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: 600; margin-bottom: 6px; }
        input[type=text], input[type=number], input[type=date], select, textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; }
        input[type=radio], input[type=checkbox] { margin-right: 5px; }
        button { background: #3498db; color: #fff; border: none; padding: 10px 18px; border-radius: 6px; cursor: pointer; font-size: 15px; transition: 0.3s; }
        button:hover { background: #2980b9; }
        .form-group.hidden { display: none; }
    </style>
</head>
<body>
<div class="container">
    <h2><?php echo htmlspecialchars($form['form_name']); ?></h2>
    <form method="POST" action="submit.php" id="dynamicForm">
        <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">

        <?php foreach($fields as $field): 
            $field_name = "field_".$field['id'];
            $isRequired = $field['required'] ? "true" : "false";
            $hiddenClass = in_array($field['id'], $targets) ? "hidden" : "";
        ?>
        <div class="form-group <?php echo $hiddenClass; ?>" id="field_wrapper_<?php echo $field['id']; ?>" data-required="<?php echo $isRequired; ?>">
            <label><?php echo htmlspecialchars($field['label']); ?> <?php if($field['required']) echo "*"; ?></label>

            <?php if(in_array($field['type'], ['text','number','date'])): ?>
                <input type="<?php echo $field['type']; ?>" name="<?php echo $field_name; ?>" data-required="<?php echo $isRequired; ?>" placeholder="<?php echo htmlspecialchars($field['placeholder']); ?>">
            <?php elseif($field['type'] == 'textarea'): ?>
                <textarea name="<?php echo $field_name; ?>" data-required="<?php echo $isRequired; ?>" placeholder="<?php echo htmlspecialchars($field['placeholder']); ?>"></textarea>
            <?php elseif($field['type'] == 'dropdown'): ?>
                <select name="<?php echo $field_name; ?>" data-required="<?php echo $isRequired; ?>">
                    <option value="">Select</option>
                    <?php foreach($field['options'] as $opt): ?>
                        <option value="<?php echo htmlspecialchars($opt); ?>"><?php echo htmlspecialchars($opt); ?></option>
                    <?php endforeach; ?>
                </select>
            <?php elseif($field['type'] == 'radio'): ?>
                <?php foreach($field['options'] as $opt): ?>
                    <label><input type="radio" name="<?php echo $field_name; ?>" value="<?php echo htmlspecialchars($opt); ?>" data-required="<?php echo $isRequired; ?>"> <?php echo htmlspecialchars($opt); ?></label>
                <?php endforeach; ?>
            <?php elseif($field['type'] == 'checkbox'): ?>
                <?php foreach($field['options'] as $opt): ?>
                    <label><input type="checkbox" name="<?php echo $field_name; ?>[]" value="<?php echo htmlspecialchars($opt); ?>" data-required="<?php echo $isRequired; ?>"> <?php echo htmlspecialchars($opt); ?></label>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

        <button type="submit">Submit</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script>
const rules = <?php echo json_encode($rules); ?>;

function toggleRequired(inputs, makeRequired) {
    inputs.forEach(input => {
        if(input.getAttribute('data-required') === 'true'){
            if(makeRequired) input.setAttribute('required', 'required');
            else input.removeAttribute('required');
        }
    });
}

function evaluateRulesForField(fieldId){
    const fieldRules = rules.filter(r => r.target_field_id == fieldId);
    let show = false;

    fieldRules.forEach(rule => {
        const triggerName = 'field_' + rule.trigger_field_id;
        const triggers = $("[name='"+triggerName+"']");
        let triggerValue = '';

        if(triggers.length > 0){
            const type = triggers[0].type;

            if(type === 'radio'){
                triggers.each(function(){ if(this.checked) triggerValue = this.value; });
            } else if(type === 'checkbox'){
                let vals = [];
                triggers.each(function(){ if(this.checked) vals.push(this.value); });
                triggerValue = vals.join(',');
            } else {
                triggerValue = triggers.val().trim(); // Trim text/number inputs
            }
        }

        // Always parse numeric values for numeric comparisons
        const numTrigger = parseFloat(triggerValue);
        const numRule = parseFloat(rule.trigger_value);
        const isNumeric = !isNaN(numTrigger) && !isNaN(numRule);

        let match = false;
        switch(rule.operator){
            case '=': match = triggerValue == rule.trigger_value; break;
            case '!=': match = triggerValue != rule.trigger_value; break;
            case '>': match = isNumeric && (numTrigger > numRule); break;
            case '<': match = isNumeric && (numTrigger < numRule); break;
        }

        if(rule.action === 'show' && match) show = true;
        if(rule.action === 'hide' && match) show = false;
    });

    const wrapper = $("#field_wrapper_" + fieldId);
    const inputs = wrapper.find('input, select, textarea').toArray();
    wrapper.toggle(show);
    toggleRequired(inputs, show);
}

function applyAllRules(){
    const targetIds = [...new Set(rules.map(r => r.target_field_id))];
    targetIds.forEach(id => evaluateRulesForField(id));
}

$(document).ready(function(){
    applyAllRules();

    $(document).on('change input', "input, select, textarea", function(){
        applyAllRules();
    });
});

</script>

</body>
</html>
