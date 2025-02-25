<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bill_id = $_POST["bill_id"];
    $conn->query("UPDATE billing SET status='Cleared' WHERE id='$bill_id'");
}

$bills = $conn->query("SELECT * FROM billing WHERE status='Not Cleared'");
?>

<h2>Pending Payments</h2>
<form method="post">
    <select name="bill_id">
        <?php while ($row = $bills->fetch_assoc()) { ?>
            <option value="<?= $row['id'] ?>">Table <?= $row['table_no'] ?> - ₹<?= $row['total'] ?></option>
        <?php } ?>
    </select>
    <button type="submit">Mark as Paid</button>
</form>
