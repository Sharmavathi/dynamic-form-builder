 <?php
include "../configure/database_connection.php";

/* -----------------------------
   Pagination Settings
------------------------------*/
$limit = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

/* -----------------------------
   Filters
------------------------------*/
$date_from = $_GET['date_from'] ?? '';
$date_to   = $_GET['date_to'] ?? '';
$search    = $_GET['search'] ?? '';

$where = " WHERE 1=1 ";
$params = [];
$types  = "";

/* Date Filter */
if (!empty($date_from) && !empty($date_to)) {
    $where .= " AND DATE(fr.submitted_at) BETWEEN ? AND ? ";
    $params[] = $date_from;
    $params[] = $date_to;
    $types .= "ss";
}

/* Search Filter (Form Name OR User ID) */
if (!empty($search)) {
    $where .= " AND (f.form_name LIKE ? OR fr.user_id LIKE ?) ";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= "ss";
}

/* -----------------------------
   Count Total Records
------------------------------*/
$count_sql = "
    SELECT COUNT(*) as total
    FROM form_responses fr
    JOIN forms f ON fr.form_id = f.id
    $where
";

$stmt = $conn->prepare($count_sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$total_records = $stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);

/* -----------------------------
   Fetch Data
------------------------------*/
$data_sql = "
    SELECT fr.id, fr.form_id, fr.user_id, fr.submitted_at, f.form_name
    FROM form_responses fr
    JOIN forms f ON fr.form_id = f.id
    $where
    ORDER BY fr.submitted_at DESC
    LIMIT ? OFFSET ?
";

$params_data = $params;
$types_data  = $types;

$params_data[] = $limit;
$params_data[] = $offset;
$types_data .= "ii";

$stmt = $conn->prepare($data_sql);
$stmt->bind_param($types_data, ...$params_data);
$stmt->execute();
$result = $stmt->get_result();
?>
<style>
/* ---------- General Page ---------- */
body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    margin: 20px;
    color: #333;
}

h2 {
    color: #2c3e50;
}

/* ---------- Filter Form ---------- */
form {
    background: #fff;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0px 2px 5px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

form input[type="date"],
form input[type="text"] {
    padding: 6px 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

form button {
    padding: 6px 15px;
    background-color: #3498db;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s;
}

form button:hover {
    background-color: #2980b9;
}

form a {
    text-decoration: none;
    padding: 6px 12px;
    background-color: #95a5a6;
    color: #fff;
    border-radius: 4px;
    transition: background 0.3s;
}

form a:hover {
    background-color: #7f8c8d;
}

/* ---------- Table ---------- */
table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    box-shadow: 0px 2px 5px rgba(0,0,0,0.1);
    border-radius: 8px;
    overflow: hidden;
}

table th, table td {
    padding: 12px 15px;
    text-align: left;
}

table th {
    background-color: #3498db;
    color: #fff;
}

table tr:nth-child(even) {
    background-color: #f2f2f2;
}

table tr:hover {
    background-color: #d6eaf8;
}

table td a {
    text-decoration: none;
    color: #2980b9;
    font-weight: bold;
}

table td a:hover {
    text-decoration: underline;
}

/* ---------- Pagination ---------- */
div.pagination {
    margin-top: 15px;
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}

div.pagination a {
    padding: 6px 12px;
    text-decoration: none;
    background-color: #3498db;
    color: #fff;
    border-radius: 4px;
    transition: background 0.3s;
}

div.pagination a:hover {
    background-color: #2980b9;
}

div.pagination a.active {
    background-color: #2c3e50;
}
</style>

<h2>All Form Submissions (Overview)</h2>

<!-- Filter Form -->
<form method="GET">
    From: <input type="date" name="date_from" value="<?= htmlspecialchars($date_from) ?>">
    To: <input type="date" name="date_to" value="<?= htmlspecialchars($date_to) ?>">
    Search: <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Form name or User ID">
    <button type="submit">Filter</button>
</form>

<br>

<!-- Data Table -->
<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Form Title</th>
        <th>User ID</th>
        <th>Submitted At</th>
        <th>Action</th>
    </tr>

<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['form_name']) ?></td>
            <td><?= $row['user_id'] ?></td>
            <td><?= date("Y-m-d H:i:s", strtotime($row['submitted_at'])) ?></td>
            <td>
                <a href="admin_submissions.php?form_id=<?= $row['form_id'] ?>">View Details</a>
            </td>
        </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr>
        <td colspan="5">No submissions found.</td>
    </tr>
<?php endif; ?>
</table>

<br>

<!-- Pagination -->

<div class="pagination">
<?php for ($i = 1; $i <= $total_pages; $i++): ?>
    <a class="<?= $i == $page ? 'active' : '' ?>" href="?page=<?= $i ?>&date_from=<?= $date_from ?>&date_to=<?= $date_to ?>&search=<?= htmlspecialchars($search) ?>">
        <?= $i ?>
    </a>
<?php endfor; ?>
</div>



 
