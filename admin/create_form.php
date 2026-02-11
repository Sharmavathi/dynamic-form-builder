 <?php
include "../configure/database_connection.php";

if(isset($_POST['create_form'])) {
    $form_name = trim($_POST['form_name']);
    if(empty($form_name)) {
        echo "<p style='color:red;'>Form name is required.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO forms (form_name, created_at) VALUES (?, NOW())");
        $stmt->bind_param("s", $form_name);
        $stmt->execute();
        $form_id = $stmt->insert_id;

        header("Location: add_fields.php?form_id=".$form_id);
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Form</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">
    <h2>Create New Form</h2>
    <form method="POST">
        <label>Form Name:</label><br>
        <input type="text" name="form_name" placeholder="Enter Form Name" required><br><br>
        <button type="submit" name="create_form">Create Form</button>
    </form>
    <br>
     <a href="../index.php">Back to Dashboard</a>

</div>
</body>
</html>
